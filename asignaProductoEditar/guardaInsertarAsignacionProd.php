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
$rptPosicion=$_GET['rptPosicion'];

$sql="select especialidad, posicion, ciudad, producto, linea_mkt, contacto, linea from asignacion_productos_excel_detalle a 
where a.linea_mkt in ($lineasMktR) and ciudad in ($ciudades) and especialidad in ($especialidades) and contacto in ($rptContacto)
GROUP BY especialidad, ciudad, linea_mkt, contacto, linea";

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){

	$espe=$dat[0];
	$posicion=$dat[1];
	$ciudad=$dat[2];
	$lineaMkt=$dat[4];
	$contacto=$dat[5];
	$lineaVis=$dat[6];
	
	$sqlVeri="select * from asignacion_productos_excel_detalle a 
	where a.linea_mkt in ($lineaMkt) and ciudad in ($ciudad) and especialidad in ('$espe') and contacto in ($contacto) and 
	linea='$lineaVis' and producto='$codProducto'";
	//echo $sqlVeri;
	$respVeri=mysql_query($sqlVeri);
	$numFilas=mysql_num_rows($respVeri);
	
	if($numFilas==0){
		$sqlUpd="update asignacion_productos_excel_detalle set posicion=posicion+1 where especialidad='$espe' and 
		ciudad='$ciudad' and linea_mkt='$lineaMkt' and contacto='$contacto' and producto not in ('$codProducto') 
		and linea='$lineaVis' and posicion>=$rptPosicion";
		//echo $sqlUpd."<br>";
		$respUpd=mysql_query($sqlUpd);
		
		//INSERTAMOS EL NUEVO
		$sqlInsert="insert into asignacion_productos_excel_detalle(id, especialidad, linea, posicion, ciudad, producto, linea_mkt, contacto) 
			values(1, '$espe','$lineaVis','$rptPosicion','$ciudad','$codProducto','$lineaMkt','$contacto')";
		//echo $sqlInsert."<br>";
		$respUpd=mysql_query($sqlInsert);
	}		
	
}

echo "<script>
alert('Se insertaron los datos.');
window.opener.location.reload();
window.close();
</script>";

?>

