<?php

header('Content-Type: text/html; charset=UTF-8');

function YmdChnage($input) {
    if (!empty($input)) {
        $date = DateTime::createFromFormat('Ymd', $input);
        return $date->format('Y-m-d');
    }
}

$GLOBALS['countryList'] = $db2->getCountryKeyList();

function getCountryName($code = NULL){
    if(!$code){
        return false;
    }else{
        $codeArr = (array)$code;
        if($codeArr[0] == 'UK'){
            $codeArr[0] = 'GB';
        }

        return $GLOBALS['countryList'][$codeArr[0]];
    }
}

/* function getCpv($cpvval, $db2) {
  $cpvarray = explode(",", $cpvval);
  if (count($cpvarray) != 0) {
  foreach ($cpvarray as $cpv) {
  if (!empty($cpv)) {
  $sectorData = getSector($cpv, $db2);
  foreach ($sectorData as $sector) {
  foreach ($sector as $sectorval) {
  //$secidArr[]=$sectorval['Sector_Id'];
  $subsecidArr[] = $sectorval['Sub_Sector_Id'];
  }
  }
  }
  }
  //$secidArr = array_unique($secidArr);
  $subsecidArr = array_unique($subsecidArr);
  $subSector = implode(",", $subsecidArr);
  return $subSector;
  }
  } */

function getCpv($cpvval, $db2) {
    $cpvarray = explode(",", $cpvval);
    if (count($cpvarray) != 0) {
        foreach ($cpvarray as $cpv) {
            if (!empty($cpv)) {
                $sectorData = getSector($cpv, $db2);
                foreach ($sectorData as $sector) {
                    $secidArr[] = $sector[0];
                    $subsecidArr[] = $sector[1];
                }
            }
        }
        $secidArr = array_unique($secidArr);
        $subsecidArr = array_unique($subsecidArr);
        $sectorCode = implode(",", $secidArr);
        $subSectorCode = implode(",", $subsecidArr);
        //return array("$sectorCode", $subSectorCode);
        return $subSectorCode;
    }
}

/* function getSector($cpv, $db2) {
  $rcpv[0] = substr($cpv, 0, 2);
  $rcpv[1] = substr($cpv, 0, 3);
  $rcpv[2] = substr($cpv, 0, 4);
  $rcpv[3] = substr($cpv, 0, 5);
  $rcpv[4] = substr($cpv, 0, 6);
  foreach ($rcpv as $rcp) {
  $sql = "SELECT Sector_Id,Sub_Sector_Id FROM tbl_sector WHERE Sub_Sector_Id like '$rcp%'";
  $sector[] = $db2->getQuery($sql);
  }
  return $sector;
  } */

function getSector($cpv, $db2) {
    $rcpv[0] = substr($cpv, 0, 2);
    $rcpv[1] = substr($cpv, 0, 3);
    $rcpv[2] = substr($cpv, 0, 4);
    $rcpv[3] = substr($cpv, 0, 5);
    $rcpv[4] = substr($cpv, 0, 6);
    foreach ($rcpv as $rcp) {
        $sql = "SELECT Sector_Id,Sub_Sector_Id FROM tbl_sector WHERE Sub_Sector_Id like '$rcp%'";
        $temp = $db2->get_sector_list($sql);
        if (!empty($temp)) {
            $sector[] = $temp;
        }
    }
    return $sector;
}

function getHtml($path, $db2 = NULL, $db2 = NULL) {
    $xml = simplexml_load_file($path);
    // echo "<pre>";
     // print_r($xml);
    $flag = true;

    $TD_DOCUMENT_TYPE = $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE;
    $COLL_OJ = $xml->CODED_DATA_SECTION->REF_OJS->COLL_OJ;
    $NO_OJ = $xml->CODED_DATA_SECTION->REF_OJS->NO_OJ;
    $DATE_PUB = $xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB;

    $tedId = $COLL_OJ . "-" . $NO_OJ;
    if (!empty($DATE_PUB))
        $pubDate = YmdChnage($DATE_PUB);
    else
        $pubDate = '';
    // echo $TD_DOCUMENT_TYPE."<br/>";
    // echo $xml['DOC_ID']."<br/>";
    // print_r($xml->FORM_SECTION);
    $CONTRACT = $xml->FORM_SECTION->CONTRACT;
    $DEFENCE = $xml->FORM_SECTION->CONTRACT_DEFENCE;
    $CONCESSION = $xml->FORM_SECTION->CONCESSION;
    $UTILITIES = $xml->FORM_SECTION->CONTRACT_UTILITIES;
    $CONTEST = $xml->FORM_SECTION->DESIGN_CONTEST;
    $PERIODIC = $xml->FORM_SECTION->PERIODIC_INDICATIVE_UTILITIES;
    $QUALIFICATION_SYSTEM_WITH = $xml->FORM_SECTION->QUALIFICATION_SYSTEM_UTILITIES;
    $PRIOR_INFORMATION = $xml->FORM_SECTION->PRIOR_INFORMATION;
    $OTH_NOT = $xml->FORM_SECTION->OTH_NOT;
    $SIMPLIFIED_CONTRACT = $xml->FORM_SECTION->SIMPLIFIED_CONTRACT;
    $PRIOR_INFORMATION_DEFENCE = $xml->FORM_SECTION->PRIOR_INFORMATION_DEFENCE;
    $PERIODIC_INDICATIVE_UTILITIES = $xml->FORM_SECTION->PERIODIC_INDICATIVE_UTILITIES;
    $PRIOR_INFORMATION_MOVE = $xml->FORM_SECTION->PRIOR_INFORMATION_MOVE;
    $CONTRACT_AWARD = $xml->FORM_SECTION->CONTRACT_AWARD;
    $CONTRACT_AWARD_UTILITIES = $xml->FORM_SECTION->CONTRACT_AWARD_UTILITIES;
    $CONTRACT_AWARD_DEFENCE = $xml->FORM_SECTION->CONTRACT_AWARD_DEFENCE;
    $RESULT_DESIGN_CONTEST = $xml->FORM_SECTION->RESULT_DESIGN_CONTEST;
    
    /*if (!empty($CONTRACT) && $TD_DOCUMENT_TYPE == 'Contract notice') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $xml['DOC_ID']."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = contract($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($CONTRACT) && $TD_DOCUMENT_TYPE == 'Dynamic purchasing system') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $xml['DOC_ID']."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = contract($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($DEFENCE) && $TD_DOCUMENT_TYPE == 'Contract notice') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $xml['DOC_ID']."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = defence($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($DEFENCE) && $TD_DOCUMENT_TYPE == 'Dynamic purchasing system') {
        echo $TD_DOCUMENT_TYPE . "<br/>";
        echo $xml['DOC_ID'] . "<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = defence($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($CONCESSION) && $TD_DOCUMENT_TYPE == 'Public works concession') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $xml['DOC_ID']."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = concession($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($UTILITIES) && $TD_DOCUMENT_TYPE == 'Contract notice') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $xml['DOC_ID']."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = utilities($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($UTILITIES) && $TD_DOCUMENT_TYPE == 'Dynamic purchasing system') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $xml['DOC_ID']."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = utilities($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($CONTEST) && $TD_DOCUMENT_TYPE == 'Design contest') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $xml['DOC_ID']."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = design_contest($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($PERIODIC) && $TD_DOCUMENT_TYPE == 'Periodic indicative notice (PIN) without call for competition') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $xml['DOC_ID']."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = preiodic_indicative($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($QUALIFICATION_SYSTEM_WITH) && ($TD_DOCUMENT_TYPE == 'Qualification system without call for competition' || $TD_DOCUMENT_TYPE == 'Qualification system with call for competition' )) {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $xml['DOC_ID']."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = qualification_system_with($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($PRIOR_INFORMATION) && $TD_DOCUMENT_TYPE == 'Prior Information Notice') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = prior_information_notice($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($OTH_NOT) && $TD_DOCUMENT_TYPE == 'Call for expressions of interest') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $TD_DOCUMENT_TYPE."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = call_expressions_interest($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($OTH_NOT) && $TD_DOCUMENT_TYPE == 'Contract notice') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $TD_DOCUMENT_TYPE."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = call_expressions_interest($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($OTH_NOT) && $TD_DOCUMENT_TYPE == 'Prior Information Notice') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $TD_DOCUMENT_TYPE."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = call_expressions_interest($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($SIMPLIFIED_CONTRACT) && $TD_DOCUMENT_TYPE == 'Contract notice') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $TD_DOCUMENT_TYPE."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = simplefied_interest($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($PRIOR_INFORMATION_DEFENCE) && $TD_DOCUMENT_TYPE == 'Prior Information Notice') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $TD_DOCUMENT_TYPE."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = prior_information_defence($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($PERIODIC_INDICATIVE_UTILITIES) && $TD_DOCUMENT_TYPE == 'Prior Information Notice') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $TD_DOCUMENT_TYPE."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = prior_indicative_utilities($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($PRIOR_INFORMATION_MOVE) && $TD_DOCUMENT_TYPE == 'Contract notice') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $TD_DOCUMENT_TYPE."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = prior_info_move($xml);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }*/
    if (!empty($CONTRACT_AWARD) && $TD_DOCUMENT_TYPE == 'Contract award notice') {
        // echo $TD_DOCUMENT_TYPE."<br/>";
        // echo $xml['DOC_ID']."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = contract_award($xml, $db2, $db2);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    /*
    if (!empty($CONTRACT_AWARD_UTILITIES) && $TD_DOCUMENT_TYPE == 'Contract award notice') {
         // echo $TD_DOCUMENT_TYPE."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = contract_award_utili($xml, $db2, $db2);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($CONTRACT_AWARD_DEFENCE) && $TD_DOCUMENT_TYPE == 'Contract award notice') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $TD_DOCUMENT_TYPE."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = contract_award_defence($xml, $db2, $db2);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }
    if (!empty($RESULT_DESIGN_CONTEST) && $TD_DOCUMENT_TYPE == 'Results of design contests') {
        //echo $TD_DOCUMENT_TYPE."<br/>";
        //echo $TD_DOCUMENT_TYPE."<br/>";
        $flag = false;
        $file = $xml['DOC_ID'];
        $html = result_design_contest($xml, $db2, $db2);
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file");
        return $data;
    }*/
    if ($flag) {
        $file = $xml['DOC_ID'];
        $tedId = $COLL_OJ . "-" . $NO_OJ;
        $pubDate = YmdChnage($DATE_PUB);
        $html = '';
        $data = array("html" => "$html", "doc" => "$TD_DOCUMENT_TYPE", "fileId" => "$file", "tedId" => "$tedId", "pubDate" => "$pubDate");
        return $data;
    }
}

function contract($xml) {
    //echo $xml['DOC_ID'];
    $htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>';
    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $town = $ml_ti->TI_TOWN;
            $htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>" . $ml_ti->TI_TEXT . "</td></tr>";
        }
    }
    //echo $htmlData;
    foreach ($xml->FORM_SECTION->CONTRACT as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
        }
    }
    if (empty($data)) {
        $data = $xml->FORM_SECTION->CONTRACT;
    }
    if (empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)) {
        $Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
        $newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
    } else {
        $newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION, 0, 8));
    }
    //echo "<pre>";
    //print_r($xml);
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">DOC_ID</td>
	<td>' . $xml['DOC_ID'] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DATE_PUB</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NO_OJ</td>
	<td>' . $xml->CODED_DATA_SECTION->REF_OJS->NO_OJ . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TOWN</td>
	<td>' . $town . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>' . $data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>' . $data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>' . $data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>' . $data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>' . $data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>' . $data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->PHONE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>' . $data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>' . $data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->FAX . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>' . $data["LG"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
	<td>';
    $country = $data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"];
    if ($country == 'UK') {
        $country = 'GB';
    }
    $htmlData .=$country . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AA_AUTHORITY_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DS_DATE_DISPATCH</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DT_DATE_FOR_SUBMISSION</td>
	<td>' . $newDate . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NC_CONTRACT_NATURE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PR_PROC</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->PR_PROC . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">RP_REGULATION</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TY_TYPE_BID</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TY_TYPE_BID . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AC_AWARD_CRIT</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AC_AWARD_CRIT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CPV_CODE</td>
	<td>' . $data->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->CPV->CPV_MAIN->CPV_CODE["CODE"];
    foreach ($data->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->CPV->CPV_ADDITIONAL as $cpv_ad)
        $htmlData .= "," . $cpv_ad->CPV_CODE['CODE'];
    $htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>' . $data->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->LOCATION_NUTS->NUTS["CODE"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">IA_URL_GENERAL</td>
	<td>' . $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DIRECTIVE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'] . '</td>
	</tr><td style="font-weight: bold;">DESCRIPTION</td>
	<td>';
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $htmlData .= $ocpv . ".<br/>";
    }
    $htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
    foreach ($data->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->SHORT_CONTRACT_DESCRIPTION->P as $shrtDesc) {
        $htmlData .="$shrtDesc<br/>";
    }
    $htmlData .='</td></tr>
	</table></body></html>';
    //echo $htmlData;
    return $htmlData;
}

function defence($xml) {
    $htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>';
    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $town = $ml_ti->TI_TOWN;
            $htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>" . $ml_ti->TI_TEXT . "</td></tr>";
        }
    }
    //echo $htmlData;
    foreach ($xml->FORM_SECTION->CONTRACT_DEFENCE as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
        }
    }
    if (empty($data)) {
        $data = $xml->FORM_SECTION->CONTRACT_DEFENCE;
    }
    if (empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)) {
        $Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
        $newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
    } else {
        $newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION, 0, 8));
    }
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">DOC_ID</td>
	<td>' . $xml['DOC_ID'] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DATE_PUB</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NO_OJ</td>
	<td>' . $xml->CODED_DATA_SECTION->REF_OJS->NO_OJ . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TOWN</td>
	<td>' . $town . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>' . $data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>' . $data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>' . $data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>' . $data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>' . $data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>' . $data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->PHONE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>' . $data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>' . $data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->FAX . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>' . $data["LG"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
        <td>';
    $country = $data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"];
    if ($country == 'UK') {
        $country = 'GB';
    }
    $htmlData .=$country . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AA_AUTHORITY_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DS_DATE_DISPATCH</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DT_DATE_FOR_SUBMISSION</td>
	<td>' . $newDate . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NC_CONTRACT_NATURE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PR_PROC</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->PR_PROC . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">RP_REGULATION</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TY_TYPE_BID</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TY_TYPE_BID . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AC_AWARD_CRIT</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AC_AWARD_CRIT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CPV_CODE</td>
	<td>' . $data->FD_CONTRACT_DEFENCE->OBJECT_CONTRACT_INFORMATION_DEFENCE->DESCRIPTION_CONTRACT_INFORMATION_DEFENCE->CPV->CPV_MAIN->CPV_CODE["CODE"];
    foreach ($data->FD_CONTRACT_DEFENCE->OBJECT_CONTRACT_INFORMATION_DEFENCE->DESCRIPTION_CONTRACT_INFORMATION_DEFENCE->CPV->CPV_ADDITIONAL as $cpv_ad)
        $htmlData .= "," . $cpv_ad->CPV_CODE['CODE'];
    $htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>' . $data->FD_CONTRACT_DEFENCE->OBJECT_CONTRACT_INFORMATION_DEFENCE->DESCRIPTION_CONTRACT_INFORMATION_DEFENCE->LOCATION_NUTS->NUTS["CODE"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">IA_URL_GENERAL</td>
	<td>' . $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DIRECTIVE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'] . '</td>
	</tr><td style="font-weight: bold;">DESCRIPTION</td>
	<td>';
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $htmlData .= $ocpv . ".<br/>";
    }
    $htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>' . $data->FD_CONTRACT_DEFENCE->OBJECT_CONTRACT_INFORMATION_DEFENCE->DESCRIPTION_CONTRACT_INFORMATION_DEFENCE->SHORT_CONTRACT_DESCRIPTION->P . '</td>
	</tr>
	</table></body></html>';

    $htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
    foreach ($data->FD_CONTRACT_DEFENCE->OBJECT_CONTRACT_INFORMATION_DEFENCE->DESCRIPTION_CONTRACT_INFORMATION_DEFENCE->SHORT_CONTRACT_DESCRIPTION->P as $shrtDesc) {
        $htmlData .="$shrtDesc<br/>";
    }
    $htmlData .='</td></tr>
	</table></body></html>';
    return $htmlData;
}

function concession($xml) {
    $htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';

    $htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>';
    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $town = $ml_ti->TI_TOWN;
            $htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>" . $ml_ti->TI_TEXT . "</td></tr>";
        }
    }
    foreach ($xml->FORM_SECTION->CONCESSION as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
        }
    }
    if (empty($data)) {
        $data = $xml->FORM_SECTION->CONCESSION;
    }
    if (empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)) {
        $Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
        $newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
    } else {
        $newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION, 0, 8));
    }
    //echo "<pre>";
    //print_r($data);
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">DOC_ID</td>
	<td>' . $xml['DOC_ID'] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DATE_PUB</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NO_OJ</td>
	<td>' . $xml->CODED_DATA_SECTION->REF_OJS->NO_OJ . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TOWN</td>
	<td>' . $town . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>' . $data->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>' . $data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>' . $data->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>' . $data->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>' . $data->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>' . $data->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->PHONE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>' . $data->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>' . $data->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->FAX . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>' . $data["LG"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
        <td>';
    $country = $data->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"];
    if ($country == 'UK') {
        $country = 'GB';
    }
    $htmlData .=$country . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AA_AUTHORITY_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DS_DATE_DISPATCH</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DT_DATE_FOR_SUBMISSION</td>
	<td>' . $newDate . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NC_CONTRACT_NATURE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PR_PROC</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->PR_PROC . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">RP_REGULATION</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TY_TYPE_BID</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TY_TYPE_BID . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AC_AWARD_CRIT</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AC_AWARD_CRIT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CPV_CODE</td>
	<td>' . $data->FD_CONCESSION->OBJECT_CONCESSION->DESCRIPTION_CONCESSION->CPV->CPV_MAIN->CPV_CODE["CODE"];
    foreach ($data->FD_CONCESSION->OBJECT_CONCESSION->DESCRIPTION_CONCESSION->CPV->CPV_ADDITIONAL as $cpv_ad)
        $htmlData .= "," . $cpv_ad->CPV_CODE['CODE'];
    $htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>' . $data->FD_CONCESSION->OBJECT_CONCESSION->DESCRIPTION_CONCESSION->F10_TYPE_OF_WORKS_CONTRACT->NUTS["CODE"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">IA_URL_GENERAL</td>
	<td>' . $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DIRECTIVE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'] . '</td>
	</tr><td style="font-weight: bold;">DESCRIPTION</td>
	<td>';
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $htmlData .= $ocpv . ".<br/>";
    }
    $htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
    foreach ($data->FD_CONCESSION->OBJECT_CONCESSION->DESCRIPTION_CONCESSION->DESCRIPTION_OF_CONTRACT->P as $shrtDesc) {
        $htmlData .="$shrtDesc<br/>";
    }
    $htmlData .='</td></tr>
	</table></body></html>';
    return $htmlData;
}

