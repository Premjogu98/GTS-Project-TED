<?php
header('Content-Type: text/html; charset=UTF-8');
function YmdChnage($input){
	if(!empty($input)){
	$date = DateTime::createFromFormat('Ymd',$input);
	return $date->format('Y-m-d');
	}
}

function getCpv($cpvval,$db2){
    $cpvarray = explode(",", $cpvval);
    if(count($cpvarray) != 0 ){
        foreach ($cpvarray as $cpv){
            if(!empty($cpv)){
                $sectorData = getSector($cpv,$db2);
                foreach ($sectorData as $sector){
                    foreach ($sector as $sectorval){
                        //$secidArr[]=$sectorval['Sector_Id'];
                        $subsecidArr[]=$sectorval['Sub_Sector_Id'];
                    }
                }
            }
        }
    //$secidArr = array_unique($secidArr);
    $subsecidArr = array_unique($subsecidArr);
    $subSector = implode(",", $subsecidArr);
    return $subSector;
    }
}

function getSector($cpv,$db2){
    $rcpv[0] = substr($cpv, 0, 2);
    $rcpv[1] = substr($cpv, 0, 3);
    $rcpv[2] = substr($cpv, 0, 4);
    $rcpv[3] = substr($cpv, 0, 5);
    $rcpv[4] = substr($cpv, 0, 6);
    foreach ($rcpv as $rcp){
        $sql = "SELECT Sector_Id,Sub_Sector_Id FROM tbl_sector WHERE Sub_Sector_Id like '$rcp%'";
        $sector[] = $db2->getQuery($sql);
    }
    return $sector;
}

