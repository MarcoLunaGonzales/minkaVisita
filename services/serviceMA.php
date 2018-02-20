<?php
	//require("conexionMI.inc");
	require("../conexion.inc");
	$sql = "select codigo_material as id, descripcion_material as nombre from material_apoyo";
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