function utilities($xml) {
    //echo $xml['DOC_ID'];
    $htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>';
    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $town = $ml_ti->TI_TOWN;
            $htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>" . $ml_ti->TI_TEXT . "</td></tr>";
        }
    }
    //echo $htmlData;
    foreach ($xml->FORM_SECTION->CONTRACT_UTILITIES as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
        }
    }
    if (empty($data)) {
        $data = $xml->FORM_SECTION->CONTRACT_UTILITIES;
    }
    if (empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)) {
        $Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
        $newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
    } else {
        $newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION, 0, 8));
    }
    //echo "<pre>";
    //print_r($xml);
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">DOC_ID</td>
	<td>' . $xml['DOC_ID'] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DATE_PUB</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NO_OJ</td>
	<td>' . $xml->CODED_DATA_SECTION->REF_OJS->NO_OJ . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TOWN</td>
	<td>' . $town . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>' . $data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>' . $data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>' . $data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>' . $data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>' . $data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>' . $data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->PHONE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>' . $data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>' . $data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->FAX . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>' . $data["LG"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
        <td>';
    $country = $data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"];
    if ($country == 'UK') {
        $country = 'GB';
    }
    $htmlData .=$country . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AA_AUTHORITY_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DS_DATE_DISPATCH</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DT_DATE_FOR_SUBMISSION</td>
	<td>' . $newDate . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NC_CONTRACT_NATURE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PR_PROC</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->PR_PROC . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">RP_REGULATION</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TY_TYPE_BID</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TY_TYPE_BID . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AC_AWARD_CRIT</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AC_AWARD_CRIT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CPV_CODE</td>
	<td>' . $data->FD_CONTRACT_UTILITIES->OBJECT_CONTRACT_INFORMATION_CONTRACT_UTILITIES->CONTRACT_OBJECT_DESCRIPTION->CPV->CPV_MAIN->CPV_CODE["CODE"];
    foreach ($data->FD_CONTRACT_UTILITIES->OBJECT_CONTRACT_INFORMATION_CONTRACT_UTILITIES->CONTRACT_OBJECT_DESCRIPTION->CPV->CPV_ADDITIONAL as $cpv_ad)
        $htmlData .= "," . $cpv_ad->CPV_CODE['CODE'];
    $htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>' . $data->FD_CONTRACT_UTILITIES->OBJECT_CONTRACT_INFORMATION_CONTRACT_UTILITIES->CONTRACT_OBJECT_DESCRIPTION->LOCATION_NUTS->NUTS["CODE"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">IA_URL_GENERAL</td>
	<td>' . $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DIRECTIVE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'] . '</td>
	</tr><td style="font-weight: bold;">DESCRIPTION</td>
	<td>';
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $htmlData .= $ocpv . ".<br/>";
    }
    $htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>' . $data->FD_CONTRACT_UTILITIES->OBJECT_CONTRACT_INFORMATION_CONTRACT_UTILITIES->CONTRACT_OBJECT_DESCRIPTION->SHORT_CONTRACT_DESCRIPTION->P . '</td>
	</tr>
	</table></body></html>';
    return $htmlData;
}

function design_contest($xml) {
    $htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>';
    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $town = $ml_ti->TI_TOWN;
            $htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>" . $ml_ti->TI_TEXT . "</td></tr>";
        }
    }
    //echo $htmlData;
    foreach ($xml->FORM_SECTION->DESIGN_CONTEST as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
        }
    }
    if (empty($data)) {
        $data = $xml->FORM_SECTION->DESIGN_CONTEST;
    }
    if (empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)) {
        $Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
        $newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
    } else {
        $newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION, 0, 8));
    }
    //echo "<pre>";
    //print_r($xml);
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">DOC_ID</td>
	<td>' . $xml['DOC_ID'] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DATE_PUB</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NO_OJ</td>
	<td>' . $xml->CODED_DATA_SECTION->REF_OJS->NO_OJ . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TOWN</td>
	<td>' . $town . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>' . $data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>' . $data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>' . $data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>' . $data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>' . $data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>' . $data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->PHONE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>' . $data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>' . $data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->FAX . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>' . $data["LG"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
        <td>';
    $country = $data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"];
    if ($country == 'UK') {
        $country = 'GB';
    }
    $htmlData .=$country . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AA_AUTHORITY_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DS_DATE_DISPATCH</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DT_DATE_FOR_SUBMISSION</td>
	<td>' . $newDate . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NC_CONTRACT_NATURE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PR_PROC</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->PR_PROC . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">RP_REGULATION</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TY_TYPE_BID</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TY_TYPE_BID . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AC_AWARD_CRIT</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AC_AWARD_CRIT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CPV_CODE</td>
	<td>' . $data->FD_DESIGN_CONTEST->OBJECT_DESIGN_CONTEST->CPV->CPV_MAIN->CPV_CODE["CODE"];
    foreach ($data->FD_DESIGN_CONTEST->OBJECT_DESIGN_CONTEST->CPV->CPV_ADDITIONAL as $cpv_ad)
        $htmlData .= "," . $cpv_ad->CPV_CODE['CODE'];
    $htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>' . $data->FD_DESIGN_CONTEST->OBJECT_DESIGN_CONTEST->LOCATION_NUTS->NUTS["CODE"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">IA_URL_GENERAL</td>
	<td>' . $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DIRECTIVE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'] . '</td>
	</tr><td style="font-weight: bold;">DESCRIPTION</td>
	<td>';
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $htmlData .= $ocpv . ".<br/>";
    }
    $htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>' . $data->FD_DESIGN_CONTEST->OBJECT_DESIGN_CONTEST->SHORT_DESCRIPTION_CONTRACT->P . '</td>
	</tr>
	</table></body></html>';
    return $htmlData;
}

function preiodic_indicative($xml) {
    $htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>';
    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $town = $ml_ti->TI_TOWN;
            $htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>" . $ml_ti->TI_TEXT . "</td></tr>";
        }
    }
    //echo $htmlData;
    foreach ($xml->FORM_SECTION->PERIODIC_INDICATIVE_UTILITIES as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
        }
    }
    if (empty($data)) {
        $data = $xml->FORM_SECTION->PERIODIC_INDICATIVE_UTILITIES;
    }
    $Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
    $ddate = $xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION;
    if (empty($ddate)) {
        $newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
    } else {
        $newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION, 0, 8));
    }
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">DOC_ID</td>
	<td>' . $xml['DOC_ID'] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DATE_PUB</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NO_OJ</td>
	<td>' . $xml->CODED_DATA_SECTION->REF_OJS->NO_OJ . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TOWN</td>
	<td>' . $town . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->PHONE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->FAX . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>' . $data["LG"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
        <td>';
    $country = $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"];
    if ($country == 'UK') {
        $country = 'GB';
    }
    $htmlData .=$country . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AA_AUTHORITY_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DS_DATE_DISPATCH</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DT_DATE_FOR_SUBMISSION</td>
	<td>' . $newDate . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NC_CONTRACT_NATURE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PR_PROC</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->PR_PROC . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">RP_REGULATION</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TY_TYPE_BID</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TY_TYPE_BID . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AC_AWARD_CRIT</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AC_AWARD_CRIT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CPV_CODE</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->OBJECT_CONTRACT_PERIODIC_INDICATIVE->CPV->CPV_MAIN->CPV_CODE["CODE"];
    foreach ($data->FD_PERIODIC_INDICATIVE_UTILITIES->OBJECT_CONTRACT_PERIODIC_INDICATIVE->CPV->CPV_ADDITIONAL as $cpv_ad)
        $htmlData .= "," . $cpv_ad->CPV_CODE['CODE'];
    $htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->OBJECT_CONTRACT_PERIODIC_INDICATIVE->LOCATION_NUTS->NUTS["CODE"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">IA_URL_GENERAL</td>
	<td>' . $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DIRECTIVE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'] . '</td>
	</tr><td style="font-weight: bold;">DESCRIPTION</td>
	<td>';
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $htmlData .= $ocpv . ".<br/>";
    }
    $htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
    foreach ($data->FD_PERIODIC_INDICATIVE_UTILITIES->OBJECT_CONTRACT_PERIODIC_INDICATIVE->DESCRIPTION_OF_CONTRACT->P as $shrtDesc) {
        $htmlData .="$shrtDesc<br/>";
    }
    $htmlData .='</td></tr>
	</table></body></html>';
    return $htmlData;
}

function qualification_system_with($xml) {
    $htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>';
    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $town = $ml_ti->TI_TOWN;
            $htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>" . $ml_ti->TI_TEXT . "</td></tr>";
        }
    }
    //echo $htmlData;
    foreach ($xml->FORM_SECTION->QUALIFICATION_SYSTEM_UTILITIES as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
        }
    }
    if (empty($data)) {
        $data = $xml->FORM_SECTION->QUALIFICATION_SYSTEM_UTILITIES;
    }

    if (empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)) {
        $Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
        $newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
    } else {
        $newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION, 0, 8));
    }
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">DOC_ID</td>
	<td>' . $xml['DOC_ID'] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DATE_PUB</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NO_OJ</td>
	<td>' . $xml->CODED_DATA_SECTION->REF_OJS->NO_OJ . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TOWN</td>
	<td>' . $town . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>' . $data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>' . $data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>' . $data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>' . $data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>' . $data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>' . $data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->PHONE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>' . $data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>' . $data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->FAX . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>' . $data["LG"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
        <td>';
    $country = $data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"];
    if ($country == 'UK') {
        $country = 'GB';
    }
    $htmlData .=$country . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AA_AUTHORITY_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DS_DATE_DISPATCH</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DT_DATE_FOR_SUBMISSION</td>
	<td>' . $newDate . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NC_CONTRACT_NATURE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PR_PROC</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->PR_PROC . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">RP_REGULATION</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TY_TYPE_BID</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TY_TYPE_BID . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AC_AWARD_CRIT</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AC_AWARD_CRIT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CPV_CODE</td>
	<td>' . $data->FD_QUALIFICATION_SYSTEM_UTILITIES->OBJECT_QUALIFICATION_SYSTEM->CPV->CPV_MAIN->CPV_CODE["CODE"];
    foreach ($data->FD_QUALIFICATION_SYSTEM_UTILITIES->OBJECT_QUALIFICATION_SYSTEM->CPV->CPV_ADDITIONAL as $cpv_ad)
        $htmlData .= "," . $cpv_ad->CPV_CODE['CODE'];
    $htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>' . $data->FD_QUALIFICATION_SYSTEM_UTILITIES->OBJECT_QUALIFICATION_SYSTEM->LOCATION_NUTS->NUTS["CODE"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">IA_URL_GENERAL</td>
	<td>' . $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DIRECTIVE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'] . '</td>
	</tr><td style="font-weight: bold;">DESCRIPTION</td>
	<td>';
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $htmlData .= $ocpv . ".<br/>";
    }
    $htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
    foreach ($data->FD_QUALIFICATION_SYSTEM_UTILITIES->OBJECT_QUALIFICATION_SYSTEM->DESCRIPTION->P as $shrtDesc) {
        $htmlData .="$shrtDesc<br/>";
    }
    $htmlData .='</td></tr>
	</table></body></html>';
    return $htmlData;
}

function prior_information_notice($xml) {
    $htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>';
    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $town = $ml_ti->TI_TOWN;
            $htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>" . $ml_ti->TI_TEXT . "</td></tr>";
        }
    }
    //echo $htmlData;
    foreach ($xml->FORM_SECTION->PRIOR_INFORMATION as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
        }
    }
    if (empty($data)) {
        $data = $xml->FORM_SECTION->PRIOR_INFORMATION;
    }
    if (empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)) {
        $Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
        $newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
    } else {
        $newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION, 0, 8));
    }
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">DOC_ID</td>
	<td>' . $xml['DOC_ID'] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DATE_PUB</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NO_OJ</td>
	<td>' . $xml->CODED_DATA_SECTION->REF_OJS->NO_OJ . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TOWN</td>
	<td>' . $town . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>' . $data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>' . $data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>' . $data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>' . $data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>' . $data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>' . $data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->PHONE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>' . $data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>' . $data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->FAX . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>' . $data["LG"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
        <td>';
    $country = $data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"];
    if ($country == 'UK') {
        $country = 'GB';
    }
    $htmlData .=$country . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AA_AUTHORITY_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DS_DATE_DISPATCH</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DT_DATE_FOR_SUBMISSION</td>
	<td>' . $newDate . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NC_CONTRACT_NATURE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PR_PROC</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->PR_PROC . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">RP_REGULATION</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TY_TYPE_BID</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TY_TYPE_BID . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AC_AWARD_CRIT</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AC_AWARD_CRIT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CPV_CODE</td>';
    if (!empty($data->FD_PRIOR_INFORMATION->OBJECT_SUPPLIES_SERVICES_PRIOR_INFORMATION)) {
        $htmlData .='<td>' . $data->FD_PRIOR_INFORMATION->OBJECT_SUPPLIES_SERVICES_PRIOR_INFORMATION->OBJECT_SUPPLY_SERVICE_PRIOR_INFORMATION->CPV->CPV_MAIN->CPV_CODE["CODE"];
        foreach ($data->FD_PRIOR_INFORMATION->OBJECT_SUPPLIES_SERVICES_PRIOR_INFORMATION->OBJECT_SUPPLY_SERVICE_PRIOR_INFORMATION->CPV->CPV_ADDITIONAL as $cpv_ad)
            $htmlData .= "," . $cpv_ad->CPV_CODE['CODE'];
    } elseif (!empty($data->FD_PRIOR_INFORMATION->OBJECT_WORKS_PRIOR_INFORMATION)) {
        $htmlData .='<td>' . $data->FD_PRIOR_INFORMATION->OBJECT_WORKS_PRIOR_INFORMATION->CPV->CPV_MAIN->CPV_CODE["CODE"];
        foreach ($data->FD_PRIOR_INFORMATION->OBJECT_WORKS_PRIOR_INFORMATION->CPV->CPV_ADDITIONAL as $cpv_ad)
            $htmlData .= "," . $cpv_ad->CPV_CODE['CODE'];
    }
    $htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>' . $data->FD_PRIOR_INFORMATION->OBJECT_SUPPLIES_SERVICES_PRIOR_INFORMATION->OBJECT_SUPPLY_SERVICE_PRIOR_INFORMATION->TYPE_CONTRACT_PLACE_DELIVERY->SITE_OR_LOCATION->NUTS["CODE"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">IA_URL_GENERAL</td>
	<td>' . $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DIRECTIVE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'] . '</td>
	</tr><td style="font-weight: bold;">DESCRIPTION</td>
	<td>';
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $htmlData .= $ocpv . ".<br/>";
    }
    $htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
    foreach ($data->FD_PRIOR_INFORMATION->OBJECT_SUPPLIES_SERVICES_PRIOR_INFORMATION->OBJECT_SUPPLY_SERVICE_PRIOR_INFORMATION->QUANTITY_SCOPE_PRIOR_INFORMATION->TOTAL_QUANTITY_OR_SCOPE->P as $shrtDesc) {
        $htmlData .="$shrtDesc<br/>";
    }
    $htmlData .='</td></tr>
	</table></body></html>';
    return $htmlData;
}

function call_expressions_interest($xml) {
    $htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>';
    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $town = $ml_ti->TI_TOWN;
            $htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>" . $ml_ti->TI_TEXT . "</td></tr>";
        }
    }
    //echo $htmlData;
    foreach ($xml->FORM_SECTION->OTH_NOT as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
        }
    }
    if (empty($data)) {
        $data = $xml->FORM_SECTION->OTH_NOT;
    }
    ///echo "<pre>";
    //print_r($xml);
    //print_r($data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR[2]);
    if (empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)) {
        $Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
        $newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
    } else {
        $newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION, 0, 8));
    }
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">DOC_ID</td>
	<td>' . $xml['DOC_ID'] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DATE_PUB</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NO_OJ</td>
	<td>' . $xml->CODED_DATA_SECTION->REF_OJS->NO_OJ . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TOWN</td>
	<td>' . $town . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>' . $data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR->TXT_MARK->P->ADDRESS_NOT_STRUCT->ORGANISATION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>' . $data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR->TXT_MARK->P->ADDRESS_NOT_STRUCT->ADDRESS . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>' . $data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR->TXT_MARK->P->ADDRESS_NOT_STRUCT->POSTAL_CODE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>' . $data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR->TXT_MARK->P->ADDRESS_NOT_STRUCT->CONTACT_POINT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>' . $data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR->TXT_MARK->P->ADDRESS_NOT_STRUCT->ATTENTION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>' . $data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR->TXT_MARK->P->ADDRESS_NOT_STRUCT->PHONE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>' . $data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR->TXT_MARK->P->ADDRESS_NOT_STRUCT->E_MAIL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>' . $data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR->TXT_MARK->P->ADDRESS_NOT_STRUCT->FAX . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>' . $data["LG"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
        <td>';
    $country = $xml->CODED_DATA_SECTION->NOTICE_DATA->ISO_COUNTRY["VALUE"];
    if ($country == 'UK') {
        $country = 'GB';
    }
    $htmlData .=$country . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AA_AUTHORITY_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DS_DATE_DISPATCH</td>
	<td>' . $newDate . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DT_DATE_FOR_SUBMISSION</td>
	<td>' . $newDate . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NC_CONTRACT_NATURE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PR_PROC</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->PR_PROC . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">RP_REGULATION</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TY_TYPE_BID</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TY_TYPE_BID . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AC_AWARD_CRIT</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AC_AWARD_CRIT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CPV_CODE</td>
	<td>';
    foreach ($data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR[2]->TXT_MARK->P as $cpv) {
        if (!empty($cpv) && is_numeric(substr($cpv, 0, 8))) {
            $htmlData .= substr($cpv, 0, 8) . ",";
        }
    }
    $htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>' . $data->FD_OTH_NOT->OBJECT_DESIGN_CONTEST->LOCATION_NUTS->NUTS["CODE"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">IA_URL_GENERAL</td>
	<td>' . $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DIRECTIVE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'] . '</td>
	</tr><td style="font-weight: bold;">DESCRIPTION</td>
	<td>';
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $htmlData .= $ocpv . ".<br/>";
    }
    $htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td></td>
	</tr>
	</table></body></html>';
    return $htmlData;
}

function simplefied_interest($xml) {
    $htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>';
    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $town = $ml_ti->TI_TOWN;
            $htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>" . $ml_ti->TI_TEXT . "</td></tr>";
        }
    }
    //echo $htmlData;
    foreach ($xml->FORM_SECTION->SIMPLIFIED_CONTRACT as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
        }
    }
    if (empty($data)) {
        $data = $xml->FORM_SECTION->SIMPLIFIED_CONTRACT;
    }
    if (empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)) {
        $Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
        $newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
    } else {
        $newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION, 0, 8));
    }
    //echo "<pre>";
    //print_r($xml);
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">DOC_ID</td>
	<td>' . $xml['DOC_ID'] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DATE_PUB</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NO_OJ</td>
	<td>' . $xml->CODED_DATA_SECTION->REF_OJS->NO_OJ . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TOWN</td>
	<td>' . $town . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>' . $data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>' . $data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>' . $data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>' . $data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>' . $data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>' . $data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->PHONE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>' . $data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>' . $data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->FAX . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>' . $data["LG"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
        <td>';
    $country = $data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"];
    if ($country == 'UK') {
        $country = 'GB';
    }
    $htmlData .=$country . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AA_AUTHORITY_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DS_DATE_DISPATCH</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DT_DATE_FOR_SUBMISSION</td>
	<td>' . $newDate . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NC_CONTRACT_NATURE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PR_PROC</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->PR_PROC . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">RP_REGULATION</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TY_TYPE_BID</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TY_TYPE_BID . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AC_AWARD_CRIT</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AC_AWARD_CRIT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CPV_CODE</td>
	<td>' . $data->FD_SIMPLIFIED_CONTRACT->OBJECT_SIMPLIFIED_CONTRACT_NOTICE->CPV->CPV_MAIN->CPV_CODE["CODE"];
    foreach ($data->FD_SIMPLIFIED_CONTRACT->OBJECT_SIMPLIFIED_CONTRACT_NOTICE->CPV->CPV_ADDITIONAL as $cpv_ad)
        $htmlData .= "," . $cpv_ad->CPV_CODE['CODE'];

    $htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>' . $data->FD_SIMPLIFIED_CONTRACT->OBJECT_SIMPLIFIED_CONTRACT_NOTICE->LOCATION_NUTS->NUTS["CODE"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">IA_URL_GENERAL</td>
	<td>' . $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DIRECTIVE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'] . '</td>
	</tr><td style="font-weight: bold;">DESCRIPTION</td>
	<td>';
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $htmlData .= $ocpv . ".<br/>";
    }
    $htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
    foreach ($data->FD_SIMPLIFIED_CONTRACT->OBJECT_SIMPLIFIED_CONTRACT_NOTICE->SHORT_DESCRIPTION_CONTRACT->P as $shrtDesc) {
        $htmlData .="$shrtDesc<br/>";
    }
    $htmlData .='</td></tr>
	</table></body></html>';
    return $htmlData;
}

