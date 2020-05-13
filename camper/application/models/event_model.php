<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin Event Model
 *
 * This script is the model for the Camper admin event section, used for adding and 
 * managing events in camper.
 *
 * Version 1.0 (2012.10.18.0017)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
*/

class Event_model extends CI_Model {

	public function __construct()
	{
	}

	// Single Event Details, all fields
	public function get_single_event($event = FALSE)
	{
		if ($event === FALSE)
		{
			$query = $this->db->get('event');
			return $query->result_array();
		}
		$query = $this->db->get_where('event', array('id' => $event));
		return $query->row_array();		
	}

	// Get all of the events
	public function get_all_events()
	{
		$this->db->order_by("datestart","asc");
		$this->db->select('id,title,location,datestart,dateend,open,eventtype,groups');
		$query = $this->db->get('event');
		return $query->result_array();
	}
	
	// Count the number of registrations for a session or event
	public function count_regs($id,$type="session")
	{
		if ($type=="event") {
			$this->db->where('eventid', $id);
		} elseif ($type=="session") {
			$this->db->where('session', $id);
		}
		$count = $this->db->count_all_results('eventregs');
		return $count;
	}

	// Get the regs for a session
	public function get_session_regs($session)
	{
		$query = $this->db->get_where('eventregs', array('session' => $session));
		$result = $query->result_array();
		if ($result) {
			return $result;
		} else {
			return null;
		}
	}

	// Create an event
	public function create_event()
	{
		// Setup 
		$timestamp = time();
		if ($this->input->post('ftype')) { $eligibleunits = serialize($this->input->post('ftype')); } else { $eligibleunits = serialize(array()); }
		$eventdata = array(
			'title' => $this->input->post('title'),
			'eventtype' => $this->input->post('eventtype'),
			'location' => $this->input->post('location'),
			'description' => $this->input->post('description'),
			'activityregs' => 0,
			'regcount' => 0,
			'datestart' => $this->shared->prep_option($this->input->post('datestart'),'date'),
			'dateend' => $this->shared->prep_option($this->input->post('dateend'),'date'),
			'open' => 0,
			'earlyreg' => null,
			'sessiontitle' => 'Week',
			'freeadults' => null,
			'paymenttiers' => null,
			'eligibleunits' => $eligibleunits,
			'timestamp' => $timestamp
		);
		
		// Add to the database
		$this->db->insert('event', $eventdata);
		
		// Call the record we just made
		$query = $this->db->get_where('event', array('timestamp' => $timestamp));
		$newevent = $query->row_array();
		return $newevent['id'];
	}

	// Create an event
	public function update_event($event)
	{
		// Setup
		$message = '';
		$eventdata = array();
		$eligibleunits = array();
		
		// Event data
		if ($this->input->post('title')) $eventdata['title'] = $this->input->post('title');
		if ($this->input->post('eventtype')) $eventdata['eventtype'] = $this->input->post('eventtype');
		if ($this->input->post('location')) $eventdata['location'] = $this->input->post('location');
		if ($this->input->post('description')) $eventdata['description'] = $this->input->post('description');
		if ($this->input->post('sessiontitle')) $eventdata['sessiontitle'] = $this->input->post('sessiontitle');
		if ($this->input->post('registermessage')) $eventdata['registermessage'] = $this->input->post('registermessage');
		if ($this->input->post('notes')) $eventdata['notes'] = $this->input->post('notes');
		if ($this->input->post('datestart')) {
			$eventdata['datestart'] = strtotime($this->input->post('datestart'));
		} else {
			$eventdata['datestart'] = null;
		}
		if ($this->input->post('dateend')) {
			$eventdata['dateend'] = strtotime($this->input->post('dateend'));
		} else {
			$eventdata['dateend'] = null;
		}

		// Eligible units array
		if ($this->input->post('ftype')) {
			$eventdata['eligibleunits'] = serialize($this->input->post('ftype')); 
		} else { 
			$eventdata['eligibleunits'] = serialize(array()); 
		}
				
		// Update the event
		$this->db->where('id', $event);
		$this->db->update('event', $eventdata);
		$message = $message.'High Five! The event was updated.';
		return $message;
	}

