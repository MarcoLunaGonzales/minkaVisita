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
			{	 location.href='guardar_prod_linea_espe.php?datos='+datos+'&cod_linea_vis=$cod_linea_vis';
		    }
		}
		</script>
	";
	require("conexion.inc");
	require("estilos.inc");
	echo "<form method='post' action=''>";
	//formamos la cabecera
	$sql_cab="select nombre_l_visita from lineas_visita where codigo_l_visita='$cod_linea_vis'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_linea_visita=$dat_cab[0];
	//fin formar cabecera
	echo "<center><table border='0' class='textotit'><tr><td align='center'>Registro de Líneas de Visita<br>Línea de Visita: <strong>$nombre_linea_visita</strong></td></tr></table></center><br>";

	$sql="select cod_especialidad,desc_especialidad from especialidades order by desc_especialidad";
	$resp=mysql_query($sql);
	echo "<center><table border='1' cellspacing='0' class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Producto</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{	$cod_espe=$dat[0];
		$desc_espe=$dat[1];
		$ban=0;
		$sql_filtro="select cod_especialidad from lineas_visita_especialidad where codigo_l_visita='$cod_linea_vis'";
		$resp_filtro=mysql_query($sql_filtro);
		while($dat_filtro=mysql_fetch_array($resp_filtro))
		{	$codigo_filtro=$dat_filtro[0];
			if($codigo_filtro==$cod_espe)
			{	$ban=1;
			}		
		}
		if($ban==0)
		{	echo "<tr><td align='center'><input type='checkbox' name='codigo' value='$cod_espe'></td><td>$desc_espe</td></tr>";
		}		
	}
		echo "</table></center><br>";
		echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1);'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
		echo "<center><table border='0' class='texto'>";
		echo "<tr><td><input type='button' value='Añadir a Línea Visita' class='boton' onclick='anadir_categoria(this.form)'></td></tr></table></center>";
		echo "</form>";
?>