function prior_information_defence($xml) {
    $htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>';
    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $town = $ml_ti->TI_TOWN;
            $htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>" . $ml_ti->TI_TEXT . "</td></tr>";
        }
    }
    //echo $htmlData;
    foreach ($xml->FORM_SECTION->PRIOR_INFORMATION_DEFENCE as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
        }
    }
    if (empty($data)) {
        $data = $xml->FORM_SECTION->PRIOR_INFORMATION_DEFENCE;
    }

    if (empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)) {
        $Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
        $newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
    } else {
        $newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION, 0, 8));
    }
    //echo "<pre>";
    //print_r($xml);
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">DOC_ID</td>
	<td>' . $xml['DOC_ID'] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DATE_PUB</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NO_OJ</td>
	<td>' . $xml->CODED_DATA_SECTION->REF_OJS->NO_OJ . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TOWN</td>
	<td>' . $town . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>' . $data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>' . $data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>' . $data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>' . $data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>' . $data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>' . $data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->PHONE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>' . $data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>' . $data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->FAX . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>' . $data["LG"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
        <td>';
    $country = $data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"];
    if ($country == 'UK') {
        $country = 'GB';
    }
    $htmlData .=$country . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AA_AUTHORITY_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DS_DATE_DISPATCH</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DT_DATE_FOR_SUBMISSION</td>
	<td>' . $newDate . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NC_CONTRACT_NATURE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PR_PROC</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->PR_PROC . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">RP_REGULATION</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TY_TYPE_BID</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TY_TYPE_BID . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AC_AWARD_CRIT</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AC_AWARD_CRIT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CPV_CODE</td>
	<td>' . $data->FD_PRIOR_INFORMATION_DEFENCE->OBJECT_WORKS_SUPPLIES_SERVICES_PRIOR_INFORMATION->CPV->CPV_MAIN->CPV_CODE["CODE"];
    foreach ($data->FD_PRIOR_INFORMATION_DEFENCE->OBJECT_WORKS_SUPPLIES_SERVICES_PRIOR_INFORMATION->CPV->CPV_ADDITIONAL as $cpv_ad)
        $htmlData .= "," . $cpv_ad->CPV_CODE['CODE'];

    $htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>' . $data->FD_PRIOR_INFORMATION_DEFENCE->OBJECT_WORKS_SUPPLIES_SERVICES_PRIOR_INFORMATION->TYPE_CONTRACT_PLACE_DELIVERY_DEFENCE->SITE_OR_LOCATION->NUTS["CODE"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">IA_URL_GENERAL</td>
	<td>' . $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DIRECTIVE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'] . '</td>
	</tr><td style="font-weight: bold;">DESCRIPTION</td>
	<td>';
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $htmlData .= $ocpv . ".<br/>";
    }
    $htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
    foreach ($data->FD_PRIOR_INFORMATION_DEFENCE->OBJECT_WORKS_SUPPLIES_SERVICES_PRIOR_INFORMATION->QUANTITY_SCOPE_WORKS_DEFENCE->TOTAL_QUANTITY_OR_SCOPE->P as $shrtDesc) {
        $htmlData .="$shrtDesc<br/>";
    }
    $htmlData .='</td></tr>
	</table></body></html>';
    return $htmlData;
}

function prior_indicative_utilities($xml) {
    $htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>';
    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $town = $ml_ti->TI_TOWN;
            $htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>" . $ml_ti->TI_TEXT . "</td></tr>";
        }
    }
    //echo $htmlData;
    foreach ($xml->FORM_SECTION->PERIODIC_INDICATIVE_UTILITIES as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
        }
    }
    if (empty($data)) {
        $data = $xml->FORM_SECTION->PERIODIC_INDICATIVE_UTILITIES;
    }

    if (empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)) {
        $Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
        $newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
    } else {
        $newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION, 0, 8));
    }
    //echo "<pre>";
    //print_r($xml);
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">DOC_ID</td>
	<td>' . $xml['DOC_ID'] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DATE_PUB</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NO_OJ</td>
	<td>' . $xml->CODED_DATA_SECTION->REF_OJS->NO_OJ . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TOWN</td>
	<td>' . $town . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->PHONE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->FAX . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>' . $data["LG"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
         <td>';
    $country = $data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"];
    if ($country == 'UK') {
        $country = 'GB';
    }
    $htmlData .=$country . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AA_AUTHORITY_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DS_DATE_DISPATCH</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DT_DATE_FOR_SUBMISSION</td>
	<td>' . $newDate . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NC_CONTRACT_NATURE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PR_PROC</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->PR_PROC . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">RP_REGULATION</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TY_TYPE_BID</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TY_TYPE_BID . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AC_AWARD_CRIT</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AC_AWARD_CRIT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CPV_CODE</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->OBJECT_CONTRACT_PERIODIC_INDICATIVE->CPV->CPV_MAIN->CPV_CODE["CODE"];
    foreach ($data->FD_PERIODIC_INDICATIVE_UTILITIES->OBJECT_CONTRACT_PERIODIC_INDICATIVE->CPV->CPV_ADDITIONAL as $cpv_ad)
        $htmlData .= "," . $cpv_ad->CPV_CODE['CODE'];

    $htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>' . $data->FD_PERIODIC_INDICATIVE_UTILITIES->OBJECT_CONTRACT_PERIODIC_INDICATIVE->TYPE_CONTRACT_PLACE_DELIVERY_DEFENCE->SITE_OR_LOCATION->NUTS["CODE"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">IA_URL_GENERAL</td>
	<td>' . $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DIRECTIVE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'] . '</td>
	</tr><td style="font-weight: bold;">DESCRIPTION</td>
	<td>';
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $htmlData .= $ocpv . ".<br/>";
    }
    $htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
    foreach ($data->FD_PERIODIC_INDICATIVE_UTILITIES->OBJECT_CONTRACT_PERIODIC_INDICATIVE->DESCRIPTION_OF_CONTRACT->P as $shrtDesc) {
        $htmlData .="$shrtDesc<br/>";
    }
    $htmlData .='</td></tr>
	</table></body></html>';
    return $htmlData;
}

function prior_info_move($xml) {
    $htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>';
    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $town = $ml_ti->TI_TOWN;
            $htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>" . $ml_ti->TI_TEXT . "</td></tr>";
        }
    }
    //echo $htmlData;
    foreach ($xml->FORM_SECTION->PRIOR_INFORMATION_MOVE as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
        }
    }
    if (empty($data)) {
        $data = $xml->FORM_SECTION->PRIOR_INFORMATION_MOVE;
    }
    if (empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)) {
        $Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
        $newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
    } else {
        $newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION, 0, 8));
    }
    //echo "<pre>";
    //print_r($xml);
    $htmlData .='
	<tr>
	<td style="font-weight: bold;">DOC_ID</td>
	<td>' . $xml['DOC_ID'] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DATE_PUB</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NO_OJ</td>
	<td>' . $xml->CODED_DATA_SECTION->REF_OJS->NO_OJ . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TOWN</td>
	<td>' . $town . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>' . $data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>' . $data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>' . $data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>' . $data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>' . $data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>' . $data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->PHONE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>' . $data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>' . $data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->FAX . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>' . $data["LG"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
         <td>';
    $country = $data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"];
    if ($country == 'UK') {
        $country = 'GB';
    }
    $htmlData .=$country . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AA_AUTHORITY_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DS_DATE_DISPATCH</td>
	<td>' . YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH) . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DT_DATE_FOR_SUBMISSION</td>
	<td>' . $newDate . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NC_CONTRACT_NATURE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PR_PROC</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->PR_PROC . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">RP_REGULATION</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">TY_TYPE_BID</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->TY_TYPE_BID . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AC_AWARD_CRIT</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->AC_AWARD_CRIT . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CPV_CODE</td>
	<td>' . $data->FD_PRIOR_INFORMATION_MOVE->OBJECT_PI_MOVE->DESCRIPTION_PI_MOVE->CPV->CPV_MAIN->CPV_CODE["CODE"];
    foreach ($data->FD_PRIOR_INFORMATION_MOVE->OBJECT_PI_MOVE->DESCRIPTION_PI_MOVE->CPV->CPV_ADDITIONAL as $cpv_ad)
        $htmlData .= "," . $cpv_ad->CPV_CODE['CODE'];

    $htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>' . $data->FD_PRIOR_INFORMATION_MOVE->OBJECT_PI_MOVE->DESCRIPTION_PI_MOVE->LOCATION_NUTS->NUTS["CODE"] . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">IA_URL_GENERAL</td>
	<td>' . $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL . '</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DIRECTIVE</td>
	<td>' . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'] . '</td>
	</tr><td style="font-weight: bold;">DESCRIPTION</td>
	<td>';
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $htmlData .= $ocpv . ".<br/>";
    }
    $htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
    foreach ($data->FD_PRIOR_INFORMATION_MOVE->OBJECT_PI_MOVE->DESCRIPTION_PI_MOVE->SHORT_CONTRACT_DESCRIPTION->P as $shrtDesc) {
        $htmlData .="$shrtDesc<br/>";
    }
    $htmlData .='</td></tr>
	</table></body></html>';
    return $htmlData;
}


