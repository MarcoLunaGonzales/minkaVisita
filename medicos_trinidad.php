<?php
require("conexion.inc");
$sql_busca="select paterno, materno, nombres, fecha_nac, especialidad, subespecialidad, direccion1, zona1, telefono, celular, email, 
			direccion2, zona2, hobbie, estado_civil, perfil_psico, secretaria 
			from medicos_trinidad order by paterno";
$resp_busca=mysql_query($sql_busca);
echo $sql_busca;
while($dat_busca=mysql_fetch_array($resp_busca))
{	$paterno=$dat_busca[0];
	$materno=$dat_busca[1];
	$nombres=$dat_busca[2];
	$fecha_nac=$dat_busca[3];
	$especialidad=$dat_busca[4];
	$subespecialidad=$dat_busca[5];
	$direccion1=$dat_busca[6];
	$zona1=$dat_busca[7];
	$telefono=$dat_busca[8];
	$celular=$dat_busca[9];
	$email=$dat_busca[10];
	$direccion2=$dat_busca[11];
	$zona2=$dat_busca[12];
	$hobbie=$dat_busca[13];
	$estado_civil=$dat_busca[14];
	$perfil_psico=$dat_busca[15];
	$secretaria=$dat_busca[16];
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
	$sql_inserta="insert into medicos values('$codigo','$paterno','$materno','$nombres','$fecha_nac','$telefono','$celular','$email','$hobbie','$estado_civil','$secretaria','$perfil_psico','117')";
	$resp_inserta=mysql_query($sql_inserta);
	$sql_inserta_espe="insert into especialidades_medicos values('$codigo','$especialidad','Especialidad')";
	$resp_inserta_espe=mysql_query($sql_inserta_espe);
	if($subespecialidad!="")
	{	$sql_inserta_espe="insert into especialidades_medicos values('$codigo','$subespecialidad','Sub-especialidad')";
		$resp_inserta_espe=mysql_query($sql_inserta_espe);
	}
		if($zona1=="Trinidad")
		{	$zona_inserto1=1205;}
		if($zona1=="Reyes")
		{	$zona_inserto1=1234;}
		if($zona1=="Rurrenabaque")
		{	$zona_inserto1=1237;}
		if($zona1=="San Borja")
		{	$zona_inserto1=1238;}
		if($zona1=="San Buenaventura")
		{	$zona_inserto1=1238;}
		if($zona1=="Yucumo")
		{	$zona_inserto1=1242;}
		if($zona1=="San Ignacio de Moxos")
		{	$zona_inserto1=1241;}
		if($zona1=="Cobija")
		{	$zona_inserto1=1232;}
		if($zona1=="Guayaramerin")
		{	$zona_inserto1=1213;}
		if($zona1=="Riberalta")
		{	$zona_inserto1=1235;}
		
	$sql_inserta_zonas="insert into direcciones_medicos values('$codigo','$zona_inserto1','$direccion1','Consultorio I')";
	$resp_inserta_zonas=mysql_query($sql_inserta_zonas);
	if($zona2!="")
	{	if($zona2=="Trinidad")
		{	$zona_inserto2=1205;}
		if($zona2=="Reyes")
		{	$zona_inserto2=1234;}
		if($zona2=="Rurrenabaque")
		{	$zona_inserto2=1237;}
		if($zona2=="San Borja")
		{	$zona_inserto2=1238;}
		if($zona2=="San Buenaventura")
		{	$zona_inserto2=1238;}
		if($zona2=="Yucumo")
		{	$zona_inserto2=1242;}
		if($zona2=="San Ignacio de Moxos")
		{	$zona_inserto2=1241;}
		if($zona2=="Cobija")
		{	$zona_inserto2=1232;}
		if($zona2=="Guayaramerin")
		{	$zona_inserto2=1213;}
		if($zona2=="Riberalta")
		{	$zona_inserto2=1235;}
		$sql_inserta_zonas2="insert into direcciones_medicos values('$codigo','$zona_inserto2','$direccion2','Consultorio II')";
		echo $sql_inserta_zonas2;
		$resp_inserta_zonas2=mysql_query($sql_inserta_zonas2);
	}
	
	echo "$paterno $materno $nombres $codigo<br>";
}
?>