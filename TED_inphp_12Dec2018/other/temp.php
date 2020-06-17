<?php
// Geting the CPV and slection status hight low reject value value for the entry id

$SQLCPV = "SELECT en.cpv_value,dn.status FROM dms_entrynotice_tbl as en, dms_downloadfiles_tbl as dn WHERE en.file_id=dn.file_id AND en.entry_id='$entry_id'";
$QUERYCPV = mysql_query($SQLCPV);
list($cpvval, $filestatus) = mysql_fetch_row($QUERYCPV);


$cpvarray = explode(",", $cpvval); // Making an array for CPV data should be like 32400000,79700000,80600000,79800000,79900000 (Qumma seprated)
$cpvcount = count($cpvarray); // Getting the array size how much cpv is inserted in an array.

        
// If there is more than one cpv, then we have to collect sector/ sub sector for each CPV

if ($cpvcount > 1) {



            $secid = "";
            $subsecid = "";

            foreach ($cpvarray as $cp) {

                $rcp2 = substr($cp, 0, 2);
                $rcp3 = substr($cp, 0, 3);
                $rcp4 = substr($cp, 0, 4);
                $rcp5 = substr($cp, 0, 5);
                $rcp6 = substr($cp, 0, 6);

                // For everycpv

                $SQLEXP2 = "SELECT Sector_Id,Sub_Sector_Id FROM tbl_sector WHERE Sub_Sector_Id like '$rcp2%'";
                $QUERYEXP2 = mysql_query($SQLEXP2);

                list($s2, $ss2) = mysql_fetch_row($QUERYEXP2);

                if (mysql_num_rows($QUERYEXP2) > 0) {
                    $secid .= "$s2,";
                    $subsecid .="$ss2,";
                }

                //for 32400000,79700000,80600000,79800000,79900000

                $SQLEXP3 = "SELECT Sector_Id,Sub_Sector_Id FROM tbl_sector WHERE Sub_Sector_Id like '$rcp3%'";
                $QUERYEXP3 = mysql_query($SQLEXP3);

                list($s3, $ss3) = mysql_fetch_row($QUERYEXP3);

                if (mysql_num_rows($QUERYEXP3) > 0) {
                    $secid .= "$s3,";
                    $subsecid .="$ss3,";
                }

                //for 51550000
                $SQLEXP4 = "SELECT Sector_Id,Sub_Sector_Id FROM tbl_sector WHERE Sub_Sector_Id like '$rcp4%'";
                $QUERYEXP4 = mysql_query($SQLEXP4);

                list($s4, $ss4) = mysql_fetch_row($QUERYEXP4);

                if (mysql_num_rows($QUERYEXP4) > 0) {
                    $secid .= "$s4,";
                    $subsecid .="$ss4,";
                }

                // For 72231000
                $SQLEXP5 = "SELECT Sector_Id,Sub_Sector_Id FROM tbl_sector WHERE Sub_Sector_Id like '$rcp5%'";
                $QUERYEXP5 = mysql_query($SQLEXP5);
                list($s5, $ss5) = mysql_fetch_row($QUERYEXP5);
                if (mysql_num_rows($QUERYEXP5) > 0) {
                    $secid .= "$s5,";
                    $subsecid .="$ss5,";
                }

                // For 31642200
                $SQLEXP6 = "SELECT Sector_Id,Sub_Sector_Id FROM tbl_sector WHERE Sub_Sector_Id like '$rcp6%'";
                $QUERYEXP6 = mysql_query($SQLEXP6);
                list($s6, $ss6) = mysql_fetch_row($QUERYEXP6);
                if (mysql_num_rows($QUERYEXP6) > 0) {
                    $secid .= "$s6,";
                    $subsecid .="$ss6,";
                }
            }
        } else {


            // If only one CPV.

            $rcp2 = substr($cpvval, 0, 2);
            $rcp3 = substr($cpvval, 0, 3);
            $rcp4 = substr($cpvval, 0, 4);
            $rcp5 = substr($cpvval, 0, 5);
            $rcp6 = substr($cpvval, 0, 6);

            // For everycpv

            $SQLEXP2 = "SELECT Sector_Id,Sub_Sector_Id FROM tbl_sector WHERE Sub_Sector_Id like '$rcp2%'";
            $QUERYEXP2 = mysql_query($SQLEXP2);

            list($s2, $ss2) = mysql_fetch_row($QUERYEXP2);

            if (mysql_num_rows($QUERYEXP2) > 0) {
                $secid .= "$s2,";
                $subsecid .="$ss2,";

                //$subsecid .= "";
            }

            //for 32400000,79700000,80600000,79800000,79900000

            $SQLEXP3 = "SELECT Sector_Id,Sub_Sector_Id FROM tbl_sector WHERE Sub_Sector_Id like '$rcp3%'";
            $QUERYEXP3 = mysql_query($SQLEXP3);

            list($s3, $ss3) = mysql_fetch_row($QUERYEXP3);

            if (mysql_num_rows($QUERYEXP3) > 0) {
                $secid .= "$s3,";
                $subsecid .="$ss3,";
            }

            //for 51550000
            $SQLEXP4 = "SELECT Sector_Id,Sub_Sector_Id FROM tbl_sector WHERE Sub_Sector_Id like '$rcp4%'";
            $QUERYEXP4 = mysql_query($SQLEXP4);

            list($s4, $ss4) = mysql_fetch_row($QUERYEXP4);

            if (mysql_num_rows($QUERYEXP4) > 0) {
                $secid .= "$s4,";
                $subsecid .="$ss4,";
            }

            // For 72231000
            $SQLEXP5 = "SELECT Sector_Id,Sub_Sector_Id FROM tbl_sector WHERE Sub_Sector_Id like '$rcp5%'";
            $QUERYEXP5 = mysql_query($SQLEXP5);
            list($s5, $ss5) = mysql_fetch_row($QUERYEXP5);
            if (mysql_num_rows($QUERYEXP5) > 0) {
                $secid .= "$s5,";
                $subsecid .="$ss5,";
            }

            // For 31642200
            $SQLEXP6 = "SELECT Sector_Id,Sub_Sector_Id FROM tbl_sector WHERE Sub_Sector_Id like '$rcp6%'";
            $QUERYEXP6 = mysql_query($SQLEXP6);
            list($s6, $ss6) = mysql_fetch_row($QUERYEXP6);
            if (mysql_num_rows($QUERYEXP6) > 0) {
                $secid .= "$s6,";
                $subsecid .="$ss6,";
            }
        }

        if (!empty($secid)) {
            $secid = substr($secid, 0, -1);
            $secarr = explode(",", $secid);
            $secarr = array_unique($secarr);
            $secid = implode(",", $secarr);

            $subsecid = substr($subsecid, 0, -1);
            $subsecarr = explode(",", $subsecid);
            $subsecarr = array_unique($subsecarr);
            $subsecid = implode(",", $subsecarr);
        } else {
            $secid = "";
            $subsecid = "";
        }