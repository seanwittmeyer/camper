<?php 

/* 
 * Camper API - List of regs including event, unit and session data plus finances
 *
 * This is. 
 *
 * File: /application/views/data/regs_finance.php
 * Copyright (c) 2014 Zilifone
 * Version 1.0 (2014 11 27 1220)
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
foreach ($regs as $reg) {
	$event = $reg['eventid'];
	break;
}

// Output Function
function writerow($reg = false, $event) {
	// Set header flag if false or empty $reg
	$h = ($reg === false) ? true: false;

	// Registration ID
	$output = ($h) ? c('Reg ID'): c($reg['id']);

	// Unit Type
	$output .= ($h) ? c('Unit'): c($reg['unitid']['unittype']);

	// Unit Number
	$output .= ($h) ? c('Number'): c($reg['unitid']['number']);

	// Subunit Type and Number
	if ($h) {
		$output .= c('Subunit');
	} else {
		if (isset($reg['unitid']['associatedunit'])) {
			$output .= ($reg['unitid']['associatedunit'] == 0) ? c('None'): c($reg['unitid']['associatedunit'].' '.$reg['unitid']['associatednumber']);
		} else {
			$output .= c('None');
		}
	}

	// Contact Name
	$output .= ($h) ? c('Contact'): c($reg['unitid']['primary']['first_name'].' '.$reg['unitid']['primary']['last_name']);

	// Contact Email
	$output .= ($h) ? c('Email'): c($reg['unitid']['primary']['email']);

	// Contact Address
	$output .= ($h) ? c('Address'): c($reg['unitid']['address']);

	// City
	$output .= ($h) ? c('City'): c($reg['unitid']['city']);

	// State
	$output .= ($h) ? c('State'): c($reg['unitid']['state']);

	// Zip Code
	$output .= ($h) ? c('Zip'): c($reg['unitid']['zip']);

	// Total Registrants
	$output .= ($h) ? c('Total'): c($reg['youth']+$reg['male']+$reg['female']);

	// Youth Registrants
	$output .= ($h) ? c('Youth'): c($reg['youth']);

	// Male Adult Registrants
	$output .= ($h) ? c('Male'): c($reg['male']);

	// Female Adult Registrants
	$output .= ($h) ? c('Female'): c($reg['female']);

	// Unit Council
	$output .= ($h) ? c('Council'): c($reg['unitid']['council']);

	// Unit District
	$output .= ($h) ? c('District'): c($reg['unitid']['district']);

	// Event Title
	$output .= ($h) ? c('Event'): c($reg['eventid']['title']);

	// Session Title
	$output .= ($h) ? c($event['sessiontitle']): c((empty($reg['session']['title'])) ? $reg['eventid']['sessiontitle'].' '.$reg['session']['sessionnum']: $reg['session']['title']);

	// Group Title
	if ($h) {
		$output .= c($event['groups']['title']);
	} else {
		if (isset($reg['eventid']['groups']) && $reg['eventid']['groups']['enabled'] == 1) {
			$output .= (empty($reg['group'])) ? c(''): c($reg['eventid']['groups']['groups'][$reg['group']]['title']);
		} else {
			$output .= c('');
		}
	}

	// Total Fee
	$output .= ($h) ? c('Total Fee'): c($reg['fin']['fin']['total']);

	// Amount Paid
	$output .= ($h) ? c('Paid'): c($reg['fin']['fin']['totalpaid']);

	// Amount Due
	$output .= ($h) ? c('Due'): c($reg['fin']['fin']['totaldue']);

	// Late Fees
	$output .= ($h) ? c('Late Fee'): c($reg['fin']['fin']['latefee']);

	// Group Fees
	$output .= ($h) ? c($event['groups']['title'].' Fees'): c($reg['fin']['fin']['group']);

	// Options Total
	$output .= ($h) ? c('Options Total'): c($reg['fin']['fin']['requests']);

	// Options Loop
	if ($h) {
		if (is_array($event['options']) && count($event['options']) > 0) {
			foreach ($event['options'] as $o) {
				$flag = true; 
				if (isset($o['amount']) && $o['amount'] <= 0) {
					continue;
				} elseif (!isset($o['amount'])) {
					continue;
				} else {
					$output .= c($o['title']); 
				}
			}
		}
	} else {
		if (is_array($reg['eventid']['options']) && count($reg['eventid']['options']) > 0) {
			foreach ($reg['eventid']['options'] as $o) {
				$flag = true; 
				if (isset($o['amount']) && $o['amount'] <= 0) {
					continue;
				} elseif (!isset($o['amount'])) {
					continue;
				} else {
					if ($o['checkbox'] == 1 && isset($o['id']) && !isset($reg['options'][$o['id']]['checkbox'])) $flag = false; 
					if (isset($o['amount']) && $o['amount'] > 0 && $flag === true ) {
		
						// Setup
						$o['__total'] = (isset($o['amount'])) ? $o['amount']: 0; 
						$o['__value'] = 0; 
						$o['__percent'] = ($o['percent'] == 1) ? true: false;

						// Calculate Option
						if (isset($o['amount']) && $o['value'] == 1 && isset($reg['options'][$o['id']]['value']) ) { 
							// This option has an amount and the user entered a value
							if ($o['perperson'] == 1) {
								// This is per person, we'll display the details find the total
								//echo $reg['fin']['counts']['total'].' x '.$o['__pre'].$o['amount'].$o['__post'];
								$o['__total'] = ($o['__percent']) ? $reg['fin']['counts']['total'] * (.01 * $o['amount']) : $reg['fin']['counts']['total'] * $o['amount'];
							} else {
								// This is per the value, we'll display details and find the total
								//echo $reg['options'][$o['id']]['value'].' x '.$o['__pre'].$o['amount'].$o['__post'];
								$o['__total'] = ($o['__percent']) ? $reg['options'][$o['id']]['value'] * (.01 * $o['amount']) : $reg['options'][$o['id']]['value'] * $o['amount']; 
							}
						} elseif (isset($o['amount'])) {
							if ($o['perperson'] == 1) {
								//echo $reg['fin']['counts']['total'].' x '.$o['__pre'].$o['amount'].$o['__post'];
								$o['__total'] = ($o['__percent']) ? $reg['fin']['counts']['total'] * (.01 * $o['amount']) : $reg['fin']['counts']['total'] * $o['amount'];
							} else {
								//echo $o['__pre'].$o['amount'].$o['__post'];
								$o['__total'] = ($o['__percent']) ? .01 * $o['amount'] : $o['amount'];
							}
						}
						
						// Print Option Total
						$output .= c($o['__total']); 
					} else {
						// Print Blank
						$output .= c(0); 
					}
				}	
			}
		}
	} 

	// Preorders Cost
	$output .= ($h) ? c('Preorders'): c($reg['fin']['fin']['preorders']);

	// Discounts
	$output .= ($h) ? c('Discounts'): c($reg['fin']['fin']['discounts']);

	// Options Loop
	if ($h) {
		if (is_array($event['discounts']) && count($event['discounts']) > 0) {
			foreach ($event['discounts'] as $o) {
				$flag = true; 
				if (isset($o['amount']) && $o['amount'] <= 0) {
					continue;
				} elseif (!isset($o['amount'])) {
					continue;
				} else {
					$output .= c($o['title']); 
				}
			}
		}
	} else {
		if (is_array($reg['eventid']['discounts']) && count($reg['eventid']['discounts']) > 0) {
			foreach ($reg['eventid']['discounts'] as $o) {
				$flag = true; 
				if (isset($o['amount']) && $o['amount'] <= 0) {
					continue;
				} elseif (!isset($o['amount'])) {
					continue;
				} else {
					if ($o['checkbox'] == 1 && isset($o['id']) && !isset($reg['discounts'][$o['id']]['checkbox'])) $flag = false; 
					if (isset($o['amount']) && $o['amount'] > 0 && $flag === true ) {
		
						// Setup
						$o['__total'] = (isset($o['amount'])) ? $o['amount']: 0; 
						$o['__value'] = 0; 
						$o['__percent'] = ($o['percent'] == 1) ? true: false;

						if (isset($o['amount']) && $o['value'] == 1 && isset($reg['discounts'][$o['id']]['value']) ) { 
							// This option has an amount and the user entered a value
							if ($o['perperson'] == 1) {
								// This is per person, we'll display the details find the total
								//echo $reg['fin']['counts']['total'].' x '.$o['__pre'].$o['amount'].$o['__post'];
								$o['__total'] = ($o['__percent']) ? $reg['fin']['counts']['total'] * (.01 * $o['amount']) : $reg['fin']['counts']['total'] * $o['amount'];
							} else {
								// This is per the value, we'll display details and find the total
								//echo $reg['discounts'][$o['id']]['value'].' x '.$o['__pre'].$o['amount'].$o['__post'];
								$o['__total'] = ($o['__percent']) ? $reg['discounts'][$o['id']]['value'] * (.01 * $o['amount']) : $reg['discounts'][$o['id']]['value'] * $o['amount']; 
							}
						} elseif (isset($o['amount'])) {
							if ($o['perperson'] == 1) {
								//echo $reg['fin']['counts']['total'].' x '.$o['__pre'].$o['amount'].$o['__post'];
								$o['__total'] = ($o['__percent']) ? $reg['fin']['counts']['total'] * (.01 * $o['amount']) : $reg['fin']['counts']['total'] * $o['amount'];
							} else {
								//echo $o['__pre'].$o['amount'].$o['__post'];
								$o['__total'] = ($o['__percent']) ? .01 * $o['amount'] : $o['amount'];
							}
						}
						// Print Option Total
						$output .= c($o['__total']); 
					} else {
						// Print Blank
						$output .= c(0); 
					}
				}
			}
		}
	}

	// Free Adults Discount
	$output .= ($h) ? c('Free Adults'): c($reg['fin']['fin']['freeadults']);

	// Early Reg Discount
	$output .= ($h) ? c('Early Reg'): c($reg['fin']['fin']['earlyreg']);


	// New Line and Return
	return $output."\n";

} // end writerow()

// Build CSV File
$file = writerow(false,$event);
$i=1;
if (is_array($regs) && count($regs) > 0) { 
	foreach ($regs as $reg) {
		//if ($i === 1) { // debug
		// Replace primary ID with array
		if ($reg['unitid'] == 0) {
			$reg['unitid'] = (isset($reg['userid']['individualdata'])) ? $reg['userid']['individualdata'] : $reg['unitid']['primary'] = $this->data->get_users($reg['registerdate']['user']);
	
			$reg['unitid']['primary'] = $reg['userid'];
			// Handle Missing Data
			// Some individual users may not have these details due to registration issues, we'll set them here
			foreach (array('address','city','state','zip','council','district','unittype','number','associatedunit','associatednumber') as $item) {
				if (!isset($reg['unitid'][$item])) $reg['unitid'][$item] = '-';
			}
			foreach (array('first_name','last_name','email') as $item) {
				if (!isset($reg['userid'][$item])) $reg['userid'][$item] = '-';
			}
		} else {
			if ($reg['unitid']['primary'] == 0) {
				// If the user isn't here anymore or moved to another unit, we will use the user who created the registration
				$reg['unitid']['primary'] = $this->data->get_users($reg['registerdate']['user']);
			} else {
				$reg['unitid']['primary'] = $this->data->get_users($reg['unitid']['primary']);
			}
		}
		
		// Get Finances
		if (empty($reg['eventid']['options'])) $reg['eventid']['options'] = array();
		if (empty($reg['eventid']['discounts'])) $reg['eventid']['discounts'] = array();
		$reg['fin'] = $this->shared->get_finances($reg['eventid'], $reg, $reg['session'], $reg['unitid'], $reg['eventid']['options'], $reg['eventid']['discounts'], false, false);
		// Write the row
		//print_r($reg); die;

		$file .= writerow($reg,$event);
		$i++;
		
		//} // debug
	}
} else {
	$file .= 'No results';
}

//print_r($file); die; // debug

// Download the CSV
$this->load->helper('download');
$name = 'regs.csv';
force_download($name, $file);

?>





