<?php
ini_set('display_errors','0');

function YmdChnage($input){
	$date = DateTime::createFromFormat('Ymd',$input);
	return $date->format('Y-m-d');
}
if(isset($_POST['submit'])){
	$file = $_FILES['file']['tmp_name'];
	$xml = simplexml_load_file($file);
	//echo "<pre>";
	//print_r($xml);
	$htmlData = '<html><body><table border="1">';
	
	$htmlData .='
		<tr>
		<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
		<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE.'</td>
		</tr>';
		foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
			if($ml_ti['LG'] == 'EN'){
			$htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>".$ml_ti->TI_CY." ".$ml_ti->TI_TOWN." ".$ml_ti->TI_TEXT."</td></tr>";
			}
		}
		$CONCESSION = $xml->FORM_SECTION->CONCESSION;
		//echo "<pre>";
		//print_r($CONCESSION);
		$htmlData .='
		<tr>
		<td style="font-weight: bold;">DOC_ID</td>
		<td>'.$xml['DOC_ID'].'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">DATE_PUB</td>
		<td>'.YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB).'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">NO_OJ</td>
		<td>'.$xml->CODED_DATA_SECTION->REF_OJS->NO_OJ.'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">TOWN</td>
		<td>'. $CONCESSION->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->TOWN.'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">OFFICIALNAME</td>
		<td>'.$CONCESSION->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME .'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">POSTAL_CODE</td>
		<td>'.$CONCESSION->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE.'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">CONTACT_POINT</td>
		<td>'.$CONCESSION->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT .'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">ATTENTION</td>
		<td>'.$CONCESSION->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION .'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">PHONE</td>
		<td>'.$CONCESSION->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->PHONE.'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">E_MAIL</td>
		<td>'.$CONCESSION->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL .'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">FAX</td>
		<td>'.$CONCESSION->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->FAX.'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">LG</td>
		<td>'.$CONCESSION["LG"].'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">COUNTRY</td>
		<td>'.$CONCESSION->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"].'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">AA_AUTHORITY_TYPE</td>
		<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE.'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">DS_DATE_DISPATCH</td>
		<td>'.YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH).'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">DT_DATE_FOR_SUBMISSION</td>
		<td>'.YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION,0,8)).'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">NC_CONTRACT_NATURE</td>
		<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE.'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">PR_PROC</td>
		<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->PR_PROC.'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
		<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE.'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">RP_REGULATION</td>
		<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION.'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">TY_TYPE_BID</td>
		<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->TY_TYPE_BID.'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">AC_AWARD_CRIT</td>
		<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->AC_AWARD_CRIT.'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">CPV_CODE</td>
		<td>'.$CONCESSION->FD_CONCESSION->OBJECT_CONCESSION->DESCRIPTION_CONCESSION->CPV->CPV_MAIN->CPV_CODE["CODE"];
		foreach ($CONCESSION->FD_CONCESSION->OBJECT_CONCESSION->DESCRIPTION_CONCESSION->CPV->CPV_ADDITIONAL as $cpv_ad ) $htmlData .= ",".$cpv_ad->CPV_CODE['CODE'];
	$htmlData .='
		</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">NUTS</td>
		<td>'.$CONCESSION->FD_CONCESSION->OBJECT_CONCESSION->DESCRIPTION_CONCESSION->F10_TYPE_OF_WORKS_CONTRACT->NUTS["CODE"].'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">IA_URL_GENERAL</td>
		<td>'.$xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL.'</td>
		</tr>
		<tr>
		<td style="font-weight: bold;">DIRECTIVE</td>
		<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'].'</td>
		</tr><td style="font-weight: bold;">DESCRIPTION</td>
		<td>';
		foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv){ $htmlData .= $ocpv.".<br/>";}
			
	$htmlData .= '</td></tr></table></body></html>';
	
	
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
  	<form action="" method="post" enctype="multipart/form-data">
          <p>
          	<label for="name">&nbsp;&nbsp;&nbsp;&nbsp;Select Files :</label>
          	<input type="file" name="file" id="file" />
 			<input name="submit" type="submit" value="Submit" />
          </p>
        </form>
        <?php echo $htmlData; ?>
  </div>
</div>
<!-- ####################################################################################################### -->
<?php require_once 'inc/footer.php'; ?>
</body>
</html>