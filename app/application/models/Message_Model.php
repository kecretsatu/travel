<?php
class Message_Model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function select($email = FALSE, $condition = " (status = 1 or status = 2)") {
		
		if($email != FALSE){
			$condition .= " and email = '".$email."' ";
		}
		
		$f = '';
		$this->db->select('*, 
							if(status = 1, \'unread\', \'read\') as s, 
							if(date_Format(date_saved, \'%m-%d-%Y\') = date_Format(date_add(now(), interval -1 day), \'%m-%d-%Y\'), \'Yesterday\', 
							if(date_Format(date_saved, \'%m-%d-%Y\') < date_Format(now(), \'%m-%d-%Y\'), date_Format(date_saved, \'%d %b\'), 
							date_Format(date_saved, \'%h:%i\'))) as t, DATE_FORMAT(NOW(), \'%d %b %Y %h:%i\') as \'f\'');
		$this->db->from('message');
		$this->db->where($condition);
		$this->db->order_by('date_saved', 'desc');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function insert($data){
		$this->db->set('date_saved', 'NOW()', FALSE);
		if ($this->db->insert("message", $data)) {
			return true;
		}
		else{
			return false;
		}
	}
	public function delete($id){
		if ($this->db->delete("message", "id = '".$id."'")) {
			return true;
		}
	}
	public function update($data, $id){
		$this->db->set($data);
		$this->db->where("id", $id);
		if($this->db->update("message", $data)){
			return true;
		}
		else{
			return false;
		}
	}
	public function isExsit($id){
		$condition = " id = '".$id."'";
		$this->db->select('*');
		$this->db->from('message');
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