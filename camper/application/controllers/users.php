<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Admin Users Controller
 *
 * This is the controller that handles account changes made by the active user.
 *
 * Version 1.4.5 (2014 04 23 1530)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Users extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('users_model');
	}

	// This lists all of the users from each group
	public function listusers()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		//list the users
		/*
		$data['users'] = $this->ion_auth->users()->result();
		foreach ($data['users'] as $k => $user)
		{
			$data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
		}
		*/
		//list the users
		$data['admins'] = $this->ion_auth->users(1)->result();
		$data['leaders'] = $this->ion_auth->users(2)->result();
		$data['staffs'] = $this->ion_auth->users(3)->result();
		//$data['individuals'] = $this->shared->get_users(false,'individual');

		// Get logged in user
		$singleuser = $this->ion_auth->user()->row();
		
		// Build the page and send some data in.
		$data['page'] = 'Users';
		$data['section'] = 'users';
		$data['title'] = 'Camper Users';

		/*
		$data['first'] = $singleuser->first_name;
		$data['last'] = $singleuser->last_name;
		$data['phone'] = $singleuser->phone;
		$data['email'] = $singleuser->email;
		*/
		
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		$data['user_id'] = array(
			'name'  => 'user_id',
			'id'    => 'user_id',
			'type'  => 'hidden',
			'value' => $singleuser->id
		);

		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/users/users', $data);
		$this->load->view('templates/footer', $data);
	}

	// This lists all of the units in the system, sorted by type
	public function listunits()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// Build the page and send some data in.
		$data['page'] = 'Users';
		$data['section'] = 'users';
		$data['title'] = 'Camper Users';

		// Get logged in user.
		$singleuser = $this->ion_auth->user()->row();

		$data['first'] = $singleuser->first_name;
		$data['last'] = $singleuser->last_name;
		$data['phone'] = $singleuser->phone;
		$data['email'] = $singleuser->email;

		// Get the list of events
		$data['troops'] = $this->users_model->get_units("Troop");
		$data['crews'] = $this->users_model->get_units("Crew");
		$data['packs'] = $this->users_model->get_units("Pack");
		$data['dens'] = $this->users_model->get_units("Den");
		$data['individuals'] = $this->shared->get_users(false,'individual');

		// Get the leaders in the system
		$data['leaders'] = $this->users_model->get_unit_leaders();
		
		// Show our page
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/users/units', $data);	
		$this->load->view('templates/footer', $data);
	}

	// This lists all of the units in the system, sorted by type
	public function changecontact()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');

		// Swap contacts?
		if ($this->input->post('submit') == 'Swap Contacts')
		{
			// Security checkpoint via CSRF
			//if ($this->shared->_valid_csrf_nonce() === FALSE) { show_error('Your browser didn\'t pass our security check. fx changecontact() 1'); }
			
			// Setup
			$unit = $this->shared->get_single_unit($this->input->post('unitid'));

			// Let's swap our contacts
			$this->users_model->swap_contacts($unit); 
			
			// Return our user to the unit page
			$this->session->set_flashdata('message', '<i class="icon-ok teal"></i> Contacts swapped successfully.');
			redirect('units/edit/'.$this->input->post('unitid'), 'refresh');
		}
		// No primary/alternate contacts?
		elseif ($this->input->post('submit') == 'Set New Contact' && $this->input->post('what') == 'new')
		{
			// Security checkpoint via CSRF
			//if ($this->shared->_valid_csrf_nonce() === FALSE) { show_error('Your browser didn\'t pass our security check. fx changecontact() 2'); }

			// Setup
			$unit = $this->shared->get_single_unit($this->input->post('unitid'));
			$scope = 'new';
			$email = $this->input->post('newcontact');
			
			// Is our new contact already a user?
			if (!$this->ion_auth->email_check($email)) {
				$currentuser = $this->ion_auth->user()->row();

				$invite = array(
					'unit'		=> $unit['unittype'].' '.$unit['number'],
					'unitid'	=> $unit['id'],
					'source'	=> $currentuser->first_name.' '.$currentuser->last_name,
					'email'		=> $email,
					'adminsource' => TRUE
				);
				$invitetoken = $this->shared->create_invite($invite);
				if ($invitetoken) $this->session->set_flashdata('message', '<i class="icon-ok teal"></i> Since this is a new user to the system, we\'ve sent them an invite.');
				redirect('units/edit/'.$this->input->post('unitid'), 'refresh');

			} else {
				// Start by getting our new user's details and thier unit if they are in one.
				$newcontact = $this->users_model->get_user_by_email($email);
				$unit = $this->shared->get_single_unit($this->input->post('unitid'));

				// If they have an old unit, we'll remove them and notify the people in the old unit.
				$oldunit = false;
				if (!$newcontact['company'] == '0') {
					$oldunit = $this->shared->get_single_unit($newcontact['company']);
					//print_r($oldunit);
					// Notify Old Unit Here
				}
				
				// Let's change the unit and contact
				$result = $this->users_model->change_contacts($newcontact, $unit, $oldunit, 'new'); 
				if ($result) $this->session->set_flashdata('message', '<i class="icon-ok teal"></i> Added the contact without problem.');

				// Display the unit page
				redirect('units/edit/'.$this->input->post('unitid'), 'refresh');
			}
		}
		
		// We aren't swapping or solely adding a new user so let's get on with changing out old for new
		// Figure out who we are working with
		if ($this->input->post('what') == 'pri') { $email = $this->input->post('newprimary'); $scope = 'primary'; }
		if ($this->input->post('what') == 'alt') { $email = $this->input->post('newalternate'); $scope = 'alt'; }
		if (empty($scope) || empty($email)) { 
			$this->session->set_flashdata('message', 'The new contact email field was blank, no change was made.');
			redirect('units/edit/'.$this->input->post('unitid'), 'refresh');
		} 
		
		// Figure out if we have a user or not			
		if (!$this->ion_auth->email_check($email)) {
			$unit = $this->shared->get_single_unit($this->input->post('unitid'));
			$message = 'This user is not in our database, they will be notified.';
			//show_error('You submitted an email address that is not registered in our system. Normally this user would be invited to register and join the unit in question but this is not yet ready.');
			$currentuser = $this->ion_auth->user()->row();
			$invite = array(
				'unit'		=> $unit['unittype'].' '.$unit['number'],
				'unitid'	=> $unit['id'],
				'source'	=> $currentuser->first_name.' '.$currentuser->last_name,
				'email'		=> $email
			);
			$invitetoken = $this->shared->create_invite($invite);
			$this->session->set_flashdata('message', '<i class="icon-ok teal"></i> '.$email.' has been invited.');

			// Display the unit page
			redirect('units/edit/'.$this->input->post('unitid'), 'refresh');
		} else {
			// Start by getting our new user's details and thier unit if they are in one.
			$newcontact = $this->users_model->get_user_by_email($email);
			$unit = $this->shared->get_single_unit($this->input->post('unitid'));

			// If they have an old unit, we'll remove them and notify the people in the old unit.
			$oldunit = false;
			if (!$newcontact['company'] == '0') {
				$oldunit = $this->shared->get_single_unit($newcontact['company']);
				//print_r($oldunit);
				// Notify Old Unit Here
			}
			
			// Let's change the unit and contact
			$result = $this->users_model->change_contacts($newcontact, $unit, $oldunit, $scope); 
			if ($result) $this->session->set_flashdata('message', '<i class="icon-ok teal"></i> Changed contact without problem.');

			// Display the unit page
			redirect('units/edit/'.$this->input->post('unitid'), 'refresh');
		}
	}

	
	// This converts an user into an individual
	function individual($id)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');

		$user = $this->ion_auth->user($id)->row();
		$result = false;
		$result = $this->users_model->set_individual($user->id,$user);

		if ($result) {
			$this->session->set_flashdata('message', '<i class="icon-ok teal"></i> '.$user->first_name.' '.$user->last_name.' now has an individual account');
		} else {
			$this->session->set_flashdata('message', '<i class="icon-remove red"></i> Aw snap, it didn\'t work. No change has been made.');
		}
		redirect("users/$id", 'refresh');
	}

	// This activates a user and forwards them to the users page
	function activate($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("users", 'refresh');
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	// This will deactivate the user, confirming the action first
	function deactivate($id = NULL)
	{
		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$data['csrf'] = $this->shared->_get_csrf_nonce();
			$data['user'] = $this->ion_auth->user($id)->row();
			// Build the page and send some data in.
			$data['page'] = 'Deactivate User';
			$data['section'] = 'users';

			$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$data['title'] = 'Deactivate User';
			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/users/deactivate', $this->data);
			$this->load->view('templates/footer', $data);

		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if (/*$this->shared->_valid_csrf_nonce() === FALSE ||*/ $id != $this->input->post('id'))
				{
					show_error('Your browser didn\'t pass our security check. fx deactivate()');
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			//redirect them back to the auth page
			redirect('users', 'refresh');
		}
	}
	
	// Create an unit and invite people to manage it.
	function newunit()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');

		// Load libraries
		$this->load->model('account_model');
		$this->load->model('register_model');

		// Validate form input
		$this->form_validation->set_rules('council', 'unit\'s council', 'required');
		$this->form_validation->set_rules('district', 'unit\'s district', 'required');
		$this->form_validation->set_rules('unittype', 'unit\'s type', 'required');
		$this->form_validation->set_rules('number', 'unit\'s number', 'required|numeric');
		$this->form_validation->set_rules('add', 'unit\'s address', 'required');
		$this->form_validation->set_rules('city', 'unit\'s city', 'required');
		$this->form_validation->set_rules('state', 'unit\'s state', 'required|alpha');
		$this->form_validation->set_rules('zip', 'unit\'s zip code', 'required|numeric|exact_length[5]');
		
		$this->form_validation->set_rules('emaila', 'contact\'s email', 'valid_email');
		$this->form_validation->set_rules('emailb', 'contact\'s email', 'valid_email');

		$this->form_validation->set_rules('youth', 'youth', 'numeric');
		$this->form_validation->set_rules('male', 'male adults', 'numeric');
		$this->form_validation->set_rules('female', 'female adults', 'numeric');
		$this->form_validation->set_rules('sid', 'session id', 'numeric');

		if (isset($_POST) && !empty($_POST))
		{
		   	// Prep our unit
			$user = $this->ion_auth->user()->row();
		   	$unique = md5(microtime());
		   	$registerdate = ($this->input->post('regdate') == date('F d, Y') || $this->input->post('regdate') === FALSE) ? time(): strtotime($this->input->post('regdate'));
		   	$unitdetails = array(
		   		'council'		=> $this->input->post('council'),
		   		'district'		=> $this->input->post('district'),
		   		'unittype'		=> $this->input->post('unittype'),
		   		'number'		=> $this->input->post('number'),
		   		'address'		=> $this->input->post('add'),
		   		'city'			=> $this->input->post('city'),
		   		'state'			=> $this->input->post('state'),
		   		'zip'			=> $this->input->post('zip'),
		   		'primary'		=> 0,
		   		'registerdate'	=> time(),
				'alt'			=> $unique
			);
			$newunitid = $this->account_model->create_unit($unitdetails, $unique);

			// Invite our alternate contact
			$count = 0;
			foreach (array('emaila','emailb') as $e) {
				if ($this->input->post($e)) {
					$invite = array(
						'unit'		=> $this->input->post('unittype').' '.$this->input->post('number'),
						'unitid'	=> $newunitid,
						'source'	=> $user->first_name.' '.$user->last_name,
						'email'		=> $this->input->post($e)
					);
					$invitetoken = $this->shared->create_invite($invite);
					$count++;
				}
			}
			
			// Register our new unit for the session, if set
			if ($this->input->post('sid')) {
				$event = $this->shared->get_session_event($this->input->post('sid'));
				$id = $this->register_model->register($newunitid, $event, $this->input->post('sid'), $user->id,false,true,$registerdate);
				$session = true;
			}

			// Write our message
			if ($count>0) {
				$intro = ($count==1) ? '<i class="icon-ok teal"></i> Your contact has been invited and ' : '<i class="icon-ok teal"></i> Your contacts have been invited and ';
				$message = $intro.$this->input->post('unittype').' '.$this->input->post('number').' has been created.';
			} else {
				$message = '<i class="icon-ok teal"></i> '.$this->input->post('unittype').' '.$this->input->post('number').' has been created.';
			}
			if  (isset($message) && isset($session)) {
				$message = $message.' The '.$this->input->post('unittype').' has been registered for your requested event and session as well.';
			}
		}
		
		//set the flash data error message if there is one
		$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : (isset($message)) ? $message : $this->session->flashdata('message')));

		$data['title'] = 'Invite Unit';
		$data['page'] = 'Invite Unit';
		$data['section'] = 'users';
		
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/users/newunit', $data);
		$this->load->view('templates/footer', $data);
	}				
				
	// Create an unit and invite people to manage it.
	function newuser()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');

		// Load libraries
		$this->load->model('account_model');
		$this->load->model('register_model');

		// Validate form input
		$this->form_validation->set_rules('number', 'unit\'s number', 'numeric');
		$this->form_validation->set_rules('add', 'unit\'s address', 'required');
		$this->form_validation->set_rules('city', 'unit\'s city', 'required');
		$this->form_validation->set_rules('state', 'unit\'s state', 'required|alpha');
		$this->form_validation->set_rules('zip', 'unit\'s zip code', 'required|numeric|exact_length[5]');
		
		$this->form_validation->set_rules('email', 'email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');

		$this->form_validation->set_rules('sid', 'session id', 'numeric');

   		if ($this->ion_auth->email_check($this->input->post('email'))) {
   			$message = 'This email address is already in use';
   			$emailcheck = false;
   		} else {
	   		$emailcheck = true;
   		}

		if ($emailcheck && $this->form_validation->run() === TRUE)
		{
		   	// Prep our unit
			$user = $this->ion_auth->user()->row();
		   	$unique = md5(microtime());
		   	$registerdate = ($this->input->post('regdate') == date('F d, Y') || $this->input->post('regdate') === FALSE) ? time(): strtotime($this->input->post('regdate'));
		   	$unitdetails = array(
		   		'council'		=> $this->input->post('council'),
		   		'district'		=> $this->input->post('district'),
		   		'unittype'		=> $this->input->post('unittype'),
		   		'number'		=> $this->input->post('number'),
		   		'address'		=> $this->input->post('add'),
		   		'city'			=> $this->input->post('city'),
		   		'state'			=> $this->input->post('state'),
		   		'zip'			=> $this->input->post('zip'),
			);
			$details = array(
		   		'first'			=> $this->input->post('first'),
		   		'last'			=> $this->input->post('last'),
		   		'phone'			=> $this->input->post('phone'),
		   		'individualdata' => serialize($unitdetails)
			);
			$newuserid = $this->account_model->create_user($this->input->post('email'),$this->input->post('password'),$details);
			$message = $this->input->post('first').'\'s account is ready for use.'.anchor('users/'.$newuserid, $this->input->post('first').'\'s Account &rarr;', 'class="btn btn-small tan"');

			// Register our new unit for the session, if set
			if ($this->input->post('sid')) {
				$individual = true;
				$individualid = $newuserid;
				$unitid = 0;
				$group = false;
				$id = $this->register_model->register($unitid, $this->shared->get_session_event($this->input->post('sid')), $this->input->post('sid'), $user->id, false, true, $registerdate, $group, $individual, $individualid);
				$session = true;
			}

			// Write our message
			if  (isset($message) && isset($session)) {
				$message = $message.'<br>'.$this->input->post('first').' has also been registered for your requested event/session as well. <br>'.anchor('event/0/registrations/'.$id, 'Edit the new registration &rarr;', 'class="btn btn-small tan"');
			}
		}

		//set the flash data error message if there is one
		$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		if (isset($message)) $data['message'] = ($data['message'] == '') ? $message: $data['message'].'<br>'.$message;

		$data['title'] = 'Add User';
		$data['page'] = 'Add User';
		$data['section'] = 'users';

		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/users/newuser', $data);
		$this->load->view('templates/footer', $data);
	}				
				
				
	// This allows administrators to edit users
	function edituser($id)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');

		$data['title'] = "Edit User";


		$user = $this->ion_auth->user($id)->row();
		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		//validate form input
		$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone Number', 'required|xss_clean');
		$this->form_validation->set_rules('groups', 'User Type', 'xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if (/*$this->shared->_valid_csrf_nonce() === FALSE ||*/ $id != $this->input->post('id'))
			{
				show_error('Your browser didn\'t pass our security check. fx edituser()');
			}

			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'phone'      => $this->input->post('phone')
			);
			
			// Update Individual Details, if any
			if ($this->input->post('unit')) {
				$data['individualdata'] = serialize($this->input->post('unit'));
			}


			//Update the groups user belongs to
			$groupData = $this->input->post('groups');

			if (isset($groupData) && !empty($groupData)) {

				$this->ion_auth->remove_from_group('', $id);

				foreach ($groupData as $grp) {
					$this->ion_auth->add_to_group($grp, $id);
				}

			}

			//update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

				$data['password'] = $this->input->post('password');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$this->ion_auth->update($user->id, $data);

				//check to see if we are creating the user
				//redirect them back to the admin page
				$user = $this->ion_auth->user($id)->row();
				$groups=$this->ion_auth->groups()->result_array();
				$currentGroups = $this->ion_auth->get_users_groups($id)->result();
				$message = '<i class="icon-ok teal"></i> '.$user->first_name.' '.$user->last_name.' Updated';
			}
		}

		//display the edit user form
		$data['csrf'] = $this->shared->_get_csrf_nonce();

		//set the flash data error message if there is one
		$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		if (isset($message)) $data['message'] = $message;

		//pass the user to the view
		$data['csrffail'] = false;
		$data['user'] = $user;
		$data['groups'] = $groups;
		$data['currentGroups'] = $currentGroups;

		$data['first_name'] = $this->form_validation->set_value('first_name', $user->first_name);
		$data['last_name'] = $this->form_validation->set_value('last_name', $user->last_name);
		$data['phone'] = $this->form_validation->set_value('phone', $user->phone);

		$data['title'] = 'Edit User';
		$data['page'] = 'Edit User';
		$data['section'] = 'users';
		
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/users/edituser', $data);
		$this->load->view('templates/footer', $data);
	}
	
	// This allows administrators to edit units
	function editunit($id)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');

		$data['title'] = "Edit Unit";

		// Get the unit
		$data['unit'] = $this->shared->get_single_unit($id);

		// Get the unit's contacts
		//$data['userprimary'] = $this->ion_auth->user($data['unit']['primary'])->row();
		//$data['useralternate'] = $this->ion_auth->user($data['unit']['alt'])->row();
		if ($data['unit']['alt'] !== '0') { 
			$data['useralternate'] = $this->ion_auth->user($data['unit']['alt'])->row(); 
		} else { 
			$data['useralternate'] = false; 
		}
		if ($data['unit']['primary'] !== '0') { 
			$data['userprimary'] = $this->ion_auth->user($data['unit']['primary'])->row(); 
		} else { 
			$data['userprimary'] = false; 
			// Do we have an alternate but no primary? Swap if so.
			if ($data['useralternate']) {
				// Let's swap our contact's role and notify
				$result = $this->users_model->alt_to_pri($data['unit']); 
				if ($result) { 
					$this->session->set_flashdata('message', 'We found and fixed a problem with this unit for you. Please set an alternate contact!'); 
					redirect("units/edit/".$id, 'refresh');
				} else { 
					show_error('The contacts were not swapped as planned.');
				}
			}
		}

		//validate form input
		$this->form_validation->set_rules('unitid', 'id', 'required');
		$this->form_validation->set_rules('unittype', 'unit type', 'required');
		$this->form_validation->set_rules('council', 'council', 'required');
		$this->form_validation->set_rules('number', 'unit number', 'required');
		$this->form_validation->set_rules('address', 'address', 'required');
		$this->form_validation->set_rules('city', 'city', 'required');
		$this->form_validation->set_rules('state', 'state', 'required');
		$this->form_validation->set_rules('zip', 'zip', 'required');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ( /*$this->shared->valid_csrf_nonce() === FALSE ||*/ $id !== $this->input->post('unitid'))
			{
				show_error('Your browser didn\'t pass our security check. fx editunit()');
			}

			if ($this->form_validation->run() === TRUE)
			{
				// Update the unit and get the new updated record
				$message = $this->users_model->update_unit($this->input->post('unitid'));
				$data['unit'] = $this->shared->get_single_unit($id);

				// redirect them back to the admin page
				$this->session->set_flashdata('message', $message);
				//redirect("units/edit/".$id, 'refresh');
			}
		}

		//display the edit user form
		$data['csrf'] = $this->shared->get_csrf_nonce();

		//set the flash data error message if there is one
		$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		/* Form validation
		$data['first_name'] = $this->form_validation->set_value('first_name', $user->first_name);
		$data['last_name'] = $this->form_validation->set_value('last_name', $user->last_name);
		$data['company'] = $this->form_validation->set_value('company', $user->company);
		$data['phone'] = $this->form_validation->set_value('phone', $user->phone); */

		$data['title'] = 'Edit Unit';
		$data['page'] = 'Edit Unit';
		$data['section'] = 'users';
		
		$data['invites'] = $this->shared->get_invites($id);
		
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/users/editunit', $data);
		$this->load->view('templates/footer', $data);
	}
	
	
	// This allows administrators to edit units
	function payments($id)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');

		$this->load->model('finance_model');
		
		$data['title'] = "Unit Payments";

		// Get the unit
		$data['unit'] = $this->shared->get_single_unit($id);

		//display the edit user form
		$data['csrf'] = $this->shared->get_csrf_nonce();

		//set the flash data error message if there is one
		$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		$data['page'] = 'Edit Unit';
		$data['section'] = 'users';
		
		// Get the list of events for your unit
		$data['payments'] = $this->finance_model->get_all_payments($id);
		
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/users/payments', $data);
		$this->load->view('templates/footer', $data);
	}
	
	// This allows administrators to edit units
	function registrations($id)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');

		$this->load->model('register_model');
		
		$data['title'] = "Unit Payments";

		// Get the unit
		$data['unit'] = $this->shared->get_single_unit($id);

		//display the edit user form
		$data['csrf'] = $this->shared->get_csrf_nonce();

		//set the flash data error message if there is one
		$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// Grab our event regs for this unit
		$data['regs'] = $this->register_model->get_unit_regs($id);

		$data['page'] = 'Edit Unit';
		$data['section'] = 'users';
		
		
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/users/registrations', $data);
		$this->load->view('templates/footer', $data);
    }
	
	/*
	*
	*
	*	Members
	*
	*
	*/
	
	// Create a new member for your unit
	public function new_member($unit)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');

		// Starters
		$this->load->model('activities_model');
		$data['page'] = 'New Member &lsaquo; Units';
		$data['section'] = 'users';
		$data['success'] = false;
		$data['unit'] = $this->shared->get_units($unit);
		if (!$data['unit']) show_error('No such unit');
		
		// Get logged in user.
		//$data['user'] = $this->ion_auth->user()->row();
		//$data['unit'] = $this->shared->get_user_unit($data['user']->id, true);	
		
		// Verify member details.
		$this->form_validation->set_rules('member[name]', 'name', 'required');
		$this->form_validation->set_rules('member[dob]', 'date of birth', 'required');
		$this->form_validation->set_rules('member[gender]', 'gender', 'required');
		$this->form_validation->set_rules('member[shirtsize]', 'shirtsize');
		$this->form_validation->set_rules('member[diet]', 'diet');
		$this->form_validation->set_rules('member[allergies]', 'allergies');
		$this->form_validation->set_rules('member[medical]', 'medical');
		$this->form_validation->set_rules('member[notes]', 'notes');
		$this->form_validation->set_rules('member[address]', 'address');
		$this->form_validation->set_rules('member[citystate]', 'city and zip code');
		$this->form_validation->set_rules('member[phone]', 'phone');
		$this->form_validation->set_rules('member[insurance]', 'insurance');
		// add all here			
		
		if ($this->form_validation->run() === TRUE) {
			// Lets update the user
			$result = $this->activities_model->create_member(false,false,$unit);
			$data['success'] = true;
			if ($this->input->get('return')) {
				$this->session->set_flashdata('message', '<i class="icon-ok teal"></i> Success, '.$this->input->get('member[name]').' was added to the unit, you can add them to the roster below.');
				redirect($this->input->get('return'), 'refresh');
			}
		}

		// Form failed or wasn't run
		$data['message'] = (validation_errors()) ? validation_errors() : null;
		$data['message'] = (is_null($data['message'])) ? $this->session->flashdata('message'): $data['message'];
		if ($data['success']) $data['message'] = '<i class="icon-ok teal"></i> Success, your new member was added. '.anchor('units/'.$unit.'/members/'.$result, 'View &rarr;', 'class="btn btn-small teal right"').' '.anchor('units/'.$unit.'/members/', 'All Members &rarr;', 'class="btn btn-small tan right"');

		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/users/newmember', $data);	
		$this->load->view('templates/footer', $data);

	}

	// Listing of our user's unit members or a single member
	public function members($unit,$member=FALSE)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');

		// Starters
		$this->load->model('activities_model');
		$data['page'] = 'Members &lsaquo; Units';
		$data['section'] = 'users';
		$data['unit'] = $this->shared->get_units($unit);
		if (!$data['unit']) show_error('No such unit');
		
		// Get logged in user.
		//$data['user'] = $this->ion_auth->user()->row();
		//$data['unit'] = $this->shared->get_user_unit($data['user']->id, true);	
		
		if ($member === FALSE) {
			// No member set, lets get all members
			$data['members'] = $this->activities_model->get_members($unit);
			
			// Build the page and send some data in.
			$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/users/members', $data);	
			$this->load->view('templates/footer', $data);
			
		} else {
			// Member is set
			$data['member'] = $this->activities_model->get_member($member);
			if (!$data['member']) { 
				$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> We couldn\'t find that user, please try again.');
				redirect('units/'.$unit.'/members', 'refresh');
			}

			if ($data['unit']['id'] !== $data['member']['unit']) { 
				$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> Please try that again');
				redirect('units/'.$data['member']['unit'].'/members/'.$data['member']['id'], 'refresh');
			}
			
			// Verify member details.
			$this->form_validation->set_rules('member[name]', 'name', 'required');
			$this->form_validation->set_rules('member[dob]', 'date of birth', 'required');
			$this->form_validation->set_rules('member[gender]', 'gender', 'required');
			$this->form_validation->set_rules('member[shirtsize]', 'shirtsize');
			$this->form_validation->set_rules('member[diet]', 'diet');
			$this->form_validation->set_rules('member[allergies]', 'allergies');
			$this->form_validation->set_rules('member[medical]', 'medical');
			$this->form_validation->set_rules('member[notes]', 'notes');
			$this->form_validation->set_rules('member[address]', 'address');
			$this->form_validation->set_rules('member[citystate]', 'city and zip code');
			$this->form_validation->set_rules('member[phone]', 'phone');
			$this->form_validation->set_rules('member[insurance]', 'insurance');
			
			if ($this->form_validation->run() === TRUE) {
				// Lets update the user
				$result = $this->activities_model->update_member($member);
				$data['member'] = $this->activities_model->get_member($member);
				if ($result) {
					$data['errors'] = '<i class="icon-ok teal"></i> '.$data['member']['name'].' was updated';
				} else {
					$data['errors'] = '<i class="icon-info red"></i> '.$data['member']['name'].' was not updated';
				}
				if ($this->input->get('return')) {
					$this->session->set_flashdata('message', $data['errors']);
					redirect($this->input->get('return'), 'refresh');
				}
			} 

			// Build the page and send some data in.
			$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$data['message'] = (isset($data['errors'])) ? $data['errors']: $data['message'];
			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/users/singlemember', $data);	
			$this->load->view('templates/footer', $data);
			
		}
	}
	
	// Delete a member
	public function delete_member($unit,$id)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// Get logged in user.
		$this->load->model('activities_model');
		$member = $this->activities_model->get_member($id);

		// Make sure this user can modify this unit
		$result = $this->activities_model->delete_member($id);
		if ($result) {
			$this->session->set_flashdata('message', '<i class="icon-ok teal"></i> '.$member['name'].' has been deleted');
			redirect('units/'.$unit.'/members', 'refresh');
		}
	}
	
	// Pending changes page
	public function pending_invites()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		// Build the page and send some data in.
		$data['page'] = 'Users';
		$data['section'] = 'users';
		$data['title'] = 'Pending Changes';
		$data['invites'] = $this->shared->get_invites();
		$data['units'] = $this->shared->get_units(false,true);
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/users/pending', $data);
		$this->load->view('templates/footer', $data);
	}

}


?>