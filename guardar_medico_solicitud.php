<?php

require("conexion.inc");
require("estilos_administracion.inc");

$fecha_registro = date('Y-m-d');

/* Datos Generales */
$ap_paterno = $_POST['ap_paterno'];
$ap_materno = $_POST['ap_materno'];
$nombres = $_POST['nombres'];
$medico = $nombres . " " . $ap_paterno . " " . $ap_materno;

$exafinicial = $_POST['exafinicial'];
$fecha = explode('/', $exafinicial);
$fecha_final = '2013-' . $fecha[1] . '-' . $fecha[0];

$telefono = $_POST['telefono'];
$telf_celular = $_POST['telf_celular'];
$email = $_POST['email'];
$hobbie = $_POST['hobbie'];
$hobbie2 = $_POST['hobbie2'];
$estado_civil = $_POST['estado_civil'];
$perfil_psicografico = $_POST['perfil_psicografico'];
$cod_ciudad = $_POST['cod_ciudad'];
$lineas = $_POST['lineass'];

$centro_medico = $_POST['centro_medico'];
if($centro_medico==""){
	$centro_medico=0;
}
$direccionvial1 = $_POST['direccionvial1'];
$direccion1 = $_POST['direccion1'];
$num_casa1 = $_POST['num_casa1'];
$zona1 = $_POST['zona1'];

$espe1 = $_POST['espe1'];

$codigo_visitador = $global_visitador;

/* Fin datos generales */

/* Categorizacion */

$name_farm_1 = $_POST['name_farm_1'];
$name_farm_2 = $_POST['name_farm_2'];
$name_farm_3 = $_POST['name_farm_3'];

$dir_farm_1 = $_POST['dir_farm_1'];
$dir_farm_2 = $_POST['dir_farm_2'];
$dir_farm_3 = $_POST['dir_farm_3'];

$sexo = $_POST['sexo'];
$edad = $_POST['edad'];
$paci = $_POST['paci'];
$prescriptiva = $_POST['prescriptiva'];
$nivel = $_POST['nivel'];
$consulta = $_POST['consulta'];

/* Fin Categorizacion */
/* Ponderacion de datos */

/*
$ponderacion_especialidad = mysql_query("SELECT ponderacion from especialidades_ponderacion where especialidad = '$espe1' ");
$num_ponderacion_especialidad = mysql_num_rows($ponderacion_especialidad);

if ($num_ponderacion_especialidad >= 1) {
    while ($row_espe = mysql_fetch_assoc($ponderacion_especialidad)) {
        $ponderacion_especialidad_final = $row_espe['ponderacion'];
    }
} else {
    $ponderacion_especialidad_final = 2;
}

if ($edad == 0) {
    $ponderacion_edad = 0;
}
if ($edad == 2) {
    $ponderacion_edad = 4;
}
if ($edad == 3) {
    $ponderacion_edad = 2;
}
if ($edad == 1 || $edad == 4) {
    $ponderacion_edad = 1;
}

if ($paci < 8) {
    $ponderacion_pacientes = 2;
}
if ($paci < 12 && $paci >= 8) {
    $ponderacion_pacientes = 4;
}
if ($paci < 18 && $paci >= 12) {
    $ponderacion_pacientes = 6;
}
if ($paci >= 18) {
    $ponderacion_pacientes = 8;
}

if ($prescriptiva == 'Alta') {
    $ponderacion_prescrptiva = 4;
}
if ($prescriptiva == 'Media') {
    $ponderacion_prescrptiva = 2;
}
if ($prescriptiva == 'Baja') {
    $ponderacion_prescrptiva = 0;
}

if ($nivel == 'Alta') {
    $ponderacion_nivel = 3;
}
if ($nivel == 'Media') {
    $ponderacion_nivel = 2;
}
if ($nivel == 'Baja') {
    $ponderacion_nivel = 1;
}

$categoria_medico_sistema = $ponderacion_especialidad_final + $ponderacion_pacientes + $ponderacion_prescrptiva + $ponderacion_nivel + $ponderacion_edad;
if ($categoria_medico_sistema < 12) {
    $categoria_medico_sistea_final = 'D';
}
if ($categoria_medico_sistema >= 15) {
    $categoria_medico_sistea_final = 'C';
}
if ($categoria_medico_sistema >= 19) {
    $categoria_medico_sistea_final = 'B';
}
if ($categoria_medico_sistema >= 21) {
    $categoria_medico_sistea_final = 'A';
}
if ($categoria_medico_sistema >= 23) {
    $categoria_medico_sistea_final = 'AA';
}
if ($categoria_medico_sistema >= 27) {
    $categoria_medico_sistea_final = 'AAA';
}
*/
//echo $categoria_medico_sistema;


/* Fin Ponderacion de datos */

