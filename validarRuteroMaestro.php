<?php

require("conexion.inc");
require("estilos_visitador.inc");
require("validarRuteroReglas.php");

$visitador = $visitador;
$codRutero = $cod_rutero;
$global_linea = $global_linea;
$rpt_territorio = $global_agencia;

// echo $visitador. " ".$codRutero. " ".$global_linea. " ".$rpt_territorio;

$sql_pr =  mysql_query("SELECT codigo_ciclo, codigo_gestion from rutero_maestro_cab where cod_rutero = $codRutero");
$cilcooo = mysql_result($sql_pr, 0,0);

//$sql_gestion = mysql_query("SELECT codigo_gestion from gestiones where estado = 'Activo'");
$codigo_gestion = mysql_result($sql_pr, 0, 1);

echo "<h1>Observaciones en el envio a Aprobacion de Rutero Maestro</h1><br>";

$sql_med_vis = "SELECT distinct(rd.cod_med), rd.cod_especialidad, rd.categoria_med from rutero_maestro_cab rc, 
	rutero_maestro r, rutero_maestro_detalle rd where rc.cod_rutero=r.cod_rutero and r.cod_contacto=rd.cod_contacto and 
	rc.cod_rutero=$codRutero and rc.cod_visitador=r.cod_visitador and r.cod_visitador=rd.cod_visitador order by rd.categoria_med, rd.cod_especialidad";

// echo $sql_med_vis."<br />";

