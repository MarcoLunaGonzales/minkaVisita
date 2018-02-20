<?php
require("conexion.inc");
$global_ciclo_distribucion=$_COOKIE['global_ciclo_distribucion'];
$global_gestion_distribucion=$_COOKIE['global_gestion_distribucion'];

//PRIMERA VALIDACION SI EXISTEN CICLOS RELACIONADOS
$sqlCiclosRelacionados="select c.codigo_ciclo_relacionado, c.codigo_gestion_relacionado 
from ciclos_distribucion_relacionados c where c.codigo_gestion=$global_gestion_distribucion 
and c.codigo_ciclo=$global_ciclo_distribucion";
//echo $sqlCiclosRelacionados;

$respCiclosRelacionados=mysql_query($sqlCiclosRelacionados);
$numeroRegistrosCiclos=mysql_num_rows($respCiclosRelacionados);
//SEGUNDA VALIDACION SI YA SE DISTRIBUYO 
$sqlValidacionDistribucion="select count(*) from distribucion_productos_visitadores d
where d.codigo_gestion=$global_gestion_distribucion and d.cod_ciclo=$global_ciclo_distribucion 
and d.grupo_salida=1 and d.codigo_producto<>'0' and 
d.cantidad_distribuida>0";
//echo $sqlValidacionDistribucion;
$respValidacionDistribucion=mysql_query($sqlValidacionDistribucion);
$numeroRegistrosDistribucion=mysql_result($respValidacionDistribucion,0,0);


if($numeroRegistrosCiclos>0 && $numeroRegistrosDistribucion<=0){
	//DEPURAMOS DATOS DE LA DISTRIBUCION
	$sqlDelete="delete distribucion_productos_visitadores where cantidad_planificada=0 and 
	codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'";
	$respDelete=mysql_query($sqlDelete);
	
	$sqlDelete="delete distribucion_productos_visitadores where codigo_producto='0' and 
	codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'";
	$respDelete=mysql_query($sqlDelete);
	
	//HACEMOS EL UPDATE DE LA CANTIDAD TOTAL DE LA DISTRIBUCION
	$sqlUpdateTotales="update distribucion_productos_visitadores set cantidad_totaldistribucion=cantidad_planificada where 
	codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'";
	$respUpdateTotales=mysql_query($sqlUpdateTotales);
	
	while($datCiclos=mysql_fetch_array($respCiclosRelacionados)){
		$codCicloRel=$datCiclos[0];
		$codGestionRel=$datCiclos[1];
		
		$sqlVisitadores="select d.territorio, d.cod_visitador, d.codigo_linea, d.codigo_producto, d.cantidad_planificada 
		from distribucion_productos_visitadores d 
		where d.codigo_gestion='$global_gestion_distribucion' and d.cod_ciclo='$global_ciclo_distribucion' 
		and d.grupo_salida=1 and d.codigo_producto<>'0' ORDER BY d.territorio, d.cod_visitador, 
		d.codigo_producto, d.cantidad_planificada DESC, d.codigo_linea";
		$respVisitadores=mysql_query($sqlVisitadores);
		while($datVisitadores=mysql_fetch_array($respVisitadores)){
			$codTerritorio=$datVisitadores[0];
			$codVisitador=$datVisitadores[1];
			$codLinea=$datVisitadores[2];
			$codProducto=$datVisitadores[3];
			$cantidadPlanificada=$datVisitadores[4];
			
			$sqlDevolucion="select IFNULL(sum(dd.cantidad_devolucion),0) from devoluciones_ciclo d, devoluciones_ciclodetalle dd
				where d.codigo_devolucion=dd.codigo_devolucion and d.codigo_visitador in ($codVisitador) and 
				dd.codigo_material in ('$codProducto') and d.codigo_gestion=$codGestionRel 
				and d.codigo_ciclo=$codCicloRel";
			$respDevolucion=mysql_query($sqlDevolucion);
			$cantidadDevolucion=mysql_result($respDevolucion,0,0);
			
			//echo "$codTerritorio $codVisitador $codLinea $codProducto $cantidadPlanificada $cantidadDevolucion<br>";
			
			//SI EXISTE DEVOLUCION DESCONTAMOS LA CANTIDAD DE LA PLANIFICADA
			if($cantidadDevolucion>0){
				//VERIFICAMOS SI YA SE APLICO LA CANTIDAD DE DEVOLUCION A OTRAS LINEAS
				$sqlVerificaCantDevolucion="select IFNULL(sum(cantidad_devolucion_aplicada_visitador),0)
					from distribucion_productos_visitadores where territorio='$codTerritorio' and cod_visitador='$codVisitador' 
					and codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' 
					and codigo_producto='$codProducto' and cantidad_devolucion_total_visitador>0 and 
					cantidad_planificada=0";
				$respVerificaCantDevolucion=mysql_query($sqlVerificaCantDevolucion);
				$cantidadDevolucionAplicada=mysql_result($respVerificaCantDevolucion,0,0);
				
				//RESTAMOS LA CANTIDAD DE DEVOLUCION DEL PRODUCTO - LA APLICADA
				
				$cantidadDevolucionAplicar=$cantidadDevolucion-$cantidadDevolucionAplicada;
				
				if($cantidadDevolucionAplicar<=$cantidadPlanificada){
					$sqlUpdCantidades="update distribucion_productos_visitadores set 
						cantidad_devolucion_total_visitador='$cantidadDevolucion', cantidad_devolucion_aplicada_visitador='$cantidadDevolucionAplicar', 
						cantidad_planificada=cantidad_planificada-$cantidadDevolucionAplicar where codigo_gestion='$global_gestion_distribucion' and 
						cod_ciclo='$global_ciclo_distribucion' and territorio='$codTerritorio' and cod_visitador='$codVisitador' and 
						codigo_producto='$codProducto' and codigo_linea='$codLinea'";
					$respUpdCantidades=mysql_query($sqlUpdCantidades);						
				}else{
					$sqlUpdCantidades="update distribucion_productos_visitadores set 
						cantidad_devolucion_total_visitador='$cantidadDevolucion', cantidad_devolucion_aplicada_visitador=cantidad_planificada, 
						cantidad_planificada=0 where codigo_gestion='$global_gestion_distribucion' and 
						cod_ciclo='$global_ciclo_distribucion' and territorio='$codTerritorio' and cod_visitador='$codVisitador' and 
						codigo_producto='$codProducto' and codigo_linea='$codLinea'";
					$respUpdCantidades=mysql_query($sqlUpdCantidades);						
				}
			}	
		}		
	}
	
	echo "<script>
		alert('Se aplicaron las cantidades de los visitadores a la distribucion.');
		location.href='navegador_lineas_distribucion.php';
	</script>";
	
}else{
	echo "<script>
		alert('No se pueden tomar en cuenta las MM de los Visitadores. Consultar con el administrador.');
		history.back();
	</script>";
}




?>
