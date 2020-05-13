<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Registration Controller
 *
 * This is the controller that handles the creation and management of event 
 * registrations from the leaders section of the system.
 *
 * Version 1.4.5 (2014 04 23 1530)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Register extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('register_model');
		$this->load->model('finance_model');
		$this->load->library('form_validation');
	}

	// Listing of upcoming event regs
	public function index()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('members');
		
		// Get logged in user.
		$data['user'] = $this->ion_auth->user()->row();
		
		$data['individual'] = $this->shared->is_individual($data['user']);
		
		// Grab our regs and unit
		if ($data['individual']) { 
			// Get our user's unit details
			$data['unit'] = unserialize($data['user']->individualdata);

			// Individual Regs
			$data['regs'] = $this->register_model->get_individual_regs($data['user']->id);

		} else {
			// Grab our unit.
			$data['unit'] = $this->shared->get_current_unit($data['user']->company, $data['user']->id);
	
			// Grab our event regs for this unit
			$data['regs'] = $this->register_model->get_unit_regs($data['unit']['id']);
		}
		
		// Build the page and send some data in.
		$data['page'] = 'Registrations';
		$data['section'] = 'register';

		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->load->view('templates/header_leader', $data);
		$this->load->view('leader/register/upcoming', $data);	
		$this->load->view('templates/footer', $data);
	}
	
	// Listing of past event regs
	public function past()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('members');
		
		// Get logged in user.
		$data['user'] = $this->ion_auth->user()->row();
		
		$data['individual'] = $this->shared->is_individual($data['user']);
		
		// Grab our regs and unit
		if ($data['individual']) { 
			// Get our user's unit details
			$data['unit'] = unserialize($data['user']->individualdata);

			// Individual Regs
			$data['regs'] = $this->register_model->get_individual_regs($data['user']->id);

		} else {
			// Grab our unit.
			$data['unit'] = $this->shared->get_current_unit($data['user']->company, $data['user']->id);
	
			// Grab our event regs for this unit
			$data['regs'] = $this->register_model->get_unit_regs($data['unit']['id']);
		}
		
		// Build the page and send some data in.
		$data['page'] = 'Past Registrations';
		$data['section'] = 'register';

		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->load->view('templates/header_leader', $data);
		$this->load->view('leader/register/past', $data);	
		$this->load->view('templates/footer', $data);
	}
	

	public function dashboard($reg=FALSE)
	{
		redirect('registrations', 'refresh');
	}
	
	public function details($reg=FALSE)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('members');
		
		if ($reg === false) {
			$this->session->set_flashdata('message', "Choose an event registration to manage.");
			redirect('events', 'refresh');
		}

		// Get logged in user.
		$data['user'] = $this->ion_auth->user()->row();
		$data['individual'] = $this->shared->is_individual($data['user']);
		
		// Grab our regs and unit
		if ($data['individual']) { 
			// Get our user's unit details
			$data['unit'] = unserialize($data['user']->individualdata);
		} else {
			// Grab our unit.
			$data['unit'] = $this->shared->get_current_unit($data['user']->company, $data['user']->id);
		}
		
		/* Get cookie deprecated 
		$data['cookiereg'] = $this->input->cookie('camperactiveevent', TRUE);
		if (!isset($data['cookiereg']) || $data['cookiereg'] == '') {
			$this->session->set_flashdata('message', "Choose an event registration to manage.");
			redirect('events', 'refresh');
		}
		*/

		// If the form ran, update details. If not, fetch and display details.
		if ($data['individual']) {
			$this->form_validation->set_rules('user', 'user', 'required');
		} else {
			$this->form_validation->set_rules('unit', 'unit', 'required');
		}
		$this->form_validation->set_rules('event', 'event', 'required');
		$this->form_validation->set_rules('reg', 'reg', 'required');
		$this->form_validation->set_rules('session', 'session', 'required');

		if ($this->form_validation->run() === TRUE)
		{
			if ($data['user']->id !== $this->input->post('user')) {
				$message = 'Your registration was not updated, please try it again!';
			} elseif ($this->input->post('what') == 'unregister') {
				$this->register_model->delete_reg($this->input->post('reg'),$data['user']->id);
				$this->session->set_flashdata('message', "Your unit has been unregistered.");
				redirect('events', 'refresh');
			} else {
				$regresult = $this->register_model->update_reg($this->input->post('reg'), false);

				// Recount registration numbers
				$this->shared->recount();
				$message = ($regresult === true) ? 'High five! Your registration has been updated.' : $regresult;
			}
		}
		
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		// Get our event and dump variables into $data for the view
		$regset = ($data['individual']) ? $this->shared->get_reg_set(false, $reg, false, true, true, $data['user']): $this->shared->get_reg_set($data['unit'], $reg, false, true);
		if ($data['individual']) {
			if ($regset['reg']['userid'] !== $data['user']->id) {
				$this->session->set_flashdata('message', "You can only manage your own event registrations.");
				redirect('events', 'refresh');
			}
		} else {
			if ($regset['reg']['unitid'] !== $data['unit']['id']) {
				$this->session->set_flashdata('message', "You can only manage your own event registrations.");
				redirect('events', 'refresh');
			}
		}
		$data['reg'] = $regset['reg'];
		$data['reg']['registerdate'] = unserialize($data['reg']['registerdate']);
		$data['reg']['discounts'] = unserialize($data['reg']['discounts']);
		$data['reg']['options'] = unserialize($data['reg']['options']);
		$data['session'] = $regset['session'];
		$data['event'] = $regset['event'];
		$data['event']['earlyreg'] = unserialize($data['event']['earlyreg']);
		$data['event']['paymenttiers'] = unserialize($data['event']['paymenttiers']);
		$data['event']['freeadults'] = unserialize($data['event']['freeadults']);
		$data['event']['eligibleunits'] = unserialize($data['event']['eligibleunits']);
		$data['groups'] = unserialize($data['event']['groups']);
		$data['options'] = $regset['options'];
		$data['discounts'] = $regset['discounts'];
		$payments = $this->shared->get_reg_payments($data['reg']['id'],$data['user']->id,true,true,true);
		$data['payments'] = $this->shared->get_reg_payments($data['reg']['id'],$data['user']->id,null,null,true);
		$fin = $this->shared->get_finances($data['event'], $data['reg'], $data['session'], $data['unit'], $data['options'], $data['discounts'], $payments); 
		$data['fin'] = $fin['fin'];
		$data['counts'] = $fin['counts'];
		$data['cost'] = $fin['cost'];
		$data['verify'] = $this->shared->verify($data['reg']['id']);

		//print_r($data['fin']);die;
		$data['regfull'] = $this->data->get_regs_full($data['reg']['id'],true);

		// Build the page and send some data in.
		$data['page'] = 'Registration';
		$data['section'] = 'register';
		
		if (!isset($message)) {
			$message = $this->session->flashdata('message');
		}
		$data['message'] = (validation_errors()) ? validation_errors() : $message;

		$this->load->view('templates/header_leader', $data);
		$this->load->view('leader/register/details', $data);	
		$this->load->view('templates/footer', $data);
		
		
	}
	
	// Roster list of participants in this event
	public function roster($reg=FALSE)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('members');

		if ($reg === false) {
			$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> Choose an event registration to manage.');
			redirect('events', 'refresh');
		}

		// Get logged in user.
		$data['user'] = $this->ion_auth->user()->row();
		if ($this->shared->is_individual($data['user'])) {
			$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> Individual registrations do not qualify for roster and class registration at this time.');
			redirect('registrations/'.$reg.'/details', 'refresh');
		}

		// Get regset
		$data['unit'] = $this->shared->get_current_unit($data['user']->company, $data['user']->id);
		$regset = $this->shared->get_reg_set($data['unit'], $reg, false, true);

		// Build the page and send some data in.
		$data['page'] = 'Roster &lsaquo; Registration';
		$data['section'] = 'register';

		// Fetch some details
		$data['reg'] = $regset['reg'];
		$data['session'] = $regset['session'];
		$data['event'] = $regset['event'];
		
		// Let's get the discounts for the individual members
		$data['discounts'] = $regset['discounts'];


		// Rosters enabled?
		/* We are enabling rosters for all events
		if ($data['event']['activityregs'] == '0') {
			$this->session->set_flashdata('message', ' <i class="icon-info-sign blue"></i> We aren\'t using rosters for this event.');
			redirect('registrations/'.$reg.'/details', 'refresh');
		} elseif ($data['reg']['roster'] == '0') {
			$this->session->set_flashdata('message', anchor('/registrations/'.$reg.'/roster/create', 'Create Roster &rarr;', 'class="btn btn-small blue right"').' <i class="icon-info-sign blue"></i> You\'ll need to create a roster to do this.');
			redirect('registrations/'.$reg.'/details', 'refresh');
		}
		*/
		$data['noroster'] = false;
		if ($data['reg']['roster'] == '0') {
			// We don't have a roster yet, we will present the option to create one if allowed
			//$data['noroster'] = true;
			//$this->session->set_flashdata('message', anchor('/registrations/'.$reg.'/roster/create', 'Create Roster &rarr;', 'class="btn btn-small blue right"').' <i class="icon-info-sign blue"></i> You\'ll need to create a roster to do this.');
			redirect('registrations/'.$reg.'/roster/create', 'refresh');
		} 
		// Did we get formed?
    	$this->load->model('activities_model');

    	$this->form_validation->set_rules('event', 'event', 'required');
    	$this->form_validation->set_rules('reg', 'reg', 'required');
    	$this->form_validation->set_rules('unit', 'unit', 'required');

    	if ($this->form_validation->run() === TRUE && $reg == $this->input->post('reg'))
    	{
    		$result = $this->activities_model->create_roster($reg, $data['unit']['id'], $this->input->post('youth'), $this->input->post('adults'));
    		if ($result == true) redirect('registrations/'.$reg.'/roster', 'refresh');
    	}

    	// Some additional details
    	$data['verify'] = $this->shared->verify($data['reg']['id']);
    	$data['members'] = $this->activities_model->get_members($data['unit']['id'], true);
    	$data['roster'] = $this->activities_model->get_roster($data['unit']['id'], $data['reg']['id'], true);
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		$this->load->view('templates/header_leader', $data);
		$this->load->view('leader/register/roster', $data);	
		$this->load->view('templates/footer', $data);
	}


	// Roster list of participants in this event
	public function single_roster($reg=FALSE,$roster=FALSE)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('members');

		if ($reg === false) {
			$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> Choose an event registration to manage.');
			redirect('events', 'refresh');
		}
		if ($roster === false) {
			redirect('registrations/'.$reg.'/roster', 'refresh');
		}

		// Get logged in user.
		$data['user'] = $this->ion_auth->user()->row();
		if ($this->shared->is_individual($data['user'])) {
			$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> Individual registrations do not qualify for roster and class registration at this time.');
			redirect('registrations/'.$reg.'/details', 'refresh');
		}
		
    	
		if ($this->input->post('updatediscounts') == '1') {
			$updatediscounts = $this->register_model->update_single_roster_discounts();
			if ($updatediscounts) $message = '<i class="icon-ok teal"></i> Your discounts were saved';
		}

		// Get regset
		$data['unit'] = $this->shared->get_current_unit($data['user']->company, $data['user']->id);
		$regset = $this->shared->get_reg_set($data['unit'], $reg, false, true);

		// Fetch some details
		$data['reg'] = $regset['reg'];
		$data['session'] = $regset['session'];
		$data['event'] = $regset['event'];

		// Rosters enabled?
		if ($data['event']['activityregs'] == '0') {
			$this->session->set_flashdata('message', ' <i class="icon-info-sign blue"></i> Class registrations are not open for this event.');
			redirect('registrations/'.$reg.'/details', 'refresh');
		} elseif ($data['reg']['roster'] == '0') {
			$this->session->set_flashdata('message', anchor('/registrations/'.$reg.'/roster/create', 'Create Roster &rarr;', 'class="btn btn-small blue right"').' <i class="icon-info-sign blue"></i> You\'ll need to create a roster to do this.');
			redirect('registrations/'.$reg.'/details', 'refresh');
		}

		// Did we get formed?
		$this->load->model('activities_model');
		//$result = $this->activities_model->create_roster($reg, $data['unit']['id'], $this->input->post('youth'), $this->input->post('adults'));

		// Some additional details
		$data['verify'] = $this->shared->verify($data['reg']['id']);
		$data['members'] = $this->activities_model->get_members($data['unit']['id'], true);
		$data['rosters'] = $this->activities_model->get_roster($data['unit']['id'], $data['reg']['id'], true);
		$data['roster'] = $data['rosters'][$roster];
		$data['roster']['discounts'] = unserialize($data['roster']['discounts']);
		$data['member'] = $data['members'][$data['roster']['member']];
		if ($data['roster']['unit'] !== $data['unit']['id']) {
			$this->session->set_flashdata('message', '<i class="icon-remove red"></i> You can only manage your own unit\'s members.');
			redirect('registrations/'.$reg.'/roster', 'refresh');
		}
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$data['message'] = (isset($message)) ? $message: $data['message'];
		$data['periods'] = (isset($data['event']['periods'])) ? unserialize($data['event']['periods']) : false;
		$data['activities'] = $this->activities_model->get_activities($data['event']['eventtype'],'eventtype',true);
		$data['classes'] = $this->activities_model->get_classes($data['event']['id'],true);
		$data['classregs'] = $this->activities_model->get_class_regs($roster,true);
		
		// Discounts
		$data['discounts'] = $regset['discounts'];

		$data['qualifies'] = true;
		if ($data['verify']['result'] == false) {
			$data['qualifies'] = false;
		}
		if ($data['event']['activityregs'] !== '1') {
			$data['verify']['error']['activitiesnotenabled'] = 'Class registrations are not open for this event at this time.';
			$data['qualifies'] = false;
		}
		if ($data['event']['activityregs'] == '1' && $data['event']['activitydate'] > time()) {
			$data['verify']['error']['activitiesnotopen'] = 'Class registrations will not be open until '.date('F j, Y', $data['event']['activitydate']);
			$data['qualifies'] = false;
		}
		if (count($data['verify']['error']) == 1 && isset($data['verify']['error']['schedule'])) {
			if ($data['verify']['source']['fin']['fin']['totalpaid'] >= $data['verify']['source']['fin']['fin']['totalnopreorders']) {
				// We don't qualify because the preorder costs, we'll let that slide as we register for classes
				$data['qualifies'] = true;
			}
		}
		

		// Build the page and send some data in.
		$data['page'] = $data['member']['name'].' &lsaquo; Registration';
		$data['section'] = 'register';
		$this->load->view('templates/header_leader', $data);
		$this->load->view('leader/register/singleroster', $data);	
		$this->load->view('templates/footer', $data);
	}

	// Roster list of participants in this event
	public function create_roster($reg=FALSE)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('members');

		if ($reg === false) {
			$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> Choose an event registration to manage.');
			redirect('events', 'refresh');
		}

		// Get logged in user.
		$data['user'] = $this->ion_auth->user()->row();
		if ($this->shared->is_individual($data['user'])) {
			$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> Individual registrations do not qualify for roster and class registration at this time.');
			redirect('registrations/'.$reg.'/details', 'refresh');
		}

		// Get regset
		$data['unit'] = $this->shared->get_current_unit($data['user']->company, $data['user']->id);
		$regset = $this->shared->get_reg_set($data['unit'], $reg, false, true);

		// Build the page and send some data in.
		$data['page'] = 'Roster &lsaquo; Registration';
		$data['section'] = 'register';

		// Fetch some details
		$data['reg'] = $regset['reg'];
		$data['event'] = $regset['event'];

		if ($data['reg']['roster'] == '1') {
			$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> Your roster has already been created');
			redirect('registrations/'.$reg.'/roster', 'refresh');
		}

		// Rosters enabled?
		if ($data['event']['activityregs'] == '0') {
			$this->session->set_flashdata('message', ' <i class="icon-info-sign blue"></i> We aren\'t using rosters for this event.');
			redirect('registrations/'.$reg.'/details', 'refresh');
		}

		// Did we get formed?
		$this->load->model('activities_model');

		$this->form_validation->set_rules('event', 'event', 'required');
		$this->form_validation->set_rules('reg', 'reg', 'required');
		$this->form_validation->set_rules('unit', 'unit', 'required');

		if ($this->form_validation->run() === TRUE && $reg == $this->input->post('reg'))
		{
			$result = $this->activities_model->create_roster($reg, $data['unit']['id'], $this->input->post('youth'), $this->input->post('adults'));
			if ($result == true) redirect('registrations/'.$reg.'/roster', 'refresh');
		}

		// Some additional details
		$data['members'] = $this->activities_model->get_members($data['unit']['id'], true);
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		if (isset($messages)) $data['message'] = $messages;

		$this->load->view('templates/header_leader', $data);
		$this->load->view('leader/register/createroster', $data);	
		$this->load->view('templates/footer', $data);
	}

	// Create new registration record
	public function new_reg($event, $session=FALSE, $group=FALSE)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('members');
		
		// Get logged in user.
		$data['user'] = $this->ion_auth->user()->row();
		$data['individual'] = $this->shared->is_individual($data['user']);
		$data['page'] = 'Confirm Registeration';
		$data['section'] = 'register';
		
		// Grab our regs and unit
		if ($data['individual']) { 
			// Get our user's unit details
			$data['unit'] = unserialize($data['user']->individualdata);
			
			// First, are we already registered? If so, send them on their way
			$id = $this->register_model->get_registration($data['user']->id, $event, $session, false, true);
			if ($id && $this->input->get('confirm') == '1') {
				// We already have a reg but the user wants to create a new registration
				$action = 'new';
			} elseif ($id) {
				// Confirm that we want a new reg
				$action = 'confirm';
			} else {
				// No reg, let's create it
				$action = 'new';
			}

			// Handle the action
			if ($action == 'new') {
				// Create a new registration
				$id = ($group === false) ? $this->register_model->register(false, $event, $session, $data['user']->id, true, false, false, false, true) : $this->register_model->register(false, $event, $session, $data['user']->id, true, false, false, $group);
				redirect('registrations/'.$id.'/details', 'refresh');

			} elseif ($action == 'go') {
				// Forward to the registration we found
				redirect('registrations/'.$id.'/details', 'refresh');

			} elseif ($action == 'confirm') {
				// Display the confirm page
				if ($this->session->flashdata('message')) {
					$data['message'] = $this->session->flashdata('message');
				} else {
					$data['message'] = '';
				}

				// Fetch some details
				$data['unit'] = unserialize($data['user']->individualdata);
				$data['regs'] = $this->register_model->get_individual_regs($data['user']->id);
				$data['new'] = array(
					'event' => $event,
					'session' => $session,
					'group' => $group
				);

				// Display the view
				$this->load->view('templates/header_leader', $data);
				$this->load->view('leader/register/new', $data);	
				$this->load->view('templates/footer', $data);
			} else {
				redirect('registrations', 'refresh');
			}

		} else {
			// Grab our unit.
			$data['unit'] = $this->shared->get_current_unit($data['user']->company, $data['user']->id);
			if (!$data['unit']) {
				$this->shared->error_mandrill('You are not allowed to register this unit for events.', 'fx register->set()', array('event'=>$event,'session'=>$session,'group'=>$group,'user'=>$data['user']->id));
				show_error('You are not allowed to register this unit for events. fx set');
			}

			// First, are we already registered? If so, send them on their way
			$id = $this->register_model->get_registration($data['unit']['id'], $event, $session);


			if ($id && $this->input->get('confirm') == '1') {
				// We already have a reg but the user wants to create a new registration
				$action = 'new';
			} elseif ($id) {
				// Confirm that we want a new reg
				$action = 'confirm';
			} else {
				// No reg, let's create it
				$action = 'new';
			}

			// Handle the action
			if ($action == 'new') {
				// Create a new registration
				$id = ($group === false) ? $this->register_model->register($data['unit']['id'], $event, $session, $data['user']->id) : $this->register_model->register($data['unit']['id'], $event, $session, $data['user']->id, true, false, false, $group);
				redirect('registrations/'.$id.'/details', 'refresh');

			} elseif ($action == 'go') {
				// Forward to the registration we found
				redirect('registrations/'.$id.'/details', 'refresh');

			} elseif ($action == 'confirm') {
				// Display the confirm page
				if ($this->session->flashdata('message')) {
					$data['message'] = $this->session->flashdata('message');
				} else {
					$data['message'] = '';
				}

				// Fetch some details
				$data['unit'] = $this->shared->get_current_unit($data['user']->company, $data['user']->id);
				$data['regs'] = $this->register_model->get_unit_regs($data['unit']['id']);
				$data['new'] = array(
					'event' => $event,
					'session' => $session,
					'group' => $group
				);

				// Display the view
				$this->load->view('templates/header_leader', $data);
				$this->load->view('leader/register/new', $data);	
				$this->load->view('templates/footer', $data);
			} else {
				redirect('registrations', 'refresh');
			}
		}
	}
}


?>