$resp_med_vis = mysql_query($sql_med_vis);
$nroRegs = mysql_num_rows($resp_med_vis);
if ($nroRegs != 0) {
    echo "<center><table class='texto'>";
    echo "<tr><th colspan='6'>Validacion de Contactos de Acuerdo a Grilla Vigente</th></tr>";
    echo "<tr><th colspan='6'>$nombre_visitador $nombre_linea</th></tr>";
    echo "<tr><th>Medico</th><th>Espe.</th><th>Cat.</th><th>Contactos<br>Grilla</th><th>Contactos<br>Rutero Maestro</th><th>Dias de Contacto</th></tr>";

    $bandera = 0;

    while ($datos_med_vis = mysql_fetch_array($resp_med_vis)) {
        $cod_med = $datos_med_vis[0];
        $espe_med = $datos_med_vis[1];
        $cat_med = $datos_med_vis[2];


        $sql_cuenta_med = "SELECT count(rd.cod_med) from rutero_maestro_detalle rd, rutero_maestro r WHERE r.cod_rutero='$codRutero' AND r.cod_visitador='$visitador' AND r.cod_contacto=rd.cod_contacto and rd.cod_med='$cod_med' and rd.cod_especialidad='$espe_med' and rd.categoria_med='$cat_med'";
        $resp_cuenta_med = mysql_query($sql_cuenta_med);
        $datos_cuenta_med = mysql_fetch_array($resp_cuenta_med);
        $num_veces_medico = $datos_cuenta_med[0];
        $sql_grilla = "SELECT gd.cod_especialidad, gd.cod_categoria, gd.frecuencia from grilla g, grilla_detalle gd where 
		g.codigo_grilla=gd.codigo_grilla and gd.cod_especialidad='$espe_med' and gd.cod_categoria='$cat_med' and 
		g.codigo_linea='$global_linea' and g.agencia='$rpt_territorio' and g.estado='1'";
        //echo $sql_grilla."<br />";
        $resp_grilla = mysql_query($sql_grilla);
        $dat_grilla = mysql_fetch_array($resp_grilla);
        $frec_grilla = $dat_grilla[2];

        $cod_medico_inf = $cod_med;
        $sql_nom_med = mysql_query("SELECT ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$cod_medico_inf'");
        $dat_nom_med = mysql_fetch_array($sql_nom_med);
        $nombre_medico = "$dat_nom_med[0] $dat_nom_med[1] $dat_nom_med[2]";
        $sql_dias_contacto = "SELECT r.dia_contacto, r.turno from rutero_maestro r, rutero_maestro_detalle rd where r.cod_contacto=rd.cod_contacto and r.cod_rutero='$codRutero'and rd.cod_med='$cod_medico_inf'";
        $resp_dias_contacto = mysql_query($sql_dias_contacto);
        $cadena_dias = "";
        while ($dat_dias_contacto = mysql_fetch_array($resp_dias_contacto)) {
            $dia_contacto = $dat_dias_contacto[0];
            $turno_contacto = $dat_dias_contacto[1];
            $cadena_dias = "$cadena_dias $dia_contacto $turno_contacto - ";
        }
        // echo $frec_grilla."<br />";
		$colorFondo = "#FFFFFF";
        if ($frec_grilla == "" || $frec_grilla == 0) {
            $colorFondo = "#FF0000";
			$frec_grilla = "Especialidad No Permitida en la Linea";
			$bandera=1;
        }
        if ($frec_grilla < $num_veces_medico) {
            //$colorFondo = "#FFFFFF";
            echo "<tr bgcolor='$colorFondo'><td>$nombre_medico</td><td>$espe_med</td><td align='center'>$cat_med</td><td align='center'>$frec_grilla</td><td align='center'>$num_veces_medico</td><td>$cadena_dias</td></tr>";
            $bandera = 1;
        }
        if ($frec_grilla > $num_veces_medico) {
            //$colorFondo = "#FFFFFF";
            echo "<tr bgcolor='$colorFondo'><td>$nombre_medico</td><td>$espe_med</td><td align='center'>$cat_med</td><td align='center'>$frec_grilla</td><td align='center'>$num_veces_medico</td><td>$cadena_dias</td></tr>";
            $bandera = 1;
        }
    }
    echo "</table><br>";

    /* Validacion para las lineas, no permitir que esten vacias. */

    $count_result = 0;

    if($count_result == 0){
        $sqlVeriLineas = "SELECT * from `funcionarios_lineas` f, lineas l where f.`codigo_funcionario`=$visitador and l.`estado`=1 and f.`codigo_linea`=l.`codigo_linea`";
        //echo $sqlVeriLineas;
		$respVeriLineas = mysql_query($sqlVeriLineas);

        $numFilasVeri = mysql_num_rows($respVeriLineas);

        if ($numFilasVeri >= 1) {
            $bandera1 = validaRuteroReglas($visitador, $codRutero);
        }

        //$bandera2=validaRuteroReglasConjunto($visitador, $ciclo_global,$codigo_gestion,$global_linea);
		//SACAMOS LAS VALIDACIONES PARA TODOS
		//$bandera=0;
		//$bandera1=0;
        
		
		if (($bandera == 0 && $bandera1 == 0)) {
            $sql_pre = "UPDATE rutero_maestro_cab set estado_aprobado='0' where cod_visitador='$visitador' and codigo_linea='$global_linea'";
            $resp_pre = mysql_query($sql_pre);
            $sql_aprueba = "UPDATE rutero_maestro_cab set estado_aprobado='2' where cod_visitador='$visitador' and cod_rutero='$cod_rutero'";
            $resp_aprueba = mysql_query($sql_aprueba);
            echo "
            <script language='JavaScript'>
            alert('El rutero se aprobo satisfactoriamente.');
            location.href='navegador_rutero_maestro.php';
            </script>";
        }
        if ($bandera == 1) {
            echo "<script language='JavaScript'>
                 //history.back();
            </script>";
        }

    }else{
        echo "<script language='JavaScript'>
        alert('No puede enviar a aprobacion. El usuario no esta asignado a las especialidades: ".$cad_result.".');
        </script>";
    }
    
} else {
    echo "<script language='JavaScript'>
    alert('No puede enviar a aprobacion un rutero vacio.');
    history.back();
    </script>";
}
?>