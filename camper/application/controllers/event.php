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

class Event extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('event_model');
		$this->load->model('activities_model');
	}

	// Upcoming events and index
	public function index()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// Get logged in user.
		$user = $this->ion_auth->user()->row();
		
		// Run update and recount scripts
		$this->shared->recount();

		// Set page details.
		$data['page'] = 'Events';
		$data['section'] = 'event';
		$data['first'] = $user->first_name;
		$data['last'] = $user->last_name;
		$data['email'] = $user->email;

		// Get the list of events
		$data['events'] = $this->event_model->get_all_events();
		
		// Show our page
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/event/all', $data);	
		$this->load->view('templates/footer', $data);
	}

	// Past events
	public function past()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// Get logged in user.
		$user = $this->ion_auth->user()->row();

		// Set page details.
		$data['page'] = 'Events';
		$data['section'] = 'event';
		$data['first'] = $user->first_name;
		$data['last'] = $user->last_name;
		$data['email'] = $user->email;

		// Get the list of events
		$data['events'] = $this->event_model->get_all_events();
		
		// Show our page
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/event/past', $data);	
		$this->load->view('templates/footer', $data);
	}
	
	// Single Event Details
	public function details($eventid)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');	
	
		// Set page details.
		$data['page'] = 'Events';
		$data['section'] = 'event';

		// If the form ran, update details. If not, fetch and display details.
		$this->form_validation->set_rules('id', 'id', 'required');
		$idcheck = ($eventid == $this->input->post('id')) ? true: false;

		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		if ($idcheck && $this->form_validation->run() === TRUE)
		{
			$message = $this->event_model->update_event($eventid);
			if ($data['message'] == '') $data['message'] = $message;
		}

		// Check if we are all good to go.
		$data['event'] = $this->data->get_events($eventid);
		if (empty($data['event'])) { 
			$this->session->set_flashdata('message', 'We couldn\'t find the event you were looking for.'); 
			redirect('event', 'refresh');
		}
	
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/event/details', $data);	
		$this->load->view('templates/footer', $data);
	}

	// Single Event Messenger
	public function message($eventid)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// Get logged in user.
		$user = $this->ion_auth->user()->row();

		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// Set page details.
		$data['page'] = 'Send a Message ‹ Events';
		$data['section'] = 'event';

		// Get the event in question
		$data['event'] = $this->event_model->get_single_event($eventid);
		if (empty($data['event'])) { 
			$this->session->set_flashdata('message', 'We couldn\'t find the event you were looking for.'); 
			redirect('event', 'refresh');
		}
		$data['send_success'] = false;

		// If the form ran, update details. If not, fetch and display details.
		$this->form_validation->set_rules('id', 'id', 'required');
		$this->form_validation->set_rules('title', 'title', 'required');
		$this->form_validation->set_rules('message', 'message', 'required');
		
		if ($this->form_validation->run() === TRUE && $this->shared->valid_csrf_nonce() === TRUE && $eventid == $this->input->post('id'))
		{
			// We have a valid request, lets process.
					
			/* Send a message to users via mandrill
			 * $message[title] = (required) title and message subject
			 * $message[message] = (required) the content of the message, only text.
			 * $message[link] = (false or string) the link (camper relative or the main page for false)
			 * $message[youareregistered] = (false or string) You got this email becaus you are registered for this event.
			 * $users = mandrill to array = array(array(email,name,type=to|cc|bcc))
			 * $from[name] = (required) Full name
			 * $from[email] = (required) email address
			 */
			$message = array(
				'title' 			=> $this->input->post('title'),
				'message'			=> $this->input->post('message'),
				'link' 				=> 'registrations',
				'youareregistered' 	=> 'You got this email because your unit is registered for '.$data['event']['title']
			);
			$from = array(
				'name'				=> $user->first_name.' '.$user->last_name,
				'email' 			=> $user->email
			);
			
			// Get the users for our event
			$users = $this->shared->get_users(false,false,'all',true);
			$units = $this->shared->get_units(false,true);
			$regs  = $this->shared->get_regs($eventid);
			$to = array();
			$to[] = array(
				'email' => $from['email'],
				'name'  => $from['name'],
				'type'  => 'to'
			);
			$to[] = array(
				'email' => $this->config->item('camper_fromemail'),
				'name'  => 'Camper Admin',
				'type'  => 'cc'
			);
			$i = 2;
			foreach ($regs as $r) {
				if ($r['individual'] == '1') {
					// Individual
					$to[] = array(
						'email' => $users[$r['userid']]['email'],
						'name'  => $users[$r['userid']]['first_name'].' '.$users[$r['userid']]['last_name'],
						'type'  => 'bcc'
					);
					$i++;
				} else {
					// The primary
					$a = (isset($units[$r['unitid']]['primary']) && $units[$r['unitid']]['primary'] > 0) ? true: false;
					if ($a) { 
						$to[] = array(
							'email' => $users[$units[$r['unitid']]['primary']]['email'],
							'name'  => $users[$units[$r['unitid']]['primary']]['first_name'].' '.$users[$units[$r['unitid']]['primary']]['last_name'],
							'type'  => 'bcc'
						);
						$i++;
					}
					// The Alternate
					$a = (isset($units[$r['unitid']]['alt']) && $units[$r['unitid']]['alt'] > 0) ? true: false;
					if ($a) { 
						$to[] = array(
							'email' => $users[$units[$r['unitid']]['alt']]['email'],
							'name'  => $users[$units[$r['unitid']]['alt']]['first_name'].' '.$users[$units[$r['unitid']]['alt']]['last_name'],
							'type'  => 'bcc'
						);
						$i++;
					}
				}
			}
			//show_error(print_r(array('message'=>$message,'to'=>$to,'from'=>$from)));
			
			// Send our message
			$result = $this->shared->send_group_message($message, $to, $from);
			$data['send_success'] = true;
			
			// Unset the details we don't need for this view
			unset($users);
			unset($units);
			unset($regs);
			unset($to);
		} 
		
		// Security Check
		$data['csrf'] = $this->shared->get_csrf_nonce();
		
		// Display our page
		if ($this->form_validation->run() === TRUE && $this->shared->valid_csrf_nonce() === FALSE) {
			$data['message'] = '<i class="icon-question-sign blue"></i> Are you sure you want to send this message? &nbsp; <input type="submit" name="submit" value="Yes, Send &rarr;" class="right btn btn-small teal"  data-loading-text="Sending the message..." onclick="$(this).button(\'loading\');" />';
		} else {
			$data['message'] = (isset($result) && $result === true) ? '<i class="icon-ok teal"></i> Your message was delivered to '.$i.' leaders.': (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		}
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/event/message', $data);	
		$this->load->view('templates/footer', $data);
	}

	// Sessions Page
	public function sessions($eventid)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// Get logged in user.
		$user = $this->ion_auth->user()->row();

		$this->load->helper('url');
		$this->load->library('form_validation');
		
		// Set page details.
		$data['page'] = 'Sessions ‹ Events';
		$data['section'] = 'event';
		
		$result = false;
		$data['session'] = array();

		// If the form ran, update details. If not, fetch and display details.
		$this->form_validation->set_rules('id', 'id', 'required');
		
		if ($this->form_validation->run() === FALSE)
		{
			// Check if we are all good to go.
			$data['event'] = $this->event_model->get_single_event($eventid);
	   		$data['sessions'] = $this->event_model->get_sessions($eventid);
		}
		else
		{
			// Let's update our sessions. The sessions form array will be imported in update_sessions() so we don't need to pass it.
			$result = $this->event_model->update_sessions($eventid);
			if ($result) {
				// Get our updated data
				$data['event'] = $this->event_model->get_single_event($eventid);
		   		$data['sessions'] = $this->event_model->get_sessions($eventid);
			} else {
				show_error('The sessions were not successfully updated. fx sessions');
			}
		}
		if (empty($data['event'])) { 
			$this->session->set_flashdata('message', 'We couldn\'t find the event you were looking for.'); 
			redirect('event', 'refresh');
		}
	
		$data['message'] = (validation_errors()) ? validation_errors() : $result;
		$data['groups'] = (isset($data['event']['groups'])) ? unserialize($data['event']['groups']) : false;
		$data['periods'] = (isset($data['event']['periods'])) ? unserialize($data['event']['periods']) : false;
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/event/sessions', $data);	
		$this->load->view('templates/footer', $data);
	}
	
	// Event Activities Page
	public function classes($eventid)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// Get logged in user.
		$user = $this->ion_auth->user()->row();

		$this->load->library('form_validation');
		
		// Set page details.
		$data['page'] = 'Classes ‹ Events';
		$data['section'] = 'event';
			
		$data['event'] = $this->event_model->get_single_event($eventid);
		if (empty($data['event'])) { 
			$this->session->set_flashdata('message', 'We couldn\'t find the event you were looking for.'); 
			redirect('event', 'refresh');
		}
		if (isset($data['event']['activityregs']) && $data['event']['activityregs'] == '1') {
			$data['periods'] = (isset($data['event']['periods'])) ? unserialize($data['event']['periods']) : false;
			$data['activities'] = $this->activities_model->get_activities($data['event']['eventtype'],'eventtype',true);
			$data['classes'] = $this->activities_model->get_classes($data['event']['id']);
			$data['sessions'] = $this->event_model->get_sessions($data['event']['id']);
		} else {
			$data['disabled'] = true;
		}
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/event/classes', $data);	
		$this->load->view('templates/footer', $data);
	}
	
	// Event Activities Page
	public function class_rosters($eventid,$classid)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// Get logged in user.
		$user = $this->ion_auth->user()->row();

		$this->load->library('form_validation');
		
		// Set page details.
		$data['page'] = 'Class Roster ‹ Classes ‹ Events';
		$data['section'] = 'event';
			
   		$data['class'] = $this->activities_model->get_class($classid);
   		if (empty($data['class'])) {
			$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> We couldn\'t find the class you were looking for.'); 
			redirect('event/'.$eventid.'/classes', 'refresh');
   		}
		$data['event'] = $this->event_model->get_single_event($data['class']['event']);
		if (empty($data['event'])) { 
			$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> We couldn\'t find the event you were looking for.'); 
			redirect('event', 'refresh');
		}
		if (isset($data['event']['activityregs']) && $data['event']['activityregs'] == '1') {
			$data['periods'] = (isset($data['event']['periods'])) ? unserialize($data['event']['periods']) : false;
			$data['activity'] = $this->activities_model->get_activity($data['class']['activity']);
			$data['sessions'] = $this->event_model->get_sessions($data['event']['id']);
			$data['regs'] = $this->activities_model->get_class_members($data['class']['id'],true);
		} else {
			$data['disabled'] = true;
		}
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		//print_r($data);die;
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/event/classrosters', $data);	
		$this->load->view('templates/footer', $data);
	}
	
	// Options Page
	public function eventoptions($eventid=1)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// Get logged in user.
		$user = $this->ion_auth->user()->row();

		$this->load->helper('form');
		
		// Set page details.
		$data['page'] = 'Options ‹ Events';
		$data['section'] = 'event';
		$data['first'] = $user->first_name;
		$data['last'] = $user->last_name;
		$data['email'] = $user->email;

		// If the form ran, update details. If not, fetch and display details.
		$this->form_validation->set_rules('id', 'id', 'required');
		
		if ($this->form_validation->run() === FALSE)
		{
			// Check if we are all good to go.
			$data['event'] = $this->event_model->get_single_event($eventid);
		
			if (empty($data['event'])) { redirect('event', 'refresh'); }
			
			// Unserialize our options
			$data['earlyreg'] = unserialize($data['event']['earlyreg']);
			$data['paymenttiers'] = unserialize($data['event']['paymenttiers']);
			$data['freeadults'] = unserialize($data['event']['freeadults']);
			
			$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	
			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/event/options', $data);	
			$this->load->view('templates/footer', $data);
			
		}
		else
		{
			
			//Update the options
			$message = $this->event_model->update_event_options($eventid);
			
			// Check if we are all good to go.
			$data['event'] = $this->event_model->get_single_event($eventid);
		
			if (empty($data['event'])) { redirect('event', 'refresh'); }
		
			// Unserialize our options
			$data['earlyreg'] = unserialize($data['event']['earlyreg']);
			$data['paymenttiers'] = unserialize($data['event']['paymenttiers']);
			$data['freeadults'] = unserialize($data['event']['freeadults']);

			$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			
			if ($data['message'] == '') $data['message'] = $message;
			
			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/event/options', $data);	
			$this->load->view('templates/footer', $data);
		}
	}
	
	// Custom Options and Discounts Page
	public function customoptions($eventid=1)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// needed?
		//$user = $this->ion_auth->user()->row();
		//$this->load->helper('form');
		
		// Setup
		$data['page'] = 'Custom Options ‹ Events';
		$data['section'] = 'event';
		$data['options'] = array();
		$data['discounts'] = array();
		$result = false;

		// If the form ran, update details. If not, fetch and display details.
		$this->form_validation->set_rules('id', 'id', 'required');
		
		if ($this->form_validation->run() === FALSE)
		{
			// Check if we are all good to go.
			$data['event'] = $this->event_model->get_single_event($eventid);
	   		$data['options'] = $this->event_model->get_options($eventid);
	   		$data['discounts'] = $this->event_model->get_discounts($eventid);
	   		
		}
		else
		{
			// Let's update our options and discounts. The form array will be imported in update_custom() so we don't need to pass it.
			$result = $this->event_model->update_custom($eventid);
			if ($result !== false) {
				// Get our updated data
				$data['event'] = $this->event_model->get_single_event($eventid);
		   		$data['options'] = $this->event_model->get_options($eventid);
		   		$data['discounts'] = $this->event_model->get_discounts($eventid);
			} else {
				show_error('The options and discounts were not successfully updated. fx customoptions');
			}
		}
		if (empty($data['event'])) { redirect('event', 'refresh'); }
	
		$data['message'] = (validation_errors()) ? validation_errors() : $result;

		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/event/custom', $data);	
		$this->load->view('templates/footer', $data);
	}

	// Listing of registered units for the event
	public function registrations($eventid)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// Recount registration numbers
		$this->shared->recount();

		// Get our event and sessions
		$data['event'] = $this->event_model->get_single_event($eventid);
		if (isset($data['event']['groups']) && $data['event']['groups'] !== '') {
			$data['groups'] = unserialize($data['event']['groups']);
			//show_error(print_r($data['groups']));
			if ($data['groups']['enabled'] !== '1') { $data['groups'] = false; }
		} else {
			$data['groups'] = false;
		}
	   	$data['sessions'] = $this->event_model->get_sessions($eventid);
	
		// Build the page and send some data in.
		$data['page'] = 'Registrations ‹ Event';
		$data['section'] = 'event';

		$data['message'] = $this->session->flashdata('message');
		
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/event/registrations', $data);	
		$this->load->view('templates/footer', $data);
	}	

	// Edit an avent registration
	public function editregistration($event,$reg)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// If the form ran, update details. If not, fetch and display details.
		if ($this->input->post('individual') == '1') {
			$this->form_validation->set_rules('user', 'user', 'required');
		} else {
			$this->form_validation->set_rules('unit', 'unit', 'required');
		}
		$this->form_validation->set_rules('event', 'event', 'required|numeric');
		$this->form_validation->set_rules('reg', 'reg', 'required|numeric');
		$this->form_validation->set_rules('session', 'session', 'required|numeric');
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
					
		if ($this->input->post('reg') == $reg && $this->form_validation->run() === TRUE)
		{
			// Load the register functions
			$this->load->model('register_model');
			if ($this->input->post('action') == 'roster') {
    			$result = $this->activities_model->create_roster($reg, $this->input->post('unit'), $this->input->post('youth'), $this->input->post('adults'));
				$data['message'] = ($result === true) ? '<i class="icon-ok teal"></i> The roster has been updated.' : $regresult;
			} else {
				$regresult = $this->register_model->update_reg($reg, false);
				$data['message'] = ($regresult === true) ? '<i class="icon-ok teal"></i> High five! The registration has been updated.' : $regresult;
			}

		}
		
		// Recount registration numbers
		$this->shared->recount();

		// Get our event and sessions
	   	$regset = $this->shared->get_reg_set(false, $reg, false, true);
		$data['reg'] = $regset['reg'];
		$data['unit'] = $regset['unit'];
		if ($regset['individual']) {
			$unitid = false;
			$finunit = $regset['user'];
			$data['user'] = $regset['user'];
			$data['individual'] = true;
		} else {
			$unitid = $data['unit']['id'];
			$finunit = $regset['unit'];
			$data['individual'] = false;
		}
		$data['reg']['registerdate'] = unserialize($data['reg']['registerdate']);
		$data['reg']['discounts'] = unserialize($data['reg']['discounts']);
		$data['reg']['options'] = unserialize($data['reg']['options']);
		$data['session'] = $regset['session'];
	   	$data['sessions'] = $this->event_model->get_sessions($data['reg']['eventid']);
		$data['event'] = $regset['event'];
		$data['event']['earlyreg'] = unserialize($data['event']['earlyreg']);
		$data['event']['paymenttiers'] = unserialize($data['event']['paymenttiers']);
		$data['event']['freeadults'] = unserialize($data['event']['freeadults']);
		$data['event']['eligibleunits'] = unserialize($data['event']['eligibleunits']);
		$data['groups'] = unserialize($data['event']['groups']);
		$data['options'] = $regset['options'];
		$data['discounts'] = $regset['discounts'];
		$payments = $this->shared->get_reg_payments($data['reg']['id'],$unitid,TRUE,TRUE);
		if ($data['individual']) {
			// Individual payments
			$data['payments'] = $this->shared->get_reg_payments($data['reg']['id'],$data['user']->id, null, null, true);
		} else {
			// Show everyone else' payments
			$data['payments'] = $this->shared->get_reg_payments($data['reg']['id'],$unitid);
		}

		//$data['payments'] = $this->shared->get_reg_payments($data['reg']['id'],$unitid, null, null, $data['individual']);
		$fin = $this->shared->get_finances($data['event'], $data['reg'], $data['session'], $finunit, $data['options'], $data['discounts'], $payments);

		$data['fin'] = $fin['fin'];
		$data['counts'] = $fin['counts'];
		$data['cost'] = $fin['cost'];
		$data['verify'] = $this->shared->verify($data['reg']['id'],false,false,false,false,false,$fin);
		$data['rosters'] = $this->data->get_rosters(false,true,array('reg'=>$data['reg']['id'])); 
		$data['members'] = $this->data->get_members(false,false,array('unit'=>$data['unit']['id']));

		// Build the page and send some data in.
		$data['page'] = 'Registrations ‹ Event';
		$data['section'] = 'event';

		
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/event/editregistration', $data);	
		$this->load->view('templates/footer', $data);
	}

	// New Event
	public function newevent()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// Get logged in user.
		$user = $this->ion_auth->user()->row();

		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// Set page details.
		$data['page'] = 'New Event';
		$data['section'] = 'event';
		$data['first'] = $user->first_name;
		$data['last'] = $user->last_name;
		$data['email'] = $user->email;

		// If the form ran, update details. If not, fetch and display details.
		$this->form_validation->set_rules('id', 'id', 'required');
		$this->form_validation->set_rules('title', 'title', 'required');
		$this->form_validation->set_rules('datestart', 'starting date', 'required');
		
		if ($this->form_validation->run() === FALSE)
		{

			// No post data, we'll send them to the new event page.	
			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/event/new', $data);	
			$this->load->view('templates/footer', $data);
			
		}
		else
		{
			// Create the event and get the id of the event created
			$id = $this->event_model->create_event();
			
			//show_error('id = '.$id);
			redirect('event/'.$id.'/details', 'refresh');
		}
	}
	
	
	/*
	 *
	 *
	 *		Activities and Event Activities Pages
	 *
	 *		activities
	 *		activities/new
	 *		activities/$1
	 *		activities/$1/delete
	 *
	 *
	 */

	// Create a new activity
	public function new_activity()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// Starters
		$data['page'] = 'New Activity &lsaquo; Activities';
		$data['section'] = 'event';
		$data['success'] = false;
		
		// Get logged in user.
		$data['user'] = $this->ion_auth->user()->row();
		//$data['unit'] = $this->shared->get_user_unit($data['user']->id, true);	
		
		// Verify activity details.
		$this->form_validation->set_rules('activity[title]', 'name', 'required');
		$this->form_validation->set_rules('activity[description]', 'description', 'required');
		$this->form_validation->set_rules('activity[eventtype]', 'event type', 'required');
		$this->form_validation->set_rules('activity[category]', 'category', 'required');
		$this->form_validation->set_rules('activity[age]', 'minimum age');
		$this->form_validation->set_rules('activity[long]', 'long description');
		$this->form_validation->set_rules('activity[short]', 'short description');
		// add all here			
		
		if ($this->form_validation->run() === TRUE) {
			// Lets update the activity
			$result = $this->activities_model->create_activity();
			$data['success'] = true;
		}

		// Form failed or wasn't run
		$data['message'] = (validation_errors()) ? validation_errors() : null;
		$data['message'] = (is_null($data['message'])) ? $this->session->flashdata('message'): $data['message'];
		if ($data['success']) $data['message'] = '<i class="icon-ok teal"></i> Success! Your activity is ready for action. '.anchor("event/activities/".$result, 'View &rarr;', 'class="btn btn-small teal right"').' '.anchor("event/activities", 'All Activities &rarr;', 'class="btn btn-small tan right"');
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/event/newactivity', $data);	
		$this->load->view('templates/footer', $data);

	}

	// Listing of all activities or a single one
	public function activities($id=FALSE)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// Starters
		$data['page'] = 'Activities';
		$data['section'] = 'event';
		
		// Get logged in user.
		$data['user'] = $this->ion_auth->user()->row();
		//$data['unit'] = $this->shared->get_user_unit($data['user']->id, true);	
		
		if ($id === FALSE) {
			// No activity set, lets get all of them
			// The view will get the activities, by eventtype. $data['activities'] = $this->activities_model->get_activities();
			
			// Build the page and send some data in.
			$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/event/activities', $data);	
			$this->load->view('templates/footer', $data);
			
		} else {
			// Activity is set
			$data['activity'] = $this->activities_model->get_activity($id);
			if (!$data['activity']) { 
				$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> We couldn\'t find your activity.');
				redirect('event/activities', 'refresh');
			}
			$data['message'] = '';
			
			// Verify activity details.
			$this->form_validation->set_rules('activity[title]', 'name', 'required');
			$this->form_validation->set_rules('activity[description]', 'description', 'required');
			$this->form_validation->set_rules('activity[eventtype]', 'event type', 'required');
			$this->form_validation->set_rules('activity[category]', 'category', 'required');
			$this->form_validation->set_rules('activity[age]', 'minimum age');
			$this->form_validation->set_rules('activity[long]', 'long description');
			$this->form_validation->set_rules('activity[short]', 'short description');
			
			if ($this->form_validation->run() === TRUE) {
				// Lets update the user
				$result = $this->activities_model->update_activity($id);
				if ($result === true) {
					$data['errors'] = '<i class="icon-ok teal"></i> Good work, '.$data['activity']['title'].' was updated';
				} else {
					$data['errors'] = '<i class="icon-info red"></i> '.$data['activity']['title'].' was not updated';
				}
				$data['activity'] = $this->activities_model->get_activity($id);
			} 

			// Build the page and send some data in.
			$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$data['message'] = (isset($data['errors'])) ? $data['errors']: $data['message'];
			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/event/singleactivity', $data);	
			$this->load->view('templates/footer', $data);
			
		}
	}
	
	// Delete a member
	public function delete_activity($id)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
				
		// Get logged in user.
		$user = $this->ion_auth->user()->row();
		//$unit = $this->shared->get_user_unit($user->id, true);
		$activity = $this->activities_model->get_activity($id);

		// Make sure this user can modify this unit
		$result = $this->activities_model->delete_activity($id);
		if ($result) {
			$this->session->set_flashdata('message', '<i class="icon-ok teal"></i> '.$activity['title'].' has been deleted along with any linked event activities and regs');
			redirect("event/activities", 'refresh');
		}
	}

	// Single roster in a registration
	public function single_roster($event=FALSE,$reg=FALSE,$roster=FALSE)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');

		if ($event === false) {
			$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> Choose an event to manage.');
			redirect('event', 'refresh');
		}
		if ($reg === false) {
			$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> Choose an event registration to manage.');
			redirect('event/'.$event.'/registrations', 'refresh');
		}
		if ($roster === false) {
			redirect('event/'.$event.'/registrations/'.$reg, 'refresh');
		}

		// Fetch the reg
		$data['reg'] = $this->data->get_regs($reg,true);
		$data['event'] = $data['reg']['eventid'];
		$data['session'] = $data['reg']['session'];

		// Get the user or unit
		if ($data['reg']['unitid'] == 0) {
			//$data['user'] = $data['reg']['userid'];
			$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> Individual registrations do not qualify for roster and class registration at this time.');
			redirect('event/'.$data['event']['id'].'/registrations/'.$data['reg']['id'], 'refresh');
		} else {
			$data['unit'] = $data['reg']['unitid'];
		}

		// Update the Single Discounts
		$this->form_validation->set_rules('updatediscounts', 'discounts');
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		if ($this->input->post('updatediscounts') == '1') {
			$updatediscounts = $this->register_model->update_single_roster_discounts();
			if ($updatediscounts) $data['message'] = '<i class="icon-ok teal"></i> Your discounts were saved';
		}

		// Rosters enabled?
		if ($data['event']['activityregs'] == '0') {
			$this->session->set_flashdata('message', ' <i class="icon-info-sign blue"></i> Class registrations are not open for this event.');
			redirect('event/'.$data['event']['id'].'/registrations/'.$reg, 'refresh');
		} elseif ($data['reg']['roster'] == '0') {
			$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> You\'ll need to create a roster to do this. <a href="#roster">Set up a roster &rarr;</a>');
			redirect('event/'.$data['event']['id'].'/registrations/'.$reg, 'refresh');
		}

		// Some additional details
		$data['verify'] = $this->shared->verify($data['reg']['id']);
		$data['rosters'] = $this->data->get_rosters(false, true, array('reg'=>$data['reg']['id'])); 
		$data['members'] = $this->data->get_members(false, false, array('unit'=>$data['unit']['id']));
		$data['roster'] = $this->data->get_rosters($roster,true);
		$data['periods'] = $data['event']['periods'];
		$data['member'] = $data['roster']['member'];
		$data['activities'] = $this->data->get_activities(false, false, array('eventtype'=>$data['event']['eventtype']));
		$data['classes'] = $this->data->get_classes(false, false, array('event'=>$data['event']['id']),false,array('Title','ASC'));
		$data['classregs'] = $this->data->get_classregs(false, false, array('roster'=>$roster));
		$data['discounts'] = $this->data->get_discounts(false, false, array('eventid'=>$data['event']['id']));

		// See if this unit qualifies
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
		$data['qualifies'] = true; // Admin can register when the unit doesn't qualify

		// Build the page and send some data in.
		$data['page'] = $data['member']['name'].' &lsaquo; Roster &lsaquo; Events';
		$data['section'] = 'event';
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/event/singleroster', $data);	
		$this->load->view('templates/footer', $data);
	}

}


?>