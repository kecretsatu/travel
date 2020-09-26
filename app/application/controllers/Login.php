
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('Registrant_Model');
		$this->load->model('Login_Model');
	}

	public function index()
	{
		if(isset($this->session->userdata["userlogin"])){
			header("location: ".base_url());
		}
		else{
			$this->load->view('login.php');
		}
	}
	
	public function check(){
		$uid	= $this->input->post('uid');
		$pwd	= $this->input->post('pwd');
		$type	= $this->input->post('type'); // 0 = Normal, 1 = Google, 2 = Facebook
		
		if($this->Login_Model->isExsit($uid)){
			$registrant = $this->Login_Model->select($uid);
			if($type == 0){				
				if($pwd == $registrant[0]->password){
					$data = $this->login_success($uid);				
					echo '[{"return":"1", "result":'.json_encode($data[0]).', "m_msg":"Login Sukses"}]';
				}
				else{
					echo '[{"return":"0", "m_msg":"Maaf, Email ato Password tidak valid"}]';
				}
				
				
			}
			else{
				$data = $this->login_success($uid);				
				echo '[{"return":"1", "result":'.json_encode($data[0]).', "m_msg":"Login Sukses"}]';
			}
		}
		else{
			echo '[{"return":"0", "m_msg":"Maaf, Email tidak ditemukan"}]';
		}
	}
	
	public function login_success($uid){
		$data = $this->Registrant_Model->select($uid);
				
		$login_code	= rand(10000, 99999);				
		if($this->Login_Model->set_login($uid, $login_code)){
			$data[0]->login_code = $login_code;
		}
		
		return $data;
	}
}

