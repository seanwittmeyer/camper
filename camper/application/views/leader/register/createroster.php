<?php 

/* 
 * Camper Leader / Registration / Create Roster
 *
 * This is the roster view of the leader registration section in camper. 
 *
 * File: /application/views/register/createroster.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

 	// Normal user with an unit
 	$unittitle = (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')': $unit['unittype'].' '.$unit['number'];
 	$helpingverb =' is';
	if (!empty($members)) {
		$now = time();
		$year = 31556926;
		$adults = array();
		$youth = array();
		$adult = ($unit['unittype'] == 'Ship' || $unit['unittype'] == 'Crew') ? ($year * 21): ($year * 18); // 21 and 18 years in seconds
		foreach ($members as $member) {
			if ($now-$member['dob'] >= $adult) {
				$adults[$member['id']] = $member;
			} else {
				$youth[$member['id']] = $member;
			} 
		}
	} else {
		$adults = array();
		$youth = array();
		$message = anchor('/unit/members', 'Add Members to Camper &rarr;', 'class="btn btn-small blue right"').'<i class="icon-info-sign blue"></i> You don\'t have any members in Camper, want to add some before creating your roster?';
	}

?>	<div class="subnav">
		<div class="container">
			<h2>Registrations</h2>
			<nav class="campersubnav">
				<li class=""><?php echo anchor("registrations/past", 'Past Events');?></li>
				<li class="active"><?php echo anchor("registrations", 'Upcoming Events');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
		<?php echo form_open(uri_string());?>
		<?php //echo form_hidden($csrf); ?>
		<input type="hidden" name="unit" value="<?php echo $unit['id']; ?>" />
		<input type="hidden" name="event" value="<?php echo $event['id']; ?>" />
		<input type="hidden" name="reg" value="<?php echo $reg['id']; ?>" />
		<div class="container">
			<h2>Registrations / <?php echo $event['title']; ?></h2>
				<p>You can manage all of the details regarding <strong><?php echo $unittitle; ?>'s</strong> registration for <strong><?php echo $event['title']; ?></strong>. You will find the event dates and details, any extras you can choose to add or let us know about, and manage the finances of the event (see outstanding program fees and make payments).	</p>
			<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
		</div>
			<div class="container">
				<ul id="detailstabs" class="blue">
					<li class=""><?php echo anchor('registrations/'.$reg['id'].'/details#basics', 'Basics'); ?></li>
					<li class=""><?php echo anchor('registrations/'.$reg['id'].'/details#extras', 'Extras'); ?></li>
					<li class=""><?php echo anchor('registrations/'.$reg['id'].'/details#finances', 'Finances'); ?></li>
					<li class="active"><?php echo anchor('registrations/'.$reg['id'].'/roster', 'Roster'); ?></li>
				</ul>
			</div>
		<div class="container">
			<div class="threequarter">
				<h2 class="">Create Roster</h2>
				<p>You are about to create <strong><?php echo $unittitle; ?>'s</strong> roster for <strong><?php echo $event['title']; ?></strong>. Start by adding members from your unit into the open spots below and save your changes when you are done. You can leave spots open as place holders until you add your unit members into Camper. You can add and manage members in <?php echo anchor('unit/members', 'My Unit'); ?>.</p>
				<p><?php echo anchor('unit/members', 'Add &amp; Manage '.$unittitle.' Members &rarr;', 'class="btn blue"'); ?></p>
			</div>
			<div class="clear hr"></div>
			<script>
				function edituser(userid)
				{
					$('#modal_edit_member').modal('show');
				}
			</script>
			<h3>Youth</h3>
			<p>You have <strong><?php echo $reg['youth']; ?> youth</strong> spots, select one unique member for each spot or leave blank for a placeholder. Is the youth you are looking for not in the list? Make sure you have added them to your unit as members.</p>
			<?php $i = 1; while ($i <= $reg['youth']) { ?>
				<div class="camperform float" data-toggle="tooltip" id="fyspot<?php echo $i; ?>" title="Don't worry about this number, it has no significance except to let you know how many people you are adding to your roster."><span><?php echo $i; ?></span><label for="fyspot<?php echo $i; ?>" >Spot</label></div>
   				<div class="camperform float " style="">
	   				<select id="fymember<?php echo $i; ?>" name="youth[<?php echo $i; ?>][id]" data-toggle="tooltip" title="Choose a member, make sure you haven't chosen anyone more than once.">
						<option value="0">Placeholder</option>
						<?php foreach ($youth as $y) { ?><option value="<?php echo $y['id']; ?>"><?php echo $y['name']; ?></option><?php } ?>
					</select>
					<label for="fymember<?php echo $i; ?>">Choose a Member</label>
   				</div>
   				<div class="clear"></div>
			<?php $i++; } ?>
			<div class="clear hr"></div>
			<h3>Adults</h3>
			<p>You have <strong><?php echo $reg['male']+$reg['female']; ?> adult</strong> spots, select one unique adult member for each spot or leave blank for a placeholder. Is the adule you are looking for not in the list? Make sure you have added them to your unit as members.</p>
			<?php $i = 1; while ($i <= ($reg['male']+$reg['female'])) { ?>
				<div class="camperform float" data-toggle="tooltip" id="faspot<?php echo $i; ?>" title="Don't worry about this number, it has no significance except to let you know how many people you are adding to your roster."><span><?php echo $i; ?></span><label for="fyspot<?php echo $i; ?>" >Spot</label></div>
   				<div class="camperform float " style="">
	   				<select id="fymember<?php echo $i; ?>" name="adults[<?php echo $i; ?>][id]" data-toggle="tooltip" title="Choose a member, make sure you haven't chosen anyone more than once.">
						<option value="0">Placeholder</option>
						<?php foreach ($adults as $a) { ?><option value="<?php echo $a['id']; ?>"><?php echo $a['name']; ?></option><?php } ?>
					</select>
					<label for="fymember<?php echo $i; ?>">Choose a Member</label>
   				</div>
   				<div class="clear"></div>
			<?php $i++; } ?>
			<div class="clear hr"></div>
			<input type="submit" value="Create Roster &rarr;" data-loading-text="Creating your roster..." onclick="$(this).button('loading');" class="btn teal" /> <input type="reset" value="Reset" class="btn tan" />
		</div>
		<?php echo form_close();?> 
	</article>
