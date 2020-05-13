<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin Users Model
 *
 * This is a collection of functions that help out on pages in the admin units and users pages
 *
 * Version 1.4.5 (2014 04 23 1530)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Users_model extends CI_Model {

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

	// Get all of the users in a group
	public function get_unit_leaders()
	{
		$this->db->select('id,first_name,last_name,company,phone,email');
		//$this->db->where('unittype', $type); Figure out how to only fetch leaders at some point
		$this->db->order_by("last_name", "asc");
		$query = $this->db->get('auth_users');
		return $query->result_array();
	}

	// Get user by email
	public function get_user_by_email($email = FALSE)
	{
		if ($email == FALSE)
		{
			//show_error('You can\t find an users id without giving the email. fx users get_user_by_email');
			return false;
		}
		$this->db->select('id,company,email');
		$query = $this->db->get_where('auth_users', array('email' => $email));
		$user = $query->row_array();
		if (empty($user['id'])) {
			return false;
		}
		return $user;
		
	}
	
	// Get user by id
	public function get_user_by_id($id = FALSE)
	{
		if ($id == FALSE)
		{
			//show_error('You can\t find an user's email without giving the id. fx users get_user_by_id');
			return false;
		}
		$this->db->select('id,company,email');
		$query = $this->db->get_where('auth_users', array('id' => $id));
		return $query->row_array();
		
	}

	// Make an user an individual
	public function set_individual($id,$user=FALSE)
	{
		// Setup
		if ($user === FALSE) {
			$user = $this->ion_auth->user($id)->row();
		}
		$units = array();
		
		// Get units the user is in
		$this->db->select('id');
		$query = $this->db->get_where('unit', array('primary' => $user->id));
		$primary = $query->result_array();
		$this->db->select('id');
		$query = $this->db->get_where('unit', array('alt' => $user->id));
		$alternate = $query->result_array();

		// Find this user's units
		if (!empty($primary)) {
			foreach ($primary as $p) {
				$this->swap_contacts($p['id']);
				$units[] = $p['id'];
			}
			$primary = true;
		} else {
			$primary = false;
		}
		if (!empty($alternate)) {
			foreach ($alternate as $a) {
				$units[] = $a['id'];
			}
			$alternate = true;
		} else {
			$alternate = false;
		}

		// Remove this user and replace with zeros
		if (!empty($units)) {
			$batch = array();
			foreach ($units as $u) {
				$batch[] = array('id'=>$u, 'alt'=>'0');
			}
			$this->db->update_batch('unit', $batch, 'id'); 
		}
		
		// Units are all set, let's update our user
		$updateduser = array(
			'company' => 0
		);
		$this->db->where('id', $user->id);
		$this->db->update('auth_users', $updateduser);
		
		return true;
	}

	// Swap Units
	public function swap_contacts($unit)
	{		
		// Setup
		if (!is_array($unit)) {
			$unit = $this->shared->get_units($unit);
		}
		
		$swapped = array(
			'primary'	=> $unit['alt'],
			'alt'		=> $unit['primary']
		);
		$this->db->where('id', $unit['id']);
		$this->db->update('unit', $swapped);
		//$message = $message.'Updated '.$unit['unittype'].' '.$unit['number'].' without a problem!';
		return true;
	}
	
	// Swap units if primary is unset
	public function alt_to_pri($unit)
	{		
		if ($unit['primary'] == '0') {
			$swapped = array(
				'primary'	=> $unit['alt'],
				'alt'		=> '0'
			);
			$this->db->where('id', $unit['id']);
			$this->db->update('unit', $swapped);
			return true;
		} else {
			//show_error('You can\'t set the alternate as primary if the primary is set with this method. fx users_model alt_to_pri()');
			// Let's run our swap contacts script
			$this->swap_contacts($unit);
			return true;
		}
	}
	
	// Update an event
	public function update_unit($unit, $basic=FALSE)
	{		
		$message = '';
		if ($basic == FALSE) {
			$unitdata = array(
				'unittype'	=> $this->input->post('unittype'),
				'council'	=> $this->input->post('council'),
				'number'	=> $this->input->post('number'),
				'district'	=> $this->input->post('district'),
				'address'	=> $this->input->post('address'),
				'city'		=> $this->input->post('city'),
				'state'		=> $this->input->post('state'),
				'zip'		=> $this->input->post('zip')
			);
		} else {
			$unitdata = array(
				//'council'	=> $this->input->post('council'),
				//'district'	=> $this->input->post('district'),
				'address'	=> $this->input->post('address'),
				'city'		=> $this->input->post('city'),
				'state'		=> $this->input->post('state'),
				'zip'		=> $this->input->post('zip')
			);
		}

		// Associated Unit and Number
		if ($this->input->post('associated')) {
			$unitdata['associatedunit'] = $this->input->post('associatedunit');
			$unitdata['associatednumber'] = $this->input->post('associatednumber');
		}

		$this->db->where('id', $unit);
		$this->db->update('unit', $unitdata);
			
		$message = $message.'Updated without a problem!';
		return $message;
	}
	
	// Change contacts for a unit
	public function change_contacts($user, $unit, $oldunit, $scope)
	{
		// Replace old unit id with new in the user's company field
		$this->db->where('id', $user['id']);
		$this->db->update('auth_users', array('company' => $unit['id']));
		
		// Reset old unit's alt/pri contact with a 0 for none
		if ($oldunit !== false) {
			// First, figure out if they were the alternate or primary
			$this->db->select('primary,alt');
			$query = $this->db->get_where('unit', array('id' => $oldunit['id']));
			$oldunitcontacts = $query->row_array();
			if ($oldunitcontacts['primary'] == $user['id']) {
				// If they were the primary contact, swap contacts and reset alt as 0
				$swapped = array(
					'primary'	=> $oldunitcontacts['alt'],
					'alt'		=> 0
				);
				$this->db->where('id', $oldunit['id']);
				$this->db->update('unit', $swapped);
			} elseif ($oldunitcontacts['alt'] == $user['id']) {
				// If they were the alternate contact, set alt as 0
				$this->db->where('id', $oldunit['id']);
				$this->db->update('unit', array('alt' => 0));
			} else {
				// This is not right, we shouldn't be here so we'll blast an error through
				// This could have been because they had an account but werent a formal user. This will need to be revised at some point.
				//show_error('old_unit_pri='.$oldunitcontacts['primary'].' oldunitalt='.$oldunitcontacts['alt'].' userid='.$user['id'].' oldunitid='.$oldunit['id'].' //The user was never a part of the unit their account said they were apart of so they the old unit didn\'t get changed. This could have happened because the user was never approved as the alt or primary contact. They are now and the changes have been made. Double check to make sure things look good.');
				//return false;
				
				// log this eventually?
			}
		}
		
		// If scope is new, let's figure out if this will be our primary or alternate contact
		if ($scope == 'new') {
			if ($unit['primary'] == '0') { $scope = 'primary'; } else { $scope = 'alt'; }
		}
		
		// Replace new unit id with new in the user's company field
		$this->db->where('id', $unit['id']);
		$this->db->update('unit', array($scope => $user['id']));
		return true;
	}
	

}

?>