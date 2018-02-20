<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	//echo "$j_modificador $j_cod_contacto $j_ciclo";
	require("conexion.inc");
	$sql="update contactos set orden_visita='$j_modificador' where cod_contacto='$j_cod_contacto'";
	$resp=mysql_query($sql);
	header("location:ver_fechas_ciclo.php?j_ciclo=$j_ciclo");


?>