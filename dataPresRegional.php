<?php
header('Content-Type: application/json');

	require("conexion.inc");
	require("funcion_nombres.php");
	require("funciones.php");
	
	function utf8json($inArray) { 
	static $depth = 0; 
		/* our return object */ 
		$newArray = array(); 
		/* safety recursion limit */ 
		$depth ++; 
		if($depth >= '1000000') { 
			return false; 
		} 
		/* step through inArray */ 
		foreach($inArray as $key=>$val) { 
			if(is_array($val)) { 
				/* recurse on array elements */ 
				$newArray[$key] = utf8json($val); 
			} else { 
				/* encode string values */ 
				$newArray[$key] = utf8_encode($val); 
			} 
		} 
		/* return utf8 encoded array */ 
		return $newArray; 
	}
	
	$codRegional=$_GET["codRegional"];
	$codMes=$_GET["codMes"];
	$codAnio=$_GET["codAnio"];
	$emparray[] = array();
	
	$sql="select pp.linea, sum(p.monto_presupuesto), sum(p.cantidad_presupuesto) from presupuestos p, productos pp
		where p.cod_producto=pp.cod_producto and 
		MONTH(p.fecha)='$codMes' and YEAR(p.fecha)='$codAnio' AND
		p.cod_ciudad='$codRegional' group by pp.linea order by 1";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp)){
	
		$nombreLinea=$dat[0];
		$montoPresupuesto=$dat[1];
		$cantidadPresupuesto=$dat[2];
		
		$sqlVenta="select IFNULL(sum(v.monto_venta),0), IFNULL(sum(v.cantidad),0) from ventas v, productos p
			where MONTH(v.fecha_venta)='$codMes' and YEAR(v.fecha_venta)='$codAnio' AND
			v.cod_ciudad='$codRegional' and v.cod_producto=p.cod_producto and p.linea='$nombreLinea' 
			and v.canal not in ('INSTITUCIONAL')";
		$respVenta=mysql_query($sqlVenta);
		$montoVenta=mysql_result($respVenta,0,0);
		$cantidadVenta=mysql_result($respVenta,0,1);


		$emparray[]=array("nombreLinea"=>$nombreLinea, "montoPresupuesto"=>$montoPresupuesto, 
			"montoVenta"=>$montoVenta,"cantPresupuesto"=>$cantidadPresupuesto,"cantVenta"=>$cantidadVenta);
	}
	array_splice($emparray, 0,1);
	//echo json_encode(utf8json($emparray));
	echo json_encode($emparray);
?>