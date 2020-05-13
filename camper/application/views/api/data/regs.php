<?php 

/* 
 * Camper API - List of regs including event, unit and session data
 *
 * This is. 
 *
 * File: /application/views/data/regs.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2014 11 02 2317)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
/*
 * $data vars
 * 
 * regs		id,unitid,eventid,session
 *
 * units	id,district,council,number,unittype,primary,alt,city,state
 * events	id,title,location,datestart,eventtype,sessiontitle,groups
 * sessions	id,eventid,title,sessionnum
 */

// Setup
$allregs = array();
$event = ($this->input->get('event')) ? $this->input->get('event'): false;
$session = ($this->input->get('session')) ? $this->input->get('session'): false;

// Here we go
if ($this->input->get('format') == 'csv') {
	// CSV Export
	$this->load->helper('download');
	function c($a) {
		return str_replace(',', ' -', $a);
	}
	
	if ($event === false) {
		$group['label'] = ',Groups';
	} else {
		if (isset($events[$event]['groups'])) {
			$group['label'] = ($events[$event]['groups']['enabled'] == 1) ? ','.$events[$event]['groups']['title']: '';
			$group['e'] = true;
		} else {
			$group['label'] = '';
			$group['e'] = false;
		}
	}
	$file = 'Reg ID,Unit,Number,Subunit,Contact,Email,Address,City,State,Zip,Total,Youth,Male,Female,Council,District,Event,Session'.$group['label']."\n";
	foreach ($regs as $s) {
		$t = array();
		if ($event && $event !== $s['eventid']) continue; 
		if ($session && $session !== $s['session']) continue; 
		$file = $file.$s['id'].',';
		//$file = $file.$units[$s['unitid']]['unittype'].','.$units[$s['unitid']]['number'].',';
		if ($s['individual'] == '1') {
		    $user = $this->ion_auth->user($s['userid'])->row();
		    $unit = ($user->individual == '1') ? unserialize($user->individualdata): false;
		    $file = ($unit && $unit['unittype'] == 'None') ? $file.'-,-,-,'.$user->first_name.' '.$user->last_name.','.$user->email.',' : $file.$unit['unittype'].','.$unit['number'].',None III,'.$user->first_name.' '.$user->last_name.','.$user->email.',';
			$file = $file.c($unit['address']).','.c($unit['city']).','.strtoupper($unit['state']).','.c($unit['zip']).',';
		} else {
			$user = $this->ion_auth->user($units[$s['unitid']]['primary'])->row();
			$file = (isset($units[$s['unitid']]['associatedunit']) && $units[$s['unitid']]['associatedunit'] !== '0' ) ? /* DEN */ $file.$units[$s['unitid']]['associatedunit'].','.$units[$s['unitid']]['associatednumber'].','.$units[$s['unitid']]['unittype'].' '.$units[$s['unitid']]['number'].',': /* NOT DEN */ $file.$units[$s['unitid']]['unittype'].','.$units[$s['unitid']]['number'].',None,';
			$file = $file.c($user->first_name.' '.$user->last_name).','.c($user->email).',';
			$file = $file.c($units[$s['unitid']]['address']).','.c($units[$s['unitid']]['city']).','.strtoupper($units[$s['unitid']]['state']).','.$units[$s['unitid']]['zip'].',';
		}
		$t['total'] = $s['youth']+$s['male']+$s['female'];
		$file = $file.$t['total'].',';
		$file = $file.$s['youth'].',';
		$file = $file.$s['male'].',';
		$file = $file.$s['female'].',';
		if ($s['individual'] == '1') {
			$file = $file.c($unit['council']).',';
			$file = $file.c($unit['district']).',';
		} else {
			$file = $file.c($units[$s['unitid']]['council']).',';
			$file = $file.c($units[$s['unitid']]['district']).',';
		}
		$file = $file.c($events[$s['eventid']]['title']).',';
		$t['session'] = (empty($sessions[$s['session']]['title'])) ? $events[$s['eventid']]['sessiontitle'].' '.$sessions[$s['session']]['sessionnum'] : $sessions[$s['session']]['title']; // session title
		$file = $file.c($t['session']);
		if ($event === false) {
			// Showing multiple events, groups column is showing
			if ($events[$s['eventid']]['groups']['enabled'] == 1 ) {
				// groups and enabled
				$file = (isset($s['group'])) ? $file.','.$events[$s['eventid']]['groups']['groups'][$s['group']]['title'] : $file.',-';
			} else {
				// groups disabled or none
				$file = $file;
			}
		} else {
			// Single event, will 
			//show_error(print_r($events));
			$file = ($group['e']) ? (isset($s['group'])) ? $file.','.$events[$s['eventid']]['groups']['groups'][$s['group']]['title'] : $file.',-' : $file;
		}
		$file = $file."\n";
	}
	$name = 'regs.csv';
	force_download($name, $file);
	
} else {
	// JSON Export
	foreach ($regs as $s) {
		if ($event && $event !== $s['eventid']) continue; 
		if ($session && $session !== $s['session']) continue; 
		$t = array();
		$t['id'] = $s['id'];
		$t['event'] = $events[$s['eventid']]['title']; // event title
		$t['eventid'] = $s['eventid']; // event id
		$t['eventlocation'] = $events[$s['eventid']]['location']; // event title
		$t['session'] = (empty($sessions[$s['session']]['title'])) ? 
		$events[$s['eventid']]['sessiontitle'].' '.$sessions[$s['session']]['sessionnum'] : $sessions[$s['session']]['title']; // session title
		//$t['unit'] = $units[$s['unitid']]['unittype'].' '.$units[$s['unitid']]['number']; // unit title + number
		if ($s['individual'] == '1') {
		    $user = $this->ion_auth->user($s['userid'])->row();
		    $unit = ($user->individual == '1') ? unserialize($user->individualdata): false;
    		$t['unit'] = ($unit && $unit['unittype'] == 'None') ? $user->first_name.' '.$user->last_name.' (No Unit)' : $user->first_name.' '.$user->last_name.' ('.$unit['unittype'].' '.$unit['number'].')';
			$t['council'] = $unit['council']; // unit council
			$t['city'] = $unit['city'].', '.strtoupper($unit['state']); // city+state
			$t['tokens'] = array($unit['state'],$user->first_name,$user->last_name);
			$t['tokens'] = array_merge($t['tokens'],
				explode(" ", $unit['city']),
				explode(" ", $t['event']),
				explode(" ", $t['eventlocation']),
				explode(" ", $t['session']),
				explode(" ", $t['council']));
			if ($unit['unittype'] !== 'None' && isset($unit['number'])) array_push($t['tokens'], $unit['unittype'], $unit['number']);
		} else {
			$t['unit'] = (isset($units[$s['unitid']]['associatedunit']) && $units[$s['unitid']]['associatedunit'] !== '0' ) ? $units[$s['unitid']]['associatedunit'].' '.$units[$s['unitid']]['associatednumber'].' ('.$units[$s['unitid']]['unittype'].' '.$units[$s['unitid']]['number'].')': $units[$s['unitid']]['unittype'].' '.$units[$s['unitid']]['number'];
			$t['council'] = $units[$s['unitid']]['council']; // unit council
			$t['city'] = $units[$s['unitid']]['city'].', '.strtoupper($units[$s['unitid']]['state']); // city+state
			$t['tokens'] = array($units[$s['unitid']]['state'],$units[$s['unitid']]['unittype'],$units[$s['unitid']]['number']);
			$t['tokens'] = array_merge($t['tokens'],
				explode(" ", $units[$s['unitid']]['city']),
				explode(" ", $t['event']),
				explode(" ", $t['eventlocation']),
				explode(" ", $t['session']),
				explode(" ", $t['council']));
			if (isset($units[$s['unitid']]['associatedunit']) && $units[$s['unitid']]['associatedunit'] !== '0' ) {
				array_push($t['tokens'], $units[$s['unitid']]['associatedunit'], $units[$s['unitid']]['associatednumber']);
			}
		}

		$t['value'] = $t['id'];
		array_push($allregs, $t);
	}

	//print_r($allusers);
	print json_encode($allregs);

}

?>





