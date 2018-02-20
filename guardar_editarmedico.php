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
$cod_med = $_POST['cod_med'];
$ap_paterno = $_POST['ap_paterno'];
$ap_materno = $_POST['ap_materno'];
$nombres = $_POST['nombres'];
$fecha = $_POST['exafinicial'];
$fecha_real = $fecha[6] . $fecha[7] . $fecha[8] . $fecha[9] . "-" . $fecha[3] . $fecha[4] . "-" . $fecha[0] . $fecha[1];
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
$espe1 = $_POST['espe1'];
$espe2 = $_POST['espe2'];
$espe3 = $_POST['espe3'];
$espe4 = $_POST['espe4'];
$espe5 = $_POST['espe5'];
$codCatCUP=$_POST['codigocategoriacloseup'];
$codCUP=$_POST['categoriacloseup'];
$estadoMedico=$_POST['estadoMedico'];


$sql = "update medicos set ap_pat_med='$ap_paterno', ap_mat_med='$ap_materno', nom_med='$nombres', 
		fecha_nac_med='$fecha_real', telf_med='$telefono', telf_celular_med='$telf_celular', email_med='$email', 
		hobbie_med='$hobbie', estado_civil_med='$estado_civil', nombre_secre_med='$nombre_secretaria', 
		perfil_psicografico_med='$perfil_psicografico', cod_catcloseup='$codCatCUP', cod_closeup='$codCUP', 
		estado_registro='$estadoMedico' where cod_med='$cod_med'";
$resp = mysql_query($sql);

//borramos direcciones
$sqlBorraDir = "delete from direcciones_medicos where cod_med='$cod_med'";
$respBorrarDir = mysql_query($sqlBorraDir);

if ($direccion1 != "") {
    $sql1 = "insert into direcciones_medicos (cod_med, cod_zona, direccion, numero_direccion, cod_tipo_consultorio) 
			values('$cod_med','$zona1','$direccion1', 1, $tipoConsultorio1)";
    //echo $sql1;
    $resp1 = mysql_query($sql1);
}
if ($direccion2 != "") {
    $sql2 = "insert into direcciones_medicos (cod_med, cod_zona, direccion, numero_direccion, cod_tipo_consultorio) 
			values('$cod_med','$zona2','$direccion2', 2, $tipoConsultorio2)";
    //echo $sql2;
    $resp2 = mysql_query($sql2);
}
if ($direccion3 != "") {
    $sql3 = "insert into direcciones_medicos (cod_med, cod_zona, direccion, numero_direccion, cod_tipo_consultorio) 
			values('$cod_med','$zona3','$direccion3', 3, $tipoConsultorio3)";
    //echo $sql3;
    $resp3 = mysql_query($sql3);
}

//borramos Especialidades
$sqlBorraEspe = "delete from especialidades_medicos where cod_med='$cod_med'";
$respBorrarEspe = mysql_query($sqlBorraEspe);

if ($espe1 != "") {
    $sql_espe = "insert into especialidades_medicos (cod_med, cod_especialidad, cod_tipo_especialidad) 
				values ($cod_med, '$espe1', 1)";
    $resp_espe = mysql_query($sql_espe);
}

if($espe2!=""){
	$sqlDel="delete from especialidades_medicos where cod_tipo_especialidad=2 and cod_med=$cod_med";
	$respDel=mysql_query($sqlDel);
	
	$sql_espe = "INSERT into especialidades_medicos (cod_med, cod_especialidad, cod_tipo_especialidad) values ($cod_med, '$espe2', 2)";
	$resp_espe = mysql_query($sql_espe);
	
}
/* CAtegorias lienas */

/*$sql_cat_lin = mysql_query("select * from categorias_lineas where cod_med = $cod_med");
if (mysql_num_rows($sql_cat_lin) > 1) {
    $msn = "El medico tiene mas de una linea, avise al administrador para que se haga el cambio manual.";
} else {
*/    
mysql_query("update categorias_lineas set cod_especialidad = '$espe1' where cod_med = $cod_med ");
$seleccion_medicos_rutero="select rmd.cod_contacto, rmd.orden_visita from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd
					where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and
					rmd.cod_med='$cod_med'";
$resp_medicos_rutero=mysql_query($seleccion_medicos_rutero);
while($datos_medicos=mysql_fetch_array($resp_medicos_rutero))
{	$cod_contacto=$datos_medicos[0];
	$orden_visita=$datos_medicos[1];
	$sql_actualiza_rutero="update rutero_maestro_detalle set cod_especialidad='$espe1'
						   where cod_contacto='$cod_contacto' and orden_visita='$orden_visita'";
	$resp_actualiza_rutero=mysql_query($sql_actualiza_rutero);
}

/*    $msn = "Los datos se guardaron correctamente.";
}*/


echo "<script language='Javascript'>
			alert('$msn');
			location.href='navegador_medicos2.php?cod_ciudad=$cod_ciudad&estado=1';
			</script>";
?>