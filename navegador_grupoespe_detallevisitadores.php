<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='anadir_visitador_grupoespe.php?cod_grupo=$cod_grupo';
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
			{	alert('Debe seleccionar al menos al menos un visitador para eliminarlo del Grupo Especial.');
			}
			else
			{	
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_visitador_grupo_espe.php?datos='+datos+'&cod_grupo=$cod_grupo';
				}
				else
				{
					return(false);
				}
			}
		}
		</script>";	
	require("conexion.inc");
	require("estilos_gerencia.inc");
	echo "<form>";
	//formamos la cabecera
	$sql_cab="select nombre_grupo_especial from grupo_especial where codigo_grupo_especial='$cod_grupo'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_grupo_espe=$dat_cab[0];
	//fin formar cabecera
	echo "<center><table border='0' class='textotit'><tr><td align='center'>Visitadores de Grupos Especiales<br>Grupo Especial:<strong>$nombre_grupo_espe</strong></td></tr></table></center><br>";	
	$sql_visitadores="select f.codigo_funcionario, f.paterno, f.materno, f.nombres from funcionarios f, 
	grupo_especial_detalle_visitadores gd where f.codigo_funcionario=gd.codigo_funcionario and
	gd.codigo_grupo_especial='$cod_grupo'";
	$resp_visitadores=mysql_query($sql_visitadores);
	echo "<table border=1 class='texto' align='center'><tr><th>&nbsp;</th><th>Visitador</th></tr>";
	while($dat_visitadores=mysql_fetch_array($resp_visitadores))
	{	$codigo_visitador=$dat_visitadores[0];
		$nombre_visitador="$dat_visitadores[1] $dat_visitadores[2] $dat_visitadores[3]";
		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo_visitador'></td>
		<td>$nombre_visitador</td></tr>";
	}
	echo "</table></center><br>";
	echo"\n<table align='center'><tr><td><a href='navegador_grupo_especial.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td></tr></table></center>";
	echo "</form>";
?>