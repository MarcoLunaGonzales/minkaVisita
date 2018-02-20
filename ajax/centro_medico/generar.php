<?php

set_time_limit(0);
mysql_query('SET CHARACTER SET utf8');
require("../../conexion.inc");
$codigos_cadena = $_POST['codigos'];

$codigos_sub = substr($codigos_cadena, 0, -1);
if ($codigos_sub == '') {
    $sql_cm = mysql_query("SELECT cm.cod_centro_medico,cm.nombre,cm.direccion,c.descripcion from centros_medicos cm, ciudades c where cm.cod_ciudad = c.cod_ciudad order by cod_centro_medico DESC");
} else {
    $sql_cm = mysql_query("SELECT cm.cod_centro_medico,cm.nombre,cm.direccion,c.descripcion from centros_medicos cm, ciudades c where cm.cod_ciudad = c.cod_ciudad and cm.cod_ciudad in ($codigos_sub) order by cod_centro_medico DESC");
}
$output;
$count = 1;
while($row = mysql_fetch_array($sql_cm)){
    $codigo = $row[0];
    $nombre = $row[1];
    $direccion = $row[2];
    $descripcion = $row[3];
    
    $output .="<tr>";
    $output .="<tr>";
    $output .="<td>";
    $output .=$count;
    $output .="</td>";
    $output .="<td>";
    $output .="<input type='hidden' value='$codigo' />$nombre";
    $output .="</td>";
    $output .="<td>";
    $output .=$direccion;
    $output .="</td>";
    $output .="<td>";
    $output .=$descripcion;
    $output .="</td>";
    $output .="<td>";
    $output .="<a href='#' class='editar' value='$codigo'><img src='imagenes/btn_modificar.png' alt='Editar' /></a>";
    $output .="</td>";
    $output .="<td>";
    $output .="<a href='#' class='eliminar' value='$codigo'><img src='imagenes/no.png' alt='Eliminar' /></a>";
    $output .="</td>";
    $output .="</tr>";
    $count++;
}

//echo $output;
$return['msg'] = $output;
$encoded_rows = array_map(utf8_encode, $return);
echo json_encode($encoded_rows);
?>