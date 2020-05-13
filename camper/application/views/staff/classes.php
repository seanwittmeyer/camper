<?php 

/* 
 * Camper At Camp / Classes View
 *
 * This page lets a staff user view regs for a given session. 
 *
 * File: /application/views/staff/chooseevent.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

?>	<h2><?php echo anchor('atcamp/'.$event['id'].'/'.$session['id'], '<i class="icon-circle-arrow-left"></i>', 'class="backbutton" data-toggle="tooltip" data-placement="right" title="'.$session['nicetitle'].' Home"'); ?></a>Classes</h2>
	<p>These are all of the classes for <?php echo strtolower($event['sessiontitle']); ?>. Click on any class to view details and the roster.</p>
	<p><?php echo anchor('api/v1/bluecard/session/'.$session['id'].'.pdf', '<i class="icon-file-text"></i> Blue Cards for All Classes', 'class="btn tan"'); ?> <?php echo anchor('api/v1/classroster/session/'.$session['id'].'.pdf', '<i class="icon-file-text"></i> Rosters for All Classes', 'class="btn tan"'); ?><br />&nbsp;</p>
	<?php if (empty($classes)) : ?><h3>No classes!</h3><p>There are no classes for this event.</p>
	<?php else : ?><table class="table table-condensed">
		<thead>
			<tr>
				<thead><tr><th><i class="icon-ok tan"></i></th><th>Class</th><th>Activity</th><th><strong>Regs</strong></th><th>Limit/Cutoff</th><th>Cost</th><th>Tools</th></tr></thead>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($classes as $class) : ?>
			<tr>
				<td><?php 
					if ($class['open'] == 0) {
						echo '<i class="icon-remove red" data-toggle="tooltip" title="'.$class['title'].' is not open for registrations"></i>';
					} else {
						echo '<i class="icon-ok teal" data-toggle="tooltip" title="'.$class['title'].' is open for registrations"></i>';
					}
				?></td>
				<td><?php echo anchor("atcamp/".$event['id']."/".$session['id']."/classes/".$class['id'], $class['title']);?></td>
				<td><?php echo $class['activity']['title']; ?></td>
				<td><strong><?php echo $this->activities_model->count_class_regs($session['id'],$class['id']); ?></strong></td>
				<td><?php 
					if ($class['limit'] > 0) {
						echo ($class['hardlimit'] > 0) ? $class['limit'].'/'.$class['hardlimit']: $class['limit']; 
					} else {
						echo ($class['hardlimit'] > 0) ? $class['hardlimit']: 'No Limit'; 
					}
				?></td>
				<td><?php echo ($class['preorder'] == '0') ? 'None': '$'.$class['preorder']; ?></td>
				<td>
					<?php echo anchor('api/v1/bluecard/class/'.$class['id'].'/'.$session['id'].'.pdf', '<i class="icon-file-text"></i> Blue Cards', 'class="btn btn-small tan" data-toggle="tooltip" title="'.$class['title'].'"');?> 
					<?php echo anchor('api/v1/classroster/'.$class['id'].'/'.$session['id'].'.pdf', '<i class="icon-file-text"></i> Roster', 'class="btn btn-small tan" data-toggle="tooltip" title="'.$class['title'].'"');?> 
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
		