function getHtml($path,$db = NULL,$db2 = NULL){
	$xml = simplexml_load_file($path);
	//echo "<pre>";
	//print_r($xml);
	$flag = true;
        
	$TD_DOCUMENT_TYPE = $xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE;
        $COLL_OJ = $xml->CODED_DATA_SECTION->REF_OJS->COLL_OJ;
        $NO_OJ = $xml->CODED_DATA_SECTION->REF_OJS->NO_OJ;
        $DATE_PUB = $xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB;
        
        $tedId = $COLL_OJ."-".$NO_OJ;
        if(!empty($DATE_PUB))
            $pubDate = YmdChnage($DATE_PUB);
        else
            $pubDate = '';
	//echo $TD_DOCUMENT_TYPE."<br/>";
	//echo $xml['DOC_ID']."<br/>";
	//print_r($xml->FORM_SECTION);
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
	if(!empty($CONTRACT) && $TD_DOCUMENT_TYPE =='Contract notice'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $xml['DOC_ID']."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
                $html = contract($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($CONTRACT) && $TD_DOCUMENT_TYPE =='Dynamic purchasing system'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $xml['DOC_ID']."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = contract($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($DEFENCE) && $TD_DOCUMENT_TYPE =='Contract notice'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $xml['DOC_ID']."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = defence($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($DEFENCE) && $TD_DOCUMENT_TYPE =='Dynamic purchasing system'){
		echo $TD_DOCUMENT_TYPE."<br/>";
		echo $xml['DOC_ID']."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = defence($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($CONCESSION) && $TD_DOCUMENT_TYPE =='Public works concession'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $xml['DOC_ID']."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = concession($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($UTILITIES) && $TD_DOCUMENT_TYPE =='Contract notice'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $xml['DOC_ID']."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = utilities($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($UTILITIES) && $TD_DOCUMENT_TYPE =='Dynamic purchasing system'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $xml['DOC_ID']."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = utilities($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($CONTEST) && $TD_DOCUMENT_TYPE =='Design contest'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $xml['DOC_ID']."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = design_contest($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($PERIODIC) && $TD_DOCUMENT_TYPE =='Periodic indicative notice (PIN) without call for competition'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $xml['DOC_ID']."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = preiodic_indicative($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($QUALIFICATION_SYSTEM_WITH) && ($TD_DOCUMENT_TYPE =='Qualification system without call for competition' || $TD_DOCUMENT_TYPE =='Qualification system with call for competition' )){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $xml['DOC_ID']."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = qualification_system_with($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($PRIOR_INFORMATION) && $TD_DOCUMENT_TYPE =='Prior Information Notice'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = prior_information_notice($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($OTH_NOT) && $TD_DOCUMENT_TYPE =='Call for expressions of interest'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $TD_DOCUMENT_TYPE."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = call_expressions_interest($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($OTH_NOT) && $TD_DOCUMENT_TYPE =='Contract notice'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $TD_DOCUMENT_TYPE."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = call_expressions_interest($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($OTH_NOT) && $TD_DOCUMENT_TYPE =='Prior Information Notice'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $TD_DOCUMENT_TYPE."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = call_expressions_interest($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($SIMPLIFIED_CONTRACT) && $TD_DOCUMENT_TYPE =='Contract notice'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $TD_DOCUMENT_TYPE."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = simplefied_interest($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($PRIOR_INFORMATION_DEFENCE) && $TD_DOCUMENT_TYPE =='Prior Information Notice'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $TD_DOCUMENT_TYPE."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = prior_information_defence($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($PERIODIC_INDICATIVE_UTILITIES) && $TD_DOCUMENT_TYPE =='Prior Information Notice'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $TD_DOCUMENT_TYPE."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = prior_indicative_utilities($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($PRIOR_INFORMATION_MOVE) && $TD_DOCUMENT_TYPE =='Contract notice'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $TD_DOCUMENT_TYPE."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = prior_info_move($xml);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($CONTRACT_AWARD) && $TD_DOCUMENT_TYPE =='Contract award'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $TD_DOCUMENT_TYPE."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = contract_award($xml,$db,$db2);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($CONTRACT_AWARD_UTILITIES) && $TD_DOCUMENT_TYPE =='Contract award'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $TD_DOCUMENT_TYPE."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = contract_award_utili($xml,$db,$db2);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if(!empty($CONTRACT_AWARD_DEFENCE) && $TD_DOCUMENT_TYPE =='Contract award'){
		//echo $TD_DOCUMENT_TYPE."<br/>";
		//echo $TD_DOCUMENT_TYPE."<br/>";
		$flag = false;
		$file = $xml['DOC_ID'];
		$html = contract_award_defence($xml,$db,$db2);
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file");
		return $data;
	}
	if($flag){
		$file = $xml['DOC_ID'];
                $tedId = $COLL_OJ."-".$NO_OJ;
                $pubDate = YmdChnage($DATE_PUB);
		$html ='';
		$data = array("html"=>"$html","doc"=>"$TD_DOCUMENT_TYPE","fileId"=>"$file","tedId" => "$tedId","pubDate" => "$pubDate");
		return $data;
	}
}
function contract($xml){
	//echo $xml['DOC_ID'];
	$htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
	$htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE.'</td>
	</tr>';
	foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
		if($ml_ti['LG'] == 'EN'){
			$town = $ml_ti->TI_TOWN;
			$htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>".$ml_ti->TI_TEXT."</td></tr>";
		}
	}
	//echo $htmlData;
	foreach ($xml->FORM_SECTION->CONTRACT as $temp){
		if($temp["LG"] == 'EN'){
			$data = $temp;
		}
	}
	if(empty($data)){
		$data = $xml->FORM_SECTION->CONTRACT;
	}
	if(empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)){
		$Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
		$newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
	}else{
		$newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION,0,8));
	}
	//echo "<pre>";
	//print_r($xml);
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
	<td>'.$town.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>'.$data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>'.$data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>'.$data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>'.$data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>'.$data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>'.$data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->PHONE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>'.$data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>'.$data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->FAX.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>'.$data["LG"].'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
	<td>'.$data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"].'</td>
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
	<td>'.$newDate.'</td>
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
	<td>'.$data->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->CPV->CPV_MAIN->CPV_CODE["CODE"];
	foreach ($data->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->CPV->CPV_ADDITIONAL as $cpv_ad ) $htmlData .= ",".$cpv_ad->CPV_CODE['CODE'];
	$htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>'.$data->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->LOCATION_NUTS->NUTS["CODE"].'</td>
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
	foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv){
		$htmlData .= $ocpv.".<br/>";
	}
	$htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
	foreach ( $data->FD_CONTRACT->OBJECT_CONTRACT_INFORMATION->DESCRIPTION_CONTRACT_INFORMATION->SHORT_CONTRACT_DESCRIPTION->P as $shrtDesc) {
		$htmlData .="$shrtDesc<br/>";
	}
	$htmlData .='</td></tr>
	</table></body></html>';
	//echo $htmlData;
	return $htmlData;
}

function defence($xml){
	$htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
	$htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE.'</td>
	</tr>';
	foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
		if($ml_ti['LG'] == 'EN'){
			$town = $ml_ti->TI_TOWN;
			$htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>".$ml_ti->TI_TEXT."</td></tr>";
		}
	}
	//echo $htmlData;
	foreach ($xml->FORM_SECTION->CONTRACT_DEFENCE as $temp){
		if($temp["LG"] == 'EN'){
			$data = $temp;
		}
	}
	if(empty($data)){
		$data = $xml->FORM_SECTION->CONTRACT_DEFENCE;
	}
	if(empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)){
		$Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
		$newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
	}else{
		$newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION,0,8));
	}
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
	<td>'.$town.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>'.$data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>'.$data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>'.$data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>'.$data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>'.$data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>'.$data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->PHONE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>'.$data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>'.$data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->FAX.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>'.$data["LG"].'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
	<td>'.$data->FD_CONTRACT_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"].'</td>
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
	<td>'.$newDate.'</td>
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
	<td>'.$data->FD_CONTRACT_DEFENCE->OBJECT_CONTRACT_INFORMATION_DEFENCE->DESCRIPTION_CONTRACT_INFORMATION_DEFENCE->CPV->CPV_MAIN->CPV_CODE["CODE"];
	foreach ($data->FD_CONTRACT_DEFENCE->OBJECT_CONTRACT_INFORMATION_DEFENCE->DESCRIPTION_CONTRACT_INFORMATION_DEFENCE->CPV->CPV_ADDITIONAL as $cpv_ad ) $htmlData .= ",".$cpv_ad->CPV_CODE['CODE'];
	$htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>'.$data->FD_CONTRACT_DEFENCE->OBJECT_CONTRACT_INFORMATION_DEFENCE->DESCRIPTION_CONTRACT_INFORMATION_DEFENCE->LOCATION_NUTS->NUTS["CODE"].'</td>
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
	foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv){
		$htmlData .= $ocpv.".<br/>";
	}
	$htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>'.$data->FD_CONTRACT_DEFENCE->OBJECT_CONTRACT_INFORMATION_DEFENCE->DESCRIPTION_CONTRACT_INFORMATION_DEFENCE->SHORT_CONTRACT_DESCRIPTION->P.'</td>
	</tr>
	</table></body></html>';
	
	$htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
	foreach ( $data->FD_CONTRACT_DEFENCE->OBJECT_CONTRACT_INFORMATION_DEFENCE->DESCRIPTION_CONTRACT_INFORMATION_DEFENCE->SHORT_CONTRACT_DESCRIPTION->P as $shrtDesc) {
		$htmlData .="$shrtDesc<br/>";
	}
	$htmlData .='</td></tr>
	</table></body></html>';
	return $htmlData;
}
function concession($xml){
	$htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
	
	$htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE.'</td>
	</tr>';
	foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
		if($ml_ti['LG'] == 'EN'){
			$town = $ml_ti->TI_TOWN;
			$htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>".$ml_ti->TI_TEXT."</td></tr>";
		}
	}
	foreach ($xml->FORM_SECTION->CONCESSION as $temp){
		if($temp["LG"] == 'EN'){
			$data = $temp;
		}
	}
	if(empty($data)){
		$data = $xml->FORM_SECTION->CONCESSION;
	}
	if(empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)){
		$Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
		$newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
	}else{
		$newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION,0,8));
	}
	//echo "<pre>";
	//print_r($data);
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
	<td>'.$town.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>'.$data->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>'.$data->FD_CONTRACT->CONTRACTING_AUTHORITY_INFORMATION->NAME_ADDRESSES_CONTACT_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>'.$data->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>'.$data->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>'.$data->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>'.$data->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->PHONE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>'.$data->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>'.$data->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->FAX.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>'.$data["LG"].'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
	<td>'.$data->FD_CONCESSION->AUTHORITY_CONCESSION->NAME_ADDRESSES_CONTACT_CONCESSION->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"].'</td>
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
	<td>'.$newDate.'</td>
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
	<td>'.$data->FD_CONCESSION->OBJECT_CONCESSION->DESCRIPTION_CONCESSION->CPV->CPV_MAIN->CPV_CODE["CODE"];
	foreach ($data->FD_CONCESSION->OBJECT_CONCESSION->DESCRIPTION_CONCESSION->CPV->CPV_ADDITIONAL as $cpv_ad ) $htmlData .= ",".$cpv_ad->CPV_CODE['CODE'];
	$htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>'.$data->FD_CONCESSION->OBJECT_CONCESSION->DESCRIPTION_CONCESSION->F10_TYPE_OF_WORKS_CONTRACT->NUTS["CODE"].'</td>
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
	foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv){
		$htmlData .= $ocpv.".<br/>";
	}
	$htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
	foreach ( $data->FD_CONCESSION->OBJECT_CONCESSION->DESCRIPTION_CONCESSION->DESCRIPTION_OF_CONTRACT->P as $shrtDesc) {$htmlData .="$shrtDesc<br/>";}
	$htmlData .='</td></tr>
	</table></body></html>';
	return $htmlData;
}
function utilities($xml){
	//echo $xml['DOC_ID'];
	$htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
	$htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE.'</td>
	</tr>';
	foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
		if($ml_ti['LG'] == 'EN'){
			$town = $ml_ti->TI_TOWN;
			$htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>".$ml_ti->TI_CY." ".$ml_ti->TI_TOWN." ".$ml_ti->TI_TEXT."</td></tr>";
		}
	}
	//echo $htmlData;
	foreach ($xml->FORM_SECTION->CONTRACT_UTILITIES as $temp){
		if($temp["LG"] == 'EN'){
			$data = $temp;
		}
	}
	if(empty($data)){
		$data = $xml->FORM_SECTION->CONTRACT_UTILITIES;
	}
	if(empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)){
		$Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
		$newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
	}else{
		$newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION,0,8));
	}
	//echo "<pre>";
	//print_r($xml);
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
	<td>'.$town.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>'.$data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>'.$data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>'.$data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>'.$data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>'.$data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>'.$data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->PHONE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>'.$data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>'.$data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->FAX.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>'.$data["LG"].'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
	<td>'.$data->FD_CONTRACT_UTILITIES->CONTRACTING_AUTHORITY_INFO->NAME_ADDRESSES_CONTACT_CONTRACT_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"].'</td>
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
	<td>'.$newDate.'</td>
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
	<td>'.$data->FD_CONTRACT_UTILITIES->OBJECT_CONTRACT_INFORMATION_CONTRACT_UTILITIES->CONTRACT_OBJECT_DESCRIPTION->CPV->CPV_MAIN->CPV_CODE["CODE"];
	foreach ($data->FD_CONTRACT_UTILITIES->OBJECT_CONTRACT_INFORMATION_CONTRACT_UTILITIES->CONTRACT_OBJECT_DESCRIPTION->CPV->CPV_ADDITIONAL as $cpv_ad ) $htmlData .= ",".$cpv_ad->CPV_CODE['CODE'];
	$htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>'.$data->FD_CONTRACT_UTILITIES->OBJECT_CONTRACT_INFORMATION_CONTRACT_UTILITIES->CONTRACT_OBJECT_DESCRIPTION->LOCATION_NUTS->NUTS["CODE"].'</td>
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
	foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv){
		$htmlData .= $ocpv.".<br/>";
	}
	$htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>'.$data->FD_CONTRACT_UTILITIES->OBJECT_CONTRACT_INFORMATION_CONTRACT_UTILITIES->CONTRACT_OBJECT_DESCRIPTION->SHORT_CONTRACT_DESCRIPTION->P.'</td>
	</tr>
	</table></body></html>';
	return $htmlData;
}

