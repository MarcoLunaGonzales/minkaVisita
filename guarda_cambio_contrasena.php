<?php
require("conexion.inc");
require("estilos.inc");
$sql="update usuarios_sistema set contrasena='$clave' where codigo_funcionario='$global_usuario'";
$resp=mysql_query($sql);
echo "<script language='Javascript'>
			alert('Los datos fueron modificados.');
			location.href='principal_administracion.php';
			</script>";
?>