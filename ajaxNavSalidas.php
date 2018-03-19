<?php
require("conexion.inc");
require("funciones.php");

$fechaIniBusqueda=$_GET['fechaIniBusqueda'];
$fechaFinBusqueda=$_GET['fechaFinBusqueda'];
$notaSalida=$_GET['notaIngreso'];
$global_almacen=$_GET['global_almacen'];
$provBusqueda=$_GET['provBusqueda'];
$grupoSalida=$_GET['grupoSalida'];

$codMaterial=$provBusqueda; 

//$fechaIniBusqueda=formateaFechaVista($fechaIniBusqueda);
//$fechaFinBusqueda=formateaFechaVista($fechaFinBusqueda);
	
//
$consulta = "select DISTINCT ( s.cod_salida_almacenes), s.fecha, s.hora_salida, ts.nombre_tiposalida, c.descripcion, a.nombre_almacen, 
		s.observaciones, 
		s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino, 
		(select es.nombre_estado_salida from estados_salida es where s.estado_salida=es.cod_estado_salida),
		(select es.color from estados_salida es where s.estado_salida=es.cod_estado_salida) 			
		FROM salida_almacenes s, tipos_salida ts, ciudades c, salida_detalle_almacenes sd,
		almacenes a where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='$global_almacen' and c.cod_ciudad=s.territorio_destino 
		and a.cod_almacen=s.almacen_destino and s.grupo_salida='$grupoSalida' and s.cod_salida_almacenes=sd.cod_salida_almacen ";
if($notaSalida!="")
   {$consulta = $consulta."AND s.nro_correlativo='$notaIngreso' ";
   }
if($fechaIniBusqueda!="--" && $fechaFinBusqueda!="--")
   {$consulta = $consulta."AND '$fechaIniBusqueda'<=s.fecha AND s.fecha<='$fechaFinBusqueda' ";
   }
if($provBusqueda!=0){
	$consulta=$consulta." AND sd.cod_material='$provBusqueda' ";
}   
$consulta = $consulta."order by s.fecha desc, s.nro_correlativo desc";

//echo $consulta;
$resp = mysql_query($consulta);
	

	
	echo "<br><center><table class='texto'>";
	echo "<tr>
		<th>&nbsp;</th><th>Nro. Salida</th><th>Fecha/Hora<br>Registro Salida</th><th>Tipo de Salida</th>
		<th>Territorio<br>Destino</th><th>Funcionario Destino</th><th>Observaciones</th>
		<th>Estado</th><th>&nbsp;</th><th>&nbsp;</th>
		</tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$fecha_salida=$dat[1];
		$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
		$hora_salida=$dat[2];
		$nombre_tiposalida=$dat[3];
		$nombre_ciudad=$dat[4];
		$nombre_almacen=$dat[5];
		$obs_salida=$dat[6];
		$estado_almacen=$dat[7];
		$nro_correlativo=$dat[8];
		$salida_anulada=$dat[9];
		$cod_almacen_destino=$dat[10];
		$nombreEstadoSalida=$dat[11];
		$colorSalida=$dat[12];
		
		echo "<input type='hidden' name='fecha_salida$nro_correlativo' value='$fecha_salida_mostrar'>";
		$estado_preparado=0;

		if($estado_almacen==0 || $estado_almacen==3){
			$chk="<input type='checkbox' name='codigo' value='$codigo'>";
		}else{
			$chk="&nbsp;";
		}
		
		if($estado_almacen==3){	
			$estado_preparado=1;
		}
		
		echo "<input type='hidden' name='estado_preparado' value='$estado_preparado'>";
		$sql_funcionario="select f.paterno, f.materno, f.nombres from funcionarios f, salida_detalle_visitador sv
		where sv.cod_salida_almacen='$codigo' and f.codigo_funcionario=sv.codigo_funcionario";
		$resp_funcionario=mysql_query($sql_funcionario);
		$dat_funcionario=mysql_fetch_array($resp_funcionario);
		$nombre_funcionario="$dat_funcionario[0] $dat_funcionario[1] $dat_funcionario[2]";
		//echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>$nombre_almacen</td><td>$nombre_funcionario</td><td>&nbsp;$obs_salida</td><td>$txt_detalle</td></tr>";
		echo "<tr>";
		echo "<td align='center'>$chk</td><td align='center'>$nro_correlativo</td>
			<td align='center'>$fecha_salida_mostrar $hora_salida</td>
			<td>$nombre_tiposalida</td><td>$nombre_ciudad</td>
			<td>&nbsp;$nombre_funcionario</td><td>&nbsp;$obs_salida</td>
			<td bgcolor='$colorSalida'>$nombreEstadoSalida</td>";
		
		$url_notaremision="navegador_detallesalidamuestras.php?codigo_salida=$codigo";
		
		echo "<td><a href='navegador_detallesalidamuestras.php?codigo_salida=$codigo&grupoSalida=$grupoSalida'><img src='imagenes/detalle.png' border='0' title='Ver Detalles de la Salida Interna' width='40'></a></td>";
		echo "<td><a target='_BLANK' href='navegador_detallesalidaenvio.php?codigo_salida=$codigo'><img src='imagenes/detalle.png' border='0' title='Ver Detalles de la Salida Interna' width='40'></a></td>
		</tr>";

	}
	echo "</table></center><br>";		


?>
