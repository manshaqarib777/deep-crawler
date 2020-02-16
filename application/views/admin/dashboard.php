<?php if($this->session->userdata("user_type")=="Member") {?>
<div class="row" style="padding:20px 10px;"">
	<div class="col-xs-12 col-md-6">
		<div class="info-box bg-aqua">
			<span class="info-box-icon"><i class="fa fa-cube"></i></span>
			<div class="info-box-content">
				<!-- <span class="info-box-text">Inventory</span> -->
				<span class="info-box-number">
				   <?php if($price=="Trial") $price=0; ?>
				   <?php echo $package_name;?> @
				   <?php echo $payment_config[0]['currency']; ?> <?php echo $price;?> /
				   <?php echo $validity;?> <?php echo $this->lang->line("days")?>	
				</span>
				<div class="progress">
					<div style="width: 70%" class="progress-bar"></div>
				</div>
				<span class="progress-description">
					<b><?php echo $this->lang->line("package info")?></b>
				</span>
			</div><!-- /.info-box-content -->
		</div>	
	</div>
	<div class="col-xs-12 col-md-6">
		<div class="info-box bg-blue">
			<span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
			<div class="info-box-content">
				<!-- <span class="info-box-text">Inventory</span> -->
				<span class="info-box-number">
				    <?php echo date("Y-m-d",strtotime($this->session->userdata("expiry_date"))); ?>
				</span>
				<div class="progress">
					<div style="width: 70%" class="progress-bar"></div>
				</div>
				<span class="progress-description">
					<b><?php echo $this->lang->line("expiry date")?>: yyyy-mm-dd</b>
				</span>
			</div><!-- /.info-box-content -->
		</div>	
	</div>
</div>
<?php } ?>



<div class="row" style="padding:10px;">
	<div class="col-xs-12 col-md-6">
		<h2 class="text-center">Social Network or Custom Site's Email Report</h2>
		<div class="box-body">
			<div class="row">
				<div class="col-md-8 col-xs-12">
					<div class="chart-responsive">
						<canvas id="pieChart" height="200"></canvas>
					</div><!-- ./chart-responsive -->
				</div><!-- /.col -->
				<div class="col-md-4 col-xs-12" style="padding-top:15px;">
					<ul class="chart-legend clearfix">
						<li><i class="fa fa-circle-o text-red"></i> Facebook.com</li>
						<li><i class="fa fa-circle-o text-green"></i> Twitter.com</li>
						<li><i class="fa fa-circle-o text-yellow"></i> Linkedin.com</li>
						<li><i class="fa fa-circle-o text-aqua"></i> Pinterest.com</li>
						<li><i class="fa fa-circle-o text-light-blue"></i> Tumblr.com</li>
						<li><i class="fa fa-circle-o text-gray"></i> Reddit.com</li>
						<li><i class="fa fa-circle-o" style="color:#a4d2ed"></i> Flickr.com</li>
						<li><i class="fa fa-circle-o" style="color:#b3a4de"></i> Instagram.com</li>
						<li><i class="fa fa-circle-o" style="color:purple"></i> Others</li>
					</ul>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.box-body -->
	</div>

	<div class="col-xs-12 col-md-6">
		<h2 class="text-center">Page Status Report</h2><br/>
		<div class="box-body">
			<div class="row">
				<div class="col-md-8 col-xs-12">
					<div class="chart-responsive">
						<canvas id="page_pieChart" height="200"></canvas>
					</div><!-- ./chart-responsive -->
				</div><!-- /.col -->
				<div class="col-md-4 col-xs-12" style="padding-top:30px;">
					<ul class="chart-legend clearfix">
						<li><i class="fa fa-circle-o text-red"></i> Total Page Check</li>
						<li><i class="fa fa-circle-o text-green"></i> 200 HTTP Code (OK)</li>
						<li><i class="fa fa-circle-o text-yellow"></i> Other HTTP Response</li>
					</ul>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.box-body -->
	</div>
</div>


