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
    		<?php if ($individual) { ?>
			<h2>Your Registrations</h2>
			<p>All upcoming events that you are registered for will show up here. You can manage any event registration by clicking on the "Manage" button for the event you wish to manage.</p>
			<?php } else { ?>
			<h2><?php echo $unittitle; ?> Registrations</h2>
			<p>All upcoming events that <?php echo $unittitle.$helpingverb; ?> currently registered for will show up here. You can manage any event registration by clicking on the "Manage" button for the event you wish to manage.</p>
			<?php } ?>
			<p><strong>Looking to register for another event?</strong> Head over to the Events section to register for other events. <?php echo anchor('events', 'All events &rarr;', 'class="btn btn-small tan"'); ?></p>
			<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
		</div>
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
			  		unset($verify);
			  		$verify = $this->shared->verify($reg['id']);
			  		if ($verify['restricted']===true) {
				  		$verifyico = '<i class="icon-remove red big" data-toggle="tooltip" title="'.$unittitle.$helpingverb.' not registered, click manage to see details"></i>';
			  		} elseif ($verify['result']===false) {
				  		$verifyico = '<i class="icon-exclamation-sign red big" data-toggle="tooltip" title="'.$unittitle.$helpingverb.' registered but there are issues, click manage to see details"></i>';
			  		} else {
				  		$verifyico = '<i class="icon-ok teal big" data-toggle="tooltip" title="'.$unittitle.$helpingverb.' registered"></i>';
			  		}
		  	?>
		  		<div class="clear hr"></div>
		  		<h2><?php echo anchor('registrations/'.$regset['reg']['id'].'/details', $regset['event']['title'], 'class="noline"');?><div class="right"><?php echo anchor('registrations/'.$regset['reg']['id'].'/details', 'Manage &rarr;', 'class="btn blue"'); ?></div></h2>
		  		<div class="camperform float " style=""><span><?php echo $verifyico; ?></span><label>Status</label></div>
		  		<div class="camperform float " style=""><span><?php echo (empty($regset['session']['title'])) ? $regset['event']['sessiontitle'].' '.$regset['session']['sessionnum'] : $regset['session']['title']; ?></span><label><?php echo $regset['event']['sessiontitle']; ?></label></div>
		  		<div class="camperform float " style=""><span><?php echo $dates; ?></span><label><?php echo $datestitle; ?></label></div>
		  		<div class="camperform float " style=""><span><?php echo $regset['event']['location'];?></span><label>Location</label></div>
		  		<?php if (!$individual) { ?><div class="camperform float " style=""><span><strong><?php echo $regset['reg']['youth'];?></strong> / <?php echo $regset['reg']['male'];?> / <?php echo $regset['reg']['female'];?></span><label>Y/M/F <i class="icon-question-sign camperhoverpopover inline" data-toggle="popover" title="Participants" data-placement="top" data-content="The <strong>Y/M/F</strong> column is a breakdown of the participants that are registered for an event with your unit. Generally, only youth participants count against the limit of registrations for an event.<br /><br /><strong>Y:</strong> Youth participants<br /><strong>M:</strong> Male adult participants/leaders<br /><strong>F:</strong> Female adult participants/leaders"></i></label></div><?php } ?>
		  		<div class="clear"></div><p>&nbsp;</p>
		 	    <!--registered and all good?	<td><?php echo ($regset['event']['open'] == '0') ? '<i class="icon-remove red"></i>' : '<i class="icon-ok teal"></i>'; ?></td> -->
		 	<?php } endforeach; endif; if ($i==0) : ?>
      	   	<h3><?php echo $unittitle.$helpingverb; ?> not registered for any events. See <?php echo anchor('events', 'all events'); ?> you can register for.</h3>
		 	<?php endif; ?>
   		<div class="clear"></div>
   	<?php echo form_close();?> 
	</article>
