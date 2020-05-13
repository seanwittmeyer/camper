<?php 

/* 
 * Camper My Unit / Details View
 *
 * This is the leader view of the "My Account" section in camper. This offers
 * custom details specific to the leader part of the site.
 *
 * File: /application/views/myunit/details.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 // Setup
	$states = $this->config->item('camper_states');

	$unittitle = (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')': $unit['unittype'].' '.$unit['number'];

	
?>
	<div class="subnav">
		<div class="container">
			<h2>My Unit</h2>
			<nav class="campersubnav">
   	    		<li><?php echo anchor("unit/members", 'Members');?></li>
   	    		<li class="active"><?php echo anchor("unit", 'Details');?></li>
			</nav>
		</div>
	</div>
	<article class="content">
	<?php echo form_open(uri_string());?>
		<script>
		// Invites Functions
		function resend_invite(token,element) {
			$.ajax({
	    		url: "/camper/api/v1/invites/resend?t=" + token,
	    		type: 'GET',
	    		beforeSend: function() {
	    			$(element).text('Sending...');
	    		},
	    		statusCode: {
	    			200: function() {
	    				//alert( "success" );
	    			$(element).text('Invite sent');
	    			},
	    			304: function() {
	    				//alert( "nothing to mark as read" );
	    			$(element).text('Resend failed, retry?');
	    			}
	    		}
			});
		}
		function remove_invite(token,element) {
			$.ajax({
	    		url: "/camper/api/v1/invites/delete?t=" + token,
	    		type: 'GET',
	    		beforeSend: function() {
	    			$(element).text('Removing...');
	    		},
	    		statusCode: {
	    			200: function() {
	    				//alert( "success" );
	    			$(element).text('Invite removed from Camper');
	    			},
	    			304: function() {
	    				//alert( "nothing to mark as read" );
	    			$(element).text('Remove failed, retry?');
	    			}
	    		}
			});
		}
		</script>
   		<div class="container">
       		<div class="pull">
   	    		<h2 class="pull">Unit Details</h2>
   	    		<p>Manage your unit's primary and alternate contacts and other details here.</p>
   	    		<input type="submit" name="submit" value="Save Changes" class="btn teal"  /> <input type="reset" name="reset" value="Reset" class="btn tan"  />
   	    		<div class="clear"></div>
       		</div>
       		<div class="tab-content inner-push">
   				<h2 class="section">Details for <?php echo $unittitle; ?></h2>
   	    		<p>Keeping your unit updated on Camper makes the whole process registering easier than ever. You can manage your units contacts (including yourself) and keep your unit's general contact information up to date here.</p>
   				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
   				<?php if (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) { ?>
   				<div class="camperform float last" style="width: 165px"><span><?php echo $unittitle; ?></span><label>Unit</label></div>
   				<?php } else { ?>
   				<div class="camperform float last" style="width: 85px"><span><?php echo $unit['unittype']; ?></span><label>Unit Type</label></div>
   				<div class="camperform float last" style="width: 80px"><span><?php echo $unit['number']; ?></span><label>Number</label></div>
   				<?php } ?>
   				<div class="camperform float last" style="width: 260px"><span><?php echo $unit['council']; ?></span><label for="fcouncil">Council</label></div>
   				<div class="camperform float last" style="width: 200px"><span><?php echo $unit['district']; ?></span><label for="fdistrict">District</label></div>
   				<div class="clear"></div>
   				<div class="camperform float" style="width: 255px"><input type="text" name="address" id="fadd" value="<?php echo $unit['address']; ?>" data-toggle="tooltip" data-placement="right" title="This is where we will mail documents relating to your registered events" /><label for="fadd">Mailing Address</label></div>
   				<div class="camperform float" style="width: 140px"><input type="text" name="city" id="fcity" value="<?php echo $unit['city']; ?>" data-toggle="tooltip" data-placement="right" title="Your City" /><label for="fcity">City</label></div>
		   		<div class="camperform float" style="width: 160px;">
					<select id="fstate" name="state"> 
						<option value="<?php echo strtoupper($unit['state']); ?>" selected="selected"><?php echo $states[strtoupper($unit['state'])]; ?></option> 
						<option value="" disabled="disabled">Select a State</option> 
						<option value="AL">Alabama</option> 
						<option value="AK">Alaska</option> 
						<option value="AZ">Arizona</option> 
						<option value="AR">Arkansas</option> 
						<option value="CA">California</option> 
						<option value="CO">Colorado</option> 
						<option value="CT">Connecticut</option> 
						<option value="DE">Delaware</option> 
						<option value="DC">District Of Columbia</option> 
						<option value="FL">Florida</option> 
						<option value="GA">Georgia</option> 
						<option value="HI">Hawaii</option> 
						<option value="ID">Idaho</option> 
						<option value="IL">Illinois</option> 
						<option value="IN">Indiana</option> 
						<option value="IA">Iowa</option> 
						<option value="KS">Kansas</option> 
						<option value="KY">Kentucky</option> 
						<option value="LA">Louisiana</option> 
						<option value="ME">Maine</option> 
						<option value="MD">Maryland</option> 
						<option value="MA">Massachusetts</option> 
						<option value="MI">Michigan</option> 
						<option value="MN">Minnesota</option> 
						<option value="MS">Mississippi</option> 
						<option value="MO">Missouri</option> 
						<option value="MT">Montana</option> 
						<option value="NE">Nebraska</option> 
						<option value="NV">Nevada</option> 
						<option value="NH">New Hampshire</option> 
						<option value="NJ">New Jersey</option> 
						<option value="NM">New Mexico</option> 
						<option value="NY">New York</option> 
						<option value="NC">North Carolina</option> 
						<option value="ND">North Dakota</option> 
						<option value="OH">Ohio</option> 
						<option value="OK">Oklahoma</option> 
						<option value="OR">Oregon</option> 
						<option value="PA">Pennsylvania</option> 
						<option value="RI">Rhode Island</option> 
						<option value="SC">South Carolina</option> 
						<option value="SD">South Dakota</option> 
						<option value="TN">Tennessee</option> 
						<option value="TX">Texas</option> 
						<option value="UT">Utah</option> 
						<option value="VT">Vermont</option> 
						<option value="VA">Virginia</option> 
						<option value="WA">Washington</option> 
						<option value="WV">West Virginia</option> 
						<option value="WI">Wisconsin</option> 
						<option value="WY">Wyoming</option>
					</select><label for="fstate">State</label>
		   		</div>
   				<div class="camperform float" style="width: 60px"><input type="text" name="zip" id="fzip" value="<?php echo $unit['zip']; ?>"  data-toggle="tooltip" data-placement="right" title="Enter your Zip Code (without the +4 extension)" /><label for="fzip">Zip Code</label></div>
   				<div class="clear tall"></div>
   				<h2 class="section">Primary Contact</h2>
   	    		<p>The primary contact for <?php echo $unittitle; ?> is the person in charge of the unit here on Camper. They can manage every aspect of their unit including changing the unit contacts in addition to registering the unit for events.</p>
   				<?php if ($primary && $userunit == 1) { ?><h3><i class="icon-ok teal"></i> You are the Primary Contact for this unit.</h3><?php echo anchor("me", 'My Account &rarr;', 'class="btn blue"');?><?php } elseif ($primary) { ?> 
   				<div class="camperform float" style="width: 100px" data-toggle="tooltip" title="Your login"><span><?php echo $primary['first_name']; ?></span><label>First Name</label></div>
   				<div class="camperform float" style="width: 150px"  data-toggle="tooltip" title="Your login"><span><?php echo $primary['last_name']; ?></span><label>Last</label></div>
   				<div class="camperform float" style="width: 350px"  data-toggle="tooltip" title="Your login"><span><?php echo $primary['email']; ?></span><label>Email</label></div>
   				<div class="camperform float" style="width: 200px"  data-toggle="tooltip" title="Your login"><span><?php echo $primary['phone']; ?></span><label>Daytime Phone</label></div>
   				<?php } else { ?><h3>Your unit has no primary contact, this is odd.</h3><?php } ?> 
   				<div class="clear"></div>
   				<?php if ($userunit == 1 && $alternate) { ?><a href="#modal_change_primary" class="btn tan" data-toggle="modal">Change Primary Contact &rarr;</a><?php } ?>
   				<div class="clear tall"></div>
   				<h2 class="section">Alternate Contact</h2>
   	    		<p>Alternate contacts are leaders who can manage and edit registrations on Camper for their unit. They are the same as primary contact except that they can not change the contacts of the unit.</p>
   				<?php if ($alternate && $userunit == 2) { ?><h3><i class="icon-ok teal"></i> You are the Alternate Contact for this unit.</h3><?php echo anchor("me", 'My Account &rarr;', 'class="btn blue"');?><?php } elseif ($alternate) { ?>  
   				<div class="camperform float" style="width: 100px" data-toggle="tooltip" title="Your login"><span><?php echo $alternate['first_name']; ?></span><label>First Name</label></div>
   				<div class="camperform float" style="width: 150px"  data-toggle="tooltip" title="Your login"><span><?php echo $alternate['last_name']; ?></span><label>Last</label></div>
   				<div class="camperform float" style="width: 350px"  data-toggle="tooltip" title="Your login"><span><?php echo $alternate['email']; ?></span><label>Email</label></div>
   				<div class="camperform float" style="width: 200px"  data-toggle="tooltip" title="Your login"><span><?php echo $alternate['phone']; ?></span><label>Daytime Phone</label></div>
   				<?php } else { ?><h3><i class="icon-remove red"></i> Your unit has no alternate contact.</h3>
   				<div class="clear"></div>
   				<?php if ($userunit == 1) { ?><a href="#modal_change_alternate" class="btn tan" data-toggle="modal">Change Alternate Contact &rarr;</a><?php } ?>
   				<div class="clear tall"></div>
	   				<?php if ($invites) { ?>
	   				<h3>Open invites for <?php echo $unittitle; ?></h3>
	   				<p>When you invite someone to be a contact for <?php echo $unittitle; ?>,  they will appear here until they finish creating an account on Camper. If there are multiple invites here, the first one to signup will take the alternate contact spot.</p> 
		   			<p><?php foreach ($invites as $invite) { ?>
		   					<strong><?php echo $invite['email']; ?></strong> was invited by <?php echo $invite['source']; ?> - <a href="#" onclick="remove_invite('<?php echo $invite['token']; ?>',this); return false;">Remove invite</a> or <a href="#" onclick="resend_invite('<?php echo $invite['token']; ?>',this); return false;">Resend</a><br />
		   				<?php }} ?>
		   			</p>		
	   				<?php } ?>
   				<div class="clear"></div>
   			</div>
   		</div>
   		<div class="clear"></div>
   	<?php echo form_close();?> 
	<?php if ($userunit == 1) { ?>
	<!-- Change Primary Modal -->
	<div id="modal_change_primary" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<?php echo form_open("unit/change_contact");?><input type="hidden" name="unit" value="<?php echo $unit['id'];?>" /><?php echo form_hidden($csrf); ?>

   		<div class="container">
   			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
       		<div class="pull">
   	    		<h2 class="pull">Change the Primary Contact</h2>
   	    		<p>You can change the main contact for this unit here.</p>
   	    		<div class="clear"></div>
       		</div>
       		<div class="tab-content inner-push">
   				<h2 class="section">Swap contacts </h2>
   				<p>As the primary contact for <?php echo $unittitle; ?>, you can choose to swap the primary and alternate contacts for the unit. This will make you the alternate contact. You will still have access to your unit here on Camper but you won't be able to swap or edit the contacts.</p>
	   			<div class="clear"></div>
	   			<a data-toggle="popover" title="Are you sure?" data-placement="top" data-content="<?php echo $primary['first_name'];?>, <br />You are about to make <strong><?php echo $alternate['first_name'];?> <?php echo $alternate['last_name'];?></strong> the primary contact and <strong>yourself</strong> the alternate contact.<br /><br />You will not be able to undo this action.<br /><br /><input type='submit' name='submit' value='Swap Contacts' class='btn teal' /> " class="btn red camperpopover">Swap Contacts &rarr; </a> <button class="btn tan" data-dismiss="modal" aria-hidden="true">Nevermind</button>
   			</div>
   		</div>
   		</div>
   		<?php echo form_close();?>
	</div>
   	<!-- End Modal -->
   	<!-- Change Alternate Modal -->
	<div id="modal_change_alternate" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<?php echo form_open("unit/change_contact");?><input type="hidden" name="unit" value="<?php echo $unit['id'];?>" /><?php echo form_hidden($csrf); ?>

   		<div class="container">
   			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
       		<div class="pull">
   	    		<h2 class="pull">Change the Alternate Contact</h2>
   	    		<p>You can change the alternate contact for this unit here.</p>
   	    		<div class="clear"></div>
       		</div>
       		<div class="tab-content inner-push">
   				<h2 class="section">Add new leader via email </h2>
   				<p>You can change the alternate contact for your unit here. When you submit your new leader's email address, the new user will replace with the existing alternate contact for this unit. This will remove the old alternate contact's access to this unit. </p>
   		   		<div class="camperform float search" style="width: 60%"><i class="icon-envelope-alt"></i><input class="ico" type="text" id="mfuser" name="user" data-toggle="tooltip" data-placement="right" placeholder="baden.powell@scouting.org"  title="Enter the email address of the person you wish to make the alternate contact" /><label for="mfuser">This will make this user the alternate contact.</label></div>
	   			<div class="clear"></div>
	   			<?php if (isset($primary['first_name']) && isset($alternate['first_name'])) { ?><a data-toggle="popover" title="Are you sure?" data-placement="top" data-content="<strong><?php echo $primary['first_name'];?></strong>, <br />You are about to remove <strong><?php echo $alternate['first_name'];?> <?php echo $alternate['last_name'];?></strong> as the alternate contact. Your new contact will be invited to Camper to join your unit.<br /><br /><input type='submit' name='submit' value='Set Alternate Contact' class='btn teal' /> " class="btn red camperpopover">Add alternate contact &rarr; </a> <button class="btn tan" data-dismiss="modal" aria-hidden="true">Nevermind</button>
	   			<?php } else { ?><a data-toggle="popover" title="Add contact" data-placement="top" data-content="Your new contact will be invited to Camper to join your unit.<br /><br /><input type='submit' name='submit' value='Set Alternate Contact' class='btn teal' /> " class="btn red camperpopover">Add alternate contact &rarr; </a> <button class="btn tan" data-dismiss="modal" aria-hidden="true">Nevermind</button><?php } ?>
   			</div>
   		</div>
   		<div class="clear"></div>
   		<?php echo form_close();?>
	</div>
   	<!-- End Modal -->
   	<?php } ?>
	</article>
