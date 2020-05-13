<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Payments Controller
 *
 * This is the controller that handles account changes made by the active user.
 *
 * Version 1.4.5 (2014 04 23 1530)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Finance extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('finance_model');
	}
	
	// Placeholder
	public function index()
	{
		// Check if we are allowed to be here
		//$this->shared->check_auth('admin');
	}
	
	// Admin listing of all payments in the system
	public function adminlanding()
	{
		// Check if logged in the old fashioned way
		if (!$this->ion_auth->logged_in()) redirect('signin?go=payments', 'refresh');

		// Set page details.
		$user = $this->ion_auth->user()->row();
		$data['user'] = $user;
		$data['page'] = 'Payments';
		$data['section'] = 'payments';
		$data['title'] = 'Payments';
		$__offset = ($this->input->get('offset')) ? $this->input->get('offset'): 0;

		if ($this->ion_auth->is_admin()) { 
			// Get the list of events
			$__time = time()-63000000;
			$data['payments'] = $this->data->get_payments_full(false, true, false, false, array('date','desc'), array(500,$__offset));
			
			// Show our page
			$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/payments/payments', $data);
			$this->load->view('templates/footer', $data);

		} else {
			// Get the list of events for your unit
			if ($user->company == '0') {
				// Get the payments for the user and their unit, if any
				$data['payments'] = $this->data->get_payments_full(false, true, array('individual'=>$user->id), false, false, 'date desc');
			} else {
				// Get our unit id
				$where = false;
				$orwhere = array("unit"=>$user->company,"user"=>$user->id,"individual"=>$user->id);

				// Get the payments for the user and their unit, if any
				$data['payments'] = array();
				$pf = $this->data->get_payments_full(false, true, array("unit"=>$user->company), false, false, 'date desc');
				if ($pf) : foreach ($pf as $k=>$p) $data['payments'][$k] = $p; endif;
				$pf = $this->data->get_payments_full(false, true, array("user"=>$user->id), false, false, 'date desc');
				if ($pf) : foreach ($pf as $k=>$p) $data['payments'][$k] = $p; endif;
				$pf = $this->data->get_payments_full(false, true, array("individual"=>$user->id), false, false, 'date desc');
				if ($pf) : foreach ($pf as $k=>$p) $data['payments'][$k] = $p; endif;
			}
			
			// Show our page
			$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$data['breadcrumbs'] = anchor('payments','Payments');
			$this->load->view('templates/catalunya_head', $data);
			$this->load->view('v2/leader/payments', $data);	
			$this->load->view('templates/catalunya_foot', $data);
		}
	}
	
	// Printable form to mail in with a check payment
	public function checkform($token)
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
		} else {
			// Grab our unit.
			$data['unit'] = $this->shared->get_user_unit($data['user']->id,true);
		}

		// Set page details.
		$data['page'] = 'Payment Form';
		$data['title'] = 'Payment Form';
		$data['section'] = 'payments';
		$data['payment'] = $this->shared->get_payment($token);
		$data['regtitles'] = $this->shared->get_reg_set_titles($data['payment']['reg']);
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			
		// Show our page
		if ($this->ion_auth->is_admin()) { 
			$this->load->view('templates/header_admin', $data);
		} else {
			// Get the list of events for your unit
			$this->load->view('templates/header_leader', $data);
		}
		$this->load->view('leader/payments/checkform', $data);	
		$this->load->view('templates/footer', $data);
	}
	
	// Payment editor
	public function details($token)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin','payments');

		// Get logged in user.
	    $record = $this->finance_model->get_payment($token);
		$messages = false;
		
		$data['individual'] = ($record['individual'] == 0) ? false: true;
		
		// Grab our regs and unit
		if ($data['individual']) { 
			// Get our user's unit details
			$data['user'] = $this->ion_auth->user($record['individual'])->row();
			$data['unit'] = unserialize($data['user']->individualdata);
		} else {
			// Grab our unit.
			$data['unit'] = $this->shared->get_units($record['unit']);
		}

		// Lets update if the form went through
		$this->form_validation->set_rules('token', 'token', 'required');
			
		if ($this->form_validation->run() === TRUE) {
			// Valid Request
	    	$newrecord = false;
	    	if ($this->input->post('notes')) $newrecord['notes'] = $this->input->post('notes');
	    	if ($this->input->post('status')) $newrecord['status'] = $this->input->post('status');
	    	if ($this->input->post('amount')) {
	    		$newrecord['amount'] = str_replace(array('$', '%', ',', ' '), '', $this->input->post('amount'));
				$newrecord['amount'] = number_format($newrecord['amount'], 2, '.', '');
	
	    	}
	    	if ($this->input->post('type')) $newrecord['type'] = $this->input->post('type');
	    	if ($this->input->post('checkname') || $this->input->post('checknum')) { 
	    		$newrecord['details'] = unserialize($record['details']);
	    		if ($this->input->post('checkname')) $newrecord['details']['name'] = $this->input->post('checkname');
	    		if ($this->input->post('checknum')) $newrecord['details']['number'] = $this->input->post('checknum');
	    		$newrecord['details'] = serialize($newrecord['details']);
	    	}
	    	
	    	// Update Payment in Database
	    	if ($newrecord === false) {
	    		$messages = '<i class="icon-info-sign blue"></i> No change was made';
	    	} else {
	    		$this->finance_model->alter_payment($record['token'],$record['id'],'update',$newrecord);
	    		$messages = '<i class="icon-ok teal"></i> Registration updated';
				$record = $this->finance_model->get_payment($record['token']);
	    	}
	    }


		// Set page details.
		$data['page'] = 'Edit Payment';
		$data['title'] = 'Edit Payment';
		$data['section'] = 'payments';
		$data['payment'] = $record;
		$data['regtitles'] = $this->shared->get_reg_set_titles($data['payment']['reg']);
		$data['message'] = ($messages === false) ? (validation_errors()) ? validation_errors() : $this->session->flashdata('message') : $messages;

		$data['csrf'] = $this->shared->get_csrf_nonce();


		// Show our page
		if ($this->ion_auth->is_admin()) { 
			$this->load->view('templates/header_admin', $data);
		} else {
			// Get the list of events for your unit
			$this->load->view('templates/header_leader', $data);
		}
		$this->load->view('admin/payments/detail', $data);	
		$this->load->view('templates/footer', $data);
	}
	
	// Admin listing of all payments in the system
	public function adminnew()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh');	} elseif (!$this->ion_auth->is_admin()) { redirect('payments', 'refresh'); }
		else
		{
			// Set page details.
			$data['page'] = 'New Payment';
			$data['title'] = 'New Payment';
			$data['section'] = 'payments';
	
			// If the form ran, update details. If not, fetch and display details.
			$this->form_validation->set_rules('regid', 'registration id', 'required');
			$this->form_validation->set_rules('type', 'type', 'required');
			$this->form_validation->set_rules('status', 'status', 'required');
			$this->form_validation->set_rules('amount', 'amount', 'required');
			$this->form_validation->set_rules('date', 'date', 'required');
			
			if ($this->form_validation->run() === FALSE)
			{
				// Show our page
				$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			}
			else
			{
				// Create the event and get the id of the event created
				$id = $this->finance_model->create_admin_payment();
				$data['message'] = 'Great success! Your payment has been added.';
				if ($this->input->post('return')) { 
					$this->session->set_flashdata('message', $data['message']);
					redirect($this->input->post('return'), 'refresh');
				}
			}
			
			// Send back to the new page
			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/payments/new', $data);	
			$this->load->view('templates/footer', $data);
		}
	}
}


?>