<!-- Small boxes (Stat box) -->
<div class="row" style="padding:10px;">
	<div class="col-xs-12"><h2 class="text-center">TOTAL &nbsp REPORT</h2></div>
	<div class="col-lg-3 col-xs-12 col-md-3">
		<!-- small box -->
		<div class="small-box bg-yellow">
			<div class="inner">
				<h3><?php if(isset($total_report['unique_website'])) echo $total_report['unique_website']; else echo 0; ?></h3>
				<p>Total Unique Website</p>
			</div>
			<div class="icon">
				<i class="fa fa-magnet"></i>
			</div>
			<a href="<?php echo site_url('admin/website_search'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div><!-- ./col -->
	<div class="col-lg-3 col-xs-12 col-md-3">
		<!-- small box -->
		<div class="small-box bg-green">
			<div class="inner">
				<h3><?php if(isset($total_report['total_url'])) echo $total_report['total_url']; else echo 0; ?></h3>
				<p>Total URL Crawl</p>
			</div>
			<div class="icon">
				<i class="fa fa-crosshairs"></i>
			</div>
			<a href="<?php echo site_url('admin/url_search'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div><!-- ./col -->
	<div class="col-lg-3 col-xs-12 col-md-3">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3><?php if(isset($total_report['search_crawl'])) echo $total_report['search_crawl']; else echo 0; ?></h3>
				<p>Total Search Crawl</p>
			</div>
			<div class="icon">
				<i class="fa fa-binoculars"></i>
			</div>
			<a href="<?php echo site_url('admin/searchengine_search'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div><!-- ./col -->
	<div class="col-lg-3 col-xs-12 col-md-3">
		<!-- small box -->
		<div class="small-box bg-aqua">
			<div class="inner">
				<h3><?php if(isset($total_report['unique_email'])) echo $total_report['unique_email']; else echo 0; ?></h3>
				<p>Total Unique Email</p>
			</div>
			<div class="icon">
				<i class="fa fa-envelope"></i>
			</div>
			<a href="<?php echo site_url('admin_advance/unique_email_maker'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div><!-- ./col -->
</div><!-- /.row -->

<!-- Info boxes -->
<div class="row" style="padding:10px;">
	<div class="col-xs-12"><h2 class="text-center">TODAY'S &nbsp REPORT</h2></div>
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box" style="border:1px solid #00BFEE;border-bottom:2px solid #00BFEE;">
			<span class="info-box-icon bg-aqua"><i class="fa fa-magnet"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Total Unique Website</span>
				<span class="info-box-number"><?php if(isset($today_report['unique_website'])) echo $today_report['unique_website']; else echo 0; ?></span>
			</div><!-- /.info-box-content -->
		</div><!-- /.info-box -->
	</div><!-- /.col -->
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box" style="border:1px solid #DD4B39;border-bottom:2px solid #DD4B39;">
			<span class="info-box-icon bg-red"><i class="fa fa-crosshairs"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Total URL Crawl</span>
				<span class="info-box-number"><?php if(isset($today_report['total_url'])) echo $today_report['total_url']; else echo 0; ?></span>
			</div><!-- /.info-box-content -->
		</div><!-- /.info-box -->
	</div><!-- /.col -->

	<!-- fix for small devices only -->
	<!-- <div class="clearfix visible-sm-block"></div> -->

	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box" style="border:1px solid #00A65A;border-bottom:2px solid #00A65A;">
			<span class="info-box-icon bg-green"><i class="fa fa-binoculars"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Total Search Crawl</span>
				<span class="info-box-number"><?php if(isset($today_report['search_crawl'])) echo $today_report['search_crawl']; else echo 0; ?></span>
			</div><!-- /.info-box-content -->
		</div><!-- /.info-box -->
	</div><!-- /.col -->
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box" style="border:1px solid #F39C12;border-bottom:2px solid #F39C12;">
			<span class="info-box-icon bg-yellow"><i class="fa fa-envelope"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Total Unique Email</span>
				<span class="info-box-number"><?php if(isset($today_report['unique_email'])) echo $today_report['unique_email']; else echo 0; ?></span>
			</div><!-- /.info-box-content -->
		</div><!-- /.info-box -->
	</div><!-- /.col -->
</div><!-- /.row -->

<div class="row" style="padding:10px;">
	<!-- Info Boxes Style 2 -->
	<div class="col-xs-12"><h2 class="text-center">WHOIS &nbsp REPORT</h2></div>
	<div class="col-md-4 col-md-offset-2 col-sm-6 col-xs-12">
		<div class="info-box bg-aqua">
			<span class="info-box-icon"><i class="fa fa-street-view"></i></span>
			<div class="info-box-content">
				<!-- <span class="info-box-text">Inventory</span> -->
				<span class="info-box-number"><?php if(isset($whois_report['total_registered'])) echo $whois_report['total_registered']; else echo 0; ?></span>
				<div class="progress">
					<div class="progress-bar" style="width: 70%"></div>
				</div>
				<span class="progress-description">
					<b>Total Registered Domain</b>
				</span>
			</div><!-- /.info-box-content -->
		</div><!-- /.info-box -->
	</div>
	<div class="col-md-4 col-sm-6 col-xs-12">
		<div class="info-box bg-red">
			<span class="info-box-icon"><i class="fa fa-street-view"></i></span>
			<div class="info-box-content">
				<!-- <span class="info-box-text">Mentions</span> -->
				<span class="info-box-number"><?php if(isset($whois_report['total_unregistered'])) echo $whois_report['total_unregistered']; else echo 0; ?></span>
				<div class="progress">
					<div class="progress-bar" style="width: 70%"></div>
				</div>
				<span class="progress-description">
					<b>Total Unregistered Domain</b>
				</span>
			</div><!-- /.info-box-content -->
		</div><!-- /.info-box -->
	</div>	
