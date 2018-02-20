<?php
require("conexion.inc");
require("funcion_nombres.php");

$gestion=1011;
$ciclo=3;

//$ciudades="102,113,114,104,119,120,121,116,109,118,117,122,123,124";

$ciudades="117, 121, 123";
$sql="select DISTINCT(rc.cod_visitador), (select f.cod_ciudad from funcionarios f where f.codigo_funcionario=rc.cod_visitador) from 
rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd
where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rc.codigo_gestion='$gestion' and rc.codigo_ciclo='$ciclo' and
rc.cod_visitador in (select f.codigo_funcionario from funcionarios f where f.cod_ciudad in ($ciudades)) order by 1";
//echo $sql;
$resp=mysql_query($sql);

echo "<table border=1>";
echo "<tr><th>codVisitador</th><th>Visitador</th><th>codCiudad</th><th>codLinea</th><th>Linea</th><th>codProd</th><th>Producto</th><th>Distribucion</th><th>Contactos</th></tr>";
while($dat=mysql_fetch_array($resp)){
	$codVisitador=$dat[0];
	$nombreVisitador=nombreVisitador($codVisitador);
	$codAgenciaVis=$dat[1];
	
	$sqlProd="select d.codigo_linea, d.codigo_producto, sum(d.cantidad_planificada) 
	from distribucion_productos_visitadores d where d.codigo_gestion='$gestion' and d.cod_ciclo='$ciclo' and d.cod_visitador='$codVisitador' 
	and d.grupo_salida=1 and d.codigo_producto<>'0' group by d.codigo_linea, d.codigo_producto";
	$respProd=mysql_query($sqlProd);
	while($datProd=mysql_fetch_array($respProd)){
		$codLinea=$datProd[0];
		$nombreLinea=nombreLinea($codLinea);
		$codProducto=$datProd[1];
		$nombreProducto=nombreProducto($codProducto);
		$cantidadProducto=$datProd[2];
		
		$sqlEspe="select count(distinct(rd.cod_med)), rd.cod_especialidad ,rd.categoria_med from rutero_maestro_cab_aprobado rc, 
		rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd 
		where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rc.codigo_gestion='$gestion' and 
		rc.codigo_ciclo='$ciclo' and rc.cod_visitador=$codVisitador and rc.codigo_linea='$codLinea' GROUP BY rd.cod_especialidad, rd.categoria_med";
		$respEspe=mysql_query($sqlEspe);
		$cad="";
		$cantidadContactosProd=0;
		while($datEspe=mysql_fetch_array($respEspe)){
			$cantMed=$datEspe[0];
			$codEspeMed=$datEspe[1];
			$catMed=$datEspe[2];
			
			$sqlParrilla="select count(*) from parrilla p, parrilla_detalle pd 
				where p.codigo_gestion='$gestion' and p.cod_ciclo='$ciclo' and p.agencia='$codAgenciaVis' and  
				p.cod_especialidad='$codEspeMed' and 
				p.categoria_med='$catMed' and pd.codigo_muestra='$codProducto' 
				and p.codigo_linea='$codLinea' and p.codigo_parrilla=pd.codigo_parrilla";
			$respParrilla=mysql_query($sqlParrilla);
			$cantProdParrilla=mysql_result($respParrilla,0,0);
			
			//$cad=$cad." ".$codEspeMed." ".$catMed." ".$cantMed." ".$cantProdParrilla;
			
			$cantidadContactosProd=$cantidadContactosProd + ($cantProdParrilla * $cantMed);
		}
		
		echo "<tr><td>$codVisitador</td><td>$nombreVisitador</td><td>$codAgenciaVis</td>
		<td>$codLinea</td><td>$nombreLinea</td>
		<td>$codProducto</td><td>$nombreProducto</td>
		<td>$cantidadProducto</td><td>$cantidadContactosProd</td>
		</tr>";
	}
}

?>