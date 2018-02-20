<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql_upd=mysql_query("update ciudades set descripcion='$territorio' where cod_ciudad='$cod_territorio'");
if($sql_upd==1)
{
 	echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_territorios.php';
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