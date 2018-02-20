<script language='JavaScript'>
    function totales(idtable){
        var main=document.getElementById(idtable);   
        var numFilas=main.rows.length;
        var numCols=main.rows[1].cells.length;
	 
        for(var j=1; j<=numCols-1; j++){
            var subtotal=0;
            for(var i=2; i<=numFilas-2; i++){
                //var dato=(main.rows[i].cells[j].innerHTML==" ")?"&nbsp;":main.rows[i].cells[j].innerHTML;
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
<?php

function imprimeRuteroMaestro($codigoGestion, $codigoCiclo, $tipoRuteroRpt, $visitador, $idtable,$zonas) {
    require('conexion.inc');
//nombrevisitador
//
    $sqlNombreVis = "select concat(f.paterno, ' ',f.nombres), c.descripcion from 
							funcionarios f, ciudades c where f.codigo_funcionario='$visitador' and 
							f.cod_ciudad=c.cod_ciudad";
    $respNombreVis = mysql_query($sqlNombreVis);
    $nombreVisitador = mysql_result($respNombreVis, 0, 0);
    $nombreTerritorio = mysql_result($respNombreVis, 0, 1);

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

//PROBANDO DE AQUI
    echo "<table class='texto' border='1' cellspacing='0' align='center'width='90%' id='$idtable'>";

    $sqlRuteros = "select r.`cod_rutero`, r.`codigo_linea`, l.`nombre_linea` from $tabla1 r, lineas l, funcionarios_lineas fl
		where r.`codigo_linea`=l.`codigo_linea` and r.cod_visitador = fl.codigo_funcionario and r.codigo_linea = fl.codigo_linea and r.`codigo_ciclo` = $codigoCiclo and r.`codigo_gestion` = $codigoGestion and 
		r.`cod_visitador` = $visitador";
//    echo $sqlRuteros;

    $respRuteros = mysql_query($sqlRuteros);
    $n = mysql_num_rows($respRuteros);
    echo "<th></th>";
    while ($datRuteros = mysql_fetch_array($respRuteros)) {
        $rutero_maestro = $datRuteros[0];
        $codigo_linea = $datRuteros[1];
        $nombre_linea = $datRuteros[2];

        /* ------------------------------------------Visitador por Linea----------------------------------------------------------- */

        $sql_linea = mysql_query(" SELECT a.nombre_l_visita, c.cod_especialidad from lineas_visita a , lineas_visita_visitadores b, lineas_visita_especialidad c 
        where a.codigo_l_visita = b.codigo_l_visita and b.codigo_l_visita = c.codigo_l_visita and b.codigo_funcionario = $visitador and a.codigo_linea = $codigo_linea and b.codigo_ciclo = $codigoCiclo and b.codigo_gestion = $codigoGestion");
        echo(" SELECT a.nombre_l_visita, c.cod_especialidad from lineas_visita a , lineas_visita_visitadores b, lineas_visita_especialidad c 
        where a.codigo_l_visita = b.codigo_l_visita and b.codigo_l_visita = c.codigo_l_visita and b.codigo_funcionario = $visitador and a.codigo_linea = $codigo_linea and b.codigo_ciclo = $codigoCiclo and b.codigo_gestion = $codigoGestion")."<br />";
//        echo(" SELECT a.nombre_l_visita, c.cod_especialidad from lineas_visita a , lineas_visita_visitadores b, lineas_visita_especialidad c 
//        where a.codigo_l_visita = b.codigo_l_visita and b.codigo_l_visita = c.codigo_l_visita and b.codigo_funcionario = $visitador and a.codigo_linea = $codigo_linea and b.codigo_ciclo = $codigoCiclo and b.codigo_gestion = $codigoGestion");

        $num_lineas_f .= mysql_num_rows($sql_linea) . ",";
        $num_lineas = mysql_num_rows($sql_linea);
        if ($num_lineas != 0) {
            while ($row_linea = mysql_fetch_assoc($sql_linea)) {
                $linea_prob_b .= $row_linea['cod_especialidad'] . ",";
                $nom_li_b .= $row_linea['nombre_l_visita'] . ",";
            }
            $linea_prob_b = explode(",", substr($linea_prob_b, 0, -1));
            $nom_li_b = explode(",", substr($nom_li_b, 0, -1));
            $fi = array_combine($linea_prob_b, $nom_li_b);
        }
        /* ------------------------------------------FIN Visitador por Linea----------------------------------------------------- */


        $sql = "select r.nombre_rutero, l.nombre_linea from $tabla1 r, lineas l
	where r.codigo_linea=l.codigo_linea and r.cod_rutero=$rutero_maestro";
        $resp = mysql_query($sql);
        $dat = mysql_fetch_array($resp);
        $nom_rutero = $dat[0];
        $nom_linea = $dat[1];
        echo "<th colspan='9'>$nom_linea $nombreVisitador $nombreTerritorio</th>";
    }
    echo "<th>&nbsp;</th></tr>";
    echo "<tr><th>Especialidad</th>";
//    echo "<th>L&iacute;nea</th>";
    for ($i = 0; $i < $n; $i++) {
        echo "<th>L&iacute;nea</th><th>Cat A</th><th>Cat B</th><th>Cat C</th><th>Total M&eacute;dicos</th><th>Cont. A</th><th>Cont. B</th><th>Cont. C</th><th>Sub-total<br>Contactos</th>";
    }
    echo "<th>TOTAL<BR>CONTACTOS</th></tr>";
    $sql_especialidad = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
    $resp_especialidad = mysql_query($sql_especialidad);
    $numero_total_medicos = 0;
    while ($dat_espe = mysql_fetch_array($resp_especialidad)) {
        $cod_especialidad = $dat_espe[0];
        $desc_especialidad = $dat_espe[1];
        //aqui sacamos para cada rutero maestro
        $numero_total_contactos = 0;
        $cadena_mostrar = "";
        $cadena_mostrar.="<tr><td>$desc_especialidad</td>";

        $bandera_mostrar = 0;
        $total_gral_contactos = 0;

        $respRuteros = mysql_query($sqlRuteros);
        while ($datRuteros = mysql_fetch_array($respRuteros)) {
            $rutero_maestro = $datRuteros[0];
            $linea_ruteromaestro = $datRuteros[1];
            $sql_medicos = "select DISTINCT ( rmd.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med, rmd.categoria_med
			from $tabla1 rmc, $tabla2 rm, $tabla3 rmd, medicos m, direcciones_medicos dm
			where rmc.cod_rutero=rm.cod_rutero and rmc.cod_rutero='$rutero_maestro' and 
			rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.codigo_linea='$linea_ruteromaestro' 
			and rmc.cod_visitador='$visitador' and rmd.cod_visitador='$visitador' and 
			rmd.cod_especialidad='$cod_especialidad' and m.cod_med = dm.cod_med and dm.cod_zona in ($zonas) order by m.ap_pat_med, m.ap_mat_med, m.nom_med";

//            echo $sql_medicos."; <br />";

            $resp_medicos = mysql_query($sql_medicos);
            $num_filas = mysql_num_rows($resp_medicos);
            $numero_total_medicos = $numero_total_medicos + $num_filas;
            $numero_a = 0;
            $numero_b = 0;
            $numero_c = 0;
            $cant_contactos_a = 0;
            $cant_contactos_b = 0;
            $cant_contactos_c = 0;
            while ($dat_medicos = mysql_fetch_array($resp_medicos)) {

                $cod_med = $dat_medicos[0];
                $sql_cant_contactos = "select rmd.cod_med
			from $tabla1 rmc, $tabla2 rm, $tabla3 rmd, medicos m
			where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and 
			m.cod_med=rmd.cod_med and rmc.cod_rutero='$rutero_maestro' and rmc.codigo_linea='$linea_ruteromaestro' 
			and rmc.cod_visitador='$visitador' and rmd.cod_especialidad='$cod_especialidad' and 
			rmd.cod_med='$cod_med'";
//                echo $sql_cant_contactos."; <br />";
                $resp_cant_contactos = mysql_query($sql_cant_contactos);
                $num_contactos = mysql_num_rows($resp_cant_contactos);

//                echo "$cod_med $num_contactos<br>";
                $categoria_med = $dat_medicos[4];
                if ($categoria_med == "A") {
                    $numero_a++;
                    $cant_contactos_a = $cant_contactos_a + $num_contactos;
                }
                if ($categoria_med == "B") {
                    $numero_b++;
                    $cant_contactos_b = $cant_contactos_b + $num_contactos;
                }
                if ($categoria_med == "C") {
                    $numero_c++;
                    $cant_contactos_c = $cant_contactos_c + $num_contactos;
                }
            }
            if ($numero_a == "") {
                $numero_a = 0;
            }
            if ($numero_b == "") {
                $numero_b = 0;
            }
            if ($numero_c == "") {
                $numero_c = 0;
            }
            if ($num_filas != 0) {
                $total_medicos_espe = $numero_a + $numero_b + $numero_c;
                $total_contactos = $cant_contactos_a + $cant_contactos_b + $cant_contactos_c;
                $numero_total_contactos = $numero_total_contactos + $total_contactos;
                /* -------  LINEAS------------- */
                if ($linea_ruteromaestro == 1021) {
                    $cadena_mostrar.="<td align='center'>";
                    foreach ($fi as $a => $b) {
                        if ($cod_especialidad == $a) {
                            $cadena_mostrar .= $b;
                        } else {
                            $cadena_mostrar .= "&nbsp;";
                        }
                    }
                    $cadena_mostrar .= "</td>";
                }else{
                    $cadena_mostrar .= "<td align='center'> &nbsp;</td> ";
                }
                /* -------  Fin LINEAS------- */
                $cadena_mostrar .="<td align='center'>$numero_a</td><td align='center'>$numero_b</td><td align='center'>$numero_c</td>
                <td align='center'>$total_medicos_espe</td><td align='center'>$cant_contactos_a</td><td align='center'>$cant_contactos_b</td>
                <td align='center'>$cant_contactos_c</td><td align='center' class='subtotal'>$total_contactos</td>";
                $bandera_mostrar = 1;
                $total_gral_contactos = $total_gral_contactos + $total_contactos;
            } else { //muestra vacios
                $cadena_mostrar.="<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td class='subtotal'>&nbsp;</td>";
            }
        }
        $cadena_mostrar.="<td align='center' class='total'>$total_gral_contactos</td></tr>";
        if ($bandera_mostrar == 1) {
            echo $cadena_mostrar;
        }
    }
    echo "<tr><th>Totales</th></tr>";
    echo "</table>";
    echo "<script language='JavaScript'>totales($idtable);</script>";
}
?>