<?php 

/* 
 * Camper My Unit / New Member View
 *
 * This is the roster view of the "My Unit" section in camper. 
 *
 * File: /application/views/myunit/details.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

 $unittitle = (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')': $unit['unittype'].' '.$unit['number'];


?>	<div class="subnav">
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
   				<li class=""><?php echo anchor("units/".$unit['id'], 'Unit Details');?></li>
   				<li class=""><?php echo anchor("units/".$unit['id']."/payments", 'Payments');?></li>
   				<li class=""><?php echo anchor("units/".$unit['id']."/registrations", 'Registrations');?></li>
   				<li class="active"><?php echo anchor("units/".$unit['id']."/members", 'Members');?></li>
   			</ul>
   		</div>
	<?php echo form_open(uri_string());?>
		<?php if ($this->input->get('return')) { ?> <input type="hidden" name="return" value="<?php echo $this->input->get('return'); ?>" /><?php } ?>
		<div class="container">
			<div class="quarter">
				<h2>Members</h2>
				<p>Manage your unit's primary and alternate contacts and other details here. Didn't want to add a new member, you can <?php echo anchor('units/'.$unit['id'].'/members', 'see all members'); ?>.</p>
				<p><?php echo anchor('units/'.$unit['id'].'/members', '&larr; All Members', 'class="btn tan"'); ?> </p>	
				<div class="clear"></div>
			</div>
			<div class="threequarter">
				<h2 class="">Add a Member to <?php echo $unittitle; ?></h2>
				<p>Easily add a member to <?php echo $unittitle; ?> here, simply enter the name, date of birth, and the gender. If you add other notes including shirt size, allergies or dietary restrictions here, we can use this information to better accommodate your participation in events. </p>
				<p><strong>You can add both adults and youth</strong> to camper here, we will use the birthday to figure out if the member is an adult or youth according to your unit type (adults are <?php echo ($unit['unittype'] == 'Ship' || $unit['unittype'] == 'Crew') ? '21': '18'; ?> and older for <?php echo $unit['unittype']; ?>s). The date of birth is required and will only be visible to you and the other <?php echo $unittitle; ?> leaders.</p>
				<div class="clear"></div>

				<div class="camperform float " style="width: 70%"><input type="text" name="member[name]" id="fname" value="<?php echo ($success) ? '': set_value('member[name]'); ?>" placeholder="Baden Powell" data-toggle="tooltip" title="The member's name" /><label for="fname">Name</label></div>
				<div class="clear"></div>
				<div class="camperform float " style="width: 90px">
					<select id="fgender" name="member[gender]">
						<option value="Male">Male</option>
						<option value="Female">Female</option>
					</select>
					<label for="fgender">Gender</label>
				</div>
				<div class="camperform float " style="width: 120px">
					<select id="fshirt" name="member[shirtsize]">
						<option value="">Select a size</option>
						<option value="XXS">XXS</option>
						<option value="XS">XS</option>
						<option value="Small">Small</option>
						<option value="Medium">Medium</option>
						<option value="Large">Large</option>
						<option value="XL">XL</option>
						<option value="XXL">XXL</option>
						<option value="XXXL">XXXL</option>
					</select>
					<label for="fshirt">Shirt Size</label>
				</div>
				<div class="camperform float camperhoverpopover" style="width: 30%" placeholder="Click to set..." data-toggle="popover" title="Date of Birth" data-placement="top" data-content="Don't worry, we will keep this information safe. Birth dates are never displayed except here for you as the leader, we use this information to calculate the age of a participant at the time of an event."><input type="text" name="member[dob]" class="datepicker" id="fdob" value="<?php echo ($success) ? '': set_value('member[dob]'); ?>"  /><label for="fdob">Date of Birth</label></div>
				<div class="clear"></div>
				<p>We use the address and phone details to pre-fill rosters and Merit Badge Blue Card applications for some events. This information is collected per our <?php echo anchor('help#privacy', 'Privacy Policy', 'target="_blank"'); ?>.</p>
				<div class="clear"></div>
				<div class="camperform float" style="width: 45%"><input type="text" name="member[address]" id="fadd" value="<?php echo ($success) ? '': set_value('member[address]'); ?>" placeholder="Address" data-toggle="tooltip" title="The mailing address" /><label for="fadd">Address</label></div>
				<div class="camperform float" style="width: 45%"><input type="text" name="member[citystate]" id="fcs" value="<?php echo ($success) ? '': set_value('member[citystate]'); ?>" placeholder="City, State and Zip" data-toggle="tooltip" title="The city, state, and zip code" /><label for="fcs">City, State and Zip</label></div>
				<div class="clear"></div>
				<div class="camperform float" style="width: 45%"><input type="text" name="member[phone]" id="fph" onchange="formatPhone(this);" onkeydown="formatPhone(this);" value="<?php echo ($success) ? '': set_value('member[phone]'); ?>" placeholder="Phone Number" data-toggle="tooltip" title="A phone number we can use to contact in the event of an emergency or personal contact." /><label for="fph">Phone Number</label></div>
				<div class="camperform float" style="width: 45%"><input type="text" name="member[insurance]" id="fis" value="<?php echo ($success) ? '': set_value('member[citystate]'); ?>" placeholder="Insurance Details" data-toggle="tooltip" title="Add this members accident insurance policy and number, we will use this in case of emergencies and prefill this on the unit roster for events" /><label for="fis">Insurance Policy and Number</label></div>
				<div class="clear"></div>
				<p>We use the following information at camp and other events you register for in order to better accommodate allergies, dietary needs, and other medical conditions that will need our attention.</p>
				<div class="clear"></div>
				<div class="camperform float" style="width: 96%"><input type="text" name="member[allergies]" id="fallergies" value="<?php echo ($success) ? '': set_value('member[allergies]'); ?>" placeholder="Allergies, if any..." data-toggle="tooltip" title="Identify any allergies that we should know about" /><label for="fallergies">Allergies</label></div>
				<div class="clear"></div>
				<div class="camperform float" style="width: 96%"><input type="text" name="member[diet]" id="fdiet" value="<?php echo ($success) ? '': set_value('member[diet]'); ?>" placeholder="Dietart restrictions, if any..." data-toggle="tooltip" title="Are there any dietary restrictions that we will need to observe" /><label for="fdiet">Dietary Restrictions</label></div>
				<div class="clear"></div>
				<div class="camperform float" style="width: 96%"><input type="text" name="member[medical]" id="fmedical" value="<?php echo ($success) ? '': set_value('member[medical]'); ?>" placeholder="Medical conditions, if any..." data-toggle="tooltip" title="Are there any medical conditions that we will need to accommodate" /><label for="fmedical">Medical Notes</label></div>
				<div class="clear"></div>
				<div class="camperform float" style="width: 96%"><input type="text" name="member[notes]" id="fother" value="<?php echo ($success) ? '': set_value('member[notes]'); ?>" placeholder="Other notes, if any..." data-toggle="tooltip" title="Event description, 2 sentences max" /><label for="fdesc">Notes</label></div>
				<div class="clear"></div>
				<input type="submit" name="submit" value="Save Member" class="btn teal" data-loading-text="Saving member..." onclick="$(this).button('loading');" /> <input type="reset" name="reset" value="Reset" class="btn tan"  />	


			</div>
		</div>
		<div class="clear"></div>
	<?php echo form_close();?> 
	</article>