function design_contest($xml){
	$htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
	$htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE.'</td>
	</tr>';
	foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
		if($ml_ti['LG'] == 'EN'){
			$town = $ml_ti->TI_TOWN;
			$htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>".$ml_ti->TI_TEXT."</td></tr>";
		}
	}
	//echo $htmlData;
	foreach ($xml->FORM_SECTION->DESIGN_CONTEST as $temp){
		if($temp["LG"] == 'EN'){
			$data = $temp;
		}
	}
	if(empty($data)){
		$data = $xml->FORM_SECTION->DESIGN_CONTEST;
	}
	if(empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)){
		$Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
		$newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
	}else{
		$newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION,0,8));
	}
	//echo "<pre>";
	//print_r($xml);
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
	<td>'.$town.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>'.$data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>'.$data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>'.$data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>'.$data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>'.$data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>'.$data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->PHONE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>'.$data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>'.$data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->FAX.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>'.$data["LG"].'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
	<td>'.$data->FD_DESIGN_CONTEST->AUTHORITY_ENTITY_DESIGN_CONTEST->NAME_ADDRESSES_CONTACT_DESIGN_CONTEST->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"].'</td>
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
	<td>'.$newDate.'</td>
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
	<td>'.$data->FD_DESIGN_CONTEST->OBJECT_DESIGN_CONTEST->CPV->CPV_MAIN->CPV_CODE["CODE"];
	foreach ($data->FD_DESIGN_CONTEST->OBJECT_DESIGN_CONTEST->CPV->CPV_ADDITIONAL as $cpv_ad ) $htmlData .= ",".$cpv_ad->CPV_CODE['CODE'];
	$htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>'.$data->FD_DESIGN_CONTEST->OBJECT_DESIGN_CONTEST->LOCATION_NUTS->NUTS["CODE"].'</td>
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
	foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv){
		$htmlData .= $ocpv.".<br/>";
	}
	$htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>'.$data->FD_DESIGN_CONTEST->OBJECT_DESIGN_CONTEST->SHORT_DESCRIPTION_CONTRACT->P.'</td>
	</tr>
	</table></body></html>';
	return $htmlData;
}

