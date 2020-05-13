<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Shared Model
 *
 * This model contains functions that are used in multiple parts of the site allowing
 * a single spot for them instead of having duplicate functions all over.
 *
 * Version 1.0 (2012.10.18.0017)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class shared extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->library('mandrill');
		$this->load->library('session');
	}

	// Prepare and send a notification via mandrill
	public function start_mandrill()
	{
		// Mandrill setup
		$mandrill_ready = NULL;
		try {
			$this->mandrill->init( 'l1A0goZdxE96228GQhKQ7Q' );
			$mandrill_ready = TRUE;
		} catch(Mandrill_Exception $e) {
			$mandrill_ready = FALSE;
		}
		return $mandrill_ready;
	}

	// Prepare and send a notification via mandrill
	public function go_mandrill($message,$recipients=false)
	{
		/* Send some email. Format the source as:
		$email = array(
			'html' => '<p>This is my message<p>', //Consider using a view file
			'text' => 'This is my plaintext message',
			'subject' => 'This is my subject',
			'from_email' => 'me@ohmy.com',
			'from_name' => 'Me-Oh-My',
			'to' => array(array('email' => 'joe@example.com' )) //Check documentation for more details on this one
			//'to' => array(array('email' => 'joe@example.com' ),array('email' => 'joe2@example.com' )) //for multiple emails
			);
		*/
		//show_error(serialize($message));
		$this->mandrill->messages_send($message);
	}

	/* Send a message to users via mandrill
	 * $message[title] = (required) title and message subject
	 * $message[message] = (required) the content of the message, only text.
	 * $message[link] = (false or string) the link (camper relative or the main page for false)
	 * $message[youareregistered] = (false or string) You got this email becaus you are registered for this event.
	 * $users = mandrill to array = array(array(email,name,type=to|cc|bcc))
	 * $from[name] = (required) Full name
	 * $from[email] = (required) email address
	 */
	public function send_group_message($message, $users, $from)
	{
		// Send error report
		$data['message'] = $message;
		$data['token'] = md5(microtime().random_string('alnum', 4));
		$data['from'] = $from;
		$html = $this->load->view('notifications/groupmessage', $data, true);
		$email = array(
			'html' => $html, //Consider using a view file
			'subject' => 'Camper - '.$message['title'],
			'from_email' => $from['email'],
			'from_name' => $from['email'].' via Camper',
			'to' => $users // Check documentation for more details on this one
			//'to' => array(array('email' => 'sean@zilifone.net' )) // Check documentation for more details on this one
			);
		$this->create_transient($data['token'],$html,'email');
		if ($this->start_mandrill()) {
			$this->mandrill->messages_send($email);
			return true;
		} else {
			return false;
		}
	}

	// Send an error notification via mandrill
	public function error_mandrill($message, $function, $debugarray=array())
	{
		// Send error report
		$data['message'] = $message;
		$data['function'] = $function;
		$data['errorarray'] = $debugarray;
		$data['uri'] = uri_string();
		$data['server'] = $_SERVER;
		$data['request'] = $_REQUEST;
		$html = $this->load->view('notifications/autoerror', $data, true);
		$email = array(
			'html' => $html, //Consider using a view file
			'text' => 'Someone encountered an error on Camper. The error was "'.$message.'". That\'s all we know. Cheers, Camper.',
			'subject' => 'Camper Error',
			'from_email' => 'camper@camperapp.org',
			'from_name' => 'Camper Website',
			'to' => array(array('email' => 'sean@zilifone.net' )) // Check documentation for more details on this one
			);
		if ($this->start_mandrill()) $this->mandrill->messages_send($email);
		//$this->shared->error_mandrill('You can\'t register for an event when the session is not part of that event.','fx register->register()',$regdata);
	}

	// Send an error notification via mandrill
	public function send_notification($message, $subject, $from=false, $tofirst, $tolast, $toemail, $link=false, $notificationid=false)
	{
		if (!$link) $link = '';
		$data = array(
			'message'			=> $message,
			'subject'			=> $subject,
			'from'				=> $from,
			'tofirst'			=> $tofirst,
			'tolast'			=> $tolast,
			'toemail'			=> $toemail,
			'link'				=> site_url($link),
			'notificationid'	=> $notificationid
		);
		$emailhtml = $this->load->view('notifications/general', $data, true);
		$email = array(
			'html' => $emailhtml,
			'text' => 'Hi '.$tofirst.', '.$message.' '.$link,
			'subject' => $subject,
			'from_email' => $this->config->item('camper_fromemail'),
			'from_name' => $from.' via '.$this->config->item('camper_fromname'),
			'to' => array(array('email' => $toemail ))
		);
		if (is_array($toemail)) $email['to'] = $toemail;
		if ($from === false) $email['from_name'] = $this->config->item('camper_fromname');
		$this->start_mandrill();
		$this->go_mandrill($email);
	}
	
	// Handle permissions and redirects if not authorized
	public function check_auth($minimum='public',$redirect='dashboard') 
	{
		// Setup
		$uri = uri_string();
		if ($minimum == 'public') {
			return true;
		} elseif ($minimum == 'members' || $minimum == 'staff') {
			if (!$this->ion_auth->logged_in()) {
				redirect('signin?go='.$uri, 'refresh');
			} elseif (!$this->ion_auth->in_group($minimum) && !$this->ion_auth->is_admin()) {
				redirect($redirect, 'refresh');
			} else {
				return true;
			}
		} elseif ($minimum == 'admin') {
			if (!$this->ion_auth->logged_in()) {
				redirect('signin?go='.$uri, 'refresh');
			} elseif (!$this->ion_auth->is_admin()) { 
				redirect($redirect, 'refresh'); 
			} else {
				return true;
			}
		}
	}

	// Post the pdf build payload to the database and forward to the build api
	public function save_build($payload, $instructions, $method="html") 
	{
		// Setup
		$key = md5(microtime());
		$data = array(
			'unique' => $key,
			'payload' => $payload,
			'instructions' => serialize($instructions),
			'time' => time()
		);
		$this->db->insert('build', $data);
		redirect('api/v2/build/'.$method.'/'.$key, 'refresh');
	}

	// Single Event Details, all fields
	public function get_current_unit($unit, $user)
	{
		$query = $this->db->get_where('unit', array('id' => $unit));
		$result = $query->row_array();
		if ($user == $result['primary'] || $user == $result['alt']) {
			// Add some handy values
			
			return $result;
		} else {
			show_error('You must be a contact of a unit to see this page. fx shared get_current_unit');
			return false;
		}
	}

	// Get a single unit by id
	public function get_single_unit($unit)
	{
		$query = $this->db->get_where('unit', array('id' => $unit));
		return $query->row_array();
	}

	// Get all units or single unit. 
	public function get_units($unit=FALSE,$relational=FALSE)
	{
		if ($unit === false) {
			$query = $this->db->get('unit');
			if ($relational === false) {
				return $query->result_array();
			} else {
				$temp = $query->result_array();
				$units = array();
				foreach ($temp as $u) { $units[$u['id']] = $u;}
			}
			return $units;
		} else {
			$query = $this->db->get_where('unit', array('id' => $unit));
			return $query->row_array();
		}
	}

	// Get user's name by id
	public function get_unit_name($id, $unit = FALSE)
	{
		if ($unit === false) {
			$query = $this->db->get_where('unit', array('id' => $id));
			$query = $query->row_array();
		} else {
			$query = $unit;
		}
		return (isset($query['associatedunit']) && $query['associatedunit'] !== '0' ) ? $query['associatedunit'].' '.$query['associatednumber'].' ('.$query['unittype'].' '.$query['number'].')': $query['unittype'].' '.$query['number'];
	}

	// Get user's name by id
	public function get_user_name($id,$full=FALSE)
	{
		$query = $this->db->get_where('auth_users', array('id' => $id));
		$query = $query->row_array();
		return ($full === FALSE) ? $query['first_name'] : $query['first_name'].' '.$query['last_name'];
	}

	/* Is this use an individual or part of a unit? 
	 * $user (false) false, id or object from ionauth
	 * return (FALSE) bool or return unit details
	 */
	public function is_individual($user=FALSE,$details=FALSE)
	{
		// Get our user if not set
		if ($user === false) {
			$row = $this->ion_auth->user()->row();
		} elseif (is_object($user)) {
			$row = $user;
		} else {
			$row = $this->ion_auth->user($user)->row();
		}
		
		// Return our unit details or bool
		if ($row->company == '0') {
			return ($details) ? unserialize($row->individualdetails): true;
		} else {
			if ($details) {
				$unit = $this->get_units($row->company);
				return $unit;
			} else {
				return false;
			}
		}
	}

	/* Is this an individual registration? 
	 * $reg id or array
	 * return bool
	 */
	public function is_individual_reg($reg,$details=FALSE)
	{
		if (!is_array($reg)) {
			$query = $this->db->get_where('eventregs', array('id' => $regid));
			$reg = $query->row_array();
		}
		return ($reg['userid'] == '0') ? false: true;
	}

	/* Get users
	 * $unit = (false) users from a single unit or all
	 * $type = (false) users of a certain type (individual or all) (eventually handle ion auth groups here)
	 * $active = (all|inactive|active)
	 * return result_array
	 */
	public function get_users($unit=FALSE,$type=FALSE,$active='all',$relational=FALSE)
	{
		// Get the users
		if ($unit) {
			$this->db->where('company', $unit);
		}
		if ($type == 'individual') {
			$this->db->where('company', '0');
		}
		if ($active == '1') {
			$this->db->where('active', 1);
		} elseif ($active == '0') {
			$this->db->where('active', 2);
		}
		
		$query = $this->db->get('auth_users');
		//$result = $query->result_array();
		
		if ($relational === false) {
			return $query->result_array();
		} else {
			$temp = $query->result_array();
			$result = array();
			foreach ($temp as $u) { $result[$u['id']] = $u;}
			return $result;
		}
	}

	// Get the leader from an unit
	public function get_unit_leader($unit,$contact="primary",$all=NULL)
	{
		// Get the unit
		$query = $this->db->get_where('unit', array('id' => $unit));
		$query = $query->row_array();
		// Get the user
		if ($contact == "primary") {
			$field = "primary";
		} elseif ($contact == "alternate") {
			$field = "alt";
		}
		$query = $this->db->get_where('auth_users', array('id' => $query[$field]));
		$query = $query->row_array();
		if (count($query) == 0) return false;
		if (isset($all)) {
			return $query;
		} else {
			return $query['id'];
		}
	}

	// All sessions for a single event
	public function get_sessions($event, $session=FALSE)
	{
		if ($session === FALSE) {
			// Get all sessions for the event
			$query = $this->db->get_where('sessions', array('eventid' => $event));
			$result = $query->result_array();
		} else {
			// Get only one session
			$query = $this->db->get_where('sessions', array('eventid' => $event, 'id' => $session));
			$result = $query->row_array();
		}
		if ($result) {
			return $result;
		} else {
			return false;
		}
	}

	// Figure out if someone is an adult or youth
	public function is_youth($age,$unittype,$returnbool=false)
	{
		$unittype = strtolower($unittype);
		$cutoff = ($unittype == 'crew' || $unittype == 'ship') ? (31556926 * 21): (31556926 * 18);
		$return = ((time() - $age) < $cutoff) ? true: false;
		if ($returnbool === false) {
			return ($return) ? 'Youth': 'Adult';
		} else {
			return $return;
		}
	}

	// All sessions for a single event
	public function get_session_event($session, $full=FALSE)
	{
		// Get our session
		$query = $this->db->get_where('sessions', array('id' => $session));
		$result = $query->row_array();

		if ($full === FALSE) {
			return $result['eventid'];
		} else {
			return $result;
		}
	}
	
	// Single Event Details, all fields
	public function get_event($event = FALSE)
	{
		if ($event === FALSE)
		{
			$query = $this->db->get('event');
			return $query->result_array();
		}
		$query = $this->db->get_where('event', array('id' => $event));
		return $query->row_array();		
	}

	// Get Regs
	public function get_regs($event=FALSE,$relational=FALSE)
	{
		if ($event === FALSE)
		{
			$query = $this->db->get('eventregs');
		}
		
		$query = $this->db->get_where('eventregs', array('eventid' => $event));

		if ($relational === false) {
			return $query->result_array();
		} else {
			$temp = $query->result_array();
			$result = array();
			foreach ($temp as $e) { $result[$e['id']] = $e;}
			return $result;
		}

	}

	// Get and unserialize groups for an event, return a single group is requested... not done.
	public function get_groups($event, $group=FALSE)
	{
		// Get our event
		$query = $this->db->get_where('event', array('id' => $event));
		$event = $query->row_array();
		
		// Get the group, if set and enabled
		$groups = (isset($event['groups'])) ? unserialize($event['groups']): false;

		if ($groups !== false && $groups['enabled'] == '1') {
			$result = $groups;
		} else {
			$result = false;
		}
		
		// Return
		return $result;
	}

	// Get registration record, session and event
	public function get_reg_set($unit=FALSE, $regid, $omitreg=FALSE, $options=NULL, $individual=FALSE, $user=FALSE) 
	{
		// Get our event registration record
		$query = $this->db->get_where('eventregs', array('id' => $regid));
		$reg = $query->row_array();

		// Get our unit if it was not provided
		if ($reg['unitid'] == '0') {
			$individual = true;
			$user = ($user===false) ? $this->ion_auth->user($reg['userid'])->row(): $user;
			$unit = unserialize($user->individualdata);
		} else {
			$unit = ($unit===false) ? $this->get_single_unit($reg['unitid']): $unit;
			$user = false;
			$individual = false;
		}

		// Let's make sure this didn't return an empty
		if (!$reg) {
			$message = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->session->set_flashdata('message', $message);
			redirect('events', 'refresh'); 
			//show_error('This event registration was either not found or you are not allowed to view it. fx shared get_reg_set');	
		}
			
		// Get our session
		$query = $this->db->get_where('sessions', array('id' => $reg['session'], 'eventid' => $reg['eventid']));
		$session = $query->row_array();
		
		// Get our event
		$query = $this->db->get_where('event', array('id' => $reg['eventid']));
		$event = $query->row_array();
		
		// Get our options and discounts if requested
		if (isset($options)) {
			$query = $this->db->get_where('options', array('eventid' => $reg['eventid']));
			$options = $query->result_array();
			$query = $this->db->get_where('discounts', array('eventid' => $reg['eventid']));
			$discounts = $query->result_array();
		} else {
			$options = $options;
			$discounts = $options;
			//$codes = $options; // Someday
		}
		
		// Prep and return
		if ($session && $event && $reg) {
			$result = array(
				'unit' => $unit,
				'regid' => $regid,
				'reg' => $reg,
				'session' => $session,
				'event' => $event,
				'options' => $options,
				'discounts' => $discounts,
				'user' => $user,
				'individual' => $individual
			);
			return $result;
		} else {
			return false;
		}
	}

	// Get registration record, session and event
	public function get_reg_set_titles($regid)
	{
		// Get our event registration record
		$query = $this->db->get_where('eventregs', array('id' => $regid));
		$reg = $query->row_array();

		// Let's make sure this didn't return an empty
		if (!$reg) {
			return false;
		}
			
		// Get our session
		$query = $this->db->get_where('sessions', array('id' => $reg['session'], 'eventid' => $reg['eventid']));
		$session = $query->row_array();
		
		// Get our event
		$query = $this->db->get_where('event', array('id' => $reg['eventid']));
		$event = $query->row_array();
		
		// Get the group, if any and enabled
		$groups = (isset($event['groups'])) ? unserialize($event['groups']): false;

		if ($groups !== false && $groups['enabled'] == '1' && isset($reg['group'])) {
			$result['group'] = $groups['groups'][$reg['group']]['title'];
		} else {
			$result['group'] = false;
		}
		
		// Individuals
		if ($reg['userid'] == '0') { 
			$result['individual'] = false;
		} else {
			$result['individual'] = true;
			$result['user'] = $reg['userid'];
		}

		// Prep and return
		if ($session && $event && $reg) {
			$result['session'] = (empty($session['title'])) ? $event['sessiontitle'].' '.$session['sessionnum'] : $session['title'];
			$result['event'] = $event['title'];
			return $result;
		} else {
			return false;
		}
	}

	// Get registration record, session and event
	public function get_reg_set_ids($regid)
	{
		// Get our event registration record
		$query = $this->db->get_where('eventregs', array('id' => $regid));
		$reg = $query->row_array();

		// Let's make sure this didn't return an empty
		if (!$reg) {
			return false;
		}
			
		// Get our session
		$query = $this->db->get_where('sessions', array('id' => $reg['session'], 'eventid' => $reg['eventid']));
		$session = $query->row_array();
		$result['session'] = $session['id'];
		
		// Get our event
		$query = $this->db->get_where('event', array('id' => $reg['eventid']));
		$event = $query->row_array();
		$result['event'] = $event['id'];
		
		// Get our unit
		if ($reg['userid'] == '0') { 
			$query = $this->db->get_where('unit', array('id' => $reg['unitid']));
			$unit = $query->row_array();
			$result['unit'] = $unit['id'];
			$result['individual'] = false;
		} else {
			$result['unit'] = false;
			$result['individual'] = true;
			$result['userid'] = $reg['userid'];
		}
		
		return $result;
	}

	// Get a member with recursive details
	public function get_member_details($id)
	{
		// Get our member
		$query = $this->db->get_where('members',array('id'=>$id));
		$member = $query->row_array();
		if (empty($member)) return false;
		// Get their unit as well
		$query = $this->db->get_where('unit',array('id'=>$member['unit']));
		$unit = $query->row_array();
		$member['unit'] = $unit;
		return $member;
	}


	// Reset counts on events
	// $event = (false) a single event id if only one event
	// $verify = (true) run the verify script for each reg 
	public function recount($session=false,$event=false,$verify=true)
	{
		// Get our event registration record
		$query = $this->db->get('eventregs');
		$query = $query->result_array();
		foreach ($query as $q) { $regs[$q['id']] = $q; }

		// Get our session
		$query = ($session === false) ? $this->db->get('sessions'): $this->db->get_where('sessions', array('id' => $reg['session']));			
		$query = $query->result_array();
		foreach ($query as $q) { $sessions[$q['id']] = $q; }
		
		// Get our event
		$query = ($event === false) ? $this->db->get('event'): $this->db->get_where('event', array('id' => $reg['eventid']));			
		$query = $query->result_array();
		foreach ($query as $q) { $events[$q['id']] = $q; }
		
		// Get our unit
		$query = $this->db->get('unit');
		$query = $query->result_array();
		foreach ($query as $q) { $units[$q['id']] = $q; }
		
		// Set each session count as 0
		foreach ($sessions as $s) {
			$i[$s['id']] = 0;
		}
		
		// Loop through each reg and add numbers to the session counts
		/*foreach ($regs as $r) {
			if ($r['active'] == '0') continue;
			$v = ($r['userid'] !== '0') ? $this->verify($r,$events[$r['eventid']],$sessions[$r['session']]): $this->verify($r,$events[$r['eventid']],$sessions[$r['session']],$units[$r['unitid']]);
			$c = ($v['restricted']===true) ? 0: $r['youth']+$r['male']+$r['female'];
			if (isset($i[$r['session']])) $i[$r['session']] = (isset($i[$r['id']])) ? $i[$r['id']]+$c: $c;
		}*/
		
		// Prepare counts for the database
		$data = array();
		foreach ($sessions as $s) {
			$data[] = array('id'=>$s['id'],'count'=>$i[$s['id']]);
		}
		//show_error(print_r($data));
		$this->db->update_batch('sessions', $data, 'id'); 
		
		return true;
	}

	// Simply get the count for any specified group. No update.
	// $group = (required) id
	// $session = (required) id - return totals for a single session for for an entire event
	// $singlenumber = (false) bool - return single total number instead of an array
	//
	// Returns single total or array of totals for the session or array of sessions
	public function count_group($group,$session,$singlenumber=FALSE)
	{
		// Get single session
		$query = $this->db->get_where('eventregs', array('session' => $session, 'group' => $group));
		$result = $query->result_array();
		$count = array(
			'youth' => 0,
			'male' => 0,
			'female' => 0,
			'adults' => 0,
			'total' => 0,
			'regs' => 0
		);
		foreach ($result as $reg) {
			if ($reg['active'] == '0') continue;
			$count['youth'] = $count['youth'] + $reg['youth'];
			$count['male'] = $count['male'] + $reg['male'];
			$count['female'] = $count['female'] + $reg['female'];
			$count['adults'] = $count['adults'] + $reg['male'] + $reg['female'];
			$count['total'] = $count['total'] + $reg['youth'] + $reg['male'] + $reg['female'];
			$count['regs']++;
		}
		if ($count) {
			return ($singlenumber === false) ? $count: $count['total'];
		} else {
			//return null;
			return ($singlenumber === false) ? null: 0;
		}
	}

	// Get the regs for a session
	public function count_session($session)
	{
		$query = $this->db->get_where('eventregs', array('session' => $session));
		$result = $query->result_array();
		$count = array(
			'youth' => 0,
			'male' => 0,
			'female' => 0,
			'adults' => 0,
			'total' => 0
		);
		foreach ($result as $reg) {
			if ($reg['active'] == '0') continue;
			$count['youth'] = $count['youth'] + $reg['youth'];
			$count['male'] = $count['male'] + $reg['male'];
			$count['female'] = $count['female'] + $reg['female'];
			$count['adults'] = $count['adults'] + $reg['male'] + $reg['female'];
			$count['total'] = $count['total'] + $reg['youth'] + $reg['male'] + $reg['female'];
		}
		if ($count) {
			return $count;
		} else {
			return null;
		}
	}

	// Get the regs for a session
	public function update_count_session($session)
	{
		$query = $this->db->get_where('eventregs', array('session' => $session));
		$result = $query->result_array();
		$count = array(
			'youth' => 0,
			'male' => 0,
			'female' => 0,
			'adults' => 0,
			'total' => 0
		);
		foreach ($result as $reg) {
			if ($reg['active'] == '0') continue;
			$count['youth'] = $count['youth'] + $reg['youth'];
			$count['male'] = $count['male'] + $reg['male'];
			$count['female'] = $count['female'] + $reg['female'];
			$count['adults'] = $count['adults'] + $reg['male'] + $reg['female'];
			$count['total'] = $count['total'] + $reg['youth'] + $reg['male'] + $reg['female'];
		}
		$sessiondata = array(
			'count' => $count['total']
		);
		
		$this->db->where('id', $session);
		$this->db->update('sessions', $sessiondata);
		
		if ($count) {
			return $count;
		} else {
			return null;
		}
	}

	// Verify registration
	// $source  id or array of full reg.
	// $event   (false) id or array of full reg. unused
	// $session (false) id or array of full reg. unused
	// $unit	(false) id or array of full reg. unused
	// $lite	(false) bool, enable lite mode, for finances only maybe? unused
	// return   array(result=bool,error=array,count=int)
	public function verify($source,$event=false,$session=false,$unit=false,$lite=false,$user=false,$fin=false)
	{
		// Setup
		$result['result'] = false;
		$result['count'] = 0;
		$result['error'] = array();
		$result['restricted'] = false;
		$now = time();
		$i = 0;
		
		// Get our registration record
		if (is_array($source)) {
			$reg = $source;
		} else {
			$query = $this->db->get_where('eventregs', array('id' => $source));
			$reg = $query->row_array();
		}
		//show_error(print_r(array('source'=>$source,'reg'=>$reg)));
		// Let's make sure this didn't return an empty
		if (empty($reg)) {
			$return['reason'] = 'No registration record found.';
			return $return;
		} else {
			$reg['registerdate'] = unserialize($reg['registerdate']);
			$reg['discounts'] = unserialize($reg['discounts']);
			$reg['options'] = unserialize($reg['options']);

		}
		
		// Get our session
		if (!is_array($session)) {
			$query = $this->db->get_where('sessions', array('id' => $reg['session'], 'eventid' => $reg['eventid']));
			$session = $query->row_array();
			// session = id,eventid,open,title,description,limithard,limitsoft,count,cost,costadult,costfamily,datestart,dateend,sessionnum
		}
		
		// Get and prep event
		if (!is_array($source)) {
			$query = $this->db->get_where('event', array('id' => $reg['eventid']));
			$event = $query->row_array();
			if (empty($event)) {
				$return['reason'] = 'No event record found.';
				return $return;
			} else {
				$event['earlyreg'] = (isset($event['earlyreg'])) ? unserialize($event['earlyreg']): false;
				$event['paymenttiers'] = (isset($event['paymenttiers'])) ? unserialize($event['paymenttiers']): false;
				$event['freeadults'] = (isset($event['freeadults'])) ? unserialize($event['freeadults']): false;
				$event['groups'] = (isset($event['groups'])) ? unserialize($event['groups']): false;
				// event = id,title,eventtype,location,description,activityregs,activitydate,regcount,datestart,dateend,open,earlyreg,sessiontitle,paymenttiers,activitypreorders,freeadults,timestamp,eligibleunits
				// earlyregs = false or enabled,amount,date,per
				// freeadults = false or enabled,amount,threshold,dollar,description
				// paymenttiers = false or 	r,ramount,rper,rrefund
				//							f,famount,fdate,fpercent,fper
				//							s,samount,sdate,spercent,sper
				//							n,namount,ndate,npercent,nper
				//							l,lamount,ldate,lpercent,lper
			}
		}			
		
		// Get the discounts and options
		$query = $this->db->get_where('options', array('eventid' => $reg['eventid']));
		$options = $query->result_array();
		$query = $this->db->get_where('discounts', array('eventid' => $reg['eventid']));
		$discounts = $query->result_array();
		$latefeedate = (isset($event['paymenttiers']['l']) && $event['paymenttiers']['l'] == 1 && $reg['latefeeexempt'] == 0 && isset($event['paymenttiers']['ldate'])) ? $event['paymenttiers']['ldate']: false;
		$payments = $this->shared->get_reg_payments($reg['id'],$unit['id'],TRUE,TRUE,FALSE,$latefeedate);

		// Get the roster and individual discounts
		$individualdiscounts = array();
		if ($reg['roster'] == '1') {
			$query = $this->db->get_where('roster', array('reg' => $reg['id']));
			$roster = $query->result_array();
		} else {
			$roster = NULL;
		}

		$fin = null;
		// Get and process finances
		//if ($fin === false) $fin = $this->get_finances($event, $reg, $session, $unit, $options, $discounts, $payments, true);
		$fin = $this->get_finances($event, $reg, $session, $unit, $options, $discounts, $payments, true);
		//print_r($fin);die;
		
		// Individual handler
		if ($reg['userid'] !== '0') {
			// Get our user
			if (!is_array($user)) {
				$user = $this->ion_auth->user($reg['userid'])->row();
			}
			$individual = true;
		} else {
			// Get our unit
			if (!is_array($unit)) {
				$query = $this->db->get_where('unit', array('id' => $reg['unitid']));
				$unit = $query->row_array();
			}
			$individual = false;
			// unit = id, number, unittype, primary, alt
		}
		
		/* The Checks
		 * 
		 * The checks here are the items that are required for an unit to register 
		 * and for a registration to be in good standing. The result should include a
		 * message in the error array. These would be read out in a loop where verify 
		 * is run.
		 */

		// Check resfee = Reservation Fee
		if ($event['paymenttiers'] && $event['paymenttiers']['r']===1) {
			// is total paid more than the reservation fee?
			$resfee = ($event['paymenttiers']['rper']===1) ? $event['paymenttiers']['ramount']*$fin['counts']['total']: $event['paymenttiers']['ramount'];
			if ($resfee > $fin['fin']['totalpaid']) {
				$result['count']++; 
				$result['restricted'] = true;
				$temp['feedue'] = $resfee - $fin['fin']['totalpaid'];
				$result['error']['resfee'] = '<strong>Not registered</strong> because the reservation fee needs to be paid in full ($'.$temp['feedue'].' is due now).';
			}
		}

		$errorarray =array(
			'errorarray:824 reg' => 	$reg,
			'totalparticipants' => 		$fin['fin']['totalparticipants'],
			'countsyouth' => 			$fin['fin']['totalyouth'],
			'totaladults' => 			$fin['fin']['totaladults'],
			'totalyouth' => 			$fin['fin']['totalyouth'],
			'totaladults' => 			$fin['fin']['totaladults'],
			'discounts' => 				$fin['fin']['discounts'],
			'freeadults' => 			$fin['fin']['freeadults'],
			'earlyreg' => 				$fin['fin']['earlyreg'],
			//'totaldue' =>				$totaldue
		);
		//print_r($fin); die;

		// Check schedule = Fee schedule ontime
		if (is_array($event['paymenttiers'])) { 
			/* 
			print_r(array(
				'total' => $fin['fin']['total'],
				'totalnooptions' => $fin['fin']['totalnooptions'],
				'totaldiscounts' => $fin['fin']['totaldiscounts'],
				'preorders' => $fin['fin']['preorders'],
				'total paid' => $fin['fin']['totalpaid'],
			)); die; */
			if ($fin['fin']['total'] <= $fin['fin']['totalpaid']) {
				// all is ok, don't show an error.
				
			} elseif ($event['paymenttiers']['f']==1 || $event['paymenttiers']['s']==1 || $event['paymenttiers']['n']==1) {
				// is total paid more than the reservation fee?
				$totaldue = $fin['fin']['total'];
				$i = 0;
				foreach (array('f','s','n') as $k) {
					if ($event['paymenttiers'][$k]==1 && $now > $event['paymenttiers'][$k.'date']) {
						// First payment deadline is now in the past 
						if ($event['paymenttiers'][$k.'percent'] === 1) {
							$temp['schedulevalue'] = $totaldue*($event['paymenttiers'][$k.'amount']*.01);
							/*print_r(array(
								'percent' => $temp['schedulevalue'],
								'event paymenttiers' => $event['paymenttiers'][$k.'percent'],
								'schedule value' => $temp['schedulevalue'],
								'totaldue' => $totaldue,
								'paymenttiers amount' => $event['paymenttiers'][$k.'amount'],
								'paymenttiers percent' => ($event['paymenttiers'][$k.'amount']*.01)
							)); */
						} elseif ($event['paymenttiers'][$k.'per']===1) {
							$temp['schedulevalue'] = ($event['paymenttiers'][$k.'per']*$event['paymenttiers'][$k.'amount'])*$fin['counts']['total'];
							//show_error('perperson '.$temp['schedulevalue']);
						} else {
							$temp['schedulevalue'] = $event['paymenttiers'][$k.'amount'];
						}
						//show_error($temp['schedulevalue']);
						if ($temp['schedulevalue'] > $fin['fin']['totalpaid']) {
							$i++;
							//$result['restricted'] = true;
							$temp['pastdue'] = $temp['schedulevalue'] - $fin['fin']['totalpaid'];
						}	
					}
				}
			}
			if ($i>0) {
				$result['error']['schedule'] = '$'.$temp['pastdue'].' is past due, please make a payment to keep your registration current.';
				$result['count']++;
			}
			
		}

		
		// Check leaders = has 2 non similar leaders
		if (!$individual) {
			if ($unit['primary']==0 || $unit['alt']==0 || $unit['primary']==$unit['alt']) {
				$result['count']++; 
				$result['error']['leaders'] = $unit['unittype'].' '.$unit['number'].' must have two separate contacts in Camper to register for events. <a href="'.base_url('unit').'">Add a second contact &rarr;</a>';
			}
		}
		
		// Zero participants = has no participants set for youth, male or female
		if (($reg['youth'] + $reg['male'] + $reg['female']) == 0) {
			$result['count']++; 
			$result['error']['zero'] = 'You have 0 registered participants, <a href="#fyouth" onclick="$(\'#fyouth\').focus();">add some below</a>.';
		}
		
		// No group = has yet to choose a group
		if ($event['groups']['enabled'] && count($event['groups']['groups']) > 0) {
			if (!isset($reg['group'])) {
				$result['count']++; 
				$result['error']['groups'] = 'Please select a '.$event['groups']['title'].' below.';
			}
		}
		
		// Inactive
		if ($reg['active'] == '0') {
			$result['count']++; 
			$result['restricted'] = true;
			$result['error']['inactive'] = ($this->ion_auth->is_admin()) ? '<strong>This registration is inactive. '.anchor('api/v1/registration/activate?reg='.$reg['id'].'&return='.uri_string(), 'Activate?').'</strong>': '<strong>This registration has been marked as inactive.</strong> Contact the council service center for more information.';
		}
		
		// All good?
		if ($result['count'] === 0) {
			$result['result'] = true;
		}
		$result['source'] = array(
			'event' => $event, 
			'reg' => $reg, 
			'session' => $session, 
			'unit' => $unit, 
			'options' => $options, 
			'discounts' => $discounts, 
			'payments' => $payments,
			'fin' => $fin
		);
		return $result;
		
	}

	// Get a transient record
	public function get_transient($token)
	{
		$this->db->select('token,content,live');
		$query = $this->db->get_where('transient', array('token' => $token));
		$record = $query->row_array();
		if (!isset($record['live'])) {
			return false;
		} elseif ($record['live'] == 1) {
			return $record;
		} elseif ($record['live'] == 0) {
			return array('error' => 'expired');
		} else {
			return false;
		}
	}
	
	// Delete a transient record
	public function delete_transient($token)
	{
		$this->db->delete('transient', array('token' => $token));
		return true;
	}

	// Update a transient record
	public function update_transient($token=false,$content=false,$type='start',$serialize=true) 
	{		
		// Create transient record if no token
		if ($token == false) {
			$newtoken = md5(microtime().random_string('alnum', 4));
			if ($serialize) $content = serialize($content);
			$record = array(
				'type'		=> $type,
				'token'		=> $newtoken,
				'content'	=> $content,
				'live'		=> 1
			);
			$this->db->insert('transient', $record);
			return $newtoken;
		// Set transient record as expired if no content
		} elseif ($content == false) {
			$record = array(
				'live'		=> 0
			);
			$this->db->where('token', $token);
			$this->db->update('transient', $record);
			return true;
		// Update transient record if all is set
		} else {
			if ($serialize) $content = serialize($content);
			$record = array(
				'type'		=> $type,
				'content'	=> $content,
				'live'		=> 1
			);
			$this->db->where('token', $token);
			$this->db->update('transient', $record);
			return true;
		}
		show_error('Transient record update failed because content and token were both unspecified. I\'m not sure really what happened here.');
	}

	// Update a transient record
	public function create_transient($token=false,$content=false,$type='start',$serialize=true) 
	{		
		// Create transient record if no token
		if ($token === false) {
			$newtoken = md5(microtime().random_string('alnum', 4));
		} else {
			$newtoken = $token;
		}
		if ($serialize) $content = serialize($content);
		$record = array(
			'type'		=> $type,
			'token'		=> $newtoken,
			'content'	=> $content,
			'live'		=> 1
		);
		$this->db->insert('transient', $record);
		return $newtoken;
	}
	
	
	// Mark all notifications as read
	public function read_notifications()
	{
		// Setup
		$user = $this->ion_auth->user()->row();
		$this->db->select('id, live, userid');
		$query = $this->db->get_where('notifications', array('userid'=>$user->id,'live'=>1));
		if (count($query->result_array()) == 0) return false;
		$batch = array();
		
		// Prepare Changes
		foreach ($query->result_array() as $q) { array_push($batch,array('id'=>$q['id'],'live'=>0)); }
		
		// Update Database and Return True
		$this->db->update_batch('notifications',$batch,'id');
		return true;
	}

	// Mark single notification read
	public function read_single_notification($token)
	{
		// Setup
		$user = $this->ion_auth->user()->row();
		$this->db->select('id, live, userid');
		$query = $this->db->get_where('notifications', array('userid'=>$user->id,'live'=>1,'token'=>$token));
		$query = $query->row_array();
		if (!$query) return false;
		$batch = array();
		
		// Update Database and Return True
		$record = array(
			'id'		=> $query['id'],
			'live'		=> 0
		);
		$this->db->where('id', $query['id']);
		$this->db->update('notifications', $record);
		return true;
	}

	// Notifications handler
	public function notifications($user=FALSE)
	{
		// Prep
		$user = ($user === false) ? $this->ion_auth->user()->row(): $this->ion_auth->user($user)->row();
		$this->db->order_by('time',"desc");
		$query = $this->db->get_where('notifications', array('userid'=>$user->id));
		$notifications = $query->result_array();
		$defaults = $this->config->item('camper_notifications');
		$result['array'] = $notifications;
		$result['html'] = '';
		$result['new'] = false;
		$result['newcount'] = 0;
		$result['count'] = 0;
		
		
		// Return defaults if we came up empty
		if (count($notifications) == 0) {
			$result['html'] = false;
			//return $result;
		}
		
		// Create our results array
		foreach ($notifications as $n) { 
			$new = false;
			$newclass = false;
			$result['count']++;
			if ($n['live'] == 1) { 
				$result['newcount']++; 
				$result['new'] = true; 
				$new = true; 
				$newclass='new'; 
			}
			if (($result['count']-$result['newcount'])>5) continue;
			$rowlink = (isset($defaults[$n['type']]['l'])) ? anchor($defaults[$n['type']]['l'], $defaults[$n['type']]['sy'].' &rarr;', 'class="btn btn-small tan right"') : false;
			// Old link: <a href="'.$this->config->item('camper_path').'n/'.$n['token'].'" class="btn btn-small tan right">Confirm</a>
			$row = '<li class="'.$newclass.'">'.$rowlink.'<span>'.$n['message'].' <br /><i>'.$this->twitterdate($n['time']).'</i></span></li>';
			$result['html'] = $result['html'].$row;
			//array_push($result['array'], $row);
		}
		return $result;
	}

	// Notifications handler
	public function notify($type,$definitions=NULL,$details=NULL,$notificationuser=NULL)
	{		
		// Prep the notification record
		$mustachedefinitions = array();
		$defaults = $this->config->item('camper_notifications');
		$defaults = $defaults[$type];
		if (isset($definitions)) {
			foreach (array_keys($definitions) as $i) { array_push($mustachedefinitions, '{'.$i.'}'); }
			$message = str_replace($mustachedefinitions, array_values($definitions), $defaults['t']);
			foreach (array_keys($definitions) as $i) { array_push($mustachedefinitions, '{'.$i.'}'); }
			$defaults['l'] = str_replace($mustachedefinitions, array_values($definitions), $defaults['l']);
			//show_error(print_r($defaults['l']));
		} else {
			$message = $defaults['t'];
		}
		
		// Do the actions for each notification type
		switch ($type):
			case 'requestaccess':
				$action = 'none';
				break;
			case 'welcome':
				$action = 'none';
				break;
			default:
				$action = 'none';
		endswitch;
		
		if (!isset($notificationuser)) {
			$notificationuser = $this->ion_auth->user()->row();
			$notificationuser = $notificationuser->id;
		}

		// Add notification records into the database
		if ($notificationuser === 'admin') {
			// We are notifying administrators, we can easily handle this with an array of email addresses instead of slowing things down.
			$admin = $this->ion_auth->users(array(1))->result();
			$adminto = array();
			foreach ($admin as $a) {
				$b['email'] = $a->email;
				array_push($adminto,$b);
				$token = md5(microtime().random_string('alnum', 4));
				$record = array(
					'type'		=> $type,
					'token'		=> $token,
					'userid'	=> $a->id,
					'message'	=> $message,
					'action'	=> serialize($action),
					'time'		=> time(),
					'live'		=> 1
				);
				$this->db->insert('notifications', $record);
			}			
			$admin = true;
			$query['first_name'] = 'Admin';
			$query['last_name'] = 'Camper';
			$query['email'] = $adminto;
		} else {
			// Single notification
			$token = md5(microtime().random_string('alnum', 4));
			$record = array(
				'type'		=> $type,
				'token'		=> $token,
				'userid'	=> $notificationuser,
				'message'	=> $message,
				'action'	=> serialize($action),
				'time'		=> time(),
				'live'		=> 1
			);
			$this->db->insert('notifications', $record);
			$admin = false;
			$this->db->select('first_name,last_name,email');
			$query = $this->db->get_where('auth_users', array('id' => $notificationuser));
			$query = $query->row_array();
		}

		// Send notification email
		if (isset($defaults['s'])) $this->send_notification($message, $defaults['s'], false, $query['first_name'], $query['last_name'], $query['email'], 'n/'.$token);
		return true;
	}

	// Create invite
	public function create_invite($content) 
	{
		// Create transient record
		$newtoken = md5(microtime().random_string('alnum', 4));
		/*
		$content_sample = array(
			'unit'		=> $unitname,
			'unitid'	=> $unit,
			'source'	=> $source,
			'email'		=> $email,
			'adminsource' => NULL ( TRUE if admin sent request )
		);
		*/
		$content['sourcefrom'] = (isset($content['adminsource'])) ? $this->config->item('camper_council') : $content['unit'];
		$scontent = serialize($content);
		$record = array(
			'type'		=> 'invite',
			'token'		=> $newtoken,
			'content'	=> $scontent,
			'live'		=> 1
		);
		$this->db->insert('transient', $record);
		$record['data'] = $content;
		
		// Send invite email
		$html = $this->load->view('notifications/invite', $record, true);
		$invitelink = anchor("start/invite/".$newtoken, 'Get started');
		$email = array(
			'html' => $html, //Consider using a view file
			'text' => 'Hello! '.$content['source'].' from '.$content['sourcefrom'].' has invited you to be '.$content['unit'].'\'s alternate contact on Camper, Longs Peak Council\'s registration system. '.$invitelink,
			'subject' => 'You\'ve been invited to Camper',
			'from_email' => 'camper@camperapp.org',
			'from_name' => $content['source'].' via Camper',
			'to' => array(array('email' => $content['email'] )) //Check documentation for more details on this one
			);
		//show_error('<pre>'.serialize($email).'</pre>');
		if ($this->start_mandrill()) $this->go_mandrill($email);
		
		// Return our token
		return $newtoken;
	}

	// Resend invite
	public function resend_invite($token) 
	{
		// Get transient record
		$query = $this->db->get_where('transient', array('token' => $token));
		$record = $query->row_array();
		if (count($record) == 0) return false;
		
		$content = unserialize($record['content']);
		$record['data'] = $content;
		
		// Send invite email
		$html = $this->load->view('notifications/invite', $record, true);
		$invitelink = anchor("start/invite/".$newtoken, 'Get started');
		$email = array(
			'html' => $html, //Consider using a view file
			'text' => 'Hello! '.$content['source'].' from '.$content['sourcefrom'].' has invited you to be '.$content['unit'].'\'s alternate contact on Camper, Longs Peak Council\'s registration system. '.$invitelink,
			'subject' => 'You\'ve been invited to Camper',
			'from_email' => 'camper@camperapp.org',
			'from_name' => $content['source'].' via Camper',
			'to' => array(array('email' => $content['email'] )) //Check documentation for more details on this one
			);
		//show_error('<pre>'.serialize($email).'</pre>');
		if ($this->start_mandrill()) $this->go_mandrill($email);
		
		// Return success
		return true;
	}
	
	// Find unit invites
	public function get_invites($unit=FALSE)
	{
		$query = $this->db->get_where('transient', array('type' => 'invite'));
		$record = $query->result_array();
		$invites = array();
		$i = 0;
		foreach ($record as $invite) {
			$invite['content'] = unserialize($invite['content']);
			if ($invite['content']['unitid'] == $unit || $unit === false) {
				$invites[$i] = array('token'=>$invite['token'], 'email'=>$invite['content']['email'], 'source'=>$invite['content']['source'], 'unit'=>$invite['content']['unitid']); 
				$i++;
			}
		}
		if ($i > 0) {
			return $invites;
		} else {
			return false;
		}
	}
	
	// get an excerpt for a longer string, namely session titles.
	public function excerpt($str, $startPos=0, $maxLength=20) {
		if(strlen($str) > $maxLength) {
			$excerpt   = substr($str, $startPos, $maxLength-3);
			$lastSpace = strrpos($excerpt, ' ');
			$excerpt   = substr($excerpt, 0, $lastSpace);
			$excerpt  .= '...';
		} else {
			$excerpt = $str;
		}
		
		return $excerpt;
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



	/* Get payments for a reg
	 * This will figure out if the reg qualifies for the latefee or not. Set latefee to the cutoff date and this will return the amount paid as of the cutoff date
	 */
	public function get_reg_payments($reg, $unit=NULL, $onlyconfirmed=NULL, $onlysum=NULL, $individual=FALSE, $latefee=FALSE)
	{
		// Setup
		$this->db->order_by('date', 'desc');
		$where['reg'] = $reg;
		if (isset($onlysum)) $this->db->select_sum('amount');
		if (isset($onlyconfirmed)) $where['status'] = 'Completed';
		// Get our result
		$query = $this->db->get_where('payments', $where);

		// Return the total amount paid
		if ($latefee === false) return $query->result_array();
		
		// Get the total amount paid as of the latefee cutoff date
		$result = $query->result_array();
		$this->db->order_by('date', 'desc');
		$where['reg'] = $reg;
		$where['date <'] = $latefee; 
		$where['status'] = 'Completed';
		$this->db->select_sum('amount');
		$query = $this->db->get_where('payments', $where);
		$latefee = $query->result_array();
		$result[0]['latefee'] = $latefee[0]['amount'];
		return $result;
	}

	// Get payment by token
	public function get_payment($token, $type=NULL)
	{
		// Setup
		$where['token'] = $token;
		// Get our result
		$query = $this->db->get_where('payments', $where);

		return $query->row_array();
	}

	/* Get classes
	 * $type = (FALSE) event id
	 * $relational = (FALSE|TRUE) return associative array or no formatting
	 */
	public function get_classes($event=FALSE,$relational=FALSE)
	{
		// Get the activities
		if ($event) {
			$this->db->where('event', $event);
			$this->db->order_by('title asc');
		}
		$query = $this->db->get('classes');
		
		// Return normal or relational array
		if ($relational === false) {
			return (empty($query)) ? array(): $query->result_array();
		} else {
			$temp = $query->result_array();
			$result = array();
			foreach ($temp as $a) { $result[$a['id']] = $a;}
			return $result;
		}
	}

	// Generate financial details for an  registration record
	public function get_finances($event, $reg, $session, $unit, $options, $discounts, $payments, $serialize=FALSE)
	{
		/* Note - Unit defined here does nothing at the moment. It 
		 * may be used in the future and if so, it will double as
		 * the user for an individual registration. It is being used
		 * that way right now. Just some FYI for future sean from past sean.
		 */
		
		// Prep
		if (isset($event['groups']) && !is_array($event['groups'])) $groups = unserialize($event['groups']);
		//print_r(array($event, $reg, $session, $unit, $options, $discounts, $payments, $serialize, $groups)); die;
		
		
		// Total Paid = confirmed payments for this event
		$latefeedate = (isset($event['paymenttiers']['l']) && $event['paymenttiers']['l'] == 1 && $reg['latefeeexempt'] == 0 && isset($event['paymenttiers']['ldate'])) ? $event['paymenttiers']['ldate']: false;
		$payments = $this->shared->get_reg_payments($reg['id'],FALSE,TRUE,TRUE,FALSE,$latefeedate);
		$fin['totalpaid'] = $payments;
		$fin['totalpaid'] = $fin['totalpaid'][0]['amount'];
		
		// Total Participant Fees = adults+youth
		$counts['adults'] = $reg['male']+$reg['female'];
		$counts['youth'] = $reg['youth'];
		$counts['total'] = $reg['male']+$reg['female']+$reg['youth'];
		$cost['youth'] = (isset($session['cost'])) ? $session['cost']: FALSE;
		$cost['adult'] = (isset($session['costadult'])) ? $session['costadult']: $cost['youth'];
		$fin['totaladults'] = $counts['adults']*$cost['adult'];
		$fin['totalyouth'] = $counts['youth']*$cost['youth'];
		$fin['totalparticipants'] = $fin['totalyouth'] + $fin['totaladults'];

		//show_error(print_r($payments)); die;
		
		/* Groups = groupcosts
		 * The groups may have a cost, and things just got interesting. This will go into the fees section of 
		 * any table showing costs. It should be labeled by the groups title.
		 * If the groups cost is per unit, then we can simply add it on as a single fee. If not, we will 
		 * multiply the cost by the number of participants and we'll be good to go.
		 */
		$groupcost = false;
		$fin['group'] = 0;
		$cost['groups'] = array('cost'=>0,'perperson'=>true);
		if (isset($reg['group']) && !empty($reg['group']) && isset($groups) && $groups['enabled'] == 1 && !empty($groups['groups'])) :
			if (isset($groups['groups'][$reg['group']]) && isset($groups['groups'][$reg['group']]['cost']) && $groups['groups'][$reg['group']]['cost'] > 0) :
				// We have a group with a cost.
				$group = $groups['groups'][$reg['group']];
				if ($group['perperson'] == 1) :
					$fin['totaladults'] = $counts['adults'] * ($cost['adult'] + $group['cost']);
					$fin['totalyouth'] = $counts['youth'] * ($cost['youth'] + $group['cost']);
					$cost['youth'] = $cost['youth'] + $group['cost'];
					$cost['adult'] = $cost['adult'] + $group['cost'];
					$fin['totalparticipants'] = $fin['totalyouth'] + $fin['totaladults'];
					$cost['groups'] = array('cost'=>$group['cost'],'perperson'=>true);
				else :
					$fin['group'] = $group['cost'];
					$cost['groups'] = array('cost'=>$group['cost'],'perperson'=>false);
				endif;
				$groupcost = true;
			endif;		
		endif;

		// Amount due by next deadline (if multiple payment dates) = total amount due - payments made
		$fin['nextdue'] = 1.00;
	
		// Preorders
		$fin['preorders'] = 0;	
		if ($event['activitypreorders'] == 1 && $reg['activitypreorders'] == 1) :
			$query = $this->db->get_where('classregs', array('reg' => $reg['id']));
			$classregs = $query->result_array();
	   		if (count($classregs > 0)) : 
				$query = $this->db->get_where('roster', array('reg' => $reg['id']));
				$roster = $query->result_array();
				$classes = $this->get_classes($event['id'],true);
		   		foreach ($classregs as $c) : 
		   			$flag = true; 
		   			$c['__amount'] = $classes[$c['class']]['preorder'];
		   			$fin['preorders'] = $fin['preorders'] + $c['__amount'];
		   		endforeach;
		   	endif;
		endif;


		// Free Adults
		$fin['freeadults'] = 0;	
		$event['freeadults']['__qualified'] = false;
	   	$event['freeadults']['__earlyreginclude'] = false;
	   	$event['freeadults']['__includeindiscounts'] = false;
		if (isset($event['freeadults']['enabled']) && $event['freeadults']['enabled'] == 1 && $counts['youth'] >= $event['freeadults']['threshold']) :
   			$event['freeadults']['__qualified'] = true;
   			if ($event['freeadults']['dollar'] == 1) {
	   			// The discount is a dollar amount, per adult
	   			$event['freeadults']['__total'] = $counts['adults'] * $event['freeadults']['amount'];
	   			$event['freeadults']['__earlyreginclude'] = false;
	   			$event['freeadults']['__includeindiscounts'] = false;
   			} else {
	   			// The discount for free adults
	   			$event['freeadults']['__earlyreginclude'] = true;
	   			$event['freeadults']['__total'] = $event['freeadults']['amount'] * $cost['adult'];
	   			$event['freeadults']['__includeindiscounts'] = true;
   			}
			$fin['freeadults'] = $event['freeadults']['__total']; 
		endif;

		// Discounts = discounts
		$fin['discounts'] = 0;	
   		if (count($discounts > 0)) : 
	   		foreach ($discounts as $o) : 
	   			$flag = true; 
	   			if ($o['checkbox'] == 1 && !isset($reg['discounts'][$o['id']]['checkbox'])) $flag = false; if (isset($o['amount']) && $o['amount'] > 0 && $flag === true ) : 
	   				// Setup
	   				$o['__total'] = (isset($o['amount'])) ? $o['amount']: 0; 
	   				$o['__value'] = 0; 
	   				$o['__percent'] = ($o['percent'] == 1) ? true: false;
	   				$o['__count'] = ($event['freeadults']['__includeindiscounts'] && $counts['adults'] >= $event['freeadults']['amount']) ? $counts['youth']+($counts['adults']-$event['freeadults']['amount']): $counts['total'];

	   					
		   			if (isset($o['amount']) && $o['value'] == 1 && isset($reg['discounts'][$o['id']]['value']) ) { 
		   				// This option has an amount and the user entered a value
		   				if ($o['perperson'] == 1) {
		   					// This is per person, we'll display the details find the total
		   					$o['__total'] = ($o['__percent']) ? $o['__count'] * (.01 * $o['amount']) : $o['__count'] * $o['amount'];
		   				} else {
		   					// This is per the value, we'll display details and find the total
		   					$o['__total'] = ($o['__percent']) ? $reg['discounts'][$o['id']]['value'] * (.01 * $o['amount']) : $reg['discounts'][$o['id']]['value'] * $o['amount']; 
		   				}
		   			} elseif (isset($o['amount'])) {
		   				if ($o['perperson'] == 1) {
		   					$o['__total'] = ($o['__percent']) ? $o['__count'] * (.01 * $o['amount']) : $o['__count'] * $o['amount'];
		   				} else {
		   					$o['__total'] = ($o['__percent']) ? .01 * $o['amount'] : $o['amount'];
		   				}
			  		} 
			  		$fin['discounts'] = $fin['discounts'] + $o['__total']; 
	   			endif; 
	   		endforeach; 
   		endif;

		// Early Registration
		$fin['earlyreg'] = 0;	
		$event['earlyreg']['__qualified'] = false;
		if (isset($event['earlyreg']['enabled']) && $event['earlyreg']['enabled'] == 1 && $reg['registerdate']['time'] < $event['earlyreg']['date']) :
			$event['earlyreg']['__qualified'] = true;
	   		$event['earlyreg']['__count'] = ($event['freeadults']['__earlyreginclude']) ? ($counts['adults'] >= $event['freeadults']['amount']) ? $counts['youth']+($counts['adults'] - $event['freeadults']['amount']): $counts['youth']+($counts['adults']-$event['freeadults']['amount']): $counts['total'];
	   		if ($event['earlyreg']['percent'] == 1) {
		   		// The discount is a percentage
		   		$event['earlyreg']['__total'] = ($event['earlyreg']['per'] == 1) ? $event['earlyreg']['__count'] * ($event['earlyreg']['amount'] * .01): $event['earlyreg']['amount'] * .01;
	   		} else {
		   		// The discount is a normal dollar amount
		   		$event['earlyreg']['__total'] = ($event['earlyreg']['per'] == 1) ? $event['earlyreg']['__count'] * $event['earlyreg']['amount']: $event['earlyreg']['amount'];
	   		}
			$fin['earlyreg'] = $event['earlyreg']['__total'];
		endif; 

		// Max Reg (registration per person fee, dropout security)
		$fin['regfee'] = 0;	
		// include free adults? $event['paymenttiers']['__rcount'] = ($event['freeadults']['__includeindiscounts']) ? $counts['youth'] + ($counts['adults'] - $event['freeadults']['amount']): $counts['youth'] + $counts['adults'];
		$event['paymenttiers']['__rcount'] = $counts['youth'] + $counts['adults'];
		if (
			isset($event['paymenttiers']['r']) && 
			$event['paymenttiers']['r'] == 1 && 
			isset($event['paymenttiers']['rrefund']) &&
			$event['paymenttiers']['rrefund'] == 0 && 
			isset($event['paymenttiers']['rper']) &&
			$event['paymenttiers']['rper'] == 1 && 
			$event['paymenttiers']['__rcount'] < $reg['max']
		) :
		   	$event['paymenttiers']['__rtotal'] = $event['paymenttiers']['ramount'] * ($reg['max'] - $event['paymenttiers']['__rcount']);

			$fin['regfee'] = $event['paymenttiers']['__rtotal'];
		endif; 

		// Total of all options = options total
		$fin['requests'] = 0;
   		if (count($options > 0)) :
   			foreach ($options as $o) :
   				$flag = true; 
   				if ($o['checkbox'] == 1 && isset($o['id']) && !isset($reg['options'][$o['id']]['checkbox'])) $flag = false;
	   			if (isset($o['amount']) && $o['amount'] > 0 && $flag === true ) : 
	   				// Setup
   					$o['__total'] = (isset($o['amount'])) ? $o['amount']: 0; 
   					$o['__value'] = 0; 
   					$o['__percent'] = ($o['percent'] == 1) ? true: false;
   							
	   				if (isset($o['amount']) && $o['value'] == 1 && isset($reg['options'][$o['id']]['value']) ) { 
	   					// This option has an amount and the user entered a value
	   					if ($o['perperson'] == 1) {
	   						// This is per person, we'll display the details find the total
	   						$o['__total'] = ($o['__percent']) ? $counts['total'] * (.01 * $o['amount']) : $counts['total'] * $o['amount'];
	   					} else {
	   						// This is per the value, we'll display details and find the total
	   						$o['__total'] = ($o['__percent']) ? $reg['options'][$o['id']]['value'] * (.01 * $o['amount']) : $reg['options'][$o['id']]['value'] * $o['amount']; 
	   					}
	   				} elseif (isset($o['amount'])) {
	   					if ($o['perperson'] == 1) {
	   						$o['__total'] = ($o['__percent']) ? $counts['total'] * (.01 * $o['amount']) : $counts['total'] * $o['amount'];
	   					} else {
	   						$o['__total'] = ($o['__percent']) ? .01 * $o['amount'] : $o['amount'];
	   					}
		   			}
		   			$fin['requests'] = $fin['requests'] + $o['__total']; 
		   		endif; 
		   	endforeach;
		endif;


		// Total Discounts = custom + freeadults + earlyreg
		$fin['totaldiscounts'] = ($fin['discounts'] + $fin['freeadults'] + $fin['earlyreg']);
		
		$fin['latefee'] = 0;
		// Late Fee
		if (isset($event['paymenttiers']['l']) && 
			$event['paymenttiers']['l'] == 1 && 
			$reg['latefeeexempt'] == 0 && 
			isset($payments[0]['latefee']) && 
			$event['paymenttiers']['ldate'] < time()
			) {
			$totaldue = ($fin['totalparticipants'] + $fin['group'] + $fin['requests'] - $fin['totaldiscounts']);
			if ($payments[0]['latefee'] < $totaldue) {
				if ($event['paymenttiers']['lpercent'] == 1) {
					$fin['latefee'] = (($event['paymenttiers']['lamount'] * .01) * $totaldue);
				} elseif ($event['paymenttiers']['lper'] == 1) {
					$fin['latefee'] = ($event['paymenttiers']['lamount'] * $counts['total']);
				} else {
					$fin['latefee'] = $event['paymenttiers']['lamount'];
				}
			}
		}

		// Total without options
		$fin['totalnooptions'] = ($fin['totalparticipants'] + $fin['latefee'] + $fin['regfee'] - $fin['totaldiscounts']);

		// Total = total-totalpaid
		$fin['total'] = ($fin['totalparticipants'] + $fin['group'] + $fin['requests'] + $fin['latefee'] + $fin['preorders'] - $fin['totaldiscounts']);

		// Total no preorders
		$fin['totalnopreorders'] = ($fin['totalparticipants'] + $fin['group'] + $fin['requests'] + $fin['latefee'] - $fin['totaldiscounts']);

		// Total Due = total-totalpaid
		$fin['totaldue'] = ($fin['total'] - $fin['totalpaid']);
		
		// Return our values in a big fat array
		$return_array['fin'] = $fin;
		$return_array['counts'] = $counts;
		$return_array['cost'] = $cost;
		//$fin['sean'] = 'in fin';
		//show_error(print_r($fin));
		return $return_array;   				
	}

	// Delete a registration and all dependencies
	function delete_registration($reg)
	{
		if (!$this->ion_auth->is_admin()) return false;
		// Delete any dependents
		$tables = array('classregs', 'payments', 'roster');
		$this->db->where('reg', $reg);
		$this->db->delete($tables);
		// Delete the reg
		$this->db->delete('eventregs',array('id'=>$reg));
		return true;
	}

	// Function to reoplicate the array_column() php function for PHP < 5.5
	function array_column_fix($input = null, $columnKey = null, $indexKey = null)
	{
		// Using func_get_args() in order to check for proper number of
		// parameters and trigger errors exactly as the built-in array_column()
		// does in PHP 5.5.
		$argc = func_num_args();
		$params = func_get_args();
		$paramsInput = $params[0];
		$paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
		$paramsIndexKey = null;
		if (isset($params[2])) {
			if (is_float($params[2]) || is_int($params[2])) {
				$paramsIndexKey = (int) $params[2];
			} else {
				$paramsIndexKey = (string) $params[2];
			}
		}
		$resultArray = array();
		foreach ($paramsInput as $row) {
			$key = $value = null;
			$keySet = $valueSet = false;
			if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
				$keySet = true;
				$key = (string) $row[$paramsIndexKey];
			}
			if ($paramsColumnKey === null) {
				$valueSet = true;
				$value = $row;
			} elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
				$valueSet = true;
				$value = $row[$paramsColumnKey];
			}
			if ($valueSet) {
				if ($keySet) {
					$resultArray[$key] = $value;
				} else {
					$resultArray[] = $value;
				}
			}
		}
		return $resultArray;
	}
	
	// Function to easily prepare data for the database
	public function prep_option($field,$type='text')
	{
		if ($type == 'checkbox') {
			return ($field == 'on') ? 1 : 0;
		} elseif ($type == 'dollar') {
			$dollar = str_replace(array('$', '%', ',', ' '), '', $field);
			return $dollar;
		} elseif ($type == 'date') {
			$date = null;
			if(isset($field)) $date = strtotime($field);
			return $date;
		} elseif ($type == 'lower') {
			$lower = strtolower($field);
			return $lower;
		} elseif ($type == 'upper') {
			$upper = strtoupper($field);
			return $upper;
		} elseif ($type == 'number') {
			return (isset($field)) ? $field : 0;
		} else {
			return (isset($field)) ? $field : null;
		}
	}

	/* Get user's unit
	 * $user = (FALSE) user id or we'll get the current user's unit 
	 * $full = (FALSE) returns unit title for false, full unit for true, id for 'id'
	 */
	public function get_user_unit($user = FALSE, $full = FALSE)
	{
		// Get our user if unset
		if ($user === false) {
			$user = $this->ion_auth->user()->row();
			$user = $user->id;
		}
				
		// Get the user's unit
		$this->db->select('id,company,individualdata,first_name,last_name');
		$query = $this->db->get_where('auth_users', array('id' => $user));
		$usersunit = $query->row_array();

		// Are we an individual?
		if ($usersunit['company'] == 0) {
			// We are an individual, we'll fake the unit details from the individual's data record
			$iunit = unserialize($usersunit['individualdata']);
			$unit = array(
				'unittype' => (isset($iunit['unittype'])) ? $iunit['unittype']: null,
				'number' => (isset($iunit['number'])) ? $iunit['number']: null,
				'council' => (isset($iunit['council'])) ? $iunit['council']: null,
				'district' => (isset($iunit['district'])) ? $iunit['district']: null,
				'address' => (isset($iunit['address'])) ? $iunit['address']: null,
				'city' => (isset($iunit['city'])) ? $iunit['city']: null,
				'state' => (isset($iunit['state'])) ? $iunit['state']: null,
				'zip' => (isset($iunit['zip'])) ? $iunit['zip']: null,
				'alt' => 0,
				'primary' => $user['id'],
				'individual' => 1
			);
			if ($full === FALSE)
			{
				return $usersunit['first_name'].' '.$usersunit['last_name'].' ('.$unit['unittype'].' '.$unit['number'].')';
			} else {
				return $unit;
			}
		} else {
			// Normal user, lets get their unit
			// Get the unit
			$query = $this->db->get_where('unit', array('id' => $usersunit['company']));
			$unit = $query->row_array();
	
			// Is it a unit?
			if (empty($unit['id'])) {
				return false;
			}
	
			// Is our user actually part of this unit?
			if ($user == $unit['alt'] || $user == $unit['primary'])
			{
				if ($full === 'id') return $unit['id'];
				if ($full === FALSE)
				{
					return ($unit['associatednumber'] == '0' ) ? $unit['unittype'].' '.$unit['number']: $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')';
				} else {
					return $unit;
				}
			} else {
				if ($full === FALSE)
				{
					return 'No unit';
				} else {
					return FALSE;
				}
			}
		}
	}

	// First time to camper
	public function new_to_camper($email) {
		// Setup
		$this->db->select('id,firsttime');
		$query = $this->db->get_where('auth_users', array('email' => $email));
		$result = $query->row_array();
		
		// Update db with 0
		$this->db->where('id', $result['id']);
		$this->db->update('auth_users', array('firsttime'=>0));
		
		// Return our value
		if (isset($result['firsttime'])) {
			if ($result == 1) {
				return true;
			} else { 
				return false;
			}
		} else {
			return true;
		}
		return true;
	}

	// Time Difference
	public function twitterdate($date)
	{
		if(empty($date)) {
		return "No date provided"; 
		}
		
		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");
		
		$now = time();
		//$unix_date = strtotime($date);
		$unix_date = $date;
		// Check validity of date
		if(empty($unix_date)) {
		return "Bad date"; 
		}
		
		// Determine tense of date
		if($now > $unix_date) {
		$difference = $now - $unix_date;
		$tense = "ago"; } else {
		$difference = $unix_date - $now;
		$tense = "from now";
		}
		
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		$difference /= $lengths[$j];
		}
		
		$difference = round($difference);
		
		if($difference != 1) {
		$periods[$j].= "s";
		}
		
		return "$difference $periods[$j] {$tense}";
		
		/*
		$result = twitterdate($date);
		echo $result;
		*/
	}	

	// Number format function that will drop the decimal if ending in .0 or .00
	function number_format_drop_zero($n, $n_decimals=2)
	{
		return ((floor($n) == round($n, $n_decimals)) ? number_format($n) : number_format($n, $n_decimals));
	}

	// Create a new event registration.
	// $session = (required) id - id for a session, we will get the event
	// $unit = id - id for the unit if unit reg
	// $user = id - id for user if individual
	// $singlenumber = (false) bool - return single total number instead of an array
	//
	// Returns single total or array of totals for the session or array of sessions
	function create_event_registration($unit=false,$user=false,$session=false,$group=false,$male=false,$female=false,$youth=false,$time=false)
	{
		// Setup
		
		if (!$session || !is_numeric($session)) return array('status'=>false,'message'=>'Please choose a session.');
		$session = $this->data->get_sessions($session);
		if (!isset($session['id'])) return array('status'=>false,'message'=>'This session has past or is not open for registration.');
		$event = $session['eventid'];
		$currentuser = $this->ion_auth->user()->row();

		
		if (is_numeric($user) && $unit === false) {
			// Individual
			$individual = true;
			if (!in_array('Individuals', $event['eligibleunits'])) return array('status'=>false,'message'=>'This event is not open for individual registration.');
			$user = $this->data->get_users($user);
			
		} elseif (is_numeric($unit) && $user === false) {
			// Unit
			$individual = false;
			if (!in_array($unit['unittype'].'s', $event['eligibleunits'])) return array('status'=>false,'message'=>$unit['unittype'].'s are not eligible to register for this event.');
			
			
		} else {
			return array('status'=>false,'message'=>'A unit or user must be set to register for an event.');
		}
		
		// Validate Details
		
		// Check Rules
		
		// Build Reg
		// Starter set
		$reg = array(
			'token' 			=> 'N'.md5(microtime().random_string('alnum', 4)),
			'sessionid' 		=> $session['id'],
			'eventid' 			=> $event['id'],
			'youth'				=> $count['youth'],
			'male'				=> $count['male'],
			'female'			=> $count['female'],
			'family'			=> $count['family'],
			'count'				=> $count['total'],
			'max'				=> $count['total'],
			'discounts'			=> null,
			'options' 			=> null,
			'roster'			=> 0,
			'active'			=> 1,
			'activitypreorders'	=> 0,
			'latefeesexempt'	=> 0,
			'bluecards'			=> 0,
			'new' 				=> 1
		);

		// Conditions
		if ($group) $reg['group'] = $group;
		if ($individual) {
			// We have an individual reg
			array_push($reg, array(
				'unitid' 		=> 0,
				'userid' 		=> $user['id'],
				'individual' 	=> 1,
			));
			
		} else {
			// Unit reg
			array_push($reg, array(
				'unitid' 		=> $unit['id'],
				'userid' 		=> 0,
				'individual' 	=> 0,
			));
		}

		// Set registerdate
		$registrar = serialize(array(
			'user' => $currentuser['id'],
			'time' => ($time === false) ? time(): $time,
		));
		$reg['registerdate'] = $registrar;

		// Add to the database
		$this->db->insert('eventregs', $reg);

		// Call the record we just made
		$query = $this->db->get_where('eventregs', array('token' => $reg['token']));
		$newreg = $query->row_array();
		$reg = $this->data->get_regs($newreg['id'],true);

		// Notify admin
		$definitions = array(
			'r' => $newreg['id'],
			'i' => $reg['eventid']['id'],
			'e' => $reg['eventid']['title'],
			's' => (isset($reg['session']['title']) && $reg['session']['title'] !== '') ? $reg['session']['title']: $reg['eventid']['sessiontitle'].' '.$reg['session']['sessionnum'],
			'w' => ($regdata['individual'] == 1) ? $reg['userid']['first_name'].' '.$reg['userid']['last_name'].' (individual)': $reg['unitid']['unittitle'].' ('.$reg['unitid']['city'].', '.$reg['unitid']['state'].')',
		);

		$this->shared->notify('newregistration',$definitions,false,'admin');
		
		// Return 
		return $result;
		
	}

	// Create a CSRF key/token pair for a form (NOTE: TO BE REMOVED, see fx without underscore)
	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	// Validate a CSRF key/token pair from a submitted form (NOTE: TO BE REMOVED, see fx without underscore)
	public function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	// Create a CSRF key/token pair for a form
	public function get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('ccsrfkey', $key);
		$this->session->set_flashdata('ccsrfvalue', $value);

		return array($key => $value);
	}

	// Validate a CSRF key/token pair from a submitted form
	public function valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('ccsrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('ccsrfkey')) == $this->session->flashdata('ccsrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	// Render page function used for the ion auth pages, being phased out
	function _render_page($view, $data=null, $render=false)
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $render);

		if (!$render) return $view_html;
	}
	
}
?>