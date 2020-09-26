<?php
class Auth_Model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function login($data) {
		$condition = " uuid =" . "'" . $data['uid'] . "' AND " . "upwd =" . "'" . $data['upwd'] . "'";
		$this->db->select('*');
		$this->db->from('user_login');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	public function read_user_information($id) {
		$condition = " uuid =" . "'" . $id . "'";
		$this->db->select('*');
		$this->db->from('user_login');
		$this->db->where($condition);
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
	
}
?>