<?php 

/* 
 * Camper API - List of Users
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
$phonechar = array(" ", "(", ")", "-");
$allusers = array();

foreach ($source as $s) {
	$t = array();
	$t['email'] = $s['email'];
	$t['userid'] = $s['id'];
	$t['name'] = $s['first_name'].' '.$s['last_name'];
	$t['phone'] = $s['phone'];
	$t['tokens'] = array($s['email'], str_replace(' ','-',$s['first_name']), str_replace(' ','-',$s['last_name']), str_replace($phonechar, "", $s['phone']));
	$t['unit'] = ''; // Coming Eventually
	$t['value'] = $t['email'];
	array_push($allusers, $t);
}

//print_r($allusers);
print json_encode($allusers);

?>