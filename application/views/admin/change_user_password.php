<?php $this->load->view('admin/theme/message'); ?>
<section class="content-header">
	<section class="content">
		<div class="box box-info custom_box">
			<div class="box-header">
				<h3 class="box-title"><i class="fa fa-plus-circle"></i> Change User Password <?php echo " [ ".$user_name." ]"; ?></h3>
			</div><!-- /.box-header -->
			<!-- form start -->
			<form class="form-horizontal" action="<?php echo site_url().'admin/change_user_password_action';?>" method="POST">
				<div class="box-body">
				

					<div class="form-group">
						<label class="col-sm-3 control-label" for="name">Password
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="password" value="<?php echo set_value('password');?>"  class="form-control" type="password">		          
							<span class="red"><?php echo form_error('password'); ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" for="name">Confirm Password
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="confirm_password" value="<?php echo set_value('confirm_password');?>"  class="form-control" type="password">		          
							<span class="red"><?php echo form_error('confirm_password'); ?></span>
						</div>
					</div>



				</div> <!-- /.box-body --> 
				<div class="box-footer">
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<input name="submit" type="submit" class="btn btn-warning btn-lg" value="Save"/>  
							<input type="button" class="btn btn-default btn-lg" value="Cancel" onclick='goBack("admin/user_management")'/>  
						</div>
					</div>
				</div><!-- /.box-footer -->         
			</div><!-- /.box-info -->       
		</form>     
	</div>
</section>
</section>



