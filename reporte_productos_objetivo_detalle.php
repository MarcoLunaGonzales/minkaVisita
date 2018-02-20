<?php
error_reporting(0);
require("conexion.inc");
$productos = $_GET['productos'];
$ciclo_ges = $_GET['ciclo'];
$productos_explode = explode(",", $productos);
foreach ($productos_explode as $key ) {
    $pro .= "'".$key."'".",";
}
$pro       = substr($pro, 0, -1);
$ciclo_ges = explode("|", $ciclo_ges);
$ciclo     = $ciclo_ges[0];
$gestionn  = $ciclo_ges[1];
$gesss     = mysql_result(mysql_query("SELECT nombre_gestion from gestiones where codigo_gestion = $gestionn"), 0, 0);
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Productos Objetivo</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
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
        <?php 
        require("estilos2.inc"); 
        ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Productos Objetivo x Visitador</h3>
            <h4 style="color: #5F7BA9; font-size: 1.2em; font-family: Vernada">Ciclo reporte: <?php echo $ciclo; ?> Gesti&oacute;n reporte: <?php echo $gesss; ?></h4>
        </header>
        <div class="row">
            <div class="twelve columns">
                <h2 style="font-size:1.1em; text-align:left"><?php echo $nom_ciudad; ?></h2>
                <table border="1">
                    <tr>
                        <th>Producto</th>
                        <?php  
                        $sql_funcionarios = mysql_query("SELECT DISTINCT p.codigo_funcionario, f.cod_ciudad from productos_objetivo p, productos_objetivo_detalle pd, funcionarios f, productos_objetivo_cabecera pc where pc.id = p.id_cabecera and  p.id = pd.id and f.codigo_funcionario = p.codigo_funcionario and pd.codigo_producto in ($pro) and pc.ciclo = $ciclo and pc.gestion = $gestionn and f.estado = 1 ORDER BY 2");
                        while ($row_funcionarios = mysql_fetch_array($sql_funcionarios)) { 
                            $sql_nom_funcionario = mysql_query("SELECT CONCAT(nombres,' ',paterno, ' ', materno), c.descripcion from funcionarios f, ciudades c where c.cod_ciudad = f.cod_ciudad and codigo_funcionario = $row_funcionarios[0] "); 
                            ?>
                            <th><?php echo mysql_result($sql_nom_funcionario, 0, 0); ?>  <?php echo mysql_result($sql_nom_funcionario, 0, 1); ?></th>
                            <?php
                        } 
                        ?>
                    </tr>
                    <?php
                    foreach ($productos_explode as $producto) { 
                        ?>
                        <tr>
                            <?php 
                            $sql_nom_producto = mysql_query("SELECT CONCAT(m.descripcion,' ',m.presentacion) as nom from muestras_medicas m where m.codigo ='$producto' ") ;
                            ?>
                            <td><?php echo mysql_result($sql_nom_producto, 0, 0); ?></td>
                            <?php 
                            $sql_funcionarios = mysql_query("SELECT DISTINCT p.codigo_funcionario, f.cod_ciudad from productos_objetivo p, productos_objetivo_detalle pd, funcionarios f, productos_objetivo_cabecera pc where pc.id = p.id_cabecera and  p.id = pd.id and f.codigo_funcionario = p.codigo_funcionario and pd.codigo_producto in ($pro) and pc.ciclo = $ciclo and pc.gestion = $gestionn and f.estado = 1 ORDER BY 2"); 
                            while ($row_fun = mysql_fetch_array($sql_funcionarios)) {
                                $sql = mysql_query("SELECT pd.cantidad_distribuida, pd.orden from productos_objetivo p, productos_objetivo_detalle pd, productos_objetivo_cabecera pc where pc.id = p.id_cabecera and p.id = pd.id and pd.codigo_producto =  '$producto' and p.codigo_funcionario = $row_fun[0] and pc.ciclo = $ciclo and pc.gestion = $gestionn");
                                if(mysql_num_rows($sql) == 0){
                                    echo "<td bgcolor='red' style='background: red'>-</td>";
                                }else{
                                    $sql_lineas = mysql_query("SELECT DISTINCT ad.especialidad from asignacion_productos_excel a, asignacion_productos_excel_detalle ad where ad.id = a.id and a.ciclo = $ciclo and a.gestion = $gestionn and ad.producto = '$producto' order by 1");
                                    $conca_espe = '';
                                    while ($row_li = mysql_fetch_array($sql_lineas)) {
                                        $conca_espe .= "'".$row_li[0]."',";
                                    }
                                    $conca_espe = substr($conca_espe, 0, -1);
                                    $sql_cantidad_a = mysql_query("SELECT count(distinct(rd.cod_med)) from  rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado r, rutero_maestro_detalle_aprobado rd where rc.cod_rutero=r.cod_rutero and r.cod_contacto=rd.cod_contacto and rc.codigo_ciclo = '$ciclo' and rc.codigo_gestion = '$gestionn' and rc.cod_visitador = '$row_fun[0]' and rd.categoria_med = 'A' ");
                                    $med_a =  mysql_result($sql_cantidad_a, 0, 0);
                                    $sql_cantidad_b = mysql_query("SELECT count(distinct(rd.cod_med)) from  rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado r, rutero_maestro_detalle_aprobado rd where rc.cod_rutero=r.cod_rutero and r.cod_contacto=rd.cod_contacto and rc.codigo_ciclo = '$ciclo' and rc.codigo_gestion = '$gestionn' and rc.cod_visitador = '$row_fun[0]' and rd.categoria_med = 'B' ");
                                    $med_b =  mysql_result($sql_cantidad_b, 0, 0);
                                    $sql_cantidad_c = mysql_query("SELECT count(distinct(rd.cod_med)) from  rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado r, rutero_maestro_detalle_aprobado rd where rc.cod_rutero=r.cod_rutero and r.cod_contacto=rd.cod_contacto and rc.codigo_ciclo = '$ciclo' and rc.codigo_gestion = '$gestionn' and rc.cod_visitador = '$row_fun[0]' and rd.categoria_med = 'C' ");
                                    $med_c =  mysql_result($sql_cantidad_c, 0, 0);
                                    echo "<td bgcolor='green' style='background: green; color:white; font-wight:bold'>Cantidad: ".mysql_result($sql, 0,0)." - P&deg;".mysql_result($sql, 0, 1)."M&deg;A: ".$med_a."M&deg;B: ".$med_b."M&deg;C: ".$med_c."</td>";
                                }
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