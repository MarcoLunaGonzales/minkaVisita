<?php

/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M&eacute;dica
 * * @copyright 2007
 */
require("conexion.inc");
require("estilos_visitador_sincab.inc");
$sql_ciclo       = "SELECT cod_ciclo, codigo_gestion from ciclos where estado = 'Activo' and codigo_linea = '1032'";
$resp_ciclo      = mysql_query($sql_ciclo);
$dat_ciclo       = mysql_fetch_array($resp_ciclo);
$ciclo_activo    = $dat_ciclo[0];
$gestion_activa  = $dat_ciclo[1];
//hasta aqui tenemos el ciclo en $ciclo_activo
$vector       = explode("-", $cod_contacto);
$contacto     = $vector[0];
$orden_visita = $vector[1];
$visitador    = $vector[2];
$fecha_visita = $vector[3];

$sql_nombre_dia  = "SELECT dia_contacto from rutero where cod_contacto = '$contacto'";
$resp_nombre_dia = mysql_query($sql_nombre_dia);
$dat_nombre_dia  = mysql_fetch_array($resp_nombre_dia);
$nombre_de_dia   = $dat_nombre_dia[0];
//formamos los encabezados nombre medico, especialidad turno
$sql               = "SELECT c.turno, m.ap_pat_med, m.ap_mat_med, m.nom_med, dm.direccion, cd.categoria_med, cd.cod_especialidad, cd.orden_visita, c.cod_contacto, cd.estado, m.cod_med from rutero c, rutero_detalle cd, medicos m, direcciones_medicos dm where c.cod_ciclo = '$ciclo_activo' and c.cod_visitador = $global_visitador and m.cod_med = cd.cod_med and cd.orden_visita = $orden_visita and dm.numero_direccion = cd.cod_zona and cd.cod_med = dm.cod_med and c.cod_contacto = '$contacto' and c.cod_contacto = cd.cod_contacto order by c.turno,cd.orden_visita";
// echo $sql;
$resp              = mysql_query($sql);
$dat_enc           = mysql_fetch_array($resp);
$enc_nombre_medico = "$dat_enc[1] $dat_enc[2] $dat_enc[3]";
$enc_turno         = $dat_enc[0];
$enc_categoria     = $dat_enc[5];
$enc_especialidad  = $dat_enc[6];
$cod_medico        = $dat_enc[10];
//fin encabezados


