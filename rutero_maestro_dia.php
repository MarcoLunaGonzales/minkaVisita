<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M�dica
 * * @copyright 2006
*/
	require("conexion.inc");
	require("estilos_visitador.inc");
	//saca el nombre del rutero maestro
	$sql_nom_rutero=mysql_query("select nombre_rutero from rutero_maestro_cab where cod_rutero='$rutero' and cod_visitador='$global_visitador'");
	$dat_nom_rutero=mysql_fetch_array($sql_nom_rutero);
	$nombre_rutero=$dat_nom_rutero[0];
	//fin sacar nombre
	$sql="select DISTINCT (r.dia_contacto) from rutero_maestro r, orden_dias o where r.cod_rutero='$rutero' and r.cod_visitador='$global_visitador' and r.dia_contacto=o.dia_contacto order by o.id";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td align='center'>Rutero M�dico Maestro x D�a<br>N�mero de Rutero: $nombre_rutero</td></tr></table></center><br>";
	echo "<center><table border='1' class='textomini' cellspacing='0' width='30%'>";
	echo "<tr><th>D&iacute;a Contacto</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$dia_contacto=$dat[0];
		echo "<tr class='texto'><td>&nbsp;&nbsp;$dia_contacto</td><td align='center'><a href='rutero_maestro_dia_detalle.php?dia_contacto=$dia_contacto&rutero=$rutero&aprobado=$aprobado'>Ver >></a></td></tr>";
	}
	echo "</table></center><br>";
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
	
?>