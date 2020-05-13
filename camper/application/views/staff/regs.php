<?php 

/* 
 * Camper At Camp / Regs View
 *
 * This page lets a staff user view regs for a given session. 
 *
 * File: /application/views/staff/chooseevent.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

?>	<h2><?php echo anchor('atcamp/'.$event['id'].'/'.$session['id'], '<i class="icon-circle-arrow-left"></i>', 'class="backbutton" data-toggle="tooltip" data-placement="right" title="'.$session['nicetitle'].' Home"'); ?></a>Registrations</h2>
	<p>These are all of the registrations for this <?php echo strtolower($event['sessiontitle']); ?>. Click on any unit or individual to view details.</p>
	<p><?php echo anchor('api/v1/reports/checkin/session/'.$session['id'].'.pdf', '<i class="icon-file-text"></i> Check-in Forms for All Units', 'class="btn tan"'); ?> <?php echo anchor('api/v1/unitroster/session/'.$session['id'].'.pdf', '<i class="icon-file-text"></i> Rosters for All Units', 'class="btn tan"'); ?><br />&nbsp;</p>
	<?php if (empty($regs)) : ?><h3>No registrations!</h3><p>There are no registrations for this session.</p>
	<?php else : ?><table class="table table-condensed">
		<thead>
			<tr>
				<thead><tr><th></th><th><i class="icon-ok tan"></i></th><th>Unit</th><th>Council (#)</th><th>Total</th><th>Y</th><th>M</th><th>F</th><?php echo ($event['groups']['enabled'] == 1) ? '<th>'.$event['groups']['title'].'</th>' : ''; ?><th>Paid/<strong>Total</strong></th><th>Amount Due</th><?php echo ($event['activitypreorders'] == 1) ? '<th>Preorders</th>' : ''; ?><th>Tools</th></tr></thead>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($regs as $reg) : if ($reg['active'] == 0) continue; ?>
			<tr<?php if ($reg['active'] == '0') echo ' class="fiftypercent strikethrough"'; ?>>
				<td><input type="checkbox" /></td>
				<td><?php unset($verify);
					$verify = $this->shared->verify($reg['id']);
					if ($verify['restricted']===true) {
						$verifyico = '<i class="icon-remove red" data-toggle="tooltip" title="'.$reg['unitid']['unittitle'].' is not registered, click manage to see details"></i>';
					} elseif ($verify['result']===false) {
						$verifyico = '<i class="icon-exclamation-sign red" data-toggle="tooltip" title="'.$reg['unitid']['unittitle'].' is registered but there are issues, click manage to see details"></i>';
					} else {
						$verifyico = '<i class="icon-ok teal" data-toggle="tooltip" title="'.$reg['unitid']['unittitle'].' is registered"></i>';
					}
						echo $verifyico; ?></td>
				<td><?php echo anchor("atcamp/".$event['id']."/".$session['id']."/regs/".$reg['id'], $reg['unitid']['unittitle']);?></td>
				<td><?php echo $reg['unitid']['council']; ?></td>
				<td class="strong"><?php echo $reg['youth']+$reg['male']+$reg['female']; ?></td>
				<td><?php echo $reg['youth']; ?></td><td><?php echo $reg['male']; ?></td>
				<td><?php echo $reg['female']; ?></td>
				<?php echo ($event['groups']['enabled'] == 1) ? (isset($reg['group'])) ? '<td>'.$event['groups']['groups'][$reg['group']]['title'].'</td>' : '<td>-</td>': ''; ?>
				<td>$<?php echo (float)$verify['source']['fin']['fin']['totalpaid']; ?>/$<strong><?php echo (float)$verify['source']['fin']['fin']['total']; ?></strong></td>
	   			<?php if (($verify['source']['fin']['fin']['total']-$verify['source']['fin']['fin']['totalpaid']) > 0) { ?>
	   				<td>$<?php echo (float)($verify['source']['fin']['fin']['total']-$verify['source']['fin']['fin']['totalpaid']); ?> is due</td>
	   			<?php } elseif (($verify['source']['fin']['fin']['total']-$verify['source']['fin']['fin']['totalpaid']) < 0) { ?>
	   				<td class="red">($<?php echo (float)($verify['source']['fin']['fin']['total']-$verify['source']['fin']['fin']['totalpaid']); ?>)</td>
	   			<?php } else { ?>
	   				<td><?php if ((float)$verify['source']['fin']['fin']['total'] == 0) { ?>-<?php } else { ?><i class="icon-ok teal"></i> Paid in full<?php } ?></td>
	   			<?php } ?>
				<?php echo ($event['activitypreorders'] == 1) ? ($reg['activitypreorders'] == 1) ? '<td><!--'.anchor('api/v1/reports/preorders/'.$reg['id'].'.pdf', '<i class="icon-file-text"></i> $'.$verify['source']['fin']['fin']['preorders'], 'class="btn btn-small tan" data-toggle="tooltip" title="List of '.$reg['unitid']['unittitle'].' Preorders"').'-->$'.$verify['source']['fin']['fin']['preorders'].'</td>' : '<td>-</td>' : ''; ?>
				<td>
					<?php echo anchor('api/v1/reports/checkin/reg/'.$reg['id'].'.pdf', '<i class="icon-file-text"></i> Checkin', 'class="btn btn-small tan" data-toggle="tooltip" title="'.$reg['unitid']['unittitle'].'"');?> 
					<?php echo anchor('api/v1/unitroster/'.$reg['id'].'.pdf', '<i class="icon-file-text"></i> Roster', 'class="btn btn-small tan" data-toggle="tooltip" title="'.$reg['unitid']['unittitle'].'"');?> 
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
		
