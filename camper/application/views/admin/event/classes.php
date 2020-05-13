<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin Event / Activities View
 *
 * This is where classes can be set. Admin will add activities from the 
 * activity library here to the event schedule, these then will become open
 * for registration to unit leaders and their members.
 *
 * File: /application/views/admin/event/eventactivities.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

?>
	<script>
		$(document).ready(function() {
			(function($){
				$.fn.serializeObject = function(){
					var self = this,
						json = {},
						push_counters = {},
						patterns = {
							"validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
							"key": /[a-zA-Z0-9_]+|(?=\[\])/g,
							"push":	/^$/,
							"fixed": /^\d+$/,
							"named": /^[a-zA-Z0-9_]+$/
						};
					this.build = function(base, key, value){
						base[key] = value;
						return base;
					};
					this.push_counter = function(key){
						if(push_counters[key] === undefined){
							push_counters[key] = 0;
						}
						return push_counters[key]++;
					};
					$.each($(this).serializeArray(), function(){
						// skip invalid keys
						if(!patterns.validate.test(this.name)){
							return;
						}
						var k,
							keys = this.name.match(patterns.key),
							merge = this.value,
							reverse_key = this.name;
						while((k = keys.pop()) !== undefined){
							// adjust reverse_key
							reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');
							// push
							if(k.match(patterns.push)){
								merge = self.build([], self.push_counter(reverse_key), merge);
							}
							// fixed
							else if(k.match(patterns.fixed)){
								merge = self.build([], k, merge);
							}
							// named
							else if(k.match(patterns.named)){
								merge = self.build({}, k, merge);
							}
						}
						json = $.extend(true, json, merge);
					});
					return json;
				};
			})(jQuery);

			$('.activitylist.typeahead').typeahead({							  
			  limit: '10',														
			  prefetch: '<?php echo $this->config->item('camper_path'); ?>api/v1/activities.json?type=<?php echo str_replace(" ", "_", $event['eventtype']); ?>', 
			  header: '<p class="typeaheadtitle">Click on the activity you wish to add a class for.</p>',
			  template: [
				'<p class="typeahead-num">(#{{id}})</p>',
				'<p class="typeahead-name">{{title}}</p>',
				'<p class="typeahead-city">{{category}}</p>',   
			  ].join(''),																 
			  engine: Hogan															   
			});
			$('.typeahead').on('typeahead:autocompleted', function(evt, item) {
				$("#newclass").removeClass('hidden');
				$("#fnactivity").val(item['id']);
				$(".natitle").text(item['title']).val(item['title']);
				$('#fnalert').text('').addClass('hidden');
			})
			$('.typeahead').on('typeahead:selected', function(evt, item) {
				$("#newclass").removeClass('hidden');
				$("#fnactivity").val(item['id']);
				$(".natitle").text(item['title']).val(item['title']);
				$('#fnalert').text('').addClass('hidden');
			})
			// New Class
			$('#fnsubmit').click(function() {
				$.ajax({
					type: "POST",
					beforeSend: function() {
						//$('#fnmessage').text('Adding your class...');
						//$('#fnsubmit').addClass('hidden');
					},
					url: "<?php echo $this->config->item('camper_path'); ?>api/v1/classes/create",
					data: $("#fn").serialize(),
					statusCode: {
						200: function() {
							var newclass = $('#fn').serializeObject();
							if (newclass['new']['open'] == 'on') { var newicon = 'ok teal'; } else { var newicon = 'remove red'; }
							var i = 1;
							var newblocks = '';
							for (b in newclass['new']['blocks']) { if (i>1) newblocks = newblocks + ', '; newblocks = newblocks + b; i++; }
							var newrow = '<tr><td><i class="icon-' + newicon + '"></i></td><td><strong>' + newclass['new']['title'] + '</strong></td><td>' + newclass['new']['soft'] + ' / <strong>' + newclass['new']['hard'] + '</strong></td><td>' + newblocks + '</td><td>$' + newclass['new']['amount'] + '</td><td></td></tr>';
							$('#tablebottom').before(newrow);
							$('#fn').each(function(){this.reset();});
							$('.fncheckbox').each(function(){$(this).removeAttr('checked')});
							$('#fnalert').html('<button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon-ok teal"></i> ' + newclass['new']['title'] + ' has been added').removeClass('hidden');
							$('#fnmessage').text('');
							$('#newclass').addClass('hidden');
							$('#fnsubmit').removeClass('hidden').button('reset');
						},
						304: function() {
							$('#fnmessage').text('Well Snap! Couldn\'t create the class. Retry?');
							$('#fnsubmit').removeClass('hidden').button('reset');
						}
					}
				});
			});
			// Edit Class
			$('#fesubmit').click(function() {
				$.ajax({
					type: "POST",
					beforeSend: function() {
						//$('#femessage').text('Adding your class...');
						//$('#fesubmit').addClass('hidden');
					},
					url: "<?php echo $this->config->item('camper_path'); ?>api/v1/classes/edit",
					data: $("#fe").serialize(),
					statusCode: {
						200: function() {
							var editclass = $('#fe').serializeObject();
							if (editclass['edit']['open'] == 'on') { var editicon = 'ok teal'; } else { var editicon = 'remove red'; }
							if (editclass['edit']['amount'] == '0' || editclass['edit']['amount'] == '') { var editamount = ''; } else { var editamount = '$'+editclass['edit']['amount']; }
							var i = 1;
							var editblocks = '';
							for (b in editclass['edit']['blocks']) { if (i>1) editblocks = editblocks + ', '; editblocks = editblocks + b; i++; }
							var classid = $('#feclassid').val();
							$('#class'+classid+' .cicon').html('<i class="icon-' + editicon + '"></i>');
							$('#class'+classid+' .ctitle').text(editclass['edit']['title']);
							$('#class'+classid+' .climits').html(editclass['edit']['soft'] + ' / <strong>' + editclass['edit']['hard'] + '</strong>');
							$('#class'+classid+' .cblocks').text(editblocks);
							$('#class'+classid+' .camount').text(editamount);
							$('#fe').each(function(){this.reset();});
							$('.fecheckbox').each(function(){$(this).removeAttr('checked')});
							$('#fealert').html('<button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon-ok teal"></i> ' + editclass['edit']['title'] + ' has been updated').removeClass('hidden');
							$('#femessage').text('');
							$('#editclass').addClass('hidden');
							$('#fesubmit').removeClass('hidden').button('reset');
							$('#fedelete').button('reset');
						},
						304: function() {
							$('#femessage').text('Well Snap! Couldn\'t save your changes, Retry?');
							$('#fesubmit').removeClass('hidden').button('reset');
						}
					}
				});
			});
		});
		// Remeove Class
    	function deleteclass(element) {
    		var classid = $('#feclassid').val();
    		$.ajax({
        		url: "<?php echo $this->config->item('camper_path'); ?>api/v1/classes/delete?class=" + classid,
        		type: 'GET',
        		beforeSend: function() {
        			//$(element).text('Removing...');
        		},
        		statusCode: {
        			200: function() {
    					$('#fealert').html('<button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon-ok teal"></i> ' + $('#fetitle').val() + ' has been deleted').removeClass('hidden');
        				$(element).button('reset');
    					$('#fe').each(function(){this.reset();});
    					$('.fecheckbox').each(function(){$(this).removeAttr('checked')});
    					$('#femessage').text('');
    					$('#editclass').addClass('hidden');
    					$('#class'+classid).remove();
        			},
        			304: function() {
    					$(element).button('304 Remove failed, retry?');
        			},
        			404: function() {
    					$(element).button('404 Remove failed, retry?');
        			}
        		}
    		});
    	}
		// Prepare Edit Class
		function startedit(classid,label) {
    		var classobject = $.ajax({
    			beforeSend: function() {
					$("#editclass").removeClass('hidden');
    				$('#femessage').text('Loading '+ label +'...');
					$('#fe').each(function(){this.reset();});
					$('.fecheckbox').each(function(){$(this).removeAttr('checked')});
					$('#editclass').addClass('fiftypercent');
					$("#fetitle").focus();
    			},
    			url: "<?php echo $this->config->item('camper_path'); ?>api/v1/classes/"+classid+".json",
    			statusCode: {
    				404: function() {
    				$('#femessage').html('<i class="icon-info-sign red"></i> Couldn\'t load '+ label +', <a onclick="startedit('+classid+',\''+label+'\'); return false;">Retry?</a>');
					$('#editclass').removeClass('fiftypercent');
    				}
    			}
    		});
    		classobject.done(function(data) {
				var item = jQuery.parseJSON(data);
				$('#editclass').removeClass('fiftypercent');
				$('#femessage').text('Edit '+item['title']);
				$("#feclassid").val(item['id']);
				$("#feactivity").val(item['activity']);
				$(".netitle").text(item['title']).val(item['title']);
				$('#fealert').text('').addClass('hidden');
				$('#felocation').val(item['location']);
				$('#fesoft').val(item['limit']);
				$('#fehard').val(item['hardlimit']);
				$('#feamount').val(item['preorder']);
				if (item['open'] == '1') { 
					$('#feopen').attr('checked','checked');
				} else {
					$('#feopen').removeAttr('checked');
				}
				for (b in item['blocks']) { 
					$("input[name='edit[blocks]["+b+"]']").attr('checked','checked'); 
				}
    		});
    	}

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
   			<li class=""><?php echo anchor("event/".$event['id']."/registrations", 'Registrations');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/details", 'Details &amp; Dates');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/sessions", 'Sessions');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/options", 'Starters');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/custom", 'Options &amp; Discounts');?></li>
   			<li class="active"><?php echo anchor("event/".$event['id']."/classes", 'Classes');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/message", 'Message');?></li>
   		</ul>
	</article>
	<article class="textsection">
   		<div class="container">
   			<h2>Classes</h2>
   			<p>Where activities are general and can be used in many camps and events, <em>classes</em> are the specific scheduled activities leaders can sign their members up for. They are specific to this event and details including location, costs, and class sizes. You can have multiple classes for each activity (2 First Aid classes from the same First Aid activity). Activities must be in the <?php echo anchor('event/activities', 'Activity Library'); ?> before they can be added to this event.</p>
   		</div>
   		<?php if (isset($disabled) && $disabled === true) { ?>
   		<div class="container">
   			<h3>Classes are not enabled</h3>
   			<p>Head over to <?php echo anchor('event/'.$event['id'].'/sessions', 'Sessions'); ?> to set up some periods and enable classes.</p>
   		</div>
   		<?php } else { ?>
   		<div class="container">
   			<div class="half">
   				<h3>Classes</h3>
   				<p>These are all of the courses for <?php echo $event['title']; ?>. Hover over the activity title for details.</p>
				 <table class="table table-condensed">
				 	<thead>
				 	   	<tr><th><i class="icon-ok tan"></i></th><th><strong>Class</strong></th><th>Details</th><th>Tools</th></tr>
				 	</thead>
				 	<tbody>
				  	<?php $i=1; foreach ($classes as $class): $class['blocks'] = unserialize($class['blocks']); ?>
				 	<tr id="class<?php echo $class['id']; ?>">
				   		<td class="cicon"><i class="icon-<?php echo ($class['open'] == '1') ? 'ok teal': 'remove red'; ?>"></i></td>
				   		<td><strong class="ctitle"><?php echo $class['title']; ?></strong></td>

				   		<td><span class="label label-tan camperhoverpopover" data-toggle="popover" title="<?php echo $class['title']; ?>" data-placement="left" data-content="
				   		<?php echo $activities[$class['activity']]['description']; ?>
				   		<br><br>
				   		<strong>Cost/Supplies:</strong> <?php echo ($class['preorder'] == '0') ? 'None': '$'.$class['preorder']; ?>
				   		<br>
				   		<strong>Location:</strong> <?php echo $class['location']; ?>
				   		<br>
				   		<strong>Limits:</strong> <?php if ($class['limit'] == '0') { echo 'None'; } elseif ($class['limit'] !== '0' && $class['hardlimit'] == '0') { echo $class['limit']; } else { echo $class['limit']; ?> / <strong><?php echo $class['hardlimit']; ?></strong><?php } ?>
				   		<br>
				   		<strong>Blocks:</strong> <?php if (empty($class['blocks'])) { echo 'None'; } else { $j=1; foreach ($class['blocks'] as $class['__block']) { if ($j>1) echo ', '; $j++; echo $class['__block']; } } ?>
				   		"><i class="icon-info-sign"></i></span>
				   		
				   		<span class="label label-tan camperhoverpopover" data-toggle="popover" title="Class Numbers" data-placement="left" data-content="
				   		<strong> Class Numbers for <?php echo $class['title']; ?></strong>
				   		<br><br>
						<table class='table table-condensed'>
						<thead><th>Session</th><th><strong>Regs</strong></th><th>Soft</th><th>Limit</th></thead>
						<tbody>
						<?php $i=1; foreach ($sessions as $session) { ?>
						<tr><td><?php echo (empty($session['title'])) ? $event['sessiontitle'].' '.$i : $session['title']; ?></td><td><strong><?php echo $this->activities_model->count_class_regs($session['id'],$class['id']); ?></strong></td><?php if ($class['limit'] == '0' && $class['hardlimit'] == '0') { echo '<td colspan=\'2\'>No limit</td>'; } elseif ($class['limit'] !== '0' && $class['hardlimit'] == '0') { echo '<td>'.$class['limit'].'</td><td>-</td>'; } elseif ($class['limit'] == '0' && $class['hardlimit'] !== '0') { echo '<td>-</td><td>'.$class['hardlimit'].'</td>'; } else { echo '<td>'.$class['limit'].'</td><td>'.$class['hardlimit'].'</td>'; } ?></tr>
						<?php $i++; } ?>
						</tbody>
						</table>
				   		"><i class="icon-list-ul"></i></span>				   		
				   		
				   		</td>


				 		<td><?php echo anchor('event/'.$event['id'].'/classes/'.$class['id'], '<i class="icon-list-ul"> </i>', 'class="btn btn-small tan" data-toggle="tooltip" title="View Roster"'); ?> <button class="btn btn-small icon icon-pencil tan" onclick="startedit(<?php echo $class['id']; ?>, '<?php echo addslashes($class['title']); ?>'); return false;" data-toggle="tooltip" title="Edit this class"></button></td>
				   	</tr>
				   	<?php $i++; endforeach; ?>
				   	<tr id="tablebottom"><td colspan="6"></td></tr>
				 	</tbody>
				</table>
   				<div class="clear"></div>
   			</div>
   			<div class="half">
   				<h3>Edit Class</h3>
   				<p>You can easily edit any class, start by clicking the edit button (<i class="icon-pencil"></i>) next to the class you wish to modify or remove.
		   		<div class="clear"></div>
				
			   	<div id="editclass" class="hidden well"><!-- add hidden class here -->
			   		<form id="fe">
				   		<p><input type="reset" class="btn btn-small tan right" onclick="$('#editclass').addClass('hidden'); $('#fesubmit').button('reset');" value="Reset/Cancel" /><strong><span id="femessage">Loading...</span></strong></p>
				   		<div class="clear hr"></div>
				   		<input type="hidden" id="feevent" name="edit[event]" value="<?php echo $event['id']; ?>" />
				   		<input type="hidden" id="feactivity" name="edit[activity]" value="" />
				   		<input type="hidden" id="feclassid" name="edit[id]" value="" />
				   		<p>You can make changes to your <span class="netitle">...</span> class here, including the removal of it. Any change to the cost or schedule will notify any leaders with registered members.</p>
		   				<div class="camperform float" style="width: 290px"><input type="text" id="fetitle" name="edit[title]" class="netitle" value="" placeholder="Forestry 1" data-toggle="tooltip" title="Enter the title of this course, add a number to identify it from other classes of the same activity" /><label for="fetitle">Title</label></div>
		   				<div class="camperform float cbl last"><input type="checkbox" class="cbl" id="feopen" name="edit[open]" /><label for="feopen" class="cbl" >Open</label></div>
				   		<div class="clear"></div>
		   				<div class="camperform float " style="width: 180px"><input type="text" id="felocation" name="edit[location]" value="" placeholder="Nature Lodge" data-toggle="tooltip" title="Where will this class be taught/happen?" /><label for="felocation">Location</label></div>
		   				<div class="camperform float " style="width: 60px"><input type="text" id="fesoft" name="edit[soft]" value="" placeholder="none" data-toggle="tooltip" title="A soft limit is the number of spots in a class. Registrations beyond this limit will be waitlisted." /><label for="fesoft">Soft Limit</label></div>
		   				<div class="camperform float " style="width: 70px"><input type="text" id="fehard" name="edit[hard]" value="" placeholder="none" data-toggle="tooltip" title="The hard limit is the final limit, it can not be exceeded." /><label for="fehard">Hard Limit</label></div>
		   				<div class="camperform float last" style="width: 55px"><input type="text"  id="feamount" name="edit[amount]" value="" placeholder="$" data-toggle="tooltip" title="Enter the dollar amount for preorders. This amount will be added to unit costs here in Camper for units that choose to preorder activity materials." /><label for="feamount">Amount</label></div>
				   		<div class="clear"></div>
				   		<p>Select when in the schedule this class will be offered, please choose at least one block.</p>
				   		<?php if (is_array($periods) && !empty($periods['periods'])) { 
				   			if ($event['activitytype'] == 'day') { // Single Day Event ?>
				   			<table class="table table-condensed">
					   			<thead><tr><th>Period</th><th><i class="icon-ok tan"></i></th></tr></thead>
						   		<tbody>
							   		<?php foreach ($periods['periods'] as $p) { ?><tr><td><?php echo $p['label']; ?></td><td><input type="checkbox" class="fecheckbox" name="edit[blocks][<?php echo 'A'.$p['id']; ?>]" value="<?php echo 'A'.$p['id']; ?>" /></td></tr><?php } ?>
						   		</tbody>
				   			</table>
				   		<?php } else { // Multi day or week event ?>
				   			<table class="table table-condensed">
					   			<thead><tr><th>Period</th><?php foreach ($periods['days'] as $d) { ?><th><?php echo $d['label']; ?></th><?php } ?></tr></thead>
						   		<tbody>
							   		<?php foreach ($periods['periods'] as $p) { ?><tr><td><strong><?php echo $p['label']; ?></strong></td><?php foreach ($periods['days'] as $d) { ?><td><input type="checkbox" class="fecheckbox" name="edit[blocks][<?php echo ucfirst($d['id']).$p['id']; ?>]" value="<?php echo ucfirst($d['id']).$p['id']; ?>" /></td><?php } ?></tr><?php } ?>
						   		</tbody>
				   			</table>
				   		<?php } /* end multiday if */ } else { ?>
					   		<p><i class="icon-remove red"></i> <strong>No periods!</strong> Please head to <?php echo anchor('event/'.$event['id'].'/sessions', 'Sessions'); ?> to add some.</p>
				   		<?php } ?>
				   		<div class="clear"></div>
				   		<button class="btn teal" data-loading-text="Saving changes..." onclick="$(this).button('loading'); return false;" id="fesubmit">Save Changes</button> <button class="btn red right camperhoverpopover" data-toggle="popover" title="Delete Class" data-placement="top" data-content="When you delete this member, <strong>all linked class registrations will be deleted as well</strong>." data-loading-text="Removing class..." onclick="$(this).button('loading'); deleteclass(this); return false;" id="fedelete">Delete</button>
			   		</form>
			   	</div>
			   	<div id="fealert" class="alert hidden"></div>
   				<div class="clear hr"></div>
   				<h3>Add Class</h3>
   				<p>You can add a class from an activity in the <?php echo anchor('event/activities', 'Activity Library'); ?>, start by searching for the activity you wish to add a class for. </p>
		   		<div class="camperform float search" style="width: 70%"><i class="icon-search"></i><input class="ico activitylist typeahead" type="text" data-toggle="tooltip"  placeholder="Forestry..."  title="Search for an activity by title, category, type or id" /><label>Activity Search</label></div>
		   		<div class="clear"></div>
			   	<div id="newclass" class="hidden well"><!-- add hidden class here -->
			   		<form id="fn">
				   		<p><input type="reset" class="btn btn-small tan right" onclick="$('#newclass').addClass('hidden'); $('#fnsubmit').button('reset');" value="Reset/Cancel" /><strong>Add a class for <span class="natitle">...</span></strong></p>
				   		<div class="clear hr"></div>
				   		<input type="hidden" id="fnevent" name="new[event]" value="<?php echo $event['id']; ?>" />
				   		<input type="hidden" id="fnactivity" name="new[activity]" value="" />
				   		<p>Set the class title, location, preorder cost, limits, and schedule, then hit "add course".</p>
		   				<div class="camperform float" style="width: 290px"><input type="text" id="fntitle" name="new[title]" class="natitle" value="" placeholder="Forestry 1" data-toggle="tooltip" title="Enter the title of this course, add a number to identify it from other classes of the same activity" /><label for="fntitle">Title</label></div>
		   				<div class="camperform float cbl last"><input type="checkbox" class="cbl" name="new[open]" checked="checked" id="fnopen" /><label for="fnopen" class="cbl" >Open</label></div>
				   		<div class="clear"></div>
		   				<div class="camperform float " style="width: 180px"><input type="text" id="fnlocation" name="new[location]" value="" placeholder="Nature Lodge" data-toggle="tooltip" title="Where will this class be taught/happen?" /><label for="fnlocation">Location</label></div>
		   				<div class="camperform float " style="width: 60px"><input type="text" id="fnsoft" name="new[soft]" value="" placeholder="none" data-toggle="tooltip" title="A soft limit is the number of spots in a class. Registrations beyond this limit will be waitlisted." /><label for="fnsoft">Soft Limit</label></div>
		   				<div class="camperform float " style="width: 70px"><input type="text" id="fnhard" name="new[hard]" value="" placeholder="none" data-toggle="tooltip" title="The hard limit is the final limit, it can not be exceeded." /><label for="fnhard">Hard Limit</label></div>
		   				<div class="camperform float last" style="width: 55px"><input type="text"  id="fnamount" name="new[amount]" value="" placeholder="$" data-toggle="tooltip" title="Enter the dollar amount for preorders. This amount will be added to unit costs here in Camper for units that choose to preorder activity materials." /><label for="fnamount">Amount</label></div>
				   		<div class="clear"></div>
				   		<p>Select when in the schedule this class will be offered, please choose at least one block.</p>
				   		<?php if (is_array($periods) && !empty($periods['periods'])) { 
				   			if ($event['activitytype'] == 'day') { // Single Day Event ?>
				   			<table class="table table-condensed">
					   			<thead><tr><th>Period</th><th><i class="icon-ok tan"></i></th></tr></thead>
						   		<tbody>
							   		<?php foreach ($periods['periods'] as $p) { ?><tr><td><?php echo $p['label']; ?></td><td><input type="checkbox" class="fncheckbox" name="new[blocks][<?php echo 'A'.$p['id']; ?>]" value="<?php echo 'A'.$p['id']; ?>" /></td></tr><?php } ?>
						   		</tbody>
				   			</table>
				   		<?php } else { // Multi day or week event ?>
				   			<table class="table table-condensed">
					   			<thead><tr><th>Period</th><?php foreach ($periods['days'] as $d) { ?><th><?php echo $d['label']; ?></th><?php } ?></tr></thead>
						   		<tbody>
							   		<?php foreach ($periods['periods'] as $p) { ?><tr><td><strong><?php echo $p['label']; ?></strong></td><?php foreach ($periods['days'] as $d) { ?><td><input type="checkbox" class="fncheckbox" name="new[blocks][<?php echo ucfirst($d['id']).$p['id']; ?>]" value="<?php echo ucfirst($d['id']).$p['id']; ?>" /></td><?php } ?></tr><?php } ?>
						   		</tbody>
				   			</table>
				   		<?php } /* end multiday if */ } else { ?>
					   		<p><i class="icon-remove red"></i> <strong>No periods!</strong> Please head to <?php echo anchor('event/'.$event['id'].'/sessions', 'Sessions'); ?> to add some.</p>
				   		<?php } ?>
				   		<div class="clear"></div>
				   		<p id="fnmessage"></p>
				   		<p><button class="btn teal" data-loading-text="Adding your class..." onclick="$(this).button('loading'); return false;" id="fnsubmit">Add Class</button></p>
				   		<div class="clear"></div>
			   		</form>
			   	</div>
			   	<div id="fnalert" class="alert hidden"></div>
   				<p><strong>Can't find your activity?</strong> It may not be in Camper yet, you can add it to the Activity Library.</p>
   				<p><?php echo anchor('/event/activities/new', '<i class="icon-plus"></i> New Activity', 'class="btn btn-small tan"'); ?></p>
   				<div class="clear"></div>
   			</div>
   		</div>
   		<div class="clear"></div>
   		<?php } ?>
	</article>
