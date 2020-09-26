<?php
class Promo_Model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function select($id = FALSE, $c = FALSE) {
		$condition = " status = 1";
		
		if($id != FALSE){
			$condition .= " and id = '".$id."' ";
		}
		if($c != FALSE){
			$condition .= $c;
		}
		
		$this->db->select('*');
		$this->db->from('promo');
		$this->db->where($condition);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function insert($data){
		$this->db->set('date_saved', 'NOW()', FALSE);
		if ($this->db->insert("promo", $data)) {
			return true;
		}
		else{
			return false;
		}
	}
	public function delete($id){
		if ($this->db->delete("promo", "id = '".$id."'")) {
			return true;
		}
	}
	public function update($data, $id){
		$this->db->set($data);
		$this->db->where("id", $id);
		if($this->db->update("promo", $data)){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function get_last_item() {
		
		$this->db->select('max(id) as id');
		$this->db->from('promo');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function isExsit($id){
		$condition = " id = '".$id."'";
		$this->db->select('*');
		$this->db->from('promo');
		$this->db->where($condition);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function insertPromoView($data){
		$this->db->set('date_saved', 'NOW()', FALSE);
		if ($this->db->insert("promo_view", $data)) {
			return true;
		}
		else{
			return false;
		}
	}
}
?>