	// Update an event from the options page
	public function update_event_options($event)
	{
		// Set up
		$message = '';
		$eventdata = array();

		// Prepare the payment tiers data
		$paymenttiersdata = array(
			'r' => 			$this->shared->prep_option($this->input->post('r'),'checkbox'),
			'ramount' => 	$this->shared->prep_option($this->input->post('ramount'),'dollar'),
			'rper' => 		$this->shared->prep_option($this->input->post('rper'),'checkbox'),
			'rrefund' => 	$this->shared->prep_option($this->input->post('rrefund'),'checkbox'),

			'f' => 			$this->shared->prep_option($this->input->post('f'),'checkbox'),
			'famount' => 	$this->shared->prep_option($this->input->post('famount'),'dollar'),
			'fdate' => 		$this->shared->prep_option($this->input->post('fdate'),'date'),
			'fpercent' => 	$this->shared->prep_option($this->input->post('fpercent'),'checkbox'),
			'fper' => 		$this->shared->prep_option($this->input->post('fper'),'checkbox'),

			's' => 			$this->shared->prep_option($this->input->post('s'),'checkbox'),
			'samount' => 	$this->shared->prep_option($this->input->post('samount'),'dollar'),
			'sdate' => 		$this->shared->prep_option($this->input->post('sdate'),'date'),
			'spercent' => 	$this->shared->prep_option($this->input->post('spercent'),'checkbox'),
			'sper' => 		$this->shared->prep_option($this->input->post('sper'),'checkbox'),

			'n' => 			$this->shared->prep_option($this->input->post('n'),'checkbox'),
			'namount' => 	$this->shared->prep_option($this->input->post('namount'),'dollar'),
			'ndate' => 		$this->shared->prep_option($this->input->post('ndate'),'date'),
			'npercent' => 	$this->shared->prep_option($this->input->post('npercent'),'checkbox'),
			'nper' => 		$this->shared->prep_option($this->input->post('nper'),'checkbox'),

			'l' => 			$this->shared->prep_option($this->input->post('l'),'checkbox'),
			'lamount' => 	$this->shared->prep_option($this->input->post('lamount'),'dollar'),
			'ldate' => 		$this->shared->prep_option($this->input->post('ldate'),'date'),
			'lpercent' => 	$this->shared->prep_option($this->input->post('lpercent'),'checkbox'),
			'lper' => 		$this->shared->prep_option($this->input->post('lper'),'checkbox')
		);
		
		// Serialize and put it into the update array
		$eventdata['paymenttiers'] = serialize($paymenttiersdata);

		// Prepare the free adults data
		$freeadultsdata = array(
			'enabled' => 		$this->shared->prep_option($this->input->post('faenabled'),'checkbox'),
			'amount' => 		$this->shared->prep_option($this->input->post('faamount'),'dollar'),
			'threshold' => 		$this->shared->prep_option($this->input->post('fathreshold'),'dollar'),
			'dollar' => 		$this->shared->prep_option($this->input->post('fadollar'),'checkbox'),
			'description' => 	$this->shared->prep_option($this->input->post('fadescription'),'text')
		);
		
		// Serialize and put it into the update array
		$eventdata['freeadults'] = serialize($freeadultsdata);

		// Prepare the early registration data
		$earlyregdata = array(
			'enabled' => 	$this->shared->prep_option($this->input->post('erenabled'),'checkbox'),
			'amount' => 	$this->shared->prep_option($this->input->post('eramount'),'dollar'),
			'date' => 		$this->shared->prep_option($this->input->post('erdate'),'date'),
			'per' => 		$this->shared->prep_option($this->input->post('erper'),'checkbox'),
			'percent' => 	$this->shared->prep_option($this->input->post('erpercent'),'checkbox')
		);
		
		// Serialize and put it into the update array
		$eventdata['earlyreg'] = serialize($earlyregdata);

		$eventdata['activitypreorders'] = $this->shared->prep_option($this->input->post('activitypreorders'),'checkbox');

		$this->db->where('id', $event);
		$this->db->update('event', $eventdata);
		$message = $message.'Score! Options updated.';
		return $message;
	}


	// Get the sessions for an event
	public function get_sessions($event, $session=FALSE)
	{
		if ($session !== FALSE)
		{
			// Get only one session
			$this->db->order_by("datestart","asc");
			$query = $this->db->get_where('sessions', array('eventid' => $event, 'sessionnum' => $session));
			return $query->row_array();
		} else {
			// Get all of the sessions for the event, sorted by the start date
			$this->db->order_by("datestart", "asc");
			$query = $this->db->get_where('sessions', array('eventid' => $event));
			return $query->result_array();
		}
		
		
		show_error('We had a problem getting the sessions for this event. fx get_sessions()');
	}

