<?php

require_once("Home.php"); // including home controller

/**
* @category controller
* class Admin_advance
*/

class Admin_advance extends Home
{    
    public $user_id;
    public $download_id;    

    /**
    * load constructor
    * @access public
    * @return void
    */    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1) {
            redirect('home/login_page', 'location');
        }
        $this->load->helper('form');
        $this->load->helper('my_helper');
        $this->load->library('upload');
        $this->load->library('image_lib');
        $this->upload_path = realpath(APPPATH . '../upload');
        $this->user_id=$this->session->userdata('user_id');
        $this->download_id=$this->session->userdata('download_id');

        $this->important_feature();
        $this->periodic_check();
        $this->member_validity();
    }

    /**
    * index method
    * @access public
    * @return void
    */
    public function index()
    {

    }

    /**
    * method to who is search view page
    * @access public
    * @return void
    */
    public function whois_search()
    {
        $data['body'] = 'admin/advance/whois_search';
        $data['page_title'] = 'Whois Search';
        $this->_viewcontroller($data);
    }

    /**
    * method to who is search view page
    * @access public
    * @return void
    */
    public function whois_search_data()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $page = isset($_POST['page']) ? intval($_POST['page']) : 15;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'whois_search.id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';

        $domain_name          = trim($this->input->post("domain_name", true));
        $namve_servers        = trim($this->input->post("namve_servers", true));
        $sponsor              = trim($this->input->post("sponsor", true));

        $from_date            = trim($this->input->post('from_date', true));
        if($from_date)
            $from_date        = date('Y-m-d', strtotime($from_date));

        $to_date            = trim($this->input->post('to_date', true));
        if($to_date)
            $to_date        = date('Y-m-d', strtotime($to_date));


            // setting a new properties for $is_searched to set session if search occured
        $is_searched = $this->input->post('is_searched', true);


        if ($is_searched) {
            // if search occured, saving user input data to session. name of method is important before field

            $this->session->set_userdata('whois_search_domain_name',      $domain_name);
            $this->session->set_userdata('whois_search_namve_servers',      $namve_servers);
            $this->session->set_userdata('whois_search_sponsor',      $sponsor);
            $this->session->set_userdata('whois_search_from_date',        $from_date);
            $this->session->set_userdata('whois_search_to_date',        $to_date);
            //	$this->session->set_userdata('book_list_category',$category_id);
        }

            // saving session data to different search parameter variables

        $search_domain_name          = $this->session->userdata('whois_search_domain_name');
        $search_namve_servers          = $this->session->userdata('whois_search_namve_servers');
        $search_sponsor              = $this->session->userdata('whois_search_sponsor');
        $search_from_date              = $this->session->userdata('whois_search_from_date');
        $search_to_date              = $this->session->userdata('whois_search_to_date');
       //	$search_category=$this->session->userdata('book_list_category');

        // creating a blank where_simple array
        $where_simple=array();

      // trimming data

        
        if ($search_domain_name) {
            $where_simple['domain_name like ']    = "%".$search_domain_name."%";
        }

        if ($search_namve_servers) {
            $where_simple['namve_servers like']   = "%".$search_namve_servers."%";
        }

        if ($search_sponsor) {
            $where_simple['sponsor like']   = "%".$search_sponsor."%";
        }

        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["Date_Format(scraped_time,'%Y-%m-%d') >="]= $search_from_date;
            }
        }
        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["Date_Format(scraped_time,'%Y-%m-%d') <="]=$search_to_date;
            }
        }

        $where_simple['user_id'] = $this->user_id;
        
        
        $where  = array('where'=>$where_simple);

        $order_by_str=$sort." ".$order;

        $offset = ($page-1)*$rows;
        $result = array();

        $table = "whois_search";

        $info = $this->basic->get_data($table, $where, $select='', $join='', $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='');

        $total_rows_array = $this->basic->count_row($table, $where, $count="id", $join='');

        $total_result = $total_rows_array[0]['total_rows'];

        echo convert_to_grid_data($info, $total_result);
    }

    /**
    * method to whois download
    * @access public    
    * @return void
    */
    public function whois_download()
    {
        $table = 'whois_search';
       
        $selected_grid_data = $this->input->post('info', true);
        $url_names = json_decode($selected_grid_data, true);
        $url_names_array = array();
        foreach ($url_names as  $value) {
            $url_names_array[] = $value['id'];
        }
        $where['where_in'] = array('id' => $url_names_array);

        $info = $this->basic->get_data($table, $where, $select ='', $join='', $limit='', $start=null, $order_by='id asc');

        $fp = fopen("download/report/whois_email_{$this->user_id}_{$this->download_id}.csv", "w");
        $head=array("Doamin","Admin Email", "Tech. Email", "Name Server","Sponsor","Created At");
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) {
            $write_info['domain_name'] = $value['domain_name'];
            $write_info['admin_email'] = $value['admin_email'];
            $write_info['tech_email'] = $value['tech_email'];
            $write_info['namve_servers'] = $value['namve_servers'];
            $write_info['sponsor'] = $value['sponsor'];
            $write_info['created_at'] = $value['created_at'];

            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/report/whois_email_{$this->user_id}_{$this->download_id}.csv";
        echo $file_name;
    }

    /**
    * method to page status checker
    * @access public
    * @return void    
    */
    public function page_status_checker()
    {
        $data['body'] = 'admin/advance/page_status_checker';
        $data['page_title'] = 'Page Status Checker';
        $this->_viewcontroller($data);
    }

    public function page_status_checker_data()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $page = isset($_POST['page']) ? intval($_POST['page']) : 15;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'page_status.id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';

        $http_code        = trim($this->input->post("http_code", true));
        $http_status      = trim($this->input->post("http_status", true));

        $from_date          = trim($this->input->post('from_date', true));
        if($from_date)
            $from_date          = date('Y-m-d', strtotime($from_date));

        $to_date            = trim($this->input->post('to_date', true));
        if($to_date)
            $to_date            = date('Y-m-d', strtotime($to_date));


            // setting a new properties for $is_searched to set session if search occured
        $is_searched = $this->input->post('is_searched', true);


        if ($is_searched) {
            // if search occured, saving user input data to session. name of method is important before field

            $this->session->set_userdata('page_status_http_code',      $http_code);
            $this->session->set_userdata('page_status_http_status',      $http_status);
            $this->session->set_userdata('page_status_from_date',        $from_date);
            $this->session->set_userdata('page_status_to_date',        $to_date);
            //  $this->session->set_userdata('book_list_category',$category_id);
        }

            // saving session data to different search parameter variables

        $search_http_code         = $this->session->userdata('page_status_http_code');
        $search_http_status       = $this->session->userdata('page_status_http_status');
        $search_from_date           = $this->session->userdata('page_status_from_date');
        $search_to_date             = $this->session->userdata('page_status_to_date');
       //   $search_category=$this->session->userdata('book_list_category');

        // creating a blank where_simple array
        $where_simple=array();

      // trimming data

        
        if ($search_http_code) {
            $where_simple['http_code like ']    = "%".$search_http_code."%";
        }

        if ($search_http_status) {
            $where_simple['status like']   = "%".$search_http_status."%";
        }
        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["Date_Format(check_date,'%Y-%m-%d') >="]= $search_from_date;
            }
        }
        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["Date_Format(check_date,'%Y-%m-%d') <="]=$search_to_date;
            }
        }
        
        
        $where  = array('where'=>$where_simple);

        $order_by_str=$sort." ".$order;

        $offset = ($page-1)*$rows;
        $result = array();

        $table = "page_status";

        $info = $this->basic->get_data($table, $where, $select='', $join='', $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='');

        $total_rows_array = $this->basic->count_row($table, $where, $count="id", $join='');

        $total_result = $total_rows_array[0]['total_rows'];

        echo convert_to_grid_data($info, $total_result);
    }

    /**
    * method to page status download
    * @access public
    * @return void    
    */
    public function page_status_download()
    {
        $table = 'page_status';
       
        $selected_grid_data = $this->input->post('info', true);
        $url_names = json_decode($selected_grid_data, true);
        $url_names_array = array();
        foreach ($url_names as  $value) {
            $url_names_array[] = $value['id'];
        }
        $where['where_in'] = array('id' => $url_names_array);

        $info = $this->basic->get_data($table, $where, $select ='', $join='', $limit='', $start=null, $order_by='id asc');

        $fp = fopen("download/page_status/page_staus_{$this->user_id}_{$this->download_id}.csv", "w");
        $head=array("URL","HTTP Code", "Status", "Total Time","Name Lookup Time","Connect Time","Download Speed Time","Check Status Date");
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) {
            $write_info['url'] = $value['url'];
            $write_info['http_code'] = $value['http_code'];
            $write_info['status'] = $value['status'];
            $write_info['total_time'] = $value['total_time'];
            $write_info['namelookup_time'] = $value['namelookup_time'];
            $write_info['connect_time'] = $value['connect_time'];
            $write_info['speed_download'] = $value['speed_download'];
            $write_info['check_date'] = $value['check_date'];

            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/page_status/page_staus_{$this->user_id}_{$this->download_id}.csv";
        echo $file_name;
    }

    /**
    * method to page status delete
    * @access public
    * @return boolean
    */

    public function page_status_delete()
    {
        $table = 'page_status';
       
        $selected_grid_data = $this->input->post('info', true);
        $url_names = json_decode($selected_grid_data, true);
        $url_names_array = array();
        foreach ($url_names as  $value) {
            $url_names_array[] = $value['id'];
        }
        $this->db->where_in('id', $url_names_array);
        if ($this->db->delete($table)) {
            echo "Data has benn successfully deleted from database";
        } else {
            echo "Something went wrong! please try again";
        }
    }

    /**
    * method to text file search view page
    * @access public
    * @return void
    */
    public function text_file_search()
    {
        $data['body'] = 'admin/advance/text_file_search';
        $data['page_title'] = 'Text File Search';
        $this->_viewcontroller($data);
    }

    /**
    * method to load text file search data
    * @access public
    * @return void
    */

    public function text_file_search_data()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $page = isset($_POST['page']) ? intval($_POST['page']) : 15;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'email.id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'ASC';

        $domain_name          = trim($this->input->post("domain_name", true));
        $found_email      = trim($this->input->post("found_email", true));
        


            // setting a new properties for $is_searched to set session if search occured
        $is_searched = $this->input->post('is_searched', true);


        if ($is_searched) {
            // if search occured, saving user input data to session. name of method is important before field

            $this->session->set_userdata('text_file_search_domain_name',      $domain_name);
            $this->session->set_userdata('text_file_search_found_email',      $found_email);
           
            //	$this->session->set_userdata('book_list_category',$category_id);
        }

            // saving session data to different search parameter variables

        $search_domain_name          = $this->session->userdata('text_file_search_domain_name');
        $search_found_email          = $this->session->userdata('text_file_search_found_email');
       
       //	$search_category=$this->session->userdata('book_list_category');

        // creating a blank where_simple array
        $where_simple=array();

      // trimming data

        
        if ($search_domain_name) {
            $where_simple['domain.domain_name like ']    = "%".$search_domain_name."%";
        }

        if ($search_found_email) {
            $where_simple['email.found_email like']   = "%".$search_found_email."%";
        }
        
        
        $where  = array('where'=>$where_simple);

        $order_by_str=$sort." ".$order;

        $offset = ($page-1)*$rows;
        $result = array();

        $table = "email";
        $join = array("domain" => "email.domain_id = domain.id, left");

        $info = $this->basic->get_data($table, $where, $select='', $join, $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='');

        $total_rows_array = $this->basic->count_row($table, $where, $count="email.id", $join);

        $total_result = $total_rows_array[0]['total_rows'];

        echo convert_to_grid_data($info, $total_result);
    }


    /**
    * method to read text file
    * @access public
    * @return void
    */
    public function read_text_file()
    {
        if ($_FILES['whois_upload']['size'] != 0 && ($_FILES['whois_upload']['type'] =='text/plain' || $_FILES['whois_upload']['type'] =='text/csv' || $_FILES['whois_upload']['type'] =='text/csv' || $_FILES['whois_upload']['type'] =='text/comma-separated-values' || $_FILES['whois_upload']['type']='text/x-comma-separated-values')) {
		
            $ext=array_pop(explode('.', $_FILES['whois_upload']['name']));
            $user_id = $this->session->userdata('user_id');
            $upload_time = $this->session->userdata('download_id');

            $photo = $user_id."-".$upload_time.".".$ext;
            $config = array(
                "allowed_types" => "*",
                "upload_path" => "./upload/",
                "file_name" => $photo,
                "overwrite" => true
            );
            $this->upload->initialize($config);
            $this->load->library('upload', $config);
            $this->upload->do_upload('whois_upload');
            $photo_name = $photo;
            $path = realpath(FCPATH."upload/".$photo_name);
            $read_handle=fopen($path, "r");
            $email ='';

            while (!feof($read_handle)) {
                $information = fgetcsv($read_handle);
                if (!empty($information)) {
                    foreach ($information as $info) {
                        if (!is_numeric($info) && ($info !="SL") && ($info !="Email")) {
                            $email.=$info."\n";
                        }
                    }
                }
            }
            $email = trim($email, "\n");
            echo $email;
        } else {
            echo "Something is Wrong! Please select a file and try again.";
        }
    }
    
    /**
    * method to read multiple files
    * @access public
    * @return void
    */
    public function multiple_read_file()
    {
        if ($_FILES['myfile']['type'][0] == 'text/plain' || $_FILES['myfile']['type'][0] == 'text/xml' || $_FILES['myfile']['type'][0] == 'application/octet-stream') {
            $this->load->library('scraper');
            $email_writer=fopen("download/text_file/text_email_{$this->user_id}_{$this->download_id}.csv", "w");
            $total_email=0;
            
            $str = '';
            $num_row = count($_FILES['myfile']['name']);


            //************************************************//
            $status=$this->_check_usage($module_id=4,$request=$num_row);
            if($status=="2") 
            {
                echo "<div class='alert alert-danger'>".$this->lang->line("sorry, your bulk limit is exceeded for this this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a></div>";
                // exit();
                return;
            }
            else if($status=="3") 
            {
                echo "<div class='alert alert-danger'>".$this->lang->line("sorry, your monthly limit is exceeded for this this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a></div>";
                // exit();
                return;
            }
            //************************************************//


            for ($i=0;$i<$num_row;$i++) {
                $output_dir = FCPATH."upload";
                $error =$_FILES["myfile"]["error"][$i];
                $post_fileName = $_FILES["myfile"]["name"][$i];
                $post_fileName_array=explode(".", $post_fileName);
                $ext=array_pop($post_fileName_array);
                $filename=implode('.', $post_fileName_array);
                $filename=$filename."_".$this->session->userdata('user_id')."_".$this->session->userdata('download_id').substr(uniqid(mt_rand(), true), 0, 6).".".$ext;

                $str .= file_get_contents($_FILES["myfile"]["tmp_name"][$i]);
            }
            $found_email=$this->scraper->get_email($str);
            /***Write Email***/
            foreach ($found_email as $f_email) {
                if ($f_email) {
                    $write_email=array();
                    $write_email[]=$f_email;
                    fputcsv($email_writer, $write_email);
                    $total_email++;
                }
            }
            
            
        /*** Write all  email address in text file **/
            $email_str=implode("\n", $found_email);
            $valid_email_file_writer = fopen("download/text_file/text_email_{$this->user_id}_{$this->download_id}.txt", "w");
            fwrite($valid_email_file_writer, $email_str);
            fclose($valid_email_file_writer);

            //******************************//
            // insert data to useges log table
            $this->_insert_usage_log($module_id=4,$request=$num_row);   
            //******************************//
            
            echo "<center><h3 style='color:olive;'>Total {$total_email} email found</h3></center>";
        } else {
            echo "Something is Wrong! Please select a file and try again.";
        }
    }


  
    /**
    * method to load email validator view page
    * @access public
    * @return void
    */
    public function email_validator()
    {
        $data['body'] = 'admin/advance/email_validator';
        $data['page_title'] = 'Valid Email Checker';
        $this->_viewcontroller($data);
    }
     
    /**
    * method to make unique email
    * @access public
    * @return void
    */
    public function unique_email_maker()
    {
        $data['body'] = 'admin/advance/unique_email_maker';
        $data['page_title'] = 'Unique Email Maker';
        $this->_viewcontroller($data);
    }

    public function who_is_delete(){
        $selected_grid_data = $this->input->post('info', true);
        $who_is_names = json_decode($selected_grid_data, true);
        $who_is_id_array = array();
        foreach ($who_is_names as  $value) {
            $who_is_id_array[] = $value['id'];
        }       

        $this->db->where_in('id', $who_is_id_array);
        $this->db->delete('whois_search');
    }
	
//New Update section***********************************************	
	function read_file_docx($filename){

		    $striped_content = '';
		    $content = '';
		
		    if(!$filename || !file_exists($filename)) return false;
		
		    $zip = zip_open($filename);
		
		    if (!$zip || is_numeric($zip)) return false;
		
		    while ($zip_entry = zip_read($zip)) {
		
		        if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
		
		        if (zip_entry_name($zip_entry) != "word/document.xml") continue;
		
		        $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
		
		        zip_entry_close($zip_entry);
		    }// end while
		
		    zip_close($zip);
		    $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
		    $content = str_replace('</w:r></w:p>', "\r\n", $content);
		    $striped_content = strip_tags($content);
		    return $striped_content;
	}


	
	function read_file_doc($filename) {
	    if(file_exists($filename)) {
	        if(($fh = fopen($filename, 'r')) !== false ) {
	            $headers = fread($fh, 0xA00);
	            $n1 = ( ord($headers[0x21C]) - 1 );// 1 = (ord(n)*1) ; Document has from 0 to 255 characters
	            $n2 = ( ( ord($headers[0x21D]) - 8 ) * 256 );// 1 = ((ord(n)-8)*256) ; Document has from 256 to 63743 characters
	            $n3 = ( ( ord($headers[0x21E]) * 256 ) * 256 );// 1 = ((ord(n)*256)*256) ; Document has from 63744 to 16775423 characters
	            $n4 = ( ( ( ord($headers[0x21F]) * 256 ) * 256 ) * 256 );// 1 = (((ord(n)*256)*256)*256) ; Document has from 16775424 to 4294965504 characters
	            $textLength = ($n1 + $n2 + $n3 + $n4);// Total length of text in the document
	            $extracted_plaintext = fread($fh, $textLength);
	            $extracted_plaintext = mb_convert_encoding($extracted_plaintext,'UTF-8');
	             // if you want to see your paragraphs in a new line, do this
	             // return nl2br($extracted_plaintext);
	             return ($extracted_plaintext);
	        } else {
	            return false;
	        }
	    } else {
	        return false;
	    }  
	}


	public function doc_file_search(){
		$data['body'] = 'admin/advance/doc_file_search';
		$this->_viewcontroller($data);
	}


	public function doc_read_file(){

		 $this->load->library('scraper');
         $email_writer=fopen("download/text_file/text_email_{$this->user_id}_{$this->download_id}.csv", "w");
         $total_email=0;
		if($_FILES['myfile']['type'][0] == 'application/msword' || $_FILES['myfile']['type'][0] == 'application/pdf' || $_FILES['myfile']['type'][0] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
		{
			$str = '';
			$num_row = count($_FILES['myfile']['name']);

            //************************************************//
            $status=$this->_check_usage($module_id=5,$request=$num_row);
            if($status=="2") 
            {
                echo "<div class='alert alert-danger'>".$this->lang->line("sorry, your bulk limit is exceeded for this this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a></div>";
                // exit();
                return;
            }
            else if($status=="3") 
            {
                echo "<div class='alert alert-danger'>".$this->lang->line("sorry, your monthly limit is exceeded for this this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a></div>";
                // exit();
                return;
            }
            //************************************************//

            for ($i=0;$i<$num_row;$i++) 
            {
            	$array_file = $_FILES["myfile"]["tmp_name"][$i]; 

            	if($_FILES['myfile']['type'][$i] == 'application/msword')
            		$str .= $this->read_file_doc($array_file);

            	if($_FILES['myfile']['type'][$i] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
            		$str .= $this->read_file_docx($array_file);

            	 if($_FILES['myfile']['type'][$i] == 'application/pdf'){
				 	$this->load->library('PDF2Text');
					$this->pdf2text->setFilename($array_file);
					$this->pdf2text->decodePDF();
					$str.=$this->pdf2text->output();
					
				 }
            		           
            }
             
            $found_email=$this->scraper->get_email($str);
            /***Write Email***/
            foreach ($found_email as $f_email) {
                if ($f_email) {
                    $write_email=array();
                    $write_email[]=$f_email;
                    fputcsv($email_writer, $write_email);
                    $total_email++;
                }
            }
            
            
        /*** Write all  email address in text file **/
            $email_str=implode("\n", $found_email);
            $valid_email_file_writer = fopen("download/text_file/text_email_{$this->user_id}_{$this->download_id}.txt", "w");
            fwrite($valid_email_file_writer, $email_str);
            fclose($valid_email_file_writer);

            //******************************//
            // insert data to useges log table
            $this->_insert_usage_log($module_id=5,$request=$num_row);   
            //******************************//
            
            echo "<center><h3 style='color:olive;'>Total {$total_email} email found</h3></center>";
       	

		}
		 

         else 
         {
            echo "Something is Wrong! Please select a file and try again.";
         }
	}

	
}
