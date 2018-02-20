<?php
require("conexion.inc");
require("estilos_regional_pri.inc");
$sql_pre="update rutero_maestro_cab set estado_aprobado='0' where cod_visitador='$visitador' and codigo_linea='$global_linea'";
$resp_pre=mysql_query($sql_pre);
$sql_aprueba="update rutero_maestro_cab set estado_aprobado='0' where cod_visitador='$visitador' and cod_rutero='$cod_rutero'";
$resp_aprueba=mysql_query($sql_aprueba);
			echo "<script language='Javascript'>
			alert('El rutero medico se rechazo.');
			location.href='rutero_funcionario.php?visitador=$visitador';
			</script>";
?>