	// Update the sessions
	public function update_sessions($event, $sessions=FALSE, $count=FALSE)
	{		
		$this->load->helper('string');
		// Update the event session title
		$eventdata['sessiontitle'] = $this->input->post('sessiontitle');

		// Setup
		$sessions = $this->input->post('sessions');
		$insert = array();
		$update = array();
		$insertflag = false;
		$updateflag = false;
		$message = '';

		// Handle the Periods
		$periodraw = $this->input->post('period');
		// enabled[checkbox], opendate[date], closedate[date], type[select:day|week], daytitle[title'] 
		$periodsraw = $this->input->post('periods');
		// loop: [1,2,3,[...]] id[num], label[text] 
		$daysraw = $this->input->post('days');
		// loop: [a,b,c,[...]] id[text], label[text] 
		
		$period = array();
		$i = 1;
    	if ($periodsraw) : foreach ($periodsraw as $p) :
    		$period['periods'][$i] = array(
    			'id' => 		$i,
    			'label' => 		($p['label'] !== '') ? $this->shared->prep_option($p['label'],'text'): $i
    		);
    		$i++;
    	endforeach; endif;
		$i = 'a';
    	if ($daysraw) : foreach ($daysraw as $p) :
    		$period['days'][ucfirst($i)] = array(
    			'id' => 		ucfirst($i),
    			'label' => 		($p['label'] !== '') ? $this->shared->prep_option($p['label'],'text'): ucfirst($i)
    		);
    		$i++;
    	endforeach; endif;

    	$eventdata['activityregs'] = 		(isset($periodraw['enabled'])) ? $this->shared->prep_option($periodraw['enabled'],'checkbox'): 0;
    	$eventdata['activitydate'] = 		(isset($periodraw['opendate'])) ? $this->shared->prep_option($periodraw['opendate'],'date'): 0;
    	$eventdata['activitytime'] = 		(isset($periodraw['closedate'])) ? $this->shared->prep_option($periodraw['closedate'],'num'): 0;
    	$eventdata['activitytype'] = 		(isset($periodraw['type'])) ? $this->shared->prep_option($periodraw['type'],'text'): 'day';
    	$eventdata['activitydaytitle'] = 	(isset($periodraw['daytitle'])) ? $this->shared->prep_option($periodraw['daytitle'],'text'): 0;
    	$eventdata['periods'] = 			serialize($period);
    	$eventdata['bluecards'] = 			(isset($periodraw['bluecards'])) ? $this->shared->prep_option($periodraw['bluecards'],'checkbox'): 0;

		// Message
		$message = 'Updated the periods. ';
    	/* Update the event - we will do this down below, only need one call.
    	$this->db->where('id', $event);
    	$this->db->update('event', $eventdata);
		*/

		// Handle the sessions
		if ($sessions) : foreach ($sessions as $s) :
			// Prepare our entries
			if (isset($s['delete']) && $s['delete'] == 1 && $s['new'] !== 1) {
				// Delete the session
				if (isset($s['id'])) $this->db->delete('sessions', array('id' => $s['id']));
				$message = "Session deleted. ";
			} elseif ($s['new'] == 1) {
				// This is a new session, we'll prep and add it to our insert batch
				if(!isset($s['open'])) $s['open'] = 'off';
				$newentry = array(
					'eventid' 		=> $event,
					'title' 		=> $s['title'],
					'datestart' 	=> $this->shared->prep_option($s['datestart'],'date'),
					'dateend' 		=> $this->shared->prep_option($s['dateend'],'date'),
					'cost' 			=> $this->shared->prep_option($s['cost'],'dollar'),
					'costadult' 	=> $this->shared->prep_option($s['costadult'],'dollar'),
					'costfamily' 	=> $this->shared->prep_option($s['costfamily'],'dollar'),
					'limitsoft' 	=> $s['limitsoft'],
					'limithard' 	=> $s['limithard'],
					'open' 			=> $this->shared->prep_option($s['open'],'checkbox'),
					'sessionnum' 	=> $s['sessionnum']
				);
				array_push($insert,$newentry);
				$insertflag = true;
				unset($newentry);
			} else {
				// This session already exists, we'll prep and add it to our update batch
				if(!isset($s['open'])) $s['open'] = 'off';
				$existingentry = array(
					'id' 			=> $s['id'],
					'eventid' 		=> $event,
					'title' 		=> $s['title'],
					'datestart' 	=> $this->shared->prep_option($s['datestart'],'date'),
					'dateend' 		=> $this->shared->prep_option($s['dateend'],'date'),
					'cost' 			=> $this->shared->prep_option($s['cost'],'dollar'),
					'costadult' 	=> $this->shared->prep_option($s['costadult'],'dollar'),
					'costfamily' 	=> $this->shared->prep_option($s['costfamily'],'dollar'),
					'limitsoft' 	=> $s['limitsoft'],
					'limithard' 	=> $s['limithard'],
					'open' 			=> $this->shared->prep_option($s['open'],'checkbox'),
					'sessionnum' 	=> $s['sessionnum']
				);
				array_push($update,$existingentry);
				$updateflag = true;
				unset($existingentry);
			}
		endforeach; endif;
		
		// Insert our new sessions
		if ($insertflag) { $this->db->insert_batch('sessions', $insert); $message = $message.'Inserted the sessions. '; }
		if ($updateflag) { $this->db->update_batch('sessions', $update, 'id'); $message = $message.'Updated the sessions. '; }
		
		// Do we have atleast 1 session? If not, we'll close the event.
		$sessions = $this->shared->get_sessions($event); 
		if (!$sessions) {
			$eventdata['open'] = 0;
		}

		// Update our groups
		$groupsraw = $this->input->post('groups');
		$groupraw = $this->input->post('group');
		//print_r($groupsraw);
		//print_r($groupraw);
		
		$this->load->helper('string');
		$groups = array();
    	
    	if (is_array($groupraw)) : foreach ($groupraw as $group) :
    		if (isset($group['delete']) && $group['delete'] == '1') continue;
    		$groupkey = ($group['new'] == 1) ? random_string('alnum', 6): $group['id'];
    		$groups[$groupkey] = array(
    			'id' => 	$groupkey,
    			'title' => 		(isset($group['title'])) ? $this->shared->prep_option($group['title'],'text'): null,
    			'desc' => 		(isset($group['desc'])) ? $this->shared->prep_option($group['desc'],'text'): null,
    			'limit' => 		(isset($group['limit'])) ? $this->shared->prep_option($group['limit'],'text'): null,
    			'softlimit' => 	(isset($group['softlimit'])) ? $this->shared->prep_option($group['softlimit'],'text'): null,
    			'cost' => 		(isset($group['cost'])) ? $this->shared->prep_option($group['cost'],'dollar'): null,
    			'perperson' => 	(isset($group['perperson'])) ? $this->shared->prep_option($group['perperson'],'checkbox'): null,
    			'num' => 		(isset($group['num'])) ? $this->shared->prep_option($group['num'],'text'): 0
    		);
    	endforeach; endif;
    	$groupsdata = array(
    		'enabled' => 	$groupsraw['enabled'],
    		'title' => 		$this->shared->prep_option($groupsraw['title'],'text'),
    		'desc' => 		$this->shared->prep_option($groupsraw['desc'],'text'),
    		'limited' => 	(isset($groupsraw['limited'])) ? $this->shared->prep_option($groupsraw['limited'],'checkbox'): 0,
    		'percent' => 	(isset($groupsraw['percent'])) ? $this->shared->prep_option($groupsraw['percent'],'checkbox'): 0,
    		'perunit' => 	(isset($groupsraw['perunit'])) ? $this->shared->prep_option($groupsraw['perunit'],'checkbox'): 0,
    		'show' => 		(isset($groupsraw['show'])) ? $this->shared->prep_option($groupsraw['show'],'checkbox'): 0,
    		'default' => 	(isset($groupsraw['default'])) ? $this->shared->prep_option($groupsraw['default'],'text'): 0,
    		'groups' => 	$groups
    	);
    	// Update the event
    	$groupsdata = serialize($groupsdata);
    	$eventdata['groups'] = $groupsdata;
    	$this->db->where('id', $event);
    	$this->db->update('event', $eventdata);
		
		// Return our messages
		return $message;		
	}

