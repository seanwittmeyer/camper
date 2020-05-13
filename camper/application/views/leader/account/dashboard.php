<?php 

/* 
 * This is a test.
 *
 * File: /application/views/admin/dashboard/admin.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

?>
	<article class="textsection">
    	<div class="container">
			<h1 class="center">Welcome to Camper!</h1>
			<div class="clear"></div>
			<div class="quarter">&nbsp;</div>
			<div class="half"><p class="center">The Longs Peak Council uses the Camper Registration System to allow units and scouters to register for camp and activities run by the Longs Peak Council and it's districts. Camper is split in to 3 main sections, you can explore the sections below.</p></div>
			<div class="quarter last">&nbsp;</div>
			<div class="clear"></div>
    	</div>
    	<div class="container">
			<div class="clear"></div>
			<div class="quarter center">
				<h2><i class="icon-user"></i></h2>
				<?php if ($this->shared->is_individual()) { ?>
				<h2>My Account</h2>
				<p>Manage your Camper account and your unit details in the My Account section. Since you have an individual account, you don't need to maintain any unit like full accounts.</p>
				<p><?php echo anchor("me", 'My Account &rarr;', 'class="btn blue"'); ?></p>
				<?php } else { ?>
				<h2>My Unit</h2>
				<p>Manage your Camper account and your unit in the My Unit section. Unit primary contacts have the additional ability to manage unit contacts!</p>
				<p><?php echo anchor("unit", 'My Unit &rarr;', 'class="btn blue"'); ?></p>
				<?php } ?>
			</div>
			<div class="quarter center">
				<h2><i class="icon-calendar"></i></h2>
				<h2>Events</h2>
				<p>Register with one click and get back to having Scouting fun in the outdoors! Events will eventually include a variety of events from camporees to trainings.</p>
				<p><?php echo anchor("events/all", 'All Upcoming Events &rarr;', 'class="btn blue"');?></p>
			</div>
			<div class="quarter center">
				<h2><i class="icon-ok"></i></h2>
				<h2>Registrations</h2>
				<p>Easily manage discounts and options, preorder merit badge/activity supplies and even register individual scouts and adults for activities.</p>
				<p><?php echo anchor("registrations", 'Registrations &rarr;', 'class="btn blue"');?></p>
			</div>
			<div class="quarter center last">
				<h2><i class="icon-question"></i></h2>
				<h2>Getting Help</h2>
				<p>Camper has a help and FAQ page that is constantly growing, and if you can't find what you are looking for, we are only a quick phone call away with help!</p>
				<p><?php echo anchor("help", 'Help Center &rarr;', 'class="btn tan"');?></p>
			</div>
			<div class="clear"></div>
			<h2>Coming Soon</h2>
			<p>Camper is a brand new system developed by the Longs Peak Council designed to make registration as easy and fun as possible. Since we are just getting started, Camper may seem to be missing features. Be patient and let us know when something could be better, and check back often to check out what's new.</p>
			<p>The biggest features coming this winter include a new dashboard (this page), online payments, event preorders (for summer-camp merit badge supplies, etc), activity and merit badge registration for individual scouts (including an easy to use schedule builder), and notifications for when anything changes (be the first to know when an event you are registered for changes).</p>
			<h4>How Can I Help?</h4>
			<p>The whole project started with the feedback of our council's leaders and that is what you can help us out with. See something that doesn't look right or find a bug? You can help by clicking on the "<i>Is there anything wrong with this page?</i>" link at the bottom of any page and let us know!. Constructive feedback is best and will help us grow Camper into the best option for event registration.</p>
    	</div>

	</article>
