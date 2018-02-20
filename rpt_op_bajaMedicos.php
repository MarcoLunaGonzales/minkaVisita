<script language='JavaScript'>
function nuevoAjax() {	
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
		try {
			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
function envia_formulario(f) {	
	var j = 0;
	var rpt_inicio = f.exafinicial.value;
	var rpt_final  = f.exafvalidez.value;
	var tipo_baja  = f.tipo_baja.value;
	
	var rpt_territorio  = new Array();
	var rpt_territorio1 = new Array();
	for(var i=0; i<=f.rpt_territorio.options.length-1; i++) {	
		if(f.rpt_territorio.options[i].selected) {	
			rpt_territorio[j]  = f.rpt_territorio.options[i].value;
			rpt_territorio1[j] = f.rpt_territorio.options[i].innerHTML;
			j++;
		}
	}
	window.open('rpt_bajaMedicos.php?rpt_territorio='+rpt_territorio+'&rpt_nombreTerritorio='+rpt_territorio1+'&rpt_inicio='+rpt_inicio+'&rpt_final='+rpt_final+'&tipo='+tipo_baja+'','','scrollbars=yes,status=yes,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');
	return(true);
}
</script>


<?php

require("conexion.inc");
require("estilos_gerencia.inc");

echo "<form action='' method='get'>";
echo "<table class='textotit' align='center'><tr><th>Baja de Medicos</th></tr></table><br>";
echo "<table class='texto' border='1' cellspacing='0' align='center'>";
echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' size='12' multiple>";
$sql="SELECT c.cod_ciudad, c.descripcion from ciudades c, funcionarios_agencias f where f.cod_ciudad=c.cod_ciudad and f.codigo_funcionario=$global_usuario order by c.descripcion";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)) {	
	$codigo_ciudad=$dat[0];
	$nombre_ciudad=$dat[1];
	echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
}
echo "</select></td></tr>";

echo "<tr><th align='left'>Fecha Inicio</th>";
	//<!-- INI fecha con javascript -->
echo" <TD bgcolor='#ffffff'><INPUT  type='text' class='texto' id='exafinicial' size='10' name='exafinicial'>";
echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
echo" input_element_id='exafinicial' ";
echo" click_element_id='imagenFecha'></DLCALENDAR>";
echo"  </TD>";
  //<!-- FIN fecha con javascript -->
echo"</tr>";
echo"<tr><th align='left'>Fecha Final</th>";
	//<!-- INI fecha con javascript -->
echo" <TD bgcolor='#ffffff'><INPUT type='text'class='texto' id='exafvalidez' size='10' name='exafvalidez'>";
echo" <IMG id='imagenFecha1' src='imagenes/fecha.bmp'>";
echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
echo"  input_element_id='exafvalidez' ";
echo" click_element_id='imagenFecha1'></DLCALENDAR>";
echo"  </TD></tr>";
  //<!-- FIN fecha con javascript -->

echo "<tr>";
echo "<th align='left'>Estado Baja</th>";
echo "<td>";
echo "<select name='tipo_baja' id='tipo_baja'>";
echo "<option value='0'>Solicitado</option>";
echo "<option value='1'>Aprobado Supervisor</option>";
echo "<option value='2'>Aprobado Dr. Pacheco</option>";
echo "</select>";
echo "</td>";
echo "</tr>";
echo "</table><br>";

echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'> </center><br>";
echo "</form>";
echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";
?>