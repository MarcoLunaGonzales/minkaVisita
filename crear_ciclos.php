<?php
require("estilos_gerencia.inc");
require("conexion.inc");

echo "<script language='JavaScript'>
	function validar(f)
	{	if(f.exafinicial.value=='')
		{	alert('El campo Fecha Inicio esta vacio.');
			return(false);
		}
		if(f.exafvalidez.value=='')
		{	alert('El campo Fecha Final esta vacio.');
			return(false);
		}

		//alert(f.exafinicial.value+' '+f.exafvalidez.value);
		
		if(f.exafinicial.value>f.exafvalidez.value)
		{	alert('La Fecha Final de Ciclo no puede ser Anterior o Igual a la de Inicio de Ciclo.');
			return(false);
		}
		f.submit();
	}
	</script>";	
	
			$sql_gestion=mysql_query("select codigo_gestion, nombre_gestion from gestiones where estado='Activo'");
			$dat_gestion=mysql_fetch_array($sql_gestion);
			$codigoGestion=$dat_gestion[0];
			$nombreGestion=$dat_gestion[1];
	
			echo "<h1>Registro de Ciclo</h1>";
			
			echo "<form method='post' action='guarda_ciclo.php'>";
			echo"<center><table class='texto'>";
			echo "<tr>
			<td>Ciclo</td>";
			//aqui formamos los ciclos de manera consecutiva para mandarlos a guardar
				$sql_codigo="select max(cod_ciclo)+1 from ciclos where
				codigo_gestion='$codigoGestion'";
				
				//echo $sql_codigo;
				
				$resp_codigo=mysql_query($sql_codigo);
				$dat_codigo=mysql_fetch_array($resp_codigo);
				$codigo_ciclo=$dat_codigo[0];
				
			echo "<input type='hidden' name='cod_ciclo' value='$codigo_ciclo'>";
			echo "<td>$codigo_ciclo</td>";			
			echo "</tr>";
			echo "<tr><td>Fecha Inicio</td>";
			//<!-- INI fecha con javascript -->
    		echo" <td><input type='date' class='texto' id='exafinicial' name='exafinicial'>";
    		echo"  </td>";
			echo"</tr>";
			echo"<tr><td>Fecha Final</td>";
   			echo"<td><input type='date' class='texto' id='exafvalidez' name='exafvalidez'>";
    		echo"</td>";
	echo"</tr></table></center><br>";

	echo"<div class='divBotones'>
<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_activar_ciclos.php\"'>";
echo "</div>";

echo"</form>";

?>