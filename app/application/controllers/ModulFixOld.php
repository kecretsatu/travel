<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modul extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('Modul_Model');
		$this->load->model('Registrant_Model');
	}
	
	public function get_api_domain(){
		return "https://api-sandbox.tiket.com";
	}

	public function index2(){
		$param = array('title' => 'Dashboard', 'body' => 'admin/dashboard');
				
		$this->load->view('index.php', $param);
	}
	
	function get_token(){
		$token = "";
		if($this->input->post('token')){
			$token = $this->input->post('token');	
		}
		else{
			$token = $this->request_token();
		}
		
		return $token;
	}
	
	function my_token(){
		echo $this->request_token();
	}
	
	function get_list_country(){
		$token = $this->get_token();
		$reqURL = $this->get_api_domain() . "/general_api/listCountry?token=".$token."&v=3&output=json";
		
		$result = $this->CURL($reqURL);
		echo $result;
	}
	
	function get_airport(){
		$token = $this->get_token(); //"e5c3d6ba52ba91e261c86b5b7567647657a2ca13" ; 
		$reqURL = $this->get_api_domain() . "/flight_api/all_airport?token=".$token."&v=3&output=json";
		
		$result = $this->CURL($reqURL);
		echo $result;
	}
	
	function get_flight(){
		$token		= $this->get_token() ; 
		
		$d 			= $this->input->post('d');
		$a 			= $this->input->post('a');
		$date		= $this->input->post('date');
		$ret_date	= $this->input->post('ret_date');
		$adult 		= $this->input->post('adult');
		$child 		= $this->input->post('child');
		$infant 	= $this->input->post('infant');
		
		$reqURL = $this->get_api_domain() . "/search/flight?d=".$d."&a=".$a."&date=".$date."&adult=".$adult."&child=".$child."&infant=".$infant."&token=".$token."&v=3&output=json";
				
		$result = $this->CURL($reqURL);		
		echo $result;
	}
	
	function get_flight_data(){
		$token			= $this->get_token() ; 
		
		$flight_id		= $this->input->post('flight_id');
		$ret_flight_id	= $this->input->post('ret_flight_id');
		$date			= $this->input->post('date');
		$ret_date		= $this->input->post('ret_date');
		
		$reqURL = $this->get_api_domain() . "/flight_api/get_flight_data?flight_id=".$flight_id."&date=".$date."&ret_flight_id=".$ret_flight_id."&ret_date=".$ret_date."&token=".$token."&v=3&output=json";
				
		$result = $this->CURL($reqURL, TRUE);		
		echo $result;
	}
	
	function book_flight(){
		$token				= $this->get_token() ; 
		
		$flight_id			= $this->input->post('flight_id');
		$departure_city 	= $this->input->post('departure_city');
		$departure_city_name= $this->input->post('departure_city_name');
		$arrival_city		= $this->input->post('arrival_city');
		$arrival_city_name	= $this->input->post('arrival_city_name');
		$real_flight_date 	= $this->input->post('real_flight_date');
		$airlines_name 		= $this->input->post('airlines_name');
		$flight_number 		= $this->input->post('flight_number');
		
		$ret_flight_id = "";
		if($this->input->post('ret_flight_id') != FALSE){
			$ret_flight_id 			= $this->input->post('ret_flight_id');
			$ret_departure_city 	= $this->input->post('ret_departure_city');
			$ret_departure_city_name= $this->input->post('ret_departure_city_name');
			$ret_arrival_city		= $this->input->post('ret_arrival_city');
			$ret_arrival_city_name	= $this->input->post('ret_arrival_city_name');
			$ret_real_flight_date 	= $this->input->post('ret_real_flight_date');
			$ret_airlines_name 		= $this->input->post('ret_airlines_name');
			$ret_flight_number 		= $this->input->post('ret_flight_number');
		}
		
		$adult	= $this->input->post('adult'); $child	= $this->input->post('child'); $infant = $this->input->post('infant');
		
		$contact_person = $this->input->post('contact_person');
		$passenger		= $this->input->post('passenger');
		
		$query = "insert into order_flight (departure_city, arrival_city, departure_city_name, arrival_city_name, real_flight_date, airlines_name, flight_number, flight_id, flight_type, contact_person, passenger, token, status, date_saved) 
						values ('".$departure_city."', '".$arrival_city."', '".$departure_city_name."', '".$arrival_city_name."', '".$real_flight_date."', '".$airlines_name."', '".$flight_number."', '".$flight_id."', 'trip', '".$contact_person."', '".$passenger."', '".$token."', '1', now())";

		if($this->Modul_Model->execquery($query)){
			
		}
		
		if($ret_flight_id != ""){
			$query = "insert into order_flight (departure_city, arrival_city, departure_city_name, arrival_city_name, real_flight_date, airlines_name, flight_number, flight_id, flight_type, contact_person, passenger, token, status, date_saved) 
				values ('".$ret_departure_city."', '".$ret_arrival_city."', '".$ret_departure_city_name."', '".$ret_arrival_city_name."', '".$ret_real_flight_date."', '".$ret_airlines_name."', '".$ret_flight_number."', '".$ret_flight_id."', 'round', '".$contact_person."', '".$passenger."', '".$token."', '1', now())";
			
			$this->Modul_Model->execquery($query);
		}
		
		$contact_person = json_decode($contact_person, true);
		$passenger 		= json_decode($passenger, true);
		
		$reqURL = $this->get_api_domain() . "/order/add/flight?token=" . $token . "&output=json";

		if($ret_flight_id == ""){
			$reqURL .= "&flight_id=".$flight_id."&adult=".$adult."&child=".$child."&infant=".$infant."";
		}
		else{
			$reqURL .= "&flight_id=".$flight_id."&ret_flight_id=".$ret_flight_id."&adult=".$adult."&child=".$child."&infant=".$infant."";
		}
		
		$reqURL .= $this->jsonObjectToParamURL($contact_person);
		$reqURL .= $this->jsonObjectToParamURL($passenger);
		
		$result = $this->CURL($reqURL, TRUE);		
		echo $result;
	}
	
	function get_book_flight(){
		$token = $this->get_token();
		
		$reqURL = $this->get_api_domain() . "/order?&token=".$token."&v=3&output=json";
		
		$bookFlight = $this->CURL($reqURL);
		
		//$this->writeFile("req.txt", $reqURL);
		//$this->writeFile("book.txt", $bookFlight);
		
		$bookFlight	= json_decode($bookFlight, true);
		
		if(isset($bookFlight["myorder"])){
			$bookFlightMyOrder = $bookFlight["myorder"]["data"];
		
			$order_id = $bookFlight["myorder"]["order_id"];
			
			$deleteDataIndex = array();
			
			$in = 0;
			foreach($bookFlightMyOrder as $data){
				$order_type				= $data["order_type"];
				if($order_type == "flight"){
					$trip				= $data["detail"]["trip"];
					$departure_city		= $data["detail"]["departure_city"];
					$arrival_city		= $data["detail"]["arrival_city"];
					$real_flight_date	= $data["detail"]["real_flight_date"];
					$airlines_name		= $data["detail"]["airlines_name"];
					$flight_number		= $data["detail"]["flight_number"];
					$flight_type		= "";
					
					$query = "select ifnull(flight_id, '0') as 'flight_id' from order_flight 
							where departure_city = '".$departure_city."' and arrival_city = '".$arrival_city."' and real_flight_date = '".$real_flight_date."' 
							and airlines_name = '".$airlines_name."' and flight_number = '".$flight_number."' and flight_type = '".$trip."' 
							and token = '".$token."'";	
					
					$flight_id = $this->Modul_Model->execqueryreturn($query);
					$flight_id = $flight_id[0]["flight_id"];
					
					$query = "select departure_city_name from order_flight 
							where departure_city = '".$departure_city."' and arrival_city = '".$arrival_city."' and real_flight_date = '".$real_flight_date."' 
							and airlines_name = '".$airlines_name."' and flight_number = '".$flight_number."' and flight_type = '".$trip."' 
							and token = '".$token."'";
							
					$departure_city_name = $this->Modul_Model->execqueryreturn($query);
					$departure_city_name = $departure_city_name[0]["departure_city_name"];
					
					$query = "select arrival_city_name from order_flight 
							where departure_city = '".$departure_city."' and arrival_city = '".$arrival_city."' and real_flight_date = '".$real_flight_date."' 
							and airlines_name = '".$airlines_name."' and flight_number = '".$flight_number."' and flight_type = '".$trip."' 
							and token = '".$token."'";						
					$arrival_city_name = $this->Modul_Model->execqueryreturn($query);
					$arrival_city_name = $arrival_city_name[0]["arrival_city_name"];
					
					if($trip == "trip"){
						$flight_type = "departures";
						$f = $this->get_book_flight_data($flight_id, true, $token);
					}
					else{
						$flight_type = "returns";
						$f = $this->get_book_flight_data($flight_id, false, $token);
					}
					
					$f = json_decode($f, true);
					
					if(isset($f[$flight_type])){
						$f = $f[$flight_type];	
					}
					
					$bookFlight["myorder"]["data"][$in]["flight_detail"] = $f;
					$bookFlight["myorder"]["data"][$in]["departure_city_name"] = $departure_city_name;
					$bookFlight["myorder"]["data"][$in]["arrival_city_name"] = $arrival_city_name;
					
					//array_push($bookFlight["myorder"]["data"][$in]["flight_detail"], $f);
				}
				else{
					array_push($deleteDataIndex, $in);
				}
				$in++;
			}
			$bookFlight = json_encode($bookFlight);
			echo $bookFlight;
		}
		else{
			echo "[]";
		}
	}
	
	function get_book_flight_data($flight_id, $is_departure, $token){
		if($is_departure){
			$reqURL = $this->get_api_domain() . "/flight_api/get_flight_data?flight_id=".$flight_id."&token=".$token."&v=3&output=json";
		}
		else{
			$reqURL = $this->get_api_domain() . "/flight_api/get_flight_data?ret_flight_id=".$flight_id."&token=".$token."&v=3&output=json";
		}
		
		$result = $this->CURL($reqURL);		
		return $result;
	}
	
	function get_area(){
		$token = $this->get_token();
		
		$q = $this->input->post('q');
		$reqURL = $this->get_api_domain() . "/search/autocomplete/hotel?q=".$q."&token=".$token."&v=3&output=json";
		
		$result = $this->CURL($reqURL);		
		echo $result;
	}
	
	function get_hotel(){
		$token = $this->get_token();
		
		$q			= $this->input->post('q');
		$startdate	= $this->input->post('startdate');
		$enddate	= $this->input->post('enddate');
		$adult		= $this->input->post('adult');
		$child		= $this->input->post('child');
		$night		= $this->input->post('night');
		$room		= $this->input->post('room');
		$page		= $this->input->post('page');
		
		$reqURL = $this->get_api_domain() . "/search/hotel?q=".$q."&startdate=".$startdate."&enddate=".$enddate."&adult=".$adult."&child=".$child."&night=".$night."&room=".$room."&page=".$page."&token=".$token."&v=3&output=json";
		
		$result = $this->CURL($reqURL);		
		echo $result;
	}
	
	function get_room(){
		$token = $this->get_token();
		
		$uri = $this->input->post('uri');
		
		$reqURL = $uri."&token=".$token."&v=3&output=json";
		
		$result = $this->CURL($reqURL);		
		echo $result;
	}
	
	function book_hotel(){
		$token = $this->get_token();
		
		$hotel					= $this->input->post('hotel');
		$hotel_id				= $this->input->post('hotel_id');
		$room					= $this->input->post('room');
		$room_id				= $this->input->post('room_id');
		$start_date				= $this->input->post('start_date');
		$end_date				= $this->input->post('end_date');
		$contact_person			= $this->input->post('contact_person');
		$forSomeOneElse 		= $this->input->post('forSomeOneElse');
		$contact_person_else	= $this->input->post('contact_person_else');
		
		$query = "insert into order_hotel (hotel_id, hotel, room_id, room, start_date, end_date, contact_person, for_someone, contact_person_else, token, status, date_saved)
					values ('".$hotel_id."', '".$hotel."', '".$room_id."', '".$room."', '".$start_date."', '".$end_date."', '".$contact_person."', '".$forSomeOneElse."', '".$contact_person_else."', '".$token."', 1, now())";
		
		$bookUri = $this->input->post('bookUri');
		
		if($this->Modul_Model->execquery($query)){			
			$reqURL = $bookUri . "&token=".$token."&v=3&output=json";
			$this->writeFile("req.txt", $reqURL);
		
			$result = $this->CURL($reqURL);		
			echo $result;
		}
		else{
			echo '[{"error":"1", "error_msg":""}]';
		}
	}
	
	function get_book_hotel(){
		$token = $this->get_token();
		
		$reqURL = $this->get_api_domain() . "/order?&token=".$token."&v=3&output=json";
		
		$bookFlight = $this->CURL($reqURL);
		
		//$this->writeFile("req.txt", $reqURL);
		//$this->writeFile("book.txt", $bookFlight);
		
		$bookFlight	= json_decode($bookFlight, true);
		
		if(isset($bookFlight["myorder"])){
			$bookFlightMyOrder = $bookFlight["myorder"]["data"];
		
			$order_id = $bookFlight["myorder"]["order_id"];
			
			$deleteDataIndex = array();
			
			$in = 0;
			foreach($bookFlightMyOrder as $data){
				$order_type				= $data["order_type"];
				if($order_type == "hotel"){
					$hotel_id			= $data["business_id"];
					$room_id			= $data["detail"]["room_id"];
					$startdate			= $data["detail"]["startdate"];
					$enddate			= $data["detail"]["enddate"];
					
					$query = "select hotel from order_hotel 
							where hotel_id = '".$hotel_id."' and room_id = '".$room_id."' and start_date = '".$startdate."' and end_date = '".$enddate."' 
							and token = '".$token."'";											
					$hotel = $this->Modul_Model->execqueryreturn($query);
					
					$query = "select room from order_hotel 
							where hotel_id = '".$hotel_id."' and room_id = '".$room_id."' and start_date = '".$startdate."' and end_date = '".$enddate."' 
							and token = '".$token."'";							
					$room = $this->Modul_Model->execqueryreturn($query);
					
					$query = "select contact_person from order_hotel 
							where hotel_id = '".$hotel_id."' and room_id = '".$room_id."' and start_date = '".$startdate."' and end_date = '".$enddate."' 
							and token = '".$token."'";							
					$contact_person = $this->Modul_Model->execqueryreturn($query);
					
					$query = "select for_someone from order_hotel 
							where hotel_id = '".$hotel_id."' and room_id = '".$room_id."' and start_date = '".$startdate."' and end_date = '".$enddate."' 
							and token = '".$token."'";							
					$for_someone = $this->Modul_Model->execqueryreturn($query);
					
					$query = "select contact_person_else from order_hotel 
							where hotel_id = '".$hotel_id."' and room_id = '".$room_id."' and start_date = '".$startdate."' and end_date = '".$enddate."' 
							and token = '".$token."'";							
					$contact_person_else = $this->Modul_Model->execqueryreturn($query);
					
					$hotel 					= json_decode(strip_tags($hotel), true);
					$room 					= json_decode(strip_tags($room), true);
					$contact_person 		= json_decode(strip_tags($contact_person), true);
					$for_someone			= json_decode(strip_tags($for_someone), true);
					$contact_person_else	= json_decode(strip_tags($contact_person_else), true);
					
					$bookFlight["myorder"]["data"][$in]["hotel_detail"] = $hotel;
					$bookFlight["myorder"]["data"][$in]["room_detail"] = $room;
					$bookFlight["myorder"]["data"][$in]["contact_person"] = $contact_person;
					$bookFlight["myorder"]["data"][$in]["for_someone"] = $for_someone;
					$bookFlight["myorder"]["data"][$in]["contact_person_else"] = $contact_person_else;
					
					//array_push($bookFlight["myorder"]["data"][$in]["flight_detail"], $f);
				}
				else{
					array_push($deleteDataIndex, $in);
				}
				$in++;
			}
			$bookFlight = json_encode($bookFlight);
			echo $bookFlight;
		}
		else{
			echo "[]";
		}
	}
	
	function check_out_order(){
		$token = $this->get_token();
		
		$orderID = $this->input->post('orderID');
		
		$reqURL = $this->get_api_domain() . "/order/checkout/".$orderID."/IDR?token=".$token."&v=3&output=json";		
					
		
		$result = $this->CURL($reqURL);		
		
		$this->writeFile("checkOutOrder.txt", $reqURL);
		$this->writeFile("checkOutOrderResult.txt", $result);
		
		echo $result;
	}
	
	function check_out_login(){
		$token = $this->get_token();
		
		$email = $this->input->post('email');
		$data = $this->Registrant_Model->select($email);
		
		$reqURL  = $this->get_api_domain() . "/checkout/checkout_customer?token=" . $token;
		$reqURL .= "&salutation=".$data[0]->title."&firstName=".$data[0]->first_name."&lastName=".$data[0]->last_name;
		$reqURL .= "&emailAddress=".$data[0]->email."&phone=".$data[0]->phone."&saveContinue=2&output=json";
		
		
		$result = $this->CURL($reqURL);		
		
		echo $result;
	}
	
	function check_out_payment(){
		$token = $this->get_token();
		
		$reqURL = $this->get_api_domain() . "/checkout/checkout_payment?token=".$token."&v=3&output=json";
		
		$result = $this->CURL($reqURL);		
		echo $result;		
	}
	
	function get_payment(){
		$token	= $this->get_token();
		$link	= $this->input->post('link');
		
		$reqURL = $link;
		
		$quote  = "?";
		if (strpos($reqURL, '?') !== false) {
			//TRUE
			$quote = "";
		}
		
		$reqURL = $reqURL . $quote . "&token=".$token."&checkouttoken=".$token."&btn_booking=1&currency=IDR&v=3&output=json";
		
		$result = $this->CURL($reqURL);		
		
		$this->writeFile("getPayment.txt", $reqURL);
		$this->writeFile("getPaymentResult.txt", $result);
		
		echo $result;
	}
	
	function CURL($reqURL, $ssl = FALSE){
		
		$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
		$curl = curl_init();

		$curlArr = array(
		  CURLOPT_URL => $reqURL,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 300,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_HTTPHEADER => array(),
		);
		
		if($ssl){
			$curlArr[CURLOPT_SSL_VERIFYPEER] = FALSE;
			$curlArr[CURLOPT_USERAGENT] = $agent;
		}
		
		curl_setopt_array($curl, $curlArr);
		
		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		
		if ($err) {
		  return '[{"error":"1", "error_msg":"'.$err.'"}]';
		} else {
		  return $response;
		}
	}
	
	function request_token(){
		$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';

		//$urlAPI = "http://hsn-id.com/api_travel/General/get_token";
		$urlAPI = "http://tiketravel.co.id/app/General/get_token";
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $urlAPI,
		  CURLOPT_USERAGENT => $agent,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "api_key=JVf65MJtX5ATp7JQ2cVTDeU1928D6k0P",
		  CURLOPT_HTTPHEADER => array(),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		
		if ($err) {
		  return "cURL Error #:" . $err;
		} else {
		  return $response;
		}
	}
	
	function jsonObjectToParamURL($json){
		$param = "";
		
		foreach($json as $key => $val)
		{
			$param .= "&" . $key . "=" . $val;
		}
		return $param;
		
	}
	
	function writeFile($path, $content){
		$this->load->helper('file');
		$data = $content;

		if ( !write_file($path, $data)){
			 echo 'Unable to write the file';
		}
	}
}
