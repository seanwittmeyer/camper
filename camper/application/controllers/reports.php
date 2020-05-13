<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Reports Controller
 *
 * This controller manages pages that build reports for users and admin.
 *
 * Version 1.4.5 (2014 04 23 1530)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Reports extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->load->model('events_model');
		$this->load->model('report_model');
		$this->load->helper('url');
	}

	// Lis any reports in the system
	public function index()
	{
	
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// Get some data
		$data['user'] = $this->ion_auth->user()->row();
		$data['reports'] = $this->report_model->get_reports();
		
		// Build the page and send some data in.
		$data['page'] = 'Reports';
		$data['section'] = 'reports';
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/reports/all', $data);	
		$this->load->view('templates/footer', $data);
		
		
	}

	// Edit / view a single report
	public function view_report($reportid)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
	}

	// Create a new report 
	public function new_report()
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');
		
		// Get some data
		$data['user'] = $this->ion_auth->user()->row();
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$data['step'] = ($this->input->post('step')) ? $this->input->post('step'): 1;
		
		if ($data['step'] > 1) {
		// Step 2
			$data['report']['title'] = ($this->input->post('title') == '') ? 'My Report': $this->input->post('title');

			/* someday, we will store definitions in the database. Not now.
			$data['report']['source'] = $this->data->get_report_definitions(false,false,array('title' => $this->input->post('source')));
			if ($data['report']['source'] == false) {
				// The input base is invalid or not in our database so we don't know what to do. We will simply reset the 
				$data['step'] = 1;
				$data['message'] = 'We could not find any data for the source you set, try one in the dropdown below.';
			} else {
			*/

			$data['step'] = 2;
			$data['report']['payload'] = array();
			$source = ($this->input->post('source') == '') ? 'regs_full': $this->input->post('source');
			$selection = ($this->input->post('columns')) ? $this->input->post('columns'): false;
			$where = ($this->input->post('where')) ? $this->input->post('where'): false;
			
			
			switch ($source) :
				// Registrations
				case 'regs_full':
					$source = array();
					$source['slug'] = 'regs_full';
					$source['title'] = 'Registrations';
					$source['data'] = array();
					$source['data']['columns'] = array(
						'regid' 			=> 'Registration Camper ID',
						'unitid' 			=> 'Unit Camper ID',
						'unittype' 			=> 'Unit Type',
						'number' 			=> 'Unit Number',
						'district' 			=> 'District',
						'council' 			=> 'Council',
						'address' 			=> 'Address',
						'city' 				=> 'City',
						'state' 			=> 'State',
						'zip' 				=> 'Zip',
						'associatedunit' 	=> 'Associated Unit',
						'event'				=> 'Event',
						'eventtype' 		=> 'Event Type',
						'session' 			=> 'Session',
						'youth' 			=> 'Youth',
						'family' 			=> 'Family',
						'male' 				=> 'Male',
						'total' 			=> 'Total',
						'female' 			=> 'Female',
						'registerdate' 		=> 'Registration Date',
						'individual' 		=> 'Individual Registration',
					);
					$source['data']['where'] = array(
						//'unitid' 			=> 'Unit ID',
						//'unittype' 			=> 'Unit Type',
						//'district' 			=> 'District',
						//'council' 			=> 'Council',
						//'city' 				=> 'City',
						//'state' 			=> 'State',
						'event'				=> 'Event',
						//'eventtype' 		=> 'Event Type',
						'session' 			=> 'Session',
						//'individual' 		=> 'Individual Registration',
					);
					$data['preview'] = ($selection) ? $this->report_model->get_regs_full($selection, $where): false;
					$data['source'] = $source;
					break;
				// ad infinitum, we'll add others here later.
			endswitch;
			
			// deliver results to the interface. should already be done with data[preview,source, and the post data]
		} // endif step > 1
		if ($data['step'] > 2) {
		// Step 2
		
			// save to database
		
		} // endif > 2

		// Build the page and send some data in.
		$data['page'] = 'Reports';
		$data['section'] = 'reports';

		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/reports/new', $data);	
		$this->load->view('templates/footer', $data);
		
	}

}
?>