<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
echo "<script language='Javascript'>
		function anadir_categoria(f)
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
			{	alert('Debe seleccionar al menos un Producto para añadirlo a la lista de Productos Objetivo.');
				return(false);
			}
			else
			{	 location.href='guardar_prod_objetivo.php?datos='+datos+'&j_cod_med=$j_cod_med';
		    }
		}
		function sel_todo(f)
		{
			var i;
			var j=0;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.todo.checked==true)
					{	f.elements[i].checked=true;
					}
					else
					{	f.elements[i].checked=false;
					}
					
				}
			}
		}
		</script>
	";
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	$sql_cab=mysql_query("select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$j_cod_med'");
	$dat_cab=mysql_fetch_array($sql_cab);
	$nombre_medico="$dat_cab[2] $dat_cab[0] $dat_cab[1]";
	$sql_cab_espe=mysql_query("select c.cod_especialidad,c.categoria_med, e.descripcion
								from categorias_lineas c, especialidades_medicos e
								where c.cod_med=e.cod_med and c.cod_med='$j_cod_med' and c.cod_especialidad=e.cod_especialidad and c.codigo_linea='$global_linea' order by e.descripcion");
	$num_filas_cab=mysql_num_rows($sql_cab_espe);
	if($num_filas_cab==1)
	{	$dat=mysql_fetch_array($sql_cab_espe);	
		$espe_cab="$dat[2]:<strong>$dat[0]</strong> Categoria:<strong>$dat[1]</strong>";
	}
	else
	{	while($dat=mysql_fetch_array($sql_cab_espe))
		{	$espe_cab=$espe_cab."$dat[2]:<strong>$dat[0]</strong> Categoria:<strong>$dat[1]</strong> ";
		}
	}
	echo "<center><table border='0' class='textotit'><tr><td>Seleccionar Productos Objetivo</td></tr></table></center>";
	echo "<center><table border='0' class='textotit'><tr><td>Medico: <strong>$nombre_medico</strong> $espe_cab</td></tr></table></center><br>";
	echo "<form method='post'>";
	$sql="select codigo,descripcion,presentacion from muestras_medicas order by descripcion";
	$resp=mysql_query($sql);
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' class='boton' onclick='anadir_categoria(this.form)'></td></tr></table></center>";
	echo "<br><center><table class='texto' border=1 cellspacing='0'>";
	echo "<tr><td><input type='checkbox' name='todo' onClick='sel_todo(this.form)'>Seleccionar Todo</td></tr></table>";
	echo "<center><table border='1' cellspacing='0' class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Producto</th><th>Presentación</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{	$codigo_mm=$dat[0];
		$prod=$dat[1];
		$pres=$dat[2];
		$ban=0;
		$sql_filtro="select codigo_muestra from muestras_negadas where codigo_linea='$global_linea' and cod_med='$j_cod_med'";
		$resp_filtro=mysql_query($sql_filtro);
		while($dat_filtro=mysql_fetch_array($resp_filtro))
		{	$codigo_filtro=$dat_filtro[0];
			if($codigo_filtro==$codigo_mm)
			{	$ban=1;
			}		
		}
		$sql_filtro="select codigo_muestra from productos_objetivo where codigo_linea='$global_linea' and cod_med='$j_cod_med'";
		$resp_filtro=mysql_query($sql_filtro);
		while($dat_filtro=mysql_fetch_array($resp_filtro))
		{	$codigo_filtro=$dat_filtro[0];
			if($codigo_filtro==$codigo_mm)
			{	$ban=1;
			}		
		}
		if($ban==0)
		{	echo "<tr><td align='center'><input type='checkbox' name='codigo' value='$codigo_mm'></td><td>$prod</td><td>$pres</td></tr>";
		}		
	}
		echo "</table></center><br>";
		echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
		echo "<center><table border='0' class='texto'>";
		echo "<tr><td><input type='button' value='Adicionar' class='boton' onclick='anadir_categoria(this.form)'></td></tr></table></center>";
		echo "</form>";
?>