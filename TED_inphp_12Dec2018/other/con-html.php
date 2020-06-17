<?php
ini_set('display_errors','0');
if(isset($_POST['submit'])){
	$file = $_FILES['file']['tmp_name'];
	$xml = simplexml_load_file($file);
	echo "<pre>";
	print_r($xml);
}
?>
<html>
<body>
<table border="1">
<tr>
<td style="font-weight: bold;">DOC_ID</td>
<td><?php echo $xml['DOC_ID']?></td>
</tr>
<tr>
<td style="font-weight: bold;">EDITION</td>
<td><?php echo $xml['EDITION']?></td>
</tr>
<tr>
<td style="font-weight: bold;">TECHNICAL_SECTION</td>
<td></td>
</tr>
<tr>
<td>RECEPTION_ID</td>
<td><?php echo $xml->TECHNICAL_SECTION->RECEPTION_ID?></td>
</tr>
<tr>
<td>DELETION_DATE</td>
<td><?php echo $xml->TECHNICAL_SECTION->DELETION_DATE?></td>
</tr>
<tr>
<td>FORM_LG_LIST</td>
<td><?php echo $xml->TECHNICAL_SECTION->FORM_LG_LIST?></td>
</tr>
<tr>
<td>COMMENTS</td>
<td><?php echo $xml->TECHNICAL_SECTION->COMMENTS?></td>
</tr>

<tr>
<td style="font-weight: bold;">REF_OJS</td>
<td></td>
</tr>
<tr>
<td>COLL_OJ</td>
<td><?php echo $xml->CODED_DATA_SECTION->REF_OJS->COLL_OJ?></td>
</tr>
<tr>
<td>NO_OJ</td>
<td><?php echo $xml->CODED_DATA_SECTION->REF_OJS->NO_OJ ?></td>
</tr>
<tr>
<td>DATE_PUB</td>
<td><?php echo $xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB ?></td>
</tr>

<tr>
<td style="font-weight: bold;">NOTICE_DATA</td>
<td></td>
</tr>
<tr>
<td>NO_DOC_OJS</td>
<td><?php echo $xml->CODED_DATA_SECTION->NOTICE_DATA->NO_DOC_OJS ?></td>
</tr>
<tr>
<td>URI_DOC</td>
<td><?php $uri_doc = $xml->CODED_DATA_SECTION->NOTICE_DATA->URI_LIST->URI_DOC ; foreach ($uri_doc as $uri_docV) echo $uri_docV."<br/>"?></td>
</tr>
<tr>
<td>LG_ORIG</td>
<td><?php echo $xml->CODED_DATA_SECTION->NOTICE_DATA->LG_ORIG ?></td>
</tr>
<tr>
<td>ISO_COUNTRY</td>
<td><?php echo $xml->CODED_DATA_SECTION->NOTICE_DATA->ISO_COUNTRY['VALUE'] ?></td>
</tr>
<tr>
<td>IA_URL_GENERAL</td>
<td><?php echo $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL ?></td>
</tr>
<tr>
<td>IA_URL_ETENDERING</td>
<td><?php echo $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_ETENDERING ?></td>
</tr>
<tr>
<td>ORIGINAL_CPV</td>
<td><?php $ocpv = $xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV; foreach ($ocpv as $ocpvVal) echo $ocpvVal."<br/>"; ?></td>
</tr>
<tr>
<td>ORIGINAL_NUTS</td>
<td><?php echo $xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_NUTS ?></td>
</tr>
<tr>
<td style="font-weight: bold;">VALUES_LIST</td>
<td></td>
</tr>
<?php 
	foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->VALUES_LIST->VALUES as $array1Val1){
			echo "<tr><td>TYPE</td><td>".$array1Val1['TYPE']."</td></tr>";
			echo "<tr><td>VALUE</td><td>".$array1Val1->RANGE_VALUE->VALUE[1]."</td><tr>";
	}
