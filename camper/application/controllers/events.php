<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Events Controller
 *
 * This is the controller that handles account changes made by the active user.
 *
 * Version 1.4.5 (2014 04 23 1530)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Events extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('register_model');
	}

	// Send to new URI, register/index
	public function index()
	{
		redirect('registrations', 'refresh');
	}

	
	public function all()
	{
		if (!$this->ion_auth->logged_in()) redirect('public/events', 'refresh');
		
		// Get logged in user.
		$data['user'] = $this->ion_auth->user()->row();
		
		$data['individual'] = $this->shared->is_individual($data['user']);
		
		// Grab our events and unit
		$this->shared->recount();
		if ($data['individual']) {
			// Get our user's unit details
			$data['unit'] = unserialize($data['user']->individualdata);
			// Get events open to individuals
			$data['events'] = $this->register_model->get_events('Individuals');
		} else {
			// Grab our unit.
			$data['unit'] = $this->shared->get_current_unit($data['user']->company, $data['user']->id);

			// Get events
			$data['events'] = $this->register_model->get_events($data['unit']['unittype'].'s');
		}

		// Build the page and send some data in.
		$data['page'] = 'Events';
		$data['section'] = 'events';

		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->load->view('templates/header_leader', $data);
		$this->load->view('leader/events/all', $data);	
		$this->load->view('templates/footer', $data);
		
		
	}
	
	// Events listing for the public view
	public function public_view()
	{
		// Grab our events and unit
		$this->shared->recount();
		
		// Get events
		$data['events'] = $this->register_model->get_events();


		// Build the page and send some data in.
		$data['page'] = 'Events';
		$data['section'] = 'events';

		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->load->view('templates/header_public', $data);
		$this->load->view('public/events', $data);	
		$this->load->view('templates/footer', $data);
		
		
	}
	
}


?>