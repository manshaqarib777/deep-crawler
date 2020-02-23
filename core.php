<?php

	session_start();

	function isActive($condition){
		echo $condition ? 'active' : '';
	}

	$request = isset($_GET['request']) ? $_GET['request'] : '';
	$request = $request ? $request : 'home';

	$title   = array(
		'home'    => htmlspecialchars('Automatic Content Crawler and Poster for Wordpress',ENT_QUOTES),
		'pricing' => htmlspecialchars('Pricing',ENT_QUOTES),
		'about'   => htmlspecialchars('About',ENT_QUOTES),
		'support' => htmlspecialchars('Support',ENT_QUOTES),
		'jobs'    => htmlspecialchars('Jobs',ENT_QUOTES),
		'docs'    => htmlspecialchars('Documentation',ENT_QUOTES),
		'account' => htmlspecialchars('Account',ENT_QUOTES),
		'change_log' => htmlspecialchars('Change Log',ENT_QUOTES),
		'signup'   => htmlspecialchars('Signup',ENT_QUOTES),
		'login'    => htmlspecialchars('Login',ENT_QUOTES),
		'editor'   => htmlspecialchars('Editor',ENT_QUOTES),
		'faq'      => htmlspecialchars('Frequently Asked Questions',ENT_QUOTES),
		'roadmap'  => htmlspecialchars('Roadmap',ENT_QUOTES),
		'regex_library' => htmlspecialchars('Useful Regex Codes for Scraping',ENT_QUOTES),
		'additional_request' => htmlspecialchars('Additional Request',ENT_QUOTES)
	);

	$content = array(
		'home'    => htmlspecialchars('Scraper is a Wordpress plugin that copies content and posts automatically from any website.',ENT_QUOTES),
		'pricing' => htmlspecialchars('Find Scraper\'s fees and pricing information.',ENT_QUOTES),
		'about'   => htmlspecialchars('Find everything about Scraper.',ENT_QUOTES),
		'support' => htmlspecialchars('Scraper Support and troubleshoot',ENT_QUOTES),
		'jobs'    => htmlspecialchars('See current career opportunities and job openings at Scraper.',ENT_QUOTES),
		'docs'    => htmlspecialchars('Scraper Documentation',ENT_QUOTES),
		'account' => htmlspecialchars('Account',ENT_QUOTES),
		'regex_library' => htmlspecialchars('Regex Library',ENT_QUOTES),
		'change_log' => htmlspecialchars('Scraper\'s Change Log',ENT_QUOTES),
		'roadmap' => htmlspecialchars('Scraper\'s Roadmap',ENT_QUOTES),
		'access_token' => htmlspecialchars('Get Access Token',ENT_QUOTES),
		'faq'     => htmlspecialchars('Frequently Asked Questions',ENT_QUOTES)
	);

	$pages = array_keys($title);

	//Create CSR
	$_SESSION['CSR'] = date('s');