<?php
$doc = new DOMDocument();
$doc->load('087077_2014.xml');
//echo $doc->saveXML();
//$doc;

$val = $doc->getElementsByTagName("ORIGINAL_CPV");
echo $val;
?>