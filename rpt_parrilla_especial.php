<?php
require("conexion.inc");
require("estilos_reportes_central.inc"); 
// echo $rpt_territorio;
$global_linea = $linea_rpt;
$sql_cab = "select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";
$resp1 = mysql_query($sql_cab);
$dato = mysql_fetch_array($resp1);
$nombre_territorio = $dato[1];
$sql_cabecera_gestion = mysql_query("select nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
$datos_cab_gestion = mysql_fetch_array($sql_cabecera_gestion);
$nombre_cab_gestion = $datos_cab_gestion[0];
echo "<form method='post' action=''>";
$sql_ciclo = mysql_query("select cod_ciclo from ciclos where estado='Activo' and codigo_linea='$global_linea'");
$dat = mysql_fetch_array($sql_ciclo);
$cod_ciclo = $dat[0];
if ($rpt_territorio == 0) {
    echo "<center><table border='0' class='textotit'><tr><th>Parrillas Especiales<br>Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
} else {
    echo "<center><table border='0' class='textotit'><tr><th>Parrillas Especiales<br>Territorio: $nombre_territorio<br>Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
} 
// //////////
echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
echo "<tr><th>Linea</th><th>Grupo Especial</th><th>Territorio</th><th>Visita</th><th>Parrilla Promocional</th></tr>";

$sql_grupoespecial = "select g.codigo_grupo_especial, g.nombre_grupo_especial, c.descripcion, 
(select l.nombre_linea from lineas l where l.codigo_linea=g.codigo_linea) 
 from grupo_especial g, ciudades c where c.cod_ciudad=g.agencia ";
if($rpt_territorio!=0){
	$sql_grupoespecial.=" and g.agencia=$rpt_territorio ";
}
$sql_grupoespecial.="order by g.nombre_grupo_especial";
$resp_grupoespecial = mysql_query($sql_grupoespecial);
while ($dat_grupoespe = mysql_fetch_array($resp_grupoespecial)) {
    
	$cod_grupoespecial = $dat_grupoespe[0];
    $nombre_grupoespecial = $dat_grupoespe[1];
	$nombre_territorio=$dat_grupoespe[2];
	$lineaGrupo=$dat_grupoespe[3];
	
    $sql = "select codigo_parrilla_especial, cod_ciclo, numero_visita, agencia
	from parrilla_especial where codigo_linea=$global_linea and cod_ciclo='$ciclo_rpt' and codigo_gestion='$gestion_rpt' 
	and codigo_grupo_especial='$cod_grupoespecial' order by cod_ciclo";
    $resp = mysql_query($sql);
    $filas = mysql_num_rows($resp);
    if ($filas > 0) {
        while ($dat = mysql_fetch_array($resp)) {
            $cod_parrilla = $dat[0];
            $cod_ciclo = $dat[1];
            $numero_de_visita = $dat[2];
		

            $sql1 = "select m.descripcion, m.presentacion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones,p.prioridad,p.extra
				from muestras_medicas m, parrilla_detalle_especial p, material_apoyo mm
      				where p.codigo_parrilla_especial=$cod_parrilla and m.codigo=p.codigo_muestra and mm.codigo_material=p.codigo_material order by p.prioridad";
            $resp1 = mysql_query($sql1);
            $parrilla_medica = "<table class='textomini' width='100%' border='0'>";
            $parrilla_medica = $parrilla_medica . "<tr><th>Orden</th><th>Producto</th><th>Cantidad</th><th>Material de Apoyo</th><th>Cantidad</td><th>Obs.</th></tr>";
            while ($dat1 = mysql_fetch_array($resp1)) {
                $muestra = $dat1[0];
                $presentacion = $dat1[1];
                $cant_muestra = $dat1[2];
                $material = $dat1[3];
                $cant_material = $dat1[4];
                $obs = $dat1[5];
                $prioridad = $dat1[6];
                $extra = $dat1[7];
                if ($extra == 1) {
                    $fondo_extra = "<tr bgcolor='#66CCFF'>";
                } else {
                    $fondo_extra = "<tr>";
                } 
                if ($obs != "") {
                    $observaciones = "<img src='imagenes/informacion.gif' alt='$obs'>";
                } else {
                    $observaciones = "&nbsp;";
                } 
                $parrilla_medica = $parrilla_medica . "$fondo_extra<td align='center'>$prioridad</td><td align='left' width='35%'>$muestra $presentacion</td><td align='center' width='10%'>$cant_muestra</td><td align='left' width='35%'>$material</td><td align='center' width='10%'>$cant_material</td><td align='center' width='10%'>$observaciones</td></tr>";
            } 
            $parrilla_medica = $parrilla_medica . "</table>";
            echo "<tr><td align='center'>$lineaGrupo</td><td align='center'>$nombre_grupoespecial</td><td align='center'>$nombre_territorio</td><td align='center'>$numero_de_visita</td><td align='center'>$parrilla_medica</td></tr>";
        } 
    } 
} 
echo "</table></center><br>";

echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

?>