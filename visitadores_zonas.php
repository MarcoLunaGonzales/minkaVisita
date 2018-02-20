<?php 

error_reporting(0);
set_time_limit(0);
require("conexion.inc");
$ciclo   = 4;
$gestion = 1010;

$idProdObj=4;

$sql_zonas_visitador =  mysql_query("SELECT DISTINCT rmd.cod_visitador, dr.cod_zona from rutero_maestro_cab_aprobado rmc, rutero_maestro_detalle_aprobado rmd, rutero_maestro_aprobado rma, direcciones_medicos dr WHERE rmc.cod_rutero = rma.cod_rutero and rma.cod_contacto = rmd.cod_contacto and dr.cod_med = rmd.cod_med and rmc.codigo_ciclo = $ciclo and rmc.codigo_gestion = $gestion ORDER BY 1");
$sql_id = mysql_query("SELECT max(id) from visitadores_zonas");
$id = mysql_result($sql_id, 0, 0);
if($id == 0 or $id == ''){
	$id = 1;
}else{
	$id++;
}

while ($row = mysql_fetch_array($sql_zonas_visitador)) {
	$visitador   = $row[0];
	$zona        = $row[1];
	$bandera=0;
	$cod_med = '';

    $sqlProdObj = "SELECT pd.codigo_producto from productos_objetivo_cabecera pc, productos_objetivo p, productos_objetivo_detalle pd where pc.id=p.id_cabecera and p.id=pd.id and pc.id = $idProdObj and p.codigo_funcionario = $visitador ";
    $respProdObj=mysql_query($sqlProdObj);
    $cadProdObj="'0'";
    while($rowProdObj=mysql_fetch_array($respProdObj)){
    	$cadProdObj=$cadProdObj.",'".$rowProdObj[0]."'";
    }


    $sqlVeriEspe="SELECT rd.cod_especialidad, rd.categoria_med, count(distinct(rd.cod_med)) from rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd where rmc.cod_rutero = rm.cod_rutero and rm.cod_contacto = rd.cod_contacto and rmc.codigo_ciclo = $ciclo and rmc.codigo_gestion = $gestion and rmc.cod_visitador = $visitador and rd.cod_med in (select d.cod_med from direcciones_medicos d  where d.cod_zona =$zona) group by rd.cod_especialidad, rd.categoria_med ";
    $respVeriEspe=mysql_query($sqlVeriEspe);
    while($rowVeriEspe=mysql_fetch_array($respVeriEspe)){
    	$codEspeVeri=$rowVeriEspe[0];
    	$catEspeVeri=$rowVeriEspe[1];
    	$cantEspeVeri=$rowVeriEspe[2];

    	$sqlLineaVisita="SELECT l.codigo_l_visita  from lineas_visita l, lineas_visita_especialidad le, lineas_visita_visitadores lv where l.codigo_l_visita=le.codigo_l_visita and le.codigo_l_visita=lv.codigo_l_visita and lv.codigo_ciclo=$ciclo and lv.codigo_gestion=$gestion and le.cod_especialidad='$codEspeVeri' and lv.codigo_funcionario='$visitador'";
    	$respLineaVisita=mysql_query($sqlLineaVisita);
    	$codLineaVis=mysql_result($respLineaVisita, 0, 0);

    	//echo $codEspeVeri." ".$catEspeVeri." ".$codLineaVis."<br>";

    	$sqlParrilla="SELECT pd.`codigo_muestra`, sum(pd.`cantidad_muestra`) from `parrilla` p, `parrilla_detalle` pd where p.`codigo_parrilla`=pd.`codigo_parrilla` and p.`cod_ciclo`=$ciclo and p.`codigo_gestion`=$gestion and p.`cod_especialidad`='$codEspeVeri' and p.`categoria_med`='$catEspeVeri' and p.`codigo_l_visita`=$codLineaVis  and p.`codigo_linea`=1021 and pd.`codigo_muestra` in ($cadProdObj) group by pd.`codigo_muestra`";
    	$respParrilla=mysql_query($sqlParrilla);
    	$numFilas=mysql_num_rows($respParrilla);
    	if($numFilas>0){

    		if($bandera==0){
    			$sqlInsertCab="INSERT into visitadores_zonas (id, cod_visitador, cod_zona, cant_medicos, cant_contactos) values ($id, $visitador, $zona,0,0)";
    			$respInsertCab=mysql_query($sqlInsertCab);
    			$bandera=1;
    		}
    		while($datParrilla=mysql_fetch_array($respParrilla)){
    			$codigoMM=$datParrilla[0];

    			$sqlInsertDet="INSERT into visitadores_zonas_detalle (id_cabecera, especialidad, categoria, cantidad, codigo_producto, estado) values ($id, '$codEspeVeri', '$catEspeVeri', '$cantEspeVeri', '$codigoMM', 1)";
    			echo $sqlInsertDet."<br>";
    			$respInsertDet=mysql_query($sqlInsertDet);
    		}
    	}
    }


    $id++;
}

?>