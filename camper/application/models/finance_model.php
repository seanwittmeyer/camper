<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Finance Model
 *
 * This model contains functions that are used in multiple parts of the site allowing
 * a single spot for them instead of having duplicate functions all over.
 *
 * Version 1.0 (2012.10.18.0017)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

class Finance_model extends CI_Model {

	public function __construct()
	{
		$this->load->library('mandrill');
	}

	// Get payments for...
	public function get_all_payments($unit=FALSE,$user=FALSE)
	{
		$this->db->order_by('date', 'desc');
		if ($user) {
			$query = $this->db->get_where('payments', array('individual' => $unit));
		} elseif ($unit === FALSE) {
			$query = $this->db->get_where('payments');
		} else {
			$query = $this->db->get_where('payments', array('unit' => $unit));
		}
		return $query->result_array();
	}

	/* Get payments for any user and/or any unit
	 * This will combine the two arrays if both are provided.
	 * $user = user id
	 * $unit = unit id
	 * returns array of payments, sorted by date, descending (latest at top)
	 */
	public function get_user_payments($user=FALSE,$unit=FALSE)
	{
		$this->db->order_by('date', 'desc');
		$userpayments = array();
		$unitpayments = array();
		
		if ($user && $user !== "0") {
			$query = $this->db->get_where('payments', array('individual' => $user));
			$userpayments = $query->result_array();
		} 
		if ($unit && $unit !== "0") {
			$query = $this->db->get_where('payments', array('unit' => $unit));
			$unitpayments = $query->result_array();
		}
		
		$result = array_merge($userpayments, $unitpayments);

		return $result;
	}


	// Get payments for...
	// Also in shared
	public function get_reg_payments($reg, $unit=NULL, $onlyconfirmed=NULL, $onlysum=NULL, $individual=FALSE)
	{
		// Setup
		$this->db->order_by('date', 'desc');
		$where['reg'] = $reg;
		if (isset($onlysum)) $this->db->select_sum('amount');
		if ($individual === false) {
			if (isset($unit)) $where['user'] = $unit;
		} else {
			if (isset($unit)) $where['unit'] = $unit;
		}
		if (isset($onlyconfirmed)) $where['status'] = 'Completed';
		// Get our result
		$query = $this->db->get_where('payments', $where);

		return $query->result_array();
	}


	// Get a single payment...
	public function get_payment($token=null,$ref=null,$id=null)
	{
		//$this->db->select('token,content,live');
		if (isset($ref)) {
			$field = 'ref'; $key = $ref;
		} elseif (isset($token)) {
			$field = 'token'; $key = $token;
		} elseif (isset($id)) {
			$field = 'id'; $key = $id;
		} else {
			return false;
		}
		$query = $this->db->get_where('payments', array($field => $key));
		$record = $query->row_array();
		return $record;
	}
	
	// Create a payment record (the financial transaction happens with omnipay)
	public function create_paypal_payment($token,$amount,$reg,$unit=FALSE,$user,$time,$individual=FALSE)
	{		
		// Verify input
		if ($individual) { 
			$unitflag = false; 
		} else { 
			$unitflag = ($unit === false) ? true: false; 
		}		
		if (empty($token) || empty($amount) || $amount == 0 || empty($reg) ||  $reg == 0 || $unitflag || empty($user) || !is_int($time)) {
			$error = 'The payment could not be completed because there were missing details. fx finance_model create_paypal_payment :60 ';
			$this->shared->error_mandrill($error,'create_paypal_payment:60',array($token,$amount,$reg,$unit,$user,$time));
			show_error($error);
		}

		// Create our token
		$ref = 'temp_'.md5(microtime().random_string('alnum', 4));
		$record = array(
			'type'			=> 'paypal',
			'ref'			=> $ref,
			'amount'		=> number_format($amount, 2, '.', ''),
			'token'			=> $token,
			'reg'			=> $reg,
			'user'			=> $user,
			'individual'	=> ($individual === FALSE) ? 0: $user,
			'unit'			=> ($individual === FALSE) ? $unit: 0,
			'date'			=> $time,
			'refundid'		=> null,
			'comment'		=> 'This payment is in progress.'
		);
		$this->db->insert('payments', $record);
		return true;
	}

