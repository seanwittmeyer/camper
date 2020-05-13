<?php 

/* 
 * Camper Signup from Invite View
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
			  prefetch: '/camper/api/v1/units.json?return=number&v=d124', 
			  template: [																 
				'<p class="typeahead-num">{{city}}, {{state}}</p>',							  
				'<p class="typeahead-name">{{name}}</p>',									  
				'<p class="typeahead-city">{{council}}</p>'						 
			  ].join(''),																 
			  engine: Hogan															   
			});
			$('.camperpopover').popover({html:true});
			$('.unitslist.typeahead').on('typeahead:selected', function(evt, item) {
				//window.location.href = '<?php echo base_url(); ?>units/edit/' + item['unitid'];
				$('#isthis').show();
				$('#isthisname').text(item['name']);
				$('#isthiscouncil').text(item['council']);
				$('#isthiscity').text(item['city']);
				$('#isthisstate').text(item['state']);
			})
			$('.councillist.typeahead').typeahead({							  
			  limit: '10',														
			  prefetch: '/camper/api/v1/list/councils.json',											 
			  template: [																 
				'<p class="typeahead-num">{{num}}</p>',							  
				'<p class="typeahead-name">{{name}} Council</p>',									  
				'<p class="typeahead-city">{{city}}</p>'						 
			  ].join(''),																 
			  engine: Hogan															   
			});
		});
	</script>
	<article class="textsection">
	<?php echo form_open('start');?>

   		<div class="container">
	   		<div class="quarter">
				<h2>Getting started</h2>
				<p>
				<span class="">1. Check invite <i class="icon-ok teal"></i></span><br />
				<span class="">2. Create your account <i class="icon-arrow-right"></i></span><br />
	   			<span class="tan">3. Verify your unit </span><br />
	   			<span class="tan">4. Confirm and finish </span><br />
				</p>
				<div class="clear"></div>
	   		</div>
	   		<div class="threequarter">
				<h2 class="section">Welcome to Camper</h2>
				<p>Camper is the Longs Peak Council's camp and activity registration system. You now have the ability to register your unit for activities and events including summer camps, camporees, trainings, and other upcoming activities. Camper allows you to register your unit, add a roster, preregister for activities and merit badges, and even preorder activity supplies, all in one place.</p>
				<p><strong><?php echo $invitedata['source'] ?></strong> invited you to be the alternate contact for <strong><?php echo $invitedata['unit'] ?></strong>, you can create your account here.</p>
				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
				<h2 class="section">1. Create your login</h2>
				<p>Let's get started with your email address and the password you want to use.
				<div class="camperform" style="width:400px;"><input type="text" name="email" value="<?php echo set_value('email', $invitedata['email']); ?>" placeholder="baden.powell@scouting.org" id="newemail" data-toggle="tooltip" data-placement="right" title="Your email will be your login, you will not be able to change this later" /><label>Your Email (this will be your login)</label></div>
				<div class="camperform" style="width:400px;"><input type="password" name="password" value="<?php echo set_value('password'); ?>" placeholder="password" id="newpassword" data-toggle="tooltip" data-placement="right" title="Choose a strong password with uppercase and lowercase letters and numbers" /><label>Choose a password</label></div>
				<h2 class="section">2. Personal details</h2>
				<p>The second step is to fill in your email address, name and phone number. Click next to view your unit, <?php echo $invitedata['unit'] ?>.</p>
				<div class="camperform float" style="width: 105px"><input type="text" name="first" id="ffirst" value="<?php echo set_value('first'); ?>" placeholder="Baden" data-toggle="tooltip" data-placement="right" title="Your first name or nickname you go by" /><label for="ffirst" >First Name</label></div>
				<div class="camperform float" style="width: 200px;"><input type="text" name="last" id="flast" value="<?php echo set_value('last'); ?>" placeholder="Powell" data-toggle="tooltip" data-placement="right" title="Your last name" /><label for="flast" >Last</label></div>
				<div class="camperform float last" style="width: 150px;"><input type="tel" id="ftel" onchange="formatPhone(this);" onkeydown="formatPhone(this);" name="phone" value="<?php echo set_value('phone'); ?>" placeholder="(970) 330 - 4052" data-toggle="tooltip" data-placement="right" title="Your phone number, we will call if we have any issues" /><label for="ftel" >Daytime Phone</label></div>
				<div class="clear"></div>
				<input type="hidden" name="s" value="5" />
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<input type="submit" name="submit" value="Next &rarr;" class="btn teal" data-loading-text="Preparing your account..." onclick="$(this).button('loading');" />

   			</div>
   		</div>
   		
   		
   		<div class="clear"></div>
   	<?php echo form_close();?> 
	</article>
