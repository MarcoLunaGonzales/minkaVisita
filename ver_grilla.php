<?php
	header("Content-Type: text/html; charset=UTF-8");  
	require("conexion.inc");
	require("estilos_gerencia.inc");
	$codigo_linea=$_GET['codigo_linea'];
	$global_linea=$codigo_linea;
	
	echo "<center><table border='0' class='textotit'>";
	echo "<tr><td align='center'>Grilla</center></td></tr></table><br>";
	echo "<center><table border='1' cellspacing='0' class='texto' width='60%'>";
	echo "<tr><th>Nombre Grilla</th><th>Territorio</th><th>Distrito</th>
	<th>Fecha de Creacion</th><th>Fecha de Modificacion</th><th>&nbsp</th></tr>";
	
	$sql="select g.codigo_grilla, g.nombre_grilla, g.agencia, g.total_medicos, g.total_contactos, g.total_visitadores, 
		g.contactos_visitador, g.fecha_creacion, g.fecha_modificacion, 
		g.codigo_linea, g.estado, (select d.descripcion from distritos d where d.cod_dist=g.cod_distrito)distrito 
		from grilla g
		where codigo_grilla='$codigo_grilla'";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
		$codigo=$dat[0];
		$nombre=$dat[1];
		$cod_ciudad=$dat[2];
		$total_medicos=$dat[3];
		$total_contactos=$dat[4];
		$total_visitadores=$dat[5];
		$conxvis=$dat[6];
		$fecha_creacion=$dat[7];
		$fecha_modi=$dat[8];
		$distrito=$dat[11];
			$sql1="select cod_ciudad,descripcion from ciudades where cod_ciudad='$cod_ciudad'";
			$resp1=mysql_query($sql1);
			$dato=mysql_fetch_array($resp1);
			$ciudad=$dato[1];	
		echo "<tr><td align='center'>$nombre</td><td align='center'>$ciudad</td>
		<td align='center'>$distrito</td>
		<td align='center'>$fecha_creacion</td><td align='center'>$fecha_modi</td></tr>";
	echo "</table><br>";
	
	$sql_det="select * from grilla_detalle where codigo_grilla='$codigo' order by cod_especialidad,cod_categoria";

	$resp_detalle=mysql_query($sql_det);
	echo "<center><table border='1' cellspacing='0' class='texto' width='30%'>";
	echo "<tr><th>Especialidad</th><th>Categoria</th><th>Frecuencia</th></tr>";
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{
		$espe=$dat_detalle[1];
		$cat=$dat_detalle[2];
		$frecuencia=$dat_detalle[3];
		
		if($frecuencia>0)
		{	echo "<tr><td align='left'>$espe</td><td align='center'>$cat</td><td align='center'>$frecuencia</td></tr>";
		}		
	}
	echo "</table><br>";

	echo "<a href='navegador_grillas.php?cod_ciudad=$cod_ciudad&codigo_linea=$global_linea'><--Volver Atras</a>";
?>