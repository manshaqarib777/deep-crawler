<?php
class Paypal_class{

	public $mode="live";
	public $paypal_url;
	public $success_url;
	public $cancel_url;
	public $notify_url;
	public $business_email="konokcseru@gmail.com";
	public $product_quantity=1;
	public $product_name;
	public $product_number;
	public $amount;
	public $shipping_amount=0;
	public $currency="USD";
	public $button_image; 
	public $ipn_response;
	public $user_id;
	public $package_id;
	
	function __construct(){
		
		$this->CI =& get_instance();
		$this->CI->load->database();
	
		if($this->mode=='sandbox')
			$this->paypal_url="https://www.sandbox.paypal.com/cgi-bin/webscr";
		else
			$this->paypal_url="https://www.paypal.com/cgi-bin/webscr";
			
			$databae_name= $this->CI->db->database;
	}
	
	function set_button(){
	
		$button="";
		
		$button.= "<form action='{$this->paypal_url}' method='post' style='padding: 0; margin: 0;'>";
			$button.= "<input type='hidden' name='cmd' value='_xclick' />";
			$button.= "<input type='hidden' name='business' value='{$this->business_email}' />";
			$button.= "<input type='hidden' name='quantity' value='{$this->product_quantity}' />";
			$button.= "<input type='hidden' name='item_name' value='{$this->product_name}' />";
			$button.= "<input type='hidden' name='item_number' value='{$this->product_number}' />";
			$button.= "<input type='hidden' name='amount' value='{$this->amount}' />";
			$button.= "<input type='hidden' name='shipping' value='{$this->shipping_amount}' />";
			$button.= "<input type='hidden' name='no_note' value='1' />";
			$button.= "<input type='hidden' name='notify_url' value='{$this->notify_url}'>";
			$button.= "<input type='hidden' name='currency_code' value='{$this->currency}' />";
			$button.= "<input type='hidden' name='return' value='{$this->success_url}'>";
			$button.= "<input type='hidden' name='cancel_return' value='{$this->cancel_url}'>";
			$button.= "<input type='hidden' name='custom' value='{$this->user_id}_{$this->package_id}'>";
			
			$button_url=base_url()."assets/images/paypal_btn.png";
			$button.= "<input type='image' src='{$button_url}' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
			$button.= "<img alt='' border='0' src='{$button_url}' width='1' height='1'>";
		$button.= "</form>";	
		
		return $button;
	
	}
	
	/****
		 This run_ipn() function will return the verified status that is payment is VERIFIED or NOTVERIFIED. And some correspoding
		 data of the payment. 
		 
		 	$payment_info=$paypal_ipn->run_ipn();
			$verify_status=$payment_info['verify_status'];
			$first_name=$payment_info['data']['first_name'];
			$last_name=$payment_info['data']['last_name'];
			$buyer_email=$payment_info['data']['payer_email'];
			$receiver_email=$payment_info['data']['receiver_id'];
			$country=$payment_info['data']['address_country'];
			$payment_date=$payment_info['data']['payment_date'];
			$transaction_id=$payment_info['data']['txn_id'];
			$payment_type=$payment_info['data']['payment_type'];
			
	***/
	
	function run_ipn($insert=0){
		$req = 'cmd=' . urlencode('_notify-validate');
		foreach ($_POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}
		
		 $ch = curl_init();
		 $headers = array("Content-type: application/json");
		 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		 curl_setopt($ch, CURLOPT_POST, 1);  
		 curl_setopt($ch, CURLOPT_POSTFIELDS,$req);
	     curl_setopt($ch, CURLOPT_URL, $this->paypal_url);
		 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
	     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
	     curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');  
	     curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');  
	     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
	     curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");   
		 $st=curl_exec($ch);  
		 curl_close($ch); 	
		 $response['verify_status']=$st;
		 $response['data']=$_POST;
		 $this->ipn_response=$response;
		 
		 if($insert==1)
		 	$this->insert_ipn_data();
			
		 return $response;	 
	}
	
	public function insert_ipn_data(){
	
		$payment_info=$this->ipn_response;	
		$verify_status=$payment_info['verify_status'];
		$first_name=$payment_info['data']['first_name'];
		$last_name=$payment_info['data']['last_name'];
		$buyer_email=$payment_info['data']['payer_email'];
		$receiver_email=$payment_info['data']['receiver_id'];
		$country=$payment_info['data']['address_country'];
		$payment_date=$payment_info['data']['payment_date'];
		$transaction_id=$payment_info['data']['txn_id'];
		$payment_type=$payment_info['data']['payment_type'];
		$entry_time=date("Y-m-d H:i:s");
		
		/***Insert into table ***/
		$insert_data=array("verify_status"=>$verify_status,"buyer_first_name"=>$first_name,
							"buyer_last_name"=>$last_name,"buyer_email"=>$buyer_email,"receiver_email"=>$receiver_email,
							"buyer_country"=>$country,"payment_date"=>$payment_date,"transaction_id"=>$transaction_id,
							"entry_time"=>$entry_time,"payment_type"=>$payment_type);
		
		$this->CI->db->insert("paypal_payment_data",$insert_data);
		
	}
}

?>