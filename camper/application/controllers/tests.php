<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Tests Controller
 *
 * This is a test suite.
 *
 * Version 1.4.5 (2014 04 23 1530)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Tests extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->load->model('account_model');
		//$this->load->model('activities_model');
		//$this->load->model('users_model');
	}

	public function index()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// Get logged in user.
		//$user = $this->ion_auth->user()->row();
		$data=array();
		$data['reg'] = $this->data->get_regs_full(303,true);
		$data['rosters'] = $this->data->get_rosters(false,true,array('reg'=>303));
		
		//print_r($data); die;
		//show_error('The test suite is disabled.');
		$this->load->view('pdf/reg_rosters', $data);
		
		//$result = $this->data->get_payments_full(false, true, false, false, 'date desc');
		//print_r($result); die;
		
	}
}


?>