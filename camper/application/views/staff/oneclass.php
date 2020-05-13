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
 

?>	<h2><?php echo anchor('atcamp/'.$event['id'].'/'.$session['id'].'/classes', '<i class="icon-circle-arrow-left"></i>', 'class="backbutton" data-toggle="tooltip" data-placement="right" title="'.$session['nicetitle'].' Classes"'); ?></a><?php echo $class['title']; ?></h2>
	<p>These are the all of the registrations for <?php echo $class['title']; ?>. You can add and remove participants from the class, print blue cards, and print a roster.</p>
	<div class="clear"></div>
	<div class="camperform float " style="width:auto;"><span id="event"><?php echo $class['activity']['title']; ?></span><label>Activity</label></div>
	<div class="camperform float " style="width:auto;"><span id="session"><?php echo $class['location']; ?></span><label>Location</label></div>
	<div class="camperform float " style="width:auto;"><span id="session"><?php if ($class['limit'] == '0') { echo 'None'; } elseif ($class['limit'] !== '0' && $class['hardlimit'] == '0') { echo $class['limit']; } else { ?><abbr data-toggle="tooltip" title="Class Size"> <?php echo $class['limit']; ?></abbr> / <strong data-toggle="tooltip" title="Hard Limit"><?php echo $class['hardlimit']; ?></strong><?php } ?></span><label>Limits</label></div>
	<div class="camperform float " style="width:auto;"><span id="session"><?php echo ($class['preorder'] == '0') ? 'None': '$'.$class['preorder']; ?></span><label>Cost</label></div>
	<div class="camperform float " style="width:auto;"><span id="session"><?php 
		$scheduleblocks = array(); 
		foreach ($class['blocks'] as $j) {
			$split = str_split($j);
			$scheduleblocks[$j] = $event['periods']['days'][$j[0]]['label'].' / '.$event['periods']['periods'][$j[1]]['label']; 
		}
		if (empty($class['blocks'])) { 
			echo 'None'; 
		} else { 
			foreach ($class['blocks'] as $class['__block']) { 
				echo '<a class="badge badge-important" data-toggle="tooltip" title="'.$scheduleblocks[$class['__block']].'">'.$class['__block'].'</a> '; 
			} 
		} 
	?></span><label>Blocks</label></div>
	<div class="clear"></div>
	<p><!--<?php echo anchor('api/v1/bluecard/class/'.$class['id'].'/'.$session['id'].'.pdf', '<i class="icon-file-text"></i> Blue Cards', 'class="btn tan"'); ?>--> <?php echo anchor('api/v1/classroster/'.$class['id'].'/'.$session['id'].'.pdf', '<i class="icon-file-text"></i> Class Roster', 'class="btn tan"'); ?><br />&nbsp;</p>
	<?php if (empty($regs)) : ?>
	<p>There are no registrations for this class.</p>
	<?php else : ?>
	<?php echo form_open('api/v1/bluecard/class/'.$class['id'].'/'.$session['id'].'.pdf');?>
	<input type="hidden" name="buildbluecards" value="1" />
	<table class='table table-condensed'>
    	<thead><th>I, C</th><th>Spot</th><th>Name</th><th>Unit</th><th>Council</th><th>Date</th><th>Tools</th></thead>
    	<tbody>
    		<?php $j=1; foreach ($regs as $reg) { if ($reg['session'] != $session['id']) continue; ?>
    			<tr>
    				<td><input type="checkbox" name="include[<?php echo $reg['id']; ?>]" checked="checked" data-toggle="tooltip" title="Include in Blue Cards?" /> <input type="checkbox" name="completed[<?php echo $reg['id']; ?>]" data-toggle="tooltip" title="Completed?" /> </td>
    				<td><?php echo $j; ?></td>
    				<td><?php echo $reg['member']['name']; ?></td>
    				<td><?php echo $reg['member']['unit']['unittype'].' '.$reg['member']['unit']['number'].' ('.$reg['member']['unit']['city'].', '.$reg['member']['unit']['state'].')'; ?></td>
    				<td><?php echo $reg['member']['unit']['council']; ?></td>
    				<td><?php echo date("F j, Y, g:i a", $reg['time']); ?></td>
    				<td>-</td>
    			</tr>
    			<?php if ($class['hardlimit'] == $j) echo '<tr class="warning"><td colspan="7"><strong>Hard Limit</strong> ('.$j.' spots)</td></tr>'; ?>
    			<?php if ($class['limit'] == $j) echo '<tr class="warning"><td colspan="7"><strong>Soft Limit</strong> ('.$j.' spots)</td></tr>'; ?>
    		<?php $j++; } ?>
    	</tbody>
    </table>
    <h3>Print Blue Cards</h3>
    <p>Check the boxes next to the participants above if they completed the merit badge at this event. Merit Badge blue card applications will be printed for all participants, those checked above will be marked complete. Click "build blue cards" below when done to export a pdf for printing. The following details are used for completed blue cards.</p>
    <div class="camperform float " style="width: 30%"><input type="text" name="counselor[name]" id="fcn" value="BDSR" placeholder="none" /><label for="fcn">Counselor Name</label></div>
    <div class="camperform float " style="width: 30%"><input type="text" name="counselor[address]" id="fca" value="2331 County Road 68C" placeholder="none" /><label for="fca">Address</label></div>
    <div class="camperform float " style="width: 30%"><input type="text" name="counselor[city]" id="fcc" value="Red Feather Lakes, CO 80545" placeholder="none" /><label for="fcc">City, State, Zip</label></div>
    <div class="camperform float " style="width: 30%"><input type="text" name="counselor[phone]" id="fcp" value="(970) 881-2144" placeholder="none" /><label for="fcp">Counselor Phone</label></div>
    <div class="camperform float " style="width: 60%"><input type="text" name="counselor[remarks]" id="fcr" value="Completed at Summer Camp" placeholder="none" /><label for="fcr">Remarks / Notes</label></div>
    <div class="clear"></div>
    <p><input type="submit" value=" Build Blue Cards" class="btn teal" /></p>
	<?php echo form_close();?> 
    <?php endif; ?>
