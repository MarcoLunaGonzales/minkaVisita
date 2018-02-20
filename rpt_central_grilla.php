<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/	
	require("conexion.inc");
	require("estilos_reportes_central.inc");
			$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
			$dato=mysql_fetch_array($resp1);
			$nombre_territorio=$dato[1];	
			
			$sql_nombrelinea="select nombre_linea from lineas where codigo_linea=$linea_rpt";
			$resp_nombrelinea=mysql_query($sql_nombrelinea);
			$dat_nombrelinea=mysql_fetch_array($resp_nombrelinea);
			$nombre_linea=$dat_nombrelinea[0];
			
	echo "<table border='0' class='textotit' align='center'>";
	echo "<tr><th>Grillas<br>Linea: $nombre_linea <br>Territorio: $nombre_territorio</th></tr></table><br>";
	echo "<center><table border='1' cellspacing='0' class='texto'>";
	echo "<tr><th>Nombre Grilla</th></tr>";
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
		echo "<tr><td align='center'>$nombre</td></tr>";
	echo "</table><br>";
	
	$sql_det="select * from grilla_detalle where codigo_grilla='$codigo' order by cod_especialidad,cod_categoria";
	$resp_detalle=mysql_query($sql_det);
	echo "<center><table border='1' cellspacing='0' class='texto'>";
	echo "<tr><th>Especialidad</th><th>Categoria</th><th>Frecuencia</th></tr>";
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{
		$espe=$dat_detalle[1];
		$cat=$dat_detalle[2];
		$frecuencia=$dat_detalle[3];
		$can_medicos=$dat_detalle[4];
		$can_contactos=$dat_detalle[5];
		if($frecuencia>0){
			echo "<tr><td align='left'>$espe</td><td align='center'>$cat</td><td align='center'>$frecuencia</td></tr>";
		}
	}
	echo "</table><br>";
	echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
?>