<?php
	require("../conexion.inc");
	
	$sql = "select codigo_linea as id, nombre_linea as nombre from lineas where linea_promocion=1 and estado=1";
    $result = mysql_query($sql) or die("Error in Selecting. ");

    $emparray[] = array();
    while($row =mysql_fetch_assoc($result))
    {
        $emparray[] = $row;
    }
    echo json_encode($emparray);

    //close the db connection
    mysql_close($conexion);
?>
