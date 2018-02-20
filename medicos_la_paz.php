<?php
require("conexion.inc");
$sql_busca="select paterno, materno, nombres, fecha_nac, especialidad, direccion1, zona1, telefono, celular, email, 
			direccion2, zona2, hobbie, estado_civil, perfil_psico, secretaria 
			from medicos_la_paz order by paterno";
$resp_busca=mysql_query($sql_busca);
echo $sql_busca;
while($dat_busca=mysql_fetch_array($resp_busca))
{	$paterno=$dat_busca[0];
	$materno=$dat_busca[1];
	$nombres=$dat_busca[2];
	$fecha_nac=$dat_busca[3];
	$especialidad=$dat_busca[4];
	$direccion1=$dat_busca[5];
	$zona1=$dat_busca[6];
	$telefono=$dat_busca[7];
	$celular=$dat_busca[8];
	$email=$dat_busca[9];
	$direccion2=$dat_busca[10];
	$zona2=$dat_busca[11];
	$hobbie=$dat_busca[12];
	$estado_civil=$dat_busca[13];
	$perfil_psico=$dat_busca[14];
	$secretaria=$dat_busca[15];
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
	$sql_inserta="insert into medicos values('$codigo','$paterno','$materno','$nombres','$fecha_nac','$telefono','$celular','$email','$hobbie','$estado_civil','$secretaria','$perfil_psico','113')";
	$resp_inserta=mysql_query($sql_inserta);
	$sql_inserta_espe="insert into especialidades_medicos values('$codigo','$especialidad','Especialidad')";
	$resp_inserta_espe=mysql_query($sql_inserta_espe);
	if($zona1=="ZONA NORTE")
	{	$zona_inserto1=1012;}
	if($zona1=="ZONA CENTRAL")
	{	$zona_inserto1=1012;}
	if($zona1=="ZONA SUR")
	{	$zona_inserto1=1012;}
	if($zona1=="ZONA MIRAFLORES")
	{	$zona_inserto1=1012;}
	if($zona1=="SAN PEDRO")
	{	$zona_inserto1=1012;}
	if($zona1=="SOPOCACHI")
	{	$zona_inserto1=1012;}
	if($zona1=="AV. ARCE")
	{	$zona_inserto1=1012;}
	if($zona1=="VILLA COPACABANA")
	{	$zona_inserto1=1012;}
	if($zona1=="SAN JORGE")
	{	$zona_inserto1=1012;}
	if($zona1=="BUENOS AIRES")
	{	$zona_inserto1=1012;}
	if($zona1=="GARITA DE LIMA")
	{	$zona_inserto1=1012;}
	if($zona1=="AV. 6 DE AGOSTO")
	{	$zona_inserto1=1012;}
	if($zona1=="ACHUMANI")
	{	$zona_inserto1=1012;}
	if($zona1=="ACHUMANI")
	{	$zona_inserto1=1012;}
	if($zona1=="VILLA FATIMA")
	{	$zona_inserto1=1012;}
	$sql_inserta_zonas="insert into direcciones_medicos values('$codigo','$zona_inserto1','$direccion1','Consultorio I')";
	$resp_inserta_zonas=mysql_query($sql_inserta_zonas);
	if($zona2!="")
	{	if($zona2=="ZONA NORTE")
		{	$zona_inserto2=1012;}
		if($zona2=="ZONA CENTRAL")
		{	$zona_inserto2=1012;}
		if($zona2=="ZONA SUR")
		{	$zona_inserto2=1012;}
		if($zona2=="ZONA MIRAFLORES")
		{	$zona_inserto2=1012;}
		if($zona2=="SAN PEDRO")
		{	$zona_inserto2=1012;}
		if($zona2=="SOPOCACHI")
		{	$zona_inserto2=1012;}
		if($zona2=="AV. ARCE")
		{	$zona_inserto2=1012;}
		if($zona2=="VILLA COPACABANA")
		{	$zona_inserto2=1012;}
		if($zona2=="SAN JORGE")
		{	$zona_inserto2=1012;}
		if($zona2=="BUENOS AIRES")
		{	$zona_inserto2=1012;}
		if($zona2=="GARITA DE LIMA")
		{	$zona_inserto2=1012;}
		if($zona2=="AV. 6 DE AGOSTO")
		{	$zona_inserto2=1012;}
		if($zona2=="ACHUMANI")
		{	$zona_inserto2=1012;}
		if($zona2=="ACHUMANI")
		{	$zona_inserto2=1012;}
		if($zona2=="VILLA FATIMA")
		{	$zona_inserto2=1012;}
		$sql_inserta_zonas2="insert into direcciones_medicos values('$codigo','$zona_inserto2','$direccion2','Consultorio II')";
		echo $sql_inserta_zonas2;
		$resp_inserta_zonas2=mysql_query($sql_inserta_zonas2);
	}
	
	echo "$paterno $materno $nombres $codigo<br>";
}
?>