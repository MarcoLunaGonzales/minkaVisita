<?php
require("conexion.inc");
require("estilos_gerencia.inc");

//$ciclo=$_GET['cod_ciclo'];
$ciclo=$cod_ciclo;

list($codCiclo,$codGestion)=explode("|",$ciclo);

$cod_ciclo=$codCiclo;
$cod_gestion=$codGestion;


$cod_gestion=1014;

//activamos el ciclo

$sqlActiva="update ciclos set estado='Cerrado' where estado='Activo'";
$respActiva=mysql_query($sqlActiva);
$sqlActiva="update ciclos set estado='Activo' where cod_ciclo='$cod_ciclo' and codigo_gestion='$cod_gestion'";
$respActiva=mysql_query($sqlActiva);

$sqlVeri="select * from rutero where codigo_gestion='$cod_gestion' and cod_ciclo='$cod_ciclo'";
$respVeri=mysql_query($sqlVeri);
$filasVeri=mysql_num_rows($respVeri);

if($filasVeri==0){
	$sql_cab="select f.codigo_funcionario, l.codigo_linea
		from funcionarios f, funcionarios_lineas l
		where f.codigo_funcionario=l.codigo_funcionario and f.cod_cargo='1011' and f.estado=1";
	//echo $sql_cab;
	$resp_cab=mysql_query($sql_cab);

	while($dat_cab=mysql_fetch_array($resp_cab))
	{	$global_visitador=$dat_cab[0];
		$global_linea=$dat_cab[1];
		$sql="select r.cod_rutero, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje, r.cod_contacto
			from rutero_maestro_aprobado r, rutero_maestro_cab_aprobado rc
			where r.cod_rutero=rc.cod_rutero and rc.estado_aprobado='1' and rc.cod_visitador=r.cod_visitador and 
			rc.cod_visitador='$global_visitador' and rc.codigo_linea='$global_linea' and 
			rc.codigo_gestion='$cod_gestion' and rc.codigo_ciclo='$cod_ciclo'";
		$resp=mysql_query($sql);
		while($datos=mysql_fetch_array($resp))
		{	$codigo_rutero=$datos[0];
			$codigo_vis=$datos[1];
			$dia_contacto=$datos[2];
			$turno=$datos[3];
			$zona_viaje=$datos[4];
			$cod_contacto_maestro=$datos[5];
			
			//formamos el codigo de contacto
			$sql_cod="select max(cod_contacto) from rutero";
			$resp_cod=mysql_query($sql_cod);
			$num_filas=mysql_num_rows($resp_cod);
			if($num_filas==0)
			{	$cod_contacto=1000;
			}
			else
			{	$dat_cod=mysql_fetch_array($resp_cod);
				$cod_contacto=$dat_cod[0];
				$cod_contacto=$cod_contacto+1;
			}
			
			$cod_contacto=$cod_contacto_maestro;
			
			//fin formar codigo contacto
			$sql_inserta="insert into rutero values('$cod_ciclo','$cod_gestion','$cod_contacto','$codigo_vis','$dia_contacto','$turno','$zona_viaje','$global_linea')";
			$resp_inserta=mysql_query($sql_inserta);
			
			/*//insertamos en la tabla de rutero auxiliar denominada rutero_utilizado
			$sql_inserta_extra="insert into rutero_utilizado values('$cod_ciclo','$cod_gestion','$cod_contacto','$codigo_vis','$dia_contacto','$turno','$zona_viaje','$global_linea')";
			$resp_inserta_extra=mysql_query($sql_inserta_extra);
			*/

			//aqui insertamos en la tabla de rutero_detalle
			$sql_detalle="select * from rutero_maestro_detalle where cod_contacto='$cod_contacto_maestro'";
			$resp_detalle=mysql_query($sql_detalle);
			while($datos_detalle=mysql_fetch_array($resp_detalle))
			{	$orden_visita=$datos_detalle[1];
				$cod_med=$datos_detalle[3];
				$cod_espe=$datos_detalle[4];
				$categoria_med=$datos_detalle[5];
				$zona=$datos_detalle[6];
				$estado=$datos_detalle[7];
				
				$sql_inserta_det="insert into rutero_detalle values('$cod_contacto','$orden_visita','$codigo_vis','$cod_med','$cod_espe','$categoria_med','$zona','$estado')";
				$resp_inserta_det=mysql_query($sql_inserta_det);

				/*//insertamos en la tabla rutero_detalle_utilizado
				$sql_inserta_det_extra="insert into rutero_detalle_utilizado values('$cod_contacto','$orden_visita','$codigo_vis','$cod_med','$cod_espe','$categoria_med','$zona','$estado')";
				$resp_inserta_det_extra=mysql_query($sql_inserta_det_extra);
				*/
			}
		}
	}

	$sqlParrilla="insert into parrilla_personalizadareg (cod_gestion, cod_ciclo, cod_linea, cod_med, numero_visita, orden_visita, cod_mm, cantidad_mm, 
	cod_ma, cantidad_ma) select cod_gestion, cod_ciclo, cod_linea, cod_med, numero_visita, orden_visita, cod_mm, cantidad_mm, 
	cod_ma, cantidad_ma from parrilla_personalizada where cod_gestion=$codGestion and cod_ciclo=$cod_ciclo";
	$respParrilla=mysql_query($sqlParrilla);
	
}




echo "<script language='Javascript'>
			alert('Se activo el ciclo correctamente.');
			location.href='navegador_activar_ciclos.php';
	</script>";
?>