<?php
require("estilos.inc");
echo "<form action='prueba.php'>";
//require("calendario.inc");
echo "<table class='texto'>
	<tr>
	<!-- INI fecha con javascript -->
                <TD>Seleccione la Fecha: <INPUT id=input_FechaConsulta size=10 class='texto' name=input_FechaConsulta></TD>
                <TD><IMG id=imagenFecha src='imagenes/fecha.bmp'></TD>
                <TD>
                <DLCALENDAR tool_tip='Seleccione la Fecha' 
                daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' 
                navbar_style='background-color: 7992B7; color:ffffff;' 
                input_element_id='input_FechaConsulta' 
                click_element_id='imagenFecha'></DLCALENDAR>
                </TD></TR></table>";
echo "<input type='submit' value='Enviar'>";
echo '<script type="text/javascript" language="javascript"  src="dlcalendar.js"></script>';
echo "</form>";
?>