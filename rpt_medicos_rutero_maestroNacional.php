<script language='JavaScript'>
    function totales(){
        var main=document.getElementById('main');   
        var numFilas=main.rows.length;
        var numCols=main.rows[0].cells.length;
	 
        for(var j=1; j<=numCols-1; j++){
            var subtotal=0;
            for(var i=1; i<=numFilas-2; i++){
                var dato=parseInt(main.rows[i].cells[j].innerHTML);
                subtotal=subtotal+dato;
            }
            var fila=document.createElement('TH');
            main.rows[numFilas-1].appendChild(fila);
            main.rows[numFilas-1].cells[j].innerHTML=subtotal;
        }	 
    }
</script>
<?php
error_reporting(0);
require("conexion.inc");
require("estilos_reportes.inc");
require("funcion_nombres.php");
$tipoRuteroRpt = $tipoRuteroRpt;
$gestionCicloRpt = $gestionCicloRpt;
$codigoLinea = $rpt_linea;
$codigos = explode("|", $gestionCicloRpt);
$codigoCiclo = $codigos[0];
$codigoGestion = $codigos[1];
$nombreGestion = $codigos[2];
$codigoEspe = $rpt_especialidad;
$codigoEspe1 = str_replace("`", "'", $codigoEspe);

$vectorVisitador = explode(",", $rpt_visitador);
$tamVectorVisitador = sizeof($vectorVisitador);

echo "<center><table border='0' class='textotit'><tr><th>M&eacute;dicos en Rutero Maestro
<br> Gestion: $nombreGestion Ciclo: $codigoCiclo</th></tr></table></center><br>";

echo "<center><table border='1' class='textomini' cellspacing='0' width='60%'>";
echo "<tr><th>&nbsp;</th><th>CodigoBPH</th><th>Codigo CloseUp</th><th>Codigo</th><th>Territorio</th><th>Medico</th><th>Especialidad</th><th>Categoria</th><th>Linea</th><th>CodVisitador</th><th>Visitador</th>
<th>Contactos</th></tr>";

for ($i = 0; $i <= $tamVectorVisitador - 1; $i++) {
    $rpt_visitador = $vectorVisitador[$i];
    $sql_visitador = "SELECT paterno, materno, nombres, cod_ciudad from funcionarios where codigo_funcionario='$rpt_visitador'";
    $resp_visitador = mysql_query($sql_visitador);
    $dat_visitador = mysql_fetch_array($resp_visitador);
    $nombre_visitador = "$dat_visitador[0] $dat_visitador[1] $dat_visitador[2]";
    $codTerritorio = $dat_visitador[3];
    $nombreTerritorio = nombreTerritorio($codTerritorio);
    if ($tipoRuteroRpt == 0) {
        $sql = "SELECT cod_rutero from rutero_maestro_cab where cod_visitador='$rpt_visitador' and codigo_linea in ($codigoLinea) and codigo_ciclo='$codigoCiclo' and codigo_gestion='$codigoGestion'";
        $resp = mysql_query($sql);
        $filas = mysql_num_rows($resp);
        $rutero_maestro="0";
		while($datR=mysql_fetch_array($resp)){
			$rutero_maestro=$rutero_maestro.",".$datR[0];
		}

        $tabla1 = "rutero_maestro_cab";
        $tabla2 = "rutero_maestro";
        $tabla3 = "rutero_maestro_detalle";
    }
    if ($tipoRuteroRpt == 1) {
        $sql = "SELECT cod_rutero from rutero_maestro_cab_aprobado where cod_visitador='$rpt_visitador' and codigo_linea in ($codigoLinea) and codigo_ciclo='$codigoCiclo' and codigo_gestion='$codigoGestion'";
        $resp = mysql_query($sql);
        $rutero_maestro="0";
		while($datR=mysql_fetch_array($resp)){
			$rutero_maestro=$rutero_maestro.",".$datR[0];
		}
        $tabla1 = "rutero_maestro_cab_aprobado";
        $tabla2 = "rutero_maestro_aprobado";
        $tabla3 = "rutero_maestro_detalle_aprobado";
    }

	
    $sql_medicos = "SELECT DISTINCT (rmd.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med, rmd.categoria_med, rmd.cod_especialidad, rmc.codigo_linea, rmc.cod_rutero from $tabla1 rmc, $tabla2 rm, $tabla3 rmd, medicos m where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.cod_rutero in ($rutero_maestro) and rmc.cod_visitador='$rpt_visitador' and rmd.cod_visitador='$rpt_visitador'and rmd.cod_especialidad in ($codigoEspe1) order by rmd.cod_especialidad, rmd.categoria_med, m.ap_pat_med";
	
	//echo $sql_medicos;
		
    $resp_medicos = mysql_query($sql_medicos);
    $indice_tabla = 1;
    while ($dat_medicos = mysql_fetch_array($resp_medicos)) {
        $codigo_medico = $dat_medicos[0];
        $nombre_medico = "$dat_medicos[1] $dat_medicos[2] $dat_medicos[3]";
        $categoria_med = $dat_medicos[4];
        $especialidad_med = $dat_medicos[5];
		$codigoLineaVis=$dat_medicos[6];
		$nombreLineaVis=nombreLinea($codigoLineaVis);
		$codRuterito=$dat_medicos[7];
		
        $sql_cant_contactos = "SELECT rmd.cod_med from $tabla1 rmc, $tabla2 rm, $tabla3 rmd, medicos m where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.cod_visitador=rm.cod_visitador and rmc.cod_rutero='$codRuterito' and rmc.codigo_linea='$codigoLineaVis' and rmc.cod_visitador='$rpt_visitador'and rmd.cod_especialidad in ('$especialidad_med') and rmd.cod_med='$codigo_medico'";
        $resp_cant_contactos = mysql_query($sql_cant_contactos);
        $num_contactos = mysql_num_rows($resp_cant_contactos);

        $sqlCodCloseUp = "SELECT m.`cod_catcloseup`, m.cod_closeup from medicos m where m.`cod_med`=$codigo_medico";
        $respCloseUp = mysql_query($sqlCodCloseUp);
        $filasCloseUp = mysql_num_rows($respCloseUp);
        $codigoCloseUp = "";
        if ($filasCloseUp == 1) {
            $codigoCatCloseUp = mysql_result($respCloseUp, 0, 0);
            $codCloseUp = mysql_result($respCloseUp, 0, 1);
        } else {
            $codigoCatCloseUp = 0;
            $codCloseUp = 0;
        }

        $codigoBPH = "$codigoCatCloseUp$categoria_med$frecuenciaGrilla";

        // $sql_linea_visita = mysql_query("SELECT l.nombre_l_visita FROM lineas_visita l, lineas_visita_visitadores lv, lineas_visita_especialidad lvv  WHERE l.codigo_l_visita = lv.codigo_l_visita AND lvv.codigo_l_visita = lv.codigo_l_visita AND lv.codigo_funcionario = 1133 AND lv.codigo_ciclo = 10 AND lv.codigo_gestion = 1009 and lvv.cod_especialidad = '$especialidad_med' ORDER BY 1");

        echo "<tr><td>$indice_tabla</td><td align='center'>$codigoBPH</td><td align='center'>$codCloseUp</td><td align='center'>$codigo_medico</td>
		<td>$nombreTerritorio</td><td>$nombre_medico</td><td>$especialidad_med</td><td align='center'>$categoria_med</td><td>$nombreLineaVis</td><td>$rpt_visitador</td><td>$nombre_visitador</td><td align='center'>$num_contactos</td></tr>";
        $indice_tabla++;
    }
}
echo "</table></center>";