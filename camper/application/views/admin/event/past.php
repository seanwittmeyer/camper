<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin Event / Past Events Index Listing View
 *
 * This is the ...
 *
 * File: /application/views/admin/event/past.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

?>
	<div class="subnav">
		<div class="container">
			<h2>Events</h2>
			<nav class="campersubnav">
				<li class="" data-toggle="tooltip" title="New Activity"><?php echo anchor("event/activities/new", '<i class="icon-plus"></i>');?></li>
				<li class=""><?php echo anchor("event/activities", 'Activity Library');?></li>
				<li class="" data-toggle="tooltip" title="New Event"><?php echo anchor("event/new", '<i class="icon-plus"></i>');?></li>
				<li class="active"><?php echo anchor("event/past", 'Past Events');?></li>
				<li class=""><?php echo anchor("event", 'Upcoming Events');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
   	    <h2 class="">Past Events</h2>
   	    <p>These are the events in Camper that have already happened. You can view details for these events including registrations and reports. These events are no longer open for registration.</p>
		<div class="clear"></div>
   		<!-- Eventually sort by event type
   		<ul id="detailstabs" class="teal">
   			<li class="active"><a href="/camper/event/<?php echo $event['id']; ?>/details" >Details &amp; Dates</a></li>
   			<li class=""><a href="/camper/event/<?php echo $event['id']; ?>/sessions" >Sessions</a></li>
   			<li class=""><a href="/camper/event/<?php echo $event['id']; ?>/options" >Costs &amp; Options</a></li>
   			<li class=""><a href="#" >Activities</a></li>
   			<li class=""><a href="#" >Registrations</a></li>
   			<li class=""><a href="#" >Reports</a></li>
   		</ul>
   		-->
		<!--<h5 class="section">All Users</h5>-->
      	<table class="table table-condensed">
      		<thead>
      	   	<tr><th>Event</th><th>Dates</th><th>Location</th><th>Tools</th></tr>
      		</thead>
      		<tbody>
      		
      		<?php 
		  	$timestamp = time();
		  	$f = false;
		  	foreach ($events as $event):
			  	if ($event['dateend']) { $date = $event['dateend']; $f=true; } else { $date = $event['datestart']; }
			  	if ($date > $timestamp) {
				  	$pastflag=true;
			  	} else {
		  	?>
      	    	<tr>
		 	    	<td><?php echo anchor("event/".$event['id']."/details", $event['title']) ;?></td>
		 	    	<td><?php echo date('F d, Y', $event['datestart']); if($f) { echo date(' - F d, Y', $event['dateend']); } ?></td>
		 	    	<td><?php echo $event['location'];?></td>
		 	    	<td><?php //echo ($user->active) ? '<a href="users/deactivate/'.$user->id.'">Deactivate</a>' : '<a href="users/activate/'.$user->id.'">Activate</a>'; ?> 
		 	    	<?php echo anchor("event/".$event['id']."/details", 'Edit') ;?> <?php echo anchor("event/".$event['id']."/registrations", 'Registrations') ;?></td>
		 		</tr>
		 	<?php } $f=false; endforeach;?>
      	</table>
   		
	</article>
