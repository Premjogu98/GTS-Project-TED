<?php
require_once 'inc/global.inc.php';
//ini_set('display_errors','0');
//if(!isset($_SESSION["userEmail"])){header("Location: login.php");}
//$userEmail = $_SESSION["userEmail"];
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
  	<form action="con-html.php" method="post" enctype="multipart/form-data">
          <p>
          	<label for="name">&nbsp;&nbsp;&nbsp;&nbsp;Select Files :</label>
          	<input type="file" name="file" id="file" />
 			<input name="submit" type="submit" value="Submit" />
          </p>
        </form>
  </div>
</div>
<!-- ####################################################################################################### -->
<?php require_once 'inc/footer.php'; ?>
</body>
</html>