<?php
require("conexion.inc");
require("estilos_regional_pri.inc");
$ciclo=$_POST['ciclo'];
list($codCiclo,$codGestion)=explode("|",$ciclo);
$sql_upd=mysql_query("update rutero_maestro_cab set nombre_rutero='$nombre_rutero', codigo_ciclo='$codCiclo', codigo_gestion='$codGestion' 
where cod_rutero='$codigo' and cod_visitador='$global_visitador'");
if($sql_upd==1)
{
	echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_rutero_maestro.php';
			</script>";  
}
else
{
	echo "<script language='Javascript'>
			alert('No se pudo modificar los datos porque ese nombre de rutero ya existe.');
			history.back(-1);
			</script>";  
}
?>