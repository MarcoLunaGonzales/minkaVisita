<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005 
*/
	echo "<script language='Javascript'>
			function abre_nuevo(f)
			{
				var i;
				i=f.elements[0].value;
				window.open(i,'_blank','toolbar=0');
			}
			function abre_nuevo1(f)
			{
				var j;
				j=f.elements[1].value;
				window.open(j,'_blank','toolbar=0');
			}
			</script>";
	require("estilos.inc");
	require("conexion.inc");
	//$ventana1="registrar_direccion_medico.php?h_ciudad=$cod_ciudad&h_cod_med=$codigo&h_nombre_med=$nombre_medico";
	//$ventana2="registrar_especialidad_medico.php?h_ciudad=$cod_ciudad&h_cod_med=$codigo&h_nombre_med=$nombre_medico";
	echo "<form name='f'>";
	echo "<input type='hidden' name='campo1' value='http://localhost/visita_medica/registrar_direccion_medico.php?h_ciudad=$cod_ciudad&h_cod_med=$codigo&h_nombre_med=$nombre_medico'>";
	echo "<input type='hidden' name='campo2' value='http://localhost/visita_medica/registrar_especialidad_medico.php?h_ciudad=$cod_ciudad&h_cod_med=$codigo&h_nombre_med=$nombre_medico'>";
	echo "<center><table border='0' class='textotit'><tr><td><center>Añadir Datos extras de $nombre_medico</center></td></tr></table><br>\n";
	echo "<table border='0' class='texto'><tr><td><li><a href='javascript:abre_nuevo(f)'>Registrar Direccion de $nombre_medico</a></li></td></tr>\n";
	echo "<tr><td><li><a href='javascript:abre_nuevo1(f)'>Registrar Especialidad de $nombre_medico</a></li></td></tr></table></center>\n";	
	echo "</form>";
?>