//CA
function contract_award($xml, $db2 = NULL, $db2 = NULL) {

    // echo '<pre>';
    // print_r($xml);exit;

    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $short_descp = str_replace("'", "\'", $ml_ti->TI_TEXT);

            // News
            $news_txt = str_replace("'", "\'", $ml_ti->TI_TEXT);
            $news_cy = str_replace("'", "\'", $ml_ti->TI_CY);
            $news_town = str_replace("'", "\'", $ml_ti->TI_TOWN);

            $news_head = "Contract awarded for " . $news_txt . ", " . $news_cy . "-" . $news_town;
        } else {

            $news_txt = str_replace("'", "\'", $ml_ti->TI_TEXT);
            $news_cy = str_replace("'", "\'", $ml_ti->TI_CY);
            $news_town = str_replace("'", "\'", $ml_ti->TI_TOWN);

            $news_mlti_doc .= $news_txt . " " . $news_cy . " " . $news_town . "<br/>";
        }
    }

    $FORM_SECTION = NULL;
    foreach ($xml->FORM_SECTION->CONTRACT_AWARD as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
            $FORM_SECTION = $temp;
            foreach ($temp->FD_CONTRACT_AWARD->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->ADDITIONAL_INFORMATION->P as $FD_additonalinfo) {
                $news_additonal_info.=$FD_additonalinfo . "<br/>";
            }
        } else {
            foreach ($temp->FD_CONTRACT_AWARD->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->ADDITIONAL_INFORMATION->P as $FD_additonalinfo) {
                $news_additonal_info.=$FD_additonalinfo . "<br/>";
            }
        }
    }

    if (empty($data)) {
        $data = $xml->FORM_SECTION->CONTRACT_AWARD;
        $FORM_SECTION = $data;
    }
    $ref_number = str_replace("'", "\'", $xml['DOC_ID']);
    $data1 = $data->FD_CONTRACT_AWARD->CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE;
    $purchasername = str_replace("'", "\'", $data1->ORGANISATION->OFFICIALNAME);

    $purchaseradd = str_replace("'", "\'", $data1->ADDRESS . "," . $data1->TOWN . " " . $data1->POSTAL_CODE . ",");

    if (!empty($data1->PHONE))
        $purchaseradd .=" Tel : " . $data1->PHONE . ",";

    if (!empty($data1->FAX))
        $purchaseradd .=" Fax : " . $data1->FAX;

    $purch_country = str_replace("'", "\'", $data1->COUNTRY["VALUE"]);

    if ($purch_country == 'UK') {
        $purch_country = 'GB';
    }
    $purch_email = str_replace("'", "\'", $data1->E_MAILS->E_MAIL);
    $purch_url = str_replace("'", "\'", $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL);
    $data2 = $data->FD_CONTRACT_AWARD->AWARD_OF_CONTRACT->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME;
    $contractorname = str_replace("'", "\'", $data2->ORGANISATION->OFFICIALNAME);
    $cont_add = str_replace("'", "\'", $data2->ADDRESS . "," . $data2->TOWN . " " . $data2->POSTAL_CODE);
    $cont_country = str_replace("'", "\'", $data2->COUNTRY["VALUE"]);

    if ($cont_country == 'UK') {
        $cont_country = 'GB';
    }
    $cont_email = '';
    $cont_url = '';
    $project_location = str_replace("'", "\'", $data1->COUNTRY["VALUE"]);
    if (empty($project_location)) {
        $project_location = $cont_country;
    }
    if ($project_location == 'UK') {
        $project_location = 'GB';
    }
    $award_detail = '';
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $award_detail .= str_replace("'", "\'", $ocpv . ",");
    }

    $contract_val = str_replace("'", "\'", $data->FD_CONTRACT_AWARD->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE->VALUE_COST);
    $contract_currency = str_replace("'", "\'", $data->FD_CONTRACT_AWARD->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE['CURRENCY']);
    $sector = '';
    $sector .= str_replace("'", "\'", $data->FD_CONTRACT_AWARD->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->DESCRIPTION_AWARD_NOTICE_INFORMATION->CPV->CPV_MAIN->CPV_CODE["CODE"]);

    if (!empty($sector)) {
        $seccode = getCpv($sector, $db2);
        //echo "secode".$seccode;
    }
    foreach ($data->FD_CONTRACT_AWARD->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->DESCRIPTION_AWARD_NOTICE_INFORMATION->CPV->CPV_ADDITIONAL as $cpv_ad)
        $sector .= "," . str_replace("'", "\'", $cpv_ad->CPV_CODE['CODE']);

    $cont_date = YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH);

    $contData = array(
        "short_descp" => "'$short_descp'",
        "ref_number" => "'$ref_number'",
        "purchasername" => "'$purchasername'",
        "purchaseradd" => "'$purchaseradd'",
        "purch_country" => "'$purch_country'",
        "purch_email" => "'$purch_email'",
        "purch_url" => "'$purch_url'",
        "contractorname" => "'$contractorname'",
        "cont_add" => "'$cont_add'",
        "cont_country" => "'$cont_country'",
        "cont_email" => "'$cont_email'",
        "cont_url" => "'$cont_url'",
        "project_location" => "'$project_location'",
        "award_detail" => "'$award_detail'",
        "contract_val" => "'$contract_val'",
        "contract_currency" => "'$contract_currency'",
        "sector" => "'$seccode'",
        "cont_date" => "'$cont_date'",
        "userid" => "0",
        "qc" => "1"
        );
    // echo "<pre>";
    //print_r($contData);
    $check = $db2->count('contract_award', "ref_number='$ref_number'");
    if ($check == 0) {
    //        $db->insert($contData, 'contract_award');
    //        $id = $db->getMax('contract_award', "id");
    //        $contData1 = array("id" => "$id");
    //        $contData1 = array_merge($contData1, $contData);
    //        $db2->insert($contData1, 'contract_award');
    //        
        $db2->insert($contData, 'contract_award');
    }

    // News

    $Directive =  $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'];

    $CODED_DATA_SECTION = $xml->CODED_DATA_SECTION;
    $LG_ORIG = (array)$CODED_DATA_SECTION->NOTICE_DATA->LG_ORIG;
    $CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD = $FORM_SECTION->FD_CONTRACT_AWARD->CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD;
    $PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE = $FORM_SECTION->FD_CONTRACT_AWARD->PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE;
    $OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE = $FORM_SECTION->FD_CONTRACT_AWARD->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE;
    $AWARD_OF_CONTRACT = $FORM_SECTION->FD_CONTRACT_AWARD->AWARD_OF_CONTRACT;
    $COMPLEMENTARY_INFORMATION_CONTRACT_AWARD = $FORM_SECTION->FD_CONTRACT_AWARD->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD;
    
    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $news_cur_city = $ml_ti->TI_CY;
            $news_cur_town = $ml_ti->TI_TOWN;
            $news_cur_text = $ml_ti->TI_TEXT;
        }

        if ($ml_ti['LG'] == $LG_ORIG[0]) {
            $news_org_city = $ml_ti->TI_CY;
            $news_org_town = $ml_ti->TI_TOWN;
            $news_org_text = $ml_ti->TI_TEXT;

        }
    }

    $CRITERIA_DEFINITION_VAL = '';
    foreach ($PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->AWARD_CRITERIA_CONTRACT_AWARD_NOTICE_INFORMATION->AWARD_CRITERIA_DETAIL_F03->MOST_ECONOMICALLY_ADVANTAGEOUS_TENDER_SHORT->CRITERIA_DEFINITION as $CRITERIA_DEFINITION) {
        $CRITERIA_DEFINITION_VAL .= "<p>".$CRITERIA_DEFINITION->ORDER_C.". ".$CRITERIA_DEFINITION->CRITERIA." Weighting ".$CRITERIA_DEFINITION->WEIGHTING."</p>";
    }

    $SHORT_CONTRACT_DESCRIPTION = '';
    foreach ($OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->DESCRIPTION_AWARD_NOTICE_INFORMATION->SHORT_CONTRACT_DESCRIPTION->P as $pVal) {
        $SHORT_CONTRACT_DESCRIPTION .= "<p>".$pVal."<p>";
    }

    $AWARD_OF_CONTRACT_VAL = '';
    foreach ($AWARD_OF_CONTRACT as $AWARD) {
        $AWARD_OF_CONTRACT_VAL .= "<p>Contract No: ".$AWARD->CONTRACT_NUMBER." Lot No: ".$AWARD->LOT_NUMBER." - Lot title: ".$AWARD->CONTRACT_TITLE->P."</p>";
        $AWARD_OF_CONTRACT_VAL .= "<div style='margin-left: 2em;'>";
        $AWARD_OF_CONTRACT_VAL .= "<p style='font-weight: bold;'>V.1) Date of contract award decision:</p>";
        $AWARD_OF_CONTRACT_VAL .= "<p style='margin-left: 1em;'>".$AWARD->CONTRACT_AWARD_DATE->DAY.".".$AWARD->CONTRACT_AWARD_DATE->MONTH.".".$AWARD->CONTRACT_AWARD_DATE->YEAR."</p>";
        
        $AWARD_OF_CONTRACT_VAL .= "<p style='font-weight: bold;'>V.2) Information about offers:</p>";
        $AWARD_OF_CONTRACT_VAL .= "<p style='margin-left: 1em;'>Number of offers received:".$AWARD->OFFERS_RECEIVED_NUMBER."</p>";
        
        $AWARD_OF_CONTRACT_VAL .= "<p style='font-weight: bold;'>V.3) Name and address of economic operator in favour of whom the contract award decision has been taken</p>";
        $AWARD_OF_CONTRACT_VAL .= "<p style='margin-left: 1em;'>".$AWARD->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ORGANISATION->OFFICIALNAME."</p>";
        $AWARD_OF_CONTRACT_VAL .= "<p style='margin-left: 1em;'>".$AWARD->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ADDRESS."</p>";
        $AWARD_OF_CONTRACT_VAL .= "<p style='margin-left: 1em;'>".$AWARD->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->POSTAL_CODE." ".$AWARD->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->TOWN."</p>";
        $AWARD_OF_CONTRACT_VAL .= "<p style='margin-left: 1em;'>".getCountryName($AWARD->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->COUNTRY['VALUE'])."</p>";
        $AWARD_OF_CONTRACT_VAL .= "<p style='margin-left: 1em;'>E-mail: ".$AWARD->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->E_MAILS->E_MAIL."</p>";

        $AWARD_OF_CONTRACT_VAL .= "<p style='font-weight: bold;'>V.4) Information on value of contract</p>";
        $AWARD_OF_CONTRACT_VAL .= "<p style='font-weight: bold;'>V.5) Information about subcontracting</p>";
        
        $AWARD_OF_CONTRACT_VAL .= "</div>";
    }
    $ADDITIONAL_INFORMATION_ARR = '';
    foreach ($COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->ADDITIONAL_INFORMATION->P as $ADDITIONAL_INFORMATION) {
        $ADDITIONAL_INFORMATION_ARR .= "<span style='  line-height: 1.2;'>$ADDITIONAL_INFORMATION</span><br/>";
    }

    $ORIGINAL_CPV = '';
    foreach ($CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ORIGINAL_CPV_VAL) {
        $ORIGINAL_CPV .= '<p>'.$ORIGINAL_CPV_VAL.'</p>';
    }

    $newsDetails_cur_head = "
        <h3 align='center'>
            <p>$news_cur_city-$news_cur_town : $news_cur_text</p>
            <p>".$CODED_DATA_SECTION->NOTICE_DATA->NO_DOC_OJS."</p>
            <p>".$CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE." - ".$CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE."</p>
        </h3>";

    $newsDetails_org_head = "
        <h3 align='center'>
            <p>$news_org_city-$news_org_town : $news_org_text</p>
            <p>".$CODED_DATA_SECTION->NOTICE_DATA->NO_DOC_OJS."</p>
            <p>".$CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE." - ".$CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE."</p>
        </h3>";


    $newsDetails_cur = "
        <div style='line-height:0.7;border:solid 2px;padding:0.5em;margin:0 0 1em 0;'>
        $newsDetails_cur_head
        <span> Directive : $Directive</span>
            <p style='font-weight: bold;text-decoration: underline'>Section I: Contracting entity</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>I.1) Name, addresses and contact point(s)</p>
                <div style='margin-left: 2em;'>
                    <p>".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME."</p>
                    <p>".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS."</p>
                    <p>For the attention of: ".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION."</p>
                    <p>Contact point(s): ".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT."</p>
                    <p>".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE." ".$news_cur_town."</p>
                    <p>$news_cur_city</p>
                    <p>Telephone : ".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE->PHONE."</p>
                    <p>E-mail: ".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL."</p>
                    <p>Fax : ".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE->FAX."</p>
                    <p style='font-weight: bold;'>Internet address(es):<p>
                    <p>General address of the contracting entity: <a target='_blank' href='http://".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->INTERNET_ADDRESSES_CONTRACT_AWARD->URL_GENERAL."'>".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->INTERNET_ADDRESSES_CONTRACT_AWARD->URL_GENERAL."</a></p>
                </div>
                <p style='font-weight: bold;'>I.2) Type of the contracting authority</p>
                <p>".$CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION."</p>
                <p style='font-weight: bold;'>I.3) Main activity</p>
                <p>".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->TYPE_AND_ACTIVITIES_AND_PURCHASING_ON_BEHALF->TYPE_AND_ACTIVITIES->TYPE_OF_ACTIVITY_OTHER."</p>
                <p style='font-weight: bold;'>I.4) Contract award on behalf of other contracting entities</p>
                <p>The contracting authority is purchasing on behalf of other contracting authorities: ".$PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->AWARD_CRITERIA_CONTRACT_AWARD_NOTICE_INFORMATION->F03_IS_ELECTRONIC_AUCTION_USABLE['VALUE']."</p>
            </div>

            <p style='font-weight: bold;text-decoration: underline'>Section II: Object of the contract</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>II.1) Description</p>
                <p style='font-weight: bold;'>II.1.1) Title attributed to the contract</p>
                <p>".$OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->DESCRIPTION_AWARD_NOTICE_INFORMATION->TITLE_CONTRACT->P."</p>
                <p style='font-weight: bold;'>II.1.2) Type of contract and location of works, place of delivery or of performance</p>
                <p>".$OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->DESCRIPTION_AWARD_NOTICE_INFORMATION->TYPE_CONTRACT_LOCATION_W_PUB->TYPE_CONTRACT['VALUE']."</p>
                <p>Main site or location of works, place of delivery or of performance: ".$OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->DESCRIPTION_AWARD_NOTICE_INFORMATION->LOCATION_NUTS->LOCATION->P."</p>
                <p style='font-weight: bold;'>II.1.3) Information about a framework agreement or a dynamic purchasing system (DPS)</p>
                <p style='font-weight: bold;'>II.1.4) Short description of the contract or purchase(s):</p>
                <p>".$SHORT_CONTRACT_DESCRIPTION."</p>
                <p style='font-weight: bold;'>II.1.5) Common procurement vocabulary (CPV)</p>
                <p>$sector</p>
                <p style='font-weight: bold;'>Description</p>
                <p>$ORIGINAL_CPV</p>
                <p style='font-weight: bold;'>II.1.6) Information about Government Procurement Agreement (GPA)</p>
                <p>The contract is covered by the Government Procurement Agreement (GPA): ".$OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->DESCRIPTION_AWARD_NOTICE_INFORMATION->CONTRACT_COVERED_GPA['VALUE']."</p>
                <p style='font-weight: bold;'>II.2) Total final value of contract(s)</p>
                <p style='font-weight: bold;'>II.2.1) Total final value of contract(s)</p>
                <p>Value : ".$OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE->VALUE_COST." ".$OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE['CURRENCY']."</p>
            </div>

            <p style='font-weight: bold;text-decoration: underline'>Section IV: Procedure</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>IV.1) Type of procedure</p>
                <p style='font-weight: bold;'>IV.1.1) Type of procedure</p>
                <p>Open<p>
                <p style='font-weight: bold;'>IV.2) Award criteria</p>
                <p style='font-weight: bold;'>IV.2.1) Award criteria</p>
                <p>The most economically advantageous tender in terms of</p>
                <p>".$CRITERIA_DEFINITION_VAL."</p>
                <p style='font-weight: bold;'>IV.2.2) Information about electronic auction</p>
                <p>An electronic auction has been used : ".$PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->AWARD_CRITERIA_CONTRACT_AWARD_NOTICE_INFORMATION->F03_IS_ELECTRONIC_AUCTION_USABLE['VALUE']."</p>
                <p style='font-weight: bold;'>IV.3) Administrative information</p>
                <p style='font-weight: bold;'>IV.3.1) File reference number attributed by the contracting entity:</p>
                <p>".$PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_AWARD->FILE_REFERENCE_NUMBER->P."</p>
                <p style='font-weight: bold;'>IV.3.2) Previous publication(s) concerning the same contract</p>
                <p>Notice number in the OJEU: ".$PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_AWARD->PREVIOUS_PUBLICATION_INFORMATION_NOTICE_F3->PREVIOUS_PUBLICATION_EXISTS_F3->CNT_NOTICE_INFORMATION->NOTICE_NUMBER_OJ." of ".$PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_AWARD->PREVIOUS_PUBLICATION_INFORMATION_NOTICE_F3->PREVIOUS_PUBLICATION_EXISTS_F3->CNT_NOTICE_INFORMATION->DATE_OJ->DAY.".".$PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_AWARD->PREVIOUS_PUBLICATION_INFORMATION_NOTICE_F3->PREVIOUS_PUBLICATION_EXISTS_F3->CNT_NOTICE_INFORMATION->DATE_OJ->MONTH.".".$PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_AWARD->PREVIOUS_PUBLICATION_INFORMATION_NOTICE_F3->PREVIOUS_PUBLICATION_EXISTS_F3->CNT_NOTICE_INFORMATION->DATE_OJ->YEAR."</p>
            </div>

            <p style='font-weight: bold;text-decoration: underline'>Section V: Award of contract</p>
            <div style='margin-left: 1em;'>
                ".$AWARD_OF_CONTRACT_VAL."
            </div>

            <p style='font-weight: bold;text-decoration: underline'>Section VI: Complementary information</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>VI.1) Information about European Union funds</p>
                <p style='font-weight: bold;'>VI.2) Additional information:</p>
                <p>".$ADDITIONAL_INFORMATION_ARR."</p>
                <p style='font-weight: bold;'>VI.3) Procedures for appeal</p>
                <p style='font-weight: bold;'>VI.3.1) Body responsible for appeal procedures</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ORGANISATION->OFFICIALNAME."</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ADDRESS."</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->POSTAL_CODE." ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->TOWN."</p>
                <p style='margin-left: 3em;'>".getCountryName($COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->COUNTRY['VALUE'])."</p>
                <p style='margin-left: 3em;'>E-mail: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->E_MAILS->E_MAIL."</p>
                <p style='margin-left: 3em;'>Telephone: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->PHONE."</p>
                <p style='margin-left: 3em;'>Internet address : ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->URL."</p>
                <p style='margin-left: 3em;'>Fax: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->FAX."</p>
                <p style='margin-left: 3em;font-weight: bold;'>Body responsible for mediation procedures</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ORGANISATION->OFFICIALNAME."</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ADDRESS."</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->POSTAL_CODE." ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->TOWN."</p>
                <p style='margin-left: 3em;'>".getCountryName($COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->COUNTRY['VALUE'])."</p>
                <p style='margin-left: 3em;'>E-mail: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->E_MAILS->E_MAIL."</p>
                <p style='margin-left: 3em;'>Telephone: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->PHONE."</p>
                <p style='margin-left: 3em;'>Internet address : ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->URL."</p>
                <p style='margin-left: 3em;'>Fax: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->FAX."</p>
                <p style='font-weight: bold;'>VI.3.2) Lodging of appeals</p>
                <p style='font-weight: bold;'>VI.3.3) Service from which information about the lodging of appeals may be obtained</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ORGANISATION->OFFICIALNAME."</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ADDRESS."</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->POSTAL_CODE." ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->TOWN."</p>
                <p style='margin-left: 3em;'>".getCountryName($COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->COUNTRY['VALUE'])."</p>
                <p style='margin-left: 3em;'>E-mail: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->E_MAILS->E_MAIL."</p>
                <p style='margin-left: 3em;'>Telephone: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->PHONE."</p>
                <p style='margin-left: 3em;'>Internet address : ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->URL."</p>
                <p style='margin-left: 3em;'>Fax: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->FAX."</p>
                <p style='font-weight: bold;'>VI.3.4) Date of dispatch of this notice:</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->NOTICE_DISPATCH_DATE->DAY.".".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->NOTICE_DISPATCH_DATE->MONTH.".".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->NOTICE_DISPATCH_DATE->YEAR."</p>
            </div>
        </div>";

    $newsDetails_org = "
        <div style='line-height:0.7;border:solid 2px;padding:0.5em;margin:0 0 1em 0;'>
        $newsDetails_org_head
        <span> Directive : $Directive</span>
            <p style='font-weight: bold;text-decoration: underline'>Section I: Contracting entity</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>I.1) Name, addresses and contact point(s)</p>
                <div style='margin-left: 2em;'>
                    <p>".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME."</p>
                    <p>".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS."</p>
                    <p>For the attention of: ".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION."</p>
                    <p>Contact point(s): ".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT."</p>
                    <p>".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE." ".$news_cur_town."</p>
                    <p>$news_cur_city</p>
                    <p>Telephone : ".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE->PHONE."</p>
                    <p>E-mail: ".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL."</p>
                    <p>Fax : ".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE->FAX."</p>
                    <p style='font-weight: bold;'>Internet address(es):<p>
                    <p>General address of the contracting entity: <a target='_blank' href='http://".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->INTERNET_ADDRESSES_CONTRACT_AWARD->URL_GENERAL."'>".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->INTERNET_ADDRESSES_CONTRACT_AWARD->URL_GENERAL."</a></p>
                </div>
                <p style='font-weight: bold;'>I.2) Type of the contracting authority</p>
                <p>".$CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION."</p>
                <p style='font-weight: bold;'>I.3) Main activity</p>
                <p>".$CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->TYPE_AND_ACTIVITIES_AND_PURCHASING_ON_BEHALF->TYPE_AND_ACTIVITIES->TYPE_OF_ACTIVITY_OTHER."</p>
                <p style='font-weight: bold;'>I.4) Contract award on behalf of other contracting entities</p>
                <p>The contracting authority is purchasing on behalf of other contracting authorities: ".$PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->AWARD_CRITERIA_CONTRACT_AWARD_NOTICE_INFORMATION->F03_IS_ELECTRONIC_AUCTION_USABLE['VALUE']."</p>
            </div>

            <p style='font-weight: bold;text-decoration: underline'>Section II: Object of the contract</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>II.1) Description</p>
                <p style='font-weight: bold;'>II.1.1) Title attributed to the contract</p>
                <p>".$OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->DESCRIPTION_AWARD_NOTICE_INFORMATION->TITLE_CONTRACT->P."</p>
                <p style='font-weight: bold;'>II.1.2) Type of contract and location of works, place of delivery or of performance</p>
                <p>".$OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->DESCRIPTION_AWARD_NOTICE_INFORMATION->TYPE_CONTRACT_LOCATION_W_PUB->TYPE_CONTRACT['VALUE']."</p>
                <p>Main site or location of works, place of delivery or of performance: ".$OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->DESCRIPTION_AWARD_NOTICE_INFORMATION->LOCATION_NUTS->LOCATION->P."</p>
                <p style='font-weight: bold;'>II.1.3) Information about a framework agreement or a dynamic purchasing system (DPS)</p>
                <p style='font-weight: bold;'>II.1.4) Short description of the contract or purchase(s):</p>
                <p>".$SHORT_CONTRACT_DESCRIPTION."</p>
                <p style='font-weight: bold;'>II.1.5) Common procurement vocabulary (CPV)</p>
                <p>$sector</p>
                <p style='font-weight: bold;'>Description</p>
                <p>$ORIGINAL_CPV</p>
                <p style='font-weight: bold;'>II.1.6) Information about Government Procurement Agreement (GPA)</p>
                <p>The contract is covered by the Government Procurement Agreement (GPA): ".$OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->DESCRIPTION_AWARD_NOTICE_INFORMATION->CONTRACT_COVERED_GPA['VALUE']."</p>
                <p style='font-weight: bold;'>II.2) Total final value of contract(s)</p>
                <p style='font-weight: bold;'>II.2.1) Total final value of contract(s)</p>
                <p>Value : ".$OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE->VALUE_COST." ".$OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE['CURRENCY']."</p>
            </div>

            <p style='font-weight: bold;text-decoration: underline'>Section IV: Procedure</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>IV.1) Type of procedure</p>
                <p style='font-weight: bold;'>IV.1.1) Type of procedure</p>
                <p>Open<p>
                <p style='font-weight: bold;'>IV.2) Award criteria</p>
                <p style='font-weight: bold;'>IV.2.1) Award criteria</p>
                <p>The most economically advantageous tender in terms of</p>
                <p>".$CRITERIA_DEFINITION_VAL."</p>
                <p style='font-weight: bold;'>IV.2.2) Information about electronic auction</p>
                <p>An electronic auction has been used : ".$PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->AWARD_CRITERIA_CONTRACT_AWARD_NOTICE_INFORMATION->F03_IS_ELECTRONIC_AUCTION_USABLE['VALUE']."</p>
                <p style='font-weight: bold;'>IV.3) Administrative information</p>
                <p style='font-weight: bold;'>IV.3.1) File reference number attributed by the contracting entity:</p>
                <p>".$PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_AWARD->FILE_REFERENCE_NUMBER->P."</p>
                <p style='font-weight: bold;'>IV.3.2) Previous publication(s) concerning the same contract</p>
                <p>Notice number in the OJEU: ".$PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_AWARD->PREVIOUS_PUBLICATION_INFORMATION_NOTICE_F3->PREVIOUS_PUBLICATION_EXISTS_F3->CNT_NOTICE_INFORMATION->NOTICE_NUMBER_OJ." of ".$PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_AWARD->PREVIOUS_PUBLICATION_INFORMATION_NOTICE_F3->PREVIOUS_PUBLICATION_EXISTS_F3->CNT_NOTICE_INFORMATION->DATE_OJ->DAY.".".$PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_AWARD->PREVIOUS_PUBLICATION_INFORMATION_NOTICE_F3->PREVIOUS_PUBLICATION_EXISTS_F3->CNT_NOTICE_INFORMATION->DATE_OJ->MONTH.".".$PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_AWARD->PREVIOUS_PUBLICATION_INFORMATION_NOTICE_F3->PREVIOUS_PUBLICATION_EXISTS_F3->CNT_NOTICE_INFORMATION->DATE_OJ->YEAR."</p>
            </div>

            <p style='font-weight: bold;text-decoration: underline'>Section V: Award of contract</p>
            <div style='margin-left: 1em;'>
                ".$AWARD_OF_CONTRACT_VAL."
            </div>

            <p style='font-weight: bold;text-decoration: underline'>Section VI: Complementary information</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>VI.1) Information about European Union funds</p>
                <p style='font-weight: bold;'>VI.2) Additional information:</p>
                <p>".$ADDITIONAL_INFORMATION_ARR."</p>
                <p style='font-weight: bold;'>VI.3) Procedures for appeal</p>
                <p style='font-weight: bold;'>VI.3.1) Body responsible for appeal procedures</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ORGANISATION->OFFICIALNAME."</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ADDRESS."</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->POSTAL_CODE." ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->TOWN."</p>
                <p style='margin-left: 3em;'>".getCountryName($COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->COUNTRY['VALUE'])."</p>
                <p style='margin-left: 3em;'>E-mail: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->E_MAILS->E_MAIL."</p>
                <p style='margin-left: 3em;'>Telephone: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->PHONE."</p>
                <p style='margin-left: 3em;'>Internet address : ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->URL."</p>
                <p style='margin-left: 3em;'>Fax: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->FAX."</p>
                <p style='margin-left: 3em;font-weight: bold;'>Body responsible for mediation procedures</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ORGANISATION->OFFICIALNAME."</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ADDRESS."</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->POSTAL_CODE." ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->TOWN."</p>
                <p style='margin-left: 3em;'>".getCountryName($COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->COUNTRY['VALUE'])."</p>
                <p style='margin-left: 3em;'>E-mail: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->E_MAILS->E_MAIL."</p>
                <p style='margin-left: 3em;'>Telephone: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->PHONE."</p>
                <p style='margin-left: 3em;'>Internet address : ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->URL."</p>
                <p style='margin-left: 3em;'>Fax: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->MEDIATION_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->FAX."</p>
                <p style='font-weight: bold;'>VI.3.2) Lodging of appeals</p>
                <p style='font-weight: bold;'>VI.3.3) Service from which information about the lodging of appeals may be obtained</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ORGANISATION->OFFICIALNAME."</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ADDRESS."</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->POSTAL_CODE." ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->TOWN."</p>
                <p style='margin-left: 3em;'>".getCountryName($COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->COUNTRY['VALUE'])."</p>
                <p style='margin-left: 3em;'>E-mail: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->E_MAILS->E_MAIL."</p>
                <p style='margin-left: 3em;'>Telephone: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->PHONE."</p>
                <p style='margin-left: 3em;'>Internet address : ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->URL."</p>
                <p style='margin-left: 3em;'>Fax: ".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->LODGING_INFORMATION_FOR_SERVICE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->FAX."</p>
                <p style='font-weight: bold;'>VI.3.4) Date of dispatch of this notice:</p>
                <p style='margin-left: 3em;'>".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->NOTICE_DISPATCH_DATE->DAY.".".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->NOTICE_DISPATCH_DATE->MONTH.".".$COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->NOTICE_DISPATCH_DATE->YEAR."</p>
            </div>
        </div>";


    $news_details_main = "
    <div>
        $newsDetails_cur
        $newsDetails_org
    </div>";

    $news_details_main = trim($news_details_main);
    echo $news_details_main;
    echo '<hr/>';exit;


    $news_reception_id = "<b>Notice Reception Id: </b>" . str_replace("'", "\'", $xml->TECHNICAL_SECTION->RECEPTION_ID);

    $deletion_date = YmdChnage($xml->TECHNICAL_SECTION->DELETION_DATE);

    $news_purchaser_details = "<b>Purchaser Details: </b>" . $purchasername . ", " . $purch_country . ", " . $purch_email . ", " . $purch_url;

    $news_contractor_details = "<b>Contractor Details: </b>" . $contractorname . ", " . $cont_add . ", " . $cont_country . ", " . $cont_email . ", " . $cont_url;

    $news_DELETION_DATE = "<b>Notice Deletion Date: </b>" . $deletion_date;
    $news_lang_avl = "<b>Available Language: </b>" . str_replace("'", "\'", $xml->TECHNICAL_SECTION->FORM_LG_LIST);


    $news_contract_type = "<b>Contract Type: </b>" . str_replace("'", "\'", $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE);
    $news_ref_num = "<b>Ref Num: </b>" . $ref_number;


    $news_directive_value = "<b>Directive Value: </b>" . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'];
    $news_cpv = "<b>CPV: </b>" . $sector;
    // Notice DATA
    $news_uri_doc_url = "";
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA as $noticedata_arr) {
        $news_NO_DOC_OJS = $noticedata_arr->NO_DOC_OJS;
        foreach ($noticedata_arr->URI_LIST->URI_DOC as $urival) {
            $news_uri_doc_url .= $urival . "<br/>";
        }
        $news_orglang = $noticedata_arr->LG_ORIG;
        $news_isocountry = $noticedata_arr->ISO_COUNTRY['VALUE'];
        $news_originalcpv = $noticedata_arr->ORIGINAL_CPV;
        $news_notice_value = $noticedata_arr->VALUES_LIST->VALUES->SINGLE_VALUE->VALUE;

        $news_notice_data = "<b>Notice Data</b>: " . $news_NO_DOC_OJS . ", " . $news_uri_doc_url . ", " . $news_orglang . ", " . $news_isocountry . ", " . $news_originalcpv . ", " . $news_notice_value;

        $news_notice_data = str_replace("'", "\'", $news_notice_data);
    }

    // CodIf Data

    foreach ($xml->CODED_DATA_SECTION->CODIF_DATA as $codifdata_arr) {

        $news_datedispatch = $codifdata_arr->DS_DATE_DISPATCH;
        $news_AA_AUTHORITY_TYPE = $codifdata_arr->AA_AUTHORITY_TYPE;
        $news_pr_proc = $codifdata_arr->PR_PROC;
        $news_rpregulation = $codifdata_arr->RP_REGULATION;
        $news_typebid = $codifdata_arr->TY_TYPE_BID;
        $news_acawardcrit = $codifdata_arr->AC_AWARD_CRIT;
        $news_mamaninactivity = $codifdata_arr->MA_MAIN_ACTIVITIES;
        $news_codifheading = $codifdata_arr->HEADING;

        $news_codifdata = "<b>CodIf Data</b>: " . $news_datedispatch . ", " . $news_AA_AUTHORITY_TYPE . ", " . $news_pr_proc . ", " .
        $news_rpregulation . ", " . $news_typebid . ", " . $news_acawardcrit . ", " . $news_mamaninactivity . ", " . $news_codifheading;
        $news_codifdata = str_replace("'", "\'", $news_codifdata);
    }

    // FormSection
    $i = 1;
    foreach ($xml->FORM_SECTION->CONTRACT_AWARD as $compinfcontractarr) {



        foreach ($compinfcontractarr->FD_CONTRACT_AWARD->CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE as $concessionval) {
            $news_officialname = $concessionval->ORGANISATION->OFFICIALNAME;
            $news_address = $concessionval->ADDRESS;
            $news_town = $concessionval->TOWN;
            $news_postalcode = $concessionval->POSTAL_CODE;
            $news_country_value = $concessionval->COUNTRY['VALUE'];
            $news_attention = $concessionval->ATTENTION;
            $news_email = $concessionval->E_MAILS->E_MAIL;
            //echo "<br/>_________________<br/>";

            $news_form_values = $news_officialname . ", " . $news_address . ", " . $news_town . ", " . $news_postalcode . ", " . $news_country_value . ", " . $news_attention . ", " . $news_email;
        }

        $news_internetadd = $compinfcontractarr->FD_CONTRACT_AWARD->CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->INTERNET_ADDRESSES_CONTRACT_AWARD->URL_GENERAL;
        $news_form_values = $news_form_values . ", " . $news_internetadd;
        //$news_form_values = str_replace("'", "\'", $news_form_values);
        //echo $news_form_values."<br/>";

        foreach ($compinfcontractarr->FD_CONTRACT_AWARD->CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->TYPE_AND_ACTIVITIES_AND_PURCHASING_ON_BEHALF as $typeactivity) {
            $contractauth = $typeactivity->TYPE_AND_ACTIVITIES->TYPE_OF_CONTRACTING_AUTHORITY['VALUE'];
            $contractactivity = $typeactivity->TYPE_AND_ACTIVITIES->TYPE_OF_ACTIVITY['VALUE'];
            $contracttypeofactivty = $typeactivity->TYPE_AND_ACTIVITIES->TYPE_OF_ACTIVITY_OTHER;

            foreach ($typeactivity->PURCHASING_ON_BEHALF->PURCHASING_ON_BEHALF_YES->CONTACT_DATA_OTHER_BEHALF_CONTRACTING_AUTORITHY as $purchaseval) {
                $purbehalfofcname = $purchaseval->ORGANISATION->OFFICIALNAME;
                $purbehalfaddress = $purchaseval->ADDRESS;
                $purbehalftown = $purchaseval->TOWN;
                $purbehalfpostalcode = $purchaseval->POSTAL_CODE;
                $purbehalfcountry = $purchaseval->COUNTRY['VALUE'];
            }
        }




        foreach ($compinfcontractarr->FD_CONTRACT_AWARD->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->DESCRIPTION_AWARD_NOTICE_INFORMATION as $awardNoticeInfo) {
            $news_titlecontract = $awardNoticeInfo->TITLE_CONTRACT->P;
            $news_typecontractlocation = $awardNoticeInfo->TYPE_CONTRACT_LOCATION_W_PUB->TYPE_CONTRACT['VALUE'];
            $news_categorypub = $awardNoticeInfo->TYPE_CONTRACT_LOCATION_W_PUB->SERVICE_CATEGORY_PUB;
            $news_shortcontractdescp = $awardNoticeInfo->SHORT_CONTRACT_DESCRIPTION->P;

            $news_descriptioninfo = $news_titlecontract . ", " . $news_typecontractlocation . ", " . $news_categorypub . ", " . $news_shortcontractdescp;
        }


        //$news_descriptioninfo = str_replace("'", "\'", $news_descriptioninfo);

        $news_valuecost = $compinfcontractarr->FD_CONTRACT_AWARD->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE->VALUE_COST;

        $news_full_criteria = "";

        foreach ($compinfcontractarr->FD_CONTRACT_AWARD->PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->AWARD_CRITERIA_CONTRACT_AWARD_NOTICE_INFORMATION->AWARD_CRITERIA_DETAIL_F03->MOST_ECONOMICALLY_ADVANTAGEOUS_TENDER_SHORT as $CriteraDef) {

            foreach ($CriteraDef->CRITERIA_DEFINITION as $cdef) {

                $news_full_criteria .= $cdef->ORDER_C . " " . $cdef->CRITERIA . " " . $cdef->WEIGHTING . "<br/>";
            }
        }

        $news_filerefnum = $compinfcontractarr->FD_CONTRACT_AWARD->PROCEDURE_DEFINITION_CONTRACT_AWARD_NOTICE->ADMINISTRATIVE_INFORMATION_CONTRACT_AWARD->FILE_REFERENCE_NUMBER->P;

        $news_contractnumber = $compinfcontractarr->FD_CONTRACT_AWARD->AWARD_OF_CONTRACT->CONTRACT_NUMBER;

        $news_contract_awardofficalnum = $compinfcontractarr->FD_CONTRACT_AWARD->AWARD_OF_CONTRACT->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ORGANISATION->OFFICIALNAME;

        $news_contract_address = $compinfcontractarr->FD_CONTRACT_AWARD->AWARD_OF_CONTRACT->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ADDRESS;

        $news_contract_town = $compinfcontractarr->FD_CONTRACT_AWARD->AWARD_OF_CONTRACT->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->TOWN;

        $news_contract_prject = $compinfcontractarr->FD_CONTRACT_AWARD->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->RELATES_TO_EU_PROJECT_YES->P;

        $news_contract_prject_oficialname = $compinfcontractarr->FD_CONTRACT_AWARD->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ORGANISATION->OFFICIALNAME;

        $news_contract_prject_oficialname = $compinfcontractarr->FD_CONTRACT_AWARD->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->TOWN;



        $news_form_values_complete .= $news_form_values . ", " . $contractauth . ", " . $contractactivity . ", " . $contracttypeofactivty . ", " .
        $purbehalfofcname . ", " . $purbehalfaddress . ", " . $purbehalftown . ", " . $purbehalfpostalcode . ", " . $purbehalfcountry . ", " .
        $news_descriptioninfo . ", " . $news_valuecost . ", " . $news_full_criteria . ", " . $news_filerefnum . ", " . $news_filerefnum . ", " .
        $news_contractnumber . ", " . $news_contract_awardofficalnum . ", " . $news_contract_address . ", " . $news_contract_town . ", " . $news_contract_prject . ", " . $news_contract_prject_oficialname . ", " . $news_contract_prject_oficialname;
    }

    $news_form_values_complete = str_replace("'", "\'", $news_form_values_complete);


    $news_additonal_info = str_replace("'", "\'", $news_additonal_info);
    $news_additioal_info = "<b>Additional Information: </b>" . $news_additonal_info . "<br/>" . $news_mlti_doc;

    $news_details = $award_detail . "<br/>" . $news_purchaser_details . "<br/>" . $news_contractor_details . "<br/>" .
    $news_reception_id . "<br/>" . $news_DELETION_DATE . "<br/>" . $news_lang_avl . "<br/>" . $news_contract_type . "<br/>" .
    $news_directive_value . "<br/>" . $news_cpv . "<br/>" . $news_ref_num . "<br/>" . $news_notice_data . "<br/>" .
    $news_codifdata . "<br/>" . $news_additioal_info . "<br/>" . $news_form_values_complete;

    $source_url = $xml->CODED_DATA_SECTION->NOTICE_DATA->URI_LIST->URI_DOC;

    $NewsData_Arr = array(
        "headline" => "'$news_head'",
        "details" => "'$news_details'",
        "source" => "'1068'",
        "source_url" => "'$source_url'",
        "sector" => "'$seccode'",
        "ca_news" => "'0'",
        "p_news" => "'1'",
        "user_id" => "0",
        "qc" => "'1'"
        );

    $checknews = $db2->count('news', "headline='$news_head'");
    if ($checknews == 0) {

        $db2->insert($NewsData_Arr, 'news');
    }
    return "DONE";
}