	// Create a payment record (the financial transaction happens with omnipay)
	public function create_check_payment($token,$ref,$amount,$reg,$name,$number,$unit=FALSE,$user,$time,$individual=FALSE)
	{		
		// Verify input
		if ($individual) { 
			$unitflag = false; 
		} else { 
			$unitflag = ($unit === false) ? true: false; 
		}		
		if (empty($token) || empty($amount) || $amount == 0 || empty($reg) ||  $reg == 0 || $unitflag || empty($user) || !is_int($time)) {
			$error = 'The payment could not be completed because there were missing details. fx finance_model create_check_payment :113 ';
			$this->shared->error_mandrill($error,'create_check_payment:60',array($token,$amount,$reg,$unit,$user,$time));
			show_error($error);
		}
		$amount = str_replace(array('$', '%', '-', ' '), '', $amount);
		$amount = number_format($amount, 2, '.', '');
		$details = array(
			'name' => $name,
			'number' => $number,
			'amount' => $amount,
			'status' => 'Pending',
			'created' => $time,
			'verifiedadmin' => null,
			'rejectreason' => null,
		);

		// Create our token
		$record = array(
			'type'			=> 'check',
			'ref'			=> $ref,
			'token'			=> $token,
			'amount'		=> number_format($amount, 2, '.', ''),
			'reg'			=> $reg,
			'user'			=> $user,
			'individual'	=> ($individual === FALSE) ? 0: $user,
			'unit'			=> ($individual === FALSE) ? $unit: 0,
			'date'			=> $time,
			'notes'			=> $this->input->post('notes'),
			'details'		=> serialize($details),
			'refundid'		=> null,
			'comment'		=> 'Payment awaiting confirmation.'
		);
		$this->db->insert('payments', $record);
		return true;
	}

	// Create a payment record by admin
	public function create_admin_payment()
	{		
		// Start with some details
		$date = ($this->input->post('date') == date('F d, Y')) ? time() : strtotime($this->input->post('date'));
		$details = array(
			'amount' => $this->input->post('amount'),
			'status' => $this->input->post('status'),
			'created' => $date,
			'verifiedadmin' => null,
			'rejectreason' => null,
		);
		if ($this->input->post('checkname')) $details['name'] = $this->input->post('checkname');
		if ($this->input->post('checknum')) $details['number'] = $this->input->post('checknum');
		if ($this->input->post('type') == 'refund' || $this->input->post('type') == 'transfer') {
			$amount = str_replace(array('$', '%', '-', ' '), '', $this->input->post('amount'));
			$amount = number_format("-$amount", 2, '.', '');
		} else {
			$amount = str_replace(array('$', '%', '-', ' '), '', $this->input->post('amount'));
			$amount = number_format($amount, 2, '.', '');
		}
		
		$this->load->helper('string');
		$user = $this->ion_auth->user()->row();
		$regset = $this->shared->get_reg_set_ids($this->input->post('regid'));

		// Create our token
		$record = array(
			'type'			=> $this->input->post('type'),
			'ref'			=> random_string('alnum', 17),
			'token'			=> 'AD-'.random_string('alnum', 17),
			'amount'		=> $amount,
			'reg'			=> $this->input->post('regid'),
			'user'			=> $user->id,
			'individual'	=> ($regset['individual'] === FALSE) ? 0: $regset['userid'],
			'unit'			=> ($regset['individual'] === FALSE) ? $regset['unit']: 0,
			'date'			=> $date,
			'notes'			=> $this->input->post('notes'),
			'details'		=> serialize($details),
			'status'		=> $this->input->post('status'),
			'refundid'		=> null,
			'comment'		=> 'Payment added by Council'
		);
		$this->db->insert('payments', $record);
		return true;
	}

	// Update a payment record (the financial transaction happens with omnipay)
	public function update_paypal_payment($token,$ref,$id,$details,$raw)
	{		
		// Verify input
		if (empty($ref)) {
			$error = 'The payment could not be updated because there were missing details. fx finance_model update_paypal_payment :180 ';
			$this->shared->error_mandrill($error,'update_paypal_payment:189',array($token,$id,$details,$raw));
			show_error($error);
		}

		// Get the record
		$query = $this->db->get_where('payments', array('token' => $token));
		$record = $query->row_array();

		// Prep the updates
		$record = array(
			'ref'		=> $ref,
			'token'		=> $details['TOKEN'],
			'amount'	=> $details['PAYMENTINFO_0_AMT'],
			'status'	=> $details['PAYMENTINFO_0_PAYMENTSTATUS'],
			'date'		=> strtotime($details['TIMESTAMP']),
			'details'	=> serialize($details),
			'raw'		=> serialize($raw),
			'comment'	=> $details['ACK']
		);
		$this->db->where('token', $token);
		$this->db->update('payments', $record);
		return true;
	}

