<script language='JavaScript'>
		function nuevoAjax()
		{	var xmlhttp=false;
 			try {
 				xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	 		} catch (e) {
 				try {
 					xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
 				} catch (E) {
 					xmlhttp = false;
 				}
  			}
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 			xmlhttp = new XMLHttpRequest();
			}
			return xmlhttp;
		}
		function cambiaStock(f, id, codigo, tipoStock, cantidad){
			var contenedor;
			contenedor = document.getElementById(id);
			ajax=nuevoAjax();
			ajax.open('GET', 'ajaxCambiaStock.php?codigo='+codigo+'&tipoStock='+tipoStock+'&cantidad='+cantidad+'&id='+id+'',true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText
				}
			}
	 		ajax.send(null)
		}		
		function guardaAjaxStock(cantidad, codigo, tipoStock, id){
			var contenedor;
			cantidadStock=cantidad.value;
			contenedor = document.getElementById(id);
			ajax=nuevoAjax();
			ajax.open('GET', 'ajaxGuardaStock.php?codigo='+codigo+'&tipoStock='+tipoStock+'&cantidad='+cantidadStock+'',true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText
				}
			}
	 		ajax.send(null)
		}
</script>

<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
	require("conexion.inc");
	require("estilos_almacenes.inc");
	echo "<form method='post' action=''>";
	$sql="select m.codigo, m.descripcion, m.presentacion, m.estado, tm.nombre_tipo_muestra, m.codigo_linea, m.stock_minimo, m.stock_reposicion, m.stock_maximo
from muestras_medicas m, tipos_muestra tm
where m.cod_tipo_muestra=tm.cod_tipo_muestra and m.estado=1 order by m.descripcion";
	echo "<center><table border='0' class='textotit'><tr><th>Stocks de Productos de Visita</th></tr></table></center><br>";
	$resp=mysql_query($sql);
	echo "<center><table border='1' class='texto' cellspacing='0' width='95%'>";
	echo "<tr><th>&nbsp;</th><th>Producto</th><th>Stock Min</th><th>Stock Reposicion</th><th>Stock Max</th></tr>";
	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$muestra=$dat[1];
		$presentacion=$dat[2];
		$estado=$dat[3];
		$tipo_muestra=$dat[4];
		$codigo_linea=$dat[5];
		$stock_min=$dat[6];
		$stock_reposicion=$dat[7];
		$stock_max=$dat[8];
		if($stock_min==""){$stock_min=0;}
		if($stock_reposicion==""){$stock_reposicion=0;}
		if($stock_max==""){$stock_max=0;}
		$sql_linea="select nombre_linea from lineas where codigo_linea='$codigo_linea'";
		$resp_linea=mysql_query($sql_linea);
		$dat_linea=mysql_fetch_array($resp_linea);
		$nombre_linea=$dat_linea[0];
		echo "<tr><td align='center'>$indice_tabla</td>
			<td>$muestra $presentacion</td>
			<td><div id='1$codigo' onDblClick='cambiaStock(this.form, this.id,\"$codigo\",1,$stock_min)';>&nbsp; $stock_min</div></td>
			<td><div id='2$codigo' onDblClick='cambiaStock(this.form, this.id,\"$codigo\",2,$stock_reposicion)';>&nbsp; $stock_reposicion</div></td>
			<td><div id='3$codigo' onDblClick='cambiaStock(this.form, this.id,\"$codigo\",3,$stock_max)';>&nbsp; $stock_max</div></td></tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	echo "</form>";
?>
