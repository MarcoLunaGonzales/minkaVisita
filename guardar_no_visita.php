<?php
require('estilos_visitador_sincab.inc');
$vector=explode("-",$cod_contacto);
$contacto=$vector[0];
$orden_visita=$vector[1];
$visitador=$vector[2];
$fecha_registro=date("Y-m-d");
$hora_registro=date("H:i:s");
$sql="INSERT into registro_no_visita values('$ciclo_global','$codigo_gestion','$contacto','$orden_visita','$motivo_novisita','$fecha_registro','$hora_registro',3)";
// echo $sql;
$resp=mysql_query($sql);
$sql_upd="UPDATE rutero_maestro_detalle_aprobado set estado = 3 where cod_contacto = $contacto 
	and orden_visita = $orden_visita and cod_visitador='$visitador'";
// echo $sql_upd;
$resp_upd=mysql_query($sql_upd);
echo "<script language='Javascript'>
alert('Los datos se registraron satisfactoriamente');
opener.location.reload();
window.location.href = 'envios_mails/solicitud_baja.php?visitador=$contacto&motivo=$motivo_novisita';
// window.close();
</script>";
?>