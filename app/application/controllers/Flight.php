<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Flight extends CI_Controller {
	
	function __construct(){
		parent::__construct();
	}

	public function index()
	{
		if (!is_dir('application/views/admin/api')) {
			mkdir('application/views/admin/api', 0777, TRUE);

		}
		if (!is_dir('application/views/admin/api/flight')) {
			mkdir('application/views/admin/api/flight', 0777, TRUE);

		}
		$param = array('title' => 'Dashboard', 'body' => 'admin/api/flight', 'airport' => $this->airport());
				
		$this->load->view('index.php', $param);
	}
	
	public function request(){
		$token = "976f1ca4b96213d7c0044ba0db981ee44c3a22cb";
		$departure = $this->input->post('departure');
		$arrival = $this->input->post('arrival');
		$date = date_create($this->input->post('date'));
		$date = date_format($date,"Y-m-d");
		$adult = $this->input->post('adult');
		$child = $this->input->post('child');
		$infant = $this->input->post('infant');
		
		$post = [
			'token' => $token,
			'd' => $departure,
			'a' => $arrival,
			'date' => $date,
			'adult' => $adult,
			'child' => $child,
			'infant' => $infant,
		];
		
		$url = "http://tiketravel.co.id/app/Modul/get_flight";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $post );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		
		$this->load->helper('file');
		$abc = $response;

		if ( !write_file("kepet.txt", $abc)){
			 echo 'Unable to write the file';
		}
		
		$param = array("data" => $response);
		
		$this->load->view('admin/api/flight/view.php', $param);
	}
	
	public function airport(){
		$token = "976f1ca4b96213d7c0044ba0db981ee44c3a22cb";
		
		$post = [
			'token' => $token
		];
		
		$url = "http://tiketravel.co.id/app/Modul/get_airport";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $post );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		
		$this->load->helper('file');
		$abc = $response;

		if ( !write_file("kepetx.txt", $abc)){
			 echo 'Unable to write the file';
		}
		
		return json_decode($response);
	}
}
