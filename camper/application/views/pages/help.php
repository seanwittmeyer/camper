<?php 

/* 
 * Camper Help Page
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
				<li class=""><?php echo anchor("meet", 'Meet Camper');?></li>
				<li class="active"><?php echo anchor("help", 'Help Center');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
		<div class="container">
			<h2>Help using Camper</h2>
   			<p>Find out about Camper Registration, including our use of cookies, the privacy policy and terms of use, what browser requirements, and how to get some assistance. Start by looking at the FAQ and the help section for what you were doing. If you still need help or want to learn more about how Camper works, <a href="http://camps.longspeakbsa.org/contact/" target="_blank">contact us</a> or check out our <a href="http://support.zilifone.net/" target="_blank">Knowledge Base</a>.</p>
		</div>
   		<div class="container">
       		<div class="quarter">
   	    		<h2>Topics</h2>
   	    		<div class="clear"></div>
       			<ul id="mapstabs" class="blue">
   	    			<li class="active"><a href="#start" data-toggle="tab">Start Here</a></li>
   	    			<li class=""><a href="#faq" data-toggle="tab">FAQ</a></li>
   	    			<li class=""><a href="#privacy" data-toggle="tab">Privacy</a></li>
   	    			<li class=""><a href="#signup" data-toggle="tab">Creating an account</a></li>
   	    		</ul>
       		</div>
       		<div class="tab-content threequarter ">
   				<div class="tab-pane fade in active" id="start">
   					<h2>Getting help with Camper Registration</h2>
   					<p>We designed and built our registration system with you, the user, in mind from the ground up. We are in the process of creating help documentation. For help on specific pages, look for little (?) buttons on the right side of the screen for detailed instructions and other information.</p>
   					<p>If you are having issues and you can't find what you are looking for here or in our FAQ, feel free to call or email us. We are here to help. Since Camper is new, you may have found a bug. simply click on the "Is there anything wrong with this page" link at the bottom of the page you are on and let us know what you were doing and what went wrong. If the issue is bigger, simply <a href="http://camps.longspeakbsa.org/contact/" target="_blank" >contact us</a> or shoot an <a href="mailto:sean@camperapp.org">email to the web team</a>. </p>
   				</div>
   				<div class="tab-pane fade" id="faq">
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
   				<div class="tab-pane fade" id="privacy">
   					<h2>Privacy Policy and Use of Cookies</h2>
					<p><strong>By using Camper, you understand and approve of how we, Longs Peak Council, will use the information you provide.</strong> We collect certain information or data about you when you use Camper, we'll explain how we will use your information here.</p>
					<p><strong>The information we collect includes:</strong></p>
					<ul>
						<li>information you provide about yourself and your unit, eg your name, phone, email address and unit address</li>
						<li>information you provide about the members of your unit, eg names, dates of birth, allergies, etc</li>
						<li>registration details and preferences you provide when registering for an event</li>
						<li>questions, queries or feedback you leave</li>
						<li>your IP address, and details of which version of web browser you used</li>
						<li>information on how you use the site, using cookies and page tagging techniques to help us improve the website</li>
						<li>error messages you encounter along with details on what you were doing to help us squish bugs in the site</li>
						<li>details to allow you to access Camper services and transactions, eg an email address and password to access your account</li>
					</ul>
					<p><strong>This helps us to:</strong></p>
					<ul>
						<li>register your unit for council and district events</li>
						<li>let you know when changes are made that will affect you or your unit</li>
						<li>give you the power to manage more aspects of event registration</li>
						<li>improve the site by monitoring how you use it</li>
						<li>respond to any feedback you send us, if you’ve asked us to</li>
						<li>provide you with information about events you registered for</li>
					</ul>

					<h3>Where your data is stored</h3>
					<p>We store your data in an encrypted form on our secure servers, separate from our council systems. Your data and the Camper software is backed up daily in a variety of locations. By creating an account and submitting information, you agree to this.</p>

					<h3>Keeping your data secure</h3>
					<p>While transmitting information over the internet is generally not completely secure, we have procedures and security features in place to try and keep your data secure once we receive it.</p>
					<p>When you use Camper, make sure you are using a secure or HTTPS connection. You can make sure your connection is secure by looking at the address bar in your browser, confirm that it has a padlock or it shows https://ssl.longspeakbsa.org. Our server is secured by GeoTrust:</p>
					<p><!-- GeoTrust QuickSSL [tm] Smart  Icon tag. Do not edit. --><script language="javascript" type="text/javascript" src="//smarticon.geotrust.com/si.js"></script><!-- end  GeoTrust Smart Icon tag --></p>
					<p>We won’t share your information with any other organizations for marketing, market research or commercial purposes, and we don’t pass on your details to other websites.</p>
					<p>Payment transactions are always encrypted. We don't collect your financial or payment details such as credit cards if you pay online. These details are collected and managed by PayPal, the service we use for online payments.</p>
					
					<h3>Disclosing your information</h3>
					<p>We may pass on your personal information if we have a legal obligation to do so, or if we have to enforce or apply our terms of use and other agreements. This includes exchanging information within the council and council contractors to provide service you sign up for and/or request. For example, we will provide your dietary restrictions and allergies to our food service contractors to accommodate these needs when you are at an event.</p>
					
					<h3>Your rights</h3>
					<p>You can find out what information we hold about you, and ask us to provide all information we collected. If you wish to have a copy of your information in Camper, please contact us.</p>
					<p>If you choose not to use online registration, you have the opportunity to register for events over the phone or by mail with the council service center.</p>
					
					<h2>Cookies</h2>
					<p>Camper puts small files (known as "cookies") onto your computer to collect information about how you use the site and to help us identify you.</p>
					<p>Cookies are used to measure how you use the site, remember who you are (including keeping you logged in), and to deliver messages as you use Camper (eg, show an error or success message when you update a page).</p>

					<h3>How cookies are used</h3>
					<p>We use cookies in a couple of ways. Camper sets 2 cookies when you login to the site. The first is called "camper" and stores information about you including your user id, your ip address and a session id. Camper also sets a cookie caller "camperactiveevent" which stores a key. We use this to remember what you were doing the last time you used the site. </p>
					<p>We use Google Analytics to measure website usage and they also create cookies. They create up to 4 cookies called "_utma", "_utmb", "_utmc" and "_utmz" which help us count the number of visitors we get to the site and information about these visits like how long you use a page. Google does not collect information that can be used to personally identify you with these cookies.</p>
   				</div>
   				<div class="tab-pane fade" id="signup">
   					<h2>Creating an account</h2>
   					<p>Camper is designed to be used as a single source for units to register for events. Each unit has 2 contacts and both can manage and register their unit. Setting up an account and unit only needs to happen once but we do require a lot of information.</p>
   					<h2>Start here</h2>
   					<p>To get started, head over to the <?php echo anchor("start", 'Create an account');?> page and supply your email address and a strong password. Only you should login with this information. Click next when you are done.</p>
   					<p>Seep 2 is to fill out some personal details including your name and phone number. Click next to start creating your unit. Select your unit type (troop, pack, crew, ship, etc...) and start typing your number. If you see your unit in the popup list, that means your unit has already been set up. If you select it, your unit's primary contact will be notified and asked to approve your access to your unit.</p>
   					<p>If your unit does not show up, you will need to supply your unit's address if we need to mail anything to your unit. You will get a chance to confirm your signup details. Once you confirm everything looks good, finish and you will have created your unit.</p>
   					<p>Check your email for an activation link to login and start using Camper.</p>
   				</div>
   			</div>
   		</div>
   		<div class="clear"></div>

	</article>