<?php
	require("conexionMI.inc");
	
	$sql = "select codigo_material as id, descripcion_material as nombre from material_apoyo";
    $result = mysqli_query($conexionM, $sql) or die("Error in Selecting. ");

    $emparray[] = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $emparray[] = $row;
    }
    echo json_encode($emparray);

    //close the db connection
    mysqli_close($conexionM);
?>
