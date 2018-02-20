<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2009 
 **/
require("conexion.inc");
require("estilos_gerencia.inc");
echo "<form method='post' action='guarda_replicagrilla.php'>";
$codigo_grilla=$_GET['codigo_grilla'];
echo "<table border=0 class='textotit' align='center'><tr><td>Replicar Grilla</td></tr></table>";
$sql_territorios="select cod_ciudad, descripcion from ciudades order by descripcion";
$resp_territorios=mysql_query($sql_territorios);
echo"<br><table border=1 class='texto' align='center'>";
echo"<tr><th>Territorio</th></tr>
	<tr><td>
		<select name='codigo_territorio' class='texto'>";
while($dat_territorios=mysql_fetch_array($resp_territorios))
{	$cod_ciudad=$dat_territorios[0];
	$nombre_ciudad=$dat_territorios[1];
	echo "<option value='$cod_ciudad'>$nombre_ciudad</option>";
}
echo "</select></td></tr></table><br>";
echo "<input type='hidden' name='codigo_grilla' value='$codigo_grilla'>";
echo "<center><input type='submit' value='Guardar' class='boton'></center>";
echo "</form>";
?>