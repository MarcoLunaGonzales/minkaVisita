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
		{	location.href='recuperar_parrilla.php?ciclo_trabajo=$ciclo_trabajo&gestion_trabajo=$gestion_trabajo';
		}
	</script>";
	require("conexion.inc");
	require("estilos.inc");
	require("funcion_nombres.php");
	echo "<center><table border='0' class='textotit'>";
	$nombreGestion=nombreGestion($gestion_trabajo);
	echo "<tr><td align='center'>Registro de Parrillas<br>Ciclo de Trabajo:$ciclo_trabajo Gestion: $nombreGestion</center></td></tr></table><br>";

	
	$sql="select distinct (p.cod_especialidad), e.desc_especialidad from parrilla p, especialidades e
	      where p.cod_especialidad=e.cod_especialidad and p.cod_ciclo='$ciclo_trabajo' and p.codigo_gestion='$gestion_trabajo' and p.codigo_linea='$global_linea' order by e.desc_especialidad";
	$resp=mysql_query($sql);
	$filas_parrillas=mysql_num_rows($resp);
	echo "<center><table border='1' width='40%' cellspacing='0' class='texto'>";
	echo "<tr><th>Parrillas por Tipo de Cliente</th><th>&nbsp</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_especialidad=$dat[0];
		$espe=$dat[1];
		echo "<tr><td>&nbsp;&nbsp;$espe</td><td align='center'><a href='navegador_parrillas_ciclos_detalle.php?cod_especialidad=$cod_especialidad&ciclo_trabajo=$ciclo_trabajo&gestion_trabajo=$gestion_trabajo'>Ver>></a></td></tr>";
	}
	echo "</table>";
	echo "<br>";
	echo"\n<table align='center'><tr><td><a href='navegador_parrillas_ciclos.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	echo "<table border='0'><tr>";
	if($filas_parrillas==0)
	{	echo "<td><input type='button' value='Replicar' class='boton' onclick='recuperar(this.form)'></td>";
	}
	echo "<td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td></tr></center>";
	echo "</table>";
?>