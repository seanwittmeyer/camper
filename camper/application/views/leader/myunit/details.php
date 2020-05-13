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

	
?>	<div id="help">
		<div class="container">
			<div class="quarter">
				<h2>How can we help?</h2>
				<p>Choose the section you want to learn about to see help topics. Contact us if you still need a hand.</p>
				<p><i class="icon icon-phone"></i> <?php echo $this->config->item('camper_supportphone'); ?><br /><i class="icon icon-envelope"></i> Registration Questions: <?php echo $this->config->item('camper_supportemail'); ?><br /><i class="icon icon-envelope-alt"></i> Site Issues: <?php echo $this->config->item('camper_fromemail'); ?></p>
			</div>
			<div class="threequarter last">
				<button class="btn btn-link" style="display: block; position: absolute; right: 60px; z-index:1000; " onclick="$('#help').slideToggle(100); return false; "><i class="icon icon-remove"></i> Close</button>
				<ul class="nav nav-tabs" id="myTab">
					<li class="active"><a data-toggle="tab" href="#help-basics">The Basics</a></li>
					<li><a data-toggle="tab" href="#help-details">Unit Contact Info</a></li>
					<li><a data-toggle="tab" href="#help-users">Primary Contact</a></li>
					<li><a data-toggle="tab" href="#help-contacts">Alternate Contacts</a></li>
				</ul>
				 
				<div class="tab-content">
					<div class="tab-pane active fade in" id="help-basics">
						<p>This is the home tab. Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui</p>
					</div>
					<div class="tab-pane fade" id="help-details">
						<p>Contact issues . Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui. Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
					</div>
					<div class="tab-pane fade" id="help-users">
						<p>How those users? Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
					</div>
					<div class="tab-pane fade" id="help-contacts">
						<p>Contacts... Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui. aw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div><!-- /#help -->
	<div class="flow">
		<div class="container">
			<div class="quarter">
				<h2>Make a payment</h2>
				<p>Choose the section you want to learn about to see help topics. Contact us if you still need a hand.</p>
			</div>
			<div class="threequarter last">
				<button class="btn btn-link" style="display: block; position: absolute; right: 60px; z-index:1000; " onclick="$('.flow').slideToggle(100); return false; "><i class="icon icon-remove"></i> Close</button>
				<!--<ul class="nav nav-tabs" id="myTab">
					<li class="active"><a data-toggle="tab" href="#help-basics">The Basics</a></li>
					<li><a data-toggle="tab" href="#help-details">Unit Contact Info</a></li>
					<li><a data-toggle="tab" href="#help-users">Primary Contact</a></li>
					<li><a data-toggle="tab" href="#help-contacts">Alternate Contacts</a></li>
				</ul>-->
				 
				<div class="tab-content">
					<div class="tab-pane active fade in" id="flow-basics">
						<h2>Step 1: Choose an amount</h2>
		   				<div class="camperform float" style="width: 255px"><input type="text" name="address" id="fadd" value="<?php echo $unit['address']; ?>" data-toggle="tooltip" data-placement="right" title="This is where we will mail documents relating to your registered events" /><label for="fadd">Mailing Address</label></div>
		   				<div class="clear"></div>
		   				<div class="camperform float" style="width: 140px"><input type="text" name="city" id="fcity" value="<?php echo $unit['city']; ?>" data-toggle="tooltip" data-placement="right" title="Your City" /><label for="fcity">City</label></div>
				   		<div class="camperform float" style="width: 160px;">
							<select id="fstate" name="state"> 
								<option value="<?php echo strtoupper($unit['state']); ?>" selected="selected"><?php echo $states[strtoupper($unit['state'])]; ?></option> 
								<option value="" disabled="disabled">Select a State</option> 
								<option value="AL">Alabama</option> 
								<option value="WY">Wyoming</option>
							</select><label for="fstate">State</label>
				   		</div>
		   				<div class="camperform float" style="width: 60px"><input type="text" name="zip" id="fzip" value="<?php echo $unit['zip']; ?>"  data-toggle="tooltip" data-placement="right" title="Enter your Zip Code (without the +4 extension)" /><label for="fzip">Zip Code</label></div>
						<li class="active"><a data-toggle="tab" href="#flow-basics">The Basics</a></li>
						<li><a data-toggle="tab" href="#flow-details">Unit Contact Info</a></li>
	
					</div>
					<div class="tab-pane fade" id="flow-details">
						<h2>Step 2: How do you want to pay?</h2>
						<p>Contact issues . Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui. Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
						<li><a data-toggle="tab" href="#flow-basics">The Basics</a></li>
						<li class="active"><a data-toggle="tab" href="#flow-details">Unit Contact Info</a></li>
	
					</div>
					<div class="tab-pane fade" id="flow-users">
						<p>How those users? Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
					</div>
					<div class="tab-pane fade" id="flow-contacts">
						<p>Contacts... Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui. aw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div><!-- /.flow -->
	<article class="wide">
	<?php echo form_open(uri_string());?>
		<header>
			<div class="left">
				<h1><?php echo $unittitle; ?></h1>
				<p>Manage your <?php echo lcfirst((isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit']:$unit['unittype']); ?>'s primary and alternate contacts and other details here.</p>
   				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><div class="clear"></div><?php } ?> 
				<nav class="article">
				<li class="active"><?php echo anchor("unit", '<i class="icon icon-align-left"></i> Details');?></li>
				<li><?php echo anchor("unit/members", '<i class="icon icon-group"></i> Members');?></li>
				</nav>
			</div>
			<div class="right">
				<input type="submit" name="submit" value="Save Changes" class="btn teal" data-loading-text="Saving..." onclick="$(this).button('loading');" /> <input type="reset" name="reset" value="Reset" class="btn tan"  />
				<div class="clear"></div>
			</div>
		    <div class="clear"></div>
		</header>
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
       		<div class="tab-content">
	       		<div class="half center"> 
	   				<h2>The Basics</h2>
	   				<p class="padded">This is intended to be the shortest and most brief way to describe this bit of the page. <a href="#" onclick="$('.flow').slideToggle(100); return false; ">A link to the help box would be awesome.</a></p>
	   				<img src="http://www.scouting.org/filestore/marketing/logos/Venturing/VenturingNoType_4K.gif" class="unittypeimg">
	   				<div>
		   				<?php if (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) { ?> 
		   				<div class="camperform float " style="width: 30%"><input type="text" name="funit" id="funit" value="<?php echo $unittitle; ?>" data-toggle="tooltip" data-placement="right" title="TITLETITLETITLE" /><label for="funit">Unit</label></div>
		   				<?php } else { ?> 
		   				<div class="camperform float " style="width: 25%"><input type="text" name="ftype" id="ftype" value="<?php echo $unit['unittype']; ?>" data-toggle="tooltip" data-placement="right" title="TITLETITLETITLE" /><label for="ftype">Unit Type</label></div>
		   				<div class="camperform float last" style="width: 20%"><input type="text" name="fnumber" id="fnumber" value="<?php echo $unit['number']; ?>" data-toggle="tooltip" data-placement="right" title="TITLETITLETITLE" /><label for="fnumber">Number</label></div>
		   				<?php } ?> 
		   				<div class="camperform float " style="width: 50%"><input type="text" name="fcouncil" id="fcouncil" value="<?php echo $unit['council']; ?>" data-toggle="tooltip" data-placement="right" title="TITLETITLETITLE" /><label for="fcouncil">Council</label></div>
		   				<div class="camperform float " style="width: 50%"><input type="text" name="fdistrict" id="fdistrict" value="<?php echo $unit['district']; ?>" data-toggle="tooltip" data-placement="right" title="TITLETITLETITLE" /><label for="fdistrict">District</label></div>
		   				<div class="clear"></div>
	   				</div>
	   				<div class="clear"></div>
	       		</div><!-- /.half -->
	       		<div class="half center"> 
	   				<h2>How we'll contact <?php echo $unittitle; ?></h2>
	   				<p class="padded">This is intended to be the shortest and most brief way to describe this bit of the page. A link to the help box would be awesome.</p>
	   				<div class="camperform float" style="width: 95%"><input type="text" name="address" id="fadd" value="<?php echo $unit['address']; ?>" data-toggle="tooltip" data-placement="right" title="This is where we will mail documents relating to your registered events" /><label for="fadd">Mailing Address</label></div>
	   				<div class="clear"></div>
	   				<div class="camperform float" style="width: 33%"><input type="text" name="city" id="fcity" value="<?php echo $unit['city']; ?>" data-toggle="tooltip" data-placement="right" title="Your City" /><label for="fcity">City</label></div>
			   		<div class="camperform float" style="width: 37%;">
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
	   				<div class="camperform float" style="width: 15%"><input type="text" name="zip" id="fzip" value="<?php echo $unit['zip']; ?>"  data-toggle="tooltip" data-placement="right" title="Enter your Zip Code (without the +4 extension)" /><label for="fzip">Zip Code</label></div>
	       		</div><!-- /.half -->
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
		<?php echo form_open("unit/change_contact"); ?><input type="hidden" name="unit" value="<?php echo $unit['id']; ?>" /><?php echo form_hidden($csrf); ?>

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
