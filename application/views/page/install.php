<?php 
  /*****Curl******/
  $curl="Can't Checked";
  $mbstring="Can't Checked";
  $safe_mode="Can't Checked";
  $open_basedir="Can't Checked";
  $allow_url_fopen="Can't Checked";
  
  $mysql_support="";
  $install_allow = 1;

  if(function_exists('curl_version'))
    $curl="<div class='alert alert-success'><i class='fa fa-check-circle'></i> <b>curl : </b>Ok. Enabled.</div>";
  else{
    $install_allow = 0;
    $curl="<div class='alert alert-danger'><i class='fa fa-times-circle'></i> <b>curl : </b>Error. Curl is not enabled.Please Enable it.</div>";
  }
  
  if(function_exists( "mb_detect_encoding" ) )
    $mbstring="<div class='alert alert-success'><i class='fa fa-check-circle'></i> <b>mbstring : </b>Ok. Enabled.</div>";
  else{
    $install_allow = 0;
    $mbstring="<div class='alert alert-danger'><i class='fa fa-times-circle'></i> <b>mbstring : </b>Error.Mbstring is not Enabled. Please enable it.</div>";
  }
    
    
  if(function_exists('ini_get')){
    if( ini_get('safe_mode') ){
      $install_allow = 0;
      $safe_mode="<div class='alert alert-danger'><i class='fa fa-times-circle'></i> <b>safe mode : </b>Error. safe_mode=on, please make safe_mode=off.</div>";
    }
    else
      $safe_mode="<div class='alert alert-success'><i class='fa fa-check-circle'></i> <b>safe mode : </b>OK</div>";
      
    if(ini_get('open_basedir')=="")
      $open_basedir="<div class='alert alert-success'><i class='fa fa-check-circle'></i> <b>open_basedir : </b>Ok. </div>";
    else{
      $install_allow = 0;
      $open_basedir="<div class='alert alert-danger'><i class='fa fa-times-circle'></i> <b>open_basedir</b>Error. open_basedir has value. Please clear value for open_basedir from php.ini</div>";
    }
    
    if(ini_get('allow_url_fopen'))
      $allow_url_fopen="<div class='alert alert-success'><i class='fa fa-check-circle'></i> <b>allow_url_fopen : </b>Ok. Enabled</div>";
    else{
      $install_allow = 0;
      $allow_url_fopen="<div class='alert alert-danger'><i class='fa fa-times-circle'></i> <b>allow_url_fopen : </b>Error. Please make allow_url_fopen=1 from php.ini file</div>";
    }
    
  }
  
  if(function_exists('mysqli_connect'))
    $mysql_support="<div class='alert alert-success'><i class='fa fa-check-circle'></i> <b>mysqli support : </b>Ok. Supported</div>";
  else{
    $install_allow = 0;
    $mysql_support="<div class='alert alert-danger'><i class='fa fa-times-circle'></i> <b>mysqli support : </b>Error. MySqli Support is not available. Please enable MySqli Support.</div>";
  }
?>

