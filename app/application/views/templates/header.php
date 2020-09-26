<nav class="header navbar navbar-default">
	<div class="col-md-6">
		<img src="<?php echo base_url(); ?>assets/images/logo.png" />
	</div>
	<div class="col-md-3">
		<div class="input-group">
			<input class="form-control input-sm">
			<span class="input-group-btn">
				<button class="btn btn-sm btn-search"><i class="fa fa-search"></i></button>
			</span>
		</div>
	</div>
	<div class="col-md-3">
		<div class="header-right">
			<ul class="nofitications-dropdown">
				<!--
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope" style="color:red"></i><span class="badge">3</span></a>
					<ul class="dropdown-menu nofitications-box">
						<li>
							<div class="notification-header">
								<h3>You have 3 new messages</h3>
							</div>
						</li>
						<li><a href="#">
						   <div class="notification-img"><img src="<?php echo base_url(); ?>assets/images/blank-user.png" alt=""></div>
						   <div class="notification-desc">
								<p>Lorem ipsum dolor amet</p>
								<p><span>1 hour ago</span></p>
							</div>
						   <div class="clearfix"></div>	
						</a></li>
						<li>
							<div class="notification-bottom">
								<a href="#">See all messages</a>
							</div> 
						</li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell" style="color:blue"></i><span class="badge">3</span></a>
					<ul class="dropdown-menu nofitications-box">
						<li>
							<div class="notification-header">
								<h3>You have 3 new messages</h3>
							</div>
						</li>
						<li><a href="#">
						   <div class="notification-img"><img src="<?php echo base_url(); ?>assets/images/blank-user.png" alt=""></div>
						   <div class="notification-desc">
								<p>Lorem ipsum dolor amet</p>
								<p><span>1 hour ago</span></p>
							</div>
						   <div class="clearfix"></div>	
						</a></li>
						<li>
							<div class="notification-bottom">
								<a href="#">See all messages</a>
							</div> 
						</li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-tasks" style="color:green"></i><span class="badge">3</span></a>
					<ul class="dropdown-menu nofitications-box">
						<li>
							<div class="notification-header">
								<h3>You have 3 new messages</h3>
							</div>
						</li>
						<li><a href="#">
						   <div class="notification-img"><img src="<?php echo base_url(); ?>assets/images/blank-user.png" alt=""></div>
						   <div class="notification-desc">
								<p>Lorem ipsum dolor amet</p>
								<p><span>1 hour ago</span></p>
							</div>
						   <div class="clearfix"></div>	
						</a></li>
						<li>
							<div class="notification-bottom">
								<a href="#">See all messages</a>
							</div> 
						</li>
					</ul>
				</li>
				-->
				<li class="dropdown notification-profile">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<div class="profile-img">
							<img src="<?php echo base_url(); ?>assets/images/blank-user.png" alt="" />
							<div class="clearfix"></div>	
						</div>	
					</a>
					<ul class="dropdown-menu drp-mnu nofitications-box">
						<li>
							<div class="notification-header">
								<h3 style="font-family:Raleway">Administrator<?php //echo $this->session->userdata['userlogin']->nama ; ?></h3>
							</div>
						</li>
						<li> <a href="<?php echo base_url(); ?>changepassword"><i class="fa fa-cog"></i>&nbsp;&nbsp;&nbsp;Ubah Password</a> </li> 
						<li> <a href="<?php echo base_url(); ?>auth/logout"><i class="fa fa-sign-out"></i>&nbsp;&nbsp;&nbsp;Keluar</a> </li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	
</nav>