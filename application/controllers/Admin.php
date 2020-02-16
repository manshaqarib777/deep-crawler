<?php


require_once("Home.php"); // loading home controller

/**
* @category controller
* class Admin
*/

class Admin extends Home
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
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') {
            redirect('admin/member_panel', 'location');
        }
        $this->user_management();
    }

    public function dashboard()
    {
        $user_id = $this->user_id;
        $data['body'] = 'admin/dashboard';
        $where_social_net['where'] = array('user_id'=>$user_id);
        $select_social_net = array(
            'search_in',
            'count(id) as total_email'
            );
        $social_net_email = $this->basic->get_data('search_engine_search',$where_social_net,$select_social_net,$join='',$limit='',$start=NULL,$order_by='',$group_by='search_in');
        $social_network = array();
        if(!empty($social_net_email)) {
            foreach ($social_net_email as $value) {
                if($value['search_in'] == 'facebook.com') {
                    $where['where'] = array('search_engine_search.user_id'=>$user_id,'search_engine_search.search_in'=>'facebook.com');
                    $join = array('search_engine_search' => 'email.search_engine_url_id=search_engine_search.id,left');
                    $fb_result = $this->basic->get_data('email',$where,$select=array('distinct(email.found_email)'),$join);                    
                    $social_network['facebook']['total_email'] = count($fb_result);
                }
                if($value['search_in'] == 'twitter.com') {
                    $where['where'] = array('search_engine_search.user_id'=>$user_id,'search_engine_search.search_in'=>'twitter.com');
                    $join = array('search_engine_search' => 'email.search_engine_url_id=search_engine_search.id,left');
                    $tw_result = $this->basic->get_data('email',$where,$select=array('distinct(email.found_email)'),$join);
                    $social_network['twitter']['total_email'] = count($tw_result);
                }
                if($value['search_in'] == 'linkedin.com') {
                    $where['where'] = array('search_engine_search.user_id'=>$user_id,'search_engine_search.search_in'=>'linkedin.com');
                    $join = array('search_engine_search' => 'email.search_engine_url_id=search_engine_search.id,left');
                    $in_result = $this->basic->get_data('email',$where,$select=array('distinct(email.found_email)'),$join);
                    $social_network['linkedin']['total_email'] = count($in_result);
                }
                if($value['search_in'] == 'pinterest.com') {
                    $where['where'] = array('search_engine_search.user_id'=>$user_id,'search_engine_search.search_in'=>'pinterest.com');
                    $join = array('search_engine_search' => 'email.search_engine_url_id=search_engine_search.id,left');
                    $pn_result = $this->basic->get_data('email',$where,$select=array('distinct(email.found_email)'),$join);
                    $social_network['pinterest']['total_email'] = count($pn_result);
                }
                if($value['search_in'] == 'tumblr.com') {
                    $where['where'] = array('search_engine_search.user_id'=>$user_id,'search_engine_search.search_in'=>'tumblr.com');
                    $join = array('search_engine_search' => 'email.search_engine_url_id=search_engine_search.id,left');
                    $tu_result = $this->basic->get_data('email',$where,$select=array('distinct(email.found_email)'),$join);
                    $social_network['tumblr']['total_email'] = count($tu_result);
                }
                if($value['search_in'] == 'reddit.com') {
                    $where['where'] = array('search_engine_search.user_id'=>$user_id,'search_engine_search.search_in'=>'reddit.com');
                    $join = array('search_engine_search' => 'email.search_engine_url_id=search_engine_search.id,left');
                    $re_result = $this->basic->get_data('email',$where,$select=array('distinct(email.found_email)'),$join);
                    $social_network['reddit']['total_email'] = count($re_result);
                }
                if($value['search_in'] == 'flickr.com') {
                    $where['where'] = array('search_engine_search.user_id'=>$user_id,'search_engine_search.search_in'=>'flickr.com');
                    $join = array('search_engine_search' => 'email.search_engine_url_id=search_engine_search.id,left');
                    $fl_result = $this->basic->get_data('email',$where,$select=array('distinct(email.found_email)'),$join);
                    $social_network['flickr']['total_email'] = count($fl_result);
                }
                if($value['search_in'] == 'instagram.com') {
                    $where['where'] = array('search_engine_search.user_id'=>$user_id,'search_engine_search.search_in'=>'instagram.com');
                    $join = array('search_engine_search' => 'email.search_engine_url_id=search_engine_search.id,left');
                    $inst_result = $this->basic->get_data('email',$where,$select=array('distinct(email.found_email)'),$join);
                    $social_network['instagram']['total_email'] = count($inst_result);
                }
            }
            $not_where_social_net['where'] = array('user_id' => $user_id);
            $not_where_social_net['where_in'] = array('search_in' => array('facebook.com','twitter.com','linkedin.com','pinterest.com','tumblr.com','reddit.com','flickr.com','instagram.com'));
            $not_select_social_net = array('id');
            $not_social_networks = $this->basic->get_data('search_engine_search',$not_where_social_net,$not_select_social_net);
            $not_social_ids = array();
            if(!empty($not_social_networks)) {
                foreach ($not_social_networks as $not_social) {
                    $not_social_ids[] = $not_social['id'];
                }
                $where_other['where_not_in'] = array('search_engine_url_id'=>$not_social_ids);
                $where_other['where'] = array('user_id'=>$user_id);
                $not_social_net = $this->basic->get_data('email',$where_other,$select=array('distinct(email.found_email)'));
                $social_network['other']['total_email'] = count($not_social_net);
            }
            
            $data['social_network'] = $social_network;


        }
        
        
        $page_status = array();
        $where['where'] = array();
        $total_http = $this->basic->get_data('page_status',$where,$select=array('count(id) as total_page'));
        if(!empty($total_http)) {
            $http_200_where['where']=array('http_code'=>'200');
            $http_200 = $this->basic->get_data('page_status',$http_200_where,$select1=array('count(id) as total_200'));
            // $not_http_200_where['where']=array('user_id'=>$user_id);
            $not_http_200_where['where_not_in']=array('http_code'=>array('200'));
            $not_http_200 = $this->basic->get_data('page_status',$not_http_200_where,$select2=array('count(id) as not_total_200'));
            
            $page_status['total_page'] = $total_http[0]['total_page'];
            $page_status['total_200'] = $http_200[0]['total_200'];
            $page_status['not_total_200'] = $not_http_200[0]['not_total_200'];
            $data['page_status'] = $page_status;
        }
        

        $total_report = array();

        $where_unique['where'] = array('user_id'=>$user_id);
        $unique_website = $this->basic->get_data('domain',$where_unique,$select_unique=array('distinct(domain_name) as domain_name'));
        $total_report['unique_website'] = count($unique_website);

        $where_url['where'] = array('user_id'=>$user_id);
        $url_crawl = $this->basic->get_data('url',$where_url,$select_url=array('url_name'),$join='',$limit='',$start=NULL,$order_by='',$group_by='url_name');
        $total_report['total_url'] = count($url_crawl);

        $where_searchengine['where'] = array('user_id'=>$user_id);
        $searchengine_crawl = $this->basic->get_data('search_engine_search',$where_searchengine,$select_searchengine=array('count(id) as total_searchengine'));
        $total_report['search_crawl'] = $searchengine_crawl[0]['total_searchengine'];

        $where_unique_email['where'] = array('user_id'=>$user_id);
        $unique_email = $this->basic->get_data('email',$where_unique_email,$select_unique_email=array('distinct(found_email) as distinct_email'));
        $total_report['unique_email'] = count($unique_email);
    
        $data['total_report'] = $total_report;


        $today_report = array();
        $date_today = date('Y-m-d');
        $simple_where_unique_today['user_id'] = $user_id;
        $simple_where_unique_today["date_format(last_scraped_time,'%Y-%m-%d') ="] = $date_today;
        $where_unique_today = array('where'=>$simple_where_unique_today);
        $unique_website_today = $this->basic->get_data('domain',$where_unique_today,$select_unique_today=array('distinct(domain_name) as domain_name'));
        $today_report['unique_website'] = count($unique_website_today);

        $url_crawl_today = $this->basic->get_data('url',$where_unique_today,$select_url_today=array('url_name'),$join='',$limit='',$start=NULL,$order_by='',$group_by='url_name');
        $today_report['total_url'] = count($url_crawl_today);

        $searchengine_crawl_today = $this->basic->get_data('search_engine_search',$where_unique_today,$select_searchengine_today=array('count(id) as total_searchengine'));
        $today_report['search_crawl'] = $searchengine_crawl_today[0]['total_searchengine'];


        $this->db->where('email.user_id',$user_id);
        $w = "(date_format(url.last_scraped_time,'%Y-%m-%d') = '{$date_today}' OR date_format(domain.last_scraped_time,'%Y-%m-%d') = '{$date_today}' OR date_format(search_engine_search.last_scraped_time,'%Y-%m-%d') = '{$date_today}')";
        $this->db->where($w);
        $join = array(
            'url' => 'email.url_id=url.id,left',
            'domain' => 'email.domain_id=domain.id,left',
            'search_engine_search' => 'email.search_engine_url_id=search_engine_search.id,left'
            );
        $today_email = $this->basic->get_data('email',$where_email_today='',$select_email=array('distinct(found_email) as total_email'),$join);
        $today_report['unique_email'] = count($today_email);
        $data['today_report'] = $today_report;

        $whois_report = array();

        $registered_where['where'] = array('user_id' => $user_id, 'is_registered' => 'yes');
        $whois_registered = $this->basic->get_data('whois_search',$registered_where,$registered_select=array('count(id) as total_registered'));
        if(!empty($whois_registered))
            $whois_report['total_registered'] = $whois_registered[0]['total_registered'];

        $unregistered_where['where'] = array('user_id' => $user_id, 'is_registered' => 'no');
        $whois_unregistered = $this->basic->get_data('whois_search',$unregistered_where,$unregistered_select=array('count(id) as total_unregistered'));
        if(!empty($whois_unregistered))
            $whois_report['total_unregistered'] = $whois_unregistered[0]['total_unregistered'];
        $data['whois_report'] = $whois_report;


        $google_success_rate = 0;
        $bing_success_rate = 0;
        $email_per_google = 0;
        $email_per_bing = 0;
        $total_google_search_email = array();
        $total_bing_search_email = array();
        
            //success rate calculation
        $total_google_search = $this->basic->get_data('search_engine_search',$where=array('where'=>array('search_engine_name'=>'Google','user_id'=>$user_id)),$select=array('id'));
        $google_search_ids = array();
        if(!empty($total_google_search)) {
            foreach($total_google_search as $value){
                $google_search_ids[] = $value['id'];
            }
            $where_google_id['where_in'] = array('search_engine_url_id' => $google_search_ids);
            $total_google_search_email = $this->basic->get_data('email',$where_google_id,$select=array('distinct(found_email)'),$join='',$limit='',$start=NULL,$order_by='',$group_by='');

            if(count($total_google_search) != 0 && count($total_google_search_email) != 0) {
                $email_per_google = count($total_google_search_email)/count($total_google_search);
            } else {
                $email_per_google = 0;
            }
        }

        $total_bing_search = $this->basic->get_data('search_engine_search',$where=array('where'=>array('search_engine_name'=>'Bing','user_id'=>$user_id)),$select=array('id'));
        $bing_search_ids = array();
        if(!empty($total_bing_search)) {
            foreach($total_bing_search as $value){
                $bing_search_ids[] = $value['id'];
            }
            $where_bing_id['where_in'] = array('search_engine_url_id' => $bing_search_ids);
            $total_bing_search_email = $this->basic->get_data('email',$where_bing_id,$select=array('distinct(found_email)'),$join='',$limit='',$start=NULL,$order_by='',$group_by='');
            if(count($total_bing_search) != 0 && count($total_bing_search_email) != 0) {
                $email_per_bing = count($total_bing_search_email)/count($total_bing_search);
            } else {
                $email_per_bing = 0;
            }
        }
        

        $total_search_emails = $email_per_google+$email_per_bing;
        if($total_search_emails != 0 && $email_per_google != 0){
            $google_success_rate = number_format(($email_per_google*100)/$total_search_emails,2);
        }
        if($total_search_emails != 0 && $email_per_bing != 0){
            $bing_success_rate = number_format(($email_per_bing*100)/$total_search_emails,2);
        }     

        $data['google_success_rate'] = $google_success_rate;
        $data['google_found_email'] = count($total_google_search_email);
        $data['bing_success_rate'] = $bing_success_rate;
        $data['bing_found_email'] = count($total_bing_search_email);
        

            // bar chart
        $year = date('Y')-1;
        $last_scraped_year = date("$year-m-d");

        $simple_where_bar_chart['email.user_id'] = $user_id;
        $simple_where_bar_chart["date_format(last_scraped_time,'%Y-%m-%d') >="] = $last_scraped_year;
        $where_bar_chart = array('where'=>$simple_where_bar_chart);
        $order_by = "search_engine_search.last_scraped_time ASC";
        $join = array('search_engine_search' => 'email.search_engine_url_id=search_engine_search.id,left');
        $results = $this->basic->get_data('email', $where_bar_chart, $select=array('distinct(found_email)','search_engine_search.*'), $join, $limit='', $start=null, $order_by);
        
        $issued = 0;
        $returned = 0;
        $month_year_array=array();

        foreach ($results as $result) {
            $scraped_month = date('M', strtotime($result['last_scraped_time']));
            $scraped_year = date('Y', strtotime($result['last_scraped_time']));            
			
			if (!isset($google[$scraped_month][$scraped_year])) {
                $google[$scraped_month][$scraped_year]=0;
            }
			
			if (!isset($bing[$scraped_month][$scraped_year])) {
				 $bing[$scraped_month][$scraped_year]=0;
			}			  
			  
            if (isset($google[$scraped_month][$scraped_year])) {
                if($result['search_engine_name']=="Google")
                    $google[$scraped_month][$scraped_year] += 1;
                if($result['search_engine_name']=="Bing")
                    $bing[$scraped_month][$scraped_year] += 1;
            }
        }

        $chart_array=array();

        $cur_year=date('Y');
        $cur_month=date('m');
        $cur_month=(int)$cur_month;
        $months_name = array(1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'May', 6=>'Jun', 7=>'Jul', 8=>'Aug', 9=>'Sep', 10=>'Oct', 11=>'Nov', 12=>'Dec');
        // $months_name_full = array(1=>'January', 2=>'February', 3=>'March', 4=>'April', 5=>'May', 6=>'June', 7=>'July', 8=>'August', 9=>'September', 10=>'October', 11=>'November', 12=>'December');

        for ($i=0;$i<=11;$i++) {
            $m=$months_name[$cur_month];
            $m_dis=$months_name[$cur_month];
            $chart_array[$i]['year']=$m_dis."-".$cur_year;

            if (isset($google[$m][$cur_year])) {
                $chart_array[$i]['google']=$google[$m][$cur_year];
            } else {
                $chart_array[$i]['google']=0;
            }
            if (isset($bing[$m][$cur_year])) {
                $chart_array[$i]['bing']=$bing[$m][$cur_year];
            } else {
                $chart_array[$i]['bing']=0;
            }

            $cur_month=$cur_month-1;
            if ($cur_month==0) {
                $cur_month=12;
                $cur_year=$cur_year-1;
            }
        }

        $chart_array=array_reverse($chart_array);
        $data['bar'] = $chart_array;



        if($this->session->userdata("user_type")=="Member")
        {
            $package_info=$this->session->userdata("package_info");              
            $package_name="No Package";
            if(isset($package_info["package_name"]))  $package_name=$package_info["package_name"];
            $validity="0";
            if(isset($package_info["validity"]))  $validity=$package_info["validity"];
            $price="0";
            if(isset($package_info["price"]))  $price=$package_info["price"];
            $data['package_name']=$package_name;
            $data['validity']=$validity;
            $data['price']=$price;
        }
        $data['payment_config']=$this->basic->get_data('payment_config');

        

        // echo "<pre>"; print_r($bing_success_rate); exit();
        $this->_viewcontroller($data);
    }

    /**
    * dashboard method to show statistics
    * @access public
    * @return void
    */

    public function member_panel()
    {
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Member') {
            redirect('admin/index', 'location');
        }
        // $data['body'] = 'admin/member_panel';
        // $this->_viewcontroller($data);
        $this->website_search();
    }

    public function notify_members()
    {
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') {
            redirect('admin/member_panel', 'location');
        }

        $data['body'] = 'admin/notify_members';
        $data['page_title'] = 'Notify Member';
        $this->_viewcontroller($data);
    }

    public function notify_members_data_loader()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 15;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';
        $order_by_str = $sort." ".$order;

        // setting properties for search
        $first_name = trim($this->input->post('first_name', true));

        $is_searched= $this->input->post('is_searched', true);


        if ($is_searched) {
            // if search occured, saving user input data to session. name of method is important before field
            $this->session->set_userdata('notify_member_first_name', $first_name);
        }
        // saving session data to different search parameter variables
        $search_first_name = $this->session->userdata('notify_member_first_name');

        // creating a blank where_simple array
        $where_simple = array();

        // trimming data
        if ($search_first_name) {
            $where_simple['name like'] = $search_first_name."%";
        }

        $where_simple['deleted'] = '0';
        // $where_simple['user_type !='] = 'Admin';

        $where = array('where' => $where_simple);
        $offset = ($page-1)*$rows;
        $result = array();

        $table = "users";
        $info = $this->basic->get_data($table, $where, $select = '', $join='', $limit = $rows, $start = $offset, $order_by = $order_by_str);

        $total_rows_array = $this->basic->count_row($table, $where, $count = "id");
        $total_result = $total_rows_array[0]['total_rows'];

        echo convert_to_grid_data($info, $total_result);
    }


    /**
    * user_management method to manage user
    * @access public
    * @return void
    */

    public function user_management()
    {
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') {
            redirect('admin/member_panel', 'location');
        }
        
        $this->load->database();
        $this->load->library('grocery_CRUD');
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('users');
        $crud->order_by('id');
        $crud->where('users.deleted', '0');
        $crud->set_subject('User');
        $crud->set_relation('package_id','package','package_name',array('package.deleted' => '0'));

        $crud->fields('name', 'email', 'mobile', 'password', 'address', 'user_type', 'status');

        $crud->edit_fields('name', 'email', 'mobile', 'address', 'user_type','expired_date','package_id', 'status');

        $crud->add_fields('name', 'email', 'mobile', 'password', 'address', 'user_type', 'status');

        $crud->required_fields('name', 'email', 'mobile', 'password', 'address', 'user_type', 'expired_date','package_id','status');

        $crud->columns('name', 'email', 'mobile', 'address', 'expired_date','package_id','status', 'user_type');

        $crud->field_type('expired_date', 'input');

        $crud->display_as('name', 'Name');
        $crud->display_as('email', 'Email');
        $crud->display_as('mobile', 'Mobile');
        $crud->display_as('address', 'Address');
        $crud->display_as('status', 'Status');
        $crud->display_as('user_type', 'User Type');        
        $crud->display_as('package_id', $this->lang->line('package name'));
        $crud->display_as('expired_date', $this->lang->line('expired date')." : yyyy-mm-dd ");

       
        $crud->set_rules("email","Email",'callback_unique_email_check['.$this->uri->segment(4).']');


        $images_url = base_url("plugins/grocery_crud/themes/flexigrid/css/images/password.png");
        $crud->add_action('Change User Password', $images_url, 'admin/change_user_password');

        $crud->callback_column('status', array($this, 'status_display_crud'));
        $crud->callback_field('status', array($this, 'status_field_crud'));

        $crud->callback_after_insert(array($this, 'encript_password'));
       
        
        $crud->unset_read();
        $crud->unset_print();
        $crud->unset_export();

        $output = $crud->render();
        $data['output']=$output;
        $data['page_title'] = 'User Management';
        $data['crud']=1;

        $this->_viewcontroller($data);
    }

    function unique_email_check($str, $edited_id)
    {
        $email= strip_tags(trim($this->input->post('email',TRUE)));
        if($email==""){
            $s="<b>Email</b> is required.";
            $this->form_validation->set_message('unique_email_check', $s);
            return FALSE;
        }
        
        if(!isset($edited_id) || !$edited_id)
            $where=array("email"=>$email);
        else        
            $where=array("email"=>$email,"id !="=>$edited_id);
        
        
        $is_unique=$this->basic->is_unique("users",$where,$select='');
        
        if (!$is_unique) {
             $s="<b>Email</b> is already used.";
            $s="<b>".$this->lang->line("email")."</b> ".$s;
            $this->form_validation->set_message('unique_email_check', $s);
            return FALSE;
            }
                
        return TRUE;
    }

    /**
    * method to display status
    * @access public
    * @param int
    * @param array
    * @return string
    */


    public function status_display_crud($value, $row)
    {
        if ($value == 1) {
            return "<span class='label label-success'>Active</sapn>";
        } else {
            return "<span class='label label-warning'>Inactive</sapn>";
        }
    }

    /**
    * method to status display
    * @access public
    * @param int
    * @param array
    * @return string
    */

    public function status_field_crud($value, $row)
    {
        if ($value == '') {
            $value = 1;
        }
        return form_dropdown('status', array(0 => 'Inactive', 1 => 'Active'), $value, 'class="form-control" id="field-status"');
    }


    /**
    * method to encrypt password
    * @access public    
    * @param array
    * @param int
    * @return true
    */

    public function encript_password($post_array, $primary_key)
    {
        $id = $primary_key;
        $where = array('id'=>$id);
        $password = md5($post_array['password']);
        $table = 'users';
        $data = array('password'=>$password);
        $this->basic->update_data($table, $where, $data);
        return true;
    }


    /**
    * method to change user password
    * @access public       
    * @param int
    * @return void
    */

    public function change_user_password($id)
    {
        $this->session->set_userdata('change_user_password_id', $id);

        $table = 'users';
        $where['where'] = array('id' => $id);

        $info = $this->basic->get_data($table, $where);

        $data['user_name'] = $info[0]['name'];

        $data['body'] = 'admin/change_user_password';
        $data['page_title'] = "Password Change";
        $this->_viewcontroller($data);
    }

    /**
    * method to change user password action
    * @access public       
    * @return void
    */

    public function change_user_password_action()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }
        $id = $this->session->userdata('change_user_password_id');
        // $this->session->unset_userdata('change_member_password_id');
        if ($_POST) {
            $this->form_validation->set_rules('password', '<b>Password</b>', 'trim|required');
            $this->form_validation->set_rules('confirm_password', '<b>Confirm Password</b>', 'trim|required|matches[password]');
        }
        if ($this->form_validation->run() == false) {
            $this->change_user_password($id);
        } else {
            $new_password = $this->input->post('password', true);
            $new_confirm_password = $this->input->post('confirm_password', true);

            $table_change_password = 'users';
            $where_change_passwor = array('id' => $id);
            $data = array('password' => md5($new_password));
            $this->basic->update_data($table_change_password, $where_change_passwor, $data);

            $where['where'] = array('id' => $id);
            $mail_info = $this->basic->get_data('users', $where);
            
            $name = $mail_info[0]['name'];
            $to = $mail_info[0]['email'];
            $password = $new_password;

            $subject = 'Change Password Notification';
            $mask = $this->config->item('product_name');
            $from = $this->config->item('institute_email');
            $url = site_url();

            $message = "Dear {$name}, Your <a href='".$url."'>{$mask}</a> password has been changed. Your new password is: {$password}. Thank you.";
            $this->_mail_sender($from, $to, $subject, $message, $mask);
            $this->session->set_flashdata('success_message', 1);
                // return $this->config_member();
            redirect('admin/user_management', 'location');
        }
    }

    /**
    * method to load website search view page
    * @access public       
    * @return void
    */

    public function website_search()
    {
        $data['body'] = 'admin/website_search';
        $data['page_title'] = 'Crawl Website';
        $this->_viewcontroller($data);
    }


    /**
    * method to load website search data 
    * @access public      
    * @return void
    */
    public function website_search_data()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }
            // setting variables for pagination
        $page = isset($_POST['page']) ? intval($_POST['page']) : 15;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'domain.id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';

        $domain_name      = trim($this->input->post("domain_name", true));
        $user_name      = trim($this->input->post("user_name", true));
        $from_date = trim($this->input->post('from_date', true));
        if($from_date)
            $from_date = date('Y-m-d', strtotime($from_date));

        $to_date = trim($this->input->post('to_date', true));
        if($to_date)
            $to_date = date('Y-m-d', strtotime($to_date));


            // setting a new properties for $is_searched to set session if search occured
        $is_searched = $this->input->post('is_searched', true);


        if ($is_searched) {
            // if search occured, saving user input data to session. name of method is important before field

            $this->session->set_userdata('website_search_domain_name',      $domain_name);
            $this->session->set_userdata('website_search_user_name',      $user_name);
            $this->session->set_userdata('website_search_from_date',        $from_date);
            $this->session->set_userdata('website_search_to_date',        $to_date);
            //	$this->session->set_userdata('book_list_category',$category_id);
        }

            // saving session data to different search parameter variables

        $search_domain_name      = $this->session->userdata('website_search_domain_name');
        $search_user_name      = $this->session->userdata('website_search_user_name');
        $search_from_date      = $this->session->userdata('website_search_from_date');
        $search_to_date      = $this->session->userdata('website_search_to_date');
       //	$search_category=$this->session->userdata('book_list_category');

        // creating a blank where_simple array
        $where_simple=array();

      // trimming data

        
        if ($search_domain_name) {
            $where_simple['domain.domain_name like ']    = "%".$search_domain_name."%";
        }

        if ($search_user_name) {
            $where_simple['users.name like']   = "%".$search_user_name."%";
        }

        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["Date_Format(domain.last_scraped_time,'%Y-%m-%d') >="]= $search_from_date;
            }
        }
        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["Date_Format(domain.last_scraped_time,'%Y-%m-%d') <="]=$search_to_date;
            }
        }
        $where_simple['domain.user_id'] = $this->user_id;
        // $where_simple['domain.deleted'] = "0";
        
        $where  = array('where'=>$where_simple);

        $order_by_str=$sort." ".$order;

        $offset = ($page-1)*$rows;
        $result = array();

        $table = "domain";
        $select = array('domain.id','domain.id as domain_id','count(email.found_email) as found_email','domain.domain_name','domain.last_scraped_time','domain.is_available');
        $join = array(
            "email" => "email.domain_id=domain.id,left",
            );

        $info = $this->basic->get_data($table, $where, $select, $join, $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='domain.id');



        $total_rows_array = $this->basic->count_row($table, $where, $count="domain.id", $join, $group_by='domain.id');
        

        $total_result = $total_rows_array[0]['total_rows'];

        echo convert_to_grid_data($info, $total_result);
    }


    /**
    * method to view website wise details
    * @access public      
    * @return void
    */
    public function website_wise_details()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $domain_id = trim($this->input->post('domain_id'));

        $where['where'] = array('domain.id'=>$domain_id);
        $table = 'domain';
        $join = array("email" => "email.domain_id=domain.id,left");

        $info = $this->basic->get_data($table, $where, $select ='', $join);

        $str = "<h3 class = 'text-info' style='margin-top:0px !important'>Emails from {$info[0]['domain_name']}</h3>
        <div class='table-responsive'>
		<table class='table table-hover'>
		<tr>
			<th>SL</th>
			<th>Email Address</th>			
		</tr>";

        $row_count = 1;

        foreach ($info as $detail) {
            $str .= "<tr><td>".$row_count."</td><td>".$detail['found_email']."</td></tr>";
            $row_count++;
        }
        $str .= "</table></div>";

        echo $str;
    }

    /**
    * method to perform website wise download
    * @access public  
    * @return void
    */

    public function website_wise_download()
    {
        $table = 'domain';
        $join = array(
            "email" => "email.domain_id=domain.id,left"
            );

        $selected_grid_data = $this->input->post('info', true);
        $domain_names = json_decode($selected_grid_data, true);
        $domain_names_array = array();
        foreach ($domain_names as  $value) {
            $domain_names_array[] = $value['domain_name'];
        }
        $where['where_in'] = array('domain_name' => $domain_names_array);
		$where_simple['domain.user_id'] = $this->user_id;
        $where  = array('where'=>$where_simple);
		
		

        $info = $this->basic->get_data($table, $where, $select ='', $join, $limit='', $start=null, $order_by='domain_name asc');

        $fp = fopen("download/report/website_wise_email_{$this->user_id}_{$this->download_id}.csv", "w");
        $head=array("Domain Name","Last Scraped Time", "Email");
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) {
            $write_info['domain_name'] = $value['domain_name'];
            $write_info['last_scraped_time'] = $value['last_scraped_time'];
            $write_info['email'] = $value['found_email'];

            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/report/website_wise_email_{$this->user_id}_{$this->download_id}.csv";
        echo $file_name;
    }

    /**
    * method to search url view page
    * @access public        
    * @return true
    */

    public function url_search()
    {
        $data['body'] = 'admin/url_search';
        $data['page_title'] = 'Crawl URL';
        $this->_viewcontroller($data);
    }

     /**
    * method to status display data
    * @access public    
    * @return void
    */
    public function url_search_data()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }
            // setting variables for pagination
        $page    = isset($_POST['page']) ? intval($_POST['page']) : 15;
        $rows    = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort    = isset($_POST['sort']) ? strval($_POST['sort']) : 'email.id';
        $order    = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';

        $url        =   trim($this->input->post("url", true));
        $user_name  =    trim($this->input->post("user_name", true));

        $from_date    =    trim($this->input->post('from_date', true));
        if($from_date)
            $from_date    =    date('Y-m-d', strtotime($from_date));

        $to_date    =    trim($this->input->post('to_date', true));
        if($to_date)
            $to_date    =    date('Y-m-d', strtotime($to_date));


            // setting a new properties for $is_searched to set session if search occured
        $is_searched = $this->input->post('is_searched', true);


        if ($is_searched) {
            // if search occured, saving user input data to session. name of method is important before field

            $this->session->set_userdata('url_search_url', $url);
            $this->session->set_userdata('url_search_user_name', $user_name);
            $this->session->set_userdata('url_search_from_date', $from_date);
            $this->session->set_userdata('url_search_to_date', $to_date);
            //	$this->session->set_userdata('book_list_category',$category_id);
        }

            // saving session data to different search parameter variables

        $search_url          = $this->session->userdata('url_search_url');
        $search_user_name    = $this->session->userdata('url_search_user_name');
        $search_from_date    = $this->session->userdata('url_search_from_date');
        $search_to_date      = $this->session->userdata('url_search_to_date');
       //	$search_category=$this->session->userdata('book_list_category');

        // creating a blank where_simple array
        $where_simple=array();

      // trimming data

        
        if ($search_url) {
            $where_simple['url.url_name like ']    = "%".$search_url."%";
        }

        if ($search_user_name) {
            $where_simple['users.name like']   = "%".$search_user_name."%";
        }

        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["Date_Format(url.last_scraped_time,'%Y-%m-%d') >="]= $search_from_date;
            }
        }
        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["Date_Format(url.last_scraped_time,'%Y-%m-%d') <="]=$search_to_date;
            }
        }

        $where_simple['url.user_id'] = $this->user_id;
        $where  = array('where'=>$where_simple);

        $order_by_str=$sort." ".$order;

        $offset = ($page-1)*$rows;
        $result = array();

        $table = "url";
        $select = array('url.id','email.id as email_id','count(email.found_email) as found_email','url.url_name','url.last_scraped_time','url.is_available');
        $join = array(
            "email" => "email.url_id=url.id,left"
            );

        $info = $this->basic->get_data($table, $where, $select, $join, $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='url.id');

        $total_rows_array = $this->basic->count_row($table, $where, $count="url.id", $join, $group_by='url.id');

        $total_result = $total_rows_array[0]['total_rows'];

        echo convert_to_grid_data($info, $total_result);
    }

     /**
    * method to load url wise detaisl
    * @access public  
    * @return void
    */

    public function url_wise_details()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $url_id = trim($this->input->post('url_id'));

        $where['where'] = array('url.id'=>$url_id);
        $table = 'url';
        $join = array("email" => "email.url_id=url.id,left");

        $info = $this->basic->get_data($table, $where, $select ='', $join);

        $str = "<h3 class = 'text-center text-info'>URL: {$info[0]['url_name']}</h3>
        <div class='table-responsive'>
		<table class='table table-hover'>
		<tr>
			<th>SL</th>
			<th>Email Address</th>			
		</tr>";

        $row_count = 1;

        foreach ($info as $detail) {
            if (!empty($detail['found_email'])) {
                $str .= "<tr><td>".$row_count."</td><td>".$detail['found_email']."</td></tr>";
                $row_count++;
            }
        }
        $str .= "</table></div>";

        echo $str;
    }


     /**
    * method to url wise download
    * @access public   
    * @return void
    */
    public function url_wise_download()
    {
        $table = 'url';
        $join = array("email" => "email.url_id=url.id,left","users" => "email.user_id = users.id,left");

        $selected_grid_data = $this->input->post('info', true);
        $url_names = json_decode($selected_grid_data, true);
        $url_names_array = array();
        foreach ($url_names as  $value) {
            $url_names_array[] = $value['url_name'];
        }
        $where['where_in'] = array('url_name' => $url_names_array);
		
		 $where_simple['url.user_id'] = $this->user_id;
         $where  = array('where'=>$where_simple);
		 

        $info = $this->basic->get_data($table, $where, $select ='', $join, $limit='', $start=null, $order_by='url_name asc');

        $fp = fopen("download/report/url_wise_email_{$this->user_id}_{$this->download_id}.csv", "w");
        $head=array("URL Name","Last Scraped Time", "User Name", "Email");
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) {
            $write_info['url_name'] = $value['url_name'];
            $write_info['last_scraped_time'] = $value['last_scraped_time'];
            $write_info['user_name'] = $value['name'];
            $write_info['email'] = $value['found_email'];

            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/report/url_wise_email_{$this->user_id}_{$this->download_id}.csv";
        echo $file_name;
    }

     /**
    * method to url wise download
    * @access public   
    * @return void
    */
    public function url_with_email_wise_download()
    {
         $table = 'url';
        $join = array("email" => "email.url_id=url.id,left","users" => "email.user_id = users.id,left");

        $selected_grid_data = $this->input->post('info', true);
        $url_names = json_decode($selected_grid_data, true);
        $url_names_array = array();
        foreach ($url_names as  $value) {
            $url_names_array[] = $value['url_name'];
        }
        $where['where_in'] = array('url_name' => $url_names_array);
		
		 $where_simple['url.user_id'] = $this->user_id;
         $where  = array('where'=>$where_simple);
		
		
		$select=array("url_name","group_concat(DISTINCT cast(found_email as char) separator ',') as found_email","last_scraped_time");
		
        $info = $this->basic->get_data($table, $where, $select, $join, $limit='', $start=null, $order_by='url.id DESC',$group_by="url.id");
		
		

        $fp = fopen("download/report/url_wise_email_{$this->user_id}_{$this->download_id}.csv", "w");
        $head=array("URL Name","Last Scraped Time","Email");
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) {
			$write_info=array();
			
            $write_info[] = $value['url_name'];
            $write_info[] = $value['last_scraped_time'];
            $found_email_str=$value['found_email'];
			
			$found_email_array=explode(",",$found_email_str);
			foreach($found_email_array as $email){
				$write_info[]=$email;
			}
			
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/report/url_wise_email_{$this->user_id}_{$this->download_id}.csv";
        echo $file_name;
    }


    public function get_country_names()
    {
        $array_countries = array (
          'AF' => 'AFGHANISTAN',
          'AX' => 'ÅLAND ISLANDS',
          'AL' => 'ALBANIA',
          
          'DZ' => 'ALGERIA (El Djazaïr)',
          'AS' => 'AMERICAN SAMOA',
          'AD' => 'ANDORRA',
          'AO' => 'ANGOLA',
          'AI' => 'ANGUILLA',
          'AQ' => 'ANTARCTICA',
          'AG' => 'ANTIGUA AND BARBUDA',
          'AR' => 'ARGENTINA',
          'AM' => 'ARMENIA',
          'AW' => 'ARUBA',
          
          'AU' => 'AUSTRALIA',
          'AT' => 'AUSTRIA',
          'AZ' => 'AZERBAIJAN',
          'BS' => 'BAHAMAS',
          'BH' => 'BAHRAIN',
          'BD' => 'BANGLADESH',
          'BB' => 'BARBADOS',
          'BY' => 'BELARUS',
          'BE' => 'BELGIUM',
          'BZ' => 'BELIZE',
          'BJ' => 'BENIN',
          'BM' => 'BERMUDA',
          'BT' => 'BHUTAN',
          'BO' => 'BOLIVIA',
          
          'BA' => 'BOSNIA AND HERZEGOVINA',
          'BW' => 'BOTSWANA',
          'BV' => 'BOUVET ISLAND',
          'BR' => 'BRAZIL',

          'BN' => 'BRUNEI DARUSSALAM',
          'BG' => 'BULGARIA',
          'BF' => 'BURKINA FASO',
          'BI' => 'BURUNDI',
          'KH' => 'CAMBODIA',
          'CM' => 'CAMEROON',
          'CA' => 'CANADA',
          'CV' => 'CAPE VERDE',
          'KY' => 'CAYMAN ISLANDS',
          'CF' => 'CENTRAL AFRICAN REPUBLIC',
          'CD' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE (formerly Zaire)',
          'CL' => 'CHILE',
          'CN' => 'CHINA',
          'CX' => 'CHRISTMAS ISLAND',
          
          'CO' => 'COLOMBIA',
          'KM' => 'COMOROS',
          'CG' => 'CONGO, REPUBLIC OF',
          'CK' => 'COOK ISLANDS',
          'CR' => 'COSTA RICA',
          'CI' => 'CÔTE D\'IVOIRE (Ivory Coast)',
          'HR' => 'CROATIA (Hrvatska)',
          'CU' => 'CUBA',
          'CW' => 'CURAÇAO',
          'CY' => 'CYPRUS',
          'CZ' => 'ZECH REPUBLIC',
          'DK' => 'DENMARK',
          'DJ' => 'DJIBOUTI',
          'DM' => 'DOMINICA',
          'DC' => 'DOMINICAN REPUBLIC',
          'EC' => 'ECUADOR',
          'EG' => 'EGYPT',
          'SV' => 'EL SALVADOR',
          'GQ' => 'EQUATORIAL GUINEA',
          'ER' => 'ERITREA',
          'EE' => 'ESTONIA',
          'ET' => 'ETHIOPIA',
          'FO' => 'FAEROE ISLANDS',

          'FJ' => 'FIJI',
          'FI' => 'FINLAND',
          'FR' => 'FRANCE',
          'GF' => 'FRENCH GUIANA',
          
          'GA' => 'GABON',
          'GM' => 'GAMBIA, THE',
          'GE' => 'GEORGIA',
          'DE' => 'GERMANY (Deutschland)',
          'GH' => 'GHANA',
          'GI' => 'GIBRALTAR',
          'GB' => 'UNITED KINGDOM',
          'GR' => 'GREECE',
          'GL' => 'GREENLAND',
          'GD' => 'GRENADA',
          'GP' => 'GUADELOUPE',
          'GU' => 'GUAM',
          'GT' => 'GUATEMALA',
          'GG' => 'GUERNSEY',
          'GN' => 'GUINEA',
          'GW' => 'GUINEA-BISSAU',
          'GY' => 'GUYANA',
          'HT' => 'HAITI',
          
          'HN' => 'HONDURAS',
          'HK' => 'HONG KONG (Special Administrative Region of China)',
          'HU' => 'HUNGARY',
          'IS' => 'ICELAND',
          'IN' => 'INDIA',
          'ID' => 'INDONESIA',
          'IR' => 'IRAN (Islamic Republic of Iran)',
          'IQ' => 'IRAQ',
          'IE' => 'IRELAND',
          'IM' => 'ISLE OF MAN',
          'IL' => 'ISRAEL',
          'IT' => 'ITALY',
          'JM' => 'JAMAICA',
          'JP' => 'JAPAN',
          'JE' => 'JERSEY',
          'JO' => 'JORDAN (Hashemite Kingdom of Jordan)',
          'KZ' => 'KAZAKHSTAN',
          'KE' => 'KENYA',
          'KI' => 'KIRIBATI',
          'KP' => 'KOREA (Democratic Peoples Republic of [North] Korea)',
          'KR' => 'KOREA (Republic of [South] Korea)',
          'KW' => 'KUWAIT',
          'KG' => 'KYRGYZSTAN',
          
          'LV' => 'LATVIA',
          'LB' => 'LEBANON',
          'LS' => 'LESOTHO',
          'LR' => 'LIBERIA',
          'LY' => 'LIBYA (Libyan Arab Jamahirya)',
          'LI' => 'LIECHTENSTEIN (Fürstentum Liechtenstein)',
          'LT' => 'LITHUANIA',
          'LU' => 'LUXEMBOURG',
          'MO' => 'MACAO (Special Administrative Region of China)',
          'MK' => 'MACEDONIA (Former Yugoslav Republic of Macedonia)',
          'MG' => 'MADAGASCAR',
          'MW' => 'MALAWI',
          'MY' => 'MALAYSIA',
          'MV' => 'MALDIVES',
          'ML' => 'MALI',
          'MT' => 'MALTA',
          'MH' => 'MARSHALL ISLANDS',
          'MQ' => 'MARTINIQUE',
          'MR' => 'MAURITANIA',
          'MU' => 'MAURITIUS',
          'YT' => 'MAYOTTE',
          'MX' => 'MEXICO',
          'FM' => 'MICRONESIA (Federated States of Micronesia)',
          'MD' => 'MOLDOVA',
          'MC' => 'MONACO',
          'MN' => 'MONGOLIA',
          'ME' => 'MONTENEGRO',
          'MS' => 'MONTSERRAT',
          'MA' => 'MOROCCO',
          'MZ' => 'MOZAMBIQUE (Moçambique)',
          'MM' => 'MYANMAR (formerly Burma)',
          'NA' => 'NAMIBIA',
          'NR' => 'NAURU',
          'NP' => 'NEPAL',
          'NL' => 'NETHERLANDS',
          'AN' => 'NETHERLANDS ANTILLES (obsolete)',
          'NC' => 'NEW CALEDONIA',
          'NZ' => 'NEW ZEALAND',
          'NI' => 'NICARAGUA',
          'NE' => 'NIGER',
          'NG' => 'NIGERIA',
          'NU' => 'NIUE',
          'NF' => 'NORFOLK ISLAND',
          'MP' => 'NORTHERN MARIANA ISLANDS',
          'ND' => 'NORWAY',
          'OM' => 'OMAN',
          'PK' => 'PAKISTAN',
          'PW' => 'PALAU',
          'PS' => 'PALESTINIAN TERRITORIES',
          'PA' => 'PANAMA',
          'PG' => 'PAPUA NEW GUINEA',
          'PY' => 'PARAGUAY',
          'PE' => 'PERU',
          'PH' => 'PHILIPPINES',
          'PN' => 'PITCAIRN',
          'PL' => 'POLAND',
          'PT' => 'PORTUGAL',
          'PR' => 'PUERTO RICO',
          'QA' => 'QATAR',
          'RE' => 'RÉUNION',
          'RO' => 'ROMANIA',
          'RU' => 'RUSSIAN FEDERATION',
          'RW' => 'RWANDA',
          'BL' => 'SAINT BARTHÉLEMY',
          'SH' => 'SAINT HELENA',
          'KN' => 'SAINT KITTS AND NEVIS',
          'LC' => 'SAINT LUCIA',
          
          'PM' => 'SAINT PIERRE AND MIQUELON',
          'VC' => 'SAINT VINCENT AND THE GRENADINES',
          'WS' => 'SAMOA (formerly Western Samoa)',
          'SM' => 'SAN MARINO (Republic of)',
          'ST' => 'SAO TOME AND PRINCIPE',
          'SA' => 'SAUDI ARABIA (Kingdom of Saudi Arabia)',
          'SN' => 'SENEGAL',
          'RS' => 'SERBIA (Republic of Serbia)',
          'SC' => 'SEYCHELLES',
          'SL' => 'SIERRA LEONE',
          'SG' => 'SINGAPORE',
          'SX' => 'SINT MAARTEN',
          'SK' => 'SLOVAKIA (Slovak Republic)',
          'SI' => 'SLOVENIA',
          'SB' => 'SOLOMON ISLANDS',
          'SO' => 'SOMALIA',
          'ZA' => 'ZAMBIA (formerly Northern Rhodesia)',
          'GS' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
          'SS' => 'SOUTH SUDAN',
          'ES' => 'SPAIN (España)',
          'LK' => 'SRI LANKA (formerly Ceylon)',
          'SD' => 'SUDAN',
          'SR' => 'SURINAME',
          'SJ' => 'SVALBARD AND JAN MAYE',
          'SZ' => 'SWAZILAND',
          'SE' => 'SWEDEN',
          'CH' => 'SWITZERLAND (Confederation of Helvetia)',
          'SY' => 'SYRIAN ARAB REPUBLIC',
          'TW' => 'TAIWAN ("Chinese Taipei" for IOC)',
          'TJ' => 'TAJIKISTAN',
          'TZ' => 'TANZANIA',
          'TH' => 'THAILAND',
          'TL' => 'TIMOR-LESTE (formerly East Timor)',
          'TG' => 'TOGO',
          'TK' => 'TOKELAU',
          'TO' => 'TONGA',
          'TT' => 'TRINIDAD AND TOBAGO',
          'TN' => 'TUNISIA',
          'TR' => 'TURKEY',
          'TM' => 'TURKMENISTAN',
          'TC' => 'TURKS AND CAICOS ISLANDS',
          'TV' => 'TUVALU',
          'UG' => 'UGANDA',
          'UA' => 'UKRAINE',
          'AE' => 'UNITED ARAB EMIRATES',
          'US' => 'UNITED STATES',
          'UM' => 'UNITED STATES MINOR OUTLYING ISLANDS',
          'UY' => 'URUGUAY',
          'UZ' => 'UZBEKISTAN',
          'VU' => 'VANUATU',
          'VA' => 'VATICAN CITY (Holy See)',
          'VN' => 'VIET NAM',
          'VG' => 'VIRGIN ISLANDS, BRITISH',
          'VI' => 'VIRGIN ISLANDS, U.S.',
          'WF' => 'WALLIS AND FUTUNA',
          'EH' => 'WESTERN SAHARA (formerly Spanish Sahara)',
          'YE' => 'YEMEN (Yemen Arab Republic)',
          'ZW' => 'ZIMBABWE'
        );
        return $array_countries;
    }

    public function get_language_names()
    {
        $array_languages = array(
        'ar-XA'=>'Arabic',
        'bg'=>'Bulgarian',
        'hr'=>'Croatian',
        'cs'=>'Czech',
        'da'=>'Danish',
        'de'=>'German',
        'el'=>'Greek',
        'en'=>'English',
        'et'=>'Estonian',
        'es'=>'Spanish',
        'fi'=>'Finnish',
        'fr'=>'French',
        'ga'=>'Irish',
        'hr'=>'Hindi',
        'hu'=>'Hungarian',
        'he'=>'Hebrew',
        'ja'=>'Japanese',
        'ko'=>'Korean',
        'lv'=>'Latvian',
        'lt'=>'Lithuanian',
        'nl'=>'Dutch',
        'no'=>'Norwegian',
        'pl'=>'Polish',
        'pt'=>'Portuguese',
        'sv'=>'Swedish',
        'ro'=>'Romanian',
        'ru'=>'Russian',
        'sr-CS'=>'Serbian',
        'sk'=>'Slovak',
        'sl'=>'Slovenian',
        'th'=>'Thai',
        'tr'=>'Turkish',
        'uk-UA'=>'Ukrainian',
        'zh-chs'=>'Chinese (Simplified)',
        'zh-cht'=>'Chinese (Traditional)'
        );
        return $array_languages;
    }

     /**
    * method to search search engine
    * @access public    
    * @return string
    */

    public function searchengine_search()
    {
        $data['body'] = 'admin/searchengine_search';
        $data['page_title'] = 'Searchengine Search';
        $data['social_network'] = $this->get_social_networks();
        $data['email_provider'] = $this->get_email_providers();
        $data['searh_engine'] = $this->get_searche_engines();
        $data['country_name'] = $this->get_country_names();
        $data['language_name'] = $this->get_language_names();
        $this->_viewcontroller($data);
    }

     /**
    * method to load search engine search data
    * @access public    
    * @return void
    */
    public function searchengine_search_data()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }
        $page    = isset($_POST['page']) ? intval($_POST['page']) : 15;
        $rows    = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort    = isset($_POST['sort']) ? strval($_POST['sort']) : 'search_engine_search.id';
        $order    = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';

        $keyword            =   trim($this->input->post("keyword", true));
        $user_name          =    trim($this->input->post("user_name", true));
        $searchengine          =    trim($this->input->post("searchengine", true));
        $social_network     =    trim($this->input->post("social_network", true));

        $from_date            =    trim($this->input->post('from_date', true));
        if($from_date)
            $from_date            =    date('Y-m-d', strtotime($from_date));


        $to_date            =    trim($this->input->post('to_date', true));
        if($to_date)
            $to_date            =    date('Y-m-d', strtotime($to_date));


            // setting a new properties for $is_searched to set session if search occured
        $is_searched = $this->input->post('is_searched', true);


        if ($is_searched) {
            // if search occured, saving user input data to session. name of method is important before field

            $this->session->set_userdata('searchengine_search_keyword', $keyword);
            $this->session->set_userdata('searchengine_search_user_name', $user_name);
            $this->session->set_userdata('searchengine_search_searchengine', $searchengine);
            $this->session->set_userdata('searchengine_search_social_network', $social_network);
            $this->session->set_userdata('searchengine_search_from_date', $from_date);
            $this->session->set_userdata('searchengine_search_to_date', $to_date);
            //	$this->session->set_userdata('book_list_category',$category_id);
        }

            // saving session data to different search parameter variables

        $search_keyword         = $this->session->userdata('searchengine_search_keyword');
        $search_user_name         = $this->session->userdata('searchengine_search_user_name');
        $search_searchengine     = $this->session->userdata('searchengine_search_searchengine');
        $search_social_network  = $this->session->userdata('searchengine_search_social_network');
        $search_from_date        = $this->session->userdata('searchengine_search_from_date');
        $search_to_date          = $this->session->userdata('searchengine_search_to_date');
       //	$search_category=$this->session->userdata('book_list_category');

        // creating a blank where_simple array
        $where_simple=array();

      // trimming data

        
        if ($search_keyword) {
            $where_simple['search_engine_search.search_keyword like ']    = "%".$search_keyword."%";
        }

        if ($search_user_name) {
            $where_simple['users.name like ']    = "%".$search_user_name."%";
        }

        if (!empty($search_searchengine)) {
            $where_simple['search_engine_search.search_engine_name like ']    = "%".$search_searchengine."%";
        }

        if ($search_social_network) {
            $where_simple['search_engine_search.search_in like ']    = "%".$search_social_network."%";
        }

        if ($search_from_date) {
            if ($search_from_date != '1970-01-01') {
                $where_simple["Date_Format(search_engine_search.last_scraped_time,'%Y-%m-%d') >="]= $search_from_date;
            }
        }
        if ($search_to_date) {
            if ($search_to_date != '1970-01-01') {
                $where_simple["Date_Format(search_engine_search.last_scraped_time,'%Y-%m-%d') <="]=$search_to_date;
            }
        }

        $where_simple['search_engine_search.user_id'] = $this->user_id;
        $where  = array('where'=>$where_simple);

        $order_by_str=$sort." ".$order;

        $offset = ($page-1)*$rows;
        $result = array();

        $table = "search_engine_search";
        $select = array('email.id','search_engine_search.id as search_engine_url_id','search_engine_search.id','count(email.found_email) as found_email','search_engine_search.search_engine_name','search_engine_search.last_scraped_time','search_engine_search.search_in','search_engine_search.search_keyword','country','language');
        $join = array(
            "email"        => "email.search_engine_url_id	 	= 	search_engine_search.id,left"
            );

        $info = $this->basic->get_data($table, $where, $select, $join, $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='search_engine_search.id');



        $total_rows_array = $this->basic->count_row($table, $where, $count="search_engine_search.id", $join, $group_by='search_engine_search.id');

        $total_result = $total_rows_array[0]['total_rows'];

        echo convert_to_grid_data($info, $total_result);
    }


     /**
    * method to view search engine wise details
    * @access public    
    * @return void
    */
    public function searchengine_wise_details()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $search_engine_url_id = trim($this->input->post('search_engine_url_id', true));

        $where['where'] = array('search_engine_search.id'=>$search_engine_url_id);
        $table = 'search_engine_search';
        $join = array(
            "email"        => "email.search_engine_url_id	 	= 	search_engine_search.id,left",
            "searh_engine"  => "searh_engine.search_engine_name  =	search_engine_search.search_engine_name,left"
            );

        $info = $this->basic->get_data($table, $where, $select ='', $join);

        $str = "<h3 class = 'text-center text-info'>Email from {$info[0]['search_engine_name']}</h3>
        <div class='table-responsive'>
		<table class='table table-hover'>
		<tr>
			<th>SL</th>
			<th>Email Address</th>			
		</tr>";

        $row_count = 1;

        foreach ($info as $detail) {
            $str .= "<tr><td>".$row_count."</td><td>".$detail['found_email']."</td></tr>";
            $row_count++;
        }
        $str .= "</table></div>";

        echo $str;
    }

    

     /**
    * method for SE Wise Details Download
    * @access public    
    * @return void
    */
    public function search_engine_search_detailw_download()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $search_engine_url_id = trim($this->input->post('download_details_se_url', true));
        $where['where'] = array('email.search_engine_url_id'=>$search_engine_url_id);
        $table = 'search_engine_search';
        $join = array(
            "email"         => "email.search_engine_url_id      =   search_engine_search.id,left",
            "searh_engine"  => "searh_engine.search_engine_name  =  search_engine_search.search_engine_name,left"
            );

        $info = $this->basic->get_data($table, $where, $select ='', $join);
        $fp = fopen("download/report/search_engine_details_email_{$this->user_id}_{$this->download_id}.csv", "w");
        $head=array("SL","Email");
        fputcsv($fp, $head);
        $write_info = array();
        $row_count=1;

        foreach ($info as  $value) {
            $write_info['sl'] = $row_count;
            $write_info['email'] = $value['found_email'];

            fputcsv($fp, $write_info);
            $row_count++;
        }

        fclose($fp);
        $file_name = "download/report/search_engine_details_email_{$this->user_id}_{$this->download_id}.csv";
        $this->session->set_userdata('download_file_name', $file_name);
        echo $file_name;
    }

      

     /**
    * method to url wise details download
    * @access public    
    * @return void    
    */

    public function url_wise_details_download()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $url_id = trim($this->input->post('download_details_url', true));
        $where['where'] = array('email.url_id'=>$url_id);
        $table = 'url';
        $join = array("email" => "email.url_id = url.id,left");

        $info = $this->basic->get_data($table, $where, $select ='', $join);
        $fp = fopen("download/report/url_details_email.csv", "w");
        $head=array("SL","Email");
        fputcsv($fp, $head);
        $write_info = array();
        $row_count=1;

        foreach ($info as  $value) {
            $write_info['sl'] = $row_count;
            $write_info['email'] = $value['found_email'];

            fputcsv($fp, $write_info);
            $row_count++;
        }

        fclose($fp);
        $file_name = "download/report/url_details_email.csv";
        $this->session->set_userdata('download_file_name', $file_name);
        echo $file_name;
    }

   /**
    * method to domain wise details download
    * @access public    
    * @return void    
    */

    public function domain_wise_details_download()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $domain_id = trim($this->input->post('download_details_url', true));
        $where['where'] = array('email.domain_id'=>$domain_id);
        $table = 'domain';
        $join = array("email" => "email.domain_id = domain.id,left");

        $info = $this->basic->get_data($table, $where, $select ='', $join);
        $fp = fopen("download/report/domain_details_email_{$this->user_id}_{$this->download_id}.csv", "w");
        $head=array("SL","Email");
        fputcsv($fp, $head);
        $write_info = array();
        $row_count=1;

        foreach ($info as  $value) {
            $write_info['sl'] = $row_count;
            $write_info['email'] = $value['found_email'];

            fputcsv($fp, $write_info);
            $row_count++;
        }

        fclose($fp);
        $file_name = "download/report/domain_details_email_{$this->user_id}_{$this->download_id}.csv";
        echo $file_name;
    }


    /**
    * method to search engine wise details download
    * @access public    
    * @return void    
    */
    public function searchengine_wise_download()
    {
        $table = "search_engine_search";
        $join = array(
            "email"        => "email.search_engine_url_id	 	= 	search_engine_search.id,left",
            "searh_engine"  => "searh_engine.search_engine_name  =	search_engine_search.search_engine_name,left",
            "users" => "search_engine_search.user_id = users.id, left"
            );

        $selected_grid_data = $this->input->post('info', true);
        $search_engine_names = json_decode($selected_grid_data, true);
        $search_engine_names_array = array();
        foreach ($search_engine_names as  $value) {
            $search_engine_names_array[] = $value['search_engine_url_id'];
        }
        $where['where_in'] = array('search_engine_search.id' => $search_engine_names_array);
		$where_simple['search_engine_search.user_id'] = $this->user_id;
   	    $where  = array('where'=>$where_simple);
		

        $info = $this->basic->get_data($table, $where, $select ='', $join, $limit='', $start=null, $order_by='search_engine_search.search_engine_name asc');

        $fp = fopen("download/report/search_engine_wise_email_{$this->user_id}_{$this->download_id}.csv", "w");
        $head=array("Keyword", "Search Engine","Social Network", "Last Scraped Time","Email");
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) {
            $write_info['search_keyword'] = $value['search_keyword'];
            $write_info['search_engine_name'] = $value['search_engine_name'];
            $write_info['search_in'] = $value['search_in'];
            // $write_info['name'] = $value['name'];
            $write_info['last_scraped_time'] = $value['last_scraped_time'];
            $write_info['email'] = $value['found_email'];

            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/report/search_engine_wise_email_{$this->user_id}_{$this->download_id}.csv";
        $this->session->set_userdata('download_file_name', $file_name);
        echo $file_name;
    }

    /**
    * method to view all email
    * @access public    
    * @return void    
    */
    public function all_email()
    {
        $this->load->database();
        $this->load->library('grocery_CRUD');
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('email');
        $crud->order_by('id');
        $crud->set_subject('Email List');
        $crud->fields('found_email', 'domain_id', 'url_id', 'search_engine_url_id');
        
        $crud->columns('found_email', 'domain_id', 'url_id', 'search_engine_url_id');

        $crud->display_as('found_email', 'Email');
        $crud->display_as('domain_id', 'Domain Name');
        $crud->display_as('url_id', 'URL');
        $crud->display_as('search_engine_url_id', 'Search Engine');
        // $crud->display_as('user_id', 'User Name'); 

        $crud->set_relation('user_id', 'users', 'name', 'id ASC');
        $crud->set_relation('url_id', 'url', 'url_name', 'id ASC');
        $crud->set_relation('domain_id', 'domain', 'domain_name', 'id ASC');
        $crud->set_relation('search_engine_url_id', 'searh_engine', 'search_engine_name', 'id ASC');

        $crud->unset_read();
        $crud->unset_edit();
        $crud->unset_delete();
        $crud->unset_add();
       /* $crud->unset_print();
        $crud->unset_export();*/

        $output = $crud->render();
        $data['output']=$output;
        $data['crud']=1;

        $this->_viewcontroller($data);
    }

     /**
    * Reset password form
    * @access public
    * @return void
    */
    public function reset_password_form()
    {
        $data['page_title'] = 'Password Reset';
        $data['body'] = 'admin/theme/password_reset_form';
        $this->_viewcontroller($data);
    }

    /**
    * Reset password action
    * @access public
    * @return void
    * @param int
    */
    public function reset_password_action()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $this->form_validation->set_rules('old_password', '<b>Old Password</b>', 'trim|required|xss_clean');
        $this->form_validation->set_rules('new_password', '<b>New Password</b>', 'trim|required|xss_clean');
        $this->form_validation->set_rules('confirm_new_password', '<b>Confirm New Password</b>', 'trim|required|xss_clean|matches[new_password]');
        if ($this->form_validation->run() == false) {
            $this->reset_password_form();
        } else {
            $user_id = $this->user_id;
            $password = trim($this->input->post('old_password', true));
            $new_password = trim($this->input->post('new_password', true));
            $table = 'users';
            $where['where'] = array(
            'id' => $user_id,
            'password' => md5($password)
            );
            $select = array('email');
            if ($this->basic->get_data($table, $where, $select)) {
                $where = array(
                'id' => $user_id,
                'password' => md5($password)
                );
                $data = array('password' => md5($new_password));
                $this->basic->update_data($table, $where, $data);
                $this->session->set_userdata('logged_in', 0);
                $this->session->set_flashdata('reset_success', 'Please login with new password');
                redirect('home/login', 'location');
                // echo $this->session->userdata('reset_success');exit();
            } else {
                $this->session->set_userdata('error', 'The old password you have given is wrong!');
                return $this->reset_password_form();
            }
        }
    }

    public function website_delete(){
        
        $selected_grid_data = $this->input->post('info', true);
        $domain_names = json_decode($selected_grid_data, true);


        $domain_id_array = array();
        foreach ($domain_names as  $value) {
            $domain_id_array[] = $value['domain_id'];
        }
        
        $this->db->where_in('id', $domain_id_array);
        $this->db->delete('domain');

        $this->db->where_in('domain_id', $domain_id_array);
        $this->db->delete('url'); 

        $this->db->where_in('domain_id', $domain_id_array);
        $this->db->delete('email');
           
    }

    public function url_delete(){
       
        $selected_grid_data = $this->input->post('info', true);
        $url_names = json_decode($selected_grid_data, true);
        $url_id_array = array();


        foreach ($url_names as  $value) {
            $url_id_array[] = $value['id'];
        }       

        $this->db->where_in('id', $url_id_array);
        $this->db->delete('url'); 

        $this->db->where_in('url_id', $url_id_array);
        $this->db->delete('email');       
    }

    public function search_engine_delete(){
        $selected_grid_data = $this->input->post('info', true);
        $search_engine_names = json_decode($selected_grid_data, true);
        $search_engine_id_array = array();
        foreach ($search_engine_names as  $value) {
            $search_engine_id_array[] = $value['search_engine_url_id'];
        }       

        $this->db->where_in('id', $search_engine_id_array);
        $this->db->delete('search_engine_search'); 

        $this->db->where_in('search_engine_url_id', $search_engine_id_array);
        $this->db->delete('email');

    }
	
	
	public function decode_email_string(){
		$this->load->database();
        $this->load->library('grocery_CRUD');
        $crud = new grocery_CRUD();

       
        $crud->where('user_id', $this->user_id);
        $crud->set_theme('flexigrid');
        $crud->set_table('fuzzy_string_replace');
        $crud->order_by('id');
        $crud->where('deleted','0');
        $crud->set_subject('Decode Email String Settings');
        $crud->columns('id','search_string','replaced_by');
        $crud->required_fields('search_string','replaced_by');
        $crud->fields('search_string','replaced_by');


        $crud->display_as('search_string', 'Search String');
        $crud->display_as('replaced_by', 'Replaced By');
         $crud->callback_after_insert(array($this, 'insert_user_id_decode_string'));    /**insert the user_id***/
		  
        $crud->unset_read();
        $crud->unset_print();
        $crud->unset_export();
    
    
        $output = $crud->render();
        $data['page_title'] = 'Decode Email String';
        $data['output']=$output;
        $data['crud']=1;
        $this->_viewcontroller($data);
		
		
		
	}
	
	
	public function insert_user_id_decode_string($post_array, $primary_key)
    {
        $user_id=$this->user_id;
        $update_data=array('user_id'=>$user_id);
        $where=array("id"=>$primary_key);
        $this->basic->update_data("fuzzy_string_replace", $where, $update_data);
    }

    function send_email_member()
    {   
        
        if($_POST)
        {
            $subject= $this->input->post('subject');
            $content= $this->input->post('content');
            $info=$this->input->post('info');
            $info=json_decode($info,TRUE);
            $count=0;
            
           foreach($info as $member)
            {               
                $email=$member['email'];
                $member_id=$member['id'];                
                $message=$content;
                $from=$this->config->item('institute_email');
                $to=$email;
                $mask=$this->config->item('institute_address1');
                
                if($message=="" || $from=="" || $to=="" || $subject=="") continue;

                if($this->_mail_sender($from,$to,$subject,$message,$mask))  $count++;
               
            }
             echo "<b>Email Report : $count / ".count($info)." Email sent successfully</b>";
           
        }   
    }
	

}
