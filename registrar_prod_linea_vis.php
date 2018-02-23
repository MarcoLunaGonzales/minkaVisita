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
			{	alert('Debe seleccionar al menos un Producto para añadirlo a la Línea de Visita.');
				return(false);
			}
			else
			{	 location.href='guardar_prod_linea.php?datos='+datos+'&cod_linea_vis=$cod_linea_vis';
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
	require("estilos.inc");
	echo "<form method='post' action='opciones_medico.php'>";
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1);'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Añadir a Línea Visita' class='boton' onclick='anadir_categoria(this.form)'></td></tr></table></center>";
	$sql="select codigo,descripcion,presentacion from muestras_medicas order by descripcion";
	$resp=mysql_query($sql);
	echo "<br><center><table class='texto' border=1 cellspacing='0'>";
	echo "<tr><td><input type='checkbox' name='todo' onClick='sel_todo(this.form)'>Seleccionar Todo</td></tr></table>";
	echo "<br><center><table border='1' cellspacing='0' class='texto' width='70%'>";
	echo "<tr><th>&nbsp;</th><th>Producto</th><th>Presentación</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{	$codigo_mm=$dat[0];
		$prod=$dat[1];
		$pres=$dat[2];
		$ban=0;
		$sql_filtro="select codigo_mm from lineas_visita_detalle where codigo_l_visita='$cod_linea_vis'";
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
		echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1);'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
		echo "<center><table border='0' class='texto'>";
		echo "<tr><td><input type='button' value='Añadir a Línea Visita' class='boton' onclick='anadir_categoria(this.form)'></td></tr></table></center>";
		echo "</form>";
?>