<br>
<style>#recovery_form{text-align:center;}</style>
  <div class="row" style="padding-left:15px;padding-right:15px;">    
    <div class="col-sm-12 col-xs-12 col-md-7 col-lg-7 border_gray grid_content padded background_white">
    <h6 class="column-title"><i class="fa fa-cog fa-2x blue"> Install <?php echo $this->config->item('product_short_name');?> Package</i></h6>
    
    <?php 
    if($this->session->userdata('mysql_error')!="")
      {
        echo "<pre style='margin:0 auto;color:red;text-align:center;'><h3 style='color:red;'>";
        echo $this->session->userdata('mysql_error');
        $this->session->unset_userdata('mysql_error');
        echo "</h3></pre><br/>"; 
      }
    ?>

    <?php 
      if(validation_errors())
      {
        echo "<pre style='margin:0 auto;color:red;text-align:center;'>";
        print_r(validation_errors()); 
        echo "</pre><br/>"; 
      }
    ?>
    <div class="account-wall" id='recovery_form' style='text-align:left; padding:0 15px;'> 
      <form class="form-horizontal" action="<?php echo site_url().'home/installation_action';?>" method="POST">
        <div class="form-group">
           <label>Host Name *</label>
           <input type="text" value="localhost" name="host_name" required class="form-control col-xs-12"  placeholder="Host Name *">          
        </div>
        <div class="form-group">
           <label>Database Name *</label>
           <input type="text" value="<?php echo set_value('database_name'); ?>" name="database_name" required class="form-control col-xs-12"  placeholder="Database Name *">          
        </div>
        
        <div class="form-group">
           <label>Database Username *</label>
           <input type="text" value="<?php echo set_value('database_username'); ?>" name="database_username" required class="form-control col-xs-12"  placeholder="Database Username *">          
        </div>
        <div class="form-group">
           <label>Database Password </label>
           <input type="password" name="database_password" class="form-control col-xs-12"  placeholder="Database Password ">          
        </div>

         <div class="form-group">
           <label><?php echo $this->config->item('product_short_name') ?> Admin Panel Login Email*</label>
           <input type="email" value="<?php echo set_value('app_username'); ?>" name="app_username" required class="form-control col-xs-12"  placeholder="Application Username *">          
        </div>
        <div class="form-group">
           <label><?php echo $this->config->item('product_short_name') ?> Admin Panel Login Password *</label>
           <input type="password" name="app_password" required class="form-control col-xs-12"  placeholder="Application Password *">          
        </div>

   
        <div class="form-group">
           <label>Company Name </label>
           <input type="text" value="<?php echo set_value('institute_name'); ?>" name="institute_name" class="form-control col-xs-12"  placeholder="Company Name">          
        </div>
        <div class="form-group">
           <label>Company Address </label>
           <input type="text" value="<?php echo set_value('institute_address'); ?>" name="institute_address" class="form-control col-xs-12"  placeholder="Company Address">          
        </div>
        <div class="form-group">
           <label>Company Phone / Mobile </label>
           <input type="text" value="<?php echo set_value('institute_mobile'); ?>" name="institute_mobile" class="form-control col-xs-12"  placeholder="Company Phone / Mobile">          
        </div>   

       
        <div class="form-group text-center">
          <button type="submit" style="margin-top:20px" class="btn btn-warning btn-lg" <?php if($install_allow == 0) echo "disabled"; ?> ><i class="fa fa-check"></i> Install <?php echo $this->config->item('product_short_name');?> Now</button><br/><br/> 
        </div>  
      </form>    
    </div>
  </div>


  <div class="col-sm-12 col-xs-12 col-md-5 col-lg-5 border_gray grid_content padded background_white alert alert-warning">
      <h6 class="column-title"><i class="fa fa-cog fa-2x blue"> Server Requirements</i></h6>
      <?php
        echo $curl;
        echo $mbstring;
        echo $safe_mode;
        echo $open_basedir;
        echo $allow_url_fopen;
        echo $mysql_support;
      ?>
      <div class="alert alert-warning">
        <p>Please make sure that the following folders and file have write permission</p>
        <p>
          <ul>
            <li>Folder : application/config (777)</li>
            <li>Folder : application/core (777)</li>
            <li>Folder : download (777)</li>
            <li>Folder : upload (777)</li>
            <li>File : application/install.txt (777)</li>
          </ul>
        </p>
      </div>
      <?php if($install_allow==1) :?>
        <div class="alert alert-info text-center"><b>Congratulation ! Your server is fully configured to install this application.</b></div>
      <?php else : ?>
        <div class="alert alert-danger text-center"><b>Warning ! Please fulfill the above requirements (red colored) first.</b></div>
      <?php endif; ?>
    </div>
  </div>
