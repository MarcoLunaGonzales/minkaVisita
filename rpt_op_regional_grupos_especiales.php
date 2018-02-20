<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var grupo_especial;
			grupo_especial=f.grupo_especial.value;
			window.open('rpt_regional_grupos_especiales.php?grupo_especial='+grupo_especial+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_xls(f)
		{	var grupo_especial;
			grupo_especial=f.grupo_especial.value;
			window.open('rpt_regional_grupos_especiales_xls.php?grupo_especial='+grupo_especial+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_pdf(f)
		{	var grupo_especial;
			grupo_especial=f.grupo_especial.value;
			window.open('rpt_regional_grupos_especiales_pdf.php?grupo_especial='+grupo_especial+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		</script>";
require("conexion.inc");
require("estilos_regional_pri.inc");
require("espacios_regional.inc");
echo "<center><table class='textotit'><tr><th>Reporte Grupos Especiales</th></tr></table><br>";
echo"<form metodh='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0'>\n";
	echo "<tr><th>Grupo Especial</th><td align='center'><select name='grupo_especial' class='texto'>";
	$sql_grupos="select codigo_grupo_especial, nombre_grupo_especial from grupo_especial 
				where agencia='$global_agencia' and codigo_linea='$global_linea' order by nombre_grupo_especial";
	$resp_grupos=mysql_query($sql_grupos);
	echo "<option value=''>Todos</option>";
	while($dat_grupos=mysql_fetch_array($resp_grupos))
	{	$codigo_grupo=$dat_grupos[0];
		$nombre_grupo=$dat_grupos[1];
		echo "<option value='$codigo_grupo'>$nombre_grupo</option>";
	}
	echo "</select>";
	echo "</td></tr>";
	echo"\n </table><br>";
	require("home_regional1.inc");
	//echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo"</form>";
?>