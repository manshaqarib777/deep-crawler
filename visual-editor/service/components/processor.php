<?php

	/**
	 * 
	 */
	class Processor
	{

        private $next_page = '';
		
		function __construct()
		{
			
		}

        public function rel2abs($rel, $base) {
            if (empty($rel)) $rel = ".";
            if (parse_url($rel, PHP_URL_SCHEME) != "" || strpos($rel, "//") === 0) return $rel; //Return if already an absolute URL
            if ($rel[0] == "#" || $rel[0] == "?") return $base.$rel; //Queries and anchors
            extract(parse_url($base)); //Parse base URL and convert to local variables: $scheme, $host, $path
            $path = isset($path) ? preg_replace('#/[^/]*$#', "", $path) : "/"; //Remove non-directory element from path
            if ($rel[0] == '/') $path = ""; //Destroy path if relative url points to root
            $port = isset($port) && $port != 80 ? ":" . $port : "";
            $auth = "";
            if (isset($user)) {
            $auth = $user;
            if (isset($pass)) {
              $auth .= ":" . $pass;
            }
            $auth .= "@";
            }
            $abs = "$auth$host$path$port/$rel"; //Dirty absolute URL
            for ($n = 1; $n > 0; $abs = preg_replace(array("#(/\.?/)#", "#/(?!\.\.)[^/]+/\.\./#"), "/", $abs, -1, $n)) {} //Replace '//' or '/./' or '/foo/../' with '/'
            return $scheme . "://" . $abs; //Absolute URL is ready.
        }

		public function replace_unescape($string){
            return preg_replace_callback('/\\\\u(\w{4})/', function ($matches) {
                return html_entity_decode('&#x' . $matches[1] . ';', ENT_COMPAT, 'UTF-8');
            }, $string);
        }

		public function render_content($data, $content, $fields, $variables, $images, $gallery, $task_data){
            $output = $content;

            //static variable
            $output = str_replace('{{content}}', $data, $output);
            $output = str_replace('{{source_url}}', @$task_data['contentURL'], $output);

            if(isset($task_data['contentURL'])){
	            $source_domain = parse_url($task_data['contentURL']);
	            $output = str_replace('{{source_domain}}',  @$source_domain['host'], $output);
	        }

            if($variables){
                foreach ($variables as $key => $value) {
                    $output = str_replace('{{'.$key.'}}', $value, $output);
                }
            }

            if($images){
                foreach ($images as $key => $value) {
                    $output = str_replace('{{'.$key.'}}', $value, $output);
                }
            }

            if($gallery){
                $output = str_replace('{{gallery}}', '[gallery ids="' . implode(',', $gallery) . '"]', $output);
            }

            $output = preg_replace('/\{\{(.*?)\}\}/', '', $output);

            return $output;
        }

        public function transform($content, $parameters, $fields, $variables = array(), $images = array(), $gallery = array(), $task_data = array()){
            $escape_characters = true;

            $output = $content;
            $output = $this->render_content($output, $parameters['content'], $fields, $variables, $images, $gallery, $task_data);

            //apply find & replace
            if(isset($parameters['replaces'])){
                foreach ($parameters['replaces'] as $key => $find_replace) {
                    //$output = str_replace($find_replace['find'], $find_replace['replace'], $output);
                    $output = preg_replace('/'.$find_replace['find'].'/', $find_replace['replace'], $output);
                }
            }

            if(@$parameters['translate'] != ''){
                //$output = $this->translate($output, $parameters['translate']);
            }

            if(@$parameters['spinner']){
                //$output = $this->spin_content($output);
            }

            if(isset($parameters['isNumber']) && $parameters['isNumber'] == 'true'){
                if(isset($parameters['cleanNonNumerical']) &&  @$parameters['cleanNonNumerical'] == 'true'){
                    $output = preg_replace('/[^\d\.]/', '', $output);
                }

                $value = floatval($output);

                if(isset($parameters['math'])){
                    $parameters['math'] = str_replace('value', '$value', $parameters['math']);
                    $output = eval('return '.$parameters['math'] . ';');
                }
            }

            if(@$parameters['stripLinks'] == 'true'){
                $output = preg_replace('#<a.*?>(.*?)</a>#i', '\1', $output);
            }

            if(@$parameters['stripAds'] == 'true'){
                $output = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $output);
            }

            if(@$parameters['stripTags'] == 'true'){
                $output = strip_tags($output);
            }

            if(@$parameters['attributeParse'] == 'background-image'){
                preg_match('/url\((\'|\"|)(.*?)(\'|\"|)\)/', $output, $matches);

                if(isset($matches[2])){
                    $output = @$matches[2];
                    $output = preg_replace('/\\\3a/i', ':', $output);
                    $output = preg_replace('/\\\20/i', '', $output);
                    $output = preg_replace('/\\\3d/i', '=', $output);
                    $output = preg_replace('/\\\26/i', '&', $output);

                    $output = str_replace(' ', '', $output);
                }

                $escape_characters = false;
            }

            if(@$parameters['splitContent'] == 'true' && $parameters['splitDelimiter']){
                $output = explode($parameters['splitDelimiter'], $output);
            }

            //unescape characters
            if($escape_characters){
                $output = $this->replace_unescape($output);
            }

            return $output;
        }

        public function process_content($post_url, $data, $task){
            $post_html = $this->get_url($post_url, $data['connection']['user_agent'], $data['connection']['cookie'], $data['feedURL']);

            $featured_image = -1;
            $images    = array();
            $gallery   = array();
            $variables = array();
            $custom_fields = array();
            $success   = false;
            $query_success = 0;

            //query variables
            foreach ($data['fields'] as $key => $field) {
                if(
                    $field['type'] == 'variable' || 
                    $field['type'] == 'image' ||
                    $field['type'] == 'gallery'
                ){
                    if($task['parse_method'] == 'xpath'){
                        $field_content = $this->parse_xpath($post_html, $field['path'], $field['prop']);
                    }else{
                        $field_content = $this->parse_regex($post_html, $field['path'], $field);
                    }

                    if($field['type'] == 'gallery'){
                        if($field_content){
                            foreach ($field_content as $key => $field_url) {
                                //$gallery[] = $this->upload_image(@$field_url, -1, $field, $data['feedURL']);

                                $content_value = $this->transform(@$field_url, $field, $data['fields'], $variables, $images, $gallery, $data);
                                
                                if(is_array($content_value)){
                                    foreach ($content_value as $key => $value) {
                                        if($value){
                                            $post[$field['type']][] = $this->clean_url(@$value, $data['feedURL']);
                                        }
                                    }
                                }else{
                                    $post[$field['type']][] = $this->clean_url(@$content_value, $data['feedURL']);
                                }
                            }
                        }
                    }else if($field['type'] == 'image'){
                        //$images[$field['name']] = $this->upload_image(@$field_content[0], -1, $field, $data['feedURL'], true);

                        $content_value = $this->transform(@$field_content[0], $field, $data['fields']);
                        
                        $post[$field['type']][] = $this->clean_url($content_value, $data['feedURL']);
                    }else if($field['type'] == 'variable'){
                        $content_value = $this->transform(@$field_content[0], $field, $data['fields']);
                        $post[$field['name']][] = $content_value;

                        $variables[$field['name']] = $content_value;
                    }
                }
            }

            //query fields
            foreach ($data['fields'] as $key => $field) {
                if($task['parse_method'] == 'xpath'){
                    $field_content = $this->parse_xpath($post_html, $field['path'], $field['prop']);

                    if($field_content){
                        $query_success++;
                    }
                }else{
                    $field_content = $this->parse_regex($post_html, $field['path'], $field);

                    if($field_content){
                        $query_success++;
                    }
                }

                if($field['type'] == 'featured_image'){
                    $image_url = $this->transform(@$field_content[0], $field, $data['fields'], $variables, $images, $gallery, $data);
                    //$featured_image = $this->upload_image($image_url, -1, $field, $data['feedURL'] ? $data['feedURL'] : $data['contentURL']);

                    $post[$field['type']] = $this->clean_url(@$image_url, ($data['feedURL'] ? $data['feedURL'] : $data['contentURL']));
                }else if($field['type'] == 'post_title'){
                    $post[$field['type']] = $this->transform(@$field_content[0], $field, $data['fields'], $variables, $images, $gallery, $data);
                }else if($field['type'] == 'post_content'){
                    $multiple_content_element = '';

                    foreach (@$field_content as $key => $value) {
                        $multiple_content_element.=$this->transform(@$value, $field, $data['fields'], $variables, $images, $gallery, $data);
                    }

                    $post[$field['type']] = $multiple_content_element;
                }else if($field['type'] == 'gallery'){
                    //Pass
                }else if($field['type'] == 'image'){
                    //Pass
                }else if($field['type'] == 'variable'){
                    //Pass
                }else if($field['type'] == 'tags_input'){
                    $tags_input = array();

                    foreach ($field_content as $field_key => $field_value) {
                        $tags_input[] = $this->transform(@$field_value, $field, $data['fields'], $variables, $images, $gallery, $data);
                    }

                    if($field['splitContent'] == 'true'){
                        $post[$field['name']] = @$tags_input[0];
                    }else{
                        $post[$field['name']] = @$tags_input;
                    }
                }else if($field['type'] == '_product_attributes'){
                    $multiple_content_element = array();

                    foreach (@$field_content as $key => $value) {
                        $multiple_content_element[] =$this->transform(@$value, $field, $data['fields'], $variables, $images, $gallery, $data);
                    }

                    $post[$field['type']] = $multiple_content_element;
                }else if($field['type'] == 'post_category'){
                    $categories = array();

                    foreach ($field_content as $field_key => $field_value) {
                        $categories[] = $this->transform(@$field_value, $field, $data['fields'], $variables, $images, $gallery, $data);
                    }

                    if($field['splitContent'] == 'true'){
                        $post[$field['type']] = @$categories[0];
                    }else{
                        $post[$field['type']] = @$categories;
                    }
                }else{
                    //Custom field
                    $post[$field['type']] = $this->transform(@$field_content[0], $field, $data['fields'], $variables, $images, $gallery, $data);
                }
            }

           	//2 iteration can be reasonable
            if($query_success > 0){
                return array(
                	'URL'            => $post_url,
                    'post'           => $post,
                    'custom_fields'  => $custom_fields,
                    'images'         => $images,
                    'gallery'        => $gallery,
                    'timestamp'      => time(),
                    'date'           => date('Y-m-d H:i:s')
                );
            }else{
                return false;
            }
        }

        public function process_task($task){
            //Set timelimit for delays for 5 minutes, user can change this value.
            //All sub functions has timeout, so it won't reach the time limit.
            set_time_limit(1500);
            
            $output = array();
            $data   = json_decode($task['data'], true);

            $count  = 0;

            //is single post?
            if($data['singlePost'] == 'true'){
                //process differently

                if(isset($data['other']) && @$data['other'] && @$data['other']['bulkURL'] && strlen($data['other']['bulkURL']) > 5){
                    $url_list  = array(@$data['contentURL']);
                    $bulk_urls = explode("\n", $data['other']['bulkURL']);

                    foreach ($bulk_urls as $key => $bulk_url) {
                        $url_list[] = $bulk_url;
                    }

                    //Items will be processed
                    foreach ($url_list as $key => $post_url) {
                        if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'scraper.site'){
                            if($count < 8){
                                $output[] = $this->process_content($post_url, $data, $task);
                            }                            
                        }else{
                            $output[] = $this->process_content($post_url, $data, $task);
                        }

                        $count++;
                    }
                }else{
                    $output[] = $this->process_content($data['contentURL'], $data, $task);
                }

                //$output[] = $this->process_content($data['contentURL'], $data, $task);
            }else{
                //feed URL can be change after pagination process
                if(@$data['nextPage']['path']){
                    if($task['current_page'] == 0){
                        $feedURL = $data['feedURL'];
                        $baseURL = false;
                    }else{
                        $baseURL = $data['feedURL'];

                        //get next URL
                        if($task['nextPage']){
                            $feedURL    = $task['nextPage'];
                        }else{
                            $feedURL    = $data['feedURL'];
                        }

                        $feed_html  = $this->get_url($feedURL, $data['connection']['user_agent'], $data['connection']['cookie']);

                        //find next link
                        $field_content = $this->parse_xpath($feed_html, $data['nextPage']['path'], 'deep_link');
                        $feedURL = @$field_content[0];
                    }

                    //clean URL
                    $feedURL = $this->clean_url($feedURL, $baseURL);

                    $this->next_page = $feedURL;
                    
                    //increase current page
                    //$this->increase_page($task, $feedURL);
                }else{
                    $feedURL = $data['feedURL'];
                    $baseURL = false;
                }

                if($feedURL){
                    //feed method
                    $feed_html  = $this->get_url($feedURL, $data['connection']['user_agent'], $data['connection']['cookie'], $baseURL);
                    $feed_items = $this->parse_xpath($feed_html, $data['feed']['path'], 'deep_link');

                    //Items will be processed
                    foreach ($feed_items as $key => $post_url) {
                    	if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'scraper.site'){
                            if($count < 8){
                                $post_url = $this->clean_url($post_url, $baseURL);
                    		$process_result = $this->process_content($post_url, $data, $task);

                    		if($process_result){
                    			$output[] = $process_result;
                    		}
                            }                                                    	
                    	}else{
                            $post_url = $this->clean_url($post_url, $baseURL);
                            $process_result = $this->process_content($post_url, $data, $task);

                            if($process_result){
                                    $output[] = $process_result;
                            }
                        }
                        $count++;
                    }
                }
            }

            return array('result' => $output, 'next_page' => $this->next_page);
        }

        public function parse_regex($html, $path, $field){
            $output = array();

            preg_match_all('/'.$path.'/m', $html, $matches);

            if($field['regexIndex'] == -1){
                foreach ($matches[1] as $key => $value) {
                    $output[] = $value;
                }
            }else{
                $output[] = $matches[1][$field['regexIndex']];
            }

            return $output;
        }

        public function parse_xpath($html, $path, $prop){
            if($path == '-'){
                return array();
            }else if($html){
                $output = array();

                libxml_use_internal_errors(true);
                $dom   = new DomDocument;
                $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
                $xpath = new DomXPath($dom);
                $nodes = $xpath->query($path);

                $props = explode(':', $prop);

                if(count($props) > 1){
                    $propType  = $props[0];
                    $propValue = $props[1];

                    if($propValue == 'original-href'){
                        $propValue = 'href';
                    }
                }else{
                    $propType  = false;
                }

                foreach ($nodes as $i => $node) {
                    if($prop == 'innerHTML'){
                        $output[] = $node->C14N();
                    }else if($prop == 'deep_link'){
                        $output[] = $node->getAttribute('href');
                    }else if($propType == 'attr'){
                        $output[] = $node->getAttribute($propValue);
                    }else if($prop == 'href'){
                        $output[] = $node->getAttribute('href');
                    }else{
                        $output[] = $node->nodeValue;
                    }
                }

                return $output;
            }else{
                return array();
            }
        }

        public function clean_url($url, $base_url = ''){
            if(substr($url, 0, 2) == '//'){
                $domain_parse = parse_url($base_url);

                $url = @$domain_parse['scheme']. ':' .$url;
            }else if($base_url && strpos($url, 'http') === false){
                $domain_parse = parse_url($base_url);

                if(substr($url, 0, 1) == '/'){
                    $url = $domain_parse['scheme']. '://' . $domain_parse['host'] . $url;
                }else if(count(explode('/', $base_url)) > 4){
                    $query_params = explode('?', $url);
                    $query_params = $query_params[0];

                    if(count(explode('/', $query_params)) == 1){
                        //clean last part from URL
                        $split_base_url = explode('?', $base_url);
                        $split_base_url = explode('/', $split_base_url[0]);
                        $split_base_url = array_slice($split_base_url, 0, -1);
                        $url = implode('/', $split_base_url) . '/' . $url;
                    }else{
                        $url = $base_url . '/' . $url;
                    }
                }else{
                    $url = $domain_parse['scheme']. '://' . $domain_parse['host'] . '/' . $url;
                }
            }else{
                $query_params_0 = explode('?', $url);
                $query_params_1 = explode('?', $base_url);

                $last_part_0 = end($query_params_0);
                $last_part_1 = end($query_params_1);

                if(count($query_params_0) > 1 && count($query_params_1) > 1){
                    $url = $query_params_0[0] . '?' . $last_part_0;
                }
            }

            if(substr($url, 0, 3) == '://'){
                $url = 'http'.$url;
            }

            if(strpos($url, 'http') === false){
                $url = 'http://'.$url;
            }

            return $url;
        }

        function get($url, $data){
			$handle = curl_init($url);

		    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);

		    if($data['user-agent']){
		    	curl_setopt($handle, CURLOPT_USERAGENT, $data['user-agent']);
		    }else{
		    	curl_setopt($handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36');
		    }

		    if($data['cookies']){
		    	//curl_setopt($handle, CURLOPT_COOKIESESSION, true);
		    	curl_setopt($handle, CURLOPT_HTTPHEADER, array("Cookie: ".$data['cookies']));
		    }

		    if(isset($data['proxy'])){
		    	curl_setopt($handle, CURLOPT_PROXY, @$data['proxy']);
		    }

            curl_setopt($handle,CURLOPT_ENCODING , "gzip");

		    $response = curl_exec($handle);
		    $info     = curl_getinfo($handle);

		    $this->status    = $info['http_code'];
		    $this->load_time = $info['total_time'];

		    curl_close($handle);

		    return $response;
		}

        public function get_url($url, $user_agent, $cookie, $base_url = ''){
            $url = str_replace("\n", "", $url);
            $url = $this->rel2abs($url, $base_url);

            $data = array(
                'user-agent'  => $user_agent,
                'redirection' => 5,
                'sslverify'   => false,
                'timeout'     => 5,
                'cookies'     => $cookie
            );

            $result = $this->get($url, $data);

            $doc = new DomDocument();
            $doc->encoding = 'utf-8';
            
            @$doc->loadHTML(mb_convert_encoding($result, "HTML-ENTITIES", mb_detect_encoding($result)));
            $xpath = new DOMXPath($doc);

            preg_match_all('/<base href="(.*?)"(.*?)\/>/', $result, $matches);

            if(@$matches[1][0]){
                $relative_url = $matches[1][0];
            }else{
                $relative_url = $url;
            }

            //Proxify any of these attributes appearing in any tag.
            $proxifyAttributes = array("href", "src");
            foreach($proxifyAttributes as $attrName) {
                foreach($xpath->query('//*[@' . $attrName . ']') as $element) { //For every element with the given attribute...
                $attrContent = $element->getAttribute($attrName);
                if ($attrName == "href" && (stripos($attrContent, "javascript:") === 0 || stripos($attrContent, "mailto:") === 0)) continue;
                    $attrContent = $this->rel2abs($attrContent, $relative_url);
                    $attrContent = $attrContent;
                    $element->setAttribute($attrName, $attrContent);
                }
            }

            $result = $doc->saveHTML();

            return $result;
        }
	}