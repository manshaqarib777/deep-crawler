<?php

	$this->set_css($this->default_theme_path.'/flexigrid/css/flexigrid.css');
	$this->set_js_lib($this->default_theme_path.'/flexigrid/js/jquery.form.js');
	$this->set_js_config($this->default_theme_path.'/flexigrid/js/flexigrid-add.js');

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
?>
<!-- Addded by Al-amin -->
<style type="text/css">
.form-field-box.odd,.form-field-box.even
{
	background: #fff;
	margin: 0;
}
select
{
	width: 90% !important;
	background:#fff !important;
	border:1px solid #ccc !important;
}
input[type=text],input[type=password],input[type=email],input[type=file],textarea
{
	width: 90% !important;
	height:35px;
	background:#fff !important;
	border:1px solid #ccc !important;
}
input[type=text]:focus,input[type=password]:focus,input[type=email]:focus,input[type=file]:focus,textarea:focus,select:focus
{
	border:1px solid #3C8DBC !important;
}
.form-div
{
	padding-top:25px !important;
	background:#f7f7f7 !important;
}
.form-div .control-label
{
	color:#563F7F !important;
	font-size:14px !important;
}
@media (max-width:767px)
{
	.custom_box{padding:10px !important;}
	input[type=text],input[type=password],input[type=email],input[type=file],textarea
	{
		width: 96% !important;
	}
	select
	{
		width: 96% !important;
	}
	.form-div
	{
		padding-left:15px !important;
	}	
}
</style>
<!-- Addded by Al-amin -->	
<div class="flexigrid crud-form" style='width: 100%;' data-unique-hash="<?php echo $unique_hash; ?>">
	<div class="spacer"></div>
	<div class="mDiv">
		<div class="ftitle">
			<div class='ftitle-left'>
				<i class="fa fa-plus-circle"></i> <?php echo $this->l('form_add'); ?> <?php echo $subject?>
			</div>
			<div class='clear'></div>
		</div>
		<!-- <div title="<?php echo $this->l('minimize_maximize');?>" class="ptogtitle">
			<span></span>
		</div> -->
	</div>
<div id='main-table-box custom_box'>
	<?php echo form_open( $insert_url, 'method="post" class="form-horizontal" id="crudForm" autocomplete="off" enctype="multipart/form-data"'); ?>
		<div class='form-div'>
			<?php
			$counter = 0;
				foreach($fields as $field)
				{
					$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
					$counter++;
			?>
			
			<div class="form-group <?php echo $even_odd?>" id="<?php echo $field->field_name; ?>_field_box">
             <label class="col-sm-3 control-label" for="name" id="<?php echo $field->field_name; ?>_display_as_box">
             	<?php echo $input_fields[$field->field_name]->display_as; ?> <?php echo ($input_fields[$field->field_name]->required)? "<span class='required'>*</span> " : ""; ?> 
             </label>
             <div class="col-sm-9 col-md-6 col-lg-6" id="<?php echo $field->field_name; ?>_input_box">
               <?php echo $input_fields[$field->field_name]->input?>
               <!-- <span class="red"><?php echo form_error('name'); ?></span> -->
             </div>
           </div>


			<?php }?>
			<!-- Start of hidden inputs -->
				<?php
					foreach($hidden_fields as $hidden_field){
						echo $hidden_field->input;
					}
				?>
			<!-- End of hidden inputs -->
			<?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php }?>

			<div id='report-error' class='report-div error red text-center'></div>
			<div id='report-success' class='report-div success green text-center'></div>
		</div>
		<div class="pDiv">
			<!-- <div class='form-button-box'>
				<input id="form-button-save" type='submit' value='<?php echo $this->l('form_save'); ?>'  class="btn btn-large btn-info"/>
			</div> -->
			<center>
<?php 	if(!$this->unset_back_to_list) { ?>
			
				<input type='button' value='<?php echo "Save" ?>' id="save-and-go-back-button"  class="btn btn-warning btn-lg"/>
				<input type='button' value='<?php echo $this->l('form_cancel'); ?>' class="btn btn-lg btn-default" id="cancel-button" />			
<?php 	} ?>			
			<span class='small-loading' id='FormLoading'><?php echo $this->l('form_insert_loading'); ?></span>
			</center>
			<div class='clear'></div>
		</div>
	<?php echo form_close(); ?>

</div>
</div>
<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_add_form = "<?php echo $this->l('alert_add_form')?>";
	var message_insert_error = "<?php echo $this->l('insert_error')?>";
</script>