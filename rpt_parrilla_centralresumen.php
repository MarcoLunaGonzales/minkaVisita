<?php
	require("conexion.inc");
	require("estilos_reportes_central.inc");
	//echo $rpt_territorio;
	$global_linea=$linea_rpt;
		$sql_cabecera_gestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
		$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
		$nombre_cab_gestion=$datos_cab_gestion[0];
	echo "<form method='post' action='opciones_medico.php'>";
	$sql_ciclo=mysql_query("select cod_ciclo from ciclos where estado='Activo' and codigo_linea='$global_linea'");
	$dat=mysql_fetch_array($sql_ciclo);
	$cod_ciclo=$dat[0];
	echo "<center><table border='0' class='textotit'><tr><th>Parrillas Promocionales Resumen: <br>Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
	echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><td>Producto Extra</td><td bgcolor='#66CCFF' width='30%'></td></tr></table></center><br>";

	////////////
	$sql="select codigo_parrilla, cod_especialidad, categoria_med, agencia, codigo_l_visita from parrilla 
	where cod_ciclo='$ciclo_rpt' and codigo_gestion='$gestion_rpt' and codigo_linea='$linea_rpt'
	and agencia='$rpt_territorio' group BY cod_especialidad, categoria_med, codigo_l_visita";	
		
//	echo $sql;
	$resp=mysql_query($sql);
	$filas=mysql_num_rows($resp);
	echo "<table align='center' class='texto'><tr><th>Todos los Territorios</th></tr></table>";
	echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
	echo "<tr><th>Especialidad</th><th>Categoria</th><th>Agencia</th><th>Linea Visita</th><th>Parrilla Promocional</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_parrilla=$dat[0];
		$cod_espe=$dat[1];
		$cod_cat=$dat[2];
		$agencia=$dat[3];
		$cod_lineavisita=$dat[4];
		$sql_nombre_lineavisita="select nombre_l_visita from lineas_visita where codigo_l_visita='$cod_lineavisita'";
		$resp_lineavisita=mysql_query($sql_nombre_lineavisita);
		$dat_lineavisita=mysql_fetch_array($resp_lineavisita);
		$nombre_lineavisita=$dat_lineavisita[0];
		$sql1="SELECT pd.codigo_muestra, SUM(pd.cantidad_muestra) from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla 
and p.cod_especialidad='$cod_espe' and p.categoria_med='$cod_cat' and p.agencia='$agencia' 
and p.codigo_l_visita='$cod_lineavisita' and p.cod_ciclo='$ciclo_rpt' and p.codigo_gestion='$gestion_rpt' 
and p.codigo_linea='$linea_rpt' group by codigo_muestra";
//                echo $sql1;
		$resp1=mysql_query($sql1);
		$parrilla_medica="<table class='textomini' width='100%' border='0'>";
		$parrilla_medica=$parrilla_medica."<tr><th>Producto</th><th>Cantidad</th></tr>";
		while($dat1=mysql_fetch_array($resp1))
		{
			$codigo_muestra=$dat1[0];
			$cantidad_muestra=$dat1[1];
			$sql_nombre_muestra=mysql_query("select descripcion, presentacion from muestras_medicas where codigo='$codigo_muestra'");
			$dat_nombremuestra=mysql_fetch_array($sql_nombre_muestra);
			$nombre_muestra="$dat_nombremuestra[0] $dat_nombremuestra[1]";
			$parrilla_medica=$parrilla_medica."<tr><td>$nombre_muestra</td><td>$cantidad_muestra</td></tr>";
		}
		$parrilla_medica=$parrilla_medica."</table>";
		echo "<tr><th>$cod_espe</th><th>$cod_cat</th><th>$agencia</th>
		<th>&nbsp;$nombre_lineavisita</th><td>$parrilla_medica</td></tr>";
	}
	echo "</table></center><br>";
	echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
?>