//CA
function contract_award_utili($xml, $db2 = NULL, $db2 = NULL) {
    // echo '<pre>';
    // print_r($xml);exit;
    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $short_descp = str_replace("'", "\'", $ml_ti->TI_TEXT);

            // News

            $news_txt = str_replace("'", "\'", $ml_ti->TI_TEXT);
            $news_cy = str_replace("'", "\'", $ml_ti->TI_CY);
            $news_town = str_replace("'", "\'", $ml_ti->TI_TOWN);

            $news_head = "Contract awarded for " . $news_txt . ", " . $news_cy . "-" . $news_town;
        } else {

            $news_txt = str_replace("'", "\'", $ml_ti->TI_TEXT);
            $news_cy = str_replace("'", "\'", $ml_ti->TI_CY);
            $news_town = str_replace("'", "\'", $ml_ti->TI_TOWN);

            $news_mlti_doc .= $news_txt . " " . $news_cy . " " . $news_town . "<br/>";
        }
    }

    foreach ($xml->FORM_SECTION->CONTRACT_AWARD_UTILITIES as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
        } else {
            foreach ($temp->FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->ADDITIONAL_INFORMATION->P as $FD_additonalinfo) {
                $news_additonal_info.=$FD_additonalinfo . "<br/>";
            }
        }
    }
    if (empty($data)) {
        $data = $xml->FORM_SECTION->CONTRACT_AWARD_UTILITIES;
    }
    $ref_number = str_replace("'", "\'", $xml['DOC_ID']);
    $data1 = $data->FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE;
    $purchasername = str_replace("'", "\'", $data1->ORGANISATION->OFFICIALNAME);
    $purchaseradd = str_replace("'", "\'", $data1->ADDRESS . "," . $data1->TOWN . " " . $data1->POSTAL_CODE . ",");

    if (!empty($data1->PHONE))
        $purchaseradd .=" Tel : " . $data1->PHONE . ",";

    if (!empty($data1->FAX))
        $purchaseradd .=" Fax : " . $data1->FAX;

    $purch_country = str_replace("'", "\'", $data1->COUNTRY["VALUE"]);

    if ($purch_country == 'UK') {
        $purch_country = 'GB';
    }
    $purch_email = str_replace("'", "\'", $data1->E_MAILS->E_MAIL);
    $purch_url = str_replace("'", "\'", $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL);
    $data2 = $data->FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME_CHP;
    $contractorname = str_replace("'", "\'", $data2->ORGANISATION->OFFICIALNAME);
    $cont_add = str_replace("'", "\'", $data2->ADDRESS . "," . $data2->TOWN . " " . $data2->POSTAL_CODE);
    $cont_country = str_replace("'", "\'", $data2->COUNTRY["VALUE"]);

    if ($cont_country == 'UK') {
        $cont_country = 'GB';
    }

    $cont_email = '';
    $cont_url = '';
    $project_location = str_replace("'", "\'", $data1->COUNTRY["VALUE"]);

    if (empty($project_location)) {
        $project_location = $cont_country;
    }
    if ($project_location == 'UK') {
        $project_location = 'GB';
    }

    $award_detail = '';
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $award_detail .= str_replace("'", "\'", $ocpv . ",");
    }

    $contract_val = str_replace("'", "\'", $data->FD_CONTRACT_AWARD_UTILITIES->OBJECT_CONTRACT_AWARD_UTILITIES->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE->VALUE_COST);
    $contract_currency = str_replace("'", "\'", $data->FD_CONTRACT_AWARD_UTILITIES->OBJECT_CONTRACT_AWARD_UTILITIES->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE['CURRENCY']);
    $sector = '';
    $sector .= str_replace("'", "\'", $data->FD_CONTRACT_AWARD_UTILITIES->OBJECT_CONTRACT_AWARD_UTILITIES->DESCRIPTION_CONTRACT_AWARD_UTILITIES->CPV->CPV_MAIN->CPV_CODE["CODE"]);

    if (!empty($sector)) {
        $seccode = getCpv($sector, $db2);
        //echo "secode".$seccode;
    }
    foreach ($data->FD_CONTRACT_AWARD_UTILITIES->OBJECT_CONTRACT_AWARD_UTILITIES->DESCRIPTION_CONTRACT_AWARD_UTILITIES->CPV->CPV_ADDITIONAL as $cpv_ad)
        $sector .= "," . str_replace("'", "\'", $cpv_ad->CPV_CODE['CODE']);

    $cont_date = YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH);

    $contData = array(
        "short_descp" => "'$short_descp'",
        "ref_number" => "'$ref_number'",
        "purchasername" => "'$purchasername'",
        "purchaseradd" => "'$purchaseradd'",
        "purch_country" => "'$purch_country'",
        "purch_email" => "'$purch_email'",
        "purch_url" => "'$purch_url'",
        "contractorname" => "'$contractorname'",
        "cont_add" => "'$cont_add'",
        "cont_country" => "'$cont_country'",
        "cont_email" => "'$cont_email'",
        "cont_url" => "'$cont_url'",
        "project_location" => "'$project_location'",
        "award_detail" => "'$award_detail'",
        "contract_val" => "'$contract_val'",
        "contract_currency" => "'$contract_currency'",
        "sector" => "'$seccode'",
        "cont_date" => "'$cont_date'",
        "userid" => "0",
        "qc" => "1"
    );
     // echo "<pre>";
     // print_r($contData);
    $check = $db2->count('contract_award', "ref_number='$ref_number'");
    if ($check == 0) {
        //        $db->insert($contData, 'contract_award');
        //        $id = $db->getMax('contract_award', "id");
        //        $contData1 = array("id" => "$id");
        //        $contData1 = array_merge($contData1, $contData);
        //        $db2->insert($contData1, 'contract_award');
        $db2->insert($contData, 'contract_award');
    }

    // News

    $Directive =  $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'];

    $CODED_DATA_SECTION = $xml->CODED_DATA_SECTION;
    $LG_ORIG = (array)$CODED_DATA_SECTION->NOTICE_DATA->LG_ORIG;
    $FD_CONTRACT_AWARD_UTILITIES = $xml->FORM_SECTION->CONTRACT_AWARD_UTILITIES->FD_CONTRACT_AWARD_UTILITIES;

    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $news_cur_city = $ml_ti->TI_CY;
            $news_cur_town = $ml_ti->TI_TOWN;
            $news_cur_text = $ml_ti->TI_TEXT;
        }

        if ($ml_ti['LG'] == $LG_ORIG[0]) {
            $news_org_city = $ml_ti->TI_CY;
            $news_org_town = $ml_ti->TI_TOWN;
            $news_org_text = $ml_ti->TI_TEXT;

        }
    }

    foreach ($FD_CONTRACT_AWARD_UTILITIES->OBJECT_CONTRACT_AWARD_UTILITIES->DESCRIPTION_CONTRACT_AWARD_UTILITIES->TYPE_CONTRACT_LOCATION_W_PUB->{0} as $TYPE_CONTRACT_LOCATION_W_PUB) {
        $TYPE_CONTRACT_LOCATION_W_PUB = (array)$TYPE_CONTRACT_LOCATION_W_PUB['VALUE'];
    }

    $ADDITIONAL_INFORMATION_ARR = '';
    foreach ($FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->ADDITIONAL_INFORMATION->P as $ADDITIONAL_INFORMATION) {
        $ADDITIONAL_INFORMATION_ARR .= "<span style='  line-height: 1.2;'>$ADDITIONAL_INFORMATION</span><br/>";
    }

    $ORIGINAL_CPV = '';
    foreach ($CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ORIGINAL_CPV_VAL) {
        $ORIGINAL_CPV .= '<p>'.$ORIGINAL_CPV_VAL.'</p>';
    }

    $newsDetails_cur_head = "
        <h3 align='center'>
            <p>$news_cur_city-$news_cur_town : $news_cur_text</p>
            <p>".$CODED_DATA_SECTION->NOTICE_DATA->NO_DOC_OJS."</p>
            <p>".$CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE." - ".$CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE."</p>
        </h3>";

    $newsDetails_org_head = "
        <h3 align='center'>
            <p>$news_org_city-$news_org_town : $news_org_text</p>
            <p>".$CODED_DATA_SECTION->NOTICE_DATA->NO_DOC_OJS."</p>
            <p>".$CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE." - ".$CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE."</p>
        </h3>";


    $newsDetails_cur = "
        <div style='line-height:0.7;border:solid 2px;padding:0.5em;margin:0 0 1em 0;'>
        $newsDetails_cur_head
        <span> Directive : $Directive</span>
            <p style='font-weight: bold;text-decoration: underline'>Section I: Contracting entity</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>I.1) Name, addresses and contact point(s)</p>
                <div style='margin-left: 2em;'>
                    <p>".$FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME."</p>
                    <p>".$FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS."</p>
                    <p>For the attention of: ".$FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION."</p>
                    <p>Contact point(s): ".$FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT."</p>
                    <p>".$FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE." ".$news_cur_town."</p>
                    <p>$news_cur_city</p>
                    <p>E-mail: ".$FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL."</p>
                    <p style='font-weight: bold;'>Internet address(es):<p>
                    <p>General address of the contracting entity: <a target='_blank' href='http://".$FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->INTERNET_ADDRESSES_CONTRACT_AWARD_UTILITIES->URL_GENERAL."'>".$FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->INTERNET_ADDRESSES_CONTRACT_AWARD_UTILITIES->URL_GENERAL."</a></p>
                </div>
                <p style='font-weight: bold;'>I.2) Main activity</p>
                <p>".$CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION."</p>
                <p style='font-weight: bold;'>I.3) Contract award on behalf of other contracting entities</p>
            </div>

            <p style='font-weight: bold;text-decoration: underline'>Section II: Object of the contract</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>II.1) Description</p>
                <p style='font-weight: bold;'>II.1.1) Title attributed to the contract</p>
                <p>".$FD_CONTRACT_AWARD_UTILITIES->OBJECT_CONTRACT_AWARD_UTILITIES->DESCRIPTION_CONTRACT_AWARD_UTILITIES->TITLE_CONTRACT->P."</p>
                <p style='font-weight: bold;'>II.1.2) Type of contract and location of works, place of delivery or of performance</p>
                <p>".$CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE."</p>
                <p style='font-weight: bold;'>II.1.3) Information about a framework agreement or a dynamic purchasing system (DPS)</p>
                <p style='font-weight: bold;'>II.1.4) Short description of the contract or purchase(s):</p>
                <p style='font-weight: bold;'>II.1.5) Common procurement vocabulary (CPV)</p>
                <p>$sector</p>
                <p style='font-weight: bold;'>Description</p>
                <p>$ORIGINAL_CPV</p>
                <p style='font-weight: bold;'>II.1.6) Information about Government Procurement Agreement (GPA)</p>
                <p>The contract is covered by the Government Procurement Agreement (GPA): ".$FD_CONTRACT_AWARD_UTILITIES->OBJECT_CONTRACT_AWARD_UTILITIES->DESCRIPTION_CONTRACT_AWARD_UTILITIES->CONTRACT_COVERED_GPA['VALUE']."</p>
                <p style='font-weight: bold;'>II.2) Total final value of contract(s)</p>
                <p style='font-weight: bold;'>II.2.1) Total final value of contract(s)</p>
            </div>

            <p style='font-weight: bold;text-decoration: underline'>Section IV: Procedure</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>IV.1) Type of procedure</p>
                <p style='font-weight: bold;'>IV.1.1) Type of procedure</p>
                <p>Open<p>
                <p style='font-weight: bold;'>IV.2) Award criteria</p>
                <p style='font-weight: bold;'>IV.2.1) Award criteria</p>
                <p style='font-weight: bold;'>IV.2.2) Information about electronic auction</p>
                <p style='font-weight: bold;'>IV.3) Administrative information</p>
                <p style='font-weight: bold;'>IV.3.1) File reference number attributed by the contracting entity:</p>
                <p style='font-weight: bold;'>IV.3.2) Previous publication(s) concerning the same contract</p>
                <p>Notice number in the OJEU: ".$CODED_DATA_SECTION->NOTICE_DATA->NO_DOC_OJS."</p>
            </div>

            <p style='font-weight: bold;text-decoration: underline'>Section V: Award of contract</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>V.1) Award and contract value</p>
                <p>Contract No: ".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->CONTRACT_NO."</p>
                <p>Lot No: ".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->LOT_NUMBER." Lot Title: ".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->TITLE_CONTRACT->P."</p>
                <p style='font-weight: bold;'>V.1.1) Date of contract award decision:</p>
                <p>".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->DATE_OF_CONTRACT_AWARD->DAY.".".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->DATE_OF_CONTRACT_AWARD->MONTH.".".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->DATE_OF_CONTRACT_AWARD->YEAR."</p>
                <p style='font-weight: bold;'>V.1.2) Information about offers</p>
                <p>Number of offers received: ".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->OFFERS_RECEIVED_NUMBER."</p>
                <p style='font-weight: bold;'>V.1.3) Name and address of economic operator in favour of whom the contract award decision has been taken</p>
                <p>".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME_CHP->ORGANISATION->OFFICIALNAME."</p>
                <p>".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME_CHP->ADDRESS."</p>
                <p>".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME_CHP->POSTAL_CODE." ".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME_CHP->TOWN."</p>
                <p>".$news_cur_city."</p>
                <p style='font-weight: bold;'>V.1.4) Information on value of contract</p>
                <p>".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->INFORMATION_VALUE_CONTRACT->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE->VALUE_COST." ".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->INFORMATION_VALUE_CONTRACT->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE['CURRENCY']."</p>
                <p style='font-weight: bold;'>V.1.5) Information about subcontracting</p>
                <p style='font-weight: bold;'>V.1.6) Price paid for bargain purchases</p>
            </div>

            <p style='font-weight: bold;text-decoration: underline'>Section VI: Complementary information</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>VI.1) Information about European Union funds</p>
                <p style='font-weight: bold;'>VI.2) Additional information:</p>
                <p>".$ADDITIONAL_INFORMATION_ARR."</p>
                <p style='font-weight: bold;'>VI.3) Procedures for appeal</p>
                <p style='font-weight: bold;'>VI.3.1) Body responsible for appeal procedures</p>
                <p style='font-weight: bold;'>VI.3.2) Lodging of appeals</p>
                <p style='font-weight: bold;'>VI.3.3) Service from which information about the lodging of appeals may be obtained</p>
                <p style='font-weight: bold;'>VI.3.4) Date of dispatch of this notice:</p>
                <p>".$FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->NOTICE_DISPATCH_DATE->DAY.".".$FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->NOTICE_DISPATCH_DATE->MONTH.".".$FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->NOTICE_DISPATCH_DATE->YEAR."</p>
            </div>
        </div>";

    $newsDetails_org = "
        <div style='line-height:0.7;border:solid 2px;padding:0.5em;margin:0 0 1em 0;'>
        $newsDetails_org_head
        <span> Directive : $Directive</span>
            <p style='font-weight: bold;text-decoration: underline'>Section I: Contracting entity</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>I.1) Name, addresses and contact point(s)</p>
                <div style='margin-left: 2em;'>
                    <p>".$FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME."</p>
                    <p>".$FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS."</p>
                    <p>For the attention of: ".$FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION."</p>
                    <p>Contact point(s): ".$FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT."</p>
                    <p>".$FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE." ".$news_org_town."</p>
                    <p>$news_org_city</p>
                    <p>E-mail: ".$FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL."</p>
                    <p style='font-weight: bold;'>Internet address(es):<p>
                    <p>General address of the contracting entity: <a target='_blank' href='http://".$FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->INTERNET_ADDRESSES_CONTRACT_AWARD_UTILITIES->URL_GENERAL."'>".$FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->INTERNET_ADDRESSES_CONTRACT_AWARD_UTILITIES->URL_GENERAL."</a></p>
                </div>
                <p style='font-weight: bold;'>I.2) Main activity</p>
                <p>".$CODED_DATA_SECTION->CODIF_DATA->RP_REGULATION."</p>
                <p style='font-weight: bold;'>I.3) Contract award on behalf of other contracting entities</p>
            </div>

            <p style='font-weight: bold;text-decoration: underline'>Section II: Object of the contract</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>II.1) Description</p>
                <p style='font-weight: bold;'>II.1.1) Title attributed to the contract</p>
                <p>".$FD_CONTRACT_AWARD_UTILITIES->OBJECT_CONTRACT_AWARD_UTILITIES->DESCRIPTION_CONTRACT_AWARD_UTILITIES->TITLE_CONTRACT->P."</p>
                <p style='font-weight: bold;'>II.1.2) Type of contract and location of works, place of delivery or of performance</p>
                <p>".$CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE."</p>
                <p style='font-weight: bold;'>II.1.3) Information about a framework agreement or a dynamic purchasing system (DPS)</p>
                <p style='font-weight: bold;'>II.1.4) Short description of the contract or purchase(s):</p>
                <p style='font-weight: bold;'>II.1.5) Common procurement vocabulary (CPV)</p>
                <p>$sector</p>
                <p style='font-weight: bold;'>Description</p>
                <p>$ORIGINAL_CPV</p>
                <p style='font-weight: bold;'>II.1.6) Information about Government Procurement Agreement (GPA)</p>
                <p>The contract is covered by the Government Procurement Agreement (GPA): ".$FD_CONTRACT_AWARD_UTILITIES->OBJECT_CONTRACT_AWARD_UTILITIES->DESCRIPTION_CONTRACT_AWARD_UTILITIES->CONTRACT_COVERED_GPA['VALUE']."</p>
                <p style='font-weight: bold;'>II.2) Total final value of contract(s)</p>
                <p style='font-weight: bold;'>II.2.1) Total final value of contract(s)</p>
            </div>

            <p style='font-weight: bold;text-decoration: underline'>Section IV: Procedure</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>IV.1) Type of procedure</p>
                <p style='font-weight: bold;'>IV.1.1) Type of procedure</p>
                <p>Open<p>
                <p style='font-weight: bold;'>IV.2) Award criteria</p>
                <p style='font-weight: bold;'>IV.2.1) Award criteria</p>
                <p style='font-weight: bold;'>IV.2.2) Information about electronic auction</p>
                <p style='font-weight: bold;'>IV.3) Administrative information</p>
                <p style='font-weight: bold;'>IV.3.1) File reference number attributed by the contracting entity:</p>
                <p style='font-weight: bold;'>IV.3.2) Previous publication(s) concerning the same contract</p>
                <p>Notice number in the OJEU: ".$CODED_DATA_SECTION->NOTICE_DATA->NO_DOC_OJS."</p>
            </div>

            <p style='font-weight: bold;text-decoration: underline'>Section V: Award of contract</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>V.1) Award and contract value</p>
                <p>Contract No: ".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->CONTRACT_NO."</p>
                <p>Lot No: ".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->LOT_NUMBER." Lot Title: ".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->TITLE_CONTRACT->P."</p>
                <p style='font-weight: bold;'>V.1.1) Date of contract award decision:</p>
                <p>".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->DATE_OF_CONTRACT_AWARD->DAY.".".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->DATE_OF_CONTRACT_AWARD->MONTH.".".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->DATE_OF_CONTRACT_AWARD->YEAR."</p>
                <p style='font-weight: bold;'>V.1.2) Information about offers</p>
                <p>Number of offers received: ".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->OFFERS_RECEIVED_NUMBER."</p>
                <p style='font-weight: bold;'>V.1.3) Name and address of economic operator in favour of whom the contract award decision has been taken</p>
                <p>".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME_CHP->ORGANISATION->OFFICIALNAME."</p>
                <p>".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME_CHP->ADDRESS."</p>
                <p>".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME_CHP->POSTAL_CODE." ".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME_CHP->TOWN."</p>
                <p>".$news_cur_city."</p>
                <p style='font-weight: bold;'>V.1.4) Information on value of contract</p>
                <p>".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->INFORMATION_VALUE_CONTRACT->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE->VALUE_COST." ".$FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->INFORMATION_VALUE_CONTRACT->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE['CURRENCY']."</p>
                <p style='font-weight: bold;'>V.1.5) Information about subcontracting</p>
                <p style='font-weight: bold;'>V.1.6) Price paid for bargain purchases</p>
            </div>

            <p style='font-weight: bold;text-decoration: underline'>Section VI: Complementary information</p>
            <div style='margin-left: 1em;'>
                <p style='font-weight: bold;'>VI.1) Information about European Union funds</p>
                <p style='font-weight: bold;'>VI.2) Additional information:</p>
                <p>".$ADDITIONAL_INFORMATION_ARR."</p>
                <p style='font-weight: bold;'>VI.3) Procedures for appeal</p>
                <p style='font-weight: bold;'>VI.3.1) Body responsible for appeal procedures</p>
                <p style='font-weight: bold;'>VI.3.2) Lodging of appeals</p>
                <p style='font-weight: bold;'>VI.3.3) Service from which information about the lodging of appeals may be obtained</p>
                <p style='font-weight: bold;'>VI.3.4) Date of dispatch of this notice:</p>
                <p>".$FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->NOTICE_DISPATCH_DATE->DAY.".".$FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->NOTICE_DISPATCH_DATE->MONTH.".".$FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->NOTICE_DISPATCH_DATE->YEAR."</p>
            </div>
        </div>";


    $news_details_main = "
    <div>
        $newsDetails_cur
        $newsDetails_org
    </div>";

    $news_details_main = trim($news_details_main);
    echo $news_details_main;
    echo '<hr/>';

    $news_reception_id = "Notice Reception Id: " . $xml->TECHNICAL_SECTION->RECEPTION_ID;

    $deletion_date = YmdChnage($xml->TECHNICAL_SECTION->DELETION_DATE);

    $news_purchaser_details = "<b>Purchaser Details: </b>" . $purchasername . ", " . $purch_country . ", " . $purch_email . ", " . $purch_url;

    $news_contractor_details = "<b>Contractor Details: </b>" . $contractorname . ", " . $cont_add . ", " . $cont_country . ", " . $cont_email . ", " . $cont_url;


    $news_DELETION_DATE = "Notice Deletion Date: " . $deletion_date;
    $news_lang_avl = "Available Language: " . $xml->TECHNICAL_SECTION->FORM_LG_LIST;

    $news_contract_type = "<b>Contract Type: </b>" . str_replace("'", "\'", $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE);
    $news_ref_num = "<b>Ref Num: </b>" . $ref_number;


    $news_contract_type = $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE . " Contract - " . $ref_number;
    $news_directive_value = "Directive Value: " . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'];
    $news_cpv = "CPV: " . $sector;



    // Notice DATA
    $news_uri_doc_url = "";
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA as $noticedata_arr) {
        $news_NO_DOC_OJS = $noticedata_arr->NO_DOC_OJS;
        foreach ($noticedata_arr->URI_LIST->URI_DOC as $urival) {
            $news_uri_doc_url .= $urival . "<br/>";
        }
        $news_orglang = $noticedata_arr->LG_ORIG;
        $news_isocountry = $noticedata_arr->ISO_COUNTRY['VALUE'];
        $news_originalcpv = $noticedata_arr->ORIGINAL_CPV;
        $news_notice_value = $noticedata_arr->VALUES_LIST->VALUES->SINGLE_VALUE->VALUE;

        $news_notice_data = "<b>Notice Data</b>: " . $news_NO_DOC_OJS . ", " . $news_uri_doc_url . ", " . $news_orglang . ", " . $news_isocountry . ", " . $news_originalcpv . ", " . $news_notice_value;

        $news_notice_data = str_replace("'", "\'", $news_notice_data);
    }

    // CodIf Data

    foreach ($xml->CODED_DATA_SECTION->CODIF_DATA as $codifdata_arr) {

        $news_datedispatch = $codifdata_arr->DS_DATE_DISPATCH;
        $news_AA_AUTHORITY_TYPE = $codifdata_arr->AA_AUTHORITY_TYPE;
        $news_pr_proc = $codifdata_arr->PR_PROC;
        $news_rpregulation = $codifdata_arr->RP_REGULATION;
        $news_typebid = $codifdata_arr->TY_TYPE_BID;
        $news_acawardcrit = $codifdata_arr->AC_AWARD_CRIT;
        $news_mamaninactivity = $codifdata_arr->MA_MAIN_ACTIVITIES;
        $news_codifheading = $codifdata_arr->HEADING;

        $news_codifdata = "<b>CodIf Data</b>: " . $news_datedispatch . ", " . $news_AA_AUTHORITY_TYPE . ", " . $news_pr_proc . ", " .
                $news_rpregulation . ", " . $news_typebid . ", " . $news_acawardcrit . ", " . $news_mamaninactivity . ", " . $news_codifheading;
        $news_codifdata = str_replace("'", "\'", $news_codifdata);
    }

    // FormSection
    $i = 1;
    foreach ($xml->FORM_SECTION->CONTRACT_AWARD_UTILITIES as $compinfcontractarr) {



        foreach ($compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE as $concessionval) {
            $news_officialname = $concessionval->ORGANISATION->OFFICIALNAME;
            $news_address = $concessionval->ADDRESS;
            $news_town = $concessionval->TOWN;
            $news_postalcode = $concessionval->POSTAL_CODE;
            $news_country_value = $concessionval->COUNTRY['VALUE'];
            $news_attention = $concessionval->ATTENTION;
            $news_email = $concessionval->E_MAILS->E_MAIL;
            //echo "<br/>_________________<br/>";

            $news_form_values = $news_officialname . ", " . $news_address . ", " . $news_town . ", " . $news_postalcode . ", " . $news_country_value . ", " . $news_attention . ", " . $news_email;
        }

        $news_internetadd = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->INTERNET_ADDRESSES_CONTRACT_AWARD_UTILITIES->URL_GENERAL;
        $news_form_values = $news_form_values . ", " . $news_internetadd;
        //$news_form_values = str_replace("'", "\'", $news_form_values);
        //        echo $news_form_values."<br/>";

        foreach ($compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->TYPE_AND_ACTIVITIES_AND_PURCHASING_ON_BEHALF as $typeactivity) {
            $contractauth = $typeactivity->TYPE_AND_ACTIVITIES->TYPE_OF_CONTRACTING_AUTHORITY['VALUE'];
            $contractactivity = $typeactivity->TYPE_AND_ACTIVITIES->TYPE_OF_ACTIVITY['VALUE'];
            $contracttypeofactivty = $typeactivity->TYPE_AND_ACTIVITIES->TYPE_OF_ACTIVITY_OTHER;

            foreach ($typeactivity->PURCHASING_ON_BEHALF->PURCHASING_ON_BEHALF_YES->CONTACT_DATA_OTHER_BEHALF_CONTRACTING_AUTORITHY as $purchaseval) {
                $purbehalfofcname = $purchaseval->ORGANISATION->OFFICIALNAME;
                $purbehalfaddress = $purchaseval->ADDRESS;
                $purbehalftown = $purchaseval->TOWN;
                $purbehalfpostalcode = $purchaseval->POSTAL_CODE;
                $purbehalfcountry = $purchaseval->COUNTRY['VALUE'];
            }
        }




        foreach ($compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->OBJECT_CONTRACT_AWARD_UTILITIES->DESCRIPTION_CONTRACT_AWARD_UTILITIES as $awardNoticeInfo) {
            $news_titlecontract = $awardNoticeInfo->TITLE_CONTRACT->P;
            $news_typecontractlocation = $awardNoticeInfo->TYPE_CONTRACT_LOCATION_W_PUB->TYPE_CONTRACT['VALUE'];
            $news_categorypub = $awardNoticeInfo->TYPE_CONTRACT_LOCATION_W_PUB->SERVICE_CATEGORY_PUB;
            $news_shortcontractdescp = $awardNoticeInfo->SHORT_CONTRACT_DESCRIPTION->P;

            $news_descriptioninfo = $news_titlecontract . ", " . $news_typecontractlocation . ", " . $news_categorypub . ", " . $news_shortcontractdescp;
        }


        //$news_descriptioninfo = str_replace("'", "\'", $news_descriptioninfo);

        $news_valuecost = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->OBJECT_CONTRACT_AWARD_UTILITIES->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE->VALUE_COST;

        $news_award_full_value = "";
        $news_chp_data = "";

        foreach ($compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE as $CriteraDef) {

            $news_contract_num = $CriteraDef->CONTRACT_NO;
            $news_contract_date = $CriteraDef->DATE_OF_CONTRACT_AWARD->DAY . "-" . $CriteraDef->DATE_OF_CONTRACT_AWARD->MONTH . "-" . $CriteraDef->DATE_OF_CONTRACT_AWARD->YEAR;

            foreach ($CriteraDef->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME_CHP as $cdef) {

                $news_chp_data .= $cdef->ORGANISATION->OFFICIALNAME . " " . $cdef->ADDRESS . " " . $cdef->TOWN . " " . $cdef->POSTAL_CODE;
            }
            $news_award_full_value = $news_contract_num . " " . $news_contract_date . " " . $news_chp_data;
        }

        $news_filerefnum = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->PROCEDURES_CONTRACT_AWARD_UTILITIES->ADMINISTRATIVE_INFO_CONTRACT_AWARD_UTILITIES->PREVIOUS_PUBLICATION_INFORMATION_NOTICE_F6->PREVIOUS_PUBLICATION_EXISTS_F6->CNT_NOTICE_INFORMATION->NOTICE_NUMBER_OJ;

        //        $news_contractnumber = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->AWARD_OF_CONTRACT->CONTRACT_NUMBER;
        //
        //        $news_contract_awardofficalnum = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->AWARD_OF_CONTRACT->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ORGANISATION->OFFICIALNAME;
        //
        //        $news_contract_address = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->AWARD_OF_CONTRACT->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ADDRESS;
        //
        //        $news_contract_town = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->AWARD_OF_CONTRACT->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->TOWN;
        //
        //        $news_contract_prject = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->RELATES_TO_EU_PROJECT_YES->P;
        //
        //        $news_contract_prject_oficialname = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ORGANISATION->OFFICIALNAME;
        //
        //        $news_contract_prject_town = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->TOWN;



        $news_form_values_complete .= $news_form_values . ", " . $contractauth . ", " . $contractactivity . ", " . $contracttypeofactivty . ", " .
                $purbehalfofcname . ", " . $purbehalfaddress . ", " . $purbehalftown . ", " . $purbehalfpostalcode . ", " . $purbehalfcountry . ", " .
                $news_descriptioninfo . ", " . $news_valuecost . ", " . $news_filerefnum . "," . $news_award_full_value;
    }

    $news_form_values_complete = str_replace("'", "\'", $news_form_values_complete);

    //$news_additioal_info = "Additional Information: " . $xml->FORM_SECTION->CONTRACT_AWARD_UTILITIES->FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->ADDITIONAL_INFORMATION['0'];

    $news_additonal_info = str_replace("'", "\'", $news_additonal_info);
    $news_additioal_info = "<b>Additional Information: </b>" . $news_additonal_info . "<br/>" . $news_mlti_doc;

    $news_details = $award_detail . "<br/>" . $news_purchaser_details . "<br/>" . $news_contractor_details . "<br/>" .
            $news_reception_id . "<br/>" . $news_DELETION_DATE . "<br/>" . $news_lang_avl . "<br/>" . $news_contract_type . "<br/>" .
            $news_directive_value . "<br/>" . $news_cpv . "<br/>" . $news_ref_num . "<br/>" . $news_notice_data . "<br/>" .
            $news_codifdata . "<br/>" . $news_additioal_info . "<br/>" . $news_form_values_complete;


    $source_url = $xml->CODED_DATA_SECTION->NOTICE_DATA->URI_LIST->URI_DOC;

    //$news_details = str_replace("'", "\'", $news_details);

    $news_details_main = addslashes($news_details_main);
    $NewsData_Arr = array(
        "headline" => "'$news_head'",
        "details" => "'$news_details_main'",
        "source" => "'1068'",
        "source_url" => "'$source_url'",
        "sector" => "'$seccode'",
        "ca_news" => "'0'",
        "p_news" => "'1'",
        "user_id" => "0",
        "qc" => "'1'"
    );

    // echo "<pre>";
    //       print_r($NewsData_Arr);exit;
    $checknews = $db2->count('news', "headline='$news_head'");
    if ($checknews == 0) {
        $db2->insert($NewsData_Arr, 'news');
    }

    return "DONE";
}


