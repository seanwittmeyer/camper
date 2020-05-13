<?php 

/* 
 * Camper API - List of sessions including event session data
 *
 * This is. 
 *
 * File: /application/views/data/sessions.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
/*
 * $data vars
 * 
 * events	id,title,location,datestart,dateend,eventtype,sessiontitle,open,earlyreg,eligibleunits
 * sessions	id,eventid,open,title,description,limithard,limitsoft,count,cost,costadult,costfamily,datestart,dateend,sessionnum
 */

// Setup
$allsessions = array();
foreach ($sessions as $s) {
	$t = array();
	$t['id'] = $s['id'];
	$t['type'] = $events[$s['eventid']]['eventtype'];
	$t['start'] = ($s['datestart'] == 0) ? date('F d, Y', $events[$s['eventid']]['datestart']): date('F d, Y', $s['datestart']);
	$t['end'] = ($s['dateend'] == 0) ? ($events[$s['eventid']]['dateend'] == 0) ? '': date(' - F d, Y', $events[$s['eventid']]['dateend']): date(' - F d, Y', $s['dateend']);
	$t['event'] = $events[$s['eventid']]['title']; // event title
	$t['location'] = $events[$s['eventid']]['location']; // event title
	$t['session'] = (empty($s['title'])) ? $events[$s['eventid']]['sessiontitle'].' '.$s['sessionnum'] : $s['title']; // session title
	$t['tokens'] = array_merge(
		explode(" ", $t['event']),
		explode(" ", $t['type']),
		explode(" ", $t['location']),
		explode(" ", $t['session'])
	);
	$t['value'] = $t['id'];
	array_push($allsessions, $t);
}

//print_r($allusers);
print json_encode($allsessions);

?>