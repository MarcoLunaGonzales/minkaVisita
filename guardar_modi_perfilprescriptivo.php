<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
require("conexion.inc");
require("estilos_regional_pri.inc");
for($i=0;$i<=$numero_medicos;$i++)
{
	$medico="cod_med$i";
	$perfil="perfil$i";
	$cod_med=$$medico;
	$cod_perfil=$$perfil;
	$sql="update medicoslinea_perfilprescriptivo set codigo_perfilprescriptivo='$cod_perfil' where cod_med='$cod_med' and codigo_linea='$global_linea'";
	$resp=mysql_query($sql);
}
echo "<script language='Javascript'>
			alert('Los datos se modificaron correctamente.');
			location.href='navegador_medicos_lineas.php';
			</script>";
?>