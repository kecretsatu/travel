<?php
$URL 		= "http://".$_SERVER["HTTP_HOST"];
$URLURI		= $_SERVER["REQUEST_URI"];
$URI		= "/projects/tiketravel/filemanager/";
$URI		= "/filemanager/";


$BASE_URL	= $URL.$URI;
$URL		= $URL . $URLURI;

$target_dir = $URLURI;
$target_dir = explode("filemanager", $target_dir);
$target_dir = $target_dir[count($target_dir)-1];
//$target_dir = substr($target_dir, 1, strlen($target_dir));

$current_dir = substr($target_dir, 1, strlen($target_dir))."/";

$dir = '../app' . $target_dir;

$f = "";
if(isset($_POST["submit"])){
	if($_POST["submit"] == "upload"){
		$f = $dir . basename($_FILES["file"]["name"]);
		if (move_uploaded_file($_FILES["file"]["tmp_name"], $f)) {
			header("location:".$URL);
			exit;
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
	if($_POST["submit"] == "delete"){
		$f = $_POST["file"];
		$f = $dir . $f;
		if (unlink($f)){
			header("location:".$URL);
			exit;
		}
		else{
			echo "Sorry, there was an error deleting your file.";
		}
	}
}

$directories = array();
$files_list  = array();
$files = scandir($dir);
foreach($files as $file){
   if(($file != '.') && ($file != '..')){
	  if(is_dir($dir.'/'.$file)){
		 $directories[]  = $file;

	  }else{
		 $files_list[]    = $file;

	  }
   }
}

$tbody = '<tbody>';
$tbody .= '<tr>';
$tbody .= '<td><a href="'.$BASE_URL.'"><i class="fa fa-home"></i>&nbsp;&nbsp;<span>Home Directory</span></a></td>';
$tbody .= '<td></td>';
$tbody .= '<td></td>';
$tbody .= '<td></td>';
$tbody .= '</tr>';
foreach($directories as $directory){
	$tbody .= '<tr>';
	$tbody .= '<td><a href = "'.$URL.$directory."/".'"><i class="fa fa-folder"></i>&nbsp;&nbsp;<span>'.$directory.'</span></a></td>';
	$tbody .= '<td></td>';
	$tbody .= '<td></td>';
	$tbody .= '<td></td>';
	$tbody .= '</tr>';
}
foreach($files_list as $file_list){
	$tbody .= '<tr>';
	$tbody .= '<td><a href="'.$BASE_URL.'view.php?file='.$target_dir.$file_list.'" target = "_blank"><i class="fa fa-file-text-o"></i>&nbsp;&nbsp;<span>'.$file_list.'</span></a></td>';
	$tbody .= '<td>'.number_format(filesize($dir.$file_list)).' KB</td>';
	$tbody .= '<td></td>';
	$tbody .= '<td style="width:100px;"><a href="javascript:void(0)" onclick="remove(\''.$file_list.'\')"><i class="fa fa-trash"></i>&nbsp;&nbsp;Remove</a></td>';
	$tbody .= '</tr>';
}
$tobdy = '</tbody>';
?>

<!DOCTYPE html>
<html lang="id">
	<head>
		<link href="<?php echo $BASE_URL; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo $BASE_URL; ?>bootstrap/css/bootstrap-timepicker.min.css" rel="stylesheet">
		<link href="<?php echo $BASE_URL; ?>bootstrap/css/datepicker.css" rel="stylesheet">
		<link href="<?php echo $BASE_URL; ?>bootstrap/font-awesome/css/font-awesome.css" rel="stylesheet">

		<script src="<?php echo $BASE_URL; ?>bootstrap/js/jquery.js"></script>
		<script src="<?php echo $BASE_URL; ?>bootstrap/js/jquery.min.js"></script>
		<script src="<?php echo $BASE_URL; ?>bootstrap/js/bootstrap.min.js"></script>				
		<script src="<?php echo $BASE_URL; ?>bootstrap/js/bootstrap-timepicker.js"></script>
		<script src="<?php echo $BASE_URL; ?>bootstrap/js/bootstrap-datepicker.js"></script>
	</head>
	<body>
	<?php echo $f; ?>
		<div class="control">
			<a href="javascript:void(0)" onclick="add()"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add File</a>
		</div>
		<table class="table table-striped">
			<thead>
				<th>Name</th>
				<th>Size</th>
				<th>Date Modified</th>
				<th>Action</th>
			</thead>
			<?php echo $tbody; ?>
		</table>
		<form id="upload" method="post" action="<?php echo $URL; ?>"  enctype="multipart/form-data">
		<input id="file" type="file" name="file" onchange="upload();" />
		<input id="uploadSubmit" type="submit" name="submit" value="upload" />
		</form>
		<form id="delete" method="post" action="<?php echo $URL; ?>"  enctype="multipart/form-data">
		<input id="fileremove" type="text" name="file" />
		<input id="deleteSubmit" type="submit" name="submit" value="delete" />
		</form>
	</body>
	
	<script>
	function add(){
		$("#file").click();
	}
	function remove(f){
		if(confirm("Delete file ( " + f + " )")){
			$("#fileremove").val(f);
			$("#deleteSubmit").click();
		}
	}
	function upload(){
		$("#uploadSubmit").click();
	}
	</script>
	
	<style>
	body{
		font-size:1.2em;
		padding-top:40px;
	}
	.control{
		position:fixed;
		top:0;
		width:100%;
		background:#eee;
		padding:8px;
		text-align:right;
	}
	form{
		display:none;
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