<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper / Leader / Single Roster Schedule + Invoice View
 *
 * This page is the html output for the schedule/invoice view for single rosters.
 * It is designed to be stand alone for PDF generation.
 *
 * File: /application/views/pdf/roster_schedule.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 10 1909)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
/* 
 * Function & Resource Grab
 */	


?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<!--

	 Camper Schedule View (<?php echo $this->config->item('camper_version'); ?>) 
	 http://camperapp.org/
	 Copyright (c) 2001-2014 Sean Wittmeyer, Zilifone
	 Page Contact:  Sean Wittmeyer
	 sean[at]zilifone[dot]net

-->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<style>
	/* Default style definitions */
	/* Notes
	-----------------------------------------------------------------------*/
	.note_form {  display: none;  }
	/* Page
	-----------------------------------------------------------------------*/
	.page { background-color: white; padding: 20px; font-size: 0.7em; margin-bottom: 15px; margin-right: 5px;  }
	.page table.header td h1 {  margin: 0px;  }
	.page table.header {  border-bottom: 1px solid black;  }
	.page h1 { text-align: center; color: black; font-style: normal; font-size: 2em;  }
	.page h2 { text-align: center; color: black;  }
	.page h3 { color: black; font-size: 1em;  }
	.page p {  text-align: justify; font-size: 1em;  }
	.page em { font-weight: bold; font-style: normal; text-decoration: underline; margin-left: 1%; margin-right: 1%; }
	.money_table { width: 85%; margin-left: auto; margin-right: auto;  }
	.money { text-align: right; padding-right: 20px;  }
	.money_field { text-align: right; padding: 0px 15px 5px 15px; font-weight: bold;  }
	.total_label { border-top: 2px double black; font-weight: bold;  }
	.total_field { border-top: 2px double black; text-align: right; padding: 0px 15px 5px 15px; font-weight: bold;  }
	.written_field {  border-bottom: 0.1pt solid black;  }
	.page .indent * { margin-left: 4em; }
	.checkbox {  border: 1px solid black; padding: 1px 2px; font-size: 7px; font-weight: bold;  }
	table.fax_head {  width: 100%; font-weight: bold; font-size: 1.1em; border-bottom: 1px solid black;  }
	/* Sales-agreement specific
	-----------------------------------------------------------------------*/
	table.sa_signature_box {  margin: 2em auto 2em auto;  }
	table.sa_signature_box tr td {  padding-top: 1.5em; vertical-align: top; white-space: nowrap;  }
	.special_conditions {  font-style: italic; margin-left: 2em;  white-space: pre; font-weight: bold;  }
	.page h2 {  text-align: left;  }
	@page {  margin: 0.25in;  }
	/* General
	-----------------------------------------------------------------------*/
	body {  background-color: transparent; color: black; font-family: "verdana", "sans-serif"; margin: 0px; padding-top: 0px; font-size: 1em;  }
	@media print {  p { margin: 2px; }  }
	h1 { font-size: 1.1em; font-style: italic;  }
	h2 { font-size: 1.05em;  }
	img {  border: none;  }
	pre { font-family: "verdana", "sans-serif"; font-size: 0.7em;  }
	ul { list-style-type: circle; list-style-position: inside; margin: 0px; padding: 3px;  }
	li.alpha { list-style-type: lower-alpha; margin-left: 15px;  }
	p { font-size: 0.8em;  }
	a:link,
	a:visited { /* font-weight: bold;  */ text-decoration: none; color: black;  }
	a:hover { text-decoration: underline;  }
	#body {  padding-bottom: 2em; padding-top: 5px;  }
	#body pre {  }
	.center { text-align: center;  }
	.right { text-align: right;  }
	#money { text-align: right; padding-right: 20px;  }
	/* Footer
	-----------------------------------------------------------------------*/
	#footer { color: black;  }
	#copyright {  padding: 5px; font-size: 0.6em; background-color: white;  }
	#footer_spacer_row { width: 100%;  }
	#footer_spacer_row td { padding: 0px; border-bottom: 1px solid #000033; background-color: #F7CF07; height: 2px; font-size: 2px; line-height: 2px;  }
	#logos { padding: 5px; float: right;  }
	/* Section Header
	-----------------------------------------------------------------------*/
	#section_header { text-align: center;  }
	#job_header {  text-align: left; background-color: white; margin-left: 5px; padding: 5px; border: 1px dashed black;  }
	#job_info { font-weight: bold;  }
	.header_details td { font-size: 0.6em;  }
	.header_label { padding-left: 20px;  }
	.header_field { padding-left: 5px; font-weight: bold;  }
	/* Content
	-----------------------------------------------------------------------*/
	#content { padding: 0.2em 1% 0.2em 1%; min-height: 15em;  }
	.page_buttons { text-align: center; margin: 3px; font-size: 0.7em; white-space: nowrap; font-weight: bold; width: 74%;  }
	.link_bar { font-size: 0.7em; text-align: center; margin: auto; /*  white-space: nowrap; */  }
	.link_bar a { white-space: nowrap; font-weight: bold;  }
	.page_menu li { margin: 5px; font-size: 0.8em;  }
	/* Detail
	-----------------------------------------------------------------------*/
	.detail_table { border-top: 1px solid black; border-bottom: 1px solid black; padding: 3px; margin: 15px;  }
	.detail_head td { background-color: #ddd; color: black; font-weight: bold; padding: 3px; font-size: 0.75em; text-align: center;  }
	.detail_label {  padding: 3px; font-size: 0.75em;   width: 16%; border-top: 1px solid #fff; border-bottom: 1px solid #fff; background-color: #ddd;  }
	.detail_field {  width: 33%; font-size: 0.8em; color: ; text-align: center; padding: 3px;    }
	.detail_sub_table { font-size: 1em;  }
	.detail_spacer_row td { border-top: 1px solid white; border-bottom: 1px solid white; background-color: #999; font-size: 2px; line-height: 2px;  }
	#narrow { width: 50%;  }
	.operation { width: 1%;  }
	.summary_spacer_row { font-size: 0.1em;  }
	.bar {  border-top: 1px solid black;  }
	/* Forms
	-----------------------------------------------------------------------*/
	.form { border-top: 1px solid black; border-bottom: 1px solid black; margin-top: 10px;  }
	.form td { padding: 3px;  }
	.form th, .form_head td {  background-color: #ddd; border-bottom: 1px solid black; color: black; padding: 3px; text-align: center; font-size: 0.65em; font-weight: bold;  }
	.form_head a:link,
	.form_head a:visited { color: black;  }
	.form_head a:hover {  }
	.sub_form_head td { border: none; font-size: 0.9em; white-space: nowrap;  }
	.form input { color: black; background-color: white; border: 1px solid black; padding: 1px 2px 1px 2px; text-decoration: none; font-size: 1em;  }
	.form textarea { color: black; background-color: white; border: 1px solid black; font-size: 1em;  }
	.form select { color: black; background-color: white; font-size: 1em;  }
	.button, a.button {  color: black; background-color: white; border: 1px solid black; font-weight: normal; white-space: nowrap; text-decoration: none;  }
	a.button {  display: inline-block; text-align: center; padding: 2px;  }
	a.button:hover { text-decoration: none; color: black;  }
	.form_field { color: black; background-color: white; font-size: 0.7em;  }
	.form_label { color: black; background-color: #ddd; font-size: 0.7em; padding: 3px;  }
	/* .form_foot { background-color: #E5D9C3; font-size: 0.6em;  } */
	.form_foot td {  background-color: #ddd; border-bottom: 1px solid black; color: black; padding: 3px; text-align: center; font-size: 0.65em; font-weight: bold;  }
	.form_foot a:link,
	.form_foot a:visited { color: black;  }
	.form_foot a:hover { color: black;  }
	.no_border_input input { border: none;  }
	.no_wrap { white-space: nowrap;  }
	tr.row_form td {  white-space: nowrap;  }
	/* Wizards
	-----------------------------------------------------------------------*/
	.wizard { font-size: 0.8em; border-top: 1px solid black;  }
	#no_border { border: none;  }
	.wizard p { text-indent: 2%;  }
	.wizard td {  padding: 3px; /*  padding-left: 3px; padding-right: 3px; padding-bottom: 3px;*/  }
	.wizard input { color: black; background-color: white; border: 1px solid black; padding: 1px 2px 1px 2px; text-decoration: none;  }
	.wizard textarea { color: black; background-color: white; border: 1px solid black;  }
	.wizard select { color: black; background-color: white; border: 1px solid black;  }
	.wizard_head { color: black; font-weight: bold;  }
	.wizard_buttons { border-top: 1px solid black; padding-top: 3px;  }
	.wizard_buttons a { background-color: white; border: 1px solid black; padding: 2px 3px 2px 3px;  }
	/* List
	-----------------------------------------------------------------------*/
	.list_table,
	.notif_list_table { color: black; padding-bottom: 4px; background-color: white;  }
	.list_table td,
	.notif_list_table td {  padding: 3px 5px 3px 5px;  }
	.list_table input { color: black; background-color: white; border: 1px solid black; padding: 1px 2px 1px 2px; text-decoration: none;  }
	.list_head,
	.notif_list_head { font-weight: bold; background-color: #ddd; font-size: 0.65em;  }
	.list_head td,
	.notif_list_head td { border-top: 1px solid black; border-bottom: 1px solid black; color: black; text-align: center; white-space: nowrap;  }
	.list_head a:link,
	.list_head a:visited,
	.notif_list_head a:link,
	.notif_list_head a:visited { color: black;  }
	.list_head a:hover,
	.notif_list_head a:hover {  }
	.list_foot { font-weight: bold; background-color: #ddd; font-size: 0.65em;  }
	.list_foot td { border-top: 1px solid black; border-bottom: 1px solid black; color: black; text-align: right; white-space: nowrap;  }
	.sub_list_head td { border: none; font-size: 0.7em;  }
	.odd_row td { /*  background-color: #EDF2F7; border-top: 2px solid #FFFFff;*/ background-color: transparent; border-bottom: 0.9px solid #ddd; /* 0.9 so table borders take precedence */  }
	.even_row td { /*  background-color: #F8EEE4; border-top: 3px solid #FFFFff;*/ background-color: #f6f6f6; border-bottom: 0.9px solid #ddd;  }
	.spacer_row td {  line-height: 2px; font-size: 2px;  }
	.phone_table td { border: none; font-size: 0.8em;  }
	div.notif_list_text {  margin-bottom: 1px; font-size: 1.1em;  }
	.notif_list_row td.notif_list_job {  text-align: center; font-weight: bold; font-size: 0.65em;  }
	.notif_list_row td.notif_list_dismiss table td { text-align: center; font-size: 1em; border: none; padding: 0px 2px 0px 2px;  }
	.notif_list_row td {  padding: 5px 5px 7px 5px; border-bottom: 1px dotted #ddd; background-color: white; font-size: 0.6em;  }
	.notif_list_row:hover td { background-color: #ddd;  }
	/* Page
	-----------------------------------------------------------------------*/
	.page { border: none; padding: 0in; margin-right: 0.1in; margin-left: 0.1in; /*margin: 0.33in 0.33in 0.4in 0.33in; */ background-color: transparent;  }
	.page table.header h1{  font-size: 12pt;  }
	.page>h2,
	.page>p {  margin-top: 2pt; margin-bottom: 2pt;    }
	.page h2 {  page-break-after: avoid;  }
	.money_table { border-collapse: collapse; font-size: 6pt;  }
	/* Tree
	-----------------------------------------------------------------------*/
	.tree_div {  display: none; background-color: #ddd; border: 1px solid #333;  }
	.tree_div .tree_step_bottom_border {  border-bottom: 1px dashed #8B9DBE;  }
	.tree_div .button, .tree_row_table .button,
	.tree_div .no_button { width: 110px; font-size: 0.7em; padding: 3px; text-align: center;  }
	/* .tree_div .button a, .tree_row_table .button a { text-decoration: none; color: #114C8D;  } */ 
	.tree_row_desc {  font-weight: bold; font-size: 0.7em; text-indent: -10px;    }
	.tree_row_info { font-size: 0.7em; width: 200px;  }
	.tree_div_head a,
	.tree_row_desc a {  color: #000033; text-decoration: none;  }
	.tree_div_head {  font-weight: bold; font-size: 0.7em;  }
	/* Summaries
	-----------------------------------------------------------------------*/
	.summary { border: 1px solid black; background-color: white; padding: 1%; font-size: 0.8em;  }
	.summary h1 { color: black; font-style: normal;  }
	/* Sales-agreement specific
	-----------------------------------------------------------------------*/
	table.sa_signature_box {  margin: 2em auto 2em auto;  }
	table.sa_signature_box tr td {  padding-top: 1.25em; vertical-align: top; white-space: nowrap;  }
	.special_conditions {  font-style: italic; margin-left: 2em;  white-space: pre;  }
	.sa_head * {  font-size: 7pt;  }
	/* Change order specific
	-----------------------------------------------------------------------*/
	table.change_order_items {  font-size: 8pt; width: 100%; border-collapse: collapse; margin-top: 2em; margin-bottom: 2em;  }
	table.change_order_items>tbody {  border: 1px solid black;  }
	table.change_order_items>tbody>tr>th {  border-bottom: 1px solid black;  }
	table.change_order_items>tbody>tr>td {  border-right: 1px solid black; padding: 0.5em;  }
	td.change_order_total_col {  padding-right: 4pt; text-align: right;  }
	td.change_order_unit_col {  padding-left: 2pt; text-align: left;  }
	div.pagebreak { height: 0; clear: both; page-break-after: always; }
</style>
</head>
<body>
	<div id="body">
		<div id="section_header"></div>
		<div id="content"> 
			<div class="page" style="font-size: 7pt">
				<table style="width: 100%;" class="header">
					<tr>
						<td><h1 style="text-align: left"><?php echo $reg['unitid']['unittitle']; ?> - Scout Roster</h1><h2>Reg #<?php echo $reg['id']; ?></h2></td>
						<td><h1 style="text-align: right"><?php echo (empty($reg['session']['title'])) ? $reg['eventid']['sessiontitle'].' '.$reg['session']['sessionnum']: $reg['session']['title']; ?> 
						<?php if (isset($reg['eventid']['groups']) && $reg['eventid']['groups']['enabled'] == 1) echo '('.$reg['eventid']['groups']['groups'][$reg['group']]['title'].')'; ?></h1><h2 style="text-align: right;"><?php echo $reg['eventid']['title']; ?></h2></td>
					</tr>
				</table>
				<table style="width: 100%; font-size: 8pt;">
					<tr>
						<td><?php echo $reg['unitid']['unittype']; ?>: <strong><?php echo $reg['unitid']['unittype']; ?> <?php echo $reg['unitid']['number']; ?></strong></td>
						<td>Event: <strong><?php echo $reg['eventid']['title']; ?></strong></td>
					</tr>
					<tr>
						<td>City/State: <strong><?php echo $reg['unitid']['city']; ?>, <?php echo $reg['unitid']['state']; ?></strong></td>
						<td><?php echo $reg['eventid']['sessiontitle']; ?>: <strong><?php echo (empty($reg['session']['title'])) ? $reg['eventid']['sessiontitle'].' '.$reg['session']['sessionnum']: $reg['session']['title']; ?></strong></td>
					</tr>
					<tr>
						<td>Council: <strong><?php echo $reg['unitid']['council']; ?></strong> (<?php echo $reg['unitid']['district']; ?> District)</td>
						<td>Event Dates: <strong><?php //Prep
							// Dates prep
							if (empty($reg['session']['datestart'])) {
								if (empty($reg['eventid']['dateend'])) {
									$dates = date('F j, Y', $reg['eventid']['datestart']);
								} else {
									$dates = date('F j', $reg['eventid']['datestart']).date(' - F j, Y', $reg['eventid']['dateend']);
								} 
							} else {
								if (empty($reg['session']['dateend'])) {
									$dates = date('F j, Y', $reg['session']['datestart']);
								} else {
									$dates = date('F j', $reg['session']['datestart']).date(' - F j, Y', $reg['session']['dateend']);
								} 
							} echo $dates; ?></strong>
						</td>
					</tr>
					<tr>
						<td>Time of Registration: <strong><?php echo date('F j, Y \a\t g:ia', $reg['registerdate']['time']); ?></strong></td>
						<td><?php if (isset($reg['eventid']['groups']) && $reg['eventid']['groups']['enabled'] == 1) echo $reg['eventid']['groups']['title'].': <strong>'.$reg['eventid']['groups']['groups'][$reg['group']]['title'].'</strong>'; ?></td>
					</tr>
				</table>
			
				<!-- START Youth Roster -->
				<table class="change_order_items">
					<tr>
						<td colspan="2"><h1>Youth</h1></td>
						<td colspan="3"><h2>Please fill out all information completely and bring three (3) copies of this roster with you to camp.</h2></td>
					</tr>
					<tbody>
						<tr>
							<th style="width: 3%">#</th>
							<th style="width: 20%">Youth Name</th>
							<th>Address</th>
							<th>Phone Number</th>
							<th>Accident Insurance Policy #</th>
						</tr>
						<?php $adult = ($reg['unitid']['unittype'] == 'Ship' || $reg['unitid']['unittype'] == 'Crew') ? (31556926 * 21): (31556926 * 18); // 21 and 18 years in seconds
							$i=1; foreach ($rosters as $roster) : 
							if (time()-$roster['member']['dob'] >= $adult) continue; ?>
						<tr class="<?php echo ($i % 2 == 0) ? 'even':'odd'; ?>_row">
							<td style="text-align: center"><?php echo $i; ?></td>
							<td><?php echo $roster['member']['name']; ?></td>
							<td><?php echo $roster['member']['address']; ?><?php if (!empty($roster['member']['citystate'])) { ?><br><?php echo $roster['member']['citystate']; } ?></td>
							<td><?php echo $roster['member']['phone']; ?></td>
							<td><?php echo $roster['member']['insurance']; ?></td>
						</tr>
						<?php $i++; endforeach; ?>


						<tr class="<?php echo ($i % 2 == 0) ? 'even':'odd'; ?>_row"><td style="text-align: center"><?php echo $i++; ?></td><td></td><td></td><td></td><td></td></tr>
						<tr class="<?php echo ($i % 2 == 0) ? 'even':'odd'; ?>_row"><td style="text-align: center"><?php echo $i++; ?></td><td></td><td></td><td></td><td></td></tr>
						<tr class="<?php echo ($i % 2 == 0) ? 'even':'odd'; ?>_row"><td style="text-align: center"><?php echo $i++; ?></td><td></td><td></td><td></td><td></td></tr>
						<?php if ($i==1) : ?><tr><td colspan="6"><?php echo $roster['member']['name']; ?> is not signed up for any classes.</td></tr><?php endif; ?>
					</tbody>
					<tr><td colspan="5"><p>Attach a copy of your <?php echo strtolower($reg['unitid']['unittype']); ?>'s accident insurance policy. If you don't have a <?php echo strtolower($reg['unitid']['unittype']); ?> policy, please record individual policies and numbers above. Thank you!</p></td></tr>
				</table>
				<!-- END Youth Roster -->
				
			</div>
			
		</div>
		<div class="pagebreak"></div>
		<div id="section_header"></div>
		<div id="content"> 
			<div class="page" style="font-size: 7pt">
				<table style="width: 100%;" class="header">
					<tr>
						<td><h1 style="text-align: left"><?php echo $reg['unitid']['unittitle']; ?> - Adult Roster</h1><h2>Reg #<?php echo $reg['id']; ?></h2></td>
						<td><h1 style="text-align: right"><?php echo (empty($reg['session']['title'])) ? $reg['eventid']['sessiontitle'].' '.$reg['session']['sessionnum']: $reg['session']['title']; ?> 
						<?php if (isset($reg['eventid']['groups']) && $reg['eventid']['groups']['enabled'] == 1) echo '('.$reg['eventid']['groups']['groups'][$reg['group']]['title'].')'; ?></h1><h2 style="text-align: right;"><?php echo $reg['eventid']['title']; ?></h2></td>
					</tr>
				</table>
				<table style="width: 100%; font-size: 8pt;">
					<tr>
						<td><?php echo $reg['unitid']['unittype']; ?>: <strong><?php echo $reg['unitid']['unittype']; ?> <?php echo $reg['unitid']['number']; ?></strong></td>
						<td>Event: <strong><?php echo $reg['eventid']['title']; ?></strong></td>
					</tr>
					<tr>
						<td>City/State: <strong><?php echo $reg['unitid']['city']; ?>, <?php echo $reg['unitid']['state']; ?></strong></td>
						<td><?php echo $reg['eventid']['sessiontitle']; ?>: <strong><?php echo (empty($reg['session']['title'])) ? $reg['eventid']['sessiontitle'].' '.$reg['session']['sessionnum']: $reg['session']['title']; ?></strong></td>
					</tr>
					<tr>
						<td>Council: <strong><?php echo $reg['unitid']['council']; ?></strong> (<?php echo $reg['unitid']['district']; ?> District)</td>
						<td>Event Dates: <strong><?php //Prep
							// Dates prep
							if (empty($reg['session']['datestart'])) {
								if (empty($reg['eventid']['dateend'])) {
									$dates = date('F j, Y', $reg['eventid']['datestart']);
								} else {
									$dates = date('F j', $reg['eventid']['datestart']).date(' - F j, Y', $reg['eventid']['dateend']);
								} 
							} else {
								if (empty($reg['session']['dateend'])) {
									$dates = date('F j, Y', $reg['session']['datestart']);
								} else {
									$dates = date('F j', $reg['session']['datestart']).date(' - F j, Y', $reg['session']['dateend']);
								} 
							} echo $dates; ?></strong>
						</td>
					</tr>
					<tr>
						<td>Time of Registration: <strong><?php echo date('F j, Y \a\t g:ia', $reg['registerdate']['time']); ?></strong></td>
						<td><?php if (isset($reg['eventid']['groups']) && $reg['eventid']['groups']['enabled'] == 1) echo $reg['eventid']['groups']['title'].': <strong>'.$reg['eventid']['groups']['groups'][$reg['group']]['title'].'</strong>'; ?></td>
					</tr>
				</table>
			
				<!-- START Youth Roster -->
				<table class="change_order_items">
					<tr>
						<td colspan="4"><h1>Adults</h1></td>
						<td colspan="3"><h2>Please fill out all information completely and bring three (3) copies of this roster with you to camp.</h2></td>
					</tr>
					<tbody>
						<tr>
							<th style="width: 5%">Arrival<br>Date</th>
							<th style="width: 5%">Ref<br>Form</th>
							<th style="width: 3%">#</th>
							<th style="width: 20%">Adult Name</th>
							<th>Address</th>
							<th>Phone Number</th>
							<th>Accident Insurance Policy #</th>
						</tr>
						<?php $adult = ($reg['unitid']['unittype'] == 'Ship' || $reg['unitid']['unittype'] == 'Crew') ? (31556926 * 21): (31556926 * 18); // 21 and 18 years in seconds
							$i=1; foreach ($rosters as $roster) : 
							if (time()-$roster['member']['dob'] < $adult) continue; ?>
						<tr class="<?php echo ($i % 2 == 0) ? 'even':'odd'; ?>_row">
							<td></td><td></td>
							<td style="text-align: center"><?php echo $i; ?></td>
							<td><?php echo $roster['member']['name']; ?></td>
							<td><?php echo $roster['member']['address']; ?><?php if (!empty($roster['member']['citystate'])) { ?><br><?php echo $roster['member']['citystate']; } ?></td>
							<td><?php echo $roster['member']['phone']; ?></td>
							<td><?php echo $roster['member']['insurance']; ?></td>
						</tr>
						<?php $i++; endforeach; ?>
						<tr class="<?php echo ($i % 2 == 0) ? 'even':'odd'; ?>_row"><td></td><td></td><td style="text-align: center"><?php echo $i++; ?></td><td></td><td></td><td></td><td></td></tr>
						<tr class="<?php echo ($i % 2 == 0) ? 'even':'odd'; ?>_row"><td></td><td></td><td style="text-align: center"><?php echo $i++; ?></td><td></td><td></td><td></td><td></td></tr>
						<tr class="<?php echo ($i % 2 == 0) ? 'even':'odd'; ?>_row"><td></td><td></td><td style="text-align: center"><?php echo $i++; ?></td><td></td><td></td><td></td><td></td></tr>
						<tr class="<?php echo ($i % 2 == 0) ? 'even':'odd'; ?>_row"><td></td><td></td><td style="text-align: center"><?php echo $i++; ?></td><td></td><td></td><td></td><td></td></tr>
						<?php if ($i==1) : ?><tr><td colspan="6"><?php echo $roster['member']['name']; ?> is not signed up for any classes.</td></tr><?php endif; ?>
					</tbody>
					<tr><td colspan="7" style="border-bottom:1px solid black"><p><strong>Your first aid certified leader:</strong></p></td></tr>
					<tr><td colspan="7"><p>All Scouts and Scout leaders <strong>MUST</strong> be covered by an accident insurance policy. The camp does not carry an accident insurance policy for Scouts and adult leaders outside of Longs Peak Council. Attach a copy of your <?php echo strtolower($reg['unitid']['unittype']); ?>'s accident insurance policy. If you don't have a <?php echo strtolower($reg['unitid']['unittype']); ?> policy, please record individual policies and numbers above. Thank you!</p></td></tr>
				</table>
				<!-- END Youth Roster -->
				
			</div>
			
		</div>

	</div>

	<script type="text/php">
	
	if ( isset($pdf) ) {
	
	  $font = Font_Metrics::get_font("verdana");
	  // If verdana isn't available, we'll use sans-serif.
	  if (!isset($font)) { Font_Metrics::get_font("sans-serif"); }
	  $size = 6;
	  $color = array(0,0,0);
	  $text_height = Font_Metrics::get_font_height($font, $size);
	
	  $foot = $pdf->open_object();
	  
	  $w = $pdf->get_width();
	  $h = $pdf->get_height();
	
	  // Draw a line along the bottom
	  $y = $h - 2 * $text_height - 24;
	  $pdf->line(16, $y, $w - 16, $y, $color, 1);
	
	  $y += $text_height;
	
	  $text = "Job: 132-003";
	  $pdf->text(16, $y, $text, $font, $size, $color);
	
	  $pdf->close_object();
	  $pdf->add_object($foot, "all");
	
	  global $initials;
	  $initials = $pdf->open_object();
	  
	  // Add an initals box
	  $text = "Initials:";
	  $width = Font_Metrics::get_text_width($text, $font, $size);
	  $pdf->text($w - 16 - $width - 38, $y, $text, $font, $size, $color);
	  $pdf->rectangle($w - 16 - 36, $y - 2, 36, $text_height + 4, array(0.5,0.5,0.5), 0.5);
	    
	
	  $pdf->close_object();
	  $pdf->add_object($initials);
	 
	  // Mark the document as a duplicate
	  $pdf->text(110, $h - 240, "DUPLICATE", Font_Metrics::get_font("verdana", "bold"),
	             110, array(0.85, 0.85, 0.85), 0, 0, -52);
	
	  $text = "Page {PAGE_NUM} of {PAGE_COUNT}";  
	
	  // Center the text
	  $width = Font_Metrics::get_text_width("Page 1 of 2", $font, $size);
	  $pdf->page_text($w / 2 - $width / 2, $y, $text, $font, $size, $color);
	    }
	</script>

</body>
</html>