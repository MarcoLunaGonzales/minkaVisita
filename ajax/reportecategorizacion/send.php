<?php

require("../../conexion.inc");
$territorio = $_REQUEST[ 'territorio' ];
$formato = $_REQUEST['formato'];

echo json_encode( $territorio);
/**/
?>