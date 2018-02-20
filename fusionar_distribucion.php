<?php
require('conexion.inc');
$sql_lista="select territorio, codigo_linea, cod_visitador, codigo_producto, cantidad_planificada, 
						cantidad_distribuida, cantidad_sacadaalmacen, grupo_salida from 
						distribucion_productos_visitadores2";
$resp_lista=mysql_query($sql_lista);
while($dat_lista=mysql_fetch_array($resp_lista)){
		$territorio=$dat_lista[0];
		$codigoLinea=$dat_lista[1];
		$codVisitador=$dat_lista[2];
		$codProducto=$dat_lista[3];
		$cantPlani=$dat_lista[4];
		$cantDist=$dat_lista[5];
		$cantSacada=$dat_lista[6];
		$grupoSalida=$dat_lista[7];
		
		$sql_verificacion="select * from distribucion_productos_visitadores where territorio='$territorio'
		and codigo_linea='$codigoLinea' and cod_visitador='$codVisitador' and codigo_producto='$codProducto'";
		$resp_verificacion=mysql_query($sql_verificacion);
		$num_filas_verificacion=mysql_num_rows($resp_verificacion);
		
		if($num_filas_verificacion==1){
			$sqlUpd="update distribucion_productos_visitadores set cantidad_planificada=cantidad_planificada+$cantPlani,
			cantidad_distribuida=cantidad_distribuida+$cantDist, cantidad_sacadaalmacen=cantidad_sacadaalmacen+$cantSacada 
			where territorio='$territorio' and codigo_linea='$codigoLinea' 
			and cod_visitador='$codVisitador' and codigo_producto='$codProducto'";
			$respUpd=mysql_query($sqlUpd);
			echo $sqlUpd;
		}
		else{
			$sqlInsert="insert into distribucion_productos_visitadores values('1006','13','$territorio',
			'$codigoLinea','$codVisitador','$codProducto','$cantPlani','$cantDist','$grupoSalida','$cantSacada')";
			$respInsert=mysql_query($sqlInsert);
			echo $sqlInsert;
			
		}
}


?>