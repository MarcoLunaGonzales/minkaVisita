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
			{	alert('Debe seleccionar al menos un Producto para añadirlo a la Especialidad.');
				return(false);
			}
			else
			{	 location.href='guardar_prod_espe.php?datos='+datos+'&cod_espe=$cod_espe';
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
	//formamos la cabecera
	$sql_cab="select desc_especialidad from especialidades where cod_especialidad='$cod_espe'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_espe=$dat_cab[0];
	//fin formar cabecera
	echo "<center><table border='0' class='textotit'><tr><td align='center'>Registro de Productos por Especialidad<br>Especialidad: <strong>$nombre_espe</strong><br>Adicionar Productos a la Especialidad</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0'>";
	echo "<form method='post' action=''>";
	echo "<center><table border='0' class='texto'>";
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1);'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
	echo "<tr><td><input type='button' value='Añadir a Especialidad' class='boton' onclick='anadir_categoria(this.form)'></td></tr></table></center>";
	$sql="select codigo,descripcion,presentacion from muestras_medicas order by descripcion";
	$resp=mysql_query($sql);
	echo "<br><table class='texto' border=1 cellspacing='0'>";
	echo "<tr><td><input type='checkbox' name='todo' onClick='sel_todo(this.form)'>Seleccionar Todo</td></tr></table>";
	echo "<br><center><table border='1' cellspacing='0' class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Producto</th><th>Presentación</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{	$codigo_mm=$dat[0];
		$prod=$dat[1];
		$pres=$dat[2];
		$ban=0;
		$sql_filtro="select codigo_mm from producto_especialidad where cod_especialidad='$cod_espe' and codigo_linea='$global_linea'";
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
		echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1);'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
		echo "<center><table border='0' class='texto'>";
		echo "<tr><td><input type='button' value='Añadir a Especialidad' class='boton' onclick='anadir_categoria(this.form)'></td></tr></table></center>";
		echo "</form>";
?>