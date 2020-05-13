<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper API Controller
 *
 * This is the controller that handles all of the API calls. It handles calls 
 * authenticated by key, session user, or the public..
 *
 * Version 1.4.5 (2014 04 23 1530)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->load->model('users_model');
		$this->load->model('api_model');
		$this->load->model('activities_model');
	}

	public function index()
	{
		redirect('api/help', 'refresh');
	}

	public function help()
	{
		// Show our page
		redirect('api', 'refresh');
	}
	
	// List API
	public function showlist($list)
	{
		// Show the list requested
		$this->load->view('api/list/'.$list); 
	}

	// View an Email Notification
	public function viewemail($token)
	{
		// Show the list requested
		$data = $this->shared->get_transient($token);
		if ($data === false) show_404();
		$data['email'] = unserialize($data['content']);
		$this->load->view('notifications/viewemail', $data);	
			
	}

	// Delete Invite Transient
	public function delete_invite()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } 
		// Show the list requested
		if ($this->input->get('t')) {
			$result['result'] = $this->shared->delete_transient($this->input->get('t'));
		} else {
			$result['result'] = false;	
		}
		if ($result['result']) { 
			$this->output->set_status_header('200');
			print json_encode($result);
		} else {
			$this->output->set_status_header('304');
			print json_encode($result);
		}
	}

	// Resend Invite
	public function resend_invite()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } 
		// Show the list requested
		if ($this->input->get('t')) {
			$result['result'] = $this->shared->resend_invite($this->input->get('t'));
		} else {
			$result['result'] = false;	
		}
		if ($result['result']) { 
			$this->output->set_status_header('200');
			print json_encode($result);
		} else {
			$this->output->set_status_header('304');
			print json_encode($result);
		}
	}

	// Inactivate Reg
	public function deactivate_reg()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { $this->output->set_status_header('403'); print json_encode(array('result'=>false)); die; 
		} else {
			// Inactivate the reg
			if ($this->input->get('reg')) {
				$result['result'] = $this->api_model->deactivate_reg($this->input->get('reg'));
			} else {
				$result['result'] = false;	
			}

			// Return JSON or to location
			if ($this->input->get('return') && $this->input->get('return') !== '') {
				redirect($this->input->get('return'), 'refresh');
			} else {
				$statusheader = ($result['result']) ? '200': '304';
				$this->output->set_status_header($statusheader);
				print json_encode($result);
			}
		}
	}

	// Inactivate Reg
	public function activate_reg()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { $this->output->set_status_header('403'); print json_encode(array('result'=>false)); die; 
		} else {
			// Close the event
			if ($this->input->get('reg')) {
				$result['result'] = $this->api_model->activate_reg($this->input->get('reg'));
			} else {
				$result['result'] = false;	
			}

			// Return JSON or to location
			if ($this->input->get('return') && $this->input->get('return') !== '') {
				redirect($this->input->get('return'), 'refresh');
			} else {
				$statusheader = ($result['result']) ? '200': '304';
				$this->output->set_status_header($statusheader);
				print json_encode($result);
			}
		}
	}

	// Close Event
	public function close_event()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { $this->output->set_status_header('403'); print json_encode(array('result'=>false)); die; 
		} else {
			// Close the event
			if ($this->input->get('e')) {
				$result['result'] = $this->api_model->close_event($this->input->get('e'));
			} else {
				$result['result'] = false;	
			}

			// Return JSON or to location
			if ($this->input->get('return') && $this->input->get('return') !== '') {
				redirect($this->input->get('return'), 'refresh');
			} else {
				$statusheader = ($result['result']) ? '200': '304';
				$this->output->set_status_header($statusheader);
				print json_encode($result);
			}
		}
	}

	// Open Event
	public function open_event()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { $this->output->set_status_header('403'); print json_encode(array('result'=>false)); die; 
		} else {
			// Close the event
			if ($this->input->get('e')) {
				$result['result'] = $this->api_model->open_event($this->input->get('e'));
			} else {
				$result['result'] = false;	
			}

			// Return JSON or to location
			if ($this->input->get('return') && $this->input->get('return') !== '') {
				redirect($this->input->get('return'), 'refresh');
			} else {
				$statusheader = ($result['result']) ? '200': '304';
				$this->output->set_status_header($statusheader);
				print json_encode($result);
			}
		}
	}

	// Delete a reg with dependencies
	public function delete_reg()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { $this->output->set_status_header('403'); print json_encode(array('result'=>false)); die; 
		} else {
			// Do it.
			$result = $this->shared->delete_registration($this->input->get('reg'));
			// Return JSON or to location
			if ($this->input->get('return') && $this->input->get('return') !== '') {
				$this->session->set_flashdata('message', 'The registration, it\'s roster, and all classes and payments have been deleted');
				redirect($this->input->get('return'), 'refresh');
			} else {
				$statusheader = ($result) ? '200': '304';
				$this->output->set_status_header($statusheader);
				print json_encode($result);
			}
		}
	}

	// Open Event
	public function register()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { $this->output->set_status_header('403'); print json_encode(array('result'=>false)); die; 
		} else {
			$this->load->model('register_model');

			// Close the event
			if ($this->input->get('unit') && $this->input->get('session') && $this->input->get('event')) {
				$result['id'] = $this->register_model->register($this->input->get('unit'), $this->input->get('event'), $this->input->get('session'), $this->ion_auth->user()->row()->id, $redirect=FALSE, $admin=TRUE, $customtime=FALSE, $this->input->get('group'), $this->input->get('individual'));
				$result['result'] = true;
			} else {
				$result['id'] = false;	
				$result['result'] = false;
			}

			// Return JSON or to location
			if ($this->input->get('return') && $this->input->get('return') !== '') {
				$this->session->set_flashdata('message', 'Registration success!');
				redirect($this->input->get('return'), 'refresh');
			} else {
				$statusheader = ($result['result']) ? '200': '304';
				$this->output->set_status_header($statusheader);
				print json_encode($result);
			}
		}
	}

	// Register an unit or individual for an event
	public function register_for_session($type='unit', $unitid, $sessionid, $group=FALSE)
	{
		// Check if we are allowed to be here
		$this->shared->check_auth('admin');

		// Setup
		$user = $this->ion_auth->user()->row();
		$this->load->model('register_model');

		// Setup things for individual versus unit
		if ($type == 'individual') {
			$individual = true;
			$individualid = $unitid;
			$unitid = 0;
		} else {
			$userid = $unitid;
			$individual = false;
			$individualid = false;
		}

    	// Register our new unit for the session, if set
   		$id = $this->register_model->register($unitid, $this->shared->get_session_event($sessionid), $sessionid, $user->id, false, true, time(), $group, $individual, $individualid);

		if ($this->input->get('return') && $this->input->get('return') !== '') {
			redirect($this->input->get('return'), 'refresh');
		} else {
			$this->output->set_status_header(200);
			print json_encode(array('result'=>true));
		}
	}

	// Export Class Regs as csv
	public function export_classregs($id, $type='reg') 
	{
		$user = $this->ion_auth->user()->row();

		// if reg
		if ($type == 'reg') {
			$data['reg'] = $this->data->get_regs($id,true);
			if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('staff') && $user->company !== $data['reg']['unitid']['id']) show_error('You are not allowed to view this report.');
			$data['rosters'] = $this->data->get_rosters(false,true,array('reg'=>$id));
			$this->load->view('csv/reg_classregs', $data);
		}
	}

	// Create Single Roster PDF
	public function build_single_roster($rosterid, $output='pdf', $include=FALSE)
	{
		// Check if we are allowed to be here
		//$this->shared->check_auth('members');

		// Fetch some data
		$user = $this->ion_auth->user()->row();
		$data['roster'] = $this->data->get_rosters_full($rosterid, true);

		// Authorized to be here?
		if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('staff') && $user->company !== $data['roster']['unit']['id']) show_error('You are not allowed to view this report.');

		$data['classes'] = $this->data->get_classregs_full(false, true, array('roster'=>$rosterid));

		if ($include == 'invoice') {
			$data['verify'] = $this->shared->verify($data['roster']['reg']['id']);
			$data['fin'] = $data['verify']['source']['fin'];
		}

		// Setup the html
		$html = $this->load->view('pdf/roster_schedule', $data, true);
		
		// Test
		//print_r($data); die;
		//print($html); die;
		
		// Make our PDF
		$this->load->helper(array('pdf', 'file'));
		pdf_create_html($html, strtolower(str_replace(' ', '_', $data['roster']['member']['name'])));
	}

	// Create PDF Report of Allergies and Dietary Restrictions
	public function build_conditions_report($session)
	{
		// Fetch some data
		//$user = $this->ion_auth->user()->row();
		if (!$this->ion_auth->logged_in()) { redirect('signin?go='.uri_string()); }
		if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('staff')) show_error('You are not allowed to view this report.');

		// Get the data
		$session = $this->data->get_sessions($session,true);
		$data['regs'] = $this->data->get_regs(false,true,array('session'=>$session['id']));

		// Get the regs & build the content array for the pdf
		$data['session'] = $session;
		$data['event'] = $session['eventid'];

		// Setup the html
		$html = $this->load->view('pdf/report_conditions', $data, true);

		// Make our PDF
		//print_r($html); die;
		$this->load->helper(array('pdf', 'file'));
		pdf_create_html($html, 'conditions_'.strtolower(str_replace(' ', '_', $data['session']['nicetitle'])));
	}

	// Create PDF Report of Allergies and Dietary Restrictions
	public function build_classroster($session)
	{
		//$user = $this->ion_auth->user()->row();
		if (!$this->ion_auth->logged_in()) { redirect('signin?go='.uri_string()); }
		if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('staff')) show_error('You are not allowed to view this report.');

		// Get the data
		$session = $this->data->get_sessions($session,true);
		$data['classes'] = $this->data->get_classes(false,true,array('event'=>$session['eventid']['id']));

		// Get the regs & build the content array for the pdf
		$data['session'] = $session;
		$data['event'] = $session['eventid'];

		// Setup the html
		$html = $this->load->view('pdf/classrosters', $data, true);

		//$this->load->helper(array('pdf'));
		//pdf_create_html($html, 'classrosters_'.strtolower(str_replace(' ', '_', $data['session']['nicetitle'])));

		$instructions['filename'] = 'classrosters_'.strtolower(str_replace(' ', '_', $data['session']['nicetitle']));
		$instructions['orientation'] = 'Landscape';
		$this->shared->save_build($html, $instructions, 'pdf');
		show_error('pdf build failed in build_classrosters('.$session['id'].')');

		// Make our PDF
		print_r($html); die;
	}

	// Create Check-in Form PDF
	public function build_checkin_form($id, $type="reg")
	{
		// Fetch some data
		//$user = $this->ion_auth->user()->row();
		if (!$this->ion_auth->logged_in()) { redirect('signin?go='.uri_string()); }
		if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('staff')) show_error('You are not allowed to view this report.');

		// Get the data
		if ($type == 'session') {
			$data['session'] = $this->data->get_sessions($id,true);
			$data['event'] = $this->data->get_events($data['session']['eventid']['id'],true);
			$data['regs'] = $this->data->get_regs(false,true,array('session'=>$data['session']['id']));
			$instructions['filename'] = 'checkin_'.strtolower(str_replace(' ', '_', $data['session']['nicetitle']));
			$instructions['orientation'] = 'Portrait';
			$output = 'pdf';
		} else {
			$data['reg'] = $this->data->get_regs($id,true);
			$data['session'] = $this->data->get_sessions($data['reg']['session']['id'],true);
			$data['event'] = $this->data->get_events($data['session']['eventid']['id'],true);
			$data['regs'][] = $data['reg'];
			$instructions['filename'] = 'checkin_'.strtolower(str_replace(' ', '_', $data['reg']['unitid']['unittitle']));
			$instructions['orientation'] = 'Portrait';
			$output = 'pdf';
		}

		// Setup the html
		$html = $this->load->view('pdf/checkin', $data, true);

		// Make our PDF
		//print_r($html); die;

		$this->shared->save_build($html, $instructions, $output);
		show_error('pdf build failed in build_checkin('.$id['id'].')');
	}

	// Create PDF Report of Preorders
	public function build_preorders_report($session,$all=FALSE)
	{
		// Fetch some data
		//$user = $this->ion_auth->user()->row();
		if (!$this->ion_auth->logged_in()) { redirect('signin?go='.uri_string()); }
		if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('staff')) show_error('You are not allowed to view this report.');

		// Get the data
		$session = $this->data->get_sessions($session,true);
		$data['regs'] = $this->data->get_regs(false,true,array('session'=>$session['id']));

		// Get the regs & build the content array for the pdf
		$data['session'] = $session;
		$data['event'] = $session['eventid'];
		$data['loadall'] = ($all === false) ? false: true;

		// Setup the html
		$html = $this->load->view('pdf/report_preorders', $data, true);

		// Make our PDF
		//print_r($html); die;
		$output = 'pdf';
		$instructions['filename'] = 'preorders_'.strtolower(str_replace(' ', '_', $data['session']['nicetitle']));
		$instructions['orientation'] = 'Portrait';

		$this->shared->save_build($html, $instructions, $output);
		show_error('pdf build failed in build_preorders_report('.$id['id'].')');

		
		$this->load->helper(array('pdf', 'file'));
		pdf_create_html($html, 'preorders_'.strtolower(str_replace(' ', '_', $data['session']['nicetitle'])));
	}

	// Create Single Roster PDF
	public function build_birthdays_report($session)
	{
		// Fetch some data
		$user = $this->ion_auth->user()->row();
		if (!$this->ion_auth->logged_in()) { redirect('signin?go='.uri_string()); }
		if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('staff')) show_error('You are not allowed to view this report.');

		// Get the data
		$session = $this->data->get_sessions($session,true);
		$end = false;
		if (empty($session['datestart'])) {
			if (empty($session['eventid']['dateend'])) {
				$start = $session['datestart'];
			} else {
				$start = $session['eventid']['datestart'];
				$end = $session['eventid']['dateend'];
			} 
		} else {
			if (empty($session['dateend'])) {
				$start = $session['datestart'];
			} else {
				$start = $session['datestart'];
				$end = $session['dateend'];
			} 
		} 
		if ($start < $end) {
			$i = $start;
			$days[] = date('Fd',$end);
			while ($i <= $end) {
				$days[] = date('Fd',$i);
				$i++;
			}
		}
		
		$regs = $this->data->get_regs(false,false,array('session'=>$session['id']));
		$birthdays = array();
		if (!empty($regs)) {
			foreach ($regs as $reg) {
				$rosters = $this->data->get_rosters(false,true,array('reg'=>$reg['id']));
				if (!empty($rosters)) {
					foreach ($rosters as $r=>$roster) {
						if (in_array(date('Fd',$roster['member']['dob']), $days)) $birthdays[$r] = $roster;
					}
				}
			}
		}

		// Get the regs & build the content array for the pdf
		$data['session'] = $session;
		$data['event'] = $session['eventid'];
		$data['rosters'] = $birthdays;

		// Setup the html
		$html = $this->load->view('pdf/report_birthdays', $data, true);

		// Make our PDF
		//print_r($html); die;
		$this->load->helper(array('pdf', 'file'));
		pdf_create_html($html, 'birthdays_'.strtolower(str_replace(' ', '_', $data['session']['nicetitle'])));
	}

	// Create Single Roster PDF
	public function build_unit_roster($reg)
	{
		// Fetch some data
		$user = $this->ion_auth->user()->row();
		if (!$this->ion_auth->logged_in()) { redirect('signin?go='.uri_string()); }

		// Build our classregs where statement based on permissions and request
		$reg = $this->data->get_regs($reg,true);
		if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('staff') && $user->company !== $reg['unitid']['id']) show_error('You are not allowed to view this report.');

		// Get the regs & build the content array for the pdf
		$data['reg'] = $this->data->get_regs_full($reg['id'],true);
		$data['rosters'] = $this->data->get_rosters(false,true,array('reg'=>$reg['id']));

		// Setup the html
		$html = $this->load->view('pdf/reg_rosters', $data, true);

		// Make our PDF
		//print_r($content); die;
		$this->load->helper(array('pdf', 'file'));
		pdf_create_html($html, 'roster_'.strtolower(str_replace(' ', '_', $reg['unitid']['unittitle'])));
	}

	// Create Single Roster PDF
	public function build_blue_cards($type,$id,$altid=0)
	{
		// Fetch some data
		$user = $this->ion_auth->user()->row();
		if (!$this->ion_auth->logged_in()) { redirect('signin?go='.uri_string()); }
		$counselor = false;
		$include = false;

		// Build our classregs where statement based on permissions and request
		switch ($type) :
			case 'reg':
				$reg = $this->data->get_regs($id,true);
				//print_r($reg);die;
				if ($reg['eventid']['bluecards'] == 0 || $reg['bluecards'] == 0) show_error(($reg['eventid']['bluecards'] == 0) ? 'Blue cards are not enabled for this event': 'You need to enable blue cards for this registration.');
				if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('staff') && $user->company !== $reg['unitid']['id']) show_error('You are not allowed to view this report.');
				$where = array('reg'=>$id);
				if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('staff')) {
					$counselor = $this->input->post('counselor');
					$completed = $this->input->post('complated');
					$exclude = $this->input->post('exclude');
					$all = false;
				}
			break;
			case 'activity': 
				if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('staff')) show_error('You are not allowed to view this report.');
				$where = array('activity'=>$id,'session'=>$altid);
				$counselor = $this->input->post('counselor');
				$completed = $this->input->post('completed');
				$exclude = $this->input->post('exclude');
				$all = true;
			break;
			case 'class':
				if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('staff')) show_error('You are not allowed to view this report.');
				$where = array('class'=>$id,'session'=>$altid);
				$counselor = $this->input->post('counselor');
				$completed = $this->input->post('completed');
				$include = $this->input->post('include');
				$all = true;
			break;
			case 'roster':
				$roster = $this->data->get_rosters($id,true);
				if (!$this->ion_auth->is_admin() && !$this->ion_auth->in_group('staff') && $user->company !== $reg['unitid']['id']) show_error('You are not allowed to view this report.');

			break;
			default: show_error('Unsupported request, please go back and try again. fx build_blue_cards'); 
		endswitch;

		// Get the regs & build the content array for the pdf
		$classregs = $this->data->get_classregs_full(false, true, $where);
		//print_r($classregs); die;
		
		// REPLACE THIS WITH DATEPICKER IMPUT FROM FORM
		
		//$date = (isset($counselor)) ? strtotime($counselor['date']): false;
		$date = time(); // TEMP FIX
		
		$content = array();
		if ($classregs): foreach ($classregs as $c) :
			if ($all === false) {
				if ($c['reg']['bluecards'] == 0 || $c['event']['bluecards'] == 0 || $c['activity']['meritbadge'] == 0) continue;
			}
			if ($include !== false && !isset($include[$c['id']])) continue;
			$content[] = array(
				'name' 			=> $c['member']['name'],
				'address' 		=> (empty($c['member']['citystate'])) ? '' : $c['member']['address'],
				'city' 			=> (empty($c['member']['citystate'])) ? '' : $c['member']['citystate'],
				'unittype' 		=> $c['unit']['unittype'],
				'number' 		=> $c['unit']['number'],
				'district' 		=> $c['unit']['district'],
				'council' 		=> $c['unit']['council'],
				'meritbadge' 	=> $c['activity']['title'],
				'cdm' 			=> ($date) ? date('M', $date): '',
				'cdd' 			=> ($date) ? date('j', $date): '',
				'cdy' 			=> ($date) ? date('y', $date): '',
				'cname' 		=> ($completed && isset($completed[$c['id']]) && isset($counselor['name'])) ? $counselor['name']: '',
				'caddress' 		=> ($completed && isset($completed[$c['id']]) && isset($counselor['address'])) ? $counselor['address']: '',
				'ccity' 		=> ($completed && isset($completed[$c['id']]) && isset($counselor['city'])) ? $counselor['city']: '',
				'cphone' 		=> ($completed && isset($completed[$c['id']]) && isset($counselor['phone'])) ? $counselor['phone']: '',
				'remarks' 		=> ($completed && isset($completed[$c['id']]) && isset($counselor['remarks'])) ? $counselor['remarks']: '',
				'completed'		=> ($completed && isset($completed[$c['id']])) ? true: false
			);
		endforeach; endif;

		// Make our PDF
		//print_r($content); die;
		$this->load->helper(array('pdf', 'file'));
		pdf_create_bluecard($content,'bluecards');
	}

	// Mark notifications read
	public function read_notifications()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } 
		// Show the list requested
		if ($this->input->get('n')) {
			$result['result'] = $this->shared->read_single_notification($this->input->get('n'));
		} else {
			$result['result'] = $this->shared->read_notifications();	
		}
		if ($result['result']) { 
			$this->output->set_status_header('200');
			print json_encode($result);
		} else {
			$this->output->set_status_header('304');
			print json_encode($result);
		}
	}

	// Send notifications
	public function notify($who,$what,$batch=false,$email=false)
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } 
		// Show the list requested
		if ($this->input->get('n')) {
			$result['result'] = $this->shared->read_single_notification($this->input->get('n'));
		} else {
			$result['result'] = $this->shared->read_notifications();	
		}
		if ($result['result']) { 
			$this->output->set_status_header('200');
			print json_encode($result);
		} else {
			$this->output->set_status_header('304');
			print json_encode($result);
		}
	}

	// Feedback form
	public function feedback()
	{
		// Validate the form so we aren't wasting anyone's time
		$this->load->library('form_validation');
		$this->form_validation->set_rules('doing', 'what you were doing', 'required');
		$this->form_validation->set_rules('wrong', 'that went wrong', 'required');
		$go = $this->shared->start_mandrill();

		if ($this->form_validation->run() == TRUE)
		{
			// Lets make an email
			$data['uri'] = $this->input->post('uri');
			$data['doing'] = $this->input->post('doing');
			$data['wrong'] = $this->input->post('wrong');
			$html = $this->load->view('notifications/feedback', $data, true);
			$email = array(
				'html' => $html, //Consider using a view file
				'text' => 'We received some feedback for Camper. Someone browsing '.$this->input->post('uri').' said, "'.$this->input->post('doing').'" and what went wrong was, "'.$this->input->post('wrong').'". That\'s all we know. Cheers, Camper.',
				'subject' => 'Camper Feedback!',
				'from_email' => 'camper@camperapp.org',
				'from_name' => 'Camper Website',
				'to' => array(array('email' => 'sean@zilifone.net' )) //Check documentation for more details on this one
				);
			if ($go) $this->shared->go_mandrill($email);
			$this->output->set_status_header('200');
			print json_encode(array('result'=>true));
			die;
		}
		$this->output->set_status_header('304');
		print json_encode(array('result'=>false));
	}

	// Delete Roster Member
	public function delete_roster()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } 

		$this->load->model('activities_model');
		
		// Delete our class
		if ($this->input->get('m') && $this->input->get('m') !== '' && $this->input->get('r') && $this->input->get('r') !== '')
		{
			// Valid request
			$this->activities_model->delete_roster($this->input->get('r'),$this->input->get('m'));	
			$result['result'] = true;
			$message = ' <i class="icon-ok teal"></i> Your member has been removed from the roster.';
		} else {
			$result['result'] = false;
			$message = ' <i class="icon-remove red"></i> Delete member failed.';
		}
		// Return JSON or to location
		if ($this->input->get('return') && $this->input->get('return') !== '') {
			$this->session->set_flashdata('message', $message);
			redirect($this->input->get('return'), 'refresh');
		} else {
			$statusheader = ($result['result']) ? '200': '304';
			$this->output->set_status_header($statusheader);
			print json_encode($result);
		}
 	}

	// Delete Class
	public function delete_class()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { $this->output->set_status_header('403'); print json_encode(array('result'=>false)); die; }

		$this->load->model('activities_model');
		
		// Delete our class
		if ($this->input->get('class') && $this->input->get('class') !== '')
		{
			// Valid request
			$this->activities_model->delete_class($this->input->get('class'));	
			$this->output->set_status_header('200');
			print json_encode(array('result'=>true));
		} else {
			$this->output->set_status_header('304');
			print json_encode(array('result'=>false));
		}
 	}

	// Update Class Regs
	public function update_class_regs()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); }

		// Delete our class
		if ($this->input->post('classes') && $this->input->post('roster'))
		{
			// Valid request
			$updatedone = $this->activities_model->update_class_regs();	
			$result['result'] = true;
			$message = ' <i class="icon-ok teal"></i> Classes have been updated.';
		} elseif ($this->input->post('roster'))
		{
			// Valid request, no classes
			$updatedone = $this->activities_model->update_class_regs();	
			$result['result'] = true;
			$message = ' <i class="icon-ok teal"></i> The schedule has been cleared.';
		} else {
			$result['result'] = false;
			$message = ' <i class="icon-remove red"></i> Class update failed, please try it again.';
		}
		// Return JSON or to location
		if ($this->input->get('return') && $this->input->get('return') !== '') {
			$this->session->set_flashdata('message', $message);
			redirect($this->input->get('return'), 'refresh');
		} else {
			$statusheader = ($result['result']) ? '200': '304';
			$this->output->set_status_header($statusheader);
			print json_encode($result);
		}
 	}

	// Create Class
	public function edit_class()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { $this->output->set_status_header('403'); print json_encode(array('result'=>false)); die; }

		$this->load->library('form_validation');
		$this->load->model('activities_model');
		$this->form_validation->set_rules('edit', 'class details', 'required');
		
		// Create our class
		if ($this->form_validation->run() == TRUE)
		{
			// Valid request
			$result['result'] = $this->activities_model->edit_class();	
		}

		if ($result['result'] === false) { 
			$this->output->set_status_header('304');
			print json_encode(array('result'=>false));
		} else {
			$this->output->set_status_header('200');
			print json_encode($result);
		}
	}

	// Edit Class
	public function create_class()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { $this->output->set_status_header('403'); print json_encode(array('result'=>false)); die; }

		$this->load->library('form_validation');
		$this->load->model('activities_model');
		$this->form_validation->set_rules('new', 'new class details', 'required');
		
		// Create our class
		if ($this->form_validation->run() == TRUE)
		{
			// Valid request
			$result['result'] = $this->activities_model->create_class();	
		}
		if ($result['result'] === false) { 
			$this->output->set_status_header('304');
			print json_encode(array('result'=>false));
		} else {
			$this->output->set_status_header('200');
			print json_encode($result);
		}
	}

	// Get Class
	public function single_class($class=FALSE)
	{
		if ($class)
		{
			// Valid request
			$query = $this->db->get_where('classes',array('id' => $class));
			$query = $query->row_array();
			if (!empty($query)) { 
				$this->output->set_status_header('200');
				$query['blocks'] = unserialize($query['blocks']);
				print json_encode($query);
			} else {
				$this->output->set_status_header('404');
				print json_encode(array('result'=>'Could not find class'));
			}
		} else {
			$this->output->set_status_header('403');
			print json_encode(array('result'=>false));
		}
	}

	// Get Roster Discounts
	public function single_discount($rosterid=NULL)
	{
		if (is_null($rosterid))
		{
			$this->output->set_status_header('403');
			print json_encode(array('result'=>'Invalid roster ID.'));
		} else {
			// Valid request
			$query = $this->db->get_where('roster',array('id' => $rosterid));
			$query = $query->row_array();
			if (!empty($query) ) { 
				$query = unserialize($query['discounts']);
				if (is_array($query)) {
					$object = array();
					foreach ($query as $k => $q) {
						$object[] = array('key'=>$k,'val'=>$q);
					}
					$this->output->set_status_header('200');
					print json_encode($object);
				} else {
					$this->output->set_status_header('201');
					print json_encode(array('result'=>'New Record'));
				}
			} else {
				$this->output->set_status_header('404');
				print json_encode(array('result'=>'No such roster.'));
			}
		}
	}

	// List of users, accessible to administrators 
	public function users()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { $this->output->set_status_header('403'); print json_encode(array('result'=>false)); die; 
		} else {
			$data['source'] = $this->api_model->get_unit_leaders();			
			$this->load->view('api/data/users', $data);	
		} 
	}

	// List of units
	public function units()
	{
		$data['source'] = $this->api_model->get_units();	
		$data['value'] = false;
		if ($this->input->get('return') == 'number') $data['value'] = 'num';	
		$this->load->view('api/data/units', $data);	
	}

	// List of categories
	public function categories()
	{
		$this->load->model('activities_model');
		$data['source'] = $this->activities_model->get_activities(false,'category');	
		$this->load->view('api/data/categories', $data);	
	}

	// List of events without sessions, limits, regs, or costs
	public function events()
	{
		$data['source'] = $this->api_model->get_events();	
		$data['value'] = false;
		$this->load->view('api/data/events', $data);	
	}

	// List of registrations for payment search, accessible to administrators
	public function regs()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { $this->output->set_status_header('403'); print json_encode(array('result'=>false)); die; 
		} else {
			$units = $this->api_model->get_units();
			$events = $this->api_model->get_events();
			$sessions = $this->api_model->get_sessions();
			$data['units'] = array();
			$data['events'] = array();
			$data['sessions'] = array();
			foreach ($units as $u) { $data['units'][$u['id']] = $u; }
			foreach ($events as $e) { 
				if (isset($e['groups'])) $e['groups'] = unserialize($e['groups']);
				$data['events'][$e['id']] = $e; 
			}
			foreach ($sessions as $s) { $data['sessions'][$s['id']] = $s; }
			$data['regs'] = (is_numeric($this->input->post('event'))) ? $this->api_model->get_regs($this->input->post('event')): $this->api_model->get_regs();
			$data['value'] = false;
			if ($this->input->get('return') == 'number') $data['value'] = 'num';	
			$this->load->view('api/data/regs', $data);	
		}
	}

	// List of registrations and finances as a csv export
	public function regs_finances()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { $this->output->set_status_header('403'); print json_encode(array('result'=>false)); die; 
		} else {
			$where = false;
			if ($this->input->get('event')) $where = array('eventid' => $this->input->get('event'));
			if ($this->input->get('session')) $where = array('sessionid' => $this->input->get('session'));
			$data['regs'] = $this->data->get_regs_full(false,true,$where);
			$this->load->view('api/data/regs_finances', $data);	
		}
	}

	// List of preorder finances collected by camper as a csv export
	public function preorders()
	{
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { $this->output->set_status_header('403'); print json_encode(array('result'=>false)); die; 
		} else {
			// Get Regs with preorders enabled
			$where = array('activitypreorders' => 1);
			if ($this->input->get('event')) $where = array('eventid' => $this->input->get('event'), 'activitypreorders' => 1);
			//if ($this->input->get('session')) $where = array('sessionid' => $this->input->get('session'), 'activitypreorders' => 1); // not yet
			$data['regs'] = $this->data->get_regs_full(false,true,$where);
			
			// Get classes with costs
			$where = array('preorder >' => 0);
			if ($this->input->get('event')) $where = array('event' => $this->input->get('event'), 'preorder >' => 0);
			$data['classes'] = $this->data->get_classes(false,false,$where);

			// Send to view
			$this->load->view('api/data/preorders', $data);	
		}
	}

	// Session listing including event data, numbers, and more
	public function sessions()
	{
		// Get and add events to an associative array
		$events = $this->api_model->get_events();
		$data['events'] = array();
		foreach ($events as $e) { $data['events'][$e['id']] = $e; }

		// Get and add sessions to an associative array
		$sessions = $this->api_model->get_sessions();
		$data['sessions'] = array();
		foreach ($sessions as $s) { $data['sessions'][$s['id']] = $s; }
		
		//$data['value'] = false;
		//if ($this->input->get('return') == 'number') $data['value'] = 'num';	
		
		$this->load->view('api/data/sessions', $data);	
	}

	// List of activities.
	public function activities()
	{
		// Get the activities
		if ($this->input->get('type')) {
			$type = str_replace("_", " ", $this->input->get('type'));
			$this->db->where('eventtype', $type);
		}
		$this->db->order_by('title asc');
		$query = $this->db->get('activities');
		
		// Return normal
		$data['activities'] = $query->result_array();
		
		$this->load->view('api/data/activities', $data);	
	}

	// HTML view of events for public pages.
	public function eventsview($type=false)
	{
		$this->load->model('register_model');
		
		// Grab our events and unit
		$this->shared->recount();
		
		// Handle Type
		if ($type === false) {
			$data['events'] = $this->register_model->get_events();
		} else {
			$type = str_replace("_", " ", $type);
			$data['events'] = $this->register_model->get_events($type,true);
		}

		// Build the page and send some data in.
		$data['page'] = 'Events';
		$data['section'] = 'events';

		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->load->view('api/views/events', $data);	
	}

	// HTML view of one event for public pages.
	public function eventview($event)
	{
		$this->load->model('register_model');
		
		// Grab our events and unit
		$this->shared->recount();
		
		$data['event'] = $this->register_model->get_single_event($event);
		// Build the page and send some data in.
		$data['page'] = 'Event';
		$data['section'] = 'events';

		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->load->view('api/views/event', $data);	
	}

	public function search($type)
	{

		/*
		if (!$this->ion_auth->logged_in()) {
			redirect('signin', 'refresh');
		} elseif (!$this->ion_auth->is_admin()) {
			return show_error('You must be an administrator to view this page.');
		} else {
		*/
		if ($type == 'leaders') {
			$data['source'] = $this->api_model->get_unit_leaders();			
			$this->load->view('api/js', $data);	
		} elseif ($type == 'units') {
			$data['source'] = $this->api_model->get_units();
			$this->load->view('api/js', $data);	
		}
	/*	} */
			
	}

	public function edit()
	{
		if (!$this->ion_auth->logged_in())
		{
			redirect('signin', 'refresh');
		}
		
		// Get logged in user.
		$user = $this->ion_auth->user()->row();

		// Grab new details, prepare and update user.
		$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone Number', 'required|xss_clean');
		//$this->form_validation->set_rules('company', 'Unit', 'required|xss_clean');
		//$this->form_validation->set_rules('groups', 'Account Type', 'xss_clean');
		
		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($user->id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}

			$newdata = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'phone'      => $this->input->post('phone'),
			);

			//Update the groups user belongs to
			$groupData = $this->input->post('groups');

			if (isset($groupData) && !empty($groupData)) {

				$this->ion_auth->remove_from_group('', $id);

				foreach ($groupData as $grp) {
					$this->ion_auth->add_to_group($grp, $id);
				}

			}


			if ($this->form_validation->run() === TRUE)
			{
				$this->ion_auth->update($user->id, $newdata);

				//check to see if we are creating the user
				//redirect them back to the admin page
				$this->session->set_flashdata('message', "User Saved");
				redirect("me", 'refresh');
			}
		}


		//set the flash data error message if there is one
		$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		$this->session->set_flashdata('message', $data['message']);
		redirect("me", 'refresh');
		
		
	}
	
	
	function activate($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("users", 'refresh');
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	//deactivate the user
	function deactivate($id = NULL)
	{
		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();
			// Build the page and send some data in.
			$data['page'] = 'Deactivate User';
			$data['section'] = 'users';

			$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$data['title'] = 'Deactivate User';
			$this->load->view('templates/header_admin', $data);
			$this->load->view('admin/users/deactivate', $this->data);
			$this->load->view('templates/footer', $data);

		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			//redirect them back to the auth page
			redirect('users', 'refresh');
		}
	}
	
	function edituser($id)
	{
		$data['title'] = "Edit User";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('users', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		//validate form input
		$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone Number', 'required|xss_clean');
		$this->form_validation->set_rules('unit', 'Unit', 'required|xss_clean');
		$this->form_validation->set_rules('groups', 'User Type', 'xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}

			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('unit'),
				'phone'      => $this->input->post('phone'),
			);

			//Update the groups user belongs to
			$groupData = $this->input->post('groups');

			if (isset($groupData) && !empty($groupData)) {

				$this->ion_auth->remove_from_group('', $id);

				foreach ($groupData as $grp) {
					$this->ion_auth->add_to_group($grp, $id);
				}

			}

			//update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

				$data['password'] = $this->input->post('password');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$this->ion_auth->update($user->id, $data);

				//check to see if we are creating the user
				//redirect them back to the admin page
				$this->session->set_flashdata('message', $user->first_name.' '.$user->last_name.' Updated');
				redirect("users", 'refresh');
			}
		}

		//display the edit user form
		$data['csrf'] = $this->_get_csrf_nonce();

		//set the flash data error message if there is one
		$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$data['user'] = $user;
		$data['groups'] = $groups;
		$data['currentGroups'] = $currentGroups;

		$data['first_name'] = $this->form_validation->set_value('first_name', $user->first_name);
		$data['last_name'] = $this->form_validation->set_value('last_name', $user->last_name);
		$data['company'] = $this->form_validation->set_value('company', $user->company);
		$data['phone'] = $this->form_validation->set_value('phone', $user->phone);

		$data['title'] = 'Edit User';
		$data['page'] = 'Edit User';
		$data['section'] = 'users';
		
		$this->load->view('templates/header_admin', $data);
		$this->load->view('admin/users/edituser', $data);
		$this->load->view('templates/footer', $data);
	}
	
	// This will get the report and rerun with the latest details from the database, outputting it in the requesrted format
	public function run_report($output='csv',$id)
	{
		// Get our report
		
		// Run the get_data
		
		// Format our master array
		
		// Update the last run report in the database
		
		// Pass on to the CSV view
		
	}

	// This will get the report and rerun with the latest details from the database, outputting it in the requesrted format
	public function view_report($output='csv',$id)
	{
		// Get our report
		
		// Pass on to the CSV view
		
	}
	
	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function _render_page($view, $data=null, $render=false)
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $render);

		if (!$render) return $view_html;
	}
	
}


?>