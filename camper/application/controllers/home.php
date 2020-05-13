<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Home Controller
 *
 * This handles the dashboard / home for Camper 1.5+. It is a single page 
 * with an overview of all Camper elements, from registrations, events, 
 * unit, member, and payment details.
 *
 * Version 1.5 (2014 12 27 1424)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Leader extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->load->model('account_model');
	}

	public function index()
	{
		// Nothing to see here
		redirect('home', 'refresh');
	}

	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('signin?go=home', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('members') && $this->ion_auth->in_group('staff')) 
		{
			redirect('atcamp', 'refresh');
		}

		// Build the page and send some data in.
		$data['page'] = 'Dashboard';
		$data['section'] = 'dashboard';
		$data['breadcrumbs'] = anchor('home','Dashboard');
		$data['message'] = (validation_errors()) ? validation_errors() : (isset($message)) ? $message : $this->session->flashdata('message');
		$this->load->view('templates/catalunya_head', $data);
		if (!$this->ion_auth->is_admin()) {
			$this->load->view('v2/leader/general/home', $data);
		} else {
			
		}
		$this->load->view('templates/catalunya_foot', $data);
	}
}