<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql_upd=mysql_query("update zonas set zona='$zona' where cod_ciudad='$cod_territorio' and cod_dist='$cod_distrito' and cod_zona='$cod_zona'");
if($sql_upd==1)
{
 	echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_zonas.php?cod_territorio=$cod_territorio&cod_distrito=$cod_distrito';
			</script>"; 
}
else
{
 	echo "<script language='Javascript'>
			alert('No se pudieron modificar los datos.');
			history.back();
			</script>"; 
}
?>