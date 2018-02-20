<?php
error_reporting(0);
require("conexion.inc");
set_time_limit(0);
$ciclo_gestion = $_GET['ciclo'];
$territorios = $_GET['territorios'];
$linea = $_GET['linea'];

$explode = explode("-", $ciclo_gestion);
$ciclo = $explode[0];
$gestion = $explode[1];
$sql_nom_gestion = mysql_query("SELECT nombre_gestion from gestiones where codigo_gestion = $gestion");
$nom_gestion = mysql_result($sql_nom_gestion, 0,0);
$nombre_ciudades ='';
$sql_territorios = mysql_query("SELECT descripcion from ciudades where cod_ciudad in ($territorios)");
while ($row_nom_terri = mysql_fetch_array($sql_territorios)) {
    $nombre_ciudades .= $row_nom_terri[0].",";
}
$nombre_ciudades = substr($nombre_ciudades, 0,-1);
$territorios_explode = explode(",", $nombre_ciudades);

function compara_fechas($fecha1,$fecha2) {  
    if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1))
        list($dia1,$mes1,$año1)=split("/",$fecha1);
    if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1))
        list($dia1,$mes1,$año1)=split("-",$fecha1);
    if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2))
        list($dia2,$mes2,$año2)=split("/",$fecha2);
    if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2))
        list($dia2,$mes2,$año2)=split("-",$fecha2);
    $dif = mktime(0,0,0,$mes1,$dia1,$año1) - mktime(0,0,0, $mes2,$dia2,$año2);
    // echo "<script>alert('".$dif."')</script>";
    return ($dif);                         
}
//esta parte saca el dia de contacto actual
$sql_dias_ini_fin="SELECT fecha_ini, fecha_fin from ciclos where cod_ciclo = '$ciclo' and codigo_gestion = '$gestion'and codigo_linea = '$linea'";

$resp_dias_ini_fin=mysql_query($sql_dias_ini_fin);
$dat_dias=mysql_fetch_array($resp_dias_ini_fin);
$fecha_ini_actual=$dat_dias[0];
$fecha_fin_actual=$dat_dias[1];
$fecha_actual=$fecha_ini_actual;
$inicio=$fecha_ini_actual;
$k=0;
list($anio,$mes,$dia)=explode("-",$fecha_actual);
$dia1=$dia;
while($inicio<$fecha_fin_actual) {
    $ban=0;
    while($ban==0) {   
        $nueva1 = mktime(0,0,0, $mes,$dia1,$anio);
        $dia_semana=date("l",$nueva1);
        if($dia_semana=='Sunday' or $dia_semana=='Saturday') {   
            $dia1=$dia1+1;
        } else {
            $ban=1;
        }
    }
    $num_dia=intval($k/5)+1;
    if($dia_semana=='Monday'){$dias[$k]="Lunes $num_dia";}
    if($dia_semana=='Tuesday'){$dias[$k]="Martes $num_dia";}
    if($dia_semana=='Wednesday'){$dias[$k]="Miercoles $num_dia";}
    if($dia_semana=='Thursday'){$dias[$k]="Jueves $num_dia";}
    if($dia_semana=='Friday'){$dias[$k]="Viernes $num_dia";}

    $fecha_actual=date("Y-m-d",$nueva1);
    $inicio=$fecha_actual;
    list($anio,$mes,$dia)=explode("-",$fecha_actual);
    $dia1=$dia+1;
    $fecha_actual_formato="$dia/$mes/$anio";
    $fechas[$k]=$fecha_actual_formato;
    $k++;
}
//fin vectores dias y fechas
$contador=1;
//desde aqui sacamos las fechas nuevas
$fecha_sistema=date("d/m/Y");
list($d_actual,$m_actual,$a_actual)=explode("/",$fecha_sistema);
$sec_actual=mktime(0,0,0,$m_actual,$d_actual,$a_actual);
for($i=0;$i<=$k-1;$i++) {   
    list($d_comp,$m_comp,$a_comp)=explode("/",$fechas[$i]);
    $sec_comp=mktime(0,0,0,$m_comp,$d_comp,$a_comp);
    if($sec_comp<=$sec_actual) {   
        $posicion=$i;
    }
}
$dia_contacto_sistema=$dias[$posicion];
$sql_id="SELECT id from orden_dias where dia_contacto='$dia_contacto_sistema'";
$resp_id=mysql_query($sql_id);
$dat_id=mysql_fetch_array($resp_id);
$id_sistema=$dat_id[0];

