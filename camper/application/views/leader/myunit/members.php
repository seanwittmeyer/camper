<?php 

/* 
 * Camper My Unit / Members View
 *
 * This is the roster view of the "My Unit" section in camper. 
 *
 * File: /application/views/myunit/details.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

 $unittitle = (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')': $unit['unittype'].' '.$unit['number'];


?>	<script type="text/javascript" charset="utf-8">
		$.extend( $.fn.dataTableExt.oStdClasses, {
			"sSortAsc": "header headerSortDown",
			"sSortDesc": "header headerSortUp",
			"sSortable": "header"
		});
		$(document).ready(function() {
			oTable = $('.datatables').dataTable( {
				"sDom": "<r>t<''<'left'i><'left'p><'right'l>>",
				"oLanguage": {
					"sInfo": "_START_ through _END_ of _TOTAL_ members"
				}
			});
		});
	</script>

	<div class="subnav">
		<div class="container">
			<h2>My Unit</h2>
			<nav class="campersubnav">
						<li class="active"><?php echo anchor("unit/members", 'Members');?></li>
						<li><?php echo anchor("unit", 'Details');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
	<?php echo form_open(uri_string());?>

			<div class="container">
					<h2 class=""> <?php echo $unittitle; ?> Members</h2>
					<p>You can view all of your unit's members here. The roster is only a list of members in the Camper Registration system, not a participating list for any specific event or activity. You can manage event rosters by going to the events section of this site.</p>
						<p><?php echo anchor('unit/members/new', '<i class="icon-plus"></i> Add Member', 'class="btn teal"'); ?></p>
					<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
					<div class="clear"></div>
						<ul id="detailstabs" class="blue">
						<li class="active"><a href="#membersyouth" data-toggle="tab">Youth Members</a></li>
						<li class=""><a href="#membersadults" data-toggle="tab">Adult Members</a></li>
					</ul>
					
						<div class="tab-content">
						<div class="tab-pane fade  in active" id="membersyouth">
						<script>
							function edituser(userid)
							{
									$('#modal_edit_member').modal('show');
							}
			  		</script>
					  		<table class="table table-condensed datatables">
					  			<thead>
					  				<tr><th>Name</th><th>Age (DOB)</th><th>Shirt Size</th><th>Allergies</th><th>Dietary</th><th>Medical</th><th>Other Notes</th><th>Tools</th></tr>
					  			</thead>
					  			<tbody>
					  			<?php if (!empty($members)) :
					  				$now = time();
						  			$adult = ($unit['unittype'] == 'Ship' || $unit['unittype'] == 'Crew') ? (31556926 * 21): (31556926 * 18); // 21 and 18 years in seconds
						  			foreach ($members as $member) : 
						  			if ($now-$member['dob'] >= $adult) continue; 
						  			?>
					  					<tr>
					  					<td><?php echo anchor('unit/members/'.$member['id'], $member['name']); ?></td>
					  					<td><?php echo floor((($now-$member['dob']) / 31556926)); ?> (<?php echo date('M j, Y', $member['dob']); ?>)</td>
					  					<td><?php echo $member['shirtsize']; ?></td>
					  					<td><?php echo (!isset($member['allergies']) || empty($member['allergies'])) ? '': ' <i class="icon-ellipsis-horizontal camperhoverpopover" data-toggle="popover" title="Allergies" data-placement="top" data-content="'.$member['allergies'].'"> Yes</i>'; ?></td>
					  					<td><?php echo (!isset($member['diet']) || empty($member['diet'])) ? '': ' <i class="icon-ellipsis-horizontal camperhoverpopover" data-toggle="popover" title="Dietary Restrictions" data-placement="top" data-content="'.$member['diet'].'"> Yes</i>'; ?></td>
					  					<td><?php echo (!isset($member['medical']) || empty($member['medical'])) ? '': ' <i class="icon-ellipsis-horizontal camperhoverpopover" data-toggle="popover" title="Medical Notes" data-placement="top" data-content="'.$member['medical'].'"> Yes</i>'; ?></td>
					  					<td><?php echo (!isset($member['notes']) || empty($member['notes'])) ? '': ' <i class="icon-ellipsis-horizontal camperhoverpopover" data-toggle="popover" title="Notes" data-placement="top" data-content="'.$member['notes'].'"> Yes</i>'; ?></td>
					  					<td><?php echo anchor('unit/members/'.$member['id'], '<i class="icon-pencil"></i> ', 'class="btn btn-small tan"'); ?> <a data-toggle="popover" title="Delete" data-placement="top" data-content="Are you sure you want to delete this member? <br /><br />When you delete this member, all event roster and activity registrations will also be removed.<br /><br /><?php echo str_replace('"', "'", anchor('unit/members/'.$member['id'].'/delete', 'Delete '.$member['name'], 'class="btn red"')); ?>" class="btn btn-small red camperpopover"><i class="icon-remove"></i></a>
					  					</tr>
						  		<?php endforeach; endif; ?>
					  			</tbody>
					  		</table>
							</div>
						<div class="tab-pane fade" id="membersadults">
						
					  		<table class="table table-condensed datatables">
					  			<thead>
					  				<tr><th>Name</th><th>Age (DOB)</th><th>Shirt Size</th><th>Allergies</th><th>Dietary</th><th>Medical</th><th>Other Notes</th><th>Tools</th></tr>
					  			<tbody>
					  			<?php if (!empty($members)) :
						  			foreach ($members as $member) : 
						  			if ($now-$member['dob'] < $adult) continue; 
						  			?>
					  					<tr>
					  					<td><?php echo anchor('unit/members/'.$member['id'], $member['name']); ?></td>
					  					<td><?php echo floor((($now-$member['dob']) / 31556926)); ?> (<?php echo date('M j, Y', $member['dob']); ?>)</td>
					  					<td><?php echo $member['shirtsize']; ?></td>
					  					<td><?php echo (!isset($member['allergies']) || empty($member['allergies'])) ? '': '<i class="icon-ellipsis-horizontal camperhoverpopover" data-toggle="popover" title="Allergies" data-placement="top" data-content="'.$member['allergies'].'"> Yes</i>'; ?></td>
					  					<td><?php echo (!isset($member['diet']) || empty($member['diet'])) ? '': ' <i class="icon-ellipsis-horizontal camperhoverpopover" data-toggle="popover" title="Dietary Restrictions" data-placement="top" data-content="'.$member['diet'].'"> Yes</i>'; ?></td>
					  					<td><?php echo (!isset($member['medical']) || empty($member['medical'])) ? '': ' <i class="icon-ellipsis-horizontal camperhoverpopover" data-toggle="popover" title="Medical Notes" data-placement="top" data-content="'.$member['medical'].'"> Yes</i>'; ?></td>
					  					<td><?php echo (!isset($member['notes']) || empty($member['notes'])) ? '': ' <i class="icon-ellipsis-horizontal camperhoverpopover" data-toggle="popover" title="Notes" data-placement="top" data-content="'.$member['notes'].'"> Yes</i>'; ?></td>
					  					<td><?php echo anchor('unit/members/'.$member['id'], '<i class="icon-pencil"></i> Edit', 'class="btn btn-small tan"'); ?> <a data-toggle="popover" title="Delete" data-placement="top" data-content="Are you sure you want to delete this member? <br /><br />When you delete this member, all event roster and activity registrations will also be removed.<br /><br /><?php echo str_replace('"', "'", anchor('unit/members/'.$member['id'].'/delete', 'Delete '.$member['name'], 'class="btn red"')); ?>" class="btn btn-small red camperpopover"><i class="icon-remove"></i></a>
					  					</tr>
						  		<?php endforeach; endif; ?>
					  			</tbody>
					  		</table>
							</div>
					</div>
				</div>
			<div class="clear"></div>
		<?php echo form_close();?> 
	</article>
