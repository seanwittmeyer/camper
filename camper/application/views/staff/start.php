<?php 

/* 
 * Camper At Camp / Choose Event View
 *
 * This page lets a staff user choose an event or session. 
 *
 * File: /application/views/staff/chooseevent.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 

?>	<h2>Home</h2>
	<p>Stay on top of managing <?php echo $event['title']; ?> with reports on who is participating, what they are doing, and prepare with reports.<br />&nbsp;</p>
	<h3>Participants &amp; Units</h3>
	<p>See all of the units and participants participating in this event.</p>
	<p>Search for unit or participant will show here</p>
	<p>
		<?php echo anchor('atcamp/'.$event['id'].'/'.$session['id'].'/regs', $session['nicetitle'].' Registrations &rarr;', 'class="btn red"'); ?><br />
		<?php echo anchor('api/v1/reports/checkin/session/'.$session['id'].'.pdf', '<i class="icon-file-text"></i> '.$session['nicetitle'].' Check-in Forms', 'class="btn tan"'); ?><br />
		<?php echo anchor('api/v1/unitroster/session/'.$session['id'].'.pdf', '<i class="icon-file-text"></i> '.$session['nicetitle'].' Rosters', 'class="btn tan"'); ?> *in the works<br />
	</p>

	<h3>Activities &amp; Classes</h3>
	<p>Keep track and manage the activities and classes happening during this event.</p>
	<p>Search for classes will show here</p>
	<p>
		<?php echo anchor('atcamp/'.$event['id'].'/'.$session['id'].'/classes', $session['nicetitle'].' Classes &rarr;', 'class="btn red"'); ?> <br />
		<!--<?php echo anchor('api/v1/bluecard/session/'.$session['id'].'.pdf', '<i class="icon-file-text"></i> Blue Cards for All Classes', 'class="btn tan"'); ?> *in the works<br />-->
		<?php echo anchor('api/v1/classroster/session/'.$session['id'].'.pdf', '<i class="icon-file-text"></i> Rosters for All Classes', 'class="btn tan"'); ?><br />&nbsp;
	</p>
	<h3>Reports</h3>
	<p>Create and print reports for allergies, dietary restrictions and allergies, preorders and more.</p>
	<p>
		<?php echo anchor('api/v1/reports/birthdays/'.$session['id'].'.pdf', '<i class="icon-file-text"></i> Birthdays this '.strtolower($event['sessiontitle']).' &rarr;', 'class="btn tan"'); ?><br />
		<?php echo anchor('api/v1/reports/preorders/'.$session['id'].'.pdf', '<i class="icon-file-text"></i> Preordered Supplies (requested) &rarr;', 'class="btn tan"'); ?> <br />
		<?php echo anchor('api/v1/reports/preorders/all/'.$session['id'].'.pdf', '<i class="icon-file-text"></i> Preordered Supplies (all) &rarr;', 'class="btn tan"'); ?> <br />
		<?php echo anchor('api/v1/reports/conditions/'.$session['id'].'.pdf', '<i class="icon-file-text"></i> Allergies and Dietary Restrictions &rarr;', 'class="btn tan"'); ?><br />
		<?php echo anchor('atcamp/'.$event['id'].'/'.$session['id'].'/reports', 'Custom Reports &rarr;', 'class="btn red"'); ?> *in the works<br />
	</p>
	<h3>Admin Access <i class="icon-lock"></i></h3>
	<p>Access the admin side of Camper to manage users, units, and events.</p>
	<?php echo anchor($this->ion_auth->is_admin() ? 'dashboard':'signout', 'Admin Area &rarr;', 'class="btn tan"'); ?>