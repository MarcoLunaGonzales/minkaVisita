<?php
error_reporting(0);
require("conexion.inc");
$territorio = $_GET['territorio'];

$arrayCodCiudades = array();
$arrayNomCiudades = array();
$sql_nom_ciudades = mysql_query("SELECT cod_ciudad, descripcion from ciudades where cod_ciudad in ($territorio)");
while ($row_ciudades = mysql_fetch_array($sql_nom_ciudades)) {
    $arrayCodCiudades[] = $row_ciudades[0];
    $arrayNomCiudades[] = $row_ciudades[1];
}
$arrayCiudades = array_combine($arrayCodCiudades, $arrayNomCiudades);
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Visitadores x Zona</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {

    });
    </script>
    <style type="text/css">
    #contenedopr tr th {
        padding: 5px 10px
    }
    .controls input, .controls select {
        padding: 0
    }
    input[type="button"] {
        margin: 10px 0;
        cursor: pointer;
        background: #fff;
    }
    .six table th, .six table td{
        padding: 5px 10px;
    }
    </style>
</head>
<body>
    <div id="container">
        <?php require("estilos2.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Visitadores x Zona</h3>
        </header>
        <div class="row">
            <?php  
            foreach ($arrayCiudades as $cod_ciudad => $nom_ciudad) {
                $count = 1;
                ?>
                <div class="six columns end">
                    <h2 style="font-size:1.1em; text-align:left"><?php echo $nom_ciudad; ?></h2>
                    <table border="1">
                        <tr>
                            <th>&nbsp;</th>
                            <th>Nombre Visitador</th>
                            <th>Distrito</th>
                            <th>Zona</th>
                        </tr>
                        <?php  
                        $sql_datos = mysql_query("SELECT vz.cod_visitador, CONCAT(f.nombres,' ',f.paterno,' ',f.materno) as nombre, z.cod_dist, d.descripcion, vz.cod_zona, z.zona FROM visitadores_zonas vz, funcionarios f, zonas z, distritos d WHERE f.codigo_funcionario = vz.cod_visitador and z.cod_zona = vz.cod_zona and d.cod_dist = z.cod_dist and f.cod_ciudad = $cod_ciudad ORDER BY 1,3,5");
                        $num_datos = mysql_num_rows($sql_datos);
                        if($num_datos == 0){
                            ?>
                            <tr>
                                <td colspan="4"><center style="font-weight:bold">No hay datos</center></td>
                            </tr>
                            <?php
                        }else{
                            while ($row_datos = mysql_fetch_array($sql_datos)) {
                                ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $row_datos[1]; ?></td>
                                    <td><?php echo $row_datos[3]; ?></td>
                                    <td><?php echo $row_datos[5]; ?></td>
                                </tr>
                                <?php
                                $count++;
                            }
                        }
                        ?>
                    </table>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</body>
</html>