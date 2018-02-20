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


$borrado_cab     = mysql_query("DELETE from reporte_cabge");

$borrado_detalle = mysql_query("DELETE from reporte_detallege");

$sql_territorio    = "SELECT descripcion from ciudades where cod_ciudad = '$rpt_territorio'";

$resp_territorio   = mysql_query($sql_territorio);

$dat_territorio    = mysql_fetch_array($resp_territorio);

$nombre_territorio = $dat_territorio[0];

echo "<table align='center' class='textotit'><tr><th>Reporte Boletas de Visita Grupos Especiales</th></tr></table><br>";

$sql_visitadores="select distinct(gd.cod_visitador) from grupos_especiales g, grupos_especiales_detalle gd 
	where g.id=gd.id and g.gestion='$rpt_gestion' and g.ciclo='$rpt_ciclo' and g.territorio in ($rpt_territorio)";
	
$resp_visitadores  = mysql_query($sql_visitadores);

$indice_reporte    = 1;

while ($dat_visitadores = mysql_fetch_array($resp_visitadores)) {

    $codigo_funcionario = $dat_visitadores[0];
    $nombre_funcionario = nombreVisitador($codigo_funcionario);
    $nombre_linea       = "GE";

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

    $sql_cantidad_total_boletas = "select count(*) from grupos_especiales g, grupos_especiales_detalle gd 
		where g.id=gd.id and g.gestion=$rpt_gestion and g.ciclo=$rpt_ciclo and gd.cod_visitador in ($codigo_funcionario);";
    $resp_cantidad_total_boletas = mysql_query($sql_cantidad_total_boletas);
    $cantidad_total_boletas = mysql_result($resp_cantidad_total_boletas,0,0);

    $indice_boleta = 1;

	$sql="select g.gestion, g.ciclo, g.codigo_grupo_especial, gd.cod_med, gg.codigo_linea from 
		grupos_especiales g, grupos_especiales_detalle gd, grupo_especial gg 
		where g.id=gd.id and g.gestion=$rpt_gestion and g.ciclo=$rpt_ciclo and gd.cod_visitador in ($codigo_funcionario) 
		and gg.codigo_grupo_especial=g.codigo_grupo_especial 
		group by g.gestion, g.ciclo, gd.cod_med";
		
	/*$sql="select g.gestion, g.ciclo, g.codigo_grupo_especial, gd.cod_med, gg.codigo_linea from 
		grupos_especiales g, grupos_especiales_detalle gd, grupo_especial gg 
		where g.id=gd.id and g.gestion=$rpt_gestion and g.ciclo=$rpt_ciclo and gd.cod_visitador in ($codigo_funcionario) 
		and gg.codigo_grupo_especial=g.codigo_grupo_especial 
		and gg.codigo_grupo_especial not in (2484, 2483, 2476, 2477, 2478, 2479, 2480, 2481, 2482, 2472)
		GROUP BY g.gestion, g.ciclo, gd.cod_med";*/
	
	echo $sql."<br>";
	$resp = mysql_query($sql);
    $filas_rutero = mysql_num_rows($resp);

    while ($dat = mysql_fetch_array($resp)) {
        $cod_ciclo = $dat[1];
		$codGrupoEsp=$dat[2];
		$nombreGrupoEsp=nombreGE($codGrupoEsp);
        $codigo_medico = $dat[3];
		$nombre_medico=nombreMedico($codigo_medico);
		$codigoLineaMkt=$dat[4];

		$sqlDatC="select c.cod_especialidad, c.categoria_med, m.telf_med, m.telf_celular_med, 
			m.cod_closeup, m.cod_catcloseup 
			from medicos m, categorias_lineas c where c.cod_med=m.cod_med and 
			c.cod_med=$codigo_medico and c.codigo_linea=$codigoLineaMkt";
		
		//echo $sqlDatC;
		
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
        
		
		
		
		for($ii=1; $ii<=3; $ii++){
		
			$sqlParrilla="select (select concat(m.descripcion, ' ', m.presentacion) from muestras_medicas m where m.codigo=pd.codigo_muestra)muestrasmedicas,
			pd.cantidad_muestra,
			(select ma.descripcion_material from material_apoyo ma where ma.codigo_material=pd.codigo_material)materialapoyo,
			pd.cantidad_material
			from parrilla_especial p, parrilla_detalle_especial pd
			where p.codigo_parrilla_especial=pd.codigo_parrilla_especial and p.codigo_grupo_especial in 
			(select gd.codigo_grupo_especial from grupo_especial_detalle gd where gd.cod_med=$codigo_medico) and 
			p.codigo_gestion=$rpt_gestion and p.cod_ciclo=$rpt_ciclo and p.numero_visita=$ii order by muestrasmedicas";
			
			echo "<br>".$sqlParrilla."<br>";
			
			$respParrilla=mysql_query($sqlParrilla);
			$numDatosParrilla=mysql_num_rows($respParrilla);
			
			if($numDatosParrilla>0){
				$sql_insert_reporte_cab = "INSERT into reporte_cabge values
				($indice_reporte,'$nombre_territorio', '$nombre_funcionario','$nombre_medico','$nombre_linea',
				'$rpt_ciclo','$rpt_gestion', '-','$dir_rep1','$dir_rep2',
				'$dir_rep3','$telefono_medico', '$celular_medico','$espe - $nombreGrupoEsp','$fecha_planificada','$nombre_supervisor',
				'$cadGrupo','$codBPH')";			
				echo "<br>".$sql_insert_reporte_cab."<br>";			
				$resp_insert_reporte_cab = mysql_query($sql_insert_reporte_cab);
			
			}

			
			$orden_promocional = 1;
			$bandera=0;
			while($datParrilla=mysql_fetch_array($respParrilla)){
				$muestra = $datParrilla[0];
				$cant_muestra = $datParrilla[1];
				$material = $datParrilla[2];
				$cant_material = $datParrilla[3];				
				if ($material == " - ") {
					$material = " ";
				}
				if ($cant_material == 0) {
					$cant_material = " ";
				}
				$insert_reporte_det = "INSERT into reporte_detallege values($indice_reporte,$orden_promocional,'$muestra',
				'$cant_muestra','$material','$cant_material')";
				$resp_reporte_det = mysql_query($insert_reporte_det);	
				echo "<BR>DET:    ".$insert_reporte_det."<br>";
				$orden_promocional++;
				$bandera=1;
			}
			for ($jj = $orden_promocional - 1; $jj < 12; $jj++) {
				if($bandera==1){
					$insert_reporte_det = "INSERT into reporte_detallege values($indice_reporte,$orden_promocional,'','','','')";
					echo "<BR>DETXX:    ".$insert_reporte_det."<br>";
					$resp_reporte_det = mysql_query($insert_reporte_det);
					$orden_promocional++;
				}
			}
			if($bandera==1){
				$indice_boleta++;
				$indice_reporte++;
			}
			
		}            
    }
}

echo "<script language='JavaScript'>
location.href='boletaspdfGE.php';
</script>";

?>