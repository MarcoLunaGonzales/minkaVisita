<?php
require('conexion.inc');
$codMed = $_GET['codMed'];
$codCUP = $_GET['codCUP'];

$sqlUpd = "update medicos set cod_closeup='$codCUP' where cod_med='$codMed'";
$respUpd = mysql_query($sqlUpd);

echo "Guardado $codCUP";

?>