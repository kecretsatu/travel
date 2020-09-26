<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function index()
	{
		$this->load->model('Auth_Model');
		$data = array(
			'uid' => $this->input->post('uid'),
			'upwd' => $this->input->post('upwd')
		);
		$result = $this->Auth_Model->login($data);
		if ($result == TRUE) {
			$id = $this->input->post('uid');
			$result = $this->Auth_Model->read_user_information($id);
			$this->session->set_userdata("userlogin", $result[0]);
			echo '[{"return":"1"}]';
		}
		else{
			echo '[{"return":"0", "msg":"Username atau Password Salah."}]';
		}
	}
	
	public function logout()
	{
		$this->session->unset_userdata('userlogin');
		header("location: ".base_url() . "login");
	}
}
