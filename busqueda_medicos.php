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
		</script>
	";
	require("conexion.inc");
	require("estilos_administracion.inc");
	echo "<form method='post' action='navegador_medicos2.php'>";
	echo "<center><table border='0' class='textotit'><tr><th>Busqueda de Medicos</th></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0'>";
	echo "<tr><td>Territorio:</td>";
	$sql_territorios="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp_territorios=mysql_query($sql_territorios);
	echo "<td><select name='cod_ciudad' class='texto'>";
	while($dat_territorios=mysql_fetch_array($resp_territorios))
	{	$cod_ciudad=$dat_territorios[0];
		$nombre_ciudad=$dat_territorios[1];
		echo "<option value='$cod_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	echo "<tr><td>Buscar por:</td>";
	echo "<td><select name='tipo_busqueda' class='texto'>
			<option value='2'>Apellido Paterno</option>
			<option value='3'>Apellido Materno</option>
			<option value='4'>Especialidad</option>
			<option value='1'>Codigo</option>
			</select></td></tr>";
	echo "<tr><td>Parametro de Búsqueda: </td>";
	echo "<td><input type='text' class='texto' name='parametro'></td></tr>";
	echo "</table><br>";
	echo "<center><input type='button' onClick='validar(this.form)' value='Buscar' class='boton'></center>";
	echo "</form>";
	echo "<table border=0 class='texto'><tr><th>En caso de realizar una busqueda por especialidad
	ingrese como parametro la abreviatura de tal especialidad.</th></tr></table>";
?>