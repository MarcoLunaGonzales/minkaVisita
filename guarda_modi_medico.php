<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005*/


require("conexion.inc");
require("estilos_administracion.inc");
$da=explode("|,",$datos);
$num_elementos=sizeof($datos);
$fecha=$da[3];
$fecha_nac=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];

$sql="update medicos set ap_pat_med='$da[0]', ap_mat_med='$da[1]', nom_med='$da[2]', fecha_nac_med='$fecha_nac', telf_med='$da[4]', telf_celular_med='$da[5]', email_med='$da[6]', hobbie_med='$da[7]', estado_civil_med='$da[8]',nombre_secre_med='$da[9]', perfil_psicografico_med='$da[10]', cod_ciudad='$da[11]' where cod_med=$cod_med";
$resp=mysql_query($sql);

$sql1="update direcciones_medicos set cod_zona='$da[14]', direccion='$da[12]' where cod_med=$cod_med and descripcion='Consultorio I'";
$resp1=mysql_query($sql1);

$sql_especialidades="delete from especialidades_medicos where cod_med=$cod_med";
$resp_especialidades=mysql_query($sql_especialidades);

$vector_especialidades=split(",",$vectorEspe);
for($i=0;$vector_especialidades[$i];$i++){
	$cod_especialidad=$vector_especialidades[$i];
	if($cod_especialidad!=''){
		$inserta_espe="insert into especialidades_medicos values($cod_med, '$cod_especialidad', 'Especialidad')";
		$resp_inserta_espe=mysql_query($inserta_espe);
	}
}

//$sql1="update especialidades_medicos set cod_especialidad='$da[23]' where cod_med=$cod_med and descripcion='Especialidad'";
//$resp1=mysql_query($sql1);
//modifica las especialidades en lineas
//$sql_mod="update categorias_lineas set cod_especialidad='$da[23]' where cod_med='$cod_med'";
//$resp_mod=mysql_query($sql_mod);

	if($dir2==1)
	{
		$sql_aux="select * from direcciones_medicos where cod_med=$cod_med and descripcion='Consultorio II'";
		$resp_aux=mysql_query($sql_aux);
		$num_filas=mysql_num_rows($resp_aux);
		if($num_filas==1)
		{	$sql1="update direcciones_medicos set cod_zona='$da[17]', direccion='$da[15]' where cod_med=$cod_med and descripcion='Consultorio II'";
	  		$resp1=mysql_query($sql1);
		}
		else
		{
			$sql1="insert into direcciones_medicos values('$cod_med','$da[17]','$da[15]','Consultorio II',2)";
			$resp1=mysql_query($sql1);
		}

	}
	else
	{
		$sql1="delete from direcciones_medicos where cod_med=$cod_med and descripcion='Consultorio II'";
	  	$resp1=mysql_query($sql1);
	}
	if($dir3==1)
	{	$sql_aux="select * from direcciones_medicos where cod_med=$cod_med and descripcion='Consultorio III'";
		$resp_aux=mysql_query($sql_aux);
		$num_filas=mysql_num_rows($resp_aux);
		if($num_filas==1)
		{
			$sql1="update direcciones_medicos set cod_zona='$da[21]', direccion='$da[19]' where cod_med=$cod_med and descripcion='Consultorio III'";
	  		$resp1=mysql_query($sql1);
		}
		else
		{
			$sql1="insert into direcciones_medicos values('$cod_med','$da[21]','$da[19]','Consultorio III',3)";
			$resp1=mysql_query($sql1);
		}
	}
	else
	{
		$sql1="delete from direcciones_medicos where cod_med=$cod_med and descripcion='Consultorio III'";
	  	$resp1=mysql_query($sql1);
	}
	$codigo_ciudad=$da[11];
	echo "<script language='Javascript'>
			alert('Los datos se modificaron correctamente.');
			location.href='navegador_medicos2.php?cod_ciudad=$codigo_ciudad';
			</script>";
?>