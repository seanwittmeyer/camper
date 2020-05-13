<?php 

/* 
 * Camper API - List of regs including event, unit and session data
 *
 * This is. 
 *
 * File: /application/views/data/regs.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
/*
 * $data vars
 * 
 * reg		id,unitid,eventid,session
 * rosters	id,district,council,number,unittype,primary,alt,city,state
 */

// CSV Export
function s($a) {
	return str_replace(' ', '', $a);
}
function q($a) {
	return str_replace('"', "'", $a);
}
function w($a) {
	return '"'.$a.'"';
}
$b = ',';

$blocks = false;
if (isset($reg['eventid']['periods']['periods']) && isset($reg['eventid']['periods']['days'])) {
	$blocks = true; 
} 
	
// Setup with header
$file = 'Name,Class,Activity,Location,"Status (as of '.date('j M y').')",Cost,Event,Session,Prerequisites,Periods'."\n";

// Run through our regs
foreach ($rosters as $r) {
	//print_r($r);die;
	$classes = $this->data->get_classregs(false,true,array('member'=>$r['member']['id'],'reg'=>$r['reg']['id']));
	// old $classes = $this->data->get_classregs(false,true,array('member'=>$r['member']['id']));
	if (empty($classes)) continue;
	foreach ($classes as $c) {
		//print_r($c);die;
		// Name, Class, Activity, Location
		$file .= w($r['member']['name']).$b.w($c['class']['title']).$b.w($c['activity']['title']).$b.w($c['class']['location']).$b;
		// Position / Status
		$file .= w($this->activities_model->class_position($r['id'], $c['class']['id'], $reg['session']['id'])).$b;
		// Costs
		$file .= (!empty($c['class']['preorder']) || $c['class']['preorder'] > 0) ? w('$'.$c['class']['preorder']).$b: $b;
		// Event, Session, Prereqs
		$file .= w($reg['eventid']['title'].$c['reg']).$b.w($reg['session']['nicetitle']).$b.w(q($c['activity']['long'])).$b;
		// Periods
		$file .= '"';
		$i=0;
		if ($blocks && $c['class']['blocks']) {
			foreach ($c['class']['blocks'] as $p) {
				if ($i>0) $file .= ', ';
				$q = str_split($p);
				$file .= $reg['eventid']['periods']['days'][$q[0]]['label'].' ('.$reg['eventid']['periods']['periods'][$q[1]]['label'].')';
				$i++;
			}
		}
		$file .= "\"\n";
	}
}


$name = 'classregs_'.s($reg['unitid']['unittitle']).'.csv';
//print($file); die;
$this->load->helper('download');
force_download($name, $file);

?>





