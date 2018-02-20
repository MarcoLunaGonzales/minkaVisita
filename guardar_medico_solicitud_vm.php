<?php

$cod_med = $_GET['cod'];

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
$fecha_final = '2012-' . $fecha[1] . '-' . $fecha[0];

$telefono = $_POST['telefono'];
$telf_celular = $_POST['telf_celular'];
$email = $_POST['email'];
$hobbie = $_POST['hobbie'];
$hobbie2 = $_POST['hobbie2'];
$estado_civil = $_POST['estado_civil'];
$perfil_psicografico = $_POST['perfil_psicografico'];
$lineas = $_POST['lineass'];

$centro_medico = $_POST['centro_medico'];
$direccionvial1 = $_POST['direccionvial1'];
$direccion1 = $_POST['direccion1'];
$num_casa1 = $_POST['num_casa1'];
$zona1 = $_POST['zona1'];

$espe1 = $_POST['espe1'];

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

$ponderacion_especialidad = mysql_query("Select ponderacion from especialidades_ponderacion where especialidad = '$espe1' ");
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

/* Fin Ponderacion de datos */

/* Datos generales Insterst */

$sql_verificacion = mysql_num_rows(mysql_query("Select * from medicos where cod_med = $cod_med"));

if ($sql_verificacion == 1) {

    $resp = mysql_query("Update medicos set ap_pat_med = '$ap_paterno' , ap_mat_med = '$ap_materno' , nom_med = '$nombres' , fecha_nac_med = '$fecha_final' 
            , telf_med = '$telefono' , telf_celular_med = '$telf_celular'  , email_med = '$email' , hobbie_med = '$hobbie' , hobbie_med2 = '$hobbie2' , estado_civil_med = '$estado_civil' 
            , perfil_psicografico_med = '$perfil_psicografico', estado_registro = 3, categorizacion_medico = '$categoria_medico_sistea_final' where cod_med = $cod_med  ");
} else {
    
}

$sql1_verificacion = mysql_num_rows(mysql_query("Select * from direcciones_medicos where cod_med = $cod_med"));

if ($sql1_verificacion >= 1) {

    $sql1 = " UPDATE direcciones_medicos set cod_zona = '$zona1' , direccion = '$direccion1' , direccionvial = '$direccionvial1' , num_casa = '$num_casa1' 
            , cod_centro_medico = '$centro_medico' where cod_med = $cod_med";
	echo $sql1;
	$resp1=mysql_query($sql1);
	
} else {
	$sql1="insert into direcciones_medicos values ('$cod_med','$zona1','$direccion1','$direccionvial1','$num_casa1','1','1','$centro_medico')";
	echo $sql1;
	$respxx=mysql_query($sql1);
    
}

$sql_espe_verificacion = mysql_num_rows(mysql_query("SELECT * from especialidades_medicos where cod_med = $cod_med "));

if ($sql_espe_verificacion == 1) {

    $resp_espe = mysql_query("UPDATE especialidades_medicos set cod_especialidad = '$espe1' where cod_med =  $cod_med ");
} else {
    
}

/* Fin Datos generales Insterst */


/*  Datos categorizacion Insterst */

$sql_farmacias_referencia_verificacion = mysql_num_rows(mysql_query("select * from farmacias_referencia_medico where cod_med = $cod_med"));

if ($sql_farmacias_referencia_verificacion > 0) {

    mysql_query("DELETE from farmacias_referencia_medico where cod_med = $cod_med ");

    $sql_farmacias_referecnia = mysql_query("Insert into farmacias_referencia_medico (cod_med, nombre_farmacia, direccion_farmacia) values ($cod_med, '$name_farm_1', '$dir_farm_1')");

    if ($name_farm_2 != '') {
        $sql_farmacias_referecnia2 = mysql_query("Insert into farmacias_referencia_medico (cod_med, nombre_farmacia, direccion_farmacia) values ($cod_med, '$name_farm_2', '$dir_farm_2')");
    }
    if ($name_farm_3 != '') {
        $sql_farmacias_referecnia3 = mysql_query("Insert into farmacias_referencia_medico (cod_med, nombre_farmacia, direccion_farmacia) values ($cod_med, '$name_farm_3', '$dir_farm_3')");
    }
}

$sql_categorizacion_medico_verificacion = mysql_num_rows(mysql_query("SELECT * from categorizacion_medico where cod_med = $cod_med "));

if ($sql_categorizacion_medico_verificacion == 1) {

    $sql_categorizacion_medico = mysql_query("UPDATE categorizacion_medico set sexo = '$sexo' , edad = $edad , n_pacientes = '$paci' , tiene_preferencia = '$prescriptiva' , nivel = '$nivel' , costo = '$consulta' where cod_med = $cod_med ");
} else {

    $sql_categorizacion_medico = mysql_query("INSERT into categorizacion_medico (cod_med,sexo,edad,n_pacientes,tiene_preferencia,nivel,costo) values ($cod_med,'$sexo',$edad,$paci,'$prescriptiva','$nivel',$consulta)");
}

$sql_linea = mysql_query("update categorias_lineas set codigo_linea = $lineas where cod_med = $cod_med ");

/*  Fin Datos categorizacion Insterst */

$sql_verificacion1 = mysql_query("Select cod_ciudad, cod_visitador from medicos where cod_med = $cod_med");
$cod_ciudad = mysql_result($sql_verificacion1, 0, 0);
$codigo_visitador = mysql_result($sql_verificacion1, 0, 1);

if ($resp == 1 && $resp1 == 1 && $resp_espe == 1 && $sql_farmacias_referecnia == 1 || ( $sql_farmacias_referecnia2 == 1 || $sql_farmacias_referecnia3 == 1 ) && $sql_categorizacion_medico == 1 && $sql_linea == 1) {
//    echo "<script language='Javascript'>
//  alert('Los datos se guardaron correctamente.');
//  location.href='medicos_solicitados_lista.php';
//  </script>";
  
  echo "<script language='Javascript'>
  alert('Los datos se guardaron correctamente.');
  location.href='envios_mails/envio_edicion_medico.php?medico=$medico&territorio=$cod_ciudad&dia=$fecha_registro&visitador=$codigo_visitador';
  </script>";
} else {
    echo "<script language='Javascript'>
  
  alert('Los datos NO se guardaron correctamente verifique los datos.');
  location.href='medicos_solicitados_lista.php';
  </script>";
}
?>