<?php 

/* 
 * Camper Admin Users View
 *
 * This is. 
 *
 * File: /application/views/admin/users/users.php
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
			  prefetch: '<?php echo $this->config->item('camper_path'); ?>api/v1/users.json?123', 
			  template: [                                                                 
			    '<p class="typeahead-num">{{unit}}</p>',                              
			    '<p class="typeahead-name">{{name}}</p>',                                      
			    '<p class="typeahead-city">{{email}} / {{phone}}</p>'                         
			  ].join(''),                                                                 
			  engine: Hogan                                                               
			});
			$('.camperpopover').popover({html:true});
			$('.typeahead').on('typeahead:autocompleted', function(evt, item) {
				window.location.href = '<?php echo base_url(); ?>users/' + item['userid'];
			})
			$('.typeahead').on('typeahead:selected', function(evt, item) {
				window.location.href = '<?php echo base_url(); ?>users/' + item['userid'];
			})
		});
	</script>
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
	<article class="content">
	<?php echo form_open("unit/edit");?> 

   		<div class="container">
       		<div class="pull">
   	    		<h2 class="pull">Users</h2>
   	    		<p>Users are the people who use Camper, including leaders, staff and administrators. You can look up and manage all of the users, including managing details about individual users.</p>
   	    		<p><?php echo anchor("users/new", '<i class="icon-plus"></i> Add a new user', 'class="btn teal"'); ?></p>
   	    		<div class="clear"></div>
       		</div>
       		<div class="tab-content inner-push">
   				<h2 class="">Camper Users</h2>
   				<p>All of the users in Camper are listed here. You can see details about each person including name, user type (admin/leader/staff), email, and get easy access to tools for editing, activating/deactivating and inviting users.</p>
   				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
   				
   				<p>You can also locate a user by searching. Search by name, phone number or email address.</p>
   		   		<div class="camperform float search" style="width: 60%"><i class="icon-search"></i><input class="ico userslist typeahead" type="text" name="altemail" data-toggle="tooltip"  placeholder="Baden Powell or 970..."  title="Search for a user by name, phone number or email address" /><label>User Search</label></div>

   				<div class="clear"></div>
   	    		<ul id="detailstabs" class="teal">
   					<li class="active"><a href="#leaders" data-toggle="tab">Leaders</a></li>
   					<li class=""><a href="#individuals" data-toggle="tab">Individuals</a></li>
   					<li class=""><a href="#admin" data-toggle="tab">Admin</a></li>
   					<li class=""><a href="#staff" data-toggle="tab">Staff</a></li>
   				</ul>
			   	<script>
					function edituser(userid)
						{
					    $('#modal_edit_user').modal('show');
					    return false;
					}
			  	</script>
   	    		<div class="tab-content">
   					<div class="tab-pane fade in active" id="leaders">
   					<!--<h5 class="section">All Users</h5>-->
   				  		<table class="table table-condensed">
   				  			<thead>
   				  	    	<tr><th>Name</th><th>Unit</th><th>Email</th><th>Tools</th></tr>
   				  			</thead>
   				  			<tbody>
							<?php foreach ($leaders as $user): 
								if ($user->company == 0) continue;
							?>
   				  		    	<tr>
	   				  		    	<td><?php echo anchor("users/".$user->id, $user->first_name.' '.$user->last_name) ;?></td>
	   				  		    	<td><?php $unit = $this->shared->get_user_unit($user->id); if ($user->individual == '1') { ?><i class="icon-info-sign tan"></i> Individual<?php } elseif ($unit && $unit !== 'No unit') { echo $unit; } else { ?><i class="icon-warning-sign red"></i> No Unit<?php } ?></td>
	   				  		    	<td><?php echo $user->email;?></td>
	   				  		    	<td><?php echo ($user->active) ? '<a href="users/deactivate/'.$user->id.'">Deactivate</a>' : '<a href="users/activate/'.$user->id.'">Activate</a>'; ?> <?php echo anchor("users/edit/".$user->id, 'Edit') ;?></td>
   				  		    	</tr>
							<?php endforeach;?>
   				  	    	<!--<tr><td>Charles Emerson Winchester</td><td>Crew 464</td><td>cewinchester@4077.army.mil</td><td><a href="#" onclick="edituser(55);"><i class="icon-edit"></i> Edit</a></td></tr>-->
   				  		</table>
   	    			</div>
   					<div class="tab-pane fade" id="individuals">
   					<!--<h5 class="section">All Users</h5>-->
   				  		<table class="table table-condensed">
   				  			<thead>
   				  	    	<tr><th>Name</th><th>Unit</th><th>Email</th><th>Tools</th></tr>
   				  			</thead>
   				  			<tbody>
							<?php foreach ($leaders as $user): 
								if ($user->company != 0) continue;
								$unit = unserialize($user->individualdata);
							?>
   				  		    	<tr>
	   				  		    	<td><?php echo anchor('users/'.$user->id, $user->first_name.' '.$user->last_name); ?></td>
	   				  		    	<td><?php echo (isset($unit['unittype']) && isset($unit['number'])) ? $unit['unittype'].' '.$unit['number']: 'None'; ?></td>
	   				  		    	<td><?php echo $user->email; ?></td>
	   				  		    	<td><?php echo ($user->active) ? '<a href="users/deactivate/'.$user->id.'">Deactivate</a>' : '<a href="users/activate/'.$user->id.'">Activate</a>'; ?> <?php echo anchor("users/edit/".$user->id, 'Edit') ;?></td>
   				  		    	</tr>
							<?php endforeach;?>
   				  	    	<!--<tr><td>Charles Emerson Winchester</td><td>Crew 464</td><td>cewinchester@4077.army.mil</td><td><a href="#" onclick="edituser(55);"><i class="icon-edit"></i> Edit</a></td></tr>-->
   				  		</table>
   	    			</div>
   					<div class="tab-pane fade" id="admin">
   					<!--<h5 class="section">All Users</h5>-->
   				  		<table class="table table-condensed">
   				  			<thead>
   				  	    	<tr><th>Name</th><th>Email</th><th>Phone</th><th>Tools</th></tr>
   				  			</thead>
   				  			<tbody>
							<?php foreach ($admins as $user): ?>
   				  		    	<tr>
	   				  		    	<td><?php echo anchor("users/".$user->id, $user->first_name.' '.$user->last_name) ;?></td>
	   				  		    	<td><?php echo $user->email;?></td>
	   				  		    	<td><?php echo $user->phone;?></td>
	   				  		    	<td><?php echo ($user->active) ? '<a href="users/deactivate/'.$user->id.'">Deactivate</a>' : '<a href="users/activate/'.$user->id.'">Activate</a>'; ?> <?php echo anchor("users/edit/".$user->id, 'Edit') ;?></td>
   				  		    	</tr>
							<?php endforeach;?>
   				  		</table>
   	    			</div>
   					<div class="tab-pane fade" id="staff">
   					<!--<h5 class="section">All Users</h5>-->
   				  		<table class="table table-condensed">
   				  			<thead>
   				  	    	<tr><th>Name</th><th>Email</th><th>Phone</th><th>Tools</th></tr>
   				  			</thead>
   				  			<tbody>
							<?php foreach ($staffs as $user): ?>
   				  		    	<tr>
	   				  		    	<td><?php echo anchor("users/".$user->id, $user->first_name.' '.$user->last_name) ;?></td>
	   				  		    	<td><?php echo $user->email;?></td>
	   				  		    	<td><?php echo $user->phone;?></td>
	   				  		    	<td><?php echo ($user->active) ? '<a href="users/deactivate/'.$user->id.'">Deactivate</a>' : '<a href="users/activate/'.$user->id.'">Activate</a>'; ?> <?php echo anchor("users/edit/".$user->id, 'Edit') ;?></td>
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
