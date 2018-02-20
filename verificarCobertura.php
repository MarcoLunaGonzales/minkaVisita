<?php
	require("conexion.inc");
	include("funcionScaner.php");

		$sqlBoletas="select b.id_boleta, fecha_visita from boletas_visita_cabXXX b where b.estado=1 
		and b.fecha_visita BETWEEN '2017-10-01 00:00:00' and '2017-10-31 23:59:59' and id_visitador_hermes=1398";
		$respBoletas=mysql_query($sqlBoletas);
		while($datBoletas=mysql_fetch_array($respBoletas)){
			$fecha=$datBoletas[1];
			$idBoleta=$datBoletas[0];
			
			/*empezamos a sacar los datos del registro de las boletas*/
			$sqlContacto="select b.fecha, b.cod_contacto, b.id_medico from boletas_visita_cabXXX b where b.id_boleta='$idBoleta'";
			$respContacto=mysql_query($sqlContacto);
			$fechaVisitaX=mysql_result($respContacto,0,0);
			$codContactoX=mysql_result($respContacto,0,1);
			$codMedX=mysql_result($respContacto,0,2);
			
			$sqlVerifica="select * from reg_visita_cab where cod_contacto='$codContactoX' and cod_med='$codMedX'";
			$respVerifica=mysql_query($sqlVerifica);
			$numFilas=mysql_num_rows($respVerifica);
			
			if($numFilas==0){
				$sqlDatosCab="select rc.codigo_gestion, rc.codigo_ciclo, rm.cod_visitador, rd.cod_med, rd.cod_especialidad, rd.categoria_med, o.id from
				rutero_maestro_detalle_aprobado rd, rutero_maestro_aprobado rm, orden_dias o, rutero_maestro_cab_aprobado rc
				where rc.cod_rutero=rm.cod_rutero and rd.cod_contacto=rm.cod_contacto and rd.cod_visitador=rm.cod_visitador
				and rc.cod_visitador=rm.cod_visitador and o.dia_contacto=rm.dia_contacto and
				 rd.cod_contacto='$codContactoX' and rd.cod_med='$codMedX'";
				$respDatosCab=mysql_query($sqlDatosCab);
				$codGestionX=mysql_result($respDatosCab,0,0);
				$codCicloX=mysql_result($respDatosCab,0,1);
				$codVisitadorX=mysql_result($respDatosCab,0,2);
				//$codMedX
				$codEspecialidadX=mysql_result($respDatosCab,0,4);
				$categoriaX=mysql_result($respDatosCab,0,5);
				$idDiaX=mysql_result($respDatosCab,0,6);
				
				//sacamos el codigo
				//GENERAMOS EL CODIGO CORRELATIVO
				$fecha_registro=date("Y-m-d");
				$hora_registro=date("H:i:s");
				$sql  = "SELECT max(cod_reg_visita) from reg_visita_cab";
				$resp = mysql_query($sql);
				$dat  = mysql_fetch_array($resp);
				$num_filas = mysql_num_rows($resp);
				if ($num_filas == 0) {
					$codigo = 1;
				} else {
					$codigo = $dat[0];
					$codigo++;
				}
				$sqlInsertCab  = "INSERT into reg_visita_cab values 
				($codigo, $codContactoX, $codVisitadorX, $codMedX, '$codEspecialidadX', '$categoriaX', $codGestionX, $codCicloX, $idDiaX, '$fecha_registro', '$hora_registro', '$fechaVisitaX', '0')";
				echo "sqlcab: ".$sqlInsertCab;
				
				$respInsertCab = mysql_query($sqlInsertCab);			
				
				/*fin datos de las boletas*/
				
				$sqlDetalleBoleta="select b.muestra, b.codigo_muestra, b.cantidad_muestra, b.cantidad_material, b.tipo from boletas_visita_detalleXXX b where b.id_boleta=$idBoleta";
				$respDetalleBoleta=mysql_query($sqlDetalleBoleta);
				
				while($datDetalleBoleta=mysql_fetch_array($respDetalleBoleta)){
					$muestra=$datDetalleBoleta[0];
					$codigo_muestra=$datDetalleBoleta[1];
					$cantidad_muestra=$datDetalleBoleta[2];
					$cantidad_material=$datDetalleBoleta[3];
					$tipo=$datDetalleBoleta[4];
					
					//INSERTAMOS EL DETALLE
					$sql = "INSERT into reg_visita_detalle values ($codigo, '$codigo_muestra', $cantidad_muestra, 0, 0, $cantidad_material, 0,'','$tipo')";
					echo "sqlDet: ".$sql;
					
					$resp = mysql_query($sql);				
					
					//FIN INSERTAR DETALLE
				}
				
			}
			
			$sql_upd = "UPDATE rutero_detalle set estado = 1 where cod_contacto = $codContactoX and cod_med = $codMedX";
			echo "upd: ".$sql_upd;
			$resp_upd = mysql_query($sql_upd);
		
		}

	
?>