?>
<tr>
<td style="font-weight: bold;">REF_NOTICE-NO_DOC_OJS</td>
<td><?php echo $xml->CODED_DATA_SECTION->NOTICE_DATA->REF_NOTICE->NO_DOC_OJS?></td>
</tr>
<tr>
<td style="font-weight: bold;">CODIF_DATA</td>
<td></td>
</tr>
<tr>
<td>DS_DATE_DISPATCH</td>
<td><?php echo $xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH ?></td>
</tr>
<tr>
<td>DD_DATE_REQUEST_DOCUMENT</td>
<td><?php echo $xml->CODED_DATA_SECTION->CODIF_DATA->DD_DATE_REQUEST_DOCUMENT ?></td>
</tr>
<tr>
<td>DT_DATE_FOR_SUBMISSION</td>
<td><?php echo $xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION ?></td>
</tr>
<tr>
<td>AA_AUTHORITY_TYPE</td>
<td><?php echo $xml->CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE ?></td>
</tr>
<tr>
<td>TD_DOCUMENT_TYPE</td>
<td><?php echo $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE ?></td>
</tr>
<tr>
<td>NC_CONTRACT_NATURE</td>
<td><?php echo $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE ?></td>
</tr>
<tr>
<td>PR_PROC</td>
<td><?php echo $xml->CODED_DATA_SECTION->CODIF_DATA->PR_PROC ?></td>
</tr>
<tr>
<td>RP_REGULATION</td>
<td><?php echo $xml->CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION ?></td>
</tr>
<tr>
<td>TY_TYPE_BID</td>
<td><?php echo $xml->CODED_DATA_SECTION->CODIF_DATA->TY_TYPE_BID ?></td>
</tr>
<tr>
<td>AC_AWARD_CRIT</td>
<td><?php echo $xml->CODED_DATA_SECTION->CODIF_DATA->AC_AWARD_CRIT ?></td>
</tr>
<tr>
<td>MA_MAIN_ACTIVITIES</td>
<td><?php foreach ($xml->CODED_DATA_SECTION->CODIF_DATA->MA_MAIN_ACTIVITIES as $mn_act) echo $mn_act."<br/>"; ?></td>
</tr>
<tr>
<td>HEADING</td>
<td><?php echo $xml->CODED_DATA_SECTION->CODIF_DATA->HEADING ?></td>
</tr>
<tr>
<td>DIRECTIVE</td>
<td><?php echo $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE']?></td>
</tr>

<tr>
<td style="font-weight: bold;">TRANSLATION_SECTION</td>
<td></td>
</tr>
<?php foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
			echo "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td></td></tr>";
			echo "<tr><td>TI_CY</td><td>".$ml_ti->TI_CY."</td></tr>";
			echo "<tr><td>TI_TOWN</td><td>".$ml_ti->TI_TOWN."</td></tr>";
			echo "<tr><td>TI_TEXT</td><td>".$ml_ti->TI_TEXT."</td></tr>";
			echo "<tr><td>LG</td><td>".$ml_ti['LG']."</td></tr>";
	}
