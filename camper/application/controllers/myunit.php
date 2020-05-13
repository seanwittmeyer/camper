<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Accounts Controller
 *
 * This is the controller that handles account changes made by the active user.
 *
 * Version 1.5.0 (2015 01 04 1006)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Myunit extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('account_model');
		$this->load->model('activities_model');
		$this->load->model('users_model');
	}

	public function index()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('members');
		
		// Get logged in user.
		$user = $this->ion_auth->user()->row();
		
		// Update unit if posted
		$this->form_validation->set_rules('address', 'address', 'required');
		$this->form_validation->set_rules('city', 'city', 'required');
		$this->form_validation->set_rules('zip', 'zip code', 'required');
		
		$data['message'] = FALSE;
		
		if ($this->form_validation->run() === TRUE)
		{
			// Update the unit and get the new updated record
			$data['unit'] = $this->shared->get_user_unit($user->id,true);	
			$data['message'] = $this->users_model->update_unit($data['unit']['id'], true);

			// Post update message 
			//$data['unit'] = $this->shared->get_user_unit($user->id);	
		}
		$data['unit'] = $this->shared->get_user_unit($user->id, true);	

		// Get unit details
		if (isset($data['unit']['primary']) && $data['unit']['primary'] !== 0) { $data['primary'] = $this->account_model->get_user_by_id($data['unit']['primary'], true); } else { $data['primary'] = false; }
		if (isset($data['unit']['alt']) && $data['unit']['alt'] !== 0) { $data['alternate'] = $this->account_model->get_user_by_id($data['unit']['alt'], true); } else { $data['alternate'] = false; }
		
		if ($data['alternate'] && !$data['primary']) {
			// We have an alternate but no primary, lets swap this
			$result = $this->users_model->alt_to_pri($data['unit']); 
			if ($result) { 
				$this->session->set_flashdata('message', 'We\'ve made you the primary contact! You need to invite someone to be an alternate contact in order to register for events.'); 
				redirect("unit", 'refresh');
			} else { 
				show_error('Your unit has been misconfigured. Reload the page to fix this! Thanks.');
			}
		}
		$data['userunit'] = false;

		if ($data['unit']['primary'] == $user->id) {
			$data['userunit'] = 1;
		} elseif ($data['unit']['alt'] == $user->id) {
			$data['userunit'] = 2;
		} 
		
		$data['invites'] = $this->shared->get_invites($data['unit']['id']);

		
		// Build the page and send some data in.
		$data['page'] = 'My Unit';
		$data['section'] = 'unit';
		$data['breadcrumbs'] = anchor('unit','Crew 1');


		/*		
		$data['first'] = $user->first_name;
		$data['last'] = $user->last_name;
		$data['phone'] = $user->phone;
		$data['email'] = $user->email;
		$data['user_id'] = array(
			'name'  => 'user_id',
			'id'    => 'user_id',
			'type'  => 'hidden',
			'value' => $user->id
		);
		*/
		
		// Create a CSRF key/token pair
    	$data['csrf'] = $this->shared->get_csrf_nonce();

		$data['message'] = (validation_errors()) ? validation_errors() : null;
		$data['message'] = (!is_null($data['message'])) ? $data['message'] : $this->session->flashdata('message');

		
		$this->load->view('templates/catalunya_head', $data);
		$this->load->view('leader/myunit/details', $data);	
		$this->load->view('templates/catalunya_foot', $data);
		/*
		$this->load->view('templates/header_leader', $data);
		$this->load->view('leader/myunit/details_old', $data);	
		$this->load->view('templates/footer', $data); */
	}
	
	// Create a new member for your unit
	public function new_member()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('members');

		// Starters
		$data['page'] = 'New Member &lsaquo; My Unit';
		$data['section'] = 'myunit';
		$data['success'] = false;
		
		// Get logged in user.
		$data['user'] = $this->ion_auth->user()->row();
		$data['unit'] = $this->shared->get_user_unit($data['user']->id, true);	
		
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
			$result = $this->activities_model->create_member();
			$member = $this->input->post('member');
			$data['success'] = true;
		}

		// Form failed or wasn't run
		$data['message'] = (validation_errors()) ? validation_errors() : null;
		$data['message'] = (is_null($data['message'])) ? $this->session->flashdata('message'): $data['message'];
		if ($data['success']) $data['message'] = '<div class="right">'.anchor("unit/members/".$result, 'View '.$member['name'].' &rarr;', 'class="btn btn-small tan "').' '.anchor("unit/members", 'All Members &rarr;', 'class="btn btn-small tan "').'</div> <i class="icon-ok teal"></i> Success, '.$member['name'].' was added. ';

		$this->load->view('templates/header_leader', $data);
		$this->load->view('leader/myunit/newmember', $data);	
		$this->load->view('templates/footer', $data);

	}

	// Listing of our user's unit members or a single member
	public function members($member=FALSE)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('members');

		// Starters
		$data['page'] = 'Members &lsaquo; My Unit';
		$data['section'] = 'myunit';
		
		// Get logged in user.
		$data['user'] = $this->ion_auth->user()->row();
		$data['unit'] = $this->shared->get_user_unit($data['user']->id, true);	
		
		if ($member === FALSE) {
			// No member set, lets get all members
			$data['members'] = $this->activities_model->get_members($data['unit']['id']);
			
			// Build the page and send some data in.
			$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->load->view('templates/header_leader', $data);
			$this->load->view('leader/myunit/members', $data);	
			$this->load->view('templates/footer', $data);
			
		} else {
			// Member is set
			$data['member'] = $this->activities_model->get_member($member);
			if (!$data['member']) { 
				$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> We couldn\'t find that user, please try again.');
				redirect('/unit/members', 'refresh');
			}

			if ($data['unit']['id'] !== $data['member']['unit']) { 
				$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> You can only manage your unit\'s members.');
				redirect('/unit/members', 'refresh');
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
			} 

			// Build the page and send some data in.
			$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$data['message'] = (isset($data['errors'])) ? $data['errors']: $data['message'];
			$this->load->view('templates/header_leader', $data);
			$this->load->view('leader/myunit/singlemember', $data);	
			$this->load->view('templates/footer', $data);
			
		}
	}
	
	// Delete a member
	public function delete_member($id)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('members');
		
		// Get logged in user.
		$user = $this->ion_auth->user()->row();
		$unit = $this->shared->get_user_unit($user->id, true);
		$member = $this->activities_model->get_member($id);

		// Make sure this user can modify this unit
		if ($this->ion_auth->is_admin() || $unit['id'] == $member['unit']) {
			$result = $this->activities_model->delete_member($id);
			if ($result) {
				$this->session->set_flashdata('message', '<i class="icon-ok teal"></i> '.$member['name'].' has been deleted');
				redirect("unit/members", 'refresh');
			}
		}
		// Return to the members page
		$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> You can only manage your unit\'s members.');
		redirect("unit/members", 'refresh');

	}

	public function change_contact()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('members');
		
		// Grab new details, prepare and update user.
		$this->form_validation->set_rules('unit', 'ID', 'required');
		$this->form_validation->set_rules('submit', 'correct token', 'required');
		$this->form_validation->set_rules('user', 'email', 'valid_email');
		
		// Get logged in user.
		$user = $this->ion_auth->user()->row();
		$unit = $this->shared->get_user_unit($user->id, true);

		if (isset($_POST) && $this->form_validation->run() === TRUE)
		{
			// Swap Contacts
			if ($this->shared->valid_csrf_nonce() && $this->input->post('submit') == "Swap Contacts" && $this->input->post('unit') == $unit['id'])
			{
				$this->account_model->swap_contacts($unit);
				$this->session->set_flashdata('message', '<i class="icon-info-ok teal"></i>  Contacts swapped, great success!');
				redirect("unit", 'refresh');
			} 
			// Invite a contact
			if ($this->shared->valid_csrf_nonce() && $this->input->post('submit') == "Set Alternate Contact" && $this->input->post('unit') == $unit['id'])
			{
				$result = $this->account_model->invite_contact($this->input->post('unit'), $this->input->post('user'), "alternate");
				//show_error('You invited a contact, result='.serialize($result));
				if ($result) $this->session->set_flashdata('message', '<i class="icon-ok teal"></i> Contact invited, success!');
				redirect("unit", 'refresh');
			} 

			//redirect them back to the admin page
			$this->session->set_flashdata('message', "Your browser didn't pass the security check, no changes were made to your unit. Please try again.");
			redirect("unit", 'refresh');
			
		}


		//set the flash data error message if there is one
		$this->session->set_flashdata('message', $data['message']);
		redirect("unit", 'refresh');
		
		
	}
}


?>