/* Datos generales Insterst */

$sql_aux = "SELECT cod_med from medicos order by cod_med desc";
$resp_aux = mysql_query($sql_aux);
$num_filas = mysql_num_rows($resp_aux);
if ($num_filas == 0) {
    $cod_med = 1000;
} else {
    $dat = mysql_fetch_array($resp_aux);
    $codigo = $dat[0];
    $cod_med = $codigo + 1;
}

$sql = "INSERT into medicos (cod_med, ap_pat_med, ap_mat_med, nom_med, fecha_nac_med, telf_med, telf_celular_med, email_med, hobbie_med, hobbie_med2, estado_civil_med, perfil_psicografico_med, cod_ciudad,estado_registro,cod_visitador, categorizacion_medico, fecha_registro) values ($cod_med, '$ap_paterno','$ap_materno','$nombres','$fecha_final','$telefono','$telf_celular','$email','$hobbie','$hobbie2','$estado_civil', '$perfil_psicografico','$cod_ciudad',3,$codigo_visitador, '$categoria_medico_sistea_final','$fecha_registro')";
// echo $sql."<br />";
$resp = mysql_query($sql);

$sql1 = "INSERT into direcciones_medicos (cod_med, cod_zona, direccion, direccionvial, num_casa, numero_direccion ,cod_centro_medico) 
values('$cod_med','$zona1','$direccion1','$direccionvial1', '$num_casa1', 1, '$centro_medico')";

//echo $sql1;

$resp1 = mysql_query($sql1);

$sql_espe = "INSERT into especialidades_medicos (cod_med, cod_especialidad, cod_tipo_especialidad) values ($cod_med, '$espe1', 1)";
$resp_espe = mysql_query($sql_espe);

if($espe2!=""){
	$sql_espe = "INSERT into especialidades_medicos (cod_med, cod_especialidad, cod_tipo_especialidad) values ($cod_med, '$espe2', 2)";
	$resp_espe = mysql_query($sql_espe);
}
/* Fin Datos generales Insterst */


/*  Datos categorizacion Insterst */

/*
$sql_farmacias_referecnia = mysql_query("INSERT into farmacias_referencia_medico (cod_med, nombre_farmacia, direccion_farmacia) values ($cod_med, '$name_farm_1', '$dir_farm_1')");

if ($name_farm_2 != '') {
    $sql_farmacias_referecnia2 = mysql_query("INSERT into farmacias_referencia_medico (cod_med, nombre_farmacia, direccion_farmacia) values ($cod_med, '$name_farm_2', '$dir_farm_2')");
}
if ($name_farm_3 != '') {
    $sql_farmacias_referecnia3 = mysql_query("INSERT into farmacias_referencia_medico (cod_med, nombre_farmacia, direccion_farmacia) values ($cod_med, '$name_farm_3', '$dir_farm_3')");
}

$sql_categorizacion_medico = mysql_query("INSERT into categorizacion_medico (cod_med,sexo,edad,n_pacientes,tiene_preferencia,nivel,costo) values ($cod_med,'$sexo',$edad,$paci,'$prescriptiva','$nivel',$consulta)");
*/

$sql_medico_asignado = mysql_query("INSERT into medico_asignado_visitador (cod_med,codigo_visitador,codigo_linea) values($cod_med, $codigo_visitador, $lineas)");

$sql_linea_medico = mysql_query("INSERT into categorias_lineas (codigo_linea,cod_med,cod_especialidad,frecuencia_linea,frecuencia_permitida) values($lineas,$cod_med,'$espe1',0,0)");

/*  Fin Datos categorizacion Inserts */

/* Guardar Ponderacion categorizacion medico */

//$ponderacion = mysql_query("INSERT into categorias_lineas (cod_med, cod_especialidad, categoria_med, frecuencia_liena, frecuencia_permitida) VALUES ($cod_med, '$espe1', '$categoria_medico_sistea_final', 0, 0)");

/* FIN Guardar Ponderacion categorizacion medico */


if ($resp == 1 && $resp1 == 1 && $resp_espe == 1 && $sql_medico_asignado == 1 && $sql_linea_medico == 1) {
    /*echo "<script language='Javascript'>
    alert('Los datos se guardaron correctamente.');
    location.href='envios_mails/envio_alta_medico.php?medico=$medico&territorio=$cod_ciudad&dia=$fecha_registro&visitador=$codigo_visitador';
    </script>";*/
	
	echo "<script language='Javascript'>
    alert('Los datos se guardaron correctamente.');
    location.href='medicos_solicitados_lista.php';
    </script>";
	
} else {
    echo "<script language='Javascript'>
    alert('Los datos NO se guardaron correctamente verifique los datos.');
    location.href='medicos_solicitados_lista.php';
    </script>";
}

?>