<?php
$file = $_GET["file"];

$file_content = file_get_contents("../app".$file);

?>

<!DOCTYPE html>
<html lang="id">
	<head>
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap-timepicker.min.css" rel="stylesheet">
		<link href="bootstrap/css/datepicker.css" rel="stylesheet">
		<link href="bootstrap/font-awesome/css/font-awesome.css" rel="stylesheet">

		<script src="bootstrap/js/jquery.js"></script>
		<script src="bootstrap/js/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>				
		<script src="bootstrap/js/bootstrap-timepicker.js"></script>
		<script src="bootstrap/js/bootstrap-datepicker.js"></script>
	</head>
	<body>
		<div class="controlx">
			
		</div>
		<div class="content">
			<textarea><?php echo $file_content; ?></textarea>
		</div>
	</body>
	
	<style>
	.control{
		background:#eee;
		padding:8px;
		text-align:right;
	}
	.content textarea{
		width:100%;
		min-height:600px;
	}
	table tbody tr td a{
		color:#000;
		text-decoration:none;
	}
	table tbody tr td a:hover{
		text-decoration:none;
	}
	table tbody tr td a:hover span{
		text-decoration:underline;
	}
	</style>
</html>