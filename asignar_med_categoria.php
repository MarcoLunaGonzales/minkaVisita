<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	$visitador=$visitador;
	//formamos el nombre del funcionario
	$sql_cab="select paterno,materno,nombres from funcionarios where codigo_funcionario='$visitador'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_funcionario="$dat_cab[0] $dat_cab[1] $dat_cab[2]";
	//fin formar nombre funcionario
	echo "<form>";
	$sql="select categoria_med from categorias_medicos order by categoria_med";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Asignar Medicos<br>Listado por Categorias<br>Visitador: $nombre_funcionario</th></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='40%'>";
	echo "<tr><th>Categoria</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_cat=$dat[0];
		echo "<tr><td align='center'>$cod_cat</td><td align='center'><a href='ver_medicos_categorias_det.php?cod_cat=$cod_cat&visitador=$visitador'>Ver Medicos >></a></td></tr>";
	}
	echo "</table></center><br>";
	echo"\n<table align='center'><tr><td><a href='asignar_med_fun.php?j_funcionario=$visitador'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
	echo "</form>";
?>