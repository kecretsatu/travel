<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Bootstrap Plugin -->
		<link href="<?php echo base_url() ;?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo base_url() ;?>assets/bootstrap/css/bootstrap-timepicker.min.css" rel="stylesheet">
		<link href="<?php echo base_url() ;?>assets/bootstrap/css/datepicker.css" rel="stylesheet">
		<link href="<?php echo base_url() ;?>assets/bootstrap/font-awesome/css/font-awesome.css" rel="stylesheet">

		<script src="<?php echo base_url() ;?>assets/bootstrap/js/jquery.js"></script>
		<script src="<?php echo base_url() ;?>assets/bootstrap/js/jquery.min.js"></script>
		<script src="<?php echo base_url() ;?>assets/bootstrap/js/bootstrap.min.js"></script>				
		<script src="<?php echo base_url() ;?>assets/bootstrap/js/bootstrap-timepicker.js"></script>
		<script src="<?php echo base_url() ;?>assets/bootstrap/js/bootstrap-datepicker.js"></script>
		
		<script type="text/javascript" src="<?php echo base_url();?>assets/js/script.js" ></script>		
		<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.vide.min.js" ></script>
		<link href="<?php echo base_url() ;?>assets/css/login.css" rel="stylesheet">
		
		<!--web-fonts-->
		<link href='//fonts.googleapis.com/css?family=Raleway:400,100,100italic,200,200italic,300,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic' rel='stylesheet' type='text/css'><link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
		<link href='//fonts.googleapis.com/css?family=Pompiere' rel='stylesheet' type='text/css'>
		<link href='//fonts.googleapis.com/css?family=Fascinate' rel='stylesheet' type='text/css'>
		<link href="//fonts.googleapis.com/css?family=Oleo+Script:400,700&subset=latin-ext" rel="stylesheet">

		<!--web-fonts-->
		
		<title>Login</title>
	</head>
	<body>	
		<div class="body" data-vide-bg="<?php echo base_url() ;?>assets/images/vid">
			<div class="container">
				<h1> </h1>
			</div>
			<div class="container">
				<div class="panel">
					<div class="panel-heading">Login Here</div>
					<div class="panel-body">
						<form action="#" method="post">
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<input id="id" type="text" class="form-control" name="uid" placeholder="ID User">
							</div>
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
								<input id="pwd" type="password" class="form-control" name="upwd" placeholder="Password">
							</div>
						</form>
						<div><a href="#" >Forgot Password ?</a></div>
						<div><button class="btn btn-success" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Please wait...">Login</button></div>
					</div>
					<div class="panel-footer">
						<div class="alert" role="alert"></div>
					<div>
				</div>
			</div>
		</div>
	</body>
	
	<script>
		$(document).ready(function(e){
			$('form').submit(function(e){
				e.preventDefault();
				
				var url = "<?php echo base_url() ;?>auth"; // the script where you handle the form input.

				$.ajax({type: "POST", url: url, dataType: 'json', data: $("form").serialize(), 
					success: function(data){
					   if(data[0].return == 1){
						   showAlert('.alert', '.btn', "alert-success", '<i class="fa fa-check" aria-hidden="true"></i>   <strong>Login Berhasil</strong> Silahkan tunggu...');
						   setTimeout(function(e){
							   window.location.reload();
						   }, 500);
					   }
					   else{
						   showAlert('.alert', '.btn', "alert-danger", '<i class="fa fa-warning" aria-hidden="true"></i>   <strong>Mohon maaf !</strong> ' + data[0].msg);
					   }
					},
					error: function (data) {
						showAlert('.alert', '.btn', "alert-danger", '<i class="fa fa-warning" aria-hidden="true"></i>   <strong>Mohon maaf !</strong> An error occurred.');
					}
				});
			});
			$(".btn").click(function(e){
				$(this).button('loading');
				$('form').submit();
			});
		});
	</script>
	
</html>