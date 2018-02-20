<?php
require("conexion.inc");
require("estilos.inc");
$sql_update=mysql_query("update ciclos set estado='Cerrado' where cod_ciclo='$cod_ciclo' and codigo_linea=$global_linea");
//prueba
echo "<script language='Javascript'>
				alert('El ciclo $cod_ciclo quedo Cerrado.');
				location.href='navegador_ciclos.php';
			</script>
		";

?>