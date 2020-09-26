<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modul extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('Modul_Model');
		$this->load->model('Registrant_Model');
	}
	
	public function get_api_domain(){
		$api = "https://api-sandbox.tiket.com";
		$api = "https://api.tiket.com";
		return $api;
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
		
		$query1 = "insert into order_flight (departure_city, arrival_city, departure_city_name, arrival_city_name, real_flight_date, airlines_name, flight_number, flight_id, flight_type, contact_person, passenger, token, status, date_saved) 
						values ('".$departure_city."', '".$arrival_city."', '".$departure_city_name."', '".$arrival_city_name."', '".$real_flight_date."', '".$airlines_name."', '".$flight_number."', '".$flight_id."', 'trip', '".$contact_person."', '".$passenger."', '".$token."', '1', now())";

		/*if($this->Modul_Model->execquery($query)){
			
		}*/
		
		$query2 = "";
		if($ret_flight_id != ""){
			$query2 = "insert into order_flight (departure_city, arrival_city, departure_city_name, arrival_city_name, real_flight_date, airlines_name, flight_number, flight_id, flight_type, contact_person, passenger, token, status, date_saved) 
				values ('".$ret_departure_city."', '".$ret_arrival_city."', '".$ret_departure_city_name."', '".$ret_arrival_city_name."', '".$ret_real_flight_date."', '".$ret_airlines_name."', '".$ret_flight_number."', '".$ret_flight_id."', 'round', '".$contact_person."', '".$passenger."', '".$token."', '1', now())";
			
			//$this->Modul_Model->execquery($query);
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
		
		$this->writeFile("book_flight.txt", $reqURL);
		
		$result = $this->CURL($reqURL, TRUE);		
		
		$this->writeFile("result_book_flight.txt", $result);
		
		$resultx = json_decode($result, true);
		$confirm = $resultx["diagnostic"]["confirm"];
		if($confirm == "success"){
			if($this->Modul_Model->execquery($query1)){
				if($ret_flight_id != ""){
					$this->Modul_Model->execquery($query2);
				}
			}
		}
		
		$this->sync_book_data($token, "");
		
		echo $result;
	}
	
	function sync_book_data($token, $c){
		$reqURL = $this->get_api_domain() . "/order?&token=".$token."&v=3&output=json";
		$bookFlight = $this->CURL($reqURL);
		
		$this->writeFile("sync_flight.txt", $bookFlight);
		
		$bookFlight	= json_decode($bookFlight, true);
		
		if(isset($bookFlight["myorder"])){
			$bookFlightMyOrder = $bookFlight["myorder"]["data"];
			$order_id = $bookFlight["myorder"]["order_id"];
			
			foreach($bookFlightMyOrder as $data){
				$order_detail_id		= $data["order_detail_id"];
				$order_type				= $data["order_type"];
				
				if($order_type == "flight"){
					$trip				= $data["detail"]["trip"];
					$departure_city		= $data["detail"]["departure_city"];
					$arrival_city		= $data["detail"]["arrival_city"];
					$real_flight_date	= $data["detail"]["real_flight_date"];
					$airlines_name		= $data["detail"]["airlines_name"];
					$flight_number		= $data["detail"]["flight_number"];
					$flight_type		= "";
					
					$query = "update order_flight set order_id = '".$order_id."', order_detail_id = '".$order_detail_id."' 
							where departure_city = '".$departure_city."' and arrival_city = '".$arrival_city."' and real_flight_date = '".$real_flight_date."' 
							and airlines_name = '".$airlines_name."' and flight_number = '".$flight_number."' and flight_type = '".$trip."' 
							and token = '".$token."'";
					
					
		
					$this->writeFile("q.txt", $query);
					
					$this->Modul_Model->execquery($query);
				}
				else if($order_type == "hotel"){					
					$hotel_id			= $data["business_id"];
					$room_id			= $data["detail"]["room_id"];
					$startdate			= $data["detail"]["startdate"];
					$enddate			= $data["detail"]["enddate"];
					
					$query = "update order_hotel set order_id = '".$order_id."', order_detail_id = '".$order_detail_id."' " . $c;
					
					$this->writeFile("req.txt", $query);
					
					$this->Modul_Model->execquery($query);
				}
			}
		}
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
				$order_detail_id		= $data["order_detail_id"];
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
							where order_id = '".$order_id."' and order_detail_id = '".$order_detail_id."'  and flight_type = '".$trip."' 
							and departure_city = '".$departure_city."' and arrival_city = '".$arrival_city."' 
							and token = '".$token."'";	
					//$this->writeFile("q.txt", $query);
					$flight_id = $this->Modul_Model->execqueryreturn($query);
					$flight_id = $flight_id[0]["flight_id"];
					
					$query = "select departure_city_name from order_flight 
							where order_id = '".$order_id."' and order_detail_id = '".$order_detail_id."' and flight_type = '".$trip."'  
							and departure_city = '".$departure_city."' and arrival_city = '".$arrival_city."'  
							and token = '".$token."'";
							
					$departure_city_name = $this->Modul_Model->execqueryreturn($query);
					$departure_city_name = $departure_city_name[0]["departure_city_name"];
					
					$query = "select arrival_city_name from order_flight 
							where order_id = '".$order_id."' and order_detail_id = '".$order_detail_id."' and flight_type = '".$trip."' 
							and departure_city = '".$departure_city."' and arrival_city = '".$arrival_city."'  
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
		
		$sort		= $this->input->post('sort');
		$filter		= $this->input->post('filter');
		
		$reqURL = $this->get_api_domain() . "/search/hotel?q=".$q."&startdate=".$startdate."&enddate=".$enddate."&adult=".$adult."&child=".$child."&night=".$night."&room=".$room.$sort.$filter."&page=".$page."&token=".$token."&v=3&output=json";
		
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
		
		$reqURL = $bookUri . "&token=".$token."&v=3&output=json";
		$this->writeFile("req.txt", $reqURL);
		$result = $this->CURL($reqURL);	
		
		$resultx = json_decode($result, true);
		$confirm = $resultx["diagnostic"]["confirm"];
		if($confirm == "success"){
			if($this->Modul_Model->execquery($query)){
				$this->sync_book_data($token, " where hotel_id = '".$hotel_id."' and room_id = '".$room_id."' and start_date = '".$start_date."' and end_date = '".$end_date."'  
							and token = '".$token."'");
			}
		}		
		
		echo $result;
	
	}
	
	function get_book_hotel(){
		$token = $this->get_token();
		//$token = "31680c92bb9b62f931c4c1b3eeca5f7669a78b38";
		
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
					$order_detail_id	= $data["order_detail_id"];
					$hotel_id			= $data["business_id"];
					$room_id			= $data["detail"]["room_id"];
					$startdate			= $data["detail"]["startdate"];
					$enddate			= $data["detail"]["enddate"];
					
					$query = "select hotel from order_hotel 
							where order_id = '".$order_id."' and order_detail_id = '".$order_detail_id."'  
							and token = '".$token."'";											
					$hotel = $this->Modul_Model->execqueryreturn($query);
					//echo json_encode(json_decode(json_encode($hotel[0]["hotel"])), JSON_UNESCAPED_SLASHES);
					
					$query = "select room from order_hotel 
							where order_id = '".$order_id."' and order_detail_id = '".$order_detail_id."'  
							and token = '".$token."'";							
					$room = $this->Modul_Model->execqueryreturn($query);
					
					$query = "select contact_person from order_hotel 
							where order_id = '".$order_id."' and order_detail_id = '".$order_detail_id."'  
							and token = '".$token."'";							
					$contact_person = $this->Modul_Model->execqueryreturn($query);
					
					$query = "select for_someone from order_hotel 
							where order_id = '".$order_id."' and order_detail_id = '".$order_detail_id."'  
							and token = '".$token."'";							
					$for_someone = $this->Modul_Model->execqueryreturn($query);
					
					$query = "select contact_person_else from order_hotel 
							where order_id = '".$order_id."' and order_detail_id = '".$order_detail_id."'  
							and token = '".$token."'";							
					$contact_person_else = $this->Modul_Model->execqueryreturn($query);
					
					//echo  json_encode($hotel[0], JSON_UNESCAPED_SLASHES);
					//echo  json_encode(json_decode($hotel[0]["hotel"], true), JSON_UNESCAPED_SLASHES);
					//echo  json_encode($hotel[0]->hotel, JSON_UNESCAPED_SLASHES);
					
					/*$hotel 					= json_decode(strip_tags(json_encode($hotel)), true);
					$room 					= json_decode(strip_tags(json_encode($room)), true);
					$contact_person 		= json_decode(strip_tags(json_encode($contact_person)), true);
					$for_someone			= json_decode(strip_tags(json_encode($for_someone)), true);
					$contact_person_else	= json_decode(strip_tags(json_encode($contact_person_else)), true);*/
					
					//echo json_decode(json_decode(json_encode($hotel[0]["hotel"], JSON_UNESCAPED_SLASHES)));
					
					//echo json_encode(json_decode($hotel[0]["hotel"], true));
					//echo json_encode(json_decode(json_encode($hotel[0]["hotel"])), JSON_UNESCAPED_SLASHES);
					//echo json_decode(json_encode($hotel[0]["hotel"], JSON_UNESCAPED_SLASHES));
					//$hotel 					= json_decode(json_encode(json_decode(json_encode($hotel[0]["hotel"]), true), JSON_UNESCAPED_SLASHES));
					//$hotel 					= json_decode(json_encode(json_decode(json_encode($hotel[0]["hotel"])), JSON_UNESCAPED_SLASHES));
					$hotel 					= json_decode(json_encode($hotel[0]["hotel"], JSON_UNESCAPED_SLASHES));
					$room 					= json_decode(json_encode($room[0]["room"], JSON_UNESCAPED_SLASHES));
					$contact_person 		= json_decode(json_encode(json_decode($contact_person[0]["contact_person"], true), JSON_UNESCAPED_SLASHES));
					$for_someone			= json_decode(json_encode(json_decode($for_someone[0]["for_someone"], true), JSON_UNESCAPED_SLASHES));
					$contact_person_else	= json_decode(json_encode(json_decode($contact_person_else[0]["contact_person_else"], true), JSON_UNESCAPED_SLASHES));
					
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
	
	function remove_order(){
		$token = $this->get_token();
		//deleteUri
		$deleteUri = $this->input->post('deleteUri');
		
		$reqURL = $deleteUri . "&token=".$token."&v=3&output=json";
		//$reqURL = $this->get_api_domain() . "/general_api/listCountry?token=".$token."&v=3&output=json";
		
		$result = $this->CURL($reqURL);
		echo $result;
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
	
	function check_out_customer(){
		$token = $this->get_token();
		
		$order_id 		= $this->input->post('orderID');
		$email 			= $this->input->post('email');
		$data			= $this->Registrant_Model->select($email);
		$nationality	= $data[0]->nationality;
		
		$query = "select * from order_hotel 
				where order_id = '".$order_id."'   
				and token = '".$token."'";											
		$order_hotel 	 = $this->Modul_Model->execqueryreturn($query);
		//$order_hotel	 = json_decode(json_encode(json_decode($order_hotel[0]["order_detail_id"], true), JSON_UNESCAPED_SLASHES));
		
		$result = "";
		foreach($order_hotel as $order){
			$for_someone 			= $order["for_someone"];
			$order_detail_id 		= $order["order_detail_id"];
			$contact_person 		= json_decode(json_encode(json_decode($order["contact_person"], true), JSON_UNESCAPED_SLASHES));
			$contact_person_else	= json_decode(json_encode(json_decode($order["contact_person_else"], true), JSON_UNESCAPED_SLASHES));
			
			$reqURL  = $this->get_api_domain() . "/checkout/checkout_customer?token=" . $token;
			$reqURL .= "&conSalutation=".$contact_person->cpTitle."&conFirstName=".$contact_person->cpFirstName."&conLastName=".$contact_person->cpLastName;
			$reqURL .= "&conEmailAddress=".$contact_person->cpEmail."&conPhone=".$contact_person->cpPhone;
			
			if($for_someone == 1){
				$reqURL .= "&salutation=".$contact_person_else->cpTitleElse."&firstName=".$contact_person_else->cpFirstNameElse."&lastName=".$contact_person_else->cpLastNameElse;
				$reqURL .= "&phone="."";
			}
			else{
				$reqURL .= "&salutation=".$contact_person->cpTitle."&firstName=".$contact_person->cpFirstName."&lastName=".$contact_person->cpLastName;
				$reqURL .= "&phone="."";
			}
			
			$reqURL .= "&detailId=".$order_detail_id."&country=".$nationality."&output=json";
			$this->writeFile("checkOutCustomer.txt", $reqURL);
			
			$result = $this->CURL($reqURL);	
		}
		echo $result;
		
		
		/*$query = "select contact_person from order_hotel 
				where order_id = '".$order_id."'   
				and token = '".$token."'";											
		$contact_person = $this->Modul_Model->execqueryreturn($query);
		
		$query = "select contact_person_else from order_hotel 
				where order_id = '".$order_id."'   
				and token = '".$token."'";											
		$contact_person_else = $this->Modul_Model->execqueryreturn($query);
		
		$query = "select for_someone from order_hotel 
				where order_id = '".$order_id."' 
				and token = '".$token."'";							
		$for_someone = $this->Modul_Model->execqueryreturn($query);
		
		
		$order_detail_id 		= json_decode(json_encode(json_decode($order_detail_id[0]["order_detail_id"], true), JSON_UNESCAPED_SLASHES));
		$contact_person 		= json_decode(json_encode(json_decode($contact_person[0]["contact_person"], true), JSON_UNESCAPED_SLASHES));
		$contact_person_else	= json_decode(json_encode(json_decode($contact_person_else[0]["contact_person_else"], true), JSON_UNESCAPED_SLASHES));
		
		$reqURL  = $this->get_api_domain() . "/checkout/checkout_customer?token=" . $token;
		$reqURL .= "&conSalutation=".$contact_person->cpTitle."&conFirstName=".$contact_person->cpFirstName."&conLastName=".$contact_person->cpLastName;
		$reqURL .= "&conEmailAddress=".$contact_person->cpEmail."&conPhone=".$contact_person->cpPhone;
		
		if($for_someone == 1){
			$reqURL .= "&salutation=".$contact_person_else->cpTitleElse."&firstName=".$contact_person_else->cpFirstNameElse."&lastName=".$contact_person_else->cpLastNameElse;
			$reqURL .= "&phone="."";
		}
		else{
			$reqURL .= "&salutation=".$contact_person->cpTitle."&firstName=".$contact_person->cpFirstName."&lastName=".$contact_person->cpLastName;
			$reqURL .= "&phone="."";
		}
		
		$reqURL .= "&detailId=".$order_id."&output=json";
		$this->writeFile("checkOutCustomer.txt", $reqURL);
		
		$result = $this->CURL($reqURL);		
		
		echo $result;*/
	}
	
	function check_out_payment2(){
		$token = $this->get_token();
		
		$diagnostic = array("confirm"=>"success");
		
		/*array("type" => "banktransfer", "text" => "Bank Transfer", "link" => "https://api-sandbox.tiket.com/checkout/checkout_payment/2", "message"=>"", 
									"infos"=>array("confirm_checkbox"=>"0", "confirm_checkbox_text"=>"Jika anda setuju dengan kebijakan dibawah, silahkan klik tombol \"Selesaikan Pemesanan\"", "info1"=>"", "info2"=>"<b>Kami menerima pembayaran melalui</b>"))*/
									
		$available_payment = array(
								array("type" => "atm", "text" => "ATM", "link" => "https://api.tiket.com/checkout/checkout_payment/35", "message"=>"", 
									"infos"=>array("confirm_checkbox"=>"1", "confirm_checkbox_text"=>"Mohon menyetujui untuk membayar hanya melalui ATM (tidak berlaku untuk e-banking & m-banking)", "info1"=>"<b>Informasi Penting: </b>", "info2"=>"")),
								//array("type" => "creditcard", "text" => "Kartu Kredit", "link" => "http://tiket.com/payment/checkout_payment?checkouttoken=".$token, "message"=>""),
								array("type" => "creditcard", "text" => "Kartu Kredit", "link" => "https://tiket.com/payment/checkout_payment?checkouttoken=".$token, "message"=>"")
							);
		
		$data = array();
		$data["diagnostic"] = $diagnostic;
		$data["available_payment"] = $available_payment;
		
		echo json_encode($data);
	}
	
	function check_out_payment(){
		/*$token = "b39f6a830dac83e3b3d2c9963ca4eefe02e56572";
		
		$available_payment = array("type" => "ATM", "link" => "https://api-sandbox.tiket.com/checkout/checkout_payment/35");
		
		$data = array();
		$data["available_payment"] = $available_payment;
		
		echo json_encode($data);*/
		
		$token = $this->get_token();
		$reqURL = $this->get_api_domain() . "/checkout/checkout_payment?token=".$token."&v=3&output=json";
		
		$result = $this->CURL($reqURL);		
		echo $result;
	}
	
	function get_payment(){
		$token			= $this->get_token();
		$email			= $this->input->post('email');
		$orderID		= $this->input->post('orderID');
		
		$btn_booking	= $this->input->post('btn_booking');
		$link			= $this->input->post('link');
		
		$reqURL = $link;
		
		$quote  = "?";
		if (strpos($reqURL, '?') !== false) {
			//TRUE
			$quote = "";
		}
		
		$x = "currency=IDR";
		$reqURL = $reqURL . $quote . "&token=".$token."&checkouttoken=".$token."&btn_booking=".$btn_booking."&".$x."&v=3&output=json"; //
		
		$result = $this->CURL($reqURL);		
		
		
		$q = "update order_flight set email = '".$email."', payment = ".$btn_booking." where order_id = '".$orderID."' and token = '".$token."' ";
		if($this->Modul_Model->execquery($q)){
			
		}
		
		$q = "update order_hotel set email = '".$email."', payment = ".$btn_booking." where order_id = '".$orderID."' and token = '".$token."' ";
		if($this->Modul_Model->execquery($q)){
			
		}
		
		
		$this->writeFile("getPayment.txt", $reqURL);
		$this->writeFile("getPaymentResult.txt", $result);
		
		echo $result;
	}
	
	function get_my_order(){
		$token		= $this->input->post('token');
		$email		= $this->input->post('email');
		$orderID	= $this->input->post('orderID');
		
		$reqURL 	= $this->get_api_domain() . "/check_order?order_id=".$orderID."&email=".$email."&token=".$token."&v=3&output=json";
		
		$result 	= $this->CURL($reqURL);		
		$result		= json_decode($result, true);
		
		if(isset($result["result"])){
			$orderCartDetail = $result["result"]["order__cart_detail"];
		
			$order_id = $result["result"]["order_id"];
			$order_type = "";
			
			$in = 0;
			foreach($orderCartDetail as $data){
				$order_type				= $data["order_type"];
				
				if($order_type == "flight"){
					$order_type = "flight";
					$order_detail_id	= $data["order_detail_id"];
					$departure_city		= $data["detail"]["departure_city"];
					$arrival_city		= $data["detail"]["arrival_city"];
					$trip				= $data["detail"]["trip"];
					$flight_type		= "";
					
					$query = "select ifnull(flight_id, '0') as 'flight_id', departure_city_name, arrival_city_name  from order_flight 
							where order_id = '".$order_id."' and order_detail_id = '".$order_detail_id."'";
					
					$kecret = $this->Modul_Model->execqueryreturn($query);
					$flight_id				= $kecret[0]["flight_id"];						
					$departure_city_name	= $kecret[0]["departure_city_name"];
					$arrival_city_name		= $kecret[0]["arrival_city_name"];
					
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
					
					$result["result"]["order__cart_detail"][$in]["flight_detail"] = $f;
					$result["result"]["order__cart_detail"][$in]["departure_city_name"] = $departure_city_name;
					$result["result"]["order__cart_detail"][$in]["arrival_city_name"] = $arrival_city_name;
					
					//array_push($bookFlight["myorder"]["data"][$in]["flight_detail"], $f);
				}
				else if($order_type == "hotel"){
					$order_type = "hotel";
					$order_detail_id		= $data["order_detail_id"];
					
					$query = "select hotel, room, contact_person, for_someone, contact_person_else from order_hotel 
							where order_id = '".$order_id."' and order_detail_id = '".$order_detail_id."' ";											
					$kepet = $this->Modul_Model->execqueryreturn($query);
					
					$hotel 					= json_decode(json_encode(json_decode($kepet[0]["hotel"], true), JSON_UNESCAPED_SLASHES));
					$room 					= json_decode(json_encode(json_decode($kepet[0]["room"], true), JSON_UNESCAPED_SLASHES));
					$contact_person 		= json_decode(json_encode(json_decode($kepet[0]["contact_person"], true), JSON_UNESCAPED_SLASHES));
					$for_someone			= json_decode(json_encode(json_decode($kepet[0]["for_someone"], true), JSON_UNESCAPED_SLASHES));
					$contact_person_else	= json_decode(json_encode(json_decode($kepet[0]["contact_person_else"], true), JSON_UNESCAPED_SLASHES));
					
					$result["result"]["order__cart_detail"][$in]["hotel_detail"] = $hotel;
					$result["result"]["order__cart_detail"][$in]["room_detail"] = $room;
					$result["result"]["order__cart_detail"][$in]["contact_person"] = $contact_person;
					$result["result"]["order__cart_detail"][$in]["for_someone"] = $for_someone;
					$result["result"]["order__cart_detail"][$in]["contact_person_else"] = $contact_person_else;
				}
				else{
					array_push($deleteDataIndex, $in);
				}
				$in++;
			}
			$result["result"]["order_type"] = $order_type;
			
			$result = json_encode($result);
			//echo $result;
		}
		else{
			$result = "[]";
		}
		
		
		$this->writeFile("q_myorder.txt", $result);
		
		echo $result;
	}
	
	function get_history_booking(){
		$token		= $this->get_token();;
		$email		= $this->input->post('email');
		
		$q = "select order_id, 'flight' as type from order_flight where email = '".$email."' union
				select order_id, 'hotel' as type from order_hotel where email = '".$email."' 
				group by order_id order by order_id desc";
				
		$q = $this->Modul_Model->execqueryreturn($q);
		
		$x = 0;
		foreach($q as $data){
			$order_id		= $data["order_id"];
			$type			= $data["type"];
			
			//$detail			= $this->Modul_Model->execqueryreturn("");
			
			$detailOrder	= $this->check_order($order_id, $email, $token);			
			
			/*$do				= $detailOrder["result"]["order__cart_detail"];
			$y	= 0;
			foreach($do as $d){
				$order_detail_id	= $d["order_detail_id"];
				$order_type 		= $d["order_type"];
				
				if($order_type == "flight"){
					$trip 				= $d["detail"]["trip"];
					$departure_city 	= $d["detail"]["departure_city"];
					$arrival_city	 	= $d["detail"]["arrival_city"];
					
					$query = "select flight_id, departure_city_name, arrival_city_name from order_flight 
							where order_id = '".$order_id."' and order_detail_id = '".$order_detail_id."' and flight_type = '".$trip."'  
							and departure_city = '".$departure_city."' and arrival_city = '".$arrival_city."'";
							
					$abc				 = $this->Modul_Model->execqueryreturn($query);
					$departure_city_name = $abc[0]["departure_city_name"];
					$arrival_city_name	 = $abc[0]["arrival_city_name"];
					$flight_id			 = $abc[0]["flight_id"];
					
					if($trip == "trip"){
						$f = $this->get_book_flight_data($flight_id, true, $token);
					}
					else{
						$f = $this->get_book_flight_data($flight_id, false, $token);
					}
					
					$detailOrder["result"]["order__cart_detail"][$y]["flight_detail"]		= $f;
					$detailOrder["result"]["order__cart_detail"][$y]["departure_city_name"] = $departure_city_name;
					$detailOrder["result"]["order__cart_detail"][$y]["arrival_city_name"]	= $arrival_city_name;
					
				}
				
				$y++;
			}*/
			
			$q[$x]["check_order"] = $detailOrder;
			
			/*$query = "select ifnull(flight_id, '0') as 'flight_id' from order_flight 
					where order_id = '".$order_id."' and order_detail_id = '".$order_detail_id."'  and flight_type = '".$trip."' 
					and departure_city = '".$departure_city."' and arrival_city = '".$arrival_city."' 
					and token = '".$token."'";	
			//$this->writeFile("q.txt", $query);
			$flight_id = $this->Modul_Model->execqueryreturn($query);
			$flight_id = $flight_id[0]["flight_id"];*/
			
			$x++;
		}
		
		$q = json_encode($q);
		
		$this->writeFile("historyBooking.txt", $q);
		
		echo $q;
	}
	
	function check_order($orderID, $email, $token){
		$reqURL 	= $this->get_api_domain() . "/check_order?order_id=".$orderID."&email=".$email."&token=".$token."&v=3&output=json";
		
		$result 	= $this->CURL($reqURL);		
		$result		= json_decode($result, true);
		
		return $result;
	}
	
	function CURL($reqURL, $ssl = FALSE){
		
		$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
		$curl = curl_init();

		$curlArr = array(
		  CURLOPT_URL				=> $reqURL,
		  CURLOPT_RETURNTRANSFER	=> true,
		  CURLOPT_ENCODING			=> "",
		  CURLOPT_MAXREDIRS			=> 10,
		  CURLOPT_TIMEOUT			=> 300,
		  CURLOPT_USERAGENT			=> "twh:[BUSINESS_ID];[BUSINESS_NAME];",
		  CURLOPT_HTTP_VERSION		=> CURL_HTTP_VERSION_1_1,
		  CURLOPT_HTTPHEADER		=> array(),
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
			$param .= "&" . $key . "=" . str_replace(" ", "%20", $val);
		}
		return $param;
		
	}
	
	function q(){
		$q		= $this->input->get('q');
		$q = $this->Modul_Model->execqueryreturn($q);
		
		$q = json_encode($q);
		echo $q;
	}
	
	function writeFile($path, $content){
		$this->load->helper('file');
		$data = $content;

		if ( !write_file($path, $data)){
			 echo 'Unable to write the file';
		}
	}
}

