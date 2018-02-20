<?php

error_reporting(0);
set_time_limit(0);

require("conexion.inc");
require("funcion_nombres.php");

require('estilos_reportes_administracion.inc');

$global_gestion = $_GET['rpt_gestion'];
$rpt_ciclo = $_GET['rpt_ciclo'];
$rpt_gestion = $_GET['rpt_gestion'];
$rpt_territorio=$_GET['rpt_territorio'];


$borrado_cab     = mysql_query("DELETE from reporte_cabbm");
$borrado_detalle = mysql_query("DELETE from reporte_detallebm");
$sql_territorio    = "SELECT descripcion from ciudades where cod_ciudad = '$rpt_territorio'";
$resp_territorio   = mysql_query($sql_territorio);
$dat_territorio    = mysql_fetch_array($resp_territorio);
$nombre_territorio = $dat_territorio[0];
echo "<table align='center' class='textotit'><tr><th>Reporte Boletas de Visita Banco MM</th></tr></table><br>";

/*$sql_visitadores="SELECT distinct(bmv.cod_visitador)
	from banco_muestras b, banco_muestras_detalle bd, banco_muestra_cantidad_visitador bmv where 
	b.id=bd.id and b.gestion='$global_gestion' and b.ciclo_inicio<='$rpt_ciclo' and b.ciclo_final>='$rpt_ciclo' 
	and bmv.id_for=b.id and  bmv.cantidad>0 
	and b.estado=1 and b.cod_med=bd.cod_med and bmv.codigo_muestra=bd.cod_muestra and
	 bmv.cod_visitador in (select f.codigo_funcionario from funcionarios f where f.cod_ciudad='$rpt_territorio');";*/

$sql_visitadores="SELECT distinct(bmv.cod_visitador)
	from banco_muestras b, banco_muestras_detalle bd, banco_muestra_cantidad_visitador bmv where 
	b.id=bd.id and b.gestion='$global_gestion' and (b.ciclo_inicio<='11' or b.ciclo_final>='11') 
	and bmv.id_for=b.id and  bmv.cantidad>0 
	and b.estado=1 and b.cod_med=bd.cod_med and bmv.codigo_muestra=bd.cod_muestra and
	 bmv.cod_visitador in (select f.codigo_funcionario from funcionarios f where f.cod_ciudad='$rpt_territorio');";
	 
	 
$resp_visitadores  = mysql_query($sql_visitadores);

$indice_reporte    = 1;

