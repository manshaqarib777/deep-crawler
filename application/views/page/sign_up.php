
  <link href="<?php echo site_url(); ?>sign_up_page_layout/css/custom.css" rel="stylesheet">
  <div class='gray'>

  	<div class="container" >
	  	<div class="row" style="margin-top:10px;margin-bottom:30px;">
	  	    <!-- <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 text-center">
	  			<img src="<?php echo site_url(); ?>assets/images/logo.png" alt="Logo" class="img-responsive logo">
	  	    </div> -->
	  	    <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 container_header_radius title-container blue text-center">		
		  		<h3 class='color_white'>Sign Up Form</h3>
		    </div>
	  		<div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 container_body_radius custom-container white border_gray">
				<div>
					<?php 
						if($this->session->userdata('reg_success') == 1) {
							echo "<div class='alert alert-success'> An activation code has been sent to your email. <br/> Please log in your email to activate your account. </div>";
							$this->session->unset_userdata('reg_success');
						}
					?>
				</div>
	  			<form class="form-horizontal" method="post" action="<?php echo site_url('home/sign_up_action'); ?>">
	  				<div class="form-group">
	  					<!-- <label for="sname" class="col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label">First Name*</label> -->
	  					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 input-group">
	  						<div class="input-group-addon"><i class="fa fa-user group_bg"></i></div>
	  						<input type="text" class="form-control right_border_radius" value="<?php echo set_value('name');?>" id="name" name="name" placeholder="Name *">	  						
	  					</div>
	  					<span style="color:red;margin-top:5px;"><?php echo form_error('name'); ?></span>
	  				</div>

	  				<div class="form-group">
	  					<!-- <label for="sname" class="col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label">First Name*</label> -->
	  					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 input-group">
	  						<div class="input-group-addon"><i class="fa fa-home group_bg"></i></div>
	  						<input type="text" class="form-control right_border_radius" value="<?php echo set_value('address');?>" id="address" name="address" placeholder="Address (optional)">	  						
	  					</div>
	  					<span style="color:red;margin-top:5px;"><?php echo form_error('address'); ?></span>
	  				</div>
					
					
	  				<div class="form-group">
	  					<!-- <label for="semail" class="col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label">Email*</label> -->
	  					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 input-group">
	  						<div class="input-group-addon"><i class="fa fa-envelope group_bg"></i></div>
	  						<input type="email" class="form-control right_border_radius" value="<?php echo set_value('email');?>" id="email" name="email" placeholder="Email *">	  						
	  					</div>
	  					<span style="color:red;margin-top:5px;"><?php echo form_error('email'); ?></span>
	  				</div>
					
					
					<div class="form-group">
	  					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 input-group">
	  						<div class="input-group-addon"><i class="fa fa-mobile group_bg"></i></div>
	  						<input type="text" class="form-control right_border_radius" value="<?php echo set_value('mobile');?>" id="mobile" name="mobile" placeholder="Mobile (With Country Code) (optional)">
	  					</div>
	  					<span style="color:red;margin-top:5px;"><?php echo form_error('mobile'); ?></span>				
					</div>
					
					
					
	  				<div class="form-group">
	  					<!-- <label for="spassword" class="col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label">Password*</label> -->
	  					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 input-group">
	  						<div class="input-group-addon"><i class="fa fa-key group_bg"></i></div>
	  						<input type="password" class="form-control right_border_radius" value="<?php echo set_value('password');?>" name="password" id="password" placeholder="Password *">
	  						
	  					</div>
	  					<span style="color:red;margin-top:5px;"><?php echo form_error('password'); ?></span>
	  				</div>
	  				<div class="form-group">
	  					<!-- <label for="spassword2" class="col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label">Retype Password*</label> -->
	  					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 input-group">
	  						<div class="input-group-addon"><i class="fa fa-key group_bg"></i></div>
	  						<input type="password" class="form-control right_border_radius" value="<?php echo set_value('confirm_password');?>" name="confirm_password" id="confirm_password" placeholder="Confirm Password *">	  						
	  					</div>
	  					<span style="color:red;margin-top:5px;"><?php echo form_error('confirm_password'); ?></span>
	  				</div>
	  				<div class="form-group">
	  					<!-- <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-3 col-lg-8 col-lg-offset-3"> -->
	  						<button type="submit" class="btn btn-primary pull-left b_radius" id="sign_up_button"><b>Sign Up</b></button>
	  						<a type="button" class="btn btn-default pull-right b_radius" href="<?php echo site_url('website/index'); ?>" ><b>Cancel</b></a>
	  					<!-- </div> -->
	  				</div>
	  			</form>
	  		</div>
	  	</div>
  	</div>	
</div>	

