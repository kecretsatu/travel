<?php
if(!isset($this->session->userdata["userlogin"])){
	header("location: ".base_url() . "login");
	exit;
}


$userlogin = $this->session->userdata['userlogin'];
//$userlogin = [];
?>

<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<meta name="author" content="TikeTravel" />
		<meta name="description" content="TikeTravel" />
		<meta name="keywords"  content="TikeTravel" />
		<meta name="Resource-type" content="Document" />

		<link rel="icon" href="<?php echo base_url(); ?>assets/images/favicon.ico">
		<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.ico">

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
		
		<!-- datepicker -->
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.id.js" charset="UTF-8"></script>
		<!-- // datepicker -->

		<!-- datatables -->
		<script type="text/javascript" src="<?php echo base_url();?>assets/js/dataTables.bootstrap.min.js" ></script>
		<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.dataTables.min.js" ></script>
		<link href="<?php echo base_url() ;?>assets/css/dataTables.bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo base_url() ;?>assets/css/jquery.dataTables.min.css" rel="stylesheet">
		<!-- // datatables -->

		<!-- sidebar -->
		<script type="text/javascript" src="<?php echo base_url();?>assets/js/sidebar.js" ></script>
		<link href="<?php echo base_url() ;?>assets/css/sidebar.css" rel="stylesheet">
		<!-- // sidebar -->
		
		<script type="text/javascript" src="<?php echo base_url();?>assets/js/script.js" ></script>		
		<script type="text/javascript" src="<?php echo base_url();?>assets/js/graph.js" ></script>		
		<link href="<?php echo base_url() ;?>assets/css/header.css" rel="stylesheet">
		<link href="<?php echo base_url() ;?>assets/css/styles.css" rel="stylesheet">
		
		<!-- charts -->
		<script src="<?php echo base_url() ;?>assets/js/raphael-min.js"></script>
		<script src="<?php echo base_url() ;?>assets/js/morris.js"></script>
		<script src="<?php echo base_url() ;?>assets/js/jquery.fn.gantt.js"></script>
		<link rel="stylesheet" href="<?php echo base_url() ;?>assets/css/morris.css">
		<!-- //charts -->
		<!--skycons-icons-->
		<script src="<?php echo base_url() ;?>assets/js/skycons.js"></script>
		<!--//skycons-icons-->
		
		<!--web-fonts-->
		<link href='//fonts.googleapis.com/css?family=Raleway:400,100,100italic,200,200italic,300,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic' rel='stylesheet' type='text/css'><link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
		<link href='//fonts.googleapis.com/css?family=Pompiere' rel='stylesheet' type='text/css'>
		<link href='//fonts.googleapis.com/css?family=Fascinate' rel='stylesheet' type='text/css'>
		<!--web-fonts-->
		
		<title><?php echo $title; ?></title>
	</head>
	<body>	
		<div class="body">
			<?php
			$this->load->view('templates/sidebar.php');
			$this->load->view('templates/header.php', $userlogin);
			
			if(strpos($body, '.php') !== false){
				$this->load->view($body);
			}
			else{
				$this->load->view($body . '/index.php');
			}
			
			if(isset($form)){
				$this->load->view($form . '/index.php');
			}
			?>
		</div>
	</body>
	
</html>