	// Get the options for an event
	public function get_options($event, $option=FALSE)
	{
		if ($option !== FALSE)
		{
			// Get only one session
			$query = $this->db->get_where('options', array('eventid' => $event, 'id' => $option));
			return $query->row_array();
		} else {
			// Get all of the sessions for the event, sorted by the title
			$query = $this->db->get_where('options', array('eventid' => $event));
			return $query->result_array();
		}
		show_error('We had a problem getting the options for this event. fx get_options()');
	}

	// Get the options for an event
	public function get_discounts($event, $discount=FALSE)
	{
		if ($discount !== FALSE)
		{
			// Get only one session
			$query = $this->db->get_where('options', array('eventid' => $event, 'id' => $discount));
			return $query->row_array();
		} else {
			// Get all of the sessions for the event, sorted by the title
			$this->db->order_by("title", "asc");
			$query = $this->db->get_where('discounts', array('eventid' => $event));
			return $query->result_array();
		}
		show_error('We had a problem getting the discounts for this event. fx get_discounts()');
	}

	// Update the sessions
	public function update_custom($event, $sessions=FALSE, $count=FALSE)
	{		
		/* We don't edit event details here, if we do, we'll do this:
		$eventdata = array(
			'sessiontitle' => $this->input->post('sessiontitle')
			// add session count here maybe for quick access?
		);
		$this->db->where('id', $event);
		$this->db->update('event', $eventdata);
		*/

		// Setup
		$insert = array();
		$update = array();
		$options = $this->input->post('options');
		$discounts = $this->input->post('discounts');
		$insertflag = false;
		$updateflag = false;
		$message = '';

		// Process our options
		if ($options) : foreach ($options as $s) :
			// Prepare our entries
			if (isset($s['delete']) && $s['delete'] == 1 && $s['new'] !== 1) {
				// Delete the session
				if (isset($s['id'])) $this->db->delete('options', array('id' => $s['id']));
				$message = "Option deleted. ";
			} elseif ($s['new'] == 1) {
				// This is a new option, we'll prep and add it to our insert batch
				if(!isset($s['verify'])) $s['verify'] = 'off';
				if(!isset($s['checkbox'])) $s['checkbox'] = 'off';
				if(!isset($s['perperson'])) $s['perperson'] = 'off';
				if(!isset($s['percent'])) $s['percent'] = 'off';
				if(!isset($s['value'])) $s['value'] = 'off';
				$newentry = array(
					'eventid' 		=> $event,
					'verify' 		=> $this->shared->prep_option($s['verify'],'checkbox'),
					'title' 		=> $s['title'],
					'description' 	=> $s['description'],
					'amount' 		=> $this->shared->prep_option($s['amount'],'dollar'),
					'perperson' 	=> $this->shared->prep_option($s['perperson'],'checkbox'),
					'checkbox' 		=> $this->shared->prep_option($s['checkbox'],'checkbox'),
					'percent' 		=> $this->shared->prep_option($s['percent'],'checkbox'),
					'value' 		=> $this->shared->prep_option($s['value'],'checkbox'),
					'date' 			=> $this->shared->prep_option($s['date'],'date')
					//'value' 		=> $s['value'],
					//'optionnum' 	=> $s['optionnum']
				);
				array_push($insert,$newentry);
				$insertflag = true;
				unset($newentry);
			} else {
				// This option already exists, we'll prep and add it to our update batch
				if(!isset($s['verify'])) $s['verify'] = 'off';
				if(!isset($s['checkbox'])) $s['checkbox'] = 'off';
				if(!isset($s['perperson'])) $s['perperson'] = 'off';
				if(!isset($s['percent'])) $s['percent'] = 'off';
				if(!isset($s['value'])) $s['value'] = 'off';
				$existingentry = array(
					'id' 			=> $s['id'],
					'eventid' 		=> $event,
					'verify' 		=> $this->shared->prep_option($s['verify'],'checkbox'),
					'title' 		=> $s['title'],
					'description' 	=> $s['description'],
					'amount' 		=> $this->shared->prep_option($s['amount'],'dollar'),
					'perperson' 	=> $this->shared->prep_option($s['perperson'],'checkbox'),
					'checkbox' 		=> $this->shared->prep_option($s['checkbox'],'checkbox'),
					'percent' 		=> $this->shared->prep_option($s['percent'],'checkbox'),
					'value' 		=> $this->shared->prep_option($s['value'],'checkbox'),
					'date' 			=> $this->shared->prep_option($s['date'],'date')
					//'optionnum' 	=> $s['optionnum']
				);
				array_push($update,$existingentry);
				$updateflag = true;
				unset($existingentry);
			}
		endforeach; endif;
		
		// Insert our new options
		if ($insertflag) { $this->db->insert_batch('options', $insert); $message = $message.'Inserted the sessions. '; }
		if ($updateflag) { $this->db->update_batch('options', $update, 'id'); $message = $message.'Updated the sessions. '; }
		
		// Do our discounts now
		unset($insert);
		unset($update);
		unset($s);
		$insert = array();
		$update = array();
		$insertflag = false;
		$updateflag = false;

		// Process our discounts
		if ($discounts) : foreach ($discounts as $s) :
			// Prepare our entries
			if (isset($s['delete']) && $s['delete'] == 1 && $s['new'] !== 1) {
				// Delete the session
				if (isset($s['id'])) $this->db->delete('discounts', array('id' => $s['id']));
				$message = "Discount deleted. ";
			} elseif ($s['new'] == 1) {
				// This is a new discount, we'll prep and add it to our insert batch
				if(!isset($s['verify'])) $s['verify'] = 'off';
				if(!isset($s['checkbox'])) $s['checkbox'] = 'off';
				if(!isset($s['perperson'])) $s['perperson'] = 'off';
				if(!isset($s['percent'])) $s['percent'] = 'off';
				if(!isset($s['individual'])) $s['individual'] = 'off';
				if(!isset($s['value'])) $s['value'] = 'off';
				$newentry = array(
					'eventid' 		=> $event,
					'verify' 		=> $this->shared->prep_option($s['verify'],'checkbox'),
					'title' 		=> $s['title'],
					'description' 	=> $s['description'],
					'amount' 		=> $this->shared->prep_option($s['amount'],'dollar'),
					'perperson' 	=> $this->shared->prep_option($s['perperson'],'checkbox'),
					'checkbox' 		=> $this->shared->prep_option($s['checkbox'],'checkbox'),
					'percent' 		=> $this->shared->prep_option($s['percent'],'checkbox'),
					'date' 			=> $this->shared->prep_option($s['date'],'date'),
					'value' 		=> $this->shared->prep_option($s['value'],'checkbox'),
					'individual' 	=> $this->shared->prep_option($s['individual'],'checkbox'),
					'code' 			=> $s['code']
				);
				array_push($insert,$newentry);
				$insertflag = true;
				unset($newentry);
			} else {
				// This option already exists, we'll prep and add it to our update batch
				if(!isset($s['verify'])) $s['verify'] = 'off';
				if(!isset($s['checkbox'])) $s['checkbox'] = 'off';
				if(!isset($s['perperson'])) $s['perperson'] = 'off';
				if(!isset($s['percent'])) $s['percent'] = 'off';
				if(!isset($s['individual'])) $s['individual'] = 'off';
				if(!isset($s['value'])) $s['value'] = 'off';
				$existingentry = array(
					'id' 			=> $s['id'],
					'eventid' 		=> $event,
					'verify' 		=> $this->shared->prep_option($s['verify'],'checkbox'),
					'title' 		=> $s['title'],
					'description' 	=> $s['description'],
					'amount' 		=> $this->shared->prep_option($s['amount'],'dollar'),
					'perperson' 	=> $this->shared->prep_option($s['perperson'],'checkbox'),
					'checkbox' 		=> $this->shared->prep_option($s['checkbox'],'checkbox'),
					'percent' 		=> $this->shared->prep_option($s['percent'],'checkbox'),
					'date' 			=> $this->shared->prep_option($s['date'],'date'),
					'value' 		=> $this->shared->prep_option($s['value'],'checkbox'),
					'individual' 	=> $this->shared->prep_option($s['individual'],'checkbox'),
					'code' 			=> $s['code']
				);
				array_push($update,$existingentry);
				$updateflag = true;
				unset($existingentry);
			}
		endforeach; endif;
		
		// Insert our new options
		if ($insertflag) { $this->db->insert_batch('discounts', $insert); $message = $message.'Inserted the discounts. '; }
		if ($updateflag) { $this->db->update_batch('discounts', $update, 'id'); $message = $message.'Updated the discounts. '; }

		return $message;		
	}

}

?>