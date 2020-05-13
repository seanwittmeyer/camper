<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Notifications All View
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
	<script>
	// Notifications Functions
	function mark_notification(token,element) {
		$.ajax({
    		url: "/camper/api/v1/notifications/markread?n=" + token,
    		type: 'GET',
    		beforeSend: function() {
    			$(element).removeClass('icon-circle').addClass('icon-circle-blank');
    		},
    		statusCode: {
    			200: function() {
    				//alert( "success" );
    			$(element).removeClass('blue').addClass('tan');
    			},
    			304: function() {
    				//alert( "nothing to mark as read" );
    			$(element).removeClass('icon-circle-blank').addClass('icon-circle');
    			}
    		}
		});
	}
	</script>
	<article class="textsection">
   	    <h2 class="">Your Notifications</h2>
   	    <p>We use notifications to keep you updated when changes happen or when we need you to take action. We normally email you when you get a notification, manage <a href="#" data-toggle="tooltip" title="Notification Settings Coming Soon!">when we email</a> you here. You can mark new notifications as read by either clicking on the blue dot next to the notification or by hitting 'Mark all read' in the notifications menu at the top of the screen.</p>
		<div class="clear"></div>
      	<table class="table table-condensed">
      		<!--
      		<thead>
      	   	<tr><th>Event</th><th>Dates</th><th>Location</th><th>Open</th><th>Tools</th></tr>
      		</thead>
      		-->
      		<tbody>
		  	<?php 
		  	foreach ($notifications['array'] as $n):
		  	?>	<tr>
		  			<td><?php if($n['live']==1) { ?><i class="icon-circle blue link" onclick="mark_notification('<?php echo $n['token']; ?>',this); return false;"></i><?php } else { ?><i class="icon-circle-blank tan"></i><?php } ?></td>
		  			<td><?php echo $n['message']; ?> - <i><?php echo $this->shared->twitterdate($n['time']); ?></i></td>
		  			<td><?php if (isset($defaults[$n['type']]['l'])) { echo anchor($defaults[$n['type']]['l'], $defaults[$n['type']]['y']." &rarr;", 'class="btn btn-small tan right"'); } ?></td>
		 		</tr>
		 	<?php endforeach;?>
      	</table>
   		
	</article>
