<?php 

/* 
 * Camper Admin Users Edit User View
 *
 * This is. 
 *
 * File: /application/views/admin/users/deactivate.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 $unit = $this->shared->get_user_unit($user->id,true); 
 if ($user->company == '0') {
 	$unittitle = "Individual Account";
 	$individual = true;
 } else {
 	$unittitle = (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')': $unit['unittype'].' '.$unit['number'];
 	$individual = false;
 }
?>
	<div class="subnav">
		<div class="container">
			<h2>Units &amp; Users</h2>
			<nav class="campersubnav">
   	    		<li><?php echo anchor("users/pending", 'Pending Invites');?></li>
   	    		<li class="active"><?php echo anchor("users", 'Users');?></li>
   	    		<li><?php echo anchor("units", 'Units');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
	<?php echo form_open(uri_string());?>
	<?php echo form_hidden('id', $user->id);?>
	<?php echo form_hidden($csrf); ?>
	<?php if (!$csrffail) { ?>
		<script>
		$(document).ready(function() {
			$('.councillist.typeahead').typeahead({                              
			  limit: '10',                                                        
			  prefetch: '<?php echo $this->config->item('camper_path'); ?>api/v1/list/councils.json',                                             
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
		});
		</script>
	<?php } ?>
   		<div class="container">
       		<div class="quarter">
   	    		<h2>Users / Editor</h2>
   	    		<p>Manage the users and units in Camper. You can view all units that have been set up and all of the users in the system, along with some details about each.</p>
   	    		<?php echo anchor("users", '&larr; All Users', 'class="btn tan"');?>
   	    		<div class="clear"></div>
       		</div>
       		<div class="threequarter">
   				<h2 class="">Edit User <?php if (!$user->active) { ?><span data-toggle="tooltip" title="You can activate <?php echo $user->first_name;?> with the green activate button below">(inactive)</span><?php } ?></h2>
   				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
   				<?php if (!$user->active) { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon-info-sign red"></i> <?php echo $user->first_name.' '.$user->last_name;?>'s account is inactive. <?php echo anchor('users/'.$user->id.'/activate', 'Activate &rarr;', 'class="btn btn-small teal right"'); ?></div><?php } ?> 
   				<div class="camperform float" style="width: 16%"><input type="text" name="first_name" value="<?php echo $user->first_name;?>" data-toggle="tooltip" data-placement="right" title="Your" /><label>First Name</label></div>
   				<div class="camperform float" style="width: 30%"><input type="text" name="last_name" value="<?php echo $user->last_name;?>" data-toggle="tooltip" data-placement="right" title="Your" /><label>Last</label></div>
   				<div class="camperform float last" style="" data-toggle="tooltip" title="You"><span><?php echo $user->email;?></span><label>User Email</label></div>
   				<div class="clear hr"></div>

   				<?php if (!$individual) { ?><div class="right"><?php if ($unit) { echo anchor("units/".$unit['id'], 'View/Manage '.$unittitle.' &rarr;', 'class="btn tan"'); }?></div><?php } ?>
   				<div class="camperform float" style="width: 20%"><input type="tel" name="phone" onchange="formatPhone(this);" onkeydown="formatPhone(this);" value="<?php echo $user->phone;?>" data-toggle="tooltip" data-placement="right" title="Your" /><label>Daytime Phone</label></div>
   				<div class="camperform float" style="" data-toggle="tooltip" data-placement="right" title="You can change the unit by finding the unit in the Units section." ><span><?php if ($unit) { echo $unittitle; } else { ?>No Unit<?php } ?></span><label>Unit</label></div>
   				<?php if($individual) { ?>
   				<div class="clear hr"></div>
   				<p>This is an individual account, which means that this user has unit data associated with them and not with any unit in Camper. </p><p><strong>This will not add <?php echo $user->first_name; ?> to the unit below, </strong> it is simply a label. You can add users to units by visiting the unit you want to add users to.</p>
		   		<div class="camperform float" style="width: 15%;">
		   			<select name="unit[unittype]">
						<option value="Troop"<?php if ($unit['unittype'] == 'Troop') { ?> selected="selected"<?php } ?>>Troop</option>
						<option value="Crew"<?php if ($unit['unittype'] == 'Crew') { ?> selected="selected"<?php } ?>>Crew</option>
						<option value="Ship"<?php if ($unit['unittype'] == 'Ship') { ?> selected="selected"<?php } ?>>Ship</option>
						<option value="Pack"<?php if ($unit['unittype'] == 'Pack') { ?> selected="selected"<?php } ?>>Pack</option>
						<option value="Den"<?php if ($unit['unittype'] == 'Den') { ?> selected="selected"<?php } ?>>Den</option>
						<option value="None"<?php if ($unit['unittype'] == 'None') { ?> selected="selected"<?php } ?>>-- No Unit --</option>
					</select>
					<label class="">Unit Type</label>
				</div>
   				<div class="camperform float" style="width: 10%"><input type="text" id="fnumber" name="unit[number]" value="<?php echo $unit['number']; ?>" /><label for="fnumber">Number</label></div>
   				<div class="camperform float" style="width: 30%"><input type="text" class="typeahead councillist" id="fcouncil" name="unit[council]" value="<?php echo $unit['council']; ?>" /><label for="fcouncil">Council</label></div>
   				<div class="camperform float" style="width: 30%"><input type="text" class="typeahead districtlist" id="fdistrict" name="unit[district]" value="<?php echo $unit['district']; ?>" /><label for="fdistrict">District</label></div>
   				<div class="clear"></div>
   				<div class="camperform float" style="width: 30%"><input type="text" id="faddress" name="unit[address]" value="<?php echo $unit['address']; ?>" /><label for="faddress">Address</label></div>
   				<div class="camperform float" style="width: 20%"><input type="text" id="fcity" name="unit[city]" value="<?php echo $unit['city']; ?>" /><label for="fcity">City</label></div>
		   		<div class="camperform float" style="">
					<select id="fstate" name="unit[state]"> 
						<option value="<?php echo $unit['state']; ?>"><?php $i=$this->config->item('camper_states'); echo $i[$unit['state']]; ?></option> 
						<optgroup label="... or choose another:">
							<option value="AL">Alabama</option> 
							<option value="AK">Alaska</option> 
							<option value="AZ">Arizona</option> 
							<option value="AR">Arkansas</option> 
							<option value="CA">California</option> 
							<option value="CO">Colorado</option> 
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
						</optgroup>
					</select><label for="fstate">State</label>
		   		</div>
   				<div class="camperform float" style="width: 10%"><input type="text" id="fzip" name="unit[zip]" value="<?php echo $unit['zip']; ?>" /><label for="fzip">Zip</label></div>
   				<?php } ?>
   				<div class="clear hr"></div>
   				<div class="camperform float " style="width: 27%" data-toggle="tooltip" title="You"><input type="password" value="" placeholder="••••••••" name="password" /><label>New Password</label></div>
   				<div class="camperform float " style="width: 27%" data-toggle="tooltip" title="You"><input type="password" value="" placeholder="••••••••" name="password_confirm" /><label>Repeat Password</label></div>
   				<div class="clear hr"></div>

				<?php foreach ($groups as $group):
					$gID=$group['id'];
					$checked = null;
					$item = null;
					foreach($currentGroups as $grp) {
						if ($gID == $grp->id) {
							$checked= ' checked="checked"';
						break;
						}
					}
				?><div class="camperform float cbl" style=""><input type="checkbox"<?php echo $checked;?> class="cbl" value="<?php echo $group['id'];?>" name="groups[]" id="f<?php echo $group['name'];?>" /><label for="f<?php echo $group['name'];?>" class="cbl" ><?php echo $group['name'];?></label></div>
				<?php endforeach; ?>

				<?php /*foreach ($groups as $group):?>
				<label class="checkbox">
				<?php
					$gID=$group['id'];
					$checked = null;
					$item = null;
					foreach($currentGroups as $grp) {
						if ($gID == $grp->id) {
							$checked= ' checked="checked"';
						break;
						}
					}
				?>
				<input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
				<?php echo $group['name'];?>
				</label>
				<?php endforeach */ ?>
				<div class="clear hr"></div>
				<p><div class="right"><?php if (!$individual) echo anchor('users/'.$user->id.'/individual', 'Set Individual &rarr;', 'class="btn tan camperhoverpopover" data-toggle="popover" title="Make '.$user->first_name.' an Individual" data-placement="top" data-content="<strong>This will make this use an individual user in Camper</strong><br><br>When you do this, '.$user->first_name.' will be removed from any associated unit and made eligible for individual registrations."'); ?> <?php echo ($user->active) ? anchor('users/'.$user->id.'/deactivate', 'Deactivate &rarr;', 'class="btn red"') : anchor('users/'.$user->id.'/activate', 'Activate &rarr;', 'class="btn teal"');?></div><input type="submit" name="submit" value="Edit User" class="btn teal"  /> <?php echo anchor("users", 'Nevermind', 'class="btn tan"');?></p>
   			</div>
   		</div>
   		<div class="clear"></div>
   	<?php echo form_close();?> 
	</article>
