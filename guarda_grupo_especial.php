
<?php

//$global_agencia=$_GET["global_agencia"];

require("conexion.inc");
require("estilos_gerencia.inc");

$sql="SELECT codigo_grupo_especial FROM grupo_especial ORDER BY codigo_grupo_especial DESC";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{   $codigo=1000;
}
else
{   $codigo=$dat[0];
    $codigo++;
}

$nombre_grupo=$_POST['nombre_grupo'];
$ciudad=$_POST['ciudad'];
$muestra=$_POST['muestra'];
$linea1=$_POST['linea1'];
$linea2=$_POST['linea2'];
$linea3=$_POST['linea3'];
$linea4=$_POST['linea4'];


$nombre_grupo=str_replace("'", "''", $nombre_grupo);

$sql_inserta=mysql_query("INSERT INTO grupo_especial VALUES($codigo,'$nombre_grupo','$muestra','$ciudad','$linea1',1,'$linea2','$linea3','$linea4')");
if($sql_inserta==1)
{   echo "<script language='Javascript'>
        alert('Los datos fueron insertados correctamente.');
        location.href='navegador_grupo_especial.php';
        </script>";
}
else
{   echo "<script language='Javascript'>
        alert('Los datos no pudieron insertarse.');
        history.back();
        </script>";
}

?>