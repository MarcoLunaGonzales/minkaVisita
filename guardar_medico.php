<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * 
 * @autor : Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * *
 * @copyright 2005
 */
require("conexion.inc");
require("estilos_administracion.inc");
$ap_paterno = $_POST['ap_paterno'];
$ap_materno = $_POST['ap_materno'];
$nombres = $_POST['nombres'];
$exafinicial = $_POST['exafinicial'];
$telefono = $_POST['telefono'];
$telf_celular = $_POST['telf_celular'];
$email = $_POST['email'];
$hobbie = $_POST['hobbie'];
$estado_civil = $_POST['estado_civil'];
$perfil_psicografico = $_POST['perfil_psicografico'];
$nombre_secretaria = $_POST['nombre_secretaria'];
$cod_ciudad = $_POST['cod_ciudad'];
$tipoConsultorio1 = $_POST['tipoConsultorio1'];
$direccion1 = $_POST['direccion1'];
$distrito1 = $_POST['distrito1'];
$zona1 = $_POST['zona1'];
$tipoConsultorio2 = $_POST['tipoConsultorio2'];
$direccion2 = $_POST['direccion2'];
$distrito2 = $_POST['distrito2'];
$zona2 = $_POST['zona2'];
$tipoConsultorio3 = $_POST['tipoConsultorio3'];
$direccion3 = $_POST['direccion3'];
$distrito3 = $_POST['distrito3'];
$zona3 = $_POST['zona3'];
$espe1=$_POST['espe1'];
$espe2=$_POST['espe2'];
$espe3=$_POST['espe3'];
$espe4=$_POST['espe4'];
$espe5=$_POST['espe5'];

$sql_aux = "select cod_med from medicos order by cod_med desc";
$resp_aux = mysql_query($sql_aux);
$num_filas = mysql_num_rows($resp_aux);
if ($num_filas == 0) {
    $cod_med = 1000;
} else {
    $dat = mysql_fetch_array($resp_aux);
    $codigo = $dat[0];
    $cod_med = $codigo + 1;
} 
$sql = "insert into medicos (cod_med, ap_pat_med, ap_mat_med, nom_med, fecha_nac_med, telf_med, telf_celular_med, email_med, 
	hobbie_med, estado_civil_med, nombre_secre_med, perfil_psicografico_med, cod_ciudad) values 
	($cod_med, '$ap_paterno','$ap_materno','$nombres','$exafinicial','$telefono','$telf_celular','$email','$hobbie','$estado_civil',
	'$nombre_secretaria','$perfil_psicografico','$cod_ciudad')";
$resp = mysql_query($sql);

if($direccion1!=""){
	$sql1 = "insert into direcciones_medicos (cod_med, cod_zona, direccion, numero_direccion, cod_tipo_consultorio) 
			values('$cod_med','$zona1','$direccion1', 1, $tipoConsultorio1)";
	echo $sql1;
	$resp1 = mysql_query($sql1);
}
if($direccion2!=""){
	$sql2 = "insert into direcciones_medicos (cod_med, cod_zona, direccion, numero_direccion, cod_tipo_consultorio) 
			values('$cod_med','$zona2','$direccion2', 2, $tipoConsultorio2)";
	echo $sql2;
	$resp2 = mysql_query($sql2);
}
if($direccion3!=""){
	$sql3 = "insert into direcciones_medicos (cod_med, cod_zona, direccion, numero_direccion, cod_tipo_consultorio) 
			values('$cod_med','$zona3','$direccion3', 3, $tipoConsultorio3)";
	echo $sql3;
	$resp3 = mysql_query($sql3);
}
if($espe1!=""){
	$sql_espe="insert into especialidades_medicos (cod_med, cod_especialidad, cod_tipo_especialidad) 
				values ($cod_med, '$espe1', 1)";
	$resp_espe=mysql_query($sql_espe);
}
if($espe2!=""){
	$sql_espe="insert into especialidades_medicos (cod_med, cod_especialidad, cod_tipo_especialidad) 
				values ($cod_med, '$espe2', 2)";
	$resp_espe=mysql_query($sql_espe);
}
if($espe3!=""){
	$sql_espe="insert into especialidades_medicos (cod_med, cod_especialidad, cod_tipo_especialidad) 
				values ($cod_med, '$espe3', 3)";
	$resp_espe=mysql_query($sql_espe);
}
if($espe4!=""){
	$sql_espe="insert into especialidades_medicos (cod_med, cod_especialidad, cod_tipo_especialidad) 
				values ($cod_med, '$espe4', 3)";
	$resp_espe=mysql_query($sql_espe);
}
if($espe5!=""){
	$sql_espe="insert into especialidades_medicos (cod_med, cod_especialidad, cod_tipo_especialidad) 
				values ($cod_med, '$espe5', 3)";
	$resp_espe=mysql_query($sql_espe);
}
/*echo "<script language='Javascript'>
			alert('Los datos se guardaron correctamente.');
			location.href='navegador_medicos2.php?cod_ciudad=$cod_ciudad';
			</script>";*/
?>