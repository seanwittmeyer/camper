<?php 

/* 
 * Camper Leader / Registerations / Single Reg Details View
 *
 * This is the event registration details page, basically the page that handles
 * almost all of the event registration record management from basics to the options.
 * It also gives an overview for payments and offers a place to start them. The
 * scope of this page is a single event registration, single session, single event for
 * an unit.
 *
 * File: /application/views/register/details.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 /* Available vars
 $event		array
 $reg 		array
 $session 	array
 $unit 		array
 $message 	string
 */

 if ($individual) {
 	// This user is an individual, we'll prep here
 	$unittitle = 'You';
 	$helpingverb =' are';
 } else {
 	// Normal user with an unit
 	$unittitle = (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')': $unit['unittype'].' '.$unit['number'];
 	$helpingverb =' is';
 }

	// Lock out if past event start date
	$startdate = (empty($session['datestart'])) ? $event['datestart']: $session['datestart'];
	$startdate = ($event['activitytime'] > 0) ? $startdate - $event['activitytime']: $startdate;
	$lock = (time() > $startdate) ? true : false;

	//Prep
	// Dates prep
	if (empty($session['datestart'])) {
		if (empty($event['dateend'])) {
			$dates = date('F j, Y', $event['datestart']);
		} else {
			$dates = date('F j', $event['datestart']).date(' - F j, Y', $event['dateend']);
		} 
	} else {
		if (empty($session['dateend'])) {
			$dates = date('F j, Y', $session['datestart']);
		} else {
			$dates = date('F j', $session['datestart']).date(' - F j, Y', $session['dateend']);
		} 
	} ?>
	<div class="subnav">
		<div class="container">
			<h2>Registrations</h2>
			<nav class="campersubnav">
				<li class=""><?php echo anchor("registrations/past", 'Past Events');?></li>
				<li class="active"><?php echo anchor("registrations", 'Upcoming Events');?></li>
			</nav>
		</div>
	</div>
	<script>
	$(document).ready(function() {
		$( ".warnchange" ).change(function() {
			$("#changewarning").removeClass('hidden');
		});
	});
	</script>
	<article class="textsection">
	<?php if (!$lock) : ?>
	<?php echo form_open(uri_string());?>
	<?php //echo form_hidden($csrf); ?>
	<?php if ($individual) { ?><input type="hidden" name="user" value="<?php echo $user->id; ?>" /><?php } else { ?><input type="hidden" name="unit" value="<?php echo $unit['id']; ?>" /><?php } ?>
	<input type="hidden" name="event" value="<?php echo $event['id']; ?>" />
	<input type="hidden" name="reg" value="<?php echo $reg['id']; ?>" />
	<input type="hidden" name="session" value="<?php echo $session['id']; ?>" />
	<input type="hidden" name="user" value="<?php echo $user->id; ?>" />
	<?php endif; ?>
		<div class="container">
			<h2>Registrations / <?php echo $event['title']; ?></h2>
   			<p>You can manage all of the details regarding <strong><?php echo $unittitle; ?>'s</strong> registration for <strong><?php echo $event['title']; ?></strong>. You will find the event dates and details, any extras you can choose to add or let us know about, and manage the finances of the event (see outstanding program fees and make payments).  </p>
			<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
		</div>
   		<div class="container">
   			<ul id="detailstabs" class="blue">
   				<li class="active"><a href="#basics" data-toggle="tab">Basics</a></li>
   				<li class=""><a href="#extras" data-toggle="tab">Extras</a></li>
   				<li class=""><a href="#finances" data-toggle="tab">Finances</a></li>
   				<li class=""><?php echo anchor('registrations/'.$reg['id'].'/roster', 'Roster'); ?></li>
   			</ul>
   			<div class="tab-content">
   				<div class="tab-pane fade in active" id="basics">
				   	<div class="quarter">
			   			<h2>The Basics</h2>
			   			<p>These are the starter options and details regarding your registration for <strong><?php echo $event['title']; ?></strong></p>
			   			<?php if ($lock) : ?><p><i class="icon-warning-sign red"></i> You can no longer make changes or payments for your registration because this event is about to start or has already begun. Please contact the council service center.</p><?php else : ?>
			   			<input type="submit" name="submit" value="Save Changes" data-loading-text="Saving changes..." onclick="$(this).button('loading');" class="btn teal"  /> <!--<p><a href="#cancelevent" class="btn red" role="button" data-toggle="modal" > Unregister</a></p>-->
			   			<?php endif; ?>
			   			<div class="clear"></div>
				   	</div>
				   	<div class="threequarter">
			   			<?php if ($verify['restricted']===true) { ?><h2 class=""><i class="icon-remove red"></i> <?php echo $unittitle.$helpingverb; ?> not registered</h2>
			   			<p><?php foreach ($verify['error'] as $e) { ?><i class="icon-minus tan"></i> <?php print_r($e); ?><br /><?php } ?></p>
				   		<?php } elseif ($verify['result']===false) { ?><h2 class=""><i class="icon-exclamation-sign red"></i> <?php echo $unittitle.$helpingverb; ?> registered, but there are issues:</h2>
			   			<p><?php foreach ($verify['error'] as $e) { ?><i class="icon-minus tan"></i> <?php print_r($e); ?><br /><?php } ?></p>
			   			<?php } else { ?><h2><i class="icon-ok teal"></i> <?php echo $unittitle.$helpingverb; ?> registered and all is well</h2><?php } ?>
			   			<div class="clear"></div>
			   			<h2 class="section">Details</h2>
			   			<p><strong><?php echo $this->shared->get_user_name($reg['registerdate']['user'],true); ?></strong> registered <strong><?php echo $unittitle; ?></strong> for <strong><?php echo (empty($session['title'])) ? $event['sessiontitle'].' '.$session['sessionnum'] : $session['title']; ?></strong> of <strong><?php echo $event['title']; ?></strong> on <strong><?php echo date('F j, Y', $reg['registerdate']['time']); ?></strong>. The <?php echo strtolower($event['sessiontitle']); ?> details including dates, location and event type are below. You can set the number of people attending the event here.</p>
			   			<div class="camperform float last" style="" data-toggle="tooltip" title="The <?php echo $event['sessiontitle']; ?> you are registered for. Smaller events that are only 1 day still have a <?php echo $event['sessiontitle']; ?>."><span><?php echo (empty($session['title'])) ? $event['sessiontitle'].' '.$session['sessionnum'] : $session['title']; ?></span><label><?php echo $event['sessiontitle']; ?></label></div>
			   			<div class="camperform float last" style=""><span><?php echo $dates; ?></span><label>Dates</label></div>
			   			<div class="camperform float last" style=""><span><?php echo $event['location']; ?></span><label>Location</label></div>
			   			<div class="clear "></div>
			   			<?php if (isset($regfull['eventid']['notes']) && !empty($regfull['eventid']['notes'])) { ?><div class="well" style=""><b>Event Updates:</b><br /><?php echo $regfull['eventid']['notes']; ?></div><div class="clear "></div><?php } ?>


			   			<?php if($groups['enabled'] == '1'): $currentgroup = (isset($reg['group'])) ? $reg['group'] : false; ?>
				   			<h2 class="section"><?php echo $groups['title']; ?></h2>
				   			<p><?php echo $groups['desc']; ?></p>
						   	<p><?php $i=0; foreach ($groups['groups'] as $group): 
							   	// Let's do some math
							   	// Open Spots
							   	if (is_null($group['limit']) || $group['limit'] == '') {
							   		// Group limit is not set
							   		$group['__openspots'] = $session['limithard']-$session['count'];
							   	} else {
							   		// Group limit is set
							   		$group['__openspots'] = $group['limit']-$this->shared->count_group($group['id'],$session['id'],true);
							   	}
							   	if (isset($group['cost']) && $group['cost'] > 0) {
								   	$group['__cost'] = ($group['perperson'] == 1) ? '<br /><strong>Cost (per person)</strong>: $'.$this->shared->number_format_drop_zero($group['cost']): '<br /><strong>Cost (one time)</strong>: $'.$this->shared->number_format_drop_zero($group['cost']);
							   	} else {
								   	$group['__cost'] = '';
							   	}
							   	if ($group['__openspots'] > 0) {
								   	// Group has space
								   	$group['__message'] = '<strong>'.$group['title'].' has '.$group['__openspots'].' open spots</strong>';
								   	$group['__full'] = false;
							   	} else {
								   	// Group is full
								   	$group['__message'] = '<strong>'.$group['title'].' is full.</strong> If you are registered in this '.$groups['title'].' and change to another, you may lose your spots.';  
								   	$group['__full'] = ($this->ion_auth->is_admin()) ? false : true;
							   	}
						   	?>
							   			<div class="camperform float cbl camperhoverpopover" data-toggle="popover" title="<?php echo $group['title']; ?>" data-placement="top" data-content="<?php echo $group['desc']; ?> <br /><br /><?php echo $group['__message'].$group['__cost']; ?>" style="width: auto;"><input type="radio" class="cbl"<?php if ($group['__full'] && $session['limithard'] > 0) { ?> disabled="disabled"<?php } ?> value="<?php echo $group['id']; ?>"<?php if ($currentgroup == $group['id']){ ?> checked="checked"<?php } ?> name="fgroup" id="fgroup<?php echo $i; ?>" /><label for="fgroup<?php echo $i; ?>" class="cbl" ><?php echo $group['title']; ?></label></div>
						   	<?php $i++; endforeach; ?>
				   			<div class="clear "></div>
			   			<?php endif; ?>
			   			
			   			<h2 class="section">Your Participants</h2>
			   			<p>You can specify the number of participants here. Participants are recorded as <strong>youth</strong> 
			   			<i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Youth" data-placement="top" data-content="Youth are the youth participants of an event. This should not include any adults or parents nor any family or visitors who will not be participating in the event."></i> or <strong>adults</strong> 
			   			<i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Adults" data-placement="top" data-content="Adults are leaders participating in an event. For some events, we separate male and female participants (eg non-related male and female adults can not share tents at summer camp)."></i><?php if ($session['family'] == 1) { ?>, or <strong><?php echo strtolower(); ?></strong>
			   			<i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Family and Visitors" data-placement="top" data-content="Some events allow family members or visitors to come. These are people who are not participating in the normal program, but should be included for meal or campsite counts."></i><?php } ?>.</p>
			   			<p><!--There are <strong><?php echo $session['limithard']-$session['count'] ?> open spots</strong> <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Open Spots" data-placement="top" data-content="Many events have registration limits to ensure the events run smoothly. While you have the ability to add or remove participants from this event, you can not add more than the number of open spots. If you have questions, please contact the council service center."></i> for this session and y-->You have a total of <strong><?php echo $reg['youth']+$reg['male']+$reg['female'] ?> people registered</strong> <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="People Registered" data-placement="top" data-content="The number of people registered is the total of the youth and adults participating."></i>.</p>
			   			<div class="camperform float" style="width: 90px"><input id="fyouth" class="warnchange" name="youth" type="text" placeholder="none" value="<?php echo $reg['youth']; ?>"  /><label for="fyouth">Youth</label></div>
			   			<div class="camperform float" style="width: 90px"><input id="fmale" class="warnchange" type="text" name="male" placeholder="none" value="<?php echo $reg['male']; ?>"  /><label for="fmale">Male Adults</label></div>
			   			<div class="camperform float" style="width: 90px"><input id="ffemale" class="warnchange" type="text" name="female" placeholder="none" value="<?php echo $reg['female']; ?>"  /><label for="ffemale">Female Adults</label></div>
			   			<div class="clear"></div>
			   			<?php if (!$lock) : ?>
			   			<p><input type="submit" name="submit" value="Save Changes" data-loading-text="Saving changes..." onclick="$(this).button('loading');" class="btn teal"  /></p>
			   			<?php endif; ?>

			   			<!--<div class="camperform float" style="width: 90px" data-toggle="tooltip" title="Family / Visitors are people who are coming but will not be participating in activities. Contact the council service center for details on family/visitors for this event."><input id="ffamily" class="warnchange" name="family" type="text" placeholder="none" value="<?php echo $reg['family']; ?>"  /><label for="ffamily">Friday Dinner</label></div>--><input type="hidden" value="0" name="family" />
			   			<div class="clear"></div> 
			   			<?php if ($event['activityregs'] == '1' && $reg['roster'] == '0') { ?>
			   			<?php if ($individual) { ?>
			   			<p>Soon, you will be able to create a roster including the allergies and dietary restrictions for your participants. We are working and will let you know when it is available.</p>
			   			<!--<p>Create your roster for <strong><?php echo $event['title']; ?></strong> so you can register for classes and let us know about details like allergies and dietary restrictions. </p>
			   			<p><?php echo anchor('registrations/'.$reg['id'].'/roster/create','Create Roster &rarr;','class="btn tan"'); ?></p>-->
			   			<?php } else { ?> 
			   			<p>You can create <strong><?php echo $unittitle; ?>'s</strong> roster for <strong><?php echo $event['title']; ?></strong> so you can register for classes and other benefits.</p>
			   			<p><?php if (!$lock) echo anchor('registrations/'.$reg['id'].'/roster/create','Create Roster &rarr;','class="btn tan"'); ?></p>
			   			<?php } ?> 
			   			<?php } elseif ($event['activityregs'] == '1' && $reg['roster'] == '1') { ?>
			   			<?php if ($individual) { ?>
			   			<p>Your roster has been set up, you can view participants and manage class registrations (if available).</p>
			   			<?php } else { ?> 
			   			<p><strong><?php echo $unittitle; ?>'s</strong> roster for <strong><?php echo $event['title']; ?></strong> has been set up, you can view participants and manage class registrations (if enabled and open).</p>
			   			<?php } ?>
			   			<p><?php echo anchor('registrations/'.$reg['id'].'/roster','Manage Roster &rarr;','class="btn tan"'); ?> <?php echo anchor('api/v1/unitroster/'.$reg['id'].'.pdf', '<i class="icon-file-text inline"></i> Print/View Your Roster', 'class="btn tan camperhoverpopover" data-toggle="popover" title="Roster (pdf)" data-content="You will need to print and complete three copies of this document. Two copies to turn in during check-in at camp, and a copy for your records."'); ?></p>
			   			<?php } ?>
			   		</div>
			   		<div class="clear"></div>
   				</div>
   				<div class="tab-pane fade" id="extras">
			   		<div class="quarter">
		   				<h2>Extras</h2>
		   				<p>Extras are all of the options, discounts, and other items of interest with regards to the event.</p>
			   			<?php if ($lock) : ?>
			   				<p><i class="icon-warning-sign red"></i> You can no longer make changes or payments for your registration because this event is about to start or has already begun. Please contact the council service center.</p>
			   			<?php else : ?>
			   				<p><input type="submit" name="submit" value="Save Changes" data-loading-text="Saving changes..." onclick="$(this).button('loading');" class="btn teal"  /></p>
			   			<?php endif; ?>
		   				<div class="clear"></div>
			   		</div>
			   		<div class="threequarter">
		   				<a class="clear" id="options"></a>
					   	<?php $i=0; if (count($options) > 0 || $event['activitypreorders'] == '1') : $i++; ?>
		   				<h2 class="section">Options</h2>
		   				<p>These are the options that you have for <?php echo $event['title']; ?>. Some options have a cost and others are just items that allow us to better prepare for the upcoming event. If an option has a cost or fee, it will be stated here and will be clearly displayed in the event fees section.</p>
		   				<div class="clear"></div>
		   				<?php if ($event['activitypreorders'] == '1') { ?><div class="camperform float cbl" style="width:auto;"><input type="checkbox" class="cbl warnchange" <?php if($reg['activitypreorders'] == 1) { ?> checked="checked"<?php } ?> name="activitypreorders" id="fpreorders" /><label for="fpreorders" class="cbl" >Preorder Activity Supplies</label><small>Preordering activity supplies allows you to pay for activity costs up front.</small></div><!--<div class="camperform float " style="width: 30%"><?php if ($reg['activitypreorders'] == 1) echo anchor('api/v1/preorders/reg/'.$reg['id'].'.pdf', '<i class="icon-file-text inline"></i> Print/View Preorders', 'class="btn tan" style="margin-top: 34px;"'); ?></div>--><?php } ?>
		   				<?php if ($event['bluecards'] == 1) { ?><div class="camperform float cbl" style="width:60%;"><input type="checkbox" class="cbl warnchange" <?php if($reg['bluecards'] == 1) { ?> checked="checked"<?php } ?> name="bluecards" id="fbluecards" /><label for="fbluecards" class="cbl" >Request Blue Cards</label><small>Request blue cards for merit badges to be provided at camp. By enabling this, you certify that the scouts enrolled in classes on this site are eligible to participate in those programs.</small></div>
		   				<?php if ($reg['bluecards'] == 1) { ?><div class="camperform float " style="width: 30%"><?php echo anchor('api/v1/bluecard/reg/'.$reg['id'].'.pdf', '<i class="icon-file-text inline"></i> Print/View Blue Cards', 'class="btn tan" style="margin-top: 34px;"'); ?></div><?php }} ?>
		   				<!-- Start Custom Options -->
		   				<?php foreach ($options as $o) : ?>
			   				<?php if ($o['checkbox'] == 1) { ?><div class="camperform float cbl" style="width:60%;"><input type="checkbox" class="cbl warnchange" <?php if(isset($reg['options'][$o['id']]['checkbox'])) { ?> checked="checked"<?php } ?> name="options[<?php echo $o['id']; ?>][checkbox]" id="foc<?php echo $o['id']; ?>" /><label for="foc<?php echo $o['id']; ?>" class="cbl" ><?php echo $o['title']; ?></label><small><?php echo $o['description']; ?></small></div><?php } ?>
			   				<?php if ($o['value'] == 1) { ?><div class="camperform float " style="width: 30%"><input type="text" name="options[<?php echo $o['id']; ?>][value]" id="fov<?php echo $o['id']; ?>" value="<?php if(isset($reg['options'][$o['id']]['value'])) { echo $reg['options'][$o['id']]['value']; } ?>" placeholder="none" /><label for="fov<?php echo $o['id']; ?>"><?php echo $o['title']; ?></label></div><?php } ?>
			   				<div class="clear"></div>
		   				<?php endforeach; ?>
					   	<?php endif; ?>
					   	<?php if (count($discounts) > 0) : $i++; ?>
			   			<div class="clear"></div> 
		   				<a class="clear" id="discounts"></a>
		   				<h2 class="section">Discounts</h2>
		   				<p>These are all of the discounts for <?php echo $event['title']; ?>. Select discounts that apply to your unit, discounts may require council verification. When verified, the discounts will be applied and visible in the finances section.</p>
		   				<div class="clear"></div>
		   				<?php foreach ($discounts as $o) : if ($o['individual']) : ?>
		   					<h4 class="optionheading"><?php echo $o['title']; ?></h4><p class="half">This discount is an individual discount, you can apply this to participants that qualify in your roster. <?php echo anchor('registrations/'.$reg['id'].'/roster', 'My Roster &rarr;', 'class="btn btn-small tan"'); ?></p>
		   					<?php else : ?>
			   				<?php if ($o['checkbox'] == 1) { ?><div class="camperform float cbl" style="width:60%;"><input type="checkbox" class="cbl warnchange" <?php if(isset($reg['discounts'][$o['id']]['checkbox'])) { ?> checked="checked"<?php } ?> name="discounts[<?php echo $o['id']; ?>][checkbox]" id="foc<?php echo $o['id']; ?>" /><label for="foc<?php echo $o['id']; ?>" class="cbl" ><?php echo $o['title']; ?></label><small><?php echo $o['description']; ?></small></div><?php } ?>
			   				<?php if ($o['value'] == 1) { ?><div class="camperform float " style="width: 30%"><input type="text" name="discounts[<?php echo $o['id']; ?>][value]" id="fov<?php echo $o['id']; ?>" value="<?php if(isset($reg['discounts'][$o['id']]['value'])) { echo $reg['discounts'][$o['id']]['value']; } ?>" placeholder="none" /><label for="fov<?php echo $o['id']; ?>"><?php echo $o['title']; ?></label></div><?php } ?>
			   				<?php endif; ?>
			   				<div class="clear"></div>
		   				<?php endforeach; ?><!-- End Custom Options -->
		   				<div class="clear"></div>
					   	<?php endif; ?>
					   	<?php if ($i==0) { ?><strong>There are no discounts or options for this event.</strong><?php } ?>
		   			</div>
			   		<div class="clear"></div>
   				</div>
   				<div class="tab-pane fade" id="finances">
			   		<div class="quarter">
		   				<h2 class="">Finances</h2>
		   				<p>We work diligently to make the registration process easier and part of that is handling finances online. You can pay via PayPal for <strong><?php echo $event['title']; ?></strong> or send traditional payment to the council. This record here is up to date, if you have any questions, please don't hesitate to ask.</p>
		   				<!--<input type="submit" name="submit" value="Save Changes" class="btn blue"  />-->   			
		   				<div class="clear"></div>
			   		</div>
			   		<div class="threequarter">
		   				<h2 class="section">Financial Details</h2>
		   				<?php if (!$lock) { ?><div id="changewarning" class="alert alert-success hidden"><button type="button" class="close" data-dismiss="alert">&times;</button><p>You've made changes to your registration, save your changes to see updated financial details.</p><p><input type="submit" name="submit" value="Save Changes" class="btn btn-small teal"  /></p></div><?php } ?>
		   				<div class="registerfinancial">
			   				<div class="camperform float last" style="width: 150px" data-toggle="tooltip" title="The total, all inclusive, cost for camp"><span>$<?php echo $this->shared->number_format_drop_zero($fin['total']); ?></span><label>Total Fee</label></div>
			   				<div class="camperform float last" style="width: 150px"  data-toggle="tooltip" title="The total amount of confirmed payments, does not include pending payments"><span>$<?php echo $this->shared->number_format_drop_zero($fin['totalpaid']); ?></span><label>Total Paid</label></div>
			   				<div class="clear"></div>
			   				<?php if (($fin['total'] - $fin['totalpaid']) > 0) { ?>
			   				<div class="camperform float last" style="width: 150px"  data-toggle="tooltip" title="The amount remaining (total - paid). This amount includes all discounts and options (including preorders, if set), and may not be due right now."><span class="label">$<?php echo $this->shared->number_format_drop_zero($fin['total'] - $fin['totalpaid']); ?></span><label>Amount Remaining</label></div>
			   				<?php } elseif (($fin['total'] - $fin['totalpaid']) < 0) { ?>
			   				<div class="camperform float last" style="width: 150px"  data-toggle="tooltip" title="You have paid more than what is due, contact us for details on getting a refund."><span class="label label-info">$<?php echo $this->shared->number_format_drop_zero($fin['totalpaid'] - $fin['total']); ?></span><label>Amount Overpaid</label></div>
			   				<?php } elseif ($fin['totalparticipants'] == 0) { ?>
			   				<div class="camperform float last" style="width: 150px"  data-toggle="tooltip" title="Add participants in the Basics tab to see your amount due."><span ><a class="btn tan" href="#basics" data-toggle="tab">Add participants &rarr;</a></span><label>No participants registered</label></div>
			   				<?php } else { ?>
			   				<div class="camperform float last" style="width: 150px"  data-toggle="tooltip" title="It looks like you are all paid up and good to go."><span><i class="icon-ok teal"></i> Paid in full!</span><label>Amount Remaining</label></div>
			   				<?php } ?>
			   				<div class="clear"></div>
		  	   				<div class="camperform float last" style="width: 150px" data-toggle="tooltip" title="Total of all discounts"><span>$<?php echo $this->shared->number_format_drop_zero($fin['totaldiscounts']); ?></span><label>Total Discounts</label></div>
			   				<div class="camperform float last" style="width: 150px"  data-toggle="tooltip" title="Total of all options and extras, edit these under the extras tab"><span>$<?php echo $this->shared->number_format_drop_zero($fin['requests']); ?></span><label>Total Options &amp; Fees</label></div>
			   				<div class="clear"></div>
			   				<?php if ($event['activitypreorders'] == 1 && $reg['activitypreorders'] == 1 && isset($fin['preorders'])) { ?>
		  	   				<div class="camperform float last" style="width: 150px" data-toggle="tooltip" title="Total of all activity or merit badge supplies, you have this because you enabled supplies preorders"><span>$<?php echo $this->shared->number_format_drop_zero($fin['preorders']); ?></span><label>Activity Supplies Preorder</label></div>
			   				<div class="clear"></div>
			   				<?php } ?>
			   				<!--<div class="camperform float last red" style="width: 300px"  data-toggle="tooltip" title="The amount due now"><span>$<?php echo $this->shared->number_format_drop_zero($fin['nextdue']); ?></span><label>Amount Due by December 2</label></div>
			   				<!--<div class="camperform float last red" style="width: 300px"  data-toggle="tooltip" title="The amount due for the next payment"><span>$<?php echo $this->shared->number_format_drop_zero($fin['nextdue']); ?></span><label>Amount Due by December 2</label></div>
			   				<div class="clear"></div>-->
		 				</div>
		   				<div class="registerfeetable">
					  		<table class="table table-condensed">
					  			<tbody>
						  			<tr><td colspan="3"><strong>Fees</strong>  <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Fees" data-placement="top" data-content="Fees are the base cost for an event. Fees are automatically calculated from the youth and adult (if any) costs. You can manage your registration numbers in the 'Basics' tab for basic registrations or by managing your participating roster in the 'Roster' tab. Contact us if you have any questions."></i></td></tr>
						  			<?php if (($session['cost'] + $session['costadult'] + (($cost['groups']['perperson'] === true) ? $cost['groups']['cost']: 0)) == 0) { ?>
						  			<tr><td colspan="3">There are no registration fees for this event.</td></tr>
						  			<?php } else { ?>
						  			<tr><td>Youth</td><td><?php echo $this->shared->number_format_drop_zero($reg['youth']); ?> x $<?php echo $this->shared->number_format_drop_zero($cost['youth']); ?></td><td>= $<?php echo $this->shared->number_format_drop_zero($fin['totalyouth']); ?></td></tr>
						  			<tr><td>Adults</td><td><?php echo $this->shared->number_format_drop_zero($counts['adults']); ?> x $<?php echo $this->shared->number_format_drop_zero($cost['adult']); ?></td><td>= $<?php echo $this->shared->number_format_drop_zero($fin['totaladults']); ?></td></tr>
						  			<tr><td colspan="2">Total Fee</td><td>= $<?php echo $this->shared->number_format_drop_zero($fin['totalparticipants']); ?></td></tr>
						  			<?php } ?>
						  			<?php if ($cost['groups']['perperson'] === false && $cost['groups']['cost'] > 0) { ?> 
						  			<tr><td><?php echo $groups['groups'][$currentgroup]['title']; ?> <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="<?php echo $groups['groups'][$currentgroup]['title']; ?>" data-placement="top" data-content="<?php echo $groups['groups'][$currentgroup]['desc']; ?>"></i></td><td></td><td>= <?php echo $this->shared->number_format_drop_zero($cost['groups']['cost']); ?> </td></tr> 
						  			<?php } ?>
						  			
						  			<?php if ($event['activitypreorders'] == 1 && $reg['activitypreorders'] == 1) { ?>
						  			<tr><td colspan="3"><strong>Preordered Costs</strong> <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Preordered Costs" data-placement="top" data-content="Preordered costs include activity / class / merit badge fees that are separate from the event fees. These include kits or other items that you can choose to preorder.<br><br>You can manage preorders in the 'Extras' tab, and you can exclude individuals from preorders in the 'Roster' tab soon. We are working on the individual scout invoices."></i></td></tr>
						  			<tr><td>Activity Supplies</td><td></td><td>= $<?php echo $this->shared->number_format_drop_zero($fin['preorders']); ?></td></tr>
						  			<?php } ?>
						  			
						  			<!-- Start Options -->
					   				<?php if (count($options > 0)) : $i=1; foreach ($options as $o) : ?>
					   					<?php $flag = true; if ($o['checkbox'] == 1 && isset($o['id']) && !isset($reg['options'][$o['id']]['checkbox'])) $flag = false; if (isset($o['amount']) && $o['amount'] > 0 && $flag === true ) : ?>
					   					<?php if($i==1) { ?><tr><td colspan="3"><strong>Requests</strong> <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Requests/Options" data-placement="top" data-content="You can edit most requests and options in the 'Extras' tab, if an option requires verification, it won't show here until approved. Some options/requests are not editable, contact us for details."></i></td></tr><?php } $i++; ?>
					   					<tr>
					   						<td><?php echo $o['title']; ?></td>
					   						<td><?php // Setup
					   							$o['__total'] = (isset($o['amount'])) ? $o['amount']: 0; 
					   							$o['__value'] = 0; 
					   							$o['__percent'] = ($o['percent'] == 1) ? true: false;
					   							$o['__pre'] = ($o['__percent']) ? false: '$';
					   							$o['__post'] = ($o['__percent']) ? '%': false;
					   							
						   						if (isset($o['amount']) && $o['value'] == 1 && isset($reg['options'][$o['id']]['value']) ) { 
						   							// This option has an amount and the user entered a value
						   							if ($o['perperson'] == 1) {
						   								// This is per person, we'll display the details find the total
						   								echo $counts['total'].' x '.$o['__pre'].$o['amount'].$o['__post'];
						   								$o['__total'] = ($o['__percent']) ? $counts['total'] * (.01 * $o['amount']) : $counts['total'] * $o['amount'];
						   							} else {
						   								// This is per the value, we'll display details and find the total
						   								echo $reg['options'][$o['id']]['value'].' x '.$o['__pre'].$o['amount'].$o['__post'];
						   								$o['__total'] = ($o['__percent']) ? $reg['options'][$o['id']]['value'] * (.01 * $o['amount']) : $reg['options'][$o['id']]['value'] * $o['amount']; 
						   							}
						   						} elseif (isset($o['amount'])) {
						   							if ($o['perperson'] == 1) {
						   								echo $counts['total'].' x '.$o['__pre'].$o['amount'].$o['__post'];
						   								$o['__total'] = ($o['__percent']) ? $counts['total'] * (.01 * $o['amount']) : $counts['total'] * $o['amount'];
						   							} else {
						   								echo $o['__pre'].$o['amount'].$o['__post'];
						   								$o['__total'] = ($o['__percent']) ? .01 * $o['amount'] : $o['amount'];
						   							}
							   					} ?></td>
					   						<td>= $<?php echo $o['__total']; ?></td>
					   					</tr>
					   				<?php endif; endforeach; endif; ?>
						  			<!-- End Options -->

						  			<!-- Start Custom Discounts -->
					   				<?php $i=1; if (count($discounts > 0)) : foreach ($discounts as $o) : ?>
					   					<?php $flag = true; if ($o['checkbox'] == 1 && !isset($reg['discounts'][$o['id']]['checkbox'])) $flag = false; if (isset($o['amount']) && $o['amount'] > 0 && $flag === true ) : ?>
					   					<?php if($i==1) { ?><tr><td colspan="3"><strong>Discounts</strong> <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Discounts" data-placement="top" data-content="There are 2 types of discounts. Some, like early bird registration, are automatically added. Others, such as Friends of Scouting discounts, need to be added. You can edit discounts in the 'Extras' tab."></i></td></tr><?php } $i++; ?>
					   					<tr>
					   						<td><?php echo $o['title']; ?></td>
					   						<td><?php // Setup
					   							$o['__total'] = (isset($o['amount'])) ? $o['amount']: 0; 
					   							$o['__value'] = 0; 
					   							$o['__percent'] = ($o['percent'] == 1) ? true: false;
					   							$o['__pre'] = ($o['__percent']) ? false: '$';
					   							$o['__post'] = ($o['__percent']) ? '%': false;
					   							$o['__count'] = false;
					   							
						   						if (isset($o['amount']) && $o['value'] == 1 && isset($reg['discounts'][$o['id']]['value']) ) { 
						   							// This option has an amount and the user entered a value
						   							if ($o['perperson'] == 1) {
						   								// This is per person, we'll display the details find the total
						   								if ($event['freeadults']['enabled'] == 1 && $counts['youth'] >= $event['freeadults']['threshold']) {
							   								// Adjust count so to not to give free adults additional discounts
							   								$o['__count'] = $counts['youth']+($counts['adults']-$event['freeadults']['amount']);
						   								} else {
							   								// Pass through the count
							   								$o['__count'] = $counts['total'];
							   								
						   								}
						   								echo $o['__count'].' x '.$o['__pre'].$o['amount'].$o['__post'];
						   								$o['__total'] = ($o['__percent']) ? $o['__count'] * (.01 * $o['amount']) : $o['__count'] * $o['amount'];
						   							} else {
						   								// This is per the value, we'll display details and find the total
						   								echo $reg['discounts'][$o['id']]['value'].' x '.$o['__pre'].$o['amount'].$o['__post'];
						   								$o['__total'] = ($o['__percent']) ? $reg['discounts'][$o['id']]['value'] * (.01 * $o['amount']) : $reg['discounts'][$o['id']]['value'] * $o['amount']; 
						   							}
						   						} elseif (isset($o['amount'])) {
						   							if ($o['perperson'] == 1) {
						   								echo $counts['total'].' x '.$o['__pre'].$o['amount'].$o['__post'];
						   								$o['__total'] = ($o['__percent']) ? $counts['total'] * (.01 * $o['amount']) : $counts['total'] * $o['amount'];
						   							} else {
						   								echo $o['__pre'].$o['amount'].$o['__post'];
						   								$o['__total'] = ($o['__percent']) ? .01 * $o['amount'] : $o['amount'];
						   							}
							   					} ?></td>
					   						<td>= <i class="red">$<?php echo $o['__total']; ?></i></td>
					   					</tr>
					   				<?php endif; endforeach; endif; ?>
						  			<!-- End Options -->

						  			<!-- Start Integrated Discounts -->
					   				<?php // Early Registration
					   				if ($event['earlyreg']['enabled'] == 1 && $reg['registerdate']['time'] < $event['earlyreg']['date']) :
					   					if($i==1) { ?><tr><td colspan="3"><strong>Discounts</strong> <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Discounts" data-placement="top" data-content="There are 2 types of discounts. Some, like early bird registration, are automatically added. Others, such as Friends of Scouting discounts, need to be added. You can edit discounts in the 'Extras' tab."></i></td></tr><?php } $i++; ?><tr>
						   				<?php $freeadultsmessage = ($event['freeadults']['enabled'] == 1 && $counts['youth'] >= $event['freeadults']['threshold'] && $event['freeadults']['dollar'] !== 1) ? ' This discount does not apply to your '.$event['freeadults']['amount'].' free adults.':''; ?>
						   				<td>Early Registration <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Early Registration" data-placement="top" data-content="The early registration discount is automatically applied if you registered by <?php echo date('F j, Y',$event['earlyreg']['date']); ?>.<?php echo $freeadultsmessage; ?>"></i></td>
						   				<td><?php 
							   				if ($event['earlyreg']['percent'] == 1) {
								   				// The discount is a percentage
								   				echo ($event['earlyreg']['per'] == 1) ? $counts['total'].' x '.$event['earlyreg']['amount'].'%': $event['earlyreg']['amount'].'%';
								   				$event['earlyreg']['__total'] = ($event['earlyreg']['per'] == 1) ? $counts['total'] * ($event['earlyreg']['amount'] * .01): $event['earlyreg']['amount'] * .01;
							   				} else {
								   				// The discount is a normal dollar amount
								   				if ($event['freeadults']['enabled'] == 1 && $counts['youth'] >= $event['freeadults']['threshold']) {
									   				// Handle the free adults discounts so we aren't giving discounts on non-qualifying leaders.
									   				if ($event['freeadults']['dollar'] == 1) {
										   				// This is a dollar discount, subtract the amount x qualifying leaders, subtract that from the early discount
										   				//echo $counts['adults'].' x $'.$event['freeadults']['amount'];
										   				// NOTE: We aren't doing anything special here, the discount will apply to all leaders. This is the same ans the else code below.
										   				echo ($event['earlyreg']['per'] == 1) ? $counts['total'].' x $'.$event['earlyreg']['amount']: '$'.$event['earlyreg']['amount'];
										   				$event['earlyreg']['__total'] = ($event['earlyreg']['per'] == 1) ? $counts['total'] * $event['earlyreg']['amount']: $event['earlyreg']['amount'];
									   				} else {
									   					// Subtract the free adult threshold from the amount qualifying for the discount. This way we aren't giving discounts on top of the free adults.
										   				echo ($event['earlyreg']['per'] == 1) ? ($counts['total']-$event['freeadults']['amount']).' x $'.$event['earlyreg']['amount']: '$'.$event['earlyreg']['amount'];
										   				$event['earlyreg']['__total'] = ($event['earlyreg']['per'] == 1) ? ($counts['total']-$event['freeadults']['amount']) * $event['earlyreg']['amount']: $event['earlyreg']['amount'];
									   				}
								   				} else {
									   				// Our free adults aren't setup or didn't qualify, proceed without that factor.
									   				echo ($event['earlyreg']['per'] == 1) ? $counts['total'].' x $'.$event['earlyreg']['amount']: '$'.$event['earlyreg']['amount'];
									   				$event['earlyreg']['__total'] = ($event['earlyreg']['per'] == 1) ? $counts['total'] * $event['earlyreg']['amount']: $event['earlyreg']['amount'];
								   				}
							   				}
						   				?></td>
						   				<td>= <i class="red">$<?php echo $event['earlyreg']['__total']; ?></i></td>
					   				</tr><?php endif; ?>
					   				<?php // Free Adults
					   				if ($event['freeadults']['enabled'] == 1 && $counts['youth'] >= $event['freeadults']['threshold']) :
					   					if($i==1) { ?><tr><td colspan="3"><strong>Discounts</strong> <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Discounts" data-placement="top" data-content="There are 2 types of discounts. Some, like early bird registration, are automatically added. Others, such as Friends of Scouting discounts, need to be added. You can edit discounts in the 'Extras' tab."></i></td></tr><?php } $i++; ?><tr>
						   				<td>Adults <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Adults Discount" data-placement="top" data-content="<?php echo $event['freeadults']['description']; ?>"></i></td>
						   				<td><?php 
							   				if ($event['freeadults']['dollar'] == 1) {
								   				// The discount is a dollar amount, per adult
								   				echo $counts['adults'].' x $'.$event['freeadults']['amount'];
								   				$event['freeadults']['__total'] = $counts['adults'] * $event['freeadults']['amount'];
							   				} else {
								   				// The discount for free adults
								   				echo $event['freeadults']['amount'].' x $'.$this->shared->number_format_drop_zero($cost['adult']);
								   				$event['freeadults']['__total'] = $event['freeadults']['amount'] * $cost['adult'];
							   				}
						   				?></td>
						   				<td>= <i class="red">$<?php echo $event['freeadults']['__total']; ?></i></td>
					   				</tr><?php endif; ?>
					   				<?php // Late fee
					   				if (isset($event['paymenttiers']['l']) && $event['paymenttiers']['l'] == 1 && $reg['latefeeexempt'] == 0 && $fin['latefee'] > 0 && $event['paymenttiers']['ldate'] < time()) :
					   					$totaldue = ($fin['totalparticipants'] + $fin['group'] + $fin['requests'] - $fin['totaldiscounts']);
										if ($event['paymenttiers']['lpercent'] == 1) {
											$latefeedescription = 'A late fee of '.$event['paymenttiers']['lamount'].'% is applied for registrations that are not paid in full before '.date('F j, Y g:i:sa', $event['paymenttiers']['ldate']).'.<br><br>'.$event['paymenttiers']['lamount'].'% of $'.$totaldue.' = $'.$fin['latefee'].'<br><br>The late fee is calculated on the total due minus any class or activity preorders.';
											$latefeemath = $event['paymenttiers']['lamount'].'% of $'.$totaldue;
										} elseif ($event['paymenttiers']['lper'] == 1) {
											$latefeedescription = 'A late fee of $'.$event['paymenttiers']['lamount'].' per person is applied for registrations that are not paid in full before '.date('F j, Y g:i:sa', $event['paymenttiers']['ldate']).'.<br><br>'.$counts['total'].' x $'.$event['paymenttiers']['lamount'].' = $'.$fin['latefee'];
											$latefeemath = $counts['total'].' x $'.$event['paymenttiers']['lamount'];
										} else {
											$latefeedescription = 'A late fee of $'.$event['paymenttiers']['lamount'].' is applied for registrations that are not paid in full before '.date('F j, Y g:i:sa', $event['paymenttiers']['ldate']).'.';
											$latefeemath = '$'.$event['paymenttiers']['lamount'];
										}
					   					?><tr><td colspan="3"><strong>Registration Fees</strong> <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Discounts" data-placement="top" data-content="Registration fees are fees added on when late payments or returned checks occur. Contact us for more information."></i></td></tr><tr>
						   				<td>Late Fee <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Late Fee" data-placement="top" data-content="<?php echo $latefeedescription; ?>"></i></td>
						   				<td><?php echo $latefeemath; ?></td>
						   				<td>= $<?php echo $fin['latefee']; ?></td>
					   				</tr><?php endif; ?>
					  			</tbody>
					  		</table>
		   				</div>
		   			
		   				<div class="clear"></div>
					   	<?php // payment schedule
					   		if (isset($event['paymenttiers']['f']) || isset($event['paymenttiers']['s']) || isset($event['paymenttiers']['n'])) :
					   			// We have atleast 1 payment schedule entry. We will list them in order until we do something better.
					   			?><p><strong>Payment Schedule:</strong><br />
						   <?php
							$schedulecount = 0;
							foreach (array('f','s','n') as $schedule) :
								if (isset($event['paymenttiers'][$schedule]) && $event['paymenttiers'][$schedule] == 1) :
									$schedulearticle = (time() < $event['paymenttiers'][$schedule.'date']) ? ' is' : ' was';
									$scheduletext = '<strong>'.$schedulearticle.' due by '.date('F j, Y g:ia', $event['paymenttiers'][$schedule.'date']-300).'</strong> (<i>'.$this->shared->twitterdate($event['paymenttiers'][$schedule.'date']-300).'</i>)';
									if ($event['paymenttiers'][$schedule.'percent'] == 1) {
										if (($event['paymenttiers'][$schedule.'amount'] * $fin['totalnopreorders'] * .01) <= $fin['totalpaid']) continue;
										$scheduleprefix = '<strong>$'.number_format((float)(($event['paymenttiers'][$schedule.'amount'] * $fin['totalnopreorders'] * .01)-$fin['totalpaid']), 2, '.','').'</strong> <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Remaining Amount Due" data-placement="top" data-content="This is '.$event['paymenttiers'][$schedule.'amount'].'% of your total fee minus the total you have already paid"></i> (<i>'.$event['paymenttiers'][$schedule.'amount'].'% of your total fee</i>)';
									} else {
										if (($event['paymenttiers'][$schedule.'per'] == 1) && (($event['paymenttiers'][$schedule.'amount'] * $counts['total']) <= $fin['totalpaid'])) {
											continue;
										} elseif (($event['paymenttiers'][$schedule.'per'] == 0) && $event['paymenttiers'][$schedule.'amount'] <= $fin['totalpaid']) {
											continue;
										}
										$scheduleper = ($event['paymenttiers'][$schedule.'per'] == 1) ? ' per person (<strong>$'.number_format((float)($event['paymenttiers'][$schedule.'amount'] * $counts['total']), 2, '.','').'</strong>)': '';
										$scheduleprefix = '<strong>$'.$event['paymenttiers'][$schedule.'amount'].'</strong>'.$scheduleper;
									}
						   			echo $scheduleprefix.$scheduletext.'<br />';
						   			$schedulecount++;
						   		endif;
						   		if ($schedulecount == 0) echo (($fin['total'] - $fin['totalpaid']) <= 0) ? 'Your payments have all been made, thank you!': 'Please make your remaining payment of $'.number_format((float)($fin['total'] - $fin['totalpaid']), 2, '.', '').' before the event starts.';
						   	endforeach; ?></p>
						<?php endif; ?>
						<?php // Late fee
					   		if (isset($event['paymenttiers']['l']) && $event['paymenttiers']['l'] == 1 && $reg['latefeeexempt'] == 0) :
								if ($event['paymenttiers']['lpercent'] == 1) {
									$latefeedescription = 'A late fee of '.$event['paymenttiers']['lamount'].'% is applied to registrations that are not paid in full before '.date('F j, Y g:ia', $event['paymenttiers']['ldate']-300).' (<i>'.$this->shared->twitterdate($event['paymenttiers']['ldate']-300).'</i>). The late fee is calculated on the total due minus any class or activity preorders.';
								} elseif ($event['paymenttiers']['lper'] == 1) {
									$latefeedescription = 'A late fee of $'.$event['paymenttiers']['lamount'].' per person is applied to registrations that are not paid in full before '.date('F j, Y g:ia', $event['paymenttiers']['ldate']-300).' (<i>'.$this->shared->twitterdate($event['paymenttiers']['ldate']-300).'</i>).';
								} else {
									$latefeedescription = 'A late fee of $'.$event['paymenttiers']['lamount'].' is applied to registrations that are not paid in full before '.date('F j, Y g:ia', $event['paymenttiers']['ldate']-300).' (<i>'.$this->shared->twitterdate($event['paymenttiers']['ldate']-300).'</i>).';
								}
					   			?><p><strong>Late Fee:</strong> <?php echo $latefeedescription; endif; ?>
		   				<div class="clear"></div>
		   				<h2 class="section">Payments</h2>
   						<p>This is a list of all payments made for this event.</p>
				      	<table class="table table-condensed ">
				      		<thead>
					      	   	<tr><th>Status</th><th>Type</th><th>Date</th><th>Amount</th><th class="right">Tools</th></tr>
				      		</thead>
				      		<tbody>
						  	<?php 
						  	$regtitles = $this->shared->get_reg_set_titles($reg['id']);
						  	foreach ($payments as $p):
						  	$c = false;
						  	$d = false;
						  	
						  	?>	<tr>
							  		<td><span class="badge <?php if($p['type']=='transfer' || $p['type']=='credit') { echo "badge-inverse"; } elseif($p['status']=='Completed') { echo "badge-success"; } elseif ($p['status']=='Cancelled') { $c=true; } elseif ($p['status']=='Pending') { echo "badge-info"; } else { echo "badge-important"; } ?> camperhoverpopover" data-toggle="popover" title="Payment Details" data-placement="top" data-content="<strong>Payer:</strong> <?php echo $this->shared->get_user_name($p['user'],true); ?><br /><strong>Type:</strong> <?php echo ucwords($p['type']); ?><br /><strong>Status:</strong> <?php echo ucwords($p['comment']); ?><br /><strong>Time:</strong> <?php echo date('F j, Y g:i:sa', $p['date']); ?><br /><?php if ($p['type'] == 'check') { $p['__details'] = unserialize($p['details']); ?><strong>Check Number:</strong> <?php echo $p['__details']['number']; ?><br /><strong>Check Amount:</strong> $<?php echo $p['__details']['amount']; ?><br /><strong>Name on Check:</strong> <?php echo $p['__details']['name']; ?><br /><?php } ?><strong>Notes:</strong> <?php echo $p['notes']; ?><br />"><?php if($c) echo '<strong>'; echo ($p['type']=='transfer' || $p['type']=='credit') ? ucfirst($p['type']): $p['status']; if($c) echo '</strong>'; ?></span></td>
						  			<td<?php if($c) echo ' class="fiftypercent strikethrough"'; ?>><?php echo ucwords($p['type']); ?></td>
						  			<td<?php if($c) echo ' class="fiftypercent strikethrough"'; ?>><span data-toggle="tooltip" class="moment-format" title="<?php echo date('F j, Y g:i:sa', $p['date']); ?>" ><?php echo date('F j, Y', $p['date']); ?></span></td>
						  			<td<?php if($c) echo ' class="fiftypercent strikethrough"'; ?>>$<?php echo $p['amount']; ?></i></td>
						  			<td><span class="right"><?php if ($p['type'] == 'check') echo anchor('payments/checkform/'.$p['token'], 'Check form', 'class="btn btn-small tan" data-toggle="tooltip" title="Click to view the check payment detail form. You need to print and send this in with your check payment."'); ?></span></td>
						 		</tr>
						 	<?php endforeach;?>
				      	</table>
		   				<?php /* if ($individual) { ?>
		   				<p>Payments online for individual registrations are not yet ready right now , but there are other ways to make a payment while we get the online system ready for you.</p>
		   				<div class="quarter last">
							<h3>Check via Mail</h3>
							<p>Mail a check made out to Longs Peak Council</p>
							<p>Longs Peak Council - Camp Registration<br />
								PO Box 1166<br />
								Greeley, CO 80632-1166
							</p>
						</div>
		   				<div class="quarter">
							<h3>Credit Card via Phone</h3>
							<p>Call our Greeley Service Center to make a credit card payment.</p>
							<p>Call us at 800-800-4052 (970-330-6305 local) and ask for the camping department. We will post your payment when it's been completed.</p>
						</div>
						<div class="quarter last">
							<h3>In Person</h3>
							<p>Bring your check to our Greeley Service Center: </p>
							<p>Longs Peak Council - Greeley Service Center<br />
								2215 23rd Avenue<br />
								Greeley, Colorado
							</p>
						</div>

		   				<?php } else { */ ?>
						<p><?php echo anchor('payments', 'View all payments &rarr;', 'class="btn blue"'); ?>
		   				<h2 class="section">Make a Payment</h2>
			   			<?php if ($lock) : ?><p><i class="icon-warning-sign red"></i> You are not able to make payments because this event is about to start or has already begun. Please contact the council service center.</p><?php else : ?>
		   				<p>You can make payments in a variety of ways. Camper allows you to make online payments via PayPal. You can use a debit/credit card, PayPal account, or do a bank transfer. If you prefer to pay in cash or check, you can create the payment here and then send your check into the council service center.</p>
						<h3>Pay Online</h3>
						<p>Paying online with PayPal is easy. Enter the amount you would like to pay in the box and click "Pay with PayPal". You will be taken to PayPal where you will complete the payment. There is no fee to pay with PayPal, and you are not required to login or signup.</p>
						<script>
						$(document).ready(function() {
							$( "#payamount" ).change(function() {
								var payamount = $('#payamount').val();
								var payhref = '<?php echo base_url('/api/v1/pay/start'); ?>?reg=<?php echo $reg['id']; ?><?php if ($individual) echo '&individual=1'; ?>&amount=' + payamount;
								$("#paytrigger").attr('href', payhref);
							});
						});
						</script>
		   				<div class="camperform float" style="width: 150px" data-toggle="tooltip" title="How much would you like to pay right now? You can make as many payments as you would like. Confirm the amount and click pay to start a payment."><input id="payamount" class="" name="payamount" type="text" autocomplete="off" placeholder="$" value="$<?php echo $fin['totaldue']; ?>"  /><label for="payamount">Enter Amount</label></div>
		   				<a href="<?php echo base_url('/api/v1/pay/start'); ?>?reg=<?php echo $reg['id']; ?>&amount=<?php echo $fin['totaldue']; ?>" id="paytrigger" data-loading-text="Starting payment..." onclick="$(this).button('loading');" class="btn btn-teal pay">Pay with PayPal &rarr;</a>
		   				<div class="clear"></div>

						<h3>Check</h3>
						<p>You can also send us or drop off a check at our service center. Enter the amount you paid, your check number (if any) and hit "Create Payment". Your payment will show up as pending until we confirm it. <strong>Please make your check payable to Longs Peak Council</strong> and send it with a print-out of the confirmation page (this page will show when you click "Create Payment").</p>
						<script>
						$(document).ready(function() {
							$( ".checkfield" ).keyup(function() {
								var checkamount = $('#checkamount').val();
								var checknumber = $('#checknumber').val();
								var checkname = $('#checkname').val();
								var checkhref = '<?php echo base_url('/api/v1/pay/check'); ?>?reg=<?php echo $reg['id']; ?><?php if ($individual) echo '&individual=1'; ?>&amount=' + checkamount + '&number=' + checknumber + '&name=' + checkname;
								$("#checktrigger").attr('href', checkhref);
							});
						});
						</script>
		   				<div class="camperform float" style="width: 100px" data-toggle="tooltip" title="How much would you like to pay right now? You can make as many payments as you would like. Confirm the amount and click pay to start a payment."><input id="checkamount" class="checkfield" name="checkamount" type="text" autocomplete="off" placeholder="$" value="$<?php echo $fin['totaldue']; ?>"  /><label for="checkamount">Enter Amount</label></div>
		   				<div class="camperform float" style="width: 80px" data-toggle="tooltip" title="Enter the check number so we can verify payment. This is required."><input id="checknumber" class="checkfield" name="checknumber" type="text" autocomplete="off" placeholder="0000" value=""  /><label for="checknumber">Check no.</label></div>
		   				<div class="camperform float" style="width: 250px" data-toggle="tooltip" title="What is the name on the check? This will help us verify and confirm the payment."><input id="checkname" class="checkfield" name="checkname" type="text" autocomplete="off" placeholder="Baden Powell..." value=""  /><label for="checkname">Name on Check</label></div>
		   				<a href="<?php echo base_url('/api/v1/pay/check'); ?>?reg=<?php echo $reg['id']; ?>&amount=<?php echo $fin['totaldue']; ?>" id="checktrigger" data-loading-text="Starting payment..." onclick="$(this).button('loading');" class="btn btn-teal pay">Create Payment &rarr;</a>
		   				<?php endif; ?>
		   			</div>
			   		<div class="clear"></div>
   				</div>
   			</div>
   		</div>
   		<div class="clear"></div>
   	<?php if (!$lock) echo form_close();?> 
	<!-- Unregister Modal -->
	<div id="cancelevent" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<?php echo form_open(uri_string()); ?>
	<?php if ($individual) { ?><input type="hidden" name="user" value="<?php echo $user->id; ?>" /><?php } else { ?><input type="hidden" name="unit" value="<?php echo $unit['id']; ?>" /><?php } ?>
	<input type="hidden" name="event" value="<?php echo $event['id']; ?>" />
	<input type="hidden" name="reg" value="<?php echo $reg['id']; ?>" />
	<input type="hidden" name="session" value="<?php echo $session['id']; ?>" />
		<input type="hidden" name="what" value="unregister" />
   		<div class="container">
   			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	   		<div class="quarter">
   				<h2 class="pull">Unregister</h2>
   				<div class="clear"></div>
	   		</div>
	   		<div class="threequarter">
   				<p>A number of things will happen when you unregister your unit, including the following:</p>
   				<ol>
   					<li>Removes your registration</li>
   					<li>Deletes any activity registrations (any members registered for activities will lose their spots)</li>
   					<li>Clears this event's roster (your unit's member roster will still be there)</li>
   					<li>Prepares a refund report for the council</li>
   					<li>Prepares an automatic refund of any online/PayPal payments. This will not be a credit and funds will go back to the original payment source (the credit card used to make the payments).</li>
   				</ol>
   				<p></p>
   				<p>All of the contacts for your unit and the council will be notified that your unit has unregistered, this is so we can keep an open channel of communication and we can prepare any refunds that need to occur.</p>
   				<p>If you choose to register your unit at a later time, late fees and new non-refundable fees may apply. Contact the council for details. Also review our <a href="http://camps.longspeakbsa.org/refund-policy/" target="_blank">refund policy</a>.</p>
   					
   				<div class="camperform float last" style="width: 660px"><textarea name="cancelnotice" id="fstart" class="" placeholder="We unregistered becasue (not required)..."></textarea><label for="fstart">Reason (not required)</label></div>
	   			<div class="clear"></div><input type="submit" name="submit" value="Unregister for this event" class="btn red"  /> <button class="btn tan" data-dismiss="modal" aria-hidden="true">Nevermind</button>
   			</div>
   		</div>
   		<div class="clear"></div>
   		<?php echo form_close();?>
	</div>
   	<!-- End Modal -->
   	<!-- HHHHHHHHHHH Modal -->
	<div id="modal_HHHHHHHHHHHH" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<?php echo form_open("path/to/form");?>
   		<div class="container">
   			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	   		<div class="pull">
   				<h2 class="pull">Change the Primary Contact</h2>
   				<p>You can change the main contact for this unit here.</p>
   				<div class="clear"></div>
	   		</div>
	   		<div class="tab-content inner-push">
   				<h2 class="section">Add leader via email </h2>
   				<p>You can change the primary contact for this unit here. By submitting the request, the primary contact for this unit will be set and gain access to this unit and the information of it's members. If you are the primary contact, this will make you the alternate contact replacing the existing primary contact. </p>
   		   		<div class="camperform float search" style="width: 60%"><i class="icon-envelope-alt"></i><input class="ico" type="text" name="priemail" data-toggle="tooltip" data-placement="right" placeholder="baden.powell@scouting.org"  title="Enter the email address of the person you wish to make the primary contact" /><label>This will make this user the primary contact and set you as the alternate contact.</label></div>
	   			<div class="clear"></div><input type="submit" name="submit" value="Set new Primary Contact &rarr;" class="btn blue"  /> <button class="btn" data-dismiss="modal" aria-hidden="true">Nevermind</button>
	   			
   			</div>
   		</div>
   		<div class="clear"></div>
   		<?php echo form_close();?>
	</div>
   	<!-- End Modal -->
	</article>
