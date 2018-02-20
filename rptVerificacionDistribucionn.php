<?php
require("conexion.inc");
require("estilos_inicio_adm.inc");
error_reporting(0);
$url = $_GET['gestionCicloRpt'];
$url1 = explode("|", $url);
$codCiclo = $url1[0];
$codGestion = $url1[1];
$codLinea = $_GET['linea_rpt'];
//$cadena = "";
?>

<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <title>Reporte de Verificaci&oacute;n Distribuci&oacute;n</title>
        <style type="text/css">
            #container {
                width: 960px;
                margin: 0 auto;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <header>
                <center>
                    <h2>
                        Reporte de Verificaci&oacute; Distribuci&oacute;n<br />
                        Ciclo:<?php echo $codCiclo ?> Gesti&oacute;n: <?php echo $codGestion ?>
                    </h2>
                </center>
            </header>
            <section role="main">
                <table border='1' cellspacing='0' align='center' width="80%">
                    <?php
                    $sqlVis = "select f.`codigo_funcionario`, CONCAT(f.`paterno`, ' ', f.`nombres`) as visitador,(select c.`descripcion` from `ciudades` c where c.`cod_ciudad` = f.cod_ciudad) as ciudad, f.cod_ciudad 
                                       from funcionarios f, `funcionarios_lineas` fl where f.`codigo_funcionario` = fl.`codigo_funcionario` and
                                       fl.`codigo_linea` = $codLinea and f.`estado`=1 and f.cod_ciudad <>115 and f.cod_cargo=1011 order by ciudad, visitador";
                    $respVis = mysql_query($sqlVis);
                    while ($datVis = mysql_fetch_array($respVis)) {
                        $codVisitador = $datVis[0];
                        $nombreVisitador = $datVis[1];
                        $ciudadVisitador = $datVis[2];
                        $codCiudad = $datVis[3];
                        $cadena .= "<tr>";
                        $cadena .= "<td colspan='7'><span style='color:#5858FA; background-color:#F3F781'> $nombreLinea </span></td>";
                        $cadena .= "</tr>";
                        $cadena .= "<tr>";
                        $cadena .= "<td colspan='7'>";
                        $cadena .= "<span style='color:#5858FA; background-color:#F3F781'>$ciudadVisitador   ------  $codVisitador  -------  $nombreVisitador  ---------  $nombreLinea </span>";
                        $cadena .= "</td>";
                        $cadena .= "</tr>";
                        ?>
                        <?php
                        $sqlRutero = "select rd.`cod_med`, count(*) as contactos, rd.`cod_especialidad`, rd.`categoria_med`, 
			(select concat(m.ap_pat_med,' ',m.ap_mat_med,' ', m.nom_med) from medicos m where m.cod_med=rd.cod_med) as medico
			from `rutero_maestro_cab_aprobado` rc,  `rutero_maestro_aprobado` rm, `rutero_maestro_detalle_aprobado` rd
			where rc.`cod_rutero`=rm.`cod_rutero` and rm.`cod_contacto`=rd.`cod_contacto` and 
			rc.`cod_visitador`=$codVisitador and rc.`codigo_ciclo`=$codCiclo and rc.`codigo_gestion`=$codGestion and 
			rc.`codigo_linea`=$codLinea group by rd.`cod_med` order by rd.`cod_especialidad`, rd.`categoria_med`, medico";
                        $respRutero = mysql_query($sqlRutero);
                        $numFilasRutero = mysql_num_rows($respRutero);
                        if ($numFilasRutero == 0) {
                            $obs = "<img src='imagenes/no.png'>";

                            $cadena .= "<tr>
                                    <td colspan='6'>
                                        <span style='color:#ff0000;'>EL FUNCIONARIO NO TIENE RUTERO APROBADO PARA EL CICLO Y GESTI&Oacute;N!!!!!!!!!!</span>
                                    </td>
                                    <td>$obs</td>
                                </tr>";
                        } else {
                            while ($datRutero = mysql_fetch_array($respRutero)) {
                                $codMed = $datRutero[0];
                                $nroContactos = $datRutero[1];
                                $codEspecialidadMed = $datRutero[2];
                                $categoriaMed = $datRutero[3];
                                $nombreMed = $datRutero[4];

                                $sqlLineaVis = "select l.`codigo_l_visita`, l.`nombre_l_visita` from `lineas_visita` l, `lineas_visita_especialidad` le, `lineas_visita_visitadores` lv
				where l.`codigo_l_visita`=le.`codigo_l_visita` and le.`codigo_l_visita`=lv.`codigo_l_visita` and l.`codigo_l_visita`=lv.`codigo_l_visita`
				and lv.`codigo_funcionario`=$codVisitador and le.`cod_especialidad`='$codEspecialidadMed' and l.`codigo_linea`=$codLinea";
                                $respLineaVis = mysql_query($sqlLineaVis);
                                $numFilasLineaVis = mysql_num_rows($respLineaVis);
                                if ($numFilasLineaVis == 0) {
                                    $codLineaVisita = 0;
                                    $nombreLineaVisita = "";
                                } else {
                                    $codLineaVisita = mysql_result($respLineaVis, 0, 0);
                                    $nombreLineaVisita = mysql_result($respLineaVis, 0, 1);
                                }

                                $sqlParrilla = "select count(*) from `parrilla` p where p.`agencia`=$codCiudad and p.`cod_ciclo`=$codCiclo and p.`codigo_gestion`=$codGestion and  
                                                        p.`cod_especialidad`='$codEspecialidadMed' and p.`categoria_med`='$categoriaMed' 
                                                        and p.`codigo_l_visita`=$codLineaVisita and p.`codigo_linea`=$codLinea";
                                $respParrilla = mysql_query($sqlParrilla);
                                $nroParrillas = mysql_result($respParrilla, 0, 0);
                                $obs = "";
                                if ($nroParrillas == $nroContactos) {
                                    $obs = "<img src='imagenes/si.gif'>";
                                }
                                if ($nroParrillas < $nroContactos) {
                                    $obs = "<img src='imagenes/pendiente.png'>";
//                                        $cadena .= "<tr>";
////                                        $cadena .= "<td colspan='7'><span style='color:#5858FA; background-color:#F3F781'> $nombreLinea </span></td>";
//                                        $cadena .= "</tr>";
//                                        $cadena .= "<tr>";
//                                        $cadena .= "<td colspan='7'>";
////                                        $cadena .= "<span style='color:#5858FA; background-color:#F3F781'>$ciudadVisitador   ------  $codVisitador  -------  $nombreVisitador  ---------  $nombreLinea </span>";
//                                        $cadena .= "</td>";
//                                        $cadena .= "</tr>";
//                                        $cadena .= "<tr>";
//                                        $cadena .= "<td>Cod Med</td>";
//                                        $cadena .= "<td>Medico</td>";
//                                        $cadena .= "<td>Esp.</td>";
//                                        $cadena .= "<td>Categoria</td>";
//                                        $cadena .= "<td>Contactos Rutero</td>";
//                                        $cadena .= "<td>Nro. Parrillas</td>";
//                                        $cadena .= "<td>Observaci&oacute;n</td>";
//                                        $cadena .= "</tr>";
//                                        $cadena .= "<tr>
//                                        <td>$codMed</td>
//                                        <td>$nombreMed</td>
//                                        <td>$codEspecialidadMed</td>
//                                        <td>$categoriaMed</td>
//                                        <td>$nroContactos</td>
//                                        <td>$nroParrillas</td>
//                                        <td>$obs</td>
//                                    </tr>";
//                                        echo $cadena;
                                }
                                if ($nroParrillas > $nroContactos) {
                                    $obs = "<img src='imagenes/pendiente.png'>";
//                                        $cadena .= "<tr>";
////                                        $cadena .= "<td colspan='7'><span style='color:#5858FA; background-color:#F3F781'> $nombreLinea </span></td>";
//                                        $cadena .= "</tr>";
//                                        $cadena .= "<tr>";
//                                        $cadena .= "<td colspan='7'>";
////                                        $cadena .= "<span style='color:#5858FA; background-color:#F3F781'>$ciudadVisitador   ------  $codVisitador  -------  $nombreVisitador  ---------  $nombreLinea </span>";
//                                        $cadena .= "</td>";
//                                        $cadena .= "</tr>";
//                                        $cadena .= "<tr>";
//                                        $cadena .= "<td>Cod Med</td>";
//                                        $cadena .= "<td>Medico</td>";
//                                        $cadena .= "<td>Esp.</td>";
//                                        $cadena .= "<td>Categoria</td>";
//                                        $cadena .= "<td>Contactos Rutero</td>";
//                                        $cadena .= "<td>Nro. Parrillas</td>";
//                                        $cadena .= "<td>Observaci&oacute;n</td>";
//                                        $cadena .= "</tr>";
//                                        $cadena .= "<tr>
//                                        <td>$codMed</td>
//                                        <td>$nombreMed</td>
//                                        <td>$codEspecialidadMed</td>
//                                        <td>$categoriaMed</td>
//                                        <td>$nroContactos</td>
//                                        <td>$nroParrillas</td>
//                                        <td>$obs</td>
//                                    </tr>";
//                                        echo $cadena;
                                }
                                if ($nroParrillas == 0) {
                                    $obs = "<img src='imagenes/no.png'>";
                                }
                                $cadena .= "<tr>";
                                $cadena .= "<td colspan='7'><span style='color:#5858FA; background-color:#F3F781'> $nombreLinea </span></td>";
                                $cadena .= "</tr>";
                                $cadena .= "<tr>";
                                $cadena .= "<td colspan='7'>";
                                $cadena .= "<span style='color:#5858FA; background-color:#F3F781'>$ciudadVisitador   ------  $codVisitador  -------  $nombreVisitador  ---------  $nombreLinea </span>";
                                $cadena .= "</td>";
                                $cadena .= "</tr>";
                                $cadena .= "<tr>";
                                $cadena .= "<td>Cod Med</td>";
                                $cadena .= "<td>Medico</td>";
                                $cadena .= "<td>Esp.</td>";
                                $cadena .= "<td>Categoria</td>";
                                $cadena .= "<td>Contactos Rutero</td>";
                                $cadena .= "<td>Nro. Parrillas</td>";
                                $cadena .= "<td>Observaci&oacute;n</td>";
                                $cadena .= "</tr>";
                                $cadena .= "<tr>
                                        <td>$codMed</td>
                                        <td>$nombreMed</td>
                                        <td>$codEspecialidadMed</td>
                                        <td>$categoriaMed</td>
                                        <td>$nroContactos</td>
                                        <td>$nroParrillas</td>
                                        <td>$obs</td>
                                    </tr>";
                                echo $cadena;
                            }
                        }
                    }
                    ?>
                </table>
            </section>
        </div>
    </body>
</html>