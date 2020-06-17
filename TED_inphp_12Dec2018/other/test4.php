<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head profile="http://gmpg.org/xfn/11">
<title>Automation-CA-QC</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="imagetoolbar" content="no" />
<link rel="stylesheet" href="styles/layout.css" type="text/css" />
 <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="scripts/jquery-1.9.1.js"></script>
<script src="scripts/jquery-ui.js"></script>
<script type="text/javascript">
$(function(){
	 $('.prospTbl tr:odd').addClass('light');
	 $('.prospTbl tr:even').addClass('dark');
	 $("#date").datepicker({
		 defaultDate: "+1w",
	     dateFormat: "yy-mm-dd",
	     maxDate: '0d'
		 });
});
</script>
<style>
        .pagination {
            height:34px;
            position:relative;
            width:auto;
            display:inline-block;
        }

        .pagination a {
            padding:2px 5px 2px 5px;
            margin:2px;
            border:1px solid #999;
            text-decoration:none;
            color: #666;
        }
        .pagination a:hover, .paginate a:active {
            border: 1px solid #999;
            color:#0384DA
        }
        .pagination span.current {
            margin: 2px;
            padding: 2px 5px 2px 5px;
            border: 1px solid #0384DA;

            font-weight: bold;
            background-color: #0384DA;
            color: #FFF;
        }
        .pagination span.disabled {
            padding:2px 5px 2px 5px;
            margin:2px;
            border:1px solid #eee;
            color:#0384DA;
        }

        li{
            padding:4px;
            margin-bottom:3px;
            background-color:#FCC;
            list-style:none;}

        ul{margin:6px;
           padding:0px;}    

    </style>
<style type="text/css">
.td1{text-align: left;font-weight: bold;padding: 0 0 0 20px;vertical-align: middle; width: 250px;}
.td2{text-align: left;padding-left: 20px;}
</style>
</head>
<body id="top">
<!-- menu header -->
<div id="header">
  <div class="wrapper">
    <div class="fl_left" style="width: auto;">
      <h1><a href="#">Automation-CA-Testing-QC</a></h1>
    </div>
    <br class="clear" />
    <p style="float: right;"><a href="logout.php">logout</a></p>	
  </div>
</div>
<div id="topbar">
  <div class="wrapper">
    <div id="topnav">
      <ul>
                <li class="active" ><a href="home.php">Home</a></li>
                <!--li class="active"><a href="full-width.html">Full Width</a></li>
        <li><a href="#">DropDown</a>
          <ul>
            <li><a href="#">Link 1</a></li>
            <li><a href="#">Link 2</a></li>
            <li><a href="#">Link 3</a></li>
          </ul>
        </li>
        <li class="last"><a href="#">A Long Link Text</a></li--->
      </ul>
    </div>
    <br class="clear" />
  </div>
</div><!-- menu header -->
<div id="container">
  <div class="wrapper" style="min-height:400px;">
  	<!--  form action="" method="post">
          <p>
                         <label for="name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date:</label>
            <input type="text" name="date" id="date" value=""/>
 			<input name="search" type="submit" value="Search" />
 			</p>
        </form-->
     <h2 style="margin-bottom: 0px;text-align: center;" ></h2>
      <div id="respond">
            <a style="float: left;" title="Previous" href="home.php?page=0"><img src="images/prev.png"/></a>
      <a style="float: right;" title="Next" href="home.php?page=2"><img src="images/next.png"/></a>
      <br/><br/>
      <p><b>Total Record : </b>747</p>
      <center><div class="pagination"><span class="disabled">prev</span><span class="current">1</span><a href="home.php?page=2">2</a><a href="home.php?page=3">3</a><a href="home.php?page=4">4</a><a href="home.php?page=5">5</a><a href="home.php?page=6">6</a><a href="home.php?page=7">7</a><a href="home.php?page=8">8</a><a href="home.php?page=9">9</a>...<a href="home.php?page=746">746</a><a href="home.php?page=747">747</a><a href="home.php?page=2">next</a></div>
