<?php

require("conexion.inc");
$sql1 = mysql_query("select r.dia_contacto, rd.cod_med from rutero_maestro_aprobado r, rutero_maestro_cab_aprobado rmc, rutero_maestro_detalle_aprobado rd where rmc.cod_rutero=r.cod_rutero and rmc.estado_aprobado=1 and r.cod_visitador='1126' and rmc.cod_visitador=r.cod_visitador and r.cod_visitador=rd.cod_visitador and rmc.codigo_linea='1021' and r.cod_contacto=rd.cod_contacto and rmc.codigo_ciclo='6' and rmc.codigo_gestion='1009' ORDER BY r.dia_contacto");
echo "<table>";
while ($row = mysql_fetch_array($sql1)) {
    echo "<tr>";
    echo "<td>";
    echo $row[0];
    echo "</td>";
    $sql2 = mysql_query("select * from medicos where cod_med = $row[1] ");
    while ($row_2 = mysql_fetch_array($sql2)){
        echo "<td>";
        echo $row_2[1]." " .$row_2[2] ." ".$row_2[3] ."<br />";
        echo "</td>";
    }
    echo "</tr>";
}
echo "</table>";
?>