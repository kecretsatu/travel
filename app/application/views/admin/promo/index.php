<!-- Page Content -->
<div class="page-title-2">
	<h1>Promo</h1>
	<div class="control">
		<a href="javascript:void(0)" class="add" onclick="promo.add()"><i class="fa fa-plus"></i>&nbsp; Add</a>
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
			<table id="promo" class="table table-striped">
				<thead>
					<th>Image</th>
					<th>Type</th>
					<th>Name</th>
					<th>Promo Code</th>
					<th>Description</th>
					<th>Requirement</th>
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
var promo;
$(document).ready(function(e){
	promo = new Promo();
	promo.refresh();
});

var Promo = function(){
	var table	= $("#promo");
	var dialog	= $("#myModal");
	var form	= $("#myModal form");
	var data	= [];
	
	Promo.prototype.refresh = function(){
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
	
	Promo.prototype.build = function(){
		var parent = this;
		var items = "";
		$.each(parent.data, function(index, array){
			items += '<tr>';
			items += '<td><img src="<?php echo base_url(); ?>assets/images/promo/'+array["id"]+array["image"]+'" style="width:100px; height:80px" /></td>';
			items += '<td>'+array["type"]+'</td>';
			items += '<td>'+array["name"]+'</td>';
			items += '<td>'+array["promo_code"]+'</td>';
			items += '<td>'+array["description"]+'</td>';
			items += '<td>'+array["requirement"]+'</td>';
			items += '<td class="action">';
			items += '<a href="javascript:void(0)" onclick="promo.edit('+index+')"><i class="fa fa-edit"></i>&nbsp; Edit</a>';
			items += '<a href="javascript:void(0)" onclick="promo.remove('+array["id"]+')"><i class="fa fa-trash"></i>&nbsp; Delete</a>';
			items += '</td>';
			items += '</tr>';
		});
		
		table.children("tbody").html(items);
		
		table.DataTable();
	};
	
	Promo.prototype.add = function(){
		var parent = this;
		parent.reset();
		
		form.find("#crud").val("add");
		dialog.modal('show');
	};
	
	Promo.prototype.edit = function(index){
		var parent = this;
		parent.reset();
		
		var curr_data = parent.data[index];
		
		form.find('.promoImg > div').css('background-image', 'url(<?php echo base_url(); ?>assets/images/promo/'+curr_data["id"]+curr_data["image"]+')');
		form.find("#id").val(curr_data.id);
		form.find("#name").val(curr_data.name);
		form.find("#promo_code").val(curr_data.promo_code);
		form.find("#description").val(curr_data.description);
		form.find("#requirement").val(curr_data.requirement);
		
		form.find("#crud").val("edit");
		
		dialog.modal('show');
	};
	
	Promo.prototype.reset = function(index){
		form.find('.promoImg > div').css('background-image', 'url()');
		form.find("#id").val("");
		form.find("#name").val("");
		form.find("#promo_code").val("");
		form.find("#description").val("");
		form.find("#requirement").val("");
		
		dialog.find(".alert").hide();
	};
	
	Promo.prototype.submit = function(){
		var parent = this;
		var data = new FormData(form[0]);
		
		$.ajax({type: "POST", url: "<?php echo $postURL; ?>", dataType: 'json', data: data, processData: false, contentType: false,
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
	
	Promo.prototype.remove = function(id){
		
		if(!confirm("Apakah anda yakin untuk menghapus data? Tekan OK untuk melanjutkan.")){
			return false;
		}
		
		var parent = this;
		$.ajax({type: "POST", url: "<?php echo $removeURL; ?>", dataType: 'json', data: {"id":id}, 
			success: function(data){
			   if(data[0].return == 1){
					
					showAlert('.alert', '.btn', "alert-success", data[0].msg);
			   }
			   else{
				   showAlert('.alert', '.btn', "alert-danger", data[0].msg);
			   }
			},
			error: function (data) {
				showAlert('.alert', '.btn', "alert-danger", '<i class="fa fa-warning" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<strong>Mohon maaf !</strong> An error occurred. ' + JSON.stringify(data));
			}
		})
		.done(function(data){
				setTimeout(function(){
					window.location.reload();
				}, 500);
		});
	}
}

</script>