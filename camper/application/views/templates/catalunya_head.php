<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Leader View Header
 *
 * This is the header that displays on all leader pages that call the
 * header template. This page includes some javascript needed for the
 * page.
 *
 * File: /application/views/templates/catalunya_head.php
 * Copyright (c) 2015 Zilifone
 * Version 1.5 (2014 12 29 1234)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
/* 
 * Function & Resource Grab
 */	

 $baseurl = $this->config->item('camper_path');
 if($section == '') $section = 'none';

 if ($this->ion_auth->logged_in()) {
 
	 $ionuser = $this->ion_auth->user()->row();
	
	 // Setup Notifications
	 if(!isset($notifications)) $notifications = $this->shared->notifications();
	 $unit = $this->shared->get_user_unit($ionuser->id, TRUE);
		if ($ionuser->individual == '1') {
			$hasunit = true;
			$isindividual = true;
		} elseif ($unit['primary'] == $ionuser->id || $unit['alt'] == $ionuser->id) {
			$hasunit = true;
			$isindividual = false;
		} else {
			$hasunit = false;
			$isindividual = false;
		}
	
	 // Get unit details
	 if ($hasunit) {
		$unit = $this->data->get_units($ionuser->company);
	 }
 }

?><!DOCTYPE html>

<!--

     The Camper Registration System (<?php echo $this->config->item('camper_version'); ?>) 
     http://zilifone.net/camper
     Copyright (c) 2001-2015 Sean Wittmeyer, Zilifone
     Page Contact:  Sean Wittmeyer
     sean[at]zilifone[dot]net

-->

