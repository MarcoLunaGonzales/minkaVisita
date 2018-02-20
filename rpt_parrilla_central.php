<?php

require("conexion.inc");
require("funcion_nombres.php");
require("estilos_reportes_central.inc");
//echo $rpt_territorio;
$global_linea         = $linea_rpt;
$nombreGlobalLinea=nombreLinea($global_linea);

$sql_cab              = "SELECT cod_ciudad,descripcion from ciudades where cod_ciudad = '$rpt_territorio'";
$resp1                = mysql_query($sql_cab);
$dato                 = mysql_fetch_array($resp1);
$nombre_territorio    = $dato[1];

$sql_cabecera_gestion = mysql_query("SELECT nombre_gestion from gestiones where codigo_gestion = '$gestion_rpt' and codigo_linea = '$global_linea'");
$datos_cab_gestion    = mysql_fetch_array($sql_cabecera_gestion);
$nombre_cab_gestion   = $datos_cab_gestion[0];

echo "<form method='post' action='opciones_medico.php'>";

$sql_ciclo = mysql_query("SELECT cod_ciclo from ciclos where estado = 'Activo' and codigo_linea = '$global_linea'");
$dat = mysql_fetch_array($sql_ciclo);
$cod_ciclo = $dat[0];
if ($rpt_territorio == 0) {
    echo "<center><table border='0' class='textotit'><tr><th>Parrillas Promocionales<br>Gesti&oacute;n: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
} else {
    echo "<center><table border='0' class='textotit'><tr><th>Parrillas Promocionales
	<br>Territorio: $nombre_territorio Linea: $nombreGlobalLinea<br>Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
}
echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><td>Producto Extra</td><td bgcolor='#66CCFF' width='30%'></td></tr></table></center><br>";

