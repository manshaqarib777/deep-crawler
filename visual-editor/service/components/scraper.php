<?php

	/**
	 * Scraper Core
	 */
	class Scraper
	{

		private $url    = '';
		private $base_url = '';
		private $html   = '';
		private $domain = '';
		private $protocol = 'https';
		private $status   = 404;
		private $load_time = 0;
		private $view_method = 'HTML';
		private $proxy_prefix = 'https://scraper.site/visual-editor/service/components/proxy.php/';
		
		private $site = 'https://scraper.site/visual-editor/';

		function __construct($url, $site, $base_url, $view_method, $user_agent = '', $cookie = '', $proxy = ''){
			$url = $this->clean_url($url, $base_url);
			$url = $this->rel2abs($url, $base_url);

			$this->site = $site;
			$this->url  = $url;
			$this->html = $this->get($this->url, $user_agent, $cookie, $proxy);

			$doc = new DomDocument();
			libxml_use_internal_errors(true);
                        $body = mb_convert_encoding($this->html, 'HTML-ENTITIES', 'UTF-8');
			
                        @$doc->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $body);                        
			$xpath = new DOMXPath($doc);

			preg_match_all('/<base href="(.*?)"(.*?)\/>/', $this->html, $matches);

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

					if(strpos($attrContent, 'scraper.site') > -1){

					}else if(strpos($attrContent, 'https') > -1){

					}else if(strpos($attrContent, 'data:') > -1){

					}else{
						$attrContent = $this->proxy_prefix . $attrContent.'?cache=' . rand(0,1000);
					}
					
					if($element->tagName != 'a'){
						$element->setAttribute($attrName, $attrContent);
					}
				}
			}

			//clear links
			foreach($xpath->query('//a') as $element) {
				$element->setAttribute('original-href', $element->getAttribute('href'));
				$element->setAttribute('href', 'javascript:;');
			}

			//clear preload or loaders
			foreach($xpath->query('//*') as $element) {
				$className = @$element->getAttribute('class') . ' - ' . @$element->getAttribute('id');

				if(preg_match('/(loader|loading|preload)/', $className)){
					$element->parentNode->removeChild($element);
				}
			}

			$this->html = $doc->saveHTML();

			$this->view_method = $view_method;

			//Domain settings
			$domain_parse = parse_url($this->url);

			if(@$domain_parse['host']){
				$this->domain   = $domain_parse['host'];
				$this->protocol = $domain_parse['scheme'];
			}

			$this->base_url = $base_url;
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
                $query_params_0 = explode('?', $url, 2);
                $query_params_1 = explode('?', $base_url, 2);

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

		function rel2abs($rel, $base) {
			if (empty($rel)) $rel = ".";
			if (parse_url($rel, PHP_URL_SCHEME) != "" || strpos($rel, "//") === 0) return $rel; //Return if already an absolute URL
			if ($rel[0] == "#" || $rel[0] == "?") return $base.$rel; //Queries and anchors
                        $host = $scheme = '';
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

		function get($url, $user_agent, $cookie, $proxy){
			$handle = curl_init($url);

		    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);

		    if($user_agent){
		    	curl_setopt($handle, CURLOPT_USERAGENT, $user_agent);
		    }else{
		    	curl_setopt($handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36');
		    }

		    if($cookie){
		    	//curl_setopt($handle, CURLOPT_COOKIESESSION, true);
		    	curl_setopt($handle, CURLOPT_HTTPHEADER, array("Cookie: ".$cookie));
		    }

		    if($proxy){
		    	curl_setopt($handle, CURLOPT_PROXY, $proxy);
		    }

		    curl_setopt($handle,CURLOPT_ENCODING , "gzip");

		    $response = curl_exec($handle);
		    $info     = curl_getinfo($handle);

		    $this->status    = $info['http_code'];
		    $this->load_time = $info['total_time'];

		    curl_close($handle);

		    return $response;
		}

		function clearScripts($data){
			$output = str_replace('<script', '<!--<script', $data);
			$output = str_replace('</script>', '</script>-->', $output);

			$data = str_replace('<script', '<!--<script', $data);
			$data = str_replace('</script>', '</script>-->', $data);

			$output = preg_replace('/onload=("|\'|"\n|\'\n|$)(.*?)("|\'|"\n|\'\n|$)/mus', '$1original-onload="$2"', $output);

			//$output = preg_replace('/display:(none| none |none | none)/mus', 'display:block', $output);
			//$output = preg_replace('/visibility:(hidden| hidden |hidden | hidden)/mus', 'visibility:visible', $output);

			return $output ? preg_replace('#<script(.*?)>(.*?)</script>#is', '', $output) : $data;
		}

		function clearLinks($output){
			$output = str_replace("\"\n", '"', $output);
			$output = str_replace("\'\n", '\'', $output);
			$output = preg_replace('/<a(.*?)href=("|\'|"\n|\'\n|$)(.*?)("|\'|"\n|\'\n|$)(.*?)>/mus', '<a$1original-href="$3" href="javascript:;"$5>', $output);			

			return $output;
		}

		function addScript($data){
			
			$output = $data;

			$output='<link rel="stylesheet" href="'.$this->site.'css/visual-editor.css?cache='.time().'"><script src="'.$this->site.'js/visual-editor.js?cache='.time().'"></script><base href="'.$this->protocol . '://' . $this->domain.'"><script src="'.$this->site.'js/jquery.min.js?cache='.time().'"></script><base href="'.$this->protocol . '://' . $this->domain.'">'.$output;
			return $output;
		}

		function parseMetaTags($html){
			$output = $html.'<div id="editor_interface">';

			preg_match_all('/<meta(.*?)>/', $html, $tags);

			foreach ($tags[1] as $key => $tag) {
				//get meta by property or name
				$meta_path = 'meta['.($key+1).']';

				preg_match('/(property|name)=(\"|\')(.*?)(\"|\')/', $tag, $matches);

				if(@$matches[1]){
					$meta_path = 'meta[contains(@'.$matches[1].', "'.$matches[3].'")]';
				}

				$output.='<div path=\'//'.$meta_path.'\' class="meta_tag"><b>Meta Tag</b> : '.$tag.'</div>';
			}

			$output.='</div>';

			return $output;
		}

		function parseJSON($html){
			$output = $html.'<div id="editor_interface">';

			preg_match_all('/<script(.*?)>(.*?)<\/script>/', $html, $tags);

			foreach ($tags[2] as $key => $tag) {
				$output.='<div path="//head/script['.($key+1).']" class="script_tag"><b>Script Tag</b> : ';

				$json = json_decode($tag);

				if($json){
					foreach ($json as $key => $value) {
						$output.=$key . ' : '. json_encode($value);
					}
				}

				$output.='</div>';
			}

			$output.='</div>';

			return $output;
		}

		function proxy(){
			$output = $this->html;
			$output = $this->clearScripts($output);
			
			$output = $this->addScript($output);

			if($this->view_method == 'HTML'){
				return $output;
			}

			if($this->view_method == 'META'){
				$output = $this->parseMetaTags($output);

				return $output;
			}

			if($this->view_method == 'JSON'){
				$output = $this->parseJSON($output);

				return $output;
			}
		}
	}