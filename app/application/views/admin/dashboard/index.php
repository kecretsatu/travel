
<!-- Page Content -->
<div class="page-content">
	<div class="page-content-body">
		<?php 
			$this->load->view('admin/dashboard/top.php'); 
		?>
		<br />
		<?php 
			//$param["color"] = "#9c2b26"; // "#585858"; #4F52BA  #e94e02
			$this->load->view('admin/dashboard/registrant.php'); 
		?>
		<br />
		<?php 
			//$param["color"] = "#585858"; // "#585858"; #4F52BA  #e94e02
			//$this->load->view('admin/dashboard/order.php'); 
		?>
		<br />
		<?php 
			//$param["color"] = "#4F52BA"; // "#585858"; #4F52BA  #e94e02
			//$this->load->view('admin/dashboard/sosialisasi.php', $param); 
		?>
		<br />
	</div>
</div>
<!-- /#page-content -->
