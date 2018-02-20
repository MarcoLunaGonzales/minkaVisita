<?php
error_reporting(0);
set_time_limit(0);
require("conexion.inc");

$ciclo_gestion         = $_GET['ciclo_gestion'];
$ciclo_gestion_explode = explode("-", $ciclo_gestion);
$ciclo_final           = $ciclo_gestion_explode[0];
$gestion_final         = $ciclo_gestion_explode[1];

$codigo_funcionario    = $_GET['productos'];
$codigo_funcionario    = explode(",", $productos);

$tipoRuteroRpt = $_GET['ver'];

if ($tipoRuteroRpt == 0) {
    $tabla1 = "rutero_maestro_cab";
    $tabla2 = "rutero_maestro";
    $tabla3 = "rutero_maestro_detalle";
}
if ($tipoRuteroRpt == 1) {
    $tabla1 = "rutero_maestro_cab_aprobado";
    $tabla2 = "rutero_maestro_aprobado";
    $tabla3 = "rutero_maestro_detalle_aprobado";
}
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Medicos en Rutero Maestro Resumido x CVS</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <style>
        table.aa tr th, table.aa tr td {
            padding: 5px 15px
        }
    </style>
    <script language='JavaScript'>
        function totales(idtable){
            var main=document.getElementById(idtable);   
            var numFilas=main.rows.length;
            var numCols=main.rows[1].cells.length;

            for(var j=1; j<=numCols-1; j++){
                var subtotal=0;
                for(var i=2; i<=numFilas-2; i++){
                    var dato=main.rows[i].cells[j].innerHTML;
                    if(dato=="&nbsp;"){
                        dato=0;
                    }else{
                        dato=parseInt(main.rows[i].cells[j].innerHTML);
                    }
                    subtotal=subtotal+dato;
                }
                var fila=document.createElement('TH');
                main.rows[numFilas-1].appendChild(fila);
                main.rows[numFilas-1].cells[j].innerHTML=subtotal;
            }    
        }
    </script>
