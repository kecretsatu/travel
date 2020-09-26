<?php
	session_start();
	header('Content-type: application/json');
	
	$result;
	$query = '';
	
	ini_set('max_execution_time', 300);
	
	$token = "";
	
	try{
		if(isset($_GET["reqtoken"])){
			echo getToken();
			exit;
		}
		if(isset($_POST["token"])){
			$token = $_POST["token"];
		}
		
		if(isset($_POST["POST"])){
			if($_POST["POST"] == "getToken"){
				$t = getToken();
				echo $t;
				exit;
			}
			if($_POST["POST"] == "getListCountry"){
				if($token == ""){
					$token = getToken();
					$token = json_decode($token);
					
					if($token->result == 1){
						$token = $token -> response[0] -> token;
					}
				}
				$listCountry = getListCountry($token);				
				
				echo '' . $listCountry . '';
				exit;
				
			}
			if($_POST["POST"] == "getAirport"){
				if($token == ""){
					$token = getToken();
					$token = json_decode($token);
					
					if($token->result == 1){
						$token = $token -> response[0] -> token;
					}
				}
				$airport = getAirport($token);				
				
				echo '' . $airport . '';
				exit;
				
			}
			if($_POST["POST"] == "getFlight"){	
				if($token == ""){
					$token = getToken();
					$token = json_decode($token);
					
					if($token->result == 1){
						$token = $token -> response[0] -> token;
					}
				}
				
				$flight = getFlight($_POST["d"], $_POST["a"], $_POST["date"], $_POST["ret_date"]
								, $_POST["adult"], $_POST["child"], $_POST["infant"], $token);
								
				echo $flight;				
				exit;
			}
			if($_POST["POST"] == "getFlightData"){	
				if($token == ""){
					$token = getToken();
					$token = json_decode($token);
					
					if($token->result == 1){
						$token = $token -> response[0] -> token;
					}
				}
				
				$flightData = getFlightData2($_POST["flight_id"], $_POST["ret_flight_id"], $_POST["date"], $_POST["ret_date"], $token);
						
				writeFile("flightData.txt", $flightData);
				
				echo $flightData;				
				exit;
			}
			if($_POST["POST"] == "bookFlight"){
				$flight_id			= $_POST["flight_id"]; 
				$departure_city 	= $_POST["departure_city"]; 
				$departure_city_name= $_POST["departure_city_name"]; 
				$arrival_city		= $_POST["arrival_city"]; 
				$arrival_city_name	= $_POST["arrival_city_name"]; 
				$real_flight_date 	= $_POST["real_flight_date"]; 
				$airlines_name 		= $_POST["airlines_name"]; 
				$flight_number 		= $_POST["flight_number"]; 
				
				$ret_flight_id = "";
				if(isset($_POST["ret_flight_id"])){
					$ret_flight_id 			= $_POST["ret_flight_id"]; 
					$ret_departure_city 	= $_POST["ret_departure_city"]; 
					$ret_departure_city_name= $_POST["ret_departure_city_name"]; 
					$ret_arrival_city		= $_POST["ret_arrival_city"]; 
					$ret_arrival_city_name	= $_POST["ret_arrival_city_name"]; 
					$ret_real_flight_date 	= $_POST["ret_real_flight_date"]; 
					$ret_airlines_name 		= $_POST["ret_airlines_name"]; 
					$ret_flight_number 		= $_POST["ret_flight_number"];
				}
				
				$adult = $_POST["adult"]; $child = $_POST["child"]; $infant = $_POST["infant"]; 
				$contact_person = $_POST["contact_person"]; $passenger = $_POST["passenger"]; 
				
				if($token == ""){
					$token = getToken();
					$token = json_decode($token);
					
					if($token->result == 1){
						$token = $token -> response[0] -> token;
					}
				}
				
				$query = "insert into order_flight (departure_city, arrival_city, departure_city_name, arrival_city_name, real_flight_date, airlines_name, flight_number, flight_id, flight_type, contact_person, passenger, token, status, date_saved) 
						values ('".$departure_city."', '".$arrival_city."', '".$departure_city_name."', '".$arrival_city_name."', '".$real_flight_date."', '".$airlines_name."', '".$flight_number."', '".$flight_id."', 'trip', '".$contact_person."', '".$passenger."', '".$token."', '1', now())";
				
				execquery("order_flight", $query);
				
				if($ret_flight_id != ""){
					$query = "insert into order_flight (departure_city, arrival_city, departure_city_name, arrival_city_name, real_flight_date, airlines_name, flight_number, flight_id, flight_type, contact_person, passenger, token, status, date_saved) 
						values ('".$ret_departure_city."', '".$ret_arrival_city."', '".$ret_departure_city_name."', '".$ret_arrival_city_name."', '".$ret_real_flight_date."', '".$ret_airlines_name."', '".$ret_flight_number."', '".$ret_flight_id."', 'round', '".$contact_person."', '".$passenger."', '".$token."', '1', now())";
					
					execquery("order_flight", $query);
				}
				
				$bookFlight = bookFlight($flight_id, $ret_flight_id, $adult, $child, $infant, $contact_person, $passenger, $token);
				
				echo $bookFlight;
			}
			if($_POST["POST"] == "getBookFlight"){
				
				if($token == ""){
					$token = getToken();
					$token = json_decode($token);
					
					if($token->result == 1){
						$token = $token -> response[0] -> token;
					}
				}
				
				$bookFlight = getBookFlight($token);
				$bookFlight = json_decode($bookFlight, true);
				
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
							
							$query = "select ifnull(flight_id, '0') from order_flight 
									where departure_city = '".$departure_city."' and arrival_city = '".$arrival_city."' and real_flight_date = '".$real_flight_date."' 
									and airlines_name = '".$airlines_name."' and flight_number = '".$flight_number."' and flight_type = '".$trip."' 
									and token = '".$token."'";			
							$flight_id = execqueryreturn("order_flight", $query);
							
							$query = "select departure_city_name from order_flight 
									where departure_city = '".$departure_city."' and arrival_city = '".$arrival_city."' and real_flight_date = '".$real_flight_date."' 
									and airlines_name = '".$airlines_name."' and flight_number = '".$flight_number."' and flight_type = '".$trip."' 
									and token = '".$token."'";						
							$departure_city_name = execqueryreturn("order_flight", $query);
							
							$query = "select arrival_city_name from order_flight 
									where departure_city = '".$departure_city."' and arrival_city = '".$arrival_city."' and real_flight_date = '".$real_flight_date."' 
									and airlines_name = '".$airlines_name."' and flight_number = '".$flight_number."' and flight_type = '".$trip."' 
									and token = '".$token."'";						
							$arrival_city_name = execqueryreturn("order_flight", $query);
							
							if($trip == "trip"){
								$flight_type = "departures";
								$f = getFlightData($flight_id, true, $token);
							}
							else{
								$flight_type = "returns";
								$f = getFlightData($flight_id, false, $token);
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
					
					for($i = 0; $i < count($deleteDataIndex); $i++ ){
						//unset($bookFlight["myorder"]["data"][$deleteDataIndex[$i]]);						
					}
				
					$bookFlight = json_encode($bookFlight);
					writeFile("myOrder.txt", $bookFlight);
									
					echo $bookFlight;	
				}
				else{
					echo "[]";
				}
			}
			if($_POST["POST"] == "getArea"){	
				if($token == ""){
					$token = getToken();
					$token = json_decode($token);
					
					if($token->result == 1){
						$token = $token -> response[0] -> token;
					}
				}
				
				$area = getArea($_POST["q"], $token);
				echo $area;
				
				exit;
			}
			if($_POST["POST"] == "getHotel"){
				
				if($token == ""){
					$token = getToken();
					$token = json_decode($token);
					
					if($token->result == 1){
						$token = $token -> response[0] -> token;
					}
				}
				
				$hotel = getHotel($_POST["q"], $_POST["startdate"], $_POST["enddate"]
								, $_POST["adult"], $_POST["child"], $_POST["night"], $_POST["room"], $_POST["page"], $token);
				
				echo $hotel;
				
				
				exit;
			}
			if($_POST["POST"] == "getHotelDetail"){	
				if($token == ""){
					$token = getToken();
					$token = json_decode($token);
					
					if($token->result == 1){
						$token = $token -> response[0] -> token;
					}
				}
				
				$hotelDetail = getHotelDetail($_POST["uri"], $token);
				echo $hotelDetail;
				
				
				exit;
			}
			if($_POST["POST"] == "getRoom"){	
				
				if($token == ""){
					$token = getToken();
					$token = json_decode($token);
					
					if($token->result == 1){
						$token = $token -> response[0] -> token;
					}
				}
				
				$hotel = getHotel($_POST["q"], $_POST["startdate"], $_POST["enddate"]
								, $_POST["adult"], $_POST["child"], $_POST["night"], $_POST["room"], $token);
				
				echo $hotel;
				
				
				exit;
			}
			if($_POST["POST"] == "bookHotel"){	
				
				if($token == ""){
					$token = getToken();
					$token = json_decode($token);
					
					if($token->result == 1){
						$token = $token -> response[0] -> token;
					}
				}
				
				$hotel					= $_POST["hotel"];
				$hotel_id				= $_POST["hotel_id"];
				$room					= $_POST["room"];
				$room_id				= $_POST["room_id"];
				$start_date				= $_POST["start_date"];
				$end_date				= $_POST["end_date"];
				$contact_person			= $_POST["contact_person"];
				$forSomeOneElse 		= $_POST["forSomeOneElse"];
				$contact_person_else	= $_POST["contact_person_else"];
				
				$query = "insert into order_hotel (hotel_id, hotel, room_id, room, start_date, end_date, contact_person, for_someone, contact_person_else, token, status, date_saved)
							values ('".$hotel_id."', '".$hotel."', '".$room_id."', '".$room."', '".$start_date."', '".$end_date."', '".$contact_person."', '".$forSomeOneElse."', '".$contact_person_else."', '".$token."', 1, now())";
				
				execquery("order_hotel", $query);
				
				$bookHotel = bookHotel($_POST["bookUri"], $token);
				
				echo $bookHotel;
				
				
				exit;
			}
			if($_POST["POST"] == "getBookHotel"){
				
				if($token == ""){
					$token = getToken();
					$token = json_decode($token);
					
					if($token->result == 1){
						$token = $token -> response[0] -> token;
					}
				}
				
				$bookFlight = getBookFlight($token);
				$bookFlight = json_decode($bookFlight, true);
				
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
							$hotel = execqueryreturn("order_hotel", $query);
							
							$query = "select room from order_hotel 
									where hotel_id = '".$hotel_id."' and room_id = '".$room_id."' and start_date = '".$startdate."' and end_date = '".$enddate."' 
									and token = '".$token."'";							
							$room = execqueryreturn("order_hotel", $query);
							
							$query = "select contact_person from order_hotel 
									where hotel_id = '".$hotel_id."' and room_id = '".$room_id."' and start_date = '".$startdate."' and end_date = '".$enddate."' 
									and token = '".$token."'";							
							$contact_person = execqueryreturn("order_hotel", $query);
							
							$query = "select for_someone from order_hotel 
									where hotel_id = '".$hotel_id."' and room_id = '".$room_id."' and start_date = '".$startdate."' and end_date = '".$enddate."' 
									and token = '".$token."'";							
							$for_someone = execqueryreturn("order_hotel", $query);
							
							$query = "select contact_person_else from order_hotel 
									where hotel_id = '".$hotel_id."' and room_id = '".$room_id."' and start_date = '".$startdate."' and end_date = '".$enddate."' 
									and token = '".$token."'";							
							$contact_person_else = execqueryreturn("order_hotel", $query);
							
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
					
					for($i = 0; $i < count($deleteDataIndex); $i++ ){
						//unset($bookFlight["myorder"]["data"][$deleteDataIndex[$i]]);						
					}
				
					$bookFlight = json_encode($bookFlight);
					writeFile("myOrderHotel.txt", $bookFlight);
									
					echo $bookFlight;	
				}
				else{
					echo "[]";
				}
			}
			if($_POST["POST"] == "removeOrder"){	
				
				if($token == ""){
					$token = getToken();
					$token = json_decode($token);
					
					if($token->result == 1){
						$token = $token -> response[0] -> token;
					}
				}
				$removeOrder = removeOrder($_POST["deleteUri"], $token);
				
				echo $removeOrder;
				
				
				exit;
			}
			if($_POST["POST"] == "checkOutOrder"){	
				
				if($token == ""){
					$token = getToken();
					$token = json_decode($token);
					
					if($token->result == 1){
						$token = $token -> response[0] -> token;
					}
				}
				$checkOutOrder = checkOutOrder($token, $_POST["orderID"]);
				
				echo $checkOutOrder;
				
				
				exit;
			}
		
		}
		else{
			echo '[{"error":"Permission Denied"}]';
		}
	}
	catch(Exception $e){
		echo '[{"error":"'.$e->getMessage().'"}]'; //'Message: ' .$e->getMessage();
		$myfile = fopen("logs.txt", "a") or die("Unable to open file!");
		$txt = $e->getMessage();
		fwrite($myfile, "\n". $txt);
		fclose($myfile);
	}

