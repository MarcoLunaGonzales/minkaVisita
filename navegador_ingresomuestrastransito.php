<?php

require("conexion.inc");

echo "<script language='Javascript'>
		function editar_nav(f, grupoIngreso)
		{
			var i;
			var j=0;
			var j_cod_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un Ingreso Pendiente para registrar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Ingreso Pendiente para registrar sus datos.');
				}
				else
				{
					location.href='registrar_ingresotransito.php?codigo_registro='+j_cod_registro+'&grupoIngreso='+grupoIngreso;
				}
			}
		}
		</script>";
		
require("estilos_almacenes.inc");

echo "<form method='post' action=''>";

$grupoIngreso=$_GET["grupoIngreso"];

$sql = "SELECT s.cod_salida_almacenes, s.cod_almacen, s.despacho_fecha, ts.nombre_tiposalida, c.descripcion, 
a.nombre_almacen, s.observaciones, s.nro_correlativo , s.fecha FROM salida_almacenes s, tipos_salida ts, 
ciudades c, almacenes a where s.cod_tiposalida=ts.cod_tiposalida and s.almacen_destino='$global_almacen' and 
s.cod_almacen<>'$global_almacen' and s.grupo_salida='$grupoIngreso' and s.estado_salida=1 and s.salida_anulada <> 1 
and c.cod_ciudad=s.territorio_destino and a.cod_almacen=s.cod_almacen order by fecha DESC";

// echo $sql;
$resp = mysql_query($sql);
if($grupoIngreso==1){
	echo "<h1>Traspasos de Muestras Pendientes de Ingreso</h1>";	
}else{
	echo "<h1>Traspasos de Material Pendientes de Ingreso</h1>";
}

echo "<div class='divBotones'>
	<input type='button' value='Aceptar Traspaso' name='adicionar' class='boton' onclick='editar_nav(this.form,$grupoIngreso)'>
	</div>";

echo "<center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Fecha</th><th>Fecha Despacho</th><th>Tipo de Salida<br>(Origen)</th><th>Territorio<br>Origen</th><th>Nota de Remision<br>(Origen)</th><th>Observaciones</th><th>Funcionario Destino</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
while ($dat = mysql_fetch_array($resp)) {
    $codigo = $dat[0];
    $cod_almacen_origen = $dat[1];
    $sql_almacen_origen = mysql_query("select a.nombre_almacen, c.descripcion from almacenes a, ciudades c 
		where a.cod_almacen=$cod_almacen_origen and a.cod_ciudad=c.cod_ciudad");
    $dat_almacen_origen = mysql_fetch_array($sql_almacen_origen);
    $nombre_almacen_origen = $dat_almacen_origen[0];
    $ciudad_almacen_origen = $dat_almacen_origen[1];
    $fecha_salida = $dat[2];
    $fecha_salida_mostrar = "$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
    $nombre_tiposalida = $dat[3];
    $nombre_ciudad = $dat[4];
    $nombre_almacen = $dat[5];
    $obs_salida = $dat[6];
    $nro_correlativo = $dat[7];
    $fecha = $dat[8];
    $sql_funcionario = "select f.paterno, f.materno, f.nombres from funcionarios f, salida_detalle_visitador sv
		where sv.cod_salida_almacen='$codigo' and f.codigo_funcionario=sv.codigo_funcionario";
    $resp_funcionario = mysql_query($sql_funcionario);
    $dat_funcionario = mysql_fetch_array($resp_funcionario);
    $nombre_funcionario = "$dat_funcionario[0] $dat_funcionario[1] $dat_funcionario[2]";
    echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha</td><td align='center'>$fecha_salida_mostrar</td><td align='center'>$nombre_tiposalida</td><td>$nombre_almacen_origen $ciudad_almacen_origen</td><td align='center'>$nro_correlativo</td><td>&nbsp;$obs_salida</td><td>&nbsp;$nombre_funcionario</td>";
    echo "<td><a target='_BLANK' href='navegador_detallesalidamuestrastransito.php?codigo_salida=$codigo&almacen_origen=$cod_almacen_origen&grupo_salida=$grupoIngreso'><img src='imagenes/detalle.png' title='Ver Detalles' width='40'></a></td>";
    echo "<td><a target='_BLANK' href='navegador_detalleingresotransitoenvio.php?codigo_salida=$codigo&almacen_origen=$cod_almacen_origen'><img src='imagenes/detalle.png' border='0' title='Detalles Envio' width='40'></a></td></tr>";
}
echo "</table></center><br>";

echo "<div class='divBotones'>
	<input type='button' value='Aceptar Traspaso' name='adicionar' class='boton' onclick='editar_nav(this.form, $grupoIngreso)'>
	</div>";
	
echo "</form>";
?>