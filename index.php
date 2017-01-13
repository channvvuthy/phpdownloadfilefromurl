<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Download File</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<style>
		*{
			border-radius:0px !important;
		}
		form{
			margin:100px auto;
		}
	</style>
</head>
<body>
	<form action="?" enctype="multipart/form-data" method="post">
		<div class="col-md-8 col-md-offset-2">
			<div class="form-group">
				<label for="">Choose File(.csv)</label>
				<input type="file" name="file" id="" class="form-control" required>
				<input type="hidden" name="_token" value="1">
				<?php 
					if(isset($_SESSION['error'])){
						echo "<br/>";
						echo "<label class=''>".$_SESSION['error']."</label>";
					}
				?>
			</div>
			<div class="form-group">
				<button class="btn btn-default"><span class="glyphicon glyphicon-download"></span> Download</button>
			</div>
		</div>
	</form>
</body>
</html>
<?php
if(isset($_POST['_token'])){
	$defaultUrl="http://leonardo.olive.plan-b.xyz.s3-ap-northeast-1.amazonaws.com/_wp/_cnv/picture/";
	$defaultEx=".jpg";
	$fileName=$_FILES['file']['name'];
	$ex=explode(".", $fileName);
	$ex=end($ex);
	if($ex !="csv"){
		$_SESSION['error']="File invalid extention";
		header("Location:index.php");
		exit();
	}else{
		unset($_SESSION['error']);
		$fileTmp=$_FILES['file']['tmp_name'];
	              $path="files/";
	              $rand=rand(1,99999);
	              $newFile=$rand.$fileName;
	              if(move_uploaded_file($fileTmp, $path.$newFile)){
	              	$row = 1;
			$files = fopen("files/".$newFile, "r");
			while (($data = fgetcsv($files, 8000, ",")) !== FALSE) {
				    $num = count($data);
				    $row++;
				    for ($c=0; $c < $num; $c++) {
				        if(strtolower($data[$c])!="url"){
				        	$curlCh = curl_init();
					curl_setopt($curlCh, CURLOPT_URL, $defaultUrl.$data[$c].$defaultEx);
					curl_setopt($curlCh, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($curlCh, CURLOPT_SSLVERSION,3);
					$curlData = curl_exec ($curlCh);
					curl_close ($curlCh);
					$downloadPath = "images/".date('m-d-y').rand(1,9999).".jpg";
					$file = fopen($downloadPath, "w+");
					fputs($file, $curlData);
					fclose($file);
				        }
				     }
			}
			fclose($files);
	              }
	}
	$_SESSION['error']="Download Complete";
	header("Location:index.php"); 
}
unset($_SESSION['error']);
?>