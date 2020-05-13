<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin / Units / New User View
 *
 * This is the ...
 *
 * File: /application/views/admin/users/newuser.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

?>
	<script>
		$(document).ready(function() {
			// Council and districts
			$('.councillist.typeahead').typeahead({							  
			  limit: '10',														
			  prefetch: '<?php echo $this->config->item('camper_path'); ?>api/v1/list/councils.json',
			  header: '<p class="typeaheadtitle"><i class="icon-search"></i> Council Search - Click on the council below</p>',
			  template: [																 
				'<p class="typeahead-num">{{num}}</p>',							  
				'<p class="typeahead-name">{{name}} Council</p>',									  
				'<p class="typeahead-city">{{city}}</p>'						 
			  ].join(''),																 
			  engine: Hogan															   
			});
			$('.districtlist.typeahead').typeahead({                              
			  limit: '10',                                                        
			  prefetch: '<?php echo $this->config->item('camper_path'); ?>api/v1/list/districts.json',                                             
			  template: [                                                                 
			    '<p class="typeahead-name">{{name}} District</p>',                                      
			    '<p class="typeahead-city">{{schools}}</p>'                         
			  ].join(''),                                                                 
			  engine: Hogan                                                               
			});

			// Events and sessions
			$('.sesslist.typeahead').typeahead({							  
			  limit: '10',														
			  prefetch: '<?php echo $this->config->item('camper_path'); ?>api/v1/sessions.json?123', 
			  template: [
				'<p class="typeahead-num">{{session}}</p>',
				'<p class="typeahead-name">{{event}}</p>',
				'<p class="typeahead-city">{{location}}</p>',   
				'<p class="typeahead-city">{{start}}{{end}} ({{type}})</p>'
			  ].join(''),																 
			  engine: Hogan															   
			});
			
			// Events and sessions toggle
			$('.sesslist.typeahead').on('typeahead:autocompleted', function(evt, item) {
				$("#sessiondetails").removeClass('hidden');
				$("#sid").val(item['id']);
				$("#sessionid").html('<i class="icon-ok teal right"></i> '+item['id']);
				$("#event").text(item['event']);
				$("#session").text(item['session']);
			})
			$('.sesslist.typeahead').on('typeahead:selected', function(evt, item) {
				$("#sessiondetails").removeClass('hidden');
				$("#sid").val(item['id']);
				$("#sessionid").html('<i class="icon-ok teal right"></i> '+item['id']);
				$("#event").text(item['event']);
				$("#session").text(item['session']);
			})
		});
	</script>
	<div class="subnav">
		<div class="container">
			<h2>Units &amp; Users</h2>
			<nav class="campersubnav">
   	    		<li><?php echo anchor("users/pending", 'Pending Invites'); ?></li>
   	    		<li class="active"><?php echo anchor("users", 'Users'); ?></li>
   	    		<li><?php echo anchor("units", 'Units'); ?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
   		<h2 class="">Create an User</h2>
   		<p>Adding an user to Camper is different than inviting an user, this will create their account and send an 'activate' email. They will need to change their password in order to sign in. This is designed to let administrators create an user then register that user for an event (inviting someone doesn't let you register them). Registering for an event is optional on this page.</p>
		<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
   		<div class="clear"></div>
	</article>
	<?php echo form_open(uri_string()); ?>
	<input type="hidden" id="sid" name="sid" /> 
	<article class="textsection">
   		<div class="container">
	   		<div class="quarter">
   				<h2>User Details</h2>
   				<p>You can also invite people to be contacts with this unit.</p>
   				<div class="clear"></div>
	   		</div>
	   		<div class="threequarter">
				<p>Creating an account is as easy. Start by filling out your email address, name and phone number. The email address and password can be set to allow this user to sign in.</p>
				<div class="camperform" style="width:400px;"><input type="text" name="email" value="<?php echo set_value('email'); ?>" placeholder="baden.powell@scouting.org" id="newemail" data-toggle="tooltip" data-placement="right" title="Enter your email address, this will be your login" /><label>Your Email (this will be your login)</label></div>
				<div class="camperform" style="width:400px;"><input type="password" name="password" value="<?php echo set_value('password'); ?>" placeholder="••••••••" id="newpassword" data-toggle="tooltip" data-placement="right" title="Choose a strong password with uppercase and lowercase letters and numbers" /><label>Choose a password</label></div>
				<div class="clear"></div>
				<div class="camperform float" style="width: 105px"><input type="text" name="first" id="ffirst" value="<?php echo set_value('first'); ?>" placeholder="Baden" data-toggle="tooltip" data-placement="right" title="Your first name or nickname you go by" /><label for="ffirst" >First Name</label></div>
				<div class="camperform float" style="width: 200px;"><input type="text" name="last" id="flast" value="<?php echo set_value('last'); ?>" placeholder="Powell" data-toggle="tooltip" data-placement="right" title="Your last name" /><label for="flast" >Last</label></div>
				<div class="camperform float last" style="width: 150px;"><input type="tel" id="ftel" onchange="formatPhone(this);" onkeydown="formatPhone(this);" name="phone" value="<?php echo set_value('phone'); ?>" placeholder="(970) 330 - 4052" data-toggle="tooltip" data-placement="right" title="Your phone number, we will call if we have any issues" /><label for="ftel" >Daytime Phone</label></div>
				<div class="clear"></div>
   			</div>
   		</div>
   		<div class="clear"></div>
	</article>
	<article class="textsection">
   		<div class="container">
	   		<div class="clear hr"></div>
	   		<div class="quarter">
   				<h2>Unit</h2>
   				<p>Adding unit details to an user will help keep things organized. This will not add this user to this unit, you must do that on the edit unit page.  <br /><br /><strong>This is optional.</strong></p>
   				<div class="clear"></div>
	   		</div>
	   		<div class="threequarter">
		   		<p class="">Set the council, district, and unit for this user. This will not add them to this unit, it is simply for our reference. To add this person to an unit, you can create the user then add them from the unit page you wish to add them to.</p>
    			<div class="camperform float " style="width: 300px;"><input type="text" name="council" id="fcouncil" class="councillist typeahead" value="<?php echo set_value('council'); ?>" placeholder="Longs Peak Council" data-toggle="tooltip" title="Enter your local Scout council, Begin typing to see hints! If you are from Northern Colorado, Southern Wyoming or Western Nebraska, your council is Longs Peak Council" /><label for="fcouncil">Council</label></div>
    			<div class="camperform float " style="width: 300px;"><input type="text" name="district" id="fdistrict" class="districtlist typeahead" value="<?php echo set_value('district'); ?>" placeholder="Tri Trails" data-toggle="tooltip" title="Enter your local district, Begin typing to see hints if you are in the Longs Peak Council! If you don't know your district, contact your council's service center or search on their website." /><label for="fdistrict">District</label></div>
    			<div class="clear"></div>
    			<p class="">Please specify the user's unit. We use this to help identify them at events they've registered for.</p>
    			<div class="camperform float" style="width: 110px;">
    		   		<select name="unittype">
    					<option value="Troop" selected="selected">Troop</option>
    					<option value="Crew">Crew</option>
    					<option value="Ship">Ship</option>
    					<option value="Pack">Pack</option>
    					<option value="Team">Team</option>
    					<option value="None">-- No Unit --</option>
    				</select>
    				<label class="">Unit Type</label>
    			</div>
    	   		<div class="camperform float " style="width: 50px"><input type="text" class="typeahead" name="number" value="<?php echo set_value('number'); ?>" placeholder="1" data-toggle="tooltip" title="Unit number" /><label>Number</label></div>
    	   		<div class="clear"></div>
    			<div class="camperform float " style="width: 250px;"><input type="text" name="add" id="fadd" class="" value="<?php echo set_value('add'); ?>" placeholder="2215 23rd Avenue" data-toggle="tooltip" title="User mailing address" /><label for="fadd">User Address</label></div>
    			<div class="camperform float " style="width: 150px;"><input type="text" name="city" id="fcity" class="" value="<?php echo set_value('city'); ?>" placeholder="Greeley" /><label for="fcity">City</label></div>
    	   		<div class="camperform float" style="width: 160px;">
    				<select id="fstate" name="state"> 
    					<option value="" disabled="disabled">Select a State</option> 
    					<option value="AL">Alabama</option> 
    					<option value="AK">Alaska</option> 
    					<option value="AZ">Arizona</option> 
    					<option value="AR">Arkansas</option> 
    					<option value="CA">California</option> 
    					<option value="CO" selected="selected">Colorado</option> 
    					<option value="CT">Connecticut</option> 
    					<option value="DE">Delaware</option> 
    					<option value="DC">District Of Columbia</option> 
    					<option value="FL">Florida</option> 
    					<option value="GA">Georgia</option> 
    					<option value="HI">Hawaii</option> 
    					<option value="ID">Idaho</option> 
    					<option value="IL">Illinois</option> 
    					<option value="IN">Indiana</option> 
    					<option value="IA">Iowa</option> 
    					<option value="KS">Kansas</option> 
    					<option value="KY">Kentucky</option> 
    					<option value="LA">Louisiana</option> 
    					<option value="ME">Maine</option> 
    					<option value="MD">Maryland</option> 
    					<option value="MA">Massachusetts</option> 
    					<option value="MI">Michigan</option> 
    					<option value="MN">Minnesota</option> 
    					<option value="MS">Mississippi</option> 
    					<option value="MO">Missouri</option> 
    					<option value="MT">Montana</option> 
    					<option value="NE">Nebraska</option> 
    					<option value="NV">Nevada</option> 
    					<option value="NH">New Hampshire</option> 
    					<option value="NJ">New Jersey</option> 
    					<option value="NM">New Mexico</option> 
    					<option value="NY">New York</option> 
    					<option value="NC">North Carolina</option> 
    					<option value="ND">North Dakota</option> 
    					<option value="OH">Ohio</option> 
    					<option value="OK">Oklahoma</option> 
    					<option value="OR">Oregon</option> 
    					<option value="PA">Pennsylvania</option> 
    					<option value="RI">Rhode Island</option> 
    					<option value="SC">South Carolina</option> 
    					<option value="SD">South Dakota</option> 
    					<option value="TN">Tennessee</option> 
    					<option value="TX">Texas</option> 
    					<option value="UT">Utah</option> 
    					<option value="VT">Vermont</option> 
    					<option value="VA">Virginia</option> 
    					<option value="WA">Washington</option> 
    					<option value="WV">West Virginia</option> 
    					<option value="WI">Wisconsin</option> 
    					<option value="WY">Wyoming</option>
    				</select><label for="fstate">State</label>
    	   		</div>
    			<div class="camperform float last" style="width: 60px;"><input type="text" name="zip" id="fzip" class="" value="<?php echo set_value('zip'); ?>" placeholder="80632" data-toggle="tooltip" title="Your users's zip code without the +4 extension" /><label for="fzip">Zip</label></div>
    			<div class="clear"></div>
	   		</div>
   		</div>
	</article>
	<article class="textsection">
	<div class="clear hr"></div>
   		<div class="container">
	   		<div class="quarter">
   				<h2>Register</h2>
   				<p>You also have the option to register this user for an event. <br /><br /><strong>This is optional.</strong></p>
   				<div class="clear"></div>
	   		</div>
	   		<div class="threequarter">
				<p>You can start an individual registration for you new user here. Search for and select the event and session/week from the live search results to register. <br /><br />This registration tool is exempt from the session/week hard or soft limit numbers, but the registration will not be final without adding payments (if required). The user will be able to make payments for this registration when they sign in, or you can add payments in the payments section.</p>
		   		<div class="camperform float search" style="width: 60%"><i class="icon-search"></i><input class="ico sesslist typeahead" type="text" data-toggle="tooltip"  placeholder="Week 2 CLP Summer Camp..."  title="Search for an event by..." /><label>Event Search</label></div>
				<div class="clear"></div>
			   	<div id="sessiondetails" class="hidden">
				   	<div class="camperform float " style="width:auto;"><span id="event">...</span><label>Event</label></div>
				   	<div class="camperform float " style="width:auto;"><span id="session">...</span><label>Session</label></div>
				   	<div class="camperform float last" style="width:auto;"><span id="sessionid">...</span><label>Session ID</label></div>
			   		<div class="clear"></div>
			   		<p>Set the registration date here. You can use this to make a registration eligible for early registration or to put in a registration after it was made over another medium such as phone or email.
	   				<div class="camperform float " style="width: 30%"><input type="text" name="regdate" class="datepicker" id="fdate" value="<?php echo date('F d, Y'); ?>" placeholder="<?php echo date('F d, Y'); ?>" data-toggle="tooltip" title="Enter the registration effective date." /><label for="fdate">Registration Date</label></div>
	   				<!--<div class="camperform float " style="width: 100px"><input type="text" name="youth" id="fyouth" value="" placeholder="10" data-toggle="tooltip" title="Enter the number of youth you want to register." /><label for="fyouth">Youth</label></div>
	   				<div class="camperform float " style="width: 100px"><input type="text" name="male" id="fmale" value="" placeholder="10" data-toggle="tooltip" title="Enter the number of male adults you want to register." /><label for="fmale">Male Adults</label></div>
	   				<div class="camperform float " style="width: 100px"><input type="text" name="female" id="ffemale" value="" placeholder="10" data-toggle="tooltip" title="Enter the number of female adults you want to register." /><label for="ffemale">Female Adults</label></div>
	   				<!--<div class="camperform float last" style="width: 100px"><input type="text" name="special" id="fspecial" value="" placeholder="10" data-toggle="tooltip" title="Enter the number of youth you want to register." /><label for="fspecial">Special</label></div>-->
	   				<div class="clear"></div>
			   	</div>
   			</div>
   		</div>
   		<div class="clear"></div>
	</article>
	<article class="textsection">
	<div class="clear hr"></div>
   		<div class="container">
	   		<div class="quarter">
   				<h2>Finish</h2>
   				<div class="clear"></div>
	   		</div>
	   		<div class="threequarter">
				<p>This user will be created and they will be registered for the chosen event session/week when you click submit. You will be able to make payments for the registration created (if applicable) as well. </p>
				<div class="clear"></div>
   				<input type="submit" name="submit" value="Create User" class="btn teal" data-loading-text="Creating the user..." onclick="$(this).button('loading');"  /> <input type="reset" name="reset" value="Reset" class="btn tan"  />	
   			</div>
   		</div>
   		<div class="clear"></div>
	</article>
   	<?php echo form_close();?> 
