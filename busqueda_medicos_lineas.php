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
	require("estilos_gerencia.inc");
	echo "<form method='post' action='anadir_medico_linea_busqueda.php'>";
	echo "<center><table border='0' class='textotit'><tr><th>Añadir Medicos a la Línea<br>Búsquedas</th></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0'>";
	
	
	echo "<tr><td>Buscar por:</td>";
	echo "<td><select name='campo' class='texto'>
			<option value='cod_med'>Codigo</option>
			<option value='ap_pat_med'>Apellido Paterno</option>
			<option value='ap_mat_med'>Apellido Materno</option>
			<option value='especialidad'>Especialidad</option>
			</select></td></tr>";
	echo "<tr><td>Parametro de Búsqueda: </td>";
	echo "<td><input type='text' class='texto' name='parametro'></td></tr>";
	echo "</table><br>";
	require("home_regional1.inc");
	echo "<center><input type='button' onClick='validar(this.form)' value='Buscar' class='boton'></center>";
	echo "</form>";
	echo "<table border=0 class='texto'><tr><th>En caso de realizar una busqueda por especialidad
	ingrese como parametro la abreviatura de tal especialidad.</th></tr></table>";
?>