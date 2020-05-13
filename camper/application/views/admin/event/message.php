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
	$(document).ready(function() {
		$('#fmessage').wysihtml5({
			"font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
			"emphasis": true, //Italics, bold, etc. Default true
			"lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
			"html": true, //Button which allows you to edit the generated HTML. Default false
			"link": false, //Button to insert a link. Default true
			"image": false, //Button to insert an image. Default true,
			"color": false //Button to change color of font  
		});
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
			<li class=""><?php echo anchor("event/".$event['id']."/registrations", 'Registrations');?></li>
			<li class=""><?php echo anchor("event/".$event['id']."/details", 'Details &amp; Dates');?></li>
			<li class=""><?php echo anchor("event/".$event['id']."/sessions", 'Sessions');?></li>
			<li class=""><?php echo anchor("event/".$event['id']."/options", 'Starters');?></li>
			<li class=""><?php echo anchor("event/".$event['id']."/custom", 'Options &amp; Discounts');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/classes", 'Classes');?></li>
				<li class="active"><?php echo anchor("event/".$event['id']."/message", 'Message');?></li>
		</ul>	
	</article>
	<article class="textsection">
	<?php echo form_open(uri_string());?>
	<?php echo form_hidden($csrf); ?>
	<input type="hidden" name="id" value="<?php echo $event['id']; ?>" /> 
		<div class="container">
			<?php if ($send_success) { ?>
			<h2>Message Sent</h2>
			<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
			<p>We sent your message via email to all of the leaders in this event. <?php echo anchor('event/'.$event['id'].'/message', 'Need to send another?'); ?></p>
			<?php } else { ?>
			<div class="quarter">
				<h2>Message Leaders</h2>
				<p>You can message via email all of the leaders signed up for this event. This will include all leaders for all sessions.</p>
				<p>Make sure you proofread your message, it will go out as is.</p>
				<input type="submit" name="submit" value="Send" class="btn teal camperhoverpopover" data-toggle="popover" title="Send Message" data-placement="top" data-content="You are about to send your message, when you click, the message will be sent to everyone signed up for this event. <br /><br />Please proofread your message before sending." data-loading-text="Sending the message..." onclick="$(this).button('loading');" /><br /><input type="reset" name="reset" value="Start over" class="btn tan"  />	
				<div class="clear"></div>
			</div>
			<div class="threequarter">
				<h2>New Message</h2>
				<p>Sending a new email message to leaders is simple. Choose a subject, type out your message, and hit send. We'll send you a copy and set it so you get the replies.</p>
				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
					<div class="camperform float last" style="width: 70%"><input type="text" name="title" id="ftitle" value="<?php echo $this->input->post('title'); ?>" placeholder="2014 Summer Camp at BDSR" data-toggle="tooltip" title="Event title" /><label for="ftitle">Subject</label></div>
				<div class="clear"></div>
			<textarea id="fmessage" class="wysiwyg" name="message" placeholder="Start your message here..."><?php echo $this->input->post('message'); ?></textarea>
			</div>
			<?php } ?>
		</div>
		<div class="clear"></div>
	<?php echo form_close();?> 
	</article>
