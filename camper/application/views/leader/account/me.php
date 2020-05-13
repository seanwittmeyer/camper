<?php 

/* 
 * Camper Account Leader View
 *
 * This is the leader view of the "My Account" section in camper. This offers
 * custom details specific to the leader part of the site.
 *
 * File: /application/views/account/leader.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 10 1909)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

?>
	<article class="content">
	<?php echo form_open("me/edit");?> 
	<?php echo form_hidden('id', $user_id['value']);?> 
   		<div class="container">
       		<div class="pull">
   	    		<h2 class="pull">My Account</h2>
   	    		<p>You can view and make changes to your account on camper here. The details here control your account for the Camper Registration system.</p>
   				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
   	    		<input type="submit" name="submit" value="Save Changes" class="btn teal"  />
   	    		
   	    		<div class="clear"></div>
       		</div>
       		<div class="tab-content inner-push">
   				<h2 class="section">Login Details</h2>
   				<div class="camperform float last" style="width: 40%" data-toggle="tooltip" title="You can't change your email address"><span><?php echo $email;?></span><label for="femail">Email Address</label></div>
   				<div class="right"><a href="#modal_change_password" class="btn tan" data-toggle="modal">Change Password &rarr;</a></div>
   				<div class="clear"></div>
   				<h2 class="section">Contact Information</h2>
   				<div class="camperform float" style="width: 20%"><input type="text" name="first_name" id="ffirst" value="<?php echo $first;?>" data-toggle="tooltip" data-placement="right" title="Your first name or nickname you go by" /><label for="ffirst">First Name</label></div>
   				<div class="camperform float" style="width: 30%"><input type="text" name="last_name" id="flast" value="<?php echo $last;?>" data-toggle="tooltip" data-placement="right" title="Your last name" /><label for="flast">Last</label></div>
   				<div class="camperform float last" style="width: 20%"><input type="tel" id="fphone" onchange="formatPhone(this);" onkeydown="formatPhone(this);" name="phone" value="<?php echo $phone;?>" data-toggle="tooltip" data-placement="right" title="Your phone number, we will call if we have any issues" /><label for="fphone">Daytime Phone</label></div>
   				<div class="clear"></div>
   				<h2 class="section">Unit Information</h2>
   				<?php echo $unit; ?> 
   				<p>If you have switched units and wish to be linked with another unit, you can either create that unit if it is not in the system or you should request that you be made the alternate contact.</p>
   				<!--
   				<p>If you wish to change units, use the search tool below to find the unit you wish to manage. By submitting the request, the primary contact for the unit you requested will get notified and will have the chance to approve the change and make you the alternate contact. The primary contact for that unit will be supplied with your name, phone number and email address to help them verify your request.</p>
   		   		<div class="camperform float search" style="width: 60%"><i class="icon-search"></i><input class="ico" type="text" data-toggle="tooltip" data-placement="right" placeholder="Find Unit"  title="Search for a unit" /><label>Change the unit associated with your account here. Select a unit and click submit. The change will finalize when approved.</label></div>
   		   		-->
   			</div>
   		</div>
   		<div class="clear"></div>
   	<?php echo form_close();?> 
   	<!-- Change PW Modal -->
	<div id="modal_change_password" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<?php echo form_open("auth/change_password");?><?php echo form_input($user_id);?>
   		<div class="container">
   			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
       		<div class="pull">
   	    		<h2 class="pull">Change Password</h2>
   	    		<p>You can change you password here. The change will take effect immediately, log back in to continue to use Camper.</p>
   	    		<input type="submit" name="submit" value="Change &rarr;" class="btn teal"  /> <button class="btn tan" data-dismiss="modal" aria-hidden="true">Nevermind</button>
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
	</div>
   	<!-- End Modal -->
	</article>