function preiodic_indicative($xml){
	$htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
	$htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE.'</td>
	</tr>';
	foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
		if($ml_ti['LG'] == 'EN'){
			$town = $ml_ti->TI_TOWN;
			$htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>".$ml_ti->TI_TEXT."</td></tr>";
		}
	}
	//echo $htmlData;
	foreach ($xml->FORM_SECTION->PERIODIC_INDICATIVE_UTILITIES as $temp){
		if($temp["LG"] == 'EN'){
			$data = $temp;
		}
	}
	if(empty($data)){
		$data = $xml->FORM_SECTION->PERIODIC_INDICATIVE_UTILITIES;
	}
	$Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
	$ddate = $xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION;
	if(empty($ddate)){
		$newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
	}else{
		$newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION,0,8));
	}
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
	<td>'.$town.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->PHONE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->FAX.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>'.$data["LG"].'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"].'</td>
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
	<td>'.$newDate.'</td>
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
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->OBJECT_CONTRACT_PERIODIC_INDICATIVE->CPV->CPV_MAIN->CPV_CODE["CODE"];
	foreach ($data->FD_PERIODIC_INDICATIVE_UTILITIES->OBJECT_CONTRACT_PERIODIC_INDICATIVE->CPV->CPV_ADDITIONAL as $cpv_ad ) $htmlData .= ",".$cpv_ad->CPV_CODE['CODE'];
	$htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->OBJECT_CONTRACT_PERIODIC_INDICATIVE->LOCATION_NUTS->NUTS["CODE"].'</td>
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
	foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv){
		$htmlData .= $ocpv.".<br/>";
	}
	$htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
	foreach ( $data->FD_PERIODIC_INDICATIVE_UTILITIES->OBJECT_CONTRACT_PERIODIC_INDICATIVE->DESCRIPTION_OF_CONTRACT->P as $shrtDesc) {$htmlData .="$shrtDesc<br/>";}
	$htmlData .='</td></tr>
	</table></body></html>';
	return $htmlData;
}
function qualification_system_with($xml){
	$htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
	$htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE.'</td>
	</tr>';
	foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
		if($ml_ti['LG'] == 'EN'){
			$town = $ml_ti->TI_TOWN;
			$htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>".$ml_ti->TI_TEXT."</td></tr>";
		}
	}
	//echo $htmlData;
	foreach ($xml->FORM_SECTION->QUALIFICATION_SYSTEM_UTILITIES as $temp){
		if($temp["LG"] == 'EN'){
			$data = $temp;
		}
	}
	if(empty($data)){
		$data = $xml->FORM_SECTION->QUALIFICATION_SYSTEM_UTILITIES;
	}
	
	if(empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)){
		$Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
		$newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
	}else{
		$newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION,0,8));
	}
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
	<td>'.$town.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>'.$data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>'.$data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>'.$data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>'.$data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>'.$data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>'.$data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->PHONE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>'.$data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>'.$data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->FAX.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>'.$data["LG"].'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
	<td>'.$data->FD_QUALIFICATION_SYSTEM_UTILITIES->CONTRACTING_ENTITY_QUALIFICATION_SYSTEM->NAME_ADDRESSES_CONTACT_QUALIFICATION_SYSTEM_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"].'</td>
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
	<td>'.$newDate.'</td>
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
	<td>'.$data->FD_QUALIFICATION_SYSTEM_UTILITIES->OBJECT_QUALIFICATION_SYSTEM->CPV->CPV_MAIN->CPV_CODE["CODE"];
	foreach ($data->FD_QUALIFICATION_SYSTEM_UTILITIES->OBJECT_QUALIFICATION_SYSTEM->CPV->CPV_ADDITIONAL as $cpv_ad ) $htmlData .= ",".$cpv_ad->CPV_CODE['CODE'];
	$htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>'.$data->FD_QUALIFICATION_SYSTEM_UTILITIES->OBJECT_QUALIFICATION_SYSTEM->LOCATION_NUTS->NUTS["CODE"].'</td>
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
	foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv){
		$htmlData .= $ocpv.".<br/>";
	}
	$htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
	foreach ( $data->FD_QUALIFICATION_SYSTEM_UTILITIES->OBJECT_QUALIFICATION_SYSTEM->DESCRIPTION->P as $shrtDesc) {$htmlData .="$shrtDesc<br/>";}
	$htmlData .='</td></tr>
	</table></body></html>';
	return $htmlData;
}

function prior_information_notice($xml){
	$htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
	$htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE.'</td>
	</tr>';
	foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
		if($ml_ti['LG'] == 'EN'){
			$town = $ml_ti->TI_TOWN;
			$htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>".$ml_ti->TI_TEXT."</td></tr>";
		}
	}
	//echo $htmlData;
	foreach ($xml->FORM_SECTION->PRIOR_INFORMATION as $temp){
		if($temp["LG"] == 'EN'){
			$data = $temp;
		}
	}
	if(empty($data)){
		$data = $xml->FORM_SECTION->PRIOR_INFORMATION;
	}
	if(empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)){
		$Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
		$newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
	}else{
		$newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION,0,8));
	}
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
	<td>'.$town.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>'.$data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>'.$data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>'.$data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>'.$data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>'.$data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>'.$data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->PHONE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>'.$data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>'.$data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->FAX.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>'.$data["LG"].'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
	<td>'.$data->FD_PRIOR_INFORMATION->AUTHORITY_PRIOR_INFORMATION->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"].'</td>
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
	<td>'.$newDate.'</td>
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
	<td style="font-weight: bold;">CPV_CODE</td>';
	if(!empty($data->FD_PRIOR_INFORMATION->OBJECT_SUPPLIES_SERVICES_PRIOR_INFORMATION)){
		$htmlData .='<td>'.$data->FD_PRIOR_INFORMATION->OBJECT_SUPPLIES_SERVICES_PRIOR_INFORMATION->OBJECT_SUPPLY_SERVICE_PRIOR_INFORMATION->CPV->CPV_MAIN->CPV_CODE["CODE"];
		foreach ($data->FD_PRIOR_INFORMATION->OBJECT_SUPPLIES_SERVICES_PRIOR_INFORMATION->OBJECT_SUPPLY_SERVICE_PRIOR_INFORMATION->CPV->CPV_ADDITIONAL as $cpv_ad ) $htmlData .= ",".$cpv_ad->CPV_CODE['CODE'];
	}elseif(!empty($data->FD_PRIOR_INFORMATION->OBJECT_WORKS_PRIOR_INFORMATION)){
		$htmlData .='<td>'.$data->FD_PRIOR_INFORMATION->OBJECT_WORKS_PRIOR_INFORMATION->CPV->CPV_MAIN->CPV_CODE["CODE"];
		foreach ($data->FD_PRIOR_INFORMATION->OBJECT_WORKS_PRIOR_INFORMATION->CPV->CPV_ADDITIONAL as $cpv_ad ) $htmlData .= ",".$cpv_ad->CPV_CODE['CODE'];
	}
	$htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>'.$data->FD_PRIOR_INFORMATION->OBJECT_SUPPLIES_SERVICES_PRIOR_INFORMATION->OBJECT_SUPPLY_SERVICE_PRIOR_INFORMATION->TYPE_CONTRACT_PLACE_DELIVERY->SITE_OR_LOCATION->NUTS["CODE"].'</td>
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
	foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv){
		$htmlData .= $ocpv.".<br/>";
	}
		$htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
	foreach ( $data->FD_PRIOR_INFORMATION->OBJECT_SUPPLIES_SERVICES_PRIOR_INFORMATION->OBJECT_SUPPLY_SERVICE_PRIOR_INFORMATION->QUANTITY_SCOPE_PRIOR_INFORMATION->TOTAL_QUANTITY_OR_SCOPE->P as $shrtDesc) {$htmlData .="$shrtDesc<br/>";}
	$htmlData .='</td></tr>
	</table></body></html>';
	return $htmlData;
}

