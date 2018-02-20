<?php
require("conexion.inc");
require("funcion_nombres.php");

$gestion=1014;
$ciclo=8;
$ciudades=$_GET['codCiudad'];

$ciudades="120,121,122,123,124,125";
//$ciudades="123, 124, 125";
$lineasProv="1009, 1021, 1023, 1022, 1023, 1048, 1049";
//$lineasProv="0";


$sqlDel="delete from comisiones";
$respDel=mysql_query($sqlDel);

echo $ciclo;
$sql="select f.cod_ciudad, r.cod_visitador, r.codigo_linea, f.cod_zeus from rutero_maestro_cab_aprobado r, funcionarios f 
	where r.codigo_gestion=$gestion and r.codigo_ciclo=$ciclo and r.cod_visitador=f.codigo_funcionario and r.estado_aprobado=1
	and r.codigo_linea not in ($lineasProv) 
	ORDER BY f.cod_ciudad, r.cod_visitador, r.codigo_linea";
////
//echo $sql;
$resp=mysql_query($sql);

echo "<table border=1>";
echo "<tr><td>Agencia</td><td>CodHermes</td><td>codZeus</td><td>Visitador</td><td>CodLinea</td><td>Linea</td><td>Contactos</td><td>Peso</td></tr>";

$codVisPivote=0;
while($dat=mysql_fetch_array($resp)){
	$codCiudad=$dat[0];
	$codVisitador=$dat[1];
	$codLinea=$dat[2];
	$codZeus=$dat[3];
	
	$nombreCiudad=nombreTerritorio($codCiudad);
	$nombreVisitador=nombreCompletoVisitador($codVisitador);
	$nombreLinea=nombreLinea($codLinea);
	
	
	$sqlNumContactos1="select count(*) from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, 
		rutero_maestro_detalle_aprobado rd 
		where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rc.cod_visitador=rd.cod_visitador 
		and rc.cod_visitador=rm.cod_visitador 
		and rc.cod_visitador='$codVisitador' and rc.codigo_gestion=$gestion and rc.codigo_ciclo=$ciclo and rc.codigo_linea not in ($lineasProv)";
	$respNumContactos1=mysql_query($sqlNumContactos1);
	$numContactosTotal=mysql_result($respNumContactos1,0,0);
	
	$sqlNumContactos="select count(*) from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, 
		rutero_maestro_detalle_aprobado rd 
		where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rc.cod_visitador=rd.cod_visitador 
		and rc.cod_visitador=rm.cod_visitador 
		and rc.cod_visitador='$codVisitador' and rc.codigo_linea=$codLinea and rc.codigo_gestion=$gestion and rc.codigo_ciclo=$ciclo";
	$respNumContactos=mysql_query($sqlNumContactos);
	$numContactos=mysql_result($respNumContactos,0,0);
	
	$porcLinea=($numContactos/$numContactosTotal)*100;
	
	//echo "<tr><td>$nombreCiudad</td><td>$codVisitador</td><td>$nombreVisitador</td><td>$codLinea</td><td>$nombreLinea</td><td>$numContactos</td><td>$porcLinea</td></tr>";
	$sqlInsert="insert into comisiones values($codVisitador,'$nombreVisitador','$nombreCiudad','$nombreLinea', $codLinea, $numContactos, $porcLinea)";
	//echo $sqlInsert;
	$respInsert=mysql_query($sqlInsert);
}


//$sqlDel="delete from comisiones where peso<=9.5"; // ya no se aplica esta regla, se valoran todos los porcentajes
//$respDel=mysql_query($sqlDel);

$sqlU="select c.territorio, c.codVisitador, c.nombreVisitador, c.codigoLinea, c.linea, c.contactos, c.peso from comisiones c";
$respU=mysql_query($sqlU);

while($datU=mysql_fetch_array($respU)){
	$agencia=$datU[0];
	$codVis=$datU[1];
	$nombreVis=$datU[2];
	$codLinea=$datU[3];
	$nombreLinea=$datU[4];
	$contactos=$datU[5];
	
	$sqlTotal="select sum(contactos) from comisiones where codVisitador=$codVis";
	//echo $sqlTotal;
	$respTotal=mysql_query($sqlTotal);
	$totalContactos=mysql_result($respTotal,0,0);
	
	$sqlUpd="update comisiones set peso=(contactos/($totalContactos)*100) where codVisitador=$codVis and codigoLinea=$codLinea";
	//echo $sqlUpd;
	$respUpd=mysql_query($sqlUpd);
}

$sqlList="select c.territorio, c.codVisitador, c.nombreVisitador, c.codigoLinea, c.linea, c.contactos, c.peso,
	(select f.cod_zeus from funcionarios f where f.codigo_funcionario=c.codVisitador)codzeus 
	from comisiones c";
$respList=mysql_query($sqlList);

while($datList=mysql_fetch_array($respList)){
	$agencia=$datList[0];
	$codVis=$datList[1];
	$nombreVis=$datList[2];
	$codLinea=$datList[3];
	$nombreLinea=$datList[4];
	$contactos=$datList[5];
	$pesoX=$datList[6];
	$codZeus=$datList[7];
	
	echo "<tr><td>$agencia</td><td>$codVis</td><td>$codZeus</td><td>$nombreVis</td><td>$codLinea</td><td>$nombreLinea</td><td>$contactos</td><td>$pesoX</td></tr>";

}
echo "</table>";
?>