<?php
require("conexion.inc");
require("estilos_gerencia.inc");

$codLineaDist=$_POST['codLineaDist'];
$codCicloDist=$_POST['codCicloDist'];
$codGestionDist=$_POST['codGestionDist'];
$codProdOrigen=$_POST['codProdOrigen'];
$codProdDestino=$_POST['codProdDestino'];

$sqlParrilla="select sum(cantidad_distribuida) from distribucion_productos_visitadores 
	where cod_ciclo=$codCicloDist and codigo_gestion=$codGestionDist and codigo_linea=$codLineaDist and codigo_producto='$codProdOrigen'";
$respParrilla=mysql_query($sqlParrilla);
$cantidadDist=mysql_result($respParrilla,0,0);
if($cantidadDist==0){
	
	echo "<script language='JavaScript'>
			alert('Se esta procediendo a realizar el cambio del item en parrilla');
		</script>
	";
	$sqlUpdParrilla="select pd.codigo_parrilla, pd.prioridad from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and p.cod_ciclo=$codCicloDist and p.codigo_gestion=$codGestionDist and p.codigo_linea=$codLineaDist and pd.codigo_material='$codProdOrigen'";
	$respUpdParrilla=mysql_query($sqlUpdParrilla);
	while($datUpdParrilla=mysql_fetch_array($respUpdParrilla)){
		$codParrilla=$datUpdParrilla[0];
		$codPrioridad=$datUpdParrilla[1];
		
		/*/verificamos q el producto destino no haya en la parrilla 
		$sqlVeriProdParrilla="select prioridad, cantidad_muestra from parrilla_detalle where codigo_muestra='$codProdDestino' and
			codigo_parrilla=$codParrilla";
		$respVeriProdParrilla=mysql_query($sqlVeriProdParrilla);
		$filasVeriProdParrilla=mysql_num_rows($respVeriProdParrilla);
		*/
		
		$sqlActParrilla="update parrilla_detalle set codigo_material='$codProdDestino' where codigo_material='$codProdOrigen' and
			codigo_parrilla=$codParrilla and prioridad=$codPrioridad";
		$respActParrilla=mysql_query($sqlActParrilla);
	}

}
else{
	echo "<script language='JavaScript'>
			alert('No se procedera al cambio del item en parrillas porque ya se distribuyeron cantidades del mismo.');
		</script>
	";
}


$sql="select d.`cod_visitador`, (d.`cantidad_planificada`-d.cantidad_distribuida), d.`territorio` from `distribucion_productos_visitadores` d where 
	d.`cod_ciclo`=$codCicloDist and d.`codigo_gestion`=$codGestionDist and d.`codigo_producto`='$codProdOrigen' and d.codigo_linea='$codLineaDist'";

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codVisitador=$dat[0];
	$cantPlani=$dat[1];
	$codCiudad=$dat[2];
	
	$sqlUpd="select d.`cod_visitador`, (d.`cantidad_planificada`-d.cantidad_distribuida) from `distribucion_productos_visitadores` d where 
	d.`cod_ciclo`=$codCicloDist and d.`codigo_gestion`=$codGestionDist and d.`codigo_producto`='$codProdDestino' and cod_visitador='$codVisitador' 
	and d.codigo_linea='$codLineaDist'";
	
	$respUpd=mysql_query($sqlUpd);
	$filasUpd=mysql_num_rows($respUpd);
	
	if($filasUpd==0){
		$sqlActualiza="insert into distribucion_productos_visitadores values($codGestionDist,$codCicloDist,$codCiudad,$codLineaDist, $codVisitador, '$codProdDestino', $cantPlani,0,2,0)";
		$respActualiza=mysql_query($sqlActualiza);
		
		$sqlDelete="delete from distribucion_productos_visitadores where `cod_ciclo`=$codCicloDist and `codigo_gestion`=$codGestionDist 
		and `codigo_producto`='$codProdOrigen' and cod_visitador='$codVisitador' and codigo_linea='$codLineaDist'";
		$respDelete=mysql_query($sqlDelete);
	}else{
		$sqlActualiza="update distribucion_productos_visitadores set cantidad_planificada=cantidad_planificada+$cantPlani 
		where `cod_ciclo`=$codCicloDist and `codigo_gestion`=$codGestionDist and `codigo_producto`='$codProdDestino' and cod_visitador='$codVisitador' 
		and codigo_linea=$codLineaDist";
		$respActualiza=mysql_query($sqlActualiza);
		
		$sqlActualiza2="update distribucion_productos_visitadores set cantidad_planificada=cantidad_planificada-$cantPlani 
		where `cod_ciclo`=$codCicloDist and `codigo_gestion`=$codGestionDist and `codigo_producto`='$codProdOrigen' and cod_visitador='$codVisitador'
		and codigo_linea=$codLineaDist";
		$respActualiza2=mysql_query($sqlActualiza2);
	}
	/*$sqlDelete="delete from distribucion_productos_visitadores where 
	`cod_ciclo`=$codCicloDist and `codigo_gestion`=$codGestionDist and 
	`codigo_producto`='$codProdOrigen' and cod_visitador='$codVisitador'";
	$respDelete=mysql_query($sqlDelete);*/
}

echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			window.close();
			</script>";

?>