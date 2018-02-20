<?php
error_reporting(0);
require("../../conexion.inc");
$idd = $_GET['codigo'];
$sql_codigo = mysql_query("SELECT cod_med from banco_muestras where id in ($idd)");
while ($row_codigo = mysql_fetch_array($sql_codigo)) {
   $codigos .= $row_codigo[0].",";
} 
if(!isset($global_linea)){
    $global_linea = 1021;
}

$codigos_finales = substr($codigos, 0, -1);
$sql_gestion = mysql_query("SELECT codigo_gestion, nombre_gestion from gestiones where estado = 'Activo' ");
$codigo_gestion = mysql_result($sql_gestion, 0, 0);

$sql_ciclo=mysql_query("SELECT cod_ciclo from ciclos where estado='Activo' and codigo_linea='$global_linea'");
$dat_ciclo=mysql_fetch_array($sql_ciclo);
$ciclo_global=$dat_ciclo[0];

$sql_medicos = mysql_query("SELECT DISTINCT m.cod_med, CONCAT(m.nom_med,' ',m.ap_pat_med,' ',m.ap_mat_med), rd.cod_especialidad from medicos m, rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd WHERE rc.cod_rutero = rm.cod_rutero and  rm.cod_contacto = rd.cod_contacto and rd.cod_med = m.cod_med and m.cod_med in ($codigos_finales) and rc.codigo_gestion = $codigo_gestion and rc.codigo_ciclo = $ciclo_global");

?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Banco De Muestras</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <link type="text/css" href="css/tables.css" rel="stylesheet" />
    <script type="text/javascript" language="javascript" src="lib/jquery.dataTables.js"></script>
    <style type="text/css">
    h3{
        color: #5F7BA9;
        font-size: 1.2em;
        text-align: left;
    }
    table tbody tr:nth-child(2n){
        /*background: #F9E5E0*/
        background: #e9e9e9
    }
    table thead, table tfoot{
        background: #cfcdcd
    }
    </style>
    
</head>
<body>
    <div id="container">
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada; text-align: center">Banco de Muestras Detallo </h3>
        </header>
        <section role="main">
            <div class="row">
                <?php
                while ($row_medicos = mysql_fetch_array($sql_medicos)) {
                    $sql_visitadores = mysql_query("SELECT DISTINCT rmda.cod_visitador, CONCAT(f.nombres,' ',f.paterno,' ',f.materno) from rutero_maestro_aprobado rma, rutero_maestro_cab_aprobado rmca, rutero_maestro_detalle_aprobado rmda, funcionarios f where rmca.cod_rutero = rma.cod_rutero and rma.cod_contacto = rmda.cod_contacto and f.codigo_funcionario = rmda.cod_visitador and rmda.cod_med = $row_medicos[0] and rmca.codigo_ciclo = $ciclo_global and rmca.codigo_gestion = $codigo_gestion");
                    $cantidad_visitadores = mysql_num_rows($sql_visitadores);
                    $num = 1;
                    $espe_med = $row_medicos[2];
                    ?>
                    <div class="six columns end tablitas">
                        <h3><?php echo $row_medicos[1] . " (" . $row_medicos[0] . " - " . $row_medicos[2] . ")"; ?></h3>
                        <?php $codigo_medico = $row_medicos[0]; ?>
                        <table border="1">
                            <thead>
                                <tr>
                                    <td>N&uacute;mero</td> 
                                    <td>Nombre Visitador</td> 
                                    <td></td> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $contador_linea = 0; ?>
                                <?php while ($row_visitador = mysql_fetch_array($sql_visitadores)) { ?>
                                <tr>
                                    <td><?php echo $num; ?></td>
                                    <td><?php echo $row_visitador[1] ?>  </td>
                                    <?php
                                    /* Linea visita */
                                    $line_veri = mysql_query("SELECT codigo_l_visita from lineas_visita_visitadores where codigo_funcionario = $row_visitador[0] and codigo_ciclo = $ciclo_global and codigo_gestion = $codigo_gestion ");
                                    while ($row_line = mysql_fetch_array($line_veri)) {
                                        $lii .= $row_line[0] . ",";
                                    }
                                    $lii_sub = substr($lii, 0, -1);
                                    $lii_explode = explode(",", $lii_sub);
                                    ?>
                                    <td>
                                        <table border="1">
                                            <thead>
                                                <tr>
                                                    <td>Producto</td>
                                                    <td align="center">Cantidad a Entregar</td>
                                                    <td align="center">Cantidad Total</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql_muestra = mysql_query("SELECT DISTINCT a.cod_muestra, CONCAT(b.descripcion,' ',b.presentacion),a.cantidad, a.id from banco_muestras_detalle a, muestras_medicas b WHERE a.cod_muestra = b.codigo and a.cod_med = $codigo_medico");
                                                while ($row_muestras = mysql_fetch_array($sql_muestra)) {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $row_muestras[1] . " - " . $row_muestras[0]; ?> 
                                                            <input type="hidden" value="<?php echo $row_medicos[0] ?>" />
                                                            <input type="hidden" value="<?php echo $row_visitador[0]; ?>" />
                                                            <input type="hidden" value="<?php echo $row_muestras[0] ?>" />
                                                            <input type="hidden" value="<?php echo $row_muestras[3] ?>" />
                                                            <?php  
                                                            $cantidad = $row_muestras[2];
                                                            ?>
                                                        </td>
                                                        <td align="center">
                                                            <?php
                                                            $sql_canti = mysql_query("SELECT cantidad from banco_muestra_cantidad_visitador where id_for = $row_muestras[3] and cod_medico = $row_medicos[0] and cod_visitador = $row_visitador[0] and codigo_muestra = '$row_muestras[0]'");
                                                            $canti = mysql_result($sql_canti, 0, 0);
                                                            ?>
                                                            <input type="text" value="<?php echo $canti; ?>" disabled>
                                                        </td>
                                                        <td><?php echo $cantidad; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <?php
                                $num++;
                                ?>
                                <?php
                            }
                            $lii = '';
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
            ?>
        </div>
    </section>
</div>
</body>
</html>