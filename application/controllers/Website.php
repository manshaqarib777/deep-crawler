<?php require_once("Home.php"); // including home controller

class Website extends Home
{
    
    /**
    * load constructor
    * @access public
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        set_time_limit(0);
    }

    public function index()
    {
        $config_data=array();
        $data=array();

        // $price=0;
        // $config_data=$this->basic->get_data("payment_config","","monthly_fee");
        // if(array_key_exists(0,$config_data)) $price=$config_data[0]['monthly_fee'];
        // $data['price']=$price;

        $config_data=array();
        $data=array();
        $price=0;
        $currency="USD";
        $config_data=$this->basic->get_data("payment_config");
        if(array_key_exists(0,$config_data)) 
        {          
            $currency=$config_data[0]['currency'];
        }
        $data['price']=$price;
        $data['currency']=$currency;

        //catcha for contact page
        $data["payment_package"]=$this->basic->get_data("package",$where=array("where"=>array("is_default"=>"0","price > "=>0,"validity >"=>0)),$select='',$join='',$limit='',$start=NULL,$order_by='CAST(`price` AS SIGNED)');         
        $data["default_package"]=$this->basic->get_data("package",$where=array("where"=>array("is_default"=>"1","validity >"=>0,"price"=>"Trial")));         
    



        $this->load->view('aes_website/index',$data);
    }

}
