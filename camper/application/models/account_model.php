<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin Accounts Model
 *
 * This ...
 *
 * Version 1.0 (2012.10.18.0017)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Account_model extends CI_Model {

	public function __construct()
	{
	}

	// Create an unit
	public function create_unit($details, $unique)
	{
		// Insert the new unit
		$this->db->insert('unit', $details);
		return $this->db->insert_id();
	}

	// Create a new user
	public function create_user($email,$password,$details)
	{
		$username = strtolower($details['first']) . ' ' . strtolower($details['last']);
		$email    = strtolower($email);
		$password = $password;
		$additional_data = array(
			'first_name' 		=> $details['first'],
			'last_name'  		=> $details['last'],
			'company'    		=> 0,
			'phone'      		=> $details['phone'],
			'individualdata' 	=> $details['individualdata']
		);
		if ($this->ion_auth->register($username, $password, $email, $additional_data))
		{
			//check to see if we are creating the user
			//redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
		}
		else { return false; }
		$userid = $this->data->get_users(false,false,array('email'=>$email));
		foreach ($userid as $user) return $user['id'];
	}

	// Set a new user's unit
	public function set_user_unit($user, $unit)
	{
		$this->db->where('id', $user);
		$this->db->update('auth_users', array('company' => $unit));
		return true;
	}

	// Update a users details
	public function update_user($user, $fields)
	{
		$this->db->where('id', $user);
		$this->db->update('auth_users', $fields);
		return true;
	}

	// Set a unit's contact
	public function set_unit_contact($unit, $user, $role='alternate')
	{
		if ($role === 'primary') {
			$field = 'primary';
		} elseif ($role === 'alternate') {
			$field = 'alt';
		} else {
			return false;
		}
		$this->db->where('id', $unit);
		$this->db->update('unit', array($field => $user));
		return true;
	}

	
	// Get all of the users in a group
	public function get_units($type=FALSE)
	{
		if ($type == FALSE) {
			$this->db->select('id,district,council,number,unittype,primary,city,state,associatedunit,associatednumber');
		} else {
			$this->db->select('id,district,council,number,unittype,primary,city,state,associatedunit,associatednumber');
			$this->db->where('unittype', $type);
			if ($type=='Crew') $this->db->or_where('unittype', 'Ship');
			if ($type=='Den') $this->db->or_where('unittype', 'Pack');
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

	// Single Event Details, all fields
	public function get_single_unit($unit = FALSE)
	{
		if ($unit == FALSE) return false;

		$query = $this->db->get_where('unit', array('id' => $unit));
		return $query->row_array();
	}

	// Get user by email
	public function get_user_by_email($email = FALSE, $all = FALSE)
	{
		if ($email == FALSE)
		{
			//show_error('You can\t find an users id without giving the email. fx account get_user_by_id()');
			return false;
		}
		if ($all == FALSE) {
			$this->db->select('id,company,email,individual,individualdata');
		}		
		$query = $this->db->get_where('auth_users', array('email' => $email));
		$user = $query->row_array();
		if (empty($user['id'])) {
			return false;
		}
		return $user;
		
	}
	
	// Get user by id
	public function get_user_by_id($id=FALSE, $full=FALSE)
	{
		if ($id === FALSE) return false;
		if ($full === FALSE) {
			$this->db->select('id,company,email,individual,individualdata');
		}
		$query = $this->db->get_where('auth_users', array('id' => $id));
		return $query->row_array();
		
	}

	// Update an event
	public function swap_contacts($unit)
	{		
		$swapped = array(
			'primary'	=> $unit['alt'],
			'alt'		=> $unit['primary']
		);
		$this->db->where('id', $unit['id']);
		$this->db->update('unit', $swapped);
		//$message = $message.'Updated '.$unit['unittype'].' '.$unit['number'].' without a problem!';
		return true;
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

	// Invite a contact for a unit
	public function invite_contact($unit, $user, $role = "alternate")
	{
		$currentuser = $this->ion_auth->user()->row();
		$currentunit = $this->shared->get_current_unit($unit, $currentuser->id);
		if ($role === 'alternate') {
			$type = 'alt';
		} elseif ($role === 'primary') {
			$type = 'primary';
		} else {
			return false;
		}
		if ($currentuser->company == $unit || $this->ion_auth->is_admin()) {

			// Is our new user already in the system? 
		    if ($this->ion_auth->email_check($user)) {
				// User is in the system, if they don't have a unit, we'll add them now.
				$newuser = $this->get_user_by_email($user, true);
				if (isset($newuser['email'])) {
					// Update unit, new user, old user
					if ($newuser['id'] == $currentuser->id) {
						$this->session->set_flashdata('message', "You can't set yourself as both contacts. Use the swap contacts tool to change your unit contact type."); 
						//show_error(2);
						return false;
					}
					if ($newuser['company'] == 0) {
						// Our invited user isn't a part of a unit, we will make them the alternate and let them know it succeeded
						//show_error('new user company = 0');
						$this->set_unit_contact($unit, $newuser['id'], $role);
						$this->set_user_unit($newuser['id'], $unit);
						$this->set_user_unit($currentunit[$type], 0);
						$this->shared->send_notification(
							"You are now the $role contact for ".$currentunit['unittype'].' '.$currentunit['number'].", which means you can manage and register for events on Camper. You can sign on today and get started. ".$currentuser->first_name.' '.$currentuser->last_name.' made you the contact.', 
							"You've been made the $role contact for ".$currentunit['unittype'].' '.$currentunit['number'], 
							$currentuser->first_name.' '.$currentuser->last_name, 
							$newuser['first_name'], 
							$newuser['last_name'], 
							$newuser['email'], 
							$link=false, $notificationid=false
						);
						return true;				
					} else {
						// Our invited user is already part of an unit, we'll ask if it's ok to switch them.
						//show_error('new user company != 0');
						/*  This section is incomplete, it should email the user that they can choose to accept the invite to manage the unit or not. 
							If they accept, it should go to a page that accepts the invite, sets them as the alt contact for the unit, resets the alt of the old unit, then notify the old unit's remaining contact that they need to invite someone new.
						$newuserunit = $this->get_single_unit($newuser['company']);
						if ($newuser['id'] == $newuserunit['alt']) {
							$newuserunit['role'] = 'alternate'; 
						} elseif ($newuser['id'] == $newuserunit['primary']) {
							$newuserunit['role'] = 'primary';
						} else {
							$this->shared->error_mandrill('Leader invited an user who was marked as a part of a unit, the unit','fx account_model->invite_contact():251',array('unit'=>$unit,'user'=>$user,'role'=>$role));
							return false;
						}
						$this->shared->send_notification(
							$currentunit['unittype'].' '.$currentunit['number']." invited you to be the $role contact for ".$currentunit['unittype'].' '.$currentunit['number'].". Since you are currently the  which means you can manage and register for events on Camper. You can sign on today and get started. ".$currentuser->first_name.' '.$currentuser->last_name.' made you the contact.', 
							"You've been made the $role contact for ".$currentunit['unittype'].' '.$currentunit['number'], 
							$currentuser->first_name.' '.$currentuser->last_name, 
							$newuser['first_name'], 
							$newuser['last_name'], 
							$newuser['email'], 
							$link=false,
							$notificationid=false
						);
						*/
						$this->shared->error_mandrill('Unfinished feature: invite user who is already part of a unit.','fx account_model->invite_contact():257',array('newuser'=>$newuser));
						$this->session->set_flashdata('message', $newuser['first_name'].' '.$newuser['last_name']." is already a contact for another unit. Right now, you can only be a member of one unit at a time. Try using another email address for them or choose another leader. Contact the council for more information."); return false;
						return false;
					}
					//show_error('wtf');
					$this->shared->error_mandrill('Invited new user is in the system. This error shouldn\'t ever show.','fx account_model->invite_contact():261',array('newuser'=>$newuser));
					// done!
				} else {
					$this->shared->error_mandrill('Invited new user is in the system. newuser is not set?','fx account_model->invite_contact():264',array('newuser'=>$newuser));
					//show_error('Invited user is in the system but email is null. See admin error report.');
				}
			} else {
				// Create invite from our current user
				$invite = array(
					'unit'		=> $currentunit['unittype'].' '.$currentunit['number'],
					'unitid'	=> $unit,
					'source'	=> $currentuser->first_name.' '.$currentuser->last_name,
					'email'		=> $user
				);
				$invitetoken = $this->shared->create_invite($invite);

				// Remove the unit from outgoing contact
				$this->db->where('id', $currentunit[$type]);
				$this->db->update('auth_users', array('company' => 0));
				
				// Update unit
				$this->db->where('id', $unit);
				$this->db->update('unit', array($type => 0));
				return true;
			}			
		}
		$this->shared->error_mandrill('A leader tried to invite an user but the process failed.','fx account_model->invite_contact():251',array('unit'=>$unit,'user'=>$user,'role'=>$role));
		return false;
		
		// we didnt do anything
	}

}

?>