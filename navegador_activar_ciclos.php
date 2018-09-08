<?php

require("conexion.inc");

echo "<script language='Javascript'>
		
		function enviar_nav(){	
			location.href='crear_ciclos.php';
		}
		function editar_nav(f){
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
		function activar(f)
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
					location.href='rutero_actual.php?cod_ciclo='+j_ciclo+'';
				}
			}
		}
		</script>";	
	require("estilos_gerencia.inc");
	echo "<form method='post' action=''>";
	// utilizamos $codigo_gestion de estilos.inc
	$sqlGestion="select distinct(codigo_gestion), nombre_gestion from gestiones where estado='Activo'";
	$respGestion=mysql_query($sqlGestion);
	$datGestion=mysql_fetch_array($respGestion);
	$codGestion=$datGestion[0];
	$nombreGestion=$datGestion[1];
	
	$sql="select * from ciclos where codigo_gestion='1000' order by fecha_ini desc LIMIT 0,12";
	$resp=mysql_query($sql);
	$indice_tabla=1;
	echo "<h1>Ciclos Gestion: $nombreGestion</h1>";
	
	
	echo "<center><table class='zebra'>";
	echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Nro. de Ciclo</th><th>Fecha de Inicio</th><th>Fecha de Fin</th><th>Estado</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$fecha_inicio=$dat[1];
		$fecha_inicio="$fecha_inicio[8]$fecha_inicio[9]-$fecha_inicio[5]$fecha_inicio[6]-$fecha_inicio[0]$fecha_inicio[1]$fecha_inicio[2]$fecha_inicio[3]";
		$fecha_fin=$dat[2];
		$fecha_fin="$fecha_fin[8]$fecha_fin[9]-$fecha_fin[5]$fecha_fin[6]-$fecha_fin[0]$fecha_fin[1]$fecha_fin[2]$fecha_fin[3]";
		$estado=$dat[3];
		if($estado=="Inactivo")
		{	$desc_estado="Programado"; 
			echo "<tr><td align='center'>$indice_tabla</td><td><input type='checkbox' name='ciclo' value='$codigo|$codGestion'></td><td align='center'>$codigo</td><td align='center'>$fecha_inicio</td><td align='center'>$fecha_fin</td><td align='center'>$desc_estado</td></tr>";
		}
		if($estado=="Activo")
		{	$desc_estado="Activo"; 
			echo "<tr><td align='center'>$indice_tabla</td><td>&nbsp;</td><td align='center'>$codigo</td><td align='center'>$fecha_inicio</td><td align='center'>$fecha_fin</td><td align='center'>$desc_estado</td></tr>";
		}
		if($estado=="Cerrado")
		{	$desc_estado="Cerrado"; 
			echo "<tr><td align='center'>$indice_tabla</td><td>&nbsp;</td><td align='center'>$codigo</td><td align='center'>$fecha_inicio</td><td align='center'>$fecha_fin</td><td align='center'>$desc_estado</td></tr>";
		}
		$indice_tabla++;		
	}
	echo "</table></center><br>";
	echo "<div class='divBotones'>";
	echo "<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Activar' class='boton' onclick='activar(this.form)'>";
	echo "</div>";
	echo "</form>";
?>