</div>

<div class="row" style="padding:10px;">
	<div class="col-xs-12 col-md-6">
		<h2 class="text-center">Google Vs Bing Success Rate (%)</h2><br/>
		<div class="box-body">
			<div class="row">
				<div class="col-md-8 col-xs-12">
					<div class="chart-responsive">
						<canvas id="success_pieChart" height="200"></canvas>
					</div><!-- ./chart-responsive -->
				</div><!-- /.col -->
				<div class="col-md-4 col-xs-12" style="padding-top:30px;">
					<ul class="chart-legend clearfix">
						<li><i class="fa fa-circle-o" style="color:#00a65a"></i> Google Success Rate</li>
						<li><i class="fa fa-circle-o" style="color:orange"></i> Bing Success Rate</li>
					</ul>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.box-body -->
	</div>
	<div class="col-xs-12 col-md-6">
		<h2 class="text-center">Google Vs Bing Found Email</h2><br/>
		<div class="box-body">
			<div class="row">
				<div class="col-md-8 col-xs-12">
					<div class="chart-responsive">
						<canvas id="found_email_pieChart" height="200"></canvas>
					</div><!-- ./chart-responsive -->
				</div><!-- /.col -->
				<div class="col-md-4 col-xs-12" style="padding-top:30px;">
					<ul class="chart-legend clearfix">
						<li><i class="fa fa-circle-o" style="color:#00c0ef"></i> Found Email in Google</li>
						<li><i class="fa fa-circle-o" style="color:#f56954"></i> Found Email in Bing</li>
					</ul>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.box-body -->
	</div>
</div>

<div class="row" style="padding:10px;">
	<h2 class="text-center">Google Vs Bing Found Email Report For Last 12 Months</h2>
	<div id="div_for_bar"></div>
</div>

