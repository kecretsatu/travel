<?php
class Order_Model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function select($id = FALSE, $c = FALSE) {
		$SQL = "select f.token, f.email, f.order_id, f.user,  count(*) as 'count' , 
(select count(*) from order_flight where order_id = f.order_id) as 'cflight', 
(select count(*) from order_hotel where order_id = f.order_id) as 'chotel'
from 
(
select o.order_id, o.token, o.email, 
if(o.email = '', '-', (select concat(title, ' ', first_name, ' ', last_name) from registrant where email = o.email order by date_saved desc limit 0, 1)) as 'user' 
from order_flight o where o.order_id <> '' and o.status <> -1
union all
select h.order_id, h.token, h.email, 
if(h.email = '', '-', (select concat(title, ' ', first_name, ' ', last_name) from registrant where email = h.email order by date_saved desc limit 0, 1)) as 'user' 
from order_hotel h where h.order_id <> '' and h.status <> -1
) f
group by f.order_id, f.token, f.email
order by f.order_id desc";
		$query = $this->db->query($SQL);

		return $query->result_array();
	}
	
	public function selectx($id = FALSE, $c = FALSE) {
		
		$SQL = "SELECT 'flight' as 'type', o.order_id, o.token, 
if(o.email = '', '-', (select concat(title, ' ', first_name, ' ', last_name) from registrant where email = o.email order by date_saved desc limit 0, 1)) as 'user',
if(o.email = '', o.token, o.email) as 'token_email',
(select count(order_detail_id) from order_flight where order_id = o.order_id group by order_detail_id order by date_saved desc limit 0, 1) as 'total', 
(select date_format(date_saved, '%d %M %Y %H:%i') from order_flight where order_id = o.order_id order by date_saved desc limit 0, 1) as 'date',
(select date_saved from order_flight where order_id = o.order_id order by date_saved desc limit 0, 1) as 'date_saved'
FROM order_flight o where order_id <> ''
group by o.order_id

union

SELECT 'hotel' as 'type', h.order_id,  h.token, 
if(h.email = '', '-', (select concat(title, ' ', first_name, ' ', last_name) from registrant where email = h.email order by date_saved desc limit 0, 1)) as 'user',
if(h.email = '', h.token, h.email) as 'token_email', 
(select count(order_detail_id) from order_hotel where order_id = h.order_id group by order_detail_id order by date_saved desc limit 0, 1) as 'total', 
(select date_format(date_saved, '%d %M %Y %H:%i') from order_flight where order_id = h.order_id order by date_saved desc limit 0, 1) as 'date',
(select date_saved from order_hotel where order_id = h.order_id order by date_saved desc limit 0, 1) as 'date_saved'
FROM order_hotel h where order_id <> ''
group by h.order_id

order by date_saved desc";
		$query = $this->db->query($SQL);

		return $query->result_array();
		
	}
	
	public function get($order_id, $type) {
		$SQL = "SELECT *, 'FLIGHT' as 'type', o.order_id,
if(o.email = '', '-', (select concat(title, ' ', first_name, ' ', last_name) from registrant where email = o.email order by date_saved desc limit 0, 1)) as 'user',
if(o.email = '', o.token, o.email) as 'token_email',
(select count(order_detail_id) from order_flight where order_id = o.order_id group by order_detail_id order by date_saved desc limit 0, 1) as 'total', 
(select date_format(date_saved, '%d %M %Y %H:%i') from order_flight where order_id = o.order_id order by date_saved desc limit 0, 1) as 'date',
(select date_saved from order_flight where order_id = o.order_id order by date_saved desc limit 0, 1) as 'date_saved'
FROM order_flight o where order_id = '".$order_id."'";
		
		if($type == "hotel"){
			$SQL = "SELECT *, 'HOTEL' as 'type', h.order_id, 
if(h.email = '', '-', (select concat(title, ' ', first_name, ' ', last_name) from registrant where email = h.email order by date_saved desc limit 0, 1)) as 'user',
if(h.email = '', h.token, h.email) as 'token_email', 
(select count(order_detail_id) from order_hotel where order_id = h.order_id group by order_detail_id order by date_saved desc limit 0, 1) as 'total', 
(select date_format(date_saved, '%d %M %Y %H:%i') from order_flight where order_id = h.order_id order by date_saved desc limit 0, 1) as 'date',
(select date_saved from order_hotel where order_id = h.order_id order by date_saved desc limit 0, 1) as 'date_saved'
FROM order_hotel h where order_id = '".$order_id."'";
		}
		
		$query = $this->db->query($SQL);

		return $query->result_array();
		
		
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
		if($this->db->update("order", $data)){
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
	
	public function execute($q){
		$query = $this->db->query($q);
		if($query != FALSE){
			return true;
		}
		else{
			return false;
		}
	}
}
?>