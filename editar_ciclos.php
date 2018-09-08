<?php

echo "<script language='JavaScript'>
	function validar(f)
	{	if(f.exafinicial.value=='')
		{	alert('El campo Fecha Inicio no puede estar vacio.');
			return(false);
		}
		if(f.exafvalidez.value=='')
		{	alert('El campo Fecha Final de Ciclo no puede estar vacio.');
			return(false);
		}
		if(f.exafinicial.value>f.exafvalidez.value)
		{	alert('La Fecha de Fin de Ciclo no puede ser Anterior o Igual a la de Inicio de Ciclo.');
			return(false);
		}
		f.submit();
	}
	</script>";	

	require("conexion.inc");
	require("estilos_gerencia.inc");
	
	list($codCiclo, $codGestion)=explode("|",$_GET["cod_ciclo"]);
	
	echo "<h1>Editar Ciclo $codCiclo</h1>";

	$sql=mysql_query("select cod_ciclo, codigo_gestion, fecha_ini, fecha_fin from ciclos where cod_ciclo='$codCiclo' and codigo_gestion='$codGestion'");
	$dat=mysql_fetch_array($sql);
	
	$fecha_ini=$dat[2];
	$fecha_fin=$dat[3];
	
	echo "<form method='post' action='guarda_modificacion.php'>";
	echo "<input type='hidden' name='cod_ciclo' value='$codCiclo'>";
	echo "<input type='hidden' name='cod_gestion' value='$codGestion'>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>Fecha Inicio</th><th>Fecha Fin</th></tr>";
	echo "<tr>";
	echo "<td><input type='date' class='texto' id='exafinicial' value='$fecha_ini' size='10' name='exafinicial'></td>";
	echo "<td><input type='date' class='texto' value='$fecha_fin' id='exafvalidez' size='10' name='exafvalidez'>";
    echo "</td>
	</tr>";
	echo "</table></center><br>";

	echo "<div class='divBotones'>
	<input type='button' class='boton' value='Modificar' onClick='validar(this.form)'>
	<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_activar_ciclos.php\"'>
	</div>";
	echo "</form>";	
?>