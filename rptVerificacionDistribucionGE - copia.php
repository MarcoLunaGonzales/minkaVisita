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
                $sqlGrupo = "select g.`codigo_grupo_especial`, g.`nombre_grupo_especial`,
		(select c.descripcion from ciudades c where c.cod_ciudad=g.agencia) as agencia, g.codigo_linea
		from `grupo_especial` g order by g.codigo_linea";
                $respGrupo = mysql_query($sqlGrupo);
                while ($datGrupo = mysql_fetch_array($respGrupo)) {
                    $codGrupo = $datGrupo[0];
                    $nombreGrupo = $datGrupo[1];
                    $nombreCiudad = $datGrupo[2];
                    $codLinea = $datGrupo[3];
                    $cadena = '';
                    $sql_medicos = mysql_query("select g.cod_med, CONCAT(m.ap_pat_med,' ',m.ap_mat_med,' ',m.nom_med), c.cod_especialidad from 
                                medicos m, grupo_especial_detalle g, categorias_lineas c where m.cod_med = g.cod_med and c.cod_med = g.cod_med and
                                c.codigo_linea = $codLinea and g.codigo_grupo_especial = $codGrupo");
                    $num_medicos = mysql_num_rows($sql_medicos);
                    if ($num_medicos == 0) {
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
                        $cadena .= "<tr><td colspan='6'><span style='color:red; font-weight:bold'>No hay registro de m&eacute;dicos.</span></td></tr>";
                    } else {
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
                        while ($datMedicos = mysql_fetch_array($sql_medicos)) {
                            $codMed = $datMedicos[0];
                            $nombreMed = $datMedicos[1];
                            $codEspecialidad = $datMedicos[2];
                            $codVisitador = 0;
                            $sqlLineaVisita = mysql_query("select count(*) from lineas_visita lv, lineas_visita_especialidad lve  where lv.codigo_l_visita = lve.codigo_l_visita and
                                        lve.cod_especialidad = '$codEspecialidad' and lv.codigo_linea = '$codLinea' ");
                            $numLineaVisita = mysql_result($sqlLineaVisita, 0, 0);
                            if ($numLineaVisita == 0) {
                                $sqlVisitador = "select DISTINCT(rd.`cod_visitador`) from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` rm,
			`rutero_maestro_detalle_aprobado` rd where 
			rc.`cod_rutero`=rm.`cod_rutero` and rm.`cod_contacto`=rd.`cod_contacto` and 
			rc.`codigo_ciclo`='$codCiclo' and rc.`codigo_gestion`='$codGestion' and 
			rd.`cod_med`='$codMed' and rc.codigo_linea='$codLinea'";
                                $respVisitador = mysql_query($sqlVisitador);
                                $numFilasVisitador = mysql_num_rows($respVisitador);
                                if ($numFilasVisitador > 0) {
                                    $codVisitador = mysql_result($respVisitador, 0, 0);
                                }
                            } else {

                                $sqlVisitador = "select DISTINCT(rd.`cod_visitador`) from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` rm,
			`rutero_maestro_detalle_aprobado` rd where 
			rc.`cod_rutero`=rm.`cod_rutero` and rm.`cod_contacto`=rd.`cod_contacto` and 
			rc.`codigo_ciclo`='$codCiclo' and rc.`codigo_gestion`='$codGestion' and 
			rd.`cod_med`='$codMed' and rc.codigo_linea='$codLinea'";
                                $respVisitador = mysql_query($sqlVisitador);
                                while ($datVisitador = mysql_fetch_array($respVisitador)) {
                                    $codigoVis = $datVisitador[0];
                                    $sqlLinVisita = "select gl.`cod_l_visita` from `grupo_especial` g, `grupoespecial_lineavisita` gl, lineas_visita_especialidad le where 
					g.`codigo_grupo_especial`=gl.`cod_grupo` and g.`codigo_grupo_especial`='$codGrupo' and g.codigo_linea='$codLinea' and 
					gl.cod_l_visita=le.codigo_l_visita and le.cod_especialidad='$codEspecialidad'";
                                    $respLinVisita = mysql_query($sqlLinVisita);
                                    while ($datLinVisita = mysql_fetch_array($respLinVisita)) {
                                        $codigoLineaVisitaGrupo = $datLinVisita[0];
                                        $sqlVeriFuncionario = "select count(*) from `lineas_visita_visitadores` l, lineas_visita lv where 
					l.codigo_l_visita=lv.codigo_l_visita and lv.codigo_linea='$codLinea' and
					l.`codigo_funcionario`='$codigoVis' 
					and l.`codigo_l_visita`='$codigoLineaVisitaGrupo' and l.codigo_ciclo=$codCiclo and l.codigo_gestion=$codGestion";

                                        $respVeriFuncionario = mysql_query($sqlVeriFuncionario);
                                        $numFilasVeriFuncionario = mysql_result($respVeriFuncionario, 0, 0);
                                        if ($numFilasVeriFuncionario == 1) {
                                            $codVisitador = $codigoVis;
                                        }
                                    }
                                }
                            }
                            if ($codVisitador != 0) {
                                $nombreVisitador = nombreCompletoVisitador($codVisitador);
                            } else {
                                $nombreVisitador = '---';
                            }
                            $sqlParrilla = "select count(*) from `parrilla_especial` p where 
			p.`cod_ciclo`=$codCiclo and p.`codigo_gestion`=$codGestion and 
			p.`codigo_grupo_especial`=$codGrupo";
                            $respParrilla = mysql_query($sqlParrilla);
                            $nroParrillas = mysql_result($respParrilla, 0, 0);
                            $obs = "";
                            if ($nroParrillas == 0 || $codVisitador == 0) {
                                $obs = "<img src='imagenes/no.png'>";
                                $imprime_cab = $imprime_cab * 0;
                                $cadena .= "<tr><td>$codMed</td>
                                                 <td>$nombreMed</td>
                                                 <td>$codEspecialidad</td>
                                                 <td>$nombreVisitador</td>
                                                  <td>$nroParrillas</td><td>$obs</td></tr>";
                            } else {
                                $imprime_cab = $imprime_cab * 1;
                            }
                        }
                    }
                    if ($imprime_cab == 0) {
                        echo $cadena;
                    }
                    ?>
                    </tbody>
                    </table>
                    <?php
                }
                ?>
            </section>
            <footer>

            </footer>
        </div>
    </body>
</html>