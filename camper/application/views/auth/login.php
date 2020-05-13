<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Auth / Signin View
 *
 * This.
 *
 * File: /application/views/auth/login.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 10 1909)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 
?>	<script>
		$(document).ready(function() {
		  $('#identity').focus();
		});	
	</script>
	<div id="help">
		<div class="container">
			<div class="quarter">
				<h2>How can we help?</h2>
				<p>Choose the section you want to learn about to see help topics. Contact us if you still need a hand.</p>
				<p><i class="icon icon-phone"></i> <?php echo $this->config->item('camper_supportphone'); ?><br /><i class="icon icon-envelope"></i> Registration Questions: <?php echo $this->config->item('camper_supportemail'); ?><br /><i class="icon icon-envelope-alt"></i> Site Issues: <?php echo $this->config->item('camper_fromemail'); ?></p>
			</div>
			<div class="threequarter last">
				<button class="btn btn-link" style="display: block; position: absolute; right: 60px; z-index:1000; " onclick="$('#help').slideToggle(100); return false; "><i class="icon icon-remove"></i> Close</button>
				<ul class="nav nav-tabs" id="myTab">
					<li class="active"><a data-toggle="tab" href="#help-signingin">Signing in</a></li>
					<li><a data-toggle="tab" href="#help-loginissues">Login Issues</a></li>
				</ul>
				 
				<div class="tab-content">
					<div class="tab-pane active fade in" id="help-signingin">
						<p>Sign into Camper with your email address and password you used to set up your personal account here with Camper. If you don't know your login details, use the "Forgot Password" tool to set a new one.</p>
						<p>If you haven't created an account on Camper yet, simply set one up. You can create your account and your troop/crew/pack's account at the same time or just start with an account for yourself.</p>
					</div>
					<div class="tab-pane fade" id="help-loginissues">
						<p>We know it's hard to keep all of of your passwords and logins in order and we are here to help. If you can't seem to sign in, try entering your email address into the "Forgot Password" tool. If you have an account, we will send you an email with a link to reset your password. We can help you via email or phone if you need help or ind any issues with the site.</p>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div><!-- /#help -->

	<article class="title">
		<!--<div class="toolbox">
			<?php echo anchor("unit", 'My Unit &rarr;', 'class="btn blue"'); ?> <?php echo anchor("unit", 'My Unit &rarr;', 'class="btn blue"'); ?>
		</div>-->
		<h1>Welcome to Camper</h1>
		<p>The Longs Peak Council uses the Camper Registration System to allow units and scouters to register for camp and activities run by the Longs Peak Council and it's districts.</p>
		<div class="clear"></div>
    </article>
    <article class="tabset">
		<div class="tabset-nav">
			<ul>
				<h2>Start Here...</h2>
				<li><a href="<?php echo base_url('events'); ?>"><i class="icon icon-th"></i><strong>Browse Events</strong>See all of our upcoming events</a></li>
				<li><a data-toggle="tab" href="#start"><i class="icon icon-plus"></i><strong>Create an account</strong>Sign up once for registration ease</a></li>
				<li class="active"><a data-toggle="tab" href="#signin"><i class="icon icon-user"></i><strong>Sign in</strong>Create and manage your registrations</a></li>
			</ul>
		</div><!-- /tabset-nav -->
		<div class="tab-content tabset-content">
			<!-- Unit Event Set -->
			<div class="home-eventset tab-pane active fade in" id="signin">
				<!-- Unit details -->
				<!-- Unit registrations -->
				<h2>or <span style="font-weight: normal">sign in...</span></h2>
				<p>Sign in with your email address and password you used to sign up for Camper.</p>
				<!--[if IE 8]>
				<div class=" alert alert-error alert-block">
					<h4>Just a heads up...</h4>
					Your version of Internet Explorer has known issues with Camper. Although we are working to make fixes, we recommend you use a newer browser. <a href="http://browsehappy.com/">Upgrade your browser today</a>, <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> or use <strong>Mozilla Firefox</strong> or <strong>Google Chrome</strong> to better experience this site.
				</div>
				<![endif]-->
					<!--[if lt IE 8]>
				<div class=" alert alert-error alert-block">
					<h4>Well snap!</h4>
					<p><strong>Camper will not work with your version of Internet Explorer.</strong></p><p><a href="http://browsehappy.com/">Upgrade your browser today</a>, <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> or use <strong>Mozilla Firefox</strong> or <strong>Google Chrome</strong> to better experience this site.
				</div>
				<![endif]-->
				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
				<?php if (!$successmessage == '') { ?><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon-ok teal"></i> <?php echo $successmessage; ?></div><?php } ?> 
				<?php echo form_open("signin");?>
					<input type="hidden" name="go" value="<?php echo $go; ?>" />
  					<div class="camperform" style="width:400px;"><input type="email" name="identity" value="<?php echo $em; ?>" placeholder="baden.powell@scouting.org" id="identity" data-toggle="tooltip" data-placement="right" title="Enter your email address" /><label>Your Email</label></div>
  					<div class="camperform" style="width:400px;"><input type="password" name="password" value="<?php echo $pw; ?>" placeholder="••••••••" id="password" data-toggle="tooltip" data-placement="right" title="Enter your password" /><label>Your Password</label></div>
  					<div class="camperform" style="width:200px;"><input type="checkbox" name="remember" value="1" id="remember" class="cbl" /><label for="remember" class="cbl" >Keep me signed in </label></div>
  					<p><input type="submit" name="submit" value="Sign me in!" class="btn blue"  data-loading-text="Signing in..." onclick="$(this).button('loading');"  /> <a href="#forgot" data-toggle="tab"  class="btn tan">Forgot Password &rarr;</a></p>
  					<p>Don't have an account? Sign up!</p>
  					 <a href="#start" data-toggle="tab"  class="btn tan">Create an account &rarr;</a>
				<?php echo form_close();?> 

				<div class="clear"></div>
			</div>



			<div class="home-eventset tab-pane fade" id="forgot">
					<h2>Forgot your password?</h2>
   					<p>No worries, we can help you sign in. Please enter your email address you signed up with and we will send you an email with instructions on how to sign in and reset your password.</p>
   					<?php echo form_open("auth/forgot_password");?>
	   					<div class="camperform" style="width:400px;"><input type="text" name="email" value="<?php echo $em; ?>" placeholder="baden.powell@scouting.org" id="email" data-toggle="tooltip" data-placement="right" title="The email address you signed up with" /><label>Your Email</label></div>
	   					<input type="submit" name="submit" value="Next step &rarr;" data-loading-text="Loading..." onclick="$(this).button('loading');" class="btn blue"  />  <a href="#signin" data-toggle="tab"  class="btn tan">Back to Sign In &rarr;</a>
   					<?php echo form_close();?>
			</div>



			<div class="home-eventset tab-pane fade" id="start">
				<!-- individual details -->
   					<h2>Create an account</h2>
   					<?php echo form_open("start");?>
					<p>Welcome to Camper, our camp and activity registration system. You now have the ability to register your unit for activities and events including summer camps, camporees, trainings, and other upcoming activities. Camper allows you to register your unit, add a roster, preregister for activities and merit badges, and even preorder activity supplies, all in one place.</p>
					<p>Let's get started with your email address and the password you want to use.
					<div class="camperform" style="width:400px;"><input type="text" name="email" value="<?php echo $em; ?>" placeholder="baden.powell@scouting.org" id="newemail" data-toggle="tooltip" data-placement="right" title="Enter your email address, this will be your login" /><label>Your Email (this will be your login)</label></div>
					<div class="camperform" style="width:400px;"><input type="password" name="password" value="<?php echo $pw; ?>" placeholder="••••••••" id="newpassword" data-toggle="tooltip" data-placement="right" title="Choose a strong password with uppercase and lowercase letters and numbers" /><label>Choose a password</label></div>
		   			<input type="hidden" name="s" value="1" />
		   			<input type="hidden" name="token" value="1" />
					<input type="submit" name="submit" value="Next &rarr;" class="btn blue"  data-toggle="tooltip"  data-loading-text="Creating your account..." onclick="$(this).button('loading');"  />  <a data-toggle="tab"  href="#signin" class="btn tan">Back to Sign In &rarr;</a>
   					<?php echo form_close();?>
			</div>
		</div>
	</article><!-- /tabset-content -->
