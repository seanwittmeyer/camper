<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin Payments Detail Page
 *
 * This is the ...
 *
 * File: /application/views/admin/payments/checkform.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 $payment['__details'] = unserialize($payment['details']);
 
?>	<script>
		$(document).ready(function() {
			$('.regslist.typeahead').typeahead({							  
			  limit: '10',														
			  prefetch: '/camper/api/v1/regs.json?123', 
			  template: [
				'<p class="typeahead-num">{{session}}</p>',
				'<p class="typeahead-name">{{event}}</p>',
				'<p class="typeahead-num">(reg #{{id}})</p>',
				'<p class="typeahead-city">{{eventlocation}}</p>',   
				'<p class="typeahead-city"><strong>{{unit}}</strong>, {{city}} ({{council}})</p>'
			  ].join(''),																 
			  engine: Hogan															   
			});
			$('.typeahead').on('typeahead:autocompleted', function(evt, item) {
				$("#paymentdetails").removeClass('hidden');
				$("#regidh").val(item['id']);
				$("#fregid").html('<i class="icon-ok teal right"></i> '+item['id']);
				$("#event").text(item['event']);
				$("#unit").text(item['unit']);
				$("#session").text(item['session']);
			})
			$('.typeahead').on('typeahead:selected', function(evt, item) {
				//window.location.href = '<?php echo base_url(); ?>users/edit/' + item['userid'];
				$("#paymentdetails").removeClass('hidden');
				$("#regidh").val(item['id']);
				$("#fregid").html('<i class="icon-ok teal right"></i> '+item['id']);
				$("#event").text(item['event']);
				$("#unit").text(item['unit']);
				$("#session").text(item['session']);
			})
		});
	</script>

	<?php echo form_open(uri_string());?>
		<?php echo form_hidden('token', $payment['token']);?>
		<?php echo form_hidden($csrf); ?>
	<article class="textsection">
		<div class="quarter">
			<h2 class="">Payments</h2>
			<p>You can make changes and edit this payment here. You can also choose to switch which event to apply this payment.</p>
			<p><?php echo anchor('payments', '&larr; All Payments', 'class="btn tan noprint"'); ?></p>
		</div>
		<div class="threequarter last">
			<h2>Payment Details</h2>
			<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
			<p>This is the payment confirmation of <strong>$<?php echo $payment['amount']; ?></strong> for <strong><?php echo $regtitles['event']; ?></strong>. You can edit the payment details below, the unit leaders will be notified of any changes.</p>
			<div class="clear"></div>
			<strong>Time:</strong> <?php echo date('F j, Y g:i:sa', $payment['date']); ?> | 
			<strong>ID:</strong> <?php echo $payment['token']; ?> | 
			<strong>Status:</strong> <?php echo $payment['status']; ?>
			<div class="clear hr"></div>
			<h2 class="">Basics</h2>
	   		<div class="camperform float " style="width: 20%"><input type="text" name="amount" id="famount" value="$<?php echo $this->shared->number_format_drop_zero($payment['amount']); ?>" placeholder="$" data-toggle="tooltip" title="Enter the amount, you don't need to include the dollar sign or commas." /><label for="famount">Amount</label></div>
	   		<div class="camperform float " style="">
	   			<select id="ftype" name="type">
					<option value="<?php echo $payment['type']; ?>"><?php echo ucfirst($payment['type']); ?></option>
					<optgroup label="or change to...">
						<option value="paypalm">PayPal (manual)</option>
						<option value="cash">Cash</option>
						<option value="check">Check</option>
						<option value="refund">Refund</option>
						<option value="transfer">Transfer</option>
						<option value="card">Credit/Debit Card (manual)</option>
						<option value="other">Other Payment Type</option>
					</optgroup>
				</select>
				<label for="ftype">Type</label>
			</div>
	   		<div class="camperform float " style="">
	   			<select id="fstatus" name="status">
					<option value="<?php echo $payment['status']; ?>"><?php echo $payment['status']; ?></option>
					<optgroup label="or change to...">
						<option value="Completed">Completed</option>
						<option value="Pending">Pending</option>
						<option value="Rejected">Rejected</option>
						<option value="Cancelled">Cancelled</option>
					</optgroup>
				</select>
				<label for="fstatus">Status</label>
			</div>
			<?php if ($payment['type'] == 'check') { ?>
			<div class="clear"></div>
	   		<div class="camperform float" style="width: 15%"><input type="text" name="checknum" id="fchecknum" value="<?php echo $payment['__details']['number']; ?>" placeholder="none" data-toggle="tooltip" title="Optional. Check Number or reference number, if any." /><label for="fchecknum">Check Number</label></div>
	   		<div class="camperform float" style="width: 40%"><input type="text" name="checkname" id="fcheckname" value="<?php echo $payment['__details']['name']; ?>" placeholder="none" data-toggle="tooltip" title="Optional. Name on Check, if any." /><label for="fcheckname">Check Name</label></div>
			<div class="clear"></div>
	   		<div class="camperform float" style="width: 80%"><input type="text" name="notes" id="fnotes" value="<?php echo $payment['notes']; ?>" placeholder="Notes..." data-toggle="tooltip" title="Optional, save notes on this payment." /><label for="fnotes">Notes</label></div>
			<?php } ?>
			<div class="clear hr"></div>
			<h2 class="">Event &amp; Session</h2>
			<a href="#modal_change_reg" class="btn btn-small tan right" data-toggle="modal">Change &rarr;</a><div class="camperform float last" style=""><span><?php echo $regtitles['event']; ?></span><label>Event</label></div>
			<div class="camperform float last" style=""><span><?php echo $regtitles['session']; ?></span><label>Session</label></div>
			<?php echo ($regtitles['group'] === false) ? '': '<div class="camperform float last" style=""><span>'.$regtitles['group'].'</span><label>Group</label></div>'; ?>
			<div class="clear hr"></div>
			<h2 class="">Unit</h2>
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
			<div class="clear hr"></div>
			<?php echo anchor('api/v1/pay/delete?token='.$payment['token'], 'Delete Payment', 'class="btn red right"'); ?><input type="submit" class="btn teal" value="Save Changes" /> <input type="reset" class="btn tan" value="Reset" /> 
		</div>
	</article>
   	<?php echo form_close();?> 
	<article class="content">
   	<!-- Change Alternate Modal -->
	<div id="modal_change_reg" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<?php echo form_open('api/v1/pay/change?return='.uri_string());?>
		<?php echo form_hidden('token', $payment['token']);?>
		<?php echo form_hidden($csrf); ?>
		<input type="hidden" id="regidh" name="reg" /> 
   		<div class="container">
   			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
       		<div class="pull">
   	    		<h2 class="pull">Edit Payment</h2>
   	    		<p>You can change which registration this payment applies to.</p>
   	    		<div class="clear"></div>
       		</div>
       		<div class="tab-content inner-push">
   				<h2 class="section">Change Registration</h2>
   				<p>Search for a registration and select a registration, when you change the reg, the payment will no longer apply to the old registration. You can change this back using the same method. </p>
				<p>Start by setting the registration this payment belongs to (beta feature)</p>
		   		<div class="camperform float search" style="width: 60%"><i class="icon-search"></i><input class="ico regslist typeahead" type="text" data-toggle="tooltip"  placeholder="Troop 1 BDSR Summer Camp..."  title="Search for an event by..." /><label>Registration Search</label></div>
		   		<div class="clear"></div>
			   	<div id="paymentdetails" class="hidden">
					<div class="camperform float " style="width:auto;"><span id="event">...</span><label>Event</label></div>
					<div class="camperform float " style="width:auto;"><span id="unit">...</span><label>Unit</label></div>
					<div class="camperform float " style="width:auto;"><span id="session">...</span><label>Session</label></div>
					<div class="camperform float last" style="width:auto;"><span id="fregid">...</span><label>Reg ID</label></div>
				   	<div class="clear"></div>
				   	<input type="submit" value="Change Registration" class="btn teal" /> <button class="btn tan" data-dismiss="modal" aria-hidden="true">Nevermind</button>
			   	</div>
   			</div>
   		</div>
   		<div class="clear"></div>
   		<?php echo form_close();?>
	</div>
   	<!-- End Modal -->
	</article>

