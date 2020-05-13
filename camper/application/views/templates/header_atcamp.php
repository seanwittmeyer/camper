<?php 

/* 
 * Camper Header
 *
 * This is the global header that loads resources and starts the page,
 * this page is supposed to handle any user type and will customize
 * as it is needed. It is part of the new layout.
 *
 * File: /application/views/templates/header.php
 * Copyright (c) 2013 Zilifone
 * Version 1.5 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
/* 
 * Function & Resource Grab
 */	

 // Set up some basics and our scope
 $baseurl = base_url();
 if(empty($section)) $section = 'none';
 if(empty($scope)) $scope = 'leader';
 
 // Set the current user or view as another user if admin
 if ($this->ion_auth->is_admin() && $this->input->get('viewas')) {
 	// We are an admin and want to view this pags as an admin, we'll have fun.
 	$user = $this->ion_auth->user($this->input->get('viewas'))->row();
 	$viewas = true;
 } else {
 	$user = $this->ion_auth->user()->row();
 	$viewas = false;
 }
 
 // Setup Notifications
 if(!isset($notifications)) $notifications = $this->shared->notifications($user->id);

?><!DOCTYPE html>

<!--

	 Zilifone Camper (<?php echo $this->config->item('camper_version'); ?>) 
	 http://camper.zilifone.net/camper
	 Copyright (c) 2001-2014 Sean Wittmeyer, Zilifone
	 Page Contact:  Sean Wittmeyer
	 sean[at]zilifone[dot]net

-->

<html lang="en-US">
<head>
	<meta charset="UTF-8" />
	<title><?php echo $page; ?> &lsaquo; <?php echo $this->config->item('camper_systemname'); if ($scope == 'admin') echo ' Admin'; ?></title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta name="author" content="Zilifone Design, Sean Wittmeyer" />
	<meta name="robots" content="Index, Follow" />
	<!--<meta name="viewport" content="initial-scale=1.0, user-scalable=no">-->
	<meta name="viewport" content="width=1000">
	<meta name="generator" content="<?php echo $this->config->item('camper_version'); ?>" />
	<link rel="Shortcut Icon" href="<?php echo $baseurl; ?>includes/img/favicon.png" />
	<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/bootstrap.css?v2.3.1" />
	<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/datepicker/default.css?v=3.4.0" id="theme_base">
	<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/datepicker/default.date.css?v=3.4.0" id="theme_date">
	<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/datepicker/default.time.css?v=3.4.0" id="theme_time">
	<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/font-awesome.min.css" />
	<!--[if IE 7]>
		<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/font-awesome-ie7.min.css">
	<![endif]-->
	<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/jquery.fancybox.css?v2.1.5" />
	<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/fonts/fonts.css" />
	<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/style.css?v<?php echo $this->config->item('camper_version_num'); ?>" />
	<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/<?php echo $this->config->item('camper_theme'); ?>.css?v<?php echo $this->config->item('camper_version_num'); ?>" />
	
	<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
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
	// Load Analytics
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', 'UA-26542072-2', 'camperapp.org');
	ga('send', 'pageview');

	// Everything that should go when the page is ready
	$(document).ready(function() {
		// Scrollable Hash Links
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

		// Handle placeholders in forms
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

		// Prep our popovers and tooltips
		$('.camperpopover').popover({html:true});
		$('.camperhoverpopover').popover({html:true,trigger:'hover'});
		$('*[data-toggle="tooltip"]').tooltip();

		// Setup the date pickers
		$('.datepicker').pickadate({
			// Escape any “rule” characters with an exclamation mark (!).
			format: 'mmmm dd, yyyy',
			formatSubmit: 'mmmm dd yyyy',
			hiddenPrefix: 'prefix__',
			hiddenSuffix: '__suffix',
			selectYears: 4,
			selectMonths: true
		});

		// Add back button functionality to tabs
		jQuery('#mapstabs a[data-toggle="tab"]').on('click', function(e) {
			history.pushState(null, null, jQuery(this).attr('href'));
		});
		window.addEventListener("popstate", function(e) {
			var activeTab = jQuery('#mapstabs [href=' + location.hash + ']');
			if (activeTab.length) {
				activeTab.tab('show');
			} else {
				jQuery('#mapstabs li:first a').tab('show');
			}
		});
	});

	// Format phone number entry
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
	</script>
	<!--[if lt IE 9]>
	<style>
		body {
			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		}
	</style>
	<![endif]-->
</head>
<body class="atcamp red">
	<div class="atcampcontainer">
	<!--[if lt IE 7]>
	<div class="container alert alert-error alert-block">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<h4>Oh no!</h4>
		You are using an outdated browser which can make your computer unsafe. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.
	</div>
	<![endif]-->
	<h1 class="logo"><?php echo anchor('atcamp', 'Camper at Camp'); ?></h1>
	<h2><?php if (isset($event)) { echo $event['title'];?> (<?php echo anchor('atcamp/'.$event['id'], $session['nicetitle']); ?>) <?php } elseif (isset($heading)) { echo $heading; } else { echo 'Welcome back, '. $this->ion_auth->user()->row()->first_name; } ?></h2>
	<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
	<div class="clear hr"></div>
