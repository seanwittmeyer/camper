<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper Payments API Controller
 *
 * This controller extends the API controller, containing all of the payment
 * transaction functions. These functions should be called from javascript.
 *
 * Version 1.4.5 (2014 04 23 1530)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

use Omnipay\Omnipay;

class Api_payments extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('finance_model');
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->database();
		//$this->load->helper('url');
	}

	public function index()
	{
		print(json_encode(array('status'=>200,'response'=>'Bad Gateway. Use the POST::go() api method to make a payment.')));
		//redirect('finance', 'refresh');
	}

	// Payments demo
	public function start()
	{
		// Verify User
		if (!$this->ion_auth->logged_in()) redirect('signin', 'refresh');
		$user = $this->ion_auth->user()->row();
		$unit = ($user->company == "0") ? unserialize($user->individualdata): $this->shared->get_user_unit($user->id,true);

		// Get registration details
		$reg = $this->input->get('reg'); // This is the registration id, the event reg balance we are paying towards.
		$regtitles = $this->shared->get_reg_set_titles($reg);
		if ($regtitles['individual'] == '1') {
			$individual = true;
		} else {
			$individual = false;
		}
		// Bounce if the reg id, amount or if we can't find the reg
		if (!$this->input->get('reg') || $regtitles === FALSE) {
			$this->session->set_flashdata('message', '<i class="icon-remove red"></i> We couldn\'t start a payment for the registration you chose. No payment was made. Please go to the finances page for your registration and choose a payment option there.');
			redirect('registrations', 'refresh');
		}

		// Bounce if the amount or if we can't find the reg
		if (!$this->input->get('amount')) {
			$this->session->set_flashdata('message', '<i class="icon-remove red"></i> Uh oh, you need to specify the payment amount (at least $1). No payment was made.');
			redirect('registrations/'.$reg.'/details', 'refresh');
		}

		// Bounce if the amount is negative
		if ($this->input->get('amount') < 0) {
			$this->session->set_flashdata('message', '<i class="icon-remove red"></i> You must make a positive payment, please try again. No payment was made.');
			redirect('registrations/'.$reg.'/details', 'refresh');
		}

		// Gateway Setup
		$gateway_config = $this->config->item('camper_pay_paypal');
		$gateway = Omnipay::create('PayPal_Express');
		$gateway->setUsername($gateway_config['username']);
		$gateway->setPassword($gateway_config['password']);
		$gateway->setSignature($gateway_config['signature']);
		$gateway->setTestMode($gateway_config['test_mode']);
		$gateway->setHeaderImageUrl($gateway_config['headerurl']);
		
		/* Payment Details
		if (empty($this->input->get('amount')) || empty($this->input->get('reg')) || $this->input->get('reg') == 0) { 
			show_error('The payment failed because the amount or event registration record was not set. Please try again.'); 
		}*/
		$amount = str_replace(array('$', '%', '-', ' '), '', $this->input->get('amount'));
		$amount = number_format($amount, 2, '.', '');
		
		// Send Payment
		if ($individual) {
			$unittitle = ($unit['unittype'] == 'None') ? $user->first_name.' '.$user->last_name.' (No Unit)' : $user->first_name.' '.$user->last_name.' ('.$unit['unittype'].' '.$unit['number'].')';
		} else {
			$unittitle = (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')': $unit['unittype'].' '.$unit['number'];
		}
		$description = ($regtitles['group'] === false) ? $unittitle.' - '.$regtitles['event'].' ('.$regtitles['session'].')': $unittitle.' - '.$regtitles['event'].' ('.$regtitles['session'].', '.$regtitles['group'].')';
		$response = $gateway->purchase(array(
			'amount' => $amount, 
			'description' => $description,
			'invoice' => $regtitles['event'].', '.$regtitles['session'], 
			'returnUrl' => base_url('api/v1/pay/done'), 
			'cancelUrl' => base_url('api/v1/pay/cancelled')
		))->send();
		$token = $response->getTransactionReference();

		// Create Payment in Database
		$unitid = ($individual) ? false: $unit['id'];
		$this->finance_model->create_paypal_payment($token,$amount,$reg,$unitid,$user->id,time(),$individual);
		
		// Forward to the next step or fail gracefully with error
		if ($response->isSuccessful()) {
			// payment was successful
			$error = 'Your payment failed, please try again. fx api_payments start :70 ';
			$this->shared->error_mandrill($error,'start:70',$response);
			show_error($error);
		} elseif ($response->isRedirect()) {
			// Redirect to offsite payment gateway, this is the preferred situation
			$response->redirect();
		} else {
			// payment failed: display message to customer
			$error = 'Your payment failed, the error we have is: '.$response->getMessage().'. fx api_payments start :77 ';
			$this->shared->error_mandrill($error,'start:77',$response);
			show_error($error);
			//echo $response->getMessage();
		}
	}

	// Payments demo
	public function done()
	{
		// Verify User
		if (!$this->ion_auth->logged_in()) redirect('signin', 'refresh');

		// Gateway Setup
		$gateway_config = $this->config->item('camper_pay_paypal');
		$gateway = Omnipay::create('PayPal_Express');
		$gateway->setUsername($gateway_config['username']);
		$gateway->setPassword($gateway_config['password']);
		$gateway->setSignature($gateway_config['signature']);
		$gateway->setTestMode($gateway_config['test_mode']);
		
		// Payment Details
		$record = $this->finance_model->get_payment($this->input->get('token'));
		$amount = $record['amount'];
		
		// Complete the purchase
		$response = $gateway->completePurchase(array(
			'amount' => $amount, 
			'returnUrl' => base_url('/api/v1/pay/done'), 
			'cancelUrl' => base_url('/api/v1/pay/cancelled')
		))->send();

		// Store $ref in your database with the payment
		$ref = $response->getTransactionReference();

		if ($response->isSuccessful()) {
			// Payment Successful
			// Setup
		    $responsemsg = $response->getMessage(); 
		    $data = $response->getData(); 
		    $timestamp = $data['TIMESTAMP'];
		    $token = $data['TOKEN'];
		    $status = $data['ACK'];

			// Add request-specific fields to the request string.
			$paypaltoken = $data['TOKEN'];
			$paypalstring = "&TOKEN=$paypaltoken";
			
			// Execute the PayPal API operation; see the PPHttpPost function above.
			/* FIX THIS SOMEDAY
			$paypaldetails = $this->paypal_method_request('GetTransactionDetails', $paypalstring);
			if("SUCCESS" == strtoupper($paypaldetails["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($paypaldetails["ACK"])) {
				$awesome=true;
			} else  {
				// Notify admin of error, let it slide for now. If payments are not processing, then action will need to be taken.
				$this->shared->error_mandrill('GetExpressCheckoutDetails failed. fx api_payments done :133','done:144',array($paypaldetails,$response));
			}*/
			$paypaldetails = false;

			// Update Payment in Database
			$this->finance_model->update_paypal_payment($token,$ref,$record['id'],$data,$paypaldetails);
		
			//print_r($response);
		} elseif ($response->isRedirect()) {
			// redirect to offsite payment gateway
			$response->redirect();
		} else {
			// payment failed: display message to customer
			show_error($response->getMessage());
		}
		
		// Notify admins
		if (!isset($record['unit']) || $record['unit'] == '0') {
			$unit = $this->shared->get_user_name($record['user'], true);
		} else {
			$unit = $this->shared->get_unit_name($record['unit']);
		}

		$definitions = array('a'=>$amount,'t'=>'paypal','u'=>$unit);
		$this->shared->notify('paymentpaypal',$definitions,false,'admin');
		
		// All done, head back to event registration
		$this->session->set_flashdata('message', '<i class="icon-ok teal"></i> Thank you for your payment of $'.$amount.'! You can view your payments in the finances tab.');
		redirect('registrations/'.$record['reg'].'/details', 'refresh');

	}

	// Payments demo
	public function cancelled()
	{
		// Verify User
		if (!$this->ion_auth->logged_in()) redirect('signin', 'refresh');

		// Payment Details
		$record = $this->finance_model->get_payment($this->input->get('token'));
		$amount = $record['amount'];
		
		// Update Payment in Database
		$this->finance_model->cancel_paypal_payment($record['token'],$record['id']);
		
		// All done, head back to event registration
		$this->session->set_flashdata('message', '<i class="icon-ok teal"></i> Your payment was cancelled.');
		redirect('registrations/'.$record['reg'].'/details', 'refresh');

	}	
	
	// Cancel a payment
	public function cancel()
	{
		// Verify User
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { return show_error('You need to contact council to use this tool.'); }

		// Payment Details
		$record = $this->finance_model->get_payment($this->input->get('token'));
		
		// Update Payment in Database
		$this->finance_model->alter_payment($record['token'],$record['id'],'cancel');
		
		// All done, head back to event registration
		$this->session->set_flashdata('message', '<i class="icon-ok teal"></i> The payment was cancelled.');
		redirect('payments', 'refresh');

	}	
	
	// Approve a payment
	public function approve()
	{
		// Verify User
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { return show_error('You need to contact council to use this tool.'); }

		// Payment Details
		$record = $this->finance_model->get_payment($this->input->get('token'));
		
		// Update Payment in Database
		$this->finance_model->alter_payment($record['token'],$record['id'],'approve');
		
		// All done, head back to event registration
		$this->session->set_flashdata('message', '<i class="icon-ok teal"></i> The payment was approved.');
		if ($this->input->get('return')) {
			redirect($this->input->get('return'), 'refresh');
		} else {
			redirect('payments', 'refresh');
		}

	}	
	
	// Change a payment reg
	public function change()
	{
		// Verify User
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { return show_error('You need to contact council to use this tool.'); }

		// Payment Details
		$record = $this->finance_model->get_payment($this->input->post('token'));
    	$newrecord = false;
    	if ($this->input->post('status')) $newrecord['status'] = $this->input->post('status');
    	if ($this->input->post('reg')) $newrecord['reg'] = $this->input->post('reg');
    	if ($this->input->post('amount')) {
    		$newrecord['amount'] = str_replace(array('$', '%', ',', ' '), '', $this->input->post('amount'));
			$newrecord['amount'] = number_format($newrecord['amount'], 2, '.', '');

    	}
    	if ($this->input->post('type')) $newrecord['type'] = $this->input->post('type');
    	if ($this->input->post('checkname') || $this->input->post('checknum')) { 
    		$newrecord['details'] = unserialize($record['details']);
    		if ($this->input->post('checkname')) $newrecord['name'] = $this->input->post('checkname');
    		if ($this->input->post('checknum')) $newrecord['number'] = $this->input->post('checknum');
    		$newrecord['details'] = serialize($newrecord['details']);
    	}
    	
    	// Update Payment in Database
    	if ($newrecord === false) {
    		$this->session->set_flashdata('message', '<i class="icon-info-sign blue"></i> No change was made');
    	} else {
    		$this->finance_model->alter_payment($record['token'],$record['id'],'update',$newrecord);
    		$this->session->set_flashdata('message', '<i class="icon-ok teal"></i> Registration updated');
    	}

		// All done, head back to event registration
		if ($this->input->get('return')) {
			redirect($this->input->get('return'), 'refresh');
		} else {
			redirect('payments', 'refresh');
		}

	}	
	
	// Delete a payment
	public function delete()
	{
		// Verify User
		if (!$this->ion_auth->logged_in()) { redirect('signin', 'refresh'); } elseif (!$this->ion_auth->is_admin()) { return show_error('You need to contact council to use this tool.'); }

		// Payment Details
		$record = $this->finance_model->get_payment($this->input->get('token'));
		
		// Update Payment in Database
		$this->finance_model->alter_payment($record['token'],$record['id'],'delete');
		
		// All done, head back to event registration
		$this->session->set_flashdata('message', '<i class="icon-ok teal"></i> The payment was deleted.');
		redirect('payments', 'refresh');

		// All done, head back to event registration
		if ($this->input->get('return')) {
			redirect($this->input->get('return'), 'refresh');
		} else {
			redirect('payments', 'refresh');
		}

	}	
	
	// Create Check Payment
	public function check()
	{
		// Verify User
		if (!$this->ion_auth->logged_in()) redirect('signin', 'refresh');
		$user = $this->ion_auth->user()->row();
		if ($this->input->get('individual') == '1') {
			$unitid = false;
			$individual = true;
			$unit = ($user->individual == '1') ? unserialize($user->individualdata): false;
			$unittitle = ($unit && $unit['unittype'] == 'None') ? $user->first_name.' '.$user->last_name.' (No Unit)' : $user->first_name.' '.$user->last_name.' ('.$unit['unittype'].' '.$unit['number'].')';
		} else {
			$unit = $this->shared->get_user_unit($user->id,true);
			$unitid = $unit['id'];
			$individual = false;
			$unittitle = (isset($unit['associatedunit']) && $unit['associatedunit'] !== '0' ) ? $unit['associatedunit'].' '.$unit['associatednumber'].' ('.$unit['unittype'].' '.$unit['number'].')': $unit['unittype'].' '.$unit['number'];
		}

		if (!$this->input->get('reg') || !$this->input->get('amount') || !$this->input->get('number') || !$this->input->get('name')) {
			$this->session->set_flashdata('message', '<i class="icon-remove red"></i> Please make sure you enter the check number, amount and name on the check. Payment was not created.');
			redirect('registrations/'.$this->input->get('reg').'/details', 'refresh');
		}

		// Prep
		$this->load->helper('string');
		$token = 'CK-'.random_string('alnum', 17);
		$ref = random_string('alnum', 17);

		$amount = str_replace(array('$', '%', ' ', ','), '', $this->input->get('amount'));
		if ($amount == 0) {
			$this->session->set_flashdata('message', '<i class="icon-remove red"></i> Your payment amount was zero, please try a larger amount. Payment was not created.');
			redirect('registrations/'.$this->input->get('reg').'/details', 'refresh');
		}

		// Bounce if the amount is negative
		if ($amount < 0) {
			$this->session->set_flashdata('message', '<i class="icon-remove red"></i> Your payment amount was negative, please try a positive amount. No payment was made.');
			redirect('registrations/'.$this->input->get('reg').'/details', 'refresh');
		}

		$reg = $this->input->get('reg'); // This is the registration id, the event reg balance we are paying towards.
		$name = $this->input->get('name'); // This is the registration id, the event reg balance we are paying towards.
		$number = $this->input->get('number'); // This is the registration id, the event reg balance we are paying towards.
		
		// Create Payment in Database
		$this->finance_model->create_check_payment($token,$ref,$amount,$reg,$name,$number,$unitid,$user->id,time(),$individual);
		
		// Notify admin
		$definitions = array('a'=>$amount,'t'=>'check','u'=>$unittitle);
		$this->shared->notify('paymentpending',$definitions,false,'admin');

		
		// All done, head back to event registration
		//$this->session->set_flashdata('message', '<i class="icon-ok teal"></i> Thank you for your payment of $'.$amount.'! Your payment has been sent for verification and will show up when confirmed.');
		redirect('payments/checkform/'.$token, 'refresh');
	}



	// Send HTTP POST Request
	function paypal_method_request($methodName_, $nvpStr_) {
		/*
		 * Use his function to handle PayPal method requests that are not already handled by Omnipay
		 * 
		 * @param	string	The API method name
		 * @param	string	The POST Message fields in &name=value pair format
		 * @return	array	Parsed HTTP Response body
		 */
		$paypal_config = $this->config->item('camper_pay_paypal');
		$environment = $paypal_config['environment'];
	
		// Set up your API credentials, PayPal end point, and API version.
		$API_UserName = urlencode($paypal_config['username']);
		$API_Password = urlencode($paypal_config['password']);
		$API_Signature = urlencode($paypal_config['signature']);
		$API_Endpoint = "https://api-3t.paypal.com/nvp";
		if("sandbox" === $environment || "beta-sandbox" === $environment) {
			$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
		}
		$version = urlencode('51.0');
	
		// Set the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
	
		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
	
		// Set the API operation, version, and API signature in the request.
		$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
	
		// Set the request as a POST FIELD for curl.
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
	
		// Get response from the server.
		$httpResponse = curl_exec($ch);
	
		if(!$httpResponse) {
			exit('$methodName_ failed: '.curl_error($ch).'('.curl_errno($ch).')');
		}
	
		// Extract the response details.
		$httpResponseAr = explode("&", $httpResponse);
	
		$httpParsedResponseAr = array();
		foreach ($httpResponseAr as $i => $value) {
			$tmpAr = explode("=", $value);
			if(sizeof($tmpAr) > 1) {
				$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
			}
		}
	
		if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
			exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
		}
	
		return $httpParsedResponseAr;
	}


}


?>