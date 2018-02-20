<?php
require("conexion.inc");
$sql_busca="select paterno, materno, nombres, fecha_nac, especialidad, direccion1, zona1, telefono, celular, email, direccion2, zona2, hobbie 
			from medicos_la_paz order by paterno";
$resp_busca=mysql_query($sql_busca);
while($dat_busca=mysql_fetch_array($resp_busca))
{	$paterno=$dat_busca[0];
	$materno=$dat_busca[1];
	$nombres=$dat_busca[2];
	$fecha_nac=$dat_busca[3];
	$telefono=$dat_busca[4];
	$celular=$dat_busca[5];
	$hobbie=$dat_busca[6];
	$especialidad=$dat_busca[7];
	$direccion=$dat_busca[8];
	$zona=$dat_busca[9];
	$direccion2=$dat_busca[10];
	$zona2=$dat_busca[11];
	$sql_codigo=mysql_query("select MAX(cod_med) as maximo from medicos");
	if(mysql_num_rows($sql_codigo)==0)
	{
		$codigo=1000;
	}
	else
	{
		$dat=mysql_fetch_array($sql_codigo);
		$codigo=$dat[0];
		$codigo++;
	}
	$sql_inserta="insert into medicos values('$codigo','$paterno','$materno','$nombres','$fecha_nac','$telefono','$celular','$email','$hobbie','$estado_civil','$secretaria','$perfil_psico','114')";
	$resp_inserta=mysql_query($sql_inserta);
	$sql_inserta_espe="insert into especialidades_medicos values('$codigo','$especialidad','Especialidad')";
	$resp_inserta_espe=mysql_query($sql_inserta_espe);
	$sql_inserta_zonas="insert into direcciones_medicos values('$codigo','1147','$direccion','Consultorio I')";
	$resp_inserta_zonas=mysql_query($sql_inserta_zonas);
	echo "$paterno $materno $nombres $codigo<br>";
}
?>