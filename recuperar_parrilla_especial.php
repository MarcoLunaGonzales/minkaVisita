<?php

require("conexion.inc");
require("estilos.inc");

	
echo "<script language='Javascript'>		
		function recuperar(f)
		{	var ciclo_rec,ciclo_trabajo;
			ciclo_rec=f.cod_ciclo.value;
		  	ciclo_trabajo=f.ciclo_trabajo.value;
			location.href='guardar_recuperacion_parrilla_especial.php?ciclo_rec='+ciclo_rec+'&ciclo_trabajo='+ciclo_trabajo+'';
		}	
		</script>";
	
	
	echo "<form action=''>";
	$sql1="select distinct(cod_ciclo), codigo_gestion from ciclos where codigo_gestion=1014 order by cod_ciclo";

	$sql=mysql_query($sql1);
	$filas_ciclos=mysql_num_rows($sql);
	echo "<center><table class='textotit'><tr><th>Replicación de Parrillas Especiales<br>Ciclo de Trabajo: $ciclo_trabajo</th></tr></table>";
	echo "<br><table border=1 cellspacing='0' class='texto'>";
	echo "<tr><td colspan='$filas_ciclos' align='center'>Seleccione Ciclo para replicar sus parrillas especiales</td></tr><tr>";
	while($dat=mysql_fetch_array($sql))
	{	$codigo_ciclo=$dat[0];
		$gestion_ciclo=$dat[1];
		echo "<td align='center'><a href='recuperar_parrilla_especial.php?cod_ciclo=$codigo_ciclo&ciclo_trabajo=$ciclo_trabajo'>$codigo_ciclo</td>";
	}
	echo "</tr></table></center><br>";
	
	echo "<form method='post'>";
	
	$sql_agencia="select cod_ciudad, descripcion from ciudades";
	$resp_agencia=mysql_query($sql_agencia);
	while($dat_agencia=mysql_fetch_array($resp_agencia))
	{
		$cod_ciudad=$dat_agencia[0];
		$descripcion_ciudad=$dat_agencia[1];
		$sql="select * from parrilla_especial p, grupo_especial g 
		where  g.codigo_grupo_especial=p.codigo_grupo_especial and 
		p.codigo_linea=$global_linea  and p.agencia=$cod_ciudad and p.cod_ciclo='$cod_ciclo' and p.codigo_gestion = $gestion_ciclo 
		order by p.agencia, p.cod_especialidad, p.numero_visita";
				
		//echo $sql;
		
		$resp=mysql_query($sql);
		$filas=mysql_num_rows($resp);
		$total_parrillas=$total_parrillas+$filas;
		if($filas>0)
		{	echo "<table align='center' class='texto'><tr><th>$descripcion_ciudad</th></tr></table>";
			echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
			echo "<tr><th>&nbsp;</th><th>Grupo Especial</th><th>Especialidad</th><th>Vísita</th><th>Parrilla Promocional</th></tr>";
			while($dat=mysql_fetch_array($resp))
			{
				$cod_parrilla=$dat[0];
				$cod_ciclo=$dat[1];
				$cod_espe=$dat[2];
				$fecha_creacion=$dat[4];
				$fecha_modi=$dat[5];
				$numero_de_visita=$dat[6];
				$agencia=$dat[7];
				$sql_agencia=mysql_query("select descripcion from ciudades where cod_ciudad='$agencia'");
				$dat_agencia=mysql_fetch_array($sql_agencia);
				$ciudad=$dat_agencia[0];
				$grupo_espe=$dat[8];
				$sql_grupoespe=mysql_query("select nombre_grupo_especial from grupo_especial where codigo_grupo_especial='$grupo_espe'");
				$dat_grupoespe=mysql_fetch_array($sql_grupoespe);
				$nombre_grupoespe=$dat_grupoespe[0];
				$sql1="select m.descripcion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones,p.prioridad,p.extra
						from muestras_medicas m, parrilla_detalle_especial p, material_apoyo mm
      					where p.codigo_parrilla_especial=$cod_parrilla and m.codigo=p.codigo_muestra and mm.codigo_material=p.codigo_material order by p.prioridad";
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
				echo "<tr><td align='center'><input type='checkbox' name='cod_parrilla' value=$cod_parrilla></td><td align='center'>$nombre_grupoespe</td><td align='center'>$cod_espe</td><td align='center'>$numero_de_visita</td><td align='center'>$parrilla_medica</td></tr>";
			}	
			echo "</table></center><br>";
		}
	}
	
	echo "</table></center><br>";
	echo "<input type='hidden' name='cod_ciclo' value='$cod_ciclo'>";
	echo "<input type='hidden' name='ciclo_trabajo' value='$ciclo_trabajo'>";
	echo"\n<table align='center'><tr><td><a href='navegador_parrilla_especial_ciclos.php?ciclo_trabajo=$ciclo_trabajo'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Recuperar' name='adicionar' class='boton' onclick='recuperar(this.form)'></td></tr>";
	echo "</form>";
	echo "</table></center><br>";
	echo "<center><table border='0' class='texto' width='80%'>";
	echo "<tr><th>Nota: Para recuperar correctamente las parrillas de un determinado ciclo, el ciclo de trabajo no debe de contar con ninguna parrilla definida.</th></tr>";
	echo "</table></center><br>";
?>