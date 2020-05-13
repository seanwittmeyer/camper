<?php 

/* 
 * Camper Meet Page
 *
 * This is a test.
 *
 * File: /application/views/pages/help.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2012.10.25.1412)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

?>
	<div class="subnav">
		<div class="container">
			<h2>Help</h2>
			<nav class="campersubnav">
				<li class=""><?php echo anchor("http://camps.longspeakbsa.org/contact/", 'Contact Us');?></li>
				<li class="active"><?php echo anchor("meet", 'Meet Camper');?></li>
				<li class=""><?php echo anchor("help", 'Help Center');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
    	<div class="container">
			<h1 class="center">Hi, This is Camper</h1>
			<div class="clear"></div>
			<div class="quarter">&nbsp;</div>
			<div class="half"><p class="center">Camper is the new way to register for events in Longs Peak Council, from summer camp to camporees and other weekend events. Camper is still new and we are adding new features and squishing bugs every day but we are excited to open it up to you and your unit. Explore this page to learn more about Camper and how to get started.</p></div>
			<div class="quarter last">&nbsp;</div>
			<div class="clear"></div>
    	</div>
    	<div class="container">
			<div class="clear"></div>
			<div class="quarter center">
				<h2><i class="icon-user"></i></h2>
				<h2>My Unit</h2>
				<p>Manage your Camper account and your unit in the My Unit section. Unit primary contacts have the additional ability to manage unit contacts!</p>
			</div>
			<div class="quarter center">
				<h2><i class="icon-calendar"></i></h2>
				<h2>Events</h2>
				<p>Register with one click and get back to having Scouting fun in the outdoors! Events will eventually include a variety of events from camporees to trainings.</p>
			</div>
			<div class="quarter center">
				<h2><i class="icon-ok"></i></h2>
				<h2>Registrations</h2>
				<p>Easily manage discounts and options, preorder merit badge/activity supplies and even register individual scouts and adults for activities.</p>
			</div>
			<div class="quarter center last">
				<h2><i class="icon-question"></i></h2>
				<h2>Getting Help</h2>
				<p>Camper has a help and FAQ page that is constantly growing, and if you can't find what you are looking for, we are only a quick phone call away with help!</p>
			</div>
    	</div>
		<div class="clear hr"></div>
    	<div class="container">
			<div><p>&nbsp;</p></div>
			<div class="clear"></div>
			<h1 class="center">Getting Started</h1>
			<div class="clear"></div>
			<div class="quarter">&nbsp;</div>
			<div class="half"><p class="center">We have worked hard to make creating your account and unit as easy as possible. You only have to create your account once and getting started is as easy as 1, 2, 3!</p><p class="center"><?php echo anchor("start?fr=meet", 'Start Here &rarr;', 'class="btn teal"');?></p></div>
			<div class="quarter last">&nbsp;</div>
			<div class="clear"></div>
    	</div>
    	<div class="container">
			<div class="clear"></div>
			<div class="quarter center">
				<h2><i class="icon-list-ol"></i></h2>
				<h2>What You Need</h2>
				<p>All you need to get started with Camper is <strong>two leaders</strong> to be your unit's contacts (the first one will create the unit). This could include your Scoutmaster and a tech-savvy leader.</p>
			</div>
			<div class="quarter center">
				<h2><i class="icon-user"></i></h2>
				<h2>Your Account</h2>
				<p>The first step is to create your account. Each user will have their own email and password so you don't need to share a single login / password anymore. Camper uses two-deep leadership online.</p>
			</div>
			<div class="quarter center">
				<h2><i class="icon-group"></i></h2>
				<h2>Your Unit</h2>
				<p>Once you set your email and a password, you will create your unit. If it's in Camper already, simply select it and we'll do the rest. Camper is not ready for individual registration yet.</p>
			</div>
			<div class="quarter center last">
				<h2><i class="icon-ok"></i></h2>
				<h2>That's it!</h2>
				<p>When you are done creating your account and setting up your unit, you'll get a chance to confirm everything. Simply hit the back button in your browser to make changes. When you hit 'create', you'll be ready to go.</p>
			</div>
    	</div>
		<div class="clear hr"></div>
		<div class="container">
			<div class="threequarter">
				<h2>Frequent Questions</h2>
	   			<p>We tried to make the process of creating an account as easy as possible, but sometimes things can get a little confusing. These are some common questions with creating an account and getting started with Camper.</p>
	   			<h4><i class="icon-question-sign"></i> Does each unit need 2 leaders to register?</h4>
	   			<p><strong>Yes!</strong> Camper uses two-deep leadership online, you must have two different leaders as contacts in Camper in order to register for events. When you create your unit, you will have a place to enter the second leader's email address, they will be sent an invite to create an account. Both must be in Camper to register.</p>
	   			<h4><i class="icon-question-sign"></i> Will you share my email address with anyone?</h4>
	   			<p><strong>No!</strong> We require your email address to verify that you are a user in the system (for signing in) and for occasional notifications for when an event changes (change in location or if it gets cancelled). We want to improve the communication between the Council and you, the unit leaders, and this is the first step in that process. Your email address will never be shared with anyone outside of the Longs Peak Council.</p>
	   			<h4><i class="icon-question-sign"></i> Do I have to pay to use Camper?</h4>
	   			<p><strong>Nope!</strong> Signing up and registering for events with camper is totally free to the unit leaders. Camper does allow you to make online payments for camp/event registration fees though, simply add a check or PayPal payment and you'll see the balance updated.</p>
	   			<h4><i class="icon-question-sign"></i> Is Camper secure?</h4>
	   			<p><strong>Yes!</strong> We have built the system from the ground up with security in mind. The system uses bank-grade security including an SSL connection to your browser, and we encrypt your data when we store it in our database. <?php echo anchor("help#privacy", 'Learn more about security and privacy on Camper &rarr;');?></p>
	   			<h4><i class="icon-question-sign"></i> How can I get help?</h4>
	   			<p>Having trouble with Camper? Start by checking out our Help Page. If you can't find anything there, <a href="http://camps.longspeakbsa.org/contact/" target="_blank" >feel free to contact us by phone or email</a>.</p>
	   			<h4><i class="icon-question-sign"></i> I found a bug or something that doesn't work</h4>
	   			<p>If you ever find a problem with the site, simply click on the "Is there anything wrong with this page" link at the bottom of the page you are on and let us know what you were doing and what went wrong. If the issue is bigger, simply <a href="http://camps.longspeakbsa.org/contact/" target="_blank" >contact us</a> or shoot an <a href="mailto:sean@camperapp.org">email to the web team</a>.</p>
			</div>
		</div>
		
   		<div class="clear"></div>
	</article>