function call_expressions_interest($xml){
	$htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
	$htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE.'</td>
	</tr>';
	foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
		if($ml_ti['LG'] == 'EN'){
			$town= $ml_ti->TI_TOWN;
			$htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>".$ml_ti->TI_TEXT."</td></tr>";
		}
	}
	//echo $htmlData;
	foreach ($xml->FORM_SECTION->OTH_NOT as $temp){
		if($temp["LG"] == 'EN'){
			$data = $temp;
		}
	}
	if(empty($data)){
		$data = $xml->FORM_SECTION->OTH_NOT;
	}
	///echo "<pre>";
	//print_r($xml);
	//print_r($data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR[2]);
	if(empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)){
		$Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
		$newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
	}else{
		$newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION,0,8));
	}
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
	<td>'.$town.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>'.$data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR->TXT_MARK->P->ADDRESS_NOT_STRUCT->ORGANISATION.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>'.$data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR->TXT_MARK->P->ADDRESS_NOT_STRUCT->ADDRESS.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>'.$data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR->TXT_MARK->P->ADDRESS_NOT_STRUCT->POSTAL_CODE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>'.$data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR->TXT_MARK->P->ADDRESS_NOT_STRUCT->CONTACT_POINT .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>'.$data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR->TXT_MARK->P->ADDRESS_NOT_STRUCT->ATTENTION .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>'.$data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR->TXT_MARK->P->ADDRESS_NOT_STRUCT->PHONE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>'.$data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR->TXT_MARK->P->ADDRESS_NOT_STRUCT->E_MAIL .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>'.$data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR->TXT_MARK->P->ADDRESS_NOT_STRUCT->FAX.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>'.$data["LG"].'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
	<td>'.$xml->CODED_DATA_SECTION->NOTICE_DATA->ISO_COUNTRY["VALUE"].'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">AA_AUTHORITY_TYPE</td>
	<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->AA_AUTHORITY_TYPE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DS_DATE_DISPATCH</td>
	<td>'.$newDate.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">DT_DATE_FOR_SUBMISSION</td>
	<td>'.$newDate.'</td>
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
	<td>';
	foreach ($data->FD_OTH_NOT->CONTENTS->MARK_LIST->MLI_OCCUR[2]->TXT_MARK->P as $cpv){
		if(!empty($cpv) && is_numeric(substr($cpv, 0, 8))){
			$htmlData .= substr($cpv, 0, 8).",";
		}
	}
	$htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>'.$data->FD_OTH_NOT->OBJECT_DESIGN_CONTEST->LOCATION_NUTS->NUTS["CODE"].'</td>
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
	foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv){
		$htmlData .= $ocpv.".<br/>";
	}
	$htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td></td>
	</tr>
	</table></body></html>';
	return $htmlData;
}

function simplefied_interest($xml){
	$htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
	$htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE.'</td>
	</tr>';
	foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
		if($ml_ti['LG'] == 'EN'){
			$town = $ml_ti->TI_TOWN;
			$htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>".$ml_ti->TI_TEXT."</td></tr>";
		}
	}
	//echo $htmlData;
	foreach ($xml->FORM_SECTION->SIMPLIFIED_CONTRACT as $temp){
		if($temp["LG"] == 'EN'){
			$data = $temp;
		}
	}
	if(empty($data)){
		$data = $xml->FORM_SECTION->SIMPLIFIED_CONTRACT;
	}
	if(empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)){
		$Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
		$newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
	}else{
		$newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION,0,8));
	}
	//echo "<pre>";
	//print_r($xml);
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
	<td>'.$town.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>'.$data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>'.$data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>'.$data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>'.$data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>'.$data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>'.$data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->PHONE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>'.$data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>'.$data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->FAX.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>'.$data["LG"].'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
	<td>'.$data->FD_SIMPLIFIED_CONTRACT->AUTHORITY_ENTITY_SIMPLIFIED_CONTRACT_NOTICE->NAME_ADDRESSES_CONTACT_SIMPLIFIED_CONTRACT->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"].'</td>
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
	<td>'.$newDate.'</td>
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
	<td>'.$data->FD_SIMPLIFIED_CONTRACT->OBJECT_SIMPLIFIED_CONTRACT_NOTICE->CPV->CPV_MAIN->CPV_CODE["CODE"];
	foreach ($data->FD_SIMPLIFIED_CONTRACT->OBJECT_SIMPLIFIED_CONTRACT_NOTICE->CPV->CPV_ADDITIONAL as $cpv_ad ) $htmlData .= ",".$cpv_ad->CPV_CODE['CODE'];
	
	$htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>'.$data->FD_SIMPLIFIED_CONTRACT->OBJECT_SIMPLIFIED_CONTRACT_NOTICE->LOCATION_NUTS->NUTS["CODE"].'</td>
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
	foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv){
		$htmlData .= $ocpv.".<br/>";
	}
	$htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
	foreach ( $data->FD_SIMPLIFIED_CONTRACT->OBJECT_SIMPLIFIED_CONTRACT_NOTICE->SHORT_DESCRIPTION_CONTRACT->P as $shrtDesc) {$htmlData .="$shrtDesc<br/>";}
	$htmlData .='</td></tr>
	</table></body></html>';
	return $htmlData;
}

