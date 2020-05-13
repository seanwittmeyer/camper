<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin Payments / New View
 *
 * This is the ...
 *
 * File: /application/views/admin/payments/new.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

?>
	<script>
		$(document).ready(function() {
			$('.regslist.typeahead').typeahead({							  
			  limit: '10',														
			  prefetch: '<?php echo $this->config->item('camper_path'); ?>api/v1/regs.json?123', 
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
	<div class="subnav">
		<div class="container">
			<h2>Payments</h2>
			<nav class="campersubnav">
				<li class="active"><?php echo anchor("payments/new", '<i class="icon-plus"></i>');?></li>
				<li class=""><?php echo anchor("payments", 'All Payments');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
   		<h2 class="">New Payment</h2>
   		<p>It's simple to add a payment in Camper. When you add a payment, you are associating a payment to a unit's registration. This means that a unit registered for multiple events can not pay for multiple events with one payment. Fill in the payment type, date (leave blank for today), amount, and status. Then, use the search box to find the unit registration the payment should be applied to. You can find the unit registration number on the Event > Registrations page or by searching below.</p>
		<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
   		<div class="clear"></div>
	</article>
	<article class="textsection">
	<?php echo form_open(uri_string());?>
	<input type="hidden" id="regidh" name="regid" /> 

   		<div class="container">
	   		<div class="quarter">
   				<h2>Details</h2>
   				<p>Creating a payment is easy. Start by setting the registration by searching. Once that is set, enter the details and you are done.</p>
   				<input type="submit" name="submit" value="Create Payment" class="btn teal" data-loading-text="Creating payment..." onclick="$(this).button('loading');" /> <input type="reset" name="reset" value="Reset" class="btn tan"  />	
   				<div class="clear"></div>
	   		</div>
	   		<div class="threequarter">
				<p>Start by setting the registration this payment will be applied to.</p>
		   		<div class="camperform float search" style="width: 60%"><i class="icon-search"></i><input class="ico regslist typeahead" type="text" data-toggle="tooltip"  placeholder="Troop 1 BDSR Summer Camp..."  title="Search for an event by..." /><label>Registration Search</label></div>
		   		<div class="clear"></div>
			   	<div id="paymentdetails" class="hidden">
				   	<div class="camperform float " style="width:auto;"><span id="event">...</span><label>Event</label></div>
				   	<div class="camperform float " style="width:auto;"><span id="unit">...</span><label>Unit</label></div>
				   	<div class="camperform float " style="width:auto;"><span id="session">...</span><label>Session</label></div>
				   	<div class="camperform float last" style="width:auto;"><span id="fregid">...</span><label>Reg ID</label></div>
			   		<div class="clear"></div>
	   				<div class="camperform float " style="width: 40%">
	   					<select id="ftype" name="type">
							<option value="check">Check</option>
							<option value="paypalm">PayPal (manual)</option>
							<option value="cash">Cash</option>
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
	   				<div class="camperform float last" style="width: 40%"><input type="text" name="amount" id="famount" value="" placeholder="$" data-toggle="tooltip" title="Enter the amount, you don't need to include the dollar sign or commas." /><label for="famount">Amount</label></div>
	   				<div class="clear"></div>
	   				<div class="camperform float" style="width: 15%"><input type="text" name="checknum" id="fchecknum" value="" placeholder="none" data-toggle="tooltip" title="Optional. Check Number or reference number, if any." /><label for="fchecknum">Check Number</label></div>
	   				<div class="camperform float" style="width: 40%"><input type="text" name="checkname" id="fcheckname" value="" placeholder="none" data-toggle="tooltip" title="Optional. Name on Check, if any." /><label for="fcheckname">Check Name</label></div>
	   				<div class="clear"></div>
	   				<div class="camperform float" style="width: 80%"><input type="text" name="note" id="fnote" value="" placeholder="Notes..." data-toggle="tooltip" title="Optional, add any notes to this payment" /><label for="fnotes">Notes</label></div>
	   				<div class="clear"></div>
	   				<div class="camperform float " style="width: 30%"><input type="text" name="date" class="datepicker" id="fdate" value="<?php echo date('F d, Y'); ?>" placeholder="June 15, 2013" data-toggle="tooltip" title="Enter the payment effective date." /><label for="fdate">Date</label></div>
	   				<div class="clear"></div>
			   	</div>
   			</div>
   		</div>
   		<div class="clear"></div>
   	<?php echo form_close();?> 
	</article>
