<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('Order_Model');
		$this->load->model('Registrant_Model');
	}

	public function index()
	{
		$data = null;
		
		$getURL = base_url().'order/get';
		$postURL = base_url().'order/post';
		$viewURL = base_url().'order/view';
		$removeURL = base_url().'order/remove';
		
		$param = array('title' => 'Order', 'body' => 'admin/order', 'data' => $data, 'viewURL' => $viewURL, 'postURL' => $postURL, 'getURL' => $getURL, 'removeURL' => $removeURL);
				
		$this->load->view('index.php', $param);
	}
	
	public function get(){
		$data = $this->Order_Model->select();
		
		echo json_encode($data);
	}

	public function view()
	{
		$order_id	= $this->input->get('order_id');
		$type		= $this->input->get('type');
		$token		= $this->input->get('token');
		$email		= "";
		
		$data = $this->Order_Model->get($order_id, $type);
		
		if($data[0]["email"] != ''){
			$email = $data[0]["email"];
			$data[0]["registrant"] = $this->Registrant_Model->select($data[0]["email"]);
		}
		else{
			$data[0]["registrant"] = array();
		}
		
		$param = array('title' => 'Order', 'body' => 'admin/order/view.php', 'data' => $data, 'token' => $token, 'order_id' => $order_id, 'email' => $email);
				
		$this->load->view('index.php', $param);
	}
	
	public function read(){
		$data = array(
			'status'	=> 2
		);
		
		$crud = $this->input->post('crud');
		
		$id = $this->input->post('id');
		$result = $this->Message_Model->update($data, $id);
		
		if($result){			
			echo '[{"return":"1", "id":"'.$id.'"}]';
		}
		else{
			echo '[{"return":"0", "id":"'.$id.'"}]';
		}
	}
	
	public function post(){
		$email = $this->input->post('email');
		$data = array(
			'title'	 			=> $this->input->post('title'),
			'full_name'			=> $this->input->post('full_name'),
			'email'		 		=> $email,
			'phone'		 		=> $this->input->post('phone'),
			'subyek'			=> $this->input->post('subyek'),
			'message'			=> $this->input->post('message'),
			'status'			=> 1
		);
		
		$crud = $this->input->post('crud');
		
		$result = null;
		if($crud == "add"){
			$isExist = true;
			
			//$data['id'] = 1;
			$result = $this->Message_Model->insert($data);
		}		
		else if($crud == "edit"){
			$id = $this->input->post('id');
			$result = $this->Message_Model->update($data, $id);
		}
		
		
		if($result){
			$data = $this->Message_Model->select($email);
			
			echo '[{"return":"1", "result":'.json_encode($data[0]).', "msg":"<i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Simpan data berhasil", 
						"m_msg":"Simpan data berhasil"}]';
		}
		else{
			echo '[{"return":"0", "msg":"<i class=\"fa fa-warning\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;<strong>Mohon maaf !</strong> Terjadi kesalahan saat proses, Silahkan ulangi", 
					"m_msg":"Mohon maaf!. Terjadi kesalahan saat proses, Silahkan ulangi"}]';
		}
	}
	
	public function request(){
		$token = $this->input->post('token');
		$order_id	= $this->input->post('order_id');
		$email	= $this->input->post('email');
		
		$post = [
			'token' => $token
		];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.tiket.com/order?token=".$token."&output=json");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		
		$this->load->helper('file');
		$abc = $response;

		if ( !write_file("kepet.txt", $abc)){
			 echo 'Unable to write the file';
		}
		
		$isOrder = 1;
		
		$response = json_decode($response);
		if(isset($response->diagnostic->error_msgs)){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://api.tiket.com/check_order?token=".$token."&email=".$email."&order_id=".$order_id."&output=json");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			
			$this->load->helper('file');
			$abc = $response;
			
			
			if ( !write_file("kepet2.txt", $abc)){
				 echo 'Unable to write the file';
			}
			
			$isOrder = 2;
			$response = json_decode($response);
		}
		
		$param = array("data" => $response, "isOrder" => $isOrder);
		
		$this->load->view('admin/order/booking.php', $param);
	}
	
	public function remove(){
		$order_id	= $this->input->post('order_id');
		
		$flight = $this->Order_Model->get($order_id, "flight");
		foreach($flight as $f){
			$order_detail_id = $f["order_detail_id"];
			$token = $f["token"];
			$u = "https://api.tiket.com/order/delete_order?order_detail_id=" . $order_detail_id . "&token=" . $token . "&output=json";
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $u);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			
			$this->load->helper('file');

			if ( !write_file($order_detail_id . ".txt", $u)){
				 echo 'Unable to write the file';
			}
		}
		$hotel = $this->Order_Model->get($order_id, "hotel");
		foreach($hotel as $h){
			$order_detail_id = $h["order_detail_id"];
			$token = $f["token"];
			$u = "https://api.tiket.com/order/delete_order?order_detail_id=" . $order_detail_id . "&token=" . $token . "&output=json";
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $u);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			
			$this->load->helper('file');

			if ( !write_file($order_detail_id . ".txt", $u)){
				 echo 'Unable to write the file';
			}
		}
		
		$q = "update order_flight set status = -1 where order_id = '".$order_id."'";
		if($this->Order_Model->execute($q)){ 
			$q = "update order_hotel set status = -1 where order_id = '".$order_id."'";
			$this->Order_Model->execute($q);
			echo '[{"status":"1"}]';
		}
		else{
			echo '[{"status":"0"}]';
		}
	}
}
