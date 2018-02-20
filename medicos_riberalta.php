<?php
require("conexion.inc");
$sql_busca="select paterno, materno, nombres, fecha_nac, telefono, celular, email, hobbie, estado_civil, perfil, 
			secretaria, espe1, espe2, espe3, direccion1, zona1, direccion2, zona2 
			from medicos_riberalta order by paterno";
echo $sql_busca;
$resp_busca=mysql_query($sql_busca);
echo $sql_busca;
while($dat_busca=mysql_fetch_array($resp_busca))
{	$paterno=$dat_busca[0];
	$materno=$dat_busca[1];
	$nombres=$dat_busca[2];
	$fecha_nac=$dat_busca[3];
	$telefono=$dat_busca[4];
	$celular=$dat_busca[5];
	$email=$dat_busca[6];
	$hobbie=$dat_busca[7];
	$estado_civil=$dat_busca[8];
	$perfil_psico=$dat_busca[9];
	$secretaria=$dat_busca[10];
	$especialidad1=$dat_busca[11];
	$especialidad2=$dat_busca[12];
	$especialidad3=$dat_busca[13];
	$direccion1=$dat_busca[14];
	$zona1=$dat_busca[15];
	$direccion2=$dat_busca[16];
	$zona2=$dat_busca[17];
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
	$sql_inserta="insert into medicos values('$codigo','$paterno','$materno','$nombres','$fecha_nac','$telefono','$celular','$email','$hobbie','$estado_civil','$secretaria','$perfil_psico','121')";
	$resp_inserta=mysql_query($sql_inserta);
	$sql_inserta_espe="insert into especialidades_medicos values('$codigo','$especialidad1','Especialidad I')";
	$resp_inserta_espe=mysql_query($sql_inserta_espe);
	if($especialidad2!="")
	{	$sql_inserta_espe="insert into especialidades_medicos values('$codigo','$especialidad2','Especialidad II')";
		$resp_inserta_espe=mysql_query($sql_inserta_espe);
	}
	if($especialidad3!="")
	{	$sql_inserta_espe="insert into especialidades_medicos values('$codigo','$especialidad3','Especialidad III')";
		$resp_inserta_espe=mysql_query($sql_inserta_espe);
	}
	$sql_inserta_zonas="insert into direcciones_medicos values('$codigo','$zona1','$direccion1','Consultorio I',1)";
	$resp_inserta_zonas=mysql_query($sql_inserta_zonas);
	if($zona2!="")
	{	$sql_inserta_zonas2="insert into direcciones_medicos values('$codigo','$zona2','$direccion2','Consultorio II',2)";
		$resp_inserta_zonas2=mysql_query($sql_inserta_zonas2);
	}
	
	echo "$paterno $materno $nombres $codigo<br>";
}
?>