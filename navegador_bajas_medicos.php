<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_baja_medicos.php';
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
			{	alert('Debe seleccionar al menos un Medico para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_baja_medicos.php?datos='+datos+'';
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
			var j_cargo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cargo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un Medico para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Medico para editar sus datos.');
				}
				else
				{
					location.href='editar_baja_medicos.php?cod_medico='+j_cargo+'';
				}
			}
		}
		</script>";
	require("conexion.inc");
	require("estilos_regional.inc");
	echo "<form method='post' action=''>";
	$sql="select b.cod_med, b.inicio, b.fin, b.codigo_motivo, l.nombre_linea 
	from baja_medicos b, medicos m, lineas l where m.cod_med=b.cod_med and 
	m.cod_ciudad='$global_agencia' and b.codigo_linea=l.codigo_linea order by b.inicio desc limit 0,100";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Registro de Baja de Medicos</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='60%'>";
	echo "<tr><th>&nbsp;</th><th>Medico</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Motivo</th><th>Línea</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo_medico=$dat[0];
		$fecha_ini=$dat[1];
		$fecha_fin=$dat[2];
		$codigo_motivo=$dat[3];
		$nombre_linea=$dat[4];
		$sql_medico="select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$codigo_medico'";
		$resp_medico=mysql_query($sql_medico);
		$dat_medico=mysql_fetch_array($resp_medico);
		$nombre_medico="$dat_medico[0] $dat_medico[1] $dat_medico[2]";
		$sql_motivo="select descripcion_motivo from motivos_baja where codigo_motivo='$codigo_motivo'";
		$resp_motivo=mysql_query($sql_motivo);
		$dat_motivo=mysql_fetch_array($resp_motivo);
		$nombre_motivo=$dat_motivo[0];
		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo_medico'></td><td>$nombre_medico</td>
		<td align='center'>$fecha_ini</td><td align='center'>$fecha_fin</td><td>$nombre_motivo</td>
		<td>$nombre_linea</td></tr>";
	}
	echo "</table></center><br>";
	require("home_regional.inc");
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	echo "</form>";
?>