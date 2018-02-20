<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
	echo "<script language='JavaScript'>
		function enviar_nav()
		{	location.href='creacion_parrilla.php?h_numero_muestras=1&ciclo_trabajo=$ciclo_trabajo';
		}
		function recuperar()
		{	location.href='recuperar_parrilla.php?ciclo_trabajo=$ciclo_trabajo';
		}
	</script>";
	require("conexion.inc");
	require("estilos.inc");
	require("funcion_nombres.php");
	
	$ciclo_trabajo=$_GET['ciclo_trabajo'];
	$gestion_trabajo=$_GET['gestion_trabajo'];
	
	$nombreGestion=nombreGestion($gestion_trabajo);
	
	echo "<center><table border='0' class='textotit'>";
	echo "<tr><td align='center'>Registro de Parrillas<br>Ciclo de Trabajo:$ciclo_trabajo Gestion de Trabajo: $gestion_traajo</center></td></tr></table><br>";

	$sql="select cod_ciudad, descripcion from ciudades order by 2";
	$resp=mysql_query($sql);
	$filas_parrillas=mysql_num_rows($resp);
	echo "<center><table border='1' width='40%' cellspacing='0' class='texto'>";
	echo "<tr><th>Parrillas por Territorio</th><th>Nro. Parrillas</th><th>&nbsp</th><th>&nbsp</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codCiudad=$dat[0];
		$nombreCiudad=$dat[1];
		$sqlParrilla="select * from parrilla where codigo_gestion='$gestion_trabajo' and cod_ciclo='$ciclo_trabajo'
		and agencia='$codCiudad' and codigo_linea='$global_linea'";
		$respParrilla=mysql_query($sqlParrilla);
		$numFilasParrilla=mysql_num_rows($respParrilla);
		echo "<tr><td>$nombreCiudad</td>
		<td align='center'>$numFilasParrilla</td>
		<td align='center'>
		<a href='navegador_parrillas_ciclos_detalle.php?codTerritorio=$codCiudad&ciclo_trabajo=$ciclo_trabajo&gestion_trabajo=$gestion_trabajo'>Ver>></a>
		</td></tr>";
		/*<td align='center'>
		<a href='replicarParrillaAgencia.php?codTerritorio=$codCiudad&cicloTrabajo=$ciclo_trabajo&gestionTrabajo=$gestion_trabajo' target='_blank'>Replicar>></a>
		</td>
		</tr>";*/
	}
	echo "</table>";
	echo "<br>";
	echo"\n<table align='center'><tr><td><a href='navegador_parrillas_ciclos.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
?>