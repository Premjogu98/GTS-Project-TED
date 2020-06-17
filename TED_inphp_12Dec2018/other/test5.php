<?php
include 'inc/global.inc.php';
//print_r($con);
$cpvval = "32400000,79700000,80600000,79800000,79900000,";
$cpvarray = explode(",", $cpvval); // Making an array for CPV data should be like 32400000,79700000,80600000,79800000,79900000 (Qumma seprated)
// If there is more than one cpv, then we have to collect sector/ sub sector for each CPV
 $secid = "";
 $subsecid = "";
  echo "<pre>";
if(count($cpvarray) != 0 ){
    foreach ($cpvarray as $cpv){
        if(!empty($cpv)){
            $sectorData = getSector($cpv,$db2);
            //print_r($sectorData);
            foreach ($sectorData as $sector){
                foreach ($sector as $sectorval){
                    $secidArr[]=$sectorval['Sector_Id'];
                    $subsecidArr[]=$sectorval['Sub_Sector_Id'];
                }
            }
        }
    }
    print_r(array_unique($secidArr));
    print_r(array_unique($subsecidArr));
    echo implode(",",array_unique($secidArr));
    echo "<br>";
    echo implode(",",array_unique($subsecidArr));
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
?>