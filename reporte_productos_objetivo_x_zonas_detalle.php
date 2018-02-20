<?php
error_reporting(0);
require("conexion.inc");
$territorio = $_GET['territorio'];
$ciclo_gestion = $_GET['ciclo_gestion'];

$ciclo_gestion = explode(",", $ciclo_gestion);
$ciclo       = $ciclo_gestion[0];
$gestion_fin = $ciclo_gestion[1];
$sql_nom_ciudades = mysql_query("SELECT descripcion from ciudades where cod_ciudad in ($territorio)");
$nom_ciudad = mysql_result($sql_nom_ciudades, 0, 0);
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Reporte productos Objetivo x zonas detalle</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {

    });
    </script>
    <style type="text/css">
    #contenido tr th {
        padding: 5px
    }
    .controls input, .controls select {
        padding: 0
    }
    input[type="button"] {
        margin: 10px 0;
        cursor: pointer;
        background: #fff;
    }
    .twelve table th{
        padding: 5px;
    }
    </style>
</head>
<body>
    <div id="container">
        <?php require("estilos2.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Reporte Productos Objetivo x Zonas</h3>
        </header>
        <div class="row centered">
            <div class="twelve columns centered">
                <h2 style="font-size:1.1em; text-align:left"><?php echo $nom_ciudad; ?></h2>
                <table border="1">
                    <tr>
                        <th>Producto / Zona</th>
                        <?php  
                        $sql_distritos = mysql_query("SELECT cod_dist, descripcion from distritos where cod_ciudad = $territorio");
                        while ($row_dist = mysql_fetch_array($sql_distritos)) {
                            ?>
                            <th>
                                <?php echo $row_dist[1]; ?>
                                <table>
                                    <tr>
                                        <?php  
                                        $sql_zonas = mysql_query("SELECT cod_zona, zona from zonas where cod_dist = $row_dist[0] and cod_ciudad = $territorio");
                                        $sql_cuantas_zonas = mysql_query("SELECT COUNT(cod_zona) from zonas where cod_dist = $row_dist[0] and cod_ciudad = $territorio");
                                        $cuantas_zonas = mysql_result($sql_cuantas_zonas, 0, 0);
                                        $porcentaje_final = round(100/$cuantas_zonas);
                                        while ($row_zona = mysql_fetch_array($sql_zonas)) {
                                            ?>
                                            <th width="<?php echo $porcentaje_final; ?>%"><?php echo $row_zona[1]; ?></th>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                </table>
                            </th>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                    $sql_productos = mysql_query("SELECT DISTINCT pd.codigo_producto, CONCAT(mm.descripcion,' ',mm.presentacion)  FROM productos_objetivo_cabecera pc, productos_objetivo po, productos_objetivo_detalle pd, muestras_medicas mm, funcionarios f WHERE pc.id = po.id_cabecera and po.id = pd.id and pd.codigo_producto = mm.codigo and po.codigo_funcionario = f.codigo_funcionario and pc.ciclo = $ciclo and pc.gestion = $gestion_fin and f.cod_ciudad = $territorio ORDER BY 2 ");  
                    while ($row_productos = mysql_fetch_array($sql_productos)) {
                        $cadena_funcionarios = '';
                        $sql_codigos_funcionarios = mysql_query("SELECT DISTINCT pd.codigo_funcionario from productos_objetivo_cabecera p, productos_objetivo pd, productos_objetivo_detalle pdd where p.id = pd.id_cabecera and pd.id = pdd.id and p.ciclo = $ciclo and p.gestion = $gestion_fin and codigo_producto = '$row_productos[0]' ");
                        while ($row_codigos_funcioanrios = mysql_fetch_array($sql_codigos_funcionarios)) {
                            $cadena_funcionarios .= $row_codigos_funcioanrios[0].",";
                        }
                        $cadena_funcionarios = substr($cadena_funcionarios, 0, -1);
                        ?>
                        <tr>
                            <td>
                                <?php echo $row_productos[1]; ?>
                            </td>
                            <?php  
                            $sql_distritos = mysql_query("SELECT cod_dist, descripcion from distritos where cod_ciudad = $territorio");
                            while ($row_dist = mysql_fetch_array($sql_distritos)) {
                                ?>
                                <th>
                                    <table>
                                        <tr>
                                            <?php  
                                            $sql_zonas = mysql_query("SELECT cod_zona, zona from zonas where cod_dist = $row_dist[0] and cod_ciudad = $territorio");
                                            while ($row_zona = mysql_fetch_array($sql_zonas)) {
                                                $sql_medicos = mysql_query("SELECT DISTINCT rmd.cod_visitador FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd, medicos m, direcciones_medicos dm WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto AND m.cod_med = rmd.cod_med and rmd.cod_med = dm.cod_med and rmc.codigo_gestion = $gestion_fin and rmc.codigo_ciclo = $ciclo and rmd.cod_visitador in ($cadena_funcionarios) and m.cod_ciudad = $territorio and dm.cod_zona = $row_zona[0] ");
                                                $num_rows = mysql_num_rows($sql_medicos);
                                                $visitadores_finales = "";
                                                while ($row_visitadores_finales = mysql_fetch_array($sql_medicos)) {
                                                    $visitadores_finales .= $row_visitadores_finales[0].",";
                                                }
                                                $visitadores_finales = substr($visitadores_finales, 0, -1);
                                                if($num_rows == 0 || $num_rows == ''){
                                                    $bg = "red";
                                                }
                                                if($num_rows == 1){
                                                    $bg = "green";
                                                }
                                                if($num_rows == 2){
                                                    $bg = "blue";
                                                }
                                                if($num_rows >= 3){
                                                    $bg = "orange";
                                                } 
                                                $sql_cuantas_zonas = mysql_query("SELECT COUNT(cod_zona) from zonas where cod_dist = $row_dist[0] and cod_ciudad = $territorio");
                                                $cuantas_zonas = mysql_result($sql_cuantas_zonas, 0, 0);
                                                $porcentaje_final = round(100/$cuantas_zonas);
                                                ?>
                                                <td width="<?php echo $porcentaje_final; ?>%" background="<?php echo $bg;?>" style="background: <?php echo $bg;?>; color:#fff">
                                                    <?php
                                                    $sql_nom_funcioanrios = mysql_query("SELECT CONCAT(nombres,' ',paterno,' ',materno) from funcionarios where codigo_funcionario in ($visitadores_finales)");
                                                    while ($row_a = mysql_fetch_array($sql_nom_funcioanrios)) {
                                                        echo $row_a[0];
                                                        echo "<span style='border: 1px dashed #c9c9c9; display:block; overflow:hidden; width: 100%'></span>";
                                                    }
                                                    ?>
                                                </td>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                    </table>
                                </th>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>