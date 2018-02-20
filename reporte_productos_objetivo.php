<?php
error_reporting(0);
require("conexion.inc");
$year = date('Y');
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Banco De Muestras</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $("#enviar").click(function(){
            var productos;
            ciclo = $("#ciclo_de").val();
            productos = $("#productos").val();
            window.location.href = "reporte_productos_objetivo_detalle.php?&productos="+productos+"&ciclo="+ciclo;
        })                
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
    </style>
</head>
<body>
    <div id="container">
        <?php require("estilos2.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Productos Objetivo</h3>
        </header>
        <div id="contenido">
            <center>
                <table border="1">
                    <tr>
                        <th>Ciclo</th>
                        <td>
                            <select name="ciclo_de" id="ciclo_de">
                                <?php
                                $sql_gestion = mysql_query("SELECT distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 0,11");
                                while ($dat = mysql_fetch_array($sql_gestion)) {
                                    $codCiclo = $dat[0];
                                    $codGestion = $dat[1];
                                    $nombreGestion = $dat[2];
                                    ?>
                                    <option value="<?php echo $codCiclo . "|" . $codGestion ?>"><?php echo $codCiclo . " " . $nombreGestion ?></option>
                                    <?php
                                } 
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Productos</th>
                        <td>
                            <select name="productos" id="productos" multiple size="13">
                                <?php
                                $sql_territorio = mysql_query("SELECT DISTINCT pd.codigo_muestra, CONCAT(m.descripcion,' ',m.presentacion) from parrilla p, parrilla_detalle pd, muestras_medicas m where p.codigo_parrilla = pd.codigo_parrilla and m.codigo = pd.codigo_muestra and p.cod_ciclo in (4,5) and p.codigo_gestion = 1010 order by 2");
                                while ($dat_t = mysql_fetch_array($sql_territorio)) {
                                    $codigo_ciudad = $dat_t[0];
                                    $nombre_ciudad = $dat_t[1];
                                    ?>
                                    <option value="<?php echo $codigo_ciudad ?>"> <?php echo $nombre_ciudad ?></option>
                                    <?php
                                } 
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type="button" id="enviar" value="Ver" />
            </center>
        </div>
    </div>
</body>
</html>