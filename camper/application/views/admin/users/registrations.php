<?php 

/* 
 * Camper Admin / Users / Unit / Registrations View
 *
 * This is. 
 *
 * File: /application/views/admin/users/registrations.php
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
   				<li class=""><?php echo anchor("units/".$unit['id'], 'Unit Details');?></li>
   				<li class=""><?php echo anchor("units/".$unit['id']."/payments", 'Payments');?></li>
   				<li class="active"><?php echo anchor("units/".$unit['id']."/registrations", 'Registrations');?></li>
   				<li class=""><?php echo anchor("units/".$unit['id']."/members", 'Members');?></li>

   			</ul>
   		</div>
   		<h2 class="">Registrations</h2>
   		<p>These are all of the events that <?php echo $unittitle;?> has been registered for.</p>
	  	<table class="table table-condensed">
	  		<thead>
		  	<?php 
		  	// Prep our loop
		  	$f = false;
		  	$regset = false;
		  	if ($regs) : ?>
	  	   	<tr><th>Status</th><th>Event</th><th>Session</th><th>Dates</th><th>Location</th><th>Y/M/F <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Participants" data-placement="top" data-content="The <strong>Y/M/F</strong> column is a breakdown of the participants that are registered for an event with your unit. Generally, only youth participants count against the limit of registrations for an event.<br /><br /><strong>Y:</strong> Youth participants<br /><strong>M:</strong> Male adult participants/leaders<br /><strong>F:</strong> Female adult participants/leaders"></i></th><th>Fees <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Fees" data-placement="top" data-content="We've displayed the total amount you have paid next to the total of the fees for the event. This is not a representation of what is due now, just a financial overview. Manage the event to see detailed financial information including what needs to be paid and by when."></i></th><th>Tools</th></tr>
	  		</thead>
	  		<tbody>
		  	<?php foreach ($regs as $reg):
			  	// Reset and fill $regset with our session, registration and event
			  	unset($regset);
			  	$regset = $this->shared->get_reg_set($unit['id'], $reg['id'], $reg);
			  	
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
			  	unset($verify);
			  	$verify = $this->shared->verify($reg['id']);
			  	if ($verify['restricted']===true) {
					$verifyico = '<i class="icon-remove red" data-toggle="tooltip" title="'.$unit['unittype'].' '.$unit['number'].' is not registered, click manage to see details"></i>';
			  	} elseif ($verify['result']===false) {
					$verifyico = '<i class="icon-exclamation-sign red" data-toggle="tooltip" title="'.$unit['unittype'].' '.$unit['number'].' is registered but there are issues, click manage to see details"></i>';
			  	} else {
					$verifyico = '<i class="icon-ok teal" data-toggle="tooltip" title="'.$unit['unittype'].' '.$unit['number'].' is registered"></i>';
			  	}
			?>
	  			<tr>
	  			<td><?php echo $verifyico; ?></td>
		 		<td><?php echo anchor('event/'.$regset['event']['id'].'/registrations/'.$reg['id'].'/edit', $regset['event']['title']) ;?></td>
		 		<td><?php echo (empty($regset['session']['title'])) ? $regset['event']['sessiontitle'].' '.$regset['session']['sessionnum'] : $regset['session']['title']; ?></td>
		 		<td><?php echo $dates; ?></td>
		 		<td><?php echo $regset['event']['location'];?></td>
		 		<td><strong><?php echo $regset['reg']['youth'];?></strong> / <?php echo $regset['reg']['male'];?> / <?php echo $regset['reg']['female'];?></td>
		 		<td></td>
		 		<td><?php echo anchor('event/'.$regset['event']['id'].'/registrations/'.$reg['id'].'/edit', 'Manage &rarr;', 'class="btn btn-small blue"'); ?></td>
		 		<!--registered and all good?	<td><?php echo ($regset['event']['open'] == '0') ? '<i class="icon-remove red"></i>' : '<i class="icon-ok teal"></i>'; ?></td> -->
		 		</tr>
		 	<?php endforeach; else : ?>
	  	   	<tr><th>There are no <?php echo $unit['unittype'].' '.$unit['number']; ?> event regs. </th></tr>
	  		</thead>
	  		<tbody>
		 	<?php endif; ?>
	  		</tbody>
	  	</table>
   		<div class="clear"></div>
   	<?php echo form_close();?> 
	</article>
