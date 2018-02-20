<?php

if (isset($_POST['nombre_item'])) {
    set_time_limit(0);
    require("../../conexion.inc");
    $nombre_item = $_POST['nombre_item'];
    $query = mysql_query("SELECT descripcion_material from material_apoyo where descripcion_material = '$nombre_item' ");
    $num_query = mysql_num_rows($query);
    if($num_query == 0){
        echo "OK";
    }else{
        echo '<font color="red">El Item: <STRONG>' . $nombre_item . '</STRONG> ya existe.</font>' ;
    }
}
?>