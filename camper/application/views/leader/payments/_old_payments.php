<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Leaders Payments Listing
 *
 * This is the ...
 *
 * File: /application/views/notifications/all.php
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
				var u = moment.unix(t).format('M/D/YYYY');
				$(this).text(u);
			});
			oTable = $('.datatables').dataTable( {
				"sDom": "<'container'r>t<'container'<'left'i><'left'p><'right'l>>",
				"aaSorting": [[ 2, "desc" ]],
				"oLanguage": {
					"sInfo": "_START_ through _END_ of _TOTAL_ payments"
				}
			});
		});
	</script>
	<article class="textsection">
   	    <h2 class="">Payments</h2>
   	    <p>These are all of the payments your unit has made. You can see which leader made the payment and the status. All payments, including cancelled and refunded, are listed here.</p>
   	    <p>Want to make a payment? You can pay for event registrations in the "register" section for the event you want to pay for. Payments are organized by event registration. Cash, check, and money order payments can be put in but require approval by the Council for the balance to be updated here. Contact the council service center if you have any questions.</p>
		<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
		<div class="clear"></div>
   		<div class="camperform float search" style="width: 40%"><i class="icon-search"></i><input class="ico" type="text" data-toggle="tooltip" onkeyup="oTable.fnFilter($(this).val());" placeholder="Search..."  title="Search for a payment using any combination of terms, the table below will update live." /><label>Payment Search</label></div>
		<div class="clear"></div>
      	<table class="table table-condensed datatables">
      		<thead>
      	   	<tr><th>Status</th><th>Type</th><th>Date</th><th>Amount</th><th>Payer</th><th>Event</th><th>Tools</th></tr>
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
		  	<?php /*
		  	$unit = $this->shared->is_individual(false,true);
		  	$unitid = (isset($unit['id'])) ? $unit['id']: false;
		  	$unitname = (isset($unit['id'])) ? $this->shared->get_unit_name($unit['id']): false;
		  	
		  	if ($payments): foreach ($payments as $p):
		  	$c = false;
		  	?>	<tr>
					<td><span class="badge <?php if($p['type']=='transfer' || $p['type']=='credit') { echo "badge-inverse"; } elseif($p['status']=='Completed') { echo "badge-success"; } elseif ($p['status']=='Cancelled') { $c=true; } elseif ($p['status']=='Pending') { echo "badge-info"; } else { echo "badge-important"; } ?> camperhoverpopover" data-toggle="popover" title="Payment Details" data-placement="top" data-content="<strong>Payer:</strong> <?php echo $this->shared->get_user_name($p['user'],true); ?><br /><strong>Type:</strong> <?php echo ucwords($p['type']); ?><br /><strong>Status:</strong> <?php echo ucwords($p['comment']); ?><br /><strong>Time:</strong> <?php echo date('F j, Y g:i:sa', $p['date']); ?><br /><?php if ($p['type'] == 'check') { $p['__details'] = unserialize($p['details']); ?><strong>Check Number:</strong> <?php echo $p['__details']['number']; ?><br /><strong>Check Amount:</strong> $<?php echo $p['__details']['amount']; ?><br /><strong>Name on Check:</strong> <?php echo $p['__details']['name']; ?><br /><?php } ?><strong>Notes:</strong> <?php echo $p['notes']; ?><br />"><?php if($c) echo '<strong>'; echo ($p['type']=='transfer' || $p['type']=='credit') ? ucfirst($p['type']): $p['status']; if($c) echo '</strong>'; ?></span></td>
		  			<td<?php if($c) echo ' class="fiftypercent strikethrough"'; ?>><?php echo ucwords($p['type']); if ($p['type'] == 'check') echo ' (#'.$p['__details']['number'].')'; ?></td>
		  			<td<?php if($c) echo ' class="fiftypercent strikethrough"'; ?>><span data-toggle="tooltip" class="moment-format" title="<?php echo date('F j, Y g:i:sa', $p['date']); ?>" ><?php echo $p['date']; ?></span></td>
		  			<td<?php if($c) echo ' class="fiftypercent strikethrough"'; ?>>$<?php echo $p['amount']; ?></td>
		  			<td<?php if($c) echo ' class="fiftypercent strikethrough"'; ?>><?php echo ($p['individual'] == '0') ? ($p['unit'] == $unitid) ? $unitname: $this->shared->get_unit_name($p['unit']): 'Individual'; ?></td>
		  			<td<?php if($c) echo ' class="fiftypercent strikethrough"'; ?>><?php echo $regtitles['event']; ?></td>
		  			<td<?php if($c) echo ' class="fiftypercent strikethrough"'; ?>><?php echo $regtitles['session']; ?></td>
		  			<td><?php if ($p['type'] == 'check') echo anchor('payments/checkform/'.$p['token'], 'Check form', 'class="btn btn-small tan" data-toggle="tooltip" title="Click to view the check payment detail form. You need to print and send this in with your check payment."'); ?></td>
		 		</tr>
		 	<?php endforeach; endif; */ ?>
      	</table>
   		
	</article>
