<?php
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=archivo.xls"); 	
	require("conexion.inc");
	require("estilos_reportes_central_xls.inc");
	$cod_linea_vis=$rpt_linea_visita;
	$global_linea=$linea_rpt;
	$sql_cab="select nombre_l_visita from lineas_visita where codigo_l_visita='$cod_linea_vis'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_linea_visita=$dat_cab[0];
	//fin formar cabecera
	$sql="select m.codigo,m.descripcion,m.presentacion from lineas_visita_detalle l, muestras_medicas m where m.codigo=l.codigo_mm and l.codigo_l_visita=$cod_linea_vis order by m.descripcion";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td align='center'>Productos x Líneas de Visita<br>Línea de Visita: <strong>$nombre_linea_visita</strong></td></tr></table></center><br>";
	$indice_tabla=1;
	echo "<center><table border='1' class='texto' cellspacing='0' width='40%'>";
	echo "<tr><th>&nbsp;</th><th>Producto</th><th>Presentación</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo_mm=$dat[0];
		$mm=$dat[1];
		$pres=$dat[2];
		echo "<tr><td align='center'>$indice_tabla</td><td>$mm</td><td>$pres</td></tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	
	
?>