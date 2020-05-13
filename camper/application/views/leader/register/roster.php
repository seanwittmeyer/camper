<?php 

/* 
 * Camper Leader / Registration / Roster
 *
 * This is the roster view of the leader registration section in camper. 
 *
 * File: /application/views/register/roster.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

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
		$message = anchor('/unit/members', 'Add Members to Camper &rarr;', 'class="btn btn-small blue right"').'<i class="icon-info-sign blue"></i> You don\'t have any members in Camper, want to add them to help build your roster?';
	}
	// Prep the discounts
	$i=0; foreach ($discounts as $d) {
		if ($d['individual'] == '1') $i++;
	}
	$hasdiscounts = ($i === 0) ? false: true;

	// Lock out if past event start date
	$startdate = (empty($session['datestart'])) ? $event['datestart']: $session['datestart'];
	$lock = (time() > $startdate) ? true : false;
	
?>
	<div class="subnav">
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
			<h2 class="">Roster</h2>
			<p>This is a list of <strong><?php echo $unittitle; ?>'s</strong> participating members for <strong><?php echo $event['title']; ?></strong>. You can fill placeholders with members in your unit or remove existing members from this event. If you remove a member here, they will be unregistered from all classes and activities and their spot will become a placeholder.</p>
			<p><?php echo anchor('api/v1/unitroster/'.$reg['id'].'.pdf', '<i class="icon-file-text inline"></i> Print/View Your Roster', 'class="btn tan camperhoverpopover" data-toggle="popover" title="Roster (pdf)" data-content="You will need to print and complete three copies of this document. Two copies to turn in during check-in at camp, and a copy for your records."'); ?> <?php echo anchor('api/v1/reports/classregs/reg/'.$reg['id'].'.csv', '<i class="icon-file-text inline"></i> Download Class Registrations', 'class="btn tan camperhoverpopover" data-toggle="popover" title="Class Registrations (csv)" data-content="This will download a spreadsheet with all class registrations for each person in this registration. Use in Microsoft Excel"'); ?></p>
			<div class="clear"></div>
			<ul id="detailstabs" class="blue">
				<li class="active"><a href="#youth" data-toggle="tab">Youth Participants</a></li>
				<li class=""><a href="#adults" data-toggle="tab">Adult Participants</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade in active" id="youth">
					<table class="table table-condensed">
						<thead>
							<tr><th>Name</th><th>Age</th><th>Shirt Size</th><th>Special Notes</th><th style="text-align:right;">Tools</th></tr>
						</thead>
						<tbody>
						<?php 
							$now = time();
							$i=1;
							$adult = ($unit['unittype'] == 'Ship' || $unit['unittype'] == 'Crew') ? (31556926 * 21): (31556926 * 18); // 21 and 18 years in seconds
							foreach ($roster as $r) {
								// Real
								if ($now-$members[$r['member']]['dob'] >= $adult) continue; 
								?>
								<tr>
									<td><?php echo anchor('registrations/'.$reg['id'].'/roster/'.$r['id'], $members[$r['member']]['name']); ?></td>
									<td><?php echo floor((($now-$members[$r['member']]['dob']) / 31556926)); ?></td>
									<td><?php echo $members[$r['member']]['shirtsize']; ?></td>
									<td><?php if (!empty($members[$r['member']]['allergies']) || !empty($members[$r['member']]['diet']) || !empty($members[$r['member']]['medical']) || !empty($members[$r['member']]['notes'])) {
										?><span class="label label-tan camperhoverpopover" data-toggle="popover" title="Special Notes" data-placement="top" data-content="
										Special notes are details that you want to share with event/camp staff, we use this to better accomidate and serve you.<br><br>
										<?php echo (!isset($members[$r['member']]['allergies']) || empty($members[$r['member']]['allergies'])) ? '': '<strong>Allergies</strong>: '.$members[$r['member']]['allergies'].' <br>'; ?>
										<?php echo (!isset($members[$r['member']]['diet']) || empty($members[$r['member']]['diet'])) ? '': '<strong>Dietary Restrictions</strong>: '.$members[$r['member']]['diet'].' <br>'; ?>
										<?php echo (!isset($members[$r['member']]['medical']) || empty($members[$r['member']]['medical'])) ? '': '<strong>Medical Conditions</strong>: '.$members[$r['member']]['medical'].' <br>'; ?>
										<?php echo (!isset($members[$r['member']]['notes']) || empty($members[$r['member']]['notes'])) ? '': '<strong>Notes</strong>: '.$members[$r['member']]['notes'].' <br>'; ?>
										<br>You can add or edit these restrictions by editing this member's profile, click the edit button to head there.
										">Notes</span><?php } ?></td>
									<td style="text-align:right;"><?php if ($hasdiscounts) { echo anchor('registrations/'.$reg['id'].'/roster/'.$r['id'].'#discounts', '<i class="icon-ticket"></i> Discounts', 'class="btn btn-small blue"'); } ?> <?php echo anchor('registrations/'.$reg['id'].'/roster/'.$r['id'], '<i class="icon-group"></i> Classes', 'class="btn btn-small blue"'); ?> <?php echo anchor('api/v1/rosters/invoice/'.$r['id'].'.pdf', '<i class="icon-file-text"></i>', 'class="btn btn-small tan"  data-toggle="tooltip" title="Download an individual invoice and schedule PDF"'); ?> <?php echo anchor('unit/members/'.$members[$r['member']]['id'].'?return='.uri_string(), '<i class="icon-pencil"></i>', 'class="btn btn-small tan" data-toggle="tooltip" title="Edit Member"'); ?> <a data-toggle="popover" title="Delete" data-placement="top" data-content="Are you sure you want to remove this member from your roster? They will be unregistered for any classes.<br /><br />Don't worry, <?php echo $members[$r['member']]['name']; ?> won't be deleted from Camper.<br /><br /><?php echo str_replace('"', "'", anchor('api/v1/roster/delete?m='.$members[$r['member']]['id'].'&r='.$r['id'].'&return='.uri_string(), 'Delete '.$members[$r['member']]['name'], 'class="btn red"')); ?>" class="btn btn-small red camperpopover"><i class="icon-remove"></i></a></td>
								</tr>
							<?php $i++; } ?>
							<?php while ($i <= $reg['youth']) { ?>
								<tr>
									<td colspan="8">
										<div class="camperform float last" style="">
										<select id="fymember<?php echo $i; ?>" name="youth[<?php echo $i; ?>][id]" data-toggle="tooltip" title="Choose a member, make sure you haven't chosen anyone more than once.">
											<option value="0">Choose a youth...</option>
											<option value="0">Empty Spot / Placeholder</option>
											<optgroup label="Youth in <?php echo $unittitle; ?>">
											<?php foreach ($youth as $y) { ?><option value="<?php echo $y['id']; ?>"><?php echo $y['name']; ?></option><?php } ?>
											</optgroup>
										</select> or &nbsp; <?php echo anchor('unit/members/new?return='.uri_string(), 'Add a member &rarr;', 'class="btn btn-small tan"'); ?> 
										</div>
									</td>
								</tr>
							<? $i++; } ?>
						</tbody>	
					</table>
				</div>
				<div class="tab-pane fade" id="adults">
					<table class="table table-condensed">
						<thead>
							<tr><th>Name</th><th>Age</th><th>Shirt Size</th><th>Special Notes</th><th style="text-align:right;">Tools</th></tr>
						</thead>
						<tbody>
							<?php
							$i=1;
							foreach ($roster as $r) {
								// Real
								if ($now-$members[$r['member']]['dob'] < $adult) continue; 
								?>
								<tr>
									<td><?php echo anchor('registrations/'.$reg['id'].'/roster/'.$r['id'], $members[$r['member']]['name']); ?></td>
									<td><?php echo floor((($now-$members[$r['member']]['dob']) / 31556926)); ?></td>
									<td><?php echo $members[$r['member']]['shirtsize']; ?></td>
									<td><?php if (!empty($members[$r['member']]['allergies']) || !empty($members[$r['member']]['diet']) || !empty($members[$r['member']]['medical']) || !empty($members[$r['member']]['notes'])) {
										?><span class="label label-tan camperhoverpopover" data-toggle="popover" title="Special Notes" data-placement="top" data-content="
										Special notes are details that you want to share with event/camp staff, we use this to better accomidate and serve you.<br><br>
										<?php echo (!isset($members[$r['member']]['allergies']) || empty($members[$r['member']]['allergies'])) ? '': '<strong>Allergies</strong>: '.$members[$r['member']]['allergies'].' <br>'; ?>
										<?php echo (!isset($members[$r['member']]['diet']) || empty($members[$r['member']]['diet'])) ? '': '<strong>Dietary Restrictions</strong>: '.$members[$r['member']]['diet'].' <br>'; ?>
										<?php echo (!isset($members[$r['member']]['medical']) || empty($members[$r['member']]['medical'])) ? '': '<strong>Medical Conditions</strong>: '.$members[$r['member']]['medical'].' <br>'; ?>
										<?php echo (!isset($members[$r['member']]['notes']) || empty($members[$r['member']]['notes'])) ? '': '<strong>Notes</strong>: '.$members[$r['member']]['notes'].' <br>'; ?>
										<br>You can add or edit these restrictions by editing this member's profile, click the edit button to head there.
										">Notes</span><?php } ?></td>
									<td style="text-align:right;"><?php if ($hasdiscounts) { echo anchor('registrations/'.$reg['id'].'/roster/'.$r['id'].'#discounts', '<i class="icon-ticket"></i> Discounts', 'class="btn btn-small blue"'); } ?> <?php echo anchor('registrations/'.$reg['id'].'/roster/'.$r['id'], '<i class="icon-group"></i> Classes', 'class="btn btn-small blue"'); ?> <?php echo anchor('api/v1/rosters/invoice/'.$r['id'].'.pdf', '<i class="icon-file-text"></i>', 'class="btn btn-small tan"  data-toggle="tooltip" title="Download an individual invoice and schedule PDF"'); ?> <?php echo anchor('unit/members/'.$members[$r['member']]['id'].'?return='.uri_string(), '<i class="icon-pencil"></i>', 'class="btn btn-small tan" data-toggle="tooltip" title="Edit Member"'); ?> <a data-toggle="popover" title="Delete" data-placement="top" data-content="Are you sure you want to remove this member from your roster? They will be unregistered for any classes.<br /><br />Don't worry, <?php echo $members[$r['member']]['name']; ?> won't be deleted from Camper.<br /><br /><?php echo str_replace('"', "'", anchor('api/v1/roster/delete?m='.$members[$r['member']]['id'].'&r='.$r['id'].'&return='.uri_string(), 'Delete '.$members[$r['member']]['name'], 'class="btn red"')); ?>" class="btn btn-small red camperpopover"><i class="icon-remove"></i></a></td>
								</tr>
							<?php $i++; } ?>
							<?php while ($i <= ($reg['male']+$reg['female'])) { ?>
								<tr>
									<td colspan="8">
										<div class="camperform float last" style="">
										<select id="famember<?php echo $i; ?>" name="adults[<?php echo $i; ?>][id]" data-toggle="tooltip" title="Choose a member, make sure you haven't chosen anyone more than once.">
											<option value="0">Choose an adult...</option>
											<option value="0">Empty Spot / Placeholder</option>
											<optgroup label="Adults in <?php echo $unittitle; ?>">
											<?php foreach ($adults as $a) { ?><option value="<?php echo $a['id']; ?>"><?php echo $a['name']; ?></option><?php } ?>
											</optgroup>
										</select> or &nbsp; <?php echo anchor('unit/members/new?return='.uri_string(), 'Add a member &rarr;', 'class="btn btn-small tan"'); ?> 
										</div>
									</td>
								</tr>
							<? $i++; } ?>
						</tbody>
					</table>
				</div>				
			</div>
			<div class="clear hr"></div>
			<p>If you made any changes above including adding members to placeholder spots, be sure to save your changes.</p>
			<input type="submit" value="Save Changes &rarr;" data-loading-text="Saving changes..." onclick="$(this).button('loading');" class="btn teal" /> <input type="reset" value="Reset" class="btn tan" />
			<div class="clear"></div>
		</div>
		<?php echo form_close();?> 
	</article>
	<article class="content">
	   	<!-- Change PW Modal -->
		<div id="modal_memberdiscounts" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<form id="md">
	   		<div class="container">
	   			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	       		<div class="pull">
	   	    		<h2 class="pull">Member Discounts</h2>
	   	    		<p>Manage discounts for each member on your roster. Choose a member from the list below to get started.</p>
	   	    		<p><button class="btn teal" data-loading-text="Saving changes..." onclick="$(this).button('loading'); return false;" id="mdsave">Save</button> <button class="btn tan" data-dismiss="modal" aria-hidden="true">Done</button></p>
	   	    		<div class="clear"></div>
	       		</div>
	       		<div class="tab-content inner-push">
	   				<div id="mdno">
		   				<h2 class="section">Edit Discounts</h2>
		   				<p>Choose a member from the list to view and manage discounts.</p>
	   				</div>
	   				<div id="mdyes" class="hidden">
	   					<div id="mdmessage" class="alert"></div>

	   					<h2 class="section" id="mdname">Loading...</h2>
	   					<input type="hidden" name="mdid" value="" />
		   				<p>These are all of the discounts for <?php echo $event['title']; ?> that apply to individual participants. Select discounts that apply to your unit, discounts may require council verification. When verified, the discounts will be applied and visible in the finances section.</p>
		   				<div class="clear"></div>
		   				<?php if ($hasdiscounts) : foreach ($discounts as $o) : if ($o['individual']) : ?>
			   				<?php if ($o['checkbox'] == 1) { ?><div class="camperform float cbl" style="width:60%;"><input type="checkbox" class="cbl mdcb" <?php if(isset($reg['discounts'][$o['id']]['checkbox'])) { ?> checked="checked"<?php } ?> name="discounts[<?php echo $o['id']; ?>][checkbox]" id="foc<?php echo $o['id']; ?>" /><label for="foc<?php echo $o['id']; ?>" class="cbl" ><?php echo $o['title']; ?></label><small><?php echo $o['description']; ?></small></div><?php } ?>
			   				<?php if ($o['value'] == 1) { ?><div class="camperform float " style="width: 30%"><input type="text" class="mdvalue" name="discounts[<?php echo $o['id']; ?>][value]" id="fov<?php echo $o['id']; ?>" value="<?php if(isset($reg['discounts'][$o['id']]['value'])) { echo $reg['discounts'][$o['id']]['value']; } ?>" placeholder="none" /><label for="fov<?php echo $o['id']; ?>"><?php echo $o['title']; ?></label></div><?php } ?>
			   				<?php endif; ?>
			   				<div class="clear"></div>
		   				<?php endforeach; endif; ?><!-- End Custom Options -->
		   				<div class="clear tall"></div>
		   				<h2 class="section" id="mdname">Unit Discounts and Options</h2>
	   					<p>Only options and discounts applying to individual participants are shown here. Head back to extras page to manage your unit options and discounts.</p>
	   					<p><?php echo anchor('registrations/'.$reg['id'].'/details', 'View Unit Discounts &rarr;', 'class="btn btn-small tan"'); ?></p>
	   				</div>
		   			<div class="clear"></div>
	   			</div>
	   		</div>
	   		<div class="clear"></div>
	   		</form>
		</div>
	   	<!-- End Modal -->
	</article>
