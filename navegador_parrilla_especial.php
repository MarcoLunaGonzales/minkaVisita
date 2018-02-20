<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='creacion_parrilla_especial.php?h_numero_muestras=1';
		}
		function recuperar()
		{	location.href='recuperar_parrilla.php';
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
			{	location.href='eliminar_parrilla_especial.php?datos='+datos+'';
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
					location.href='editar_parrilla_especial.php?j_cod_parrilla='+j_cod_parrilla+'';
				}
			}
		}
		</script>";	
	require("conexion.inc");
	require("estilos.inc");
	$sql_ciclo=mysql_query("select cod_ciclo from ciclos where estado='Activo' and codigo_linea=$global_linea");
	$dat=mysql_fetch_array($sql_ciclo);
	$cod_ciclo=$dat[0];
	
	echo "<form method='post' action='opciones_medico.php'>";
	$sql="select * from parrilla_especial where codigo_linea=$global_linea and cod_ciclo='$cod_ciclo' order by agencia,cod_especialidad,numero_visita";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Registro de Parrillas Especiales</td></tr></table></center><br>";
	echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><td>Producto Extra</td><td bgcolor='#66CCFF' width='30%'></td></tr></table></center><br>";
	echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
	echo "<tr><th>Agencia</th><th>Grupo Especial</th><th>Vísita</th><th>Parrilla Promocional</th><th>&nbsp;</th></tr>";
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
		echo "<tr><td align='center' class='texto'>$ciudad</td><td align='center'>$nombre_grupoespe</td>
		<td align='center'>$numero_de_visita</td><td align='center'>$parrilla_medica</td><td><a href='replicarParriEspe.php?codParrilla=$cod_parrilla' target='_new'>Replicar</a></td></tr>";
	}
	echo "</table></center><br>";
	echo "</form>";
?>