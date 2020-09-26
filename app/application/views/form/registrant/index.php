<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Form Registrant</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<input type="hidden" id="crud"  name="crud" value="" />
					
					<div class="form-group">
						<label class="control-label col-sm-3" for="nama">ID: </label>
						<div class="col-sm-7">
							<input type="text" class="form-control input-sm" readonly="readonly" id="id" name="id" placeholder="Nama" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="nama">Title: </label>
						<div class="col-sm-7">
							<select class="form-control input-sm" id="title" name="title">
								<option value="Mr">Mr.</option>
								<option value="Mrs">Mrs.</option>
								<option value="Ms">Ms.</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="nama">First Name: </label>
						<div class="col-sm-7">
							<input type="text" class="form-control input-sm" id="first_name" name="first_name" placeholder="First Name" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="nama">Last Name: </label>
						<div class="col-sm-7">
							<input type="text" class="form-control input-sm" id="last_name" name="last_name" placeholder="Last Name" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="nama">Email: </label>
						<div class="col-sm-7">
							<input type="text" class="form-control input-sm" id="email" name="email" placeholder="Email" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="nama">Phone: </label>
						<div class="col-sm-7">
							<input type="text" class="form-control input-sm" id="phone" name="phone" placeholder="Phone" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="nama">Nationality: </label>
						<div class="col-sm-7">
							<input type="text" class="form-control input-sm" id="nationality" name="nationality" placeholder="Nationality" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="nama">Birth Date: </label>
						<div class="col-sm-7">
							<input type="text" class="form-control input-sm" id="birth_date" name="birth_date" placeholder="Birth Date" />
						</div>
					</div>
					
				</form>
				
				<div class="alert" role="alert"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="registrant.submit()">Submit</button>
			</div>
		</div>

	</div>
</div>