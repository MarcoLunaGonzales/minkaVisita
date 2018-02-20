<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql_upd=mysql_query("update distritos set descripcion='$distrito' where cod_ciudad='$cod_territorio' and cod_dist='$cod_distrito'");
if($sql_upd==1)
{
 	echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_distritos.php?cod_territorio=$cod_territorio';
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