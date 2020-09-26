<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registrant extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('Registrant_Model');
		$this->load->model('Login_Model');
	}

	public function index()
	{
		$data = $this->Registrant_Model->select();
		
		$getURL = base_url().'registrant/get';
		$postURL = base_url().'registrant/post';
		$removeURL = base_url().'registrant/remove';
		
		$param = array('title' => 'Registrant', 'body' => 'admin/registrant', 'form' => 'form/registrant', 'data' => $data, 'postURL' => $postURL, 'getURL' => $getURL, 'removeURL' => $removeURL);
				
		$this->load->view('index.php', $param);
	}
	
	public function get(){
		$data = $this->Registrant_Model->select();
		
		echo json_encode($data);
	}
	
	public function post(){
		$email = $this->input->post('email');
		$data = array(
			'title'	 			=> $this->input->post('title'),
			'first_name'		=> $this->input->post('first_name'),
			'last_name'			=> $this->input->post('last_name'),
			'email'		 		=> $email,
			'phone'		 		=> $this->input->post('phone'),
			'nationality'		=> $this->input->post('nationality'),
			'birth_date'		=> $this->input->post('birth_date'),
			'user_saved'		=> "" /*$this->session->userdata['useradmin']->id*/
		);
		
		$crud = $this->input->post('crud');
		
		$result = null;
		if($crud == "add"){
			$isExist = true;
			
			//$data['id'] = 1;
			$result = $this->Registrant_Model->insert($data);
			
			$dataLogin = array(
				'email'		 		=> $email,
				'password'	 		=> $this->input->post('pwd')
			);
			$result = $this->Login_Model->insert($dataLogin);
		}		
		else if($crud == "edit"){
			$id = $this->input->post('id');
			$result = $this->Registrant_Model->update($data, $id);
		}
		
		
		if($result){
			$data = $this->Registrant_Model->select($email);
				
			$login_code	= rand(10000, 99999);				
			if($this->Login_Model->set_login($email, $login_code)){
				$data[0]->login_code = $login_code;
			}
			
			echo '[{"return":"1", "result":'.json_encode($data[0]).', "msg":"<i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Simpan data berhasil", 
						"m_msg":"Simpan data berhasil"}]';
		}
		else{
			echo '[{"return":"0", "msg":"<i class=\"fa fa-warning\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;<strong>Mohon maaf !</strong> Terjadi kesalahan saat proses, Silahkan ulangi", 
					"m_msg":"Mohon maaf!. Terjadi kesalahan saat proses, Silahkan ulangi"}]';
		}
	}
	
	public function remove(){
		$email = $this->input->post('email');
		$data =  $this->Registrant_Model->select($email);
		if($data){
			$id = $data[0]->id;
			$newData = array(
					'email' => $email . '_removed',
					'status' => -1
			);
			$success = false;
			if($this->Registrant_Model->update($newData, $id)){
				if($this->Login_Model->update($newData, $email)){
					$success = true;
				}
			}
			if($success){
				echo '[{"return":"1", "msg":"<i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Hapus data berhasil", 
						"m_msg":"Hapus data berhasil"}]';
			}
			else{
				echo '[{"return":"0", "msg":"<i class=\"fa fa-warning\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;<strong>Mohon maaf !</strong> Terjadi kesalahan saat proses, Silahkan ulangi", 
					"m_msg":"Mohon maaf!. Terjadi kesalahan saat proses, Silahkan ulangi"}]';
			}
		}
		else{
			echo '[{"return":"0", "msg":"<i class=\"fa fa-warning\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;<strong>Mohon maaf !</strong> Data dengan email '.$email.' tidak ditemukan", 
					"m_msg":"Data dengan email '.$email.' tidak ditemukan"}]';
		}
	}
	
}
