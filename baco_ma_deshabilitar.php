<?php

require("baco/coneccion.php");
error_reporting(0);
$sql_nombres = mssql_query("select nombres from material_apoyo_nombres");

while ($row_nombres = mssql_fetch_array($sql_nombres)) {
//    $sql_codigos = mssql_query("select cod_material from materiales where nombre_material like '%$row_nombres[0]%' ");
    $sql_codigos = mssql_query("select cod_material from materiales where nombre_material = '$row_nombres[0]' ");
    $si_hay = mssql_num_rows($sql_codigos);
    if ($si_hay == 0) {
        echo $row_nombres[0] . " -  <span style='color:red'>No hay!!!</span> <br />";
    } else {
        $codigo = mssql_result($sql_codigos, 0, 0);
        if(mssql_query("update materiales set COD_ESTADO_REGISTRO = 2 where cod_material = $codigo ")){
            $estado = mssql_query("select COD_ESTADO_REGISTRO from materiales where cod_material = $codigo");
            $estado_final = mssql_result($estado, 0,0);
            echo $codigo." - ".$row_nombres[0]." - <span style='color:green; font-weight:bold'>Bien-Entro</span> ". $estado_final ."<br />";
//            echo $codigo." - ".$row_nombres[0]." - <span style='color:green; font-weight:bold'>Bien-Entro</span> <br />";
        }else{
            echo $codigo." - ".$row_nombres[0]." - <span style='color:red; font-weight:bold'>Mal no entro</span> <br />";
        }
    }
}
?>