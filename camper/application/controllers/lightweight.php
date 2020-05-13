<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * Camper API Controller
 *
 * This controller is designed to process functions without as much overhead as possible.
 *
 * Version 1.5.0 (2014 06 10 1441)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
use Knp\Snappy\Pdf;

class Lightweight extends CI_Controller { 

	public function __construct()
	{
		parent::__construct();
		// Load these in the functions
		//$this->load->model('users_model');
		//$this->load->model('api_model');
		//$this->load->model('activities_model');
	}

	// Create Single Roster PDF
	public function build_pdf($key)
	{
		// Get the PDF record stored in build
		$record = $this->db->get_where('build',array('unique'=>$key));
		$record = $record->row_array();
		$record['instructions'] = unserialize($record['instructions']);

		// Test
		//print_r($data); die;
		//print($record['payload']); die;
		
		// Make our PDF
		// Use wkhtmltopdf
		$wkpath = str_replace('/system', '', BASEPATH).'vendor/h4cc/wkhtmltopdf-'.$this->config->item('camper_wkhtmltopdf').'/bin/wkhtmltopdf-'.$this->config->item('camper_wkhtmltopdf');
		$snappy = new Pdf($wkpath);
		header('Content-Type: application/pdf');
		//header('Content-Disposition: attachment; filename="'.$record['instructions']['filename'].'.pdf"');
		$options = array(
			'page-size'		=>	'Letter',
			'zoom'			=>	'0.9',
			'orientation'	=>	$record['instructions']['orientation']
		);
		echo $snappy->getOutputFromHtml($record['payload'],$options);

		/* Use DOMPDF
		$this->load->helper(array('pdf'));
		pdf_create_html($record['payload'], $record['instructions']['filename']);
		*/
	}
	// Create Single Roster PDF
	public function build_html($key)
	{
		// Get the PDF record stored in build
		$record = $this->db->get_where('build',array('unique'=>$key));
		$record = $record->row_array();
		$record['instructions'] = unserialize($record['instructions']);

		// Test
		//print_r($data); die;
		print($record['payload']); die;
	}
}