<html lang="en-US">
<head>
    <meta charset="UTF-8" />
	<title><?php echo (isset($title)) ? $title: $page; ?> &lsaquo; Camper</title>
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
	<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/camper.css?vs<?php echo $this->config->item('camper_version_num'); ?>" />
	<link rel="stylesheet" href="<?php echo $baseurl; ?>themes/<?php echo $this->config->item('camper_theme'); ?>/theme.css?v<?php echo $this->config->item('camper_version_num'); ?>" />
	<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script>document.createElement('section')</script>
	<![endif]-->

	<!-- Start Datepicker Includes -->
	<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/datepicker/default.css?v=3.4.0" id="theme_base">
	<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/datepicker/default.date.css?v=3.4.0" id="theme_date">
	<link rel="stylesheet" href="<?php echo $baseurl; ?>includes/css/datepicker/default.time.css?v=3.4.0" id="theme_time">
	
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
		$('*[data-toggle="tooltip"]').tooltip();
		$('.camperpopover').popover({html:true,body:true});
		$('.camperhoverpopover').popover({html:true,trigger:'hover'});
		$('.datepicker').pickadate({
			// Escape any characters with an exclamation mark (!).
			format: 'mmmm dd, yyyy',
			formatSubmit: 'mmmm dd yyyy',
			hiddenPrefix: 'prefix__',
			hiddenSuffix: '__suffix',
			selectYears: 200,
			selectMonths: true
		});
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
    				$('nav .badge-important').removeClass('badge-important').html('<i class="icon-tasks"></i> 0');
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
<body class="blue catalunya">
	<!--[if lt IE 7]>
	<div class="container alert alert-error alert-block">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<h4>Oh no!</h4>
		You are using an outdated browser which can make your computer unsafe. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.
	</div>
	<![endif]-->
	<header class="blue catalunya">
		<div class="headermast">
			<div class="left">
				<a href="<?php echo $baseurl; ?>">
					<img class="logo" src="<?php echo $baseurl; ?>themes/<?php echo $this->config->item('camper_theme'); ?>/logo.header.png" width="250" height="55" alt="Camper Home"  />
				</a>
			</div>
			<div class="right">
				<nav>
					<?php if (!$this->ion_auth->logged_in()) : ?> 
					<li><a href="<?php echo $baseurl; ?>events" class="<?php if($section == 'events') { ?> active<?php } ?>">Events</a></li>
					<li><a href="<?php echo $baseurl; ?>start" class="<?php if($section == 'events') { ?> active<?php } ?>">Create an Account</a></li>
					<li><a href="<?php echo $baseurl; ?>home" class="<?php if($section == 'signin') { ?> active<?php } ?>">Sign In</a></li>
					<?php else : ?> 
					<li><a href="<?php echo $baseurl; ?>home" class="<?php if($section == 'home') { ?> active<?php } ?>">Home</a></li>
					<?php if($hasunit) : ?><!--<li><a href="<?php echo $baseurl; ?>resources" class="<?php if($section == 'resources') { ?> active<?php } ?>">Resources</a></li>-->
					<li><a href="<?php echo $baseurl; ?>registrations" class="<?php if($section == 'register') { ?> active<?php } ?>">Events</a></li>
					<li><a href="<?php echo $baseurl; ?>payments" class="<?php if($section == 'payments') { ?> active<?php } ?>">Payments</a></li>
					<!--<li><a href="<?php echo $baseurl; ?>events" class="<?php if($section == 'events') { ?> active<?php } ?>">Events</a></li>-->
					<?php if(!$isindividual) : ?><li><a href="<?php echo $baseurl; ?>unit" class="<?php if($section == 'unit') { ?> active<?php } ?>"><?php echo $unit['unittitle']; ?></a></li><?php endif; ?>
					<?php else : ?><li><a href="<?php echo $baseurl; ?>me" class="<?php if($section == 'account') { ?> active<?php } ?>">My Account</a></li><?php endif; ?>
					<li><a href="<?php echo $baseurl; ?>me" class="<?php if($section == 'register') { ?> active<?php } ?>"><?php echo $ionuser->first_name.' '.$ionuser->last_name; ?></a></li>
					<?php endif; ?>
				</nav>
			</div>
			<div class="clear"></div>

		</div><!-- /.masthead -->
		<div class="clear"></div>
		<div class="headersub">
			<div class="left">
				<i class="icon-home"></i> &nbsp;<a href="<?php echo $baseurl; ?>"><?php echo $this->config->item('camper_council'); ?></a> &rsaquo; <a href="<?php echo $baseurl; ?>"><?php echo $this->config->item('camper_systemname'); ?></a> &rsaquo; <?php echo $breadcrumbs; ?>
			</div>
			<div class="right">
				<nav>
					<?php if (!$this->ion_auth->logged_in()) : ?> 
					<li><a href="<?php echo $baseurl; ?>help" class="" onclick="$('#help').slideToggle(100); return false;">Help</a></li>
					<li><a href="<?php echo $this->config->item('camper_councilpublicsite'); ?>" target="_blank" class="">Return to Camps Website <i class="icon icon-external-link"></i></a></li>
					<?php else : ?> 
					<li><?php echo anchor("signout", 'Sign Out');?></li>
					<li><a href="<?php echo $baseurl; ?>help" class="" onclick="$('#help').slideToggle(100); return false;">Help</a></li>
					<?php if ($this->ion_auth->is_admin()) { ?><li><a href="<?php echo base_url('dashboard'); ?>" class="">Admin</a></li><?php } ?>
					<?php if ($this->ion_auth->in_group('staff')) { ?><li><a href="<?php echo base_url('atcamp'); ?>" class="">AtCamp</a></li><?php } ?>
					<li><a href="<?php echo $this->config->item('camper_councilpublicsite'); ?>" target="_blank" class="">Return to Camps Website <i class="icon icon-external-link"></i></a></li>
					<li><a href="<?php echo $baseurl; ?>n" class=""><span class="badge<?php if ($notifications['new']) echo ' badge-important'; ?>"><i class="icon-tasks"></i> <?php echo $notifications['newcount']; ?></span></a><ul id="notifications"><li class="header"><a href="<?php echo $this->config->item('camper_path').'n'; ?>" data-loading-text="Marking..." class="btn btn-small tan right">Show All</a> <a href="#" onclick="mark_notifications(); return false;" data-loading-text="Marking..." class="btn btn-small tan right mark_notifications" style="margin-right: 5px;">Mark All Read</a><span>Notifications</span></li><?php echo $notifications['html']; ?><div class="clear"></div></ul></li>
					<?php endif; ?>
				</nav>
			</div>		
		</div>
		<div class="clear"></div>
	</header>
	<div class="clear"></div>
	<!-- SUB NAV GO HERE FOR EXPANDED HEADER PAGES -->
