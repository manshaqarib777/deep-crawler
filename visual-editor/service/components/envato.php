<?php

	function checkPurchaseCode($purchase_code){
		$code = trim($purchase_code);

		if(!preg_match("/^([a-z0-9]{8})[-](([a-z0-9]{4})[-]){3}([a-z0-9]{12})$/im", $code)){
	        return false;
	    }else{
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$code}",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => 20,

				CURLOPT_HTTPHEADER => array(
					"Authorization: Bearer zBVBoTdFd4Al0KIniKQU3AXgpS3mGPqC",
					"User-Agent: Enter a description of your app here for the API team"
				)
			));

			$verify_code = curl_exec($ch);
			$verify_code = json_decode($verify_code, true);
	    	curl_close($ch);

	    	if(@$verify_code['error'] == 404){
	    		return 0;
	    	}else{
	    		return (@$verify_code['license'] == 'Regular License' ? 1 : 2);
	    	}
	    }
	}