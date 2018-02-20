<?php
set_time_limit(0);
require("conexion.inc");
require("funcion_nombres.php");
$cod = $gestionCicloRpt;
$cod_e = explode('|', $cod);
$codCiclo = $cod_e[0];
$codGestion = $cod_e[1];
?>

<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <title>Verificaci&oacute;n Distribuci&oacute;n Grupos Especiales</title>
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
        <link rel="stylesheet" href="responsive/stylesheets/style.css">
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <link type="text/css" href="css/tables.css" rel="stylesheet" />
        <script type="text/javascript" language="javascript" src="lib/jquery.dataTables.js"></script>
        <style type="text/css">
            h4 {
                font-weight: normal;
                font-size: 13px
            }
            #container {
                width: 1000px;
                margin: 0 auto;
            }
            section[role="main"] {
                margin-top: 50px
            }
            table thead td{
                padding: 5px 10px;
                font-weight: bold
            }
        </style>
    </head>
    <body>
        <div id="container">
            <div class="header">
                <h2>Reporte de Verificaci&oacute;n Distribuci&oacute;n Grupos Especiales<br>Ciclo: <?php echo $codCiclo ?> Gesti&oacute;n: <?php echo $codGestion ?> </h2>
            </div>
            <section role="main">
                <?php
//                    $sqlGrupo = "select g.codigo_grupo_especial, ge.nombre_grupo_especial, c.descripcion, gd.cod_med,
//                    CONCAT(m.ap_pat_med,' ',m.ap_mat_med,' ',m.nom_med),cl.cod_especialidad, gd.cod_visitador, ge.codigo_linea from grupos_especiales g,
//                    grupo_especial ge, ciudades c, grupos_especiales_detalle gd, medicos m, categorias_lineas cl where g.codigo_grupo_especial = ge.codigo_grupo_especial 
//                    and c.cod_ciudad = g.territorio and g.id = gd.id and gd.cod_med = m.cod_med and cl.cod_med = m.cod_med and g.ciclo = $codCiclo and g.gestion =$codGestion ORDER BY 2;";
                $sqlGrupo = "select g.codigo_grupo_especial, ge.nombre_grupo_especial, c.descripcion, gd.cod_med, gd.cod_visitador, ge.codigo_linea from grupos_especiales g,
                    grupo_especial ge, ciudades c, grupos_especiales_detalle gd, medicos m, categorias_lineas cl where g.codigo_grupo_especial = ge.codigo_grupo_especial 
                    and c.cod_ciudad = g.territorio and g.id = gd.id and gd.cod_med = m.cod_med and cl.cod_med = m.cod_med and g.ciclo = $codCiclo and g.gestion =$codGestion ORDER BY 2;";
                // echo $sqlGrupo;
                $respGrupo = mysql_query($sqlGrupo);
                while ($datGrupo = mysql_fetch_array($respGrupo)) {
                    $codGrupo = $datGrupo[0];
                    $nombreGrupo = $datGrupo[1];
                    $nombreCiudad = $datGrupo[2];
                    $codMed = $datGrupo[3];
                    $codVisitador = $datGrupo[4];
                    $codLinea = $datGrupo[5];
                    $cadena = '';
                    $cadena .= "<h4>$codGrupo - $nombreGrupo - $nombreCiudad - $codLinea </h4>";
                    $cadena .= "<table border='1'>";
                    $cadena .= "<thead>
                                        <tr>
                                            <td>Cod Med</td>
                                            <td>Medico</td>
                                            <td>Especialidad</td>
                                            <td>Visitador Asignado</td>
                                            <td>Nro. Parrillas</td>
                                            <td>Observaci&oacute;n</td>
                                        </tr>
                                    </thead>
                                    <tbody>";
                    $imprime_cab = 1;
                    $nombreVisitador = nombreCompletoVisitador($codVisitador);
                    $sqlParrilla = "select count(*) from `parrilla_especial` p where 
			p.`cod_ciclo`=$codCiclo and p.`codigo_gestion`=$codGestion and 
			p.`codigo_grupo_especial`=$codGrupo";
            // echo $sqlParrilla;
                    $respParrilla = mysql_query($sqlParrilla);
                    $nroParrillas = mysql_result($respParrilla, 0, 0);
                    $obs = "";
                    $sql_medico = mysql_query("select CONCAT(ap_pat_med,' ',ap_mat_med,' ',nom_med) from medicos where cod_med = $codMed");
                    $nomMed = mysql_result($sql_medico, 0, 0);
//                    if($nroParrillas > 0){
//                        $obs = "<img src='imagenes/si.png'>";
//                        $imprime_cab = $imprime_cab * 1;
//                        $cadena .= "<tr><td>$codMed</td>
//                                                 <td>$nomMed</td>
//                                                 <td>$codEspecialidad</td>
//                                                 <td>$nombreVisitador</td>
//                                                  <td>$nroParrillas</td><td>$obs</td></tr>";
//                    }
                    if ($nroParrillas == 0) {
                        $obs = "<img src='imagenes/no.png'>";
                        $imprime_cab = $imprime_cab * 0;
                        $cadena .= "<tr><td>$codMed</td>
                                                 <td>$nomMed</td>
                                                 <td>$codEspecialidad</td>
                                                 <td>$nombreVisitador</td>
                                                  <td>$nroParrillas</td><td>$obs</td></tr>";
                    } else {
                        $imprime_cab = $imprime_cab * 1;
                    }
                    if ($imprime_cab == 0) {
                        echo $cadena;
                    }
                    ?>
                    </tbody>
                    </table>
                    <?php
                }
                if ($imprime_cab == 1) {
                    echo "<h3>Todo se encuentra en orden. Puede realizar la distribuci&oacute;n.</h3>";
                }
                ?>
            </section>
            <footer>

            </footer>
        </div>
    </body>
</html>