//CA
function contract_award_defence($xml, $db2 = NULL, $db2 = NULL) {
    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $short_descp = str_replace("'", "\'", $ml_ti->TI_TEXT);
            // News

            $news_txt = str_replace("'", "\'", $ml_ti->TI_TEXT);
            $news_cy = str_replace("'", "\'", $ml_ti->TI_CY);
            $news_town = str_replace("'", "\'", $ml_ti->TI_TOWN);

            $news_head = "Contract awarded for " . $news_txt . ", " . $news_cy . "-" . $news_town;
        } else {
            $news_txt = str_replace("'", "\'", $ml_ti->TI_TEXT);
            $news_cy = str_replace("'", "\'", $ml_ti->TI_CY);
            $news_town = str_replace("'", "\'", $ml_ti->TI_TOWN);

            $news_mlti_doc .= $news_txt . " " . $news_cy . " " . $news_town . "<br/>";
        }
    }
    foreach ($xml->FORM_SECTION->CONTRACT_AWARD_DEFENCE as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
        } else {
            foreach ($temp->FD_CONTRACT_AWARD_DEFENCE->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_DEFENCE->ADDITIONAL_INFORMATION->P as $FD_additonalinfo) {
                $news_additonal_info.=$FD_additonalinfo . "<br/>";
            }
        }
    }
    if (empty($data)) {
        $data = $xml->FORM_SECTION->CONTRACT_AWARD_DEFENCE;
    }
    $ref_number = str_replace("'", "\'", $xml['DOC_ID']);
    $data1 = $data->FD_CONTRACT_AWARD_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE;
    $purchasername = str_replace("'", "\'", $data1->ORGANISATION->OFFICIALNAME);

    $purchaseradd = str_replace("'", "\'", $data1->ADDRESS . "," . $data1->TOWN . " " . $data1->POSTAL_CODE . ",");

    if (!empty($data1->PHONE))
        $purchaseradd .=" Tel : " . $data1->PHONE . ",";

    if (!empty($data1->FAX))
        $purchaseradd .=" Fax : " . $data1->FAX;

    $purch_country = str_replace("'", "\'", $data1->COUNTRY["VALUE"]);

    if ($purch_country == 'UK') {
        $purch_country = 'GB';
    }
    $purch_email = str_replace("'", "\'", $data1->E_MAILS->E_MAIL);
    $purch_url = str_replace("'", "\'", $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL);
    $data2 = $data->FD_CONTRACT_AWARD_DEFENCE->AWARD_OF_CONTRACT_DEFENCE->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME;
    $contractorname = str_replace("'", "\'", $data2->ORGANISATION->OFFICIALNAME);
    $cont_add = str_replace("'", "\'", $data2->ADDRESS . "," . $data2->TOWN . " " . $data2->POSTAL_CODE);
    $cont_country = str_replace("'", "\'", $data2->COUNTRY["VALUE"]);

    if ($cont_country == 'UK') {
        $cont_country = 'GB';
    }

    $cont_email = '';
    $cont_url = '';
    $project_location = str_replace("'", "\'", $data1->COUNTRY["VALUE"]);
    $project_location = str_replace("'", "\'", $data1->COUNTRY["VALUE"]);
    if (empty($project_location)) {
        $project_location = $cont_country;
    }
    if ($project_location == 'UK') {
        $project_location = 'GB';
    }
    $award_detail = '';
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $award_detail .= str_replace("'", "\'", $ocpv . ",");
    }

    $contract_val = str_replace("'", "\'", $data->FD_CONTRACT_AWARD_DEFENCE->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE_DEFENCE->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE->VALUE_COST);
    $contract_currency = str_replace("'", "\'", $data->FD_CONTRACT_AWARD_DEFENCE->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE_DEFENCE->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE['CURRENCY']);
    $sector = '';
    $sector .= str_replace("'", "\'", $data->FD_CONTRACT_AWARD_DEFENCE->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE_DEFENCE->DESCRIPTION_AWARD_NOTICE_INFORMATION_DEFENCE->CPV->CPV_MAIN->CPV_CODE["CODE"]);

    foreach ($data->FD_CONTRACT_AWARD_DEFENCE->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE_DEFENCE->DESCRIPTION_AWARD_NOTICE_INFORMATION_DEFENCE->CPV->CPV_ADDITIONAL as $cpv_ad)
        $sector .= "," . str_replace("'", "\'", $cpv_ad->CPV_CODE['CODE']);
    if (!empty($sector)) {
        $seccode = getCpv($sector, $db2);
        //echo "secode".$seccode;
    }
    $cont_date = YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH);

    $contData = array(
        "short_descp" => "'$short_descp'",
        "ref_number" => "'$ref_number'",
        "purchasername" => "'$purchasername'",
        "purchaseradd" => "'$purchaseradd'",
        "purch_country" => "'$purch_country'",
        "purch_email" => "'$purch_email'",
        "purch_url" => "'$purch_url'",
        "contractorname" => "'$contractorname'",
        "cont_add" => "'$cont_add'",
        "cont_country" => "'$cont_country'",
        "cont_email" => "'$cont_email'",
        "cont_url" => "'$cont_url'",
        "project_location" => "'$project_location'",
        "award_detail" => "'$award_detail'",
        "contract_val" => "'$contract_val'",
        "contract_currency" => "'$contract_currency'",
        "sector" => "'$seccode'",
        "cont_date" => "'$cont_date'",
        "userid" => "0",
        "qc" => "1"
    );
    //echo "<pre>";
    //print_r($contData);
    $check = $db2->count('contract_award', "ref_number='$ref_number'");
    if ($check == 0) {
        $db2->insert($contData, 'contract_award');
    }



    // News


    $news_reception_id = "Notice Reception Id: " . $xml->TECHNICAL_SECTION->RECEPTION_ID;

    $news_purchaser_details = "<b>Purchaser Details: </b>" . $purchasername . ", " . $purch_country . ", " . $purch_email . ", " . $purch_url;

    $news_contractor_details = "<b>Contractor Details: </b>" . $contractorname . ", " . $cont_add . ", " . $cont_country . ", " . $cont_email . ", " . $cont_url;



    $deletion_date = YmdChnage($xml->TECHNICAL_SECTION->DELETION_DATE);

    $news_DELETION_DATE = "Notice Deletion Date: " . $deletion_date;
    $news_lang_avl = "Available Language: " . $xml->TECHNICAL_SECTION->FORM_LG_LIST;

    $news_contract_type = "<b>Contract Type: </b>" . str_replace("'", "\'", $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE);
    $news_ref_num = "<b>Ref Num: </b>" . $ref_number;

    $news_contract_type = $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE . " Contract - " . $ref_number;
    $news_directive_value = "Directive Value: " . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'];
    $news_cpv = "CPV: " . $sector;

    // Notice DATA
    $news_uri_doc_url = "";
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA as $noticedata_arr) {
        $news_NO_DOC_OJS = $noticedata_arr->NO_DOC_OJS;
        foreach ($noticedata_arr->URI_LIST->URI_DOC as $urival) {
            $news_uri_doc_url .= $urival . "<br/>";
        }
        $news_orglang = $noticedata_arr->LG_ORIG;
        $news_isocountry = $noticedata_arr->ISO_COUNTRY['VALUE'];
        $news_originalcpv = $noticedata_arr->ORIGINAL_CPV;
        $news_notice_value = $noticedata_arr->VALUES_LIST->VALUES->SINGLE_VALUE->VALUE;

        $news_notice_data = "<b>Notice Data</b>: " . $news_NO_DOC_OJS . ", " . $news_uri_doc_url . ", " . $news_orglang . ", " . $news_isocountry . ", " . $news_originalcpv . ", " . $news_notice_value;

        $news_notice_data = str_replace("'", "\'", $news_notice_data);
    }

    // CodIf Data

    foreach ($xml->CODED_DATA_SECTION->CODIF_DATA as $codifdata_arr) {

        $news_datedispatch = $codifdata_arr->DS_DATE_DISPATCH;
        $news_AA_AUTHORITY_TYPE = $codifdata_arr->AA_AUTHORITY_TYPE;
        $news_pr_proc = $codifdata_arr->PR_PROC;
        $news_rpregulation = $codifdata_arr->RP_REGULATION;
        $news_typebid = $codifdata_arr->TY_TYPE_BID;
        $news_acawardcrit = $codifdata_arr->AC_AWARD_CRIT;
        $news_mamaninactivity = $codifdata_arr->MA_MAIN_ACTIVITIES;
        $news_codifheading = $codifdata_arr->HEADING;

        $news_codifdata = "<b>CodIf Data</b>: " . $news_datedispatch . ", " . $news_AA_AUTHORITY_TYPE . ", " . $news_pr_proc . ", " .
                $news_rpregulation . ", " . $news_typebid . ", " . $news_acawardcrit . ", " . $news_mamaninactivity . ", " . $news_codifheading;
        $news_codifdata = str_replace("'", "\'", $news_codifdata);
    }

    // FormSection
    $i = 1;
    foreach ($xml->FORM_SECTION->CONTRACT_AWARD_DEFENCE as $compinfcontractarr) {



        foreach ($compinfcontractarr->FD_CONTRACT_AWARD_DEFENCE->CONTRACTING_ENTITY_CONTRACT_AWARD_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_DEFENCE->CA_CE_CONCESSIONAIRE_PROFILE as $concessionval) {
            $news_officialname = $concessionval->ORGANISATION->OFFICIALNAME;
            $news_address = $concessionval->ADDRESS;
            $news_town = $concessionval->TOWN;
            $news_postalcode = $concessionval->POSTAL_CODE;
            $news_country_value = $concessionval->COUNTRY['VALUE'];
            $news_attention = $concessionval->ATTENTION;
            $news_email = $concessionval->E_MAILS->E_MAIL;
            //echo "<br/>_________________<br/>";

            $news_form_values = $news_officialname . ", " . $news_address . ", " . $news_town . ", " . $news_postalcode . ", " . $news_country_value . ", " . $news_attention . ", " . $news_email;
        }

        $news_internetadd = $compinfcontractarr->FD_CONTRACT_AWARD_DEFENCE->CONTRACTING_ENTITY_CONTRACT_AWARD_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_DEFENCE->INTERNET_ADDRESSES_CONTRACT_AWARD_DEFENCE->URL_GENERAL;
        $news_form_values = $news_form_values . ", " . $news_internetadd;
        //$news_form_values = str_replace("'", "\'", $news_form_values);
        //        echo $news_form_values."<br/>";

        foreach ($compinfcontractarr->FD_CONTRACT_AWARD_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD->TYPE_AND_ACTIVITIES_AND_PURCHASING_ON_BEHALF as $typeactivity) {
            $contractauth = $typeactivity->TYPE_AND_ACTIVITIES->TYPE_OF_CONTRACTING_AUTHORITY['VALUE'];
            $contractactivity = $typeactivity->TYPE_AND_ACTIVITIES->TYPE_OF_ACTIVITY['VALUE'];
            $contracttypeofactivty = $typeactivity->TYPE_AND_ACTIVITIES->TYPE_OF_ACTIVITY_OTHER;

            foreach ($typeactivity->PURCHASING_ON_BEHALF->PURCHASING_ON_BEHALF_YES->CONTACT_DATA_OTHER_BEHALF_CONTRACTING_AUTORITHY as $purchaseval) {
                $purbehalfofcname = $purchaseval->ORGANISATION->OFFICIALNAME;
                $purbehalfaddress = $purchaseval->ADDRESS;
                $purbehalftown = $purchaseval->TOWN;
                $purbehalfpostalcode = $purchaseval->POSTAL_CODE;
                $purbehalfcountry = $purchaseval->COUNTRY['VALUE'];
            }
        }




        foreach ($compinfcontractarr->FD_CONTRACT_AWARD_DEFENCE->OBJECT_CONTRACT_AWARD_DEFENCE->DESCRIPTION_CONTRACT_AWARD_DEFENCE as $awardNoticeInfo) {
            $news_titlecontract = $awardNoticeInfo->TITLE_CONTRACT->P;
            $news_typecontractlocation = $awardNoticeInfo->TYPE_CONTRACT_LOCATION_W_PUB->TYPE_CONTRACT['VALUE'];
            $news_categorypub = $awardNoticeInfo->TYPE_CONTRACT_LOCATION_W_PUB->SERVICE_CATEGORY_PUB;
            $news_shortcontractdescp = $awardNoticeInfo->SHORT_CONTRACT_DESCRIPTION->P;

            $news_descriptioninfo = $news_titlecontract . ", " . $news_typecontractlocation . ", " . $news_categorypub . ", " . $news_shortcontractdescp;
        }


        //$news_descriptioninfo = str_replace("'", "\'", $news_descriptioninfo);

        $news_valuecost = $compinfcontractarr->FD_CONTRACT_AWARD_DEFENCE->OBJECT_CONTRACT_AWARD_DEFENCE->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE->VALUE_COST;

        $news_award_full_value = "";
        $news_chp_data = "";

        foreach ($compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_DEFENCE->AWARD_AND_CONTRACT_VALUE as $CriteraDef) {

            $news_contract_num = $CriteraDef->CONTRACT_NO;
            $news_contract_date = $CriteraDef->DATE_OF_CONTRACT_AWARD->DAY . "-" . $CriteraDef->DATE_OF_CONTRACT_AWARD->MONTH . "-" . $CriteraDef->DATE_OF_CONTRACT_AWARD->YEAR;

            foreach ($CriteraDef->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME_CHP as $cdef) {

                $news_chp_data .= $cdef->ORGANISATION->OFFICIALNAME . " " . $cdef->ADDRESS . " " . $cdef->TOWN . " " . $cdef->POSTAL_CODE;
            }
            $news_award_full_value = $news_contract_num . " " . $news_contract_date . " " . $news_chp_data;
        }

        $news_filerefnum = $compinfcontractarr->FD_CONTRACT_AWARD_DEFENCE->PROCEDURES_CONTRACT_AWARD_DEFENCE->ADMINISTRATIVE_INFO_CONTRACT_AWARD_DEFENCE->PREVIOUS_PUBLICATION_INFORMATION_NOTICE_F6->PREVIOUS_PUBLICATION_EXISTS_F6->CNT_NOTICE_INFORMATION->NOTICE_NUMBER_OJ;

        //        $news_contractnumber = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->AWARD_OF_CONTRACT->CONTRACT_NUMBER;
        //
        //        $news_contract_awardofficalnum = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->AWARD_OF_CONTRACT->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ORGANISATION->OFFICIALNAME;
        //
        //        $news_contract_address = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->AWARD_OF_CONTRACT->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ADDRESS;
        //
        //        $news_contract_town = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->AWARD_OF_CONTRACT->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->TOWN;
        //
        //        $news_contract_prject = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->RELATES_TO_EU_PROJECT_YES->P;
        //
        //        $news_contract_prject_oficialname = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->ORGANISATION->OFFICIALNAME;
        //
        //        $news_contract_prject_town = $compinfcontractarr->FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->PROCEDURES_FOR_APPEAL->APPEAL_PROCEDURE_BODY_RESPONSIBLE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME->TOWN;



        $news_form_values_complete .= $news_form_values . ", " . $contractauth . ", " . $contractactivity . ", " . $contracttypeofactivty . ", " .
                $purbehalfofcname . ", " . $purbehalfaddress . ", " . $purbehalftown . ", " . $purbehalfpostalcode . ", " . $purbehalfcountry . ", " .
                $news_descriptioninfo . ", " . $news_valuecost . ", " . $news_full_criteria . ", " . $news_filerefnum . "," . $news_award_full_value;
    }

    $news_form_values_complete = str_replace("'", "\'", $news_form_values_complete);




    //$news_additioal_info = "Additional Information: " . $xml->FORM_SECTION->CONTRACT_AWARD_UTILITIES->FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->ADDITIONAL_INFORMATION['0'];

    $news_additonal_info = str_replace("'", "\'", $news_additonal_info);
    $news_additioal_info = "<b>Additional Information: </b>" . $news_additonal_info . "<br/>" . $news_mlti_doc;

    $news_details = $award_detail . "<br/>" . $news_purchaser_details . "<br/>" . $news_contractor_details . "<br/>" .
            $news_reception_id . "<br/>" . $news_DELETION_DATE . "<br/>" . $news_lang_avl . "<br/>" . $news_contract_type . "<br/>" .
            $news_directive_value . "<br/>" . $news_cpv . "<br/>" . $news_ref_num . "<br/>" . $news_notice_data . "<br/>" .
            $news_codifdata . "<br/>" . $news_additioal_info . "<br/>" . $news_form_values_complete;

    $source_url = $xml->CODED_DATA_SECTION->NOTICE_DATA->URI_LIST->URI_DOC;

    //$news_details = str_replace("'", "\'", $news_details);


    $NewsData_Arr = array(
        "headline" => "'$news_head'",
        "details" => "'$news_details'",
        "source" => "'1068'",
        "source_url" => "'$source_url'",
        "sector" => "'$seccode'",
        "ca_news" => "'0'",
        "p_news" => "'1'",
        "qc" => "'1'",
        "user_id" => "0"
    );

    $checknews = $db2->count('news', "headline='$news_head'");
    if ($checknews == 0) {
        $db2->insert($NewsData_Arr, 'news');
    }



    return "DONE";
}

