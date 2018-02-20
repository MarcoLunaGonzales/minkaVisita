<?php
require("conexion.inc");

$ciclosExc="8,9,10";
$gestionExc="1008";

//INICIO
$sql="select distinct(rd.`cod_med`) from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` r,
	`rutero_maestro_detalle_aprobado` rd where 
	rc.`cod_rutero`=r.`cod_rutero` and r.`cod_contacto`=rd.`cod_contacto` and
	rc.`codigo_ciclo` in ($ciclosExc) and rc.`codigo_gestion` in ($gestionExc);";
$resp=mysql_query($sql);

$cadenaMedicos="";
while($dat=mysql_fetch_array($resp)){
	$codMed=$dat[0];
	$cadenaMedicos.="$codMed,";
}
$cadenaMedicos.="0";

echo "CADENA DE MEDICOS $cadenaMedicos";

$sqlMed="select m.`cod_med` from medicos m where m.`cod_med` not in ($cadenaMedicos)";
$respMed=mysql_query($sqlMed);

while($datMed=mysql_fetch_array($respMed)){
	$codMedBorrar=$datMed[0];
	
	$sqlDel="delete from categorias_lineas where cod_med='$codMedBorrar'";
	$respDel=mysql_query($sqlDel);
	
	$sqlDel="delete from especialidades_medicos where cod_med='$codMedBorrar'";
	$respDel=mysql_query($sqlDel);

	$sqlDel="delete from direcciones_medicos where cod_med='$codMedBorrar'";
	$respDel=mysql_query($sqlDel);

	$sqlDel="delete from medico_asignado_visitador where cod_med='$codMedBorrar'";
	$respDel=mysql_query($sqlDel);
		
	$sqlDel="delete from medicos where cod_med='$codMedBorrar'";
	$respDel=mysql_query($sqlDel);
	
	echo "BORRADO MEDICO $codMedBorrar<br>";
}

?>