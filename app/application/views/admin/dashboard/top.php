<div class="row">
			<div class="col-md-4 wow fadeInLeft animated">
				<div class="panel box-circle">
					<div class="panel-body">
						<h2><i class="glyphicon glyphicon-user"></i></h2>
						<h5>Registrant</h5>
						<h4><?php echo $registrant; ?></h4>
					</div>
					<div class="panel-footer"><a href="<?php echo base_url(); ?>registrant">View</a></div>
				</div>
			</div>
			<div class="col-md-4 wow fadeInLeft animated">
				<div class="panel box-circle v2">
					<div class="panel-body">
						<h2><i class="glyphicon glyphicon-list-alt"></i></h2>
						<h5>Order</h5>
						<h4><?php echo $order; ?></h4>
					</div>
					<div class="panel-footer"><a href="<?php echo base_url(); ?>order">View</a></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="panel box-circle v3">
					<div class="panel-body">
						<h2><i class="glyphicon glyphicon-user"></i></h2>
						<h5>Message</h5>
						<h4><?php echo $message; ?></h4>
					</div>
					<div class="panel-footer"><a href="<?php echo base_url(); ?>message">View</a></div>
				</div>
			</div>
		</div>