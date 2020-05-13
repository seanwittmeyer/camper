<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Auth / Forgot Password View
 *
 * This.
 *
 * File: /application/views/auth/login.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 10 1909)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 ?>
	<script>
	$(document).ready(function() {
	  $('#email').focus();
	});	
	</script>
	<article class="textsection">
   		<div class="container">
	   		<div class="threequarter">
   					<h2>Forgot your password?</h2>
   					<p>No worries, we can help you sign in. Please enter your email address you signed up with and we will send you an email with instructions on how to sign in and reset your password.</p>
   					<?php echo form_open("auth/forgot_password");?>
	   					<div class="camperform" style="width:400px;"><input type="text" name="email" placeholder="baden.powell@scouting.org" id="email" data-toggle="tooltip" data-placement="right" title="The email address you signed up with" /><label for="email">Your Email</label></div>
	   					<input type="submit" name="submit" value="Next step &rarr;" class="btn blue" data-loading-text="Loading..." onclick="$(this).button('loading');"  /> 
   					<?php echo form_close();?>
   			</div>
   		</div>
	</article>

