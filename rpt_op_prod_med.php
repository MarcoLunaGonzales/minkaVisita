<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var medico, obj, filtrado;
			medico=f.codmed.value;
			obj=f.codigo.value;
			filtrado=f.codigomuestra.value;
			window.open('rpt_prod_med.php?medico='+medico+'&obj='+obj+'&filtrado='+filtrado+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_xls(f)
		{	var medico, obj, filtrado;
			medico=f.codmed.value;
			obj=f.codigo.value;
			filtrado=f.codigomuestra.value;
			window.open('rpt_prod_med_visitador_xls.php?medico='+medico+'&obj='+obj+'&filtrado='+filtrado+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_pdf(f)
		{	var medico, obj, filtrado;
			medico=f.codmed.value;
			obj=f.codigo.value;
			filtrado=f.codigomuestra.value;
			window.open('rpt_prod_med_pdf.php?medico='+medico+'&obj='+obj+'&filtrado='+filtrado+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		</script>";
require("conexion.inc");
require("estilos_visitador.inc");
echo"<html>";
echo"<body>";
require('espacios_visitador.inc');
echo "<center><table class='textotit'><tr><th>Reporte Productos Objetivo y Filtrados por Medico</th></tr></table><br>";
echo"<form metodh='post'>";

	echo"\n<table class='texto' border='1' align='center' cellPadding='1' cellSpacing='0' bordercolorlight='#ffffFF' bordercolordark='#003c72' >\n";
	echo"\n <tr>";
	$sql_medicos_visitador="select distinct(m.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med 
		from medicos m,medico_asignado_visitador ma, productos_objetivo p, muestras_negadas n
		where m.cod_med=ma.cod_med and (m.cod_med=p.cod_med or m.cod_med=n.cod_med) and ma.codigo_visitador=$global_visitador order by m.ap_pat_med, ap_mat_med";
	$resp_medicos_visitador=mysql_query($sql_medicos_visitador);
	echo"<th align='left'>Medico</th><td><select name='codmed' class='texto'>";
	echo"\n<option value='' >Elija una Opción</option>";		
	while($dat_medicos_visitador=mysql_fetch_array($resp_medicos_visitador))
	{	
		$cod_med=$dat_medicos_visitador[0];
		$ap_pat_med=$dat_medicos_visitador[1];
		$ap_mat_med=$dat_medicos_visitador[2];
		$nom_med=$dat_medicos_visitador[3];
		echo"\n <option value='$cod_med'>$ap_pat_med $ap_mat_med $nom_med</option>";	
	}
	echo"\n</select></td>";
	echo"\n </tr>";
	echo"\n <tr>";
	$sql_prod_objetivo="select distinct(p.codigo_muestra), mm.descripcion, mm.presentacion
						from productos_objetivo p, medico_asignado_visitador m, muestras_medicas mm
						where p.cod_med=m.cod_med and mm.codigo=p.codigo_muestra and p.codigo_linea='$global_linea' and m.codigo_visitador='$global_visitador'
						order by mm.descripcion, mm.presentacion";
	$resp_prod_objetivo=mysql_query($sql_prod_objetivo);
	echo"<th align='left'>Productos Objetivo</th><td><select name='codigo' class='texto'>";
	echo"\n<option value='' >Elija una Opción</option>";
	while($dat_muestras=mysql_fetch_array($resp_prod_objetivo))
	{	
		$codigo=$dat_muestras[0];
		$descripcion=$dat_muestras[1];
		$presentacion=$dat_muestras[2];
		echo"\n <option value='$codigo'>$descripcion $presentacion</option>";	
	}
	echo"\n</select></td>";
	echo"\n </tr>";
	echo"\n <tr>";
	echo"<th align='left'>Productos Filtrados</th><td><select name='codigomuestra' class='texto'>";
	echo"\n<option value='' >Elija una Opción</option>";
	$sql_muestras_negadas="select distinct(mn.codigo_muestra), mm.descripcion, mm.presentacion
						from muestras_negadas mn, medico_asignado_visitador m, muestras_medicas mm
						where mn.cod_med=m.cod_med and mn.codigo_linea='$global_linea' and mm.codigo=mn.codigo_muestra and m.codigo_visitador='$global_visitador'
						order by mm.descripcion, mm.presentacion";
	$resp_muestras_negadas=mysql_query($sql_muestras_negadas);
	while($dat_muestras_negadas=mysql_fetch_array($resp_muestras_negadas))
	{	
		$codigo_muestra_negada=$dat_muestras_negadas[0];
		$descripcion_muestra_negada=$dat_muestras_negadas[1];
		$presentacion_muestra_negada=$dat_muestras_negadas[2];
		echo"\n <option value='$codigo_muestra_negada'>$descripcion_muestra_negada $presentacion_muestra_negada</option>";	
	}
	echo"\n</select></td>";
	echo"\n </tr>";
	echo"\n </table><br>";
	echo"\n<table align='center'><tr><td><a href='principal_visitador.php'><img  border='0' src='imagenes/home.gif'>Principal</a></td></tr></table>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	//echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo "<table align='center' class='texto' width='80%'><tr><th>Nota: Este reporte se genera a partir de un campo cualquiera. Si tiene mas de un campo seleccionado se generara el reporte a partir del primer campo empezando desde arriba.</th></tr></table>";
	echo"</form>";
	echo"</body>";
	echo"</html>";
?>