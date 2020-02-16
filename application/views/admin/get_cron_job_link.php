<div class="row" style="padding-top:30px;">
	<div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
		<div class="alert alert-info">
			<h2 class="text-center" style="margin-top:0px;">Automatic Notification</h2>
			<ul>
				<li>You can set up a cron job to send automatic notifications to users about their accounts' expiration validity. </li>
				<li>It will send email notification 10 days before validity ends, 1 day before validity end and after validity end. </li>
				
			</ul>
			<h4 class="text-center" style="margin-top:10px;margin-bottom:0px;">Your cron job command is given below</h4>
			<p class="text-center"><b>curl <?php echo site_url("home/send_notification/$key"); ?></b></p>
		</div>
	</div>
</div>