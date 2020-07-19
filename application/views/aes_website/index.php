<!doctype html>
<html class="no-js" lang="">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?php echo $this->config->item("product_name"); ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">
	<link rel="icon" href="<?php echo site_url(); ?>assets/images/favicon.png" type="image/x-icon"/>
	<!-- Place favicon.ico in the root directory -->
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,400italic,700,700italic|Lato:400,700,400italic' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?php echo site_url(); ?>aes_website/css/animate.css">
	<link rel="stylesheet" href="<?php echo site_url(); ?>aes_website/css/font-awesome.css">
	<link rel="stylesheet" href="<?php echo site_url(); ?>aes_website/css/bootstrap.css">
	<link rel="stylesheet" href="<?php echo site_url(); ?>aes_website/css/normalize.css">
	<link rel="stylesheet" href="<?php echo site_url(); ?>aes_website/css/main.css">
	<link rel="stylesheet" href="<?php echo site_url(); ?>aes_website/css/pricing.css">
	<link rel="stylesheet" href="<?php echo site_url(); ?>aes_website/style.css">
	<script src="<?php echo site_url(); ?>aes_website/js/vendor/modernizr-2.8.3.min.js"></script>
</head>
<body>
	<!-- Preloader -->
	<div id="preloader">
		<div id="status">&nbsp;</div>
	</div>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<!-- Add your site or application content here -->
<nav class="navbar navbar-default custom-navbar">
	<div class="custom-container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand custom-brand" href="<?php echo site_url(); ?>"><img src="<?php echo site_url(); ?>aes_website/img/deepcrawllogo.svg"  style="margin-top:15px;width:200px !important;" alt="<?php echo $this->config->item('product_name'); ?>"></a>
		</div>
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav custom-nav navbar-right">
				<li><a data-scroll href="#">Home</a></li>
				<li><a data-scroll href="#features">Feature</a></li>
				<li><a data-scroll href="#howitsworks">How It works?</a></li>
				<li><a data-scroll href="https://scraper.piktd.com/scrapper">Scrapper</a></li>
				<li><a href="<?php echo site_url('home/sign_up'); ?>">Sign Up</a></li>
				<li><a target="_blank" href="<?php echo site_url('home/login_page'); ?>">Log In</a></li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container -->
</nav> <!-- end nav bar -->


<div class="container-fluid top-margin"> <!-- start slider image -->
	<div class="row">
		<div class="hidden-xs hidden-sm col-md-12 no-padding">
			<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" >
				<!-- Indicators -->
				<ol class="carousel-indicators">
					<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
					<li data-target="#carousel-example-generic" data-slide-to="1"></li>
					<li data-target="#carousel-example-generic" data-slide-to="2"></li>
					<!-- <li data-target="#carousel-example-generic" data-slide-to="3"></li> -->
				</ol>

				<!-- Wrapper for slides -->
				<div class="carousel-inner" role="listbox">
					<div class="item active">
						<img src="<?php echo site_url(); ?>aes_website/img/slider/slider-1.png" class="carousel-img" alt="slider">
						<div class="carousel-caption text-center">
							<h3><?php echo $this->config->item('product_name'); ?></h3>
							<p>
							All crawl history in awesome dashboard. Website crawl, url crawl, search engine comparison,email found comparison, whois data report, page status report , all are in a single eye catching dashboard.
							</p>
							<br/><button type="button" class="btn btn-warning buy-it-now-btn">Read More</button>
						</div>
					</div>
					<div class="item">
						<img src="<?php echo site_url(); ?>aes_website/img/slider/slider-2.png" class="carousel-img" alt="slider">
						<div class="carousel-caption">
							<h3><?php echo $this->config->item('product_name'); ?></h3>
							<p><?php echo $this->config->item('product_name'); ?> is most powerful web based tool to extract emails by various techniques like website crawl, URL crawl, search in Google/Bing, search in txt file.It has ability to scrape Websites.The ASCII encoded email can be decoded by this tool.Not only that by this tools can check email validation (pattern, MX record) , search for whois data, filter your email list by removing duplicate emails, check web page status .It is designed carefully so that you can install and use it very easily. </p>
							<br/><button type="button" class="btn btn-warning buy-it-now-btn">Read More</button>
						</div>
					</div>
					<div class="item">
						<img src="<?php echo site_url(); ?>aes_website/img/slider/slider-3.png" class="carousel-img" alt="slider">
						<div class="carousel-caption">
							<h3>Crawl website and extract email</h3>
							<p>
								Crawl full website. Crawl bulk url , uploading it from txt/csv file. It can decrypt ecoded email. So no problem to scrape websites. 
							</p>
							<br/><button type="button" class="btn btn-warning buy-it-now-btn">Read More</button>
						</div>
					</div>
				</div>

				<!-- Controls -->
				<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				</a>
			</div>
		</div> <!-- end col md 12 -->
	</div> <!-- end row -->
