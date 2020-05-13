<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Data Model
 *
 * This model focuses on getting recursive and formatted data from the 
 * database for easy use.
 *
 * Version 1.5 (2014.04.08.1050)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 * How to use this class:
 * Add a function in the get_resources($id=FALSE, $recursive=FALSE, $where=array('id'=>$id), $data=array('resource'=>$resource), $sort=array('date'=>'desc'), $limit=array($limit,$offset)) format
 * Resources is the base data set. 
 * $id means that if an id is set, we will get that one, otherwise, we will get them all
 * $recursive means that if true, we will replace the top level IDs in the set with associated records
 * $where is our where clause, we will use that (in array form) for our sql query for the top most level
 * $data is the place where you can feed data sources in that have already been called, 
 * 	in array('sessions'=>$sessions) format, this should match the database table name
 *
 * When calling these functions, it is best to call the higher level of info so you dont make double calls. 
 * For example, don't call get_events and get_sessions to get a single event and sessions. Just call get_sessions
 * and use the get event that comes through with the recursive function.
 *
 */

class data extends CI_Model {

	public function __construct()
	{
		// Just making sure
		$this->load->database();
	}

	/*
	 * Data Helper Functions
	 */

	// Run a local get function if data hasn't yet been retrieved
	public function get_data($active, $source, $id, $recursive=FALSE)
	{
		$function = "get_$source";
		$return = ($recursive) ? $this->$function($id, true): $this->$function($id);
		$GLOBALS['get_data'][$active][$source][$id] = $return;
		return $return;
	}

	// Setup the database call and run it
	public function setup_db($method, $table, $id, $where, $data, $sort, $limit)
	{
		// Setup
		if ($id) $this->db->where('id',$id);
		if ($sort) ((is_array($sort)) ? $this->db->order_by($sort[0], $sort[1]): $this->db->order_by($sort));
		if (is_array($limit)) $this->db->limit($limit[0],$limit[1]);
		if (is_array($where)) $this->db->where($where);
		$query = $this->db->get($table);
		$base = $query->result_array();
		$GLOBALS['get_data'][$method] = (is_array($data)) ? $data: (isset($GLOBALS['get_data'][$method]) && is_array($GLOBALS['get_data'][$method])) ? $GLOBALS['get_data'][$method]: array();
		return $base;
	}
	
	/*
	 * Get Functions
	 * 
	 * Table of Contents
	 * 
	 ¥ get_regs				regs with unit, event, session, discounts, options
	 ¥ get_regs_full 		regs with unit, event, session, discounts, options, payments, verify
	 ¥ get_users			users with units
	 ¥ get_units 			units with users
	 ¥ get_payments			payments with user, reg, unit, and individual(if set)
	 ¥ get_rosters			rosters with reg, unit, member
	 ¥ get_rosters_full		rosters with reg, unit, member, event, session
	 ¥ get_members			members with unit
	 ¥ get_classes			classes with activities and event
	 - get_classes_full		classes with activities, event, classregs, members, units
	 ¥ get_activities		activities
	 ¥ get_classregs		classregs with member, unit, class, activity
	 ¥ get_classregs_full	classregs with member, unit, class, activity, session, event
	 ¥ get_events			events with sessions, options, discounts
	 ¥ get_events_full		events with sessions, options, discounts, regs, units
	 ¥ get_sessions			sessions with events, discounts, options
	 ¥ get_options			options with event
	 ¥ get_discounts		discounts with event
	 */

