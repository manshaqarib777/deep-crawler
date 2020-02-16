<?php $this->load->view('admin/theme/message'); ?>
<section class="content-header">
   <section class="content">
     	<div class="box box-info custom_box">
		    	<div class="box-header">
		         <h3 class="box-title"><i class="fa fa-pencil"></i> General Settings</h3>
		        </div><!-- /.box-header -->
		       		<!-- form start -->
		    <form class="form-horizontal" enctype="multipart/form-data" action="<?php echo site_url().'admin_config/edit_config';?>" method="POST">
		        <div class="box-body">
		           	<div class="form-group">
		              	<label class="col-sm-3 control-label" for="">Company Name 
		              	</label>
		                	<div class="col-sm-9 col-md-6 col-lg-6">
		               			<input name="institute_name" value="<?php echo $this->config->item('institute_address1');?>"  class="form-control" type="text">		               
		             			<span class="red"><?php echo form_error('institute_name'); ?></span>
		             		</div>
		            </div>
		           <div class="form-group">
		             	<label class="col-sm-3 control-label" for="">Company Address 
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">
	               			<input name="institute_address" value="<?php echo $this->config->item('institute_address2');?>"  class="form-control" type="text">		          
	             			<span class="red"><?php echo form_error('institute_address'); ?></span>
	             		</div>
		           </div> 

		           <div class="form-group">
		             	<label class="col-sm-3 control-label" for="">Company Email *
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">
	               			<input name="institute_email" value="<?php echo $this->config->item('institute_email');?>"  class="form-control" type="email">		          
	             			<span class="red"><?php echo form_error('institute_email'); ?></span>
	             		</div>
		           </div>  


		           <div class="form-group">
		             	<label class="col-sm-3 control-label" for="">Company Phone/ Mobile 
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">
	               			<input name="institute_mobile" value="<?php echo $this->config->item('institute_mobile');?>"  class="form-control" type="text">		          
	             			<span class="red"><?php echo form_error('institute_mobile'); ?></span>
	             		</div>
		           </div> 

		           <div class="form-group">
		             	<label class="col-sm-3 control-label" for="">Logo 
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6" >
		           			<div class='text-center' style="background:#357CA5;padding:10px;"><img class="img-responsive" src="<?php echo base_url().'assets/images/logo.png';?>" alt="Logo"/></div>
	               			Max dimension: 600 x 300, Max size : 200KB, Format : png
	               			<input name="logo" class="form-control" type="file">		          
	             			<span class="red"> <?php echo $this->session->userdata('logo_error'); $this->session->unset_userdata('logo_error'); ?></span>
	             		</div>
		           </div> 

		           <div class="form-group">
		             	<label class="col-sm-3 control-label" for="">Favicon
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">
	             			<div class='text-center'><img class="img-responsive" src="<?php echo base_url().'assets/images/favicon.png';?>" alt="Favicon"/></div>
	               			 Max dimension : 32 x 32, Max size : 50KB, Format : png
	               			<input name="favicon"  class="form-control" type="file">		          
	             			<span class="red"><?php echo $this->session->userdata('favicon_error'); $this->session->unset_userdata('favicon_error'); ?></span>
	             		</div>
		           </div> 

		        
		           <div class="form-group">
		             	<label class="col-sm-3 control-label" for="">Time Zone
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">	             			
	               			<?php	$time_zone['']="Time Zone";
							echo form_dropdown('time_zone',$time_zone,$this->config->item('time_zone'),'class="form-control" id="time_zone"');  ?>		          
	             			<span class="red"><?php echo form_error('time_zone'); ?></span>
	             		</div>
		           </div> 

		           
		         		               
		           </div> <!-- /.box-body --> 

		           	<div class="box-footer">
		            	<div class="form-group">
		             		<div class="col-sm-12 text-center">
		               			<input name="submit" type="submit" class="btn btn-warning btn-lg" value="Save"/>  
		              			<input type="button" class="btn btn-default btn-lg" value="Cancel" onclick='goBack("admin_config",1)'/>  
		             		</div>
		           		</div>
		         	</div><!-- /.box-footer -->         
		        </div><!-- /.box-info -->       
		    </form>     
     	</div>
   </section>
</section>



