<?php

require("conexion.inc");
require("estilos_administracion.inc");

$fecha_registro = date('Y-m-d');

$cod_med = $_POST['cod_med'];
$ap_paterno = $_POST['ap_paterno'];
$ap_materno = $_POST['ap_materno'];
$nombres = $_POST['nombres'];


$exafinicial = $_POST['exafinicial'];
//$fecha_real = $fecha[6] . $fecha[7] . $fecha[8] . $fecha[9] . "-" . $fecha[3] . $fecha[4] . "-" . $fecha[0] . $fecha[1];
$fecha = explode('/', $exafinicial);
$fecha_real = '2012-' . $fecha[1] . '-' . $fecha[0];


$telefono               = $_POST['telefono'];
$telf_celular           = $_POST['telf_celular'];
$email                  = $_POST['email'];
$hobbie                 = $_POST['hobbie'];
$hobbie2                = $_POST['hobbie2'];
$estado_civil           = $_POST['estado_civil'];
$perfil_psicografico    = $_POST['perfil_psicografico'];
$cod_ciudad             = $_POST['cod_ciudad'];
$codigocategoriacloseup = $_POST['codigocategoriacloseup'];
$categoriacloseup       = $_POST['categoriacloseup'];

$centro_medico  = $_POST['centro_medico'];
$direccionvial1 = $_POST['direccionvial1'];
$direccion1     = $_POST['direccion1'];
$num_casa1      = $_POST['num_casa1'];
$zona1          = $_POST['zona1'];
$espe1          = $_POST['espe1'];
$espe2          = $_POST['espe2'];


$lineass    = $_POST['lineass'];
$visitadorr = $_POST['visitadorr'];
$cate_medd  = $_POST['cate_medd'];

/* Categorizacion */

$name_farm_1 = $_POST['name_farm_1'];
$name_farm_2 = $_POST['name_farm_2'];
$name_farm_3 = $_POST['name_farm_3'];

$dir_farm_1 = $_POST['dir_farm_1'];
$dir_farm_2 = $_POST['dir_farm_2'];
$dir_farm_3 = $_POST['dir_farm_3'];

$sexo         = $_POST['sexo'];
$edad         = $_POST['edad'];
$paci         = $_POST['paci'];
$prescriptiva = $_POST['prescriptiva'];
$nivel        = $_POST['nivel'];
$consulta     = $_POST['consulta'];

/* Fin Categorizacion */

/* Ponderacion de datos */

$ponderacion_especialidad = mysql_query("SELECT ponderacion from especialidades_ponderacion where especialidad = '$espe1' " );
$num_ponderacion_especialidad = mysql_num_rows($ponderacion_especialidad);

