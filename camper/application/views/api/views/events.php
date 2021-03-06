<?php 

/* 
 * Camper API - List of Events as HTML
 *
 * This is the file that exports the bootstrap based accordion for the camps website.
 * Javascript and CSS dependencies are not included.
 *
 * File: /application/views/api/views/events.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
?>
			<div class="accordion" id="camper-events">
			  	<?php 
			  	$timestamp = time();
			  	$timestamp = $timestamp - 1209600;
			  	$this->load->helper('string');
			  	$key   = random_string('alnum', 8);

			  	$i=1;
			  	$f=false;
			  	$sessions = false;
			  	foreach ($events as $event):
				  	if ($event['dateend']) { $date = $event['dateend']; $f=true; } else { $date = $event['datestart']; }
				  	if ($date < $timestamp) {
					  	$pastflag=true;
				  	} elseif ($event['open'] == '0') {
				  		$closedflag=true;
				  	} else {
				  		// Get our sessions
						unset($sessions);
						$sessions = $this->register_model->get_sessions($event['id']); 
						$sessionscount = count($sessions);
			  	?>
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#upcoming" href="#upcoming<?php echo $key.$i; ?>">
							<i class="icon-plus"></i> <?php echo $event['title'];?>
							<div class="right eleven"> <?php echo $event['eventtype'];?> &nbsp; | &nbsp; <?php echo (empty($event['dateend'])) ? date('F j, Y', $event['datestart']) : date('F j', $event['datestart']).date(' - F j, Y', $event['dateend']); ?></div>
						</a>
					</div>
					<div id="upcoming<?php echo $key.$i; ?>" class="accordion-body collapse ">
						<div class="accordion-inner">
							<h3><?php echo $event['title'];?></h3>
							<div class="desc left">
								<p><?php echo $event['description'];?></p>
								<?php if ($sessionscount > 1) : ?>
								<p><strong>Event Type:</strong> <?php echo $event['eventtype'];?><br />
								<strong>Location:</strong> <?php echo $event['location'];?><br />
								<strong><?php echo $event['sessiontitle']; ?>s:</strong> <?php echo (!$sessions) ? 'None' : $sessionscount; ?></p>
								<?php else : ?><p><?php echo ($sessions[0]['open'] == '1' && $sessions[0]['count'] < $sessions[0]['limitsoft']) ? anchor("registrations/set/".$event['id'], 'Register &rarr;', 'class="btn blue"') : '<div class="alert">Registration is closed since this event is full</div>'; ?></p><?php endif; ?>
							</div>
							<div class="details left">
							<?php if ($sessionscount == 1) : ?><p>
									<strong>Event Type:</strong> <?php echo $event['eventtype'];?><br />
									<strong>Location:</strong> <?php echo $event['location'];?><br />
									<strong>Dates:</strong> <?php echo (empty($event['dateend'])) ? date('F j, Y', $event['datestart']) : date('F j', $event['datestart']).date(' - F j, Y', $event['dateend']); ?><br />
									<strong>Openings:</strong> <?php if ($sessions[0]['open'] == '1' && $sessions[0]['count'] < $sessions[0]['limitsoft']) {
								  				echo $sessions[0]['limitsoft']-$sessions[0]['count']; ?> Opening<?php if (($sessions[0]['limitsoft']-$sessions[0]['count']) > 1) echo 's'; ?><?php
									  			} elseif ($sessions[0]['open'] == '1' && $sessions[0]['count'] >= $sessions[0]['limitsoft']) { ?>This event is <strong>full</strong><?php
									  			} else { ?>Registration is <strong>not</strong> open <?php } ?><br />
									<strong>Cost:</strong> <?php if (!empty($sessions[0]['costadult']) && !empty($sessions[0]['cost']) && $sessions[0]['cost'] !== '0.00' && $sessions[0]['costadult'] !== '0.00') { ?>Youth $<?php echo $sessions[0]['cost']; ?> / Adults $<?php echo $sessions[0]['costadult']; 
												} elseif (!empty($sessions[0]['cost']) && empty($sessions[0]['costadult']) ) { ?> Youth $<?php echo $sessions[0]['cost']; 
												} elseif (empty($sessions[0]['cost']) && !empty($sessions[0]['costadult']) ) { ?> Adult $<?php echo $sessions[0]['costadult']; } else { echo 'This event has no registration fee.'; } ?>
								</p>
							<?php else :  
								// Groups Setup, if needed
								if (isset($event['groups']) && $event['groups'] !== '') {
									$groups = unserialize($event['groups']);
									//show_error(print_r($data['groups']));
									if ($groups['enabled'] !== '1') { $groups = false; }
								} else {
									$groups = false;
								}
								if ($groups['show'] == '1') { ?>
<!-- Start Groups View -->
								<table class="table table-condensed">
									<thead>
										<tr><th><?php echo $event['sessiontitle']; ?>s</th><th>Cost <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Cost" data-placement="top" data-content="The cost is the registration fee for the event. Some events will have a different fee for adults. <br><br><strong>Y</strong> / A: The bold number is the youth fee, adult fee is adjacent.<br><strong>Y</strong>: This means there is only a youth fee.<br><strong>A</strong>: This means there is only an adult fee."></i></th><th><?php echo $groups['title']; ?>s (openings) <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="<?php echo $groups['title']; ?>" data-placement="top" data-content="<?php echo $groups['desc']; ?><br /><br /><strong>About Openings</strong>: Many events limit the number of participants. You can register your unit as long as there are enough openings for your unit. Some events have a higher limit for units that have already registered. The numbers here are the official registration numbers. "></i></th></tr>
									</thead>
									<tbody>
										<?php $j=1; foreach ($sessions as $onesession): $sessioncount = $this->shared->count_session($onesession['id']); ?> 
							      	    <tr>
							      	    	<td><strong><?php echo (empty($onesession['title'])) ? $event['sessiontitle'].' '.$j : $onesession['title']; ?></strong><br />
							      	    	<?php echo (empty($onesession['dateend'])) ? (empty($onesession['datestart'])) ? date('F j, Y', $event['datestart']) : date('F j, Y', $onesession['datestart']) : date('F j', $onesession['datestart']).date(' - F j, Y', $onesession['dateend']); ?></td>
							      	    	<td><?php if (!empty($onesession['costadult']) && !empty($onesession['cost'])) { ?><strong>$<?php echo $onesession['cost']; ?></strong> / $<?php echo $onesession['costadult']; 
												} elseif (!empty($onesession['cost']) && empty($onesession['costadult']) ) { ?> Y $<?php echo $onesession['cost']; 
												} elseif (empty($onesession['cost']) && !empty($onesession['costadult']) ) { ?> A $<?php echo $onesession['costadult']; } else { echo '-'; } ?></td>

											<td><?php foreach ($groups['groups'] as $g) { 
												// Setup
												if ($onesession['open'] == '1') {
													$g['__count'] = $this->shared->count_group($g['id'],$onesession['id'], true); 
													//show_error(serialize($g['__count']));
													$g['__limit'] = (isset($g['limit']) && $g['limit'] !== '') ? $g['limit'] : false;
													$g['__limit'] = (isset($g['softlimit']) && $g['softlimit'] !== '') ? $g['softlimit'] : $g['__limit'];
													if ($g['__limit'] == 0 || $g['__limit'] == false) {
														// Limit is 0
														$g['__openings'] = '';
														$g['__message'] = '';
														$g['__button'] = anchor('registrations/set/'.$event['id'].'/'.$onesession['id'].'/'.$g['id'], 'Register &rarr;', 'class="btn btn-small blue camperhoverpopover" data-toggle="popover" data-placement="top" data-content="There is no registration limit for this '.strtolower($event['sessiontitle']).'<br /><br />Click to register, you can set your numbers on the next page."');
													} elseif ($g['__count'] < $g['__limit']) {
														// Limit not reached
														$g['__openspots'] = $g['__limit'] - $g['__count'];
														$g['__openings'] = ' (<strong data-toggle="tooltip" title="'.$g['__openspots'].' Openings">'.$g['__openspots'].'</strong>)';
														$g['__message'] = '';
														$g['__button'] = anchor('registrations/set/'.$event['id'].'/'.$onesession['id'].'/'.$g['id'], 'Register &rarr;', 'class="btn btn-small blue camperhoverpopover" data-toggle="popover" data-placement="top" data-content="<strong>'.$g['__openspots'].'</strong> Openings!"');
													} else {
														// Group Full
														$g['__message'] = '<strong class="camperhoverpopover" data-toggle="popover" data-placement="top" data-content="This '.strtolower($event['sessiontitle']).' is full">Full</strong>';
														$g['__button'] = '';
														$g['__openings'] = '';
													}
													
												} else {
													// Session is closed
													$g['__message'] = '<strong class="camperhoverpopover" data-toggle="popover" data-placement="top" data-content="Registration is <strong>not</strong> open for this '.strtolower($event['sessiontitle']).'">Closed</strong>';
													$g['__button'] = '';
													$g['__openings'] = '';
												}
												?>
												<span class="camperhoverpopover" data-toggle="popover" title="<?php echo $g['title']; ?>" data-placement="top" data-content="<?php echo $g['desc']; ?>"><?php echo $g['title']; ?></span><?php echo $g['__openings']; ?><span class="right"><?php echo $g['__message']; ?> <?php echo $g['__button']; ?></span><div class="clear"></div>
												<?php } // endforeach $groups as $g ?>
											</td>
							      	    </tr>
									 	<?php $j++; endforeach; ?>
									</tbody>
								</table>
<!-- End Groups View -->
								<?php } else { ?>
<!-- Start Normal View -->
								<table class="table table-condensed">
									<thead>
										<tr><th><?php echo $event['sessiontitle']; ?>s</th><th>Dates</th><th>Cost <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Youth" data-placement="top" data-content="The cost is the registration fee for the event. Some events will have a different fee for adults. <br><br><strong>Y</strong> / A: The bold number is the youth fee, adult fee is adjacent.<br><strong>Y</strong>: This means there is only a youth fee.<br><strong>A</strong>: This means there is only an adult fee."></i></th><th>Openings <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="Openings" data-placement="top" data-content="Most events limit the number of participants. You can register your unit as long as there are enough openings for your unit. Some events have a higher limit for units that have already registered. The numbers here are the official registration numbers. "></i></th></tr>
									</thead>
									<tbody>
										<?php $j=1; foreach ($sessions as $onesession): $sessioncount = $this->shared->count_session($onesession['id']); ?> 
							      	    <tr>
							      	    	<td><?php echo (empty($onesession['title'])) ? $event['sessiontitle'].' '.$j : $onesession['title']; ?></td>
							      	    	<td><?php echo (empty($onesession['dateend'])) ? (empty($onesession['datestart'])) ? date('F j, Y', $event['datestart']) : date('F j, Y', $onesession['datestart']) : date('F j', $onesession['datestart']).date(' - F j, Y', $onesession['dateend']); ?></td>
							      	    	<td><?php if (!empty($onesession['costadult']) && !empty($onesession['cost'])) { ?><strong>$<?php echo $onesession['cost']; ?></strong> / $<?php echo $onesession['costadult']; 
												} elseif (!empty($onesession['cost']) && empty($onesession['costadult']) ) { ?> Y $<?php echo $onesession['cost']; 
												} elseif (empty($onesession['cost']) && !empty($onesession['costadult']) ) { ?> A $<?php echo $onesession['costadult']; } else { echo '-'; } ?></td>
								  			<td><?php if ($onesession['open'] == '1' && $sessioncount['total'] < $onesession['limitsoft']) {
								  				echo $onesession['limitsoft']-$sessioncount['total']; ?> Opening<?php if (($onesession['limitsoft']-$sessioncount['total']) > 1) echo 's'; ?> <div class="right"><?php echo anchor('registrations/set/'.$event['id'].'/'.$onesession['id'], 'Register &rarr;', 'class="btn btn-small blue"'); ?></div><?php
									  			} elseif ($onesession['open'] == '1' && $sessioncount['total'] >= $onesession['limitsoft']) { ?>This <?php echo strtolower($event['sessiontitle']);?> is <strong>full</strong><?php
									  			} else { ?>Registration is <strong>not</strong> open for this <?php echo strtolower($event['sessiontitle']); } ?></td>
							      	    </tr>
									 	<?php $j++; endforeach; ?>
									</tbody>
								</table>
<!-- End Normal View -->
								<?php } ?>

							<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			 	<?php $i++; $f=false; } endforeach;?>
			 	<?php if ($i==1) { ?>
			 	<h3>There are no upcoming events in Camper that matched your request. </h3>
			 	<?php } ?>
			</div><!-- /.accordion -->
