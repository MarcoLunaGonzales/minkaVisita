<?php

	require("conexion.inc");
	require("estilos_gerencia.inc");
	$codigo_linea=$_GET['codigo_linea'];
	
	$resp_nombre_linea=mysql_query("select nombre_linea from lineas where codigo_linea=$codigo_linea");
	$dat_nombre_linea=mysql_fetch_array($resp_nombre_linea);
	$nombre_linea=$dat_nombre_linea[0];
	
	$sql="select cod_ciudad,descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	echo "<h1>Grillas por Territorio<br>Linea: <strong>$nombre_linea</h1>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>Territorio</th><th>Ver</th></tr>";
	//echo "<tr><td>Nacional</td><td><a href='grilla_nacional.php'>Ver >></a></td></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$p_cod_ciudad=$dat[0];
		$p_agencia=$dat[1];
		echo "<tr><td align='left'>&nbsp;&nbsp;$p_agencia</td><td align='center'>
			<a href='navegador_grillas.php?cod_ciudad=$p_cod_ciudad&codigo_linea=$codigo_linea'>
				<img src='imagenes/go2.png' width='40'>
			</a>
			</td></tr>";
	}
	echo "</table></center>";
	
?>