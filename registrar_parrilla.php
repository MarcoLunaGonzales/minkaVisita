<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	
	echo "<script language='Javascript'>
			function abre_nuevo(f,i)
			{
				var url;
				url=f.elements[i-1].value;
				//alert(f.elements[i-1].value);
				window.open(url,'_blank',toolbar=0);
			}
			
			</script>";

	$contador=1;
	//se requiere el cookie del visitador
	//$global_visitador=14059;
	require("estilos.inc");
	require("conexion.inc");
	$fecha_sistema=date("Y-m-d");
		$sql_aux=mysql_query("select cod_ciclo from ciclos where estado='activo'");
		$dat_aux=mysql_fetch_array($sql_aux);
		$ciclo_activo=$dat_aux[0];
	
	$sql="select * from contactos where fecha='$fecha_sistema' and cod_visitador='$global_visitador' and ciclo='$ciclo_activo'";
	$resp=mysql_query($sql);
	//esta parte es la cabecera del modulo
	echo "<center><table border='0' class='textotit'><tr><td>Registro de Visita Médica</td></tr></table><br>";
	
	echo "<table border='1' class='texto'>";
	echo "<tr><td><center>Turno</td><td><center>Medico</center></td><td><center>Especialidad</center></td><td><center>Categoria</center></td><td><center>Direccion</center></td><td><center>Registrar</center></td></tr>";
	echo "<form name='formu' action='' method='post'>";
	while($dat=mysql_fetch_array($resp))
	{   $p_ciclo=$dat[1]; 
		$p_turno=$dat[3];
		$p_medico=$dat[6];
		//consulta extra para sacar el nombre del medico
			$sql_aux="select * from medicos where cod_med='$p_medico'";
			$resp_aux=mysql_query($sql_aux);
			$dat_aux=mysql_fetch_array($resp_aux);
			$nombre_medico="$dat_aux[3] $dat_aux[1] $dat_aux[2]";
		
		$p_especialidad=$dat[7];
		//consulta auxiliar para sacar la especialidad a partir de su codigo
			$sql_extra="select * from especialidades where cod_especialidad='$p_especialidad'";
			$resp_extra=mysql_query($sql_extra);
			$dat_extra=mysql_fetch_array($resp_extra);
			$desc_especialidad=$dat_extra[1];			
		$p_categoria=$dat[8];
		$p_direccion=$dat[9];
		//consulta para sacar la direccion del medico
			$sql_extra2="select direccion from direcciones_medicos where cod_med='$p_medico' and cod_zona='$p_direccion'";
			$resp_extra2=mysql_query($sql_extra2);
			$dat_extra2=mysql_fetch_array($resp_extra2);
			$direccion=$dat_extra2[0];
				
		$p_direccion_foranea="registrar_visita.php?ciclo=$p_ciclo&especialidad=$p_especialidad&categoria=$p_categoria&medico=$p_medico";
		echo "<input type='hidden' value='$p_direccion_foranea' name='$contador'>";
		echo "<tr><td>$p_turno</td><td>$nombre_medico</td><td>$desc_especialidad</td><td><center>$p_categoria</center></td><td>$direccion</td><td><a href='javascript:abre_nuevo(formu,$contador)'>Registrar</a></td></tr>";
		$contador++;
	}
	echo "</form>";
	echo "</table></center>";
?>