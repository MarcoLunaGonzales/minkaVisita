<?php
require('function_cantrequeridanacional.php');
$tipo_material=1;
$codigo_material="CFCO0120";
$codigo_ciclo=15;
$cantidad_pais=cantidad_nacional($tipo_material,$codigo_material,$codigo_ciclo);
echo $cantidad_pais;
?>