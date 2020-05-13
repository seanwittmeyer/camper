<?php 

/* 
 * Camper Public View Header
 *
 * This is the header that displays on all leader pages that call the
 * header template. This page includes some javascript needed for the
 * page.
 *
 * File: /application/views/templates/header_leader.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
/* 
 * Function & Resource Grab
 */	
 
 $baseurl = $this->config->item('camper_path');
 if (!isset($section)) $section = 'auth';

//$user = $this->ion_auth->user()->row(); 
//$user->username;

?><!DOCTYPE html>

<!--

     Camper Base (<?php echo $this->config->item('camper_version'); ?>) 
     http://camper.zilifone.net/camper
     Copyright (c) 2001-2013 Sean Wittmeyer, Zilifone
     Page Contact:  Sean Wittmeyer
     sean[at]zilifone[dot]net

-->

<html lang="en-US">
<head>
<html lang="en-US">
<head>
    <meta charset="UTF-8" />
	<title><?php echo (isset($title)) ? $title: $page; ?> &lsaquo; Camper</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta name="author" content="Zilifone Design, Sean Wittmeyer" />
	<meta name="robots" content="Index, Follow" />
	<meta name="alexaVerifyID" content="" />
	<meta name="y_key" content="">
	<!--<meta name="viewport" content="initial-scale=1.0, user-scalable=no">-->
	<meta name="viewport" content="width=1000">
	<meta name="google-site-verification" content="" />
	<meta name="generator" content="<?php echo $this->config->item('camper_version'); ?>" />
    <link rel="Shortcut Icon" href="<?php echo $baseurl; ?>includes/img/favicon.png" />
    <link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/bootstrap.css?v2.3.1" />
    <link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/font-awesome.min.css" />
	<!--[if IE 7]>
		<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/font-awesome-ie7.min.css">
	<![endif]-->
    <link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/jquery.fancybox.css?v2.1.5" />
    <link rel="stylesheet" href="<?php echo $baseurl; ?>includes/fonts/fonts.css" />
    <link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/style.css?v<?php echo $this->config->item('camper_version_num'); ?>" />
    <!--[if lt IE 9]>
    	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	<script src="<?php echo $baseurl; ?>includes/js/jquery.js?ver=1.10.2" type="text/javascript"></script>
	<script src="<?php echo $baseurl; ?>includes/js/jquery.fancybox.pack.js?v2.1.5" type="text/javascript"></script>
	<script src="<?php echo $baseurl; ?>includes/js/jquery.mousewheel-3.0.6.pack.js?v3.0.6" type="text/javascript"></script>
	<script src="<?php echo $baseurl; ?>includes/js/bootstrap.min.js?v2.3.1" type="text/javascript"></script>
	<!-- Start Typeahead Includes -->
    <script src="<?php echo $baseurl; ?>includes/js/hogan.js"></script>
    <script src="<?php echo $baseurl; ?>includes/js/typeahead.js"></script>
    <script type="text/javascript">
	
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', 'UA-26542072-2', 'camperapp.org');
	ga('send', 'pageview');

	$(document).ready(function() {
		$('*[data-toggle="tooltip"]').tooltip();
	});
	function formatPhone(obj) {
	    var numbers = obj.value.replace(/\D/g, ''),
	        char = {0:'(',3:') ',6:' - '};
	    obj.value = '';
	    for (var i = 0; i < numbers.length; i++) {
	        obj.value += (char[i]||'') + numbers[i];
	    }
	}
	$(document).ready(function() {
	
		if ( !("placeholder" in document.createElement("input")) ) {
			$("input[placeholder], textarea[placeholder]").each(function() {
				var val = $(this).attr("placeholder");
				if ( this.value == "" ) {
					this.value = val;
				}
				$(this).focus(function() {
					if ( this.value == val ) {
						this.value = "";
					}
				}).blur(function() {
					if ( $.trim(this.value) == "" ) {
						this.value = val;
					}
				})
			});
	
			// Clear default placeholder values on form submit
			$('form').submit(function() {
	            $(this).find("input[placeholder], textarea[placeholder]").each(function() {
	                if ( this.value == $(this).attr("placeholder") ) {
	                    this.value = "";
	                }
	            });
	        });
		}
	});
	// BEGIN BACK BUTTON BOOTSTRAP TABS
	jQuery(document).ready(function() {
	  // add a hash to the URL when the user clicks on a tab
	  jQuery('#mapstabs a[data-toggle="tab"]').on('click', function(e) {
	    history.pushState(null, null, jQuery(this).attr('href'));
	  });
	  // navigate to a tab when the history changes
	  window.addEventListener("popstate", function(e) {
	    var activeTab = jQuery('#mapstabs [href=' + location.hash + ']');
	    if (activeTab.length) {
	      activeTab.tab('show');
	    } else {
	      jQuery('#mapstabs li:first a').tab('show');
	    }
	  });
	});	
	// END BACK BUTTON BOOTSTRAP TABS
	$(document).ready(function() {
		$('*[data-toggle="tooltip"]').tooltip();
		$('.camperpopover').popover({html:true});
		$('.camperhoverpopover').popover({html:true,trigger:'hover'});
	});
	</script>
    <!--[if lt IE 9]>
	<style>
		body {
			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		}
	</style>
    <![endif]-->
</head>
<body class="blue">
	<header class="blue">
		<div class="container">
			<div class="left">
				<a class="logo" href="<?php echo $baseurl; ?>"><img src="<?php echo $baseurl; ?>includes/img/logo.header.camp.png" width="250" height="55" alt="Camper Home"  /></a>
			</div>
			<div class="right">
				<nav class="session">
					<li><a href="<?php echo $baseurl; ?>start" class="">Create an Account</a></li>
					<li><a href="<?php echo $baseurl; ?>signin" class="">Sign In</a></li>
					<li><a href="<?php echo $baseurl; ?>help" class="">Help</a></li>
				</nav>
			</div>
		</div><!-- /.container -->
		<div class="clear"></div>
		<div class="container">
			<div class="left"><h1>Camper<?php if ($this->config->item('camper_debug')) echo ' Dev'; ?></h1></div>
			<div class="right">
				<nav class="main">
					<li><a href="<?php echo $baseurl; ?>signin" class="<?php if($section == 'auth') { ?> active<?php } ?>"><i class="campimg-user c<?php if($section == 'auth') { echo 'page'; } else { echo 'brown';} ?> tabicon"></i><br />Sign In</a></li>
					<li><a href="<?php echo $baseurl; ?>meet" class="<?php if($section == 'help' || $section == 'meet') { ?> active<?php } ?>"><i class="campimg-home c<?php if($section == 'help' || $section == 'meet') { echo 'page'; } else { echo 'brown';} ?> tabicon"></i><br />First Time?</a></li>
				</nav>
			</div>		
		</div>
	</header>
	<!-- SUB NAV GO HERE FOR EXPANDED HEADER PAGES -->
	<!--[if lt IE 7]>
	<div class="container alert alert-error alert-block">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<h4>Oh no! Camper registration doesn't work with Internet Explorer</h4>
		You are using an outdated browser which can make your computer unsafe. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.
	</div>
	<![endif]-->
