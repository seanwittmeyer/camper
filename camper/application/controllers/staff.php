<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Admin Event Controller
 *
 * This is the controller that handles the heart of the admin part of Camper. It
 * controls the creation and editing tools for events in the system.
 *
 * Version 1.4.5 (2014 04 23 1530)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Staff extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	// Get the list of regs for this event
	public function classes($event=FALSE,$session=FALSE,$class=FALSE)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('staff');
		if ($event === FALSE || $session == FALSE) redirect('atcamp', 'refresh');
		
		// Set page details.
		
		$data['page'] = 'Classes';

		// Get the list of events
		$data['event'] = $this->data->get_events($event,true);
		$data['session'] = $this->data->get_sessions($session,true);

		// One reg or all of them
		if ($class === FALSE) {
			$this->load->model('activities_model');
			$data['classes'] = $this->data->get_classes(false,true,array('event'=>$event));
			$view = 'staff/classes';
		} else {
			$data['class'] = $this->data->get_classes($class,true);
			$data['regs'] = $this->data->get_classregs(false,true,array('session'=>$session,'class'=>$class));
			$view = 'staff/oneclass';
		}
		
		// Show our page
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->load->view('templates/header_atcamp', $data);
		$this->load->view($view, $data);	
		$this->load->view('templates/footer_atcamp', $data);
	}

	// Get the list of regs for this event
	public function regs($event=FALSE,$session=FALSE,$reg=FALSE)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('staff');
		if ($event === FALSE || $session == FALSE) redirect('atcamp', 'refresh');
		
		// Set page details.
		
		$data['page'] = 'Registrations';

		// Get the list of events
		$this->shared->recount();
		$data['event'] = $this->data->get_events($event,true);
		$data['session'] = $this->data->get_sessions($session,true);

		// One reg or all of them
		if ($reg === FALSE) {
			$data['regs'] = $this->data->get_regs(false,true,array('session'=>$session));
			$view = 'staff/regs';
		} else {
			$data['reg'] = $this->data->get_regs($reg,true);
			if ($data['reg']['unitid'] == 0) {
				$unitid = false;
				$finunit = $data['reg']['userid'];
				$data['user'] = $data['reg']['userid'];
				$data['individual'] = true;
			} else {
				$unitid = $data['reg']['unitid']['id'];
				$finunit = $data['reg']['unitid'];
				$data['unit'] = $data['reg']['unitid'];
				$data['individual'] = false;
			}
			$payments = $this->data->get_payments($data['reg']['id'],$data['reg']['unitid']['id'],TRUE,TRUE);
			if ($data['individual']) {
				// Individual payments
				$data['payments'] = $this->shared->get_reg_payments($data['reg']['id'],$data['user']->id, null, null, true);
			} else {
				// Show everyone else' payments
				$data['payments'] = $this->shared->get_reg_payments($data['reg']['id'],$unitid);
			}
			$fin = $this->shared->get_finances($data['event'], $data['reg'], $data['session'], $finunit, $data['event']['options'], $data['event']['discounts'], $payments);
	
			$data['fin'] = $fin['fin'];
			$data['counts'] = $fin['counts'];
			$data['cost'] = $fin['cost'];
			$data['groups'] = $data['event']['groups'];
			$data['options'] = $data['event']['options'];
			$data['discounts'] = $data['event']['discounts'];
			$data['verify'] = $this->shared->verify($data['reg']['id'],false,false,false,false,false,$fin);
			$data['rosters'] = $this->data->get_rosters(false,true,array('reg'=>$data['reg']['id'])); 
			$data['members'] = $this->data->get_members(false,false,array('unit'=>$data['reg']['unitid']['id']));
			$view = 'staff/onereg';
		}
		
		// Show our page
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->load->view('templates/header_atcamp', $data);
		$this->load->view($view, $data);	
		$this->load->view('templates/footer_atcamp', $data);
	}

	// Upcoming events and index
	public function choose_event($event=FALSE)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('staff');
		
		// Set page details.
		
		$data['page'] = 'Set Event';

		// Get the list of events
		$data['events'] = ($event === FALSE) ? $this->data->get_events(): $this->data->get_events($event);
		$data['sessions'] = ($event === FALSE) ? false: $this->data->get_sessions(false,true,array('eventid'=>$event));
		if ($event) $data['heading'] = $data['events']['title'];
		
		// Show our page
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->load->view('templates/header_atcamp', $data);
		$this->load->view('staff/chooseevent', $data);	
		$this->load->view('templates/footer_atcamp', $data);
	}

	// Upcoming events and index
	public function start($event,$session)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('staff');
		
		// Run update and recount scripts
		//$this->shared->recount();

		// Set page details.
		$data['page'] = 'At Camp';
		$data['section'] = 'event';

		$data['event'] = $this->data->get_events($event,true);
		$data['session'] = $this->data->get_sessions($session,true);
		
		// Show our page
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->load->view('templates/header_atcamp', $data);
		$this->load->view('staff/start', $data);	
		$this->load->view('templates/footer_atcamp', $data);
	}

	/*	// If the form ran, update details. If not, fetch and display details.
		$this->form_validation->set_rules('id', 'id', 'required');
		$idcheck = ($eventid == $this->input->post('id')) ? true: false;

		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		if ($idcheck && $this->form_validation->run() === TRUE)
		{
			$message = $this->event_model->update_event($eventid);
			if ($data['message'] == '') $data['message'] = $message;
		}
		$this->session->set_flashdata('message', 'We couldn\'t find the event you were looking for.'); 
		redirect('event', 'refresh');
	*/

}


?>