</div> <!-- end container fluid -->




<section id="about">
	<div class="discription-bg padding-top-50">
		<div class="container">
			<div class="row">
				<div class="col-md-6 intro-img animated wow zoomInRight">
					<img src="<?php echo site_url(); ?>aes_website/img/img-2.png" class="img-responsive" alt="intro">
				</div>
				<div class="col-md-6 intro-contain animated wow zoomInLeft">
					<h2>
						Welcome to <?php echo $this->config->item('product_name'); ?>
					</h2>
					<hr>

					<p>
						<?php echo $this->config->item('product_name'); ?> is most powerful web based tool to extract emails by various techniques like website crawl, URL crawl, search in Google/Bing, search in txt file. Not only that by this tools can check email validation (pattern, MX record) , search for whois data, filter your email list by removing duplicate emails, check web page status .It is designed carefully so that you can install and use it very easily.
					</p>
				</div>
			</div> <!-- end row -->
		</div> <!-- end container -->
	</div> <!-- end container bg -->
</section>


<section id="features" data-type="background" data-speed="5" class="padding-top-200">
	<div class="container Key-features">
		<div class="features-margin">
			<div class="row rowmargin">
				<div class="col-md-12">
					<h2>
						Key Features
					</h2>
				</div>
			</div> <!-- end row -->
			
			<div class="row">
				<div class="col-md-3 feature-margin animated wow slideInLeft">
					<img src="<?php echo site_url(); ?>aes_website/img/icon/web-crawling.png" alt="Web Crawling">
					<h3>
						Web Crawling
					</h3>
					<p>
						Enter website address. The tool then start to find all url of the website and scrape web pages from all urls. It can extract encoded email too.
					</p>
				</div>
				<div class="col-md-3 feature-margin animated wow slideInLeft">
					<img src="<?php echo site_url(); ?>aes_website/img/icon/bulk_url.png" alt="Bulk URL Crawling">
					<h3>
						Bulk URL Crawling
					</h3>
					<p>
						Upload your url from text or csv file. Start scraping all url. The tool then start to scrape web pages from all url you have uploaded. 
					</p>
				</div>
				<!-- <div class="col-md-3 feature-margin animated wow slideInLeft">
					<img src="<?php echo site_url(); ?>aes_website/img/icon/google.png" alt="Google Search">
					<h3>
						Google Search
					</h3>
					<p>
					   Search in Google with keyword, then select sites like facebook.com, linkedin.com, twitter.com or other sites to extract desired emails. 
					</p>
				</div>
				<div class="col-md-3 feature-margin animated wow slideInLeft">
					<img src="<?php echo site_url(); ?>aes_website/img/icon/bing.png" alt="Bing Search">
					<h3>
						Bing Search
					</h3>
					<p>
						Search in Bing with keyword, then select sites like facebook.com, linkedin.com, twitter.com or other sites to extract desired emails. 
					</p>
				</div> -->
				<div class="col-md-3 feature-margin animated wow slideInRight">
					<img src="<?php echo site_url(); ?>aes_website/img/icon/whois.png" alt="Whois Search">
					<h3>
						Whois Search
					</h3>
					<p>
						Get the doamin's whois information like admin email, technical email, name server, created date, expired date, last update date, sponsor. Have bulk search ability. 
					</p>
				</div>
				<div class="col-md-3 feature-margin animated wow slideInRight">
					<img src="<?php echo site_url(); ?>aes_website/img/icon/email_validation.png" alt="Email Validation">
					<h3>
						Email Validation
					</h3>
					<p>
						Check email is valid or invalid. Bulk checking facility is available. We check email pattern and MX record for validity checking. 
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3 feature-margin animated wow slideInLeft">
					<img src="<?php echo site_url(); ?>aes_website/img/icon/email_filter.png" alt="Email Filtering">
					<h3>
						Email Filtering
					</h3>
					<p>
						Make your email list by removing duplicate email. Import txt or csv or put email in textarea, export list with unique email as txt / csv. 
							
					</p>
				</div>
				<div class="col-md-3 feature-margin animated wow slideInRight">
					<img src="<?php echo site_url(); ?>aes_website/img/icon/docx.png" alt="doc / docx File Search">
					<h3>
						Search in DOC / DOCX File
					</h3>
					<p>
						Search web pages in doc /docx file. You can upload bulk doc /docx files and extract email from all doc /docx files. 
					</p>
				</div>

				<div class="col-md-3 feature-margin animated wow slideInRight">
					<img src="<?php echo site_url(); ?>aes_website/img/icon/pdf.png" alt="PDF File Search">
					<h3>
						Search in PDF File
					</h3>
					<p>
						Search web pages in pdf file. You can upload bulk PDF files and extract email from all pdf files. 
					</p>
				</div>

				<div class="col-md-3 feature-margin animated wow slideInRight">
					<img src="<?php echo site_url(); ?>aes_website/img/icon/json-xml.png" alt="JSON /  XML File Search">
					<h3>
						Search in JSON /  XML File
					</h3>
					<p>
						Search web pages in JSON /  XML file. You can upload bulk JSON /  XML files and extract email from all JSON /  XML files. 
					</p>
				</div>

				<!-- <div class="col-md-3 feature-margin animated wow slideInRight">
					<img src="<?php echo site_url(); ?>aes_website/img/icon/text.png" alt="Text File Search">
					<h3>
						Search in Text File
					</h3>
					<p>
						Search web pages in text file. You can upload bulk text files and extract email from all text files. 
					</p>
				</div> -->
			</div>
			</div>
		</div>
	</div> <!-- end container -->
