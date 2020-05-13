<?php 

/* 
 * Camper Admin Users Deactivate View
 *
 * This is. 
 *
 * File: /application/views/admin/users/deactivate.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

?>
	<div class="subnav">
		<div class="container">
			<h2>Units &amp; Users</h2>
			<nav class="campersubnav">
   	    		<li><?php echo anchor("users/pending", 'Pending Invites');?></li>
   	    		<li class="active"><?php echo anchor("users", 'Users');?></li>
   	    		<li><?php echo anchor("units", 'Units');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
	<?php echo form_open("users/deactivate/".$user->id);?> 
	<?php echo form_hidden($csrf); ?> 
	<?php echo form_hidden(array('id'=>$user->id)); ?> 
   		<h2 class="">Deactivate <?php echo $user->first_name; ?> <?php echo $user->last_name; ?></h2>
		<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
   		<p>You are about to deactivate <strong><?php echo $user->first_name; ?> <?php echo $user->last_name; ?></strong>. This will prevent them from logging in, making changes, viewing unit details, and making registrations. This will not delete the user from the system, just lock them out. You can reactivate them at any time.</p>
		<div class="camperform float cbl" style="width: auto;"><input type="radio" class="cbl" value="yes" checked="checked" name="confirm" id="fyes" /><label for="fyes" class="cbl" >Yes</label></div>
		<div class="camperform float cbl" style="width: auto;"><input type="radio" class="cbl" value="no" name="confirm" id="fno" /><label for="fno" class="cbl" >No</label></div>
		<div class="clear"></div>
		<p><input type="submit" name="submit" value="Deactivate User" class="btn red"  /> <?php echo anchor("users", 'Nevermind', 'class="btn tan"');?>
   	<?php echo form_close();?> 
	</article>
