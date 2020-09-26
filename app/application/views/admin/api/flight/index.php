<?php
$airport = $airport->all_airport->airport;
usort($airport, function($a, $b) { //Sort the array using a user defined function
    return strnatcasecmp($a->location_name, $b->location_name); //Compare the scores
});  
?>

<!-- Page Content -->
<div class="page-title-2">
	<h1>Flight API</h1>
	<div class="control">
		<a href="javascript:void(0)" onclick="request()" class="refresh"><i class="fa fa-refresh"></i>&nbsp; Refresh</a>
	</div>
</div>
<div class="page-content">
	<div class=" no-background no-padding">
		<div class="alert" role="alert"><p></p></div>
	</div>
	<div class="container">
		<br />
		<div class="table-responsive col-md-12">
			<form id="form" class="form-horizontal">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label col-sm-3" >Asal:</label>
							<div class="col-sm-9">
								<select name="departure" class="form-control">
									<?php
									foreach($airport as $a){
										if($a->country_id == "id"){
											echo '<option value="'.$a->airport_code.'">'.$a->location_name.'</option>';
										}
									}
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label col-sm-3" >Tujuan:</label>
							<div class="col-sm-9">
								<select name="arrival" class="form-control">
									<?php
									foreach($airport as $a){
										if($a->country_id == "id"){
											echo '<option value="'.$a->airport_code.'">'.$a->location_name.'</option>';
										}
									}
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label col-sm-3" >Berangkat:</label>
							<div class="col-sm-9">
								<input type="date" name="date" class="form-control" />
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label col-sm-3" >Dewasa:</label>
							<div class="col-sm-9">
								<input type="number" name="adult" class="form-control" value="1" />
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label col-sm-3" >Anak:</label>
							<div class="col-sm-9">
								<input type="number" name="child" class="form-control" value="0" />
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="control-label col-sm-3" >Bayi:</label>
							<div class="col-sm-9">
								<input type="number" name="infant" class="form-control" value="0" />
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="table-responsive col-md-12">
			<div id="content"></div>
		</div>
	</div>
	<div class=" no-background no-padding">
		<?php //echo $this->pagination->create_links(); ?>
	</div>
</div>

<script>

function request(){
	$("#content").html('<i class="fa fa-spinner fa-spin"></i>&nbsp;&nbsp;Mengambil data');
	$.ajax({type: "POST", url: "<?php echo base_url(); ?>flight/request", dataType: 'html', data: $("#form").serializeArray(), 
		success: function(data){
			$("#content").html(data);
			//parent.data = data;
			//alert(JSON.stringify(data));
			//parent.build();
		},
		error: function (data) {
			alert("error");
			//showAlert('.alert', '.btn', "alert-danger", '<i class="fa fa-warning" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<strong>Mohon maaf !</strong> An error occurred.');
		}
	});
}
</script>

<style>
#form label.control-label{
	text-align:left;
}
</style>