</section> <!-- end paralox bg section -->

<section id="howitsworks" class="padding-top-100">
	<div class="container">
		<div class="row">
			<div class="col-md-6 how-its-works animated wow wow slideInLeft">
				<h2>
					How web scraping works ?
				</h2>
				<hr>
				<p>
					Very simple. Just imagine you want to do your product marketing and need url to let them know about your product.Think how much time consuming it is to collect email from google or Bing? We have made this work as easy like a click simple. Let say, you want people who are engaged in webdesign profession. So webdesign is your keyword. Put this keyword in the input field, then select Google or Bing for searching. Then try to more specific by selecting social network from where you want to collect. Then choose what email you want gmail, yahoo, hotmail, outlook etc. You are done! Start scraping. You don’t need to do anything more.Wait and see the magic of the tools.
				</p>
			</div>
			<div class="col-md-6 intro-img animated wow slideInRight">
				<img src="<?php echo site_url(); ?>aes_website/img/img-1.png" class="img-responsive" alt="intro">
			</div>
		</div> <!-- end row -->
	</div> <!-- end container -->
</section>



<!-- <section id="pricing" data-type="background" data-speed="5" class="padding-top-50">
<div class="container">

		<div style="text-align:center;padding-bottom:40px;" class="col-xs-12 demotext">
				<br><br>
				<h2 style="font-size:48px">
					<i class="fa fa-money"></i> Pricing 
				</h2><hr>			
				<h3>
					Get early access to <b><?php echo $this->config->item('product_name'); ?></b> today!
				</h3>
                <?php 
                if(isset($default_package[0])) 
                { ?>
	                Let's try it for <?php echo $default_package[0]["validity"] ?> days  and check yourself<br/><br/>
	                <a class="btn btn-default btn-lg" href="<?php echo site_url('home/sign_up'); ?>">Trial : <?php echo $default_package[0]["validity"] ?> days</a>
	                <?php 
            	} 
            	else 
            	{ ?>
            		<a class="btn btn-default btn-lg" href="<?php echo site_url('home/sign_up'); ?>">Sign Up Now</a>
            	<?php
            	} ?>
                <br><br>
			</div>
	</div> 