//CA
function result_design_contest($xml, $db2 = NULL, $db2 = NULL) {

    foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti) {
        if ($ml_ti['LG'] == 'EN') {
            $short_descp = str_replace("'", "\'", $ml_ti->TI_TEXT);
            // News

            $news_txt = str_replace("'", "\'", $ml_ti->TI_TEXT);
            $news_cy = str_replace("'", "\'", $ml_ti->TI_CY);
            $news_town = str_replace("'", "\'", $ml_ti->TI_TOWN);

            $news_head = "Contract awarded for " . $news_txt . ", " . $news_cy . "-" . $news_town;
        } else {
            $news_txt = str_replace("'", "\'", $ml_ti->TI_TEXT);
            $news_cy = str_replace("'", "\'", $ml_ti->TI_CY);
            $news_town = str_replace("'", "\'", $ml_ti->TI_TOWN);

            $news_mlti_doc .= $news_txt . " " . $news_cy . " " . $news_town . "<br/>";
        }
    }
    foreach ($xml->FORM_SECTION->RESULT_DESIGN_CONTEST as $temp) {
        if ($temp["LG"] == 'EN') {
            $data = $temp;
        } else {
            foreach ($temp->FD_RESULT_DESIGN_CONTEST->OBJECT_RESULT_DESIGN_CONTEST->DESCRIPTION->P as $FD_additonalinfo) {
                $news_additonal_info.=$FD_additonalinfo . "<br/>";
            }
        }
    }
    if (empty($data)) {
        $data = $xml->FORM_SECTION->RESULT_DESIGN_CONTEST;
    }
    $ref_number = str_replace("'", "\'", $xml['DOC_ID']);
    $data1 = $data->FD_RESULT_DESIGN_CONTEST->CONTRACTING_ENTITY_RESULT_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_RESULT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE;
    $purchasername = str_replace("'", "\'", $data1->ORGANISATION->OFFICIALNAME);
    $purchaseradd = str_replace("'", "\'", $data1->ADDRESS . "," . $data1->TOWN . " " . $data1->POSTAL_CODE . ",");
    if (!empty($data1->PHONE))
        $purchaseradd .=" Tel : " . $data1->PHONE . ",";

    if (!empty($data1->FAX))
        $purchaseradd .=" Fax : " . $data1->FAX;

    $purch_country = str_replace("'", "\'", $data1->COUNTRY["VALUE"]);

    if ($purch_country == 'UK') {
        $purch_country = 'GB';
    }

    $purch_email = str_replace("'", "\'", $data1->E_MAILS->E_MAIL);
    $purch_url = str_replace("'", "\'", $xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL);
    $data2 = $data->FD_RESULT_DESIGN_CONTEST->RESULTS_CONTEST_RESULT_DESIGN_CONTEST->RESULT_CONTEST->AWARD_PRIZES->NAME_ADDRESS_WINNER->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME;
    $contractorname = str_replace("'", "\'", $data2->ORGANISATION->OFFICIALNAME);
    $cont_add = str_replace("'", "\'", $data2->ADDRESS . "," . $data2->TOWN . " " . $data2->POSTAL_CODE);
    $cont_country = str_replace("'", "\'", $data2->COUNTRY["VALUE"]);

    if (empty($cont_country)) {
        $cont_country = $purch_country;
    }

    if ($cont_country == 'UK') {
        $cont_country = 'GB';
    }
    $cont_email = str_replace("'", "\'", $data2->E_MAILS->E_MAIL);
    $cont_url = '';
    $project_location = str_replace("'", "\'", $data1->COUNTRY["VALUE"]);
    if (empty($project_location)) {
        $project_location = $cont_country;
    }
    if ($project_location == 'UK') {
        $project_location = 'GB';
    }
    $award_detail = '';

    //$award_detail .=

    foreach ($data->FD_RESULT_DESIGN_CONTEST->COMPLEMENTARY_INFORMATION_RESULT_DESIGN_CONTEST->ADDITIONAL_INFORMATION->P as $info) {
        $award_detail .= str_replace("'", "\'", $info . "<br/>");
    }

    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv) {
        $award_detail .= str_replace("'", "\'", $ocpv . ",");
    }

    $contract_val = str_replace("'", "\'", $data->FD_RESULT_DESIGN_CONTEST->RESULTS_CONTEST_RESULT_DESIGN_CONTEST->RESULT_CONTEST->PRIZE_VALUE);
    $contract_currency = str_replace("'", "\'", $data->FD_RESULT_DESIGN_CONTEST->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE['CURRENCY']);
    $sector = '';
    $sector .= str_replace("'", "\'", $data->FD_RESULT_DESIGN_CONTEST->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->DESCRIPTION_AWARD_NOTICE_INFORMATION->CPV->CPV_MAIN->CPV_CODE["CODE"]);



    foreach ($data->FD_RESULT_DESIGN_CONTEST->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE->DESCRIPTION_AWARD_NOTICE_INFORMATION->CPV->CPV_ADDITIONAL as $cpv_ad)
        $sector .= "," . str_replace("'", "\'", $cpv_ad->CPV_CODE['CODE']);
    if (!empty($sector)) {
        $seccode = getCpv($sector, $db2);
        //echo "secode".$seccode;
    }
    $cont_date = YmdChnage($xml->CODED_DATA_SECTION->CODIF_DATA->DS_DATE_DISPATCH);

    $contData = array(
        "short_descp" => "'$short_descp'",
        "ref_number" => "'$ref_number'",
        "purchasername" => "'$purchasername'",
        "purchaseradd" => "'$purchaseradd'",
        "purch_country" => "'$purch_country'",
        "purch_email" => "'$purch_email'",
        "purch_url" => "'$purch_url'",
        "contractorname" => "'$contractorname'",
        "cont_add" => "'$cont_add'",
        "cont_country" => "'$cont_country'",
        "cont_email" => "'$cont_email'",
        "cont_url" => "'$cont_url'",
        "project_location" => "'$project_location'",
        "award_detail" => "'$award_detail'",
        "contract_val" => "'$contract_val'",
        "contract_currency" => "'$contract_currency'",
        "sector" => "'$seccode'",
        "cont_date" => "'$cont_date'",
        "userid" => "0",
        "qc"=>1
    );
    $check = $db2->count('contract_award', "ref_number='$ref_number'");
    if ($check == 0) {
        //        $db->insert($contData, 'contract_award');
        //        $id = $db->getMax('contract_award', "id");
        //        $contData1 = array("id" => "$id");
        //        $contData1 = array_merge($contData1, $contData);
        //        $db2->insert($contData1, 'contract_award');

        $db2->insert($contData, 'contract_award');
    }
    // News


    $news_reception_id = "<b>Notice Reception Id: </b>" . $xml->TECHNICAL_SECTION->RECEPTION_ID;

    $deletion_date = YmdChnage($xml->TECHNICAL_SECTION->DELETION_DATE);

    $news_purchaser_details = "<b>Purchaser Details: </b>" . $purchasername . ", " . $purch_country . ", " . $purch_email . ", " . $purch_url;

    $news_contractor_details = "<b>Contractor Details: </b>" . $contractorname . ", " . $cont_add . ", " . $cont_country . ", " . $cont_email . ", " . $cont_url;

    $news_DELETION_DATE = "<b>Notice Deletion Date: </b>" . $deletion_date;
    $news_lang_avl = "<b>Available Language: </b>" . $xml->TECHNICAL_SECTION->FORM_LG_LIST;


    $news_contract_type = "<b>Contract Type: </b>" . $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE;
    $news_ref_num = "<b>Ref Num: </b>" . $ref_number;

    $news_contract_type = $xml->CODED_DATA_SECTION->CODIF_DATA->NC_CONTRACT_NATURE . " Contract - " . $ref_number;
    $news_directive_value = "<b>Directive Value: </b>" . $xml->CODED_DATA_SECTION->CODIF_DATA->DIRECTIVE['VALUE'];
    $news_cpv = "<b>CPV: </b>" . $sector;


    // Notice DATA
    $news_uri_doc_url = "";
    foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA as $noticedata_arr) {
        $news_NO_DOC_OJS = $noticedata_arr->NO_DOC_OJS;
        foreach ($noticedata_arr->URI_LIST->URI_DOC as $urival) {
            $news_uri_doc_url .= $urival . "<br/>";
        }
        $news_orglang = $noticedata_arr->LG_ORIG;
        $news_isocountry = $noticedata_arr->ISO_COUNTRY['VALUE'];
        $news_originalcpv = $noticedata_arr->ORIGINAL_CPV;
        $news_notice_value = $noticedata_arr->VALUES_LIST->VALUES->SINGLE_VALUE->VALUE;

        $news_notice_data = "<b>Notice Data</b>: " . $news_NO_DOC_OJS . ", " . $news_uri_doc_url . ", " . $news_orglang . ", " . $news_isocountry . ", " . $news_originalcpv . ", " . $news_notice_value;

        $news_notice_data = str_replace("'", "\'", $news_notice_data);
    }

    // CodIf Data

    foreach ($xml->CODED_DATA_SECTION->CODIF_DATA as $codifdata_arr) {

        $news_datedispatch = $codifdata_arr->DS_DATE_DISPATCH;
        $news_AA_AUTHORITY_TYPE = $codifdata_arr->AA_AUTHORITY_TYPE;
        $news_pr_proc = $codifdata_arr->PR_PROC;
        $news_rpregulation = $codifdata_arr->RP_REGULATION;
        $news_typebid = $codifdata_arr->TY_TYPE_BID;
        $news_acawardcrit = $codifdata_arr->AC_AWARD_CRIT;
        $news_mamaninactivity = $codifdata_arr->MA_MAIN_ACTIVITIES;
        $news_codifheading = $codifdata_arr->HEADING;

        $news_codifdata = "<b>CodIf Data</b>: " . $news_datedispatch . ", " . $news_AA_AUTHORITY_TYPE . ", " . $news_pr_proc . ", " .
                $news_rpregulation . ", " . $news_typebid . ", " . $news_acawardcrit . ", " . $news_mamaninactivity . ", " . $news_codifheading;
        $news_codifdata = str_replace("'", "\'", $news_codifdata);
    }

    // FormSection
    $i = 1;
    foreach ($xml->FORM_SECTION->RESULT_DESIGN_CONTEST as $compinfcontractarr) {



        foreach ($compinfcontractarr->FD_RESULT_DESIGN_CONTEST->CONTRACTING_ENTITY_RESULT_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_RESULT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE as $concessionval) {
            $news_officialname = $concessionval->ORGANISATION->OFFICIALNAME;
            $news_address = $concessionval->ADDRESS;
            $news_town = $concessionval->TOWN;
            $news_postalcode = $concessionval->POSTAL_CODE;
            $news_country_value = $concessionval->COUNTRY['VALUE'];
            $news_attention = $concessionval->ATTENTION;
            $news_email = $concessionval->E_MAILS->E_MAIL;
            $news_fax = $concessionval->FAX;
            //echo "<br/>_________________<br/>";

            $news_form_values = $news_officialname . ", " . $news_address . ", " . $news_town . ", " . $news_postalcode . ", " . $news_country_value . ", " . $news_attention . ", " . $news_email . ", " . $news_fax;
        }

        $news_objectdesgin = $compinfcontractarr->FD_RESULT_DESIGN_CONTEST->OBJECT_RESULT_DESIGN_CONTEST->TITLE_RESULT_DESIGN_CONTEST->P;

        $news_form_values = $news_form_values . ", " . $news_objectdesgin;


        foreach ($compinfcontractarr->FD_RESULT_DESIGN_CONTEST->PROCEDURES_RESULT_DESIGN_CONTEST as $resultdesign) {

            $news_noticenumoj = $resultdesign->PREVIOUS_PUBLICATION_OJ->NOTICE_NUMBER_OJ;
            $news_dateoj = $resultdesign->PREVIOUS_PUBLICATION_OJ->DATE_OJ->DAY . "-" . $resultdesign->PREVIOUS_PUBLICATION_OJ->DATE_OJ->MONTH . "-" . $resultdesign->PREVIOUS_PUBLICATION_OJ->DATE_OJ->YEAR;

            $news_procedureresult = $news_noticenumoj . ", " . $news_dateoj;
        }
        $news_responsible_data = "";
        foreach ($compinfcontractarr->FD_RESULT_DESIGN_CONTEST->RESULTS_CONTEST_RESULT_DESIGN_CONTEST->RESULT_CONTEST as $resultcontest) {

            $contestnum = $resultcontest->CONTEST_NUMBER;
            $contest_title = $resultcontest->CONTEST_TITLE->P;
            $contest_participants = $resultcontest->AWARD_PRIZES->PARTICIPANTS_NUMBER;
            $contest_part_foreign = $resultcontest->AWARD_PRIZES->FOREIGN_PARTICIPANTS_NUMBER;

            foreach ($resultcontest->AWARD_PRIZES->NAME_ADDRESS_WINNER->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME as $cdatacont) {



                $contest_orgofcialname = $cdatacont->ORGANISATION->OFFICIALNAME;
                $contest_nationalid = $cdatacont->ORGANISATION->NATIONALID;
                $contest_address = $cdatacont->ADDRESS;
                $contest_town = $cdatacont->TOWN;
                $contest_countryval = $cdatacont->COUNTRY['VALUE'];
                $contest_emails = $cdatacont->E_MAILS->E_MAIL;
                $contest_phone = $cdatacont->PHONE;

                $news_contest_result = $contest_orgofcialname . ", " . $contest_nationalid . ", " .
                        $contest_address . ", " . $contest_town . ", " . $contest_countryval . ", " . $contest_emails . ", " . $contest_phone;
            }

            $news_responsible_data .= $contestnum . ", " . $contest_title . ", " . $contest_participants .
                    ", " . $contest_part_foreign . ", " . $news_contest_result;
        }

        $news_eudata = "";
        foreach ($compinfcontractarr->FD_RESULT_DESIGN_CONTEST->COMPLEMENTARY_INFORMATION_RESULT_DESIGN_CONTEST as $res_desgin_cont) {


            $cont_euproject = $res_desgin_cont->RELATES_TO_EU_PROJECT_YES->P;
            $cont_additionalinfo = $res_desgin_cont->ADDITIONAL_INFORMATION->P;

            foreach ($res_desgin_cont->APPEAL_PROCEDURES->RESPONSIBLE_FOR_APPEAL_PROCEDURES->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME as $responsedata) {

                $contorg_ofcname = $responsedata->ORGANISATION->OFFICIALNAME;
                $contorg_address = $responsedata->ADDRESS;
                $contorg_town = $responsedata->TOWN;
                $contorg_postalcode = $responsedata->POSTAL_CODE;
                $contorg_country = $responsedata->COUNTRY['VALUE'];
                $contorg_phone = $responsedata->PHONE;
                $contorg_fax = $responsedata->FAX;

                $contdata = $contorg_ofcname . ", " . $contorg_address . ", " . $contorg_town . ", "
                        . $contorg_postalcode . ", " . $contorg_country . ", " . $contorg_phone . ", " . $contorg_fax;
            }
            $cont_eudate = $res_desgin_cont->NOTICE_DISPATCH_DATE->DAY . "-" . $res_desgin_cont->NOTICE_DISPATCH_DATE->MONTH . "-" . $res_desgin_cont->NOTICE_DISPATCH_DATE->YEAR;

            $news_eudata .= $cont_euproject . ", " . $cont_additionalinfo . ", " . $contdata . ", " . $cont_eudate . "<br/>";
        }




        $news_form_values_complete .= $news_form_values . ", " . $news_objectdesgin . ", " . $news_procedureresult . ", " . $news_responsible_data . ", " .
                $news_eudata;
    }

    $news_form_values_complete = str_replace("'", "\'", $news_form_values_complete);




    //$news_additioal_info = "Additional Information: " . $xml->FORM_SECTION->CONTRACT_AWARD_UTILITIES->FD_CONTRACT_AWARD_UTILITIES->COMPLEMENTARY_INFORMATION_CONTRACT_AWARD_UTILITIES->ADDITIONAL_INFORMATION['0'];

    $news_additonal_info = str_replace("'", "\'", $news_additonal_info);
    $news_additioal_info = "<b>Additional Information: </b>" . $news_additonal_info . "<br/>" . $news_mlti_doc;

    $news_details = $award_detail . "<br/>" . $news_purchaser_details . "<br/>" . $news_contractor_details . "<br/>" .
            $news_reception_id . "<br/>" . $news_DELETION_DATE . "<br/>" . $news_lang_avl . "<br/>" . $news_contract_type . "<br/>" .
            $news_directive_value . "<br/>" . $news_cpv . "<br/>" . $news_ref_num . "<br/>" . $news_notice_data . "<br/>" .
            $news_codifdata . "<br/>" . $news_additioal_info . "<br/>" . $news_form_values_complete;




    $source_url = $xml->CODED_DATA_SECTION->NOTICE_DATA->URI_LIST->URI_DOC;

        //   echo $news_details = str_replace("'", "\'", $news_details);


    $NewsData_Arr = array(
        "headline" => "'$news_head'",
        "details" => "'$news_details'",
        "source" => "'1068'",
        "source_url" => "'$source_url'",
        "sector" => "'$seccode'",
        "ca_news" => "'0'",
        "p_news" => "'1'",
        "user_id" => "0",
        "qc"=>1
    );



    $checknews = $db2->count('news', "headline='$news_head'");
    if ($checknews == 0) {
        $db2->insert($NewsData_Arr, 'news');
    }

    return "DONE";
}

?>