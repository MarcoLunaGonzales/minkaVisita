<?php
echo "<script language='JavaScript'>
		function envia_select(form){
			form.submit();
			return(true);
		}
		function envia_formulario(f)
		{	var visitador,rutero_maestro, formato;
			visitador=f.visitador.value;
			var tipoRuteroRpt=f.tipoRuteroRpt.value;
			var gestionCicloRpt=f.gestionCicloRpt.value;
			formato=f.formato.value;
			var codEspecialidad=new Array();
			var j=0;
			for(var i=0;i<=f.cod_especialidad.options.length-1;i++)
			{	if(f.cod_especialidad.options[i].selected)
				{	codEspecialidad[j]=f.cod_especialidad.options[i].value;
					codEspecialidad[j]='´'+codEspecialidad[j]+'´';
					j++;
				}
			}
			window.open('rpt_regional_medicos_rutero_maestro.php?visitador='+visitador+'&tipoRuteroRpt='+tipoRuteroRpt+'&gestionCicloRpt='+gestionCicloRpt+'&codEspecialidad='+codEspecialidad+'&formato='+formato+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
			return(true);
		}
		</script>";
require("conexion.inc");
require("estilos_regional_pri.inc");
require('espacios_regional.inc');
echo "<center><table class='textotit'><tr><th>Reporte Medicos en Rutero Maestro</th></tr></table><br>";
echo"<form method='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='40%'>\n";
	echo "<tr><th align='left'>Visitador</th><td><select name='visitador' class='texto' onChange='envia_select(this.form)'>";
	$sql_visitador="select f.codigo_funcionario, f.paterno, f.materno, f.nombres
	from funcionarios f, cargos c, ciudades ci, funcionarios_lineas fl
	where f.cod_cargo=c.cod_cargo and f.cod_cargo='1011' and f.estado=1 and f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' and f.cod_ciudad='$global_agencia' and f.cod_ciudad=ci.cod_ciudad order by ci.descripcion,f.paterno,c.cargo";
	$resp_visitador=mysql_query($sql_visitador);
	echo "<option value=''></option>";
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	$codigo_visitador=$dat_visitador[0];
		$nombre_visitador="$dat_visitador[1] $dat_visitador[2] $dat_visitador[3]";
		if($visitador==$codigo_visitador)
		{	echo "<option value='$codigo_visitador' selected>$nombre_visitador</option>";
		}
		else
		{
			echo "<option value='$codigo_visitador'>$nombre_visitador</option>";
		}
	}
	echo "</select>";
	echo "</td></tr>";	
	echo "<tr><th align='left'>Ver:</th><td>
	<select name='tipoRuteroRpt' class='texto'>
		<option value='0'>Rutero Maestro</option>
		<option value='1'>Rutero Maestro Aprobado</option>
	</select></td></tr>";
	
	echo "<tr><th align='left'>Gestion - Ciclo</th><td>
	<select name='gestionCicloRpt' class='texto'>";
	$sql="select distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g
				where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 0,15";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{
		$codCiclo=$dat[0];
		$codGestion=$dat[1];
		$nombreGestion=$dat[2];
		echo "<option value='$codCiclo|$codGestion|$nombreGestion'>$codCiclo $nombreGestion</option>";
	}
	echo "</select>";
	echo "</td></tr>";
	echo "<tr><th align='left'>Formato</th><td><select name='formato' class='texto'>";
	echo "<option value='0'>Resumido</option>";
	echo "<option value='1'>Detallado</option>";
	echo "</select>";
	echo "</td></tr>";
	echo "<tr><th align='left'>Especialidad: </th><td><select name='cod_especialidad' size='10' class='texto' multiple>";
	$sql_espe="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
		$resp_espe=mysql_query($sql_espe);
		while($dat_espe=mysql_fetch_array($resp_espe))
	  	{
		 	$cod_espe=$dat_espe[0];
		 	$desc_espe=$dat_espe[1];
			echo "<option value='$cod_espe'>$desc_espe</option>";
		}
	echo "</select>";
	echo "</td></tr>";
	echo"\n </table><br>";
	require('home_regional1.inc');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
?>