<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql_upd=mysql_query("update cargos set cargo='$cargo' where cod_cargo='$codigo'");
echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_cargos.php';
			</script>";
?>