$sql_agencia = "SELECT cod_ciudad, descripcion from ciudades where cod_ciudad = '$rpt_territorio'";
$resp_agencia = mysql_query($sql_agencia);
while ($dat_agencia = mysql_fetch_array($resp_agencia)) {
    $cod_ciudad = $dat_agencia[0];
    $descripcion_ciudad = $dat_agencia[1];
    //seleccionando parrillas dependiendo de la agencia
    if ($cod_especialidad == "") {
        $sql = "SELECT p.codigo_parrilla, p.cod_ciclo, p.cod_especialidad, p.categoria_med, p.codigo_linea, p.fecha_creacion, p.fecha_modificacion, p.numero_visita, p.agencia, p.codigo_l_visita, p.muestras_extra, p.codigo_gestion, l.nombre_l_visita from parrilla p, lineas_visita l where p.agencia = $cod_ciudad and p.codigo_linea = $global_linea and p.cod_ciclo = '$ciclo_rpt' and p.codigo_l_visita = l.codigo_l_visita and p.codigo_gestion = '$gestion_rpt' order by p.cod_ciclo,p.cod_especialidad,l.orden ASC, p.categoria_med,p.numero_visita";
    } else {
        $sql = "SELECT p.codigo_parrilla, p.cod_ciclo, p.cod_especialidad, p.categoria_med, p.codigo_linea, p.fecha_creacion, p.fecha_modificacion, p.numero_visita, p.agencia, p.codigo_l_visita, p.muestras_extra, p.codigo_gestion, l.nombre_l_visita from parrilla p, lineas_visita l where  p.cod_especialidad = '$cod_especialidad' and p.agencia = $cod_ciudad and p.codigo_linea = $global_linea and p.cod_ciclo = '$ciclo_rpt' and p.codigo_l_visita = l.codigo_l_visita and p.codigo_gestion = '$gestion_rpt' order by p.cod_ciclo,p.cod_especialidad,l.orden ASC, p.categoria_med,p.numero_visita";
    }
    $resp = mysql_query($sql);
    $filas = mysql_num_rows($resp);
    if ($filas > 0) {
        echo "<table align='center' class='texto'><tr><th>$descripcion_ciudad</th></tr></table>";
        echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
        echo "<tr><th>Especialidad</th><th>L. V&iacute;sita</th><th>Categor&iacute;a</th><th>Visita</th><th>Parrilla Promocional</th></tr>";
        while ($dat = mysql_fetch_array($resp)) {
            $cod_parrilla      = $dat[0];
            $cod_ciclo         = $dat[1];
            $cod_espe          = $dat[2];
            $cod_cat           = $dat[3];
            $codLineaVisita    = $dat[9];
            $sqlLineaVisita    = "SELECT nombre_l_visita from lineas_visita where codigo_l_visita = $codLineaVisita";
            $respLineaVisita   = mysql_query($sqlLineaVisita);
            $nombreLineaVisita = "";

            while ($datLineaVisita = mysql_fetch_array($respLineaVisita)) {
                $nombreLineaVisita = $datLineaVisita[0];
            }
            //Plan de Lineas
            $sql_plan = mysql_query("SELECT count(pd.especialidad) from plan_linea_cab pc, plan_lineas p, plan_lineas_detalle pd 
                WHERE pc.id =p.id_cab and p.id = pd.id and pc.estado = 1 and p.ciudad = $cod_ciudad and pd.especialidad = '$cod_espe' ");
            $cuantos = mysql_result($sql_plan, 0, 0);
            if ($cuantos > 0) {
                $ultimo = $nombreLineaVisita[strlen($nombreLineaVisita)-1];
                $imprime_plan = $nombreLineaVisita." (".$ultimo." de ".$cuantos.")";
            }else{
                $imprime_plan = $nombreLineaVisita." (<span style='color:red'>No tiene Plan de Lineas</span>)";
            }

            //Fin plan de lienas
            $fecha_creacion   = $dat[5];
            $fecha_modi       = $dat[6];
            $numero_de_visita = $dat[7];
            $sql1 = "SELECT m.descripcion, m.presentacion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones,p.prioridad,p.extra from muestras_medicas m, parrilla_detalle p, material_apoyo mm where p.codigo_parrilla = $cod_parrilla and m.codigo = p.codigo_muestra and mm.codigo_material = p.codigo_material order by p.prioridad";
            $resp1 = mysql_query($sql1);
            $parrilla_medica = "<table class='textomini' width='100%' border='0'>";
            $parrilla_medica = $parrilla_medica . "<tr><th>Orden</th><th>Producto</th><th>Cantidad</th><th>Material de Apoyo</th><th>Cantidad</td><th>Obs.</th></tr>";
            while ($dat1 = mysql_fetch_array($resp1)) {
                $muestra       = $dat1[0];
                $presentacion  = $dat1[1];
                $cant_muestra  = $dat1[2];
                $material      = $dat1[3];
                $cant_material = $dat1[4];
                $obs           = $dat1[5];
                $prioridad     = $dat1[6];
                $extra         = $dat1[7];
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
            echo "<tr><td align='center'>$cod_espe</td><td align='center'>&nbsp;$imprime_plan</td>
            <td align='center'>$cod_cat</td><td align='center'>$numero_de_visita</td><td align='center'>$parrilla_medica</td></tr>";
        }
        echo "</table></center><br>";
    }
}

if ($rpt_territorio == 0) {

    if ($cod_especialidad == "") {
        $sql = "SELECT * from parrilla where agencia = 0 and codigo_linea = $global_linea and cod_ciclo = '$ciclo_rpt' and codigo_gestion = '$gestion_rpt' order by cod_ciclo,cod_especialidad,categoria_med,numero_visita";
    } else {
        $sql = "SELECT * from parrilla where cod_especialidad = '$cod_especialidad' and agencia = 0 and codigo_linea = $global_linea and cod_ciclo = '$ciclo_rpt' and codigo_gestion = '$gestion_rpt' order by cod_ciclo,cod_especialidad,categoria_med,numero_visita";
    }
    $resp = mysql_query($sql);
    $filas = mysql_num_rows($resp);
    if ($filas > 0) {
        echo "<table align='center' class='texto'><tr><th>Todas los Territorios</th></tr></table>";
        echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
        echo "<tr><th>Especialidad</th><th>L&iacute;nea de Visita</th><th>Categor&iacute;a</th><th>Visita</th><th>Parrilla Promocional</th></tr>";
        while ($dat = mysql_fetch_array($resp)) {
            $cod_parrilla       = $dat[0];
            $cod_ciclo          = $dat[1];
            $cod_espe           = $dat[2];
            $cod_cat            = $dat[3];
            $fecha_creacion     = $dat[5];
            $fecha_modi         = $dat[6];
            $numero_de_visita   = $dat[7];
            $codigo_lineavisita = $dat[9];

            $sql_nombre_lineavisita = "SELECT nombre_l_visita from lineas_visita where codigo_l_visita = '$codigo_lineavisita'";
            $resp_lineavisita       = mysql_query($sql_nombre_lineavisita);
            $dat_lineavisita        = mysql_fetch_array($resp_lineavisita); 
             $nombre_lineavisita    = $dat_lineavisita[0];

            $sql1 = "SELECT m.descripcion, m.presentacion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, 
			p.observaciones,p.prioridad,p.extra from muestras_medicas m, parrilla_detalle p, material_apoyo mm 
			where p.codigo_parrilla = $cod_parrilla and m.codigo = p.codigo_muestra and mm.codigo_material = p.codigo_material 
			order by p.prioridad";
            $resp1 = mysql_query($sql1);
            $parrilla_medica = "<table class='textomini' width='100%' border='0'>";
            $parrilla_medica = $parrilla_medica . "<tr><th>Orden</th><th>Producto</th><th>Cantidad</th><th>Material de Apoyo</th><th>Cantidad</td><th>Obs.</th></tr>";
            while ($dat1 = mysql_fetch_array($resp1)) {
                $muestra       = $dat1[0];
                $presentacion  = $dat1[1];
                $cant_muestra  = $dat1[2];
                $material      = $dat1[3];
                $cant_material = $dat1[4];
                $obs           = $dat1[5];
                $prioridad     = $dat1[6];
                $extra         = $dat1[7];

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
            echo "<tr><td align='center'>$cod_espe</td><td align='center'>$nombre_lineavisita&nbsp;</td><td align='center'>$cod_cat</td><td align='center'>$numero_de_visita</td><td align='center'>$parrilla_medica</td></tr>";
        }
        echo "</table></center><br>";
    }
}
echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
?>