?>
<tr>
<td style="font-weight: bold;">ML_AA_NAMES</td>
<td><?php foreach($xml->TRANSLATION_SECTION->ML_AA_NAMES->AA_NAME as $mm_aa) echo $mm_aa."<br/>"; ?></td>
</tr>
<tr>
<td style="font-weight: bold;">FORM_SECTION</td>
<td></td>
</tr>
<?php
	$contract = $xml->FORM_SECTION->CONTRACT;
	foreach ($contract as $contractV){
?>
<tr>
<td style="font-weight: bold;">CONTRACT</td>
<td></td>
</tr>
<tr>
<td>CATEGORY</td>
<td><?php echo $contractV['CATEGORY']; ?></td>
</tr>
<tr>
<td>FORM</td>
<td><?php echo $contractV['FORM']; ?></td>
</tr>
<tr>
<td>LG</td>
<td><?php echo $contractV['LG']; ?></td>
</tr>
<tr>
<td>VERSION</td>
<td><?php echo $contractV['VERSION']; ?></td>
</tr>
<tr>
<td>FD_CONTRACT</td>
<td><?php echo $contractV->FD_CONTRACT['CTYPE'] ?></td>
</tr>
<tr>
<td style="font-weight: bold;">CONTRACTING_AUTHORITY_INFORMATION</td>
<td></td>
</tr>
<tr>
<td style="font-weight: bold;">NAME_ADDRESSES_CONTACT_CONTRACT</td>
<td></td>
</tr>
<tr>
<td style="font-weight: bold;">CA_CE_CONCESSIONAIRE_PROFILE</td>
<td></td>
</tr>
<tr>
<td>OFFICIALNAME</td>
<td><?php echo $contractV->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME ?></td>
</tr>
<tr>
<td>ADDRESS</td>
<td><?php echo $contractV->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS ?></td>
</tr>
<tr>
<td>TOWN</td>
<td><?php echo $contractV->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->TOWN  ?></td>
</tr>
<tr>
<td>POSTAL_CODE</td>
<td><?php echo $contractV->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE ?></td>
</tr>
<tr>
<td>COUNTRY</td>
<td><?php echo $contractV->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY['VALUE']?></td>
</tr>
<tr>
<td>CONTACT_POINT</td>
<td><?php echo $contractV->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT ?></td>
</tr>
<tr>
<td>PHONE</td>
<td><?php echo $contractV->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->PHONE ?></td>
</tr>
<tr>
<td>ATTENTION</td>
<td><?php echo $contractV->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION ?></td>
</tr>
<tr>
<td>FURTHER_INFORMATION</td>
<td><?php echo $contractV->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->FURTHER_INFORMATION->IDEM ?></td>
</tr>
<tr>
<td>SPECIFICATIONS_AND_ADDITIONAL_DOCUMENTS</td>
<td><?php echo $contractV->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->SPECIFICATIONS_AND_ADDITIONAL_DOCUMENTS->IDEM ?></td>
</tr>
<tr>
<td>TENDERS_REQUESTS_APPLICATIONS_MUST_BE_SENT_TO</td>
<td><?php echo $contractV->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->TENDERS_REQUESTS_APPLICATIONS_MUST_BE_SENT_TO->IDEM ?></td>
</tr>
<tr>
<td style="font-weight: bold;">TYPE_AND_ACTIVITIES_AND_PURCHASING_ON_BEHALF</td>
<td></td>
</tr>
<tr>
<td>TYPE_OF_CONTRACTING_AUTHORITY</td>
<td><?php echo $contractV->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->TYPE_AND_ACTIVITIES_AND_PURCHASING_ON_BEHALF->TYPE_AND_ACTIVITIES->TYPE_OF_CONTRACTING_AUTHORITY['VALUE']?></td>
</tr>
<tr>
<td>TYPE_OF_ACTIVITY_OTHER</td>
<td><?php echo $contractV->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->TYPE_AND_ACTIVITIES_AND_PURCHASING_ON_BEHALF->TYPE_AND_ACTIVITIES->TYPE_OF_ACTIVITY_OTHER?></td>
</tr>
<tr>
<td>TYPE_OF_ACTIVITY</td>
<td><?php foreach ($contractV->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->TYPE_AND_ACTIVITIES_AND_PURCHASING_ON_BEHALF->TYPE_AND_ACTIVITIES->TYPE_OF_ACTIVITY as $toa) echo $toa['VALUE']."<br/>";?></td>
</tr>
<tr>
<td style="font-weight: bold;">PURCHASING_ON_BEHALF</td>
<td></td>
</tr>
<tr>
<td style="font-weight: bold;">PURCHASING_ON_BEHALF_YES</td>
<td></td>
</tr>
<?php foreach ($contractV->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->TYPE_AND_ACTIVITIES_AND_PURCHASING_ON_BEHALF->PURCHASING_ON_BEHALF->PURCHASING_ON_BEHALF_YES->CONTACT_DATA_OTHER_BEHALF_CONTRACTING_AUTORITHY as $con_d_o){
			echo "<tr><td style='font-weight: bold;'>CONTACT_DATA_OTHER_BEHALF_CONTRACTING_AUTORITHY</td><td></td></tr>";
			echo "<tr><td>OFFICIALNAME</td><td>".$con_d_o->ORGANISATION->OFFICIALNAME."</td></tr>";
			echo "<tr><td>ADDRESS</td><td>".$con_d_o->ADDRESS."</td></tr>";
			echo "<tr><td>TOWN</td><td>".$con_d_o->TOWN."</td></tr>";
			echo "<tr><td>POSTAL_CODE</td><td>".$con_d_o->POSTAL_CODE."</td></tr>";
			echo "<tr><td>COUNTRY</td><td>".$con_d_o->COUNTRY['VALUE']."</td></tr>";
	}
?>
<tr>
<td style="font-weight: bold;">OBJECT_CONTRACT_INFORMATION</td>
<td></td>
</tr>
<tr>
<td style="font-weight: bold;">DESCRIPTION_CONTRACT_INFORMATION</td>
<td></td>
</tr>
<tr>
<td>TITLE_CONTRACT</td>
<td><?php echo $contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->TITLE_CONTRACT->P?></td>
</tr>
<tr>
<td>TYPE_CONTRACT</td>
<td><?php echo $contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->TYPE_CONTRACT_LOCATION->TYPE_CONTRACT['VALUE']?></td>
</tr>
<tr>
<td>SERVICE_CATEGORY</td>
<td><?php echo $contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->TYPE_CONTRACT_LOCATION->SERVICE_CATEGORY ?></td>
</tr>
<tr>
<td>LOCATION_NUTS</td>
<td><?php echo $contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->LOCATION_NUTS->NUTS['CODE']?></td>
</tr>
<tr>
<td>NOTICE_INVOLVES</td>
<td><?php echo $contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->NOTICE_INVOLVES->PUBLIC_CONTRACT ?></td>
</tr>
<tr>
<td>SHORT_CONTRACT_DESCRIPTION</td>
<td><?php foreach($contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->SHORT_CONTRACT_DESCRIPTION->P as $sh_d) echo $sh_d."<br/>";?></td>
</tr>
<tr>
<td>CPV_MAIN</td>
<td><?php echo $contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->CPV->CPV_MAIN->CPV_CODE['CODE']?></td>
</tr>
<tr>
<td>CPV_ADDITIONAL</td>
<td><?php foreach($contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->CPV->CPV_ADDITIONAL as $cpv_add) echo $cpv_add->CPV_CODE['CODE']."<br/>";?></td>
</tr>
<tr>
<td>CONTRACT_COVERED_GPA</td>
<td><?php echo $contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->CONTRACT_COVERED_GPA['VALUE']?></td>
</tr>
<tr>
<td style="font-weight: bold;">F02_DIVISION_INTO_LOTS</td>
<td></td>
</tr>
<tr>
<td>F02_DIV_INTO_LOT_YES</td>
<td><?php echo $contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->F02_DIVISION_INTO_LOTS->F02_DIV_INTO_LOT_YES['VALUE'] ?></td>
</tr>
<tr>
<td>DIV_INTO_LOT_NO</td>
<td><?php echo $contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->F02_DIVISION_INTO_LOTS->DIV_INTO_LOT_NO ?></td>
</tr>
<?php foreach ($contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->F02_DIVISION_INTO_LOTS->F02_DIV_INTO_LOT_YES->F02_ANNEX_B as $f02){

			echo "<tr><td style='font-weight: bold;'>F02_ANNEX_B</td><td></td></tr>";
			echo "<tr><td>LOT_NUMBER</td><td>".$f02->LOT_NUMBER."</td></tr>";
			echo "<tr><td>LOT_TITLE</td><td>".$f02->LOT_TITLE."</td></tr>";
			echo "<tr><td>LOT_DESCRIPTION</td><td>".$f02->LOT_DESCRIPTION->P."</td></tr>";
			echo "<tr><td>CPV_MAIN</td><td>".$f02->CPV->CPV_MAIN->CPV_CODE['CODE']."</td></tr>";
			echo "<tr><td>CPV_ADDITIONAL</td><td>";
			foreach ($f02->CPV->CPV_ADDITIONAL as $f02_cpv ) echo $f02_cpv->CPV_CODE['CODE']."<br/>";
			echo "</td></tr>";
			echo "<tr><td>TOTAL_QUANTITY_OR_SCOPE</td><td>".$f02->NATURE_QUANTITY_SCOPE->TOTAL_QUANTITY_OR_SCOPE->P."</td></tr>";
			echo "<tr><td>COSTS_RANGE_AND_CURRENCY-LOW_VALUE</td><td>".$f02->NATURE_QUANTITY_SCOPE->COSTS_RANGE_AND_CURRENCY->RANGE_VALUE_COST->LOW_VALUE['FMTVAL']."</td></tr>";
			echo "<tr><td>COSTS_RANGE_AND_CURRENCY-HIGH_VALUE</td><td>".$f02->NATURE_QUANTITY_SCOPE->COSTS_RANGE_AND_CURRENCY->RANGE_VALUE_COST->HIGH_VALUE['FMTVAL']."</td></tr>";
			echo "<tr><td>COSTS_RANGE_AND_CURRENCY-CURRENCY</td><td>".$f02->NATURE_QUANTITY_SCOPE->COSTS_RANGE_AND_CURRENCY['CURRENCY']."</td></tr>";
			echo "<tr><td>PERIOD_WORK_DATE_STARTING-START_DATE</td><td>".$f02->PERIOD_WORK_DATE_STARTING->INTERVAL_DATE->START_DATE->YEAR."-".$f02->PERIOD_WORK_DATE_STARTING->INTERVAL_DATE->START_DATE->MONTH."-".$f02->PERIOD_WORK_DATE_STARTING->INTERVAL_DATE->START_DATE->DAY."</td></tr>";
	}
?>
<tr>
<td>ACCEPTED_VARIANTS</td>
<td><?php echo $contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->ACCEPTED_VARIANTS['VALUE'] ?></td>
</tr>
<tr>
<td style="font-weight: bold;">QUANTITY_SCOPE</td>
<td></td>
</tr>
<tr>
<td style="font-weight: bold;">NATURE_QUANTITY_SCOPE</td>
<td></td>
</tr>
<tr>
<td>TOTAL_QUANTITY_OR_SCOPE</td>
<td><?php echo $contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->QUANTITY_SCOPE->NATURE_QUANTITY_SCOPE->TOTAL_QUANTITY_OR_SCOPE->P ?></td>
</tr>
<tr>
<td>COSTS_RANGE_AND_CURRENCY-LOW_VALUE</td>
<td><?php echo $contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->QUANTITY_SCOPE->NATURE_QUANTITY_SCOPE->COSTS_RANGE_AND_CURRENCY->RANGE_VALUE_COST->LOW_VALUE['FMTVAL'] ?></td>
</tr>
<tr>
<td>COSTS_RANGE_AND_CURRENCY-HIGH_VALUE</td>
<td><?php echo $contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->QUANTITY_SCOPE->NATURE_QUANTITY_SCOPE->COSTS_RANGE_AND_CURRENCY->RANGE_VALUE_COST->HIGH_VALUE['FMTVAL'] ?></td>
</tr>
<tr>
<td>COSTS_RANGE_AND_CURRENCY-CURRENCY</td>
<td><?php echo $contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->QUANTITY_SCOPE->NATURE_QUANTITY_SCOPE->COSTS_RANGE_AND_CURRENCY['CURRENCY'] ?></td>
</tr>
<tr>
<td>RECURRENT_CONTRACT</td>
<td><?php echo $contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->QUANTITY_SCOPE->RECURRENT_CONTRACT ?></td>
</tr>
<tr>
<td>PERIOD_WORK_DATE_STARTING</td>
<td><?php echo $contractV->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->PERIOD_WORK_DATE_STARTING->MONTHS ?></td>
</tr>
<tr>
<td>CONTRACT_RELATING_CONDITIONS</td>
<td><?php echo $contractV->FD_CONTRACT->LEFTI_CONTRACT->CONTRACT_RELATING_CONDITIONS->NO_EXISTENCE_OTHER_PARTICULAR_CONDITIONS ?></td>
</tr>
<tr>
<td>ECONOMIC_OPERATORS_PERSONAL_SITUATION</td>
<td><?php foreach($contractV->FD_CONTRACT->LEFTI_CONTRACT->F02_CONDITIONS_FOR_PARTICIPATION->ECONOMIC_OPERATORS_PERSONAL_SITUATION->P as $f_c) echo $f_c."<br/>"; ?></td>
</tr>
<tr>
<td>EAF_CAPACITY_INFORMATION</td>
<td><?php foreach($contractV->FD_CONTRACT->LEFTI_CONTRACT->F02_CONDITIONS_FOR_PARTICIPATION->F02_ECONOMIC_FINANCIAL_CAPACITY->EAF_CAPACITY_INFORMATION->P as $f_s) echo $f_s."<br/>"; ?></td>
</tr>
<tr>
<td>T_CAPACITY_INFORMATION</td>
<td><?php foreach($contractV->FD_CONTRACT->LEFTI_CONTRACT->F02_CONDITIONS_FOR_PARTICIPATION->TECHNICAL_CAPACITY_LEFTI->T_CAPACITY_INFORMATION->P as $f_t) echo $f_t."<br/>"; ?></td>
</tr>
<tr>
<td style="font-weight: bold;">PROCEDURE_DEFINITION_CONTRACT_NOTICE</td>
<td></td>
</tr>
<tr>
<td>TYPE_OF_PROCEDURE</td>
<td><?php echo $contractV->FD_CONTRACT->PROCEDURE_DEFINITION_CONTRACT_NOTICE->TYPE_OF_PROCEDURE->TYPE_OF_PROCEDURE_DETAIL_FOR_CONTRACT_NOTICE->PT_OPEN ?></td>
</tr>
<tr>
<td>CRITERIA_STATED_IN_OTHER_DOCUMENT</td>
<td><?php echo $contractV->FD_CONTRACT->PROCEDURE_DEFINITION_CONTRACT_NOTICE->AWARD_CRITERIA_CONTRACT_NOTICE_INFORMATION->AWARD_CRITERIA_DETAIL->MOST_ECONOMICALLY_ADVANTAGEOUS_TENDER->CRITERIA_STATED_IN_OTHER_DOCUMENT ?></td>
</tr>

<?php foreach($contractV->FD_CONTRACT->PROCEDURE_DEFINITION_CONTRACT_NOTICE->AWARD_CRITERIA_CONTRACT_NOTICE_INFORMATION->AWARD_CRITERIA_DETAIL->MOST_ECONOMICALLY_ADVANTAGEOUS_TENDER->CRITERIA_STATED_BELOW->CRITERIA_DEFINITION as $c_d){
	echo "<tr><td style='font-weight: bold;'>CRITERIA_DEFINITION</td><td></td></tr>";
	echo "<tr><td>ORDER_C</td><td>".$c_d->ORDER_C."</td></tr>";
	echo "<tr><td>CRITERIA</td><td>".$c_d->CRITERIA."</td></tr>";
	echo "<tr><td>WEIGHTING</td><td>".$c_d->WEIGHTING."</td></tr>";
}?>
<tr>
<td>IS_ELECTRONIC_AUCTION_USABLE</td>
<td><?php echo $contractV->FD_CONTRACT->PROCEDURE_DEFINITION_CONTRACT_NOTICE->AWARD_CRITERIA_CONTRACT_NOTICE_INFORMATION->IS_ELECTRONIC_AUCTION_USABLE->NO_USE_ELECTRONIC_AUCTION ?></td>
</tr>
<tr>
<td style="font-weight: bold;">ADMINISTRATIVE_INFORMATION_CONTRACT_NOTICE</td>
<td></td>
</tr>
<tr>
<td>FILE_REFERENCE_NUMBER</td>
<td><?php echo $contractV->FD_CONTRACT->PROCEDURE_DEFINITION_CONTRACT_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_NOTICE->FILE_REFERENCE_NUMBER->P ?></td>
</tr>
<tr>
<td>PRIOR_INFORMATION_NOTICE_F2</td>
<td><?php echo $contractV->FD_CONTRACT->PROCEDURE_DEFINITION_CONTRACT_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_NOTICE->PREVIOUS_PUBLICATION_INFORMATION_NOTICE_F2->PREVIOUS_PUBLICATION_EXISTS_F2->PREVIOUS_PUBLICATION_NOTICE_F2->PRIOR_INFORMATION_NOTICE_F2['CHOICE']?></td>
</tr>
<tr>
<td>NOTICE_NUMBER_OJ</td>
<td><?php echo $contractV->FD_CONTRACT->PROCEDURE_DEFINITION_CONTRACT_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_NOTICE->PREVIOUS_PUBLICATION_INFORMATION_NOTICE_F2->PREVIOUS_PUBLICATION_EXISTS_F2->PREVIOUS_PUBLICATION_NOTICE_F2->NOTICE_NUMBER_OJ ?></td>
</tr>
<tr>
<td>DATE_OJ</td>
<td><?php $date_oj = $contractV->FD_CONTRACT->PROCEDURE_DEFINITION_CONTRACT_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_NOTICE->PREVIOUS_PUBLICATION_INFORMATION_NOTICE_F2->PREVIOUS_PUBLICATION_EXISTS_F2->PREVIOUS_PUBLICATION_NOTICE_F2->DATE_OJ;echo $date_oj->YEAR."-".$date_oj->MONTH."-".$date_oj->DAY ?></td>
</tr>
<tr>
<td>TIME_LIMIT</td>
<td><?php $time_l = $contractV->FD_CONTRACT->PROCEDURE_DEFINITION_CONTRACT_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_NOTICE->CONDITIONS_OBTAINING_SPECIFICATIONS->TIME_LIMIT; echo $time_l->YEAR."-".$time_l->MONTH.":".$time_l->DAY." ".$time_l->TIME?></td>
</tr>
<tr>
<td>RECEIPT_LIMIT_DATE</td>
<td><?php $rtime_l = $contractV->FD_CONTRACT->PROCEDURE_DEFINITION_CONTRACT_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_NOTICE->RECEIPT_LIMIT_DATE; echo $rtime_l->YEAR."-".$time_l->MONTH.":".$time_l->DAY." ".$time_l->TIME ?></td>
</tr>
<tr>
<td>MINIMUM_TIME_MAINTAINING_TENDER-PERIOD_DAY</td>
<td><?php echo $contractV->FD_CONTRACT->PROCEDURE_DEFINITION_CONTRACT_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_NOTICE->MINIMUM_TIME_MAINTAINING_TENDER->PERIOD_DAY ?></td>
</tr>
<tr>
<td style="font-weight: bold;">CONDITIONS_FOR_OPENING_TENDERS</td>
<td></td>
</tr>
<tr>
<td>TIME_LIMIT</td>
<td><?php $time_lim = $contractV->FD_CONTRACT->PROCEDURE_DEFINITION_CONTRACT_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_NOTICE->CONDITIONS_FOR_OPENING_TENDERS->DATE_TIME; echo $time_lim->YEAR."-".$time_lim->MONTH."-".$time_lim->DAY." ".$time_lim->TIME ?></td>
</tr>
<tr>
<td>PLACE_OPENING</td>
<td><?php echo $contractV->FD_CONTRACT->PROCEDURE_DEFINITION_CONTRACT_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_NOTICE->CONDITIONS_FOR_OPENING_TENDERS->PLACE_OPENING->PLACE_NOT_STRUCTURED->P ?></td>
</tr>
<tr>
<td>ADDITIONAL_INFORMATION</td>
<td><?php foreach($contractV->FD_CONTRACT->COMPLEMENTARY_INFORMATION_CONTRACT_NOTICE->ADDITIONAL_INFORMATION->P as $add_in) echo $add_in."<br/>";?></td>
</tr>
<tr>
<td>NOTICE_DISPATCH_DATE</td>
<td><?php $no_date = $contractV->FD_CONTRACT->COMPLEMENTARY_INFORMATION_CONTRACT_NOTICE->NOTICE_DISPATCH_DATE; echo $no_date->YEAR."-".$no_date->MONTH."-".$no_date->DAY?></td>
</tr>
<tr>
<?php } ?>
</table>
</body>
</html>