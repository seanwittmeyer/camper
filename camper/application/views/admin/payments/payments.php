<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin / Payments View
 *
 * This is the ...
 *
 * File: /application/views/admin/payments.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

?>
	<script type="text/javascript" charset="utf-8">
		$.extend( $.fn.dataTableExt.oStdClasses, {
			"sSortAsc": "header headerSortDown",
			"sSortDesc": "header headerSortUp",
			"sSortable": "header"
		});
		$(document).ready(function() {
			$.each($('.moment-format'), function(){ 
				var t = $(this).text();
				var u = moment.unix(t).format('YYYY-MM-DD');
				$(this).text(u);
			});
			oTable = $('.datatables').dataTable( {
				"sDom": "<'container'r><'container'<'left'i><'left'p><'right'l>>t<'container'<'left'i><'left'p><'right'l>>",
				"aaSorting": [[ 2, "desc" ]],
				"iDisplayLength": 100,
				"aLengthMenu": [[25, 50, 100, 200, -1], [25, 50, 100, 200, "All"]],
				"oLanguage": {
					"sInfo": "_START_ through _END_ of _TOTAL_ payments"
				}
			});
		});
	</script>
	<div class="subnav">
		<div class="container">
			<h2>Payments</h2>
			<nav class="campersubnav">
				<li class=""><?php echo anchor("payments/new", '<i class="icon-plus"></i>');?></li>
				<li class="active"><?php echo anchor("payments", 'All Payments');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
		<h2 class="">All Payments</h2>
		<p>All payments in Camper are listed below. Payments in Camper are either PayPal transactions that are created as the user uses PayPal, these will automatically be marked as cancelled or completed. Check payments are manually added by leaders and require an admin to be approved and count towards a camp/event fee.</p>
		<p>As an admin, you can view all payments, approve pending check payments, or delete payments (removing them from Camper). You can create payments by clicking on the (+) link in the payments menu above. Payments below can search using the live search box, simply search for anything in the table. </p>
		<p>Note: The table script is having a difficult time sorting by date. The format below is <strong>Year-Month-Day</strong></p>
		<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
		<div class="camperform float search" style="width: 40%"><i class="icon-search"></i><input class="ico" type="text" data-toggle="tooltip" onkeyup="oTable.fnFilter($(this).val());" placeholder="Paypal Cancelled..."  title="Search for a payment using any combination of terms, the table below will update live." /><label>Payment Search</label></div>
		<div class="clear"></div>
		<table class="table table-condensed datatables">
			<thead>
			<tr><th>Status</th><th>Type</th><th>Date</th><th>Amount</th><th>Unit</th><th>Event (Session)</th><th class="right">Tools</th></tr>
			</thead>
			<tbody>
			<?php 
				foreach ($payments as $p):
					$c = false;
					?>	<tr>
					<td><span class="badge <?php if($p['type']=='transfer' || $p['type']=='credit') { echo "badge-inverse"; } elseif($p['status']=='Completed') { echo "badge-success"; } elseif ($p['status']=='Cancelled') { $c=true; } elseif ($p['status']=='Pending') { echo "badge-info"; } else { echo "badge-important"; } ?> camperhoverpopover" data-toggle="popover" title="Payment Details" data-placement="top" data-content="<strong>Payer:</strong> <?php echo $p['user']['first_name'].' '.$p['user']['last_name']; ?><br /><strong>Type:</strong> <?php echo ucwords($p['type']); ?><br /><strong>Status:</strong> <?php echo ucwords($p['comment']); ?><br /><strong>Time:</strong> <?php echo date('F j, Y g:i:sa', $p['date']); ?><br /><?php if ($p['type'] == 'check') { ?><strong>Check Number:</strong> <?php echo (isset($p['details']['number'])) ? $p['details']['number']: 'None'; ?><br /><strong>Check Amount:</strong> $<?php echo $p['details']['amount']; ?><br /><strong>Name on Check:</strong> <?php echo (isset($p['details']['name'])) ? $p['details']['name']: 'None'; ?><br /><?php } ?><strong>Notes:</strong> <?php echo $p['notes']; ?><br />"><?php if($c) echo '<strong>'; echo ($p['type']=='transfer' || $p['type']=='credit') ? ucfirst($p['type']): $p['status']; if($c) echo '</strong>'; ?></span></td>
					<td<?php if($c) echo ' class="tan"'; ?>><?php echo anchor('payments/'.$p['token'], ($p['type'] == 'check' && isset($p['details']['number'])) ? ucwords($p['type']).' (#'.$p['details']['number'].')': ucwords($p['type'])); ?></td>
					<td<?php if($c) echo ' class="tan"'; ?>><span data-toggle="tooltip" class="moment-format" title="<?php echo date('F j, Y g:i:sa', $p['date']); ?>" ><?php echo $p['date']; ?></span></td>
					<td<?php if($c) echo ' class="tan"'; ?>>$<?php echo $p['amount']; ?></td>
					<td<?php if($c) echo ' class="tan"'; ?>><?php echo ($p['individual'] == 0) ? anchor("units/".$p['unit']['id'], $this->shared->get_unit_name(false,$p['unit'])) : anchor("users/".$p['individual']['id'], $p['individual']['first_name'].' '.$p['individual']['last_name']);?></td>
					<td<?php if($c) echo ' class="tan"'; ?>><?php echo anchor("event/".$p['event']['id']."/registrations/".$p['reg']['id'], $p['event']['title'].'<br>'.((empty($p['session']['title'])) ? $p['event']['sessiontitle'].' '.$p['session']['sessionnum'] : $p['session']['title']));?></td>
					<td><span class="right"><?php if($p['status']=='Pending') echo anchor('api/v1/pay/approve?token='.$p['token'], ' Approve', 'class="btn btn-small teal icon icon-ok"'); ?> <?php // if($p['type']=='paypal' && $p['status']=='Completed') echo anchor('api/v1/pay/refund?token='.$p['token'], ' ', 'class="btn btn-small tan icon icon-share-alt"'); ?> <?php //if($p['type']!=='paypal') echo anchor('api/v1/pay/updateX?token='.$p['token'], ' ', 'class="btn btn-small tan icon icon-pencil"'); ?> <?php echo anchor('payments/'.$p['token'], ' ', 'class="btn btn-small tan icon icon-pencil"'); ?> <?php echo anchor('api/v1/pay/delete?token='.$p['token'], ' ', 'class="btn btn-small red icon icon-remove"'); ?></span></td>
				</tr>
			<?php endforeach;?>
		</table>
	</article>
