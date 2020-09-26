<?php
class Database_Model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function execute($query) {
		$query = $this->db->query($query);

		return $query->result_array();
	}
}
?>
