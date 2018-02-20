<?php

require("conexion.inc");
$global_ciclo_distribucion=$_COOKIE['global_ciclo_distribucion'];
$global_gestion_distribucion=$_COOKIE['global_gestion_distribucion'];

?>
<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
<script type="text/javascript" src="ajax/navegador_lineas_distribucion/send.data.js"></script>
<script type="text/javascript" src="ajax/ejecutar_distribucion/send2.data.js"></script>
<script type='text/javascript' language='javascript'>
$(document).ready(function(){
	$('.ins_dis_li_ra').click(function(){
		$('#loaderr').css('display','block')
		var cod= $(this).attr('cod')
		sendData(cod);
	});
	$('.eje_dis_mmma').click(function(){
		$('#loaderr').css('display','block')
		var codi= $(this).attr('codi')
		sendDataa(codi);
	});
})
</script>
<?php
echo "
<script language='JavaScript'>
function distribuye_mmma(codigo_linea){  
	location.href='efectuar_distribucion_mmma.php?codigo_linea='+codigo_linea+'';
}
function eliminarDist(codigo_linea) {  
	if(confirm('Esta seguro de eliminar la distribucion.')) {   
		location.href='eliminarDistribucionLineas.php?global_linea_distribucion='+codigo_linea+'';
	}
}
</script>";
if ( $global_usuario == 1052 ) {
	require("estilos_gerencia.inc");
} else {
	require("estilos_inicio_adm.inc");
}
$sql_gestion = "select nombre_gestion, codigo_gestion from gestiones where codigo_gestion=1014";
$resp_gestion = mysql_query( $sql_gestion );
$dat_gestion = mysql_fetch_array( $resp_gestion );
$nombre_gestion = $dat_gestion[ 0 ];
$codGestion=$dat_gestion[1];
$sql = "select * from lineas where linea_promocion = 1 and estado=1 order by nombre_linea";
$resp = mysql_query( $sql );
echo "<center><table border='0' class='textotit'><tr><td align='center'>Distribucion de MM y MA por L&iacute;neas<br>Ciclo: <strong>$global_ciclo_distribucion</strong> Gesti&oacute;n: <strong>$nombre_gestion</strong></td></tr></table></center><br>";

$sqlVerificaDevolucion="select count(*) from distribucion_productos_visitadores d where d.codigo_gestion='$global_gestion_distribucion' 
	and d.cod_ciclo='$global_ciclo_distribucion' and d.cantidad_devolucion_total_visitador>0";
$respVerificaDevolucion=mysql_query($sqlVerificaDevolucion);
$filasDevolucion=mysql_result($respVerificaDevolucion,0,0);

if($filasDevolucion==0){
	echo "<br><table align='center'><tr><td align='center'><a href='procesoAplicarDevolucionMMDistribucion.php'>
		Aplicar MM de Visitadores<br><img src='imagenes/visitador.png' width='50' height='65' alt='Aplicar MM en custodia de Visitadores'>
		</a></td></tr></table>";
}else{
	echo "<br><table align='center'><tr><td align='center'><a href=''>Datos de MM de Visitadores Aplicados<br>
	<img src='imagenes/visitadorok.png' width='65' height='65'></a></td></tr></table>";
}


?>
<div style=" margin: 0 auto; position: relative">
	<div id="loaderr" style="background: #000; width:80%; height:700px; position:absolute; top: 0; left: 10%; opacity: 0.8; z-index: 7; display: none; filter: alpha(opacity = 80);">
		<img src="imagenes/loader.gif" alt="" style="position: absolute; z-index: 9; width: 55px; margin: 0 auto; left: 47%; top: 32%" />
	</div>
	<?php
	echo "<center><table border='1' class='texto' cellspacing='0' width='80%'>";
//echo "<tr><td>&nbsp;</td><th>L�neas</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
	echo "<tr><td>&nbsp;</td><th>L&iacute;neas</th><th>Procesar</th><th>Eliminar</th><th>Ejecutar Proceso</th></tr>";
	$indice_tabla = 1;
	while ( $dat = mysql_fetch_array( $resp ) ) {
		$codigo = $dat[ 0 ];
		$nombre = $dat[ 1 ];
		$sqlVeriDistri="select count(*) from distribucion_productos_visitadores where codigo_gestion='$global_gestion_distribucion' and
				cod_ciclo='$global_ciclo_distribucion' and codigo_linea='$codigo'";
		$respVeriDistri=mysql_query($sqlVeriDistri);
		$contadorDistribucion=mysql_result($respVeriDistri,0,0);
		$imagenTieneDatos="";
		if($contadorDistribucion>0){
			$imagenTieneDatos="<img src='imagenes/aprobado.png' width='25px' height='25px' alt='Con Distribucion'>";
		}
		
		echo "<tr><td align='center'>$indice_tabla</td></td><td>$nombre $imagenTieneDatos</td>
		<td align='center'><a href='#' class='ins_dis_li_ra' cod='$codigo'><img src='imagenes/procesar.png' width='40px' height='40px' alt='Preparar Distribuci&oacute;n de Muestras en base a ruteros'></a></td>
		<td align='center'><a href='javascript:eliminarDist($codigo)'><img src='imagenes/eliminarproceso.gif' width='40px' height='40px' alt='Eliminar Distribucion'></a></td>";
		/*<td align='center'><a href='cambiarProductoDistribucion.php?global_linea_distribucion=$codigo' target='_blank'>Intercambiar Productos>></a></td>
		<td align='center'><a href='cambiarMaterialDistribucion.php?global_linea_distribucion=$codigo' target='_blank'>Intercambiar MA>></a></td>
		<td align='center'><a href='eliminarProductoDistribucion.php?global_linea_distribucion=$codigo' target='_blank'>Eliminar Productos>></a></td>
		<td align='center'><a href='eliminarMaterialDistribucion.php?global_linea_distribucion=$codigo' target='_blank'>Eliminar MA>></a></td>*/
		echo "<td align='center'><a class='eje_dis_mmma' href='#' codi='$codigo'><img src='imagenes/ejecutarproceso.jpg'  width='40px' height='40px' alt='Ejecutar Distribuci&oacute;n de MM y MA'></a></td></tr>";
		$indice_tabla ++;
	}
	echo "</table></center>";
	?>
</div>
<?php
if($filasDevolucion==0){
	echo "<br><table align='center'><tr><td align='center'><a href='procesoAplicarDevolucionMMDistribucion.php'>
		Aplicar MM de Visitadores<br><img src='imagenes/visitador.png' width='50' height='65' alt='Aplicar MM en custodia de Visitadores'>
		</a></td></tr></table>";
}else{
	echo "<br><table align='center'><tr><td align='center'><a href=''>Datos de MM de Visitadores Aplicados<br>
	<img src='imagenes/visitadorok.png' width='65' height='65'></a></td></tr></table>";
}
?>

<!--<td align='center'><a href='ajax/navegador_lineas_distribucion/send.php?global_linea_distribucion=$codigo' class='ins_dis_li_ra' cod='$codigo'>Preparar Distribuci&oacute;n de Muestras en base a ruteros>></a></td>-->