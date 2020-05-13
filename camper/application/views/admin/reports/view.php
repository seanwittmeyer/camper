<?php 

/* 
 * Camper Admin / Reports / View Single Report View
 *
 * This view shows the single report.
 *
 * File: /application/views/admin/reports/view.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 
?>	<div class="subnav">
		<div class="container">
			<h2>Reports</h2>
			<nav class="campersubnav">
				<li class="" data-toggle="tooltip" title="New Report"><?php echo anchor("reports/new", '<i class="icon-plus"></i>');?></li>
				<li class="active"><?php echo anchor("reports", 'All Reports');?></li>
			</nav>
		</div>
	</div>
	<script>
	$(document).ready(function() {
	});
	</script>
	<article class="textsection">
	<?php echo form_open(uri_string());?>
		<input type="hidden" name="user" value="<?php echo $user->id; ?>" />
		<div class="container">
			<h2>View Report</h2>
			<div class="threequarter">
				<p>Reports are a way to get information out of Camper in a usable format. Right now, we only support online and downloadable CSV files but we'll be adding other options like PDF files soon.</p>
				<p><?php echo anchor('reports/new', 'Create a new report &rarr;', 'class="btn tan"'); ?></p>
				<div class="clear"></div>
			</div>
			<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
		</div>
		<div class="container">
			<ul id="detailstabs" class="teal">
				<li class="active"><a href="#allreports" data-toggle="tab">All Reports</a></li>
				<li class=""><a href="#myreports" data-toggle="tab">My Reports</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade in active" id="allreports">
					<div class="container">
						<h2>All Reports</h2>
						<p>These are all of the reports created in Camper, click on any of them to see details or use the tools to download/export the data.</p>
						<div class="clear"></div>
						<?php foreach ($reports as $report) : ?>
							<p><?php echo anchor('reports/'.$report['id'], '<strong>Report # '.$report['id'].'</strong>'); ?></p>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="tab-pane fade" id="myreports">
					<div class="container">
						<h2>My Reports</h2>
						<p>These are The reports you have created in Camper. You can view the reports and rerun them to get the latest data.</p>
						<div class="clear"></div>
						<?php foreach ($reports as $report) : if ($report['user'] == $user->id) : ?>
							<p><?php echo anchor('reports/'.$report['id'], '<strong>Report # '.$report['id'].'</strong>'); ?></p>
						<?php endif; endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	<?php echo form_close(); ?>
	</article>
					
