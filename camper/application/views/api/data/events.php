<?php 

/* 
 * Camper API - List of Events
 *
 * This is. 
 *
 * File: /application/views/data/users.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 //print_r($source);
// Setup
$allevents = array();

foreach ($source as $s) {
	$t = array();
	$t['title'] = $s['title'];
	$t['id'] = $s['id'];
	$t['datestart'] = date('F d, Y',$s['datestart']);
	$t['location'] = $s['location'];
	$t['tokens'] = array($s['id']);
	$t['tokens'] = array_merge($t['tokens'],explode(" ", $s['location']),explode(" ", $s['title']));
	$t['value'] = $t['id'];
	array_push($allevents, $t);
}

//print_r($allusers);
print json_encode($allevents);

?>