<?php

	require("conexion.inc");
	require("estilos.inc");

	echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='creacion_parrilla_especial.php?h_numero_muestras=1&ciclo_trabajo=$ciclo_trabajo';
		}
		function recuperar()
		{	location.href='recuperar_parrilla_especial.php?ciclo_trabajo=$ciclo_trabajo';
		}
		function copiar_parrilla()
		{	location.href='copiar_parrillapromocional_a_especial.php?ciclo_trabajo=$ciclo_trabajo&grupo_especial=$grupo_especial';
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
			{	location.href='eliminar_parrilla_especial.php?datos='+datos+'&ciclo_trabajo=$ciclo_trabajo';
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
					location.href='editar_parrilla_especial.php?j_cod_parrilla='+j_cod_parrilla+'&ciclo_trabajo=$ciclo_trabajo';
				}
			}
		}
		</script>";
	echo "<form method='post' action='opciones_medico.php'>";

	$sql_ciclo=mysql_query("select cod_ciclo from ciclos where cod_ciclo='$ciclo_trabajo' and codigo_linea=$global_linea");
	// echo("select cod_ciclo from ciclos where cod_ciclo='$ciclo_trabajo' and codigo_linea=$global_linea");
	
	$dat=mysql_fetch_array($sql_ciclo);
	$cod_ciclo=$dat[0];
	echo "<center><table border='0' class='textotit'><tr><td align='center'>Registro de Parrillas Especiales<br>Ciclo Trabajo:$ciclo_trabajo</td></tr></table></center><br>";
	echo"\n<table align='center'><tr><td><a href='navegador_parrillas_especial_ciclos.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
	echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><td>Producto Extra</td><td bgcolor='#66CCFF' width='30%'></td></tr></table></center><br>";
	$total_parrillas=0;

	$sql_agencia="select cod_ciudad, descripcion from ciudades";
	$resp_agencia=mysql_query($sql_agencia);
	while($dat_agencia=mysql_fetch_array($resp_agencia))
	{
		$cod_ciudad=$dat_agencia[0];
		$descripcion_ciudad=$dat_agencia[1];
		$sql="select * from parrilla_especial where codigo_linea=$global_linea 
			and agencia=$cod_ciudad and cod_ciclo='$ciclo_trabajo'and codigo_gestion=1014 and codigo_grupo_especial='$grupo_especial' order by agencia,cod_especialidad,numero_visita";
		// $sql="select * from parrilla_especial where codigo_linea=$global_linea  and agencia=$cod_ciudad and cod_ciclo='$cod_ciclo'and codigo_gestion=1010 order by agencia,cod_especialidad,numero_visita";
		
		//echo $sql."<br />";
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
				$sql1="select m.descripcion, m.presentacion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones,p.prioridad,p.extra
						from muestras_medicas m, parrilla_detalle_especial p, material_apoyo mm
      					where p.codigo_parrilla_especial=$cod_parrilla and m.codigo=p.codigo_muestra and mm.codigo_material=p.codigo_material order by p.prioridad";
				$resp1=mysql_query($sql1);
				$parrilla_medica="<table class='textomini' width='100%' border='0'>";
				$parrilla_medica=$parrilla_medica."<tr><th>Orden</th><th>Producto</th><th>Cantidad</th><th>Material de Apoyo</th><th>Cantidad</td><th>Obs.</th></tr>";
				while($dat1=mysql_fetch_array($resp1))
				{
					$muestra=$dat1[0];
					$presentacion=$dat1[1];
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
					$parrilla_medica.="$fondo_extra<td align='center'>$prioridad</td><td align='left' width='35%'>$muestra $presentacion</td><td align='center' width='10%'>$cant_muestra</td><td align='left' width='35%'>$material</td><td align='center' width='10%'>$cant_material</td><td align='center' width='10%'>$observaciones</td></tr>";
				}
				$parrilla_medica.="</table>";
				echo "<tr><td align='center'><input type='checkbox' name='cod_parrilla' value=$cod_parrilla></td><td align='center'>$nombre_grupoespe</td><td align='center'>$cod_espe</td><td align='center'>$numero_de_visita</td><td align='center'>$parrilla_medica</td></tr>";
			}
			echo "</table></center><br>";
		}
	}
	echo"\n<table align='center'><tr><td><a href='navegador_parrillas_especial_ciclos_grupos.php?ciclo_trabajo=$ciclo_trabajo'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
	echo "<center><table border='0' class='texto'><tr>";
	if($total_parrillas==0)
	{	echo "<td><input type='button' value='Replicar' class='boton' onclick='recuperar(this.form)'></td>";
	}
	echo "<td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td>
	<td><input type='button' value='Eliminar' class='boton' onclick='eliminar_nav(this.form)'></td>
	<td><input type='button' value='Editar' class='boton' onclick='editar_nav(this.form)'></td>
	<td><input type='button' value='Copiar de PP' title='Copiar de Parrillas Promocionales' class='boton' onclick='copiar_parrilla()'></td>
	</tr></table></center>";
	echo "</form>";
?>