<?php
echo '<table>
<tr>
<TD><INPUT id=fecha1 size=10 name=fecha1></TD>
<TD><IMG id=imagenFecha src="imagenes/fecha.bmp"></TD>
<TD>
<DLCALENDAR tool_tip="Seleccione la Fecha" 
daybar_style="background-color: DBE1E7; font-family: verdana; color:000000;" 
navbar_style="background-color: 7992B7; color:ffffff;" 
input_element_id="fecha1" 
click_element_id="imagenFecha"></DLCALENDAR>
</TD>
<!-- FIN fecha con javascript -->
</tr>
</table>';
echo '<script type="text/javascript" language="javascript"  src="dlcalendar.js"></script>';
?>