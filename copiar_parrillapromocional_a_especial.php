<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
	echo "<script language='Javascript'>
	function copiar_parrilla(f)
	{	var i;
		var j=0;
		datos=new Array();
		for(i=0;i<=f.length-1;i++)
		{
			if(f.elements[i].type=='checkbox')
			{	if(f.elements[i].checked==true)
				{	datos[j]=f.elements[i].value;
					j=j+1;
				}
			}
		}
		if(j==0)
		{	alert('Debe seleccionar al menos una parrilla para copiar.');
		}
		else
		{	location.href='guarda_copiaparrillapromocionalaespecial.php?datos='+datos+'&ciclo_trabajo=$ciclo_trabajo&grupo_especial=$grupo_especial';
		}
	}
	</script>";
	require("conexion.inc");
	require("estilos.inc");
	echo "<form method='post' action='opciones_medico.php'>";
	$sql_ciclo=mysql_query("select cod_ciclo from ciclos where cod_ciclo='$ciclo_trabajo' and codigo_gestion='$codigo_gestion' and codigo_linea=$global_linea");
	$dat=mysql_fetch_array($sql_ciclo);
	$cod_ciclo=$dat[0];
	echo "<center><table border='0' class='textotit'><tr><th>Copiar de Parrillas Promocionales a Especiales<br>Ciclo de Trabajo:$ciclo_trabajo</th></tr></table></center><br>";
	echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><td>Producto Extra</td><td bgcolor='#66CCFF' width='30%'></td></tr></table></center><br>";

	$sql_agencia="select cod_ciudad, descripcion from ciudades";
	$resp_agencia=mysql_query($sql_agencia);
	while($dat_agencia=mysql_fetch_array($resp_agencia))
	{
		$cod_ciudad=$dat_agencia[0];
		$descripcion_ciudad=$dat_agencia[1];
		//seleccionando parrillas dependiendo de la agencia
		$sql="select * from parrilla where agencia=$cod_ciudad and cod_ciclo='$cod_ciclo' and codigo_gestion='$codigo_gestion' order by cod_ciclo,cod_especialidad,categoria_med,numero_visita";
		$resp=mysql_query($sql);
		$filas=mysql_num_rows($resp);
		if($filas>0)
		{	echo "<table align='center' class='texto'><tr><th>$descripcion_ciudad</th></tr></table>";
			echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
			echo "<tr><th>&nbsp;</th><th>Ciclo</th><th>Categoria</th><th>Visita</th><th>Parrilla Promocional</th></tr>";
			while($dat=mysql_fetch_array($resp))
			{
				$cod_parrilla=$dat[0];
				$cod_ciclo=$dat[1];
				$cod_espe=$dat[2];
				$cod_cat=$dat[3];
				$codigo_linea=$dat[4];
				$fecha_creacion=$dat[5];
				$fecha_modi=$dat[6];
				$numero_de_visita=$dat[7];
				$sql1="select m.descripcion, m.presentacion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones,p.prioridad,p.extra
					from muestras_medicas m, parrilla_detalle p, material_apoyo mm
      				where p.codigo_parrilla=$cod_parrilla and m.codigo=p.codigo_muestra and mm.codigo_material=p.codigo_material order by p.prioridad";
				$resp1=mysql_query($sql1);
				$parrilla_medica="<table class='textomini' width='100%' border='0'>";
				$parrilla_medica=$parrilla_medica."<tr><th>Orden</th><th>Producto</th><th>Cantidad</th><th>Material de Apoyo</th><th>Cantidad</td><th>Obs.</th></tr>";
				while($dat1=mysql_fetch_array($resp1))
				{
					$muestra="$dat1[0] $dat1[1]";
					$cant_muestra=$dat1[2];
					$material=$dat1[3];
					$cant_material=$dat1[4];
					$obs=$dat1[5];
					$prioridad=$dat1[6];
					$extra=$dat1[7];
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
					$parrilla_medica=$parrilla_medica."$fondo_extra<td align='center'>$prioridad</td><td width='35%'>$muestra</td><td align='center' width='10%'>$cant_muestra</td><td align='center' width='35%'>$material</td><td align='center' width='10%'>$cant_material</td><td align='center' width='10%'>$observaciones</td></tr>";
				}
				$parrilla_medica=$parrilla_medica."</table>";
				echo "<tr><td align='center'><input type='checkbox' name='cod_parrilla' value=$cod_parrilla></td><td align='center' class='texto'>$cod_ciclo</td><td align='center'>$cod_cat</td><td align='center'>$numero_de_visita</td><td align='center'>$parrilla_medica</td></tr>";
			}
			echo "</table></center><br>";
			// fin de seleccionando parrillas dependiendo de la agencia
		}//aqui cierra el if
	}
	/// Este  codigo   se realiza en el caso de que exista parrilla paratodas las agencias
	$sql="select * from parrilla where agencia=0 and codigo_gestion='$codigo_gestion' and cod_ciclo='$cod_ciclo' order by cod_ciclo,codigo_linea, cod_especialidad,categoria_med,numero_visita";
	$resp=mysql_query($sql);
	$filas=mysql_num_rows($resp);
	if($filas>0)
		{	echo "<table align='center' class='texto'><tr><th>Todas las Agencias</th></tr></table>";
			echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
			echo "<tr><th>&nbsp;</th><th>Ciclo</th><th>Línea</th><th>Especialidad</th><th>Categoria</th><th>Visita</th><th>Línea de Visita</th><th>Parrilla Promocional</th></tr>";
			while($dat=mysql_fetch_array($resp))
			{
				$cod_parrilla=$dat[0];
				$cod_ciclo=$dat[1];
				$cod_espe=$dat[2];
				$cod_cat=$dat[3];
				$codigo_linea=$dat[4];
				$sql_nombrelinea=mysql_query("select nombre_linea from lineas where codigo_linea='$codigo_linea'");
				$dat_nombrelinea=mysql_fetch_array($sql_nombrelinea);
				$nombre_linea=$dat_nombrelinea[0];
				$fecha_creacion=$dat[5];
				$fecha_modi=$dat[6];
				$numero_de_visita=$dat[7];
				$codigo_lineavisita=$dat[9];
				$sql_nombrelineavisita="select nombre_l_visita from lineas_visita where codigo_l_visita='$codigo_lineavisita'";
				$resp_nombrelineavisita=mysql_query($sql_nombrelineavisita);
				$dat_nombrelineavisita=mysql_fetch_array($resp_nombrelineavisita);
				$nombre_lineavisita=$dat_nombrelineavisita[0];
				$sql1="select m.descripcion, m.presentacion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones,p.prioridad,p.extra
					from muestras_medicas m, parrilla_detalle p, material_apoyo mm
      				where p.codigo_parrilla=$cod_parrilla and m.codigo=p.codigo_muestra and mm.codigo_material=p.codigo_material order by p.prioridad";
				$resp1=mysql_query($sql1);
				$parrilla_medica="<table class='textomini' width='100%' border='0'>";
				$parrilla_medica=$parrilla_medica."<tr><th>Orden</th><th>Producto</th><th>Cantidad</th><th>Material de Apoyo</th><th>Cantidad</td><th>Obs.</th></tr>";
				while($dat1=mysql_fetch_array($resp1))
				{
					$muestra="$dat1[0] $dat1[1]";
					$cant_muestra=$dat1[2];
					$material=$dat1[3];
					$cant_material=$dat1[4];
					$obs=$dat1[5];
					$prioridad=$dat1[6];
					$extra=$dat1[7];
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
					$parrilla_medica=$parrilla_medica."$fondo_extra<td align='center'>$prioridad</td><td width='35%'>$muestra</td><td align='center' width='10%'>$cant_muestra</td><td align='center' width='35%'>$material</td><td align='center' width='10%'>$cant_material</td><td align='center' width='10%'>$observaciones</td></tr>";
				}
				$parrilla_medica=$parrilla_medica."</table>";
				echo "<tr><td align='center'><input type='checkbox' name='cod_parrilla' value='$cod_parrilla'></td><td align='center' class='texto'>$cod_ciclo</td><td>$nombre_linea</td><td>$cod_espe</td><td align='center'>$cod_cat</td><td align='center'>$numero_de_visita</td><td align='left'>&nbsp;$nombre_lineavisita</td><td align='center'>$parrilla_medica</td></tr>";
			}
			echo "</table></center><br>";

		}//aqui cierra el if

	//fin de parrillas para todas las agencias
	echo"\n<table align='center'><tr><td><a href='navegador_parrillas_espe_ciclos.php?ciclo_trabajo=$ciclo_trabajo'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Copiar' class='boton' onclick='copiar_parrilla(this.form)'></td></tr></table></center>";
	echo "</form>";
?>