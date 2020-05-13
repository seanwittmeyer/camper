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

?>	<div id="help">
		<div class="container">
			<div class="quarter">
				<h2>How can we help?</h2>
				<p>Choose the section you want to learn about to see help topics. Contact us if you still need a hand.</p>
				<p><i class="icon icon-phone"></i> <?php echo $this->config->item('camper_supportphone'); ?><br /><i class="icon icon-envelope"></i> Registration Questions: <?php echo $this->config->item('camper_supportemail'); ?><br /><i class="icon icon-envelope-alt"></i> Site Issues: <?php echo $this->config->item('camper_fromemail'); ?></p>
			</div>
			<div class="threequarter last">
				<button class="btn btn-link" style="display: block; position: absolute; right: 60px; z-index:1000; " onclick="$('#help').slideToggle(100); return false; "><i class="icon icon-remove"></i> Close</button>
				<ul class="nav nav-tabs" id="myTab">
					<li class="active"><a data-toggle="tab" href="#help-basics">The Basics</a></li>
					<li><a data-toggle="tab" href="#help-details">Unit Contact Info</a></li>
					<li><a data-toggle="tab" href="#help-users">Primary Contact</a></li>
					<li><a data-toggle="tab" href="#help-contacts">Alternate Contacts</a></li>
				</ul>
				 
				<div class="tab-content">
					<div class="tab-pane active fade in" id="help-basics">
						<p>This is the home tab. Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui</p>
					</div>
					<div class="tab-pane fade" id="help-details">
						<p>Contact issues . Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui. Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
					</div>
					<div class="tab-pane fade" id="help-users">
						<p>How those users? Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
					</div>
					<div class="tab-pane fade" id="help-contacts">
						<p>Contacts... Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui. aw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div><!-- /#help -->

	<article class="title">
		<div class="toolbox">
			<?php echo anchor("unit", 'My Unit &rarr;', 'class="btn blue"'); ?> <?php echo anchor("unit", 'My Unit &rarr;', 'class="btn blue"'); ?>
		</div>
		<h1>Home</h1>
		<!--<p>The Longs Peak Council uses the Camper Registration System to allow units and scouters to register for camp and activities run by the Longs Peak Council and it's districts.</p>-->
		<div class="clear"></div>
    </article>
    <article class="tabset">
		<div class="tabset-nav">
			<ul>
				<li class="active"><a data-toggle="tab" href="#troop12"><i class="icon icon-group"></i><strong>Troop 12</strong>Events Troop 12 is registered for</a></li>
				<li><a data-toggle="tab" href="#crew1"><i class="icon icon-group"></i><strong>Crew 1</strong>Events Crew 1 is registered for</a></li>
				<li><a data-toggle="tab" href="#individual"><i class="icon icon-user"></i><strong>Individual</strong>Events you are registered for as an individual</a></li>
				<hr />
				<li><a data-toggle="tab" href="#register"><i class="icon icon-plus"></i><strong>Register</strong>Register for an event</a></li>
				<li><a data-toggle="tab" href="#eventviewer"><i class="icon icon-th"></i><strong>Event Viewer</strong>Browse upcoming events</a></li>
			</ul>
		</div><!-- /tabset-nav -->
		<div class="tab-content tabset-content">
			<!-- Unit Event Set -->
			<div class="home-eventset tab-pane active fade in" id="troop12">
				<!-- Unit details -->
				<div class="home-unit">
					<h2>Troop 12</h2>
					<h4>Details and Contacts</h4>
					<p>1200 South Taft Hill<br />Fort Collins, Colorado 80525</p>
					<p>Longs Peak (62)<br />Cache la Poudre District</p>
					<p><a href="#">Tim Colton</a> (primary)<br><a href="#">Kelley Wittmeyer</a><br /><a href="#">Pete Evans</a></p>
					<p><a href="" class="small">Manage system contacts &rarr;</a><br /><a href="" class="small">Invite/add a new leader &rarr;</a></p>
					<hr />
					<h4>Troop Members</h4>
					<ul id="troop12-131-tabs" class="unitlisttabs">
						<li class="active"><a data-toggle="tab" href="#troop12-131-adults">Adults</a></li>
						<li><a data-toggle="tab" href="#troop12-131-youth">Youth</a></li>
					</ul>
					<div class="tab-content unitlist">
						<div class="tab-pane active fade in" id="troop12-131-adults">
							<a href="#">Tim Colton</a>
							<a href="#">Greg Meyer</a>
							<a href="#">Jerry Frankenheimer</a>
							<a href="#">Trey Johnson</a>
							<a href="#">Sam Masters</a>
						</div>
						<div class="tab-pane fade" id="troop12-131-youth">
							<a href="#">Jerry Frankenheimer</a>
							<a href="#">Trey Johnson</a>
							<a href="#">Sam Masters</a>
							<a href="#">Tim Colton</a>
							<a href="#">Greg Meyer</a>
							<a href="#">Trey Johnson</a>
							<a href="#">Sam Masters</a>
							<a href="#">Tim Colton</a>
							<a href="#">Jerry Frankenheimer</a>
							<a href="#">Greg Meyer</a>
							<a href="#">Greg Meyer</a>
							<a href="#">Tim Colton</a>
							<a href="#">Jerry Frankenheimer</a>
							<a href="#">Greg Meyer</a>
							<a href="#">Trey Johnson</a>
							<a href="#">Sam Masters</a>
						</div>
					</div>
					<p><a href="" class="small">Manage system contacts &rarr;</a><br /><a href="" class="small">Invite/add a new leader &rarr;</a></p>
				</div>
				<!-- Unit registrations -->
				<h2>Upcoming Troop 12 Events</h2>
				<p>Troop 12 is registered for the events below. View a registration, see past registrations, or register for another event.</p>
				<div class="home-event">
					<i class="flag icon icon-warning-sign yellow"></i>
					<h3><a href="">2015 Summer Camp at BDSR</a></h3>
					<span class="event-medium">Troop 12 is registered, July 22 - 26</span>
					<hr />
					<span class="event-small"><i class="icon icon-money"></i> $1500 was due on Feb 2nd, <a href="#">make a payment &rarr;</a></span><br />
					<span class="event-small"><i class="icon icon-user"></i> 14 youth and 4 adults, <a href="#">manage your roster &rarr;</a></span>
				</div>
				<div class="home-event">
					<i class="flag icon icon-ok teal"></i>
					<h3><a href="">Chimney Park Clean-up Day</a></h3>
					<span class="event-medium">Troop 12 is registered, May 21</span>
					<hr />
					<span class="event-small"><i class="icon icon-money"></i> You are paid in full, thank you! <a href="#">View finances &rarr;</a></span><br >
					<span class="event-small"><i class="icon icon-user"></i> 5 youth and 2 adults, <a href="#">manage your roster &rarr;</a></span>
				</div>
				<div class="home-event">
					<i class="flag icon icon-remove red"></i>
					<h3><a href="">Fort Robinson Tree Replant</a></h3>
					<span class="event-medium">There are errors with your registration</span>
					<hr />
					<span class="event-small"><i class="icon icon-money"></i> $1500 was due on Feb 2nd, <a href="#">make a payment &rarr;</a></span><br />
					<span class="event-small"><i class="icon icon-user"></i> 3 adults, <a href="#">manage your roster &rarr;</a></span>
				</div>
				<div class="clear"></div>
			</div>



			<div class="home-eventset tab-pane fade" id="crew1">
				<h2>Upcoming Crew 1 Events</h2>
				<p>Crew 1 is registered for the events below. View a registration, see past registrations, or register for another event.</p>
				<div class="home-event">
					<i class="flag icon icon-ok teal"></i>
					<h3><a href="">Chimney Park Clean-up Day</a></h3>
					<span class="event-medium">Crew 1 is registered, May 21</span>
					<hr />
					<span class="event-small"><i class="icon icon-money"></i> You are paid in full, thank you! <a href="#">View finances &rarr;</a></span><br >
					<span class="event-small"><i class="icon icon-user"></i> 5 youth and 2 adults, <a href="#">manage your roster &rarr;</a></span>
				</div>
				<div class="home-event">
					<i class="flag icon icon-remove red"></i>
					<h3><a href="">Fort Robinson Tree Replant</a></h3>
					<span class="event-medium">There are errors with your registration</span>
					<hr />
					<span class="event-small"><i class="icon icon-money"></i> $1500 was due on Feb 2nd, <a href="#">make a payment &rarr;</a></span><br />
					<span class="event-small"><i class="icon icon-user"></i> 3 adults, <a href="#">manage your roster &rarr;</a></span>
				</div>
			</div>



			<div class="home-eventset tab-pane fade" id="individual">
				<!-- individual details -->
				<div class="home-unit">
					<h2>Sean Wittmeyer</h2>
					<h4>Your profile</h4>
					<p>sean@zilifone.net<br />(970) 219-2477</p>
					<p>No contact information on file, add one?</p>
					<p><a href="" class="small">Edit your profile &rarr;</a></p>
					<h4>Your units</h4>
					<p><a href="#">Troop 12</a> (primary)<br><a href="#">Crew 1</a></p>
					<p><a href="" class="small">Manage your units &rarr;</a><br /><a href="" class="small">Request access to another unit &rarr;</a></p>
				</div>
				<h2>Your Upcoming Events</h2>
				<p>You are registered as an individual for the events below. View a registration, see past registrations, or register for another event.</p>
				<div class="home-event">
					<i class="flag icon icon-remove red"></i>
					<h3><a href="">Fort Robinson Tree Replant</a></h3>
					<span class="event-medium">There are errors with your registration</span>
					<hr />
					<span class="event-small"><i class="icon icon-money"></i> $1500 was due on Feb 2nd, <a href="#">make a payment &rarr;</a></span><br />
					<span class="event-small"><i class="icon icon-user"></i> 3 adults, <a href="#">manage your roster &rarr;</a></span>
				</div>
			</div>
		</div>
	</article><!-- /tabset-content -->
