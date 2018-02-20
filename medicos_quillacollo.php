<?php
require("conexion.inc");
$sql_busca="select paterno, materno, nombres, fecha_nac, especialidad, subespecialidad, direccion1, zona1, 
			direccion2, zona2, direccion3, zona3, telefono, celular, estado_civil,hobbie, perfil_psicografico, secretaria 
			from medicos_cochabamba order by paterno";
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
	$direccion2=$dat_busca[8];
	$zona2=$dat_busca[9];
	$direccion3=$dat_busca[10];
	$zona3=$dat_busca[11];
	$telefono=$dat_busca[12];
	$celular=$dat_busca[13];
	$estado_civil=$dat_busca[14];
	$hobbie=$dat_busca[15];
	$perfil_psico=$dat_busca[16];
	$secretaria=$dat_busca[17];
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
	$sql_inserta="insert into medicos values('$codigo','$paterno','$materno','$nombres','$fecha_nac','$telefono','$celular','$email','$hobbie','$estado_civil','$secretaria','$perfil_psico','102')";
	$resp_inserta=mysql_query($sql_inserta);
	$sql_inserta_espe="insert into especialidades_medicos values('$codigo','$especialidad','Especialidad')";
	$resp_inserta_espe=mysql_query($sql_inserta_espe);
	if($subespecialidad!="")
	{	$sql_inserta_espe="insert into especialidades_medicos values('$codigo','$subespecialidad','Sub-especialidad')";
		$resp_inserta_espe=mysql_query($sql_inserta_espe);
	}
		if($zona1=="CENTRAL")
		{	$zona_inserto1=1275;}
		if($zona1=="NORTE")
		{	$zona_inserto1=1276;}
		if($zona1=="SUR")
		{	$zona_inserto1=1277;}
		if($zona1=="OESTE")
		{	$zona_inserto1=1279;}
		if($zona1=="NOROESTE")
		{	$zona_inserto1=1259;}
		if($zona1=="QLLO")
		{	$zona_inserto1=1275;}
		if($zona1=="ESTE")
		{	$zona_inserto1=1278;}
		if($zona1=="CANCHA")
		{	$zona_inserto1=1280;}
		if($zona1=="LORETO")
		{	$zona_inserto1=1281;}
		if($zona1=="CALA CALA")
		{	$zona_inserto1=1272;}
		if($zona1=="RECOLETA")
		{	$zona_inserto1=1282;}
		if($zona1=="HIPODROMO")
		{	$zona_inserto1=1270;}
		if($zona1=="SAN PEDRO")
		{	$zona_inserto1=1283;}
		if($zona1=="MUYURINA")
		{	$zona_inserto1=1268;}
		if($zona1=="QUERU QUERU")
		{	$zona_inserto1=1266;}
		if($zona1=="SARCO")
		{	$zona_inserto1=1274;}

	$sql_inserta_zonas="insert into direcciones_medicos values('$codigo','$zona_inserto1','$direccion1','Consultorio I',1)";
	$resp_inserta_zonas=mysql_query($sql_inserta_zonas);
	if($zona2!="")
	{	if($zona2=="CENTRAL")
		{	$zona_inserto2=1275;}
		if($zona2=="NORTE")
		{	$zona_inserto2=1276;}
		if($zona2=="SUR")
		{	$zona_inserto2=1277;}
		if($zona2=="OESTE")
		{	$zona_inserto2=1279;}
		if($zona2=="NOROESTE")
		{	$zona_inserto2=1259;}
		if($zona2=="QLLO")
		{	$zona_inserto2=1275;}
		if($zona2=="ESTE")
		{	$zona_inserto2=1278;}
		if($zona2=="CANCHA")
		{	$zona_inserto2=1280;}
		if($zona2=="LORETO")
		{	$zona_inserto2=1281;}
		if($zona2=="CALA CALA")
		{	$zona_inserto2=1272;}
		if($zona2=="RECOLETA")
		{	$zona_inserto2=1282;}
		if($zona2=="HIPODROMO")
		{	$zona_inserto2=1270;}
		if($zona2=="SAN PEDRO")
		{	$zona_inserto2=1283;}
		if($zona2=="MUYURINA")
		{	$zona_inserto2=1268;}
		if($zona2=="QUERU QUERU")
		{	$zona_inserto2=1266;}
		if($zona2=="SARCO")
		{	$zona_inserto2=1274;}
		if($zona2=="SUROESTE")
		{	$zona_inserto2=1261;}
		if($zona2=="SACABA")
		{	$zona_inserto2=1247;}
		if($zona2=="PUNATA")
		{	$zona_inserto2=1249;}

		$sql_inserta_zonas2="insert into direcciones_medicos values('$codigo','$zona_inserto2','$direccion2','Consultorio II',2)";
		echo $sql_inserta_zonas2;
		$resp_inserta_zonas2=mysql_query($sql_inserta_zonas2);
	}
	if($zona3!="")
	{	if($zona3=="CENTRAL")
		{	$zona_inserto3=1275;}
		if($zona3=="NORTE")
		{	$zona_inserto3=1276;}
		if($zona3=="SUR")
		{	$zona_inserto3=1277;}
		if($zona3=="OESTE")
		{	$zona_inserto3=1279;}
		if($zona3=="NOROESTE")
		{	$zona_inserto3=1259;}
		if($zona3=="QLLO")
		{	$zona_inserto3=1275;}
		if($zona3=="ESTE")
		{	$zona_inserto3=1278;}
		if($zona3=="CANCHA")
		{	$zona_inserto3=1280;}
		if($zona3=="LORETO")
		{	$zona_inserto3=1281;}
		if($zona3=="CALA CALA")
		{	$zona_inserto3=1272;}
		if($zona3=="RECOLETA")
		{	$zona_inserto3=1282;}
		if($zona3=="HIPODROMO")
		{	$zona_inserto3=1270;}
		if($zona3=="SAN PEDRO")
		{	$zona_inserto3=1283;}
		if($zona3=="MUYURINA")
		{	$zona_inserto3=1268;}
		if($zona3=="QUERU QUERU")
		{	$zona_inserto3=1266;}
		if($zona3=="SARCO")
		{	$zona_inserto3=1274;}
		if($zona3=="SUROESTE")
		{	$zona_inserto3=1261;}
		$sql_inserta_zonas3="insert into direcciones_medicos values('$codigo','$zona_inserto3','$direccion3','Consultorio III',3)";
		echo $sql_inserta_zonas3;
		$resp_inserta_zonas3=mysql_query($sql_inserta_zonas3);
	}
	
	echo "$paterno $materno $nombres $codigo<br>";
}
?>