<?php
//header("Content-type: application/vnd.ms-excel"); 
//header("Content-Disposition: attachment; filename=archivo.xls"); 	
require("conexion.inc");
require("estilos_reportes.inc");
$rpt_visitador=$_GET["rpt_visitador"];
$rpt_gestion=$_GET["rpt_gestion"];
$rpt_ciclo=$_GET["rpt_ciclo"];
$rpt_territorio=$_GET["rpt_territorio"];
if($rpt_territorio!=0)
{	$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";
	$resp1=mysql_query($sql_cab);
	$dato=mysql_fetch_array($resp1);
	$nombre_territorio=$dato[1];	
	$cad_territorio="<br>Territorio: $nombre_territorio";

}
else
{	$cad_territorio="";
}
echo "<table border='0' class='textotit' align='center'><tr><th>Reporte Distribución Faltante de Envío<br>Gestion: $rpt_gestion Ciclo: $rpt_ciclo $cad_territorio</th></tr></table></center><br>";

if($rpt_territorio==0)
{	$sql_productos="select distinct(codigo_producto), grupo_salida from distribucion_productos_visitadores 
	where cod_ciclo='$rpt_ciclo' and codigo_gestion='$rpt_gestion' order by grupo_salida";
	$resp_productos=mysql_query($sql_productos);
	echo "<table border=1 class='texto'>";
	$sql_territorio="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
	$resp_territorios=mysql_query($sql_territorio);
	echo "<tr><th>&nbsp;Producto</th>";
	while($dat_territorios=mysql_fetch_array($resp_territorios))
	{	$cod_territorio=$dat_territorios[0];
		$nombre_territorio=$dat_territorios[1];
		echo "<th>$nombre_territorio</th>";
	}	
	echo "<th>Total</th><th>Stock Almacen Central</th></tr>";
	while($dat_productos=mysql_fetch_array($resp_productos))
	{	$cod_prod=$dat_productos[0];
		$grupo_salida=$dat_productos[1];
		//sacamos el nombre del producto
		if($grupo_salida==1)
		{	$sql_nom_prod=mysql_query("select descripcion, presentacion from muestras_medicas where codigo='$cod_prod'");
			$dat_nom_prod=mysql_fetch_array($sql_nom_prod);
			$nombre_producto="$dat_nom_prod[0] $dat_nom_prod[1]";
		}
		else
		{	$sql_nom_prod=mysql_query("select descripcion_material from material_apoyo where codigo_material='$cod_prod'");
			$dat_nom_prod=mysql_fetch_array($sql_nom_prod);
			$nombre_producto="$dat_nom_prod[0]";
		}
		$cad_mostrar="<tr><td>$nombre_producto</td>";
		$sql_territorio="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
		$resp_territorios=mysql_query($sql_territorio);
		$suma_cantidadfaltante=0;
		while($dat_territorios=mysql_fetch_array($resp_territorios))
		{	$cod_territorio=$dat_territorios[0];
			$nombre_territorio=$dat_territorios[1];
			$sql_dist="select sum(cantidad_planificada), sum(cantidad_distribuida) 
			from distribucion_productos_visitadores
			where codigo_producto='$cod_prod' and territorio='$cod_territorio'
			group by territorio";
			$resp_dist=mysql_query($sql_dist);
			$dat_dist=mysql_fetch_array($resp_dist);
			$cantidad_planificada=$dat_dist[0];
			$cantidad_distribuida=$dat_dist[1];
			$cantidad_faltante=$cantidad_planificada-$cantidad_distribuida;
			$suma_cantidadfaltante=$suma_cantidadfaltante+$cantidad_faltante;
			$cad_mostrar.="<td>&nbsp;$cantidad_faltante</td>";
		}
		$sql_stock="select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
		where id.cod_material='$cod_prod' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and 
		i.ingreso_anulado=0 and i.cod_almacen='1000'";
		$resp_stock=mysql_query($sql_stock);
		$dat_stock=mysql_fetch_array($resp_stock);
		$stock_real=$dat_stock[0];
		if($stock_real=="")
		{	$stock_real=0;
		}
		
		$cad_mostrar.="<th>$suma_cantidadfaltante</th><th>$stock_real</th></tr>";
		if($suma_cantidadfaltante>0)
		{	echo $cad_mostrar;
		}
			
	}
	echo "</table>";
}
if($rpt_territorio!=0)
{	$sql_productos="select distinct(codigo_producto), grupo_salida from distribucion_productos_visitadores 
	where cod_ciclo='$rpt_ciclo' and codigo_gestion='$rpt_gestion' order by grupo_salida";
	$resp_productos=mysql_query($sql_productos);
	echo "<table border=1 class='texto' align='center'>";
	$sql_visitadores="select paterno, materno, nombres from funcionarios where cod_ciudad='$rpt_territorio'
	and estado=1 and cod_cargo='1011'";
	if($rpt_visitador!=0)
	{	$sql_visitadores.=" and codigo_funcionario='$rpt_visitador'";
	}
	$sql_visitadores.=" order by paterno, materno";
	$resp_visitadores=mysql_query($sql_visitadores);
	echo "<tr><th>&nbsp;Producto</th>";
	while($dat_visitadores=mysql_fetch_array($resp_visitadores))
	{	$cod_visitador=$dat_visitadores[0];
		$nombre_visitador="$dat_visitadores[0] $dat_visitadores[2]";
		echo "<th>$nombre_visitador</th>";
	}	
	echo "<th>Total</th><th>Stock Almacen Central</th></tr>";
	while($dat_productos=mysql_fetch_array($resp_productos))
	{	$cod_prod=$dat_productos[0];
		$grupo_salida=$dat_productos[1];
		//sacamos el nombre del producto
		if($grupo_salida==1)
		{	$sql_nom_prod=mysql_query("select descripcion, presentacion from muestras_medicas where codigo='$cod_prod'");
			$dat_nom_prod=mysql_fetch_array($sql_nom_prod);
			$nombre_producto="$dat_nom_prod[0] $dat_nom_prod[1]";
		}
		else
		{	$sql_nom_prod=mysql_query("select descripcion_material from material_apoyo where codigo_material='$cod_prod'");
			$dat_nom_prod=mysql_fetch_array($sql_nom_prod);
			$nombre_producto="$dat_nom_prod[0]";
		}
		$cad_mostrar="<tr><td>$nombre_producto</td>";
		$sql_visitadores="select codigo_funcionario, paterno, materno, nombres from funcionarios where cod_ciudad='$rpt_territorio'
						and estado=1 and cod_cargo='1011'";
		if($rpt_visitador!=0)
		{	$sql_visitadores.=" and codigo_funcionario='$rpt_visitador'";
		}
		$sql_visitadores.=" order by paterno, materno";
		$resp_visitadores=mysql_query($sql_visitadores);
		$suma_cantidadfaltante=0;
		while($dat_visitadores=mysql_fetch_array($resp_visitadores))
		{	$cod_visitador=$dat_visitadores[0];
			$sql_dist="select sum(cantidad_planificada), sum(cantidad_distribuida) 
			from distribucion_productos_visitadores
			where codigo_producto='$cod_prod' and territorio='$rpt_territorio' 
			and cod_visitador='$cod_visitador' group by territorio, cod_visitador";
			$resp_dist=mysql_query($sql_dist);
			$dat_dist=mysql_fetch_array($resp_dist);
			$cantidad_planificada=$dat_dist[0];
			$cantidad_distribuida=$dat_dist[1];
			$cantidad_faltante=$cantidad_planificada-$cantidad_distribuida;
			$suma_cantidadfaltante=$suma_cantidadfaltante+$cantidad_faltante;
			$cad_mostrar.="<td>&nbsp;$cantidad_faltante</td>";
		}
		$sql_stock="select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
		where id.cod_material='$cod_prod' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and 
		i.ingreso_anulado=0 and i.cod_almacen='1000'";
		$resp_stock=mysql_query($sql_stock);
		$dat_stock=mysql_fetch_array($resp_stock);
		$stock_real=$dat_stock[0];
		if($stock_real=="")
		{	$stock_real=0;
		}
		
		$cad_mostrar.="<th>$suma_cantidadfaltante</th><th>$stock_real</th></tr>";
		if($suma_cantidadfaltante>0)
		{	echo $cad_mostrar;
		}			
	}
	echo "</table>";
}
require("imprimirInc.php");
	
?>