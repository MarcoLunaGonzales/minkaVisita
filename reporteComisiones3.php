<?php
require("conexion.inc");

$sql="select c.territorio, c.codVisitador, c.nombreVisitador, c.codigoLinea, c.linea, c.contactos, c.peso from comisiones c";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$agencia=$dat[0];
	$codVis=$dat[1];
	$nombreVis=$dat[2];
	$codLinea=$dat[3];
	$nombreLinea=$dat[4];
	$contactos=$dat[5];
	
	$sqlTotal="select sum(contactos) from comisiones where codVisitador=$codVis";
	//echo $sqlTotal;
	$respTotal=mysql_query($sqlTotal);
	$totalContactos=mysql_result($respTotal,0,0);
	
	$sqlUpd="update comisiones set peso=(contactos/($totalContactos)*100) where codVisitador=$codVis and codigoLinea=$codLinea";
	echo $sqlUpd;
	$respUpd=mysql_query($sqlUpd);
}


?>