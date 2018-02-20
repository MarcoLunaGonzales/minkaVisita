<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	$sql_cab="select paterno,materno,nombres from funcionarios where codigo_funcionario='$visitador'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_funcionario="$dat_cab[0] $dat_cab[1] $dat_cab[2]";
	$sql="select DISTINCT (r.dia_contacto) from rutero_maestro r, orden_dias o where r.cod_rutero='$rutero' and r.cod_visitador='$visitador' and r.dia_contacto=o.dia_contacto order by o.id";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Rutero Medico Maestro x Dia<br>Visitador: $nombre_funcionario<br>Número de Rutero: $rutero</th></tr></table></center><br>";
	echo "<center><table border='1' class='textomini' cellspacing='0' width='50%'>";
	echo "<tr><th>Dia Contacto</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$dia_contacto=$dat[0];
		echo "<tr><td align='center' class='texto'>$dia_contacto</td><td align='center'><a href='funcionario_rutero_maestro_dia_detalle.php?dia_contacto=$dia_contacto&rutero=$rutero&visitador=$visitador'>Ver >></a></td></tr>";
	}
	echo "</table></center><br>";
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
		
?>