function prior_information_defence($xml){
	$htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
	$htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE.'</td>
	</tr>';
	foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
		if($ml_ti['LG'] == 'EN'){
			$town = $ml_ti->TI_TOWN;
			$htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>".$ml_ti->TI_TEXT."</td></tr>";
		}
	}
	//echo $htmlData;
	foreach ($xml->FORM_SECTION->PRIOR_INFORMATION_DEFENCE as $temp){
		if($temp["LG"] == 'EN'){
			$data = $temp;
		}
	}
	if(empty($data)){
		$data = $xml->FORM_SECTION->PRIOR_INFORMATION_DEFENCE;
	}
	
	if(empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)){
		$Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
		$newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
	}else{
		$newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION,0,8));
	}
	//echo "<pre>";
	//print_r($xml);
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
	<td>'.$town.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>'.$data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>'.$data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>'.$data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>'.$data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>'.$data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>'.$data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->PHONE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>'.$data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>'.$data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->FAX.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>'.$data["LG"].'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
	<td>'.$data->FD_PRIOR_INFORMATION_DEFENCE->AUTHORITY_PRIOR_INFORMATION_DEFENCE->NAME_ADDRESSES_CONTACT_PRIOR_INFORMATION->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"].'</td>
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
	<td>'.$newDate.'</td>
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
	<td>'.$data->FD_PRIOR_INFORMATION_DEFENCE->OBJECT_WORKS_SUPPLIES_SERVICES_PRIOR_INFORMATION->CPV->CPV_MAIN->CPV_CODE["CODE"];
	foreach ($data->FD_PRIOR_INFORMATION_DEFENCE->OBJECT_WORKS_SUPPLIES_SERVICES_PRIOR_INFORMATION->CPV->CPV_ADDITIONAL as $cpv_ad ) $htmlData .= ",".$cpv_ad->CPV_CODE['CODE'];
	
	$htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>'.$data->FD_PRIOR_INFORMATION_DEFENCE->OBJECT_WORKS_SUPPLIES_SERVICES_PRIOR_INFORMATION->TYPE_CONTRACT_PLACE_DELIVERY_DEFENCE->SITE_OR_LOCATION->NUTS["CODE"].'</td>
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
	foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv){
		$htmlData .= $ocpv.".<br/>";
	}
	$htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
	foreach ( $data->FD_PRIOR_INFORMATION_DEFENCE->OBJECT_WORKS_SUPPLIES_SERVICES_PRIOR_INFORMATION->QUANTITY_SCOPE_WORKS_DEFENCE->TOTAL_QUANTITY_OR_SCOPE->P as $shrtDesc) {$htmlData .="$shrtDesc<br/>";}
	$htmlData .='</td></tr>
	</table></body></html>';
	return $htmlData;
}

function prior_indicative_utilities($xml){
	$htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
	$htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE.'</td>
	</tr>';
	foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
		if($ml_ti['LG'] == 'EN'){
			$town = $ml_ti->TI_TOWN;
			$htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>".$ml_ti->TI_TEXT."</td></tr>";
		}
	}
	//echo $htmlData;
	foreach ($xml->FORM_SECTION->PERIODIC_INDICATIVE_UTILITIES as $temp){
		if($temp["LG"] == 'EN'){
			$data = $temp;
		}
	}
	if(empty($data)){
		$data = $xml->FORM_SECTION->PERIODIC_INDICATIVE_UTILITIES;
	}
	
	if(empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)){
		$Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
		$newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
	}else{
		$newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION,0,8));
	}
	//echo "<pre>";
	//print_r($xml);
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
	<td>'.$town.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->PHONE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->FAX.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>'.$data["LG"].'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->AUTHORITY_PERIODIC_INDICATIVE->NAME_ADDRESSES_CONTACT_PERIODIC_INDICATIVE_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"].'</td>
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
	<td>'.$newDate.'</td>
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
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->OBJECT_CONTRACT_PERIODIC_INDICATIVE->CPV->CPV_MAIN->CPV_CODE["CODE"];
	foreach ($data->FD_PERIODIC_INDICATIVE_UTILITIES->OBJECT_CONTRACT_PERIODIC_INDICATIVE->CPV->CPV_ADDITIONAL as $cpv_ad ) $htmlData .= ",".$cpv_ad->CPV_CODE['CODE'];
	
	$htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>'.$data->FD_PERIODIC_INDICATIVE_UTILITIES->OBJECT_CONTRACT_PERIODIC_INDICATIVE->TYPE_CONTRACT_PLACE_DELIVERY_DEFENCE->SITE_OR_LOCATION->NUTS["CODE"].'</td>
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
	foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv){
		$htmlData .= $ocpv.".<br/>";
	}
	$htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
	foreach ( $data->FD_PERIODIC_INDICATIVE_UTILITIES->OBJECT_CONTRACT_PERIODIC_INDICATIVE->DESCRIPTION_OF_CONTRACT->P as $shrtDesc) {$htmlData .="$shrtDesc<br/>";}
	$htmlData .='</td></tr>
	</table></body></html>';
	return $htmlData;
}

