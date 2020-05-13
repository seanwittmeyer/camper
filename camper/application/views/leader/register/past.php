<?php 

/* 
 * Camper Leader / Registrations / Past View
 *
 * This is the listing of events the unit has registered for.
 *
 * File: /application/views/register/past.php
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
				<li class="active"><?php echo anchor("registrations/past", 'Past Events');?></li>
				<li class=""><?php echo anchor("registrations", 'Upcoming Events');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
    	<div class="container">
    		<?php if ($individual) { ?>
			<h2>Past Registrations</h2>
			<p>Past events are events that you have registered for, and have past. You can view any event registration by clicking on the "View this Registration" button for the event you wish to view.</p>
			<?php } else { ?>
			<h2>Past <?php echo $unittitle; ?> Registrations</h2>
			<p>Past events are events that <?php echo $unittitle; ?> registered for, and have past. You can view any event registration by clicking on the "View" button for the event you wish to view.</p>
			<?php } ?>
			<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
		</div>
		
      	<table class="table table-condensed">
      		<thead>
		  	<?php 
		  	// Prep our loop
		  	$timestamp = time();
		  	$f = false;
		  	$pastflag = false;
		  	$regset = false;
		  	if ($regs) : ?>
      	   	<tr><th>Event</th><th>Session</th><th>Dates</th><th>Location</th><th>Y/M/F <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Participants" data-placement="top" data-content="The <strong>Y/M/F</strong> column is a breakdown of the participants that are registered for an event with your unit. Generally, only youth participants count against the limit of registrations for an event.<br /><br /><strong>Y:</strong> Youth participants<br /><strong>M:</strong> Male adult participants/leaders<br /><strong>F:</strong> Female adult participants/leaders"></i></th><th>Fees <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Fees" data-placement="top" data-content="We've displayed the total amount you have paid next to the total of the fees for the event. This is not a representation of what is due now, just a financial overview. Manage the event to see detailed financial information including what needs to be paid and by when."></i></th><th>Tools</th></tr>
      		</thead>
      		<tbody>
		  	<?php foreach ($regs as $reg):
			  	// Reset and fill $regset with our session, registration and event
			  	unset($regset);
			  	$regset = ($individual) ? $this->shared->get_reg_set(false, $reg['id'], $reg, null, true, $user): $this->shared->get_reg_set($unit['id'], $reg['id'], $reg);
			  	
			  	// Prep our date
			  	if (empty($regset['session']['datestart'])) {
				    if (empty($regset['event']['dateend'])) {
						$dates = date('F j, Y', $regset['event']['datestart']);
				    } else {
						$dates = date('F j', $regset['event']['datestart']).date(' - F j, Y', $regset['event']['dateend']);
				    } 
				} else {
				    if (empty($regset['session']['dateend'])) {
						$dates = date('F j, Y', $regset['session']['datestart']);
				    } else {
						$dates = date('F j', $regset['session']['datestart']).date(' - F j, Y', $regset['session']['dateend']);
				    } 
				}
			  	if ($regset['event']['dateend']) { $date = $regset['event']['dateend']; } else { $date = $regset['event']['datestart']; }
			  	
			  	// If we have an event that happened already, we'll pass on showing it.
			  	if ($date > $timestamp) {
				  	$pastflag=true;
			  	} else {
		  	?>
      	    	<tr>
		 	    <td><?php echo anchor('registrations/'.$reg['id'], $regset['event']['title']) ;?></td>
		 	    <td><?php echo (empty($regset['session']['title'])) ? $regset['event']['sessiontitle'].' '.$regset['session']['sessionnum'] : $regset['session']['title']; ?></td>
		 	    <td><?php echo $dates; ?></td>
		 	    <td><?php echo $regset['event']['location'];?></td>
		 	    <td><strong><?php echo $regset['reg']['youth'];?></strong> / <?php echo $regset['reg']['male'];?> / <?php echo $regset['reg']['female'];?></td>
		 	    <td></td>
		 	    <td><?php echo anchor('registrations/'.$reg['id'], 'View &rarr;', 'class="btn btn-small blue"'); ?></td>
		 	    <!--registered and all good?	<td><?php echo ($regset['event']['open'] == '0') ? '<i class="icon-remove red"></i>' : '<i class="icon-ok teal"></i>'; ?></td> -->
		 		</tr>
		 	<?php } endforeach; else : ?>
      	   	<tr><th>No registrations, make history and see <?php echo anchor('events', 'all events'); ?> you can register for!</th></tr>
      		</thead>
      		<tbody>
		 	<?php endif; ?>
      		</tbody>
      	</table>
		
   		<div class="container">
			<div class="accordion" id="upcoming">
			  	<?php /*
			  	$timestamp = time();
			  	$timestamp = $timestamp - 1209600;
			  	$i=1;
			  	$f=false;
			  	$sessions = false;
			  	foreach ($regs as $reg):
				  	if ($event['dateend']) { $date = $event['dateend']; $f=true; } else { $date = $event['datestart']; }
				  	if ($date < $timestamp) {
					  	$pastflag=true;
				  	} elseif ($event['open'] == '0') {
				  		$closedflag=true;
				  	} else {
				  		// Get our sessions
						unset($sessions);
						$sessions = $this->register_model->get_sessions($event['id']); 
						$sessionscount = count($sessions);
			  	?>
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#upcoming" href="#upcoming<?php echo $i; ?>">
							<?php echo $event['title'];?>
							<div class="right eleven"> <?php echo $event['eventtype'];?> &nbsp; | &nbsp; <?php echo (empty($event['dateend'])) ? date('F j, Y', $event['datestart']) : date('F j', $event['datestart']).date(' - F j, Y', $event['dateend']); ?></div>
						</a>
					</div>
					<div id="upcoming<?php echo $i; ?>" class="accordion-body collapse ">
						<div class="accordion-inner">
							<h3><?php echo $event['title'];?></h3>
							<div class="desc left">
								<p><?php echo $event['description'];?></p>
								<?php if ($sessionscount > 1) : ?>
								<p><strong>Event Type:</strong> <?php echo $event['eventtype'];?><br />
								<strong>Location:</strong> <?php echo $event['location'];?><br />
								<strong><?php echo $event['sessiontitle']; ?>s:</strong> <?php echo (!$sessions) ? 'None' : $sessionscount; ?></p>
								<?php else : ?><p><?php echo anchor("registrations/set/".$event['id'], 'Register &rarr;', 'class="btn blue"') ;?></p><?php endif; ?>
							</div>
							<div class="details left">
								<?php if ($sessionscount == 1) : ?><p>
									<strong>Event Type:</strong> <?php echo $event['eventtype'];?><br />
									<strong>Location:</strong> <?php echo $event['location'];?><br />
									<strong>Dates:</strong> <?php echo (empty($event['dateend'])) ? date('F j, Y', $event['datestart']) : date('F j', $event['datestart']).date(' - F j, Y', $event['dateend']); ?><br />
									<strong>Openings:</strong> TBA<br />
									<strong>Cost:</strong> TBA
								</p>
								<?php else : ?> 
								<table class="table table-condensed">
									<thead>
										<tr><th><?php echo $event['sessiontitle']; ?>s</th><th>Dates</th><th>Openings</th></tr>
									</thead>
									<tbody>
										<?php $j=1; foreach ($sessions as $onesession): ?> 
							      	    <tr>
							      	    	<td><?php echo (empty($onesession['title'])) ? $event['sessiontitle'].' '.$j : $onesession['title']; ?></td>
							      	    	<td><?php echo (empty($onesession['dateend'])) ? date('F j, Y', $onesession['datestart']) : date('F j', $onesession['datestart']).date(' - F j, Y', $onesession['dateend']); ?></td>
								  			<td><?php if ($onesession['open'] == '1' && $onesession['count'] < $onesession['limitsoft']) {
								  				echo $onesession['limitsoft']-$onesession['count']; ?> Openings <div class="right"><?php echo anchor('registrations/set/'.$event['id'].'/'.$onesession['id'], 'Register &rarr;', 'class="btn btn-small blue"'); ?></div><?php
									  			} elseif ($onesession['open'] == '1' && $onesession['count'] >= $onesession['limitsoft']) { ?>This <?php echo strtolower($event['sessiontitle']);?> is full.<?php
									  			} else { ?>Registration is <strong>not</strong> open for this <?php echo strtolower($event['sessiontitle']); } ?></td>
							      	    </tr>
									 	<?php $j++; endforeach; ?>
									</tbody>
									</table>
									<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			 	<?php $i++; $f=false; } endforeach; */?>
			</div><!-- /.accordion -->
			<div class="quarter">
				<p><!-- Patience is a virtue. --></p>
			</div>			
   		</div>
   		<div class="clear"></div>
   	<?php echo form_close();?> 
	</article>
