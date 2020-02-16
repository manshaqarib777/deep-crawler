<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-md-12 col-lg-6 col-lg-offset-3">
				<?php 
				if($this->session->userdata('error'))
					echo '<div class="alert alert-warning text-center">'.$this->session->userdata('error').'</div>';
				$this->session->unset_userdata('error');
				?>
				
				<form class="" method="POST" action="<?php echo site_url('admin/reset_password_action'); ?>">
					<div class="form-group">
						<label for="old_password">Old Password</label>
						<div>
							<input required type="password" class="form-control" id="old_password" name="old_password" placeholder="Old Password">
							<span class="red"><?php echo form_error('old_password');?></span>
						</div>
					</div>
					<div class="form-group">
						<label for="new_password">New Password</label>
						<div>
							<input required type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password">
							<span class="red"><?php echo form_error('new_password');?></span>
						</div>
					</div>
					<div class="form-group">
						<label for="confirm_new_password">Confirm New Password</label>
						<div>
							<input required type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" placeholder="Confirm New Password">
							<span class="red"><?php echo form_error('confirm_new_password');?></span>
						</div>
					</div>
					<div class="form-group">
						<div>
							<button type="submit" class="btn btn-warning">Reset Password</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>