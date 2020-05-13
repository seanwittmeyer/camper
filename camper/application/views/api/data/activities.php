<?php 

/* 
 * Camper API - List of Activities
 *
 * This is. 
 *
 * File: /application/views/api/data/activities.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 //print_r($source);
// Setup
$allactivities = array();

foreach ($activities as $a) {
	$t = array();
	$t['title'] = $a['title'];
	$t['id'] = $a['id'];
	$t['eventtype'] = $a['eventtype'];
	$t['category'] = $a['category'];
	$t['lastupdate'] = $a['lastupdate'];
	$t['age'] = $a['age'];
	$t['tokens'] = array($a['id']);
	$t['tokens'] = array_merge($t['tokens'],explode(" ", $a['category']),explode(" ", $a['title']));
	$t['value'] = $t['title'];
	$allactivities[] = $t;
}

print json_encode($allactivities);

?>