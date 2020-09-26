<!-- Page Content -->
<div class="page-title-2">
	<h1>Ubah Password</h1>
</div>
<div class="page-content">
	<div class=" no-background no-padding">
		<div class="alert" role="alert"><p></p></div>
	</div>
	<div class="container">
		<div class="table-responsive col-md-12">
			<br />
			<form id="formChangePassword" class="form-horizontal">
				<div class="form-group">
					<label class="control-label col-sm-2" for="email">Password Lama:</label>
					<div class="col-sm-3">
						<input type="password" id="oldp" name="oldp" class="form-control" placeholder="Masukkan Password Lama">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="email">Password Baru:</label>
					<div class="col-sm-3">
						<input type="password" id="newp" name="newp" class="form-control" placeholder="Masukkan Password baru">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="email">Ulangi Password Baru:</label>
					<div class="col-sm-3">
						<input type="password" id="new2p" name="new2p" class="form-control" placeholder="Ulangi Password Baru">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="email"></label>
					<div class="col-sm-3">
						<button class="btn btn-sm btn-success" onclick="changePassword()"><i class="fa fa-key"></i>&nbsp;&nbsp;Ubah Password</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class=" no-background no-padding">
		<?php //echo $this->pagination->create_links(); ?>
	</div>
</div>

<script>
$(document).ready(function(e){
	
});

function changePassword(){
	var oldp = $("#oldp").val();
	var newp = $("#newp").val();
	var new2p = $("#new2p").val();
	
	$('.alert').hide();
	if(oldp == "" || newp == "" || new2p == ""){
		showAlert('.alert', '.btn', "alert-danger", "Silahkan isi seluruh field untuk merubah password");
		
		return false;
	}
	
	$.ajax({type: "POST", url: "<?php echo $changeURL; ?>", dataType: 'json', data: $('#formChangePassword').serializeArray(), 
		success: function(data){
		   if(data[0].return == 1){
				showAlert('.alert', '.btn', "alert-success", data[0].msg);
				clearForm();
		   }
		   else{
			   showAlert('.alert', '.btn', "alert-danger", data[0].msg);
				clearForm();
		   }
		},
		error: function (data) {
			showAlert('.alert', '.btn', "alert-danger", '<i class="fa fa-warning" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<strong>Mohon maaf !</strong> An error occurred. ' + JSON.stringify(data));
				clearForm();
		}
	});
	
}

function clearForm(){
	$("#formChangePassword input").val("");
}

</script>