<?php
require("conexion.inc");
require("estilos_reportes_central.inc");
	$global_territorio=$territorio_rpt;
	$bandera=0;
	$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
	$dato=mysql_fetch_array($resp1);
	$nombre_territorio=$dato[1];
    echo "<center><table border='0' class='textotit'><tr><th>Territorio: $nombre_territorio</th></tr></table></center><br>";

    $sql_visitador="select f.codigo_funcionario, f.paterno, f.materno, f.nombres
	from funcionarios f where f.estado=1 and f.cod_ciudad='$rpt_territorio' and f.cod_cargo='1011' order by f.paterno, f.materno";
	$resp_visitador=mysql_query($sql_visitador);
	$indice_tabla=1;
	echo "<center><table border='1' class='texto' width='100%' cellspacing='0'>";
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	$codigo_visitador=$dat_visitador[0];
		$nombre_visitador="$dat_visitador[1] $dat_visitador[2] $dat_visitador[3]";
        echo "<tr><th>$nombre_visitador</th><th>&nbsp;</th><th>Medico</th><th>Especialidad</th><th>Linea</th></tr>";

        $sql_medicos="select distinct(m.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med, c.cod_especialidad, l.nombre_linea
        from medicos m, medico_asignado_visitador ma, categorias_lineas c, lineas l
        where ma.codigo_visitador=$codigo_visitador and m.cod_med=ma.cod_med and l.codigo_linea=ma.codigo_linea and 
		m.cod_med=c.cod_med and ma.codigo_linea=c.codigo_linea order by m.ap_pat_med, m.ap_mat_med";
        $resp_medicos=mysql_query($sql_medicos);
        $indice=0;
        while($dat_medicos=mysql_fetch_array($resp_medicos))
        {   $cod_med=$dat_medicos[0];
            $nombre_med="$dat_medicos[1] $dat_medicos[2] $dat_medicos[3]";
            $cod_espe=$dat_medicos[4];
			$linea=$dat_medicos[5];
            $indice++;
            echo "<tr><td>&nbsp;</td><td>$indice</td><td align='left'>$nombre_med</td><td>$cod_espe</td><td>$linea</td></tr>";
        }
    }
    echo "</table></center>";
?>