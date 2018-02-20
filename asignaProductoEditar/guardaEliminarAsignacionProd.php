<?php
	require("../funcion_nombres.php");
	require("../conexion.inc");	
	require("../conexioni.inc");
	
	//$link = mysqli_connect('localhost', 'root', '', 'visita20140527');
	
	$codProducto=$_GET['codProducto'];	
	$rptAgencia=$_GET['fAgencia'];
	$rptEspecialidad=$_GET['fEspecialidad'];
	$rptContacto=$_GET['fContacto'];
	
$lineasMktR=$_GET['fLineaMkt'];
$ciudades=$rptAgencia;
$especialidades=$rptEspecialidad;
$especialidades=str_replace("`","'",$especialidades);


$sql="select especialidad, posicion, ciudad, producto, linea_mkt, contacto, linea from asignacion_productos_excel_detalle a 
where a.linea_mkt in ($lineasMktR) and ciudad in ($ciudades) and especialidad in ($especialidades) and producto='$codProducto' and 
contacto in ($rptContacto)
GROUP BY especialidad, ciudad, linea_mkt, contacto, linea";

//echo $sql;

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){

	$espe=$dat[0];
	$posicion=$dat[1];
	$ciudad=$dat[2];
	$lineaMkt=$dat[4];
	$contacto=$dat[5];
	$lineaVis=$dat[6];
	
	$sqlDel="delete from asignacion_productos_excel_detalle where especialidad='$espe' and ciudad='$ciudad' and linea_mkt='$lineaMkt' 
	and contacto='$contacto' and linea='$lineaVis' and producto='$codProducto'";
	//echo $sqlDel."<br>";
	$respDel=mysql_query($sqlDel);
	
	$sqlUpd="update asignacion_productos_excel_detalle set posicion=posicion-1 where especialidad='$espe' and ciudad='$ciudad' and linea_mkt='$lineaMkt' 
	and contacto='$contacto' and linea='$lineaVis' and posicion>$posicion"; 
	//echo $sqlUpd."<br>";
	$respUpd=mysql_query($sqlUpd);
}

echo "<script>
alert('Se eliminaron los datos.');
window.opener.location.reload();
window.close();

</script>";

?>