	// Gets event registrations with unit, event, session
	public function get_regs($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'regs';
		$base = $this->setup_db($method, 'eventregs', $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($i['unitid'] == 0) { if ($recursive) $i['userid'] = (isset($GLOBALS['get_data'][$method]['users'][$i['userid']])) ? $GLOBALS['get_data'][$method]['users'][$i['userid']]: $this->get_data($method, 'users', $i['userid']); }
			else { if ($recursive) $i['unitid'] = (isset($GLOBALS['get_data'][$method]['units'][$i['unitid']])) ? $GLOBALS['get_data'][$method]['units'][$i['unitid']]: $this->get_data($method, 'units', $i['unitid']); }
			//if ($recursive) $i['eventid'] = (isset($GLOBALS['get_data'][$method]['events'][$i['eventid']])) ? $GLOBALS['get_data'][$method]['events'][$i['eventid']]: $this->get_data($method, 'events', $i['eventid']);
			if ($recursive) $i['session'] = (isset($GLOBALS['get_data'][$method]['sessions'][$i['session']])) ? $GLOBALS['get_data'][$method]['sessions'][$i['session']]: $this->get_data($method, 'sessions', $i['session'], true);
			if ($recursive) $i['eventid'] = $i['session']['eventid'];

			// Handle any unserialization
			$i['registerdate'] = unserialize($i['registerdate']);
			$i['options'] = unserialize($i['options']);
			$i['discounts'] = unserialize($i['discounts']);
			
			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets event registrations with unit, event, session, options, discounts
	public function get_regs_full($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'regs';
		$base = $this->setup_db($method, 'eventregs', $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($i['unitid'] == 0) { 
				if ($recursive) $i['userid'] = (isset($GLOBALS['get_data'][$method]['users'][$i['userid']])) ? $GLOBALS['get_data'][$method]['users'][$i['userid']]: $this->get_data($method, 'users', $i['userid']); 
			} else { 
				if ($recursive) $i['unitid'] = (isset($GLOBALS['get_data'][$method]['units'][$i['unitid']])) ? $GLOBALS['get_data'][$method]['units'][$i['unitid']]: $this->get_data($method, 'units', $i['unitid']); 
			}
			if ($recursive) $i['eventid'] = (isset($GLOBALS['get_data'][$method]['events'][$i['eventid']])) ? $GLOBALS['get_data'][$method]['events'][$i['eventid']]: $this->get_data($method, 'events', $i['eventid']);
			if ($recursive) $i['session'] = (isset($GLOBALS['get_data'][$method]['sessions'][$i['session']])) ? $GLOBALS['get_data'][$method]['sessions'][$i['session']]: $this->get_data($method, 'sessions', $i['session']);
			if ($recursive) $i['eventid']['discounts'] = $this->get_discounts(false, false, array('eventid'=>$i['eventid']['id']));
			if ($recursive) $i['eventid']['options'] = $this->get_options(false, false, array('eventid'=>$i['eventid']['id']));

			// Handle any unserialization
			$i['registerdate'] = unserialize($i['registerdate']);
			$i['options'] = unserialize($i['options']);
			$i['discounts'] = unserialize($i['discounts']);
			if ($i['unitid'] == 0) { 
				if (isset($i['userid']['individualdata']) && !is_array($i['userid']['individualdata'])) $i['userid']['individualdata'] = unserialize($i['userid']['individualdata']);
			}
			
			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets users with units
	public function get_users($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'users';
		$base = $this->setup_db($method, 'auth_users', $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($recursive) $i['company'] = (isset($GLOBALS['get_data'][$method]['units'][$i['company']])) ? $GLOBALS['get_data'][$method]['units'][$i['company']]: $this->get_data($method, 'units', $i['company']);

			// Handle any unserialization - $i['key'] = unserialize($i['key']);
			$i['individualdata'] = (isset($i['individualdata'])) ? unserialize($i['individualdata']): null;
			
			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets units with users
	public function get_units($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'units';
		$base = $this->setup_db($method, 'unit', $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($recursive) $i['primary'] = (isset($GLOBALS['get_data'][$method]['users'][$i['primary']])) ? $GLOBALS['get_data'][$method]['users'][$i['primary']]: $this->get_data($method, 'users', $i['primary']);
			if ($recursive) $i['alt'] = (isset($GLOBALS['get_data'][$method]['users'][$i['alt']])) ? $GLOBALS['get_data'][$method]['users'][$i['alt']]: $this->get_data($method, 'users', $i['alt']);

			// Handle any unserialization - $i['key'] = unserialize($i['key']);
			
			// Additional Processing
			$i['unittitle'] = (isset($i['associatedunit']) && $i['associatedunit'] !== '0' ) ? $i['associatedunit'].' '.$i['associatednumber'].' ('.$i['unittype'].' '.$i['number'].')': $i['unittype'].' '.$i['number'];
			
			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets payments with user, reg, unit, and individual(if set)
	public function get_payments($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'payments';
		$base = $this->setup_db($method, $method, $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($recursive) $i['user'] = (isset($GLOBALS['get_data'][$method]['users'][$i['user']])) ? $GLOBALS['get_data'][$method]['users'][$i['user']]: $this->get_data($method, 'users', $i['user']);
			if ($recursive) $i['reg'] = (isset($GLOBALS['get_data'][$method]['regs'][$i['reg']])) ? $GLOBALS['get_data'][$method]['regs'][$i['reg']]: $this->get_data($method, 'regs', $i['reg']);
			if ($i['unit'] == 0) { if ($recursive) $i['individual'] = (isset($GLOBALS['get_data'][$method]['users'][$i['individual']])) ? $GLOBALS['get_data'][$method]['users'][$i['individual']]: $this->get_data($method, 'users', $i['individual']); }
			else { if ($recursive) $i['unit'] = (isset($GLOBALS['get_data'][$method]['units'][$i['unit']])) ? $GLOBALS['get_data'][$method]['units'][$i['unit']]: $this->get_data($method, 'units', $i['unit']); }

			// Handle any unserialization
			$i['details'] = unserialize($i['details']);
			
			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets payments with user, reg, unit, individual(if set), unit, and session
	public function get_payments_full($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'payments';
		$base = $this->setup_db($method, $method, $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($recursive) $i['user'] = (isset($GLOBALS['get_data']['users']['users'][$i['user']])) ? $GLOBALS['get_data']['users']['users'][$i['user']]: $this->get_data('users', 'users', $i['user']);
			if ($recursive) $i['reg'] = (isset($GLOBALS['get_data']['regs']['regs'][$i['reg']])) ? $GLOBALS['get_data']['regs']['regs'][$i['reg']]: $this->get_data('regs', 'regs', $i['reg']);
			if ($i['unit'] == 0) { if ($recursive) $i['individual'] = (isset($GLOBALS['get_data'][$method]['users'][$i['individual']])) ? $GLOBALS['get_data'][$method]['users'][$i['individual']]: $this->get_data($method, 'users', $i['individual']); }
			else { if ($recursive) $i['unit'] = (isset($GLOBALS['get_data'][$method]['units'][$i['unit']])) ? $GLOBALS['get_data'][$method]['units'][$i['unit']]: $this->get_data($method, 'units', $i['unit']); }
			if ($recursive) $i['session'] = (isset($GLOBALS['get_data']['sessions']['sessions'][$i['reg']['session']])) ? $GLOBALS['get_data']['sessions']['sessions'][$i['reg']['session']]: $this->get_data('sessions', 'sessions', $i['reg']['session']);
			if ($recursive) $i['event'] = (isset($GLOBALS['get_data']['events']['events'][$i['reg']['eventid']])) ? $GLOBALS['get_data']['events']['events'][$i['reg']['eventid']]: $this->get_data('events', 'events', $i['reg']['eventid']);

			// Handle any unserialization
			$i['details'] = unserialize($i['details']);
			
			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets rosters with reg, unit, member
	public function get_rosters($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'rosters';
		$base = $this->setup_db($method, 'roster', $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($recursive) $i['reg'] = (isset($GLOBALS['get_data'][$method]['regs'][$i['reg']])) ? $GLOBALS['get_data'][$method]['regs'][$i['reg']]: $this->get_data($method, 'regs', $i['reg']);
			if ($recursive) $i['unit'] = (isset($GLOBALS['get_data'][$method]['units'][$i['unit']])) ? $GLOBALS['get_data'][$method]['units'][$i['unit']]: $this->get_data($method, 'units', $i['unit']);
			if ($recursive) $i['member'] = (isset($GLOBALS['get_data'][$method]['members'][$i['member']])) ? $GLOBALS['get_data'][$method]['members'][$i['member']]: $this->get_data($method, 'members', $i['member']);

			// Handle any unserialization
			$i['discounts'] = unserialize($i['discounts']);

			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		//* Test */ print_r($result); die;

		// Return our processed array, or single row
		//print_r((count($result) == 1) ? $result[$i['id']]: $result); print_r($GLOBALS['get_data']); die;
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets rosters with reg, unit, member, event, session
	public function get_rosters_full($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'rosters';
		$base = $this->setup_db($method, 'roster', $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($recursive) $i['reg'] = (isset($GLOBALS['get_data'][$method]['regs'][$i['reg']])) ? $GLOBALS['get_data'][$method]['regs'][$i['reg']]: $this->get_data($method, 'regs', $i['reg'], true);
			if ($recursive) $i['unit'] = (isset($GLOBALS['get_data'][$method]['units'][$i['unit']])) ? $GLOBALS['get_data'][$method]['units'][$i['unit']]: $this->get_data($method, 'units', $i['unit'], true);
			if ($recursive) $i['member'] = (isset($GLOBALS['get_data'][$method]['members'][$i['member']])) ? $GLOBALS['get_data'][$method]['members'][$i['member']]: $this->get_data($method, 'members', $i['member'], true);

			// Handle any unserialization
			$i['discounts'] = (isset($i['discounts'])) ? unserialize($i['discounts']): false;

			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		//* Test */ print_r($result); die;

		// Return our processed array, or single row
		//print_r((count($result) == 1) ? $result[$i['id']]: $result); print_r($GLOBALS['get_data']); die;
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets members with unit
	public function get_members($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'members';
		$base = $this->setup_db($method, $method, $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;
		

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($recursive) $i['unit'] = (isset($GLOBALS['get_data'][$method]['units'][$i['unit']])) ? $GLOBALS['get_data'][$method]['units'][$i['unit']]: $this->get_data($method, 'units', $i['unit']);

			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets classes with activities, event
	public function get_classes($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'classes';
		$base = $this->setup_db($method, $method, $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($recursive) $i['activity'] = (isset($GLOBALS['get_data'][$method]['activities'][$i['activity']])) ? $GLOBALS['get_data'][$method]['activities'][$i['activity']]: $this->get_data($method, 'activities', $i['activity'], true);
			if ($recursive) $i['event'] = (isset($GLOBALS['get_data'][$method]['events'][$i['event']])) ? $GLOBALS['get_data'][$method]['events'][$i['event']]: $this->get_data($method, 'events', $i['event'], true);

			// Handle any unserialization
			$i['blocks'] = (isset($i['blocks'])) ? unserialize($i['blocks']): false;

			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets classes with activities, event, classregs, members, units
	public function get_classes_full($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		show_error('Unbuilt get classes full function');
	}

	// Gets activities
	public function get_activities($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'activities';
		$base = $this->setup_db($method, $method, $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets classregs with member, class, activity
	public function get_classregs($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'classregs';
		$base = $this->setup_db($method, $method, $id, $where, $data, array('time','asc'), $limit);
		if (empty($base)) return false;

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($recursive) $i['member'] = (isset($GLOBALS['get_data'][$method]['members'][$i['member']])) ? $GLOBALS['get_data'][$method]['members'][$i['member']]: $this->get_data($method, 'members', $i['member'], true);
			if ($recursive) $i['class'] = (isset($GLOBALS['get_data'][$method]['classes'][$i['class']])) ? $GLOBALS['get_data'][$method]['classes'][$i['class']]: $this->get_data($method, 'classes', $i['class']);
			if ($recursive) $i['activity'] = (isset($GLOBALS['get_data'][$method]['activities'][$i['activity']])) ? $GLOBALS['get_data'][$method]['activities'][$i['activity']]: $this->get_data($method, 'activities', $i['activity']);
			//if ($recursive) $i['unit'] = (isset($GLOBALS['get_data'][$method]['units'][$i['member']['unit']])) ? $GLOBALS['get_data'][$method]['units'][$i['member']['unit']]: $this->get_data($method, 'units', $i['member']['unit']);
			
			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets classregs with member, unit, class, activity, session, event
	public function get_classregs_full($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'classregs';
		$base = $this->setup_db($method, $method, $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($recursive) $i['member'] = (isset($GLOBALS['get_data'][$method]['members'][$i['member']])) ? $GLOBALS['get_data'][$method]['members'][$i['member']]: $this->get_data($method, 'members', $i['member']);
			if ($recursive) $i['unit'] = (isset($GLOBALS['get_data']['units'][$i['member']['unit']])) ? $GLOBALS['get_data']['units'][$i['member']['unit']]: $this->get_data('units', 'units', $i['member']['unit']);
			if ($recursive) $i['class'] = (isset($GLOBALS['get_data'][$method]['classes'][$i['class']])) ? $GLOBALS['get_data'][$method]['classes'][$i['class']]: $this->get_data($method, 'classes', $i['class']);
			if ($recursive) $i['activity'] = (isset($GLOBALS['get_data'][$method]['activities'][$i['activity']])) ? $GLOBALS['get_data'][$method]['activities'][$i['activity']]: $this->get_data($method, 'activities', $i['activity']);
			if ($recursive) $i['reg'] = (isset($GLOBALS['get_data'][$method]['regs'][$i['reg']])) ? $GLOBALS['get_data'][$method]['regs'][$i['reg']]: $this->get_data($method, 'regs', $i['reg']);
			if ($recursive) $i['session'] = (isset($GLOBALS['get_data']['sessions'][$i['reg']['session']])) ? $GLOBALS['get_data']['sessions'][$i['reg']['session']]: $this->get_data('sessions', 'sessions', $i['reg']['session']);
			if ($recursive) $i['event'] = (isset($GLOBALS['get_data']['events'][$i['session']['eventid']])) ? $GLOBALS['get_data']['events'][$i['session']['eventid']]: $this->get_data('events', 'events', $i['session']['eventid']);
			
			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets events with sessions, options, discounts
	public function get_events($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'events';
		$base = $this->setup_db($method, 'event', $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;
		
		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($recursive) $i['sessions'] = $this->get_sessions(false, false, array('eventid'=>$i['id']));
			if ($recursive) $i['discounts'] = $this->get_discounts(false, false, array('eventid'=>$i['id']));
			if ($recursive) $i['options'] = $this->get_options(false, false, array('eventid'=>$i['id']));

			// Handle any unserialization
			$i['periods'] = (isset($i['freeadults'])) ? unserialize($i['periods']): false;
			$i['earlyreg'] = (isset($i['freeadults'])) ? unserialize($i['earlyreg']): false;
			$i['paymenttiers'] = (isset($i['freeadults'])) ? unserialize($i['paymenttiers']): false;
			$i['freeadults'] = (isset($i['freeadults'])) ? unserialize($i['freeadults']): false;
			$i['eligibleunits'] = (isset($i['freeadults'])) ? unserialize($i['eligibleunits']): false;
			$i['groups'] = (isset($i['freeadults'])) ? unserialize($i['groups']): false;

			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets events with sessions, options, discounts, regs
	public function get_events_full($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'events';
		$base = $this->setup_db($method, 'event', $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;
		
		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($recursive) $i['sessions'] = $this->get_sessions(false, false, array('eventid'=>$i['id']));
			if ($recursive) $i['discounts'] = $this->get_discounts(false, false, array('eventid'=>$i['id']));
			if ($recursive) $i['options'] = $this->get_options(false, false, array('eventid'=>$i['id']));
			$i['regs'] = $this->get_regs(false, (($recursive) ? true: false), array('eventid'=>$i['id']));

			// Handle any unserialization
			$i['periods'] = (isset($i['freeadults'])) ? unserialize($i['periods']): false;
			$i['earlyreg'] = (isset($i['freeadults'])) ? unserialize($i['earlyreg']): false;
			$i['paymenttiers'] = (isset($i['freeadults'])) ? unserialize($i['paymenttiers']): false;
			$i['freeadults'] = (isset($i['freeadults'])) ? unserialize($i['freeadults']): false;
			$i['eligibleunits'] = (isset($i['freeadults'])) ? unserialize($i['eligibleunits']): false;
			$i['groups'] = (isset($i['freeadults'])) ? unserialize($i['groups']): false;

			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets event sessions with events
	public function get_sessions($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'sessions';
		$base = $this->setup_db($method, $method, $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($recursive) $i['eventid'] = (isset($GLOBALS['get_data'][$method]['events'][$i['eventid']])) ? $GLOBALS['get_data'][$method]['events'][$i['eventid']]: $this->get_data($method, 'events', $i['eventid']);

			// Additional Processing
			if ($recursive) $i['nicetitle'] = (empty($i['title'])) ? $i['eventid']['sessiontitle'].' '.$i['sessionnum']: $i['title'];

			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets event options with event
	public function get_options($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'options';
		$base = $this->setup_db($method, $method, $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($recursive) $i['eventid'] = (isset($GLOBALS['get_data'][$method]['events'][$i['eventid']])) ? $GLOBALS['get_data'][$method]['events'][$i['eventid']]: $this->get_data($method, 'events', $i['eventid']);
			
			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets event discounts with event
	public function get_discounts($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'discounts';
		$base = $this->setup_db($method, $method, $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;
		

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			if ($recursive) $i['eventid'] = (isset($GLOBALS['get_data'][$method]['events'][$i['eventid']])) ? $GLOBALS['get_data'][$method]['events'][$i['eventid']]: $this->get_data($method, 'events', $i['eventid']);
			
			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}

	// Gets event discounts with event
	public function get_report_definitions($id=FALSE, $recursive=FALSE, $where=FALSE, $data=FALSE, $sort=FALSE, $limit=FALSE)
	{
		// Setup
		$method = 'reportdefinitions';
		$base = $this->setup_db($method, $method, $id, $where, $data, $sort, $limit);
		if (empty($base)) return false;
		

		// Walk the query result
		$result = array();
		foreach ($base as $i) {
			// Handle recursive elements
			//if ($recursive) $i['eventid'] = (isset($GLOBALS['get_data'][$method]['events'][$i['eventid']])) ? $GLOBALS['get_data'][$method]['events'][$i['eventid']]: $this->get_data($method, 'events', $i['eventid']);
			$i['data'] = (isset($i['data'])) ? unserialize($i['data']): false;

			// Update the key and add to result array
			$result[$i['id']] = $i;
		}

		// Return our processed array, or single row
		return ($id !== false && count($result) == 1) ? $result[$i['id']]: $result;
	}


}