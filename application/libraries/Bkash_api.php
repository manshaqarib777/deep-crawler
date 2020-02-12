<?php

 class Bkash_api
 { 		
	public function get_info_by_trxid($transaction_id)
	{			
		$url="http://getbddoctor.com/secure/bkash/sys.php?transaction_id={$transaction_id}";
	 	$headers = array("Content-type: application/xml");							
		$ch = curl_init();			   
	    curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	   	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);  
	    curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");  
	    curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");  
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
	    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");  
		$st=curl_exec($ch);
  		$transaction_array=json_decode($st);
	    return $transaction_array;				
	}	
}
 
?>