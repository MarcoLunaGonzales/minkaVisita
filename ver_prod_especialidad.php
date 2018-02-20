<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_prod_espe.php?cod_espe=$cod_espe';
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
			{	alert('Debe seleccionar al menos un Producto para eliminarlo de la Especialidad.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_prod_espe.php?datos='+datos+'&cod_espe=$cod_espe';
				}
				else
				{
					return(false);
				}
			}
		}
		</script>";
	require("conexion.inc");
	require("estilos.inc");
	echo "<form>";
	//formamos la cabecera
	$sql_cab="select desc_especialidad from especialidades where cod_especialidad='$cod_espe'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_espe=$dat_cab[0];
	//fin formar cabecera
	$sql="select m.codigo,m.descripcion,m.presentacion
	from producto_especialidad p, muestras_medicas m
	where m.codigo=p.codigo_mm and p.cod_especialidad='$cod_espe' and p.codigo_linea='$global_linea' order by m.descripcion";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td align='center'>Registro de Productos por Especialidad<br>Especialidad: <strong>$nombre_espe</strong></td></tr></table></center><br>";
	$indice_tabla=1;
	echo "<center><table border='1' class='texto' cellspacing='0' width='60%'>";
	echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Producto</th><th>Presentación</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo_mm=$dat[0];
		$mm=$dat[1];
		$pres=$dat[2];
		echo "<tr><td align='center'>$indice_tabla</td><td align='center'><input type='checkbox' name='codigo' value='$codigo_mm'></td><td>$mm</td><td>$pres</td></tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	echo"\n<table align='center'><tr><td><a href='navegador_prod_especialidad.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td></tr></table></center>";
	echo "</form>";
?>