?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Cobertura Vs Devoluciones</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <style>
    #container {
        width: 80%;
        margin: 20px auto;
    }
    tr th {
        padding: 5px 10px;
    }
    h4 {
        text-align: center;
        font-size: 1.4em;
        font-weight: normal;
    }
    </style>
</head>
<body>
    <div id="container">
        <diw class="row">
            <div class="twelve columns">
                <h2>Reporte sacado para el ciclo: <?php echo $ciclo; ?> y la gesti&oacute;n: <?php echo $nom_gestion; ?></h2>
            </div>
            <div class="twelve columns">
                <h4>Territorio(s): <?php echo $nombre_ciudades; ?></h4>
            </div>
        </diw>
        <din class="row">
            <div class="twelve columns centered">
                <table border='1'>
                    <tr>
                        <th>Visitador</th>
                        <th>Regional</th>
                        <th>% Cobertura Sin Bajas</th>
                        <th>% Cobertura Con Bajas</th>
                        <th>% Muestras Devueltas (Todas las l&iacute;neas)</th>
                        <th>Almac&eacute;n</th>
                    </tr>
                    <?php foreach ($territorios_explode as $n_ciudad) { ?> 
                    <?php 
                    $sql_cod_territorio = mysql_query("SELECT cod_ciudad from ciudades where descripcion = '$n_ciudad' "); 
                    $codigo_ciudad = mysql_result($sql_cod_territorio, 0,0);
                    ?>
                    <?php $sql_funcionarios = mysql_query("SELECT f.codigo_funcionario,CONCAT(f.nombres,' ',f.paterno,' ', f.materno) as nombre from funcionarios f, funcionarios_lineas fl where fl.codigo_funcionario = f.codigo_funcionario and f.cod_cargo = 1011 and f.estado = 1 and cod_ciudad =  $codigo_ciudad and fl.codigo_linea = $linea "); ?>
                    <?php while ($row_funcionarios = mysql_fetch_array($sql_funcionarios)) { ?>
                    <tr>
                        <td><?php echo $row_funcionarios[1]; ?></td>
                        <td align="center"><span style="font-weight:bold;text-align:center"><?php echo $n_ciudad; ?></span></td>
                        <td>
                            <?php 
                            
                            $sql_contactos_ejecutado= mysql_query("SELECT count(rd.cod_contacto) from rutero_detalle rd, rutero r where r.cod_ciclo='$ciclo' and r.codigo_gestion='$gestion' and r.cod_visitador='$row_funcionarios[0]' and r.codigo_linea='$linea' and r.cod_contacto=rd.cod_contacto and rd.estado='1' and rd.categoria_med='A'");
                            $dat_ejecutado=mysql_fetch_array($sql_contactos_ejecutado);
                            $numero_contactos_ejecutadoA=$dat_ejecutado[0];

                            $sql_contactos_maestro= mysql_query("SELECT count(*) from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado r, rutero_maestro_detalle_aprobado rd where rc.cod_rutero=r.cod_rutero and rd.cod_contacto=r.cod_contacto and rc.cod_visitador='$row_funcionarios[0]' and rc.codigo_ciclo='$ciclo' and rc.codigo_gestion='$gestion' and rd.categoria_med='A' and rc.codigo_linea = $linea"); 
                            $dat_maestro=mysql_fetch_array($sql_contactos_maestro);
                            $numero_contactos_maestroA=$dat_maestro[0];

                            $sql_contactos_ejecutado_B=mysql_query("SELECT count(rd.cod_contacto) from rutero_detalle rd, rutero r where r.cod_ciclo='$ciclo' and r.codigo_gestion='$gestion' and r.cod_visitador='$row_funcionarios[0]'and r.codigo_linea='$linea' and r.cod_contacto=rd.cod_contacto and rd.estado='1' and rd.categoria_med='B'");
                            $dat_ejecutado_b=mysql_fetch_array($sql_contactos_ejecutado_B);
                            $numero_contactos_ejecutadoB=$dat_ejecutado_b[0];

                            $sql_contactos_maestro_B= mysql_query("SELECT count(*) from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado r, rutero_maestro_detalle_aprobado rd where rc.cod_rutero=r.cod_rutero and rd.cod_contacto=r.cod_contacto and rc.cod_visitador='$row_funcionarios[0]' and rc.codigo_ciclo='$ciclo' and rc.codigo_gestion='$gestion' and rd.categoria_med='B' and rc.codigo_linea = $linea");
                            $dat_maestrob=mysql_fetch_array($sql_contactos_maestro_B);
                            $numero_contactos_maestroB=$dat_maestrob[0];

                            $sql_contactos_ejecutado_C=mysql_query("SELECT count(rd.cod_contacto) from rutero_detalle rd, rutero r where r.cod_ciclo='$ciclo' and r.codigo_gestion='$gestion' and r.cod_visitador='$row_funcionarios[0]'and r.codigo_linea='$linea' and r.cod_contacto=rd.cod_contacto and rd.estado='1' and rd.categoria_med='C'"); 
                            $dat_ejecutado_c=mysql_fetch_array($sql_contactos_ejecutado_C);
                            $numero_contactos_ejecutadoC=$dat_ejecutado_c[0];

                            $sql_contactos_maestro_C= mysql_query("SELECT count(*) from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado r, rutero_maestro_detalle_aprobado rd where rc.cod_rutero=r.cod_rutero and rd.cod_contacto=r.cod_contacto and rc.cod_visitador='$row_funcionarios[0]' and rc.codigo_ciclo='$ciclo' and rc.codigo_gestion='$gestion' and rd.categoria_med='C' and rc.codigo_linea = $linea");
                            $dat_maestroc=mysql_fetch_array($sql_contactos_maestro_C);
                            $numero_contactos_maestroC=$dat_maestroc[0];

                            $totalEjecutado=$numero_contactos_ejecutadoA+$numero_contactos_ejecutadoB+$numero_contactos_ejecutadoC;
                            $totalContactosMaestro=$numero_contactos_maestroA+$numero_contactos_maestroB+$numero_contactos_maestroC;
                            $totalCobertura=round(($totalEjecutado/$totalContactosMaestro)*100);
                            echo $totalCobertura."%";
                            ?>
                        </td>
                        <td>
                            <?php 
                            $nro_contactos_baja_diasA=0;
                            $nro_contactos_baja_diasB=0;
                            $nro_contactos_baja_diasC=0;

                            $bajaMedA=0;
                            $bajaMedB=0;
                            $bajaMedC=0;
                            $numero_medicos_baja=0;
                            $sql_medicos_baja= mysql_query("SELECT distinct(bm.cod_med), bm.inicio, bm.fin, rd.categoria_med from baja_medicos bm, medicos m, rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd where m.cod_ciudad = '$codigo_ciudad' and bm.codigo_linea in ($linea) and m.cod_med = bm.cod_med and rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and   rc.codigo_gestion=$gestion and rc.codigo_linea in ($linea) and rc.codigo_ciclo=$ciclo and rc.cod_visitador=$row_funcionarios[0] and rd.cod_med=m.cod_med");
                            while($dat_medicos=mysql_fetch_array($sql_medicos_baja)){
                                $codigo_medico=$dat_medicos[0];
                                $inicio_baja=$dat_medicos[1];
                                $fin_baja=$dat_medicos[2];
                                $catMed=$dat_medicos[3];
                                $inicio_baja_real=$inicio_baja[8].$inicio_baja[9]."/".$inicio_baja[5].$inicio_baja[6]."/".$inicio_baja[0].$inicio_baja[1].$inicio_baja[2].$inicio_baja[3];
                                $fin_baja_real=$fin_baja[8].$fin_baja[9]."/".$fin_baja[5].$fin_baja[6]."/".$fin_baja[0].$fin_baja[1].$fin_baja[2].$fin_baja[3];
                                $sql_verifica_medico= mysql_query("SELECT r.dia_contacto from rutero r, rutero_detalle rd where r.cod_contacto=rd.cod_contacto and r.cod_ciclo='$ciclo' and r.codigo_gestion='$gestion' and r.cod_visitador='$row_funcionarios[0]'and r.codigo_linea='$linea' and rd.cod_med='$codigo_medico'");
                                while($dat_verifica=mysql_fetch_array($sql_verifica_medico)){
                                    $dia_contacto_medico=$dat_verifica[0];
                                    for($j=1;$j<=30;$j++){
                                        // echo $dias[$j]."=".$dia_contacto_medico."<br />";
                                        if($dias[$j]==$dia_contacto_medico){
                                            $fecha_baja_contacto=$fechas[$j];
                                            if((compara_fechas($fecha_baja_contacto,$inicio_baja_real)>=0) and (compara_fechas($fecha_baja_contacto,$fin_baja_real)<=0)){
                                                if($catMed=="A"){
                                                    $bajaMedA++;
                                                }
                                                if($catMed=="B"){
                                                    $bajaMedB++;
                                                }
                                                if($catMed=="C"){
                                                    $bajaMedC++;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            // echo $bajaMedA."<br />";
                            $sql_baja_dias= mysql_query("SELECT distinct(b.dia_contacto), b.turno from baja_dias b, baja_dias_detalle bd, baja_dias_detalle_visitador bdv where b.codigo_baja=bd.codigo_baja and bd.codigo_baja=bdv.codigo_baja and b.ciclo='$ciclo' and b.gestion='$gestion' and bd.codigo_linea='$linea'and  bdv.codigo_visitador='$row_funcionarios[0]'");

                            while($dat_baja_dias=mysql_fetch_array($sql_baja_dias)){
                                $dia_contacto_baja=$dat_baja_dias[0];
                                $turno_contacto_baja=$dat_baja_dias[1];
                                $sql_nro_contactos_baja= mysql_query("SELECT count(r.cod_contacto), rd.categoria_med from rutero r, rutero_detalle rd where r.cod_contacto=rd.cod_contacto and r.cod_ciclo='$ciclo' and r.codigo_gestion='$gestion' and r.cod_visitador='$row_funcionarios[0]'and r.dia_contacto='$dia_contacto_baja' and turno='$turno_contacto_baja' and r.codigo_linea='$linea' group by rd.categoria_med"); 
                                // echo "conat: ".("SELECT count(r.cod_contacto), rd.categoria_med from rutero r, rutero_detalle rd where r.cod_contacto=rd.cod_contacto and r.cod_ciclo='$ciclo' and r.codigo_gestion='$gestion' and r.cod_visitador='$row_funcionarios[0]'and r.dia_contacto='$dia_contacto_baja' and turno='$turno_contacto_baja' and r.codigo_linea='$linea' group by rd.categoria_med")."<br />"; 

                                while($dat_contactos_baja=mysql_fetch_array($sql_nro_contactos_baja)){
                                    $numContactos=$dat_contactos_baja[0];
                                    $categoriaMed=$dat_contactos_baja[1];
                                    // if($bajaMedA==''){$bajaMedA=0;}
                                    // if($bajaMedB==''){$bajaMedB=0;}
                                    // if($bajaMedC==''){$bajaMedC=0;}
                                    if($categoriaMed=="A"){
                                        $nro_contactos_baja_diasA=$nro_contactos_baja_diasA+$numContactos;
                                    }
                                    if($categoriaMed=="B"){
                                        $nro_contactos_baja_diasB=$nro_contactos_baja_diasB+$numContactos;
                                    }
                                    if($categoriaMed=="C"){
                                        $nro_contactos_baja_diasC=$nro_contactos_baja_diasC+$numContactos;
                                    }
                                }
                            }

                            $bajaTotalA=$nro_contactos_baja_diasA+$bajaMedA;
                            $bajaTotalB=$nro_contactos_baja_diasB+$bajaMedB;
                            $bajaTotalC=$nro_contactos_baja_diasC+$bajaMedC;
                            $totalBajaMed=$bajaTotalA+$bajaTotalB+$bajaTotalC;
                            $contactos_realesA=$numero_contactos_maestroA-$bajaTotalA;
                            $contactos_realesB=$numero_contactos_maestroB-$bajaTotalB;
                            $contactos_realesC=$numero_contactos_maestroC-$bajaTotalC;
                            $totalContactosReales=$contactos_realesA+$contactos_realesB+$contactos_realesC;
                            $totalCoberturaReal=round(($totalEjecutado/$totalContactosReales)*100);
                            echo $totalCoberturaReal."%";
                            // echo $numero_contactos_maestroA."-".$bajaTotalA."<br />";
                            // echo $numero_contactos_maestroB."-".$bajaTotalB."<br />";
                            // echo $numero_contactos_maestroC."-".$bajaTotalC."<br />";
                            ?>
                        </td>
                        <?php 
                        $sql_ar = mysql_query("SELECT sum(sd.`cantidad_unitaria`) FROM `salida_detalle_visitador` sv, `salida_detalle_almacenes` sd, `muestras_medicas` m, salida_almacenes s WHERE sv.`cod_salida_almacen` = sd.`cod_salida_almacen` AND s.salida_anulada = 0 AND s.cod_salida_almacenes = sv.cod_salida_almacen AND s.cod_salida_almacenes = sd.cod_salida_almacen AND m.`codigo` = sd.`cod_material` AND sv.`codigo_ciclo` = $ciclo AND sv.`codigo_gestion` = $gestion AND sv.`codigo_funcionario` = $row_funcionarios[0]");
                        $cantidad_planificada = mysql_result($sql_ar, 0,0);

                        $sql_codigos_ar = mysql_query("SELECT m.`codigo`FROM `salida_detalle_visitador` sv, `salida_detalle_almacenes` sd, `muestras_medicas` m, salida_almacenes s WHERE sv.`cod_salida_almacen` = sd.`cod_salida_almacen` AND s.salida_anulada = 0 AND s.cod_salida_almacenes = sv.cod_salida_almacen AND s.cod_salida_almacenes = sd.cod_salida_almacen AND m.`codigo` = sd.`cod_material` AND sv.`codigo_ciclo` = $ciclo AND sv.`codigo_gestion` = $gestion AND sv.`codigo_funcionario` = $row_funcionarios[0] GROUP BY sd.`cod_material`");
                        $codigos_ar = '';

                        while ($row_codigo_ar = mysql_fetch_array($sql_codigos_ar)) {
                            $codigos_ar .= "'".$row_codigo_ar[0]."',";
                        }

                        $codigos_ar_final = substr($codigos_ar, 0, -1);
                        $sql_ced = mysql_query("SELECT sum(dd.`cantidad_ing_almacen`) FROM `devoluciones_ciclo` d, `devoluciones_ciclodetalle` dd WHERE d.`codigo_devolucion` = dd.`codigo_devolucion` AND d.`codigo_ciclo` = '$ciclo' AND d.`codigo_gestion` = '$gestion'AND d.`codigo_visitador` = $row_funcionarios[0] and dd.codigo_material in ($codigos_ar_final)");
                        $cantEfecDev = mysql_result($sql_ced, 0, 0);
                        $muestras_devueltas = round(($cantEfecDev/$cantidad_planificada)*100);

                        ?>
                        <td><?php echo $muestras_devueltas."%"; ?></td>
                        <td>
                            <?php 
                            $sql_almacen = mysql_query("SELECT count(*) FROM devoluciones_ciclo where codigo_gestion = $gestion and codigo_ciclo = $ciclo and codigo_visitador = $row_funcionarios[0] "); 
                            $devuelto = mysql_result($sql_almacen, 0, 0);

                            if ($devuelto > 0) {
                                // echo "El visitador ingreso la devoluci&oacute;n.";
                                $sql_devolcuion_mm = mysql_query("SELECT DISTINCT estado_devolucion from devoluciones_ciclo where codigo_gestion = $gestion and codigo_ciclo = $ciclo and codigo_visitador = $row_funcionarios[0]");
                                $estado_devolucion = mysql_result($sql_devolcuion_mm, 0, 0);
                                if ($estado_devolucion == 1) {
                                    echo "Devuelto por el visitador pero, en almacen <span style='color:red; font-weight: bold'> NO </span> se registro todavia los &iacute;tems.";
                                }else{
                                    echo "<span style='color:green; font-weight: bold'>Registrado </span> por el almacenero.";
                                }
                            }else{
                                echo "<span style='color:red; font-weight: bold'>NO </span> se ingreso ninguna devoluci&oacute;n. ";
                            }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php } ?>
                </table>
            </div>
        </din>
    </div>
</body>
</html>