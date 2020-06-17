<?php
require_once 'inc/global.inc.php';
ini_set('display_errors','0');
function recursive_dir($dir) {
	foreach(scandir($dir) as $file) {
		if ('.' === $file || '..' === $file) continue;
		if (is_dir("$dir/$file")) recursive_dir("$dir/$file");
		else unlink("$dir/$file");
	}
	rmdir($dir);
}
if(isset($_FILES) && $_FILES["zip_file"]["name"]) {
	$filename = $_FILES["zip_file"]["name"];
	$source = $_FILES["zip_file"]["tmp_name"];
	$type = $_FILES["zip_file"]["type"];

	$name = explode(".", $filename);
	$accepted_types = array('application/zip', 'application/x-zip-compressed',
			'multipart/x-zip', 'application/x-compressed');
	foreach($accepted_types as $mime_type) {
		if($mime_type == $type) {
			$okay = true;
			break;
		}
	}

	$continue = strtolower($name[1]) == 'zip' ? true : false;
	if(!$continue) {
		$myMsg = "Please upload a valid .zip file.";
	}

	$folderName = time();
	$_SESSION['folderName'] = $folderName;
	$path = dirname(__FILE__).'/upload/';
	//echo $path;
	$filenoext = basename ($filename, '.zip');
	$filenoext = basename ($filenoext, '.ZIP');

	//$myDir = $path . $filenoext; // target directory
	$myDir = $path . $folderName;
	$myFile = $path . $filename; // target zip file

	if (is_dir($myDir)) recursive_dir ( $myDir);
	mkdir($myDir, 0777);

	if(move_uploaded_file($source, $myFile)) {
		$zip = new ZipArchive();
		$x = $zip->open($myFile); // open the zip file to extract
		if ($x === true) {
			$zip->extractTo($myDir); // place in the directory with same name
			$zip->close();
			unlink($myFile);
		}
		$myMsg = "Your .zip file uploaded and unziped.";
		header("Location: get-xml.php");
	} else {
		$myMsg = "<span style='color:red;'>There was a problem with the File.</span><br/>";
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head profile="http://gmpg.org/xfn/11">
<title>TOT-Automation-Report</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="imagetoolbar" content="no" />
<link rel="stylesheet" href="styles/layout.css" type="text/css" />
<link rel="stylesheet" href="styles/jquery-ui.css">
 <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="scripts/jquery-1.9.1.js"></script>
<script src="scripts/jquery-ui.js"></script>
<script>
  $(function() {
	  
  });
  </script>
<script type="text/javascript">
$(function(){
	 $('.prospTbl tr:odd').addClass('light');
	 $('.prospTbl tr:even').addClass('dark');
});
</script>
</head>
<body id="top">
<!-- menu header -->
<?php $page="home"; require_once 'inc/header.php'; ?>
<!-- menu header -->
<div id="container">
  <div class="wrapper" style="min-height:400px;">
	<form enctype="multipart/form-data" method="post" action="">
	<?php if(isset($myMsg)) echo $myMsg; ?>
	<label>Upload Zip File: </label> <input type="file" name="zip_file">
	<br><br>
	<input type="submit" name="submit" value="Upload" class="upload"> <br><br>
	</form>
  </div>
</div>
<!-- ####################################################################################################### -->
<?php require_once 'inc/footer.php'; ?>
</body>
</html>