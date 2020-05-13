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

?>
	<div class="subnav">
		<div class="container">
			<h2>Events</h2>
			<nav class="campersubnav">
				<li class="" data-toggle="tooltip" title="New Activity"><?php echo anchor("event/activities/new", '<i class="icon-plus"></i>');?></li>
				<li class=""><?php echo anchor("event/activities", 'Activity Library');?></li>
				<li class="active" data-toggle="tooltip" title="New Event"><?php echo anchor("event/new", '<i class="icon-plus"></i>');?></li>
				<li class=""><?php echo anchor("event/past", 'Past Events');?></li>
				<li class=""><?php echo anchor("event", 'Upcoming Events');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
   	    <h2>New Event</h2>
   	    <p>It's simple to add an event to camper. Simply fill in the basic set of details below and then you will be taken to the events section where you can open the event for registration, add sessions/weeks, configure costs, discounts and options and view registrations.</p>
		<div class="clear"></div>
	</article>
	<article class="textsection">
	<?php echo form_open(uri_string());?>
	<input type="hidden" name="id" value="0" /> 

   		<div class="container">
       		<div class="quarter">
   	    		<h2>The Basics</h2>
   	    		<p>These are the basic details about the event, everything here is required. You can enable multiple unit types or just one.</p>
   	    		<input type="submit" name="submit" value="Add Event" class="btn teal" data-loading-text="Adding event..." onclick="$(this).button('loading');" /> <input type="reset" name="reset" value="Reset" class="btn tan"  />	
   	    		<div class="clear"></div>
       		</div>
       		<div class="threequarter">
   				<div class="camperform float last" style="width: 70%"><input type="text" name="title" id="ftitle" value="" placeholder="2014 Summer Camp at BDSR" data-toggle="tooltip" title="Event title" /><label for="ftitle">Title</label></div>
   				<div class="clear"></div>
   				<div class="camperform float" style="width: 96%"><input type="text" name="description" id="fdesc" value="" placeholder="Description" data-toggle="tooltip" title="Event description, 2 sentences max" /><label for="fdesc">Description</label></div>
   				<div class="clear"></div>
   				<div class="camperform float " style="width: 50%"><input type="text" name="location" value="" id="flocation" placeholder="BDSR, Camp Jeffrey" data-toggle="tooltip" title="The location of the event" /><label for="flocation">Location</label></div>
   				<div class="camperform float " style="width: 20%">
   					<select id="ftype" name="eventtype">
						<option value="Summer Camp">Summer Camp</option>
						<option value="District Event">District Event</option>
						<option value="Weekend Event">Weekend Event</option>
						<option value="Training">Training</option>
						<option value="Other Event">Other Event</option>
					</select>
					<label for="ftype">Event Type</label>
				</div>
   				<div class="clear"></div>
   				<div class="camperform float " style="width: 30%"><input type="text" name="datestart" class="datepicker" id="fstart" value="<?php echo date('F d, Y'); ?>" placeholder="June 15, 2013" data-toggle="tooltip" title="Event begin date, this is the very first day of the event" /><label for="fstart">Beginning Date</label></div>
   				<div class="camperform float " style="width: 30%"><input type="text" name="dateend" class="datepicker" id="fend" value="<?php echo date('F d, Y'); ?>" placeholder="July 15, 2013" data-toggle="tooltip" title="Event end date, this should be the very last day of the event" /><label for="fend">End Date</label></div>
   				<div class="clear"></div>
   				<?php $t='Troops'; ?><div class="camperform float cbl" style="width: 85px;"><input type="checkbox" class="cbl" value="<?php echo $t; ?>" name="ftype[]" id="fttroops" /><label for="fttroops" class="cbl" >Troops</label></div>
   				<?php $t='Crews'; ?><div class="camperform float cbl" style="width: 135px;"><input type="checkbox" class="cbl" value="<?php echo $t; ?>" name="ftype[]" id="ftcrews" /><label for="ftcrews" class="cbl" >Crews/Ships</label></div>
   				<?php $t='Packs'; ?><div class="camperform float cbl" style="width: 80px;"><input type="checkbox" class="cbl" value="<?php echo $t; ?>" name="ftype[]" id="ftpacks" /><label for="ftpacks" class="cbl" >Packs</label></div>
   				<?php $t='Dens'; ?><div class="camperform float cbl" style="width: 130px;"><input type="checkbox" class="cbl" value="<?php echo $t; ?>" name="ftype[]" id="ftdens" /><label for="ftdens" class="cbl" >Packs/Dens</label></div>
   				<?php $t='Individuals'; ?><div class="camperform float cbl" style="width: 120px;"><input type="checkbox" class="cbl" value="<?php echo $t; ?>" name="ftype[]" id="ftindividuals" /><label for="ftindividuals" class="cbl" >Individuals</label></div>
   			</div>
   		</div>
   		<div class="clear"></div>
   	<?php echo form_close();?> 
	</article>
