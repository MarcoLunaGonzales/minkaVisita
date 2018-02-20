<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	require("conexion.inc");
	require("estilos_gerencia.inc");
	echo "<form>";
	$sql="select categoria_med from categorias_medicos order by categoria_med";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Ver Medicos de la Línea por Categorias</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='40%'>";
	echo "<tr><th>Categoria</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_cat=$dat[0];
		echo "<tr><td align='center'>$cod_cat</td><td align='center'><a href='ver_medicos_categorias.php?cod_cat=$cod_cat'>Ver Medicos >></a></td></tr>";
	}
	echo "</table></center><br>";
	require("home_regional1.inc");
	echo "</form>";
?>