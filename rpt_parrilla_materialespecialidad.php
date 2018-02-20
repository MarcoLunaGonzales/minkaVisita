<?php
require("conexion.inc");
require("estilos_reportes_central.inc");
// echo $rpt_territorio;
$global_linea = $linea_rpt;
$sql_cabecera_gestion = mysql_query("select nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
$datos_cab_gestion = mysql_fetch_array($sql_cabecera_gestion);
$nombre_cab_gestion = $datos_cab_gestion[0];
$prod_rpt = $_GET["prod_rpt"];
$nombre_linea="select nombre_linea from lineas where codigo_linea='$global_linea'";
$resp_linea=mysql_query($nombre_linea);
$dat_linea=mysql_fetch_array($resp_linea);
$nombre_linea=$dat_linea[0];
echo "<form method='post' action='opciones_medico.php'>";
$sql_ciclo = mysql_query("select cod_ciclo from ciclos where estado='Activo' and codigo_linea='$global_linea'");
$dat = mysql_fetch_array($sql_ciclo);
$cod_ciclo = $dat[0];
echo "<center><table border='0' class='textotit'><tr><th>Material de Apoyo en Parrilla por Especialidad <br>Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt Línea: $nombre_linea</th></tr></table></center><br>";
// //////////
$sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
$resp = mysql_query($sql);
$num_especialidades=mysql_num_rows($resp);
echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
echo "<tr><th>Especialidad</th>";
while ($dat = mysql_fetch_array($resp)) {
    $cod_espe = $dat[0];
    $nombre_espe = $dat[1];
    echo "<th colspan=2>$nombre_espe</th>";
}
echo "</tr>";
echo "<tr><th>&nbsp;</th>";
for($i=1;$i<=$num_especialidades;$i++)
{	echo "<th>A</th><th>B</th>";
}
echo "</tr>";
$sql_prod = "select distinct(pd.codigo_material), m.descripcion_material
				from parrilla p, parrilla_detalle pd, material_apoyo m
				 where m.codigo_material=pd.codigo_material and p.codigo_parrilla=pd.codigo_parrilla and 
				 p.codigo_linea='$linea_rpt'
				 and p.codigo_gestion='$gestion_rpt' and p.cod_ciclo='$ciclo_rpt' and m.codigo_material<>0";
$resp_prod = mysql_query($sql_prod);
while ($dat_prod = mysql_fetch_array($resp_prod)) {
    $cod_material = $dat_prod[0];
    $nombre_material = $dat_prod[1];
    echo "<tr><th>$nombre_material</th>";
    $sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
    $resp = mysql_query($sql);
    while ($dat = mysql_fetch_array($resp)) {
        $cod_espe = $dat[0];
		$desc_espe=$dat[1];
		$sql_cant_a="select sum(pd.cantidad_material)
				from parrilla p, parrilla_detalle pd
				 where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$linea_rpt'
				 and p.codigo_gestion='$gestion_rpt' and p.cod_ciclo='$ciclo_rpt' and
				 pd.codigo_material='$cod_material' and p.cod_especialidad='$cod_espe' and p.categoria_med='A'";
		$resp_cant_a=mysql_query($sql_cant_a);
		$dat_cant_a=mysql_fetch_array($resp_cant_a);
		$cantidad_prod_a=$dat_cant_a[0];

		$sql_cant_b="select sum(pd.cantidad_material)
				from parrilla p, parrilla_detalle pd
				 where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$linea_rpt'
				 and p.codigo_gestion='$gestion_rpt' and p.cod_ciclo='$ciclo_rpt' and
				 pd.codigo_material='$cod_producto' and p.cod_especialidad='$cod_espe' and p.categoria_med='B'";
		$resp_cant_b=mysql_query($sql_cant_b);
		$dat_cant_b=mysql_fetch_array($resp_cant_b);
		$cantidad_prod_b=$dat_cant_b[0];

		echo "<td title='$cod_espe A $nombre_material'>&nbsp;$cantidad_prod_a</td><td title='$cod_espe B $nombre_material'>&nbsp;$cantidad_prod_b</td>";
    }
    echo "</tr>";
}
echo "</table></center><br>";
echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

?>