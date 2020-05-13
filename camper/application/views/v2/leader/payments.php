<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Payments / All View
 *
 * This.
 *
 * File: /application/views/leader/payments/payments.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 10 1909)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 
?>	<script type="text/javascript" charset="utf-8">
		$.extend( $.fn.dataTableExt.oStdClasses, {
			"sSortAsc": "header headerSortDown",
			"sSortDesc": "header headerSortUp",
			"sSortable": "header"
		});
		$(document).ready(function() {
			$.each($('.moment-format'), function(){ 
				var t = $(this).text();
				var u = moment.unix(t).format('M/D/YYYY');
				$(this).text(u);
			});
			oTable = $('.datatables').dataTable( {
				"sDom": "<'container'r>t<''<'left'i><'left'p><'right'l>>",
				"aaSorting": [[ 2, "desc" ]],
				"oLanguage": {
					"sInfo": "_START_ through _END_ of _TOTAL_ payments"
				}
			});
		});
	</script>
	<div id="help">
		<div class="container">
			<div class="quarter">
				<h2>How can we help?</h2>
				<p>Choose the section you want to learn about to see help topics. Contact us if you still need a hand.</p>
				<p><i class="icon icon-phone"></i> <?php echo $this->config->item('camper_supportphone'); ?><br /><i class="icon icon-envelope"></i> Registration Questions: <?php echo $this->config->item('camper_supportemail'); ?><br /><i class="icon icon-envelope-alt"></i> Site Issues: <?php echo $this->config->item('camper_fromemail'); ?></p>
			</div>
			<div class="threequarter last">
				<button class="btn btn-link" style="display: block; position: absolute; right: 60px; z-index:1000; " onclick="$('#help').slideToggle(100); return false; "><i class="icon icon-remove"></i> Close</button>
				<ul class="nav nav-tabs" id="myTab">
					<li class="active"><a data-toggle="tab" href="#help-listpayments">Viewing payments</a></li>
					<li><a data-toggle="tab" href="#help-makepayment">Login Issues</a></li>
				</ul>
				 
				<div class="tab-content">
					<div class="tab-pane active fade in" id="help-listpayments">
						<p>All of the payments for your individual event registrations as well as any payment made by your units are listed here. Click on a payment to see details such as who made the payment, when it was made, and any comments. You can view the check form which you will need to send in if you are paying by check here as well.</p>
					</div>
					<div class="tab-pane fade" id="help-makepayment">
						<p>You can pay by check, bank transfer, debit/credit card, or PayPal. If you are making a check payment, be sure to print the confirmation form after the payment is created and send it in with your check. If you are paying online by bank transfer, credit/debit card or with PayPal funds, start by entering the amount you want to pay then finish the payment at the PayPal website. You don't need to create a PayPal account to pay online, you can choose to continue as a guest.</p>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div><!-- /#help -->

	<article class="title">
		<!--<div class="toolbox">
			<?php echo anchor("unit", 'My Unit &rarr;', 'class="btn blue"'); ?> <?php echo anchor("unit", 'My Unit &rarr;', 'class="btn blue"'); ?>
		</div>-->
		<div class="search">
			<div class="camperform float search" style="width: 300px; margin-top: 35px;"><i class="icon-search"></i><input class="ico" type="text" onclick="$('#nav-payments').tab('show');" data-toggle="tooltip" onkeyup="oTable.fnFilter($(this).val());" placeholder="Search..."  title="Search for a payment using any combination of terms, the table below will update live." /><label>Payment Search</label></div>
		</div>
		<h1>Payments</h1>
		<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
		<?php if (isset($successmessage)&&!$successmessage=='') { ?><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon-ok teal"></i> <?php echo $successmessage; ?></div><?php } ?> 
		<p>These are all of the payments your unit has made. You can see which leader made the payment and the status. All payments, including cancelled and refunded, are listed here.</p>
		<div class="clear"></div>
    </article>
    <article class="tabset">
		<div class="tabset-nav">
			<ul>
				<li class="active"><a id="nav-payments" data-toggle="tab" href="#payments"><i class="icon icon-list"></i><strong>All Payments</strong>All of the payments you have made</a></li>
				<li><a data-toggle="tab" href="#new"><i class="icon icon-plus"></i><strong>Make a payment</strong>Start a check or online payment</a></li>
			</ul>
		</div><!-- /tabset-nav -->
		<div class="tab-content tabset-content">
			<!-- Start Tabset -->
			<div class=" tab-pane active fade in" id="payments">
				<!-- Unit details -->
				<!-- Unit registrations -->
		   		<!--<div class="camperform float search" style="width: 40%"><i class="icon-search"></i><input class="ico" type="text" data-toggle="tooltip" onkeyup="oTable.fnFilter($(this).val());" placeholder="Search..."  title="Search for a payment using any combination of terms, the table below will update live." /><label>Payment Search</label></div>
				<div class="clear"></div>-->
		      	<table class="table table-condensed datatables">
		      		<thead>
		      	   	<tr><th>Status</th><th>Type</th><th>Date</th><th>Amount</th><th>Payer</th><th>Event</th><th class="text-right">Tools</th></tr>
		      		</thead>
		      		<tbody>
					<?php 
						foreach ($payments as $p):
							$c = false;
							?>	<tr>
							<td><span class="badge <?php if($p['type']=='transfer' || $p['type']=='credit') { echo "badge-inverse"; } elseif($p['status']=='Completed') { echo "badge-success"; } elseif ($p['status']=='Cancelled') { $c=true; } elseif ($p['status']=='Pending') { echo "badge-info"; } else { echo "badge-important"; } ?> camperhoverpopover" data-toggle="popover" title="Payment Details" data-placement="top" data-content="<strong>Payer:</strong> <?php echo $p['user']['first_name'].' '.$p['user']['last_name']; ?><br /><strong>Type:</strong> <?php echo ucwords($p['type']); ?><br /><strong>Status:</strong> <?php echo ucwords($p['comment']); ?><br /><strong>Time:</strong> <?php echo date('F j, Y g:i:sa', $p['date']); ?><br /><?php if ($p['type'] == 'check') { ?><strong>Check Number:</strong> <?php echo (isset($p['details']['number'])) ? $p['details']['number']: 'None'; ?><br /><strong>Check Amount:</strong> $<?php echo $p['details']['amount']; ?><br /><strong>Name on Check:</strong> <?php echo (isset($p['details']['name'])) ? $p['details']['name']: 'None'; ?><br /><?php } ?><strong>Notes:</strong> <?php echo $p['notes']; ?><br />"><?php if($c) echo '<strong>'; echo ($p['type']=='transfer' || $p['type']=='credit') ? ucfirst($p['type']): $p['status']; if($c) echo '</strong>'; ?></span></td>
							<td<?php if($c) echo ' class="tan"'; ?>><?php echo ($p['type'] == 'check' && isset($p['details']['number'])) ? ucwords($p['type']).' (#'.$p['details']['number'].')': ucwords($p['type']); ?></td>
							<td<?php if($c) echo ' class="tan"'; ?>><span data-toggle="tooltip" class="moment-format" title="<?php echo date('F j, Y g:i:sa', $p['date']); ?>" ><?php echo $p['date']; ?></span></td>
							<td<?php if($c) echo ' class="tan"'; ?>>$<?php echo $p['amount']; ?></td>
							<td<?php if($c) echo ' class="tan"'; ?>><?php echo $p['user']['first_name'].' '.$p['user']['last_name']; ?></td>
							<td<?php if($c) echo ' class="tan"'; ?>><?php echo anchor("/registrations/".$p['reg']['id'], $p['event']['title'].'<br>'.((empty($p['session']['title'])) ? $p['event']['sessiontitle'].' '.$p['session']['sessionnum'] : $p['session']['title']));?></td>
				  			<td><?php if ($p['type'] == 'check') echo anchor('payments/checkform/'.$p['token'], 'Check form', 'class="btn btn-small tan" data-toggle="tooltip" title="Click to view the check payment detail form. You need to print and send this in with your check payment."'); ?></td>
						</tr>
					<?php endforeach;?>
		      	</table>

				<div class="clear"></div>
			</div>



			<div class="home-eventset tab-pane fade" id="new">
					<h2>Make a payment</h2>
					<p>Start a check or online payment (credit/debit card, bank transfer, or PayPal) by choosing the event you want to pay for. You can also drop a payment off at our service center.</p>
   					
			</div>
		</div>
	</article><!-- /tabset-content -->
