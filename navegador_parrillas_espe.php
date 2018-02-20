<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
	echo "<script language='JavaScript'>
		function enviar_nav()
		{	location.href='creacion_parrilla.php?h_numero_muestras=1';
		}
		function recuperar()
		{	location.href='recuperar_parrilla.php';
		}
	</script>";
	require("conexion.inc");
	require("estilos.inc");
	echo "<center><table border='0' class='textotit'>";
	echo "<tr><td align='center'>Registro de Parrillas</center></td></tr></table><br>";
	$sql_ciclo="select cod_ciclo from ciclos where estado='Activo' and codigo_linea='$global_linea'";
	$resp_ciclo=mysql_query($sql_ciclo);
	$dat_ciclo=mysql_fetch_array($resp_ciclo);
	$ciclo_activo=$dat_ciclo[0];
	$sql="select distinct (p.cod_especialidad), e.desc_especialidad from parrilla p, especialidades e
	      where p.cod_especialidad=e.cod_especialidad and p.cod_ciclo='$ciclo_activo' and p.codigo_linea='$global_linea' order by e.desc_especialidad";
	$resp=mysql_query($sql);
	echo "<center><table border='1' cellspacing='0'  width='50%' class='texto'>";
	echo "<tr><th>Parrillas por Especialidad</th><th>&nbsp</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_especialidad=$dat[0];
		$espe=$dat[1];
		echo "<tr><td align='center'>$espe</td><td align='center'><a href='navegador_parrillas.php?cod_especialidad=$cod_especialidad'>Ver>></a></td></tr>";
	}
	echo "</table>";
	echo "<br>";
	require('home_central1.inc');
	//echo "<br>";
	//echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Replicar' class='boton' onclick='recuperar(this.form)'></td></tr></center>";
?>