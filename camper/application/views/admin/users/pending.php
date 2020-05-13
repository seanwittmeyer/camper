<?php 

/* 

 *
 * This is a test.
 *
 * File: /application/views/admin/dashboard/admin.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

?>
		<script>
		// Datatables
		$.extend( $.fn.dataTableExt.oStdClasses, {
			"sSortAsc": "header headerSortDown",
			"sSortDesc": "header headerSortUp",
			"sSortable": "header"
		});
		$(document).ready(function() {
			oTable = $('.datatables').dataTable( {
				"sDom": "<r>t<''<'left'i><'left'p><'right'l>>",
				"oLanguage": {
					"sInfo": "_START_ through _END_ of _TOTAL_ invites"
				}
			});
		});
		// Invites Functions
		function resend_invite(token,element) {
			$.ajax({
	    		url: "<?php echo $this->config->item('camper_path'); ?>api/v1/invites/resend?t=" + token,
	    		type: 'GET',
	    		beforeSend: function() {
	    			$(element).text('Sending...');
	    		},
	    		statusCode: {
	    			200: function() {
	    				//alert( "success" );
	    			$(element).html('Invite sent <i class="icon-ok"></i>');
	    			$(element).removeClass('teal').addClass('tan');
	    			},
	    			304: function() {
	    				//alert( "nothing to mark as read" );
	    			$(element).text('Resend failed, retry?');
	    			}
	    		}
			});
		}
		function remove_invite(token,element) {
			$.ajax({
	    		url: "<?php echo $this->config->item('camper_path'); ?>api/v1/invites/delete?t=" + token,
	    		type: 'GET',
	    		beforeSend: function() {
	    			$(element).text('Removing...');
	    		},
	    		statusCode: {
	    			200: function() {
	    				//alert( "success" );
	    			$(element).html('Removed <i class="icon-ok"></i>');
	    			$(element).removeClass('red').addClass('tan');
	    			},
	    			304: function() {
	    				//alert( "nothing to mark as read" );
	    			$(element).text('Remove failed, retry?');
	    			}
	    		}
			});
		}
		</script>
	<div class="subnav">
		<div class="container">
			<h2>Units &amp; Users</h2>
			<nav class="campersubnav">
   	    		<li class="active"><?php echo anchor("users/pending", 'Pending Invites');?></li>
   	    		<li><?php echo anchor("users", 'Users');?></li>
   	    		<li><?php echo anchor("units", 'Units');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
    		<div class="container">
				<h2>Pending Invites</h2>
	   				<p>When someone is invited to be a contact for any unit in Camper, they will appear here until they finish creating an account on Camper. If there are multiple invites for a unit, the first one to signup will take the alternate contact spot. Contacts can be invited by administrators or the primary contact of the unit. You can resend the invite email or delete the invite. Removing an invite will not prevent a user from signing up, it will simply remove their pre-set tie to an unit.</p> 
					<div class="clear"></div>
			   		<div class="camperform float search" style="width: 40%"><i class="icon-search"></i><input class="ico" type="text" data-toggle="tooltip" onkeyup="oTable.fnFilter($(this).val());" placeholder="Search for invite..."  title="Filter the invites below using any combination of terms, the table below will update live." /><label>Invite Search</label></div>
					<div class="clear"></div>

				<?php if ($invites) { ?>
   				  	<table class="table table-condensed datatables">
   				  		<thead>
   				  	   	<tr><th>Email</th><th>Unit</th><th>Invited by</th><th>Actions</th></tr>
   				  		</thead>
   				  		<tbody>
	   				  	<?php foreach ($invites as $invite) { ?>
	   				  		<tr><td><strong><?php echo $invite['email']; ?></strong></td><td><?php echo anchor('units/'.$units[$invite['unit']]['id'], $units[$invite['unit']]['unittype'].' '.$units[$invite['unit']]['number']); ?></td><td><?php echo $invite['source']; ?></td><td><a class="btn btn-small red" href="#" onclick="remove_invite('<?php echo $invite['token']; ?>',this); return false;">Remove invite</a> <a class="btn btn-small teal" href="#" onclick="resend_invite('<?php echo $invite['token']; ?>',this); return false;">Resend</a></td></tr>
		   				<?php } ?>
		   				</tbody>
		   			</table>
	   			<?php } else { ?><p>There are no pending invites for this unit.</p><?php } ?>
			</div>
	</article>
