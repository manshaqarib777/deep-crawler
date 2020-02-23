<?php

	/**
	 * 
	 */
	class Detect
	{

        public $results = array();
		
		function __construct($url, $single_post){
			$post_html = $this->get_url($url);

            if($single_post == 'true'){
                $this->getSinglePostStructure($post_html);
            }else{
                $this->getSerialPostStructure($post_html);
            }
		}

        public function getSerialPostStructure($post_html){
            $doc = new DomDocument();
            @$doc->loadHTML($post_html);
            $xpath = new DOMXPath($doc);

            $item = $this->getLinks($xpath);

            if($item){
                $this->results[] = array(
                    'type'   => 'serial_link',
                    'sample' => @$item['sample'],
                    'link'   => @$item['link'],
                    'xpath'  => @$item['xpath']
                );

                $content_html = $this->get_url($item['link']);

                if($content_html){
                    $this->getSinglePostStructure($content_html);
                }
            }

            $item = $this->getNextPage($xpath);

            if($item){
                $this->results[] = array(
                    'type'   => 'next_page',
                    'sample' => @$item['sample'],
                    'xpath'  => @$item['xpath']
                );
            }
        }

        public function getSinglePostStructure($post_html){
            $doc = new DomDocument();
            @$doc->loadHTML($post_html);
            $xpath = new DOMXPath($doc);

            $item = $this->getTitle($xpath);

            if($item){
                $this->results[] = array(
                    'type'   => 'post_title',
                    'sample' => @$item['sample'],
                    'xpath'  => @$item['xpath']
                );
            }

            $item = $this->getContent($xpath);

            if($item){
                $this->results[] = array(
                    'type'   => 'post_content',
                    'sample' => @$item['sample'],
                    'xpath'  => @$item['xpath']
                );
            }

            $item = $this->getFeaturedImage($xpath);

            if($item){
                $this->results[] = array(
                    'type'   => 'featured_image',
                    'sample' => @$item['sample'],
                    'xpath'  => @$item['xpath']
                );
            }
        }

        public function getClass($element){
            if($element && $element->nodeType == XML_ELEMENT_NODE){
                $className   = @$element->getAttribute('class');
                $className   = explode(' ', $className);
                $className   = $className[0];

                return @$className;
            }else{
                return null;
            }
        }

        public function getId($element){
            if($element && $element->nodeType == XML_ELEMENT_NODE){
                $idName   = @$element->getAttribute('class');
                $idName   = explode(' ', $idName);
                $idName   = $idName[0];

                $idName   = explode('-', $idName);
                $idName   = $idName[0];

                return @$idName;
            }else{
                return null;
            }
        }

        public function getXpath($element){
            if($element && $element->nodeType == XML_ELEMENT_NODE){
                $className = $this->getClass($element);
                $attrName  = $element->tagName;

                if($className){
                    return $attrName.'[contains(@class, "'.$className.'")]';
                }else{
                    return $attrName;
                }
            }else{
                return null;
            }
        }

        public function recursiveGetXpath($element, $string = false){
            if($element->parentNode){
                $string = $this->getXpath($element);

                return $string;
            }else{
                return $this->getXpath($element) . '/' . $string;
            }
        }

        public function getLinks($xpath){
            $output = array();
            $title_elements = array('a');
            $title_classes  = array('title', 'heading', 'post', 'product', 'article', 'entry', 'item');

            foreach ($title_elements as $key => $attrName) {
                $elements = $xpath->query('//' . $attrName . '');

                foreach ($elements as $index => $element) {
                    $textContent = $element->textContent;
                    $className   = $this->getClass($element);

                    $parentElement0 = @$element->parentNode->parentNode;
                    $parentXpath0   = $this->getXpath($parentElement0);

                    $parentElement = @$element->parentNode;
                    $parentXpath   = $this->getXpath($parentElement);

                    if($parentXpath0){
                        $element_xpath = '//'.$parentXpath0.'/'.$parentXpath.'/'.$this->getXpath($element);
                    }else if($parentXpath){
                        $element_xpath = '//'.$parentXpath.'/'.$this->getXpath($element);
                    }else{
                        $element_xpath = '//'.$this->getXpath($element);
                    }

                    $score = 0;
                    $score-=array_search($attrName, $title_elements);
                    $score+=(strlen($textContent) > 30 && strlen($textContent) < 120) ? 1 : 0;
                    $score-=array_search($className, $title_classes);
                    $score-=$this->strposa($className, $title_classes);

                    if(
                        $parentElement->tagName == 'h1' ||
                        $parentElement->tagName == 'h2' ||
                        $parentElement->tagName == 'h3' ||
                        $parentElement->tagName == 'h4' ||
                        $parentElement->tagName == 'h5' ||
                        $parentElement->tagName == 'h6' ||
                        $parentElement->tagName == 'b' ||
                        $parentElement->tagName == 'strong' ||
                        $parentElement->tagName == 'header'
                    ){
                        $score+=1;
                    }

                    //$score+=count($xpath->query($element_xpath));

                    $output[] = array(
                        'score' => $score,
                        'xpath' => $element_xpath
                    );
                }
            }

            usort($output, function($a, $b){
                return $a['score'] < $b['score'];
            });

            if(count($output) > 0){
                $sample = $xpath->query(@$output[0]['xpath']);

                if(count($sample) > 0){
                    return array(
                        'xpath'  => @$output[0]['xpath'], 
                        'sample' => $sample[0]->textContent,
                        'link'   => $sample[0]->getAttribute('href')
                    );
                }
            }else{
                return null;
            }
        }

        public function getNextPage($xpath){
            $output = array();
            $title_elements = array('a');
            $title_classes  = array('next', 'pagination', 'page', 'navigation');

            foreach ($title_elements as $key => $attrName) {
                $elements = $xpath->query('//' . $attrName . '');

                foreach ($elements as $index => $element) {
                    $textContent = $element->textContent;
                    $className   = $this->getClass($element);
                    $elementHref = $element->getAttribute('href');

                    $parentElement = @$element->parentNode;
                    $parentXpath   = $this->getXpath($parentElement);

                    if($parentXpath){
                        $element_xpath = '//'.$parentXpath.'/'.$this->getXpath($element);
                    }else{
                        $element_xpath = '//'.$this->getXpath($element);
                    }

                    $score = 0;
                    $score-=array_search($attrName, $title_elements);
                    $score+=(strlen($textContent) > 0 && strlen($textContent) < 10) ? 1 : 0;
                    $score-=array_search($className, $title_classes);
                    $score-=$this->strposa($className, $title_classes);
                    $score+=$key * 5;

                    if($this->strposa($elementHref, $title_classes)){
                        $score+=10;
                    }

                    $nextTextContent = $textContent;
                    $nextTextContent = explode(' ', $nextTextContent);
                    $nextTextContent = trim($nextTextContent[0]);

                    if(strlen($nextTextContent) < 10){
                        $element_xpath = $element_xpath . '[contains(text(), "'.$nextTextContent.'")]';
                    }

                    $output[] = array(
                        'score' => $score,
                        'xpath' => $element_xpath
                    );
                }
            }

            usort($output, function($a, $b){
                return $a['score'] < $b['score'];
            });

            if(count($output) > 0){
                $sample = $xpath->query(@$output[0]['xpath']);

                if(count($sample) > 0){
                    return array(
                        'xpath'  => @$output[0]['xpath'], 
                        'sample' => $sample[0]->textContent,
                        'link'   => $sample[0]->getAttribute('href')
                    );
                }
            }else{
                return null;
            }
        }

        public function getTitle($xpath){
            $output = array();
            $title_elements = array('h1', 'h2', 'h3', 'h4', 'h5', 'div');
            $title_classes  = array('title', 'heading');

            //Page Title
            $title = $xpath->query('//title');
            $page_title = @$title[0]->textContent;

            foreach ($title_elements as $key => $attrName) {
                $elements = $xpath->query('//' . $attrName . '');

                foreach ($elements as $index => $element) {
                    $textContent = $element->textContent;
                    $className   = $this->getClass($element);

                    $parentElement = @$element->parentNode;
                    $parentXpath   = $this->getXpath($parentElement);

                    if($parentXpath){
                        $element_xpath = '//'.$parentXpath.'/'.$this->getXpath($element);
                    }else{
                        $element_xpath = '//'.$this->getXpath($element);
                    }

                    $score = 0;
                    $score-=array_search($attrName, $title_elements);
                    $score+=(strlen($textContent) > 5 && strlen($textContent) < 60) ? 1 : 0;
                    $score-=array_search($className, $title_classes);
                    $score+=similar_text($page_title, $textContent) * 0.1;

                    $output[] = array(
                        'score' => $score,
                        'xpath' => $element_xpath
                    );
                }
            }

            usort($output, function($a, $b){
                return $a['score'] < $b['score'];
            });

            if(count($output) > 0){
                $sample = $xpath->query(@$output[0]['xpath']);

                if(count($sample) > 0){
                    return array('xpath' => @$output[0]['xpath'], 'sample' => $sample[0]->textContent);
                }
            }else{
                return null;
            }
        }

        public function strposa($haystack, $needles=array(), $offset=0) {
            $chr = array();

            foreach($needles as $needle) {
                $res = strpos($haystack, $needle, $offset);

                if ($res !== false)
                    $chr[$needle] = $res;
            }

            if (empty($chr))
                return false;

            return min($chr);
        }

        public function getContent($xpath){
            $output = array();
            $content_elements = array('p', 'content', 'article', 'div');
            $content_classes  = array('article', 'post', 'description', 'content', 'summary_text', 'summary');

            //Page Title
            $content = $xpath->query('//meta[@name="description"]');

            if(@$content[0]){
                $page_content = @$content[0]->textContent;
            }else{
                $page_content = '';
            }

            foreach ($content_elements as $key => $attrName) {
                $elements = $xpath->query('//' . $attrName . '');

                foreach ($elements as $index => $element) {
                    $textContent = $element->textContent;
                    $className   = $this->getClass($element);
                    $idName      = $this->getId($element);

                    $parentElement = @$element->parentNode;
                    $parentXpath   = $this->getXpath($parentElement);

                    if($parentXpath){
                        $element_xpath = '//'.$parentXpath.'/'.$this->getXpath($element);
                    }else{
                        $element_xpath = '//'.$this->getXpath($element);
                    }

                    $score = 0;
                    $score-=array_search($attrName, $content_elements);

                    if($attrName == 'p'){
                        $score+=strlen($textContent) * 0.1;
                        $score+=strlen($parentElement->textContent) * 0.01;
                    }else{
                        $score+=strlen($textContent) * 0.1;
                    }
                    
                    $score-=array_search($className, $content_classes);
                    $score-=array_search($idName, $content_classes);
                    $score+=similar_text($page_content, $textContent) * 0.1;

                    $output[] = array(
                        'score' => $score,
                        'xpath' => $element_xpath
                    );
                }
            }

            usort($output, function($a, $b){
                return $a['score'] < $b['score'];
            });

            if(count($output) > 0){
                $sample = $xpath->query(@$output[0]['xpath']);

                if(count($sample) > 0){
                    return array('xpath' => @$output[0]['xpath'], 'sample' => $sample[0]->textContent);
                }
            }else{
                return null;
            }
        }

        public function getFeaturedImage($xpath){
            $output = array();
            $image_elements = array('img');
            $image_classes  = array('attachment', 'featured', 'product', 'image', 'thumbnail');
            $image_disallow = array('logo');

            //Page Title
            $content = $xpath->query('//meta[@property="og:image"]');

            if(@$content[0]){
                $image_content = @$content[0]->getAttribute('content');
            }else{
                $image_content = '';
            }

            foreach ($image_elements as $key => $attrName) {
                $elements = $xpath->query('//' . $attrName . '');

                foreach ($elements as $index => $element) {
                    $textContent = $element->textContent;
                    $className   = $this->getClass($element);
                    $idName      = $this->getId($element);

                    $parentElement = @$element->parentNode;
                    $parentXpath   = $this->getXpath($parentElement);

                    if($parentXpath){
                        $element_xpath = '//'.$parentXpath.'/'.$this->getXpath($element);
                    }else{
                        $element_xpath = '//'.$this->getXpath($element);
                    }

                    $score = 0;
                    $score-=array_search($attrName, $image_elements);
                    $score+=strlen($textContent) * 0.1;
                    $score-=$index*10;
                    $score-=array_search($className, $image_classes);
                    $score-=array_search($idName, $image_classes);
                    $score+=similar_text($image_content, $textContent) * 0.1;

                    if(
                        strpos($className, 'logo') > -1 ||
                        strpos($idName, 'logo') > -1
                    ){
                        $score = -500;
                    }

                    $output[] = array(
                        'score' => $score,
                        'xpath' => $element_xpath
                    );
                }
            }

            usort($output, function($a, $b){
                return $a['score'] < $b['score'];
            });

            if(count($output) > 0){
                $sample = $xpath->query(@$output[0]['xpath']);

                if(count($sample) > 0){
                    return array('xpath' => @$output[0]['xpath'], 'sample' => $sample[0]->getAttribute('src'));
                }
            }else if($content){
                return '//meta[@property="og:image"]';
            }else{
                return null;
            }
        }

        public function getResults(){
            return $this->results;
        }

		public function clean_url($url, $base_url = ''){
            if(substr($url, 0, 2) == '//'){
                $domain_parse = parse_url($base_url);

                $url = $domain_parse['scheme']. ':' .$url;
            }else if($base_url && strpos($url, 'http') === false){
                $domain_parse = parse_url($base_url);

                if(substr($url, 0, 1) == '/'){
                    $url = $domain_parse['scheme']. '://' . $domain_parse['host'] . $url;
                }else{
                    $url = $domain_parse['scheme']. '://' . $domain_parse['host'] . '/' . $url;
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

        public function get_url($url){
            $url = str_replace("\n", "", $url);
            $result = $this->get($url);

            $doc = new DomDocument();
            @$doc->loadHTML(mb_convert_encoding($result, "HTML-ENTITIES", mb_detect_encoding($result)));
            $xpath = new DOMXPath($doc);

            preg_match_all('/<base href="(.*?)"(.*?)\/>/', $result, $matches);

            if(@$matches[1][0]){
                $relative_url = $matches[1][0];
            }else{
                $relative_url = $url;
            }

            $proxifyAttributes = array("href", "src");
            foreach($proxifyAttributes as $attrName) {
                foreach($xpath->query('//*[@' . $attrName . ']') as $element) {
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

        function get($url){
			$handle = curl_init($url);

		    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);

		    curl_setopt($handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36');

		    $response = curl_exec($handle);

		    curl_close($handle);

		    return $response;
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
	}