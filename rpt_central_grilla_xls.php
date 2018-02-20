<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/	
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=archivo.xls"); 	
	require("conexion.inc");
	require("estilos_reportes_central_xls.inc");
			$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
			$dato=mysql_fetch_array($resp1);
			$nombre_territorio=$dato[1];	
	echo "<center>Reporte Grillas<br>Territorio $nombre_territorio</center><br>";
	echo "<center><table border='1' cellspacing='0' class='texto'>";
	echo "<tr><th>Nombre Grilla</th><th>Fecha de Creación</th><th>Fecha de Modificación</th></tr>";
	$sql="select * from grilla where codigo_grilla='$codigo_grilla'";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
		$codigo=$dat[0];
		$nombre=$dat[1];
		$cod_ciudad=$dat[2];
		$total_medicos=$dat[3];
		$total_contactos=$dat[4];
		$total_visitadores=$dat[5];
		$conxvis=$dat[6];
		$fecha_creacion=$dat[7];
		$fecha_modi=$dat[8];
			$sql1="select cod_ciudad,descripcion from ciudades where cod_ciudad='$cod_ciudad'";
			$resp1=mysql_query($sql1);
			$dato=mysql_fetch_array($resp1);
			$ciudad=$dato[1];	
		echo "<tr><td align='center'>$nombre</td><td align='center'>$fecha_creacion</td><td align='center'>$fecha_modi</td></tr>";
	echo "</table><br>";
	
	$sql_det="select * from grilla_detalle where codigo_grilla='$codigo' order by cod_especialidad,cod_categoria";
	$resp_detalle=mysql_query($sql_det);
	echo "<center><table border='1' cellspacing='0' class='texto'>";
	echo "<tr><th>Especialidad</th><th>Categoria</th><th>Frecuencia</th><th>Cantidad de Medicos</th><th>Cantidad de Contactos</th></tr>";
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{
		$espe=$dat_detalle[1];
		$cat=$dat_detalle[2];
		$frecuencia=$dat_detalle[3];
		$can_medicos=$dat_detalle[4];
		$can_contactos=$dat_detalle[5];
		echo "<tr><td align='center'>$espe</td><td align='center'>$cat</td><td align='center'>$frecuencia</td><td align='center'>$can_medicos</td><td align='center'>$can_contactos</td></tr>";
	}
	echo "</table><br>";
	echo "<table border=1 cellspacing=0 class='texto'>";
	echo "<tr><td>Total Medicos: </td><td align='center'>$total_medicos</td></tr>";
	echo "<tr><td>Total Contactos: </td><td align='center'>$total_contactos</td></tr>";
	echo "<tr><td>Total Visitadores: </td><td align='center'>$total_visitadores</td></tr>";
	echo "<tr><td>Contactos x Visitador: </td><td align='center'>$conxvis</td></tr>";
	echo "</table><br>";
?>