<?php

require("conexion.inc");
require("estilos_inicio_adm.inc");
set_time_limit(0);

$url = $_GET['gestionCicloRpt'];
$url1 = explode("|", $url);
$codCiclo = $url1[0];
$codGestion = $url1[1];
$rpt_territorio=$_GET['rpt_territorio'];
//$codLinea = $_GET['linea_rpt'];

echo "<h1>Reporte de Verificacion De Distribucion Personalizada<br>Ciclo: $codCiclo Gestion: $codGestion</h1>";


echo "<center><table class='texto1' border='1'>";
/*$sqlLineas = "select l.codigo_linea, l.nombre_linea from lineas l, configuracion_parrilla_personalizada2 c  
			where l.linea_promocion=1 and l.codigo_linea=c.codigo_linea";*/

$sqlLineas = "select l.codigo_linea, l.nombre_linea from lineas l  
			where l.linea_promocion=1";
$respLineas = mysql_query($sqlLineas);
echo "<tr><td>Cod Med</td><td>Medico</td><td>Esp.</td><td>Categoria</td><td>Contactos Rutero</td><td>Nro. Parrillas</td><td>Observaci&oacute;n</td><td>Detalles</td></tr>";
while ($datLineas = mysql_fetch_array($respLineas)) {
    $codLinea = $datLineas[0];
    $nombreLinea = $datLineas[1];
//    echo "<tr><td colspan='7'><span style='color:#5858FA; background-color:#F3F781'>$nombreLinea</span></td></tr>";

    $codCiudadVeri=$rpt_territorio;
	$sqlVis = "select f.codigo_funcionario, CONCAT(f.paterno, ' ', f.nombres) as visitador,
       (select c.descripcion from ciudades c where c.cod_ciudad = f.cod_ciudad) as ciudad, f.cod_ciudad 
	   from funcionarios f, funcionarios_lineas fl where f.codigo_funcionario = fl.codigo_funcionario and
       fl.codigo_linea = $codLinea and f.estado=1 and f.cod_ciudad<>115 and f.cod_cargo=1011 
		and  f.cod_ciudad in ($codCiudadVeri)
	   order by ciudad, visitador";
	
	// and  f.cod_ciudad in (113, 114)
	    
	/*$sqlVis = "select f.codigo_funcionario, CONCAT(f.paterno, ' ', f.nombres) as visitador,
       (select c.descripcion from ciudades c where c.cod_ciudad = f.cod_ciudad) as ciudad, f.cod_ciudad 
	   from funcionarios f, funcionarios_lineas fl where f.codigo_funcionario = fl.codigo_funcionario and
       fl.codigo_linea = $codLinea and f.estado=1 and f.cod_ciudad = 102 and f.cod_cargo=1011 order by ciudad, visitador*/
    $respVis = mysql_query($sqlVis);
    while ($datVis = mysql_fetch_array($respVis)) {
        $codVisitador = $datVis[0];
        $nombreVisitador = $datVis[1];
        $ciudadVisitador = $datVis[2];
        $codCiudad = $datVis[3];

        $sqlRutero = "select rd.cod_med, count(*) as contactos, rd.cod_especialidad, rd.categoria_med, 
			(select concat(m.ap_pat_med,' ',m.ap_mat_med,' ', m.nom_med) from medicos m where m.cod_med=rd.cod_med) as medico
			from rutero_maestro_cab_aprobado rc, 
			rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd
			where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rc.cod_visitador=rm.cod_visitador and 
			rm.cod_visitador=rd.cod_visitador and 
			rc.cod_visitador=$codVisitador and rc.codigo_ciclo=$codCiclo and rc.codigo_gestion=$codGestion and 
			rc.codigo_linea=$codLinea and rc.estado_aprobado=1 group by rd.cod_med order by rd.cod_especialidad, rd.categoria_med, medico";
        $respRutero = mysql_query($sqlRutero);
        $numFilasRutero = mysql_num_rows($respRutero);
        if ($numFilasRutero == 0) {
            $obs = "<img src='imagenes/no.png'>";
            echo "<tr><td colspan='7'><span style='color:#5858FA; background-color:#F3F781'>$ciudadVisitador------$codVisitador-------$nombreVisitador---------$nombreLinea</span></td></tr>";

            echo "<tr><td colspan='6'><span style='color:#ff0000;font-size=40px'>EL FUNCIONARIO NO TIENE RUTERO APROBADO PARA EL CICLO Y GESTION!!!!!!!!!!</span></td><td>$obs</td><td>Linea</td></tr>";
        } else {
            while ($datRutero = mysql_fetch_array($respRutero)) {
                $codMed = $datRutero[0];
                $nroContactos = $datRutero[1];
                $codEspecialidadMed = $datRutero[2];
                $categoriaMed = $datRutero[3];
                $nombreMed = $datRutero[4];

                /*$sqlParrilla = "select count(*) from parrilla p 
					where p.agencia=$codCiudad and p.cod_ciclo=$codCiclo and p.codigo_gestion=$codGestion and  
					p.cod_especialidad='$codEspecialidadMed' and p.categoria_med='$categoriaMed' 
					and p.codigo_l_visita=$codLineaVisita and p.codigo_linea=$codLinea";*/

				$sqlParrilla="select * from parrilla_personalizada p where p.cod_linea='$codLinea' and  
				 p.cod_gestion='$codGestion' and p.cod_ciclo='$codCiclo' and p.cod_med='$codMed' group by  
				 cod_gestion, cod_ciclo, cod_linea, cod_med, numero_visita";
                $respParrilla = mysql_query($sqlParrilla);
                $nroParrillas = mysql_num_rows($respParrilla);
				
				
                $obs = "";
                
				if ($nroParrillas == $nroContactos) {
                    $obs = "<img src='imagenes/si.gif'>";
					//echo "<tr><td>$codMed</td><td>$nombreMed</td><td>$codEspecialidadMed</td><td>$categoriaMed</td>
					//<td>$nroContactos</td><td>$nroParrillas</td><td>$obs</td><td>$nombreLineaVisita $nombreVisitador $nombreLinea $ciudadVisitador</td></tr>";
                }
                
				if ($nroParrillas < $nroContactos && $nroParrillas>0) {
                    //$obs = "<img src='imagenes/pendiente.png'>";
                     echo "<tr><td>$codMed</td><td>$nombreMed</td><td>$codEspecialidadMed</td><td>$categoriaMed</td>
					 <td>$nroContactos</td><td>$nroParrillas</td><td>$obs $nombreVisitador  $nombreLinea $ciudadVisitador</td></tr>";
					
					for($ii=$nroParrillas+1; $ii<=$nroContactos; $ii++){
						
						$sqlInsert="insert into parrilla_personalizada (cod_gestion, cod_ciclo, cod_linea, cod_med, numero_visita, 
							orden_visita, cod_mm, cantidad_mm, 
							cod_ma, cantidad_ma) select cod_gestion, cod_ciclo, cod_linea, cod_med, $ii, orden_visita, cod_mm, cantidad_mm, 
							cod_ma, cantidad_ma from parrilla_personalizada where cod_gestion=$codGestion and cod_ciclo=$codCiclo 
							and cod_med=$codMed and cod_linea=$codLinea and numero_visita=$nroParrillas";
						//echo $sqlInsert;
						$respInsert=mysql_query($sqlInsert);
					}
                }
                if ($nroParrillas > $nroContactos) {
                    //$obs = "<img src='imagenes/pendiente.png'>";
                    $visitaIni=$nroContactos+1;
					$visitaFin=$nroParrillas;
					$sqlDel="delete from parrilla_personalizada where cod_med='$codMed' and cod_linea='$codLinea' and 
					cod_gestion='$codGestion' and cod_ciclo='$codCiclo' and numero_visita between '$visitaIni' and '$visitaFin'";
					
					$respDel=mysql_query($sqlDel);
					echo "<tr><td>$codMed</td><td>$nombreMed</td><td>$codEspecialidadMed</td><td>$categoriaMed</td>
					 <td>$nroContactos</td><td>$nroParrillas</td><td>$obs $nombreLineaVisita $nombreVisitador $nombreLinea $ciudadVisitador</td></tr>";
					
				
				}
                if ($nroParrillas == 0) {
                    //$obs = "<img src='imagenes/no.png'>";
                    //echo "<tr><td colspan='8'><span style='color:#5858FA; background-color:#F3F781'>$ciudadVisitador------$codVisitador-------$nombreVisitador---------$nombreLinea</span></td></tr>";
//                    echo "<tr><td>Cod Med</td><td>Medico</td><td>Esp.</td><td>Categoria</td><td>Contactos Rutero</td><td>Nro. Parrillas</td><td>Observaci&oacute;n</td></tr>";
                   
					$codMedIgual=0;
					$sqlMedIgual="select m.cod_med from parrilla_personalizada p, medicos m, categorias_lineas c
					where p.cod_med=m.cod_med and m.cod_med=c.cod_med and c.cod_med=p.cod_med and c.cod_especialidad='$codEspecialidadMed' 
					and c.categoria_med='$categoriaMed' and 
					p.cod_gestion=$codGestion and p.cod_ciclo=$codCiclo and m.cod_ciudad=$codCiudad and 
					c.codigo_linea='$codLinea' and c.codigo_linea=p.cod_linea LIMIT 0,1";
					$respMedIgual=mysql_query($sqlMedIgual);
					$numFilas=mysql_num_rows($respMedIgual);
					if($numFilas>0){
						$codMedIgual=mysql_result($respMedIgual,0,0);
					
						$sqlInsert="insert into parrilla_personalizada (cod_gestion, cod_ciclo, cod_linea, cod_med, numero_visita, 
						orden_visita, cod_mm, cantidad_mm, 
						cod_ma, cantidad_ma) select cod_gestion, cod_ciclo, cod_linea, $codMed, numero_visita, orden_visita, cod_mm, cantidad_mm, 
						cod_ma, cantidad_ma from parrilla_personalizada where cod_gestion=$codGestion and cod_ciclo=$codCiclo 
						and cod_med=$codMedIgual and cod_linea=$codLinea";
						$respInsert=mysql_query($sqlInsert);
						
					}
					
					echo "<tr bgcolor='#FA5858'><td>$codMed</td><td>$nombreMed</td><td>$codEspecialidadMed</td><td>$categoriaMed</td>
					<td>$nroContactos</td><td>$nroParrillas</td><td>$obs Medico $codMedIgual $nombreLineaVisita $nombreVisitador $nombreLinea $ciudadVisitador</td></tr>";
				
				}
            }
        }
    }
}
echo "</table></center>";

echo $codCiudadVeri;
?>