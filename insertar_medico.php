<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005 
*/
	require("conexion.inc");
	$sql=mysql_query("select MAX(cod_med) as maximo from medicos");
	if(mysql_num_rows($sql)==0)
	{
		$codigo=1000;
	}
	else
	{
		$dat=mysql_fetch_array($sql);
		$codigo=$dat[0];
		$codigo++;
	}
	$fecha_nac="$anio-$mes-$dia";
	$sql1="insert into medicos values('$codigo','$ap_paterno','$ap_materno','$nombres','$fecha_nac','$telefono','$_telf_celular','$_email','$_hobbie','$estado_civil','$_nombre_secretaria','$_perfil_psicografico','$cod_ciudad')";
	$resp1=mysql_query($sql1);
	$nombre_medico="$nombres $ap_paterno $ap_materno";
	header("location:menu_direccion_especialidad.php?nombre_medico=$nombre_medico&cod_ciudad=$cod_ciudad&codigo=$codigo");
?>