<?php
header('Content-Type: text/html; charset=UTF-8');
require_once 'inc/global.inc.php';
//ini_set('display_errors','0');
function YmdChnage($input){
	$date = DateTime::createFromFormat('Ymd',$input);
	return $date->format('Y-m-d');
}
if(isset($_POST['submit'])){
	$file = $_FILES['file']['tmp_name'];
	$str = file_get_contents($file);
	$xml = simplexml_load_string($str);
	//$xml = simplexml_load_file($file);
	//echo "<pre>";
	//print_r($xml);
	foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
		if($ml_ti['LG'] == 'EN'){
			$short_descp = str_replace("'","\'",$ml_ti->TI_TEXT);
		}
	}
	foreach ($xml->FORM_SECTION->CONTRACT_AWARD as $temp){
		if($temp["LG"] == 'EN'){
			$data = $temp;
		}
	}
	if(empty($data)){
		$data = $xml->FORM_SECTION->CONTRACT_AWARD;
	}
	$ref_number = str_replace("'","\'",$xml['DOC_ID']);
	$data1 = $data->FD_CONTRACT_AWARD->CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE;
	$purchasername = str_replace("'","\'",$data1->ORGANISATION->OFFICIALNAME);
	$purchaseradd = str_replace("'","\'",$data1->ADDRESS.",".$data1->TOWN ." ". $data1->POSTAL_CODE .", Tel : ".$data1->PHONE.",  Fax : ".$data1->FAX);
	$purch_country = str_replace("'","\'",$data1->COUNTRY["VALUE"]);
	$purch_email = str_replace("'","\'",$data1->E_MAILS->E_MAIL);
	$purch_url = str_replace("'","\'",$xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL);
	$data2 = $data->FD_CONTRACT_AWARD->AWARD_OF_CONTRACT->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME;
	$contractorname = str_replace("'","\'",$data2->ORGANISATION->OFFICIALNAME);
	$cont_add = str_replace("'","\'",$data2->ADDRESS.",".$data2->TOWN ." ".$data2->POSTAL_CODE);
	$cont_country = str_replace("'","\'",$data2->COUNTRY["VALUE"]);
	$cont_email= '';
	$cont_url = '';
	$project_location = str_replace("'","\'",$data1->COUNTRY["VALUE"]);
	$award_detail = '';
	foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv){
		$award_detail .= str_replace("'","\'",$ocpv.",");
	}
	
	$contract_val = str_replace("'","\'",$data->FD_CONTRACT_AWARD->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE->VALUE_COST);
	$contract_currency = str_replace("'","\'",$data->FD_CONTRACT_AWARD->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE['CURRENCY']);
	$sector = '';
	$sector .= str_replace("'","\'",$data->FD_CONTRACT_AWARD->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->DESCRIPTION_AWARD_NOTICE_INFORMATION->CPV->CPV_MAIN->CPV_CODE["CODE"]);
	foreach ($data->FD_CONTRACT_AWARD->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->DESCRIPTION_AWARD_NOTICE_INFORMATION->CPV->CPV_ADDITIONAL as $cpv_ad ) $sector .= ",".str_replace("'","\'",$cpv_ad->CPV_CODE['CODE']);
	
	$cont_date = YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH);
	
	$contData = array(
			"short_descp"			=>		"'$short_descp'",
			"ref_number"			=>		"'$ref_number'",
			"purchasername"			=>		"'$purchasername'",
			"purchaseradd"			=>		"'$purchaseradd'",
			"purch_country"			=>		"'$purch_country'",
			"purch_email"			=>		"'$purch_email'",
			"purch_url"				=>		"'$purch_url'",
			"contractorname"		=>		"'$contractorname'",
			"cont_add"				=>		"'$cont_add'",
			"cont_country"			=>		"'$cont_country'",
			"cont_email"			=>		"'$cont_email'",
			"cont_url"				=>		"'$cont_url'",
			"project_location"		=>		"'$project_location'",
			"award_detail"			=>		"'$award_detail'",
			"contract_val"			=>		"'$contract_val'",
			"contract_currency"		=>		"'$contract_currency'",
			"sector"				=>		"'$sector'",
			"date_c"				=>		"'$cont_date'",
			"userid"				=>		"0"
			);
	echo "<pre>";
	//print_r($contData);
	/*$check = $db->count('contract_award',"ref_number='$ref_number'");
	if($check == 0){
		$db->insert($contData,'contract_award');
		$id = $db->getMax('contract_award',"id");	
		$contData1 = array("id"=>"$id");
		$contData1 = array_merge($contData1,$contData);
		print_r($contData1);
		$db2->insert($contData1,'contract_award');
	}*/
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head profile="http://gmpg.org/xfn/11">
<title>TOT-XMT-TO-HTML</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="imagetoolbar" content="no" />
<link rel="stylesheet" href="styles/layout.css" type="text/css" />
<link rel="stylesheet" href="styles/jquery-ui.css">
<!-- link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css"-->
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
  	<form action="" method="post" enctype="multipart/form-data">
          <p>
          	<label for="name">&nbsp;&nbsp;&nbsp;&nbsp;Select Files :</label>
          	<input type="file" name="file" id="file" />
 			<input name="submit" type="submit" value="Submit" />
          </p>
        </form>
        <?php //secho $htmlData; ?>
  </div>
</div>
<!-- ####################################################################################################### -->
<?php require_once 'inc/footer.php'; ?>
</body>
</html>