function prior_info_move($xml){
	$htmlData = '<html><head><meta charset="UTF-8"></head><body><table border="1">';
	$htmlData .='
	<tr>
	<td style="font-weight: bold;">TD_DOCUMENT_TYPE</td>
	<td>'.$xml->CODED_DATA_SECTION->CODIF_DATA->TD_DOCUMENT_TYPE.'</td>
	</tr>';
	foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
		if($ml_ti['LG'] == 'EN'){
			$town = $ml_ti->TI_TOWN;
			$htmlData .= "<tr><td style='font-weight: bold;'>ML_TI_DOC</td><td>".$ml_ti->TI_TEXT."</td></tr>";
		}
	}
	//echo $htmlData;
	foreach ($xml->FORM_SECTION->PRIOR_INFORMATION_MOVE as $temp){
		if($temp["LG"] == 'EN'){
			$data = $temp;
		}
	}
	if(empty($data)){
		$data = $xml->FORM_SECTION->PRIOR_INFORMATION_MOVE;
	}
	if(empty($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION)){
		$Date = YmdChnage($xml->CODED_DATA_SECTION->REF_OJS->DATE_PUB);
		$newDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($Date)) . " + 1 year"));
	}else{
		$newDate = YmdChnage(substr($xml->CODED_DATA_SECTION->CODIF_DATA->DT_DATE_FOR_SUBMISSION,0,8));
	}
	//echo "<pre>";
	//print_r($xml);
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
	<td>'.$town.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">OFFICIALNAME</td>
	<td>'.$data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->ORGANISATION->OFFICIALNAME .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ADDRESS</td>
	<td>'.$data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->ADDRESS.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">POSTAL_CODE</td>
	<td>'.$data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->POSTAL_CODE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">CONTACT_POINT</td>
	<td>'.$data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->CONTACT_POINT .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">ATTENTION</td>
	<td>'.$data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->ATTENTION .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">PHONE</td>
	<td>'.$data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->PHONE.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">E_MAIL</td>
	<td>'.$data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->E_MAILS->E_MAIL .'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">FAX</td>
	<td>'.$data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->FAX.'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">LG</td>
	<td>'.$data["LG"].'</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">COUNTRY</td>
	<td>'.$data->FD_PRIOR_INFORMATION_MOVE->AUTHORITY_PI_MOVE->NAME_ADDRESSES_CONTACT_MOVE->CA_CE_CONCESSIONAIRE_PROFILE->COUNTRY["VALUE"].'</td>
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
	<td>'.$newDate.'</td>
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
	<td>'.$data->FD_PRIOR_INFORMATION_MOVE->OBJECT_PI_MOVE->DESCRIPTION_PI_MOVE->CPV->CPV_MAIN->CPV_CODE["CODE"];
	foreach ($data->FD_PRIOR_INFORMATION_MOVE->OBJECT_PI_MOVE->DESCRIPTION_PI_MOVE->CPV->CPV_ADDITIONAL as $cpv_ad ) $htmlData .= ",".$cpv_ad->CPV_CODE['CODE'];
	
	$htmlData .='
	</td>
	</tr>
	<tr>
	<td style="font-weight: bold;">NUTS</td>
	<td>'.$data->FD_PRIOR_INFORMATION_MOVE->OBJECT_PI_MOVE->DESCRIPTION_PI_MOVE->LOCATION_NUTS->NUTS["CODE"].'</td>
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
	foreach ($xml->CODED_DATA_SECTION->NOTICE_DATA->ORIGINAL_CPV as $ocpv){
		$htmlData .= $ocpv.".<br/>";
	}
	$htmlData .= '</td></tr>
	<tr>
	<td style="font-weight: bold;">SHORT_DESCRIPTION</td>
	<td>';
	foreach ( $data->FD_PRIOR_INFORMATION_MOVE->OBJECT_PI_MOVE->DESCRIPTION_PI_MOVE->SHORT_CONTRACT_DESCRIPTION->P as $shrtDesc) {$htmlData .="$shrtDesc<br/>";}
	$htmlData .='</td></tr>
	</table></body></html>';
	return $htmlData;
}

function contract_award($xml ,$db = NULL,$db2 = NULL){
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
	
	$purchaseradd = str_replace("'","\'",$data1->ADDRESS.",".$data1->TOWN ." ". $data1->POSTAL_CODE.",");
	
	if(!empty($data1->PHONE))
		$purchaseradd .=" Tel : ".$data1->PHONE.",";
	
	if(!empty($data1->FAX))
		$purchaseradd .=" Fax : ".$data1->FAX;
	
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
	
        if(!empty($sector)){
            $seccode = getCpv($sector, $db2);
            //echo "secode".$seccode;
        }
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
			"sector"				=>		"'$seccode'",
			"date_c"				=>		"'$cont_date'",
			"userid"				=>		"0",
			"qc"					=>		"1"
	);
       // echo "<pre>";
        //print_r($contData);
	$check = $db->count('contract_award',"ref_number='$ref_number'");
	if($check == 0){
		$db->insert($contData,'contract_award');
		$id = $db->getMax('contract_award',"id");	
		$contData1 = array("id"=>"$id");
		$contData1 = array_merge($contData1,$contData);
		$db2->insert($contData1,'contract_award');
	}
	return "DONE";
}

