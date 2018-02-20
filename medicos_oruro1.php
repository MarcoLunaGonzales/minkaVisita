<?php
require("conexion.inc");
$sql_busca="select paterno, materno, nombres, breskot, medic, pharma, provincia, vidiline, otra,
	cb, cm, cph, cp, cv, direccion, zona, visitador, telefono, celular
	 from medicos_oruro order by paterno, materno";
echo $sql_busca;
$resp_busca=mysql_query($sql_busca);
echo $sql_busca;
while($dat_busca=mysql_fetch_array($resp_busca))
{	$paterno=$dat_busca[0];
	$materno=$dat_busca[1];
	$nombres=$dat_busca[2];
	$breskot=$dat_busca[3];
	$medic=$dat_busca[4];
	$pharma=$dat_busca[5];
	$provincia=$dat_busca[6];
	$vidiline=$dat_busca[7];
	$otra=$dat_busca[8];
	$cb=$dat_busca[9];
	$cm=$dat_busca[10];
	$cph=$dat_busca[11];
	$cp=$dat_busca[12];
	$cv=$dat_busca[13];
	$direccion=$dat_busca[14];
	$zona=$dat_busca[15];
	$visitador=$dat_busca[16];
	$telefono=$dat_busca[17];
	$celular=$dat_busca[18];
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
	$sql_inserta="insert into medicos values('$codigo','$paterno','$materno','$nombres','$fecha_nac','$telefono','$celular','$email','$hobbie','$estado_civil','$secretaria','$perfil_psico','104')";
	$resp_inserta=mysql_query($sql_inserta);
	$desc_espe='Especialidad I';
	$contador_espe=1;
	if($breskot!="")
	{	$sql_inserta_espe="insert into especialidades_medicos values('$codigo','$breskot','$desc_espe')";
		$resp_inserta_espe=mysql_query($sql_inserta_espe);
		$sql_inserta_linea=mysql_query("insert into categorias_lineas values('1008','$codigo','$breskot','$cb')");
		$sql_inserta_vis=mysql_query("insert into medico_asignado_visitador values('$codigo','1104','1008')");
		$sql_inserta_vis=mysql_query("insert into medico_asignado_visitador values('$codigo','1105','1008')");
		$contador_espe++;
	}
	if($medic!="")
	{	if($contador_espe==1){$desc_espe='Especialidad I';}
		if($contador_espe==2){$desc_espe='Especialidad II';}
		$sql_inserta_espe="insert into especialidades_medicos values('$codigo','$medic','$desc_espe')";
		$resp_inserta_espe=mysql_query($sql_inserta_espe);
		$sql_inserta_linea=mysql_query("insert into categorias_lineas values('1007','$codigo','$medic','$cm')");
		if($visitador=="RUBEN")
		{	$sql_inserta_vis=mysql_query("insert into medico_asignado_visitador values('$codigo','1104','1007')");
		}
		if($visitador=="PATRICIA")
		{	$sql_inserta_vis=mysql_query("insert into medico_asignado_visitador values('$codigo','1105','1007')");
		}
		$contador_espe++;
	}
	if($pharma!="")
	{	if($contador_espe==1){$desc_espe='Especialidad I';}
		if($contador_espe==2){$desc_espe='Especialidad II';}
		if($contador_espe==3){$desc_espe='Especialidad III';}
		$sql_inserta_espe="insert into especialidades_medicos values('$codigo','$pharma','$desc_espe')";
		$resp_inserta_espe=mysql_query($sql_inserta_espe);
		$sql_inserta_linea=mysql_query("insert into categorias_lineas values('1005','$codigo','$pharma','$cph')");
		$sql_inserta_vis=mysql_query("insert into medico_asignado_visitador values('$codigo','1106','1005')");
		$contador_espe++;
	}
	if($provincia!="")
	{	if($contador_espe==1){$desc_espe='Especialidad I';}
		if($contador_espe==2){$desc_espe='Especialidad II';}
		if($contador_espe==3){$desc_espe='Especialidad III';}
		if($contador_espe==4){$desc_espe='Especialidad IV';}
		$sql_inserta_espe="insert into especialidades_medicos values('$codigo','$provincia','$desc_espe')";
		$resp_inserta_espe=mysql_query($sql_inserta_espe);
		$sql_inserta_linea=mysql_query("insert into categorias_lineas values('1009','$codigo','$provincia','A')");
		if($visitador=="RUBEN")
		{	$sql_inserta_vis=mysql_query("insert into medico_asignado_visitador values('$codigo','1104','1009')");
		}
		if($visitador=="ALEX")
		{	$sql_inserta_vis=mysql_query("insert into medico_asignado_visitador values('$codigo','1106','1009')");
		}
		$contador_espe++;
	}
	if($vidiline!="")
	{	if($contador_espe==1){$desc_espe='Especialidad I';}
		if($contador_espe==2){$desc_espe='Especialidad II';}
		if($contador_espe==3){$desc_espe='Especialidad III';}
		if($contador_espe==4){$desc_espe='Especialidad IV';}
		$sql_inserta_espe="insert into especialidades_medicos values('$codigo','$vidiline','$desc_espe')";
		$resp_inserta_espe=mysql_query($sql_inserta_espe);
		$sql_inserta_linea=mysql_query("insert into categorias_lineas values('1006','$codigo','$vidiline','$cv')");
		$sql_inserta_vis=mysql_query("insert into medico_asignado_visitador values('$codigo','1104','1006')");
		$sql_inserta_vis=mysql_query("insert into medico_asignado_visitador values('$codigo','1105','1006')");
		$contador_espe++;
	}
	if($otra!=="")
	{	if($contador_espe==1){$desc_espe='Especialidad I';}
		if($contador_espe==2){$desc_espe='Especialidad II';}
		if($contador_espe==3){$desc_espe='Especialidad III';}
		if($contador_espe==4){$desc_espe='Especialidad IV';}
		$sql_inserta_espe="insert into especialidades_medicos values('$codigo','$otra','$desc_espe')";
		$resp_inserta_espe=mysql_query($sql_inserta_espe);
		
	}
	if($medic!="" and $pharma!="")
	{	$sql_inserta_vis=mysql_query("insert into medico_asignado_visitador values('$codigo','1104','1007')");
		$sql_inserta_vis=mysql_query("insert into medico_asignado_visitador values('$codigo','1106','1005')");
	}
	$sql_inserta_zonas="insert into direcciones_medicos values('$codigo','$zona','$direccion','Consultorio I',1)";
	$resp_inserta_zonas=mysql_query($sql_inserta_zonas);
	echo "$paterno $materno $nombres $codigo<br>";
}
?>