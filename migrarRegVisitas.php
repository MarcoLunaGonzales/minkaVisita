<?php
require("conexion.inc");
$cicloPar=$cicloPar;
echo "CICLOOOO:  $cicloPar";
$sqlCab="select r.codigo_ciclo,
       r.`codigo_gestion`,
       r.`cod_contacto`,
       r.`orden_visita`,
       r.`prioridad`,
       r.`muestra`,
       r.`cantidad_muestra_entregado`,
       r.`cantidad_muestraextra_entregado`,
       r.`material_apoyo`,
       r.`cantidad_material_entregado`,
       r.`cantidad_materialextra_entregado`,
       r.`fecha_registro`,
       r.`hora_registro`,
       r.`fecha_visita`,
       r.`obs`
from `registro_visita` r
where r.`codigo_gestion` = 1007 and
      r.`codigo_ciclo` in ($cicloPar) group by r.`codigo_ciclo`, r.`cod_contacto`, r.`orden_visita`";
$respCab=mysql_query($sqlCab);
while($datCab=mysql_fetch_array($respCab)){
		$codCiclo=$datCab[0];
		$codGestion=$datCab[1];
		$codContacto=$datCab[2];
		$ordenVisita=$datCab[3];
		$codMuestra=$datCab[4];
		$cantMuestra=$datCab[5];
		$cantExtMuestra=$datCab[6];
		$codMaterial=$datCab[7];
		$cantMaterial=$datCab[8];
		$cantExtMaterial=$datCab[9];
		$fechaReg=$datCab[10];
		$horaReg=$datCab[11];
		$fechaVisita=$datCab[12];
		$obs=$datCab[13];
		//sacamos los datos adicionales
		$sqlAd="select rd.`cod_visitador`, rd.`cod_med`,rd.`cod_especialidad`,rd.`categoria_med` 
		from `rutero_detalle` rd where 
		rd.`cod_contacto`=$codContacto and rd.`orden_visita`=$ordenVisita";		
		$respAd=mysql_query($sqlAd);
		$codVisitador=mysql_result($respAd,0,0);
		$codMed=mysql_result($respAd,0,1);
		$codEspe=mysql_result($respAd,0,2);
		$catMed=mysql_result($respAd,0,3);
		
		$sqlAd1="select o.`id`, r.`dia_contacto` from `rutero` r, `orden_dias` o 
				 where r.`dia_contacto`=o.`dia_contacto` and r.`cod_contacto`=$codContacto";
		$respAd1=mysql_query($sqlAd1);
		$codDiaContacto=mysql_result($respAd1,0,0);
				
		//INSERTAMOS LA CABECERA
		$sql="select max(cod_reg_visita) from reg_visita_cab";
		$resp=mysql_query($sql);
		$dat=mysql_fetch_array($resp);
		$num_filas=mysql_num_rows($resp);
		if($num_filas==0)
		{	$codigo=1;
		}
		else
		{	$codigo=$dat[0];
			$codigo++;
		}
		$sqlInsertCab="INSERT INTO `reg_visita_cab`(`COD_REG_VISITA`,`COD_CONTACTO`,`COD_VISITADOR`,`COD_MED`,
							`COD_ESPE`, `COD_CAT`,`COD_GESTION`,`COD_CICLO`,`COD_DIA_CONTACTO`,`FECHA_REGISTRO`,`HORA_REGISTRO`,`FECHA_VISITA`) VALUES 
							($codigo, $codContacto, $codVisitador, $codMed, '$codEspe', '$catMed', $codGestion, $codCiclo, $codDiaContacto,
							'$fechaReg', '$horaReg', '$fechaVisita')";
		$respInsertCab=mysql_query($sqlInsertCab);
		
		//AHORA SE SACAN LOS DETALLES
		$sqlDetalle="select r.`muestra`, r.`cantidad_muestra_entregado`,r.`cantidad_muestraextra_entregado`,
						r.`material_apoyo`,r.`cantidad_material_entregado`,r.`cantidad_materialextra_entregado`, r.`obs` 
						from `registro_visita` r where r.`codigo_gestion` = 1007 and r.`codigo_ciclo` in ($codCiclo) and 
						r.`cod_contacto`=$codContacto and r.`orden_visita`=$ordenVisita";
		$respDetalle=mysql_query($sqlDetalle);
		while($datDetalle=mysql_fetch_array($respDetalle)){
				$codMuestra=$datDetalle[0];
				$cantMuestra=$datDetalle[1];
				$cantExtMuestra=$datDetalle[2];
				$codMaterial=$datDetalle[3];
				$cantMaterial=$datDetalle[4];
				$cantExtMaterial=$datDetalle[5];
				$obs=$datDetalle[6];
				
				$sqlInsertDetalle="INSERT INTO `reg_visita_detalle` (`COD_REG_VISITA`,`COD_MUESTRA`,`CANT_MM_ENT`,`CANT_MM_EXTENT`,
										`COD_MATERIAL`,`CANT_MAT_ENT`,`CANT_MAT_EXTENT`,`OBS_REGISTRO`) VALUES 
										($codigo, '$codMuestra', $cantMuestra, $cantExtMuestra, $codMaterial, $cantMaterial, $cantExtMaterial, '$obs')";
				$respInsertDetalle=mysql_query($sqlInsertDetalle);
				echo $sqlInsertDetalle;
		}
						
}
?>