<?php

require("conexion.inc");
require('estilos_gerencia.inc');

$codigo_linea=$_GET["codigo_linea"];
$cod_ciudad=$_GET["cod_ciudad"];

$vector=explode(",",$datos);
$n=sizeof($vector);
for($i=0;$i<$n;$i++)
{
    $sql="DELETE FROM grupo_especial WHERE codigo_grupo_especial='$vector[$i]'";
    $resp=mysql_query($sql);
    $sql_det="DELETE FROM grupo_especial_detalle WHERE codigo_grupo_especial='$vector[$i]'";
    $resp_det=mysql_query($sql_det);
}
echo "
<script type='text/javascript' language='javascript'>
    alert('Los datos fueron eliminados.');
    location.href='navegador_grupo_especial.php';
</script>";

?>
