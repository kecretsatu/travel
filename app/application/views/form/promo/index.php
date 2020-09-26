<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Form Promo</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<input type="hidden" id="crud"  name="crud" value="" />
					<input type="file" id="file"  name="file" onchange="readURL(this);" style="display:none" />
					
					<div class="col-md-12">
						<a class="promoImg" onclick="$('#file').click()"><div ><span><i class="fa fa-plus"></i>&nbsp;&nbsp;Change Image</span></div></a>
					</div>
					
					<div class="form-group">
						<label class="control-label col-sm-3" for="nama">ID: </label>
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm" readonly="readonly" id="id" name="id" placeholder="ID" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="nama">Type: </label>
						<div class="col-sm-8">
							<select id="type" name="type" class="form-control">
								<option value="flight">Flight</option>
								<option value="hotel">Hotel</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="nama">Name: </label>
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm" id="name" name="name" placeholder="Name" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="nama">Promo Code: </label>
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm" id="promo_code" name="promo_code" placeholder="Promo Code" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="nama">Description: </label>
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm" id="description" name="description" placeholder="Description" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="nama">Requirement: </label>
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm" id="requirement" name="requirement" placeholder="Requirement" />
						</div>
					</div>
						
				</form>
				
				<div class="alert" role="alert"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="promo.submit()">Submit</button>
			</div>
		</div>

	</div>
</div>
<script>
function readURL(input){
	var ext = input.files[0]['name'].substring(input.files[0]['name'].lastIndexOf('.') + 1).toLowerCase();
	
	if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.promoImg > div').css('background-image', 'url(' + e.target.result + ')');
		}
		
		reader.readAsDataURL(input.files[0]);
	}
}
</script>
<style>
.promoImg{
	display:block;
	width:100%;
	height:200px;
	border:1px solid #ccc;
	cursor:pointer;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	margin-bottom:10px;
}
.promoImg > div{
	width:100%;
	height:100%;
	
	background-repeat:no-repeat;
	background-size:cover;
	background-position:center;
}
.promoImg > div > span{
	display:inline-block;
	position: relative;
	color:#ccc;
	font-size:2em;
	
	top: 50%;
	transform: translateY(-50%);
	
	left: 50%;
	transform: translateX(-50%);
}
.promoImg:hover > div{
	background-color:rgba(0, 0, 0, 0.8);
}
</style>