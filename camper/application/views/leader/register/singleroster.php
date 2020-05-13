<?php 

/* 
 * Camper Leader / Registration / Roster / Single
 *
 * This is the single roster view in Camper. It is where a leader can view and 
 * register a single youth for classes. It has links to other class functions as
 * well like the schedule reports. 
 *
 * File: /application/views/register/singleroster.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

 	$unittitle = (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')': $unit['unittype'].' '.$unit['number'];
	if (empty($session['datestart'])) {
		$sessionstart = $event['datestart'];
	} else {
		$sessionstart = $session['datestart'];
	}
	if (!empty($members)) {
		$now = time();
		$year = 31556926;
		$adults = array();
		$youth = array();
		$adult = ($unit['unittype'] == 'Ship' || $unit['unittype'] == 'Crew') ? ($year * 21): ($year * 18); // 21 and 18 years in seconds
		foreach ($members as $m) {
			if ($sessionstart-$m['dob'] >= $adult) {
				$adults[$m['id']] = $m;
			} else {
				$youth[$m['id']] = $m;
			} 
		}
	} else {
		redirect('registrations/'.$reg.'/roster', 'refresh');
	}
	
	// Prep the discounts
	$i=0; foreach ($discounts as $d) {
		if ($d['individual'] == '1') $i++;
	}
	$hasdiscounts = ($i === 0) ? false: true;


?>	<script>
    	// Hightlight on hover in and reset
    	function classhoverin(elements) {
    		$(elements).addClass('highlight');
    	}
    	function classhoverout(elements) {
    		$(elements).removeClass('highlight');
    	}

		// Background an area if class is checked
		function classchange(elements,thiselement,classidclass,title,activity,classid) {
			if($(thiselement).is(':checked')){
				$(elements).addClass('ok').append('<div class="'+classidclass+'">'+title+'</div>');
				$('.classblock').removeClass('conflict ok');
				$('#hidehere').append('<input type="hidden" class="'+classidclass+'" name="classes['+classid+'][activity]" value="'+activity+'" />');
				$('#hidehere').append('<input type="hidden" class="'+classidclass+'" name="classes['+classid+'][class]" value="'+classid+'" />');
				$('.classblock:has(div:nth-of-type(2))').removeClass('ok').addClass('conflict');
				$('.classblock:has(div:only-child)').removeClass('conflict').addClass('ok');

				
			} else {
				$('.classblock').removeClass('conflict ok');
				$('.'+classidclass).remove();
				$('.classblock:has(div:nth-of-type(2))').removeClass('ok').addClass('conflict');
				$('.classblock:has(div:only-child)').removeClass('conflict').addClass('ok');
			}
		}
		$(document).ready(function() {
			// Setup the existing classes in the schedule builder
			var classregs = { <?php foreach ($classregs as $c) { ?>"class<?php echo $c['class']; ?>": "class<?php echo $c['class']; ?>",<?php } ?> };
			$.each( classregs, function( key, value ) {
				$('#'+key).prop('checked', true);
			});
			<?php foreach ($classregs as $c) { 
				$class = $classes[$c['class']];
				$class['blocks'] = unserialize($class['blocks']);
				$class['__b'] = ''; 
				if (empty($class['blocks'])) { 
				
				} else { 
					$j=1;
					foreach ($class['blocks'] as $class['__block']) { 
						if ($j>1) $class['__b'] = $class['__b'].', ';	
						$class['__b'] = $class['__b'].'#'.$class['__block']; 
						$j++;
					}
				} 
			?>classchange('<?php echo $class['__b']; ?>', '#class<?php echo $class['id']; ?>', 'class<?php echo $class['id']; ?>', '<?php echo $class['title']; ?>',<?php echo $class['activity']; ?>, <?php echo $class['id']; ?>);
			<?php } ?>
		});

	</script>
	<div class="subnav">
		<div class="container">
			<h2>Registrations</h2>
			<nav class="campersubnav">
				<li class=""><?php echo anchor("registrations/past", 'Past Events');?></li>
				<li class="active"><?php echo anchor("registrations", 'Upcoming Events');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
		<div class="container">
			<h2>Registrations / <?php echo $event['title']; ?></h2>
				<p>You can manage all of the details regarding <strong><?php echo $unittitle; ?>'s</strong> registration for <strong><?php echo $event['title']; ?></strong>. You will find the event dates and details, any extras you can choose to add or let us know about, and manage the finances of the event (see outstanding program fees and make payments).	</p>
			<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
		</div>
			<div class="container">
				<ul id="detailstabs" class="blue">
					<li class=""><?php echo anchor('registrations/'.$reg['id'].'/details#basics', 'Basics'); ?></li>
					<li class=""><?php echo anchor('registrations/'.$reg['id'].'/details#extras', 'Extras'); ?></li>
					<li class=""><?php echo anchor('registrations/'.$reg['id'].'/details#finances', 'Finances'); ?></li>
					<li class="active"><?php echo anchor('registrations/'.$reg['id'].'/roster', 'Roster'); ?></li>
				</ul>
			</div>
		<div class="container">
			<div class="quarter">
				<h2 class="">Roster</h2>
				<p>Manage individual participants details including class registration.</p>
				<p><?php echo anchor('/registrations/'.$reg['id'].'/roster', '&larr; Full Roster', 'class="btn tan"'); ?></p>
				<h3>Youth</h3>
					<ul id="mapstabs" class="blue">
					<?php $adult = ($unit['unittype'] == 'Ship' || $unit['unittype'] == 'Crew') ? (31556926 * 21): (31556926 * 18); // 21 and 18 years in seconds
					foreach ($rosters as $r) { if ($sessionstart-$members[$r['member']]['dob'] >= $adult) continue; ?><li<?php if ($r['id'] == $roster['id']) { ?> class="active"<?php } ?>><?php echo anchor('registrations/'.$reg['id'].'/roster/'.$r['id'], $members[$r['member']]['name']); ?></li><?php } ?>
					</ul>
				<h3>Adults</h3>
					<ul id="mapstabs" class="blue">
					<?php foreach ($rosters as $r) { if ($sessionstart-$members[$r['member']]['dob'] < $adult) continue; ?><li<?php if ($r['id'] == $roster['id']) { ?> class="active"<?php } ?>><?php echo anchor('registrations/'.$reg['id'].'/roster/'.$r['id'], $members[$r['member']]['name']); ?></li><?php } ?>
					</ul>
			</div>
			<div class="threequarter last">
				<h2 class="section"><?php echo $member['name']; ?></h2>
				<p>You can modify <?php echo $member['name']; ?> in the members section. In order for us to accommodate allergies, special needs and dietary restrictions, please make sure these details are noted here.</p>
				<div class="quarter">
					<p><strong>Gender</strong>: <?php echo $member['gender']; ?><br />
					<strong>Date of Birth</strong>: <?php echo date('F j, Y', $member['dob']); ?><br />
					<strong>Shirt Size</strong>: <?php echo (empty($member['shirtsize'])) ? 'None (add one!)': $member['shirtsize']; ?></p>
				</div>
				<div class="half last">
					<!-- We'll find something to go here -->
				</div>
				<div class="clear"></div>
				<h2 class="section">Schedule</h2>
				<?php if (empty($classregs)) : ?>
				<p><?php echo $member['name']; ?>'s schedule is empty, add classes with the schedule builder below.</p>
				<?php else : ?>
				<p>These are the classes that <?php echo $member['name']; ?> is currently registered or waitlisted for. Make changes to this schedule with the schedule builder below.</p>
				<table class="table table-condensed">
					<thead>
						<tr><th>Class</th><th>Status</th><th>Cost/Prerequisites</th></tr>
					</thead>
					<tbody>
						<?php $currentclasses=array(); foreach ($classregs as $c) : 
							$currentclasses[$c['class']] = $classes[$c['class']]; ?><tr>
							<td><?php echo $classes[$c['class']]['title']; ?></td>
							<td><?php echo $this->activities_model->class_position($roster['id'],$classes[$c['class']]['id'],$session['id']); ?></td>
							<td><?php echo ($classes[$c['class']]['preorder'] > 0) ? '$'.$classes[$c['class']]['preorder']: 'No Cost'; ?> <?php echo $activities[$c['activity']]['short']; ?></td>
						</tr><?php endforeach; ?> 
					</tbody>
				</table>
				<p>
					<strong>Legend</strong><br>
					<strong>Registered:</strong> You are confirmed in the class<br>
					<strong>Waitlisted:</strong> You are not confirmed, you may get in if spots open up.<br>
					<strong>Waitlisted with an (HL):</strong> Your spot is above the hard limit, it is recommended that you register for another class.
				</p>
				<p>Note: If you remove a class from the schedule, your spot may be taken by someone else.</p>
				<?php endif; ?>
				<div class="clear"></div>
				<h2 class="section">Schedule Builder</h2>
				<?php $datestart = (isset($session['datestart']) && !empty($session['datestart'])) ? $session['datestart']: $event['datestart']; 
					if (time() > ($datestart-$event['activitytime'])) { ?>
					<p><i class="icon-info-sign blue"></i> The schedule builder is disabled right now so that we can prepare for classes. Don't worry, any class registrations you have listed above are safe and sound.</p>  
				<?php } else { // if the unit and reg qualifies ?>
					<?php echo form_open('api/v1/classregs/update?return='.uri_string(),array('id'=>'schedulebuilder'));?>
					<input type="hidden" name="unit" value="<?php echo $unit['id']; ?>" />
					<input type="hidden" name="event" value="<?php echo $event['id']; ?>" />
					<input type="hidden" name="reg" value="<?php echo $reg['id']; ?>" />
					<input type="hidden" name="session" value="<?php echo $session['id']; ?>" />
					<input type="hidden" name="roster" value="<?php echo $roster['id']; ?>" />
					<input type="hidden" name="member" value="<?php echo $member['id']; ?>" />
					<?php foreach ($classregs as $c) { ?>
						<input type="hidden" class="class<?php echo $c['class']; ?>" name="classes[<?php echo $c['class']; ?>][activity]" value="<?php echo $c['activity']; ?>" />
						<input type="hidden" class="class<?php echo $c['class']; ?>" name="classes[<?php echo $c['class']; ?>][class]" value="<?php echo $c['class']; ?>" />
					<?php } ?>
	
					<?php if (!$qualifies) : ?>
					<p><strong>Please keep your account up to date and paid so make sure schedule changes are saved. Please review the following:</strong></p>
			   			<?php if ($verify['restricted']===true) { ?>
			   			<p><?php foreach ($verify['error'] as $e) { ?><i class="icon-minus tan"></i> <?php print_r($e); ?><br /><?php } ?></p>
				   		<?php } elseif ($verify['result']===false) { ?>
			   			<p><?php foreach ($verify['error'] as $e) { ?><i class="icon-minus tan"></i> <?php print_r($e); ?><br /><?php } ?></p>
			   			<?php } else { ?>There is an error, please report this using the "anything wrong" feedback form below, please mention that the schedule builder is not open.<?php } ?>
			   			<div class="clear"></div>
					<?php endif; ?>
					<div id="hidehere"></div>
					<?php if ($datestart-$event['activitytime']-time() < 603148) { ?><p><i class="icon-warning-sign red"></i> Heads up! Class registration will be closing <?php echo date('l, F jS \a\t ga', ($datestart-$event['activitytime'])); ?>.</p><?php } ?>
					<div class="clear"></div>
					<div class="schedulelist twenty last">
						<p><strong>Class List</strong></p>
						<?php foreach ($classes as $class) { 
							$class['blocks'] = unserialize($class['blocks']);
							$class['__b'] = ''; 
							if (empty($class['blocks'])) { 
							} else { 
								$j=1;
								foreach ($class['blocks'] as $class['__block']) { 
									if ($j>1) $class['__b'] = $class['__b'].', ';	
									$class['__b'] = $class['__b'].'#'.$class['__block']; 
									$j++;
								}
							} 
							if (!isset($currentclasses[$class['id']]) && (float)$class['hardlimit'] > 0 && $this->activities_model->count_class_regs($session['id'],$class['id'],true) >= $class['hardlimit']) {
								$class['__disabled'] = true;
							} else {
								$class['__disabled'] = false;
							}
							
							?>
						<div class="camperhoverpopover item" onmouseover="classhoverin('<?php echo $class['__b']; ?>')" onmouseout="classhoverout('<?php echo $class['__b']; ?>')" data-toggle="popover" title="<?php echo $class['title']; ?>" data-placement="left" data-content="<?php echo $activities[$class['activity']]['description']; ?><br><br><strong>Cost/Supplies:</strong> <?php echo ($class['preorder'] == '0') ? 'None': '$'.$class['preorder']; ?><br><strong>Location:</strong> <?php echo $class['location']; ?><br><strong>Open Spots:</strong> <?php echo $this->activities_model->count_class_openings($session['id'],$class['id']); ?>">
						<?php if ($class['__disabled']) : ?>
						<input type="checkbox" disabled="disabled" id="class<?php echo $class['id']; ?>" class="notallowed" /><label for="class<?php echo $class['id']; ?>" class="notallowed"><?php echo $class['title']; ?></label></div>
						<?php else : ?>
						<input type="checkbox" <?php if ($class['__disabled']) echo 'disabled="disabled"'; ?> name="cb[<?php echo $activities[$class['activity']]['id']; ?>]" value="<?php echo $class['id']; ?>" id="class<?php echo $class['id']; ?>" onchange="classchange('<?php echo $class['__b']; ?>', this, 'class<?php echo $class['id']; ?>', '<?php echo $class['title']; ?>',<?php echo $class['activity']; ?>, <?php echo $class['id']; ?>);"><label for="class<?php echo $class['id']; ?>"><?php echo $class['title']; ?></label></div>
						<?php endif; /* end if disabled */ ?>
						<?php } ?>
					</div>
					<div class="scheduletable eighty last">
						<p><strong>Schedule Grid</strong></p>
						<?php if (is_array($periods) && !empty($periods['periods'])) { 
							if ($event['activitytype'] == 'day') { // Single Day Event ?>
							<table class="table table-condensed ">
								<thead><tr><th>Period</th><th><?php echo date('F j, Y', $sessionstart); ?></th></tr></thead>
								<tbody>
								<?php foreach ($periods['periods'] as $p) { ?>
									<tr>
										<td><?php echo $p['label']; ?></td>
										<td id="<?php echo 'A'.$p['id']; ?>" class="classblock"></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						<?php } else { // Multi day or week event ?>
							<table class="table table-condensed">
								<thead><tr><th>Period</th><?php foreach ($periods['days'] as $d) { ?><th><?php echo $d['label']; ?></th><?php } ?></tr></thead>
								<tbody>
									<?php foreach ($periods['periods'] as $p) { ?>
									<tr>
										<td><strong><?php echo $p['label']; ?></strong></td>
										<?php foreach ($periods['days'] as $d) { ?>
										<td id="<?php echo ucfirst($d['id']).$p['id']; ?>" class="classblock"></td>
										<?php } ?>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						<?php } /* end multiday if */ } else { ?>
						<p><i class="icon-remove red"></i> <strong>No periods!</strong> Registration doesn't work without periods... We'll get back to you.</p>
						<?php } ?>
						<p class="right"><input type="submit" value="Save Changes &rarr;" data-loading-text="Saving changes..." onclick="$(this).button('loading');" class="btn teal" /> <?php echo anchor(uri_string(), 'Reset','class="btn tan"'); ?></p>
	
					</div>
					</form>
					<div class="clear"></div>
					<p><?php if ($event['activitytime'] > 0) { ?><i class="icon-info-sign blue"></i> <strong>Note!</strong> The schedule builder will <strong>close on <?php $datestart = (isset($session['datestart']) && !empty($session['datestart'])) ? $session['datestart']: $event['datestart']; echo date('l, F jS \a\t g:ia', ($datestart-$event['activitytime'])); ?></strong> so we can prepare class schedules. Be sure to make all and any changes before that time.<?php } ?></p>
				<?php } ?>
				<div class="clear"></div>
				<h2 class="section">Actions</h2>
				<div class="quarter">
					<h3>Reports</h3>
					<p>Export a PDF of <?php echo $member['name']; ?>'s schedule. You can choose to include the costs for the participant as well. This will not include any unit-level options or discounts.</p>
					<p><?php echo anchor('api/v1/rosters/schedule/'.$roster['id'].'.pdf', '<i class="icon-file-text"></i> Schedule', 'class="btn btn-small tan"'); ?> or <?php echo anchor('api/v1/rosters/invoice/'.$roster['id'].'.pdf', '<i class="icon-file-text"></i> Schedule + Invoice', 'class="btn btn-small tan"'); ?></p>
				</div>
				<div class="half last">
					<h3>Special Requests</h3>
					<p><strong>Allergies</strong>: <?php echo (empty($member['allergies'])) ? 'None': $member['allergies']; ?><br />
					<strong>Dietary Restrictions</strong>: <?php echo (empty($member['diet'])) ? 'None': $member['diet']; ?><br />
					<strong>Medical Considerations</strong>: <?php echo (empty($member['medical'])) ? 'None': $member['medical']; ?><br />
					<strong>Notes</strong>: <?php echo (empty($member['notes'])) ? 'None': $member['notes']; ?></p>
					<p><?php echo anchor('unit/members/'.$member['id'], 'Edit &rarr;', 'class="btn tan"'); ?></p>
				</div>

				<div class="clear"></div>
				<?php if ($hasdiscounts) : ?>
				<?php echo form_open(uri_string());?>
				<input type="hidden" name="roster" value="<?php echo $roster['id']; ?>" />
				<input type="hidden" name="updatediscounts" value="1" />
				<h2 id="discounts" class="section">Discounts</h2>

			   	<p>These are all of the discounts for <?php echo $event['title']; ?> that apply to individual participants. Select discounts that apply to your unit, discounts may require council verification. When verified, the discounts will be applied and visible in the finances section.</p>
			   	<div class="clear"></div>
			   	<?php foreach ($discounts as $o) : if ($o['individual']) : ?>
					<?php if ($o['checkbox'] == 1) { ?><div class="camperform float cbl" style="width:60%;"><input type="checkbox" class="cbl mdcb" <?php if(isset($roster['discounts'][$o['id']]['checkbox'])) { ?> checked="checked"<?php } ?> name="discounts[<?php echo $o['id']; ?>][checkbox]" id="foc<?php echo $o['id']; ?>" /><label for="foc<?php echo $o['id']; ?>" class="cbl" ><?php echo $o['title']; ?></label><small><?php echo $o['description']; ?></small></div><?php } ?>
					<?php if ($o['value'] == 1) { ?><div class="camperform float " style="width: 30%"><input type="text" class="mdvalue" name="discounts[<?php echo $o['id']; ?>][value]" id="fov<?php echo $o['id']; ?>" value="<?php if(isset($roster['discounts'][$o['id']]['value'])) { echo $roster['discounts'][$o['id']]['value']; } ?>" placeholder="none" /><label for="fov<?php echo $o['id']; ?>"><?php echo $o['title']; ?></label></div><?php } ?>
				<?php endif; endforeach; ?><!-- End Custom Options -->
				<div class="clear"></div>
				<p><input type="submit" value="Save Changes &rarr;" data-loading-text="Saving changes..." onclick="$(this).button('loading');" class="btn teal" /> <?php echo anchor(uri_string(), 'Reset','class="btn tan"'); ?></p>
			   	<div class="clear tall"></div>
			   	<strong>Unit Discounts and Options</strong><br />
		   		<p>Only options and discounts applying to individual participants are shown here. Head back to extras page to manage your unit options and discounts.</p>
		   		<p><?php echo anchor('registrations/'.$reg['id'].'/details', 'View Unit Discounts &rarr;', 'class="btn btn-small tan"'); ?></p>
				<div class="clear"></div>
				<?php echo form_close();?> 

				<?php endif; ?>

				
			</div><!-- /.threequarter -->
		</div>
	</article>
