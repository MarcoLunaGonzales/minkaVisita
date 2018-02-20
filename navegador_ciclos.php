<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='crear_ciclos.php';
		}
		function eliminar_nav(f)
		{	
			var i;
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
			{	alert('Debe seleccionar al menos un ciclo para proceder a su eliminación.');
			}
			else
			{	
				alert('Al eliminar un ciclo, también se eliminara con ello toda la informacion concerniente a tal ciclo.');
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_ciclos.php?datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}
		function activa_ciclo(f)
		{
			var i;
			var j=0;
			var j_ciclo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_ciclo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un ciclo para activarlo.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un ciclo para activarlo.');
				}
				else
				{
					location.href='activar_ciclos.php?cod_ciclo='+j_ciclo+'';
				}
			}
		}
		function cerrar_ciclo(f)
		{
			var i;
			var j=0;
			var j_ciclo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_ciclo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un ciclo para cerrarlo.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un ciclo para cerrarlo.');
				}
				else
				{
					location.href='cerrar_ciclos.php?cod_ciclo='+j_ciclo+'';
				}
			}
		}
		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_ciclo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_ciclo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un ciclo para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un ciclo para editar sus datos.');
				}
				else
				{
					location.href='editar_ciclos.php?cod_ciclo='+j_ciclo+'';
				}
			}
		}
		</script>";	
	require("conexion.inc");
	require("estilos.inc");
	echo "<form method='post' action=''>";
	// utilizamos $codigo_gestion de estilos.inc
	$sql="select * from ciclos where codigo_linea='$global_linea' and codigo_gestion='$codigo_gestion' order by fecha_ini desc LIMIT 0,12";
	$resp=mysql_query($sql);
	$indice_tabla=1;
	echo "<center><table border='0' class='textotit'><tr><td>Registro de Ciclos</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='60%'>";
	echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Número de Ciclo</th><th>Fecha de Inicio</th><th>Fecha de Fin</th><th>Estado</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$fecha_inicio=$dat[1];
		$fecha_inicio="$fecha_inicio[8]$fecha_inicio[9]-$fecha_inicio[5]$fecha_inicio[6]-$fecha_inicio[0]$fecha_inicio[1]$fecha_inicio[2]$fecha_inicio[3]";
		$fecha_fin=$dat[2];
		$fecha_fin="$fecha_fin[8]$fecha_fin[9]-$fecha_fin[5]$fecha_fin[6]-$fecha_fin[0]$fecha_fin[1]$fecha_fin[2]$fecha_fin[3]";
		$estado=$dat[3];
		if($estado=="Activo")
		{	$desc_estado="En Curso"; 
			echo "<tr><td align='center'>$indice_tabla</td><td>&nbsp;</td><td align='center'>$codigo</td><td align='center'>$fecha_inicio</td><td align='center'>$fecha_fin</td><td align='center'>$desc_estado</td></tr>";
		}
		if($estado=="Inactivo")
		{	$desc_estado="Programado"; 
			echo "<tr><td align='center'>$indice_tabla</td><td><input type='checkbox' name='codigos_ciclos' value='$codigo'></td><td align='center'>$codigo</td><td align='center'>$fecha_inicio</td><td align='center'>$fecha_fin</td><td align='center'>$desc_estado</td></tr>";
		}
		if($estado=="Cerrado")
		{	$desc_estado="Cerrado"; 
			echo "<tr><td align='center'>$indice_tabla</td><td>&nbsp;</td><td align='center'>$codigo</td><td align='center'>$fecha_inicio</td><td align='center'>$fecha_fin</td><td align='center'>$desc_estado</td></tr>";
		}
		$indice_tabla++;
		
	}
	echo "</table></center><br>";
	require('home_central1.inc');
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center><br>";
	echo "</form>";
?>