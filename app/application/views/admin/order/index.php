<!-- Page Content -->
<div class="page-title-2">
	<h1>Order</h1>
	<div class="control">
		<a href="javascript:void(0)" onclick="order.refresh()" class="refresh"><i class="fa fa-refresh"></i>&nbsp; Refresh</a>
	</div>
</div>
<div class="page-content">
	<div class=" no-background no-padding">
		<div class="alert" role="alert"><p></p></div>
	</div>
	<div class="container">
		<div class="table-responsive">
			<table id="order" class="table table-striped">
				<thead>
					<th>No</th>
					<th>Token / Email / User</th>
					<th>Order ID</th>
					<th>Total Order</th>
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
var order;
$(document).ready(function(e){
	order = new Order();
	order.refresh();
});

var Order = function(){
	var table	= $("#order");
	var dialog	= $("#myModal");
	var form	= $("#myModal form");
	var data	= [];
	
	Order.prototype.refresh = function(){
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
	
	Order.prototype.build = function(){
		var parent = this;
		var items = "";
		var n = 0;
		$.each(parent.data, function(index, array){
			n++;
			items += '<tr>';
			items += '<td>'+n+'</td>';
			items += '<td>'+array["token"];
			
			if(array["email"] != ""){
				items += '<br /><b><i>' + array["email"];
			}
			if(array["user"] != "-"){
				items += '<br /><b><i>' + array["user"];
			}
			items += '</td>';
			
			items += '<td>'+array["order_id"]+'</td>';
			items += '<td><b>'+array["count"]+' Order</b>&nbsp;&nbsp;=&nbsp;&nbsp;' + array["cflight"] +' Flight&nbsp;&nbsp;|&nbsp;&nbsp;' + array["chotel"] +' Hotel</td>';
			items += '<td class="action">';
			items += '<a href="<?php echo $viewURL; ?>?order_id='+array["order_id"]+'&token='+array["token"]+'" "><i class="fa fa-eye"></i>&nbsp; View</a>';
			items += '<a href="javascript:void(0)" onclick="order.remove(\''+array["order_id"]+'\')" ><i class="fa fa-trash"></i>&nbsp; Delete</a>';
			items += '</td>';
			items += '</tr>';
		});
		
		table.children("tbody").html(items);
		
		table.DataTable();
	};
	
	Order.prototype.remove = function(str){
		var parent = this;
		
		if(confirm("Apakah anda yakin untuk menghapus data? Tekan OK untuk melanjutkan")){
			waitingDialog.show("Loading...", {dialogSize: 'sm'});
			$.ajax({type: "POST", url: "<?php echo $removeURL; ?>", dataType: 'json', data: {"order_id":str}, 
				success: function(data){
					//alert(JSON.stringify(data));
					if(data[0].status == 1){
						parent.refresh();
					}
					waitingDialog.hide();
				},
				error: function (data) {
					alert(JSON.stringify(data));
					waitingDialog.hide();
					//showAlert('.alert', '.btn', "alert-danger", '<i class="fa fa-warning" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<strong>Mohon maaf !</strong> An error occurred.');
				}
			});
		}
		
		//alert(str);
		//return false;
		
	};
}


var waitingDialog = waitingDialog || (function ($) {
    'use strict';

	// Creating modal dialog's DOM
	var $dialog = $(
		'<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
		'<div class="modal-dialog modal-m">' +
		'<div class="modal-content">' +
			'<div class="modal-header"><h3 style="margin:0;"></h3></div>' +
			'<div class="modal-body">' +
				'<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>' +
			'</div>' +
		'</div></div></div>');

	return {
		/**
		 * Opens our dialog
		 * @param message Custom message
		 * @param options Custom options:
		 * 				  options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
		 * 				  options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
		 */
		show: function (message, options) {
			// Assigning defaults
			if (typeof options === 'undefined') {
				options = {};
			}
			if (typeof message === 'undefined') {
				message = 'Loading';
			}
			var settings = $.extend({
				dialogSize: 'm',
				progressType: '',
				onHide: null // This callback runs after the dialog was hidden
			}, options);

			// Configuring dialog
			$dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
			$dialog.find('.progress-bar').attr('class', 'progress-bar');
			if (settings.progressType) {
				$dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
			}
			$dialog.find('h3').text(message);
			// Adding callbacks
			if (typeof settings.onHide === 'function') {
				$dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
					settings.onHide.call($dialog);
				});
			}
			// Opening dialog
			$dialog.modal();
		},
		/**
		 * Closes dialog
		 */
		hide: function () {
			$dialog.modal('hide');
		}
	};

})(jQuery);

</script>