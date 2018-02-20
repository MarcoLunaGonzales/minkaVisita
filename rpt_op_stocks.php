<?php
echo "<script language='JavaScript'>
		function envia_formulario(f) {	
			var rpt_territorio, rpt_almacen, tipo_item, rpt_ver, rpt_fecha;
			rpt_territorio=f.rpt_territorio.value;
			rpt_almacen=f.rpt_almacen.value;
			tipo_item=f.tipo_item.value;
			var rpt_linea=new Array();
			rpt_ver=f.rpt_ver.value;
			rpt_fecha=f.rpt_fecha.value;
			var j=0;
			j=0;
			for(var i=0;i<=f.rpt_linea.options.length-1;i++) {	
				if(f.rpt_linea.options[i].selected) {	
					rpt_linea[j]=f.rpt_linea.options[i].value;
					j++;
				}
			}
			window.open('rpt_stocks.php?rpt_territorio='+rpt_territorio+'&rpt_almacen='+rpt_almacen+'&rpt_linea='+rpt_linea+'&tipo_item='+tipo_item+'&rpt_ver='+rpt_ver+'&rpt_fecha='+rpt_fecha+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
			return(true);
		}
		function envia_select(form){
			form.submit();
			return(true);
		}
		</script>";
require("conexion.inc");
if($global_tipoalmacen==1) {	
	require("estilos_almacenes_central.inc");
} else {	
	require("estilos_almacenes.inc");
}
$fecha_rptdefault=date("d/m/Y");
echo "<table align='center' class='textotit'><tr><th>Reporte de Stocks</th></tr></table><br>";
echo"<form method='post' action=''>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
	if($global_tipoalmacen==1) {	
		$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	} else {	
		$sql="select cod_ciudad, descripcion from ciudades where cod_ciudad='$global_agencia' order by descripcion";
	}
	$resp=mysql_query($sql);
	echo "<option value='0'>Todos</option>";
	while($dat=mysql_fetch_array($resp)) {	
		$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($rpt_territorio==$codigo_ciudad) {	
			echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
		} else {	
			echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
		}
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Almacen</th><td><select name='rpt_almacen' class='texto'>";
	$sql="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$rpt_territorio'";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp)) {	
		$codigo_almacen=$dat[0];
		$nombre_almacen=$dat[1];
		if($rpt_almacen==$codigo_almacen) {	
			echo "<option value='$codigo_almacen' selected>$nombre_almacen</option>";
		} else {	
			echo "<option value='$codigo_almacen'>$nombre_almacen</option>";
		}
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Tipo de Item:</th>";
	echo "<td><select name='tipo_item' class='texto' OnChange='activa_tipomaterial(this.form)'>";
	echo "<option value='1'>Muestra Médica</option>";
	echo "</select></td>";
	echo "</tr>";

	echo "<tr><th align='left'>Línea</th><td><select name='rpt_linea' class='texto' multiple size='9'>";
	$sql="select codigo_linea, nombre_linea from lineas where linea_inventarios=1 order by nombre_linea";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_linea=$dat[0];
		$nombre_linea=$dat[1];
		if($rpt_linea==$codigo_linea)
		{	echo "<option value='$codigo_linea' selected>$nombre_linea</option>";
		}
		else
		{	echo "<option value='$codigo_linea'>$nombre_linea</option>";
		}
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Ver:</th>";
	echo "<td><select name='rpt_ver' class='texto'>";
	echo "<option value='1'>Todo</option>";
	echo "<option value='2'>Con Existencia</option>";
	echo "<option value='3'>Sin existencia</option>";
	echo "</select></td>";
	echo "</tr>";
	$fecha_rptdefault=date("d/m/Y");
	echo "<tr><th align='left'>Existencias a fecha:</th>";
			echo" <TD bgcolor='#ffffff'><INPUT  type='text' class='texto' value='$fecha_rptdefault' id='rpt_fecha' size='10' name='rpt_fecha'>";
    		echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
    		echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
    		echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
    		echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
    		echo" input_element_id='rpt_fecha' ";
    		echo" click_element_id='imagenFecha'></DLCALENDAR>";
    		echo"  </TD>";
	echo "</tr>";

	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	//echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo"</form>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>