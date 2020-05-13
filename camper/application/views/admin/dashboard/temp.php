<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Admin Dashboard
 *
 * The admin dashboard offers quick and clear access to all of the information in the 
 * system from users and units to the events and registrations. This page will evolve 
 * as the needs of the administrators evolves.
 *
 * File: /application/views/admin/dashboard/admin.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 
?>
	<script>
		$(document).ready(function() {
			$('.userslist.typeahead').typeahead({ 
			  limit: '10', 
			  prefetch: '/camper/api/v1/users.json', 
			  template: [ 
			    '<p class="typeahead-num">{{unit}}</p>', 
			    '<p class="typeahead-name">{{name}}</p>', 
			    '<p class="typeahead-city">{{email}} / {{phone}}</p>' 
			  ].join(''), 
			  engine: Hogan 
			});
			$('.userslist.typeahead').on('typeahead:autocompleted', function(evt, item) {
				window.location.href = '<?php echo base_url(); ?>users/' + item['userid'];
			})
			$('.userslist.typeahead').on('typeahead:selected', function(evt, item) {
				window.location.href = '<?php echo base_url(); ?>users/' + item['userid'];
			})
			$('.unitslist.typeahead').typeahead({                              
			  limit: '10',                                                        
			  prefetch: '/camper/api/v1/units.json', 
			  template: [ 
			    '<p class="typeahead-num">{{city}}, {{state}}</p>', 
			    '<p class="typeahead-name">{{name}}</p>', 
			    '<p class="typeahead-city">{{council}}</p>' 
			  ].join(''), 
			  engine: Hogan 
			});
			$('.unitslist.typeahead').on('typeahead:selected', function(evt, item) {
				window.location.href = '<?php echo base_url(); ?>units/' + item['unitid'];
			})
			$('.unitslist.typeahead').on('typeahead:autocompleted', function(evt, item) {
				window.location.href = '<?php echo base_url(); ?>units/' + item['unitid'];
			})
			$('.regslist.typeahead').typeahead({ 
			  limit: '10', 
			  prefetch: '<?php echo base_url(); ?>api/v1/regs.json', 
			  template: [
				'<p class="typeahead-num">{{session}}</p>',
				'<p class="typeahead-name">{{event}}</p>',
				'<p class="typeahead-num">(reg #{{id}})</p>',
				'<p class="typeahead-city">{{eventlocation}}</p>',   
				'<p class="typeahead-city"><strong>{{unit}}</strong>, {{city}} ({{council}})</p>'
			  ].join(''), 
			  engine: Hogan	
			});
			$('.regslist.typeahead').on('typeahead:autocompleted', function(evt, item) {

			})
			$('.regslist.typeahead').on('typeahead:selected', function(evt, item) {
				window.location.href = '<?php echo base_url(); ?>event/' + item['eventid'] + '/registrations/' + item['id'] + '/edit';
			})
		});
	</script>
	<article class="textsection">
		<div class="container">	
			<div class="half">
				<h2>Hi <?php echo $first; ?>, Welcome back!</h2>
				<p>This is just a start to this page, expect more soon...</p>
			</div>
			<div class="half last">
				<div class="camperform float spacetop" style="" data-toggle="tooltip" title="<?php echo ($notifications['new']) ? ' You have new notifications, click to view them': 'You don\'t have any new notifications but click to see past notifications'; ?>"><a href="<?php echo base_url(); ?>n"><span><i class="icon-tasks<?php if ($notifications['new']) echo ' red'; ?> inline"></i> <?php echo $notifications['newcount']; ?></span></a><label>Notifications</label></div>
				<div class="camperform float spacetop camperhoverpopover" style="" data-toggle="popover" title="System Check" data-placement="top" data-content="Good news, we've done a check and everything looks good.<br><i class='icon-ok teal'></i> Database (MYSQL 5+ ok)<br><i class='icon-ok teal'></i> Server (PHP 5+ ok)<br><i class='icon-ok teal'></i> Secure Connection (HTTPS/SSL) via ssl.longspeakbsa.org<br><i class='icon-ok teal'></i> Users and Units Database<br><i class='icon-ok teal'></i> Events and Sessions Database<br><i class='icon-ok teal'></i> Event Regs Database<br><i class='icon-ok teal'></i> Mail Server (mandrillapp.com ok)"><span><i class="icon-ok teal inline"></i> Good!</span><label>System Status</label></div>
				<div class="camperform float spacetop" style="" data-toggle="tooltip" title="Your version of Camper is the latest version, automatic updates are disabled. Click to see what's new."><a href="<?php echo base_url(); ?>new"><span><?php echo $this->config->item('camper_version'); ?></span></a><label>Version</label></div>

			</div>
			<div class="clear hr"></div>
			<div class="quarter">
				<h3>Regs &amp; Payments</h3>
			</div>
			<div class="threequarter">
				<p class="spacetop"><strong>Find a registration:</strong> Locate any unit's registration by searching by unit type and number, event, session or group.</p>
   		   		<div class="camperform float search" style="width: 60%"><i class="icon-search"></i><input class="ico regslist typeahead" type="text" name="regs" data-toggle="tooltip"  placeholder="Find a reg..."  title="Search for a registration by unit type and number, event or session" /><label>Registration Search</label></div>
   		   		<div class="clear"></div>
   				<p><strong>Payments:</strong> Easily find any payment made in the system.</p>
   		   		<p><?php echo anchor('payments', 'All Payments &rarr;', 'class="btn tan"'); ?></p>
			</div>
			<div class="clear hr"></div>
			<div class="quarter">
				<h3>Units &amp; Users</h3>
			</div>
			<div class="threequarter">
				<p class="spacetop"><strong>User Search:</strong> Locate a user by searching by name, phone number or email address.</p>
   		   		<div class="camperform float search" style="width: 60%"><i class="icon-search"></i><input class="ico userslist typeahead" type="text" name="users" data-toggle="tooltip"  placeholder="Find a user..."  title="Search for a user by name, phone number or email address" /><label>User Search</label></div>
   		   		<div class="clear"></div>
   		   		<p><?php echo anchor('users', 'All Users &rarr;', 'class="btn tan"'); ?></p>
   		   		<div class="clear"></div>
   				<p><strong>Units Search:</strong> Locate a unit by searching with the unit type and number, contact name, city or council.</p>
   		   		<div class="camperform float search" style="width: 60%"><i class="icon-search"></i><input class="ico unitslist typeahead" type="text" name="units" data-toggle="tooltip"  placeholder="Find a troop, pack, etc..."  title="Search for a unit by type, number, name, city, council or contact name" /><label>Unit Search</label></div>
   		   		<div class="clear"></div>
				<p><?php echo anchor('units', 'All Units &rarr;', 'class="btn tan"'); ?></p>
   		   		<div class="clear"></div>
			</div>
		</div>
	</article>
