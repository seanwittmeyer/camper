<?php 

/* 
 * Camper Admin Users Edit Unit View
 *
 * This is. 
 *
 * File: /application/views/admin/users/editunit.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

 $unittitle = (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')': $unit['unittype'].' '.$unit['number'];
?>
	<div class="subnav">
		<div class="container">
			<h2>Units &amp; Users</h2>
			<nav class="campersubnav">
   	    		<li><?php echo anchor("users/pending", 'Pending Invites');?></li>
   	    		<li><?php echo anchor("users", 'Users');?></li>
   	    		<li class="active"><?php echo anchor("units", 'Units');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
		<div class="container">
			<h2>Units / <?php echo $unittitle;?></h2>
   			<p>Manage <?php echo $unittitle;?> here, from the unit details and contacts to the payments and registrations. You can quickly see the history of <?php echo $unit['unittype'];?> <?php echo $unit['number'];?> in Camper. More coming here soon.</p>
			<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
		</div>
   		<div class="container">
   			<ul id="detailstabs" class="teal">
   				<li class="active"><?php echo anchor("units/".$unit['id'], 'Unit Details');?></li>
   				<li class=""><?php echo anchor("units/".$unit['id']."/payments", 'Payments');?></li>
   				<li class=""><?php echo anchor("units/".$unit['id']."/registrations", 'Registrations');?></li>
   				<li class=""><?php echo anchor("units/".$unit['id']."/members", 'Members');?></li>
   			</ul>
   		</div>
		<?php echo form_open(uri_string());?>
		<?php echo form_hidden('unitid', $unit['id']);?>
		<?php echo form_hidden($csrf); ?>
		<script>
		$(document).ready(function() {
			$('.councillist.typeahead').typeahead({                              
			  limit: '10',                                                        
			  prefetch: '<?php echo $this->config->item('camper_path'); ?>api/v1/list/councils.json',                                             
			  template: [                                                                 
			    '<p class="typeahead-num">{{num}}</p>',                              
			    '<p class="typeahead-name">{{name}} Council</p>',                                      
			    '<p class="typeahead-city">{{city}}</p>'                         
			  ].join(''),                                                                 
			  engine: Hogan                                                               
			});
			$('.districtlist.typeahead').typeahead({                              
			  limit: '10',                                                        
			  prefetch: '<?php echo $this->config->item('camper_path'); ?>api/v1/list/districts.json',                                             
			  template: [                                                                 
			    '<p class="typeahead-name">{{name}} District</p>',                                      
			    '<p class="typeahead-city">{{schools}}</p>'                         
			  ].join(''),                                                                 
			  engine: Hogan                                                               
			});
			$('.userslist.typeahead').typeahead({                              
			  limit: '10',                                                        
			  prefetch: '<?php echo $this->config->item('camper_path'); ?>api/v1/users.json',                                             
			  template: [                                                                 
			    '<p class="typeahead-num">{{unit}}</p>',                              
			    '<p class="typeahead-name">{{email}}</p>',                                      
			    '<p class="typeahead-city"><strong>{{name}}</strong> / {{phone}}</p>'                         
			  ].join(''),                                                                 
			  engine: Hogan                                                               
			});
			$('.camperpopover').popover({html:true});
		});
		// Invites Functions
		function resend_invite(token,element) {
			$.ajax({
	    		url: "<?php echo $this->config->item('camper_path'); ?>api/v1/invites/resend?t=" + token,
	    		type: 'GET',
	    		beforeSend: function() {
	    			$(element).text('Sending...');
	    		},
	    		statusCode: {
	    			200: function() {
	    				//alert( "success" );
	    			$(element).html('Invite sent <i class="icon-ok"></i>');
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
	    		url: "<?php echo $this->config->item('camper_path'); ?>api/v1/invites/delete?t=" + token,
	    		type: 'GET',
	    		beforeSend: function() {
	    			$(element).text('Removing...');
	    		},
	    		statusCode: {
	    			200: function() {
	    				//alert( "success" );
	    			$(element).html('Invite removed from Camper <i class="icon-ok"></i>');
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
       		<div class="quarter">
   	    		<h2 >Unit Editor</h2>
   	    		<p>Camper lets you manage all of the unit accounts including unit details including contact information, leader details and registrations.</p>
   	    		<?php echo anchor("units", '&larr; All Units', 'class="btn tan"');?>
   	    		<div class="clear"></div>
       		</div>
       		<div class="threequarter">
   				<!--<h2 class="section"><?php echo $unit['unittype'];?> <?php echo $unit['number'];?></h2>-->
   				<div class="right"><input type="submit" name="submit" value="Edit Unit" class="btn teal"  /> <input type="reset" name="reset" value="Reset" class="btn tan"  /></div>
		   		<div class="camperform float" style="width: 100px;">
		   			<select name="unittype">
						<option value="Troop"<?php if ($unit['unittype'] == 'Troop') { ?> selected="selected"<?php } ?>>Troop</option>
						<option value="Crew"<?php if ($unit['unittype'] == 'Crew') { ?> selected="selected"<?php } ?>>Crew</option>
						<option value="Ship"<?php if ($unit['unittype'] == 'Ship') { ?> selected="selected"<?php } ?>>Ship</option>
						<option value="Pack"<?php if ($unit['unittype'] == 'Pack') { ?> selected="selected"<?php } ?>>Pack</option>
						<option value="Den"<?php if ($unit['unittype'] == 'Den') { ?> selected="selected"<?php } ?>>Den</option>
						<option value="Individual"<?php if ($unit['unittype'] == 'Individual') { ?> selected="selected"<?php } ?>>Individual</option>
					</select>
					<label class="">Unit Type</label>
				</div>

   				<?php $t='number'; ?><div class="camperform float " style="width: 60px;"><input type="text" name="<?php echo $t; ?>" id="f<?php echo $t; ?>" class="" value="<?php echo $unit[$t];?>" placeholder="" /><label for="f<?php echo $t; ?>">Number</label></div>
   				<?php if (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) { ?>
   					<input type="hidden" name="associated" value="1" />
			   		<div class="camperform float" style="width: 100px;">
			   			<select name="associatedunit">
							<option value="Pack"<?php if ($unit['associatedunit'] == 'Pack') { ?> selected="selected"<?php } ?>>Pack</option>
						</select>
						<label for="associatedunit" class="">Parent Unit Type</label>
					</div>
	   				<?php $t='associatednumber'; ?><div class="camperform float " style="width: 60px;"><input type="text" name="<?php echo $t; ?>" id="f<?php echo $t; ?>" class="" value="<?php echo $unit[$t];?>" placeholder="" /><label for="f<?php echo $t; ?>">Number</label></div>
	   			<?php } ?>
   				<div class="clear hr"></div>
   				<?php $t='council'; ?><div class="camperform float " style="width: 300px;"><input type="text" name="<?php echo $t; ?>" id="f<?php echo $t; ?>" class="councillist typeahead" value="<?php echo $unit[$t];?>" data-toggle="tooltip" title="Enter your local Scout council, Begin typing to see hints! If you are from Northern Colorado, Southern Wyoming or Western Nebraska, your council is Longs Peak Council" /><label for="f<?php echo $t; ?>">Council</label></div>
   				<?php $t='district'; ?><div class="camperform float " style="width: 300px;"><input type="text" name="<?php echo $t; ?>" id="f<?php echo $t; ?>" class="districtlist typeahead" value="<?php echo $unit[$t];?>" data-toggle="tooltip" title="If you are in Longs Peak Council, please enter your district." /><label for="f<?php echo $t; ?>">District</label></div>
   				<div class="clear"></div>
   				<?php $t='address'; ?><div class="camperform float " style="width: 250px;"><input type="text" name="<?php echo $t; ?>" id="f<?php echo $t; ?>" class="" value="<?php echo $unit[$t];?>" placeholder="" /><label for="f<?php echo $t; ?>">Unit Address</label></div>
   				<?php $t='city'; ?><div class="camperform float " style="width: 150px;"><input type="text" name="<?php echo $t; ?>" id="f<?php echo $t; ?>" class="" value="<?php echo $unit[$t];?>" placeholder="" /><label for="f<?php echo $t; ?>">City</label></div>
   				<?php $t='state'; ?><div class="camperform float " style="width: 80px;"><input type="text" name="<?php echo $t; ?>" id="f<?php echo $t; ?>" class="" value="<?php echo $unit[$t];?>" placeholder="" /><label for="f<?php echo $t; ?>">City</label></div>
   				<?php $t='zip'; ?><div class="camperform float " style="width: 80px;"><input type="text" name="<?php echo $t; ?>" id="f<?php echo $t; ?>" class="" value="<?php echo $unit[$t];?>" placeholder="" /><label for="f<?php echo $t; ?>">Zip</label></div>
   				<div class="clear "></div>

   				<h2 class="section">Primary Contact</h2>
   				<?php if(!empty($userprimary)) { ?><div class="right"><?php echo anchor("users/".$userprimary->id, 'Edit '.$userprimary->first_name.' '.$userprimary->last_name.' &rarr;', 'class="btn tan"');?>
   				<?php if(!empty($useralternate)) { ?><br /><a href="#modal_change_primary" class="btn btn-small tan" data-toggle="modal">Swap &rarr;</a><?php } ?></div>
   				<?php $t='userprifirst'; ?><div class="camperform float " style=""><span><?php echo $userprimary->first_name;?></span><label for="f<?php echo $t; ?>">First</label></div>
   				<?php $t='userprilast'; ?><div class="camperform float " style=""><span><?php echo $userprimary->last_name;?></span><label for="f<?php echo $t; ?>">Last Name</label></div>
   				<?php $t='userpriphone'; ?><div class="camperform float " style=""><span><?php echo $userprimary->phone;?></span><label for="f<?php echo $t; ?>">Last Name</label></div>
   	    		<div class="clear"></div>
   				<?php $t='userpriemail'; ?><div class="camperform float " style=""><span><?php echo $userprimary->email;?></span><label for="f<?php echo $t; ?>">Email Address</label></div>
   				<div class="clear"></div>
   				<?php } else { ?>
   				<h2 class=""><i class="icon-remove red"></i> <?php echo $unit['unittype'];?> <?php echo $unit['number'];?> has no primary contact</h2>
   				<p>Units can not register for events without a primary contact. You should set a contact for the unit or request that the primary contact add one (they can log in and set an email inviting a user).</p>
   				<p><a href="#modal_new_contact" class="btn teal" data-toggle="modal">Add a Primary Contact &rarr;</a></p>
   				<?php } ?>

   				<h2 class="section">Alternate Contact</h2>
   				<?php if(!empty($useralternate)) { ?>
   				<div class="right"><?php echo anchor("users/".$useralternate->id, 'Edit '.$useralternate->first_name.' '.$useralternate->last_name.' &rarr;', 'class="btn tan"');?><br /><a href="#modal_change_alternate" class="btn btn-small tan" data-toggle="modal">Change or Swap &rarr;</a></div>
   				<?php $t='useraltfirst'; ?><div class="camperform float " style=""><span><?php echo $useralternate->first_name;?></span><label for="f<?php echo $t; ?>">First</label></div>
   				<?php $t='useraltlast'; ?><div class="camperform float " style=""><span><?php echo $useralternate->last_name;?></span><label for="f<?php echo $t; ?>">Last Name</label></div>
   				<?php $t='useraltphone'; ?><div class="camperform float " style=""><span><?php echo $useralternate->phone;?></span><label for="f<?php echo $t; ?>">Phone</label></div>
   	    		<div class="clear"></div>
   				<?php $t='useraltemail'; ?><div class="camperform float " style=""><span><?php echo $useralternate->email;?></span><label for="f<?php echo $t; ?>">Email Address</label></div>
   				<div class="clear"></div>
   				<?php } else { ?>
   				<h2 class=""><i class="icon-remove red"></i> <?php echo $unit['unittype'];?> <?php echo $unit['number'];?> has no alternate contact</h2>
   				<p>Units are best served when they have both primary and alternate contacts. You should set a contact for the unit or request that the primary contact add one (they can log in and set an email inviting a user).</p>
   				<a href="#modal_new_contact" class="btn teal" data-toggle="modal">Add an Alternate Contact &rarr;</a> <?php if(!empty($userprimary)) { ?><input type="submit" name="submit" value="Notify Primary Contact &rarr;" data-toggle="tooltip" title="This will send a notification to <?php echo $userprimary->first_name;?> <?php echo $userprimary->last_name;?>, the primary contact, asking them to set a new alternate contact." class="btn tan"  /><?php } ?>
   				
   				<?php } ?>
				<div class="clear tall"></div>
   				<h2 class="section">Invited Contacts</h2>
				<?php if ($invites) { ?>
	   				<h3>Open invites for <?php echo $unit['unittype']; ?> <?php echo $unit['number']; ?></h3>
	   				<p>When someone is invited to be a contact for <?php echo $unit['unittype']; ?> <?php echo $unit['number']; ?>,  they will appear here until they finish creating an account on Camper. If there are multiple invites here, the first one to signup will take the alternate contact spot. Contacts can be invited by administrators or the primary contact of the unit.</p> 
		   			<p><?php foreach ($invites as $invite) { ?>
		   					<strong><?php echo $invite['email']; ?></strong> was invited by <?php echo $invite['source']; ?> - <a href="#" onclick="remove_invite('<?php echo $invite['token']; ?>',this); return false;">Remove invite</a> or <a href="#" onclick="resend_invite('<?php echo $invite['token']; ?>',this); return false;">Resend</a><br />
		   				<?php } ?>
		   			</p>		
	   			<?php } else { ?><p>There are no pending invites for this unit.</p><?php } ?>

   			</div>
   		</div>
   		<div class="clear"></div>
   	<?php echo form_close();?> 
	</article>
	<article class="content">
	<?php if(!empty($userprimary)) { ?>
   	<!-- Change Primary Modal -->
	<div id="modal_change_primary" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<?php echo form_open('units/change_contact');?>
		<?php echo form_hidden('unitid', $unit['id']);?>
		<?php echo form_hidden('what', 'pri');?>
		<?php echo form_hidden($csrf); ?>
   		<div class="container">
   			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
       		<div class="pull">
   	    		<h2 class="pull">Change Contacts</h2>
   	    		<p>You can change the primary contact for this unit here.</p>
   	    		<div class="clear"></div>
       		</div>
       		<div class="tab-content inner-push">
   				<h2 class="section">Current Primary Contact </h2>
  				<?php $t='userprifirst'; ?><div class="camperform float " style="width: 100px;"><span><?php echo $userprimary->first_name;?></span><label for="f<?php echo $t; ?>">First</label></div>
   				<?php $t='userprilast'; ?><div class="camperform float " style="width: 180px;"><span><?php echo $userprimary->last_name;?></span><label for="f<?php echo $t; ?>">Last Name</label></div>
   				<?php $t='userpriphone'; ?><div class="camperform float " style="width: 140px;"><span><?php echo $userprimary->phone;?></span><label for="f<?php echo $t; ?>">Phone</label></div>
   	    		<div class="clear"></div>
   				<?php $t='userpriemail'; ?><div class="camperform float " style="width: 350px;"><span><?php echo $userprimary->email;?></span><label for="f<?php echo $t; ?>">Email Address</label></div>
	   			<div class="clear"></div>
   				<!-- Can't cold turkey change unit primary, swap only for now
   				<h2 class="section">Change leader via email </h2>
   				<p>You can change the existing primary contact by entering the email address for the user you wish to make as the new contact. If the user is in the system, you can search in this box for users' names, phone numbers and email addresses. </p>
   		   		<div class="camperform float search" style="width: 60%" ><i class="icon-envelope-alt"></i><input class=" userslist typeahead ico" type="email" name="newprimary" id="fnewprimary" placeholder="baden.powell@scouting.org" /><label for="fnewprimary">New Primary Contact Email Address</label></div>
	   			<div class="clear"></div>
	   			<p><a data-toggle="popover" title="Are you sure?" data-placement="top" data-content="You are about to replace <strong><?php echo $userprimary->first_name;?> <?php echo $userprimary->last_name;?></strong> with the email address you entered above as the primary contact.<br /><br /><input type='submit' name='submit' value='Set Contact' class='btn teal' /> " class="btn teal camperpopover">Save New Contact &rarr; </a> <button class="btn tan" data-dismiss="modal" aria-hidden="true">Nevermind</button></p>
	   			-->
   				<?php if(!empty($useralternate)) { ?>
   				<h2 class="section">Swap Unit Contacts </h2>
   				<p>If you wish to swap the alternate and primary contacts for this unit, you can do it in one step here.</p>
	   			<p><a data-toggle="popover" title="Are you sure?" data-placement="top" data-content="You are about to make <strong><?php echo $userprimary->first_name;?> <?php echo $userprimary->last_name;?></strong> the alternate contact and <strong><?php echo $useralternate->first_name;?> <?php echo $useralternate->last_name;?></strong> the primary contact.<br /><br /><input type='submit' name='submit' value='Swap Contacts' class='btn teal' /> " class="btn teal camperpopover">Swap Contacts &rarr; </a></p>
	   			<?php } ?>
   			</div>
   		</div>
   		<div class="clear"></div>
   		<?php echo form_close();?>
	</div>
   	<!-- End Modal -->
	<?php } if(!empty($useralternate)) { ?>
   	<!-- Change Alternate Modal -->
	<div id="modal_change_alternate" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<?php echo form_open('units/change_contact');?>
		<?php echo form_hidden('unitid', $unit['id']);?>
		<?php echo form_hidden('what', 'alt');?>
		<?php echo form_hidden($csrf); ?>
   		<div class="container">
   			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
       		<div class="pull">
   	    		<h2 class="pull">Change Contacts</h2>
   	    		<p>You can change the alternate contact for this unit here.</p>
   	    		<div class="clear"></div>
       		</div>
       		<div class="tab-content inner-push">
   				<h2 class="section">Current Alternate Contact </h2>
  				<?php $t='userprifirst'; ?><div class="camperform float " style="width: 100px;"><span><?php echo $useralternate->first_name;?></span><label for="f<?php echo $t; ?>">First</label></div>
   				<?php $t='userprilast'; ?><div class="camperform float " style="width: 180px;"><span><?php echo $useralternate->last_name;?></span><label for="f<?php echo $t; ?>">Last Name</label></div>
   				<?php $t='userpriphone'; ?><div class="camperform float " style="width: 140px;"><span><?php echo $useralternate->phone;?></span><label for="f<?php echo $t; ?>">Phone</label></div>
   	    		<div class="clear"></div>
   				<?php $t='userpriemail'; ?><div class="camperform float " style="width: 350px;"><span><?php echo $useralternate->email;?></span><label for="f<?php echo $t; ?>">Email Address</label></div>
	   			<div class="clear"></div>
   				<h2 class="section">Change leader via email </h2>
   				<p>You can change the existing alternate contact by entering the email address for the user you wish to make as the new contact. If the user is in the system, you can search in this box for users' names, phone numbers and email addresses. </p>
   		   		<div class="camperform float search" style="width: 60%" ><i class="icon-envelope-alt"></i><input class=" userslist typeahead ico" type="email" name="newalternate" id="fnewalternate" placeholder="baden.powell@scouting.org" /><label for="fnewalternate">New Alternate Contact Email Address</label></div>
	   			<div class="clear"></div>
	   			<p><a data-toggle="popover" title="Are you sure?" data-placement="top" data-content="You are about to replace <strong><?php echo $useralternate->first_name;?> <?php echo $useralternate->last_name;?></strong> with the email address you entered above as the alternate contact.<br /><br /><input type='submit' name='submit' value='Set Contact' class='btn teal' /> " class="btn teal camperpopover">Save New Contact &rarr; </a> <button class="btn tan" data-dismiss="modal" aria-hidden="true">Nevermind</button></p>
   				<h2 class="section">Swap Unit Contacts </h2>
   				<p>If you wish to swap the alternate and primary contacts for this unit, you can do it in one step here.</p>
	   			<p><a data-toggle="popover" title="Are you sure?" data-placement="top" data-content="You are about to make <strong><?php echo $userprimary->first_name;?> <?php echo $userprimary->last_name;?></strong> the alternate contact and <strong><?php echo $useralternate->first_name;?> <?php echo $useralternate->last_name;?></strong> the primary contact.<br /><br /><input type='submit' name='submit' value='Swap Contacts' class='btn teal' /> " class="btn teal camperpopover">Swap Contacts &rarr; </a></p>
   			</div>
   		</div>
   		<div class="clear"></div>
   		<?php echo form_close();?>
	</div>
   	<!-- End Modal -->
   	<?php } else { ?>
   	<!-- Add Alternate Modal -->
	<div id="modal_new_contact" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<?php echo form_open('units/change_contact');?>
		<?php echo form_hidden('unitid', $unit['id']);?>
		<?php echo form_hidden('what', 'new');?>
		<?php echo form_hidden($csrf); ?>
   		<div class="container">
   			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
       		<div class="pull">
   	    		<h2 class="pull">Change Contacts</h2>
   	    		<p>You can change the alternate contact for this unit here.</p>
   	    		<div class="clear"></div>
       		</div>
       		<div class="tab-content inner-push">
   				<h2 class="section">Add a new Alternate Contact </h2>
   				<p>Since this unit is missing a contact, you can add one here. Simply add the contact's email address below or if the user is in the system, you can search in this box for users' names, phone numbers and email addresses and select their email address. </p>
   		   		<div class="camperform float search" style="width: 60%" ><i class="icon-envelope-alt"></i><input class=" userslist typeahead ico" type="email" name="newcontact" id="fnew" placeholder="baden.powell@scouting.org" /><label for="fnew">New Contact Email Address</label></div>
	   			<div class="clear"></div>
	   			<p><input type='submit' name='submit' value='Set New Contact' class='btn teal' /> <button class="btn tan" data-dismiss="modal" aria-hidden="true">Nevermind</button></p>
   			</div>
   		</div>
   		<div class="clear"></div>
   		<?php echo form_close();?>
	</div>
   	<!-- End Modal -->
   	<?php } ?>
	</article>
