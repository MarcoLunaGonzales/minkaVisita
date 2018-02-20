<?php  
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
$ciclos_final   = "5,6,7,8,9,10,11";
$gestion_final = 1010;
$ciclos_finales = explode(",", $ciclos_final);

$max_rutero = mysql_result(mysql_query("SELECT max(cod_rutero) from rutero_maestro_cab_aprobado"), 0, 0);
$max_rutero = $max_rutero + 1;

$max_contacto = mysql_result(mysql_query("SELECT max(cod_contacto) from rutero_maestro_aprobado"), 0, 0);
$max_contacto = $max_contacto + 1;

foreach ($ciclos_finales as $cod_ciclo) {
	$sql_rutpri = mysql_query("SELECT * from rutero_maestro_cab_aprobado where codigo_ciclo = 4 and codigo_gestion = 1010 and estado_aprobado = 1");
	while ($row_1 = mysql_fetch_array($sql_rutpri)) {
		if ($row_1[7]== '') {
			$row_1[7] = '0000-00-00';
		}
		mysql_query("INSERT into rutero_maestro_cab_aprobado values($max_rutero,'$row_1[1]','$row_1[2]','$row_1[3]','$row_1[4]',$cod_ciclo,$gestion_final,'$row_1[7]')");
		echo("INSERT into rutero_maestro_cab_aprobado values($max_rutero,'$row_1[1]','$row_1[2]','$row_1[3]','$row_1[4]',$cod_ciclo,$gestion_final,'$row_1[7]')")."<br />";
		$sql_2 = mysql_query("SELECT * from rutero_maestro_aprobado where cod_rutero = $row_1[0]");
		while ($row_2 = mysql_fetch_array($sql_2)) {
			mysql_query("INSERT into rutero_maestro_aprobado values($max_contacto,$max_rutero,$row_2[2],'$row_2[3]','$row_2[4]','$row_2[5]')");
			echo("INSERT into rutero_maestro_aprobado values($max_contacto,$max_rutero,$row_2[2],'$row_2[3]','$row_2[4]','$row_2[5]')")."<br />";
			$sql_3 = mysql_query("SELECT * from rutero_maestro_detalle_aprobado where cod_contacto = $row_2[0]");
			while ($row_3 = mysql_fetch_array($sql_3)) {
				mysql_query("INSERT into rutero_maestro_detalle_aprobado values($max_contacto,'$row_3[1]','$row_3[2]','$row_3[3]','$row_3[4]','$row_3[5]','$row_3[6]','$row_3[7]')");
				echo("INSERT into rutero_maestro_detalle_aprobado values($max_contacto,'$row_3[1]','$row_3[2]','$row_3[3]','$row_3[4]','$row_3[5]','$row_3[6]','$row_3[7]')")."<br />";
			}
			$max_contacto++;
		}
		$max_rutero++;
	echo "<br /><br /><br />";
	}
}

echo "Ok";
?>	