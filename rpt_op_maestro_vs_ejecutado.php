<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var parametro;
			parametro=f.parametro.value;
			window.open('rpt_maestro_vs_ejecutado.php?parametro='+parametro+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_xls(f)
		{	var parametro;
			parametro=f.parametro.value;
			window.open('rpt_maestro_vs_ejecutado_xls.php?parametro='+parametro+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_pdf(f)
		{	var parametro;
			parametro=f.parametro.value;
			window.open('rpt_maestro_vs_ejecutado_pdf.php?parametro='+parametro+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		</script>";
require("conexion.inc");
require("estilos_visitador.inc");
require('espacios_visitador.inc');
echo "<center><table class='textotit'><tr><th>Reporte Rutero Maestro vs. Rutero Ejecutado</th></tr></table><br>";
echo"<form metodh='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='30%'>\n";
	echo"\n <tr>";
	echo"<th>Formato</th><td align='center'><select name='parametro' class='texto'>";
	echo"\n<option value='0'>Resumido</option>";	
	echo"\n<option value='1'>Detallado</option>";	
	echo"\n</select></td>";
	echo"\n </tr>";
	echo"\n </table><br>";
	echo"\n<table align='center'><tr><td><a href='principal_visitador.php'><img  border='0' src='imagenes/home.gif'>Principal</a></td></tr></table>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo"</form>";
?>