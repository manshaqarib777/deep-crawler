<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php echo $this->config->item('product_name')." | Login"; ?></title>    
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.png"> 
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- FontAwesome 4.3.0 -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo base_url();?>css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="<?php echo base_url();?>plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <center><a href="<?php echo site_url();?>"><img src="<?php echo base_url();?>assets/images/deepcrawllogo.svg" alt="<?php echo $this->config->item('product_name');?>" class="img-responsive"></a></center>
       </div><!-- /.login-logo -->
      <div class="login-box-body">
        <?php 
          if($this->session->flashdata('login_msg')!='') 
          {
              echo "<div class='alert alert-danger text-center'>"; 
                  echo $this->session->flashdata('login_msg');
              echo "</div>"; 
          }   
          if($this->session->flashdata('reset_success')!='') 
          {
              echo "<div class='alert alert-success text-center'>"; 
                  echo $this->session->flashdata('reset_success');
              echo "</div>"; 
          } 
          if($this->session->userdata('reg_success') != ''){
            echo '<div class="alert alert-success text-center">'.$this->session->userdata("reg_success").'</div>';
            $this->session->unset_userdata('reg_success');
          }      
        ?>
        <p class="login-box-msg">Log in</p>
        <form action="<?php echo site_url('home/login');?>" method="post">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" name="username" placeholder="Email" />
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
            <span style="color:red"><?php echo form_error('username'); ?></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password" placeholder="Password" />
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            <span style="color:red"><?php echo form_error('password'); ?></span>
          </div>
          <div class="row">            
            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Log In</button>
            </div><!-- /.col -->
          </div>
        </form>
       <br/><center><a href="<?php echo site_url();?>home/forgot_password">Forget Password?</a></center>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="<?php echo base_url();?>plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url();?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>
