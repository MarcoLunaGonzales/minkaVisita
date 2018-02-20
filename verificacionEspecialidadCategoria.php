<?php

include 'conexion.inc';

$query = mysql_query(" SELECT m.cod_med, CONCAT(m.nom_med,' ',m.ap_pat_med,' ',m.ap_mat_med) as funcionario, c.descripcion, cl.cod_especialidad, cl.categoria_med, 
    em.cod_especialidad from medicos m, ciudades c, categorias_lineas cl, especialidades_medicos em WHERE m.cod_med = cl.cod_med and 
    cl.cod_med = em.cod_med and m.cod_ciudad = c.cod_ciudad and em.cod_especialidad <> cl.cod_especialidad ORDER BY 3  ");
while($row = mysql_fetch_array($query)){
    echo "<span style='width:80px; float:left'>". $row[0] ."</span> ";
    echo "<span style='width:370px; float:left'>". $row[1] ."</span> ";
    echo "<span style='width:150px; float:left'>". $row[2] ."</span> ";
    echo "<span style='color:red; width:75px; float:left'>". $row[3] ."</span> ";
    echo "<span style='width:50px; float:left'>". $row[4] ."</span> ";
    echo "<span style='color:red; width:75px; float:left'>". $row[5] ."</span> ";
    echo "<br />";
}
?>