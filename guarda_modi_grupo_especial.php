<?php

require("conexion.inc");
require("estilos_gerencia.inc");

$cod_grupo=$_POST['cod_grupo'];
$nombre_grupo=$_POST['nombre_grupo'];
$ciudad=$_POST['ciudad'];
$muestra=$_POST['muestra'];
$linea1=$_POST['linea1'];
$linea2=$_POST['linea2'];
$linea3=$_POST['linea3'];
$linea4=$_POST['linea4'];

$nombre_grupo=str_replace("'", "''", $nombre_grupo);

$sql_inserta=mysql_query("UPDATE grupo_especial SET nombre_grupo_especial='$nombre_grupo',cod_muestra='$muestra',
	agencia='$ciudad', codigo_linea='$linea1', codigo_linea1='$linea2', codigo_linea2='$linea3', codigo_linea3='$linea4' 
	where codigo_grupo_especial='$cod_grupo'");

if($sql_inserta==1)
{   echo "<script type='text/javascript' language='javascript'>
        alert('Los datos fueron modificados correctamente.');
        location.href='navegador_grupo_especial.php';
        </script>";
}
else
{   echo "<script type='text/javascript' language='javascript'>
        alert('Los datos no pudieron modificarse.');
        history.back();
        </script>";
}

?>
