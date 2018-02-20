<?php
require("conexion.inc");
$cicloTrabajo=$ciclo;
$gestionTrabajo=$gestion;
$territorioTrabajo=$territorio;

//$cicloTrabajo=9;
//$gestionTrabajo=1006;
//$territorioTrabajo=114;

$sqlTerritorio="select codigo_funcionario from funcionarios where cod_ciudad=$territorioTrabajo
								 and estado=1";
$respTerritorio=mysql_query($sqlTerritorio);
while($datTerritorio=mysql_fetch_array($respTerritorio)){
	$codFuncionario=$datTerritorio[0];
	
	$sqlRuteros="select rd.cod_contacto, rd.orden_visita, rd.estado, rd.cod_med, 
							rd.cod_especialidad, rd.categoria_med, r.codigo_linea
							from rutero_detalle rd, rutero r 
							where rd.estado=1 and rd.cod_contacto=r.cod_contacto and r.cod_ciclo=$cicloTrabajo 
							and r.codigo_gestion=$gestionTrabajo and r.cod_visitador=$codFuncionario";								
	$respRuteros=mysql_query($sqlRuteros);
	while($datRuteros=mysql_fetch_array($respRuteros)){
			$codContacto=$datRuteros[0];
			$ordenVisita=$datRuteros[1];
			$estado=$datRuteros[2];
			$codMed=$datRuteros[3];
			$codEspecialidad=$datRuteros[4];
			$categoriaMed=$datRuteros[5];
			$codigoLinea=$datRuteros[6];
			
			$sqlVerifica="select * from registro_visita where cod_contacto=$codContacto and orden_visita=$ordenVisita";
			$respVerifica=mysql_query($sqlVerifica);
			$numFilasVerifica=mysql_num_rows($respVerifica);
			if($numFilasVerifica==0){
				//NO SE GUARDARON SUS PRODUCTOS
				$sqlNumParrilla="select rd.cod_contacto
												from rutero_detalle rd, rutero r
												where rd.cod_contacto=r.cod_contacto and r.cod_ciclo=$cicloTrabajo and 
												r.codigo_gestion=$gestionTrabajo and r.cod_visitador=$codFuncionario 
												and rd.cod_med=$codMed 
												and rd.cod_especialidad='$codEspecialidad' and rd.categoria_med='$categoriaMed' 
												and r.codigo_linea=$codigoLinea order by rd.cod_contacto";
				$respNumParrilla=mysql_query($sqlNumParrilla);
				$contador=1;
				while($datNumParrilla=mysql_fetch_array($respNumParrilla)){
						$codContactoNuevo=$datNumParrilla[0];
						if($codContacto==$codContactoNuevo){
							$numVisita=$contador;
						}
						$contador++;
				}
				echo "$codContacto $ordenVisita $estado NUMERO DE VISITA $numVisita <br>";
				//SACAMOS LA PARRILLA CORRESPONDIENTE
				$sqlParrilla="select pd.codigo_muestra, pd.cantidad_muestra, pd.codigo_material, pd.cantidad_material, 
											pd.codigo_parrilla, pd.prioridad
											from parrilla p, parrilla_detalle pd
											where p.codigo_parrilla=pd.codigo_parrilla and p.cod_ciclo=$cicloTrabajo and p.codigo_gestion=$gestionTrabajo and 
								     	p.cod_especialidad='$codEspecialidad' and p.categoria_med='$categoriaMed' and 
								     	p.numero_visita=$numVisita and p.codigo_linea=$codigoLinea";
				$respParrilla=mysql_query($sqlParrilla);
				while($datParrilla=mysql_fetch_array($respParrilla)){
						$codigoMuestra=$datParrilla[0];
						$cantidadMuestra=$datParrilla[1];
						$codigoMaterial=$datParrilla[2];
						$cantidadMaterial=$datParrilla[3];	
						$codigoParrilla=$datParrilla[4];
						$prioridad=$datParrilla[5];
						//INSERTAMOS EN EL REGISTRO DE VISITA
						$sqlInsertaProd="insert into registro_visita(codigo_ciclo, codigo_gestion, cod_contacto, orden_visita, 
															codigo_parrilla, prioridad, muestra, cantidad_muestra_entregado, 
															cantidad_muestraextra_entregado, material_apoyo, cantidad_material_entregado, 
															cantidad_materialextra_entregado, extra_parrilla, fecha_registro, hora_registro, obs,
															fecha_visita) values ($cicloTrabajo, $gestionTrabajo, $codContacto, $ordenVisita,
															$codigoParrilla, $prioridad, '$codigoMuestra', $cantidadMuestra, 0, $codigoMaterial,
															$cantidadMaterial, 0, 0, '', '', '', '')";
						$respInsertaProd=mysql_query($sqlInsertaProd);
						echo $sqlInsertaProd;																
				}	
			}			
	}
}
?>