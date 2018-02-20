<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var frecuencia,maxima_frecuencia;
			frecuencia=f.frecuencia.value;
			maxima_frecuencia=f.maxima_frecuencia.value;
			window.open('rpt_regional_frecuencia_visita.php?frecuencia_visita='+frecuencia+'&maxima_frecuencia='+maxima_frecuencia+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_xls(f)
		{	var frecuencia,maxima_frecuencia;
			frecuencia=f.frecuencia.value;
			maxima_frecuencia=f.maxima_frecuencia.value;
			window.open('rpt_regional_frecuencia_visita_xls.php?frecuencia_visita='+frecuencia+'&maxima_frecuencia='+maxima_frecuencia+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_pdf(f)
		{	var frecuencia,maxima_frecuencia;
			frecuencia=f.frecuencia.value;
			maxima_frecuencia=f.maxima_frecuencia.value;
			window.open('rpt_regional_frecuencia_visita_pdf.php?frecuencia_visita='+frecuencia+'&maxima_frecuencia='+maxima_frecuencia+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		</script>";
require("conexion.inc");
require("estilos_regional_pri.inc");
require("espacios_regional.inc");
echo "<center><table class='textotit'><tr><th>Reporte Frecuencias de Visita</th></tr></table><br>";
echo"<form metodh='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0'>\n";
	echo"\n <tr>";
	$sql_medicos_asignados="select m.cod_med from medicos m, categorias_lineas c where m.cod_med=c.cod_med and m.cod_ciudad='$global_agencia' and c.codigo_linea='$global_linea'";
	$resp_medicos_asignados=mysql_query($sql_medicos_asignados);
	$maxima_frecuencia=0;
	while($dat_medicos_asignados=mysql_fetch_array($resp_medicos_asignados))
	{	$codigo_medico=$dat_medicos_asignados[0];
		$sql_num_frecuencia="select COUNT(rd.cod_med) from rutero r, rutero_detalle rd 
		where r.cod_contacto=rd.cod_contacto and r.cod_ciclo='$ciclo_global' and r.codigo_gestion='$codigo_gestion' and r.codigo_linea='$global_linea' and rd.cod_med='$codigo_medico'";
		$resp_num_frecuencia=mysql_query($sql_num_frecuencia); 
		$dat_frecuencia=mysql_fetch_array($resp_num_frecuencia);
		$frecuencia_medico=$dat_frecuencia[0];
		if($frecuencia_medico>$maxima_frecuencia)
		{	$maxima_frecuencia=$frecuencia_medico;
		}
	}
	echo "<input type='hidden' name='maxima_frecuencia' value='$maxima_frecuencia'>";
	echo"<th>Frecuencia</th><td><select name='frecuencia' class='texto'>";
	echo"<option value=''>Todas las frecuencias</option>";		
	for($ii=$maxima_frecuencia;$ii>=1;$ii--)
	{	echo"<option value='$ii'>Frecuencia $ii</option>";		
	}
	echo"</select></td>";
	echo"</tr>";
	echo"</table><br>";
	require("home_regional1.inc");
	//echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo"</form>";
?>