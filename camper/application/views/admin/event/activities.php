<?php 

/* 
 * Camper My Unit / Members View
 *
 * This is the roster view of the "My Unit" section in camper. 
 *
 * File: /application/views/myunit/details.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

?>	<script type="text/javascript" charset="utf-8">
		$.extend( $.fn.dataTableExt.oStdClasses, {
			"sSortAsc": "header headerSortDown",
			"sSortDesc": "header headerSortUp",
			"sSortable": "header"
		});
		$(document).ready(function() {
			oTable = $('.datatables').dataTable( {
				"sDom": "<r>t<''<'left'i><'left'p><'right'l>>",
				"oLanguage": {
					"sInfo": "_START_ through _END_ of _TOTAL_ activities"
				}
			});
		});
	</script>

	<div class="subnav">
		<div class="container">
			<h2>Events</h2>
			<nav class="campersubnav">
				<li class="" data-toggle="tooltip" title="New Activity"><?php echo anchor("event/activities/new", '<i class="icon-plus"></i>');?></li>
				<li class="active"><?php echo anchor("event/activities", 'Activity Library');?></li>
				<li class="" data-toggle="tooltip" title="New Event"><?php echo anchor("event/new", '<i class="icon-plus"></i>');?></li>
				<li class=""><?php echo anchor("event/past", 'Past Events');?></li>
				<li class=""><?php echo anchor("event", 'Upcoming Events');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
	<?php echo form_open(uri_string());?>
		<div class="container">
			<h2>Activity Library</h2>
			<p>Activities are the heart of Camper's activity registration and at camp system. They are the base activities that you will use to set up classes, courses, and other activities at events for online registration. You only need to make an activity once for it to be used in multiple events.</p>
			<p>You can see all of the activities grouped by event type, sorted by category. Click on any activity to view/edit it's details.</p>
			<p><?php echo anchor('event/activities/new', '<i class="icon-plus"></i> Add an activity', 'class="btn tan"'); ?></p>
			<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
			<div class="clear"></div>
				<ul id="detailstabs" class="teal">
				<li class="active"><a href="#activitiessummer" data-toggle="tab">Summer Camp</a></li>
				<li class=""><a href="#activitiesdistrict" data-toggle="tab">District Event</a></li>
				<li class=""><a href="#activitiesweekend" data-toggle="tab">Weekend Event</a></li>
				<li class=""><a href="#activitiestraining" data-toggle="tab">Training</a></li>
				<li class=""><a href="#activitiesother" data-toggle="tab">Other</a></li>
			</ul>
			<div class="tab-content">
			<?php // Loop the eventtypes
				$et = array(
					array('Summer Camp','summer'),
					array('District Event','district'),
					array('Weekend Event','weekend'),
					array('Training','training'),
					array('Other Event','other')
				);
				$i=1;
				foreach ($et as $t) : 
			?>
				<div class="tab-pane fade<?php if ($i==1) echo " in active"; ?>" id="activities<?php echo $t[1]; ?>">
			  		<table class="table table-condensed datatables">
			  			<thead>
			  				<tr><th>Activity</th><th>Description</th><th>Category</th><th>Pre Requisites</th><th>Minimum Age</th><th>Tools</th></tr>
			  			</thead>
			  			<tbody>
			  			<?php // Get and loop our activities
			  				$activities = $this->activities_model->get_activities($t[0], 'eventtype');
			  				if (!empty($activities)) :
			  				//$now = time();
				  			//$adult = ($unit['unittype'] == 'Ship' || $unit['unittype'] == 'Crew') ? (31556926 * 21): (31556926 * 18); // 21 and 18 years in seconds
				  			foreach ($activities as $activity) : 
				  			//if ($now-$member['dob'] >= $adult) continue; 
							$excerpt = implode(" ", array_splice(explode(" ", $activity['description']), 0, 5)).'...';
			  			?>
							<tr>
							<td><?php echo anchor('event/activities/'.$activity['id'], $activity['title']); ?></td>
							<td><span class="camperhoverpopover" data-toggle="popover" title="Description" data-placement="top" data-content="<?php echo $activity['description']; ?>"><?php echo $excerpt; ?></span></td>
							<td><?php echo $activity['category']; ?></td>
							<td><?php if (isset($activity['short']) || isset($activity['long'])) { ?><span class="camperhoverpopover" data-toggle="popover" title="Prerequisites" data-placement="top" data-content="<strong>Short Description</strong><br /><?php echo (isset($activity['short'])) ? $activity['short'] : '-'; ?><br /><strong>Long Description</strong><br /><?php echo (isset($activity['long'])) ? $activity['long'] : '-'; ?>">Yes</span><?php } else { echo 'None'; } ?></td>
							<td><?php echo (!isset($activity['age'])) ? 'None': $activity['age']; ?></td>
							<td><?php echo anchor('event/activities/'.$activity['id'], '<i class="icon-pencil"></i> Edit', 'class="btn btn-small tan"'); ?> <a data-toggle="popover" title="Delete" data-placement="top" data-content="Are you sure you want to delete this activity? <br /><br />When you delete this member, all linked event activities and activity regs will be deleted as well. <strong>You may want to delete an event activity in the event you are managing</strong>.<br /><br /><?php echo str_replace('"', "'", anchor('event/activities/'.$activity['id'].'/delete', 'Delete '.$activity['title'], 'class="btn red"')); ?>" class="btn btn-small red camperpopover"><i class="icon-remove"></i></a>
							</tr>
				  		<?php endforeach; endif; ?>
			  			</tbody>
			  		</table>
				</div>
				<?php $i++; endforeach; ?>
			</div>
		</div>
		<div class="clear"></div>
		<?php echo form_close();?> 
	</article>
