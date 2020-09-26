<?php
class Registrant_Model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function select($email = FALSE) {
		$condition = " status = 1";
		
		if($email != FALSE){
			$condition .= " and email = '".$email."' ";
		}
		
		$this->db->select('*');
		$this->db->from('registrant');
		$this->db->where($condition);
		$this->db->order_by("date_saved", "desc");
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function insert($data){
		$this->db->set('date_saved', 'NOW()', FALSE);
		if ($this->db->insert("registrant", $data)) {
			return true;
		}
		else{
			return false;
		}
	}
	public function delete($id){
		if ($this->db->delete("registrant", "id = '".$id."'")) {
			return true;
		}
	}
	public function update($data, $id){
		$this->db->set($data);
		$this->db->set('date_saved', 'NOW()', FALSE);
		$this->db->where("id", $id);
		if($this->db->update("registrant", $data)){
			return true;
		}
		else{
			return false;
		}
	}
	public function isExsit($id){
		$condition = " id = '".$id."'";
		$this->db->select('*');
		$this->db->from('registrant');
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
