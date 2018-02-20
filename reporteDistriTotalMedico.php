<?php

require("conexion.inc");
require("estilos_inicio_adm.inc");
set_time_limit(0);

$url = $_GET['gestionCicloRpt'];
$url1 = explode("|", $url);
$codCiclo = 3;
$codGestion = 1013;
$rpt_territorio="114";


echo "<center><h2>Reporte de Planificacion de Muestras <br> Ciclo: $codCiclo Gestion: $codGestion</h2></center><br>";


echo "<table border='1' cellspacing='0' align='center' class='texto'>";

$sqlLineas = "select l.codigo_linea, l.nombre_linea from lineas l  
			where l.linea_promocion=1";
$respLineas = mysql_query($sqlLineas);
echo "<tr><td>Cod Med</td><td>Medico</td><td>Esp.</td><td>Categoria</td>
<td>CodMM</td><td>MM</td><td>Cantidad</td><td>Visitador</td><td>Linea</td><td>Ciudad</td></tr>";
while ($datLineas = mysql_fetch_array($respLineas)) {
    $codLinea = $datLineas[0];
    $nombreLinea = $datLineas[1];

    $codCiudadVeri=$rpt_territorio;
	$sqlVis = "select f.codigo_funcionario, CONCAT(f.paterno, ' ', f.nombres) as visitador,
       (select c.descripcion from ciudades c where c.cod_ciudad = f.cod_ciudad) as ciudad, f.cod_ciudad 
	   from funcionarios f, funcionarios_lineas fl where f.codigo_funcionario = fl.codigo_funcionario and
       fl.codigo_linea = $codLinea and f.estado=1 and f.cod_ciudad<>115 and f.cod_cargo=1011 
	   order by ciudad, visitador";
	//	and  f.cod_ciudad in ($codCiudadVeri)
	
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
			where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and 
			rc.cod_visitador=$codVisitador and rc.codigo_ciclo=$codCiclo and rc.codigo_gestion=$codGestion and 
			rc.codigo_linea=$codLinea and rc.estado_aprobado=1 group by rd.cod_med order by rd.cod_especialidad, rd.categoria_med, medico";
        $respRutero = mysql_query($sqlRutero);
        $numFilasRutero = mysql_num_rows($respRutero);
        
		while ($datRutero = mysql_fetch_array($respRutero)) {
			$codMed = $datRutero[0];
			$nroContactos = $datRutero[1];
			$codEspecialidadMed = $datRutero[2];
			$categoriaMed = $datRutero[3];
			$nombreMed = $datRutero[4];
			
			$sqlParrilla="select p.cod_mm, sum(p.cantidad_mm), m.descripcion from parrilla_personalizada p, 
				muestras_medicas m where p.cod_linea='$codLinea' and  
			 p.cod_gestion='$codGestion' and p.cod_ciclo='$codCiclo' and p.cod_med='$codMed' 
			 and p.cod_mm=m.codigo group by cod_mm, descripcion";
			$respParrilla = mysql_query($sqlParrilla);
			while($datParrilla=mysql_fetch_array($respParrilla)){
				$codMM=$datParrilla[0];
				$cantidadMM=$datParrilla[1];
				$descripMM=$datParrilla[2];
				
				echo "<tr><td>$codMed</td><td>$nombreMed</td><td>$codEspecialidadMed</td><td>$categoriaMed</td>
				<td>$codMM</td><td>$descripMM</td><td>$cantidadMM</td> 
				<td>$nombreVisitador</td><td>$nombreLinea</td><td>$ciudadVisitador</td></tr>";
			}			   
			
		}
        }
    }
echo "</table>";

echo $codCiudadVeri;
?>