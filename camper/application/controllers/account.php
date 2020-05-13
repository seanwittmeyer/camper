<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Accounts Controller
 *
 * This is the controller that handles account changes made by the active user.
 *
 * Version 1.4.5 (2014 04 23 1530)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Account extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('account_model');
		$this->load->model('users_model');
		$this->lang->load('auth');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	}

	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			redirect('signin', 'refresh');
		}
		
		// Get logged in user.
		$user = $this->ion_auth->user()->row();
		
		// Build the page and send some data in.
		$data['page'] = 'My Account';
		$data['section'] = 'account';
		$data['title'] = 'My Account';

		$data['first'] = $user->first_name;
		$data['last'] = $user->last_name;
		$data['phone'] = $user->phone;
		$data['email'] = $user->email;
		
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		$data['user_id'] = array(
			'name'  => 'user_id',
			'id'    => 'user_id',
			'type'  => 'hidden',
			'value' => $user->id
		);
		$unit = $this->shared->get_user_unit($user->id,true);
		if ($this->shared->is_individual($user)) {
			$data['unit'] = '<h3><i class="icon-ok teal"></i> You have an individual account and are not associated with any unit.</h3><p>Individual accounts are special accounts in Camper that let people sign up if they are not associated with a unit as a leader. Individuals can register for certain events just as most units can, if you wish to switch to a unit account or wish to join a unit, contact council or your unit\'s leaders for your unit\'s Camper account.</p>';
		} elseif ($unit['primary'] == $user->id) {
			$unittitle = (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')': $unit['unittype'].' '.$unit['number'];
			$data['unit'] = '<h3><i class="icon-ok teal"></i> You are the Primary Contact for '.$unittitle.' of '.$unit['council'].' Council</h3>';
		} elseif ($unit['alt'] == $user->id) {
			$unittitle = (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')': $unit['unittype'].' '.$unit['number'];
			$data['unit'] = '<h3><i class="icon-ok teal"></i> You are an Alternate Contact for '.$unittitle.' of '.$unit['council'].' Council</h3>';
		} else {
			$data['unit'] = '<h3><i class="icon-minus tan"></i> You are not associated with any unit.</h3>';
		}

		if ($this->ion_auth->is_admin()) //remove this elseif if you want to enable this for non-admins
		{
			$this->load->view('templates/header_admin', $data);
		}
		else 
		{
			$this->load->view('templates/header_leader', $data);
		}
		$this->load->view('leader/account/me', $data);	
		$this->load->view('templates/footer', $data);
		
		
	}
	
	public function edit()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } 
		
		// Get logged in user.
		$user = $this->ion_auth->user()->row();

		// Grab new details, prepare and update user.
		$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone Number', 'required|xss_clean');
		
		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($user->id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}

			$newdata = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'phone'      => $this->input->post('phone'),
			);

			if ($this->form_validation->run() === TRUE)
			{
				$this->ion_auth->update($user->id, $newdata);

				//check to see if we are creating the user
				//redirect them back to the admin page
				$this->session->set_flashdata('message', "User Saved");
				redirect("me", 'refresh');
			}
		}
		//set the flash data error message if there is one
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->session->set_flashdata('message', $data['message']);
		redirect("me", 'refresh');
	}
	
	// Notifications
	function n($token=FALSE)
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } 

		// Build the page and send some data in.
		$data['page'] = 'Notifications';
		$data['title'] = 'Notifications';
		$data['section'] = 'n';
		$data['notifications'] = $this->shared->notifications();
		$data['defaults'] = $this->config->item('camper_notifications');
		
		if ($this->ion_auth->is_admin()) //remove this elseif if you want to enable this for non-admins
		{
			$this->load->view('templates/header_admin', $data);
		}
		else 
		{
			$this->load->view('templates/header_leader', $data);
		}
		$this->load->view('notifications/all', $data);	
		$this->load->view('templates/footer', $data);
	}

	// This is the signup page, 3 parts creating the user, unit and the associated details
	function createaccounts($invite=NULL)
	{
		$is_invite = FALSE;
		// Already signed in, go to dashboard.
		if ($this->ion_auth->logged_in()) redirect('dashboard', 'refresh');		
		
		$data['title'] = 'Get Started';
		
		// Have we already started the process?
    	if ( (isset($_POST) && !empty($_POST)) || isset($invite) )
    	{
    		/* do we have a valid request?
    		if ($this->shared->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
    		{
    			show_error($this->lang->line('error_csrf'));
    		} */
    		
			$token = $this->input->post('token');
    		if (isset($invite) && empty($token)) {
	    		// Verify our invite if we have one
				$invitedata = $this->shared->get_transient($invite);
	    		if ($invitedata === false) {
					$this->session->set_flashdata('message', 'We couldn\'t find your invite. No worries, you can get started here.');
					redirect("start", 'refresh');
	    		}
	    		$invitedata = unserialize($invitedata['content']);
	    		$is_invite = TRUE;
    		}

			// Setup
	    	$this->form_validation->set_rules('token', 'Token', 'required|xss_clean');
	    	$this->form_validation->set_rules('s', 'Step', 'required|xss_clean');
			$step = $this->input->post('s');
			
			// Get our transient data and verify it didn't expire
			if ($token == 1) {
					// New signup, lets create a transient
					$token = $this->shared->update_transient(false);
					$transient_raw = true;
					$transient = 'new';
				} elseif ($is_invite === true) {
					// New signup from invite, we'll transfer the data over and set the invite flag
		    		// First, are they an user?
		    		if ($this->ion_auth->email_check($invitedata['email'])) {
		    			$this->shared->delete_transient($invite);
						$this->session->set_flashdata('message', 'You already have an account! You can sign in Camper below.');
						redirect('signin', 'refresh'); // send back to login
		    		}
					$token = $this->shared->update_transient(false);
					$transient_raw = true;
					$transient = 'invite';
		    		$record = array(
		    			'email' 		=> $invitedata['email'],
		    			'inviteunitid' 	=> $invitedata['unitid'],
		    			'inviteunit'  	=> $invitedata['unit'],
		    			'invitesource'	=> $invitedata['source'],
		    			'invite'		=> $invite,
		    			'step'  		=> 5
		    		);
		    		$data['invitedata'] = $invitedata;
		    		$data['token'] = $token;
		    		$data['step'] = 5;
		    		// Update the transient record and send the data to the view for the next step
		    		$this->shared->update_transient($token,$record,'start');
		    		// Delete the invite record since we are on a roll now (we'll do this when we finilize the new account)
		    		//$this->shared->delete_transient($invite);
		    		
				} else {
					// Transient exists so we'll get it
					$transient = $this->shared->get_transient($token);
					$transient_raw = $transient;
					$transient = unserialize($transient['content']);
					$transient['error'] = 'none';
				}
			if ($transient_raw == false) {
					show_error('Transient was empty, please re-start the create account process.');
				} elseif ($transient['error'] == 'expired') {
					show_error('Transient record was expired, please re-start the create account process.');
				} 

			// Let's handle each step individually now. Step 5 is the first invite step.
			switch ($step) {

				// We are getting email, password and checking if they are already in our system as an user
				case 1:
		    		// Validate our form
					$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
					$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
					if ($this->form_validation->run() == FALSE)
						{
			    		// Form validation failed, we will send them back to step 1
			    		$data['token'] = $token;
			    		$data['step'] = 1;
			    		break;
						}
		    		// We have a valid form
		    		$record = array(
		    			'email' 	=> $this->input->post('email'),
		    			'password'  => $this->input->post('password'),
		    			'step'  	=> 2
		    		);
		    		// Are they an user?
		    		if ($this->ion_auth->email_check($record['email'])) {
			    		// Our user is already registered, let's delete the transient record and try logging them in
			    		$this->shared->delete_transient($token);
						if ($this->ion_auth->login($record['email'], $record['password']))
						{
							$this->session->set_flashdata('message', $this->ion_auth->messages());
							redirect('dashboard', 'refresh'); // Logged in, send to dashboard
						}
						else
						{
							$this->session->set_flashdata('message', 'You already have an account but we were unable to signin. Please try signing in again.');
							$this->session->set_flashdata('identity', $this->input->post('email'));
							$this->session->set_flashdata('pw', $this->input->post('password'));
							redirect('signin', 'refresh'); // Login failed, send back to login
						}
		    		}

		    		// Update the transient record and send the data to the view for the next step
		    		$this->shared->update_transient($token,$record,'start');

		    		$data['token'] = $token;
		    		$data['step'] = 2;
		    		$data['email'] = $record['email'];
	    		break;

				// We are getting first, last, email, phone for the rest of the account.
				case 2:
		    		// Validate our form
					$this->form_validation->set_rules('first', 'first name', 'required');
					$this->form_validation->set_rules('last', 'last name', 'required');
					//$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
					$this->form_validation->set_rules('phone', 'phone', 'required');
					if ($this->form_validation->run() == FALSE)
						{
			    		// Form validation failed, we will send them back to step 1
			    		$data['token'] = $token;
			    		$data['step'] = 2;
						$data['email'] = $transient['email'];
			    		break;
						}
						
		    		// We have a valid form
					if ($transient == 'new') { show_error('You can\'t start step 3 with a blank transient record.'); } // Better handling of this later
		    		
		    		// Update the new email address if changed
		    		/*
		    		if ($this->input->post('email') !== $transient['email']) {
		    			$transient['email'] = $this->input->post('email');
						$this->session->set_flashdata('message', 'We updated the email you will sign in with to '.$transient['email'].'.');
		    		}*/
		    		$record = array(
		    			'email' 		=> $transient['email'],
		    			'password'		=> $transient['password'],
		    			'first_name'	=> htmlentities($this->input->post('first')),
		    			'last_name'		=> htmlentities($this->input->post('last')),
		    			'phone'			=> $this->input->post('phone'),
		    			'step'			=> 3
		    		);

		    		// Update the transient record and send the data to the view for the next step
		    		$this->shared->update_transient($token,$record,'start');
		    		$data['token'] = $token;
		    		$data['step'] = 3;
	    		break;
				
				// We are getting unittype, number, council, add, city, zip, and unitid for the unit creation.
				case 3:
		    		// Chose an existing unit?
		    		if ($this->input->post('custom') == 0 && $this->input->post('unitid') !== 0) {
		    			// Lets get our unit
			    		$requestunit = $this->shared->get_single_unit($this->input->post('unitid'));
						if (count($requestunit) === 0) {
							// There is an issue with this unit, we'll notify the admin and send them back to step 3.
				    		$data['token'] = $token;
				    		$data['step'] = 3;
							$data['email'] = $transient['email'];
							$message = "There was a problem with the unit you tried to join, please try creating your unit instead.";
				    		$transient['password'] = '-';
				    		$this->shared->error_mandrill('User registering requested to join a non-existing unit. The signup token was '.$token,'fx account createaccounts 303',$transient);
				    		break;
						}
						// Our user chose a valid unit, we'll populate our fields with the right information
			    		$record = array(
			    			'email' 		=> $transient['email'],
			    			'password'		=> $transient['password'],
			    			'first_name'	=> $transient['first_name'],
			    			'last_name'		=> $transient['last_name'],
			    			'phone'			=> $transient['phone'],
			    			'custom'		=> FALSE,
			    			'requestunit'	=> $requestunit['id'],
				    		'altemail'		=> NULL,
				    		'council'		=> $requestunit['council'],
				    		'district'		=> $requestunit['district'],
				    		'unittype'		=> $requestunit['unittype'],
				    		'number'		=> $requestunit['number'],
				    		'address'		=> $requestunit['address'],
				    		'city'			=> $requestunit['city'],
				    		'state'			=> $requestunit['state'],
				    		'zip'			=> $requestunit['zip'],
			    			'step'			=> 4
			    		);
			    		// Update and continue to step 4
			    		$this->shared->update_transient($token,$record,'start');
			    		$data['token'] = $token;
			    		$data['record'] = $record;
			    		$data['step'] = 4;
						break;
		    		}
		    		
		    		// Our user elected to create their own unit, we'll start by validating the data

		    		// Are we setting up an individual account?
					if ($this->input->post('individual') == '1') {
					
			    		// Validate our form
						$this->form_validation->set_rules('icouncil', 'unit\'s council', 'required');
						$this->form_validation->set_rules('unittype', 'unit\'s type', 'required');
						$this->form_validation->set_rules('iunittype', 'unit\'s type', 'required');
						$this->form_validation->set_rules('add', 'unit\'s address', 'required');
						$this->form_validation->set_rules('city', 'unit\'s city', 'required');
						$this->form_validation->set_rules('state', 'unit\'s state', 'required|alpha');
						$this->form_validation->set_rules('zip', 'unit\'s zip code', 'required|numeric|exact_length[5]');
						if ($this->form_validation->run() == FALSE)
							{
				    		// Form validation failed, we will send them back to step 3
				    		$data['token'] = $token;
				    		$data['step'] = 3;
							$data['email'] = $transient['email'];
				    		break;
							}
							
			    		// We have a valid form
						if ($transient == 'new') { show_error('You can\'t start step 4 with a blank transient record.'); } // Better handling of this later
			    		
			    		// Did they select an existing unit?
			    		if ($this->input->post('custom') !== "1") {
			    			$transient['unitid'] = $this->input->post('unitid');
			    			// is this user an invited user for the unit?
			    			// does the unit primary need to approve this user? if so, set pending details
					    	$this->shared->error_mandrill('User tried to sign up for an existing unit via the old routine. The signup token was '.$token,'fx account->createaccounts():362',$transient);
			    			show_error('Your unit has already been setup, unfortunately you can\'t be added to it at this time. You can\'t request to be added to an existing unit at this time, this is in development right now.');
							//$this->session->set_flashdata('message', 'We updated the email you will sign in with to '.$transient['email'].'.');
			    		} elseif ($this->input->post('custom') == "1") {
				    		$record = array(
				    			'email' 		=> $transient['email'],
				    			'password'		=> $transient['password'],
				    			'first_name'	=> $transient['first_name'],
				    			'last_name'		=> $transient['last_name'],
				    			'phone'			=> $transient['phone'],
				    			
				    			'custom'		=> TRUE,
				    			'altemail'		=> FALSE,
				    			'council'		=> ($this->input->post('icouncil')) ? $this->input->post('icouncil'): FALSE,
				    			'district'		=> ($this->input->post('district')) ? $this->input->post('district'): FALSE,
				    			'unittype'		=> $this->input->post('iunittype'),
				    			'number'		=> ($this->input->post('inumber')) ? $this->input->post('inumber'): FALSE,
				    			'address'		=> htmlentities($this->input->post('add')),
				    			'city'			=> $this->input->post('city'),
				    			'state'			=> $this->input->post('state'),
				    			'zip'			=> $this->input->post('zip'),
				    			'step'			=> 4,
				    			'individual'	=> 1
				    		);
			    		} else {
				    		show_error('Aw snap!');
			    		}
	
			    		// Update the transient record and send the data to the view for the next step
			    		$this->shared->update_transient($token,$record,'start');
			    		$data['token'] = $token;
			    		$data['record'] = $record;
			    		$data['step'] = 4;
						break;
					
					
					} // End Individual

		    		// Validate our form
					$this->form_validation->set_rules('council', 'unit\'s council', 'required');
					$this->form_validation->set_rules('district', 'unit\'s district', 'required');
					$this->form_validation->set_rules('unittype', 'unit\'s type', 'required');
					$this->form_validation->set_rules('number', 'unit\'s number', 'required|numeric');
					$this->form_validation->set_rules('add', 'unit\'s address', 'required');
					$this->form_validation->set_rules('city', 'unit\'s city', 'required');
					$this->form_validation->set_rules('state', 'unit\'s state', 'required|alpha');
					$this->form_validation->set_rules('zip', 'unit\'s zip code', 'required|numeric|exact_length[5]');
					$this->form_validation->set_rules('altemail', 'alternate contact\'s email', 'required|valid_email');
					if ($this->form_validation->run() == FALSE)
						{
			    		// Form validation failed, we will send them back to step 1
			    		$data['token'] = $token;
			    		$data['step'] = 3;
						$data['email'] = $transient['email'];
			    		break;
						}
						
		    		// We have a valid form
					if ($transient == 'new') { show_error('You can\'t start step 4 with a blank transient record.'); } // Better handling of this later
		    		
		    		// Did they select an existing unit?
		    		if ($this->input->post('custom') !== "1") {
		    			$transient['unitid'] = $this->input->post('unitid');
		    			// is this user an invited user for the unit?
		    			// does the unit primary need to approve this user? if so, set pending details
				    	$this->shared->error_mandrill('User tried to sign up for an existing unit via the old routine. The signup token was '.$token,'fx account->createaccounts():362',$transient);
		    			show_error('Your unit has already been setup, unfortunately you can\'t be added to it at this time. You can\'t request to be added to an existing unit at this time, this is in development right now.');
						//$this->session->set_flashdata('message', 'We updated the email you will sign in with to '.$transient['email'].'.');
		    		} elseif ($this->input->post('custom') == "1") {
			    		$record = array(
			    			'email' 			=> $transient['email'],
			    			'password'			=> $transient['password'],
			    			'first_name'		=> $transient['first_name'],
			    			'last_name'			=> $transient['last_name'],
			    			'phone'				=> $transient['phone'],
			    			
			    			'custom'			=> TRUE,
			    			'altemail'			=> $this->input->post('altemail'),
			    			'council'			=> $this->input->post('council'),
			    			'district'			=> $this->input->post('district'),
			    			'unittype'			=> $this->input->post('unittype'),
			    			'number'			=> $this->input->post('number'),
			    			'associatednumber'	=> $this->input->post('associatednumber'),
			    			'address'			=> htmlentities($this->input->post('add')),
			    			'city'				=> $this->input->post('city'),
			    			'state'				=> $this->input->post('state'),
			    			'zip'				=> $this->input->post('zip'),
			    			'step'				=> 4
			    		);
		    		} else {
			    		show_error('Aw snap!');
		    		}

		    		// Update the transient record and send the data to the view for the next step
		    		$this->shared->update_transient($token,$record,'start');
		    		$data['token'] = $token;
		    		$data['record'] = $record;
		    		$data['step'] = 4;
	    		break;
	    		
	    		// Our user is done and happy, let's create their unit and user account.
				case 4:
					if ($transient == 'new') { show_error('You can\'t start step 4 with a blank transient record.'); } // Better handling of this later
		    		
		    		// Create our new user
		    		$email = strtolower($transient['email']);
		    		$username = strtolower($transient['first_name']).' '.strtolower($transient['last_name']);
		    		$password = $transient['password'];
		    		$additional_data = array(
			    		'first_name'	=> $transient['first_name'],
			    		'last_name'		=> $transient['last_name'],
			    		'phone'			=> $transient['phone']
		    		);
		    		// Special details for individuals
		    		$individual = false;
		    		
		    		if (isset($transient['invite'])) { 
		    			$additional_data['company'] = $transient['inviteunitid']; 
		    		}
		    		$newuserid = $this->ion_auth->register($username, $password, $email, $additional_data);
		    		
		    		if (!isset($newuserid)) { 
		    			show_error('The user was not created, the user id was empty. Hmmm'); 
		    		
		    		}
		    		

		    		// If we weren't invited, we need to create our unit and invite the alternate
					if (!isset($transient['invite']) && !isset($transient['requestunit']) && !isset($transient['individual'])) { 
			    		// Create our new unit
			    		$unique = md5(microtime());
			    		$unitdetails = array(
			    			'council'			=> $transient['council'],
			    			'district'			=> $transient['district'],
			    			'unittype'			=> $transient['unittype'],
			    			'number'			=> $transient['number'],
			    			'address'			=> $transient['address'],
			    			'city'				=> $transient['city'],
			    			'state'				=> $transient['state'],
			    			'zip'				=> $transient['zip'],
							'associatednumber' 	=> ($transient['unittype'] == 'Den') ? $transient['associatednumber']: 0,
							'associatedunit' 	=> ($transient['unittype'] == 'Den') ? 'Pack': 0,
			    			'registerdate'		=> time(),
							'primary'			=> $newuserid,
							'alt'				=> $unique
			    		);
			    		$newunitid = $this->account_model->create_unit($unitdetails, $unique);
			    		
			    		// Add the new unit id as our user's unit
			    		$this->account_model->set_user_unit($newuserid, $newunitid);
			    		
						// Invite our alternate contact
						$invite = array(
							'unit'		=> $transient['unittype'].' '.$transient['number'],
							'unitid'	=> $newunitid,
							'source'	=> $transient['first_name'].' '.$transient['last_name'],
							'email'		=> $transient['altemail']
						);
						$invitetoken = $this->shared->create_invite($invite);

					} elseif (isset($transient['invite']) && !isset($transient['requestunit'])) {
						// Set our user as the alternate contact
						$this->account_model->set_unit_contact($transient['inviteunitid'], $newuserid, 'alternate');
						
						// Delete the invite transient, we don't need it anymore since the user has been created
						$this->shared->delete_transient($transient['invite']);
						
					} elseif (isset($transient['requestunit'])) {
						// Create notification for the unit primary
						$unitleader = $this->shared->get_unit_leader($transient['requestunit']);
						$definitions = array('f'=>$transient['first_name'],'l'=>$transient['last_name'],'u'=>$transient['unittype'].' '.$transient['number']);
						$details = array(
							'unit'		=> $transient['requestunit'],
							'user'		=> $newuserid
						);
						$unitleader = $this->shared->get_unit_leader($transient['requestunit']);
						$this->shared->notify('requestaccess',$definitions,$details,$unitleader);
					}
					
					// Post our individual data
					if (isset($transient['individual'])) { 
		    			$individual = true;
			    		$idata['individual'] = 1;
			    		$individualdata = array(
			    			'council'		=> $transient['council'],
			    			'district'		=> $transient['district'],
			    			'unittype'		=> $transient['unittype'],
			    			'number'		=> $transient['number'],
			    			'address'		=> $transient['address'],
			    			'city'			=> $transient['city'],
			    			'state'			=> $transient['state'],
			    			'zip'			=> $transient['zip'],
			    			'registerdate'	=> time(),
							'primary'		=> 0,
							'alt'			=> 0,
							'individual'	=> 1
			    		);
			    		$idata['individualdata'] = serialize($individualdata);
			    		
			    		$individual = $this->account_model->update_user($newuserid, $idata);
		    		}

					
		    		// Send Welcome Notification
					$this->shared->notify('welcome',$definitions = array('f'=>$transient['first_name']),null,$newuserid);
					
		    		// Delete the transient record, we don't need it anymore
		    		$this->shared->delete_transient($token);

					$data['step'] = 6;
	    		break;

				// Invite first step, we are getting first, last, email, pass, phone for the the account.
				case 5:
		    		// Validate our form
					$this->form_validation->set_rules('first', 'first name', 'required');
					$this->form_validation->set_rules('last', 'last name', 'required');
					$this->form_validation->set_rules('email', 'email address', 'required|valid_email');
					$this->form_validation->set_rules('phone', 'phone', 'required');
					$this->form_validation->set_rules('password', 'password', 'required|min_length[8]');
					if ($this->form_validation->run() == FALSE)
						{
			    		// Form validation failed, we will send them back to step 1
			    		$data['token'] = $token;
			    		$data['step'] = 5;
						$data['invitedata'] = array(
							'unitid' 	=> $transient['inviteunitid'],
							'unit' 		=> $transient['inviteunit'],
							'source' 	=> $transient['invitesource'],
							'email' 	=> $transient['email']
						);
						$is_invite = TRUE;
			    		break;
						}

		    		// We have a valid form
					if ($transient == 'new') { show_error('You need to restart the create account process because your invite code was invalid.'); } // Better handling of this later
		    		
		    		// Update the new email address if changed
		    		if ($this->input->post('email') !== $transient['email']) {
		    			$transient['email'] = $this->input->post('email');
						$this->session->set_flashdata('message', 'We updated the email you will sign in with to '.$transient['email'].'.');
		    		}
		    		
		    		// Lets get our unit details
		    		$inviteunit = $this->shared->get_single_unit($transient['inviteunitid']);
					if (count($inviteunit) === 0) {
						// Our unit doesn't exist so out invited user will need to create the unit. An error will be sent to the admin to look into this.
						$record = array(
							'email' 		=> $transient['email'],
			    			'password'  	=> $this->input->post('password'),
			    			'first_name'	=> htmlentities($this->input->post('first')),
			    			'last_name'		=> htmlentities($this->input->post('last')),
			    			'phone'			=> $this->input->post('phone'),
			    			'step'			=> 3
		    			);
			    		// Update the transient record and send the data to the view for the next step on the normal track
			    		$this->shared->update_transient($token,$record,'start');
			    		$data['token'] = $token;
			    		$data['step'] = 3;
			    		
			    		// Send an email to the debug admin with details. This is odd.
			    		$record['password'] = '-';
			    		$debug_array = array('transient' => $transient, 'new_record' => $record);
			    		$this->shared->error_mandrill('Invited user tried to signup for an unit that wasn\'t in the system. The invite token was '.$transient['invite'],'fx account->createaccounts():456',$debug_array);
			    		break;
					}

		    		$record = array(
		    			'email' 		=> $transient['email'],
		    			'password'  	=> $this->input->post('password'),
		    			'first_name'	=> htmlentities($this->input->post('first')),
		    			'last_name'		=> htmlentities($this->input->post('last')),
		    			'phone'			=> $this->input->post('phone'),
		    			'inviteunitid' 	=> $transient['inviteunitid'],
		    			'inviteunit'  	=> $transient['inviteunit'],
		    			'invite'		=> $transient['invite'],
			    		'custom'		=> FALSE,
			    		'altemail'		=> NULL,
			    		'council'		=> $inviteunit['council'],
			    		'district'		=> $inviteunit['district'],
			    		'unittype'		=> $inviteunit['unittype'],
			    		'number'		=> $inviteunit['number'],
			    		'address'		=> $inviteunit['address'],
			    		'city'			=> $inviteunit['city'],
			    		'state'			=> $inviteunit['state'],
			    		'zip'			=> $inviteunit['zip'],
		    			'step'			=> 4
		    		);

		    		// Update the transient record and send the data to the view for the next step
		    		$this->shared->update_transient($token,$record,'start');
		    		$data['token'] = $token;
		    		$data['record'] = $record;
		    		$data['step'] = 4;
	    		break;
				
	    		// default
	    		default:
		    		$data['step'] = 1;
	    			
	    		break;
				
			} // end switch
    	}

    	// Create a CSRF key/token pair
    	$data['csrf'] = $this->shared->_get_csrf_nonce();

    	//set the flash data error message if there is one
    	$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : (isset($message) ? $message : $this->session->flashdata('message'))));

		// Send the user to the view
    	$data['title'] = 'Create Account';
    	$data['page'] = 'Edit User';
    	$data['section'] = 'start';

    	$this->load->view('templates/header_public', $data);
    	if ($is_invite === TRUE) {
    		$this->load->view('leader/account/start_invite', $data);
    	} else { 
    		$this->load->view('leader/account/start_new', $data);
    	}
    	$this->load->view('templates/footer', $data);
	}	
}


?>