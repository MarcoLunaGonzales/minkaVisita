<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
	echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var parametro;
			parametro=f.grilla.value;
			window.open('rpt_grilla_regional_detalle.php?codigo_grilla='+parametro+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');			
			return(true);
		}
		function envia_formulario_xls(f)
		{	var parametro;
			parametro=f.grilla.value;
			window.open('rpt_grilla_regional_detalle_xls.php?codigo_grilla='+parametro+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');			
			return(true);
		}
		function envia_formulario_pdf(f)
		{	var parametro;
			parametro=f.grilla.value;
			window.open('rpt_grilla_regional_detalle_pdf.php?codigo_grilla='+parametro+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		</script>";
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	echo "<form>";
	echo "<table border='0' class='textotit' align='center'>";
	echo "<tr><th>Reporte Grillas Establecidas para el Territorio</th></tr></table><br>";
	echo "<center><table border='1' cellspacing='0' class='texto' width='30%'>";
	echo "<tr><th>Nombre Grilla</th><th><select name='grilla' class='texto'>";
	$sql="select codigo_grilla, nombre_grilla, fecha_creacion, fecha_modificacion,estado from grilla where agencia='$global_agencia'";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		$fecha_creacion=$dat[2];
		$fecha_modi=$dat[3];
		$estado=$dat[4];
		if($estado==0)
		{	$desc_estado="No Vigente";	}
		else
		{	$desc_estado="Vigente";	}	
		echo "<option value='$codigo'>$nombre ($desc_estado)</option>";
	}
	echo "</select></th></tr>";
	echo "</table><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo "</form>";
?>