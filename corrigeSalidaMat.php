<?php
require("conexion.inc");
//$codItem=1305;
$codItem=1058;

$codCiclo=11;
$codGestion=1007;

$sql="select s.cod_salida_almacenes, sv.codigo_funcionario,sd.cantidad_unitaria from salida_detalle_almacenes sd, salida_almacenes s, salida_detalle_visitador sv 
where sd.cod_material=$codItem and 
sd.cod_salida_almacen=s.cod_salida_almacenes and s.cod_salida_almacenes=sv.cod_salida_almacen 
and s.cod_almacen=1000 AND s.grupo_salida=1";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$codSalida=$dat[0];
	$codVis=$dat[1];
	$cantidadProducto=$dat[2];
	
	$sqlCodMat="select max(s.cod_salida_almacenes), sv.codigo_funcionario from salida_detalle_almacenes sd, 
	salida_almacenes s, salida_detalle_visitador sv 
	where sv.codigo_ciclo=$codCiclo and sv.codigo_gestion=$codGestion and  
	sd.cod_salida_almacen=s.cod_salida_almacenes and s.cod_salida_almacenes=sv.cod_salida_almacen 
	and s.cod_almacen=1000 AND s.grupo_salida=2 and sv.codigo_funcionario=$codVis";
	
	//	and s.observaciones like '%BPH General%'";
	
	$respCodMat=mysql_query($sqlCodMat);
	$codSalidaMat=mysql_result($respCodMat,0,0);
	
	echo $codSalida."  ".$codVis."  ".$codSalidaMat."<br>";
	//verifica si hay el item en esa salida
	$sqlVeri="select cantidad_unitaria from salida_detalle_almacenes where cod_salida_almacen=$codSalidaMat and cod_material=$codItem";
	$respVeri=mysql_query($sqlVeri);
	$numFilasVeri=mysql_num_rows($respVeri);
	if($numFilasVeri>0){
		$cantidadSalida=mysql_result($respVeri,0,0);
		$sqlUpd="update salida_detalle_almacenes set cantidad_unitaria=cantidad_unitaria+$cantidadProducto
		 where cod_salida_almacen=$codSalidaMat and cod_material=$codItem";
		echo "<br>".$sqlUpd."<br>";
		$respUpd=mysql_query($sqlUpd);
		
		$sqlDel="delete from salida_detalle_almacenes where cod_salida_almacen=$codSalida and cod_material=$codItem";
		$respDel=mysql_query($sqlDel);
		echo "<br>".$sqlDel."<br>";
		
	}else{
		$sqlUpd="update salida_detalle_almacenes set cod_salida_almacen=$codSalidaMat where cod_salida_almacen=$codSalida
		and cod_material=$codItem";
		echo $sqlUpd;
		$respUpd=mysql_query($sqlUpd);
	}
	
}
?>