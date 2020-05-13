<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Admin View Header
 *
 * This is the header that displays on all leader pages that call the
 * header template. This page includes some javascript needed for the
 * page.
 *
 * File: /application/views/templates/header_admin.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 10 1909)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
/* 
 * Function & Resource Grab
 */	

 $baseurl = $this->config->item('camper_path');
 if($section == '') $section = 'none';
 $ionuser = $this->ion_auth->user()->row();
 
 // Setup Notifications
 if(!isset($notifications)) $notifications = $this->shared->notifications();

?><!DOCTYPE html>

<!--

	 Camper Admin (<?php echo $this->config->item('camper_version'); ?>) 
	 http://camper.zilifone.net/camper
	 Copyright (c) 2001-2013 Sean Wittmeyer, Zilifone
	 Page Contact:  Sean Wittmeyer
	 sean[at]zilifone[dot]net

-->

<html lang="en-US">
<head>
	<meta charset="UTF-8" />
	<title><?php echo (isset($title)) ? $title: $page; ?> &lsaquo; Camper Admin</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta name="author" content="Zilifone Design, Sean Wittmeyer" />
	<meta name="robots" content="Index, Follow" />
	<!--<meta name="viewport" content="initial-scale=1.0, user-scalable=no">-->
	<meta name="viewport" content="width=1000">
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
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Start Datepicker Includes -->
	<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/datepicker/default.css?v=3.4.0" id="theme_base">
	<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/datepicker/default.date.css?v=3.4.0" id="theme_date">
	<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/datepicker/default.time.css?v=3.4.0" id="theme_time">
	
	<!--[if lt IE 9]>
		<script>document.createElement('section')</script>
	<![endif]-->

	<script src="<?php echo $baseurl; ?>includes/js/jquery.js?ver=1.10.2" type="text/javascript"></script>
	<script src="<?php echo $baseurl; ?>includes/js/jquery.fancybox.pack.js?v2.1.5" type="text/javascript"></script>
	<script src="<?php echo $baseurl; ?>includes/js/jquery.mousewheel-3.0.6.pack.js?v3.0.6" type="text/javascript"></script>
	<script src="<?php echo $baseurl; ?>includes/js/jquery.dataTables.min.js?v1.9.4" type="text/javascript"></script>
	<script src="<?php echo $baseurl; ?>includes/js/moment.min.js?v2.4.0" type="text/javascript"></script>
	<script src="<?php echo $baseurl; ?>includes/js/bootstrap.min.js?v2.3.1" type="text/javascript"></script>

	<!-- Start WYSISYG Includes -->
	<script src="<?php echo $baseurl; ?>includes/js/wysihtml5-0.3.0_rc2.min.js?v=0.3.0" type="text/javascript"></script>
	<script src="<?php echo $baseurl; ?>includes/js/bootstrap-wysihtml5-0.0.2.min.js?v=0.0.2" type="text/javascript"></script>

	<!-- Start Datepicker and Typeahead Includes -->
	<script src="<?php echo $baseurl; ?>includes/js/picker.js?v=3.4.0"></script>
	<script src="<?php echo $baseurl; ?>includes/js/picker.date.js?v=3.4.0"></script>
	<script src="<?php echo $baseurl; ?>includes/js/picker.legacy.js?v=3.4.0"></script>
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
	  function filterPath(string) {
	  return string
		.replace(/^\//,'')
		.replace(/(index|default).[a-zA-Z]{3,4}$/,'')
		.replace(/\/$/,'');
	  }
	  var locationPath = filterPath(location.pathname);
	  var scrollElem = scrollableElement('html', 'body');
	 
	  $('p a[href*=#]').each(function() {
		var thisPath = filterPath(this.pathname) || locationPath;
		if (  locationPath == thisPath
		&& (location.hostname == this.hostname || !this.hostname)
		&& this.hash.replace(/#/,'') ) {
			var $target = $(this.hash), target = this.hash;
			if (target) {
			  var targetOffset = $target.offset().top;
			  $(this).click(function(event) {
				event.preventDefault();
				$(scrollElem).animate({scrollTop: targetOffset}, 300, function() {
					location.hash = target;
				});
			  });
			}
		}
	  });
	 
	  // use the first element that is "scrollable"
	  function scrollableElement(els) {
		for (var i = 0, argLength = arguments.length; i <argLength; i++) {
			var el = arguments[i],
				$scrollElement = $(el);
			if ($scrollElement.scrollTop()> 0) {
			  return el;
			} else {
			  $scrollElement.scrollTop(1);
			  var isScrollable = $scrollElement.scrollTop()> 0;
			  $scrollElement.scrollTop(0);
			  if (isScrollable) {
				return el;
			  }
			}
		}
		return [];
	  }
	 
	});
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
	$(document).ready(function() {		
		$('.camperpopover').popover({html:true});
		$('.camperhoverpopover').popover({html:true,trigger:'hover'});
		$('*[data-toggle="tooltip"]').tooltip();
		$('.datepicker').pickadate({
			// Escape any “rule” characters with an exclamation mark (!).
			format: 'mmmm dd, yyyy',
			formatSubmit: 'mmmm dd yyyy',
			hiddenPrefix: 'prefix__',
			hiddenSuffix: '__suffix',
			selectYears: 4,
			selectMonths: true
			})	
		});
	function formatPhone(obj) {
		var numbers = obj.value.replace(/\D/g, ''),
			  char = {0:'(',3:') ',6:' - '};
		obj.value = '';
		for (var i = 0; i < numbers.length; i++) {
			  obj.value += (char[i]||'') + numbers[i];
		}
	}
	// Notifications Functions
	function mark_notifications() {
		$.ajax({
    		url: "<?php echo $this->config->item('camper_path'); ?>api/v1/notifications/markread",
    		type: 'GET',
    		beforeSend: function() {
    			$('.mark_notifications').button('loading');
    		},
    		statusCode: {
    			200: function() {
    				//alert( "success" );
    				$('.mark_notifications').button('reset');
    				$('#notifications li.new').removeClass('new');
    				$('nav.session .badge-important').removeClass('badge-important').html('<i class="icon-tasks"></i> 0');
    			},
    			304: function() {
    				//alert( "nothing to mark as read" );
    				$('.mark_notifications').button('reset');
    			}
    		}
		});
	}
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
	</script>
	<!--[if lt IE 9]>
	<style>
		body {
			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		}
	</style>
	<![endif]-->
