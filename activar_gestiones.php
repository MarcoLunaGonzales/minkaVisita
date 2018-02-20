<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql="update gestiones set estado='Inactivo' where estado='Activo'";
$resp=mysql_query($sql);
$sql="update gestiones set estado='Activo' where codigo_gestion=$cod_gestion";
$resp=mysql_query($sql);
echo "<script language='Javascript'>
				alert('La Gestión se activo satisfactoriamente.');
				location.href='navegador_gestiones.php';
	</script>";
?>