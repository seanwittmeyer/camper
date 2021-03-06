<?php 

/* 
 * Camper My Unit / New Member View
 *
 * This is the roster view of the "My Unit" section in camper. 
 *
 * File: /application/views/myunit/details.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
?>	<script>
	$(document).ready(function() {
		$('.catlist.typeahead').typeahead({                              
		  limit: '10',                                                        
		  prefetch: '<?php echo $this->config->item('camper_path'); ?>api/v1/categories.json', 
		  header: '<p class="typeaheadtitle">Click on an existing category or create a new one.</p>',
		  template: [
		    '<p class="typeahead-name">{{value}}</p>',
		  ].join(''),                                                                 
		  engine: Hogan                                                               
		});
		$('.camperpopover').popover({html:true});
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
			<div class="quarter">
				<h2>Activity Library</h2>
				<p>Manage the activities in Camper for all events here in the activity library.</p>
				<p><?php echo anchor('/event/activities', '&larr; All Activities', 'class="btn tan"'); ?> <?php echo anchor('/event/activities/new', '<i class="icon-puls-sign"></i> New', 'class="btn teal"'); ?></p>
				<div class="clear"></div>
			</div>
			<div class="threequarter">
				<h2 class=""><?php echo $activity['title']; ?></h2>
				<p>You can manage some of <?php echo $activity['title']; ?>'s details here. When you create an activity in Camper, you create a base activity with details that apply to many events. If you want to add this activity to an event for registration, or edit preorder costs, limits, location, and other <i>event-specific</i> details, then head to the event you are managing, then click on the activities tab.</p>
				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
				<div class="clear"></div>

				<div class="camperform float " style="width: 70%"><input type="text" name="activity[title]" id="ftitle" value="<?php echo $activity['title']; ?>" placeholder="First Aid" data-toggle="tooltip" title="Choose an activity name. If it is a merit badge, you don't need to include 'merit badge' after the title." /><label for="ftitle">Title</label></div>
				<div class="clear"></div>
				<div class="camperform float" style="width: 96%"><input type="text" name="activity[description]" id="fdesc" value="<?php echo $activity['description']; ?>" placeholder="Description" data-toggle="tooltip" title="Give a brief and general description about this activity or merit badge." /><label for="fdesc">Description</label></div>
				<div class="clear"></div>
				<div class="camperform float " style="width: 140px">
					<select id="ftype" name="activity[eventtype]" data-toggle="tooltip" title="Which type of event is this activity usually a part of?">
						<option value="<?php echo $activity['eventtype']; ?>"><?php echo $activity['eventtype']; ?></option>
						<optgroup label="Event Types">
							<option value="Summer Camp">Summer Camp</option>
							<option value="District Event">District Event</option>
							<option value="Weekend Event">Weekend Event</option>
							<option value="Training">Training</option>
							<option value="Other Event">Other Event</option>
						</optgroup>
					</select>
					<label for="ftype">Event Type</label>
				</div>
				<div class="camperform float " style="width: 150px">
					<select id="fage" name="activity[age]">
						<option value="<?php echo $activity['age']; ?>"><?php echo $activity['age']; ?></option>
						<optgroup label="Event Types">
							<option value="">No Minimum Age</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
						</optgroup>
					</select>
					<label for="fage">Minimum Age</label>
				</div>
				<div class="camperform float " style="width: 300px;"><input type="text" name="activity[category]" id="fcat" class="catlist typeahead" placeholder="Scoutcraft" value="<?php echo $activity['category']; ?>" data-toggle="tooltip" title="Add a category for the activity" /><label for="fcat">Category</label></div>
				<div class="clear"></div>
       			<div class="camperform float cbl" style="" ><input type="checkbox" class="cbl" name="activity[meritbadge]" id="fmeritbadge"<?php if($activity['meritbadge'] == '1') { ?> checked="checked"<?php } ?> /><label for="fmeritbadge" class="cbl">Merit Badge</label></div>
				<div class="clear"></div>
				<p>Add prerequisite information below. The short pre-req field will be displayed on the registration page and in places where a short and sweet description is required. You can go in depth in the long pre-req field, which will be displayed on printable schedules and other places where there is space for these longer details.
				<div class="clear"></div>
				<div class="camperform float" style="width: 96%"><input type="text" name="activity[short]" id="fshort" value="<?php echo $activity['short']; ?>" placeholder="CPR/AED course must be completed (#6c), bring first aid kit (#2d)" data-toggle="tooltip" title="In one sentence, explain the prerequisites, if any" /><label for="fdesc">Pre-Requisites - Short Version</label></div>
				<div class="camperform float" style="width: 96%"><input type="text" name="activity[long]" id="flong" value="<?php echo $activity['long']; ?>" placeholder="In order to complete the merit badge at camp, you need to first..." data-toggle="tooltip" title="Explain the prerequisites, if any" /><label for="flong">Pre-Requisites - Long Version</label></div>
				<div class="clear"></div>
				<p><input type="submit" name="submit" value="Save Changes" class="btn teal" data-loading-text="Saving changes..." onclick="$(this).button('loading');" /> <input type="reset" name="reset" value="Reset" class="btn tan"  /> <span class="right"><?php echo anchor('event/activities/'.$activity['id'].'/delete', 'Delete This Activity', 'class="btn red camperhoverpopover" data-toggle="popover" title="Delete" data-placement="top" data-content="Are you sure you want to delete this activity? <br /><br />When you delete this member, all linked event activities and activity regs will be deleted as well. <strong>You may want to delete an event activity in the event you are managing</strong>."'); ?></span></p>


			</div>
		</div>
		<div class="clear"></div>
	<?php echo form_close();?> 
	</article>
