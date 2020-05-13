<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin Event / Sessions View
 *
 * This is the ...
 *
 * File: /application/views/admin/event/sessions.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

?>
	<?php echo form_open(uri_string());?>
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
   		<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
		<div class="clear"></div>
   		<ul id="detailstabs" class="teal">
   			<li class=""><?php echo anchor("event/".$event['id']."/registrations", 'Registrations');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/details", 'Details &amp; Dates');?></li>
   			<li class="active"><?php echo anchor("event/".$event['id']."/sessions", 'Sessions');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/options", 'Starters');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/custom", 'Options &amp; Discounts');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/classes", 'Classes');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/message", 'Message');?></li>
   		</ul>
	</article>
	<article class="textsection">
   		<div class="container">
       		<div class="quarter">
   	    		<h2>Periods</h2>
   	    		<p>Periods are used to enable activity and merit badge (class) registration for this event. Simply set a day or week schedule here and you will be able to open class regs to your units.</p>
   	    		<div class="clear"></div>
				<input type="submit" name="submit" value="Save Changes" class="btn teal" data-loading-text="Saving changes..." onclick="$(this).button('loading');" /> 
   	    		<div class="clear"></div>
       		</div>
       		<div class="threequarter">
		   		<h2>Periods for Class Registration</h2>
   	    		<p>Set your schedule here for the class registrations, if enabled. You can choose a day or week type, and add the number of periods and days as you need. The periods and days are automatically set, simply choose the number you need, and a label for each. When you add or change the schedule here, the changes will be reflected in the event activities section.</p>
   				<div class="clear "></div>
			   	<div class="camperform float cbl" style="width:60%;"><input type="checkbox" class="cbl" <?php if($event['activityregs'] == '1') { ?> checked="checked"<?php } ?> name="period[enabled]" id="fpenabled" /><label for="fpenabled" class="cbl" >Class Registrations</label><small>Enable class/activity registration for this event?</small></div>
   				<div class="camperform float " style="">
   					<select id="fptype" name="period[type]" data-toggle="tooltip" title="Chose single day if you are only need one day, multi day if you have multiple days for activities">
						<option<?php if ($event['activitytype'] == 'day') echo ' selected="selected"'; ?> value="day">Single Day</option>
						<option<?php if ($event['activitytype'] == 'week') echo ' selected="selected"'; ?> value="week">Multi Day / Week</option>
					</select>
					<label for="fptype">Schedule Type</label>
				</div>
   				<div class="clear "></div>
   				<div class="camperform float " style="width: 60%"><input type="text" class="datepicker" name="period[opendate]" id="fpopening" value="<?php echo ($event['activitydate'] !== '0' && $event['activitydate'] !== '') ? date('F j, Y', $event['activitydate']): ''; ?>" placeholder="None" data-toggle="tooltip" title="Choose a date when activity registrations will open to units" /><label for="fpopening">Open activity registration on this date</label></div>
   				<div class="camperform float " style="">
   					<select id="fpclose" name="period[closedate]" data-toggle="tooltip" title="Choose the time before the event or session starts when registration should close">
						<option<?php if ($event['activitytime'] == '0') echo ' selected="selected"'; ?> value="0">None</option>
						<option<?php if ($event['activitytime'] == '86164') echo ' selected="selected"'; ?> value="86164">1 Day</option>
						<option<?php if ($event['activitytime'] == '172328') echo ' selected="selected"'; ?> value="172328">2 Days</option>
						<option<?php if ($event['activitytime'] == '258492') echo ' selected="selected"'; ?> value="258492">3 Days</option>
						<option<?php if ($event['activitytime'] == '603148') echo ' selected="selected"'; ?> value="603148">1 Week</option>
						<option<?php if ($event['activitytime'] == '1206297') echo ' selected="selected"'; ?> value="1206297">2 Weeks</option>
						<option<?php if ($event['activitytime'] == '2412594') echo ' selected="selected"'; ?> value="2412594">3 Weeks</option>
						<option<?php if ($event['activitytime'] == '2592000') echo ' selected="selected"'; ?> value="2592000">1 Month</option>
					</select>
					<label for="fpclose">Closing Time</label>
				</div>
   				<div class="clear "></div>
			   	<div class="camperform float cbl" style="width:60%;"><input type="checkbox" class="cbl" <?php if($event['bluecards'] == '1') { ?> checked="checked"<?php } ?> name="period[bluecards]" id="fpbluecards" /><label for="fpbluecards" class="cbl" >Blue Cards</label><small>Enable printing of blue cards? <a href="#modal_help" data-toggle="modal">Learn More &rarr;</a></small></div>
   				<div class="clear hr"></div>
   				
   				<div class="quarter">
	   				<h3>Periods</h3>
	   				<p>Periods are class times in a day, the numbers will reset on save.</p>
	   				<div class="clear hr"></div>
	   				<?php $ip=1; if (isset($periods['periods'])) : foreach ($periods['periods'] as $p): ?>
				  		<div id="period<?php echo $ip; ?>">
					  		<input type="hidden" name="periods[<?php echo $ip; ?>][id]" value="<?php echo $ip; ?>" />
					  		<div class="camperform float last" data-toggle="tooltip" id="fppid<?php echo $ip; ?>" title="This is the period number, it is set automatically."><span><a data-toggle="tooltip" title="Delete this period" onclick="deleteperiod(<?php echo $ip; ?>); return false;"><i class="icon-remove-sign tan" style='display:inline'></i></a> <?php echo $ip; ?></span><!--<label for="fppid<?php echo $ip; ?>" ></label>--></div>
					  		<div class="camperform float last" style="width: 145px"><input type="text" id="fpplabel<?php echo $ip; ?>" value="<?php echo $p['label']; ?>" name="periods[<?php echo $ip; ?>][label]" placeholder="Time or Label" data-toggle="tooltip" title="The name of this period as it will show to leaders and on schedules" /><!--<label for="fpplabel<?php echo $ip; ?>" >Label</label>--></div>
				  		</div>
				  		<div class="clear"></div> 
		   			<?php $ip++; endforeach; endif; if ($ip==1) echo '<span id="noperiods">No periods yet.</span>'; ?>
					<div id="periodsend" class="clear"></div>
		   			<div class="clear hr"></div> 
					<button class="btn btn-small tan" onclick="createperiod(); return false;"><i class="icon-plus"></i> Add a period</button>
   				</div>
   				<div class="half last" style="">
	   				<h3>Days</h3>
	   				<p>Manage your days if you have selected the multi-day schedule type. You don't need to specify days for a single day event.</p>
	   				<div class="clear hr"></div>
	   				<?php $id='a'; if (isset($periods['days'])) : foreach ($periods['days'] as $p): ?>
				  		<div id="day<?php echo $id; ?>">
					  		<input type="hidden" name="days[<?php echo $id; ?>][id]" value="<?php echo $id; ?>" />
					  		<div class="camperform float last" data-toggle="tooltip" id="fpdid<?php echo $id; ?>" title="This is the period number, it is set automatically."><span><a data-toggle="tooltip" title="Delete this day" onclick="deleteday('<?php echo $id; ?>'); return false;"><i class="icon-remove-sign tan" style='display:inline'></i></a> <?php echo ucfirst($id); ?></span><!--<label for="fpdpid<?php echo $id; ?>" ></label>--></div>
					  		<div class="camperform float last" style="width: 170px"><input type="text" id="fpdlabel<?php echo $id; ?>" value="<?php echo $p['label']; ?>" name="days[<?php echo $id; ?>][label]" placeholder="Time or Label" data-toggle="tooltip" title="The name of this day as it will show to leaders and on schedules" /><!--<label for="fpdlabel<?php echo $id; ?>" >Label</label>--></div>
				  		</div>
				  		<div class="clear"></div> 
		   			<?php $id++; endforeach; endif; if ($id=='a') echo '<span id="nodays">No days yet.</span>'; ?>
					<div id="daysend" class="clear"></div>
		   			<div class="clear hr"></div> 
					<button class="btn btn-small tan" onclick="createday(); return false;"><i class="icon-plus"></i> Add a day</button>
   				</div>
   				
				<script>
					periodscount = <?php echo $ip; ?>;
					dayscountbase = '<?php echo $id; ?>';
					function deleteperiod(num)
					{
						var divid = "#period"+num;
						$(divid).remove();
						return false;
					}
					function deleteday(num)
					{
						var divid = "#day"+num;
						$(divid).remove();
						return false;
					}
					function createperiod()
					{
						var divid = "#period"+periodscount;
						var newtitleid = "#fpplabel"+periodscount;
						var newperiod = "<div id='period"+periodscount+"'>"
					  		+"<input type='hidden' name='periods["+periodscount+"][id]' value='"+periodscount+"' />"
					  		+"<div class='camperform float last' id='fpdid"+periodscount+"'><span><a onclick='deleteperiod("+periodscount+"); return false;'><i class='icon-remove-sign tan' style='display:inline'></i></a> "+periodscount+"</span><!--<label for='fpdpid"+periodscount+"' ></label>--></div>"
					  		+"<div class='camperform float last' style='width: 145px'><input type='text' id='fpdlabel"+periodscount+"' name='periods["+periodscount+"][label]' placeholder='Time or Label' /><!--<label for='fpdlabel"+periodscount+"' >Label</label>--></div>"
					  		+"</div><div class='clear'></div>";
						$("#periodsend").before(newperiod);
						$(newtitleid).focus();
						$('#noperiods').remove();
						periodscount++;
						return false;
					}
					function createday()
					{
						var nex = dayscountbase.charCodeAt(0);
						var dayscount = String.fromCharCode(nex++);
						var divid = "#day"+dayscount;
						var uppercase = dayscount.toUpperCase();
						var newtitleid = "#fpplabel"+dayscount;
						var newday = "<div id='day"+dayscount+"'>"
					  		+"<input type='hidden' name='days["+dayscount+"][id]' value='"+dayscount+"' />"
					  		+"<div class='camperform float last' id='fpdid"+dayscount+"'><span><a "+'onclick="deleteday(\''+dayscount+'\'); return false;"'+"><i class='icon-remove-sign tan' style='display:inline'></i></a> "+uppercase+"</span><!--<label for='fpdpid"+dayscount+"' ></label>--></div>"
					  		+"<div class='camperform float' style='width: 170px'><input type='text' id='fpdlabel"+dayscount+"' name='days["+dayscount+"][label]' placeholder='Time or Label' /><!--<label for='fpdlabel"+dayscount+"' >Label</label>--></div>"
					  		+"</div><div class='clear'></div>";
						$("#daysend").before(newday);
						$(newtitleid).focus();
						$('#nodays').remove();
						dayscountbase = String.fromCharCode(nex++);
						return false;
					}
				</script>
	   			<div class="clear"></div>
   			</div>
   		</div>
   		<div class="clear"></div>
	</article>
	<div class="clear skinny"></div>
	<input type="hidden" name="id" value="<?php echo $event['id']; ?>" /> 
	<input type="hidden" name="s" value="1" /> 
	<article class="textsection">
   		<div class="container">
       		<div class="quarter">
   	    		<h2>Sessions</h2>
   	    		<p>In Camper, units register for sessions/weeks. Sessions/weeks control the registration limits, dates and costs of the event.</p>
   	    		<div class="camperform float" style="width: 200px;">
		   			<select id="fsessiontitle" name="sessiontitle">
						<option value="Week"<?php if ($event['sessiontitle'] == 'Week') { ?> selected="selected"<?php } ?>>Week</option>
						<option value="Session"<?php if ($event['sessiontitle'] == 'Session') { ?> selected="selected"<?php } ?>>Session</option>
						<option value="Trek"<?php if ($event['sessiontitle'] == 'Trek') { ?> selected="selected"<?php } ?>>Trek</option>
					</select>
					<label for="fsessiontitle" class="">Session Title</label>
				</div>
   	    		<div class="clear"></div>
				<input type="submit" name="submit" value="Save Changes" class="btn teal" data-loading-text="Saving changes..." onclick="$(this).button('loading');" /> 
				<input type="reset" name="reset" value="Reset" class="btn tan"  />	
   	    		<div class="clear"></div>
       		</div>
       		<div class="threequarter">
		   		<h2><?php echo $event['title'].' '.$event['sessiontitle'].'s'; ?></h2>
   	    		<p>Manage your <?php echo $event['sessiontitle'].'s'; ?> here. You can set the registration limits, costs, dates and other details here. While you may have as many sessions as you want, you must have at least 1 <?php echo $event['sessiontitle']; ?>.</p>
   	    		<p><a href="#sessioneditor" class="btn tan" role="button" data-toggle="modal" ><i class="icon-pencil"></i> Edit Sessions &rarr;</a></p>
   				<div class="clear "></div>

   	    		<div class="tab-content">
   	    		
			      	<table class="table table-condensed">
			      		<thead>
			      	   	<tr><th colspan="2"><?php echo $event['sessiontitle']; ?></th><th>Open</th><th>Dates</th><th>Limit/H</th><th>Cost/A/F</th><th>Tools</th></tr>
			      		</thead>
			      		<tbody>
					  	<?php $i=1; foreach ($sessions as $onesession): ?>
			      	    	<tr>
			      	    		<td><?php echo $i; ?></td>
			      	    		<td><?php echo (empty($onesession['title'])) ? $event['sessiontitle'].' '.$i : $onesession['title']; ?></td>
			      	    		<td><?php echo ($onesession['open'] == '0') ? '<i class="icon-remove red"></i>' : '<i class="icon-ok teal"></i>'; ?></td>
			      	    		<td><?php echo (empty($onesession['dateend'])) ? date('F j, Y', $onesession['datestart']) : date('F j', $onesession['datestart']).date(' - F j, Y', $onesession['dateend']); ?></td>
				  				<td><?php echo $onesession['limitsoft']; ?> / <strong><?php echo $onesession['limithard']; ?></strong></td>
				  				<td><strong>$<?php echo $onesession['cost']; ?></strong> / $<?php echo $onesession['costadult']; ?> / $<?php echo $onesession['costfamily']; ?></td>
				  				<td><a href="#sessioneditor" class="btn btn-small tan" data-toggle="modal" ><i class="icon-pencil"></i> Edit</a></td>
			      	    	</tr>
					 	<?php $i++; endforeach;?>
			      		</tbody>
			      	</table>
   				</div>
   			</div>
   		</div>
   		<div class="clear hr"></div>
   	</article>
	<article class="textsection">
   		<div class="container">
       		<div class="quarter">
   	    		<h2>Session Groups</h2>
   	    		<p>Enable and manage session groups for registrations. Note that if you make group changes after registrations have begun, the units' registration groups may no longer be correct.</p>
   	    		<div class="camperform float" style="width: 200px;">
		   			<select id="fg" name="groups[enabled]">
						<option value="0"<?php if ($groups['enabled'] == '0') { ?> selected="selected"<?php } ?>>Disabled</option>
						<option value="1"<?php if ($groups['enabled'] == '1') { ?> selected="selected"<?php } ?>>Enabled</option>
					</select>
					<label for="fg" class="">Session Groups</label>
				</div>
   	    		<div class="clear"></div>
				<input type="submit" name="submit" value="Save Options" class="btn teal" data-loading-text="Saving options..." onclick="$(this).button('loading');" /> 
   	    		<div class="clear"></div>
       		</div>
       		<div class="threequarter">
		   		<h2><?php echo $event['title']; ?> Groups</h2>
   	    		<p>Session groups let you split up unit registrations for a single session into multiple groups while still sharing activities. For example, these groups could be used to offer campsite or program area options for registrations while allowing leaders that ability to register member for activities/merit badges across the whole session. Leaders can only choose one group but session groups can have limits. This can be handy for balancing registrations between multiple dining halls for example. </p>
   	    		<p>Start by giving the groups section a title and description, then create the individual groups below, each one needs a title and a description. Set the default option which will be used when a unit registers for the <?php echo $event['sessiontitle']; ?>.</p>
   				<div class="clear "></div>
   				<div class="camperform float" style="width: 200px"><input type="text" name="groups[title]" id="fgtitle" value="<?php echo $groups['title']; ?>" placeholder="Title" data-toggle="tooltip" title="What do you want to call the groups? This will show on the leader's registration page." /><label for="fgtitle">Groups Title</label></div>
		   		<div class="camperform float cbtemp" style="width: 60px"><input type="checkbox"  id="fglimited<?php echo $i; ?>"<?php if($groups['limited'] == '1') { ?> checked="checked"<?php } ?> name="groups[limited]" data-toggle="tooltip" title="If enabled, limits will be enabled for units or participants in these groups" /><label for="fglimited<?php echo $i; ?>" name="groups[limited]" >Limited</label></div>
		   		<div class="camperform float cbtemp" style="width: 60px"><input type="checkbox"  id="fgpercent<?php echo $i; ?>"<?php if($groups['percent'] == '1') { ?> checked="checked"<?php } ?> name="groups[percent]" data-toggle="tooltip" title="If enabled, the limits in each group will be a percent of the total session openings" /><label for="fgpercent<?php echo $i; ?>" name="groups[percent]" >Percent</label></div>
		   		<div class="camperform float cbtemp" style="width: 60px"><input type="checkbox"  id="fgperunit<?php echo $i; ?>"<?php if($groups['perunit'] == '1') { ?> checked="checked"<?php } ?> name="groups[perunit]" data-toggle="tooltip" title="If enabled, the limits will affect units instead of number of participants" /><label for="fgperunit<?php echo $i; ?>" name="groups[perunit]" >Per Unit</label></div>
		   		<div class="camperform float cbtemp" style="width: 60px"><input type="checkbox"  id="fgshow<?php echo $i; ?>"<?php if($groups['show'] == '1') { ?> checked="checked"<?php } ?> name="groups[show]" data-toggle="tooltip" title="If enabled, this set of groups will replace the total session limit and registration numbers on the admin regs page, and on the leaders events listing." /><label for="fgshow<?php echo $i; ?>" name="groups[show]" >Show?</label></div>
   				<div class="clear "></div>
   				<div class="camperform float last" style="width: 665px"><input type="text" name="groups[desc]" id="fgdesc" value="<?php echo $groups['desc']; ?>" placeholder="Description" data-toggle="tooltip" title="Describe what the groups offered will do, there is no limit here." /><label for="fgdesc">Groups Description</label></div>
   				<div class="clear hr"></div>
				<?php $i=1; if (isset($groups['groups'])) : foreach ($groups['groups'] as $group): ?>
				  	<div id="group<?php echo $i; ?>">
				  	<div class="deletedcontent"><i class="icon-ok teal"></i> This group been deleted from this event. Click 'Save Groups' to save this. (<a href="#" onclick="undeletegroup(<?php echo $i; ?>); return false;">Undo</a>)</div><div class="sessionform">
				  	<!-- ID	-->				<input type="hidden" id="" name="group[<?php echo $i; ?>][id]" value="<?php echo $group['id']; ?>" />
				  	<!-- NUM	-->			<input type="hidden" id="" name="group[<?php echo $i; ?>][num]" value="<?php echo $i; ?>" />
				  	<!-- NEW -->			<input type="hidden" id="" name="group[<?php echo $i; ?>][new]" value="0" />
		   			<div class="camperform float" style="width: 60px" data-toggle="tooltip" id="fgid<?php echo $i; ?>" title="This is the group number, it is set automatically."><input type="text" value="<?php echo $i; ?>" name="group[<?php echo $i; ?>][num]" disabled="disabled" /><label for="fgid<?php echo $i; ?>" >Group</label></div>
		   			<div class="camperform float" style="width: 200px"><input type="text" id="fgtitle<?php echo $i; ?>" value="<?php echo $group['title']; ?>" name="group[<?php echo $i; ?>][title]" placeholder="Title" data-toggle="tooltip" title="Name the group as it should show to leaders" /><label for="fgtitle<?php echo $i; ?>" >Title</label></div>
		   			<div class="camperform float" style="width: 50px"><input type="text" id="fglimit<?php echo $i; ?>" value="<?php echo $group['limit']; ?>" name="group[<?php echo $i; ?>][limit]" placeholder="none" data-toggle="tooltip" title="What is the limit of registrations for this group? Leave blank for no limit." /><label for="fglimit<?php echo $i; ?>" >Limit</label></div>
		   			<div class="camperform float" style="width: 60px"><input type="text" id="fgsoftlimit<?php echo $i; ?>" value="<?php echo $group['softlimit']; ?>" name="group[<?php echo $i; ?>][softlimit]" placeholder="none" data-toggle="tooltip" title="When the soft limit is reached, new registrations for the session will be closed. This does not affect existing registrations. Leave blank for none." /><label for="fgsoftlimit<?php echo $i; ?>" >Soft Limit</label></div>
		   			<div class="camperform float" style="width: 50px"><input type="text" id="fgcost<?php echo $i; ?>" value="<?php echo $group['cost']; ?>" name="group[<?php echo $i; ?>][cost]" placeholder="$5" data-toggle="tooltip" title="Specify a cost for this group. Leave blank for none." /><label for="fgcost<?php echo $i; ?>" >Cost</label></div>
		   			<div class="camperform float cbtemp" style="width: 70px"><input type="checkbox" id="fgperperson<?php echo $i; ?>" <?php if($group['perperson'] == '1') { ?> checked="checked"<?php } ?> name="group[<?php echo $i; ?>][perperson]" placeholder="none" data-toggle="tooltip" title="If the cost applies to each person registered, check this box. Otherwise, the cost will apply to the unit." /><label for="fgperperson<?php echo $i; ?>" >Per Person</label></div>
		   			<div class="camperform float cbtemp" style="width: 60px"><input type="radio" id="fgdefault<?php echo $i; ?>"<?php if($groups['default'] == $group['id']) { ?> checked="checked"<?php } ?> name="groups[default]" value="<?php echo $group['id']; ?>" data-toggle="tooltip" title="Set which group a new registration will default to" /><label for="fgdefault<?php echo $i; ?>">Default?</label></div>
		   			<div class="clear"></div>
		   			<div class="camperform float" style="width: 60px"><button class="btn btn-mini red" data-toggle="tooltip" title="Delete this group" onclick="deletegroup(<?php echo $i; ?>); return false;"><i class="icon-remove-sign"></i></button> <button class="btn btn-mini tan" data-toggle="tooltip" title="Add a group" onclick="creategroup(); return false;"><i class="icon-plus-sign"></i></button></div>
		   			<div class="camperform float" style="width: 575px"><input type="text" id="fgdesc<?php echo $i; ?>" value="<?php echo $group['desc']; ?>" name="group[<?php echo $i; ?>][desc]" placeholder="Description" data-toggle="tooltip" title="Give a short description for this group" /><label for="fgdesc<?php echo $i; ?>" >Description</label></div>
				  	</div></div>
		   			<div class="clear hr"></div> 
				<?php $i++; endforeach; endif; ?>
					<div id="groupsend" class="clear"></div>
					<button class="btn teal" data-toggle="tooltip" title="Add a group" onclick="creategroup(); return false;"><i class="icon-plus"></i> Add a Group</button>
				<!-- ADD JQUERY ONLOAD -> set $i count as a JS var so we can use it to create new DOM nodes for the form with the same scheme. Everyone plays nice. -->
				<script>
					groupscount = <?php echo $i; ?>;
					function deletegroup(groupnum)
					{
						var divid = "#group"+groupnum;
						$(divid).append("<input type='hidden' id='fdelete"+groupnum+"' name='group["+groupnum+"][delete]' value='1' />");
						$(divid).addClass('deletedsession');
						return false;
					}
					function undeletegroup(groupnum)
					{
						var divid = "#group"+groupnum;
						var deleteinputid = "#fdelete"+groupnum;
						$(deleteinputid).remove();
						$(divid).removeClass('deletedsession');
						return false;
					}
					function creategroup()
					{
						var divid = "#group"+groupscount;
						var newtitleid = "#fgtitle"+groupscount;
						var newgroup = "<div id='group"+groupscount+"'><div class='deletedcontent'><i class='icon-ok teal'></i> This group has been deleted from this event. Click 'Save groups' to save this. (<a href='#' onclick='undeletegroup("+groupscount+"); return false;'>Undo</a>)</div><div class='sessionform'>"
							+"<input type='hidden' name='group["+groupscount+"][new]' value='1' />"
							+"<input type='hidden' name='group["+groupscount+"][num]' value='"+groupscount+"' />"
				   			+"<div class='camperform float' style='width: 60px' data-toggle='tooltip' id='fgid"+groupscount+"' title='This is the group number, it is set automatically.'><input type='text' value='"+groupscount+"' name='group["+groupscount+"][num]' disabled='disabled' /><label for='fgid"+groupscount+"' >Group</label></div>"
				   			+"<div class='camperform float' style='width: 200px'><input type='text' id='fgtitle"+groupscount+"' name='group["+groupscount+"][title]' placeholder='Title' data-toggle='tooltip' title='Name the group as it should show to leaders' /><label for='fgtitle"+groupscount+"' >Title</label></div>"
				   			+"<div class='camperform float' style='width: 80px'><input type='text' id='fglimit"+groupscount+"' name='group["+groupscount+"][limit]' placeholder='none' data-toggle='tooltip' title='What is the limit of registrations for this group? Leave blank for no limit.' /><label for='fglimit"+groupscount+"' >Limit</label></div>"
				   			+"<div class='camperform float' style='width: 80px'><input type='text' id='fgsoftlimit"+groupscount+"' name='group["+groupscount+"][softlimit]' placeholder='none' data-toggle='tooltip' title='When the soft limit is reached, new registrations for the session will be closed. This does not affect existing registrations. Leave blank for none.' /><label for='fgsoftlimit"+groupscount+"' >Soft Limit</label></div>"
				   			+"<div class='camperform float' style='width: 50px'><input type='text' id='fgcost"+groupscount+"' name='group["+groupscount+"][cost]' placeholder='$5' data-toggle='tooltip' title='Specify a cost for this group. Leave blank for none.' /><label for='fgcost"+groupscount+"' >Cost</label></div>"
				   			+"<div class='camperform float cbtemp' style='width: 70px'><input type='checkbox' id='fgperperson"+groupscount+"' checked='checked' name='group["+groupscount+"][perperson]' placeholder='none' data-toggle='tooltip' title='If the cost applies to each person registered, check this box. Otherwise, the cost will apply to the unit.' /><label for='fgperperson"+groupscount+"' >Per Person</label></div>"
				   			+"<div class='clear'></div>"
				   			+"<div class='camperform float' style='width: 60px'><button class='btn btn-mini red' data-toggle='tooltip' title='Delete this group' onclick='deletegroup("+groupscount+"); return false;'><i class='icon-remove-sign'></i></button> <button class='btn btn-mini tan' data-toggle='tooltip' title='Add a group' onclick='creategroup(); return false;'><i class='icon-plus-sign'></i></button></div>"
				   			+"<div class='camperform float' style='width: 575px'><input type='text' id='fgdesc"+groupscount+"' name='group["+groupscount+"][desc]' placeholder='Description' data-toggle='tooltip' title='Give a short description for this group' /><label for='fgdesc"+groupscount+"' >Description</label></div>"
							+"</div></div>"
							+"<div class='clear hr'></div>";
						$("#groupsend").before(newgroup);
						$(newtitleid).focus();
						groupscount++;
						return false;
					}
				</script>
	   			<div class="clear"></div>
   			</div>
   		</div>
   		<div class="clear"></div>
   	</article>
	<article class="content">
	<!-- Change Alternate Modal -->
	<div id="sessioneditor" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   		<div class="container">
   			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
       		<div class="pull">
   	    		<h2 class="pull">Sessions Editor</h2>
   	    		<p>Manage all of the sessions for this event here.</p>
   	    		<div class="clear"></div>
	   			<a data-toggle="popover" title="Are you sure?" data-placement="right" data-content="You are about to make changes to the sessions for this event. <br /><br />All leaders and units registered for these sessions will be notified about the changes.<br /><br /><input type='submit' name='submit' value='Save Sessions' class='btn teal' /> <input type='reset' name='reset' value='Reset' class='btn tan' /> " class="btn teal camperpopover">Save Sessions &rarr; </a>
	   			<button class="btn tan" data-dismiss="modal" aria-hidden="true">Close the Sessions Editor</button>
       		</div>
       		<div class="tab-content inner-push">
		   		<h2 class="section"><?php echo $event['title'].' '.$event['sessiontitle'].'s'; ?></h2>
   				<p>Leaders register for sessions, not necessarily events. You can manage all of this event's sessions here. You need to make sure you have at least 1 session so units can register for you event. Each session can have it's own costs, registration limits, and dates. You can also close a session if you wish to stop registrations.</p>
	   			<div class="clear hr"></div>
				<?php $i=1; foreach ($sessions as $onesession): ?>
				  	<div id="session<?php echo $i; ?>">
				  	<div class="deletedcontent"><i class="icon-ok teal"></i> <?php echo (empty($onesession['title'])) ? $event['sessiontitle'].' '.$i : $onesession['title']; ?> has been deleted from this event. Click 'Save Sessions' to save this. (<a href="#" onclick="undeletesession(<?php echo $i; ?>); return false;">Undo</a>)</div><div class="sessionform">
				  	<!-- ID	-->				<input type="hidden" id="" name="sessions[<?php echo $i; ?>][id]" value="<?php echo $onesession['id']; ?>" />
				  	<!-- NEW -->			<input type="hidden" id="" name="sessions[<?php echo $i; ?>][new]" value="0" />
				  	<!-- SESSION NUM -->	<input type="hidden" id="" name="sessions[<?php echo $i; ?>][sessionnum]" value="<?php echo $onesession['sessionnum']; ?>" />
		   			<div class="camperform float" style="width: 60px" data-toggle="tooltip" id="fsession<?php echo $i; ?>" title="This is the session/week number, it is set automatically."><input type="text" value="<?php echo $i; ?>" name="sessions[<?php echo $i; ?>][num]" disabled="disabled" /><label for="fsession<?php echo $i; ?>" >Session</label></div>
		   			<div class="camperform float" style="width: 200px"><input type="text" id="ftitle<?php echo $i; ?>" value="<?php echo $onesession['title']; ?>" name="sessions[<?php echo $i; ?>][title]" placeholder="Specialty Week" data-toggle="tooltip" title="Optional title, defaults to '<?php echo $event['sessiontitle'].' '.$i; ?>' if left blank" /><label for="ftitle<?php echo $i; ?>" >Title (optional)</label></div>
		   			<div class="camperform float" style="width: 150px"><input type="text" id="fstart<?php echo $i; ?>" class="datepicker" value="<?php if($onesession['datestart']) { echo date('F d, Y', $onesession['datestart']); } ?>" name="sessions[<?php echo $i; ?>][datestart]" placeholder="June 8, 2013" data-toggle="tooltip" title="The day this session starts" /><label for="fstart<?php echo $i; ?>" >Beginning Date</label></div>
		   			<div class="camperform float" style="width: 150px"><input type="text" id="fend<?php echo $i; ?>" class="datepicker" value="<?php if($onesession['dateend']) { echo date('F d, Y', $onesession['dateend']); } ?>" name="sessions[<?php echo $i; ?>][dateend]" placeholder="June 15, 2013" data-toggle="tooltip" title="The day this session ends" /><label for="fend<?php echo $i; ?>" >End Date</label></div>
		   			<div class="clear"></div>
		   			<div class="camperform float" style="width: 60px"><button class="btn btn-mini red" data-toggle="tooltip" title="Delete this session" onclick="deletesession(<?php echo $i; ?>); return false;"><i class="icon-remove-sign"></i></button> <button class="btn btn-mini tan" data-toggle="tooltip" title="Add a session" onclick="createsession(); return false;"><i class="icon-plus-sign"></i></button></div>
		   			<div class="camperform float" style="width: 65px"><input type="text" id="fsoft<?php echo $i; ?>" value="<?php echo $onesession['limitsoft']; ?>" name="sessions[<?php echo $i; ?>][limitsoft]" placeholder="none" data-toggle="tooltip" title="The soft limit is the advertised number of open spots for new unit registrations" /><label for="fsoft<?php echo $i; ?>" >Soft Limit</label></div>
		   			<div class="camperform float" style="width: 65px"><input type="text" id="fhard<?php echo $i; ?>" value="<?php echo $onesession['limithard']; ?>" name="sessions[<?php echo $i; ?>][limithard]" placeholder="none" data-toggle="tooltip" title="The hard limit is the maximum number of spots available. It is higher than the soft limit. It gives already registered units the freedom to make changes including adding an adult or scout even if the soft limit has been reached." /><label for="fhard<?php echo $i; ?>" >Hard Limit</label></div>
		   			<div class="camperform float cbtemp" style="width: 60px"><input type="checkbox"  id="fopen<?php echo $i; ?>"<?php if($onesession['open'] == '1') { ?> checked="checked"<?php } ?> name="sessions[<?php echo $i; ?>][open]" data-toggle="tooltip" title="If enabled, units will be able to register for this session, uncheck to stop registrations" /><label for="fopen<?php echo $i; ?>" name="sessions[<?php echo $i; ?>][open]" >Open</label></div>
		   			<div class="camperform float" style="width: 100px"><input type="text" id="fcost<?php echo $i; ?>" value="<?php echo $onesession['cost']; ?>" name="sessions[<?php echo $i; ?>][cost]" placeholder="none" data-toggle="tooltip" title="Youth/Participant cost, enter dollar amount" /><label for="fcost<?php echo $i; ?>" >Cost</label></div>
		   			<div class="camperform float" style="width: 100px"><input type="text" id="fadult<?php echo $i; ?>" value="<?php echo $onesession['costadult']; ?>" name="sessions[<?php echo $i; ?>][costadult]" placeholder="none" data-toggle="tooltip" title="Adult cost, leave blank to use youth cost" /><label for="fadult<?php echo $i; ?>" >Adult Cost</label></div>
		   			<div class="camperform float" style="width: 100px"><input type="text" id="ffamily<?php echo $i; ?>" value="<?php echo $onesession['costfamily']; ?>" name="sessions[<?php echo $i; ?>][costfamily]" placeholder="none" data-toggle="tooltip" title="Family/Child cost, leave blank to use youth cost" /><label for="ffamily<?php echo $i; ?>" >Family Cost</label></div>
				  	</div></div>
		   			<div class="clear hr"></div> 
				<?php $i++; endforeach;?>
					<div id="sessionsend" class="clear"></div>
					<button class="btn teal" data-toggle="tooltip" title="Add a session" onclick="createsession(); return false;"><i class="icon-plus"></i> Add a Session</button>
				<!-- ADD JQUERY ONLOAD -> set $i count as a JS var so we can use it to create new DOM nodes for the form with the same scheme. Everyone plays nice. -->
				<script>
					sessionscount = <?php echo $i; ?>;
					function deletesession(sessionnum)
					{
						var divid = "#session"+sessionnum;
						$(divid).append("<input type='hidden' id='fdelete"+sessionnum+"' name='sessions["+sessionnum+"][delete]' value='1' />");
						$(divid).addClass('deletedsession');
						return false;
					}
					function undeletesession(sessionnum)
					{
						var divid = "#session"+sessionnum;
						var deleteinputid = "#fdelete"+sessionnum;
						$(deleteinputid).remove();
						$(divid).removeClass('deletedsession');
						return false;
					}
					function createsession()
					{
						var divid = "#session"+sessionscount;
						var newtitleid = "#ftitle"+sessionscount;
						var newsession = "<div id='session"+sessionscount+"'><div class='deletedcontent'><i class='icon-ok teal'></i> This session has been deleted from this event. Click 'Save Sessions' to save this. (<a href='#' onclick='undeletesession("+sessionscount+"); return false;'>Undo</a>)</div><div class='sessionform'>"
							+"<input type='hidden' name='sessions["+sessionscount+"][new]' value='1' />"
							+"<input type='hidden' name='sessions["+sessionscount+"][sessionnum]' value='"+sessionscount+"' />"
							+"<div class='camperform float' style='width: 60px' data-toggle='tooltip' id='fsession"+sessionscount+"' title='This is the session/week number, it is set automatically.'><input type='text' value='"+sessionscount+"' name='sessions["+sessionscount+"][num]' disabled='disabled' /><label for='fsession"+sessionscount+"' >Session</label></div>"
							+"<div class='camperform float' style='width: 200px'><input type='text' id='ftitle"+sessionscount+"' value='' name='sessions["+sessionscount+"][title]' placeholder='Specialty Week' data-toggle='tooltip' title='Optional title, defaults as 'Week "+sessionscount+"' if left blank' /><label for='ftitle"+sessionscount+"' >Title (optional)</label></div>"
							+"<div class='camperform float' style='width: 150px'><input type='text' id='fstart"+sessionscount+"' class='datepicker' value='' name='sessions["+sessionscount+"][datestart]' placeholder='June 8, 2013' data-toggle='tooltip' title='The day this session starts' /><label for='fstart"+sessionscount+"' >Beginning Date</label></div>"
							+"<div class='camperform float' style='width: 150px'><input type='text' id='fend"+sessionscount+"' class='datepicker' value='' name='sessions["+sessionscount+"][dateend]' placeholder='June 15, 2013' data-toggle='tooltip' title='The day this session ends' /><label for='fend"+sessionscount+"' >End Date</label></div>"
							+"<div class='clear'></div>"
							+"<div class='camperform float' style='width: 60px'><button class='btn btn-mini red' data-toggle='tooltip' title='Delete this session' onclick='deletesession("+sessionscount+"); return false;'><i class='icon-remove-sign'></i></button> <button class='btn btn-mini tan' data-toggle='tooltip' title='Add a session'  onclick='createsession(); return false;'><i class='icon-plus-sign'></i></button></div>"
							+"<div class='camperform float' style='width: 65px'><input type='text' id='fsoft"+sessionscount+"' value='' name='sessions["+sessionscount+"][limitsoft]' placeholder='none' data-toggle='tooltip' title='The soft limit is the advertised number of open spots for new unit registrations' /><label for='fsoft"+sessionscount+"' >Soft Limit</label></div>"
							+"<div class='camperform float' style='width: 65px'><input type='text' id='fhard"+sessionscount+"' value='' name='sessions["+sessionscount+"][limithard]' placeholder='none' data-toggle='tooltip' title='The hard limit is the maximum number of spots available. It is higher than the soft limit. It gives already registered units the freedom to make changes including adding an adult or scout even if the soft limit has been reached.' /><label for='fhard"+sessionscount+"' >Hard Limit</label></div>"
							+"<div class='camperform float cbtemp' style='width: 60px'><input type='checkbox'  id='fopen"+sessionscount+"' name='sessions["+sessionscount+"][open]' data-toggle='tooltip' title='If enabled, units will be able to register for this session, uncheck to stop registrations' /><label for='fopen"+sessionscount+"' name='sessions["+sessionscount+"][open]' >Open</label></div>"
							+"<div class='camperform float' style='width: 100px'><input type='text' id='fcost"+sessionscount+"' value='' name='sessions["+sessionscount+"][cost]' placeholder='none' data-toggle='tooltip' title='Youth/Participant cost, enter dollar amount' /><label for='fcost"+sessionscount+"' >Cost</label></div>"
							+"<div class='camperform float' style='width: 100px'><input type='text' id='fadult"+sessionscount+"' value='' name='sessions["+sessionscount+"][costadult]' placeholder='none' data-toggle='tooltip' title='Adult cost, leave blank to use youth cost' /><label for='fadult"+sessionscount+"' >Adult Cost</label></div>"
							+"<div class='camperform float' style='width: 100px'><input type='text' id='ffamily"+sessionscount+"' value='' name='sessions["+sessionscount+"][costfamily]' placeholder='none' data-toggle='tooltip' title='Family/Child cost, leave blank to use youth cost' /><label for='ffamily"+sessionscount+"' >Family Cost</label></div>"
							+"</div></div>"
							+"<div class='clear hr'></div>";
						$("#sessionsend").before(newsession);
						$(newtitleid).focus();
						sessionscount++;
						$('.datepicker').pickadate({
						    format: 'mmmm dd, yyyy',
						    formatSubmit: 'mmmm dd yyyy',
						    hiddenPrefix: 'prefix__',
						    hiddenSuffix: '__suffix',
							selectYears: 4,
							selectMonths: true
						});
						return false;
					}
				</script>
	   			<div class="clear"></div>
   			</div>
   		</div>
   		<div class="clear"></div>
	</div>
   	<!-- End Modal -->
   	<?php echo form_close();?>
	</article>
   	<!-- Help Modal -->
   	<article class="content">
		<div id="modal_help" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	   		<div class="container">
	   			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	       		<div class="pull">
	   	    		<h2 class="pull">Help</h2>
	   	    		<p>Learn More about classes and merit badges.</p>
	   	    		<div class="clear"></div>
	       		</div>
	       		<div class="tab-content inner-push">
	   				<h2 class="section">How Merit Badges and Blue Cards Work</h2>
	   				<p>Camper has the capability of managing merit badge progress and the production of merit badge blue cards. The system can print these out as PDF forms, with 3 cards to each letter sized sheet. You must enable class registration, add classes to your event, and enable blue cards for the system to create the cards. In order to set if a class is a merit badge or just an activity, Camper lets you mark certain activities as merit badges.</p>
	   				<p>Go to the Events > Activities and edit the activity which is a merit badge. Check the merit badge box and save, that will add all classes the activity has for your event into the merit badge blue card list.</p>
	   				<p>You can create a blue card list by activity or by unit. All of the cards will come out in one document, be sure to set your printer to print double sided. When you go to create a blue card report, specify the counselor address if the merit badge was completed.</p>
	   				<p>Blue cards can be made in the Staff section of Camper.</p>
		   			<div class="clear"></div>
	   			</div>
	   		</div>
	   		<div class="clear"></div>
	   		<?php echo form_close();?>
		</div>
   	</article>
   	<!-- End Modal -->
