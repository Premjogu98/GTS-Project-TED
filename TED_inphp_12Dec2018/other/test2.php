<?php
header('Content-Type: text/html; charset=UTF-8');
$xmlDoc = new DOMDocument();
$xmlDoc->load("097890_2014.xml");
//echo $xmlDoc->saveXML();

$ML_TI_DOC = $xmlDoc->getElementsByTagName( "ML_TI_DOC" );

foreach ($ML_TI_DOC as $ML_TI_DOCVAl){
	$TI_CY= $ML_TI_DOCVAl->getElementsByTagName( "TI_CY" );
	$TI_CY1 = $TI_CY->item(0)->nodeValue;
	
	echo "<br/>".$TI_CY1;
}

?>