function contract_award_utili($xml,$db = NULL,$db2 = NULL){
	foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
		if($ml_ti['LG'] == 'EN'){
			$short_descp = str_replace("'","\'",$ml_ti->TI_TEXT);
		}
	}
	foreach ($xml->FORM_SECTION->CONTRACT_AWARD_UTILITIES as $temp){
		if($temp["LG"] == 'EN'){
			$data = $temp;
		}
	}
	if(empty($data)){
		$data = $xml->FORM_SECTION->CONTRACT_AWARD_UTILITIES;
	}
	$ref_number = str_replace("'","\'",$xml['DOC_ID']);
	$data1 = $data->FD_CONTRACT_AWARD_UTILITIES->CONTRACTING_ENTITY_CONTRACT_AWARD_UTILITIES->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD_UTILITIES->CA_CE_CONCESSIONAIRE_PROFILE;
	$purchasername = str_replace("'","\'",$data1->ORGANISATION->OFFICIALNAME);
	$purchaseradd = str_replace("'","\'",$data1->ADDRESS.",".$data1->TOWN ." ". $data1->POSTAL_CODE.",");
	
	if(!empty($data1->PHONE))
		$purchaseradd .=" Tel : ".$data1->PHONE.",";
	
	if(!empty($data1->FAX))
		$purchaseradd .=" Fax : ".$data1->FAX;
	
	$purch_country = str_replace("'","\'",$data1->COUNTRY["VALUE"]);
	$purch_email = str_replace("'","\'",$data1->E_MAILS->E_MAIL);
	$purch_url = str_replace("'","\'",$xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL);
	$data2 = $data->FD_CONTRACT_AWARD_UTILITIES->AWARD_CONTRACT_CONTRACT_AWARD_UTILITIES->AWARD_AND_CONTRACT_VALUE->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME_CHP;
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
	
	$contract_val = str_replace("'","\'",$data->FD_CONTRACT_AWARD_UTILITIES->OBJECT_CONTRACT_AWARD_UTILITIES->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE->VALUE_COST);
	$contract_currency = str_replace("'","\'",$data->FD_CONTRACT_AWARD_UTILITIES->OBJECT_CONTRACT_AWARD_UTILITIES->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE['CURRENCY']);
	$sector = '';
	$sector .= str_replace("'","\'",$data->FD_CONTRACT_AWARD_UTILITIES->OBJECT_CONTRACT_AWARD_UTILITIES->DESCRIPTION_CONTRACT_AWARD_UTILITIES->CPV->CPV_MAIN->CPV_CODE["CODE"]);
	
        if(!empty($sector)){
            $seccode = getCpv($sector, $db2);
            //echo "secode".$seccode;
        }
        foreach ($data->FD_CONTRACT_AWARD_UTILITIES->OBJECT_CONTRACT_AWARD_UTILITIES->DESCRIPTION_CONTRACT_AWARD_UTILITIES->CPV->CPV_ADDITIONAL as $cpv_ad ) $sector .= ",".str_replace("'","\'",$cpv_ad->CPV_CODE['CODE']);
	
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
			"sector"				=>		"'$seccode'",
			"date_c"				=>		"'$cont_date'",
			"userid"				=>		"0",
			"qc"					=>		"1"
	);
       // echo "<pre>";
       // print_r($contData);
	$check = $db->count('contract_award',"ref_number='$ref_number'");
	if($check == 0){
		$db->insert($contData,'contract_award');
		$id = $db->getMax('contract_award',"id");
		$contData1 = array("id"=>"$id");
		$contData1 = array_merge($contData1,$contData);
		$db2->insert($contData1,'contract_award');
	}
	return "DONE";
}

function contract_award_defence($xml,$db = NULL,$db2 = NULL){
	foreach ($xml->TRANSLATION_SECTION->ML_TITLES->ML_TI_DOC as $ml_ti){
		if($ml_ti['LG'] == 'EN'){
			$short_descp = str_replace("'","\'",$ml_ti->TI_TEXT);
		}
	}
	foreach ($xml->FORM_SECTION->CONTRACT_AWARD_DEFENCE as $temp){
		if($temp["LG"] == 'EN'){
			$data = $temp;
		}
	}
	if(empty($data)){
		$data = $xml->FORM_SECTION->CONTRACT_AWARD_DEFENCE;
	}
	$ref_number = str_replace("'","\'",$xml['DOC_ID']);
	$data1 = $data->FD_CONTRACT_AWARD_DEFENCE->CONTRACTING_AUTHORITY_INFORMATION_CONTRACT_AWARD_DEFENCE->NAME_ADDRESSES_CONTACT_CONTRACT_AWARD->CA_CE_CONCESSIONAIRE_PROFILE;
	$purchasername = str_replace("'","\'",$data1->ORGANISATION->OFFICIALNAME);
	
	$purchaseradd = str_replace("'","\'",$data1->ADDRESS.",".$data1->TOWN ." ". $data1->POSTAL_CODE.",");
	
	if(!empty($data1->PHONE))
		$purchaseradd .=" Tel : ".$data1->PHONE.",";
	
	if(!empty($data1->FAX))
		$purchaseradd .=" Fax : ".$data1->FAX;
	
	$purch_country = str_replace("'","\'",$data1->COUNTRY["VALUE"]);
	$purch_email = str_replace("'","\'",$data1->E_MAILS->E_MAIL);
	$purch_url = str_replace("'","\'",$xml->CODED_DATA_SECTION->NOTICE_DATA->IA_URL_GENERAL);
	$data2 = $data->FD_CONTRACT_AWARD_DEFENCE->AWARD_OF_CONTRACT_DEFENCE->ECONOMIC_OPERATOR_NAME_ADDRESS->CONTACT_DATA_WITHOUT_RESPONSIBLE_NAME;
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
	
	$contract_val = str_replace("'","\'",$data->FD_CONTRACT_AWARD_DEFENCE->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE_DEFENCE->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE->VALUE_COST);
	$contract_currency = str_replace("'","\'",$data->FD_CONTRACT_AWARD_DEFENCE->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE_DEFENCE->TOTAL_FINAL_VALUE->COSTS_RANGE_AND_CURRENCY_WITH_VAT_RATE['CURRENCY']);
	$sector = '';
	$sector .= str_replace("'","\'",$data->FD_CONTRACT_AWARD_DEFENCE->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE_DEFENCE->DESCRIPTION_AWARD_NOTICE_INFORMATION_DEFENCE->CPV->CPV_MAIN->CPV_CODE["CODE"]);
	if(!empty($sector)){
            $seccode = getCpv($sector, $db2);
            //echo "secode".$seccode;
        }
        foreach ($data->FD_CONTRACT_AWARD_DEFENCE->OBJECT_CONTRACT_INFORMATION_CONTRACT_AWARD_NOTICE_DEFENCE->DESCRIPTION_AWARD_NOTICE_INFORMATION_DEFENCE->CPV->CPV_ADDITIONAL as $cpv_ad ) $sector .= ",".str_replace("'","\'",$cpv_ad->CPV_CODE['CODE']);
	
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
			"sector"				=>		"'$seccode'",
			"date_c"				=>		"'$cont_date'",
			"userid"				=>		"0",
			"qc"					=>		"1"
	);
        //echo "<pre>";
        //print_r($contData);
	$check = $db->count('contract_award',"ref_number='$ref_number'");
	if($check == 0){
		$db->insert($contData,'contract_award');
		$id = $db->getMax('contract_award',"id");
		$contData1 = array("id"=>"$id");
		$contData1 = array_merge($contData1,$contData);
		$db2->insert($contData1,'contract_award');
	}
	return "DONE";
}
?>