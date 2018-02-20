<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var semana, dia_contacto;
			semana=f.semana.value;
			dia_contacto=f.dia_contacto.value;
			window.open('rpt_productos_entregados.php?semana='+semana+'&dia_contacto='+dia_contacto+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_xls(f)
		{	var semana, dia_contacto;
			semana=f.semana.value;
			dia_contacto=f.dia_contacto.value;
			window.open('rpt_productos_entregados_xls.php?semana='+semana+'&dia_contacto='+dia_contacto+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_pdf(f)
		{	var semana, dia_contacto;
			semana=f.semana.value;
			dia_contacto=f.dia_contacto.value;
			window.open('rpt_productos_entregados_pdf.php?semana='+semana+'&dia_contacto='+dia_contacto+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		</script>";
require("conexion.inc");
require("estilos_visitador.inc");
echo "<br><br><br><br>";
echo "<center><table class='textotit'><tr><th>Reporte Productos Entregados</th></tr></table><br>";
echo"<form method='post'>";

	echo"\n<table class='texto' border='1' align='center' cellSpacing='0'>\n";
	echo"\n <tr>";
	echo"<th align='left'>Semana</th><td><select name='semana' class='texto'>";
	echo"\n<option value=''>Todo el ciclo</option>";		
	echo"\n<option value='1'>Semana 1</option>";	
	echo"\n<option value='2'>Semana 2</option>";	
	echo"\n<option value='3'>Semana 3</option>";	
	echo"\n<option value='4'>Semana 4</option>";	
	echo"\n</select></td>";
	echo"\n </tr>";
	echo"\n <tr>";
	$sql_dias="select id, dia_contacto from orden_dias order by id";
	$resp_dias=mysql_query($sql_dias);
	echo"<th align='left'>Dia de Contacto</th><td><select name='dia_contacto' class='texto'>";
	echo"\n<option value=''>Elija una Opción</option>";
	while($dat_dias=mysql_fetch_array($resp_dias))
	{	
		$dia_contacto=$dat_dias[1];
		echo"\n <option value='$dia_contacto'>$dia_contacto</option>";	
	}
	echo"\n</select></td>";
	echo"\n </tr>";
	echo"\n </table><br>";
	echo"\n<table align='center'><tr><td><a href='principal_visitador.php'><img  border='0' src='imagenes/home.gif' width='15' height='8'>Principal</a></td></tr></table>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	//echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo "<table align='center' class='texto' width='80%'><tr><th>Nota: Este reporte se genera a partir de un campo cualquiera. Si tiene mas de un campo seleccionado se generara el reporte a partir del primer campo empezando desde arriba.</th></tr></table>";
	echo"</form>";
?>