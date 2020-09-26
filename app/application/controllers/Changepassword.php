<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Changepassword extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('Modul_Model');
	}

	public function index()
	{
		
		$param = array('title' => 'Ubah Password', 'body' => 'admin/changepassword', 'changeURL' => base_url() . "Changepassword/change");
				
		$this->load->view('index.php', $param);
	}
	
	public function change(){
		$oldp = $this->input->post('oldp');
		$newp = $this->input->post('newp');
		$new2p = $this->input->post('new2p');
		
		$uuid = $this->session->userdata["userlogin"]->uuid;
		$old = $this->Modul_Model->execqueryreturn("select upwd from user_login where uuid = '".$uuid."'")[0]["upwd"];
		
		if($oldp != $old){
				echo '[{"return":"0", "msg":"<i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Password Lama salah", 
						"m_msg":"Password Lama salah"}]';
		}
		elseif($newp != $new2p){
				echo '[{"return":"0", "msg":"<i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Ulangi Password Baru salah", 
						"m_msg":"Ulangi Password Baru salah"}]';
		}
		elseif(strlen($newp) < 6 || strlen($new2p) < 6){
				echo '[{"return":"0", "msg":"<i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Password Baru minimal 6 Karakter", 
						"m_msg":"Password Baru minimal 6 Karakter"}]';
		}
		else{
			$result = $this->Modul_Model->execquery("update user_login set upwd = '".$newp."' where uuid = '".$uuid."'");
			
			if($result){
				echo '[{"return":"1", "msg":"<i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Password berhasil diubah", 
								"m_msg":"Password berhasil diubah"}]';
			}
			else{				
				echo '[{"return":"1", "msg":"<i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Terjadi kesalahan saat melakukan perubahan password. Silahkan ulangi", 
								"m_msg":"Terjadi kesalahan saat melakukan perubahan password. Silahkan ulangi"}]';
			}
		}
	}
}
