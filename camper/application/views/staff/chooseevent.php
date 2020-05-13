<?php 

/* 
 * Camper At Camp / Choose Event View
 *
 * This page lets a staff user choose an event or session. 
 *
 * File: /application/views/staff/chooseevent.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

?>	<?php if ($sessions === false) : ?>
	<h2>Choose an event...</h2>
	<p>Start by choosing the event you wish to manage.</p>
	<table class="table table-condensed">
		<thead>
			<tr><th colspan="2">Event</th></tr>
		</thead>
		<tbody>
	<?php foreach ($events as $event) : ?>
			<tr><td><strong><?php echo anchor('atcamp/'.$event['id'], $event['title'].' &rarr;'); ?></strong></td><td><?php echo $event['eventtype']; ?> at <?php echo $event['location']; ?></td></tr>
	<?php endforeach; ?>
		</tbody>
	</table>
	<?php else : // Has Sessions ?>
	<h2>Choose a <?php echo strtolower($events['sessiontitle']); ?>...</h2>
	<p>Select the <?php echo strtolower($events['sessiontitle']); ?> for <?php echo $events['title']; ?> you wish to manage.</p>
	<table class="table table-condensed">
		<thead>
			<tr><th><?php echo $events['sessiontitle']; ?></th></tr>
		</thead>
		<tbody>
	<?php foreach ($sessions as $session) : ?>
			<tr><td><strong><?php echo anchor('atcamp/'.$events['id'].'/'.$session['id'], $session['nicetitle'].' &rarr;'); ?></strong></td></tr>
	<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>