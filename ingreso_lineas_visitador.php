<?php
	require("estilos_inicio_visitador.inc");
	require("conexion.inc");
	echo"<script language='javascript'>";

	echo"function tamanonormal()";
	echo"{ ";
	echo"	window.resizeTo(1024,768);";
	
	echo"window.moveTo(0,0);";
	
	echo"window.scrollbars=false;";
	echo"window.resizable=false;";
	echo"window.menubar=false;";
	echo"}";
	echo "tamanonormal();";
	echo"</script>";
	echo "<br><br><br><br><br>";
	echo "<center><table class='texto' border='1'  width='50%' cellspacing='0'><tr><th colspan='2'>Ingresar Por:</th></tr>";
	$sql_zonas="select codigo_visitador from zona_viaje_visitador where codigo_visitador='$global_visitador'";
	$resp_zonas=mysql_query($sql_zonas);
	$filas_zonas=mysql_num_rows($resp_zonas);
	if($filas_zonas==1)
	{	echo "<tr><td align='center'><a href='ingreso_lineas_visitador_detalle.php'>Zona Base</a></td>";
		echo "<td align='center'><a href='cookie_linea_visitador.php?linea=0'>Zona Viaje</a></td></tr></table>";
	}
	else
	{	echo "<tr><td align='center'><a href='ingreso_lineas_visitador_detalle.php'>Zona Base</a></td>";
		echo "<td align='center'>Zona Viaje</td></tr></table>";
	}
	//echo "<center><table class='texto'><tr><td><img src='imagenes/bienvenidos_visitador.gif'></td></tr></table>";

?>