<?php
	require("conexion.inc");
	require("estilos_reportes.inc");
	echo "<form method='post' action='opciones_medico.php'>";
	$sql_ciclo=mysql_query("select cod_ciclo from ciclos where estado='Activo' and codigo_linea='$global_linea'");
	$dat=mysql_fetch_array($sql_ciclo);
	$cod_ciclo=$dat[0];
	echo "<center><table border='0' class='textotit'><tr><td>Parrillas Promocionales</td></tr></table></center><br>";
	echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><td>Producto Extra</td><td bgcolor='#66CCFF' width='30%'></td></tr></table></center><br>";

	$sql_agencia="select cod_ciudad, descripcion from ciudades where cod_ciudad='$global_agencia'";
	$resp_agencia=mysql_query($sql_agencia);
	while($dat_agencia=mysql_fetch_array($resp_agencia))
	{
		$cod_ciudad=$dat_agencia[0];
		$descripcion_ciudad=$dat_agencia[1];
		//seleccionando parrillas dependiendo de la agencia
		if($cod_especialidad=="")
		{	$sql="select * from parrilla where agencia=$cod_ciudad and codigo_linea=$global_linea and cod_ciclo='$cod_ciclo' order by cod_ciclo,cod_especialidad,categoria_med,numero_visita";
		}
		else
		{	$sql="select * from parrilla where cod_especialidad='$cod_especialidad' and agencia=$cod_ciudad and codigo_linea=$global_linea and cod_ciclo='$cod_ciclo' order by cod_ciclo,cod_especialidad,categoria_med,numero_visita";
		}
		$resp=mysql_query($sql);
		$filas=mysql_num_rows($resp);
		if($filas>0)
		{	echo "<table align='center' class='texto'><tr><th>$descripcion_ciudad</th></tr></table>";
			echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
			echo "<tr><th>Ciclo</th><th>Especialidad</th><th>Categoria</th><th>Visita</th><th>Parrilla Promocional</th></tr>";
			while($dat=mysql_fetch_array($resp))
			{
				$cod_parrilla=$dat[0];
				$cod_ciclo=$dat[1];
				$cod_espe=$dat[2];
				$cod_cat=$dat[3];
				$fecha_creacion=$dat[5];
				$fecha_modi=$dat[6];
				$numero_de_visita=$dat[7];
				$sql1="select m.descripcion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones,p.prioridad,p.extra
				from muestras_medicas m, parrilla_detalle p, material_apoyo mm
      				where p.codigo_parrilla=$cod_parrilla and m.codigo=p.codigo_muestra and mm.codigo_material=p.codigo_material order by p.prioridad";
				$resp1=mysql_query($sql1);
				$parrilla_medica="<table class='textomini' width='100%' border='0'>";
				$parrilla_medica=$parrilla_medica."<tr><th>Orden</th><th>Producto</th><th>Cantidad</th><th>Material de Apoyo</th><th>Cantidad</td><th>Obs.</th></tr>";
				while($dat1=mysql_fetch_array($resp1))
				{
					$muestra=$dat1[0];
					$cant_muestra=$dat1[1];
					$material=$dat1[2];
					$cant_material=$dat1[3];
					$obs=$dat1[4];
					$prioridad=$dat1[5];
					$extra=$dat1[6];
					if($extra==1)
					{	$fondo_extra="<tr bgcolor='#66CCFF'>";
					}
					else
					{	$fondo_extra="<tr>";
					}
					if($obs!="")
					{	
			  			$observaciones="<img src='imagenes/informacion.gif' alt='$obs'>";
					}
					else
					{
					 $observaciones="&nbsp;";
					}
					$parrilla_medica=$parrilla_medica."$fondo_extra<td align='center'>$prioridad</td><td align='center' width='35%'>$muestra</td><td align='center' width='10%'>$cant_muestra</td><td align='center' width='35%'>$material</td><td align='center' width='10%'>$cant_material</td><td align='center' width='10%'>$observaciones</td></tr>";
				}
				$parrilla_medica=$parrilla_medica."</table>";
				echo "<tr><td align='center' class='texto'>$cod_ciclo</td><td align='center'>$cod_espe</td><td align='center'>$cod_cat</td><td align='center'>$numero_de_visita</td><td align='center'>$parrilla_medica</td></tr>";
			}
			echo "</table></center><br>";
	 	}
	}
	
	
	////////////
	if($cod_especialidad=="")
	{	$sql="select * from parrilla where agencia=0 and codigo_linea=$global_linea and cod_ciclo='$cod_ciclo' order by cod_ciclo,cod_especialidad,categoria_med,numero_visita";	
	}
	else
	{	$sql="select * from parrilla where cod_especialidad='$cod_especialidad' and agencia=0 and codigo_linea=$global_linea and cod_ciclo='$cod_ciclo' order by cod_ciclo,cod_especialidad,categoria_med,numero_visita";
		
	}
		$resp=mysql_query($sql);
		$filas=mysql_num_rows($resp);
		if($filas>0)
		{	echo "<table align='center' class='texto'><tr><th>Todas las Agencias</th></tr></table>";
			echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
			echo "<tr><th>Ciclo</th><th>Especialidad</th><th>Categoria</th><th>Visita</th><th>Parrilla Promocional</th></tr>";
			while($dat=mysql_fetch_array($resp))
			{
				$cod_parrilla=$dat[0];
				$cod_ciclo=$dat[1];
				$cod_espe=$dat[2];
				$cod_cat=$dat[3];
				$fecha_creacion=$dat[5];
				$fecha_modi=$dat[6];
				$numero_de_visita=$dat[7];
				$sql1="select m.descripcion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones,p.prioridad,p.extra
				from muestras_medicas m, parrilla_detalle p, material_apoyo mm
      				where p.codigo_parrilla=$cod_parrilla and m.codigo=p.codigo_muestra and mm.codigo_material=p.codigo_material order by p.prioridad";
				$resp1=mysql_query($sql1);
				$parrilla_medica="<table class='textomini' width='100%' border='0'>";
				$parrilla_medica=$parrilla_medica."<tr><th>Orden</th><th>Producto</th><th>Cantidad</th><th>Material de Apoyo</th><th>Cantidad</td><th>Obs.</th></tr>";
				while($dat1=mysql_fetch_array($resp1))
				{
					$muestra=$dat1[0];
					$cant_muestra=$dat1[1];
					$material=$dat1[2];
					$cant_material=$dat1[3];
					$obs=$dat1[4];
					$prioridad=$dat1[5];
					$extra=$dat1[6];
					if($extra==1)
					{	$fondo_extra="<tr bgcolor='#66CCFF'>";
					}
					else
					{	$fondo_extra="<tr>";
					}
					if($obs!="")
					{	
			  			$observaciones="<img src='imagenes/informacion.gif' alt='$obs'>";
					}
					else
					{
					 $observaciones="&nbsp;";
					}
					$parrilla_medica=$parrilla_medica."$fondo_extra<td align='center'>$prioridad</td><td align='center' width='35%'>$muestra</td><td align='center' width='10%'>$cant_muestra</td><td align='center' width='35%'>$material</td><td align='center' width='10%'>$cant_material</td><td align='center' width='10%'>$observaciones</td></tr>";
				}
				$parrilla_medica=$parrilla_medica."</table>";
				echo "<tr><td align='center' class='texto'>$cod_ciclo</td><td align='center'>$cod_espe</td><td align='center'>$cod_cat</td><td align='center'>$numero_de_visita</td><td align='center'>$parrilla_medica</td></tr>";
			}
			echo "</table></center><br>";
		}
		echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
?>