</head>
<body class="teal">
	<!--[if lt IE 7]>
	<div class="container alert alert-error alert-block">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<h4>Oh no!</h4>
		You are using an outdated browser which can make your computer unsafe. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.
	</div>
	<![endif]-->
	<header class="teal">
		<div class="container">
			<div class="left">
				<a class="logo" href="<?php echo $baseurl; ?>"><img src="<?php echo $baseurl; ?>includes/img/logo.header.camp.png" width="250" height="55" alt="Camper Home"  /></a>
			</div>
			<div class="right">
				<nav class="session">
					<li><a href="<?php echo $baseurl; ?>auth/logout" class="">Sign Out</a></li>
					<li><a href="<?php echo $baseurl; ?>help" class="">Help</a></li>
					<li><a href="<?php echo $baseurl; ?>events" class="">Leader View</a></li>
					<li><a href="<?php echo $baseurl; ?>me" class=""><?php echo $ionuser->first_name.' '.$ionuser->last_name; ?></a></li>
					<li><a href="<?php echo $baseurl; ?>n" class=""><span class="badge<?php if ($notifications['new']) echo ' badge-important'; ?>"><i class="icon-tasks"></i> <?php echo $notifications['newcount']; ?></span></a><ul id="notifications"><li class="header"><a href="<?php echo $this->config->item('camper_path').'n'; ?>" data-loading-text="Marking..." class="btn btn-small tan right">Show All</a> <a href="#" onclick="mark_notifications(); return false;" data-loading-text="Marking..." class="btn btn-small tan right mark_notifications" style="margin-right: 5px;">Mark All Read</a><span>Notifications</span></li><?php echo $notifications['html']; ?><div class="clear"></div></ul></li>
				</nav>
			</div>
		</div><!-- /.container -->
		<div class="clear"></div>
		<div class="container">
			<div class="left"><h1>Camper<?php if ($this->config->item('camper_debug')) echo ' Dev'; ?></h1></div>
			<div class="right">
				<nav class="main">
					<!--<li><a href="<?php echo $baseurl; ?>reports" class="<?php if($section == 'reports') { ?> active<?php } ?>"><i class="campimg-report c<?php if($section == 'reports') { echo 'page'; } else { echo 'brown';} ?> tabicon"></i><br />Reports</a></li>-->
					<li><a href="<?php echo $baseurl; ?>payments" class="<?php if($section == 'payments') { ?> active<?php } ?>"><i class="campimg-credit c<?php if($section == 'payments') { echo 'page'; } else { echo 'brown';} ?> tabicon"></i><br />Payments</a></li>
					<li><a href="<?php echo $baseurl; ?>event" class="<?php if($section == 'event') { ?> active<?php } ?>"><i class="campimg-cal c<?php if($section == 'event') { echo 'page'; } else { echo 'brown';} ?> tabicon"></i><br />Events</a></li>
					<li><a href="<?php echo $baseurl; ?>units" class="<?php if($section == 'users') { ?> active<?php } ?>"><i class="campimg-users c<?php if($section == 'users') { echo 'page'; } else { echo 'brown';} ?> tabicon"></i><br />Units &amp; Users</a></li>
					<li><a href="<?php echo $baseurl; ?>dashboard" class="<?php if($section == 'dashboard') { ?> active<?php } ?>"><i class="campimg-home c<?php if($section == 'dashboard') { echo 'page'; } else { echo 'brown';} ?> tabicon"></i><br />Dashboard</a></li>
				</nav>
			</div>		
		</div>
	</header>
	<!-- SUB NAV GO HERE FOR EXPANDED HEADER PAGES -->