if($num_ponderacion_especialidad >= 1){
    while($row_espe = mysql_fetch_assoc($ponderacion_especialidad)){
        $ponderacion_especialidad_final = $row_espe['ponderacion'];
    }
}else{
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

$estado_final = $_GET['estaa'];

if ($estado_final == 3) {
    $estado_final_registro = 2;
    $ffeecchhaa = 'fecha_pre_aprobado = ' . " ' $fecha_registro ' ";
}
if ($estado_final == 2) {
    $estado_final_registro = 1;
    $ffeecchhaa = 'fecha_aprobado = ' .  " ' $fecha_registro ' " ;
}
if ($estado_final == '') {
    $estado_final_registro = 2;
    $ffeecchhaa = 'fecha_pre_aprobado = ' . " ' $fecha_registro ' ";
}

if ( $categoriacloseup == ''){
   $categoriacloseup = 0;   
}
$sql = "UPDATE medicos set ap_pat_med='$ap_paterno', ap_mat_med='$ap_materno', nom_med='$nombres', 
fecha_nac_med='$fecha_real', telf_med='$telefono', telf_celular_med='$telf_celular', email_med='$email', 
hobbie_med='$hobbie',hobbie_med2 = '$hobbie2', estado_civil_med='$estado_civil', 
perfil_psicografico_med='$perfil_psicografico', cod_catcloseup = $codigocategoriacloseup, 
cod_closeup = $categoriacloseup  ,estado_registro = $estado_final_registro, 
categorizacion_medico = '$categoria_medico_sistea_final', $ffeecchhaa  where cod_med='$cod_med' ";
$resp = mysql_query($sql);


/*$sql1 = "UPDATE direcciones_medicos set cod_zona = '$zona1', 
direccion = '$direccion1', direccionvial = '$direccionvial1', num_casa = '$num_casa1' , cod_centro_medico = '$centro_medico' where cod_med = $cod_med  ";
//echo $sql1;
$resp1 = mysql_query($sql1);*/

	mysql_query("delete from direcciones_medicos where cod_med='$cod_med'");
	$sql1="insert into direcciones_medicos values ('$cod_med','$zona1','$direccion1','$direccionvial1','$num_casa1','1','1','$centro_medico')";
	echo $sql1;
	$respxx=mysql_query($sql1);


$sql_espe = "UPDATE especialidades_medicos set cod_especialidad = '$espe1' where cod_med = $cod_med ";
$resp_espe = mysql_query($sql_espe);

if($espe2!=""){
	$sqlDel="delete from especialidades_medicos where cod_tipo_especialidad=2 and cod_med=$cod_med";
	$respDel=mysql_query($sqlDel);
	
	$sql_espe = "INSERT into especialidades_medicos (cod_med, cod_especialidad, cod_tipo_especialidad) values ($cod_med, '$espe2', 2)";
	$resp_espe = mysql_query($sql_espe);
	
}



/* Guardamos especialidad y la linea */
if ($estado_final == 3) {
//    $sql_linea = mysql_query("INSERT into categorias_lineas (codigo_linea,cod_med,cod_especialidad,categoria_med,frecuencia_linea,frecuencia_permitida) values($lineass,$cod_med,'$espe1','$cate_medd',0,0)");
    $sql_linea = mysql_query("UPDATE categorias_lineas set codigo_linea = $lineass, cod_especialidad = '$espe1', categoria_med = '$cate_medd' where cod_med = $cod_med ");
//    $sql_visitador = mysql_query("INSERT into medico_asignado_visitador (cod_med,codigo_visitador,codigo_linea) values($cod_med,$visitadorr,$lineass)");
    $sql_visitador = mysql_query("UPDATE medico_asignado_visitador set codigo_visitador = $visitadorr, codigo_linea = $lineass where cod_med = $cod_med");
}

if ($estado_final == 2) {
    $sql_linea = mysql_query("UPDATE categorias_lineas set codigo_linea = $lineass, cod_especialidad = '$espe1', categoria_med = '$cate_medd' where cod_med = $cod_med ");
    $sql_visitador = mysql_query("UPDATE medico_asignado_visitador set codigo_visitador = $visitadorr, codigo_linea = $lineass where cod_med = $cod_med");
}

/* Fin del Guardado de especialidad y la linea */
/*  Datos categorizacion Inserts */

$sql_farmacias_referencia_verificacion = mysql_num_rows(mysql_query("SELECT * from farmacias_referencia_medico where cod_med = $cod_med"));

if ($sql_farmacias_referencia_verificacion > 0) {

    mysql_query("DELETE from farmacias_referencia_medico where cod_med = $cod_med ");

    $sql_farmacias_referecnia = mysql_query("INSERT into farmacias_referencia_medico (cod_med, nombre_farmacia, direccion_farmacia) values ($cod_med, '$name_farm_1', '$dir_farm_1')");

    if ($name_farm_2 != '') {
        $sql_farmacias_referecnia2 = mysql_query("INSERT into farmacias_referencia_medico (cod_med, nombre_farmacia, direccion_farmacia) values ($cod_med, '$name_farm_2', '$dir_farm_2')");
    }
    if ($name_farm_3 != '') {
        $sql_farmacias_referecnia3 = mysql_query("INSERT into farmacias_referencia_medico (cod_med, nombre_farmacia, direccion_farmacia) values ($cod_med, '$name_farm_3', '$dir_farm_3')");
    }
}

$sql_categorizacion_medico_verificacion = mysql_num_rows(mysql_query("SELECT * from categorizacion_medico where cod_med = $cod_med "));

if ($sql_categorizacion_medico_verificacion == 1) {

    // $sql_categorizacion_medico = mysql_query("UPDATE categorizacion_medico set sexo = '$sexo' , edad = $edad , n_pacientes = '$paci' , tiene_preferencia = '$prescriptiva' , nivel = '$nivel' , costo = '$consulta' where cod_med = $cod_med ");

} else{

    $sql_categorizacion_medico = mysql_query("INSERT into categorizacion_medico (cod_med,sexo,edad,n_pacientes,tiene_preferencia,nivel,costo) values ($cod_med,'$sexo',$edad,$paci,'$prescriptiva','$nivel',$consulta)");
    
}

if ($resp == 1 && $resp1 == 1 && $resp_espe == 1 && $sql_farmacias_referecnia == 1 || ( $sql_farmacias_referecnia2 == 1 || $sql_farmacias_referecnia3 == 1 ) && $sql_categorizacion_medico == 1) {
    echo "<script language='Javascript'>
    alert('Los datos se guardaron correctamente.');
    location.href='medicos_solicitados_lista_gerencia.php?cod_ciudad=$cod_ciudad';
    </script>";
} else {
    echo "<script language='Javascript'>
    alert('Los datos NO! se guardaron correctamente.');
    location.href='medicos_solicitados_lista_gerencia.php?cod_ciudad=$cod_ciudad';
    </script>";
}