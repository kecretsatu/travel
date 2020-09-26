<?php
class Login_Model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function select($email = FALSE) {
		$condition = " status = 1";
		
		if($email != FALSE){
			$condition .= " and email = '".$email."' ";
		}
		
		$this->db->select('*');
		$this->db->from('registrant_login');
		$this->db->where($condition);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function set_login($email, $login_code){
		$this->db->set('login_code', $login_code, FALSE);
		$this->db->set('login_date', 'NOW()', FALSE);
		$this->db->where("email", $email);
		if($this->db->update("registrant_login")){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function insert($data){
		$this->db->set('status', '1', FALSE);
		$this->db->set('date_saved', 'NOW()', FALSE);
		if ($this->db->insert("registrant_login", $data)) {
			return true;
		}
		else{
			return false;
		}
	}
	public function delete($id){
		if ($this->db->delete("registrant_login", "id = '".$id."'")) {
			return true;
		}
	}
	public function update($data, $email){
		$this->db->set($data);
		$this->db->set('date_saved', 'NOW()', FALSE);
		$this->db->where("email", $email);
		if($this->db->update("registrant_login", $data)){
			return true;
		}
		else{
			return false;
		}
	}
	public function isExsit($email){
		$condition = " email = '".$email."'";
		$this->db->select('*');
		$this->db->from('registrant_login');
		$this->db->where($condition);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
}
?>