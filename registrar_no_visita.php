<?php
require("conexion.inc");
require('estilos_visitador_sincab.inc');
$vector=explode("-",$cod_contacto);
$contacto=$vector[0];
$orden_visita=$vector[1];
$visitador=$vector[2];
//formamos los encabezados nombre medico, especialidad turno
$sql="SELECT c.turno, m.ap_pat_med, m.ap_mat_med, m.nom_med, dm.direccion, cd.categoria_med, cd.cod_especialidad, cd.orden_visita, c.cod_contacto, cd.estado, m.cod_med from rutero c, rutero_detalle cd, medicos m, direcciones_medicos dm where c.cod_ciclo='$ciclo_global' and c.cod_visitador=$global_visitador and m.cod_med=cd.cod_med and cd.orden_visita=$orden_visita and dm.numero_direccion=cd.cod_zona and cd.cod_med=dm.cod_med and c.cod_contacto='$contacto' and c.cod_contacto=cd.cod_contacto order by c.turno,cd.orden_visita";
$resp=mysql_query($sql);
$dat_enc=mysql_fetch_array($resp);
$enc_nombre_medico="$dat_enc[1] $dat_enc[2] $dat_enc[3]";
$enc_turno=$dat_enc[0];
$enc_categoria=$dat_enc[5];
$enc_especialidad=$dat_enc[6];
$cod_medico=$dat_enc[10];
echo "<form method='post' action='guardar_no_visita.php'>";
echo "<center><table border='0' class='textotit'><tr><td align='center'>Registro de Visita<br>M&eacute;dico: <strong>$enc_nombre_medico</strong> Especialidad: <strong>$enc_especialidad</strong> Categor&iacute;a: <strong>$enc_categoria</strong></td></tr></table></center><br>";
echo "<table class='texto' align='center' border='1'><tr><th>Motivo de la no Visita</th>";	
$sql_motivosnovisita="SELECT * from motivos_baja where tipo_motivo = 3 order by 2";
$resp_motivosnovisita=mysql_query($sql_motivosnovisita);		
echo "<td align='center'><select name='motivo_novisita' class='texto'>";
while($dat_novisita=mysql_fetch_array($resp_motivosnovisita)) {	
	$cod_motivo=$dat_novisita[0];
	$descripcion_motivo=$dat_novisita[2];
	echo "<option value='$cod_motivo'>$descripcion_motivo</option>";
}
echo "</select></td>";
echo "</tr></table><br>";
echo "<input type='hidden' name='cod_contacto' value='$cod_contacto'>";
echo "<input type='hidden' name='dia_registro' value='$dia_registro'>";
echo "<center><input type='submit' value='Guardar' class='boton'></center>";
echo "</form>";
/*
*/
?>