function getToken(){
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

function getListCountry($token){
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
	$reqURL = "http://api-sandbox.tiket.com/general_api/listCountry?token=".$token."&v=3&output=json";
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $reqURL,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 300,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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

function getAirport($token){
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
	$reqURL = "http://api-sandbox.tiket.com/flight_api/all_airport?token=".$token."&v=3&output=json";
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $reqURL,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 300,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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

function getFlight($d, $a, $date, $ret_date, $adult, $child, $infant, $token){
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
	$reqURL = "http://api-sandbox.tiket.com/search/flight?d=".$d."&a=".$a."&date=".$date."&adult=".$adult."&child=".$child."&infant=".$infant."&token=".$token."&v=3&output=json";
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $reqURL,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 300,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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

function getFlightData2($flight_id, $ret_flight_id, $date, $ret_date, $token){
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
	
	//$reqURL = "http://api-sandbox.tiket.com/flight_api/get_flight_data?flight_id=".$flight_id."&date=2017-05-18&ret_flight_id=".$ret_flight_id."&token=".$token."&v=3&output=json";
	$reqURL = "http://api-sandbox.tiket.com/flight_api/get_flight_data?flight_id=".$flight_id."&date=".$date."&ret_flight_id=".$ret_flight_id."&ret_date=".$ret_date."&token=".$token."&v=3&output=json";
	
	writeFile("flightDataReqURL.txt", $reqURL);
	
	$ch = curl_init();
		
	curl_setopt($ch, CURLOPT_URL,$reqURL);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	
	$json_response = curl_exec($ch);
	curl_close($ch);
	
	

	//$result = json_decode($json_response,true);
		
	if ($json_response === False)
	{
		return False;
	}
	else
	{
		return $json_response;
	}

	// $curl = curl_init();

	// curl_setopt_array($curl, array(
	//   CURLOPT_URL => $reqURL,
	//   CURLOPT_RETURNTRANSFER => true,
	//   CURLOPT_ENCODING => "",
	//   CURLOPT_MAXREDIRS => 10,
	//   CURLOPT_TIMEOUT => 300,
	//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	//   CURLOPT_HTTPHEADER => array(),
	// ));

	// $response = curl_exec($curl);
	// $err = curl_error($curl);

	// curl_close($curl);
	
	// if ($err) {
	//   return "cURL Error #:" . $err;
	// } else {
	//   return $response;
	// }
}

function getFlightData($flight_id, $is_departure, $token){
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
	
	if($is_departure){
		$reqURL = "http://api-sandbox.tiket.com/flight_api/get_flight_data?flight_id=".$flight_id."&token=".$token."&v=3&output=json";
	}
	else{
		$reqURL = "http://api-sandbox.tiket.com/flight_api/get_flight_data?ret_flight_id=".$flight_id."&token=".$token."&v=3&output=json";
	}
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $reqURL,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 300,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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

function bookFlight($flight_id, $ret_flight_id, $adult, $child, $infant, $contact_person, $passenger, $token){
	$contact_person = json_decode($contact_person, true);
	$passenger = json_decode($passenger, true);
	
	$reqURL = "https:" . "//api-sandbox.tiket.com/order/add/flight?token=" . $token . "&output=json";

	if($ret_flight_id == ""){
		$reqURL .= "&flight_id=".$flight_id."&adult=".$adult."&child=".$child."&infant=".$infant."";
	}
	else{
		$reqURL .= "&flight_id=".$flight_id."&ret_flight_id=".$ret_flight_id."&adult=".$adult."&child=".$child."&infant=".$infant."";
	}
	
	$reqURL .= jsonObjectToParamURL($contact_person);
	$reqURL .= jsonObjectToParamURL($passenger);
	
	//$reqURL .= "&conSalutation=".$contact_person["cpTitle"]."&conFirstName=".$contact_person["cpFirstName"]."&conLastName=".$contact_person["cpLastName"]."&conPhone=".$contact_person["cpPhone"]."&conEmailAddress=".$contact_person["cpEmail"]."";
	
	/*for($i = 1; $i <= $adult; $i++){
		$reqURL .= "&ida" . $i . "=".""."&titlea" . $i . "=".$passenger["pAdultTitle" . $i]."&firstnamea" . $i . "=".$passenger["pAdultFirstname" . $i]."&lastnamea" . $i . "=".$passenger["pAdultlastname" . $i]."";
	}*/
	
	writeFile("bookFlightURI.txt", $reqURL);
	
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $reqURL,
	  CURLOPT_USERAGENT => $agent,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 300,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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
	
	return $reqURL;
}

function getBookFlight($token){
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
	$reqURL = "http://api-sandbox.tiket.com/order?&token=".$token."&v=3&output=json";
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $reqURL,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 300,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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

function getArea($q, $token){
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
	$reqURL = "http://api-sandbox.tiket.com/search/autocomplete/hotel?q=".$q."&token=".$token."&v=3&output=json";
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $reqURL,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 300,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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

function getHotel($q, $startdate, $enddate, $adult, $child, $night, $room, $page, $token){
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
	$reqURL = "http://api-sandbox.tiket.com/search/hotel?q=".$q."&startdate=".$startdate."&enddate=".$enddate."&adult=".$adult."&child=".$child."&night=".$night."&room=".$room."&page=".$page."&token=".$token."&v=3&output=json";
	
	writeFile("reqHotelURL.txt", $reqURL);
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $reqURL,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 300,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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
	
function getHotelDetail($uri, $token){
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
	$reqURL = $uri."&token=".$token."&v=3&output=json";
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $reqURL,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 300,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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

function bookHotel($bookUri, $token){
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
	$reqURL = $bookUri . "&token=".$token."&v=3&output=json";
	
	
	writeFile("bookUriHotel.txt", $reqURL);
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $reqURL,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 300,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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

function removeOrder($deleteUri, $token){
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
	$reqURL = $deleteUri . "&token=".$token."&v=3&output=json";
	
	writeFile("deleteUri.txt", $reqURL);
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $reqURL,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 300,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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

function checkOutOrder($token, $orderID){
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
	//$reqURL = "http://api-sandbox.tiket.com/general_api/listCountry?token=".$token."&v=3&output=json";
	$reqURL = "http://api-sandbox.tiket.com/order/checkout/".$orderID."/IDR?token=".$token."&v=3&output=json";
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $reqURL,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 300,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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

function writeFile($file, $content){
	$myfile = fopen($file, "w") or die("Unable to open file!");
	fwrite($myfile, $content);
	fclose($myfile);
}
function jsonObjectToParamURL($json){
	$param = "";
	
	foreach($json as $key => $val)
	{
		$param .= "&" . $key . "=" . $val;
	}
	return $param;
	
}

function koneksi()
{
	$con = mysqli_connect("localhost","root","");
	if (!$con)
	  {
	  die('Sorry, connection failed to server ' );
	  }
	  mysqli_select_db($con,"tiketravel");
	return $con;
}
function execquery($tabel,$query)
{
	$con=koneksi();
	mysqli_select_db($con, $tabel);
	if (!mysqli_query($con, $query))
	{
		return mysqli_error($con);
	}
	else{
		return 1;
	}
	mysqli_close($con);
}
function execqueryreturn($tabel,$query)
{
	$con=koneksi();
	//mysqli_select_db($con, $tabel);
	$x=''; 
	$result	= mysqli_query($con, $query);
	$row 	= mysqli_fetch_array($result, MYSQLI_NUM);
	$x 		= $row[0];
	
	mysqli_free_result($result);
	return $x;
	mysqli_close($con);
}

/*function getFlightData3($flight_id, $ret_flight_id, $token){
	$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
	
	$reqURL = "http://api-sandbox.tiket.com/flight_api/get_flight_data?flight_id=".$flight_id."&date=2017-05-18&ret_flight_id=".$ret_flight_id."&token=".$token."&v=3&output=json";
	
	writeFile("flightDataReqURL.txt", $reqURL);
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $reqURL,
	  CURLOPT_USERAGENT => $agent,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 300,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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
}*/

?>




