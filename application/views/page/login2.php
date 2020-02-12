<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login System | Login</title>
  <link rel="icon" href="images/favicon.png?v=2" type="image/x-icon"/>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
  </head>
  <body class='gray'>

  <div class="container">
    <div class="row row-centered">      
      <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 text-center col-centered">
      <img src="images/logo.png" alt="Logo"class="img-responsive logo">
      </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 container_header_radius col-centered title-container blue text-center">   
      <h3 class='color_white'>Log In</h3>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 container_body_radius col-centered custom-container white border_gray">   
    <div class="row">
      <div class='' id="log_in_message"></div>
      <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
        <form class="form-horizontal">             
          <div class="form-group">
            <label for="lemail" class="col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label">Email *</label>
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
              <input type="email" class="form-control" id="lemail" name="lemail" placeholder="Email">
              <p><code id="lemail_msg"></code></p>
            </div>
          </div>
          
          <div class="form-group">
            <label for="lpassword" class="col-xs-12 col-sm-12 col-md-4 col-lg-4 control-label">Password *</label>
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
              <input type="password" class="form-control" id="lpassword" name="lpassword" placeholder="Password">
              <p><code id="lpassword_msg"></code></p>
            </div>
          </div>
                    
          <input type="hidden" name="reffer_page" id="reffer_page" value="<?php echo $refference_page; ?>"/>
          
          <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-4 col-lg-8 col-lg-offset-4">
              <p class="pull-right">
                <input type="checkbox" name="remember_me" id="remember_me" /> <label for="remember_me">Remember Me</label> 
              </p>
              <button type="button" class="btn btn-primary pull-left b_radius" id="log_in_button"><b>Log In</b></button>
            </div>
          </div>
        </form> 
      </div>
      <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 right_part">
        <p>Don't have account ?</p>
        <p style="margin-top:-10px;"><a href="signup.php">Sign Up here</a></p>
        <br/><br/>
        <p>Forget your password ?</p>
        <p style="margin-top:-10px;"><a href="forget_password.php" >Reset it here</a></p>
      </div>
    </div>
      
      
    </div>     
  </div>
  </div>
    

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->  
    <script src="js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

  <?php include("includes/js.php"); ?>

   
  </body>
</html>