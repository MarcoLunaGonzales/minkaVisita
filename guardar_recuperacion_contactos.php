<?php
require("conexion.inc");
require("estilos_visitador.inc");

$codMedicosObservados="0";
$contador=0;

$sql="SELECT cod_contacto, dia_contacto, turno, zona_viaje from rutero_maestro where cod_rutero='$rutero_rec' 
and cod_visitador=$global_visitador";

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)) {	
	$cod_contacto=$dat[0];
	$dia_contacto=$dat[1];
	$turno=$dat[2];
	$zona_viaje=$dat[3];
		//sacamos el codigo que corresponde al contacto
	$sql_aux=mysql_query("SELECT max(cod_contacto)+1 from rutero_maestro");
	$codigo_contacto_actual=mysql_result($sql_aux,0,0);		

	$sql_inserta="INSERT into rutero_maestro (cod_contacto, cod_rutero, cod_visitador, dia_contacto, turno, zona_viaje)
	values('$codigo_contacto_actual','$rutero_trabajo','$global_visitador','$dia_contacto','$turno','$zona_viaje')";
	$resp_inserta=mysql_query($sql_inserta);
	
	$sql_det="SELECT cod_contacto, orden_visita, cod_visitador, cod_med, cod_especialidad, categoria_med, cod_zona, estado, tipo 
	from rutero_maestro_detalle where cod_contacto=$cod_contacto";
	$resp_det=mysql_query($sql_det);
	while($dat_det=mysql_fetch_array($resp_det)) {	
		$orden_visita=$dat_det[1];
		$cod_med=$dat_det[3];
		$cod_espe=$dat_det[4];
		$cat_med=$dat_det[5];
		$cod_zona=$dat_det[6];
		
		//verificamos si el medico esta fuera de la linea y fuera de la asignacion del visitador
		$sqlVeriLinea="select count(*) from categorias_lineas c where 
			c.cod_med='$cod_med' and c.codigo_linea='$global_linea' and c.cod_especialidad='$cod_espe' and c.categoria_med='$cat_med'";
		$respVeriLinea=mysql_query($sqlVeriLinea);
		$banderaVeriLinea=mysql_result($respVeriLinea,0,0);
		$txtVeriLinea="";
		if($banderaVeriLinea==0){
			$txtVeriLinea="<span style='color:red'>[Retirado/Linea]</span>";
		}
		
		$sqlVeriAsignacion="select count(*) from medico_asignado_visitador m where m.cod_med='$cod_med' and 
			m.codigo_visitador='$global_visitador' and m.codigo_linea='$global_linea'";
		$respVeriAsignacion=mysql_query($sqlVeriAsignacion);
		$banderaVeriAsignacion=mysql_result($respVeriAsignacion,0,0);
		$txtVeriAsignacion="";
		if($banderaVeriAsignacion==0){
			$txtVeriAsignacion="<span style='color:red'>[Retirado/Visitador]</span>";
		}
		//
		if($banderaVeriLinea==0 || $banderaVeriAsignacion==0){
			$codMedicosObservados.= ",".$cod_med;
			$contador++;
		}else{
			//si el medico no tiene observaciones se inserta en el nuevo.
			$sql_inserta1="INSERT into rutero_maestro_detalle (cod_contacto, orden_visita, cod_visitador, cod_med, cod_especialidad, 
				categoria_med, cod_zona, estado, tipo) 
				values($codigo_contacto_actual,$orden_visita,'$global_visitador',$cod_med,'$cod_espe','$cat_med','$cod_zona',0, 2)";
			$resp_inserta1=mysql_query($sql_inserta1);
		}		
	}
	//ACA REORDENAMOS EL CONTACTO
	$sqlOrdena="select orden_visita, cod_med from rutero_maestro_detalle where cod_contacto='$codigo_contacto_actual' and 
		cod_visitador='$global_visitador' order by orden_visita";		
	//echo $sqlOrdena;
	$respOrdena=mysql_query($sqlOrdena);
	$contadorOrdena=1;
	while($datOrdena=mysql_fetch_array($respOrdena)){
		$ordenVisitaX=$datOrdena[0];
		$codMedX=$datOrdena[1];
		//aqui ejecutamos el update de reordenamiento
		$sqlUpdOrdena="update rutero_maestro_detalle set orden_visita='$contadorOrdena' where 
			cod_med='$codMedX' and cod_contacto='$codigo_contacto_actual'";
		$respUpdOrdena=mysql_query($sqlUpdOrdena);
		$contadorOrdena++;
	}
	//FIN REORDENAR CONTACTO

}
if($contador>0){
	echo "<h1>Rutero copiado con observaciones</h1>";

	echo "<center><table class='texto'>
	<tr><th>Medico(s) excluidos del rutero copiado</th></tr>";
	$sqlMedicos="select distinct(cod_med), concat(ap_pat_med,' ',ap_mat_med,' ',nom_med) from medicos where 
		cod_med in ($codMedicosObservados)";
	$respMedicos=mysql_query($sqlMedicos);
	while($datMedicos=mysql_fetch_array($respMedicos)){
		$codMedicoX=$datMedicos[0];
		$nombreMedicoX=$datMedicos[1];
		echo "<tr><td>$nombreMedicoX</td></tr>";
	}
	echo "</table></center>";
	
}else{
	echo "<h1>Rutero copiado correctamente</h1>";
}
	echo"\n<center>
	<a href='rutero_maestro_todo.php?rutero=$rutero_trabajo'>
	<img  border='0' src='imagenes/back.png' width='40'></a>
	</center>";
?>