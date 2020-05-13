<?php 

/* 
 * Camper Change Password View
 *
 * This is.
 *
 * File: /application/views/account/leader.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

?>
	<article class="content">
		<?php echo form_open("auth/change_password");?><?php echo form_input($user_id);?>
   		<div class="container">
       		<div class="pull">
   	    		<h2 class="pull">Change Password</h2>
   	    		<div id="infoMessage"><?php echo $message;?></div>
   	    		<p>You can change you password here. The change will take effect immediately, log back in to continue to use Camper.</p>
   	    		<input type="submit" name="submit" value="Change my password!" class="btn blue"  />
   	    		<div class="clear"></div>
       		</div>
       		<div class="tab-content inner-push">
   				<h2 class="section">Old Password</h2>
   				<div class="camperform" style="width:60%;"><input id="old" name="old" value="" type="password" placeholder="••••••••" data-toggle="tooltip" data-placement="right" title="Your current password" /><label>Current password</label></div>   				<div class="clear"></div>
   				<h2 class="section">New Password</h2>
   				<div class="camperform" style="width:60%;"><input id="new" pattern="^.{8}.*$" name="new" value="" type="password" placeholder="••••••••" data-toggle="tooltip" data-placement="right" title="Must be at least 8 characters" /><label>New Password</label></div>
	   			<div class="camperform" style="width:60%;"><input id="new_confirm" pattern="^.{8}.*$" name="new_confirm" value="" type="password" placeholder="••••••••" data-toggle="tooltip" data-placement="right" title="Enter your new password, just to make sure you typed it correctly!" /><label>Retype Password</label></div>
	   			<div class="clear"></div>
   			</div>
   		</div>
   		<div class="clear"></div>
   		<?php echo form_close();?>
	</article>
