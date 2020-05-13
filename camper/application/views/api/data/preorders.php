<?php 

/* 
 * Camper API - List of preorder finances brought in by Camper, broken down by class.
 *
 * This is. 
 *
 * File: /application/views/data/preorders.php
 * Copyright (c) 2014 Zilifone
 * Version 1.0 (2014 12 02 0001)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
/*
 * $data vars
 * 
 * regs		id,unitid,eventid,session
 */

// Setup
function c($a) {
	return str_replace(',', ' -', $a).',';
}

// Set up array of reg IDs for regs with preorders
$regs_array = array();
if (is_array($regs) && count($regs) > 0) { 
	foreach ($regs as $reg) {
		$regs_array[$reg['id']] = $reg['id'];
	}
}

// Output Function
function writerow($class = false) {
	// Set header flag if false or empty $reg
	$h = ($class === false) ? true: false;

	// Class Title
	$output = ($h) ? c('Class'): c($class['title']);

	// Preorder Cost per Reg
	$output .= ($h) ? c('Cost Per'): c($class['preorder']);

	// Number of Class Regs with Preorders
	$output .= ($h) ? c('Registrations'): c($class['preordercount']);

	// Number of Class Regs
	//$output .= ($h) ? c('Registrations'): c($class['regcount']);

	// Total Camper Preorder Amount
	$output .= ($h) ? c('Total'): c($class['preordertotal']);


	// New Line and Return
	return $output."\n";
	
} // end writerow()

// Build CSV File
$file = writerow(false);
$i=1; // count
$j=0; // preorder class total
$k=0; // preorder overall total
if (is_array($classes) && count($classes) > 0) { 
	foreach ($classes as $class) {
		//if ($i === 1) { // debug
		// Get class regs
		$where = array('class' => $class['id']);
		$classregs = $this->data->get_classregs(false,false,$where);

		$j=0; // preorder class total
		$jj=0; // reg count
		if (is_array($classregs) && count($classregs) > 0) { 
			foreach ($classregs as $classreg) {
				$jj++;
				if (!in_array($classreg['reg'], $regs_array)) continue;
				$j++;
			}
			$class['regcount'] = $jj;
			$class['preordercount'] = $j;
			$class['preordertotal'] = $j*$class['preorder'];
			$k += $class['preordertotal'];

		} else {
			$class['regcount'] = 0;
			$class['preordercount'] = 0;
			$class['preordertotal'] = 0;
		}

		// Write the row
		//print_r($reg); die;
		$file .= writerow($class);
		$i++;

		//} // debug
	}
} else {
	$file .= 'No results';
}

//print_r($file); die; // debug

// Download the CSV
$this->load->helper('download');
$name = 'preorders.csv';
force_download($name, $file);

?>





