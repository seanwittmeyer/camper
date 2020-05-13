<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin Report Model
 *
 * This is a collection of helper functions that builds reports and fetches data to put in them.
 *
 * Version 1.0 (2014.05.06.1436)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Report_model extends CI_Model {

	public function __construct()
	{
		// Everything is already loaded.
	}

	// Get the report definition for a given report
	public function get_regs_full($selection=false,$where=false)
	{
		// if array process where into proper where string
		// use the data->get_regs_full with the select and where.
		
		// build table, make custom changes (ie. swap associated unit with real unit when present) after table is made. 
		// run a foreach on the selection, always include the ID
		$table = array();
		return $table;
	}



	/* Sample things that come in handy when making model functions
	// Function role
	public function toolbox($event = FALSE)
	{
		$this->db->order_by("datestart","asc");
		$this->db->where('individual', 1);

		$this->db->select('title, content, date');


		$query = $this->db->get_where('event', array('id' => $event));
		$query = $this->db->get('event');
		
		$result = $query->result_array();
		$result = $query->row_array();

		$this->db->where('id', $regid);
		$this->db->update('eventregs', $regdata);

		$this->db->insert('event', $eventdata);

		$this->db->delete('eventregs', array('id' => $regid));


		$this->shared->prep_option($this->input->post('youth'),'number')		
	}
	*/

	// Get an array of all distinct values for a given table's field.
	public function get_distinct_values($table,$field)
	{
		// Only get info we will need
		$this->db->distinct();
		$this->db->select($field);
		$query = $this->db->get($table);
		print_r($query->result_array());die;
		return $query->result_array();
		
	}

	// Get all of the events, replace uses with get_events()
	public function get_reports($reportid=FALSE, $type=FALSE, $user=FALSE, $accesslevel=FALSE)
	{
		// Only get info we will need
		$this->db->select('id, title, userid, created, structure, members, admin, staff, lastrun, array');

		// Get 'er done
		if ($reportid === FALSE) {
			// Set our wheres
			if ($type !== FALSE) {
				$this->db->where('type', $type);
			} 
			if ($user !== FALSE) {
				$this->db->where('user', $user);
			} 
			if ($accesslevel !== FALSE) {
				$this->db->where($accesslevel, 1);
			} 

			// Get the results
			$query = $this->db->get('reports');
			if (empty($query)) {
				return false;
			} else {
				$results = $query->result_array();
				$newresults = array();
				foreach ($results as $result) {
					$result['structure'] = unserialize($result['structure']);
					$result['array'] = unserialize($result['array']);
					$newresults[$result['id']] = $result;
				}
				return $result;
			}

		} else {
			// Do we want a single report?
			$query = $this->db->get_where('reports', array('id' => $reportid));
			if (empty($query)) {
				return false;
			} else {
				$result = $query->row_array();
				$result['structure'] = unserialize($result['structure']);
				$result['array'] = unserialize($result['array']);
				return $result;
			}
		}
	}
		
}
?>