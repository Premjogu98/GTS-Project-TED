<?php
$str = <<<XML
<?xml version="1.0" encoding="iso-8859-1"?>
<a>
<employees>
  <employee>
  <name>Mark</name>
  <age>27</age>
  <salary>$5000</salary>
  </employee>
  <employee>
  <name>Jack</name>
  <age>25</age>
  <salary>$4000</salary>
  </employee>
  </employees>
</a>
XML;

$doc = new DOMDocument();
$doc->loadXML($str);
 
$employees = $doc->getElementsByTagName( "employee" );
print_r($employees);
foreach( $employees as $employee )
{	
	$names = $employee->getElementsByTagName( "name" );
	$name = $names->item(0)->nodeValue;
	 
	$ages= $employee->getElementsByTagName( "age" );
	$age= $ages->item(0)->nodeValue;
	 
	$salaries = $employee->getElementsByTagName( "salary" );
	$salary = $salaries->item(0)->nodeValue;
	 
	echo "<b>$name - $age - $salary\n</b><br>";
}
?>