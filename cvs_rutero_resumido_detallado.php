<?php
error_reporting(0);
set_time_limit(0);
require("conexion.inc");

header ( "Content-Type: text/html; charset=UTF-8" );
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

$ciclo_gestion         = $_GET['ciclo_gestion'];
$ciclo_gestion_explode = explode("-", $ciclo_gestion);
$ciclo_final           = $ciclo_gestion_explode[0];
$gestion_final         = $ciclo_gestion_explode[1];

$codigo_funcionario    = $_GET['productos'];

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

$sqlRuteros = mysql_query("SELECT r.cod_rutero from $tabla1 r where r.codigo_ciclo = $ciclo_final and r.codigo_gestion = $gestion_final and r.cod_visitador = $codigo_funcionario");
$rutero_maestro = mysql_result($sqlRuteros, 0, 0);

$sql_rutero = mysql_query("SELECT r.cod_contacto, r.cod_rutero, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje from $tabla2 r, orden_dias o where r.cod_visitador = $codigo_funcionario and r.cod_rutero = '$rutero_maestro' and r.dia_contacto = o.dia_contacto order by o.id, r.turno");
$num_rutero = mysql_num_rows($sqlRuteros);

$sql_nom_rutero = mysql_query("SELECT nombre_rutero from $tabla1 where cod_rutero = '$rutero_maestro' and cod_visitador = '$codigo_funcionario'");
$nom_rutero     = mysql_result($sql_nom_rutero, 0, 0);
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Rutero Maestro x CVS</title>
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
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Rutero Maestro x CVS</h3>
            <h3 style="color: #5F7BA9; font-size: 1.1em; font-family: Vernada; font-weight: normal;">Ciclo: <?php echo $ciclo_final; ?> Gesti&oacute;n: <?php echo $gestion_final; ?></h3>
        </header>
        <div id="contenido">
            <div class="row">
                <div class="ten columns centered">
                    <center><?php echo $nom_rutero; ?></center>
                </div>
            </div>
            <div class="row" style="margin: 15px 0">
                <div class="twelve columns">
                    <center>
                        <table class="rutero">
                            <tr>
                                <th>D&iacute;a Contacto</th>
                                <th>Turno</th>
                                <th>Contactos</th>
                            </tr>
                            <?php  

                            if($num_rutero == 0){
                                ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; font-weight:bold">No hay detalle del rutero</td>
                                </tr>
                                <?php
                            } else{
                                $indice = 1;
                                while ($row_rutero = mysql_fetch_array($sql_rutero)) {
                                    $cod_contacto  = $row_rutero[0];
                                    $cod_ciclo     = $row_rutero[1];
                                    $dia_contacto  = $row_rutero[3];
                                    $turno         = $row_rutero[4];
                                    $zona_de_viaje = $row_rutero[5];                            

                                    $sql_fi = mysql_query("SELECT * from rutero_maestro_detalle where cod_contacto = $cod_contacto and cod_visitador = $codigo_funcionario");
                                    $tabla_aux = "<table class='textomini' width='100%'>";
                                    $tabla_aux = $tabla_aux."<tr><th width='5%'>Orden</th><th>Nro.Cont.</th><th width='25%'>M&eacute;dico</th><th width='5%'>Especialidad</th><th width='10%'>Categor&iacute;a</th><th width='30%'>Direcci&oacute;n</th><th width='15%'>Zona</th></tr>";
                                    while ($row_fi =  mysql_fetch_array($sql_fi)) {
                                        $orden_visita  = $row_fi[1];
                                        $cod_med       = $row_fi[3];
                                        $cod_espe      = $row_fi[4];
                                        $cod_cat       = $row_fi[5];
                                        $cod_zona      = $row_fi[6];
                                        $estado        = $row_fi[7];
                                        $tipoo         = $row_fi[8];


                                        if($tipoo == 1){
                                            $sql_orden = mysql_query("SELECT nombre, direccion from centros_medicos where cod_centro_medico = $cod_med");
                                            $nombre = mysql_result($sql_orden, 0, 0);
                                            $direccion = mysql_result($sql_orden, 0, 1);
                                            if($direccion == ''){
                                                $direccion = '-';
                                            }
                                            $zona_f = '-';
                                        }
                                        if($tipoo == 2){
                                            $sql_orden = mysql_query("SELECT m.ap_pat_med, m.ap_mat_med, m.nom_med, d.direccion from medicos m, direcciones_medicos d where  (m.cod_med = d.cod_med)  and m.cod_med = $cod_med ");
                                            $nombre = mysql_result($sql_orden, 0, 0)." ".mysql_result($sql_orden, 0, 1)." ".mysql_result($sql_orden, 0, 2);
                                            $direccion = mysql_result($sql_orden, 0, 3);
                                            if($direccion == ''){
                                                $direccion = '-';
                                            }
                                            $zona_f = mysql_result(mysql_query("SELECT zona from zonas where cod_zona = $cod_zona"), 0, 0);
                                            if($zona_f == ''){
                                                $zona_f = '-';
                                            }
                                        }
                                        if($tipoo == 3){
                                            $sql_orden = mysql_query("SELECT nombre_cliente, dir_cliente, cod_zona from clientes2 where cod_cliente = $cod_med");
                                            $nombre = mysql_result($sql_orden, 0, 0);
                                            $direccion = mysql_result($sql_orden, 0, 1);
                                            $cod_zonaa = mysql_result($sql_orden, 0, 2);    
                                            if($direccion == ''){
                                                $direccion = '-';
                                            }
                                            $zona_f = mysql_result(mysql_query("SELECT zona from zonas where cod_zona = $cod_zonaa"), 0, 0);
                                            if($zona_f == ''){
                                                $zona_f = '-';
                                            }
                                        }
                                        $tabla_aux = $tabla_aux."<tr><td align='center'>$orden_visita</td><td>$indice</td><td>&nbsp;$nombre</td><td>&nbsp;$cod_espe</td><td align='center'>$cod_cat</td><td>&nbsp;$direccion </td><td align='center'>$zona_f</td></tr>";
                                        $indice++;
                                    }
                                    $tabla_aux = $tabla_aux."</table>";
                                    ?>
                                    <tr>
                                        <td align="left"><?php echo $dia_contacto ?></td>
                                        <td align="center"><?php echo $turno ?></td>
                                        <td align="center"><?php echo $tabla_aux ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </table>
                    </center>
                </div>
            </div>
        </div>
    </div>
    <div class="modal"></div>
</body>
</html>