</head>
<body>
    <div id="container">
        <?php require("estilos3.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">M&eacute;dicos en Rutero Maestro Resumido x CVS</h3>
            <h3 style="color: #5F7BA9; font-size: 1.1em; font-family: Vernada; font-weight: normal;">Ciclo: <?php echo $ciclo_final; ?> Gesti&oacute;n: <?php echo $gestion_final; ?></h3>
        </header>
        <div id="contenido">
            <?php  
            foreach ($codigo_funcionario as $codigo) {
                $sqlNombreVis = "SELECT concat(f.paterno, ' ',f.nombres), c.descripcion from funcionarios f, ciudades c where f.codigo_funcionario='$codigo' and f.cod_ciudad=c.cod_ciudad";
                $respNombreVis = mysql_query($sqlNombreVis);
                $nombreVisitador = mysql_result($respNombreVis, 0, 0);
                $nombreTerritorio = mysql_result($respNombreVis, 0, 1);
                ?>
                <div class="row">
                    <div class="nine columns centered">
                        <table class="aa" id="<?php echo $codigo ?>">
                            <tr>
                                <th>&nbsp;</th>
                                <th colspan="7"><?php echo $nombreVisitador." ".$nombreTerritorio; ?></th>
                                <th>&nbsp;</th>
                            </tr>
                            <tr>
                                <th>Especialidad</th>
                                <th>Cat A</th>
                                <th>Cat B</th>
                                <th>Cat C</th>
                                <th>Total M&eacute;dicos</th>
                                <th>Cont. A</th>
                                <th>Cont. B</th>
                                <th>Cont. C</th>
                                <th>TOTAL CONTACTOS</th>
                            </tr>
                            <?php  
                            $sqlRuteros = mysql_query("SELECT r.cod_rutero from $tabla1 r where r.codigo_ciclo = $ciclo_final and r.codigo_gestion = $gestion_final and r.cod_visitador = $codigo");
                            $rutero_maestro = mysql_result($sqlRuteros, 0, 0);
                            $sql_especialidades = mysql_query("SELECT DISTINCT cod_especialidad from rutero_maestro_detalle where cod_contacto in (SELECT cod_contacto from rutero_maestro where cod_rutero = $rutero_maestro) GROUP BY cod_especialidad");
                            while ($row_espe = mysql_fetch_array($sql_especialidades)) {
                                if($row_espe[0] == "0"){
                                    $espe  = "Sin Especialidad";
                                    $cat_A = "0";
                                    $cat_B = "0";
                                    $cat_C = "0";
                                    $sql_tota_med = mysql_query("SELECT COUNT(DISTINCT rd.cod_med) from rutero_maestro_detalle rd where rd.cod_contacto in (SELECT r.cod_contacto from rutero_maestro r where r.cod_rutero = $rutero_maestro) and rd.cod_especialidad = '0' ");
                                    $total_med = mysql_result($sql_tota_med, 0, 0);
                                    $con_A = "0";
                                    $con_B = "0";
                                    $con_C = "0";
                                    $sql_tota_med_con = mysql_query("SELECT COUNT(rd.cod_med) from rutero_maestro_detalle rd where rd.cod_contacto in (SELECT r.cod_contacto from rutero_maestro r where r.cod_rutero = $rutero_maestro) and rd.cod_especialidad = '0' ");
                                    $total_med_con = mysql_result($sql_tota_med_con, 0, 0);
                                }else{
                                    $espe = $row_espe[0];
                                    $sql_cat_a = mysql_query("SELECT COUNT(DISTINCT rd.cod_med) from rutero_maestro_detalle rd where rd.cod_contacto in (SELECT r.cod_contacto from rutero_maestro r where r.cod_rutero = $rutero_maestro) and rd.cod_especialidad <> '0' and rd.categoria_med = 'A' ");
                                    $cat_A = mysql_result($sql_cat_a, 0, 0);
                                    $sql_cat_b = mysql_query("SELECT COUNT(DISTINCT rd.cod_med) from rutero_maestro_detalle rd where rd.cod_contacto in (SELECT r.cod_contacto from rutero_maestro r where r.cod_rutero = $rutero_maestro) and rd.cod_especialidad <> '0' and rd.categoria_med = 'B' ");
                                    $cat_B = mysql_result($sql_cat_b, 0, 0);
                                    $sql_cat_c = mysql_query("SELECT COUNT(DISTINCT rd.cod_med) from rutero_maestro_detalle rd where rd.cod_contacto in (SELECT r.cod_contacto from rutero_maestro r where r.cod_rutero = $rutero_maestro) and rd.cod_especialidad <> '0' and rd.categoria_med = 'C' ");
                                    $cat_C = mysql_result($sql_cat_c, 0, 0);
                                    $sql_tota_med = mysql_query("SELECT COUNT(DISTINCT rd.cod_med) from rutero_maestro_detalle rd where rd.cod_contacto in (SELECT r.cod_contacto from rutero_maestro r where r.cod_rutero = $rutero_maestro) and rd.cod_especialidad <> '0' ");
                                    $total_med = mysql_result($sql_tota_med, 0, 0);

                                    $sql_con_a = mysql_query("SELECT COUNT(rd.cod_med) from rutero_maestro_detalle rd where rd.cod_contacto in (SELECT r.cod_contacto from rutero_maestro r where r.cod_rutero = $rutero_maestro) and rd.cod_especialidad <> '0' and rd.categoria_med = 'A' ");
                                    $con_A = mysql_result($sql_con_a, 0, 0);
                                    $sql_con_b = mysql_query("SELECT COUNT(rd.cod_med) from rutero_maestro_detalle rd where rd.cod_contacto in (SELECT r.cod_contacto from rutero_maestro r where r.cod_rutero = $rutero_maestro) and rd.cod_especialidad <> '0' and rd.categoria_med = 'B' ");
                                    $con_B = mysql_result($sql_con_b, 0, 0);
                                    $sql_con_c = mysql_query("SELECT COUNT(rd.cod_med) from rutero_maestro_detalle rd where rd.cod_contacto in (SELECT r.cod_contacto from rutero_maestro r where r.cod_rutero = $rutero_maestro) and rd.cod_especialidad <> '0' and rd.categoria_med = 'C' ");
                                    $con_C = mysql_result($sql_con_c, 0, 0);
                                    $sql_tota_med_con = mysql_query("SELECT COUNT(rd.cod_med) from rutero_maestro_detalle rd where rd.cod_contacto in (SELECT r.cod_contacto from rutero_maestro r where r.cod_rutero = $rutero_maestro) and rd.cod_especialidad <> '0' ");
                                    $total_med_con = mysql_result($sql_tota_med_con, 0, 0);
                                }
                                ?>
                                <tr>
                                    <td><?php echo $espe; ?></td>
                                    <td><?php echo $cat_A; ?></td>
                                    <td><?php echo $cat_B; ?></td>
                                    <td><?php echo $cat_C; ?></td>
                                    <td><?php echo $total_med; ?></td>
                                    <td><?php echo $con_A; ?></td>
                                    <td><?php echo $con_B; ?></td>
                                    <td><?php echo $con_C; ?></td>
                                    <td><?php echo $total_med_con; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <th>Totales</th>
                            </tr>
                        </table>
                        <script language='JavaScript'>totales(<?php echo $codigo ?>);</script>
                    </div>
                </div>
                <?php  
            }
            ?>
        </div>
    </div>
    <div class="modal"></div>
</body>
</html>