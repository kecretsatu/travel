<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promo extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('Promo_Model');
	}

	public function index()
	{
		$data = $this->Promo_Model->select();
		
		$getURL = base_url().'promo/get';
		$postURL = base_url().'promo/post';
		$removeURL = base_url().'promo/remove';
		
		$param = array('title' => 'Promo', 'body' => 'admin/promo', 'form' => 'form/promo', 'data' => $data, 'postURL' => $postURL, 'getURL' => $getURL, 'removeURL' => $removeURL);
				
		$this->load->view('index.php', $param);
	}
	
	public function newPromo(){
		$token = $this->input->post('token');
		$email = $this->input->post('email');
		
		$data = $this->Promo_Model->select(FALSE, " and id not in (select id_promo from promo_view where (token = '".$token."' or email = '".$email."')) ");
		
		if($data != FALSE){
			foreach($data as $d){			
				$x = array(
					'token'	 			=> $token,
					'email'	 			=> $email,
					'id_promo'			=> $d->id
				);
				$result = $this->Promo_Model->insertPromoView($x);
			}
		}
		else{
			$data = array();
		}
		$data = json_encode($data);
		
		$this->writeFile("promo.txt", $data);
		
		echo $data;
	}
	
	public function get(){
		if($this->input->post('type')){
			$data = $this->Promo_Model->select(FALSE, " and type = '".$this->input->post('type')."' ");
		}
		else{
			$data = $this->Promo_Model->select();
		}
		
		echo json_encode($data);
	}
	
	function post(){
		$email = $this->input->post('email');
		$data = array(
			'type'	 			=> $this->input->post('type'),
			'name'	 			=> $this->input->post('name'),
			'promo_code'		=> $this->input->post('promo_code'),
			'description'		=> $this->input->post('description'),
			'requirement' 		=> $this->input->post('requirement')
		);
		
		$crud = $this->input->post('crud');
		
		$id = "";
		$result = null;
		if($crud == "add"){
			$isExist = true;
			
			//$data['id'] = 1;
			$result = $this->Promo_Model->insert($data);
			$id = $this->Promo_Model->get_last_item()[0]->id;
		}		
		else if($crud == "edit"){
			$id = $this->input->post('id');
			$result = $this->Promo_Model->update($data, $id);
		}
		
		$ext = $this->do_upload($id);
		if($ext !== FALSE){
			$data["image"] = $ext;
			$result = $this->Promo_Model->update($data, $id);
		}
		
		if($result){
			$data = $this->Promo_Model->select($id);
			
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
		
		$result = $this->Promo_Model->update($data, $id);
		
		echo '[{"return":"1", "msg":"<i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Pesan berhasil dihapus", 
						"m_msg":"Pesan berhasil dihapus"}]';
			
	}
	
	public function do_upload($id)
	{
		$file_ext = "";
		
		$config['upload_path']          = './assets/images/promo/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['max_size']             = 1024 * 3; // 3MB
		$config['max_width']            = 1024 * 3;
		$config['max_height']           = 768 * 3;
		$config['overwrite'] 			= TRUE;
		$config['file_name']           	= $id;
		
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('file')){
			return FALSE;
		}
		else{
			$this->upload->data();
			$file_ext = $this->upload->data('file_ext');
			return $file_ext;
		}
		
	}
	
	function writeFile($path, $content){
		$this->load->helper('file');
		$data = $content;

		if ( !write_file($path, $data)){
			 echo 'Unable to write the file';
		}
	}
}