while ($dat_visitadores = mysql_fetch_array($resp_visitadores)) {

    $codigo_funcionario = $dat_visitadores[0];
    $nombre_funcionario = nombreVisitador($codigo_funcionario);
    $nombre_linea       = "BMM";

    $sql_ciudadtroncal  = "SELECT tipo from ciudades where cod_ciudad='$rpt_territorio'";
    $resp_ciudadtroncal = mysql_query($sql_ciudadtroncal);
    $dat_ciudadtroncal  = mysql_fetch_array($resp_ciudadtroncal);
    $tipo_ciudad        = $dat_ciudadtroncal[0];
    if ($tipo_ciudad == 0) {
        $sql_supervisor    = "SELECT paterno, materno, nombres from funcionarios where cod_cargo='1001'and cod_ciudad='115'";
        $resp_supervisor   = mysql_query($sql_supervisor);
        $dat_supervisor    = mysql_fetch_array($resp_supervisor);
        $nombre_supervisor = "$dat_supervisor[0] $dat_supervisor[2]";
    } else {
        $sql_supervisor = "SELECT f.paterno, f.materno, f.nombres from funcionarios f, 
		funcionarios_lineas fl where f.cod_cargo='1001' and f.codigo_funcionario=fl.codigo_funcionario and
		f.cod_ciudad='$rpt_territorio' and f.estado=1";
        $resp_supervisor = mysql_query($sql_supervisor);
        $dat_supervisor = mysql_fetch_array($resp_supervisor);
        $nombre_supervisor = "$dat_supervisor[0] $dat_supervisor[2]";
    }

    /*
	$sql_cantidad_total_boletas = "SELECT b.gestion, b.ciclo_final, 'bancoMM', b.cod_med, b.codigo_linea, bd.cod_muestra
		from banco_muestras b, banco_muestras_detalle bd, banco_muestra_cantidad_visitador bmv where 
		b.id=bd.id and b.gestion='$global_gestion' and b.ciclo_inicio<='$rpt_ciclo' and b.ciclo_final>='$rpt_ciclo' 
		and bmv.id_for=b.id and 
		bmv.cantidad>0 and b.estado=1 and b.cod_med=bd.cod_med and bmv.codigo_muestra=bd.cod_muestra and 
		bmv.cod_visitador in ($codigo_funcionario) group by cod_muestra";*/
	$sql_cantidad_total_boletas = "SELECT b.gestion, b.ciclo_final, 'bancoMM', b.cod_med, b.codigo_linea, bd.cod_muestra
	from banco_muestras b, banco_muestras_detalle bd, banco_muestra_cantidad_visitador bmv where 
	b.id=bd.id and b.gestion='$global_gestion' and (b.ciclo_inicio<='11' or b.ciclo_final>='11') 
	and bmv.id_for=b.id and 
	bmv.cantidad>0 and b.estado=1 and b.cod_med=bd.cod_med and bmv.codigo_muestra=bd.cod_muestra and 
	bmv.cod_visitador in ($codigo_funcionario) group by cod_muestra";
    $resp_cantidad_total_boletas = mysql_query($sql_cantidad_total_boletas);
    $cantidad_total_boletas = mysql_num_rows($resp_cantidad_total_boletas);

    $indice_boleta = 1;

	/*$sql="SELECT b.gestion, b.ciclo_final, 'bancoMM', b.cod_med, b.codigo_linea, bd.cod_muestra
		from banco_muestras b, banco_muestras_detalle bd, banco_muestra_cantidad_visitador bmv where 
		b.id=bd.id and b.gestion='$global_gestion' and b.ciclo_inicio<='$rpt_ciclo' and b.ciclo_final>='$rpt_ciclo' 
		and bmv.id_for=b.id and 
		bmv.cantidad>0 and b.estado=1 and b.cod_med=bd.cod_med and bmv.codigo_muestra=bd.cod_muestra and 
		bmv.cod_visitador in ($codigo_funcionario) group by cod_muestra";*/
		
	$sql="SELECT b.gestion, b.ciclo_final, 'bancoMM', b.cod_med, b.codigo_linea, bd.cod_muestra
		from banco_muestras b, banco_muestras_detalle bd, banco_muestra_cantidad_visitador bmv where 
		b.id=bd.id and b.gestion='$global_gestion' and (b.ciclo_inicio<='11' or b.ciclo_final>='11') 
		and bmv.id_for=b.id and 
		bmv.cantidad>0 and b.estado=1 and b.cod_med=bd.cod_med and bmv.codigo_muestra=bd.cod_muestra and 
		bmv.cod_visitador in ($codigo_funcionario) group by cod_muestra";
	$resp = mysql_query($sql);
    $filas_rutero = mysql_num_rows($resp);

    while ($dat = mysql_fetch_array($resp)) {
        $cod_ciclo = $dat[1];
		$codGrupoEsp=$dat[2];
        $codigo_medico = $dat[3];
		$nombre_medico=nombreMedico($codigo_medico);
		$codigoLineaMkt=$dat[4];
		$codigoMMBA=$dat[5];

		$sqlDatC="select c.cod_especialidad, c.categoria_med, m.telf_med, m.telf_celular_med, 
			m.cod_closeup, m.cod_catcloseup 
			from medicos m, categorias_lineas c where c.cod_med=m.cod_med and 
			c.cod_med=$codigo_medico and c.codigo_linea=$codigoLineaMkt";
		$respDatC=mysql_query($sqlDatC);
		$espe = mysql_result($respDatC,0,0);
        $cat = mysql_result($respDatC,0,1);
		$telefono_medico = mysql_result($respDatC,0,2);
		$celular_medico = mysql_result($respDatC,0,3);
		$catCloseUp = mysql_result($respDatC,0,4);
		
        $sql_dir = "SELECT direccion from direcciones_medicos where cod_med=$codigo_medico";
		$resp_dir = mysql_query($sql_dir);
		$indice_direccion = 1;
		$dir_rep1 = "";
		$dir_rep2 = "";
		$dir_rep3 = "";
		$direccion_medico="";
		while ($dat_dir = mysql_fetch_array($resp_dir)) {
			$dir = $dat_dir[0];
			if ($indice_direccion == 1) {$dir_rep1 = $dir;}
			if ($indice_direccion == 2) {$dir_rep2 = $dir;}
			if ($indice_direccion == 3) {$dir_rep3 = $dir;}
			$direccion_medico = "$direccion_medico<br>$dir";
			$indice_direccion++;
		}
        
		
		/*$sql_parrilla="SELECT distinct(select CONCAT(mm.descripcion,' ',mm.presentacion) from muestras_medicas mm where 
		mm.codigo=bd.cod_muestra),
		bmv.cantidad
		from banco_muestras b, banco_muestras_detalle bd, banco_muestra_cantidad_visitador bmv where 
		b.id=bd.id and b.gestion='$global_gestion' and b.ciclo_inicio<='$rpt_ciclo' and b.ciclo_final>='$rpt_ciclo' 
		and bmv.id_for=b.id and 
		bmv.cantidad>0 and b.estado=1 and b.cod_med=bd.cod_med and bmv.codigo_muestra=bd.cod_muestra and 
		bmv.cod_visitador='$codigo_funcionario' and b.cod_med='$codigo_medico' and bd.cod_muestra='$codigoMMBA'";*/
		$sql_parrilla="SELECT distinct(select CONCAT(mm.descripcion,' ',mm.presentacion) from muestras_medicas mm where 
		mm.codigo=bd.cod_muestra),
		bmv.cantidad
		from banco_muestras b, banco_muestras_detalle bd, banco_muestra_cantidad_visitador bmv where 
		b.id=bd.id and b.gestion='$global_gestion' and (b.ciclo_inicio<='11' or b.ciclo_final>='11')  
		and bmv.id_for=b.id and 
		bmv.cantidad>0 and b.estado=1 and b.cod_med=bd.cod_med and bmv.codigo_muestra=bd.cod_muestra and 
		bmv.cod_visitador='$codigo_funcionario' and b.cod_med='$codigo_medico' and bd.cod_muestra='$codigoMMBA'";
		
		/*$sql_parrilla="select p.codigo_parrilla_especial, p.codigo_gestion, p.cod_ciclo, 
			p.numero_visita, p.agencia from parrilla_especial p where p.codigo_gestion=$rpt_gestion 
			and p.cod_ciclo=$rpt_ciclo and p.codigo_grupo_especial=$codGrupoEsp order by p.numero_visita";	*/	
		$resp_parrilla = mysql_query($sql_parrilla);
		while ($dat_parrilla = mysql_fetch_array($resp_parrilla)) {
			//$cod_parrilla     = $dat_parrilla[0];
			//$cod_ciclo        = $dat_parrilla[2];
			$numero_de_visita = 1;

			$sql_insert_reporte_cab = "INSERT into reporte_cabbm values
			($indice_reporte,'$nombre_territorio', '$nombre_funcionario','$nombre_medico','$nombre_linea',
			'$rpt_ciclo','$rpt_gestion', '$indice_boleta de $cantidad_total_boletas','$dir_rep1','$dir_rep2',
			'$dir_rep3','$telefono_medico', '$celular_medico','$espe','$fecha_planificada','$nombre_supervisor',
			'$cadGrupo','$codBPH')";
			$resp_insert_reporte_cab = mysql_query($sql_insert_reporte_cab);
			//echo $sql_insert_reporte_cab."<br>";
			

			$orden_promocional = 1;
			$muestra = $dat_parrilla[0];
			$cant_muestra = $dat_parrilla[1];
			$material = "";
			$cant_material = 0;
			
			if ($material == " - ") {
				$material = " ";
			}
			if ($cant_material == 0) {
				$cant_material = " ";
			}
			$insert_reporte_det = "INSERT into reporte_detallebm values($indice_reporte,$orden_promocional,'$muestra',
			'$cant_muestra','$material','$cant_material')";
			$resp_reporte_det = mysql_query($insert_reporte_det);				
			$orden_promocional++;

			for ($ii = $orden_promocional - 1; $ii < 12; $ii++) {
				$insert_reporte_det = "INSERT into reporte_detallege values($indice_reporte,$orden_promocional,'','','','')";
				$resp_reporte_det = mysql_query($insert_reporte_det);
				$orden_promocional++;
			}
		}
            $indice_boleta++;
            $indice_reporte++;
    }
}

echo "<script language='JavaScript'>
location.href='boletaspdfBM.php';
</script>";

?>