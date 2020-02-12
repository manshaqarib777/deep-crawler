<?php require_once("Home.php");

class Test extends Home
{

	public $user_id;
	public $download_id;
	
	
	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('logged_in') != 1) {
            redirect('home/login_page', 'location');
        }
		$this->user_id=$this->session->userdata('user_id');
		$this->download_id=$this->session->userdata('download_id');
		
	}
	
	public function index()
	{
		echo APPPATH; exit();
		$data['body'] = 'admin/test';
		$this->_viewcontroller($data);		
	}

	public function multiple_read_file()
	{
		$str = '';
		$num_row = count($_FILES['myfile']['name']);
		for($i=0;$i<$num_row;$i++)
		{
			$output_dir = FCPATH."upload";
			$error =$_FILES["myfile"]["error"][$i];         
            $post_fileName = $_FILES["myfile"]["name"][$i];
            $post_fileName_array=explode(".",$post_fileName);
            $ext=array_pop($post_fileName_array);
            $filename=implode('.',$post_fileName_array);
            $filename=$filename."_".$this->session->userdata('user_id')."_".$this->session->userdata('download_id').substr(uniqid(mt_rand(), true) , 0, 6).".".$ext;
          
          	$str .= file_get_contents($_FILES["myfile"]["tmp_name"][$i]);
		}
		echo $str;
	}


	 public function multiple_file_read()
    {
        
        if($_SERVER['REQUEST_METHOD'] === 'GET') 
        redirect('home/access_forbidden','location');

        $ret=array();
        $output_dir = FCPATH."upload";
        if(isset($_FILES["myfile"]) && $_FILES['myfile']['size'] != 0 && $_FILES['myfile']['size'] <= 2097150 && $_FILES['myfile']['type'] == 'text/plain')
        {           
            $error =$_FILES["myfile"]["error"];         
            $post_fileName = $_FILES["myfile"]["name"];
            $post_fileName_array=explode(".",$post_fileName);
            $ext=array_pop($post_fileName_array);
            $filename=implode('.',$post_fileName_array);
            $filename=$filename."_".$this->session->userdata('user_id')."_".$this->session->userdata('download_id').substr(uniqid(mt_rand(), true) , 0, 6).".".$ext;
            move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.'/'.$filename);

            $path = realpath(FCPATH."upload/".$filename);
            $temp = file_get_contents($path);
            
			

            $ret[]= $filename;
            echo json_encode($ret);

            
         }
       
    }  	

	
	public function experiment(){
		$this->load->library('scraper');
		$result=$this->scraper->whois_email("kl.com");
		
		echo "<pre>";
			print_r($result);
		echo "</pre>";
		
	}
	
	function scrape_website($website='',$proxy=''){
		set_time_limit(0);
		
		$website=rawurldecode($website);
		$config['url']=$website;
		$this->load->library('scraper',$config);
		
		/*** Insert domain name into database ***/
		
		$db_insert_website=$this->security->xss_clean($website);
		$db_insert_website=$this->db->escape($db_insert_website);
		$insert_data=array(
				"user_id" =>$this->user_id,
				"domain_name"=>$db_insert_website,
				"last_scraped_time"=>date("Y-m-d H:i:s")
			);		
		$this->basic->insert_data('domain',$insert_data);
		
		$this->scraper->domain_id=$this->db->insert_id();
		$this->scraper->start_scrapping($proxy);
	}
	
	
	function scrape_searchengine($keyword='',$search_engine='',$social_network='',$proxy=''){
	
			$this->load->library('scraper');
			$keyword=$this->scraper->make_keyword($keyword,$social_network,$email_provider=array("gmail.com"));
			
			$delay=array('5','10','20','30');
			
			for($i=0;$i<10;$i++){
				 $emails=$this->scraper->googleSearch($keyword,$page_number=0,$proxy='');
				 $emails=implode(", ",$emails);
				 echo "<script type=\"text/javascript\">parent.document.getElementById( 'email_list').innerHTML += '<li>$emails</li>';</script>";
				 flush() ;
				 $sleep_time=random_value_from_array($delay, $default=null);
				 sleep($sleep_time);
				 
			}



				
	}


	public function page_status(){
		$url="xeroneit.net";
		$this->load->library('scraper');
		$this->scraper->page_status_check($url);
		
		
		$username = "dbill";
		$password = "dBILL!23";
		$remote_url = "http://mbsrv.dutchbanglabank.com/BillPayGWTest/BillInfoService?shortcode=356&userid=cfdgc101&password=e43dft4vdytrtht&opcode=GT&txnid=6419936";
		
		// Create a stream
		$opts = array(
		    'http' => array(
		        'method' => "GET",
		        'host' => '10.10.200.142',
		        'header' => "Authorization: Basic " . base64_encode("$username:$password")
		    )
		);
		
		$context = stream_context_create($opts);
		
		// Open the file using the HTTP headers set above
		$file = file_get_contents($remote_url, false, $context);
		
		print($file);


		
	}


	function dash_board(){
		$data['body'] = 'admin/dash_board';
		$this->_viewcontroller($data);		
	}


}

