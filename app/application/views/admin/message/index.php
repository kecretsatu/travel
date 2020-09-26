<!-- Page Content -->
<div class="page-title-2">
	<h1>Message</h1>
	<div class="control">
		<a href="javascript:void(0)" class="add" onclick="message.add()"><i class="fa fa-plus"></i>&nbsp; Add</a>
		<span>|</span>
		<a href="javascript:void(0)" class="remove" onclick="message.remove()"><i class="fa fa-trash"></i>&nbsp; Delete</a>
		<span>|</span>
		<a href="javascript:void(0)" onclick="message.refresh()" class="refresh"><i class="fa fa-refresh"></i>&nbsp; Refresh</a>
	</div>
</div>
<div class="page-content">
	<div class=" no-background no-padding">
		<div class="alert" role="alert"><p></p></div>
	</div>
	<div class="container">
		<div class="table-responsive">
			<table id="message" class="table table-striped">
				<!--<thead>
					<th class="check"><input type="checkbox" /></th>
					<th>Nama</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Subyek</th>
					<th>Message</th>
					<th class="action"></th>
				</thead>-->
				<tbody></tbody>
			</table>
		</div>
	</div>
	<div class=" no-background no-padding">
		<?php //echo $this->pagination->create_links(); ?>
	</div>
</div>

<script>
var message;
$(document).ready(function(e){
	message = new Message();
	message.refresh();
});

var Message = function(){
	var table	= $("#message");
	var dialog	= $("#myModal");
	var form	= $("#myModal form");
	var data	= [];
	var x = this;
	
	Message.prototype.refresh = function(){
		var parent = this;
		$.ajax({type: "POST", url: "<?php echo $getURL; ?>", dataType: 'json', data: data, 
			success: function(data){
				parent.data = data;
				//alert(JSON.stringify(data));
				parent.build();
			},
			error: function (data) {
				alert("error");
				//showAlert('.alert', '.btn', "alert-danger", '<i class="fa fa-warning" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<strong>Mohon maaf !</strong> An error occurred.');
			}
		});
	};
	
	Message.prototype.build = function(){
		var parent = this;
		var items = "";
		$.each(parent.data, function(index, array){
			items += '<tr id="row-'+array["id"]+'" class="'+array["s"]+'">';
			items += '<td class="check"><input type="checkbox" value="'+array["id"]+'" /></td>';
			items += '<td style="width:20%" onclick="message.view('+index+')"><label>'+array["title"]+' '+array["full_name"]+'</label></td>';
			items += '<td onclick="message.view('+index+')"><label>'+array["subyek"]+'</label> - <span>'+array["message"]+'</span></td>';
			items += '<td style="width:5%"><label>'+array["t"]+'</label></td>';
			/*items += '<td class="action">';
			items += '<a href="javascript:void(0)" onclick="message.edit('+index+')"><i class="fa fa-eye"></i>&nbsp; View</a>';
			items += '<a href="javascript:void(0)" onclick="show_form()"><i class="fa fa-trash"></i>&nbsp; Delete</a>';
			items += '</td>';*/
			items += '</tr>';
		});
		
		table.children("tbody").html(items);
	};
	
	Message.prototype.add = function(){
		var parent = this;
		parent.reset();
		
		form.find("#crud").val("add");
		dialog.modal('show');
	};
	
	Message.prototype.view = function(index){
		var parent = this;
		parent.reset();
		
		//var curr_data = parent.data[index];
		//alert("<?php echo base_url(); ?>message/view");
		//location.href="<?php echo base_url(); ?>message/view?id=" + curr_data.id;
		
		var curr_data = parent.data[index];
		
		form.find("#id").val(curr_data.id);
		form.find("#title").html(curr_data.title);
		form.find("#full_name").html(curr_data.full_name);
		form.find("#email").html(curr_data.email);
		form.find("#phone").html(curr_data.phone);
		form.find("#subyek").html(curr_data.subyek);
		form.find("#message").html(curr_data.message);
		form.find("#date").html(curr_data.f);
		
		form.find("#crud").val("read");
		
		dialog.modal('show');
		parent.read();
	};
	
	Message.prototype.reset = function(index){
		form.find("#id").val("");
		form.find("#title").val(0);
		form.find("#full_name").val("");
		form.find("#email").val("");
		form.find("#phone").val("");
		form.find("#subyek").val("");
		form.find("#message").val("");
		form.find("#date").val("");
		
		dialog.find(".alert").hide();
	};
	
	Message.prototype.read = function(){
		var parent = this;
		var data = form.serializeArray();
		
		$.ajax({type: "POST", url: "<?php echo $readURL; ?>", dataType: 'json', data: data, 
			success: function(data){
			   if(data[0].return == 1){
					var id = data[0].id;
					$("#message tbody tr#row-"+id).removeClass("unread");
					$("#message tbody tr#row-"+id).addClass("read");
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
	
	Message.prototype.remove = function(){
		var parent = this;
		//alert('ok');
		//$(table).hide();
		var items = "";
		$(table).find("tr").each(function(i, obj) {
			var cb = $(obj).find("input[type=checkbox]");
			if($(cb).is(":checked")){
				//row-
				var id = $(cb).val();
				//alert(id);
				items += id+";";
			}
		});
		if(items == ""){
			alert("Silahkan centang pesan yang akan dihapus.");
		}
		else{
			$.ajax({type: "POST", url: "<?php echo $removeURL; ?>", dataType: 'json', data: {"id":items}, 
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
}

</script>

<style>
#message tbody tr{
	cursor:pointer;
	background:#fff;
	border-bottom:1px solid #eee;
}
#message tbody tr td span{
	color:#838383;
}
#message tbody tr.read label{
	font-weight:normal;
}
#message tbody tr.unread{
	background:#eee;
}
#myModal form .col-md-6{
	padding:0px;
}
#myModal form .col-md-6 span{
	font-size:0.9em;
}
#myModal form h4{
	font-weight:bold;
	font-family:arial;
	margin:0px; padding:0px;
	
}
</style>