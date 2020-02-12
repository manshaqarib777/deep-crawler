<?php require_once("Home.php"); // including home controller

class Payment extends Home
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
        $this->user_id=$this->session->userdata('user_id');
		$this->load->library('paypal_class');
		$this->load->library('stripe_class');
        set_time_limit(0);

        $this->important_feature();
        $this->periodic_check();
    }

    public function payment_setting_admin()
    {
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') {
            redirect('admin/member_panel', 'location');
        }
        
		$this->load->database();
		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		$crud->set_theme('flexigrid');
		$crud->set_table('payment_config');
		$crud->order_by('id');
		$crud->where('deleted', '0');
		$crud->set_subject('Payment Setting');
		$crud->required_fields('currency');
		$crud->columns('paypal_email','stripe_secret_key','stripe_publishable_key','currency');	
		$crud->fields('paypal_email','stripe_secret_key','stripe_publishable_key','currency');	
		$crud->display_as('paypal_email','Paypal Email');
        // $crud->display_as('monthly_fee','Monthly Fee');
		$crud->display_as('currency','Currency');
		// $crud->callback_field('ordering',array($this,'class_ordering_field_crud'));
		
		$crud->unset_add();
		// $crud->unset_edit();	

		$crud->unset_delete();
		$crud->unset_read();
		$crud->unset_print();
		$crud->unset_export();
	
		$output = $crud->render();
		$data['output']=$output;
		$data['crud']=1;
		$this->_viewcontroller($data);
    }

    public function payment_dashboard_admin()
    {
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') {
            redirect('admin/member_panel', 'location');
        }

    	// $total_where_users['where'] = array('deleted'=>'0');
    	$total_user = $this->basic->get_data('users',$total_where_users='',$select=array('count(id) as total_user'));
    	$data['total_user'] = $total_user[0]['total_user'];

    	$days = date('t');
    	$first_date = date("Y-m-01");
    	$last_date = date("Y-m-{$days}");
    	// $this_month_simple_where["deleted"] = '0';
    	$this_month_simple_where["date_format(add_date,'%Y-%m-%d') >="] = $first_date;
    	$this_month_simple_where["date_format(add_date,'%Y-%m-%d') <="] = $last_date;
    	$this_month_where = array('where'=>$this_month_simple_where);
    	$this_month_user = $this->basic->get_data('users',$this_month_where,$select=array('count(id) as total_user'));
    	if(!empty($this_month_user))
    		$data['this_month_total_user'] = $this_month_user[0]['total_user'];
    	else
    		$data['this_month_total_user'] = 0;

    	$total_paid_amount = $this->basic->get_data('transaction_history',$where='',$select=array('sum(paid_amount) as total_paid_amount'));
    	if(!empty($total_paid_amount))
    		$data['total_paid_amount'] = $total_paid_amount[0]['total_paid_amount'];
    	else
    		$data['total_paid_amount'] = 0;

    	$this_month_paid_simple_where["date_format(payment_date,'%Y-%m-%d') >="] = $first_date;
    	$this_month_paid_simple_where["date_format(payment_date,'%Y-%m-%d') <="] = $last_date;
    	$this_month_paid_where = array('where' => $this_month_paid_simple_where);

    	$this_month_paid_amount = $this->basic->get_data('transaction_history',$this_month_paid_where,$select=array('sum(paid_amount) as total_paid_amount'));
    	if(!empty($this_month_paid_amount))
    		$data['this_month_paid_amount'] = $this_month_paid_amount[0]['total_paid_amount'];
    	else 
    		$data['this_month_paid_amount'] = 0;

        $where_today_user['where'] = array("date_format(add_date,'%Y-%m-%d') =" => date('Y-m-d'));
        $today_user = $this->basic->get_data('users',$where_today_user,$select=array('count(id) as total_user'));
        if(!empty($today_user))
            $data['today_user'] = $today_user[0]['total_user'];
        else
            $data['today_user'] = 0;

        $today_paid_simple_where["date_format(payment_date,'%Y-%m-%d') ="] = date("Y-m-d");
        $today_paid_where = array('where' => $today_paid_simple_where);

        $today_paid_amount = $this->basic->get_data('transaction_history',$today_paid_where,$today_select=array('sum(paid_amount) as total_paid_amount'));

        if(!empty($today_paid_amount))
            $data['today_paid_amount'] = $today_paid_amount[0]['total_paid_amount'];
        else 
            $data['today_paid_amount'] = 0;

    	$data['body'] = 'admin/payment_dashboard';
    	$data['page_title'] = 'Payment Dashboard';        
        $data['payment_config']=$this->basic->get_data('payment_config');
    	$this->_viewcontroller($data);
    }

    public function admin_payment_history()
    {
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') {
            redirect('admin/member_panel', 'location');
        }

    	$data['body'] = 'admin/admin_payment_history';
    	$data['page_title'] = 'Payment History';
        
        $table = "transaction_history";
        $info = $this->basic->get_data($table, $where='', $select = '');        
        $total_paid_amount = 0;
        foreach ($info as $payment_info) {
            $total_paid_amount = $total_paid_amount + $payment_info['paid_amount'];
        }
        $data['total_paid_amount'] = $total_paid_amount;

    	$this->_viewcontroller($data);
    }

    public function admin_payment_history_data()
    {
    	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }
        // setting variables for pagination
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 15;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'transaction_history.id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';
        $order_by_str = $sort." ".$order;

        // setting properties for search
        $first_name = trim($this->input->post('first_name', true));
        $last_name = trim($this->input->post('last_name', true));
        $from_date = trim($this->input->post('from_date', true));
        if($from_date != '')
        	$from_date = date('Y-m-d', strtotime($from_date));
        $to_date = trim($this->input->post('to_date', true));
        if($to_date != '')
        	$to_date = date('Y-m-d', strtotime($to_date));

        $is_searched= $this->input->post('is_searched', true);


        if ($is_searched) {
            // if search occured, saving user input data to session. name of method is important before field
            $this->session->set_userdata('admin_payment_history_first_name', $first_name);
            $this->session->set_userdata('admin_payment_history_last_name', $last_name);
            $this->session->set_userdata('admin_payment_history_from_date', $from_date);
            $this->session->set_userdata('admin_payment_history_to_date', $to_date);
        }
        // saving session data to different search parameter variables
        $search_first_name = $this->session->userdata('admin_payment_history_first_name');
        $search_last_name = $this->session->userdata('admin_payment_history_last_name');
        $search_from_date = $this->session->userdata('admin_payment_history_from_date');
        $search_to_date = $this->session->userdata('admin_payment_history_to_date');

        // creating a blank where_simple array
        $where_simple = array();

        // trimming data
        if ($search_first_name) {
            $where_simple['transaction_history.first_name like'] = $search_first_name."%";
        }

        if ($search_last_name) {
            $where_simple['transaction_history.last_name like'] = $search_last_name."%";
        }

        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["Date_Format(payment_date,'%Y-%m-%d') >="] = $search_from_date;
            }
        }
        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["Date_Format(payment_date,'%Y-%m-%d') <="] = $search_to_date;
            }
        }

        $where = array('where' => $where_simple);
        $offset = ($page-1)*$rows;
        $result = array();

        $table = "transaction_history";
        $join=array('users'=>"users.id=transaction_history.user_id,left");
        $info = $this->basic->get_data($table, $where, $select = 'users.email,users.name, first_name, last_name,verify_status,paypal_email,receiver_email,country,payment_date,payment_type,transaction_id,paid_amount,user_id,cycle_start_date,cycle_expired_date,transaction_history.package_id,stripe_card_source', $join, $limit = $rows, $start = $offset, $order_by = $order_by_str);
        
        // $total_paid_amount = 0;
        // foreach ($info as $payment_info) {
        //     $total_paid_amount = $total_paid_amount + $payment_info['paid_amount'];
        // }

        // $this->session->set_userdata('total_paid_amount',$total_paid_amount);

        $total_rows_array = $this->basic->count_row($table, $where, $count = "transaction_history.id",$join);
        $total_result = $total_rows_array[0]['total_rows'];
        echo convert_to_grid_data($info, $total_result);
    }

    

    public function member_payment_history()
    {
    	if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Member') 
        redirect('home/login_page', 'location');        

        $data['body'] = 'member/member_payment_history';
        $data['page_title'] = $this->lang->line("payment history");
        $data["packages"]=$this->_payment_package();        
        $config_data=$this->basic->get_data("payment_config");
        $data["currency"]=$config_data[0]["currency"];

        $this->_viewcontroller($data);
    }

    public function payment_button()
    {
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Member')
        redirect('home/login_page', 'location');
        
        if($_POST)
        {
            $cancel_url=base_url()."payment/member_payment_history";
            $success_url=base_url()."payment/member_payment_history";

            $payment_amount=0;
            $package_name="";
            $package_validity="";
            $package_id=$this->input->post("package");
            $package_data=$this->basic->get_data("package",$where=array("where"=>array("package.id"=>$package_id)));
            if(is_array($package_data) && array_key_exists(0, $package_data))
            {
                $payment_amount=$package_data[0]["price"];
                $package_name=$package_data[0]["package_name"];
                $package_validity=$package_data[0]["validity"];
            }
            else 
            {
                echo $this->lang->line("something went wrong, please try again.");
                exit();
            }

            $where['where'] = array('deleted'=>'0');
            $payment_config = $this->basic->get_data('payment_config',$where,$select='');
            if(!empty($payment_config)) 
            {
                $paypal_email = $payment_config[0]['paypal_email'];
                $currency=$payment_config[0]["currency"];
				$stripe_secret= $payment_config[0]["stripe_secret_key"];
            } 
            else 
            {
                $paypal_email = "";
                $currency="USD";
            }

            
            $this->paypal_class->mode="live";
            $this->paypal_class->cancel_url=$cancel_url;
            $this->paypal_class->success_url=$success_url;
            $this->paypal_class->notify_url=site_url()."paypal_ipn/ipn_notify";
            $this->paypal_class->amount=$payment_amount;
            $this->paypal_class->user_id=$this->user_id;
            $this->paypal_class->business_email=$paypal_email;
            $this->paypal_class->currency=$currency;
            $this->paypal_class->package_id=$package_id;
            $this->paypal_class->product_name=$this->config->item("product_name")." : ".$package_name." (".$package_validity." days)";
          
		  
		  
			$this->session->set_userdata('stripe_payment_package_id',$package_id);
			$this->session->set_userdata('stripe_payment_amount',$payment_amount);
			
			if($paypal_email!="")
            	echo $button = $this->paypal_class->set_button(); 

			/*****	Stripe Button ******/
			if($stripe_secret!=""){
			$this->stripe_class->description=$this->config->item("product_name")." : ".$package_name." (".$package_validity." days)";
			$this->stripe_class->amount=$payment_amount;
			$this->stripe_class->action_url=site_url()."stripe_action";
			echo $this->stripe_class->set_button();
			}
        } 

    }

    public function member_payment_history_data()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $user_id = $this->session->userdata('user_id');
        // setting variables for pagination
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 15;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'transaction_history.id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';
        $order_by_str = $sort." ".$order;

        // setting properties for search
        $first_name = trim($this->input->post('first_name', true));
        $last_name = trim($this->input->post('last_name', true));
        $from_date = trim($this->input->post('from_date', true));
        if($from_date != '')
            $from_date = date('Y-m-d', strtotime($from_date));
        $to_date = trim($this->input->post('to_date', true));
        if($to_date != '')
            $to_date = date('Y-m-d', strtotime($to_date));

        $is_searched= $this->input->post('is_searched', true);


        if ($is_searched) {
            // if search occured, saving user input data to session. name of method is important before field
            $this->session->set_userdata('member_payment_history_first_name', $first_name);
            $this->session->set_userdata('member_payment_history_last_name', $last_name);
            $this->session->set_userdata('member_payment_history_from_date', $from_date);
            $this->session->set_userdata('member_payment_history_to_date', $to_date);
        }
        // saving session data to different search parameter variables
        $search_first_name = $this->session->userdata('member_payment_history_first_name');
        $search_last_name = $this->session->userdata('member_payment_history_last_name');
        $search_from_date = $this->session->userdata('member_payment_history_from_date');
        $search_to_date = $this->session->userdata('member_payment_history_to_date');

        // creating a blank where_simple array
        $where_simple = array();

        // trimming data
        if ($search_first_name) {
            $where_simple['transaction_history.first_name like'] = $search_first_name."%";
        }

        if ($search_last_name) {
            $where_simple['transaction_history.last_name like'] = $search_last_name."%";
        }

        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["Date_Format(payment_date,'%Y-%m-%d') >="] = $search_from_date;
            }
        }
        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["Date_Format(payment_date,'%Y-%m-%d') <="] = $search_to_date;
            }
        }

        $where_simple['transaction_history.user_id'] = $user_id;

        $where = array('where' => $where_simple);
        $offset = ($page-1)*$rows;
        $result = array();

        $table = "transaction_history";
        $join=array('users'=>"users.id=transaction_history.user_id,left");
        $info = $this->basic->get_data($table, $where, $select = 'users.email,users.name, first_name, last_name,verify_status,paypal_email,receiver_email,country,payment_date,payment_type,transaction_id,paid_amount,user_id,cycle_start_date,cycle_expired_date,transaction_history.package_id,stripe_card_source', $join, $limit = $rows, $start = $offset, $order_by = $order_by_str);

        $total_rows_array = $this->basic->count_row($table, $where, $count = "transaction_history.id",$join);
        $total_result = $total_rows_array[0]['total_rows'];

        echo convert_to_grid_data($info, $total_result);
    }
	
	

     public function package_settings()
    {
        if($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') 
        redirect('home/login_page', 'location');
        
        $data['body']='admin/payment/package_list';
        $data['payment_config']=$this->basic->get_data('payment_config');
        $this->_viewcontroller($data);  
    }

    public function package_data()
    {           

        if($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') 
        redirect('home/login_page', 'location');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'package_name';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'ASC';
        $order_by_str=$sort." ".$order;
                
        $offset = ($page-1)*$rows;            
        $info=$this->basic->get_data('package',$where='',$select='',$join='',$limit=$rows,$start=$offset,$order_by=$order_by_str,$group_by='',$num_rows=1);
        $total_rows_array=$this->basic->count_row($table="package",$where='',$count="package.id");
        $total_result=$total_rows_array[0]['total_rows'];            
        echo convert_to_grid_data($info,$total_result);
    }


    public function add_package()
    {       
        $data['body']='admin/payment/add_package';     
        $data['page_title']=$this->lang->line('package settings');     
        $data['modules']=$this->basic->get_data('modules',$where='',$select='',$join='',$limit='',$start='',$order_by='module_name asc',$group_by='',$num_rows=0);
        $data['payment_config']=$this->basic->get_data('payment_config');
        $this->_viewcontroller($data);
    }


    public function add_package_action() 
    {
        if($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') 
        redirect('home/login_page', 'location');

        if($_SERVER['REQUEST_METHOD'] === 'GET') 
        redirect('home/access_forbidden','location');

        if($_POST)
        {
            $this->form_validation->set_rules('name', '<b>'.$this->lang->line("package name").'</b>', 'trim|required|xss_clean');   
            $this->form_validation->set_rules('price', '<b>'.$this->lang->line("price").'</b>', 'trim|required|xss_clean');
            $this->form_validation->set_rules('validity', '<b>'.$this->lang->line("validity").'</b>', 'trim|required|xss_clean|integer');   
            $this->form_validation->set_rules('modules[]','<b>'.$this->lang->line("modules").'</b>','required');       
                
            if ($this->form_validation->run() == FALSE)
            {
                $this->add_package(); 
            }
            else
            {
                $package_name=$this->input->post('name');
                $price=$this->input->post('price');
                $validity=$this->input->post('validity');
                
                $modules=array();
                if(count($this->input->post('modules'))>0)  
                {
                   $modules=$this->input->post('modules');                            
                }

                $bulk_limit=array();
                $monthly_limit=array();

                foreach ($modules as $value) 
                {
                    $monthly_field="monthly_".$value;
                   
                    $val=$this->input->post($monthly_field);
                    if($val=="") $val=0;
                    $monthly_limit[$value]=$val;
               

                    $bulk_field="bulk_".$value;
                    
                    $val=$this->input->post($bulk_field);
                    if($val=="") $val=0;
                    $bulk_limit[$value]=$val;                    
                }



                $modules_str=implode(',',$modules);                        
                               
                $data=array
                (
                    'package_name'=>$package_name,
                    'price'=>$price,
                    'validity'=>$validity,
                    'module_ids'=>$modules_str,
                    'monthly_limit'=>json_encode($monthly_limit),
                    'bulk_limit'=>json_encode($bulk_limit)
                );
                
                if($this->basic->insert_data('package',$data))                                      
                $this->session->set_flashdata('success_message',1);   
                else    
                $this->session->set_flashdata('error_message',1);     
                
                redirect('payment/package_settings','location');                 
                
            }
        }   
    }


    public function details_package($id=0)
    {        
        if($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') 
        redirect('home/login_page', 'location');

        if($id==0)
        redirect('home/access_forbidden','location');

        $data['body']='admin/payment/details_package';        
        $data['modules']=$this->basic->get_data('modules',$where='',$select='',$join='',$limit='',$start='',$order_by='module_name asc',$group_by='',$num_rows=0);
        $data['value']=$this->basic->get_data('package',$where=array("where"=>array("id"=>$id)));
        $data['payment_config']=$this->basic->get_data('payment_config');

        $data['payment_config']=$this->basic->get_data('payment_config');
        $this->_viewcontroller($data);  
    }


    public function update_package($id=0)
    {       
        if($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') 
        redirect('home/login_page', 'location');

        if($id==0) 
        redirect('home/access_forbidden','location');

        $data['body']='admin/payment/update_package';     
        $data['page_title']=$this->lang->line('package settings');     
        $data['modules']=$this->basic->get_data('modules',$where='',$select='',$join='',$limit='',$start='',$order_by='module_name asc',$group_by='',$num_rows=0);
        $data['value']=$this->basic->get_data('package',$where=array("where"=>array("id"=>$id)));
        $data['payment_config']=$this->basic->get_data('payment_config');
        $this->_viewcontroller($data);
    }


    public function update_package_action() 
    {
        if($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') 
        redirect('home/login_page', 'location');

        if($_SERVER['REQUEST_METHOD'] === 'GET') 
        redirect('home/access_forbidden','location');

        if($_POST)
        {
            $id=$this->input->post("id");
            $this->form_validation->set_rules('name', '<b>'.$this->lang->line("package name").'</b>', 'trim|required|xss_clean');  
            $this->form_validation->set_rules('modules[]','<b>'.$this->lang->line("modules").'</b>','required');   
            $this->form_validation->set_rules('price', '<b>'.$this->lang->line("price").'</b>', 'trim|required|xss_clean');    
            
            if($this->input->post("is_default")=="1" && $this->input->post("price")=="Trial")    
            $this->form_validation->set_rules('validity', '<b>'.$this->lang->line("validity").'</b>', 'trim|required|xss_clean|integer');   
            
            if ($this->form_validation->run() == FALSE)
            {
                $this->update_package($id); 
            }
            else
            {
                $package_name=$this->input->post('name');
                $validity=$this->input->post('validity');
                $price=$this->input->post('price');
                
                $modules=array();
                if(count($this->input->post('modules'))>0)  
                {
                   $modules=$this->input->post('modules');                            
                }

                $bulk_limit=array();
                $monthly_limit=array();

                foreach ($modules as $value) 
                {
                    $monthly_field="monthly_".$value;
                   
                    $val=$this->input->post($monthly_field);
                    if($val=="") $val=0;
                    $monthly_limit[$value]=$val;
               

                    $bulk_field="bulk_".$value;
                    
                    $val=$this->input->post($bulk_field);
                    if($val=="") $val=0;
                    $bulk_limit[$value]=$val;                    
                }


                $modules_str=implode(',',$modules);                        
                               
                if($this->input->post("is_default")=="1" && $this->input->post("price")=="0") 
                $validity="0"; 
                $data=array
                (
                    'package_name'=>$package_name,
                    'validity'=>$validity,
                    'module_ids'=>$modules_str,
                    'price'=>$price,
                    'monthly_limit'=>json_encode($monthly_limit),
                    'bulk_limit'=>json_encode($bulk_limit)
                );
                
                if($this->basic->update_data('package',array("id"=>$id),$data))                                      
                $this->session->set_flashdata('success_message',1);   
                else    
                $this->session->set_flashdata('error_message',1);     
                
                redirect('payment/package_settings','location');                 
                
            }
        }   
    }

    public function delete_package($id=0)
    {
        if($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') 
        redirect('home/login_page', 'location');

        if($id==0) 
        redirect('home/access_forbidden','location');

        if($this->basic->update_data('package',array("id"=>$id,"is_default"=>"0"),array("deleted"=>"1")))                                      
        $this->session->set_flashdata('delete_success_message',1); 
        else $this->session->set_flashdata('delete_error_message',1); 

        redirect('payment/package_settings','location');   

    } 


    public function usage_history()
    {        
        if($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Member') 
        redirect('home/login_page', 'location');

        $info = $this->basic->get_data($table="modules", $where="", $select = "view_usage_log.*,modules.module_name,modules.id as module_id",$join=array('view_usage_log'=>"view_usage_log.module_id=modules.id AND user_id =".$this->session->userdata("user_id").",left"));        
        $package_info=$this->session->userdata("package_info");  
        $monthly_limit='';
        if(isset($package_info["monthly_limit"]))  $monthly_limit=$package_info["monthly_limit"];
        $bulk_limit='';
        if(isset($package_info["bulk_limit"]))  $bulk_limit=$package_info["bulk_limit"];
        $package_name="No Package";
        if(isset($package_info["package_name"]))  $package_name=$package_info["package_name"];
        $validity="0";
        if(isset($package_info["validity"]))  $validity=$package_info["validity"];
        $price="0";
        if(isset($package_info["price"]))  $price=$package_info["price"];

        $data['info']=$info;
        $data['monthly_limit']=json_decode($monthly_limit,true);
        $data['bulk_limit']=json_decode($bulk_limit,true);
        $data['package_name']=$package_name;
        $data['validity']=$validity;
        $data['price']=$price;

        $data['payment_config']=$this->basic->get_data('payment_config');
        
        $data['body'] = 'member/usage_log';
        $data['page_title'] = $this->lang->line("usage log");

        $this->_viewcontroller($data);
    }
	
    
	
}