<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Auth / Change Password View
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
	  $('#new').focus();
	});	
	</script>
	<article class="textsection">
   		<div class="container">
	   		<div class="threequarter">
   					<h2>Change Password</h2>
   					<p>Change your password for Camper here, make sure you choose a good, strong password with at least 8 characters. Click change password when you are done.</p>
   					<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
   					<?php echo form_open('auth/reset_password/' . $code);?>
						<?php echo form_input($user_id);?>
						<?php echo form_hidden($csrf); ?>
	   					<div class="camperform" style="width:400px;"><input type="password" name="new" placeholder="••••••••" id="new" data-toggle="tooltip" data-placement="right" title="8 characters minimum" pattern="^.{8}.*$" /><label for="new">New Password</label></div>
	   					<div class="camperform" style="width:400px;"><input type="password" name="new_confirm" placeholder="••••••••" id="new_confirm" data-toggle="tooltip" data-placement="right" title="Confirm your new password" pattern="^.{8}.*$" /><label for="new_confirm">Confirm New Password</label></div>
	   					<input type="submit" name="submit" value="Change Password" class="btn blue"  data-loading-text="Changing..." onclick="$(this).button('loading');"  />
   					<?php echo form_close();?>
   			</div>
   		</div>
	</article>

