<style type="text/css">
    .resul  th { color: #000; font-size: 14px}
</style>
<?php
set_time_limit(0);
require("conexion.inc");
require("estilos_reportes_central.inc");

$territorio = $_REQUEST['territorio'];
$formato = $_REQUEST['formato'];

$sql_nombre_territorio = mysql_query(" Select cod_ciudad,descripcion from ciudades where cod_ciudad in ($territorio) ");
while ($row_nombre_territorio = mysql_fetch_array($sql_nombre_territorio)) {
    $nombre_territorio .= $row_nombre_territorio[1] . ", ";
}
$nombre_territorio = substr($nombre_territorio, 0, -2)
?>
<center>
    <table class='textotit'>
        <tr>
            <th>
                Reporte Categorizacion M&eacute;dicos <br />
                Territorios : <?php echo $nombre_territorio; ?> <br />
                Formato : <?php echo $formato; ?>
            </th>
        </tr>
    </table>
</center>
<?php if ($formato == 'resumido'): ?>
    <?php
    $count = 1;
    $territorios_explode = explode(",", $territorio);
    ?>

    <center>
        <table class="resul" border="1">
            <tr>
                <th>&nbsp;</th>
                <th>Territorio</th>
                <th>Total M&eacute;dicos</th>
                <th>Llenados</th>
                <th>Faltantes</th>
            </tr>
            <?php
            foreach ($territorios_explode as $cod_territorio) {

                $sql_datosc = mysql_query(" select DISTINCT a.cod_med from rutero_maestro_detalle_aprobado a, rutero_maestro_aprobado c, rutero_maestro_cab_aprobado b  where a.cod_contacto = c.cod_contacto
                                                                    and c.cod_rutero = b.cod_rutero and b.codigo_gestion = 1009 and b.codigo_ciclo in (1,2) order by cod_med ASC");
                while ($row_1c = mysql_fetch_array($sql_datosc)) {
                    $totalc .= $row_1c[0] . ",";
                }
                $total_cc = substr($totalc, 0, -1);

                $sql_datos = mysql_query(" SELECT COUNT(cod_med) FROM medicos where cod_med in ($total_cc) and cod_ciudad = $cod_territorio  ");
                while ($row_1 = mysql_fetch_array($sql_datos)) {
                    $total = $row_1[0];
                }

                $sql_datos1c = mysql_query("select cod_med from medicos where cod_ciudad = $cod_territorio ");
                while ($row_2c = mysql_fetch_array($sql_datos1c)) {
                    $llenadosc .= $row_2c[0] . ",";
                }
                $codigoss = substr($llenadosc, 0, -1);

                $sql_datos1 = mysql_query(" SELECT COUNT(cod_med) from categorizacion_medico where  cod_med in ( $codigoss )  ");
                while ($row_2 = mysql_fetch_array($sql_datos1)) {
                    $llenados = $row_2[0];
                }

                $sql_datos2 = mysql_query(" SELECT descripcion from ciudades where cod_ciudad = $cod_territorio  ");
                while ($row_3 = mysql_fetch_array($sql_datos2)) {
                    $ciudad = $row_3[0];
                }
                ?>
                <tr>
                    <td><?php echo $count ?></td>
                    <td><?php echo $ciudad; ?></td>
                    <td><?php echo $total; ?></td>
                    <td><?php echo $llenados; ?></td>
                    <td><?php
        $faltante = $total - $llenados;
        echo $faltante;
                ?></td>
                </tr>
                <?php
                $count++;
            }
            ?>
        </table>
    </center>
<?php else: ?>

    <?php
    /* ------------------------------------------------------------------------------------------------------------------------------------- */

    $sql_datoscc = mysql_query(" select DISTINCT a.cod_med from rutero_maestro_detalle_aprobado a, rutero_maestro_aprobado c, rutero_maestro_cab_aprobado b  where a.cod_contacto = c.cod_contacto
                                                                    and c.cod_rutero = b.cod_rutero and b.codigo_gestion = 1009 and b.codigo_ciclo in(1,2)  order by cod_med ASC");
    while ($row_1cc = mysql_fetch_array($sql_datoscc)) {
        $totalcc .= $row_1cc[0] . ",";
    }
    $total_ccc = substr($totalcc, 0, -1);

    $sql_datosv = mysql_query(" SELECT COUNT(cod_med) FROM medicos where cod_med in ($total_ccc) and cod_ciudad in ($territorio)  ");
    while ($row_1v = mysql_fetch_array($sql_datosv)) {
        $totalv = $row_1v[0];
    }
    $sql_datos1cv = mysql_query("select cod_med from medicos where cod_ciudad in ( $territorio) ");
    while ($row_2cv = mysql_fetch_array($sql_datos1cv)) {
        $llenadoscv .= $row_2cv[0] . ",";
    }
    $codigossv = substr($llenadoscv, 0, -1);

    $sql_datos1v = mysql_query(" SELECT COUNT(cod_med) from categorizacion_medico where  cod_med in ( $codigossv )  ");
    while ($row_2v = mysql_fetch_array($sql_datos1v)) {
        $llenadosv = $row_2v[0];
    }

    $faltantes = $totalv - $llenadosv;
    /* ------------------------------------------------------------------------------------------------------------------------------------- */

    $sql_datos_cc = mysql_query(" SELECT DISTINCT a.cod_med from rutero_maestro_detalle_aprobado a, rutero_maestro_aprobado c, rutero_maestro_cab_aprobado b  where a.cod_contacto = c.cod_contacto
        and c.cod_rutero = b.cod_rutero and b.codigo_gestion = 1009 and  b.codigo_ciclo in(1,2)  order by cod_med ASC ");
    while ($row_1cc = mysql_fetch_array($sql_datos_cc)) {
        $totalcc .= $row_1cc[0] . ",";
    }
    $codigosc = substr($totalcc, 0, -1);
    $sql_datos_c = mysql_query(" SELECT cod_med FROM medicos where cod_med in ($codigosc) and cod_ciudad  in ($territorio) order by cod_med ASC ");
    while ($row_1c = mysql_fetch_array($sql_datos_c)) {
        $totalc .= $row_1c[0] . ",";
    }
    $array1 = explode(",", substr($totalc, 0, -1));

    $sql_datos1cc = mysql_query("  select cod_med from medicos where cod_ciudad  in ($territorio) order by cod_med ASC ");
    while ($row_2cc = mysql_fetch_array($sql_datos1cc)) {
        $llenadoscc .= $row_2cc[0] . ",";
    }
    $codigosc2 = substr($llenadoscc, 0, -1);

    $sql_datos1c = mysql_query("  SELECT cod_med from categorizacion_medico where  cod_med in ( $codigosc2) order by cod_med ASC ");
    while ($row_2c = mysql_fetch_array($sql_datos1c)) {
        $llenadosc .= $row_2c[0] . ",";
    }
    $array2 = explode(",", substr($llenadosc, 0, -1));

    $result = array_diff($array1, $array2);

    foreach ($result as $val) {
        $codigosss .= $val . ",";
    }

    $cod_final_ult = substr($codigosss, 0, -1);

    /* ------------------------------------------------------------------------------------------------------------------------------------- */

    $sql_nombre_medicos = mysql_query(" SELECT  DISTINCT CONCAT(m.ap_pat_med,' ',m.ap_mat_med,' ',m.nom_med) as nombre , rmda.cod_especialidad, rmda.categoria_med from
        medicos m, rutero_maestro_detalle_aprobado rmda, rutero_maestro_cab_aprobado rmca, rutero_maestro_aprobado rma where
        m.cod_med = rmda.cod_med and rma.cod_rutero = rmca.cod_rutero and rmda.cod_contacto =  rma.cod_contacto and rmca.estado_aprobado = 1 and m.cod_med in ($cod_final_ult) 
        and rmca.codigo_ciclo in(1,2) and .rmca.codigo_gestion = 1009 order by nombre");
    ?>


    <center>
        <table class='textotit'>
            <tr>
                <th>
                    Lista M&eacute;dicos faltantes : <?php echo $faltantes; ?>
                </th>
            </tr>
        </table>
    </center>
    <center>
        <table class="resul" border="1">
            <tr>
                <th>&nbsp;</th>
                <th>Nombre M&eacute;dico</th>
                <th>Especialidad</th>
                <th>Categoria</th>
            </tr>
            <?php
            $count = 1;
            while ($row_nom = mysql_fetch_array($sql_nombre_medicos)) {
                ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $row_nom[0]; ?></td>
                    <td><?php echo $row_nom[1]; ?></td>
                    <td><?php echo $row_nom[2]; ?></td>
                </tr>
                <?php
                $count++;
            }
            ?>
        </table>
    </center>

<?php endif; ?>

<br /><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>