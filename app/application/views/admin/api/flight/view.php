<?php
$data = json_decode($data);
$result = $data->departures->result;
?>

<div class="col-md-12" style="margin:0px; padding:0px;">
	
	<div class="col-md-12" style="padding:0px;">
		<div class="panel panel-primary">
			<div class="panel-body">
				<table id="flight" class="table table-striped">
					<thead>
						<th>Logo</th>
						<th>Flight</th>
						<th>Duration</th>
						<th>Price</th>
						<th class="action"></th>
					</thead>
					<tbody>
						<?php
						foreach($result as $r){
							echo '<tr>';
							echo '<td style="width:80px"><img src="'.$r->image.'" style="width:100%" /></td>';
							echo '<td>';
							echo '<b>'.$r->airlines_name.'</b> ('.$r->flight_number.')<br />';
							echo ''.$r->departure_city_name.'('.$r->departure_city.') - '.$r->arrival_city_name.' ('.$r->arrival_city.')<br />';
							echo '</td>';
							echo '<td>';
							echo ''.$r->departure_flight_date_str.'<br />';
							echo ''.$r->departure_city.'('.$r->simple_departure_time.') - '.$r->arrival_city.' ('.$r->simple_arrival_time.')<br />';
							echo ''.$r->duration.'';
							echo '</td>';
							echo '<td>';
							echo ''.$r->price_adult.'';
							echo '</td>';
							echo '<td></td>';
							echo '</tr>';
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
</div>