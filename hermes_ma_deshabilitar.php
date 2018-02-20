<?php

require("conexion.inc");
//error_reporting(0);
$sql_nombres = mysql_query("select nombre from material_apoyo_nombres");

while ($row_nombres = mysql_fetch_array($sql_nombres)) {
    $sql_codigos = mysql_query("select codigo_material from material_apoyo where descripcion_material like '%$row_nombres[0]%' ");
    $si_hay = mysql_num_rows($sql_codigos);
    if ($si_hay == 0) {
        echo $row_nombres[0] . " -  <span style='color:red'>No hay!!!</span> <br />";
    } else {
        $codigo = mysql_result($sql_codigos, 0, 0);
        if(mysql_query("update material_apoyo set estado='Retirado' where codigo_material = $codigo ")){
            $estado = mysql_query("select estado from material_apoyo where codigo_material = $codigo");
            $estado_final = mysql_result($estado, 0,0);
            echo $codigo." - ".$row_nombres[0]." - <span style='color:green; font-weight:bold'>Bien-Entro</span> ". $estado_final ."<br />";
        }else{
            echo $codigo." - ".$row_nombres[0]." - <span style='color:red; font-weight:bold'>Mal no entro</span> <br />";
        }
    }
}
?>