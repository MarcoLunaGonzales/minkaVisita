<?php

require("conexion.inc");
require("estilos_gerencia.inc");
$vector = explode(",", $datos);
$n = sizeof($vector);
for ($i = 0; $i < $n; $i++) {
    $sql_inserta  = mysql_query("INSERT into lineas_visita_visitadores values($cod_linea_vis,'$vector[$i]',$ciclo,$gestion)");
//    echo("insert into lineas_visita_visitadores values($cod_linea_vis,'$vector[$i]',$ciclo,$gestion)")."<br />";
}
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_lineasvisitafuncionario.php?cod_linea_vis=$cod_linea_vis&ciclo=$ciclo&gestion=$gestion';
			</script>";
?>
