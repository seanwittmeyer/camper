<?php 

/* 
 * Camper Admin Units View
 *
 * This is. 
 *
 * File: /application/views/admin/users/units.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 
 // Prep 
 $leaderfirst = $this->shared->array_column_fix($leaders, 'first_name', 'id');
 $leaderlast = $this->shared->array_column_fix($leaders, 'last_name', 'id');

?>
	<script>
		$(document).ready(function() {
			$('.unitslist.typeahead').typeahead({                              
			  limit: '10',                                                        
			  prefetch: '<?php echo $this->config->item('camper_path'); ?>api/v1/units.json', 
			  template: [                                                                 
			    '<p class="typeahead-num">{{city}}, {{state}}</p>',                              
			    '<p class="typeahead-name">{{name}}</p>',                                      
			    '<p class="typeahead-city">{{council}}</p>'                         
			  ].join(''),                                                                 
			  engine: Hogan                                                               
			});
			$('.camperpopover').popover({html:true});
			$('.typeahead').on('typeahead:selected', function(evt, item) {
				window.location.href = '<?php echo base_url(); ?>units/' + item['unitid'];
			})
			$('.typeahead').on('typeahead:autocompleted', function(evt, item) {
				window.location.href = '<?php echo base_url(); ?>units/' + item['unitid'];
			})
		});
	</script>
	<div class="subnav">
		<div class="container">
			<h2>Units &amp; Users</h2>
			<nav class="campersubnav">
   	    		<li><?php echo anchor("users/pending", 'Pending Invites');?></li>
   	    		<li><?php echo anchor("users", 'Users');?></li>
   	    		<li class="active"><?php echo anchor("units", 'Units');?></li>
			</nav>
		</div>
	</div>
	<article class="content">
   		<div class="container">
       		<div class="pull">
   	    		<h2 class="pull">Units</h2>
   	    		<p>Units are just like real Scouting units, each unit is associated with leaders and can be registered for events. Camper lets you view and manage all units in the system.</p>
   	    		<p><?php echo anchor("units/new", '<i class="icon-plus"></i> Add a new unit', 'class="btn teal"');?></p>
   	    		<div class="clear"></div>
       		</div>
       		<div class="tab-content inner-push">
   				<h2 class="">All Units</h2>
   				<p>You can view and manage all of the units set up in Camper here. Click on a unit's primary contact to see contact details. You can change a units details and contact by clicking on edit.</p>
   				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
   				
   				<p>You can also locate a unit by searching. Start with the unit type and number, contact name, city or council.</p>
   		   		<div class="camperform float search" style="width: 60%"><i class="icon-search"></i><input class="ico unitslist typeahead" type="text" name="units" data-toggle="tooltip"  placeholder="Troop 1 or Greeley, Colorado..."  title="Search for a unit by type, number, name, city, council or contact name" /><label>Unit Search</label></div>

   				<div class="clear"></div>
   	    		<ul id="detailstabs" class="teal">
   					<li class="active"><a href="#unitstroops" data-toggle="tab">Troops &amp; Teams</a></li>
   					<li class=""><a href="#unitspacks" data-toggle="tab">Packs</a></li>
   					<li class=""><a href="#unitsdens" data-toggle="tab">Dens</a></li>
   					<li class=""><a href="#unitscrews" data-toggle="tab">Crews &amp; Ships</a></li>
   					<li class=""><a href="#unitssolo" data-toggle="tab">Individuals</a></li>
   				</ul>
   				<?php // print_r($leaders); ?>
   	    		<div class="tab-content">
   					<div class="tab-pane fade  in active" id="unitstroops">
   					<!--<h5 class="section">All Users</h5>-->
   				  		<table class="table table-condensed">
   				  			<thead>
   				  	    	<tr><th>Troop</th><th>City</th><th>Council</th><th>Contact</th><th>Tools</th></tr>
   				  			</thead>
   				  			<tbody>
							<?php $t = "Troop"; foreach ($troops as $unit): ?>
   				  		    	<tr>
	   				  		    	<td><?php echo anchor("units/".$unit['id'], $unit['unittype'].' '.$unit['number']);?></td>
	   				  		    	<td><?php echo $unit['city'];?>, <?php echo $unit['state'];?></td>
	   				  		    	<td><?php echo $unit['council'];?></td>
	   				  		    	<td><?php if (!empty($leaderfirst[$unit['primary']])) { echo $leaderfirst[$unit['primary']].' '.$leaderlast[$unit['primary']]; } else { ?><i class="icon-exclamation-sign red"></i> No Contact<?php } ?></td>
	   				  		    	<td><a href="units/edit/<?php echo $unit['id']; ?>">View/Edit</a></td>
   				  		    	</tr>
							<?php endforeach;?>
   				  		</table>
   	    			</div>
   					<div class="tab-pane fade" id="unitspacks">
   					<!--<h5 class="section">All Users</h5>-->
   				  		<table class="table table-condensed">
   				  			<thead>
   				  	    	<tr><th>Packs</th><th>City</th><th>Council</th><th>Contact</th><th>Tools</th></tr>
   				  			</thead>
   				  			<tbody>
							<?php $t = "Pack"; foreach ($packs as $unit): ?>
   				  		    	<tr>
	   				  				<?php $unittitle = (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')': $unit['unittype'].' '.$unit['number']; ?>
	   				  		    	<td><?php echo anchor("units/".$unit['id'], $unittitle);?></td>
	   				  		    	<td><?php echo $unit['city'];?>, <?php echo $unit['state'];?></td>
	   				  		    	<td><?php echo $unit['council'];?></td>
	   				  		    	<td><?php if (!empty($leaderfirst[$unit['primary']])) { echo $leaderfirst[$unit['primary']].' '.$leaderlast[$unit['primary']]; } else { ?><i class="icon-exclamation-sign red"></i> No Contact<?php } ?></td>
	   				  		    	<td><a href="units/edit/<?php echo $unit['id']; ?>">View/Edit</a></td>
   				  		    	</tr>
							<?php endforeach;?>
   				  		</table>
   	    			</div>
   					<div class="tab-pane fade" id="unitsdens">
   					<!--<h5 class="section">All Users</h5>-->
   				  		<table class="table table-condensed">
   				  			<thead>
   				  	    	<tr><th>Den</th><th>Pack</th><th>City</th><th>Council</th><th>Contact</th><th>Tools</th></tr>
   				  			</thead>
   				  			<tbody>
							<?php $t = "Den"; foreach ($dens as $unit): ?>
   				  		    	<tr>
	   				  		    	<td><?php echo anchor("units/".$unit['id'], $unit['unittype'].' '.$unit['number']); ?></td>
	   				  		    	<td><?php echo $unit['associatedunit'].' '.$unit['associatednumber']; ?></td>
	   				  		    	<td><?php echo $unit['city'];?>, <?php echo $unit['state']; ?></td>
	   				  		    	<td><?php echo $unit['council'];?></td>
	   				  		    	<td><?php if (!empty($leaderfirst[$unit['primary']])) { echo $leaderfirst[$unit['primary']].' '.$leaderlast[$unit['primary']]; } else { ?><i class="icon-exclamation-sign red"></i> No Contact<?php } ?></td>
	   				  		    	<td><a href="units/edit/<?php echo $unit['id']; ?>">View/Edit</a></td>
   				  		    	</tr>
							<?php endforeach;?>
   				  		</table>
   	    			</div>
   					<div class="tab-pane fade" id="unitscrews">
   					<!--<h5 class="section">All Users</h5>-->
   				  		<table class="table table-condensed">
   				  			<thead>
   				  	    	<tr><th>Crews &amp; Ships</th><th>City</th><th>Council</th><th>Contact</th><th>Tools</th></tr>
   				  			</thead>
   				  			<tbody>
							<?php foreach ($crews as $unit): ?>
   				  		    	<tr>
	   				  		    	<td><?php echo anchor("units/".$unit['id'], $unit['unittype'].' '.$unit['number']);?></td>
	   				  		    	<td><?php echo $unit['city'];?>, <?php echo $unit['state'];?></td>
	   				  		    	<td><?php echo $unit['council'];?></td>
	   				  		    	<td><?php if (!empty($leaderfirst[$unit['primary']])) { echo $leaderfirst[$unit['primary']].' '.$leaderlast[$unit['primary']]; } else { ?><i class="icon-exclamation-sign red"></i> No Contact<?php } ?></td>
	   				  		    	<td><a href="units/edit/<?php echo $unit['id']; ?>">View/Edit</a></td>
   				  		    	</tr>
							<?php endforeach;?>
   				  		</table>
   	    			</div>
   					<div class="tab-pane fade" id="unitssolo">
   					<!--<h5 class="section">All Users</h5>-->
   				  		<table class="table table-condensed">
   				  			<thead>
   				  	    	<tr><th>Name</th><th>Unit</th><th>Email</th><th>Tools</th></tr>
   				  			</thead>
   				  			<tbody>
							<?php foreach ($individuals as $user): ?>
   				  		    	<tr>
	   				  		    	<td><?php echo anchor('users/'.$user['id'], $user['first_name'].' '.$user['last_name']) ;?></td>
	   				  		    	<td><span data-toggle="tooltip" title="This unit is the user's assiciated unit, it is not a full unit in Camper"><?php $unit = $this->shared->get_user_unit($user['id'],true); if ($unit) { echo $unit['unittype'].' '.$unit['number']; } else { ?><i class="icon-exclamation-sign"></i> None Specified<?php } ?></span></td>
	   				  		    	<td><?php echo $user['email'];?></td>
	   				  		    	<td><?php echo ($user['active']) ? '<a href="users/deactivate/'.$user['id'].'">Deactivate</a>' : '<a href="users/activate/'.$user['id'].'">Activate</a>'; ?> <?php echo anchor("users/edit/".$user['id'], 'Edit') ;?></td>
   				  		    	</tr>
							<?php endforeach;?>
   				  	    	<!--<tr><td>Charles Emerson Winchester</td><td>Crew 464</td><td>cewinchester@4077.army.mil</td><td><a href="#" onclick="edituser(55);"><i class="icon-edit"></i> Edit</a></td></tr>-->
   				  		</table>
   	    			</div>
   				</div>
   				<!--<h2>Tools</h2>
   				<p>You can easily add members to your unit. By adding a member here, you will be adding them to the unit, not registering them for a specific event or activity. You can do that in the Register section.</p>
   				<p><a href="auth/create_user">Create User</a></p>
				<!--	<?php foreach ($users as $user):?>
						<tr>
							<td><?php echo $user->first_name;?></td>
							<td><?php echo $user->last_name;?></td>
							<td><?php echo $user->email;?></td>
							<td>
								<?php foreach ($user->groups as $group):?>
									<?php echo anchor("auth/edit_group/".$group->id, $group->name) ;?><br />
				                <?php endforeach?>
							</td>
							<td><?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, lang('index_active_link')) : anchor("auth/activate/". $user->id, lang('index_inactive_link'));?></td>
							<td><?php echo anchor("auth/edit_user/".$user->id, 'Edit') ;?></td>
						</tr>
					<?php endforeach;?>
				</table>
				
				<p><?php echo anchor('auth/create_user', lang('index_create_user_link'))?> | <?php echo anchor('auth/create_group', lang('index_create_group_link'))?></p>
  				-->
   			</div>
   		</div>
   		<div class="clear"></div>
   	<?php echo form_close();?> 
   	<!-- Edit Member Modal -->
	<div id="modal_edit_user" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<?php echo form_open("unit/change_member");?>
   		<div class="container">
   			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
       		<div class="pull">
   	    		<h2 class="pull">Edit User</h2>
   	    		<p>You can view and modify this user here.</p>
   	    		<input type="submit" name="submit" value="Save Changes" class="btn blue"  />
   	    		<div class="clear"></div>
       		</div>
       		<div class="tab-content inner-push">
   				<div class="camperform float" style="width: 100px" data-toggle="tooltip" title="Your login"><input type="text" value="Sean" /><label>First Name</label></div>
   				<div class="camperform float" style="width: 150px"  data-toggle="tooltip" title="Your login"><input type="text" value="Wittmeyer" /><label>Last</label></div>
   				<div class="camperform float" style="width: 350px"  data-toggle="tooltip" title="Your login"><input type="text" value="sean@zilifone.net" /><label>Email</label></div>
   				<div class="camperform float" style="width: 200px"  data-toggle="tooltip" title="Your login"><input type="text" value="9702192477" /><label>Daytime Phone</label></div>
   			</div>
   		</div>
   		<div class="clear"></div>
   		<?php echo form_close();?>
	</div>
   	<!-- End Modal -->
	</article>
