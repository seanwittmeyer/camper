<?php 

/* 
 * Camper Admin / Reports / New Report View
 *
 * This is where the user can create a new report.
 *
 * File: /application/views/admin/reports/new.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 
?>	<div class="subnav">
		<div class="container">
			<h2>Reports</h2>
			<nav class="campersubnav">
				<li class="active" data-toggle="tooltip" title="New Report"><?php echo anchor("reports/new", '<i class="icon-plus"></i>');?></li>
				<li class=""><?php echo anchor("reports", 'All Reports');?></li>
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
			<h2>New Report</h2>
			<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
			<div class="threequarter">
				<p>Reports are a way to get information out of Camper in a usable format. Right now, we only support online and downloadable CSV files but we'll be adding other options like PDF files soon.</p>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<h2 class="section">1. Title and Data Set</h2>
			<div class="threequarter">

				<?php if ($step == 1) : ?>

	   			<div class="camperform float last" style="width: 60%"><input type="text" name="title" id="ftitle" value="" placeholder="My Report" data-toggle="tooltip" title="Enter a title that will be displayed on the reports page." /><label for="ftitle">Report Title</label></div>
				<div class="clear"></div>
	   			<div class="camperform float " style="width: 60%" data-toggle="tooltip" title="Choose a data set as the source. This will be the information you are exporting from Camper, each base set will include some data from other sources.">
	   				<select id="fbase" name="source">
	   					<optgroup label="Registrations">
							<option value="regs_full">Event Registrations</option>
							<option value="rosters_full" disabled="disabled">Unit Rosters (members registered for an event) (soon)</option>
							<option value="payments">Payments</option>
	   					</optgroup>
	   					<optgroup label="Users and Units">
							<option value="users">Users</option>
							<option value="units">Units</option>
							<option value="members">Unit Members</option>
	   					</optgroup>
	   					<optgroup label="Events and Activities">
							<option value="events_full" disabled="disabled">Events (soon)</option>
							<option value="sessions" disabled="disabled">Sessions (soon)</option>
							<option value="activities" disabled="disabled">Activities (soon)</option>
							<option value="classes_full" disabled="disabled">Classes (soon)</option>
							<option value="classregs_full" disabled="disabled">Class/Activity Registrations (soon)</option>
	   					</optgroup>
					</select>
					<label for="ftype">Source</label>
				</div>
				<div class="clear"></div>
   				<p>Head to step 2 to choose columns and to filter your report.</p>
   				<input type="submit" name="submit" value="Next Step &rarr;" class="btn teal" data-loading-text="Preparing the report builder..." onclick="$(this).button('loading');" /> <input type="reset" name="reset" value="Reset" class="btn tan"  />	
   				<input type="hidden" id="fstep" name="step" value="2" />

   				<?php elseif ($step > 1 && isset($report)) : ?>

   				<div class="camperform float last" style="width: 60%"><span><?php echo $report['title']; ?></span><label>Title</label></div>
   				<div class="clear"></div>
   				<div class="camperform float last" style="width: 60%"><span><?php echo $source['title']; ?></span><label>Data Set</label></div>

   				<?php endif; ?>

				<div class="clear"></div>
			</div>
			<div class="clear"></div>
			<h2 class="section">2. Customize Report</h2>
			<div class="quarter">
				<h4>Columns</h4>
				<?php $i=1; foreach ($source['data']['columns'] as $slug=>$title) : ?>
				<input type="checkbox" id="fc<?php echo $i; ?>" style="float: left;" name="columns[<?php echo $slug; ?>]" checked="checked" /> <label for="fc<?php echo $i; ?>" style="padding-left: 20px;"><?php echo $title; ?></label> <br />
				<?php $i++; endforeach; ?>
			</div>
			<div class="quarter">
				<h4>Where</h4>
				<?php $i=1; foreach ($source['data']['where'] as $slug=>$title) : ?>
				<input type="checkbox" id="fw<?php echo $i; ?>" style="float: left;" name="columns[<?php echo $slug; ?>]" checked="checked" /> <label for="fw<?php echo $i; ?>" style="padding-left: 20px;"><?php echo $title; ?></label> 
				<br />
	   				<select id="fbase" name="source">
	   					<optgroup label="Selected">
							<option value="none" disabled="disabled">None</option>
	   					</optgroup>
	   					<optgroup label="All Values">
							<?php foreach ($this->report_model->get_distinct_values('eventregs',$slug) as $item) : ?>
							<option value="<?php echo $item; ?>"><?php echo $item; ?></option>
							<?php endforeach; ?>
	   					</optgroup>
					</select>
				<br />
				<?php $i++; endforeach; ?>
			</div>
			<div class="half last">
				<h4>Preview</h4>
   				<p>Enable columns you wish to be included in your report and hit "Update Preview" to see the data this report will generate. </p>
   				<input type="submit" name="submit" value="Update Preview" class="btn teal" data-loading-text="Updating the report preview..." onclick="$(this).button('loading');" />	
   				<?php 
   					if ($step == 2 && isset($report)) : 
   					
   					print_r('welcome, you made it.');
   					
   				?>

	   			<?php endif; ?>

			</div>
			<div class="clear"></div>
			<h2 class="section">3. Save and Build Report</h2>
			<div class="threequarter">
				<?php if ($step == 2 && isset($report)) : ?>
   				<p>Once you are happy with your report settings, hit save and you will have the option to run and export it into the .csv format for Microsoft Excel. You will be able to see your new report on the "Reports" page where you can re-run it at anytime.</p>
   				<input type="submit" name="submit" value="Save Report &rarr;" class="btn teal" data-loading-text="Saving and preparing the report..." onclick="$('#fstep').val('3'); $(this).button('loading');" />	
	   			<?php endif; ?>

			</div>
			<div class="clear"></div>
		</div>
	<?php echo form_close(); ?>
	</article>
					
