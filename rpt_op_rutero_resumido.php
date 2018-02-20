<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var semana;
			semana=f.semana.value;
			window.open('rpt_rutero_resumido.php?semana='+semana+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_xls(f)
		{	var semana;
			semana=f.semana.value;
			window.open('rpt_rutero_resumido_xls.php?semana='+semana+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_pdf(f)
		{	var semana;
			semana=f.semana.value;
			window.open('rpt_rutero_resumido_pdf.php?semana='+semana+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		</script>";
require("conexion.inc");
require("estilos_visitador.inc");
require('espacios_visitador.inc');
echo "<center><table class='textotit'><tr><th>Reporte Rutero Resumido</th></tr></table><br>";
echo"<form metodh='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0'>\n";
	echo"\n <tr>";
	echo"<th>Semana</th><td><select name='semana' class='texto'>";
	echo"\n<option value=''>Todo el rutero</option>";		
	echo"\n<option value='1'>Semana 1</option>";	
	echo"\n<option value='2'>Semana 2</option>";	
	echo"\n<option value='3'>Semana 3</option>";	
	echo"\n<option value='4'>Semana 4</option>";	
	echo"\n</select></td>";
	echo"\n </tr>";
	echo"\n </table><br>";
	echo"\n<table align='center'><tr><td><a href='principal_visitador.php'><img  border='0' src='imagenes/home.gif'>Principal</a></td></tr></table>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	//echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo"</form>";
?>