<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
echo "<script language='Javascript'>
		function recuperar(f)
		{	var ciclo_rec,ciclo_trabajo;
			var gestion_rec, gestion_trabajo;
			ciclo_rec=f.cod_ciclo.value;
		  	ciclo_trabajo=f.ciclo_trabajo.value;
			gestion_rec=f.cod_gestion.value;
			gestion_trabajo=f.gestion_trabajo.value;
			
			location.href='guardar_recuperacion_parrilla.php?ciclo_rec='+ciclo_rec+'&ciclo_trabajo='+ciclo_trabajo+'&gestion_rec='+gestion_rec+'&gestion_trabajo='+gestion_trabajo+'';
		}
		</script>";
	require("conexion.inc");
	require("estilos.inc");
	require("funcion_nombres.php");
	
	$nombreGestion=nombreGestion($gestion_trabajo);
	echo "<form action=''>";
	
	$sql=mysql_query("select distinct(cod_ciclo), codigo_gestion from ciclos where codigo_gestion='$codigo_gestion' order by cod_ciclo");
	$filas_ciclos=mysql_num_rows($sql);
	echo "<center><table class='textotit'><tr><th>Replicar de Parrillas Promocionales
		<br>Ciclo de Trabajo: $ciclo_trabajo Gestion Trabajo: $nombreGestion</th></tr></table>";
	echo "<br><table border=1 cellspacing='0' class='texto'>";
	echo "<tr><td colspan='$filas_ciclos' align='center'>Seleccione Ciclo para replicar sus parrillas</td></tr><tr>";
	while($dat=mysql_fetch_array($sql))
	{	$codigo_ciclo=$dat[0];
		$codGestion=$dat[1];
		$nombreGestionRec=nombreGestion($codGestion);
		echo "<td align='center'><a href='recuperar_parrilla.php?cod_ciclo=$codigo_ciclo&cod_gestion=$codGestion&ciclo_trabajo=$ciclo_trabajo&gestion_trabajo=$gestion_trabajo'>$codigo_ciclo - $nombreGestionRec</td>";
	}
	echo "</tr></table></center><br>";

	echo "<form method='post' action='opciones_medico.php'>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Recuperar' name='adicionar' class='boton' onclick='recuperar(this.form)'></td></tr>";
	echo "</table></center><br>";
	$sql="select * from parrilla where codigo_linea=$global_linea and cod_ciclo='$cod_ciclo' and codigo_gestion='$cod_gestion' order by cod_ciclo,cod_especialidad,categoria_med asc";
	$resp=mysql_query($sql);
	echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
	echo "<tr><th>Ciclo</th><th>Especialidad</th><th>Categoria</th><th>Parrilla Promocional</th><th>Creación</th><th>Modificación</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_parrilla=$dat[0];
		$cod_ciclo=$dat[1];
		$cod_espe=$dat[2];
		$cod_cat=$dat[3];
		$fecha_creacion=$dat[5];
		$fecha_modi=$dat[6];
		$sql1="select m.descripcion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material
				from muestras_medicas m, parrilla_detalle p, material_apoyo mm
      				where p.codigo_parrilla=$cod_parrilla and m.codigo=p.codigo_muestra and mm.codigo_material=p.codigo_material order by p.prioridad";
		$resp1=mysql_query($sql1);
		$parrilla_medica="<table class='textomini' width='100%' border='1'>";
		$parrilla_medica=$parrilla_medica."<tr><th>Producto</th><th>Cantidad</th><th>Material de Apoyo</th><th>Cantidad</td></tr>";
		while($dat1=mysql_fetch_array($resp1))
		{
			$muestra=$dat1[0];
			$cant_muestra=$dat1[1];
			$material=$dat1[2];
			$cant_material=$dat1[3];
			$parrilla_medica=$parrilla_medica."<tr><td align='center' width='40%'>$muestra</td><td align='center' width='10%'>$cant_muestra</td><td align='center' width='40%'>$material</td><td align='center' width='10%'>$cant_material</td></tr>";
		}
		$parrilla_medica=$parrilla_medica."</table>";
		echo "<tr><td align='center' class='texto'>$cod_ciclo</td><td align='center'>$cod_espe</td><td align='center'>$cod_cat</td><td align='center'>$parrilla_medica</td><td align='center'>$fecha_creacion</td><td align='center'>$fecha_modi</td></tr>";
	}
	echo "</table></center><br>";
	echo "<input type='hidden' name='cod_ciclo' value='$cod_ciclo'>";
	echo "<input type='hidden' name='ciclo_trabajo' value='$ciclo_trabajo'>";
	
	echo "<input type='hidden' name='cod_gestion' value='$cod_gestion'>";
	echo "<input type='hidden' name='gestion_trabajo' value='$gestion_trabajo'>";
	
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Recuperar' name='adicionar' class='boton' onclick='recuperar(this.form)'></td></tr>";
	echo "</form>";
	echo "</table></center><br>";
	echo "<center><table border='0' class='texto' width='80%'>";
	echo "<tr><th>Nota: Para recuperar correctamente las parrillas de un determinado ciclo, el ciclo de trabajo no debe de contar con ninguna parrilla definida.</th></tr>";
	echo "</table></center><br>";
?>