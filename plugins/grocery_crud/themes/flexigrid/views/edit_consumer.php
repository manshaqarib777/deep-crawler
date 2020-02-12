<?php

	$this->set_css($this->default_theme_path.'/flexigrid/css/flexigrid.css');
	$this->set_js_lib($this->default_theme_path.'/flexigrid/js/jquery.form.js');
	$this->set_js_config($this->default_theme_path.'/flexigrid/js/flexigrid-edit.js');

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
	width: 250px !important;
}
input[type=text],input[type=password],input[type=email],input[type=file],textarea
{
	width: 240px !important;
}

.full,.half
{
	padding: 10px;

}
.full
{
	width: 95%;
	height: auto;	
	margin: 0 auto;
	padding-top:15px;
}	

.half
{
	width: 45%;	
	padding-top:15px;
}


@media (max-width: 768px)
{
	.half
	{
		float: none;
		width: 90%;
		margin:0 auto;

	}
}

@media (max-width: 500px)
{
	.full
	{
		width: 90%;
	}	
}


@media (max-width: 400px)
{
	.full
	{
		width: 90%;
	}
	.full input,.full select
	{
		width: auto !important;

	}
}



.fieldset {
    position: relative;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-top: 35px;   
    text-align: left;
}

.fieldset .legend 
{
    background: #fff;
    height: 1px;
    position: absolute;
    top: -1px;
    padding: 0 10px;   
    overflow: visible;
    font-weight: bold;
}

.fieldset .legend span 
{
    top: -0.5em;
    position: relative;
    vertical-align: middle;
    display: inline-block;
    overflow: visible;
} 

.full .legend
{
	color:#A5462B;	
}

.half .legend
{
	 color: #0C5656;
}  


</style>
<!-- Addded by Al-amin -->	

<div class="flexigrid crud-form" style='width: 100%;' data-unique-hash="<?php echo $unique_hash; ?>">
	<div class="mDiv">
		<div class="ftitle">
			<div class='ftitle-left'>
				<?php echo $this->l('form_edit'); ?> <?php echo $subject?>
			</div>
			<div class='clear'></div>
		</div>
		<div title="<?php echo $this->l('minimize_maximize');?>" class="ptogtitle">
			<span></span>
		</div>
	</div>
