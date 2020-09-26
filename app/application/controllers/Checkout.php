<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends CI_Controller {
	
	function __construct(){
		parent::__construct();
	}

	public function index()
	{
		//$token = $this->input->get('token', TRUE);
		$token = "9424f38142665c9e1c92d3475dfdd9d66315c7f4";
		$param = array('title' => 'Checkout', 'body' => 'admin/checkout', 
			'checkout_url' => 'https://sandbox.tiket.com/payment/checkout_payment?checkouttoken='.$token.'');
				
		$this->load->view('admin/checkout.php', $param);
	}
}
