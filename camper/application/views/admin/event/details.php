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
 

?>	<script>
	// Setup the wysiwyg editor
	$(document).ready(function() {
		$('#fnotes').wysihtml5({
			"font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
			"emphasis": true, //Italics, bold, etc. Default true
			"lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
			"html": true, //Button which allows you to edit the generated HTML. Default false
			"link": false, //Button to insert a link. Default true
			"image": false, //Button to insert an image. Default true,
			"color": false //Button to change color of font  
		});
	});
	// Invites Functions
    function open_event(element) {
    	$.ajax({
    		url: "/camper/api/v1/event/open?e=<?php echo $event['id']; ?>",
    		type: 'GET',
    		beforeSend: function() {
    			$(element).text('Opening...');
    		},
    		statusCode: {
    			200: function() {
    			$(element).html('<i class="icon-ok"></i> Event opened');
	    		$(element).removeClass('teal').addClass('tan').attr('disabled','disabled').removeAttr('onclick');
	    		$('#ocmessage').html('<i class="icon-ok teal"></i> This event is open for registrations');
    			},
    			304: function() {
    			$(element).text('Open event failed, retry?');
    			$(element).removeClass('teal').addClass('tan');
    			}
    		}
    	});
    }
    function close_event(element) {
    	$.ajax({
    		url: "/camper/api/v1/event/close?e=<?php echo $event['id']; ?>",
    		type: 'GET',
    		beforeSend: function() {
    			$(element).text('Closing...');
    		},
    		statusCode: {
    			200: function() {
    			$(element).html('<i class="icon-ok"></i> Event closed');
	    		$(element).removeClass('red').addClass('tan').attr('disabled','disabled').removeAttr('onclick');
	    		$('#ocmessage').html('<i class="icon-remove icon-large red" ></i> This event <b>not</b> open for registrations.');
    			},
    			304: function() {
    			$(element).text('Close event failed, retry?');
    			$(element).removeClass('red').addClass('tan');
    			}
    		}
    	});
    }
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
   			<li class=""><?php echo anchor("event/".$event['id']."/registrations", 'Registrations');?></li>
   			<li class="active"><?php echo anchor("event/".$event['id']."/details", 'Details &amp; Dates');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/sessions", 'Sessions');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/options", 'Starters');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/custom", 'Options &amp; Discounts');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/classes", 'Classes');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/message", 'Message');?></li>
   		</ul>
	</article>
	<?php echo form_open(uri_string());?>
	<input type="hidden" name="id" value="<?php echo $event['id']; ?>" /> 
	<article class="textsection">
   		<div class="container">
   			<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
       		<div class="quarter">
   	    		<h2>Event Details</h2>
   	    		<p>These are the basic details about the event, everything here is required. </p>
   	    		<p><input type="submit" name="submit" value="Save Changes" class="btn teal" data-loading-text="Saving event..." onclick="$(this).button('loading');" />	<input type="reset" name="reset" value="Reset" class="btn tan" /></p>
   	    		<p id="ocmessage" class="left"><?php echo ($event['open'] == '0') ? '<i class="icon-remove icon-large red" ></i> This event <b>not</b> open for registrations.' : '<i class="icon-ok teal"></i> This event is open for registrations'; ?>
   	    		<div class="clear"></div>
   	    		<p><?php echo ($event['open'] == '0') ? '<a href="#" class="btn teal" data-loading-text="Opening event..." onclick="open_event(this); return false;">Open Event</a>' : '<a href="#" class="btn red" data-loading-text="Closing event..." onclick="close_event(this); return false;">Close Event</a>'; ?></p>
   	    		<div class="clear"></div>
   	    		<!--<p><a href="#cancelevent" class="btn red" role="button" data-toggle="modal" ><i class="icon-trash"></i> Cancel Event</a></p>-->
   	    		<div class="clear"></div>
       		</div>
       		<div class="threequarter">
   	    		<h2>The Basics</h2>
   				<div class="camperform float last" style="width: 70%"><input type="text" name="title" id="ftitle" value="<?php echo $event['title']; ?>" placeholder="2014 Summer Camp at BDSR" data-toggle="tooltip" title="Event title" /><label for="ftitle">Title</label></div>
   				<div class="clear"></div>
   				<div class="camperform float" style="width: 96%"><input type="text" name="description" id="fdesc" value="<?php echo $event['description']; ?>" placeholder="Description" data-toggle="tooltip" title="Event description, 2 sentences max" /><label for="fdesc">Description</label></div>
   				<div class="clear"></div>
   				<div class="camperform float " style="width: 50%"><input type="text" name="location" id="flocation" value="<?php echo $event['location']; ?>" placeholder="BDSR, Camp Jeffrey" data-toggle="tooltip" title="The location of the event" /><label for="flocation">Location</label></div>
   				<div class="camperform float " style="width: 20%">
   					<select id="ftype" name="eventtype" data-toggle="tooltip" title="Select the type of this event">
						<option value="<?php echo $event['eventtype']; ?>"><?php echo $event['eventtype']; ?></option>
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
   				<div class="clear"></div>
   				<div class="camperform float " style="width: 30%"><input type="text" name="datestart" id="fstart" class="datepicker" value="<?php echo date('F d, Y', $event['datestart']); ?>" placeholder="June 15, 2013" data-toggle="tooltip" title="Event begin date, this is the very first day of the event" /><label for="fstart">Beginning Date</label></div>
   				<div class="camperform float " style="width: 30%"><input type="text" name="dateend" id="fend" class="datepicker" value="<?php if ($event['dateend']) { echo date('F d, Y', $event['dateend']); } ?>" placeholder="none" data-toggle="tooltip" title="Event end date, this should be the very last day of the event. If a one day event, set this as the beginning day." /><label for="fend">End Date</label></div>
   				<div class="clear"></div>
   				<?php $eligibleunits = $event['eligibleunits']; $ftype=false; foreach ($eligibleunits as $single) { $ftype[$single] = true; } ?>
   				<?php $t='Troops'; ?><div class="camperform float cbl" style="width: 85px;"><input type="checkbox" class="cbl" value="<?php echo $t; ?>" <?php if(isset($ftype[$t])) { ?> checked="checked"<?php } ?> name="ftype[]" id="fttroops" /><label for="fttroops" class="cbl" >Troops</label></div>
   				<?php $t='Crews'; ?><div class="camperform float cbl" style="width: 135px;"><input type="checkbox" class="cbl" value="<?php echo $t; ?>" <?php if(isset($ftype[$t])) { ?> checked="checked"<?php } ?> name="ftype[]" id="ftcrews" /><label for="ftcrews" class="cbl" >Crews/Ships</label></div>
   				<?php $t='Packs'; ?><div class="camperform float cbl" style="width: 80px;"><input type="checkbox" class="cbl" value="<?php echo $t; ?>" <?php if(isset($ftype[$t])) { ?> checked="checked"<?php } ?> name="ftype[]" id="ftpacks" /><label for="ftpacks" class="cbl" >Packs</label></div>
   				<?php $t='Dens'; ?><div class="camperform float cbl" style="width: 130px;"><input type="checkbox" class="cbl" value="<?php echo $t; ?>" <?php if(isset($ftype[$t])) { ?> checked="checked"<?php } ?> name="ftype[]" id="ftdens" /><label for="ftdens" class="cbl" >Packs/Dens</label></div>
   				<?php $t='Individuals'; ?><div class="camperform float cbl" style="width: 120px;"><input type="checkbox" class="cbl" value="<?php echo $t; ?>" <?php if(isset($ftype[$t])) { ?> checked="checked"<?php } ?> name="ftype[]" id="ftindividuals" /><label for="ftindividuals" class="cbl" >Individuals</label></div>
   				<div class="clear"></div>
   			</div>
   		</div>
   	</article>
	<article class="textsection">
   		<div class="container">
			<div class="clear hr"></div>
       		<div class="quarter">
   	    		<h2>Resources</h2>
   	    		<p>Set messages that leaders will see when registering or when in a registration. Files/uploads will be added here as well.</p>
   	    		<p><input type="submit" name="submit" value="Save Changes" class="btn teal" data-loading-text="Saving event..." onclick="$(this).button('loading');" />	<input type="reset" name="reset" value="Reset" class="btn tan" /></p>
       		</div>
       		<div class="threequarter">
       			<h2>Messages</h2>
       			<p>Set a registration message that will be displayed when a leader is starting a registration. This will be publicly visible, and can include instructions specific to this event.</p>
   				<div class="camperform float" style="width: 96%"><input type="text" name="registermessage" id="fregistermessage" value="<?php echo $event['registermessage']; ?>" placeholder="A message to show at registration" data-toggle="tooltip" title="Add any notes to people, this will be shown as people register for this event. It can include any custom instructions." /><label for="fregistermessage">Registration Message</label></div>
   				<div class="clear"></div>
   				<p>You can also include notes or information for units and people already registered. This will show in their registration section under "event details".</p>
   				<div class="camperform " style="width: 96%"><textarea class="wysiwyg" name="notes" id="fnotes" placeholder="Add any details or information for leaders who are registered for this event."><?php echo $event['notes']; ?></textarea></div>
   				<div class="clear"></div>
       			<h2>Event Resources</h2>
       			<p>You will be able to add files and resources only accessible to leaders here. You can upload any .doc or .pdf file here for leaders to access with regards to their registration.</p>
       			<p>Files are in the works and will be launched summer 2014.</p>
       		</div>
   		<div class="clear"></div>
   	</article>
   	<?php echo form_close();?> 
	<article class="content">
	<!-- Change Alternate Modal -->
	<div id="cancelevent" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<?php echo form_open("unit/change_contact"); ?>
   		<div class="container">
   			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
       		<div class="pull">
   	    		<h2 class="pull">Cancel Event</h2>
   	    		<p>You can cancel this event here. Read the details here carefully as many things happen when you cancel an event.</p>
   	    		<div class="clear"></div>
       		</div>
       		<div class="tab-content inner-push">
   				<h2 class="section">Cancel <?php echo $event['title']; ?> </h2>
   				<p>Once events have registrations, events can not be deleted. Camper allows you to cancel events instead. Canceling an event saves the event as a "cancelled event" in the system which does the following:</p>
   				<ol>
   					<li>Closes the event preventing new signups</li>
   					<li>Locks existing registrations</li>
   					<li>Refunds pending payments</li>
   					<li>Prepares a refund report e-mailed to administrators</li>
   					<li>Sends a notification (including the notice field below) to all registrants with details</li>
   				</ol>
   				<p></p>
   				<p>Canceling events on Camper has been designed to make sure everyone stays in the loop and allows for open communication about why the event was cancelled. You must fill in the notice field when canceling events.</p>
   				<p>If the event is rescheduled or should be reopened, you can reactivate an event. This sends notifications to everyone who previously signed up and invites them to confirm their registration for the event. You will have the opportunity to make changes to the date before you reopen it, including dates, titles, sessions and fees.
   					
   				<div class="camperform float last" style="width: 660px"><textarea name="cancelnotice" id="fstart" class="" placeholder="The event was canceled because..." data-toggle="tooltip" title="Event begin date, this is the very first day of the event"></textarea><label for="fstart">Cancelation Notice</label></div>
	   			<div class="clear"></div><input type="submit" name="submit" value="Cancel Event" class="btn red"  /> <button class="btn tan" data-dismiss="modal" aria-hidden="true">Nevermind</button>
   			</div>
   		</div>
   		<div class="clear"></div>
   		<?php echo form_close();?>
	</div>
   	<!-- End Modal -->
	</article>

