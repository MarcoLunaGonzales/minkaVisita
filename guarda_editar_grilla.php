<?php

require("conexion.inc");
require("estilos_gerencia.inc");
$fecha=date("Y-m-d");
$codigo_linea_s =$_POST['codigo_linea'];
$idDistrito=$_POST['idDistrito'];

$sql_update="UPDATE grilla set nombre_grilla='$nombre_grilla', total_medicos='$cant_medicos', 
total_contactos='$cant_contactos', total_visitadores='$cant_visitadores',contactos_visitador='$contacto_vis', 
fecha_modificacion='$fecha', cod_distrito='$idDistrito' where codigo_grilla='$codigo'";
$resp_update=mysql_query($sql_update);
if($resp_update==1) {	
	for($i=1;$i<=($cantidad-1);$i++) {	
		$frecuencia="frecuencia$i";
		$contacto="contacto$i";
		$medico="medicos$i";
		$especialidad="especialidad$i";
		$categoria="categoria$i";
		$lineavisita="lineaVisita$i";
		$val_frecuencia=$$frecuencia;
		$val_contacto=$$contacto;
		$val_medico=$$medico;
		$val_espe=$$especialidad;
		$val_cat=$$categoria;
		$val_lineaVisita=$$lineavisita;

		$sql_verifica="SELECT * from grilla_detalle where cod_especialidad='$val_espe' and cod_categoria='$val_cat' 
		and codigo_grilla='$codigo' and cod_linea_visita='$val_lineaVisita'";
		// echo $sql_verifica."<br />";
		$resp_verifica=mysql_query($sql_verifica);
		$filas_verifica=mysql_num_rows($resp_verifica);
		if($codigo_linea_s == 1031){
			$sql_cambia_linea_sac_pun = mysql_query("SELECT codigo_l_visita1031,codigo_l_visita1021 from lineas_visita_trans_sac_pun where codigo_l_visita1021 = $val_lineaVisita");
			echo("SELECT codigo_l_visita1031,codigo_l_visita1021 from lineas_visita_trans_sac_pun where codigo_l_visita1021 = $val_lineaVisita")."<br />";
			if(mysql_num_rows($sql_cambia_linea_sac_pun == 0)){
				$val_lineaVisita = 0;
				$val_lineaVisitaa = $val_lineaVisita;
			}else{
				$val_lineaVisita = mysql_result($sql_cambia_linea_sac_pun, 0, 0);
				$val_lineaVisitaa = mysql_result($sql_cambia_linea_sac_pun, 0, 1);	
			}
		}
		if($filas_verifica==0) {	
			$sql_inserta_detalle="INSERT into grilla_detalle values($codigo,'$val_espe','$val_cat',$val_frecuencia,$val_lineaVisita)";
			$resp_inserta_detalle=mysql_query($sql_inserta_detalle);
			//echo $sql_inserta_detalle."<br>";
		}
		else {	
			$sql_update_detalle="UPDATE grilla_detalle set frecuencia='$val_frecuencia', cod_linea_visita='$val_lineaVisita'
			where codigo_grilla='$codigo' and cod_especialidad='$val_espe' and cod_categoria='$val_cat' and cod_linea_visita='$val_lineaVisitaa'";
			$resp_update_detalle=mysql_query($sql_update_detalle);
		}
	}
	 echo "<script language='Javascript'>
	 	alert('Los datos fueron insertados correctamente.');
	 		location.href='navegador_grillas.php?cod_ciudad=$cod_ciudad&codigo_linea=$codigo_linea';
	 		</script>";
}
else
{
	// echo "<script language='Javascript'>
	// 	  alert('Los datos no se pudieron modificar.');
	// 	  history.back(-1);
	//       </script>";
}
?>