$sql          = "SELECT cod_especialidad, categoria_med, estado from rutero_detalle where cod_contacto = $contacto and orden_visita = $orden_visita";
// echo $sql;
$res          = mysql_query($sql);
$dat          = mysql_fetch_array($res);
$especialidad = $dat[0];
$categoria    = $dat[1];
$estado_pri   = $dat[2];
echo "<center><table border='0' class='textotit'><tr><td align='center'>Registro de Visita M&eacute;dica<br>M&eacute;dico: <strong>$enc_nombre_medico</strong> Especialidad: <strong>$enc_especialidad</strong> Categor&iacute;a: <strong>$enc_categoria</strong><br>Parrillas Disponibles<br><strong>$nombre_de_dia</strong></td></tr></table></center><br>";
//echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><td>Parrillas ya Utilizadas</td><td bgcolor='#646464' width='50%'></td></table></center><br>";
echo "<form method='post' action=''>";
//verificamos si el medicos no pertenece a algun grupo especial y tiene parrillas especiales
$sql_especial       = "SELECT p.codigo_parrilla_especial,p.cod_ciclo,p.cod_especialidad,p.codigo_linea,p.fecha_creacion,p.fecha_modificacion,p.numero_visita,p.agencia,p.codigo_grupo_especial from parrilla_especial p, grupo_especial g, grupo_especial_detalle gd, medicos m where p.codigo_grupo_especial = g.codigo_grupo_especial and p.codigo_linea = '$global_linea' and g.codigo_grupo_especial = gd.codigo_grupo_especial and gd.cod_med = m.cod_med and m.cod_med = '$cod_medico' and p.cod_ciclo = '$ciclo_activo' and g.tipo_grupo<>1";
$resp_especial      = mysql_query($sql_especial);
$num_filas_especial = mysql_num_rows($resp_especial);
//        echo $num_filas_especial;
if ($num_filas_especial != 0) {
    echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
    echo "<tr><th colspan='10'>PARRILLAS ESPECIALES</th></tr>";
    echo "<tr><th>Ciclo</th><th>Especialidad</th><th>N&uacute;mero Visita</th><th>Parrilla Promocional</th><th>&nbsp;</th></tr>";
    while ($dat = mysql_fetch_array($resp_especial)) {
        $cod_parrilla = $dat[0];
        $cod_ciclo = $dat[1];
        $cod_espe = $dat[2];
        //$cod_cat=$dat[3];
        $numero_de_visita = $dat[6];
        $sql1 = "SELECT m.descripcion, m.presentacion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones from muestras_medicas m, parrilla_detalle_especial p, material_apoyo mm where p.codigo_parrilla_especial = $cod_parrilla and m.codigo = p.codigo_muestra and mm.codigo_material = p.codigo_material order by p.prioridad";
        // echo $sql1; 
        $resp1 = mysql_query($sql1);
        $parrilla_medica = "<table class='textomini' width='100%' border='0'>";
        $parrilla_medica = $parrilla_medica . "<tr><th>Producto</th><th>&nbsp</th><th>Material de Apoyo</th></tr>";
        while ($dat1 = mysql_fetch_array($resp1)) {
            $muestra       = "$dat1[0] $dat1[1]";
            $cant_muestra  = $dat1[2];
            $material      = $dat1[3];
            $cant_material = $dat1[4];
            $obs           = $dat1[5];
            if ($obs != "") {
                $observaciones = "<img src='imagenes/informacion.gif' alt='$obs'>";
            } else {
                $observaciones = "&nbsp;";
            }
            $parrilla_medica   = $parrilla_medica . "<tr><td width='40%'>$muestra</td><td align='center'>$observaciones</td><td width='40%'>$material</td></tr>";
        }
        $valor = "$contacto-$orden_visita-$visitador-$fecha_visita";
        $parrilla_medica = $parrilla_medica . "</table>";
        echo "<tr><td align='center' class='texto'>$cod_ciclo</td><td align='center'>$cod_espe</td><td align='center'>$numero_de_visita</td><td align='center'>$parrilla_medica</td><td align='center'><a href='registrar_visita_detalle_espe.php?cod_contacto=$valor&visita=$numero_de_visita&cod_parrilla_espe=$cod_parrilla'>Utilizar >></a></td></tr>";
    }
    echo "</table></center><br>";
} else {
    //aplicamos una consulta para saber si el visitador hace linea de visita para la especialidad
    // $verifica_lineas = "SELECT l.codigo_l_visita from lineas_visita l, lineas_visita_especialidad le, lineas_visita_visitadores lv where l.codigo_l_visita=le.codigo_l_visita and l.codigo_l_visita=lv.codigo_l_visita and le.codigo_l_visita=lv.codigo_l_visita and l.codigo_linea='$global_linea' and lv.codigo_funcionario='$global_visitador' and le.cod_especialidad='$especialidad' and lv.codigo_ciclo = $ciclo_activo";
    $verifica_lineas = "SELECT lv.codigo_l_visita from lineas_visita_visitadores_copy lv, lineas_visita_especialidad le WHERE le.codigo_l_visita = lv.codigo_l_visita and lv.codigo_funcionario = $global_visitador and lv.codigo_gestion = $gestion_activa and lv.codigo_ciclo = $ciclo_activo and lv.codigo_linea_visita = $global_linea and le.cod_especialidad = '$especialidad'";
    
	//echo $verifica_lineas;
    
	$resp_verifica_lineas = mysql_query($verifica_lineas);
    $filas_verifica = mysql_num_rows($resp_verifica_lineas);
    if ($filas_verifica != 0) {
        $dat_verifica = mysql_fetch_array($resp_verifica_lineas);
        $codigo_l_visita = $dat_verifica[0];

        if ($codigo_l_visita == 1014) {
            $codigo_l_visita = 0;
        }

        $sql = "SELECT * from parrilla where codigo_linea = '$global_linea' and cod_ciclo = '$ciclo_activo' and codigo_gestion = '$gestion_activa' and codigo_l_visita = '$codigo_l_visita' and cod_especialidad = '$especialidad' and categoria_med = '$categoria' and agencia = '$global_agencia' order by numero_visita";
    } else {
        //verificamos si no existen parrillas para la agencia del medico
        $sql = "SELECT * from parrilla where codigo_linea = $global_linea and cod_ciclo = '$ciclo_activo' and codigo_gestion = '$gestion_activa' and codigo_l_visita = '0' and cod_especialidad = '$especialidad' and categoria_med = '$categoria' and agencia = '$global_agencia' order by numero_visita";
        $resp_aux = mysql_query($sql);
        $num_filas = mysql_num_rows($resp_aux);
        if ($num_filas == 0) {
            $sql = "SELECT * from parrilla where codigo_linea = $global_linea and cod_ciclo = '$ciclo_activo' and codigo_gestion = '$gestion_activa' and codigo_l_visita = '0' and cod_especialidad = '$especialidad' and categoria_med = '$categoria' and agencia = 0 order by numero_visita";
        }
    }

    // echo $sql;
    $resp = mysql_query($sql);

    $num_filas = mysql_num_rows($resp);
    if ($num_filas == 4) {
        $sql_dia_contacto = "SELECT o.id, r.dia_contacto from rutero r, orden_dias o where r.dia_contacto = o.dia_contacto and r.cod_contacto = '$contacto'";
        $resp_dia_contacto = mysql_query($sql_dia_contacto);
        $dat_dia_contacto = mysql_fetch_array($resp_dia_contacto);
        $id_dia = $dat_dia_contacto[0];
        if ($id_dia <= 5) {
            $dia_contacto_medico = 1;
        }
        if ($id_dia >= 6 and $id_dia <= 10) {
            $dia_contacto_medico = 2;
        }
        if ($id_dia >= 11 and $id_dia <= 15) {
            $dia_contacto_medico = 3;
        }
        if ($id_dia >= 16 and $id_dia <= 20) {
            $dia_contacto_medico = 4;
        }
    }
    echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
    echo "<tr><th colspan='6'>PARRILLAS PROMOCIONALES</th></tr>";
    echo "<tr><th>Ciclo</th><th>Especialidad</th><th>Categor&iacute;a</th><th>N&uacute;mero Visita</th><th>Parrilla Promocional</th><th>&nbsp;</th></tr>";
    $indice = 1;
    while ($dat = mysql_fetch_array($resp)) {
        $cod_parrilla = $dat[0];
        $cod_ciclo = $dat[1];
        $cod_espe = $dat[2];
        $cod_cat = $dat[3];
        $numero_de_visita = $dat[7];
        $agencia = $dat[8];
        $estado_aprobacion = $dat[12];
        $sql1 = "SELECT m.descripcion, m.presentacion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones from muestras_medicas m, parrilla_detalle p, material_apoyo mm where p.codigo_parrilla = $cod_parrilla and m.codigo = p.codigo_muestra and mm.codigo_material = p.codigo_material order by p.prioridad";

//				echo $sql1;

        $resp1 = mysql_query($sql1);
        $parrilla_medica = "<table class='textomini' width='100%' border='0'>";
        $parrilla_medica = $parrilla_medica . "<tr><th>Producto</th><th>&nbsp</th><th>Material de Apoyo</th></tr>";
        while ($dat1 = mysql_fetch_array($resp1)) {
            $muestra = "$dat1[0] $dat1[1]";
            $cant_muestra = $dat1[2];
            $material = $dat1[3];
            $cant_material = $dat1[4];
            $obs = $dat1[5];
            if ($obs != "") {
                $observaciones = "<img src='imagenes/informacion.gif' alt='$obs'>";
            } else {
                $observaciones = "&nbsp;";
            }
            $parrilla_medica = $parrilla_medica . "<tr><td width='50%'>$muestra</td><td align='center'>$observaciones</td><td width='40%'>$material</td></tr>";
        }
        $valor = "$contacto-$orden_visita-$visitador-$fecha_visita-$agencia";
        $parrilla_medica = $parrilla_medica . "</table>";
        /* $sql_parrilla_utilizada="SELECT DISTINCT(rv.cod_contacto) from registro_visita rv, rutero r, rutero_detalle rd where rv.cod_contacto=r.cod_contacto and r.cod_contacto=rd.cod_contacto and codigo_parrilla='$cod_parrilla' AND rd.cod_med='$cod_medico'"; //echo $sql_parrilla_utilizada; 
        $resp_parrilla_utilizada=mysql_query($sql_parrilla_utilizada);
        $filas_parrilla_utilizada=mysql_num_rows($resp_parrilla_utilizada); */
        if ($filas_parrilla_utilizada != 0) { 
            $color_fondo = "";
        } else {
            $color_fondo = "";
        }
        if($estado_aprobacion == 1){
            $estado_fila = "green";
            $cade = "Utilizar >>";
        }else{
            $estado_fila = "";
            $cade = "<a href='registrar_visita_detalle.php?cod_contacto=$valor&visita=$numero_de_visita&codigo_parrilla=$cod_parrilla'>Utilizar >></a>";
        }
        if ($indice != $dia_contacto_medico and $num_filas == 4) {

            echo "<tr bgcolor='$estado_fila'><td align='center' class='texto'>$cod_ciclo</td><td align='center'>$cod_espe</td><td align='center'>$cod_cat</td><td align='center'><h3>$numero_de_visita</h3></td><td align='center'>$parrilla_medica</td><td align='center'>$cade</td></tr>";
        } else {
            echo "<tr bgcolor='$estado_fila'><td align='center' class='texto'>$cod_ciclo</td><td align='center'>$cod_espe</td><td align='center'>$cod_cat</td><td align='center'><h3>$numero_de_visita</h3></td><td align='center'>$parrilla_medica</td><td align='center'>$cade</td></tr>";
        }
        $indice++;
    }
}
echo "</table></center><br>";
echo "</form>";
?>