<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin API Model
 *
 * This ...
 *
 * Version 1.0 (2012.10.18.0017)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Api_model extends CI_Model {

	public function __construct()
	{
	}

	// Get all of the users in a group
	public function get_units($type=FALSE)
	{
		if ($type) {
			$this->db->where('unittype', $type);
			if ($type=='Troop') $this->db->or_where('unittype', 'Team');
			if ($type=='Crew') $this->db->or_where('unittype', 'Ship');
			if ($type=='Pack') $this->db->or_where('unittype', 'Den');
		}
		$this->db->order_by("number", "asc");
		$query = $this->db->get('unit');
		return $query->result_array();
	}

	// Get all of the events in the system
	public function get_events()
	{
		$this->db->order_by("datestart", "asc");
		$query = $this->db->get('event');
		return $query->result_array();
	}

	// Get all of the events in the system
	public function get_sessions()
	{
		$query = $this->db->get('sessions');
		return $query->result_array();
	}

	// Get all of the events in the system
	public function get_regs($event=null)
	{
		if (isset($event)) $this->db->where('eventid', $event);
		/*$this->db->select('id,unitid,eventid,session');*/
		$query = $this->db->get('eventregs');
		return $query->result_array();
	}

	// Get all of the users in a group
	public function get_unit_leaders()
	{
		$this->db->select('id,first_name,last_name,company,phone,email,individual,individualdata');
		//$this->db->where('unittype', $type); Figure out how to only fetch leaders at some point
		$this->db->order_by("last_name", "asc");
		$query = $this->db->get('auth_users');
		return $query->result_array();
	}
	// Close an event
	public function close_event($event)
	{
		$this->db->where('id', $event);
		$this->db->update('event', array('open' => 0));
		return true;
	}

	// Open an event
	public function open_event($event, $verify=FALSE)
	{
		if ($verify === FALSE) {
			// Does this event have sessions?
			$sessions = $this->shared->get_sessions($event); 
			if (!$sessions) return false;
		}
		$this->db->where('id', $event);
		$this->db->update('event', array('open' => 1));
		return true;
	}

	// Deactivate a registration
	public function deactivate_reg($reg)
	{
		$this->db->where('id', $reg);
		$this->db->update('eventregs', array('active' => 0));
		return true;
	}

	// Activate a registration
	public function activate_reg($reg)
	{
		$this->db->where('id', $reg);
		$this->db->update('eventregs', array('active' => 1));
		return true;
	}

}

?>