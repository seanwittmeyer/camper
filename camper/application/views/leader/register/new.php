<?php 

/* 
 * Camper Leader / Registrations / Upcoming View
 *
 * This is the listing of events the unit has registered for.
 *
 * File: /application/views/register/upcoming.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 /* Available vars
 $events
 $unit
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
 $new['event'] = $this->shared->get_event($new['event']);
 $new['session'] = ($new['session'] === false) ? false: $this->shared->get_sessions($new['event']['id'], $new['session']);

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
    	<div class="container">
	    	<div class="quarter">
	    		&nbsp;
	    	</div>
	    	<div class="half">
				<h2>Just to make sure...</h2>
				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
				<p>You are about to register for <strong><?php if ($new['session'] !== false) { echo (empty($new['session']['title'])) ? $new['event']['sessiontitle'].' '.$new['session']['sessionnum'].' of ' : $new['session']['title'].' of '; } echo $new['event']['title']; ?></strong> again. Confirm you want to register again or view your existing registration below.</p>
				<h3>Register Again</h3>
				<p><?php $r = ($new['group'] !== false) ? $new['event']['id'].'/'.$new['session']['id'].'/'.$new['group'] : ($new['session'] !== false) ? $new['event']['id'].'/'.$new['session']['id']: $new['event']['id']; 
				echo anchor('registrations/new/'.$r.'?confirm=1', 'New Registration &rarr;', 'class="btn teal"'); ?></p>
		  		<div class="clear hr"></div>
				<h3>Your Existing Registration</h3>

				<?php 
			  	// Prep our loop
			  	$timestamp = time() - 1209600;
			  	$f = false;
			  	$i = 0;
			  	$pastflag = false;
			  	$regset = false;
			  	if ($regs) : ?>
	
			  	<?php foreach ($regs as $reg):
				  	// Reset and fill $regset with our session, registration and event
				  	unset($regset);
				  	$regset = ($individual) ? $this->shared->get_reg_set(false, $reg['id'], $reg, null, true, $user): $this->shared->get_reg_set($unit['id'], $reg['id'], $reg);
				  	if ($regset['reg']['eventid'] !== $new['event']['id']) continue;
				  	
				  	// Prep our date
				  	if (empty($regset['session']['datestart'])) {
					    if (empty($regset['event']['dateend'])) {
							$dates = date('F j, Y', $regset['event']['datestart']);
							$datestitle = 'Date';
					    } else {
							$dates = date('F j', $regset['event']['datestart']).date(' - F j, Y', $regset['event']['dateend']);
							$datestitle = 'Dates';
					    } 
					} else {
					    if (empty($regset['session']['dateend'])) {
							$dates = date('F j, Y', $regset['session']['datestart']);
							$datestitle = 'Date';
					    } else {
							$dates = date('F j', $regset['session']['datestart']).date(' - F j, Y', $regset['session']['dateend']);
							$datestitle = 'Dates';
					    } 
					}
				  	if ($regset['event']['dateend']) { $date = $regset['event']['dateend']; } else { $date = $regset['event']['datestart']; }
				  	
				  	// If we have an event that happened already, we'll pass on showing it.
				  	if ($date < $timestamp) {
					  	$pastflag=true;
				  	} else { 
				  		$i++;
			  	?>
			  		<?php if ($i > 1) { ?><div class="clear hr"></div><?php } ?>
			  		<h3><?php echo anchor('registrations/'.$regset['reg']['id'].'/details', $regset['event']['title'], 'class="noline"');?><div class="right"><?php echo anchor('registrations/'.$regset['reg']['id'].'/details', 'Manage &rarr;', 'class="btn blue"'); ?></div></h3>
			  		<div class="camperform float " style=""><span><?php echo (empty($regset['session']['title'])) ? $regset['event']['sessiontitle'].' '.$regset['session']['sessionnum'] : $regset['session']['title']; ?></span><label><?php echo $regset['event']['sessiontitle']; ?></label></div>
			  		<div class="camperform float " style=""><span><?php echo $dates; ?></span><label><?php echo $datestitle; ?></label></div>
			  		<?php if (!$individual) { ?><div class="camperform float " style=""><span><strong><?php echo $regset['reg']['youth'];?></strong> / <?php echo $regset['reg']['male'];?> / <?php echo $regset['reg']['female'];?></span><label>Y/M/F <i class="icon-question-sign camperhoverpopover inline" data-toggle="popover" title="Participants" data-placement="top" data-content="The <strong>Y/M/F</strong> column is a breakdown of the participants that are registered for an event with your unit. Generally, only youth participants count against the limit of registrations for an event.<br /><br /><strong>Y:</strong> Youth participants<br /><strong>M:</strong> Male adult participants/leaders<br /><strong>F:</strong> Female adult participants/leaders"></i></label></div><?php } ?>
			  		<div class="clear"></div><p>&nbsp;</p>
			 	<?php } endforeach; endif; if ($i==0) : ?>
	      	   	<h3><?php echo $unittitle.$helpingverb; ?> not registered for any events. See <?php echo anchor('events', 'all events'); ?> you can register for.</h3>
			 	<?php endif; ?>


	    	</div>
		</div>

   		<div class="clear"></div>
   	<?php echo form_close();?> 
	</article>