<script type="text/javascript">
var total_issued_dis="Google";
var total_retuned_dis="Bing";
Morris.Bar({
  element: 'div_for_bar',
  data: <?php echo json_encode($bar); ?>,
  xkey: 'year',
  ykeys: ['google', 'bing'],
  labels: [total_issued_dis, total_retuned_dis]
});
	

  //-------------
  //- PIE CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  // var Facebook = ""
  var pieChartCanvas = $j("#pieChart").get(0).getContext("2d");
  var pieChart = new Chart(pieChartCanvas);
  var PieData = [
    {
      value: "<?php if(isset($social_network['facebook']['total_email'])) echo $social_network['facebook']['total_email']; else echo 0; ?>",
      color: "#f56954",
      highlight: "#f56954",
      label: "Emails From Facebook"
    },
    {
      value: "<?php if(isset($social_network['twitter']['total_email'])) echo $social_network['twitter']['total_email']; else echo 0; ?>",
      color: "#00a65a",
      highlight: "#00a65a",
      label: "Emails From Twitter"
    },
    {
      value: "<?php if(isset($social_network['linkedin']['total_email'])) echo $social_network['linkedin']['total_email']; else echo 0; ?>",
      color: "#f39c12",
      highlight: "#f39c12",
      label: "Emails From Linkedin"
    },
    {
      value: "<?php if(isset($social_network['pinterest']['total_email'])) echo $social_network['pinterest']['total_email']; else echo 0; ?>",
      color: "#00c0ef",
      highlight: "#00c0ef",
      label: "Emails From Pinterest"
    },
    {
      value: "<?php if(isset($social_network['tumblr']['total_email'])) echo $social_network['tumblr']['total_email']; else echo 0; ?>",
      color: "#3c8dbc",
      highlight: "#3c8dbc",
      label: "Emails From Tumblr"
    },
    {
      value: "<?php if(isset($social_network['reddit']['total_email'])) echo $social_network['reddit']['total_email']; else echo 0; ?>",
      color: "#d2d6de",
      highlight: "#d2d6de",
      label: "Emails From Reddit"
    },
    {
      value: "<?php if(isset($social_network['flickr']['total_email'])) echo $social_network['flickr']['total_email']; else echo 0; ?>",
      color: "#a4d2ed",
      highlight: "#a4d2ed",
      label: "Emails From Flickr"
    },
    {
      value: "<?php if(isset($social_network['instagram']['total_email'])) echo $social_network['instagram']['total_email']; else echo 0; ?>",
      color: "#b3a4de",
      highlight: "#b3a4de",
      label: "Emails From Instagram"
    },
    {
      value: "<?php if(isset($social_network['other']['total_email'])) echo $social_network['other']['total_email']; else echo 0; ?>",
      color: "purple",
      highlight: "purple",
      label: "Emails From Others"
    }
  ];

  var pieOptions = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke: true,
    //String - The colour of each segment stroke
    segmentStrokeColor: "#fff",
    //Number - The width of each segment stroke
    segmentStrokeWidth: 1,
    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 50, // This is 0 for Pie charts
    //Number - Amount of animation steps
    animationSteps: 100,
    //String - Animation easing effect
    animationEasing: "easeOutBounce",
    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate: true,
    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale: false,
    //Boolean - whether to make the chart responsive to window resizing
    responsive: true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: false,
    //String - A legend template
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
    //String - A tooltip template
    tooltipTemplate: "<%=value %> <%=label%>"
  };
  pieChart.Doughnut(PieData, pieOptions);



  var page_pieChartCanvas = $j("#page_pieChart").get(0).getContext("2d");
  var page_pieChart = new Chart(page_pieChartCanvas);
  var page_PieData = [
    {
      value: "<?php if(isset($page_status['total_page'])) echo $page_status['total_page']; else echo 0; ?>",
      color: "#f56954",
      highlight: "#f56954",
      label: "Total Page Check"
    },
    {
      value: "<?php if(isset($page_status['total_200'])) echo $page_status['total_200']; else echo 0; ?>",
      color: "#00a65a",
      highlight: "#00a65a",
      label: "200 HTTP Code (OK)"
    },
    {
      value: "<?php if(isset($page_status['not_total_200'])) echo $page_status['not_total_200']; else echo 0; ?>",
      color: "#f39c12",
      highlight: "#f39c12",
      label: "Other HTTP Response"
    }
  ];

  var page_pieOptions = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke: true,
    //String - The colour of each segment stroke
    segmentStrokeColor: "#fff",
    //Number - The width of each segment stroke
    segmentStrokeWidth: 1,
    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 50, // This is 0 for Pie charts
    //Number - Amount of animation steps
    animationSteps: 100,
    //String - Animation easing effect
    animationEasing: "easeOutBounce",
    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate: true,
    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale: false,
    //Boolean - whether to make the chart responsive to window resizing
    responsive: true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: false,
    //String - A legend template
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
    //String - A tooltip template
    tooltipTemplate: "<%=value %> <%=label%> "
  };
  page_pieChart.Doughnut(page_PieData, page_pieOptions);




  var success_pieChartCanvas = $j("#success_pieChart").get(0).getContext("2d");
  var success_pieChart = new Chart(success_pieChartCanvas);
  var success_PieData = [
    {
      value: "<?php echo $google_success_rate; ?>",
      color: "#00a65a",
      highlight: "#00a65a",
      label: "Google Success Rate"
    },
    {
      value: "<?php echo $bing_success_rate; ?>",
      color: "orange",
      highlight: "orange",
      label: "Bing Success Rate"
    }
  ];

  var success_pieOptions = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke: true,
    //String - The colour of each segment stroke
    segmentStrokeColor: "#fff",
    //Number - The width of each segment stroke
    segmentStrokeWidth: 1,
    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 0, // This is 0 for Pie charts
    //Number - Amount of animation steps
    animationSteps: 100,
    //String - Animation easing effect
    animationEasing: "easeOutBounce",
    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate: true,
    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale: false,
    //Boolean - whether to make the chart responsive to window resizing
    responsive: true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: false,
    //String - A legend template
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
    //String - A tooltip template
    tooltipTemplate: "<%=value %> <%=label%> "
  };
  success_pieChart.Doughnut(success_PieData, success_pieOptions);


  var found_email_pieChartCanvas = $j("#found_email_pieChart").get(0).getContext("2d");
  var found_email_pieChart = new Chart(found_email_pieChartCanvas);
  var found_email_PieData = [
    {
      value: "<?php echo $google_found_email; ?>",
      color: "#00c0ef",
      highlight: "#00c0ef",
      label: "Email Found in Google"
    },
    {
      value: "<?php echo $bing_found_email; ?>",
      color: "#f56954",
      highlight: "#f56954",
      label: "Email Found in Bing"
    }
  ];

  var found_email_pieOptions = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke: true,
    //String - The colour of each segment stroke
    segmentStrokeColor: "#fff",
    //Number - The width of each segment stroke
    segmentStrokeWidth: 1,
    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 0, // This is 0 for Pie charts
    //Number - Amount of animation steps
    animationSteps: 100,
    //String - Animation easing effect
    animationEasing: "easeOutBounce",
    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate: true,
    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale: false,
    //Boolean - whether to make the chart responsive to window resizing
    responsive: true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: false,
    //String - A legend template
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
    //String - A tooltip template
    tooltipTemplate: "<%=value %> <%=label%> "
  };
  found_email_pieChart.Doughnut(found_email_PieData, found_email_pieOptions);



</script>
