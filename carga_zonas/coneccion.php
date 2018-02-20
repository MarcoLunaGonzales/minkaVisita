<?php

$conexion = mssql_connect("172.16.10.21", "sa", "m0t1t4s@2009");
$bd = mssql_select_db("SARTORIUS", $conexion);
//$bd = mssql_select_db("sartoruis104", $conexion);
//$conexion = mssql_connect("172.16.10.194", "sa", "m0t1t4s@2009");
//$bd = mssql_select_db("SARTORIUSJULIO", $conexion);

if (!$conexion) {
    echo "Por problemas tecnicos no se puede acceder a esta seccion, por favor intentelo mas tarde";
    die();
} 
?>