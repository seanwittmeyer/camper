<?php 

/* 
 * Camper New Signup View
 *
 * This is the leader view of the "My Account" section in camper. This offers
 * custom details specific to the leader part of the site.
 *
 * File: /application/views/account/leader.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 10 1909)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

?>
	<script>
		$(document).ready(function() {
			$('.unitslist.typeahead').typeahead({							  
			  limit: '10',														
			  prefetch: '/camper/api/v1/units.json?return=number', 
			  header: '<p class="typeaheadtitle"><i class="icon-search"></i> Unit Search - Click on your unit if it shows up below.</p>',
			  template: [																 
				'<p class="typeahead-num">{{city}}, {{state}}</p>',							  
				'<p class="typeahead-name">{{name}}</p>',									  
				'<p class="typeahead-city">{{council}}</p>'						 
			  ].join(''),																 
			  engine: Hogan															   
			});
			$('.camperpopover').popover({html:true});
			$('.unitslist.typeahead').on('typeahead:selected typeahead:autocompleted', function(evt, item) {
				//window.location.href = '<?php echo base_url(); ?>units/edit/' + item['unitid'];
				$('#isthis').show();
				$('#isthisname').text(item['name']);
				$('#isthiscouncil').text(item['council']);
				$('#isthiscity').text(item['city']);
				$('#isthisstate').text(item['state']);
				$('#isthisid').val(item['unitid']);
			})
			$('.councillist.typeahead').typeahead({							  
			  limit: '10',														
			  prefetch: '/camper/api/v1/list/councils.json',
			  header: '<p class="typeaheadtitle"><i class="icon-search"></i> Council Search - Click on your council below</p>',
			  template: [																 
				'<p class="typeahead-num">{{num}}</p>',							  
				'<p class="typeahead-name">{{name}} Council</p>',									  
				'<p class="typeahead-city">{{city}}</p>'						 
			  ].join(''),																 
			  engine: Hogan															   
			});
			$('.districtlist.typeahead').typeahead({                              
			  limit: '10',                                                        
			  prefetch: '/camper/api/v1/list/districts.json',                                             
			  template: [                                                                 
			    '<p class="typeahead-name">{{name}} District</p>',                                      
			    '<p class="typeahead-city">{{schools}}</p>'                         
			  ].join(''),                                                                 
			  engine: Hogan                                                               
			});
			function setloading() { 
				$('#finalstep').button('loading'); 
			}
			$('.hiddenform').hide();
		});
		function setunittype(element,unittype) {
			$('.hiddenform').show();
			$('.bigoption').removeClass('active');
			$(element).addClass('active');
			$('#unittype').val(unittype);
			if (unittype == 'Individual') {
				//alert('den selected');
				$('#associatednumber').addClass('hidden');
				$('.ihide').addClass('hidden');
				$('.ishow').removeClass('hidden');
				$('#individual').val(1);
			} else {
				//alert('not selected');
				$('#associatednumber').addClass('hidden');
				$('.ihide').removeClass('hidden');
				$('.ishow').addClass('hidden');
				$('#individual').val(0);
			}
		}
		function checkden() { 
			var unitselect = $('#unittype').val();
			if (unitselect == 'Den') {
				//alert('den selected');
				$('#associatednumber').removeClass('hidden');
				$('.ihide').removeClass('hidden');
				$('.ishow').addClass('hidden');
				$('#individual').val(0);
			} else if (unitselect == 'Individual') {
				//alert('den selected');
				$('#associatednumber').addClass('hidden');
				$('.ihide').addClass('hidden');
				$('.ishow').removeClass('hidden');
				$('#individual').val(1);
			} else {
				//alert('not selected');
				$('#associatednumber').addClass('hidden');
				$('.ihide').removeClass('hidden');
				$('.ishow').addClass('hidden');
				$('#individual').val(0);
			}
			//$('#finalstep').button('loading'); 
		}
	</script>
	<article class="textsection">
	<?php echo form_open(uri_string());?>

   		<div class="container">
	   		<div class="quarter">
				<h2>Getting started</h2>
				<p><?php if (empty($step)) { $step = 1; } $a = $step; ?>
				<span class="<?php if($a < 1) { ?>tan<?php } ?>">1. Choose email and password <?php if($a > 1) { ?><i class="icon-ok teal"></i><?php } elseif ($a == 1) { ?><i class="icon-arrow-right"></i><?php } ?></span><br />
				<span class="<?php if($a < 2) { ?>tan<?php } ?>">2. Create your account <?php if($a > 2) { ?><i class="icon-ok teal"></i><?php } elseif ($a == 2) { ?><i class="icon-arrow-right"></i><?php } ?></span><br />
	   			<span class="<?php if($a < 3) { ?>tan<?php } ?>">3. Create your unit <?php if($a > 3) { ?><i class="icon-ok teal"></i><?php } elseif ($a == 3) { ?><i class="icon-arrow-right"></i><?php } ?></span><br />
	   			<span class="<?php if($a < 4) { ?>tan<?php } ?>">4. Confirm and finish <?php if($a > 4) { ?><i class="icon-ok teal"></i><?php } elseif($a == 4) { ?><i class="icon-arrow-right"></i><?php } ?></span><br />
				</p>
				<!--<input type="submit" name="submit" value="Save Changes" class="btn blue"  />-->
				<!--<div class="right"><a href="#modal_change_password" class="sbtn tan" data-toggle="modal">Change Password &rarr;</a></div>-->

				<div class="clear"></div>
	   		</div>
	   		<div class="threequarter">
<?php if ($step == 1) { // Step 1 - Username and password ?>
				<h2 class="section">1. Sign Up</h2>
				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
				<p>Welcome to Camper, our camp and activity registration system. You now have the ability to register your unit for activities and events including summer camps, camporees, trainings, and other upcoming activities. Camper allows you to register your unit, add a roster, preregister for activities and merit badges, and even preorder activity supplies, all in one place.</p>
				<p>Let's get started with your email address and the password you want to use.
				<div class="camperform" style="width:400px;"><input type="text" name="email" value="<?php echo set_value('email'); ?>" placeholder="baden.powell@scouting.org" id="newemail" data-toggle="tooltip" data-placement="right" title="Enter your email address, this will be your login" /><label>Your Email (this will be your login)</label></div>
				<div class="camperform" style="width:400px;"><input type="password" name="password" value="<?php echo set_value('password'); ?>" placeholder="••••••••" id="newpassword" data-toggle="tooltip" data-placement="right" title="Choose a strong password with uppercase and lowercase letters and numbers" /><label>Choose a password</label></div>
	   			<input type="hidden" name="s" value="1" />
	   			<input type="hidden" name="token" value="1" />
				<input type="submit" name="submit" value="Next &rarr;" class="btn blue" data-loading-text="One moment please..." onclick="$(this).button('loading');" />
<?php } elseif ($step == 2) { // Step 2 - User account details ?>
				<h2 class="section">2. Create your account</h2>
				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
				<p>Creating an account is as easy. Start by filling out your email address, name and phone number and then we'll help you find your unit.</p>
				<div class="camperform float" style="width: 105px"><input type="text" name="first" id="ffirst" value="<?php echo set_value('first'); ?>" placeholder="Baden" data-toggle="tooltip" data-placement="right" title="Your first name or nickname you go by" /><label for="ffirst" >First Name</label></div>
				<div class="camperform float" style="width: 200px;"><input type="text" name="last" id="flast" value="<?php echo set_value('last'); ?>" placeholder="Powell" data-toggle="tooltip" data-placement="right" title="Your last name" /><label for="flast" >Last</label></div>
				<div class="camperform float last" style="width: 330px" data-toggle="tooltip" title="You can't change your email address"><input type="text" id="femail" placeholder="baden.powell@scouting.org" value="<?php if(!empty($email)) { echo $email; ?>" disabled="disabled<?php } ?>" /><label for="femail" >Email Address</label></div>
				<div class="clear"></div>
				<div class="camperform float last" style="width: 150px;"><input type="tel" id="ftel" onchange="formatPhone(this);" onkeydown="formatPhone(this);" name="phone" value="<?php echo set_value('phone'); ?>" placeholder="(970) 330 - 4052" data-toggle="tooltip" data-placement="right" title="Your phone number, we will call if we have any issues" /><label for="ftel" >Daytime Phone</label></div>
				<div class="clear"></div>
				<input type="hidden" name="s" value="2" />
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<input type="submit" name="submit" value="Next &rarr;" data-loading-text="Creating your account..." onclick="$(this).button('loading');" class="btn teal"  />
<?php } elseif ($step == 3) { // Step 3 - Unit basics ?>
				<h2 class="section">3. Create your unit</h2>
				<p>The second step is to create your Scouting unit. Start with the type and number, then set your council.</p>
				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
				<div class="clear"></div>
				<div class="accountchooser">
					<h3 class="center">Which type of account would you like to create?</h3>
					<div class="bigoption" id="accountchooserindividual" onclick="setunittype(this,'Individual');">
						<h3>Individual</h3>
						<p>Choose this if you want to register yourself or your son/daughter for events.</p>
						<p>This option is best for parents.</p>
					</div>
					<div class="bigoption" id="accountchooserunit" onclick="setunittype(this,'Troop');">
						<h3>Unit</h3>
						<p>Choose this if you want to register an entire Troop/Pack/Den/Crew for events.</p>
						<p>This option is best for unit leaders.</p>
					</div>
				   	<div class="clear"></div>
				</div>
				<div class="hiddenform">
			   		<div class="camperform float" style="width: 90px;">
				   		<select id="unittype" name="unittype" onchange="checkden();">
							<option id="unittypetroop" value="Troop" selected="selected">Troop</option>
							<option value="Crew">Crew</option>
							<option value="Ship">Ship</option>
							<option value="Pack">Pack</option>
							<option value="Den">Den</option>
							<option id="unittypeindividual" value="Individual">Individual</option>
						</select>
						<label class="">Unit Type</label>
					</div>
			   		<div class="camperform float ihide" style="width: 50px"><input type="text" class="" name="number" value="<?php echo set_value('number'); ?>" placeholder="1" /><label>Number</label></div>
			   		<!--<div class="camperform float ihide" style="width: 50px"><input type="text" class="unitslist typeahead" name="number" value="<?php echo set_value('number'); ?>" placeholder="1" data-toggle="tooltip" title="Unit number, if you see your unit in the search list, click it to automatically set up your unit." /><label>Number</label></div>-->
			   		<div id="associatednumber" class="camperform float hidden" style="width: 50px"><input type="text" class="typeahead" name="associatednumber" value="<?php echo set_value('associatednumber'); ?>" placeholder="1" data-toggle="tooltip" title="Enter the pack number for the pack your den is a part of" /><label>Pack</label></div>
					<div class="camperform float ihide " style="width: 300px;"><input type="text" name="council" id="fcouncil" class="councillist typeahead" value="<?php echo set_value('council'); ?>" placeholder="Longs Peak Council" data-toggle="tooltip" title="Enter your local Scout council, Begin typing to see hints! If you are from Northern Colorado, Southern Wyoming or Western Nebraska, your council is Longs Peak Council" /><label for="fcouncil">Council</label></div>
			   		<div class="clear"></div>
					<div class="well well-small" id="isthis" style="display: none;"><button type="button" class="close" data-dismiss="alert">&times;</button>
						<h4>Is this your unit?</h4>
						<p><span id="isthisname">Unit 1</span> of <span id="isthiscity">City</span>, <span id="isthisstate">ST</span><br />Council: <span id="isthiscouncil">Some Council</span></p> 
						<input id="isthisid" value="0" type="hidden" />
				   		<div class="clear"></div>
				   		<input type="submit" name="submit" value="Yes, next step &rarr;" data-loading-text="One moment please..." onclick="$('#unitid').val($('#isthisid').val()); $('#custom').val(0); $(this).button('loading'); return true;" class="btn teal"  /> <button data-dismiss="alert" class="btn tan" onclick="">No, I'll create my unit</button> 
					</div>
			   		<div class="clear"></div>
			   		<p class="ishow hidden">You have chosen to create an <strong>individual account</strong> on Camper. Individual accounts are special accounts that let you register for certain events as yourself. Individual accounts do not have rosters and are not associated with any units. Please choose the council you are a member of, and the district. If you are not part of any specific district, please specify none.</p>
					<p class="ihide">Enter your unit's district. If you are in the Longs Peak Council, you search to see the options as you type. <strong>If you are not in our council</strong>: Please enter your district name, not including the word "district" (ex: Cache la Poudre). Thanks!</p>
					<div class="camperform float ishow hidden" style="width: 300px;"><input type="text" name="icouncil" id="ifcouncil" class="councillist typeahead" value="<?php echo set_value('council'); ?>" placeholder="Longs Peak Council" data-toggle="tooltip" title="Enter your local Scout council, Begin typing to see hints! If you are from Northern Colorado, Southern Wyoming or Western Nebraska, your council is Longs Peak Council" /><label for="ifcouncil">Council</label></div>
					<div class="camperform float " style="width: 300px;"><input type="text" name="district" id="fdistrict" class="districtlist typeahead" value="<?php echo set_value('district'); ?>" placeholder="Tri Trails" data-toggle="tooltip" title="Enter your local district, Begin typing to see hints if you are in the Longs Peak Council! If you don't know your district, contact your council's service center or search on their website." /><label for="fdistrict">District</label></div>
					<div class="clear"></div>
					<p class="ishow hidden">Please specify your unit you are a member of. We use this to help identify you at events you register for as an individual.</p>
					<div class="camperform float ishow hidden" style="width: 110px;">
				   		<select name="iunittype" onchange="checkden();">
							<option value="Troop" selected="selected">Troop</option>
							<option value="Crew">Crew</option>
							<option value="Ship">Ship</option>
							<option value="Pack">Pack</option>
							<option value="Team">Team</option>
							<option value="None">-- No Unit --</option>
						</select>
						<label class="">Unit Type</label>
					</div>
			   		<div class="camperform float ishow hidden" style="width: 50px"><input type="text" class="typeahead" name="inumber" value="<?php echo set_value('number'); ?>" placeholder="1" data-toggle="tooltip" title="Unit number" /><label>Number</label></div>
			   		<div class="clear"></div>
					<div class="camperform float " style="width: 250px;"><input type="text" name="add" id="fadd" class="" value="<?php echo set_value('add'); ?>" placeholder="2215 23rd Avenue" data-toggle="tooltip" title="Unit mailing address" /><label for="fadd">Unit Address</label></div>
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
					<div class="camperform float last" style="width: 60px;"><input type="text" name="zip" id="fzip" class="" value="<?php echo set_value('zip'); ?>" placeholder="80632" data-toggle="tooltip" title="You unit's zip code without the +4 extension" /><label for="fzip">Zip</label></div>
					<div class="clear hr ihide"></div>
					<p class="ihide">Each unit is required to have at least 2 leaders set up in Camper. This follows the 2-deep leadership principles for Scouting online. We'll make you the primary contact for the unit but you'll need to set an alternate contact. <strong>This must be another leader in your unit and is required</strong> for registering units for events online. Enter the alternate contact's email address below and they will be invited to join your unit. They will get an email from us stating that you requested they set up an account on Camper.
					<div class="camperform float last ihide" style="width: 300px;"><input type="email" name="altemail" id="faltemail" class="camperhoverpopover" data-toggle="popover" title="Alternate Contact" data-placement="top" data-trigger="focus" data-content="<strong>This must be another leader's email from your unit.</strong> <br><br>Camper uses 2-deep leadership online, choose a leader to help manage your unit on Camper." value="<?php echo set_value('altemail'); ?>" placeholder="baden.powell@scouting.org" /><label for="faltemail">Alternate Contact Email</label></div>
					<div class="clear"></div>
					<input type="hidden" name="custom" id="custom" value="1" />
					<input type="hidden" name="individual" id="individual" value="0" />
					<input type="hidden" name="unitid" id="unitid" value="0" />
					<input type="hidden" name="s" value="3" />
					<input type="hidden" name="token" value="<?php echo $token; ?>" />
					<input type="submit" name="submit" value="Next &rarr;" class="btn teal" data-loading-text="Creating your unit..." onclick="$(this).button('loading');" />
				</div><!-- end hidden form -->

<?php } elseif ($step == 4) { // Step 4 - Unit Alt Contact and Confirm ?>
				<h2 class="section">4. Make sure everything looks right</h2>
				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
				<p><strong>Alright <?php echo $record['first_name']; ?>,</strong><br />We want to let you confirm everything before we get your account ready for you.</p>
				<h3>Your user account</h3>
				<p>Your name is <strong><?php echo $record['first_name']; ?> <?php echo $record['last_name']; ?></strong> and your phone number is <strong><?php echo $record['phone']; ?></strong>. Your login will be your email address, <strong><?php echo $record['email']; ?></strong>, and the password you specified.</p>
				<?php if (isset($record['individual'])) { ?>
				<p>You set up an <strong>individual account</strong> which you will be able to register as yourself for events.</p>
				<?php } else { ?>
				<h3>Your unit</h3>
				<?php if (isset($record['invite'])) { ?>
				<p>Your unit, <strong><?php echo $record['unittype']; ?> <?php echo $record['number']; ?></strong> from <strong><?php echo $record['council']; ?> Council</strong> has already been set up. Your <?php echo $record['unittype']; ?> can receive mail at <strong><?php echo $record['address']; ?>, <?php echo $record['city']; ?>, <?php echo $record['state']; ?> <?php echo $record['zip']; ?></strong>, and you are authorized to register your unit for events.</p>
				<p>You will be an <strong>alternate contact</strong> for your <?php echo $record['unittype']; ?>.</p>
				<?php } elseif (isset($record['requestunit'])) { ?>
				<p>Your unit, <strong><?php echo $record['unittype']; ?> <?php echo $record['number']; ?></strong> from <strong><?php echo $record['council']; ?> Council</strong> has already been set up. Your <?php echo $record['unittype']; ?> can receive mail at <strong><?php echo $record['address']; ?>, <?php echo $record['city']; ?>, <?php echo $record['state']; ?> <?php echo $record['zip']; ?></strong>. </p>
				<p>When you click finish, the <strong>primary contact</strong> for <strong><?php echo $record['unittype']; ?> <?php echo $record['number']; ?></strong> will get a request to approve your access to the <?php echo $record['unittype']; ?>. Once they approve, you will become an <strong>alternate contact</strong> for your <?php echo $record['unittype']; ?>.</p>
				<?php } else { ?>
				<p>You are setting up <strong><?php echo $record['unittype']; ?> <?php echo $record['number']; ?></strong> from <strong><?php echo $record['council']; ?> Council</strong>. Your <?php echo $record['unittype']; ?> can receive mail at <strong><?php echo $record['address']; ?>, <?php echo $record['city']; ?>, <?php echo $record['state']; ?> <?php echo $record['zip']; ?></strong>, and you are authorized to register your unit for events.</p>
				<p>Since you are creating your unit's account in Camper, the council registration system, you will be the <?php echo $record['unittype']; ?>'s <strong>primary contact</strong>. This system is best used with 2 contacts, yourself and an alternate contact. You invited <strong><?php echo $record['altemail']; ?></strong> to be your <?php echo $record['unittype']; ?>'s alternate contact.</p>
				<?php } } ?>
				<p>Everything look good? Just hit 'Create My Account' and we'll finish the registration and get you into Camper!</p>
				<input type="hidden" name="s" value="4" />
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
	   			<input type="submit" id="finalstep" name="submit" value="Create My Account &rarr;" data-loading-text="Setting up your account..." onclick="$(this).button('loading');"  class="btn teal"  />
	   			<p>By signing up, you understand and approve of the way we will use the information you put into Camper. <br /><a href="help#privacy" target="_blank">Read our privacy policy &rarr;</a> (opens in a new window)
<?php } elseif ($step == 6) { // Step 6 - Creation Confirmation ?>
				<h2><i class="icon-ok teal"></i> Your account has been created</h2>
				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
				<p>Awesome, you are almost ready to register your unit for events. The last step is to confirm your email address and activate your account.</p>
				<p>We sent you an email with an 'activate' link, simply click on it or paste the link into your browser and you'll be all set to login and register your unit for a variety of events from summer camps and camporees to weekend trips and trainings.</p>
				<h3>Didn't get the email?</h3>
				<p>Be sure to check your spam inbox, sometimes messages from Camper like to hide there. Emails from Camper can take a minute or two to send. If you are still having trouble, contact us and we can help.</p>
<?php } // end if ?>
   			</div>
   		</div>
   		
   		
   		<div class="clear"></div>
   	<?php echo form_close();?> 
	</article>