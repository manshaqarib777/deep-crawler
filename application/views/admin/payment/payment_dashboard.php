<!-- Small boxes (Stat box) -->
<?php 
if($total_user=="") $total_user=0;
if($total_paid_amount=="") $total_paid_amount=0;
if($this_month_total_user=="") $this_month_total_user=0;
if($this_month_paid_amount=="") $this_month_paid_amount=0;
if($today_user=="") $today_user=0;
if($today_paid_amount=="") $today_paid_amount=0;
 ?>
<div class="row" style="padding:10px;">
	<div class="col-xs-12"><h2 class="text-center" style="color:olive;"><?php echo $this->lang->line("total report") ?></h2></div>
	<div class="col-lg-4 col-xs-12 col-md-4 col-md-offset-2 col-lg-offset-2">
		<!-- small box -->
		<div class="small-box bg-green">
			<div class="inner">
				<h3><?php echo $total_user; ?></h3>
				<p><?php echo $this->lang->line("total users") ?></p>
			</div>
			<div class="icon">
				<i class="fa fa-users"></i>
			</div>
			<a href="<?php echo site_url('admin/admin_payment_history'); ?>" class="small-box-footer"><?php echo $this->lang->line("more info") ?> <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div><!-- ./col -->
	<div class="col-lg-4 col-xs-12 col-md-4">
		<!-- small box -->
		<div class="small-box bg-yellow">
			<div class="inner">
				<h3><?php echo $total_paid_amount." ".$currency; ?></h3>
				<p><?php echo $this->lang->line("total paid amount") ?></p>
			</div>
			<div class="icon">
				<i class="fa fa-paypal"></i>
			</div>
			<a href="<?php echo site_url('payment/admin_payment_history'); ?>" class="small-box-footer"><?php echo $this->lang->line("more info") ?> <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div><!-- ./col -->
</div><!-- /.row -->

<!-- Small boxes (Stat box) -->
<div class="row" style="padding:10px;">
	<div class="col-xs-12"><h2 class="text-center" style="color:olive;"><?php echo $this->lang->line("this month's report") ?></h2></div>
	<div class="col-md-4 col-md-offset-2 col-sm-6 col-xs-12">
		<div class="info-box" style="border:1px solid #00A65A;border-bottom:2px solid #00A65A;">
			<span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
			<div class="info-box-content">
				<span class="info-box-text"><?php echo $this->lang->line("new users") ?></span>
				<span class="info-box-number"><?php echo $this_month_total_user; ?></span>
			</div><!-- /.info-box-content -->
		</div><!-- /.info-box -->
	</div><!-- /.col -->
	<div class="col-md-4 col-sm-6 col-xs-12">
		<div class="info-box" style="border:1px solid #F39C12;border-bottom:2px solid #F39C12;">
			<span class="info-box-icon bg-yellow"><i class="fa fa-paypal"></i></span>
			<div class="info-box-content">
				<span class="info-box-text"><?php echo $this->lang->line("total paid amount") ?></span>
				<span class="info-box-number"><?php echo $this_month_paid_amount." ".$currency; ?></span>
			</div><!-- /.info-box-content -->
		</div><!-- /.info-box -->
	</div><!-- /.col -->
</div><!-- /.row -->

<div class="row" style="padding:10px;">
	<!-- Info Boxes Style 2 -->
	<div class="col-xs-12"><h2 class="text-center" style="color:olive;"><?php echo $this->lang->line("today's report") ?></h2></div>
	<div class="col-md-4 col-md-offset-2 col-sm-6 col-xs-12">
		<div class="info-box bg-green">
			<span class="info-box-icon"><i class="fa fa-users"></i></span>
			<div class="info-box-content">
				<!-- <span class="info-box-text">Inventory</span> -->
				<span class="info-box-number"><?php echo $today_user; ?></span>
				<div class="progress">
					<div class="progress-bar" style="width: 70%"></div>
				</div>
				<span class="progress-description">
					<b><?php echo $this->lang->line("new users") ?></b>
				</span>
			</div><!-- /.info-box-content -->
		</div><!-- /.info-box -->
	</div>
	<div class="col-md-4 col-sm-6 col-xs-12">
		<div class="info-box bg-yellow">
			<span class="info-box-icon"><i class="fa fa-paypal"></i></span>
			<div class="info-box-content">
				<!-- <span class="info-box-text"><?php echo $today_paid_amount; ?></span> -->
				<span class="info-box-number"><?php echo $today_paid_amount." ".$currency; ?></span>
				<div class="progress">
					<div class="progress-bar" style="width: 70%"></div>
				</div>
				<span class="progress-description">
					<b><?php echo $this->lang->line("total paid amount") ?></b>
				</span>
			</div><!-- /.info-box-content -->
		</div><!-- /.info-box -->
	</div>	
</div>