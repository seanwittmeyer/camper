<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin / Units / New Unit View
 *
 * This is the ...
 *
 * File: /application/views/admin/users/newunit.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

?>
	<script>
		$(document).ready(function() {
			// Units
			$('.unitslist.typeahead').typeahead({							  
			  limit: '10',														
			  prefetch: '<?php echo $this->config->item('camper_path'); ?>api/v1/units.json?return=number&v=d124', 
			  header: '<p class="typeaheadtitle"><i class="icon-search"></i> Unit Search - Click on your unit if it shows up below.</p>',
			  template: [																 
				'<p class="typeahead-num">{{city}}, {{state}}</p>',							  
				'<p class="typeahead-name">{{name}}</p>',									  
				'<p class="typeahead-city">{{council}}</p>'						 
			  ].join(''),																 
			  engine: Hogan
			});
			
			// Is this your unit toggle
			$('.unitslist.typeahead').on('typeahead:selected typeahead:autocompleted', function(evt, item) {
				//window.location.href = '<?php echo base_url(); ?>units/edit/' + item['unitid'];
				$('#isthis').show();
				$('#isthisname').text(item['name']);
				$('#isthiscouncil').text(item['council']);
				$('#isthiscity').text(item['city']);
				$('#isthisstate').text(item['state']);
				$('#isthisid').val(item['unitid']);
			})
			
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
   	    		<li><?php echo anchor("users", 'Users'); ?></li>
   	    		<li class="active"><?php echo anchor("units", 'Units'); ?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
   		<h2 class="">Create a New Unit</h2>
   		<p>Creating and inviting units to join is easy to do. Start by adding the unit details and add additional details if you have them. You can set the primary and alternate users (they'll be invited if they aren't in the system yet), and register the unit for an event (handy if taking a registration over the phone). </p>
		<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
   		<div class="clear"></div>
	</article>
	<?php echo form_open(uri_string()); ?>
	<input type="hidden" id="sid" name="sid" /> 
	<article class="textsection">
   		<div class="container">
	   		<div class="quarter">
   				<h2>Details</h2>
   				<p>Fill out the unit details and address to create the unit. When entering the number, you will see other matching units, select one it it's the one you are trying to create.</p>
   				<div class="clear"></div>
	   		</div>
	   		<div class="threequarter">
		   		<div class="camperform float" style="width: 90px;">
			   		<select name="unittype">
						<option value="Troop" selected="selected">Troop</option>
						<option value="Crew">Crew</option>
						<option value="Ship">Ship</option>
						<option value="Pack">Pack</option>
						<option value="Den" disabled="disabled">Den</option>
						<option value="Individual" disabled="disabled">Individual</option>
					</select>
					<label class="">Unit Type</label>
				</div>
		   		<div class="camperform float" style="width: 50px"><input type="text" class="unitslist typeahead" name="number" value="<?php echo set_value('number'); ?>" placeholder="1" data-toggle="tooltip" title="Unit number, if you see your unit in the search list, click it to automatically set up your unit." /><label>Number</label></div>
				<div class="camperform float " style="width: 300px;"><input type="text" name="council" id="fcouncil" class="councillist typeahead" value="<?php echo set_value('council'); ?>" placeholder="Longs Peak (62)" data-toggle="tooltip" title="Enter your local Scout council, Begin typing to see hints! If you are from Northern Colorado, Southern Wyoming or Western Nebraska, your council is Longs Peak Council" /><label for="fcouncil">Council</label></div>
		   		<div class="clear"></div>
				<div class="well well-small" id="isthis" style="display: none;"><button type="button" class="close" data-dismiss="alert">&times;</button>
					<h4>Is this the unit you want to create?</h4>
					<p><span id="isthisname">Unit 1</span> of <span id="isthiscity">City</span>, <span id="isthisstate">ST</span><br />Council: <span id="isthiscouncil">Some Council</span></p> 
					<input id="isthisid" value="0" type="hidden" />
			   		<div class="clear"></div>
			   		<p><strong>This feature does not work yet!!!</strong></p>
			   		<input type="submit" name="submit" value="Yes, next step &rarr;" data-loading-text="One moment please..." onclick="$('#unitid').val($('#isthisid').val()); $('#custom').val(0); $(this).button('loading'); return true;" class="btn teal"  /> <button data-dismiss="alert" class="btn tan" onclick="">No, I'll create my unit</button> 
				</div>
		   		<div class="clear"></div>
				<p>Enter the unit's district. If you are in the Longs Peak Council, you search to see the options as you type. If not in Longs Peak Council, enter your district name, not including the word "district" (ex: Cache la Poudre). Thanks!</p>
				<div class="camperform float " style="width: 300px;"><input type="text" name="district" id="fdistrict" class="districtlist typeahead" value="<?php echo set_value('district'); ?>" placeholder="Tri Trails" data-toggle="tooltip" title="Enter your local district, Begin typing to see hints if you are in the Longs Peak Council! If you don't know your district, contact your council's service center or search on their website." /><label for="fdistrict">District</label></div>
				<div class="clear"></div>
				<div class="camperform float " style="width: 250px;"><input type="text" name="add" id="fadd" class="" value="<?php echo set_value('add'); ?>" placeholder="2215 23rd Avenue" data-toggle="tooltip" title="TOOLTIP" /><label for="fadd">Unit Address</label></div>
				<div class="camperform float " style="width: 150px;"><input type="text" name="city" id="fcity" class="" value="<?php echo set_value('city'); ?>" placeholder="Greeley" data-toggle="tooltip" title="TOOLTIP" /><label for="fcity">City</label></div>
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
				<div class="camperform float last" style="width: 60px;"><input type="text" name="zip" id="fzip" class="" value="<?php echo set_value('zip'); ?>" placeholder="80632" data-toggle="tooltip" title="TOOLTIP" /><label for="fzip">Zip</label></div>
	   		</div>
   		</div>
	</article>
	<article class="textsection">
	<div class="clear hr"></div>
   		<div class="container">
	   		<div class="quarter">
   				<h2>Contacts</h2>
   				<p>You can also invite people to be contacts with this unit.  <br /><br /><strong>This is optional.</strong></p>
   				<div class="clear"></div>
	   		</div>
	   		<div class="threequarter">
				<p>Enter the contact email addresses below and they will be invited to join this unit. They will get an email from us stating that you (as an admin) requested they set up an account with this unit on Camper.</p>
				<p><strong>Note!</strong> Don't invite someone who is already in Camper for this unit, first create the unit, then go to Units > The unit you created, and add the contact there. This will simply send another invite. This is a bug that is being fixed.</p>
				<div class="camperform float last" style="width: 300px;"><input type="email" name="emaila" id="faltemaila" class="camperhoverpopover" data-toggle="popover" title="This person will be invited to join the unit." value="<?php echo set_value('emaila'); ?>" placeholder="baden.powell@scouting.org" data-toggle="tooltip" title="TOOLTIP" /><label for="faltemail">Contact Email</label></div>
				<div class="clear"></div>
				<div class="camperform float last" style="width: 300px;"><input type="email" name="emailb" id="faltemailb" class="camperhoverpopover" data-toggle="popover" title="This person will be invited to join the unit." value="<?php echo set_value('emailb'); ?>" placeholder="baden.powell@scouting.org" data-toggle="tooltip" title="TOOLTIP" /><label for="faltemail">Contact Email</label></div>
				<div class="clear"></div>
				<input type="hidden" name="custom" id="custom" value="1" />
   			</div>
   		</div>
   		<div class="clear"></div>
	</article>
	<article class="textsection">
	<div class="clear hr"></div>
   		<div class="container">
	   		<div class="quarter">
   				<h2>Register</h2>
   				<p>You also have the option to register this unit for an event. <br /><br /><strong>This is optional.</strong></p>
   				<div class="clear"></div>
	   		</div>
	   		<div class="threequarter">
				<p>You can start a registration for you new unit here. Search for and select the event and session/week from the live search results to register this unit. <br /><br />This registration tool is exempt from the session/week hard or soft limit numbers, but the registration will not be final without adding payments (if required). The leaders will be able to make payments for this registration when they sign in, or you can add payments in the payments section.</p>
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
				<p>This unit will be created, your contacts will be invited, and the new unit will be registered for the chosen event session/week when you click submit. You will be able to make payments for the registration created (if applicable) as well. </p>
				<div class="clear"></div>
   				<input type="submit" name="submit" value="Create Unit" class="btn teal" data-loading-text="Creating the unit..." onclick="$(this).button('loading');"  /> <input type="reset" name="reset" value="Reset" class="btn tan"  />	
   			</div>
   		</div>
   		<div class="clear"></div>
	</article>
   	<?php echo form_close();?> 
