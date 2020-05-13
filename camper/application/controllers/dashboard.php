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

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('account_model');
		$this->load->model('users_model');
	}

	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('signin?go=dashboard', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('members') && $this->ion_auth->in_group('staff')) 
		{
			redirect('atcamp', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) //remove this elseif if you want to enable this for non-admins
		{
			// Get logged in user.
			$singleuser = $this->ion_auth->user()->row();

			// Error Handling
			$data['unit'] = $this->shared->get_user_unit($singleuser->id);	
	
			// Get unit details
			if (isset($data['unit']['primary']) && $data['unit']['primary'] !== 0) { $data['primary'] = $this->account_model->get_user_by_id($data['unit']['primary'], true); } else { $data['primary'] = false; }
			if (isset($data['unit']['alt']) && $data['unit']['alt'] !== 0) { $data['alternate'] = $this->account_model->get_user_by_id($data['unit']['alt'], true); } else { $data['alternate'] = false; }
			
			if ($data['alternate'] && !$data['primary']) {
				// We have an alternate but no primary, lets swap this
				$result = $this->users_model->alt_to_pri($data['unit']); 
				if ($result) { 
					$message = 'We\'ve made you the primary contact! You need to invite someone to be an alternate contact in order to register for events.'; 
				} else { 
					show_error('Your unit has been misconfigured. Reload the page to fix this! Thanks.');
				}
			}
			$data['userunit'] = false;

			// Build the page and send some data in.
			$data['page'] = 'Dashboard';
			$data['section'] = 'dashboard';

			$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			
			if ($this->input->get('beta') == '1') { $this->load->view('templates/header', $data); } else { $this->load->view('templates/header_leader', $data); }
			$this->load->view('leader/account/dashboard', $data);
			$this->load->view('templates/footer', $data);
		}
		else
		{
			// Get logged in user.
			$singleuser = $this->ion_auth->user()->row();
			
			// Setup Notifications
			$data['notifications'] = $this->shared->notifications();

	
			// Build the page and send some data in.
			$data['page'] = 'Dashboard';
			$data['section'] = 'dashboard';

			$data['first'] = $singleuser->first_name;
			$data['last'] = $singleuser->last_name;
			$data['phone'] = $singleuser->phone;
			$data['email'] = $singleuser->email;
			
			$data['message'] = (validation_errors()) ? validation_errors() : (isset($message)) ? $message : $this->session->flashdata('message');
	
			$data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $singleuser->id
			);



			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/dashboard/temp', $data);
			$this->load->view('templates/footer', $data);
		}		
	}
}


?>