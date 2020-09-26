<!-- Page Content -->
<div class="page-title-2">
	<h1>Registrant</h1>
	<div class="control">
		<a href="javascript:void(0)" class="add" onclick="registrant.add()"><i class="fa fa-plus"></i>&nbsp; Add</a>
		<span>|</span>
		<a href="#" class="remove"><i class="fa fa-trash"></i>&nbsp; Delete</a>
		<span>|</span>
		<a href="javascript:void(0)" onclick="registrant.refresh()" class="refresh"><i class="fa fa-refresh"></i>&nbsp; Refresh</a>
	</div>
</div>
<div class="page-content">
	<div class=" no-background no-padding">
		<div class="alert" role="alert"><p></p></div>
	</div>
	<div class="container">
		<div class="table-responsive">
			<table id="registrant" class="table table-striped">
				<thead>
					<th class="check"><input type="checkbox" /></th>
					<th>Nama</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Nationality</th>
					<th>Birth Date</th>
					<th class="action"></th>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
	<div class=" no-background no-padding">
		<?php //echo $this->pagination->create_links(); ?>
	</div>
</div>

<script>
var registrant;
$(document).ready(function(e){
	registrant = new Registrant();
	registrant.refresh();
});

var Registrant = function(){
	var table	= $("#registrant");
	var dialog	= $("#myModal");
	var form	= $("#myModal form");
	var data	= [];
	
	Registrant.prototype.refresh = function(){
		var parent = this;
		$.ajax({type: "POST", url: "<?php echo $getURL; ?>", dataType: 'json', data: data, 
			success: function(data){
				parent.data = data;
				parent.build();
			},
			error: function (data) {
				alert("error");
				//showAlert('.alert', '.btn', "alert-danger", '<i class="fa fa-warning" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<strong>Mohon maaf !</strong> An error occurred.');
			}
		});
	};
	
	Registrant.prototype.build = function(){
		var parent = this;
		var items = "";
		
		var n = 0;
		//for(var i = 1; i < 10; i++){
		$.each(parent.data, function(index, array){
			n++;
			items += '<tr>';
			items += '<td class="check"><input type="checkbox" /></td>';
			items += '<td>'+array["title"]+' '+array["first_name"]+' '+array["last_name"] + '</td>';
			items += '<td>'+array["email"]+'</td>';
			items += '<td>'+array["phone"]+'</td>';
			items += '<td>'+array["nationality"]+'</td>';
			items += '<td>'+array["birth_date"]+'</td>';
			items += '<td class="action">';
			items += '<a href="javascript:void(0)" onclick="registrant.edit('+index+')"><i class="fa fa-edit"></i>&nbsp; Edit</a>';
			items += '<a href="javascript:void(0)" onclick="registrant.remove(\''+array["email"]+'\')"><i class="fa fa-trash"></i>&nbsp; Delete</a>';
			items += '</td>';
			items += '</tr>';
		});
		//}
		table.children("tbody").html(items);
		
		table.DataTable();
	};
	
	Registrant.prototype.add = function(){
		var parent = this;
		parent.reset();
		
		form.find("#crud").val("add");
		dialog.modal('show');
	};
	
	Registrant.prototype.edit = function(index){
		var parent = this;
		parent.reset();
		
		var curr_data = parent.data[index];
		
		form.find("#id").val(curr_data.id);
		form.find("#title").val(curr_data.title);
		form.find("#first_name").val(curr_data.first_name);
		form.find("#last_name").val(curr_data.last_name);
		form.find("#email").val(curr_data.email);
		form.find("#phone").val(curr_data.phone);
		form.find("#nationality").val(curr_data.nationality);
		form.find("#birth_date").val(curr_data.birth_date);
		
		form.find("#crud").val("edit");
		
		dialog.modal('show');
	};
	
	Registrant.prototype.reset = function(index){
		form.find("#id").val("");
		form.find("#title").val(0);
		form.find("#first_name").val("");
		form.find("#last_name").val("");
		form.find("#email").val("");
		form.find("#phone").val("");
		form.find("#nationality").val("");
		form.find("#birth_date").val("");
		
		dialog.find(".alert").hide();
	};
	
	Registrant.prototype.submit = function(){
		var parent = this;
		var data = form.serializeArray();
		
		$.ajax({type: "POST", url: "<?php echo $postURL; ?>", dataType: 'json', data: data, 
			success: function(data){
			   if(data[0].return == 1){
				   showAlert('.alert', '.btn', "alert-success", data[0].msg);
				   parent.refresh();
				   setTimeout(function(e){
					  dialog.modal("hide"); 
				   }, 500);
			   }
			   else{
				   showAlert('.alert', '.btn', "alert-danger", data[0].msg);
			   }
			},
			error: function (data) {
				showAlert('.alert', '.btn', "alert-danger", '<i class="fa fa-warning" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<strong>Mohon maaf !</strong> An error occurred. ' + JSON.stringify(data));
			}
		});
	};
	
	Registrant.prototype.remove = function(str){
		var parent = this;
		
		if(!confirm("Apakah anda yakin untuk menghapus data dengan email: " + str + "?. Tekan OK untuk melanjukan")){
			return false;
		}
		
		$.ajax({type: "POST", url: "<?php echo $removeURL; ?>", dataType: 'json', data: {"email":str}, 
			success: function(data){
			   if(data[0].return == 1){
				   showAlert('.alert', '.btn', "alert-success", data[0].msg);
				   parent.refresh();
			   }
			   else{
				   showAlert('.alert', '.btn', "alert-danger", data[0].msg);
			   }
			},
			error: function (data) {
				showAlert('.alert', '.btn', "alert-danger", '<i class="fa fa-warning" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<strong>Mohon maaf !</strong> An error occurred. ' + JSON.stringify(data));
			}
		});
	};
}

</script>

