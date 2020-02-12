<?php ///echo "<pre>"; print_r($info); ?>
<?php //echo "<pre>"; print_r($bulk_limit); ?>
<?php //echo "<pre>"; print_r($info); ?>

<?php $this->load->view('admin/theme/message'); ?>

<?php $cur_month=date("n"); ?>
<?php $cur_year=date("Y"); ?>
<?php 
if($cur_month==1) $month="";
else if($cur_month==2) $month="cal_jan";
else if($cur_month==3) $month="cal_feb";
else if($cur_month==4) $month="cal_ma";
else if($cur_month==5) $month="cal_apr";
else if($cur_month==6) $month="cal_may";
else if($cur_month==7) $month="cal_jun";
else if($cur_month==8) $month="cal_jul";
else if($cur_month==9) $month="cal_aug";
else if($cur_month==10) $month="cal_sep";
else if($cur_month==11) $month="cal_oct";
else if($cur_month==12) $month="cal_nov";
?>

<!-- Main content -->
<section class="content-header">
	<h1 class = 'text-info'> <?php echo $this->lang->line("usage log");?> : <?php echo $this->lang->line($month)."-".$cur_year ?></h1>
</section>
<section class="content">  
	<div class="row" >
		<div class="col-xs-12">		

			
			<div class="grid_container well table-responsive" style="width:auto;background:#fff;border:1px solid #ccc;padding:20px">
				<h3 class='text-center'>
					<div class="well">			
				 	   <?php if($price=="Trial") $price=0; ?>
					   <?php echo $this->lang->line("package name")?> : 
					   <?php echo $package_name;?> @
					   <?php echo $payment_config[0]['currency']; ?> <?php echo $price;?> /
					   <?php echo $validity;?> <?php echo $this->lang->line("days")?>	<br/><br/>
					   <?php echo $this->lang->line("expired date");?> : <?php echo date("Y-m-d",strtotime($this->session->userdata("expiry_date"))); ?>			
					</div>
				</h3>	
				<table class="table table-bordered table-striped table-hover">
	               		<tr class="warning">
	               			<th></th>
	               			<th><?php echo $this->lang->line("Modules");?></th>
	               			<th class="text-center"><?php echo $this->lang->line("Analysis Limit");?></th>
	               			<th class="text-center"><?php echo $this->lang->line("Analysis Done");?></th>
	               			<th class="text-center"><?php echo $this->lang->line("Bulk Limit");?></th>
	               		</tr>
	               	 	<?php 
	               	 	$i=0;
	               	 	foreach($info as $row)
	               	 	{
		               	 	$i++;
		               	 	$str="";
		               	 	if(!in_array($row["module_id"],$this->module_access)) $str="X";
		               	 	echo "<tr>";
		               	 		echo "<td class='text-center'>";
			               	 		echo $i;
			               	 	echo "</td>";
			               	 	echo "<td>";
			               	 		echo $this->lang->line($row["module_name"]);
			               	 	echo "</td>";

			               	 	if(in_array($row["module_id"], array("13","14","16")))
			               	 	{
			               	 		echo "<td colspan='3'></td>";
			               	 		echo "</tr>";
			               	 		continue;
			               	 	}

			               	 	echo "<td class='text-center'>";
			               	 		if($str!="") echo $str;
			               	 		else
			               	 		{
			               	 			if($monthly_limit[$row["module_id"]]=="0") $monthly_limit[$row["module_id"]]=$this->lang->line("unlimited");
			               	 			if(isset($monthly_limit[$row["module_id"]])) echo $monthly_limit[$row["module_id"]];
			               	 		}

			               	 	echo "</td>";

			               	 	echo "<td class='text-center'>";
			               	 		if($str!="") echo $str;
			               	 		else
			               	 		{
			               	 			if(isset($row["usage_count"])) echo $row["usage_count"];
			               	 			else echo "0";
			               	 		}
			               	 	echo "</td>";

			               	 	echo "<td class='text-center'>";
			               	 		if($str!="") echo $str;
			               	 		else
			               	 		{	
			               	 			if(isset($bulk_limit[$row["module_id"]])) 
			               	 			{
			               	 				if($bulk_limit[$row["module_id"]]=="0") $bulk_limit[$row["module_id"]]=$this->lang->line("unlimited");
			               	 				echo $bulk_limit[$row["module_id"]];
			               	 			}
			               	 		}
			               	 	echo "</td>";	

		               	 	echo "</tr>";
	               	 	} 
	               	 	?>
	              </table>                      
			</div>

		</div>        
	</div> 
</section>

