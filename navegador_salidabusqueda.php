<?php
	/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
echo "<script language='Javascript'>
		function validar(f)
		{	if(f.parametro.value=='')
			{	alert('No puede realizar una busqueda con el parametro en blanco.');
				return(false);
			}
			f.submit();
			return(true);
		}
		</script>";
	require("conexion.inc");
	if($global_tipoalmacen==1)
	{	require("estilos_almacenes_central.inc");
	}
	else
	{	require("estilos_almacenes.inc");
	}
	if($grupo_salida==1)
	{	echo "<form method='post' action='navegador_salidamuestras.php'>";
		echo "<center><table border='0' class='textotit'><tr><th>Buscar Salida de Muestras</th></tr></table></center><br>";
	}
	else
	{	echo "<form method='post' action='navegador_salidamateriales.php'>";
		echo "<center><table border='0' class='textotit'><tr><th>Buscar Salida de Material de Apoyo</th></tr></table></center><br>";
	}
	echo "<center><table border='1' class='texto' cellspacing='0'>";
	echo "<tr><td>Buscar por:</td>";
	echo "<td><select name='campo_busqueda' class='texto'>
			<option value='nro_salida'>Número de Salida</option>
			<option value='fecha'>Fecha de Salida</option>
			</select></td></tr>";
	echo "<tr><td>Parametro de Búsqueda: </td>";
	echo "<td><input type='text' class='texto' name='parametro'></td></tr>";
	echo "</table><br>";
	require("home_regional1.inc");
	echo "<center><input type='button' onClick='validar(this.form)' value='Buscar' class='boton'></center>";
	echo "</form>";
	echo "<table border=0 class='texto'><tr><th>En caso de realizar una busqueda por fecha
	de ingreso, escriba la fecha en el siguiente formato 'dd/mm/aaaa'.</th></tr></table>";
?>