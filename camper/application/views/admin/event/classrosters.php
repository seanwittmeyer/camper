<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin Event / Details View
 *
 * This is the ...
 *
 * File: /application/views/admin/event/details.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 /* Available Vars
  * class, event, periods (periods,days), activity, sessions, regs
  */

?>	<script>
	$(document).ready(function() {
	});

	</script>
	<div class="subnav">
		<div class="container">
			<h2>Events</h2>
			<nav class="campersubnav">
				<li class="" data-toggle="tooltip" title="New Activity"><?php echo anchor("event/activities/new", '<i class="icon-plus"></i>');?></li>
				<li class=""><?php echo anchor("event/activities", 'Activity Library');?></li>
				<li class="" data-toggle="tooltip" title="New Event"><?php echo anchor("event/new", '<i class="icon-plus"></i>');?></li>
				<li class=""><?php echo anchor("event/past", 'Past Events');?></li>
				<li class="active"><?php echo anchor("event", 'Upcoming Events');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
		<h2 class="">Upcoming Events / <?php echo $event['title']; ?> </h2>
		<p>This is where you can modify upcoming event details, view registrations, and manage event activities. You can view a list of all upcoming events and change events by clicking on the event title above.</p>
		<div class="clear"></div>
		<ul id="detailstabs" class="teal">
			<li class=""><?php echo anchor("event/".$event['id']."/details", 'Details &amp; Dates');?></li>
			<li class=""><?php echo anchor("event/".$event['id']."/sessions", 'Sessions');?></li>
			<li class=""><?php echo anchor("event/".$event['id']."/options", 'Starters');?></li>
			<li class=""><?php echo anchor("event/".$event['id']."/custom", 'Options &amp; Discounts');?></li>
   			<li class="active"><?php echo anchor("event/".$event['id']."/classes", 'Classes');?></li>
			<li class=""><?php echo anchor("event/".$event['id']."/registrations", 'Registrations');?></li>
			<li class=""><?php echo anchor("event/".$event['id']."/message", 'Message');?></li>
		</ul>	
	</article>
	<article class="textsection">
	<?php echo form_open(uri_string());?>
	<input type="hidden" name="id" value="<?php echo $event['id']; ?>" /> 
		<div class="container">
			<div class="quarter">
				<h2>Class Rosters</h2>
				<p>View each sessions' roster for this class, and modify regs. </p>
				<p><?php echo anchor("event/".$event['id']."/classes", '&larr; All Classes', 'class="btn tan"');?></p>
				<div class="clear"></div>
			</div>
			<div class="threequarter">
				<h2><?php echo $class['title']; ?> Rosters for <?php echo $event['title']; ?></h2>
				<p>These are the rosters for each session.</p>
				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
				<p><?php echo $activity['description']; ?>
				   		<br><br>
				   		<strong>Cost/Supplies:</strong> <?php echo ($class['preorder'] == '0') ? 'None': '$'.$class['preorder']; ?>
				   		<br>
				   		<strong>Location:</strong> <?php echo $class['location']; ?>
				   		<br>
				   		<strong>Limits:</strong> <?php if ($class['limit'] == '0') { echo 'None'; } elseif ($class['limit'] !== '0' && $class['hardlimit'] == '0') { echo $class['limit']; } else { echo $class['limit']; ?> / <strong><?php echo $class['hardlimit']; ?></strong><?php } ?>
				   		<br>
				   		<strong>Blocks:</strong> <?php $class['blocks'] = unserialize($class['blocks']); if (empty($class['blocks'])) { echo 'None'; } else { $j=1; foreach ($class['blocks'] as $class['__block']) { if ($j>1) echo ', '; $j++; echo $class['__block']; } } ?>
				</p>
				<?php $i=1; foreach ($sessions as $session) { ?>
					<h3><?php echo (empty($session['title'])) ? $event['sessiontitle'].' '.$i : $session['title']; ?></h3>
					<?php //echo $this->activities_model->count_class_regs($session['id'],$class['id']); ?>
					<?php //if ($class['limit'] == '0' && $class['hardlimit'] == '0') { echo '<td colspan=\'2\'>No limit</td>'; } elseif ($class['limit'] !== '0' && $class['hardlimit'] == '0') { echo '<td>'.$class['limit'].'</td><td>-</td>'; } elseif ($class['limit'] == '0' && $class['hardlimit'] !== '0') { echo '<td>-</td><td>'.$class['hardlimit'].'</td>'; } else { echo '<td>'.$class['limit'].'</td><td>'.$class['hardlimit'].'</td>'; } ?>
					<table class='table table-condensed'>
						<thead><th>Spot</th><th>Name</th><th>Unit</th><th>Council</th><th>Date</th><th>Tools</th></thead>
						<tbody>
							<?php $j=1; foreach ($regs as $reg) { if ($reg['session'] != $session['id']) continue; ?>
								<tr>
									<td><?php echo $j; ?></td>
									<td><?php echo $reg['member']['name']; ?></td>
									<td><?php echo $reg['member']['unit']['unittype'].' '.$reg['member']['unit']['number'].' ('.$reg['member']['unit']['city'].', '.$reg['member']['unit']['state'].')'; ?></td>
									<td><?php echo $reg['member']['unit']['council']; ?></td>
									<td><?php echo date("F j, Y, g:i a", $reg['time']); ?></td>
									<td>-</td>
								</tr>
								<?php if ($class['hardlimit'] == $j) echo '<tr class="warning"><td colspan="6"><strong>Hard Limit</strong> ('.$j.' spots)</td></tr>'; ?>
								<?php if ($class['limit'] == $j) echo '<tr class="warning"><td colspan="6"><strong>Soft Limit</strong> ('.$j.' spots)</td></tr>'; ?>
							<?php $j++; } ?>
						</tbody>
					</table>
				<?php $i++; } ?>

				<div class="clear"></div>
			</div>
		</div>
		<div class="clear"></div>
	<?php echo form_close();?> 
	</article>