	// Mark payment as cancelled (user or paypal cancelled the payment at paypal)
	public function cancel_paypal_payment($token,$id)
	{		
		// Get the record
		$query = $this->db->get_where('payments', array('token' => $token));
		$record = $query->row_array();

		// Prep the updates
		$record = array(
			'ref'		=> str_replace('temp_', '', $record['ref']),
			'status'	=> 'Cancelled',
			'comment'	=> 'The payment was cancelled.'
		);
		$this->db->where('token', $token);
		$this->db->update('payments', $record);
		return true;
	}

	// Mark payment as cancelled (user or paypal cancelled the payment at paypal)
	public function alter_payment($token,$id,$action,$values=false)
	{		
		// Get the record
		$query = $this->db->get_where('payments', array('token' => $token));
		$record = $query->row_array();
		
		// Take action
		switch ($action) {
			case 'cancel':
				// Mark our payment as cancelled
				$newrecord = array(
					'ref'		=> str_replace('temp_', '', $record['ref']),
					'status'	=> 'Cancelled',
					'comment'	=> 'The payment was cancelled by Council.'
				);
				$this->db->where('token', $token);
				$this->db->update('payments', $newrecord);
				
				return true;
			break;
			case 'approve':
				// Mark our payment as cancelled
				$newrecord = array(
					'status'	=> 'Completed',
					'comment'	=> 'Manual payment verified by Council.'
				);
				$this->db->where('token', $token);
				$this->db->update('payments', $newrecord);

				$event = $this->shared->get_reg_set_titles($record['reg']);
				$definitions = array('a'=>$record['amount'],'e'=>$event['event']);
				if ($event['individual']) {
					$this->shared->notify('paymentapproved',$definitions,false,$event['user']);
				} else {
					$this->shared->notify('paymentapproved',$definitions,false,$this->shared->get_unit_leader($record['unit'],'primary'));
					$alt = $this->shared->get_unit_leader($record['unit'],'alternate'); 
					if ($alt['first_name']) $this->shared->notify('paymentapproved',$definitions,false,$this->shared->get_unit_leader($record['unit'],'alternate'));
				}

				return true;
			break;
			case 'delete':
				// Remove the payment from the database
				$this->db->delete('payments', array('id' => $record['id'],'token' => $record['token']));

				$event = $this->shared->get_reg_set_titles($record['reg']);
				$definitions = array('a'=>$record['amount'],'e'=>$event['event']);

				if ($event['individual']) {
					$this->shared->notify('paymentdeleted',$definitions,false,$event['user']);
				} else {
					$this->shared->notify('paymentdeleted',$definitions,false,$this->shared->get_unit_leader($record['unit'],'primary'));
					$alt = $this->shared->get_unit_leader($record['unit'],'alternate'); 
					if ($alt['first_name']) $this->shared->notify('paymentdeleted',$definitions,false,$this->shared->get_unit_leader($record['unit'],'alternate'));
				}

				return true;
			break;
			case 'update':
				// Update the payment
				if ($values === false) return false;
				$values['comment'] = 'Payment modified by Council.';
				$this->db->where('token', $token);
				$this->db->update('payments', $values);

				$event = $this->shared->get_reg_set_titles($record['reg']);
				$definitions = array('t'=>$record['type'],'e'=>$event['event']);
				if ($event['individual']) {
					$this->shared->notify('paymentupdated',$definitions,false,$event['user']);
				} else {
					$this->shared->notify('paymentupdated',$definitions,false,$this->shared->get_unit_leader($record['unit'],'primary'));
					$alt = $this->shared->get_unit_leader($record['unit'],'alternate'); 
					if ($alt['first_name']) $this->shared->notify('paymentupdated',$definitions,false,$this->shared->get_unit_leader($record['unit'],'alternate'));
				}

				return true;
			break;
			default:
				return false;
			break;
		}
	}

	// Delete a payment...
	public function delete_payment($token)
	{
		$this->db->delete('payments', array('token' => $token));
		return true;
	}


}
?>