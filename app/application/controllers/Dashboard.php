<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('Database_Model');
	}

	public function index()
	{
		$registrant	= $this->Database_Model->execute("select count(*) as 'total' from registrant")[0]["total"];
		$order		= $this->Database_Model->execute("select count(*) as 'total' from (select order_id from order_flight union select order_id from order_hotel) o")[0]["total"];
		$viewed		= 0;
		$message	= $this->Database_Model->execute("select count(*) as 'total' from message where status = 0")[0]["total"];
		
		$registrantGM	= $this->Database_Model->execute("select date_format(date_saved, '%m') as 'month', date_format(date_saved, '%M') as 'month_name', count(*) as 'total' from registrant group by date_format(date_saved, '%m') order by date_format(date_saved, '%m')");
		$orderGM		= $this->Database_Model->execute("select o.month, o.month_name, count(*) as 'total' from (select date_format(date_saved, '%m') as 'month', date_format(date_saved, '%M') as 'month_name', order_id from order_flight where order_id <> '' union select date_format(date_saved, '%m') as 'month', date_format(date_saved, '%M') as 'month_name', order_id from order_hotel where order_id <> '') o group by o.month, o.month_name order by o.month");
		$messageGM		= $this->Database_Model->execute("select date_format(date_saved, '%m') as 'month', date_format(date_saved, '%M') as 'month_name', count(*) as 'total' from message group by date_format(date_saved, '%m') order by date_format(date_saved, '%m')");
		
		$param = array(
				'title' => 'Dashboard', 
				'body' => 'admin/dashboard',
				'registrant' => $registrant,
				'order' => $order,
				'viewed' => $viewed,
				'message' => $message,
				'registrantGM' => $registrantGM,
				'orderGM' => $orderGM,
				'messageGM' => $messageGM
				);
				
		$this->load->view('index.php', $param);
	}
}
