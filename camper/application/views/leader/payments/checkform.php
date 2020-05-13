<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Leaders Payments Check Form
 *
 * This is the ...
 *
 * File: /application/views/leaders/payments/checkform.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 $payment['__details'] = unserialize($payment['details']);
 
?>
	<article class="textsection">
		<div class="quarter">
			<h2 class="">Payments</h2>
			<p>This page will serve as the confirmation of your check payment. Please print and include it with your check.</p>
			<p><?php echo anchor('#', 'Print this page', 'onclick="window.print();return false" class="btn tan noprint"'); ?></p>
			<p>Once you print this page to accompany your payment, you can head back to your registration.</p>
			<p><?php echo anchor('registrations/'.$payment['reg'].'/details', 'Back to your registration &rarr;', 'class="btn teal noprint"'); ?></p>
		</div>
		<div class="threequarter last">
			<h2>Payment Details</h2>
			<div class="alert"><button type="button" class="close" data-dismiss="alert">×</button><p><i class="icon-ok teal"></i> Thank you for your payment! Please print and send this page with your check to our address below.</p></div>
			<p>Thank you for your check payment of <strong>$<?php echo $payment['__details']['amount']; ?></strong> for <strong><?php echo $regtitles['event']; ?></strong>. In order to help us verify it quickly, please print and send in this page with your payment.</p>
			<div class="camperform float last" style=""><span>$<?php echo $this->shared->number_format_drop_zero($payment['__details']['amount']); ?></span><label>Amount</label></div>
			<div class="camperform float last" style=""><span>#<?php echo $payment['__details']['number']; ?></span><label>Number</label></div>
			<div class="camperform float last" style=""><span><?php echo $payment['__details']['name']; ?></span><label>Name on Check</label></div>
			<div class="clear hr"></div>
			<div class="camperform float last" style=""><span><?php echo $regtitles['event']; ?></span><label>Event</label></div>
			<div class="camperform float last" style=""><span><?php echo $regtitles['session']; ?></span><label>Session</label></div>
			<?php echo ($regtitles['group'] === false) ? '': '<div class="camperform float last" style=""><span>'.$regtitles['group'].'</span><label>Group</label></div>'; ?>
			<div class="clear"></div>
			<?php if ($individual) { ?>
			<div class="camperform float last" style=""><span><?php echo $user->first_name.' '.$user->last_name; ?></span><label>Name</label></div>
			<div class="camperform float last" style=""><span><?php echo $unit['unittype'].' '.$unit['number']; ?></span><label>Unit</label></div>
			<div class="camperform float last" style=""><span><?php echo $unit['city'].', '.$unit['state']; ?></span><label>City</label></div>
			<div class="camperform float last" style=""><span><?php echo $unit['council']; ?></span><label>Council</label></div>
			<?php } else { ?>
			<div class="camperform float last" style=""><span><?php echo (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')': $unit['unittype'].' '.$unit['number']; ?></span><label>Unit</label></div>
			<div class="camperform float last" style=""><span><?php echo $unit['city'].', '.$unit['state']; ?></span><label>City</label></div>
			<div class="camperform float last" style=""><span><?php echo $unit['council']; ?></span><label>Council</label></div>
			<?php } ?>
			<div class="clear"></div>
			<strong>Time:</strong> <?php echo date('F j, Y g:i:sa', $payment['date']); ?> | 
			<strong>ID:</strong> <?php echo $payment['token']; ?> | 
			<strong>Status:</strong> <?php echo $payment['status']; ?>
			<div class="clear hr"></div>
			<div class="quarter">
				<h3>Submit by Mail</h3>
				<p>Mail your check with a printout of this page to: </p>
				<p>Longs Peak Council - Camp Registration<br />
					PO Box 1166<br />
					Greeley, CO 80632-1166
				</p>
			</div>
			<div class="quarter last">
				<h3>In Person</h3>
				<p>Bring your check to our Greeley Service Center with the printout of this page to: </p>
				<p>Longs Peak Council - Greeley Service Center<br />
					2215 23rd Avenue<br />
					Greeley, Colorado
				</p>
			</div>
		</div>
	</article>

<?php /*

		  			<p><strong>Type:</strong> <?php echo ucwords($payment['type']); ?><br />
		  			<strong>Status:</strong> <?php echo ucwords($payment['comment']); ?><br />
		  			<strong>Time:</strong> <?php echo date('F j, Y g:i:sa', $payment['date']); ?><br />
		  			<?php if ($payment['type'] == 'check') { $payment['__details'] = unserialize($payment['details']); ?><strong>Check Number:</strong> <?php echo $payment['__details']['number']; ?><br />
		  			<strong>Check Amount:</strong> $<?php echo $payment['__details']['amount']; ?><br />
		  			<strong>Name on Check:</strong> <?php echo $payment['__details']['name']; ?><br /><?php } ?>
		  			</p>
		  			<p><?php echo ucwords($payment['type']); if ($payment['type'] == 'check') echo ' (#'.$payment['__details']['number'].')'; ?></p>
		  			<p><span data-toggle="tooltip" class="moment-format" title="<?php echo date('F j, Y g:i:sa', $payment['date']); ?>" ><?php echo $payment['date']; ?></span></p>
		  			<p>$<?php echo $payment['amount']; ?></i></p>
		  			<p><?php echo $this->shared->get_user_name($payment['user'],true); ?></i></p>
		  			<p><?php echo $regtitles['event']; ?></i></p>
		  			<p><?php echo $regtitles['session']; ?></i></p>
		  			<p><?php echo $payment['status']; ?></i></p>
		  			</div>
*/ ?>