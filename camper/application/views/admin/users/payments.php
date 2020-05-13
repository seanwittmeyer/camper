<?php 

/* 
 * Camper Admin / Users / Unit / Payments View
 *
 * This is. 
 *
 * File: /application/views/admin/users/payments.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

 $unittitle = (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')': $unit['unittype'].' '.$unit['number'];

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
				"oLanguage": {
					"sInfo": "_START_ through _END_ of _TOTAL_ payments"
				}
			});
		});
	</script>
	<div class="subnav">
		<div class="container">
			<h2>Units &amp; Users</h2>
			<nav class="campersubnav">
   	    		<li><?php echo anchor("users/pending", 'Pending Invites');?></li>
   	    		<li><?php echo anchor("users", 'Users');?></li>
   	    		<li class="active"><?php echo anchor("units", 'Units');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
		<div class="container">
			<h2>Units / <?php echo $unit['unittype'];?> <?php echo $unit['number'];?></h2>
   			<p>Manage <?php echo $unittitle;?> here, from the unit details and contacts to the payments and registrations. You can quickly see the history of <?php echo $unittitle;?> in Camper. More coming here soon.</p>
			<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
		</div>
   		<div class="container">
   			<ul id="detailstabs" class="teal">
   				<li class=""><?php echo anchor("units/".$unit['id'], 'Unit Details');?></li>
   				<li class="active"><?php echo anchor("units/".$unit['id']."/payments", 'Payments');?></li>
   				<li class=""><?php echo anchor("units/".$unit['id']."/registrations", 'Registrations');?></li>
   				<li class=""><?php echo anchor("units/".$unit['id']."/members", 'Members');?></li>
   			</ul>
   		</div>
   	    <h2 class="">Payments</h2>
   	    <p>These are all of the payments your unit has made. You can see which leader made the payment and the status. All payments, including cancelled and refunded, are listed here.</p>
   	    <p><?php echo anchor("payments/new", '<i class="icon-plus"></i> New Payment', 'class="btn teal"');?></p>
		<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
		<div class="clear"></div>
   		<div class="camperform float search" style="width: 40%"><i class="icon-search"></i><input class="ico" type="text" data-toggle="tooltip" onkeyup="oTable.fnFilter($(this).val());" placeholder="Search..."  title="Search for a payment using any combination of terms, the table below will update live." /><label>Payment Search</label></div>
		<div class="clear"></div>
      	<table class="table table-condensed datatables">
      		<thead>
      	   	<tr><th><i class="icon-ok"></i></th><th><i class="icon-info-sign"></i><th>Type</th><th>Date</th><th>Amount</th><th>Contact</th><th>Event</th><th>Session</th><th>Status</th><th class="right">Tools</th></tr>
      		</thead>
      		<tbody>
		  	<?php 
		  	foreach ($payments as $p):
		  	$regtitles = $this->shared->get_reg_set_titles($p['reg']);
		  	$c = false;
		  	?>	<tr>
		  			<td>
		  				<?php if($p['status']=='Completed') { ?><i class="icon-ok teal"></i>
		  				<?php } elseif($p['status']=='Cancelled') { $c=true; ?><i class="icon-remove red"></i>
		  				<?php } elseif($p['status']=='Pending') { ?><i class="icon-ellipsis-horizontal tan"></i>
		  				<?php } else { ?><i class="icon-asterisk red"></i><?php } ?>
		  			</td>
		  			<td><i class="icon-info-sign camperhoverpopover" data-toggle="popover" title="Payment Details" data-placement="top" data-content="<strong>Type:</strong> <?php echo ucwords($p['type']); ?><br /><strong>Status:</strong> <?php echo ucwords($p['comment']); ?><br /><strong>Time:</strong> <?php echo date('F j, Y g:i:sa', $p['date']); ?><br /><?php if ($p['type'] == 'check') { $p['__details'] = unserialize($p['details']); ?><strong>Check Number:</strong> <?php echo $p['__details']['number']; ?><br /><strong>Check Amount:</strong> $<?php echo $p['__details']['amount']; ?><br /><strong>Name on Check:</strong> <?php echo $p['__details']['name']; ?><br /><?php } ?>"></i></td>
		  			<td<?php if($c) echo ' class="tan"'; ?>><?php echo ucwords($p['type']); if ($p['type'] == 'check') echo ' (#'.$p['__details']['number'].')'; ?></td>
		  			<td<?php if($c) echo ' class="tan"'; ?>><span data-toggle="tooltip" class="moment-format" title="<?php echo date('F j, Y g:i:sa', $p['date']); ?>" ><?php echo $p['date']; ?></span></td>
		  			<td<?php if($c) echo ' class="tan"'; ?>>$<?php echo $p['amount']; ?></i></td>
		  			<td<?php if($c) echo ' class="tan"'; ?>><?php echo $this->shared->get_user_name($p['user'],true); ?></i></td>
		  			<td<?php if($c) echo ' class="tan"'; ?>><?php echo $regtitles['event']; ?></i></td>
		  			<td<?php if($c) echo ' class="tan"'; ?>><?php echo $regtitles['session']; ?></i></td>
		  			<td<?php if($c) echo ' class="tan"'; ?>><?php if($c) echo '<strong>'; echo $p['status']; if($c) echo '</strong>'; ?></i></td>
		  			<td><span class="right"><?php if($p['status']=='Pending') echo anchor('api/v1/pay/approve?token='.$p['token'], ' Approve', 'class="btn btn-small teal icon icon-ok"'); ?> <?php // if($p['type']=='paypal' && $p['status']=='Completed') echo anchor('api/v1/pay/refund?token='.$p['token'], ' ', 'class="btn btn-small tan icon icon-share-alt"'); ?> <?php //if($p['type']!=='paypal') echo anchor('api/v1/pay/updateX?token='.$p['token'], ' ', 'class="btn btn-small tan icon icon-pencil"'); ?> <?php echo anchor('payments/'.$p['token'], ' ', 'class="btn btn-small tan icon icon-pencil"'); ?> <?php echo anchor('api/v1/pay/delete?token='.$p['token'], ' ', 'class="btn btn-small red icon icon-remove"'); ?></span></td>
		 		</tr>
		 	<?php endforeach;?>
      	</table>
   		
	</article>
