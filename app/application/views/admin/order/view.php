<?php


?>
<!-- Page Content -->
<div class="page-title-2">
	<h1>Detail Order</h1>
</div>
<div class="page-content">
	<div class=" no-background no-padding">
		<div class="alert" role="alert"><p></p></div>
	</div>
	<div class="container">
		<br />
		<div class="col-md-12">
			<div class="detail">
				<i class="fa fa-spinner fa-spin"></i>&nbsp;&nbsp;Mengambil data
			</div>
			<!--<div class="col-md-8">
				<div class="panel panel-primary">
					<div class="panel-heading">Detail</div>
					<div class="panel-body detailx">
						<i class="fa fa-spinner fa-spin"></i>&nbsp;&nbsp;Mengambil data
					</div>
				</div>
			</div>-->
		</div>
		<br />
	</div>
	<div class=" no-background no-padding">
		<?php //echo $this->pagination->create_links(); ?>
	</div>
</div>

<script>
var order;
$(document).ready(function(e){
	order = new Order();
	order.getBookFlight();
});

var Order = function(){
	var table	= $("#order");
	var data	= [];
	
	Order.prototype.getBookFlight = function(){
		var parent = this;
		$.ajax({type: "POST", url: "<?php echo base_url(); ?>order/request", dataType: 'html', data: {"token": "<?php echo $token ;?>", "email": "<?php echo $email ;?>", "order_id": "<?php echo $order_id ;?>"}, 
			success: function(data){
				$(".detail").html(data);
			},
			error: function (data) {
				alert(JSON.stringify(data));
				//showAlert('.alert', '.btn', "alert-danger", '<i class="fa fa-warning" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<strong>Mohon maaf !</strong> An error occurred.');
			}
		});
		
		
	};
	
	Order.prototype.refresh = function(){
		var parent = this;
		$.ajax({type: "POST", url: "", dataType: 'json', data: data, 
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
	
	Order.prototype.build = function(){
		var parent = this;
		var items = "";
		var n = 0;
		$.each(parent.data, function(index, array){
			n++;
			items += '<tr>';
			items += '<td>'+n+'</td>';
			items += '<td>'+array["type"]+'</td>';
			items += '<td>'+array["order_id"]+'</td>';
			items += '<td>'+array["user"]+'</td>';
			items += '<td>'+array["token_email"]+'</td>';
			items += '<td>'+array["total"]+' Order</td>';
			items += '<td>'+array["date"]+'</td>';
			items += '<td class="action">';
			items += '<a href="?order_id='+array["order_id"]+'" onclick="promo.edit('+index+')"><i class="fa fa-eye"></i>&nbsp; View</a>';
			items += '<a href="javascript:void(0)" ><i class="fa fa-trash"></i>&nbsp; Delete</a>';
			items += '</td>';
			items += '</tr>';
		});
		
		table.children("tbody").html(items);
		
		table.DataTable();
	};
}

</script>

<style>
.form-horizontal .control-label{
	text-align:left;
}
.form-horizontal .form-control{
	text-align:left;
	background:#fff;
	border:none;
	border-bottom:1px solid #eee;
	box-shadow:none;
}
</style>