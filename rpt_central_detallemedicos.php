<?php
require("conexion.inc");
require("estilos_reportes.inc");
	$global_territorio=$territorio_rpt;
	$rpt_linea=$rpt_linea;
	$rpt_nombreLinea=$rpt_nombreLinea;
	$bandera=0;
	$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
	$dato=mysql_fetch_array($resp1);
	$nombre_territorio=$dato[1];
    echo "<center><table border='0' class='textotit'><tr><th>Detalle de Medicos en Rutero Maestro<br>Territorio: $nombre_territorio Linea: $rpt_nombreLinea</th></tr></table></center><br>";

  $sql_visitador="select f.codigo_funcionario, f.paterno, f.materno, f.nombres
	from funcionarios f where f.estado=1 and f.cod_ciudad='$rpt_territorio' and f.codigo_funcionario in ($visitador)
	and f.cod_cargo='1011' order by f.paterno, f.materno";
	$resp_visitador=mysql_query($sql_visitador);
	$indice_tabla=1;
	echo "<center><table border='1' class='texto' width='100%' cellspacing='0'>";
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	$codigo_visitador=$dat_visitador[0];
		$nombre_visitador="$dat_visitador[1] $dat_visitador[2] $dat_visitador[3]";
        echo "<tr><th>$nombre_visitador</th><th>&nbsp;</th>
        <th>Linea</th><th>Medico</th><th>Especialidad</th><th>Categoria</th><th>Nro. Contactos</th></tr>";

        $sql_medicos="select count(m.`cod_med`) as nro_contactos, m.cod_med, m.ap_pat_med, m.ap_mat_med, 
        m.nom_med, rmd.cod_especialidad, rmd.categoria_med, l.`nombre_linea` from medicos m, 
        rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd, lineas l 
        where rmc.cod_visitador = $codigo_visitador and rmc.estado_aprobado = 1 and rmc.cod_rutero = rm.cod_rutero and 
        rm.cod_contacto = rmd.cod_contacto and rmd.cod_med = m.cod_med and rmc.codigo_linea in ($rpt_linea) and 
        rmc.`codigo_linea`=l.`codigo_linea` group by l.nombre_linea, m.cod_med 
        order by l.nombre_linea, m.ap_pat_med"; 
        
        $resp_medicos=mysql_query($sql_medicos);
        $indice=0;
        $lineaPivote=mysql_result($resp_medicos,0,7);
        $sumaPivote=0;
        $sumaContactos=0;
        while($dat_medicos=mysql_fetch_array($resp_medicos))
        {   $nroContactos=$dat_medicos[0];
        		$cod_med=$dat_medicos[1];
            $nombre_med="$dat_medicos[2] $dat_medicos[3] $dat_medicos[4]";
            $cod_espe=$dat_medicos[5];
            $cat_med=$dat_medicos[6];
            $nombreLinea=$dat_medicos[7];
            $indice++;
            $sumaPivote=$sumaPivote+$nroContactos;
            $sumaContactos=$sumaContactos+$nroContactos;
         		if($lineaPivote!=$nombreLinea){
         				echo "<tr><td>&nbsp;</td><td>&nbsp;</td><th>Subtotal $lineaPivote</th>
            		<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><th>$sumaPivote</th>
         				</tr>";
         				$lineaPivote=$nombreLinea;
         				$sumaPivote=0;
         		}

            echo "<tr><td>&nbsp;</td><td>$indice</td><td align='left'>$nombreLinea</td>
            <td align='left'>$nombre_med</td><td>$cod_espe</td><td>$cat_med</td><td align='center'>$nroContactos</td>
         		</tr>";
        }
         				echo "<tr><td>&nbsp;</td><td>&nbsp;</td><th>Subtotal $lineaPivote</th>
            		<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><th>$sumaPivote</th>
         				</tr>";

         				echo "<tr><td>&nbsp;</td><td>&nbsp;</td><th>Total Contactos</th>
            		<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><th>$sumaContactos</th>
         				</tr>";

    }
    echo "</table></center>";
?>