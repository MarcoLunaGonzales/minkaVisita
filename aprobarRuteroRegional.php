<?php

require("conexion.inc");
require("estilos_visitador.inc");

$datos = $datos;

echo "<center><table border='0' class='textotit'><tr><th>Observaciones en la Aprobacion Final de Ruteros Maestro</th></tr></table></center><br>";

$sql_med_vis = "select rc.`cod_visitador`, concat(m.`ap_pat_med`,' ',m.`nom_med`), 
  		rd.`cod_med`, rd.`cod_especialidad`, rd.`categoria_med`, count(rd.`cod_med`) 
  		from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` r, 
  		`rutero_maestro_detalle_aprobado` rd, medicos m  where  m.`cod_med`=rd.`cod_med` and 
  			rc.`cod_rutero` = r.`cod_rutero` and r.`cod_contacto` = rd.`cod_contacto` and 
  			rc.`cod_rutero` in ($datos) group by rd.cod_med order by 2";

$resp_med_vis = mysql_query($sql_med_vis);
echo "<table border=1 class='texto' width='100%' align='center'>";
echo "<tr><th colspan='6'>$nombre_visitador $nombre_linea</th></tr>";
echo "<tr><th>Medico</th><th>Esp.</th><th>Cat.</th><th>Total Contactos en Ruteros</th><th>Frecuencia Definida</th></tr>";
$bandera = 0;
while ($datos_med_vis = mysql_fetch_array($resp_med_vis)) {
    $nombreMed = $datos_med_vis[1];
    $codMed = $datos_med_vis[2];
    $espe_med = $datos_med_vis[3];
    $cat_med = $datos_med_vis[4];
    $totalContactos = $datos_med_vis[5];

    $sql_frecuencia = "select c.`frecuencia_linea` from `categorias_lineas` c 
		where c.codigo_linea='$global_linea' and c.`cod_med`='$codMed'";
    $resp_frecuencia = mysql_query($sql_frecuencia);
    $frecuenciaLinea = mysql_result($resp_frecuencia, 0, 0);

    if ($frecuenciaLinea != $totalContactos) {
        $colorFondo = "#FF0000";
        $bandera = 1;
    } else {
        $colorFondo = "";
    }
    echo "<tr bgcolor='$colorFondo'><td>$nombreMed</td><td>$espe_med</td><td align='center'>$cat_med</td>
		<td align='center'>$totalContactos</td><td align='center'>$frecuenciaLinea</td></tr>";
}
echo "</table>";

if ($bandera == 0) {
    $sqlUpd = "update rutero_maestro_cab_aprobado set estado = 1 where cod_rutero in ($datos)";
    $respUpd = mysql_query($sqlUpd);
    echo "
	<script language='JavaScript'>
		location.href='navegador_ruterosRegionalCiclos.php';
	</script>
	";
}
?>