<div id='main-table-box'>
	<?php echo form_open( $update_url, 'method="post" id="crudForm" autocomplete="off" enctype="multipart/form-data"'); ?>
		<div class='form-div'>
			<?php

			$counter = 0;
			$app_info_str='';
			$app_pre_str='';
			$app_per_str='';
			$own_info_str='';
			$own_pre_str='';
			$own_per_str='';
			$con_str='';

			foreach($fields as $field)
			{
				$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
				$counter++;

				$concat_str='';
				$temp1=$input_fields[$field->field_name]->input;				
				$temp2=explode('=', $temp1);
				$temp3=$temp2[2];
				$temp4=explode('\'',$temp3);
				$filtered_field_name=trim($temp4[1]);
				$concat_str=

				"<div class='form-field-box".$even_odd."'".'id="'.$field->field_name.'_field_box">'.
				"<div class='form-display-as-box'". 'id="'.$field->field_name.'_display_as_box">'.
				$input_fields[$field->field_name]->display_as;				
				
				if($input_fields[$field->field_name]->required) 
				$concat_str.="<span class='required'>*</span>";
				else $concat_str.="";

				$concat_str.="</div><div class='form-input-box'". 'id="'.$field->field_name.'_input_box">'.$input_fields[$field->field_name]->input."</div><div class='clear'></div></div><br/>";

				if
				(		
					$filtered_field_name=='app_name_ben' 			|| 
					$filtered_field_name=='app_name_eng' 			|| 
					$filtered_field_name=='app_father_name' 		||
					$filtered_field_name=='app_mother_name' 		||
					$filtered_field_name=='app_mobile' 				||
					$filtered_field_name=='app_national_id' 

				) $app_info_str.=$concat_str;

				else if
				(		
					$filtered_field_name=='app_pre_ward' 			|| 
					$filtered_field_name=='app_pre_moholla' 		|| 
					$filtered_field_name=='app_pre_post' 			||
					$filtered_field_name=='app_pre_thana' 			||
					$filtered_field_name=='app_pre_district' 			
				) $app_pre_str.=$concat_str;

				else if
				(		
					$filtered_field_name=='app_per_ward' 			|| 
					$filtered_field_name=='app_per_moholla' 		|| 
					$filtered_field_name=='app_per_post' 			||
					$filtered_field_name=='app_per_thana' 			||
					$filtered_field_name=='app_per_district' 			
				) $app_per_str.=$concat_str;

				else if
				(		
					$filtered_field_name=='own_name_ben' 			|| 
					$filtered_field_name=='own_name_eng' 			|| 
					$filtered_field_name=='own_father_name' 		||
					$filtered_field_name=='own_mother_name' 		||
					$filtered_field_name=='own_mobile' 				||
					$filtered_field_name=='own_national_id' 				
				) $own_info_str.=$concat_str;

				else if
				(		
					$filtered_field_name=='own_pre_ward' 			|| 
					$filtered_field_name=='own_pre_moholla' 		|| 
					$filtered_field_name=='own_pre_post' 			||
					$filtered_field_name=='own_pre_thana' 			||
					$filtered_field_name=='own_pre_district' 			
				) $own_pre_str.=$concat_str;

				else if
				(		
					$filtered_field_name=='own_per_ward' 			|| 
					$filtered_field_name=='own_per_moholla' 		|| 
					$filtered_field_name=='own_per_post' 			||
					$filtered_field_name=='own_per_thana' 			||
					$filtered_field_name=='own_per_district' 			
				) $own_per_str.=$concat_str;

				else $con_str.=$concat_str;		?>

		<?php 
		}
		echo "<br/><div class='full first fieldset' id='app_info'>";
				echo '<div class="legend"><span>Applicant\'s Information</span></div>';
				echo $app_info_str;
			echo "</div>";

			echo "<div class='full clearfix fieldset' id='app_info'>";
				echo '<div class="legend"><span>Applicant\'s Address</span></div>';
				echo "<div class='half pull-left fieldset' id='app_pre_address'>";
					echo '<div class="legend"><span>Present</span></div><br/><br/>';
					echo $app_pre_str;
				echo "</div>";
				echo "<div class='half pull-right fieldset' id='app_per_address'>";
					echo '<div class="legend"><span>Permanent</span></div>';
					echo "<input type='checkbox' id='app_same_as_per'/> Same as present<br/><br/>";
					echo $app_per_str;
				echo "</div>";
			echo "</div>";

			echo "<div class='full fieldset' id='own_info'>";
				echo '<div class="legend"><span>Owner\'s Information</span></div>';
				echo "<input type='checkbox' id='is_owner'/> Applicant is owner<br/><br/>";
				echo $own_info_str;
			echo "</div>";

			echo "<div class='full clearfix fieldset' id='app_info'>";
				echo '<div class="legend"><span>Owner\'s Address</span></div>';
				echo "<div class='half pull-left fieldset' id='own_pre_address'>";
					echo '<div class="legend"><span>Present</span></div><br/><br/>';
					echo $own_pre_str;
				echo "</div>";
				echo "<div class='half pull-right fieldset' id='own_per_address'>";
					echo '<div class="legend"><span>Permanent</span></div>';
					echo "<input type='checkbox' id='own_same_as_per'/> Same as present<br/><br/>";
					echo $own_per_str;
				echo "</div>";
			echo "</div>";

			echo "<div class='full fieldset' id='connection_info'>";
				echo '<div class="legend"><span>Connection Information</span></div>';
				echo $con_str;
			echo "</div>";		
		?>

		<?php if(!empty($hidden_fields)){?>
		<!-- Start of hidden inputs -->
		<?php
			foreach($hidden_fields as $hidden_field){
				echo $hidden_field->input;
			}
		?>
		<!-- End of hidden inputs -->
		<?php }?>
		<?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php }?>
		<div id='report-error' class='report-div error'></div>
		<div id='report-success' class='report-div success'></div>
	</div>
	<div class="pDiv">
		<div class='form-button-box'>
			<input  id="form-button-save" type='submit' value='<?php echo $this->l('form_update_changes'); ?>' class="btn btn-large btn-info"/>
		</div>
<?php 	if(!$this->unset_back_to_list) { ?>
		<div class='form-button-box'>
			<input type='button' value='<?php echo $this->l('form_update_and_go_back'); ?>' id="save-and-go-back-button" class="btn btn-large btn-info"/>
		</div>
		<div class='form-button-box'>
			<input type='button' value='<?php echo $this->l('form_cancel'); ?>' class="btn btn-large btn-warning" id="cancel-button" />
		</div>
<?php 	} ?>
		<div class='form-button-box'>
			<div class='small-loading' id='FormLoading'><?php echo $this->l('form_update_loading'); ?></div>
		</div>
		<div class='clear'></div>
	</div>
	<?php echo form_close(); ?>
</div>
</div>
<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_edit_form = "<?php echo $this->l('alert_edit_form')?>";
	var message_update_error = "<?php echo $this->l('update_error')?>";
</script>