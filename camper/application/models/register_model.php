<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin Register Model
 *
 * This script is the model for the Camper events and registrations sections in 
 * the leader section of the system.
 *
 * Version 1.0 (2012.10.18.0017)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Register_model extends CI_Model {

	public function __construct()
	{
	}

	// Single Event Details, all fields
	public function get_single_event($event = FALSE)
	{
		if ($event === FALSE)
		{
			return false; 
			//show_error('Register Model tried to list events as a leader because the get_single_event() var was false. Oh well.');
		}
		
		$query = $this->db->get_where('event', array('id' => $event));
		return $query->row_array();
		
	}

	// Get all of the events, replace uses with get_events()
	public function get_all_events()
	{
		$query = $this->db->get('event');
		return $query->result_array();
	}
	
	/* Get Events
	 * $type = (false) all or single type (Troops|Teams|Packs|Crews|Ships|Dens|Individuals)
	 * 
	 */ 
	public function get_events($unittype=FALSE,$eventtype=FALSE)
	{
		// Handle ships and teams
		if ($unittype == 'Teams') $unittype = 'Troops';
		if ($unittype == 'Ships') $unittype = 'Crews';
		if ($eventtype && $unittype !== 'All') {
			// Looking for a specific unit type?
			$query = $this->db->get_where('event', array('open'=>1,'eventtype'=>$unittype));
			$query = $query->result_array();
			return $query;

		} elseif ($unittype && $unittype !== 'All') {
			// Looking for eligible events?
			$query = $this->db->get_where('event', array('open'=>1));
			$query = $query->result_array();
			$i = 0;
			$events = array();
			
			// Find eligible events
			foreach ($query as $e) {
				if (isset($e['eligibleunits'])) {
					$e['eligibleunits'] = unserialize($e['eligibleunits']);
					if (in_array($unittype,$e['eligibleunits']) || in_array('Individuals',$e['eligibleunits'])) {
						array_push($events, $e);
						$i++;
					}
				}
			}
		
			// Return our new array or olf if count is 0
			return ($i > 0) ? $events : array();
		}
		
		$query = $this->db->get_where('event', array('open'=>1));
		$query = $query->result_array();
		return $query;
	}
	

	// Single session, all fields
	public function get_single_session($event, $session=FALSE)
	{
		if ($session === FALSE)
		{
			$query = $this->db->get('sessions');
			return $query->result_array();
		}
		
		$query = $this->db->get_where('sessions', array('eventid' => $event, 'sessionnum' => $session));
		$result = $query->row_array();
		if ($result) {
			$result['enable'] = true;
			return $result;
		} else {
			$result = array(
				'id' => '0',
				'enable' => false,
				'title' => '',
				'open' => '0',
				'description' => '',
				'limithard' => '',
				'limitsoft' => '',
				'cost' => '',
				'costadult' => '',
				'costfamily' => '',
				'datestart' => false,
				'dateend' => false,
				'activitypreorders' => 0
			);
			return $result;
		}
	}

	// All sessions for a single event
	// Also in $this->shared
	public function get_sessions($event)
	{
		$this->db->order_by("datestart","asc");
		$query = $this->db->get_where('sessions', array('eventid' => $event));
		$result = $query->result_array();
		if ($result) {
			return $result;
		} else {
			return false;
		}
	}

	/* Get registrations for an individual
	 *
	 *
	 */
	public function get_individual_regs($user=FALSE)
	{
		$query = $this->db->where('individual', 1);
		if ($user)
		{
			$query = $this->db->where('userid', $user);
		}
		$query = $this->db->get('eventregs');
		$result = $query->result_array();
		if ($result) {
			return $result;
		} else {
			return false;
		}
	}

	// Get registrations for an unit
	public function get_unit_regs($unit=FALSE)
	{
		if ($unit)
		{
			$query = $this->db->where('unitid', $unit);
		}
		$query = $this->db->get('eventregs');
		$result = $query->result_array();
		if ($result) {
			return $result;
		} else {
			return false;
		}
	}

	// update the max number of reserved spots if existing one is zero.
	public function recount_max($reg) {
		// get the
		if (!is_array($reg)) {
			$query = $this->db->get_where('eventregs', array('id' => $reg));
			$reg = $query->row_array();
		}
		if ($reg['max'] == 0) {
			$max = $reg['youth'] + $reg['male'] + $reg['female'];
			$this->db->where('id', $reg['id']);
			$this->db->update('eventregs', array('max'=>$max)); 
			return true;
		}
		return false;
	}

	// Update a registration
	public function update_reg($regid, $returnresult=TRUE)
	{
		// Get the old event reg and session
		$query = $this->db->get_where('eventregs', array('id' => $regid));
		$original = $query->row_array();
		$query = $this->db->get_where('sessions', array('id' => $original['session']));
		$session = $query->row_array();
		
		// Prepare the session counts
		//$verify = $this->shared->verify($regid);
		//if ($verify['restricted'] == false) { 
			$count['old'] = $original['count'];
			$count['new'] = $this->shared->prep_option($this->input->post('youth'),'number')+$this->shared->prep_option($this->input->post('male'),'number')+$this->shared->prep_option($this->input->post('female'),'number');
			$count['difference'] = $count['new']-$count['old'];
			$count['session'] = $session['count']+$count['difference'];
			if ($count['new'] > $count['old']) {
				// update the max regs number
				$this->db->where('id', $regid);
				$this->db->update('eventregs', array('max'=>$count['new'])); 
				/* reload our new order
				$query = $this->db->get_where('eventregs', array('id' => $regid));
				$original = $query->row_array();
				*/
				$original['max'] = $count['new'];
			}
		//}
		// Prepare the groups counts
		$group['toomany'] = false;
		if ($this->input->post('fgroup')) {
			// Groups set, setup
			$group['new'] = $this->shared->prep_option($this->input->post('fgroup'),'text');
			$group['old'] = (isset($original['group']) && $original['group'] !== '') ? $original['group'] : $group['new'];
			$group['same'] = ($group['new'] == $group['old']) ? true: false;
			//	if ($group['same']) show_error(serialize(array('new'=>$group['new'],'old'=>$group['old'])));
			//	if (!$group['same']) show_error(serialize(array('new'=>$group['new'],'old'=>$group['old'])));

			if (!$group['same'] || $count['difference'] !== 0) {
				// Either the group changed or the numbers changed
				//if ($group['same']) show_error('is same 2');
				//if (!$group['same']) show_error('not same 2');
				$group['count'] = $this->shared->count_group($group['new'],$session['id'],true);
				$group['newcount'] = ($group['same']) ? $group['count'] + $count['difference']: $group['count'] + $count['new'];
				$group['source'] = $this->shared->get_groups($original['eventid']);
				$group['limit'] = $group['source']['groups'][$group['new']]['limit'];
				
				$group['toomany'] = (is_null($group['limit']) || $group['limit'] == '') ? false: ($group['newcount'] > $group['limit']) ? true: false;
				$group['toomany'] = ($this->ion_auth->is_admin()) ? false : $group['toomany'];
			}
		}
		
		// Prep the options and discounts
		$postoptions = "";
		$postdiscounts = "";
		if ($this->input->post('options')) {
			$postoptions = serialize($this->input->post('options'));
		}
		if ($this->input->post('discounts')) {
			$postdiscounts = serialize($this->input->post('discounts'));
		}
		
		// Update
		if ($this->ion_auth->is_admin() && ($this->input->post('submit') == 'Update Time') && $this->input->post('time')) {
			// Update the time from the admin side of things 
			$registrar = unserialize($original['registerdate']);
			$timestamp = strtotime($this->input->post('time'));
			$regdata['registerdate'] = serialize(array(
				'user' => $registrar['user'],
				'time' => $timestamp
			));
			$this->db->where('id', $regid);
			$this->db->update('eventregs', $regdata);
			
			// Call the record we just made
			if ($returnresult === TRUE) {
				$query = $this->db->get_where('eventregs', array('id' => $regid));
				$result = $query->row_array();
				return $result;
			} else {
				return true;
			}

		// Update
		} elseif ($this->ion_auth->is_admin() && ($this->input->post('submit') == 'Change Session') && $this->input->post('session')) {
			// Update the time from the admin side of things 
			$regdata['session'] = $this->input->post('session');
			$this->db->where('id', $regid);
			$this->db->update('eventregs', $regdata);
			
			// Call the record we just made
			if ($returnresult === TRUE) {
				$query = $this->db->get_where('eventregs', array('id' => $regid));
				$result = $query->row_array();
				return $result;
			} else {
				return true;
			}

		} elseif ($count['session'] > $session['limithard']) {
			// Make sure we don't go over our hard limit
			return 'Unfortunately, you added more participants than we have space for. We reset your registration numbers to the previous amounts.';
		} elseif ($group['toomany']) {
			// Make sure we don't go over our group limit
			return 'Unfortunately, you added more participants than we have space for in the <strong>'.$group['source']['groups'][$group['new']]['title'].' '.$group['source']['title'].'</strong>. We reset your '.$group['source']['title'].' and registration numbers to the previous amounts.';
		} else {
			// Prepare our new data
			$regdata = array(
				'count' => $count['new'],
				'youth' => $this->shared->prep_option($this->input->post('youth'),'number'),
				'male' => $this->shared->prep_option($this->input->post('male'),'number'),
				'female' => $this->shared->prep_option($this->input->post('female'),'number'),
				'family' => $this->shared->prep_option($this->input->post('family'),'number'),
				'activitypreorders' => $this->shared->prep_option($this->input->post('activitypreorders'),'checkbox'),
				'bluecards' => $this->shared->prep_option($this->input->post('bluecards'),'checkbox'),
				'options' => $postoptions,
				'discounts' => $postdiscounts
			);
			if ($this->input->post('latefeeflag') == 1) {
				$regdata['latefeeexempt'] = $this->shared->prep_option($this->input->post('latefee'),'checkbox');
			}	
			// Handle the groups
			if ($this->input->post('fgroup')) {
				$regdata['group'] = $this->shared->prep_option($this->input->post('fgroup'),'text');
			}

			// Add to the database
			$this->db->where('id', $regid);
			$this->db->update('eventregs', $regdata);
	
			/* Update the session's registration count
			// BROKEN?
			$this->db->where('id', $original['session']);
			$this->db->update('sessions', array('count' => $count['session']));
			*/
			
			// Call the record we just made
			if ($returnresult === TRUE) {
				$query = $this->db->get_where('eventregs', array('id' => $regid));
				$result = $query->row_array();
				return $result;
			} else {
				return true;
			}
		}
	}

	/* Delete or Inactivate Reg
	 * 
	 * $regid = (required) id
	 * $user = (required) user id, for notification and record of removal
	 * $inactivate = (false) force inactivate 
	 * Return bool
	/*/
	public function delete_reg($regid, $user, $inactivate=FALSE)
	{
		show_error('You can not delete registrations in Camper at this time. Contact council for more details. fx register_model delete_reg 254');
		// Get the original reg and session
		$query = $this->db->get_where('eventregs', array('id' => $regid));
		$original = $query->row_array();
		$query = $this->db->get_where('sessions', array('id' => $original['session']));
		$session = $query->row_array();
		
		// Clean reg or does it have dependencies?
		$clean = true;
		/*
		if payments, false
		if activity regs
		if ...


		// Remove our numbers from the count
		/* Not important yet
		$count['old'] = $original['count'];
		$count['session'] = $session['count']-$count['old'];
		$this->db->where('id', $original['session']);
		$this->db->update('sessions', array('count' => $count['session']));
		*/
		

		/*
		// Delete or inactivate registration
		if ($inactivate !=== false || $clean === false) {
			// Reg is not clean or forced inactivate
			// Inactivate the reg
			show_error('Reg '.$regid.' to be inactivated, will remain in the system');
		} else {
			show_error('Reg '.$regid.' to be deleted');
			$this->db->delete('eventregs', array('id' => $regid));
		}
		// Notifications 
			// notify using the user as responsible
		// Payment refunds
		// Unregister for activities
		// And more...
		
		return true;
		*/
	}

	// Create an event
	public function create_event()
	{
		// Setup 
		$timestamp = time();
		$eventdata = array(
			'title' => $this->input->post('title'),
			'eventtype' => $this->input->post('eventtype'),
			'location' => $this->input->post('location'),
			'description' => $this->input->post('description'),
			'activityregs' => 0,
			'regcount' => 0,
			'datestart' => strtotime($this->input->post('datestart')),
			'dateend' => strtotime($this->input->post('dateend')),
			'open' => 0,
			'earlyreg' => null,
			'sessiontitle' => 'Week',
			'freeadults' => null,
			'paymenttiers' => null,
			'eligibleunits' => null,
			'timestamp' => $timestamp
		);
		
		// Add to the database
		$this->db->insert('event', $eventdata);
		
		// Call the record we just made
		$query = $this->db->get_where('event', array('timestamp' => $timestamp));
		$newevent = $query->row_array();
		return $newevent['id'];
	}

	// Update an event
	public function update_event($event)
	{
		$message = '';
		
		$eventdata = array();
		if ($this->input->post('title')) $eventdata['title'] = $this->input->post('title');
		if ($this->input->post('eventtype')) $eventdata['eventtype'] = $this->input->post('eventtype');
		if ($this->input->post('location')) $eventdata['location'] = $this->input->post('location');
		if ($this->input->post('description')) $eventdata['description'] = $this->input->post('description');
		//if ($this->input->post('activityregs')) $eventdata['activityregs'] = $this->input->post('activityregs');
		//if ($this->input->post('regcount')) $eventdata['regcount'] = $this->input->post('regcount');
		if ($this->input->post('datestart')) $eventdata['datestart'] = strtotime($this->input->post('datestart'));
		if ($this->input->post('dateend')) $eventdata['dateend'] = strtotime($this->input->post('dateend'));
		if ($this->input->post('open') == 'on') { $eventdata['open'] = '1'; } else { $eventdata['open'] = '0'; }
		if ($this->input->post('sessiontitle')) $eventdata['sessiontitle'] = $this->input->post('sessiontitle');
		//if ($this->input->post('freeadults')) $eventdata['freeadults'] = $this->input->post('freeadults');
		//if ($this->input->post('earlyreg')) $eventdata['earlyreg'] = $this->input->post('earlyreg');
		//if ($this->input->post('paymenttiers')) $eventdata['paymenttiers'] = $this->input->post('paymenttiers');
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
			'r' => 			prep_option($this->input->post('r'),'checkbox'),
			'ramount' => 	prep_option($this->input->post('ramount'),'dollar'),
			'rper' => 		prep_option($this->input->post('rper'),'checkbox'),
			'rrefund' => 	prep_option($this->input->post('rrefund'),'checkbox'),

			'f' => 			prep_option($this->input->post('f'),'checkbox'),
			'famount' => 	prep_option($this->input->post('famount'),'dollar'),
			'fdate' => 		prep_option($this->input->post('fdate'),'date'),
			'fpercent' => 	prep_option($this->input->post('fpercent'),'checkbox'),
			'fper' => 		prep_option($this->input->post('fper'),'checkbox'),

			's' => 			prep_option($this->input->post('s'),'checkbox'),
			'samount' => 	prep_option($this->input->post('samount'),'dollar'),
			'sdate' => 		prep_option($this->input->post('sdate'),'date'),
			'spercent' => 	prep_option($this->input->post('spercent'),'checkbox'),
			'sper' => 		prep_option($this->input->post('sper'),'checkbox'),

			'n' => 			prep_option($this->input->post('n'),'checkbox'),
			'namount' => 	prep_option($this->input->post('namount'),'dollar'),
			'ndate' => 		prep_option($this->input->post('ndate'),'date'),
			'npercent' => 	prep_option($this->input->post('npercent'),'checkbox'),
			'nper' => 		prep_option($this->input->post('nper'),'checkbox'),

			'l' => 			prep_option($this->input->post('l'),'checkbox'),
			'lamount' => 	prep_option($this->input->post('lamount'),'dollar'),
			'ldate' => 		prep_option($this->input->post('ldate'),'date'),
			'lpercent' => 	prep_option($this->input->post('lpercent'),'checkbox'),
			'lper' => 		prep_option($this->input->post('lper'),'checkbox')
		);
		
		// Serialize and put it into the update array
		$eventdata['paymenttiers'] = serialize($paymenttiersdata);

		// Prepare the free adults data
		$freeadultsdata = array(
			'enabled' => 		prep_option($this->input->post('faenabled'),'checkbox'),
			'amount' => 		prep_option($this->input->post('faamount'),'dollar'),
			'threshold' => 		prep_option($this->input->post('fathreshold'),'dollar'),
			'dollar' => 		prep_option($this->input->post('fadollar'),'checkbox'),
			'description' => 	prep_option($this->input->post('fadescription'),'text')
		);
		
		// Serialize and put it into the update array
		$eventdata['freeadults'] = serialize($freeadultsdata);

		// Prepare the early registration data
		$earlyregdata = array(
			'enabled' => 	prep_option($this->input->post('erenabled'),'checkbox'),
			'amount' => 	prep_option($this->input->post('eramount'),'dollar'),
			'date' => 		prep_option($this->input->post('erdate'),'date'),
			'per' => 		prep_option($this->input->post('erper'),'checkbox')
		);
		
		// Serialize and put it into the update array
		$eventdata['earlyreg'] = serialize($earlyregdata);

		$eventdata['activitypreorders'] = prep_option($this->input->post('activitypreorders'),'checkbox');
		
		$this->db->where('id', $event);
		$this->db->update('event', $eventdata);
		$message = $message.'Score! Options updated.';
		return $message;
	}

	// Update a session
	public function update_session($event, $count)
	{		
		$message = '';
		if ($this->input->post('s') == 1) {
			$message = '';
			$s = 's'.$count;
			
			// Is this a new session?
			if ($this->input->post($s) == 'on' && $this->input->post($s.'id') == 0) {
				
				$sessiondata = array(
					'eventid' => $event,
					'title' => $this->input->post($s.'title'),
					'open' => $this->input->post($s.'open'),
					'description' => '',
					'limithard' => $this->input->post($s.'limithard'),
					'limitsoft' => $this->input->post($s.'limitsoft'),
					'cost' => $this->input->post($s.'cost'),
					'costadult' => $this->input->post($s.'costadult'),
					'costfamily' => $this->input->post($s.'costfamily'),
					'datestart' => strtotime($this->input->post($s.'datestart')),
					'dateend' => strtotime($this->input->post($s.'dateend')),
					'sessionnum' => $count
				);
				
				$this->db->insert('sessions', $sessiondata);
				
				$message = $message.'<br /><br />Added Session #'.$count.' without a problem!';

			} else {
			
				// Special Code for checkboxes.
				if ($this->input->post($s.'open')) {
					if($this->input->post($s.'open') == 'on') { $sessiondata['open'] = 1; } else { $sessiondata['open'] = 0; }
				} else {
					$sessiondata['open'] = 0;
				}
				
				// The Rest of the lot.
				if (prep_session_post($this->input->post($s.'title'))) $sessiondata['title'] = $this->input->post($s.'title');
				if (prep_session_post($this->input->post($s.'description'))) $sessiondata['description'] = $this->input->post($s.'description');
				if (prep_session_post($this->input->post($s.'limithard'))) $sessiondata['limithard'] = $this->input->post($s.'limithard');
				if (prep_session_post($this->input->post($s.'limitsoft'))) $sessiondata['limitsoft'] = $this->input->post($s.'limitsoft');
				if (prep_session_post($this->input->post($s.'cost'))) $sessiondata['cost'] = $this->input->post($s.'cost');
				if (prep_session_post($this->input->post($s.'costadult'))) $sessiondata['costadult'] = $this->input->post($s.'costadult');
				if (prep_session_post($this->input->post($s.'costfamily'))) $sessiondata['costfamily'] = $this->input->post($s.'costfamily');
				if (prep_session_post($this->input->post($s.'datestart'))) $sessiondata['datestart'] = strtotime($this->input->post($s.'datestart'));
				if (prep_session_post($this->input->post($s.'dateend'))) $sessiondata['dateend'] = strtotime($this->input->post($s.'dateend'));
				
				// Beam it up to the enterprise if we actually did anything
				if (empty($sessiondata)) {
		    		$message = $message;
		    	} else {
					$this->db->where('id', $this->input->post($s.'id'));
					$this->db->update('sessions', $sessiondata);
					$message = $message.'';
		    	}
			}
		}
		return $message;
	}

	// Create an event registration
	public function register($unit, $event, $session, $user, $redirect=TRUE, $admin=FALSE, $customtime=FALSE, $group=FALSE, $individual=FALSE, $individualid=FALSE)
	{
		// Setup 
		$timestamp = ($customtime === false) ? time(): $customtime;
		$token = md5(microtime().random_string('alnum', 4));
		$registrar = serialize(array(
			'user' => $user,
			'time' => $timestamp
		));
		$groupsflag = false;

		// Make sure we have a session set
		if (empty($session)) {
			$sessions = $this->shared->get_sessions($event); 
			if (!$sessions) {
				// This event has no sessions to register for, blast an error.
				show_error('There are no open sessions to register for in this event. fx register get_registration 1');
			} elseif (count($sessions) == 1) {
				// This event has one session, we'll take it.
				$session = $sessions[0]['id'];
			} elseif (count($sessions) > 1) {
				// This event has multiple sessions, we don't know which one so we'll send them back with an error.
				$this->session->set_flashdata('message', "Please choose a session/week when registering for an event.");
				redirect("events/all", 'refresh');
			} else {
				// How did they end up here?
				show_error('Unfortunately, we couldn\'t register you, please try again. fx register get_registration 2');
			}
		}

		// Verify our session is part of this event
		if ($admin===FALSE) {
			$session = $this->shared->get_sessions($event,$session); 
			if ($group !== false) {
				// Setup groups 
				$eventraw = $this->shared->get_event($event);
				$groups = (isset($eventraw['groups'])) ? unserialize($eventraw['groups']): false;
				if ($groups === false || !isset($groups['groups'][$group]['title']) || $groups['enabled'] !== '1') {
					$this->session->set_flashdata('message', "We were unable to register you for the group you specified. Please choose another group or contact us.");
					if ($redirect) { redirect("events/all", 'refresh'); } else { return false; }
				}
				// Has limit?
				if (is_null($groups['groups'][$group]['limit']) || $groups['groups'][$group]['limit'] == '') {
					$groups['__openspots'] = $session['limithard']-$session['count'];
				} else {
					$groups['__openspots'] = $groups['groups'][$group]['limit']-$this->shared->count_group($group,$session['id'],true);
				}
				// Full?
				if ($groups['__openspots'] > 0) {
					// Group has space
					$groups['__message'] = $groups['groups'][$group]['title'].' has '.$groups['__openspots'].' open spots';
					$groupsflag = false;
				} else {
					// Group is full
					$groups['__message'] = $groups['groups'][$group]['title'].' is full, please try another'.$groups['title'].' <i class="icon-question-sign camperhoverpopover" data-toggle="popover" title="'.$groups['title'].' is Full" data-placement="top" data-content="'.$eventraw['title'].' has multiple '.$groups['title'].'s and the one you tried to register for, <strong>'.$groups['groups'][$group].'</strong>, was full. Please try registering for another '.$groups['title'].' or the same one in another '.$eventraw['sessiontitle'].'. Thanks!"></i>';
					$groupsflag = ($this->ion_auth->is_admin()) ? false : true;
				}
			}
			if (!$session) {
				$this->session->set_flashdata('message', "We were unable to register you for the session you specified. Please try again.");
				if ($redirect) { redirect("events/all", 'refresh'); } else { return false; }
			}
			if ($session['open'] == 0) {
				// Session is closed
				$this->session->set_flashdata('message', "The session you tried to register for is not open for registration.");
				if ($redirect) { redirect("events/all", 'refresh'); } else { return false; }
			} elseif ($session['count'] >= $session['limitsoft']) {
				// Session is full
				$this->session->set_flashdata('message', "Unfortunately, the session you tried to register for is full.");
				if ($redirect) { redirect("events/all", 'refresh'); } else { return false; }
			} elseif ($groupsflag) {
				// Chosen group is full
				$this->session->set_flashdata('message', $groups['__message']);
				if ($redirect) { redirect("events/all", 'refresh'); } else { return false; }
			}
		}
		
		// Handle the userid if admin is registering this person
		$userid = ($individualid === false) ? $user: $individualid;

		if ($individual === false) {
			// Normal Registration
			$regdata = array(
				'unitid' => $unit,
				'eventid' => $event,
				'youth' => 0,
				'male' => 0,
				'family' => 0,
				'female' => 0,
				'max' => 0,
				'registerdate' => $registrar,
				'session' => (is_array($session)) ? $session['id']: $session,
				'discounts' => null,
				'options' => null,
				'group' => ($group === false) ? null: $group,
				'token' => $token,
				'individual' => 0
			);
		} else {
			// Individual Registration
			$regdata = array(
				'unitid' => 0,
				'eventid' => $event,
				'youth' => 0,
				'male' => 0,
				'family' => 0,
				'female' => 0,
				'max' => 0,
				'registerdate' => $registrar,
				'session' => (is_array($session)) ? $session['id']: $session,
				'discounts' => null,
				'options' => null,
				'group' => ($group === false) ? null: $group,
				'token' => $token,
				'userid' => $userid,
				'individual' => 1
			);
		}
		
		// Add to the database
		$this->db->insert('eventregs', $regdata);
		
		// Call the record we just made
		$query = $this->db->get_where('eventregs', array('token' => $token));
		$newreg = $query->row_array();

		// Notify admin
		$definitions['r'] = $newreg['id'];
		
		$reg = $this->data->get_regs($newreg['id'],true);
		
		$definitions['i'] = $reg['eventid']['id'];
		$definitions['e'] = $reg['eventid']['title'];
		$definitions['s'] = (isset($reg['session']['title']) && $reg['session']['title'] !== '') ? $reg['session']['title']: $reg['eventid']['sessiontitle'].' '.$reg['session']['sessionnum'];
		$definitions['w'] = ($regdata['individual'] == 1) ? $reg['userid']['first_name'].' '.$reg['userid']['last_name'].' (individual)': $reg['unitid']['unittitle'].' ('.$reg['unitid']['city'].', '.$reg['unitid']['state'].')';

		$this->shared->notify('newregistration',$definitions,false,'admin');
		
		return $newreg['id'];
	}
	
	// Update Discounts on the single_roster page
	public function update_single_roster_discounts()
	{
		if ($this->input->post('updatediscounts') && $this->input->post('roster')) {
			$discounts = ($this->input->post('discounts') === false) ? '': serialize($this->input->post('discounts'));
			$this->db->where('id', $this->input->post('roster'));
			$this->db->update('roster', array('discounts' => $discounts));
			return true;
		} else {
			return false;
		}
	}

	// Get an event registration
	public function get_registration($unit, $event, $session, $full=FALSE, $individual=FALSE)
	{
		if ($individual === false) {
			$query = $this->db->get_where('eventregs', array('unitid' => $unit, 'eventid' => $event));
		} else {
			$query = $this->db->get_where('eventregs', array('userid' => $unit, 'eventid' => $event));
		}
		$reg = $query->row_array();
		if ($full === FALSE) {
			if (empty($reg['id'])) {
				return false;
			} else {
				return $reg['id'];
			}
		} else {
			if (empty($reg['id'])) {
				return false;
			} else {
				return $reg;
			}
		}
	}
}
?>