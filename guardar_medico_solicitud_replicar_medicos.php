<?php

require("conexion.inc");
require("estilos_administracion.inc");

$fecha_registro = date('Y-m-d');


/* Datos generales Insterst */

$sql_id_max = mysql_query("SELECT max(cod_med) from medicos");
$id = mysql_result($sql_id_max, 0,0);
$id++;
$sql_medicos_a_replicar = mysql_query("SELECT * from medicos where cod_ciudad = 122");

while ($row_medicos = mysql_fetch_array($sql_medicos_a_replicar)) {
    
    $sql = "INSERT into medicos values ($id, '$row_medicos[1]','$row_medicos[2]','$row_medicos[3]','$row_medicos[4]','$row_medicos[5]','$row_medicos[6]','$row_medicos[7]','$row_medicos[8]','$row_medicos[9]','$row_medicos[10]', '$row_medicos[11]','$row_medicos[12]',$row_medicos[13],$row_medicos[14], '$row_medicos[15]','$row_medicos[16]','$row_medicos[17]','$row_medicos[18]','$row_medicos[19]','$row_medicos[20]','$row_medicos[21]')";
    $resp = mysql_query($sql);  

    $sql_direcciones = mysql_query("SELECT * from direcciones_medicos where cod_med = $row_medicos[0] ");

    while ($row_direcciones = mysql_fetch_array($sql_direcciones)) {

        $sql1 = "INSERT into direcciones_medicos values('$id','$row_direcciones[1]','$row_direcciones[2]','$row_direcciones[3]','$row_direcciones[4]', '$row_direcciones[5]', '$row_direcciones[6]','$row_direcciones[7]')";
        $resp1 = mysql_query($sql1);
    }
    

    $sql_espe = mysql_query("SELECT * from especialidades_medicos where cod_med = $row_medicos[0] ");

    while ($row_espe = mysql_fetch_array($sql_espe)) {

        $sql_espe = "INSERT into especialidades_medicos values ($id, '$row_espe[1]', '$row_espe[2]')";
        $resp_espe = mysql_query($sql_espe);

    }

    $sql_farm = mysql_query("SELECT * from farmacias_referencia_medico where cod_med = $row_medicos[0]");

    while($row_farm = mysql_fetch_array($sql_farm)){

        $sql_farmacias_referecnia = mysql_query("INSERT into farmacias_referencia_medico (cod_med, nombre_farmacia, direccion_farmacia) values ($id, '$row_farm[2]', '$row_farm[3]')"); 

    }

    $sql_cat = mysql_query("SELECT * from categorizacion_medico where cod_med = $row_medicos[0]");

    while ($row_cat = mysql_fetch_array($sql_cat)) {
       
        $sql_categorizacion_medico = mysql_query("INSERT into categorizacion_medico (cod_med,sexo,edad,n_pacientes,tiene_preferencia,nivel,costo) values ($id,'$row_cat[2]','$row_cat[3]','$row_cat[4]','$row_cat[5]','$row_cat[6]','$row_cat[7]')");

    }

    $sql_asig = mysql_query("SELECT * from medico_asignado_visitador where cod_med = $row_medicos[0]");

    while ($row_asig = mysql_fetch_array($sql_asig)) {
       
        $sql_medico_asignado = mysql_query("INSERT into medico_asignado_visitador values($id, '$row_asig[1]', '$row_asig[2]')");

    }

    $sql_lin = mysql_query("SELECT * from categorias_lineas where cod_med = $row_medicos[0]");

    while ($row_lin = mysql_fetch_array($sql_lin)) {
       
        $sql_linea_medico = mysql_query("INSERT into categorias_lineas values('$row_lin[0]',$id,'$row_lin[2]','$row_lin[3]','$row_lin[4]','$row_lin[5]')");

    }

    $id++;
}

?>