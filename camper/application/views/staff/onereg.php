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
 

?>	<h2><?php echo anchor('atcamp/'.$event['id'].'/'.$session['id'].'/regs', '<i class="icon-circle-arrow-left"></i>', 'class="backbutton" data-toggle="tooltip" data-placement="right" title="'.$session['nicetitle'].' Registrations"'); ?></a><?php echo $reg['unitid']['unittitle']; ?></h2>
	<p>These are the registration details for this reg.</p>
	<div class="clear"></div>
	<?php if ($reg['unitid'] == 0) { ?>
	<div class="camperform float " style="width:auto;" ><span id="unit"><?php echo $reg['unitid']['unittitle']; ?></span><label>Individual</label></div>
	<?php } else { ?>
	<div class="camperform float " style="width:auto;" ><span id="unit"><?php echo $reg['unitid']['unittitle']; ?></span><label>Unit</label></div>
	<?php } ?>
	<div class="camperform float " style="width:auto;" data-toggle="tooltip" title="Click to see this event's registrations"><a href="<?php echo base_url()."event/".$event['id']."/registrations"; ?>"><span id="event"><?php echo $event['title']; ?></span></a><label>Event</label></div>
	<div class="camperform float " style="width:auto;" data-toggle="tooltip" title="Click to edit this event's session"><a href="#modal_edit_session" data-toggle="modal"><span id="session"><?php echo $session['nicetitle']; ?></span></a><label><?php echo $event['sessiontitle']; ?></label></div>
	<div class="camperform float " style="width:auto;" data-toggle="tooltip" title="Click to edit the time of registration for this reg"><a href="#modal_edit_time" data-toggle="modal"><span id="regtime"><?php echo date('F j, Y g:ia', $reg['registerdate']['time']); ?></span></a><label>Time of Registration</label></div>
	<div class="clear"></div>
	<p><?php echo anchor('api/v1/reports/checkin/reg/'.$reg['id'].'.pdf', '<i class="icon-file-text"></i> Check-in Form', 'class="btn tan"'); ?> <?php echo anchor('api/v1/unitroster/'.$reg['id'].'.pdf', '<i class="icon-file-text"></i> '.$reg['unitid']['unittype'].' Roster', 'class="btn tan"'); ?> <?php echo anchor('api/v1/bluecard/reg/'.$reg['id'].'.pdf', '<i class="icon-file-text"></i> Blue Cards', 'class="btn tan"'.(($reg['bluecards'] == 1) ? '':' disabled="disabled"')); ?> <br />&nbsp;</p>

	<h3>Registration Status</h3>
	<?php if ($verify['restricted']===true) { ?><h3 class=""><i class="icon-remove red"></i> <?php echo $unit['unittype']; ?> <?php echo $unit['number']; ?> is not registered</h3>
	<p><?php foreach ($verify['error'] as $e) { ?><i class="icon-minus tan"></i> <?php print_r($e); ?><br /><?php } ?></p>
    <?php } elseif ($verify['result']===false) { ?><h3 class=""><i class="icon-exclamation-sign red"></i> <?php echo $unit['unittype']; ?> <?php echo $unit['number']; ?> is registered, but there are issues:</h3>
	<p><?php foreach ($verify['error'] as $e) { ?><i class="icon-minus tan"></i> <?php print_r($e); ?><br /><?php } ?></p>
	<?php } else { ?><h3><i class="icon-ok teal"></i> <?php echo $unit['unittype']; ?> <?php echo $unit['number']; ?> is registered and all is well</h3><?php } ?>

	<h3>Payments &amp; Finances</h3>
   	<ul id="detailstabs" class="red">
		<li class="active"><a href="#finances" data-toggle="tab">Overview</a></li>
    	<li class=""><a href="#payments" data-toggle="tab">Payments</a></li>
    	<li class=""><a href="#payment" data-toggle="tab">Add a Payment</a></li>
    </ul>
    <div class="tab-content">
    	<div class="tab-pane fade in active" id="finances">
			<p>This is an overview of all financial details for this registration.</p>
			<div class="registerfinancial">
				<div class="camperform float last" style="width: 150px" data-toggle="tooltip" title="The total, all inclusive, cost for camp"><span>$<?php echo $this->shared->number_format_drop_zero($fin['total']); ?></span><label>Total Fee</label></div>
				<div class="camperform float last" style="width: 150px"  data-toggle="tooltip" title="The total amount of confirmed payments, does not include pending payments"><span>$<?php echo $this->shared->number_format_drop_zero($fin['totalpaid']); ?></span><label>Total Paid</label></div>
				<div class="clear"></div>
				<?php if (($fin['total'] - $fin['totalpaid']) > 0) { ?>
				<div class="camperform float last" style="width: 150px"  data-toggle="tooltip" title="The amount remaining (total - paid). This amount includes all discounts and options (including preorders, if set), and may not be due right now."><span class="label">$<?php echo $this->shared->number_format_drop_zero($fin['total'] - $fin['totalpaid']); ?></span><label>Amount Remaining</label></div>
				<?php } elseif (($fin['total'] - $fin['totalpaid']) < 0) { ?>
				<div class="camperform float last" style="width: 150px"  data-toggle="tooltip" title="You have paid more than what is due, contact us for details on getting a refund."><span class="label label-info">$<?php echo $this->shared->number_format_drop_zero($fin['totalpaid'] - $fin['total']); ?></span><label>Amount Overpaid</label></div>
				<?php } elseif ((float)$fin['total'] == 0) { ?>
				<div class="camperform float last" style="width: 150px"  data-toggle="tooltip" title="It doesn't look like there is any fee, check the number of participants or any options. His save if you made any changes."><span>No fee</span><label>Amount Remaining</label></div>
				<?php } else { ?>
				<div class="camperform float last" style="width: 150px"  data-toggle="tooltip" title="It looks like you are all paid up and good to go."><span><i class="icon-ok teal"></i> Paid in full!</span><label>Amount Remaining</label></div>
				<?php } ?>
				<div class="clear"></div>
	 				<div class="camperform float last" style="width: 150px" data-toggle="tooltip" title="Total of all discounts"><span>$<?php echo $this->shared->number_format_drop_zero($fin['totaldiscounts']); ?></span><label>Total Discounts</label></div>
				<div class="camperform float last" style="width: 150px"  data-toggle="tooltip" title="Total of all options and extras, edit these under the extras tab"><span>$<?php echo $this->shared->number_format_drop_zero($fin['requests']); ?></span><label>Total Options &amp; Fees</label></div>
				<div class="clear"></div>
				<?php if ($event['activitypreorders'] == 1 && $reg['activitypreorders'] == 1 && isset($fin['preorders'])) { ?>
	 				<div class="camperform float last" style="width: 150px" data-toggle="tooltip" title="Total of all activity or merit badge supplies, you have this because you enabled supplies preorders"><span>$<?php echo $this->shared->number_format_drop_zero($fin['preorders']); ?></span><label>Activity Supplies Preorder</label></div>
				<div class="clear"></div>
				<?php } ?>
				<!--<div class="camperform float last red" style="width: 300px"  data-toggle="tooltip" title="The amount due now"><span>$<?php echo $this->shared->number_format_drop_zero($fin['nextdue']); ?></span><label>Amount Due by December 2</label></div>
				<!--<div class="camperform float last red" style="width: 300px"  data-toggle="tooltip" title="The amount due for the next payment"><span>$<?php echo $this->shared->number_format_drop_zero($fin['nextdue']); ?></span><label>Amount Due by December 2</label></div>
				<div class="clear"></div>-->
			</div>
			<div class="registerfeetable">
				<table class="table table-condensed">
					<tbody>
				    	<tr><td colspan="3"><strong>Fees</strong>  <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Fees" data-placement="top" data-content="Fees are the base cost for an event. Fees are automatically calculated from the youth and adult (if any) costs. You can manage your registration numbers in the 'Basics' tab for basic registrations or by managing your participating roster in the 'Roster' tab. Contact us if you have any questions."></i></td></tr>
				    	<?php if (($session['cost'] + $session['costadult'] + (($cost['groups']['perperson'] === true) ? $cost['groups']['cost']: 0)) == 0) { ?>
				    	<tr><td colspan="3">There are no registration fees for this event.</td></tr>
				    	<?php } else { ?>
				    	<tr><td>Youth</td><td><?php echo $this->shared->number_format_drop_zero($reg['youth']); ?> x $<?php echo $this->shared->number_format_drop_zero($cost['youth']); ?></td><td>= $<?php echo $this->shared->number_format_drop_zero($fin['totalyouth']); ?></td></tr>
				    	<tr><td>Adults</td><td><?php echo $this->shared->number_format_drop_zero($counts['adults']); ?> x $<?php echo $this->shared->number_format_drop_zero($cost['adult']); ?></td><td>= $<?php echo $this->shared->number_format_drop_zero($fin['totaladults']); ?></td></tr>
				    	<tr><td colspan="2">Total Fee</td><td>= $<?php echo $this->shared->number_format_drop_zero($fin['totalparticipants']); ?></td></tr>
				    	<?php } ?>
				    	<?php if($groups['enabled'] == '1') { $currentgroup = (isset($reg['group'])) ? $reg['group'] : false; } if ($cost['groups']['perperson'] === false && $cost['groups']['cost'] > 0) { ?> 
				    	<tr><td><?php echo $groups['groups'][$currentgroup]['title']; ?> <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="<?php echo $groups['groups'][$currentgroup]['title']; ?>" data-placement="top" data-content="<?php echo $groups['groups'][$currentgroup]['desc']; ?>"></i></td><td></td><td>= <?php echo $this->shared->number_format_drop_zero($cost['groups']['cost']); ?> </td></tr> 
				    	<?php } ?>
				    	
				    	<?php if ($event['activitypreorders'] == 1 && $reg['activitypreorders'] == 1) { ?>
				    	<tr><td colspan="3"><strong>Preordered Costs</strong>  <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Preordered Costs" data-placement="top" data-content="Preordered costs include activity / class / merit badge fees that are separate from the event fees. These include kits or other items that you can choose to preorder.<br><br>You can manage preorders in the 'Extras' tab, and you can exclude individuals from preorders in the 'Roster' tab."></i></td></tr>
				    	<tr><td>Activity Supplies</td><td></td><td>= $<?php echo $this->shared->number_format_drop_zero($fin['preorders']); ?></td></tr>
				    	<?php } ?>
				    	
				    	<!-- Start Options -->
						<?php if (count($options > 0)) : $i=1; foreach ($options as $o) : ?>
							<?php $flag = true; if ($o['checkbox'] == 1 && isset($o['id']) && !isset($reg['options'][$o['id']]['checkbox'])) $flag = false; if (isset($o['amount']) && $o['amount'] > 0 && $flag === true ) : ?>
							<?php if($i==1) { ?><tr><td colspan="3"><strong>Requests</strong> <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Requests/Options" data-placement="top" data-content="You can edit most requests and options in the 'Extras' tab, if an option requires verification, it won't show here until approved. Some options/requests are not editable, contact us for details."></i></td></tr><?php } $i++; ?>
							<tr>
								<td><?php echo $o['title']; ?></td>
								<td><?php // Setup
									$o['__total'] = (isset($o['amount'])) ? $o['amount']: 0; 
									$o['__value'] = 0; 
									$o['__percent'] = ($o['percent'] == 1) ? true: false;
									$o['__pre'] = ($o['__percent']) ? false: '$';
									$o['__post'] = ($o['__percent']) ? '%': false;
									
				    				if (isset($o['amount']) && $o['value'] == 1 && isset($reg['options'][$o['id']]['value']) ) { 
				    					// This option has an amount and the user entered a value
				    					if ($o['perperson'] == 1) {
				    						// This is per person, we'll display the details find the total
				    						echo $counts['total'].' x '.$o['__pre'].$o['amount'].$o['__post'];
				    						$o['__total'] = ($o['__percent']) ? $counts['total'] * (.01 * $o['amount']) : $counts['total'] * $o['amount'];
				    					} else {
				    						// This is per the value, we'll display details and find the total
				    						echo $reg['options'][$o['id']]['value'].' x '.$o['__pre'].$o['amount'].$o['__post'];
				    						$o['__total'] = ($o['__percent']) ? $reg['options'][$o['id']]['value'] * (.01 * $o['amount']) : $reg['options'][$o['id']]['value'] * $o['amount']; 
				    					}
				    				} elseif (isset($o['amount'])) {
				    					if ($o['perperson'] == 1) {
				    						echo $counts['total'].' x '.$o['__pre'].$o['amount'].$o['__post'];
				    						$o['__total'] = ($o['__percent']) ? $counts['total'] * (.01 * $o['amount']) : $counts['total'] * $o['amount'];
				    					} else {
				    						echo $o['__pre'].$o['amount'].$o['__post'];
				    						$o['__total'] = ($o['__percent']) ? .01 * $o['amount'] : $o['amount'];
				    					}
								} ?></td>
								<td>= $<?php echo $o['__total']; ?></td>
							</tr>
						<?php endif; endforeach; endif; ?>
				    	<!-- End Options -->
				
				    	<!-- Start Custom Discounts -->
						<?php $i=1; if (count($discounts > 0)) : foreach ($discounts as $o) : ?>
							<?php $flag = true; if ($o['checkbox'] == 1 && !isset($reg['discounts'][$o['id']]['checkbox'])) $flag = false; if (isset($o['amount']) && $o['amount'] > 0 && $flag === true ) : ?>
							<?php if($i==1) { ?><tr><td colspan="3"><strong>Discounts</strong> <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Discounts" data-placement="top" data-content="There are 2 types of discounts. Some, like early bird registration, are automatically added. Others, such as Friends of Scouting discounts, need to be added. You can edit discounts in the 'Extras' tab."></i></td></tr><?php } $i++; ?>
							<tr>
								<td><?php echo $o['title']; ?></td>
								<td><?php // Setup
									$o['__total'] = (isset($o['amount'])) ? $o['amount']: 0; 
									$o['__value'] = 0; 
									$o['__percent'] = ($o['percent'] == 1) ? true: false;
									$o['__pre'] = ($o['__percent']) ? false: '$';
									$o['__post'] = ($o['__percent']) ? '%': false;
									
				    				if (isset($o['amount']) && $o['value'] == 1 && isset($reg['discounts'][$o['id']]['value']) ) { 
				    					// This option has an amount and the user entered a value
				    					if ($o['perperson'] == 1) {
				    						// This is per person, we'll display the details find the total
				    						echo $counts['total'].' x '.$o['__pre'].$o['amount'].$o['__post'];
				    						$o['__total'] = ($o['__percent']) ? $counts['total'] * (.01 * $o['amount']) : $counts['total'] * $o['amount'];
				    					} else {
				    						// This is per the value, we'll display details and find the total
				    						echo $reg['discounts'][$o['id']]['value'].' x '.$o['__pre'].$o['amount'].$o['__post'];
				    						$o['__total'] = ($o['__percent']) ? $reg['discounts'][$o['id']]['value'] * (.01 * $o['amount']) : $reg['discounts'][$o['id']]['value'] * $o['amount']; 
				    					}
				    				} elseif (isset($o['amount'])) {
				    					if ($o['perperson'] == 1) {
				    						echo $counts['total'].' x '.$o['__pre'].$o['amount'].$o['__post'];
				    						$o['__total'] = ($o['__percent']) ? $counts['total'] * (.01 * $o['amount']) : $counts['total'] * $o['amount'];
				    					} else {
				    						echo $o['__pre'].$o['amount'].$o['__post'];
				    						$o['__total'] = ($o['__percent']) ? .01 * $o['amount'] : $o['amount'];
				    					}
								} ?></td>
								<td>= <i class="red">$<?php echo $o['__total']; ?></i></td>
							</tr>
						<?php endif; endforeach; endif; ?>
				    	<!-- End Options -->
				
				    	<!-- Start Integrated Discounts -->
						<?php // Early Registration
						if ($event['earlyreg']['enabled'] == 1 && $reg['registerdate']['time'] < $event['earlyreg']['date']) :
							if($i==1) { ?><tr><td colspan="3"><strong>Discounts</strong> <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Discounts" data-placement="top" data-content="There are 2 types of discounts. Some, like early bird registration, are automatically added. Others, such as Friends of Scouting discounts, need to be added. You can edit discounts in the 'Extras' tab."></i></td></tr><?php } $i++; ?><tr>
				    		<td>Early Registration <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Early Registration" data-placement="top" data-content="The early registration discount is automatically applied if you registered by <?php echo date('F j, Y',$event['earlyreg']['date']); ?>."></i></td>
				    		<td><?php 
							if ($event['earlyreg']['percent'] == 1) {
				   				// The discount is a percentage
				   				echo ($event['earlyreg']['per'] == 1) ? $counts['total'].' x '.$event['earlyreg']['amount'].'%': $event['earlyreg']['amount'].'%';
				   				$event['earlyreg']['__total'] = ($event['earlyreg']['per'] == 1) ? $counts['total'] * ($event['earlyreg']['amount'] * .01): $event['earlyreg']['amount'] * .01;
							} else {
				   				// The discount is a normal dollar amount
				   				if ($event['freeadults']['enabled'] == 1 && $counts['youth'] >= $event['freeadults']['threshold']) {
					   				// Handle the free adults discounts so we aren't giving discounts on non-qualifying leaders.
					   				if ($event['freeadults']['dollar'] == 1) {
						   				// This is a dollar discount, subtract the amount x qualifying leaders, subtract that from the early discount
						   				//echo $counts['adults'].' x $'.$event['freeadults']['amount'];
						   				// NOTE: We aren't doing anything special here, the discount will apply to all leaders. This is the same ans the else code below.
						   				echo ($event['earlyreg']['per'] == 1) ? $counts['total'].' x $'.$event['earlyreg']['amount']: '$'.$event['earlyreg']['amount'];
						   				$event['earlyreg']['__total'] = ($event['earlyreg']['per'] == 1) ? $counts['total'] * $event['earlyreg']['amount']: $event['earlyreg']['amount'];
					   				} else {
					   					// Subtract the free adult threshold from the amount qualifying for the discount. This way we aren't giving discounts on top of the free adults.
						   				echo ($event['earlyreg']['per'] == 1) ? ($counts['total']-$event['freeadults']['amount']).' x $'.$event['earlyreg']['amount']: '$'.$event['earlyreg']['amount'];
						   				$event['earlyreg']['__total'] = ($event['earlyreg']['per'] == 1) ? ($counts['total']-$event['freeadults']['amount']) * $event['earlyreg']['amount']: $event['earlyreg']['amount'];
					   				}
				   				} else {
					   				// Our free adults aren't setup or didn't qualify, proceed without that factor.
					   				echo ($event['earlyreg']['per'] == 1) ? $counts['total'].' x $'.$event['earlyreg']['amount']: '$'.$event['earlyreg']['amount'];
					   				$event['earlyreg']['__total'] = ($event['earlyreg']['per'] == 1) ? $counts['total'] * $event['earlyreg']['amount']: $event['earlyreg']['amount'];
				   				}
							}
				    		?></td>
				    		<td>= <i class="red">$<?php echo $event['earlyreg']['__total']; ?></i></td>
						</tr><?php endif; ?>
						<?php // Free Adults
						if ($event['freeadults']['enabled'] == 1 && $counts['youth'] >= $event['freeadults']['threshold']) :
							if($i==1) { ?><tr><td colspan="3"><strong>Discounts</strong> <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Discounts" data-placement="top" data-content="There are 2 types of discounts. Some, like early bird registration, are automatically added. Others, such as Friends of Scouting discounts, need to be added. You can edit discounts in the 'Extras' tab."></i></td></tr><?php } $i++; ?><tr>
				    		<td>Adults <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Adults Discount" data-placement="top" data-content="<?php echo $event['freeadults']['description']; ?>"></i></td>
				    		<td><?php 
							if ($event['freeadults']['dollar'] == 1) {
				   				// The discount is a dollar amount, per adult
				   				echo $counts['adults'].' x $'.$event['freeadults']['amount'];
				   				$event['freeadults']['__total'] = $counts['adults'] * $event['freeadults']['amount'];
							} else {
				   				// The discount for free adults
				   				echo $event['freeadults']['amount'].' x $'.$this->shared->number_format_drop_zero($cost['adult']);
				   				$event['freeadults']['__total'] = $event['freeadults']['amount'] * $cost['adult'];
							}
				    		?></td>
				    		<td>= <i class="red">$<?php echo $event['freeadults']['__total']; ?></i></td>
						</tr><?php endif; ?>
						<?php // Late fee
						if (isset($event['paymenttiers']['l']) && $event['paymenttiers']['l'] == 1 && $reg['latefeeexempt'] == 0 && $fin['latefee'] > 0 && $event['paymenttiers']['ldate'] < time()) :
							$totaldue = ($fin['totalparticipants'] + $fin['group'] + $fin['requests'] - $fin['totaldiscounts']);
						if ($event['paymenttiers']['lpercent'] == 1) {
							$latefeedescription = 'A late fee of '.$event['paymenttiers']['lamount'].'% is applied for registrations that are not paid in full before '.date('F j, Y g:i:sa', $event['paymenttiers']['ldate']).'.<br><br>'.$event['paymenttiers']['lamount'].'% of $'.$totaldue.' = $'.$fin['latefee'].'<br><br>The late fee is calculated on the total due minus any class or activity preorders.';
							$latefeemath = $event['paymenttiers']['lamount'].'% of $'.$totaldue;
						} elseif ($event['paymenttiers']['lper'] == 1) {
							$latefeedescription = 'A late fee of $'.$event['paymenttiers']['lamount'].' per person is applied for registrations that are not paid in full before '.date('F j, Y g:i:sa', $event['paymenttiers']['ldate']).'.<br><br>'.$counts['total'].' x $'.$event['paymenttiers']['lamount'].' = $'.$fin['latefee'];
							$latefeemath = $counts['total'].' x $'.$event['paymenttiers']['lamount'];
						} else {
							$latefeedescription = 'A late fee of $'.$event['paymenttiers']['lamount'].' is applied for registrations that are not paid in full before '.date('F j, Y g:i:sa', $event['paymenttiers']['ldate']).'.';
							$latefeemath = '$'.$event['paymenttiers']['lamount'];
						}
							?><tr><td colspan="3"><strong>Registration Fees</strong> <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Discounts" data-placement="top" data-content="Registration fees are fees added on when late payments or returned checks occur. Contact us for more information."></i></td></tr><tr>
				    		<td>Late Fee <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Late Fee" data-placement="top" data-content="<?php echo $latefeedescription; ?>"></i></td>
				    		<td><?php echo $latefeemath; ?></td>
				    		<td>= $<?php echo $fin['latefee']; ?></td>
						</tr><?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="tab-pane fade" id="payments">
		    <p>This is a list of all payments made for this event.</p>
			<table class="table table-condensed ">
			    <thead>
			  	   	<tr><th>Status</th><th>Type</th><th>Date</th><th>Amount</th><th class="right">Tools</th></tr>
			    </thead>
			    <tbody>
			  	<?php 
			  	$regtitles = $this->shared->get_reg_set_titles($reg['id']);
			  	foreach ($payments as $p):
			  	$c = false;
			  	$d = false;
			  	?>	<tr>
			      		<td><span class="badge <?php if($p['type']=='transfer' || $p['type']=='credit') { echo "badge-inverse"; } elseif($p['status']=='Completed') { echo "badge-success"; } elseif ($p['status']=='Cancelled') { $c=true; } elseif ($p['status']=='Pending') { echo "badge-info"; } else { echo "badge-important"; } ?> camperhoverpopover" data-toggle="popover" title="Payment Details" data-placement="top" data-content="<strong>Payer:</strong> <?php echo $this->shared->get_user_name($p['user'],true); ?><br /><strong>Type:</strong> <?php echo ucwords($p['type']); ?><br /><strong>Status:</strong> <?php echo ucwords($p['comment']); ?><br /><strong>Time:</strong> <?php echo date('F j, Y g:i:sa', $p['date']); ?><br /><?php if ($p['type'] == 'check') { $p['__details'] = unserialize($p['details']); ?><strong>Check Number:</strong> <?php echo $p['__details']['number']; ?><br /><strong>Check Amount:</strong> $<?php echo $p['__details']['amount']; ?><br /><strong>Name on Check:</strong> <?php echo $p['__details']['name']; ?><br /><?php } ?><strong>Notes:</strong> <?php echo $p['notes']; ?><br />"><?php if($c) echo '<strong>'; echo ($p['type']=='transfer' || $p['type']=='credit') ? ucfirst($p['type']): $p['status']; if($c) echo '</strong>'; ?></span></td>
			  			<td<?php if($c) echo ' class="fiftypercent strikethrough"'; ?>><?php echo ucwords($p['type']); ?></td>
			  			<td<?php if($c) echo ' class="fiftypercent strikethrough"'; ?>><?php echo date('F j, Y g:i:sa', $p['date']); ?></span></td>
			  			<td<?php if($c) echo ' class="fiftypercent strikethrough"'; ?>>$<?php echo $p['amount']; ?></i></td>
			  			<td><span class="right"><?php if($p['status']=='Pending') echo anchor('api/v1/pay/approve?token='.$p['token'].'&return='.uri_string(), ' Approve', 'class="btn btn-small teal icon icon-ok"'); ?> <?php // if($p['type']=='paypal' && $p['status']=='Completed') echo anchor('api/v1/pay/refund?token='.$p['token'], ' ', 'class="btn btn-small tan icon icon-share-alt"'); ?> <?php //if($p['type']!=='paypal') echo anchor('api/v1/pay/updateX?token='.$p['token'], ' ', 'class="btn btn-small tan icon icon-pencil"'); ?> <?php echo anchor('payments/'.$p['token'], ' ', 'class="btn btn-small tan icon icon-pencil"'); ?> <?php echo anchor('api/v1/pay/delete?token='.$p['token'], ' ', 'class="btn btn-small red icon icon-remove"'); ?></span></td>
			 		</tr>
			 	<?php endforeach;?>
			</table>
		</div>
		<div class="tab-pane fade" id="payment">
		    <p>Easily add a payment for this registration. If you mark the payment as completed, it will count in the amount paid immediately and the leaders will be notified.</p>
			<div class="clear"></div>
			<div class="camperform float " style="width: 40%">
			    <select id="ftype" name="type">
			    	<option value="check">Check</option>
			    	<option value="paypalm">PayPal (manual)</option>
			    	<option value="cash">Cash</option>
			    	<option value="credit">Credit</option>
			    	<option value="refund">Refund</option>
			    	<option value="transfer">Transfer</option>
			    	<option value="card">Credit/Debit Card (manual)</option>
			    	<option value="other">Other Payment Type</option>
			    </select>
			    <label for="ftype">Type</label>
			</div>
			<div class="camperform float " style="width: 40%">
			    <select id="fstatus" name="status">
			    	<option value="Completed">Approved and Completed</option>
			    	<option value="Pending">Pending</option>
			    	<option value="Rejected">Rejected</option>
			    	<option value="Cancelled">Cancelled</option>
			    </select>
			    <label for="ftype">Status</label>
			</div>
			<div class="camperform float " style="width: 40%"><input type="text" name="amount" id="famount" value="" placeholder="$" data-toggle="tooltip" title="Enter the amount, you don't need to include the dollar sign or commas." /><label for="famount">Amount</label></div>
			<div class="camperform float last" style="width: 30%"><input type="text" name="date" class="datepicker" id="fdate" value="<?php echo date('F d, Y'); ?>" placeholder="June 15, 2013" data-toggle="tooltip" title="Enter the payment effective date." /><label for="fdate">Date</label></div>
			<div class="clear"></div>
			<div class="camperform float" style="width: 15%"><input type="text" name="checknum" id="fchecknum" value="" placeholder="none" data-toggle="tooltip" title="Optional. Check Number or reference number, if any." /><label for="fchecknum">Check Number</label></div>
			<div class="camperform float" style="width: 40%"><input type="text" name="checkname" id="fcheckname" value="" placeholder="none" data-toggle="tooltip" title="Optional. Name on Check, if any." /><label for="fcheckname">Check Name</label></div>
			<div class="clear"></div>
			<div class="camperform float" style="width: 80%"><input type="text" name="notes" id="fnotes" value="" placeholder="Notes" data-toggle="tooltip" title="Optional, save notes with this payment" /><label for="fnotes">Notes</label></div>
			<div class="clear"></div>
			<input type="submit" name="submit" value="Create Payment" class="btn teal" data-loading-text="Creating this payment..." onclick="$(this).button('loading');"  /> <input type="reset" name="reset" value="Reset" class="btn tan"  />	
			<input type="hidden" name="return" id="paymentreturn" value="<?php echo uri_string(); ?>" />
			<input type="hidden" name="regid" id="regid" value="<?php echo $reg['id']; ?>" />
			<!-- <?php echo form_close(); ?> -->
		</div>
	</div>
	<div class="clear hr"></div>
    <h3>Participants</h3>
	<p>Enter the contact email addresses below and they will be invited to join this unit. They will get an email from us stating that you (as an admin) requested they set up an account with this unit on Camper.</p>
    <p>There are <strong><?php echo $session['limithard']-$session['count'] ?> open spots</strong> for this session and <?php echo $unit['unittype'].' '.$unit['number']; ?> has a total of <strong><?php echo $reg['youth']+$reg['male']+$reg['female'] ?> people registered</strong></i>.</p>
    <div class="camperform float" style="width: 90px"><input id="fyouth" class="warnchange" name="youth" type="text" placeholder="none" value="<?php echo $reg['youth']; ?>"  /><label for="fyouth">Youth</label></div>
    <div class="camperform float" style="width: 90px"><input id="fmale" class="warnchange" type="text" name="male" placeholder="none" value="<?php echo $reg['male']; ?>"  /><label for="fmale">Male Adults</label></div>
    <div class="camperform float" style="width: 90px"><input id="ffemale" class="warnchange" type="text" name="female" placeholder="none" value="<?php echo $reg['female']; ?>"  /><label for="ffemale">Female Adults</label></div>
    <!--<div class="camperform float" style="width: 90px" data-toggle="tooltip" title="SPECIAL - THIS IN IN THE WORKS! Woo hoo!"><input id="ffamily" class="warnchange" name="family" type="text" placeholder="none" value="<?php echo $reg['family']; ?>"  /><label for="ffamily">Special Count (in the works)</label></div>--><input type="hidden" name="family" value="0" />
	<?php if($groups['enabled'] == '1'): $currentgroup = (isset($reg['group'])) ? $reg['group'] : false; ?>
		<div class="clear hr"></div>
   	<h3><?php echo $groups['title']; ?></h3>
   	<p><?php echo $groups['desc']; ?></p>
   	<p><?php $i=0; foreach ($groups['groups'] as $group): 
       	// Let's do some math
       	// Open Spots
       	if (is_null($group['limit']) || $group['limit'] == '') {
       		// Group limit is not set
       		$group['__openspots'] = $session['limithard']-$session['count'];
       	} else {
       		// Group limit is set
       		$group['__openspots'] = $group['limit']-$this->shared->count_group($group['id'],$session['id'],true);
       	}
       	if ($group['__openspots'] > 0) {
    	   	// Group has space
    	   	$group['__message'] = $group['title'].' has '.$group['__openspots'].' open spots';
    	   	$group['__full'] = false;
       	} else {
    	   	// Group is full
    	   	$group['__message'] = $group['title'].' is full. If you are registered in this '.$groups['title'].' and change to another, you may lose your spots.';
    	   	$group['__full'] = ($this->ion_auth->is_admin()) ? false : true;
       	}
   	?></p>
    <div class="camperform float cbl" data-toggle="tooltip" title="<?php echo $group['__message']; ?>" style="width: auto;"><input type="radio" class="cbl"<?php if ($group['__full']) { ?> disabled="disabled"<?php } ?> value="<?php echo $group['id']; ?>"<?php if ($currentgroup == $group['id']){ ?> checked="checked"<?php } ?> name="fgroup" id="fgroup<?php echo $i; ?>" /><label for="fgroup<?php echo $i; ?>" class="cbl" ><?php echo $group['title']; ?></label></div>
   	<?php $i++; endforeach; ?>
	<?php endif; ?>
    <div class="clear hr"></div> 

   	<?php $i=0; if (count($options > 0)) : $i++; ?>
	<h3>Options</h3>
	<p>These are the options that you have for <?php echo $event['title']; ?>. Some options have a cost and others are just items that allow us to better prepare for the upcoming event. If an option has a cost or fee, it will be stated here and will be clearly displayed in the event fees section.</p>
	<div class="clear"></div>
	<?php if ($event['activitypreorders'] == 1) { ?><div class="camperform float cbl" style="width:auto;"><input type="checkbox" class="cbl warnchange" <?php if($reg['activitypreorders'] == 1) { ?> checked="checked"<?php } ?> name="activitypreorders" id="fpreorders" /><label for="fpreorders" class="cbl" >Preorder Activity Supplies</label><small>Preordering activity supplies allows you to pay for activity costs up front.</small></div><div class="camperform float " style="width: 30%"><?php if ($reg['activitypreorders'] == 1) echo anchor('api/v1/preorders/reg/'.$reg['id'].'.pdf', '<i class="icon-file-text inline"></i> Print/View Preorders', 'class="btn tan" style="margin-top: 34px;"'); ?></div><div class="clear"></div><?php } ?>
	<?php if ($event['bluecards'] == 1) { ?><div class="camperform float cbl" style="width:auto;"><input type="checkbox" class="cbl warnchange" <?php if($reg['bluecards'] == 1) { ?> checked="checked"<?php } ?> name="bluecards" id="fbluecards" /><label for="fbluecards" class="cbl" >Request Blue Cards</label><small>Request blue cards for merit badges to be provided at camp.</small></div><div class="camperform float " style="width: 30%"><?php echo anchor('api/v1/bluecard/reg/'.$reg['id'].'.pdf', '<i class="icon-file-text inline"></i> Print/View Blue Cards', 'class="btn tan" style="margin-top: 34px;"'); ?></div><div class="clear"></div><?php } ?>
	<?php if (isset($event['paymenttiers']['l']) && $event['paymenttiers']['l'] == 1 ) { ?><input type="hidden" name="latefeeflag" value="1" /><div class="camperform float cbl" style="width:auto;"><input type="checkbox" class="cbl warnchange" <?php if($reg['latefeeexempt'] == 1) { ?> checked="checked"<?php } ?> name="latefee" id="flatefee" /><label for="flatefee" class="cbl" >Exempt from late fees</label><small>You can exempt this registration from any late fees. This will only apply to units that have incurred a late fee. This is not visible to leaders.</small></div><?php } ?>
	<div class="clear"></div>

	<!-- Start Custom Options -->
	<?php foreach ($options as $o) : ?>
    	<?php if ($o['checkbox'] == 1) { ?><div class="camperform float cbl" style="width:60%;"><input type="checkbox" class="cbl warnchange" <?php if(isset($reg['options'][$o['id']]['checkbox'])) { ?> checked="checked"<?php } ?> name="options[<?php echo $o['id']; ?>][checkbox]" id="foc<?php echo $o['id']; ?>" /><label for="foc<?php echo $o['id']; ?>" class="cbl" ><?php echo $o['title']; ?></label><small><?php echo $o['description']; ?></small></div><?php } ?>
    	<?php if ($o['value'] == 1) { ?><div class="camperform float " style="width: 30%"><input type="text" name="options[<?php echo $o['id']; ?>][value]" id="fov<?php echo $o['id']; ?>" value="<?php if(isset($reg['options'][$o['id']]['value'])) { echo $reg['options'][$o['id']]['value']; } ?>" placeholder="none" data-toggle="tooltip" title="The location of the event" /><label for="fov<?php echo $o['id']; ?>"><?php echo $o['title']; ?></label></div><?php } ?>
    	<div class="clear"></div>
	<?php endforeach; ?>
   	<?php endif; ?>
   	<?php if (count($discounts > 0)) : $i++; ?>
    <div class="clear"></div> 
	<div class="clear hr"></div>
	<h3>Discounts</h3>
	<p>These are all of the discounts for <?php echo $event['title']; ?>. Select discounts that apply to your unit, discounts may require council verification. When verified, the discounts will be applied and visible in the finances section.</p>
	<div class="clear"></div>
	<?php foreach ($discounts as $o) : ?>
    	<?php if ($o['checkbox'] == 1) { ?><div class="camperform float cbl" style="width:60%;"><input type="checkbox" class="cbl warnchange" <?php if(isset($reg['discounts'][$o['id']]['checkbox'])) { ?> checked="checked"<?php } ?> name="discounts[<?php echo $o['id']; ?>][checkbox]" id="foc<?php echo $o['id']; ?>" /><label for="foc<?php echo $o['id']; ?>" class="cbl" ><?php echo $o['title']; ?></label><small><?php echo $o['description']; ?></small></div><?php } ?>
    	<?php if ($o['value'] == 1) { ?><div class="camperform float " style="width: 30%"><input type="text" name="discounts[<?php echo $o['id']; ?>][value]" id="fov<?php echo $o['id']; ?>" value="<?php if(isset($reg['discounts'][$o['id']]['value'])) { echo $reg['discounts'][$o['id']]['value']; } ?>" placeholder="none" data-toggle="tooltip" title="The location of the event" /><label for="fov<?php echo $o['id']; ?>"><?php echo $o['title']; ?></label></div><?php } ?>
    	<div class="clear"></div>
	<?php endforeach; ?><!-- End Custom Options -->
	<div class="clear"></div>
   	<?php endif; ?>
	<div class="clear hr"></div>

	<h3>Roster of Participants</h3>
    <ul id="rosterstabs" class="detailstabs red">
		<li class="active"><a href="#roster-youth" data-toggle="tab">Youth</a></li>
		<li class=""><a href="#roster-adults" data-toggle="tab">Adults</a></li>
	</ul>
    <div class="tab-content">
		<div class="tab-pane fade in active" id="roster-youth">
			<?php $hasroster = true;
				if (empty($rosters)) { 
					$hasroster = false; ?><p><strong>This registration has no roster, you can create one below.</strong></p><?php } else { ?> 
			<p>This is a list of all members on the roster for this event. Click on edit to see any individual discounts, options, or class registrations.</p><?php } ?>
    		<table class="table table-condensed">
    			<thead>
    				<tr><th>Name</th><th>Age</th><th>Shirt Size</th><th>Special Notes</th><th style="text-align:right;">Tools</th></tr>
    			</thead>
    			<tbody>
    			<?php 
    				$now = time();
    				$i=1;
    				$adult = ($unit['unittype'] == 'Ship' || $unit['unittype'] == 'Crew') ? (31556926 * 21): (31556926 * 18); // 21 and 18 years in seconds
    				if ($hasroster) : foreach ($rosters as $roster) :
    					// Real
    					if ($now-$roster['member']['dob'] >= $adult) continue; 
    					unset($members[$roster['member']['id']]);
    					?>
    					<tr>
    						<td><?php echo anchor('event/'.$event['id'].'/registrations/'.$reg['id'].'/roster/'.$roster['id'], $roster['member']['name']); ?></td>
    						<td><?php echo floor((($now-$roster['member']['dob']) / 31556926)); ?></td>
    						<td><?php echo $roster['member']['shirtsize']; ?></td>
    						<td><?php if (!empty($roster['member']['allergies']) || !empty($roster['member']['diet']) || !empty($roster['member']['medical']) || !empty($roster['member']['notes'])) {
    							?><span class="label label-tan camperhoverpopover" data-toggle="popover" title="Special Notes" data-placement="top" data-content="
    							Special notes are details that you want to share with event/camp staff, we use this to better accomidate and serve you.<br><br>
    							<?php echo (!isset($roster['member']['allergies']) || empty($roster['member']['allergies'])) ? '': '<strong>Allergies</strong>: '.$roster['member']['allergies'].' <br>'; ?>
    							<?php echo (!isset($roster['member']['diet']) || empty($roster['member']['diet'])) ? '': '<strong>Dietary Restrictions</strong>: '.$roster['member']['diet'].' <br>'; ?>
    							<?php echo (!isset($roster['member']['medical']) || empty($roster['member']['medical'])) ? '': '<strong>Medical Conditions</strong>: '.$roster['member']['medical'].' <br>'; ?>
    							<?php echo (!isset($roster['member']['notes']) || empty($roster['member']['notes'])) ? '': '<strong>Notes</strong>: '.$roster['member']['notes'].' <br>'; ?>
    							<br>You can add or edit these restrictions by editing this member's profile, click the edit button to head there.
    							">Notes</span><?php } ?></td>
    						<td style="text-align:right;"><?php echo anchor('event/'.$event['id'].'/registrations/'.$reg['id'].'/roster/'.$roster['id'], '<i class="icon-group"></i> Classes', 'class="btn btn-small blue"'); ?> <?php echo anchor('api/v1/rosters/invoice/'.$roster['id'].'.pdf', '<i class="icon-file-text"></i>', 'class="btn btn-small tan"  data-toggle="tooltip" title="Download an individual invoice and schedule PDF"'); ?> <?php echo anchor('unit/members/'.$roster['member']['id'], '<i class="icon-pencil"></i>', 'class="btn btn-small tan" data-toggle="tooltip" title="Edit Member"'); ?> <a data-toggle="popover" title="Delete" data-placement="top" data-content="Are you sure you want to remove this member from your roster? They will be unregistered for any classes.<br /><br />Don't worry, <?php echo $roster['member']['name']; ?> won't be deleted from Camper.<br /><br /><?php echo str_replace('"', "'", anchor('api/v1/roster/delete?m='.$roster['member']['id'].'&r='.$roster['id'].'&return='.uri_string(), 'Delete '.$roster['member']['name'], 'class="btn red"')); ?>" class="btn btn-small red camperpopover"><i class="icon-remove"></i></a></td>
    					</tr>
    				<?php $i++; endforeach; endif; ?>
    				<?php while ($i <= $reg['youth']) { ?>
    					<tr>
    						<td colspan="8">
    							<div class="camperform float last" style="">
    							<select id="fymember<?php echo $i; ?>" name="youth[<?php echo $i; ?>][id]" data-toggle="tooltip" title="Choose a member, make sure you haven't chosen anyone more than once.">
    								<option value="0">Choose a youth...</option>
    								<option value="0">Empty Spot / Placeholder</option>
    								<optgroup label="Youth in <?php echo $unittitle; ?>">
    								<?php foreach ($members as $m) { if ($now-$m['dob'] >= $adult) continue; ?><option value="<?php echo $m['id']; ?>"><?php echo $m['name']; ?></option><?php } ?>
    								</optgroup>
    							</select> or &nbsp; <?php echo anchor('units/'.$unit['id'].'/members/new?return='.uri_string(), 'Add a member '.$unittitle.' &rarr;', 'class="btn btn-small tan"'); ?> 
    							</div>
    						</td>
    					</tr>
    				<? $i++; } ?>
    			</tbody>
    		</table>
		</div>
		<div class="tab-pane fade" id="roster-adults">
			<p>This is a list of all members on the roster for this event. Click on edit to see any individual discounts, options, or class registrations.</p>
    		<table class="table table-condensed">
    			<thead>
    				<tr><th>Name</th><th>Age</th><th>Shirt Size</th><th>Special Notes</th><th style="text-align:right;">Tools</th></tr>
    			</thead>
    			<tbody>
    			<?php 
    				$i=1;
    				if ($hasroster) : foreach ($rosters as $roster) :
    					// Real
    					if ($now-$roster['member']['dob'] < $adult) continue; 
    					unset($members[$roster['member']['id']]);
    					?>
    					<tr>
    						<td><?php echo anchor('event/'.$event['id'].'/registrations/'.$reg['id'].'/roster/'.$roster['id'], $roster['member']['name']); ?></td>
    						<td><?php echo floor((($now-$roster['member']['dob']) / 31556926)); ?></td>
    						<td><?php echo $roster['member']['shirtsize']; ?></td>
    						<td><?php if (!empty($roster['member']['allergies']) || !empty($roster['member']['diet']) || !empty($roster['member']['medical']) || !empty($roster['member']['notes'])) {
    							?><span class="label label-tan camperhoverpopover" data-toggle="popover" title="Special Notes" data-placement="top" data-content="
    							Special notes are details that you want to share with event/camp staff, we use this to better accomidate and serve you.<br><br>
    							<?php echo (!isset($roster['member']['allergies']) || empty($roster['member']['allergies'])) ? '': '<strong>Allergies</strong>: '.$roster['member']['allergies'].' <br>'; ?>
    							<?php echo (!isset($roster['member']['diet']) || empty($roster['member']['diet'])) ? '': '<strong>Dietary Restrictions</strong>: '.$roster['member']['diet'].' <br>'; ?>
    							<?php echo (!isset($roster['member']['medical']) || empty($roster['member']['medical'])) ? '': '<strong>Medical Conditions</strong>: '.$roster['member']['medical'].' <br>'; ?>
    							<?php echo (!isset($roster['member']['notes']) || empty($roster['member']['notes'])) ? '': '<strong>Notes</strong>: '.$roster['member']['notes'].' <br>'; ?>
    							<br>You can add or edit these restrictions by editing this member's profile, click the edit button to head there.
    							">Notes</span><?php } ?></td>
    						<td style="text-align:right;"><?php echo anchor('event/'.$event['id'].'/registrations/'.$reg['id'].'/roster/'.$roster['id'], '<i class="icon-group"></i> Classes', 'class="btn btn-small blue"'); ?> <?php echo anchor('api/v1/rosters/invoice/'.$roster['id'].'.pdf', '<i class="icon-file-text"></i>', 'class="btn btn-small tan"  data-toggle="tooltip" title="Download an individual invoice and schedule PDF"'); ?> <?php echo anchor('unit/members/'.$roster['member']['id'], '<i class="icon-pencil"></i>', 'class="btn btn-small tan" data-toggle="tooltip" title="Edit Member"'); ?> <a data-toggle="popover" title="Delete" data-placement="top" data-content="Are you sure you want to remove this member from your roster? They will be unregistered for any classes.<br /><br />Don't worry, <?php echo $roster['member']['name']; ?> won't be deleted from Camper.<br /><br /><?php echo str_replace('"', "'", anchor('api/v1/roster/delete?m='.$roster['member']['id'].'&r='.$roster['id'].'&return='.uri_string(), 'Delete '.$roster['member']['name'], 'class="btn red"')); ?>" class="btn btn-small red camperpopover"><i class="icon-remove"></i></a></td>
    					</tr>
    				<?php $i++; endforeach; endif; ?>
    				<?php while ($i <= ($reg['male'] + $reg['female'])) { ?>
    					<tr>
    						<td colspan="8">
    							<div class="camperform float last" style="">
    							<select id="famember<?php echo $i; ?>" name="adults[<?php echo $i; ?>][id]" data-toggle="tooltip" title="Choose a member, make sure you haven't chosen anyone more than once.">
    								<option value="0">Choose a adult...</option>
    								<option value="0">Empty Spot / Placeholder</option>
    								<optgroup label="Adults in <?php echo $unittitle; ?>">
    								<?php foreach ($members as $m) { if ($now-$m['dob'] < $adult) continue; ?><option value="<?php echo $m['id']; ?>"><?php echo $m['name']; ?></option><?php } ?>
    								</optgroup>
    							</select> or &nbsp; <?php echo anchor('units/'.$unit['id'].'/members/new?return='.uri_string(), 'Add a member to '.$unittitle.' &rarr;', 'class="btn btn-small tan"'); ?> 
    							</div>
    						</td>
    					</tr>
    				<? $i++; } ?>
    			</tbody>
    		</table>
		</div>
    </div>
	<div class="clear"></div>
