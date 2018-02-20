<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var parametro;
			parametro=f.parametro.value;
			window.open('rpt_parrilla_productos.php?cod_especialidad='+parametro+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');			
			return(true);
		}
		function envia_formulario_pdf(f)
		{	var parametro;
			parametro=f.parametro.value;
			window.open('rpt_parrilla_productos_pdf.php?cod_especialidad='+parametro+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');			
			return(true);
		}
		</script>";
require("conexion.inc");
require("estilos_visitador.inc");
require('espacios_visitador.inc');
echo "<form action='rpt_parrilla_regional.php' method='get'>";
	echo "<center><table class='textotit'><tr><th>Reporte Parrillas de Productos</th></tr></table><br>";
	echo "<center><table class='texto' border='1' cellspacing='0'>";
	echo "<tr><th>Especialidad </th><td align='center'><select name='parametro' class='texto'>";
		$sql_espe="select DISTINCT(rd.cod_especialidad), e.desc_especialidad from rutero_detalle rd, rutero r, especialidades e
					where r.cod_contacto=rd.cod_contacto and r.cod_ciclo='$ciclo_global' and r.cod_visitador='$global_visitador'
     					and r.codigo_gestion='$codigo_gestion' and rd.cod_especialidad=e.cod_especialidad order by e.desc_especialidad";
+		$resp_espe=mysql_query($sql_espe);
	  	echo "<option value=''>Todas las Especialidades</option>";
		while($dat_espe=mysql_fetch_array($resp_espe))
	  	{
		 	$cod_espe=$dat_espe[0];
		 	$desc_espe=$dat_espe[1];
			echo "<option value='$cod_espe'>$desc_espe</option>";   
		}
		echo "</select>";
	echo "</td></tr>";
	echo "</table><br>";
	echo"\n<table align='center'><tr><td><a href='principal_visitador.php'><img  border='0' src='imagenes/home.gif'>Principal</a></td></tr></table>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte Excel' onClick='envia_formulario_pdf(this.form)' class='boton'></center>";
	echo "</form>";	
?>