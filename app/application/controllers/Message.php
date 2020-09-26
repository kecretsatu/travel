<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('Message_Model');
	}

	public function index()
	{
		$data = $this->Message_Model->select();
		
		$getURL = base_url().'message/get';
		$postURL = base_url().'message/post';
		$readURL = base_url().'message/read';
		$removeURL = base_url().'message/remove';
		
		$param = array('title' => 'Message', 'body' => 'admin/message', 'form' => 'form/message', 'data' => $data, 'readURL' => $readURL, 'postURL' => $postURL, 'getURL' => $getURL, 'removeURL' => $removeURL);
				
		$this->load->view('index.php', $param);
	}
	
	public function inbox(){
		$data = $this->Message_Model->select();
		
		echo json_encode($data);
	}
	
	public function get(){
		$data = $this->Message_Model->select();
		
		echo json_encode($data);
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
	
	public function remove(){
		$data = array(
			'status'	=> -1
		);
		
		$crud = $this->input->post('crud');
		
		$id = $this->input->post('id');
		
		$id = explode(";", $id);
		for($i = 0; $i < count($id); $i++){
			$idx = $id[$i];
			$result = $this->Message_Model->update($data, $idx);
		}
		
		
		echo '[{"return":"1", "msg":"<i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Pesan berhasil dihapus", 
						"m_msg":"Pesan berhasil dihapus"}]';
						
					
		/*if($result){			
			echo '[{"return":"1", "result":'.json_encode($data[0]).', "msg":"<i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Pesan berhasil dihapus", 
						"m_msg":"Pesan berhasil dihapus"}]';
		}
		else{
			echo '[{"return":"0", "msg":"<i class=\"fa fa-warning\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;<strong>Mohon maaf !</strong> Terjadi kesalahan saat proses, Silahkan ulangi", 
					"m_msg":"Mohon maaf!. Terjadi kesalahan saat proses, Silahkan ulangi"}]';
		}*/
	}
}
