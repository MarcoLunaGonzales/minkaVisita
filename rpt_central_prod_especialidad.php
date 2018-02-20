<?php
	require("conexion.inc");
	require("estilos_reportes_central.inc");
	$cod_espe=$rpt_especialidad;
	$global_linea=$linea_rpt;
	$sql_cab="select desc_especialidad from especialidades where cod_especialidad='$cod_espe'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_espe=$dat_cab[0];
	//fin formar cabecera
	$sql="select m.codigo,m.descripcion,m.presentacion 
		from producto_especialidad p, muestras_medicas m 
		where m.codigo=p.codigo_mm and m.codigo_linea=p.codigo_linea and p.cod_especialidad='$cod_espe' and m.codigo_linea='$global_linea' order by m.descripcion";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td align='center'>Grilla de Productos x Especialidad<br>Especialidad: <strong>$nombre_espe</strong></td></tr></table></center><br>";
	$indice_tabla=1;
	echo "<center><table border='1' class='texto' cellspacing='0' width='60%'>";
	echo "<tr><th>&nbsp;</th><th>Producto</th><th>Presentación</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo_mm=$dat[0];
		$mm=$dat[1];
		$pres=$dat[2];
		echo "<tr><td align='center'>$indice_tabla</td><td>$mm</td><td>$pres</td></tr>";
		$indice_tabla++;
	}
	echo "</table></center>";
	echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

?>