</center>      <table summary="Summary Here" class="prospTbl" cellpadding="0" cellspacing="0" style="font-size: 15px;">
          <tr><td class="td1">Summary</td><td class="td2">Construction work</td></tr>
            <tr><td class="td1">Ref. No.</td><td class="td2">096636-2014</td></tr>
            <tr><td class="td1">Purchaser</td><td class="td2">ÐžÐ±Ñ‰Ð¸Ð½Ð° ÐšÐ°Ð¾Ð»Ð¸Ð½Ð¾Ð²Ð¾</td></tr>
            <tr><td class="td1">Purchaser Address</td><td class="td2">Ð¿Ð». â€žÐ£ÐºÑ€Ð°Ð¹Ð½Ð°â€œ â„– 4,ÐšÐ°Ð¾Ð»Ð¸Ð½Ð¾Ð²Ð¾ 9960, Tel : +359 53612210, Fax : +359 53612110</td></tr>
            <tr><td class="td1">Purchaser Country</td><td class="td2">BG</td></tr>
            <tr><td class="td1">Purchaser Email</td><td class="td2">kaolinovo@abv.bg</td></tr>
            <tr><td class="td1">Purchaser URL</td><td class="td2">http://www.kaolinovo.bg.</td></tr>
            <tr><td class="td1">Contractor</td><td class="td2">ÐšÐ¾Ð½ÑÐ¾Ñ€Ñ†Ð¸ÑƒÐ¼ â€žÐ¥Ð¸Ð´Ñ€Ð¾-ÐŸÐœÐ”â€</td></tr>
            <tr><td class="td1">Contractor Address</td><td class="td2">ÑƒÐ». â€œÐÐºÐ°Ð´. ÐÐ½Ð´Ñ€ÐµÐ¹ Ð¡Ð°Ñ…Ð°Ñ€Ð¾Ð²â€ â„– 1,Ð’Ð°Ñ€Ð½Ð° 9000</td></tr>
            <tr><td class="td1">Contractor Country</td><td class="td2">BG</td></tr>
            <tr><td class="td1">Contractor Email</td><td class="td2"><span style="background-color: red;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>
            <tr><td class="td1">Contractor URL</td><td class="td2"><span style="background-color: red;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>
            <tr><td class="td1">Award Details</td><td class="td2">Construction work,</td></tr>
            <tr><td class="td1">Project Country/Location</td><td class="td2">BG</td></tr>
            <tr><td class="td1">Contract Currency</td><td class="td2">BGN</td></tr>
      </table>
     
      <br/>
      <hr style="border-width: 4px;">
      <br/>
      <h2 style="margin-bottom: 0px;text-align: center;" >contract_award table</h2>
      <table summary="Summary Here" class="prospTbl" cellpadding="0" cellspacing="0" style="font-size: 15px;">
            	<tr><td class="td1">id</td><td class="td2">52148</td></tr>
		      	<tr><td class="td1">short_descp</td><td class="td2">Construction work</td></tr>
		      	<tr><td class="td1">ref_number</td><td class="td2">096636-2014</td></tr>
		      	<tr><td class="td1">purchasername</td><td class="td2">ÐžÐ±Ñ‰Ð¸Ð½Ð° ÐšÐ°Ð¾Ð»Ð¸Ð½Ð¾Ð²Ð¾</td></tr>
		      	<tr><td class="td1">purch_country</td><td class="td2">BG</td></tr>
		      	<tr><td class="td1">purchaseradd</td><td class="td2">Ð¿Ð». â€žÐ£ÐºÑ€Ð°Ð¹Ð½Ð°â€œ â„– 4,ÐšÐ°Ð¾Ð»Ð¸Ð½Ð¾Ð²Ð¾ 9960, Tel : +359 53612210, Fax : +359 53612110</td></tr>
		      	<tr><td class="td1">purch_email</td><td class="td2">kaolinovo@abv.bg</td></tr>
		      	<tr><td class="td1">purch_url</td><td class="td2">http://www.kaolinovo.bg.</td></tr>
		      	<tr><td class="td1">contractorname</td><td class="td2">ÐšÐ¾Ð½ÑÐ¾Ñ€Ñ†Ð¸ÑƒÐ¼ â€žÐ¥Ð¸Ð´Ñ€Ð¾-ÐŸÐœÐ”â€</td></tr>
		      	<tr><td class="td1">cont_add</td><td class="td2">ÑƒÐ». â€œÐÐºÐ°Ð´. ÐÐ½Ð´Ñ€ÐµÐ¹ Ð¡Ð°Ñ…Ð°Ñ€Ð¾Ð²â€ â„– 1,Ð’Ð°Ñ€Ð½Ð° 9000</td></tr>
		      	<tr><td class="td1">cont_country</td><td class="td2">BG</td></tr>
		      	<tr><td class="td1">cont_email</td><td class="td2"></td></tr>
		      	<tr><td class="td1">cont_url</td><td class="td2"></td></tr>
		      	<tr><td class="td1">project_location</td><td class="td2">BG</td></tr>
		      	<tr><td class="td1">award_detail</td><td class="td2">Construction work,</td></tr>
		      	<tr><td class="td1">contract_val</td><td class="td2">5 350 346,89</td></tr>
		      	<tr><td class="td1">contract_currency</td><td class="td2">BGN</td></tr>
		      	<tr><td class="td1">sector</td><td class="td2">45000000</td></tr>
		      	<tr><td class="td1">userid</td><td class="td2">0</td></tr>
		      	<tr><td class="td1">date_c</td><td class="td2">2014-03-20 00:00:00</td></tr>
		      	<tr><td class="td1">cont_date</td><td class="td2"></td></tr>
		      	<tr><td class="td1">col1</td><td class="td2"></td></tr>
		      	<tr><td class="td1">col2</td><td class="td2"></td></tr>
		      	<tr><td class="td1">col3</td><td class="td2"></td></tr>
		      	<tr><td class="td1">col4</td><td class="td2"></td></tr>
		 
      </table>
      </div>
            </br>
      </br>
  </div>
</div>
<!-- ####################################################################################################### -->
<div id="copyright">
  <div class="wrapper">
    <p class="fl_left">Copyright &copy; 2014 - All Rights Reserved - <a target="_blank" href="http://www.tendersontime.com">Tendersontime</a></p>
    <br class="clear" />
  </div>
</div></body>
</html>