<?php 

/* 
 * Camper API - List of Units
 *
 * This is. 
 *
 * File: /application/views/data/units.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
 //print_r($source);
// Setup
$allunits = array();

foreach ($source as $s) {
	$t = array();
	$t['unitid'] = $s['id'];
	$t['name'] = (isset($s['associatedunit']) && $s['associatedunit'] !== '0' ) ? $s['associatedunit'].' '.$s['associatednumber'].' ('.$s['unittype'].' '.$s['number'].')': $s['unittype'].' '.$s['number'];
	$t['council'] = $s['council'];
	$t['city'] = $s['city'];
	$t['state'] = $s['state'];
	$t['district'] = (isset($s['district'])) ? $s['district']: '';
	$t['tokens'] = array(
		$s['unittype'], 
		$s['number'], 
		str_replace(' ','-',$s['district']), 
		str_replace(' ','-',$s['council']), 
		str_replace(' ','-',$s['city']), 
		str_replace(' ','-',$s['state'])
	);
	if (isset($s['associatedunit']) && $s['associatedunit'] !== '0' ) {
		array_push($t['tokens'], $s['associatedunit'], $s['associatednumber']);
	}
	
	if($value == 'num') {
		$t['value'] = $s['number'];
	} else {
		$t['value'] = $t['name'].' ('.$s['city'].', '.$s['state'].')';
	}
	array_push($allunits, $t);
}

//print_r($allunits);
print json_encode($allunits);

?>