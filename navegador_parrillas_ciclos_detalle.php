<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
	echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='creacion_parrilla.php?h_numero_muestras=1&ciclo_trabajo=$ciclo_trabajo&gestion_trabajo=$gestion_trabajo';
		}
		function recuperar()
		{	location.href='recuperar_parrilla.php?ciclo_trabajo=$ciclo_trabajo';
		}
		function eliminar_nav(f)
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
			{	alert('Debe seleccionar al menos una parrilla para proceder a su eliminación.');
			}
			else
			{	if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_parrilla.php?datos='+datos+'&cod_especialidad=$cod_especialidad&ciclo_trabajo=$ciclo_trabajo';
				}
				else
				{
					return(false);
				}
			}
		}
		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_cod_parrilla;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_parrilla=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente una parrilla para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar una parrilla para editar sus datos.');
				}
				else
				{
					location.href='editar_parrilla.php?j_cod_parrilla='+j_cod_parrilla+'&ciclo_trabajo=$ciclo_trabajo'+'&gestion_trabajo=2';
				}
			}
		}
		</script>";
	require("conexion.inc");
	require("estilos.inc");

	$ciclo_trabajo=$_GET['ciclo_trabajo'];
	$gestion_trabajo=$_GET['gestion_trabajo'];
	
	//$codTerritorio=$_get['codTerritorio'];

	echo "<form method='post' action='opciones_medico.php'>";
	$sql_ciclo=mysql_query("select cod_ciclo, codigo_gestion from ciclos where cod_ciclo='$ciclo_trabajo' and codigo_gestion='$gestion_trabajo' and codigo_linea=$global_linea");
	$dat=mysql_fetch_array($sql_ciclo);
		$cod_ciclo=$dat[0];
		$cod_gestion=$dat[1];
	echo "<center><table border='0' class='textotit'><tr><th>Registro de Parrillas Promocionales<br>Ciclo de Trabajo:$ciclo_trabajo</th></tr></table></center><br>";
	echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><td>Producto Extra</td><td bgcolor='#66CCFF' width='30%'></td></tr></table></center><br>";
	
	
	if($codTerritorio!=''){
		$sql_agencia="select cod_ciudad, descripcion from ciudades where cod_ciudad=$codTerritorio";
	}else{
		$sql_agencia="select cod_ciudad, descripcion from ciudades";
	}
		
	$resp_agencia=mysql_query($sql_agencia);
	while($dat_agencia=mysql_fetch_array($resp_agencia))
	{
		$cod_ciudad=$dat_agencia[0];
		$descripcion_ciudad=$dat_agencia[1];
		//seleccionando parrillas dependiendo de la agencia si la agencia tiene valor la especialidad no cuenta
		if($cod_especialidad==''){
			$sql="select * from parrilla where agencia=$cod_ciudad and 
					codigo_linea=$global_linea and cod_ciclo='$ciclo_trabajo' and codigo_gestion='$gestion_trabajo' 
					order by cod_ciclo,cod_especialidad,categoria_med,numero_visita, codigo_l_visita";
		}else{
			$sql="select * from parrilla where cod_especialidad='$cod_especialidad' and agencia=$cod_ciudad and 
					codigo_linea=$global_linea and cod_ciclo='$ciclo_trabajo' and codigo_gestion='$gestion_trabajo' 
					order by cod_ciclo,cod_especialidad,categoria_med,numero_visita, codigo_l_visita";
		}
		$resp=mysql_query($sql);
		$filas=mysql_num_rows($resp);
		if($filas>0)
		{	echo "<table align='center' class='texto'><tr><th><h2>$descripcion_ciudad</h2></th></tr></table>";
			echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
			echo "<tr><th>&nbsp;</th><th>Ciclo</th><th>Esp.</th><th>Categoria</th><th>Visita</th><th>Linea de Visita</th><th>Parrilla Promocional</th></tr>";
			while($dat=mysql_fetch_array($resp))
			{
				$cod_parrilla=$dat[0];
				$cod_ciclo=$dat[1];
				$cod_espe=$dat[2];
				$cod_cat=$dat[3];
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
				echo "<tr><td align='center'><input type='checkbox' name='cod_parrilla' value=$cod_parrilla></td><td align='center' class='texto'><a href='$cod_parrilla'>$cod_ciclo</a></td>
				<td align='center' class='texto'>$cod_espe</td>
				<td align='center'>$cod_cat</td>
				<td align='center'>$numero_de_visita</td>
				<td>$nombre_lineavisita&nbsp;</td>
				<td align='center'>$parrilla_medica</td></tr>";
			}
			echo "</table></center><br>";
			// fin de seleccionando parrillas dependiendo de la agencia
		}//aqui cierra el if
	}
	/// Este  codigo   se realiza en el caso de que exista parrilla paratodas las agencias
	$sql="select * from parrilla where cod_especialidad='$cod_especialidad' and agencia=0 and codigo_linea=$global_linea and codigo_gestion='$codigo_gestion' and cod_ciclo='$cod_ciclo' order by cod_ciclo,cod_especialidad,categoria_med,numero_visita";
	$resp=mysql_query($sql);
	$filas=mysql_num_rows($resp);
	if($filas>0)
		{	echo "<table align='center' class='texto'><tr><th>Todas las Agencias</th></tr></table>";
			echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
			echo "<tr><th>&nbsp;</th><th>Ciclo</th><th>Categoria</th><th>Visita</th><th>Línea de Visita</th><th>Parrilla Promocional</th></tr>";
			while($dat=mysql_fetch_array($resp))
			{
				$cod_parrilla=$dat[0];
				$cod_ciclo=$dat[1];
				$cod_espe=$dat[2];
				$cod_cat=$dat[3];
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
				echo "<tr><td align='center'><input type='checkbox' name='cod_parrilla' value=$cod_parrilla></td><td align='center' class='texto'><a href='$cod_parrilla'>$cod_ciclo</a></td><td align='center'>$cod_cat</td><td align='center'>$numero_de_visita</td><td align='left'>&nbsp;$nombre_lineavisita</td><td align='center'>$parrilla_medica</td></tr>";
			}
			echo "</table></center><br>";

		}//aqui cierra el if

	//fin de parrillas para todas las agencias
	echo"\n<table align='center'><tr><td><a href='navegador_parrillas_espe_ciclos.php?ciclo_trabajo=$ciclo_trabajo'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	echo "<center><table border='0' class='texto'>";
	
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	
	echo "</form>";
?>