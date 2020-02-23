<head></head>
<style type="text/css">
	body{
		font-size: 22px;
	}
	
	.attribute{
		display: block;
	}

	li{
		list-style: none;
	}

	ul{
		
	}

	ul .meta_tag{
		display: block;
		border: solid 1px #ddd;
		padding: 5px;
		border-bottom: none;
		text-decoration: none;
	}

	ul li:last-child{
		border-bottom: solid 1px #ddd;
	}

	span{
		margin-bottom: 10px;
	}

	span, ul > a{
		display: block;
	    background: #f5f5f5;
	    padding: 10px 20px;
	    border-radius: 5px;
	    border: solid 1px #ddd;
	}

	li > span{
		margin-bottom: 10px;
	}

	a{
		text-decoration: none;
	}

	.inner_field{
		margin-left: 20px;
		margin-top: 20px;
		margin-bottom: 20px;
	}

	.meta_tag{
		display: block;
	}
</style>
<?php

	function xml2array ( $xmlObject, $out = array () )
	{
	    foreach ( (array) $xmlObject as $index => $node )
	        $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;

	    return $out;
	}

	function get($url){
		$handle = curl_init($url);

	    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);

	    if($user_agent){
	    	curl_setopt($handle, CURLOPT_USERAGENT, $user_agent);
	    }else{
	    	curl_setopt($handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36');
	    }

	    $response = curl_exec($handle);
	    $info     = curl_getinfo($handle);

	    curl_close($handle);

	    return $response;
	}

	function showText($content){
		$content = strip_tags($content);
		$content = substr($content, 0, 255);

		return $content;
	}

	function displayTree($tree, $parentKey = false, $parentTree = false){
		$output = '';

		foreach ($tree as $key => $item) {
			if($parentKey){
				$link = $parentKey . '_' . $key;
			}else{
				$link = 'root-' . $key;
			}

			$innerData = displayTree($item, $link, $tree);                        
			$path = '//a[contains(@class, "'.$link.'")]';

			$output.='<a path=\''.$path.'\' href="'.showText($item).'" class="meta_tag '.$link.'">';
			$output.=''.$key.' : '.showText($item).'';

			if($innerData){
				$output.='<div class="inner_field">'.$innerData.'</div>';
			}

			if($item->attributes()){
				$output.='<ul>';
				foreach($item->attributes() as $a => $b) {
					if($b){
						$path = '//a[contains(@class, \"'.$link.'\")]';
				    	$output.='<a href="'.showText($b).'" path="'.$path.'" class="meta_tag '.$link.'attribute_'.$a.'">[attribute:'.$a . '] : ' . $b . '</a>';
					}
				}
				$output.='</ul>';
			}

			$output.='</a>';
		}

		return $output;
	}

	$url  = $_GET['url'];
	$html = get($url);
	$xml  = simplexml_load_string($html);

	echo displayTree($xml);
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('a').each(function(){
			if($(this).text().search('http') > -1){
				
		    }else{				
		    }
		});
	});
</script>