</section> -->


<div class="container-fluid">
	<div class="row" >
		<?php 
		$i=0;
		$classes=array(1=>"tiny",2=>"small",3=>"medium",4=>"pro");
		foreach($payment_package as $pack)
		{ 	
		$i++;	
		?>
			<div class="col-xs-12 col-sm-6 col-md-3" style="padding-left:5px;padding-right:5px;">
				<div class="<?php echo $classes[$i]; ?>">
					<div class="pricing-table-header-<?php echo $classes[$i]; ?> text-center">
						<h2><?php echo $pack["package_name"]?></h2>
						<h3>Only @<?php echo $currency; ?> <?php echo $pack["price"]?> / <?php echo $pack["validity"]?> days</h3>
					</div>
					<div class="pricing-table-features" style="text-align:left !important;padding-left:3px;">
					<?php 
						$module_ids=$pack["module_ids"];
						$monthly_limit=json_decode($pack["monthly_limit"],true);
						$module_names_array=$this->basic->execute_query('SELECT module_name,id FROM modules WHERE FIND_IN_SET(id,"'.$module_ids.'") > 0  ORDER BY module_name ASC');  

						foreach ($module_names_array as $row) 
						{
							$limit="0";
							$limit=$monthly_limit[$row["id"]];
							if($limit=="0") $limit="unlimited";
							if($row["id"]!="1" && $limit!="unlimited") $limit=$limit."/month";
							echo "<i class='fa fa-check'></i> ".$row["module_name"];
							echo " : <b>". $limit."</b>"."<br>";
						}
      				?>
					</div>
					<div class="pricing-table-signup-<?php echo $classes[$i]; ?>">
						<p><center><a href="<?php echo site_url('home/sign_up'); ?>"><?php echo $this->lang->line("sign up"); ?></a></center></p>
					</div>
				</div>
			</div>
				
		<?php
		if($i%4==0) break;
		}?>
	</div>
</div>

<div class="container-fluid footer"><!-- start footer content div -->
	<div class="row">
		<div class="col-md-6 footer-text">
			<p class="copyright-text">
				© <?php echo date("Y"); ?>  <a class="flinks" target="_blank" href="<?php echo site_url(); ?>"><?php echo $this->config->item("institute_address1"); ?></a> | All Rights Reserved.
			</p>
		</div>
		<div class="col-md-6">
			<div class="socials-list">
				<ul>
					<li><a href="#"><i class="fa fa-facebook-square fa-2x"></i></a></li>
					<li><a href="#"><i class="fa fa-youtube-square fa-2x"></i></a></li>
					<li><a href="#"><i class="fa fa-twitter-square fa-2x"></i></a></li>
					<li><a href="#"><i class="fa fa-google-plus-square fa-2x"></i></a></li>
					<li><a href="#"><i class="fa fa-rss-square fa-2x"></i></a></li>
				</ul>
			</div>
		</div>
	</div>
</div> <!-- end footer container -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo site_url(); ?>aes_website/js/vendor/jquery-1.11.3.min.js"><\/script>')</script>
<script src="<?php echo site_url(); ?>aes_website/js/plugins.js"></script>
<script src="<?php echo site_url(); ?>aes_website/js/bootstrap.js"></script>
<script src="<?php echo site_url(); ?>aes_website/js/main.js"></script>
<script src="<?php echo site_url(); ?>aes_website/js/jquery.scrollUp.min.js"></script>
<script src="<?php echo site_url(); ?>aes_website/js/wow.min.js"></script>
<script src="<?php echo site_url(); ?>aes_website/js/smooth-scroll.js"></script>
</body>
</html>