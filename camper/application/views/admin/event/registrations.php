<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin Event / Registrations View
 *
 * This is the ...
 *
 * File: /application/views/admin/event/details.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 /* Available vars
 $event
 $sessions
 */
?>
	<script>
		$(document).ready(function() {
			// Regs Search
				$('.regslist.typeahead').typeahead({							  
				  limit: '10',	
				  header: '<p class="typeaheadtitle"><i class="icon-search"></i> Registration Search - Click on the registration to view details.</p>',
				  prefetch: '<?php echo base_url(); ?>api/v1/regs.json?event=<?php echo $event['id']; ?>', 
				  template: [
					'<p class="typeahead-num">{{session}}</p>',
					'<p class="typeahead-name">{{unit}}, {{city}}</p>',
					'<p class="typeahead-num">reg #{{id}}</p>',
					'<p class="typeahead-city">{{council}}</p>',	
				  ].join(''),																 
				  engine: Hogan																
				});
				$('.regslist.typeahead').on('typeahead:autocompleted', function(evt, item) {
					window.location.href = '<?php echo base_url(); ?>event/<?php echo $event['id']; ?>/registrations/' + item['id'];
				})
				$('.regslist.typeahead').on('typeahead:selected', function(evt, item) {
					window.location.href = '<?php echo base_url(); ?>event/<?php echo $event['id']; ?>/registrations/' + item['id'];
				})
			// Units list
				$('.unitslist.typeahead').typeahead({                              
				  limit: '10',                                                        
				  header: '<p class="typeaheadtitle"><i class="icon-search"></i> Unit Search - Click on the unit to register them for this event. Hit escape to cancel.</p>',
				  prefetch: '<?php echo base_url(); ?>api/v1/units.json?v=124', 
				  template: [                                                                 
				    '<p class="typeahead-num">{{city}}, {{state}}</p>',                              
				    '<p class="typeahead-name">{{name}}</p>',                                      
				    '<p class="typeahead-city">{{council}}</p>'                         
				  ].join(''),                                                                 
				  engine: Hogan                                                               
				});
				$('.unitslist.typeahead').on('typeahead:selected', function(evt, item) {
					session = $('#registersession').val();
					window.location.href = '<?php echo base_url(); ?>api/v1/register/unit/' + item['unitid'] + '/' + session + '?return=<?php echo uri_string(); ?>';
				})
				$('.unitslist.typeahead').on('typeahead:autocompleted', function(evt, item) {
					session = $('#registersession').val();
					window.location.href = '<?php echo base_url(); ?>api/v1/register/unit/' + item['unitid'] + '/' + session + '?return=<?php echo uri_string(); ?>';
				})
			// Users list
				$('.userslist.typeahead').typeahead({                              
				  limit: '10',                                                        
				  header: '<p class="typeaheadtitle"><i class="icon-search"></i> User Search - Click on the individual to register them for this event. Hit escape to cancel.</p>',
				  prefetch: '<?php echo base_url(); ?>api/v1/users.json?128', 
				  template: [                                                                 
				    '<p class="typeahead-num">{{unit}}</p>',                              
				    '<p class="typeahead-name">{{name}}</p>',                                      
				    '<p class="typeahead-city">{{email}} / {{phone}}</p>'                         
				  ].join(''),                                                                 
				  engine: Hogan                                                               
				});
				$('.userslist.typeahead').on('typeahead:autocompleted', function(evt, item) {
					session = $('#registersession').val();
					window.location.href = '<?php echo base_url(); ?>api/v1/register/individual/' + item['userid'] + '/' + session + '?return=<?php echo uri_string(); ?>';
				})
				$('.userslist.typeahead').on('typeahead:selected', function(evt, item) {
					session = $('#registersession').val();
					window.location.href = '<?php echo base_url(); ?>api/v1/register/individual/' + item['userid'] + '/' + session + '?return=<?php echo uri_string(); ?>';
				})
			$.extend( $.fn.dataTableExt.oStdClasses, {
				"sSortAsc": "header headerSortDown",
				"sSortDesc": "header headerSortUp",
				"sSortable": "header"
			});
			oTable = $('.datatables').dataTable( {
				"sDom": "<'container'r><'container'<'left'i><'left'p><'right'l>>t<'container'<'left'i><'left'p><'right'l>>",
				"iDisplayLength": 100,
				"aLengthMenu": [[25, 50, 100, 200, -1], [25, 50, 100, 200, "All"]],
				"oLanguage": {
					"sInfo": "_START_ through _END_ of _TOTAL_ registrations"
				}
			});
		});
	</script>
	<div class="subnav">
		<div class="container">
			<h2>Events</h2>
			<nav class="campersubnav">
				<li class="" data-toggle="tooltip" title="New Activity"><?php echo anchor("event/activities/new", '<i class="icon-plus"></i>');?></li>
				<li class=""><?php echo anchor("event/activities", 'Activity Library');?></li>
				<li class="" data-toggle="tooltip" title="New Event"><?php echo anchor("event/new", '<i class="icon-plus"></i>');?></li>
				<li class=""><?php echo anchor("event/past", 'Past Events');?></li>
				<li class="active"><?php echo anchor("event", 'Upcoming Events');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
		<h2 class="">Upcoming Events / <?php echo $event['title']; ?> </h2>
		<p>This is where you can modify upcoming event details, view registrations, and manage event activities. You can view a list of all upcoming events and change events by clicking on the event title above.</p>
		<div class="clear"></div>
		<ul id="detailstabs" class="teal">
			<li class="active"><?php echo anchor("event/".$event['id']."/registrations", 'Registrations');?></li>
			<li class=""><?php echo anchor("event/".$event['id']."/details", 'Details &amp; Dates');?></li>
			<li class=""><?php echo anchor("event/".$event['id']."/sessions", 'Sessions');?></li>
			<li class=""><?php echo anchor("event/".$event['id']."/options", 'Starters');?></li>
			<li class=""><?php echo anchor("event/".$event['id']."/custom", 'Options &amp; Discounts');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/classes", 'Classes');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/message", 'Message');?></li>
		</ul>
	</article>
	<article class="textsection">
		<h2 class="">Registrations</h2>
		<p>These are all of the online registrations for the <strong><?php echo $event['title']; ?></strong> event.</p>
		<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
		<div class="clear"></div>
		<div class="half">
			<h3>Quick Stats</h3>
			<?php // Stats Prep
				// Prepare count, total spots and number of regs
				$stat['count']=0; $stat['spots']=0; $stat['regs']=0; $stat['sessions']=count($sessions); 
				foreach ($sessions as $session) { 
					$sessioncount = $this->shared->update_count_session($session['id']);
					$stat['count'] = $stat['count']+$sessioncount['total']; 
					$stat['spots'] = $stat['spots']+$session['limithard']; 
					//$stat['regs'] = $stat['regs']+$this->event_model->count_regs($session['id']);
				}
				$stat['regs'] = $this->event_model->count_regs($event['id'],"event");
			?>
			<div class="camperform float" style=""  data-toggle="tooltip" title="Your login"><span><?php echo $stat['sessions']; ?></span><label><?php echo $event['sessiontitle']; ?>s</label></div>
			<div class="camperform float" style=""  data-toggle="tooltip" title="Your login"><span><?php echo $stat['count']; ?>/<?php echo $stat['spots']; ?></span><label>Participants</label></div>
			<div class="camperform float" style=""  data-toggle="tooltip" title="Your login"><span>-</span><label>Preorders</label></div>
			<div class="camperform float" style=""  data-toggle="tooltip" title="Your login"><span><?php echo $stat['regs']; ?></span><label>Units</label></div>
			<div class="clear"></div>
			<h3>Jump to...</h3>
			<div class="camperform float search" style="width: 80%"><i class="icon-search"></i><input class="ico regslist typeahead" type="text" data-toggle="tooltip" placeholder="Troop 1 BDSR Summer Camp..."  title="Search for an event by..." /><label>Quickly find a registration...</label></div>
			<div class="clear"></div>
			<h3>Register...</h3>
			<p>Create a new registration for existing users or units here. Set a session then search and click on the unit/individual you want to register.</p>
   			<div class="camperform float " style="width: auto">
   				<select id="registersession" name="registersession">
					<?php $i=1; foreach ($sessions as $onesession) { ?><option value="<?php echo $onesession['id']; ?>"><?php echo (empty($onesession['title'])) ? $event['sessiontitle'].' '.$i : $onesession['title']; ?></option><?php $i++; } ?> 
				</select>
				<label for="registersession">Session</label>
			</div>
			<div class="camperform float " style="width: 120px"><input class="unitslist typeahead" type="text" data-toggle="tooltip" placeholder="Units..."  title="Select any unit to register." /><label>Units</label></div>
			<div class="camperform float last" style="width: 120px"><input class="userslist typeahead" type="text" data-toggle="tooltip" placeholder="Users..."  title="Select any user to register." /><label>Users</label></div>
			<div class="clear"></div>
		</div>
		<div class="half">
			<table class="table table-condensed">
			<?php if ($groups['show'] == '1') { ?>
				<thead><tr><th><?php echo $event['sessiontitle']; ?></th><th>Status</th><th><?php echo $groups['title']; ?>s</th></tr></thead>
				<tbody>
				<?php $i=1; foreach ($sessions as $onesession): 
				$sessioncount = $this->shared->count_session($onesession['id']); 
				?>
					<tr><!-- Special groups layout -->
						<td><strong><?php echo (empty($onesession['title'])) ? $event['sessiontitle'].' '.$i : $this->shared->excerpt($onesession['title']); ?></strong><br /><?php echo $sessioncount['total']; ?> / <?php echo $onesession['limithard']; ?></td>
						<td class="nowrap"><?php echo ($onesession['open'] == '0') ? '<i class="icon-remove red"></i> Closed' : '<i class="icon-ok teal"></i> Open'; ?></td>
						<td><?php foreach ($groups['groups'] as $g) { 
							$g['__count'] = $this->shared->count_group($g['id'],$onesession['id'],false); 
							echo $this->shared->excerpt($g['title']); ?> <span class="right"><span data-toggle="tooltip" title="Registered Participants"><?php echo $g['__count']['total']; ?></span> / <strong data-toggle="tooltip" title="Total Spots"><?php echo (!isset($g['limit']) || $g['limit'] == '') ? $g['__pass'] = true: $g['limit']; ?></strong> <span data-toggle="tooltip" title="Percent Full">(<?php echo number_format(($g['__count']['total']/$g['limit'])*100,0); ?>%)</span> <strong data-toggle="tooltip" title="Number of Units"><?php echo $g['__count']['regs']; ?></strong></span><div class="clear"></div><?php } ?></td>
					</tr>
				<?php $i++; endforeach; ?>
				</tbody>				
				<?php } else { ?>
				<thead><tr><th><?php echo $event['sessiontitle']; ?></th><th>Status</th><th>Numbers</th><th>Percent</th><th>Regs</th></tr></thead>
				<tbody>
				<?php $i=1; foreach ($sessions as $onesession): 
				$sessioncount = $this->shared->count_session($onesession['id']); 
				?>
					<tr><!-- Normal enevt numbers layout -->
						<td><?php echo (empty($onesession['title'])) ? $event['sessiontitle'].' '.$i : $this->shared->excerpt($onesession['title']); ?></td>
						<td><?php echo ($onesession['open'] == '0') ? '<i class="icon-remove red"></i> Closed' : '<i class="icon-ok teal"></i> Open'; ?></td>
						<td><?php echo $sessioncount['total']; ?> / <strong><?php echo $onesession['limithard']; ?></strong></td>
						<td><?php echo ($onesession['limithard'] == 0) ? '-' : number_format(($sessioncount['total']/$onesession['limithard'])*100,0).'%'; ?></td>
						<td><?php echo $this->event_model->count_regs($onesession['id']); ?></td>
					</tr>
				</tbody>
				<?php $i++; endforeach; } ?>
			</table>
		</div>
		<div class="clear hr"></div>
			<div class="right">
				<p>
					<?php echo anchor('api/v1/views/event/'.$event['id'].'.html', '<i class="icon-code"></i> Embed', 'class="btn tan" data-toggle="tooltip" title="View the registration table for embedding in another website, copy the URL of the next page"') ;?> 
					<?php echo anchor("api/v1/regs.json?format=csv&event=".$event['id'], '<i class="icon-download"></i> Regs', 'class="btn tan" data-toggle="tooltip" title="Download a CSV list of registrations for this event for Excel"') ;?> 
					<?php echo anchor("api/v1/regs_finances.csv?event=".$event['id'], '<i class="icon-download"></i> Regs &amp; Finances', 'class="btn tan" data-toggle="tooltip" title="Download a CSV list of registrations with financial details for this event for Excel"') ;?> 
					<?php echo anchor("api/v1/preorders.csv?event=".$event['id'], '<i class="icon-download"></i> Preorder Finances', 'class="btn tan" data-toggle="tooltip" title="Download a CSV list of classes and preorder amounts for this event for Excel"') ;?> 
				</p>
			</div>
			<h3>Registrations</h3>	
			<div class="clear"></div>
	   		<div class="camperform float search" style="width: 40%"><i class="icon-search"></i><input class="ico" type="text" data-toggle="tooltip" onkeyup="oTable.fnFilter($(this).val());" placeholder="Unit, Council, Session..."  title="Filter the registrations below using any combination of terms, the table below will update live." /><label>Search Existing Registrations Below</label></div>
			<div class="clear"></div>


			<table class="table table-condensed datatables">
				<thead><tr><th><i class="icon-ok tan"></i></th><th>Unit</th><th>Council (#)</th><th>Session</th><th>Total</th><th>Y</th><th>M</th><th>F</th><?php echo ($groups['enabled'] == 1) ? '<th>'.$groups['title'].'</th>' : ''; ?><th>Paid/<strong>Total</strong></th><th>Amount Due</th><?php echo ($event['activitypreorders'] == 1) ? '<th>Preorders</th>' : ''; ?></tr></thead>
				<tbody><?php // Prep and loop regs
				$preordertotal = 0;
				$i=1; foreach ($sessions as $onesession):
					$regs = $this->event_model->get_session_regs($onesession['id']); 
					if (!is_null($regs)) : foreach ($regs as $reg) : 
					if ($reg['individual'] == '1') {
						// individual reg
						$individual = true;
						$user = $this->ion_auth->user($reg['userid'])->row();
						$unit = ($user->individual == '1') ? unserialize($user->individualdata): false;
						$unittitle = ($unit && $unit['unittype'] == 'None') ? $user->first_name.' '.$user->last_name.' (No Unit)' : $user->first_name.' '.$user->last_name.' ('.$unit['unittype'].' '.$unit['number'].')';
					} else {
						// Normal
						$unit = $this->shared->get_single_unit($reg['unitid']);  
						$individual = false;
					 	$unittitle = (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')': $unit['unittype'].' '.$unit['number'];
					}
					
					 ?> 
					<tr<?php if ($reg['active'] == '0') echo ' class="fiftypercent strikethrough"'; ?> data-regid="<?php echo $reg['id']; ?>">
						<td><?php unset($verify);
							$recount_max = $this->shared->recount_max($reg);
							$verify = $this->shared->verify($reg['id']);
							if ($verify['restricted']===true) {
								$verifyico = '<i class="icon-remove red" data-toggle="tooltip" title="'.$unittitle.' is not registered, click manage to see details"></i>';
							} elseif ($verify['result']===false) {
								$verifyico = '<i class="icon-exclamation-sign red" data-toggle="tooltip" title="'.$unittitle.' is registered but there are issues, click manage to see details"></i>';
							} else {
								$verifyico = '<i class="icon-ok teal" data-toggle="tooltip" title="'.$unittitle.' is registered"></i>';
							}

							echo $verifyico; ?></td>
						<td><?php echo anchor("event/".$event['id']."/registrations/".$reg['id'], $unittitle);?></td>
						<td><?php echo $unit['council']; ?></td>
						<td><?php echo (empty($onesession['title'])) ? $event['sessiontitle'].' '.$i : $this->shared->excerpt($onesession['title']); ?></td>
						<td class="strong"><?php echo $reg['youth']+$reg['male']+$reg['female']; ?></td>
						<td><?php echo $reg['youth']; ?></td><td><?php echo $reg['male']; ?></td>
						<td><?php echo $reg['female']; ?></td>
						<?php echo ($groups['enabled'] == 1) ? (isset($reg['group'])) ? '<td>'.$this->shared->excerpt($groups['groups'][$reg['group']]['title']).'</td>' : '<td>-</td>': ''; ?>
						<td>$<?php echo (float)$verify['source']['fin']['fin']['totalpaid']; ?>/$<strong><?php echo (float)$verify['source']['fin']['fin']['total']; ?></strong></td>
			   			<?php if (($verify['source']['fin']['fin']['total']-$verify['source']['fin']['fin']['totalpaid']) > 0) { ?>
			   				<td>$<?php echo (float)($verify['source']['fin']['fin']['total']-$verify['source']['fin']['fin']['totalpaid']); ?> is due</td>
			   			<?php } elseif (($verify['source']['fin']['fin']['total']-$verify['source']['fin']['fin']['totalpaid']) < 0) { ?>
			   				<td class="red">($<?php echo (float)($verify['source']['fin']['fin']['total']-$verify['source']['fin']['fin']['totalpaid']); ?>)</td>
			   			<?php } else { ?>
			   				<td><?php if ((float)$verify['source']['fin']['fin']['total'] == 0) { ?>-<?php } else { ?><i class="icon-ok teal"></i> Paid in full<?php } ?></td>
			   			<?php } ?>
						<?php if ($reg['activitypreorders'] == 1) $preordertotal += $verify['source']['fin']['fin']['preorders']; ?>
						<?php echo ($event['activitypreorders'] == 1) ? ($reg['activitypreorders'] == 1) ? '<td>$'.$verify['source']['fin']['fin']['preorders'].'</td>' : '<td>-</td>' : ''; ?>
					</tr>
					<?php endforeach; endif; ?>
				<?php $i++; endforeach;?>			
				</tbody>
			</table>
			<p>Preorders total amount: $<?php echo $preordertotal; ?></p>


			
			<?php /* $i=1; foreach ($sessions as $onesession): ?>
			<table class="table table-condensed">
				<thead><tr><th><i class="icon-ok"></i></th><th><?php echo (empty($onesession['title'])) ? $event['sessiontitle'].' '.$i : $onesession['title']; ?></th><th>Council (#)</th><th>Total</th><th colspan="3">Y/M/F</th><?php echo ($groups['enabled'] == 1) ? '<th>'.$groups['title'].'</th>' : ''; ?></tr></thead>
				<tbody><?php // Prep and loop regs
					$regs = $this->event_model->get_session_regs($onesession['id']); 
					if (is_null($regs)) : ?><td colspan="11"><i class="icon-remove red"></i> No registrations for <?php echo (empty($onesession['title'])) ? $event['sessiontitle'].' '.$i : $onesession['title']; ?></td>
					<?php else : foreach ($regs as $reg) : ?>
					<?php $unit = $this->shared->get_single_unit($reg['unitid']); ?> 
					<tr><td><?php unset($verify);
					$verify = $this->shared->verify($reg['id']);
					if ($verify['restricted']===true) {
						$verifyico = '<i class="icon-remove red" data-toggle="tooltip" title="'.$unit['unittype'].' '.$unit['number'].' is not registered, click manage to see details"></i>';
					} elseif ($verify['result']===false) {
						$verifyico = '<i class="icon-exclamation-sign red" data-toggle="tooltip" title="'.$unit['unittype'].' '.$unit['number'].' is registered but there are issues, click manage to see details"></i>';
					} else {
						$verifyico = '<i class="icon-ok teal" data-toggle="tooltip" title="'.$unit['unittype'].' '.$unit['number'].' is registered"></i>';
					}
					echo $verifyico; ?></td><td><?php echo anchor("event/".$event['id']."/registrations/".$reg['id']."/edit", $unit['unittype'].' '.$unit['number']);?></td><td><?php echo $unit['council']; ?></td><td><strong><?php echo $reg['youth']+$reg['male']+$reg['female']; ?></strong></td><td><?php echo $reg['youth']; ?></td><td><?php echo $reg['male']; ?></td><td><?php echo $reg['female']; ?></td><?php echo (isset($reg['group'])) ? '<td>'.$groups['groups'][$reg['group']]['title'].'</td>' : '<td>-</td>'; ?><!--<td><i class="icon-remove red"></i></td>--></tr>
				<?php endforeach; endif; ?>
				</tbody>
			</table>
			<?php $i++; endforeach; */ ?>	
	</article>

