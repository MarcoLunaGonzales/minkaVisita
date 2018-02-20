<?php
require("conexion.inc");
require("estilos_inicio_visitador.inc");
echo "<form method='post' action=''>";
	/*$sql="SELECT DISTINCT l.* from lineas l, linea_territorio lt where linea_promocion = 1 and 
	lt.linea = l.codigo_linea and lt.cod_ciudad = $global_agencia order by nombre_linea";*/
	
	$sql="SELECT DISTINCT l.* from lineas l where linea_promocion = 1 and estado=1 order by l.nombre_linea";
	
	$resp=mysql_query($sql);
	echo "<h1>Seleccione la Linea de trabajo</h1>";

	echo "<center><table class='texto'>";
	echo "<tr><th>Linea</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp)) {
		$codigo=$dat[0];
		$nombre=$dat[1];
		$sql_filtro="SELECT codigo_linea from funcionarios_lineas where codigo_linea='$codigo' and codigo_funcionario='$global_visitador'";
		$resp_filtro=mysql_query($sql_filtro);
		$num_filas=mysql_num_rows($resp_filtro);
		if($num_filas!=0) {	
			echo "<tr><td>&nbsp;$nombre</td><td align='center'>
			<a href='cookie_linea_visitador.php?linea=$codigo'><img src='imagenes/enter.png' width='40'></a></td></tr>";
		}
		else {	
			echo "<tr><td>&nbsp;$nombre</td><td align='center'>-</td></tr>";
		}

	}
	echo "</table></center><br>";
	echo "</form>";
?>