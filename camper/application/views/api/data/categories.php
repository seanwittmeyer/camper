<?php 

/* 
 * Camper API - List of categories
 *
 * This is. 
 *
 * File: /application/views/data/categories.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 //print_r($source);
// Setup
$c = array();
$d = array();
$e = array();

foreach ($source as $s) {
	if (in_array($s['category'], $c)) continue;
	$c[] = $s['category'];
}

foreach ($c as $cc) {
	$t = array();
	$t['tokens'] = array($cc);
	$t['value'] = $cc;
	$e[] = $t;
}

//print_r($allusers);
print json_encode($e);

?>