<?php
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=archivo.xls"); 	
require("conexion.inc");
require("estilos_reportes_xls.inc");
//esta parte saca el dia de contacto actual
	$sql_dias_ini_fin="select fecha_ini,fecha_fin from ciclos where cod_ciclo='$ciclo_global' and codigo_linea='$global_linea'";
	$resp_dias_ini_fin=mysql_query($sql_dias_ini_fin);
	$dat_dias=mysql_fetch_array($resp_dias_ini_fin);
	$fecha_ini_actual=$dat_dias[0];
	$fecha_fin_actual=$dat_dias[1];
	$fecha_actual=$fecha_ini_actual;
	$inicio=$fecha_ini_actual;
	$k=0;
	list($anio,$mes,$dia)=explode("-",$fecha_actual);
	$dia1=$dia;
		while($inicio<$fecha_fin_actual)
		{
			//echo $inicio."<br>";
			$ban=0;
			while($ban==0)
			{	$nueva1 = mktime(0,0,0, $mes,$dia1,$anio);
				$dia_semana=date("l",$nueva1);
				if($dia_semana=='Sunday' or $dia_semana=='Saturday')
				{	$dia1=$dia1+1;
				}
				else
				{	$ban=1;
				}
			}
			$num_dia=intval($k/5)+1;
			if($dia_semana=='Monday'){$dias[$k]="Lunes $num_dia";}
			if($dia_semana=='Tuesday'){$dias[$k]="Martes $num_dia";}
			if($dia_semana=='Wednesday'){$dias[$k]="Miercoles $num_dia";}
			if($dia_semana=='Thursday'){$dias[$k]="Jueves $num_dia";}
			if($dia_semana=='Friday'){$dias[$k]="Viernes $num_dia";}
			
			$fecha_actual=date("Y-m-d",$nueva1);
			$inicio=$fecha_actual;
			list($anio,$mes,$dia)=explode("-",$fecha_actual);
			$dia1=$dia+1;			
			$fecha_actual_formato="$dia/$mes/$anio";
			$fechas[$k]=$fecha_actual_formato;
			$k++;
		}
	//fin vectores dias y fechas
	$contador=1;
	//desde aqui sacamos las fechas nuevas
	$fecha_sistema=date("d/m/Y");
	list($d_actual,$m_actual,$a_actual)=explode("/",$fecha_sistema);
	$sec_actual=mktime(0,0,0,$m_actual,$d_actual,$a_actual);
	for($i=0;$i<=$k-1;$i++)
	{	list($d_comp,$m_comp,$a_comp)=explode("/",$fechas[$i]);	
		$sec_comp=mktime(0,0,0,$m_comp,$d_comp,$a_comp);
		if($sec_comp<=$sec_actual)
		{	$posicion=$i;
		}
	}
$dia_contacto_sistema=$dias[$posicion];
$sql_id="select id from orden_dias where dia_contacto='$dia_contacto_sistema'";
$resp_id=mysql_query($sql_id);
$dat_id=mysql_fetch_array($resp_id);
$id_sistema=$dat_id[0];
//fin saca dia contacto actual
$bandera=0;
if($semana=="" && $dia_contacto=="")
{	$sql="select r.cod_contacto, r.dia_contacto from rutero r, orden_dias o where r.dia_contacto=o.dia_contacto and o.id<$id_sistema and r.cod_ciclo='$ciclo_global' and r.cod_visitador='$global_visitador' order by o.id";
	$resp=mysql_query($sql);
	echo "<center>Productos Entregados</center><br>";
	echo "<center><table border='1' class='textomini' width='100%' cellspacing='0'>";
	echo "<tr><th>Dia Contacto</th><th>Medicos</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_contacto=$dat[0];
		$dia_contacto=$dat[1];
		$sql_registro_visita="select muestra, cantidad_muestra_entregado, material_apoyo, cantidad_material_entregado from registro_visita where cod_contacto='$cod_contacto' and codigo_ciclo='$ciclo_global' and codigo_gestion='$codigo_gestion'";
		$resp_registro_visita=mysql_query($sql_registro_visita);
		$filas_registro_visita=mysql_num_rows($resp_registro_visita);
		if($filas_registro_visita!=0)
		{	$sql_detalle="select orden_visita, cod_med, cod_especialidad, categoria_med from rutero_detalle where cod_contacto='$cod_contacto' and estado='1' order by orden_visita";
			$resp_detalle=mysql_query($sql_detalle);
			$medicos_visitados="";
			$medicos_visitados.="<table border=1 width='100%' cellspacing=0 class='textomini'><tr><th width='5%'>Orden Visita</th><th width='20%'>Medico</th><th width='5%'>Especialidad</th><th width='5%'>Categoria</th><th width='55%'>Productos Entregados</th></tr>";
			while($dat_detalle=mysql_fetch_array($resp_detalle))
			{	$orden_visita=$dat_detalle[0];
				$cod_med=$dat_detalle[1];
				$cod_especialidad=$dat_detalle[2];
				$categoria=$dat_detalle[3];
				//saca los productos visitados del medico
				$sql_aux_prod="select muestra, cantidad_muestra_entregado, material_apoyo, cantidad_material_entregado from registro_visita where cod_contacto='$cod_contacto' and orden_visita='$orden_visita' and codigo_ciclo='$ciclo_global' and codigo_gestion='$codigo_gestion'";
				$resp_aux_prod=mysql_query($sql_aux_prod);
				$productos_entregados="";
				$productos_entregados.="<table class='textomini' width='100%' border=1><tr><th>Producto</th><th>Cantidad</th><th>Material Apoyo</th><th>Cantidad</th></tr>";	
				while($dat_productos=mysql_fetch_array($resp_aux_prod))
				{	$codigo_producto=$dat_productos[0];
					$cantidad_producto=$dat_productos[1];
					$material_apoyo=$dat_productos[2];
					$cantidad_material=$dat_productos[3];
					$sql_nombre_producto=mysql_query("select descripcion, presentacion from muestras_medicas where codigo='$codigo_producto'");
					$dat_nombre_producto=mysql_fetch_array($sql_nombre_producto);
					$nombre_producto="$dat_nombre_producto[0] $dat_nombre_producto[1]";
					$sql_nombre_material=mysql_query("select descripcion_material from material_apoyo where codigo_material='$material_apoyo'");
					$dat_nombre_material=mysql_fetch_array($sql_nombre_material);
					$nombre_material=$dat_nombre_material[0];
					$productos_entregados.="<tr><td>$nombre_producto</td><td>$cantidad_producto</td><td>$nombre_material</td><td>$cantidad_material</td></tr>";
				}
				$productos_entregados.="</table>";
				//fin prod visitados
				$sql_nombre_med=mysql_query("select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$cod_med'");
				$dat_nombre_med=mysql_fetch_array($sql_nombre_med);
				$nombre_medico="$dat_nombre_med[0] $dat_nombre_med[1] $dat_nombre_med[2]";
				$medicos_visitados.="<tr><td align='center'>$orden_visita</td><td>$nombre_medico</td><td align='center'>$cod_especialidad</td><td align='center'>$categoria</td><td>$productos_entregados</td></tr>";
			}
			$medicos_visitados.="</table>";
			echo "<tr><td align='center'>$dia_contacto</td><td align='center'>$medicos_visitados</td></tr>";		
		}
	}
	echo "</table></center><br>";
	$bandera=1;
}
if($semana!="" && $bandera==0)
{	$consulta_semana="(r.dia_contacto='Lunes $semana' or r.dia_contacto='Martes $semana' or r.dia_contacto='Miercoles $semana' or r.dia_contacto='Jueves $semana' or r.dia_contacto='Viernes $semana')";
	$sql="select r.cod_contacto, r.dia_contacto from rutero r, orden_dias o where $consulta_semana and r.dia_contacto=o.dia_contacto and o.id<$id_sistema and r.cod_ciclo='$ciclo_global' and r.cod_visitador='$global_visitador' order by o.id";
	$resp=mysql_query($sql);
	echo "<center>Productos Entregados<br> Semana $semana</center><br>";
	echo "<center><table border='1' class='textomini' width='100%' cellspacing='0'>";
	echo "<tr><th>Dia Contacto</th><th>Medicos</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_contacto=$dat[0];
		$dia_contacto=$dat[1];
		$sql_registro_visita="select muestra, cantidad_muestra_entregado, material_apoyo, cantidad_material_entregado from registro_visita where cod_contacto='$cod_contacto' and codigo_ciclo='$ciclo_global' and codigo_gestion='$codigo_gestion'";
		$resp_registro_visita=mysql_query($sql_registro_visita);
		$filas_registro_visita=mysql_num_rows($resp_registro_visita);
		if($filas_registro_visita!=0)
		{	$sql_detalle="select orden_visita, cod_med, cod_especialidad, categoria_med from rutero_detalle where cod_contacto='$cod_contacto' and estado='1' order by orden_visita";
			$resp_detalle=mysql_query($sql_detalle);
			$medicos_visitados="";
			$medicos_visitados.="<table border=1 width='100%' cellspacing=0 class='textomini'><tr><th width='5%'>Orden Visita</th><th width='20%'>Medico</th><th width='5%'>Especialidad</th><th width='5%'>Categoria</th><th width='55%'>Productos Entregados</th></tr>";
			while($dat_detalle=mysql_fetch_array($resp_detalle))
			{	$orden_visita=$dat_detalle[0];
				$cod_med=$dat_detalle[1];
				$cod_especialidad=$dat_detalle[2];
				$categoria=$dat_detalle[3];
				//saca los productos visitados del medico
				$sql_aux_prod="select muestra, cantidad_muestra_entregado, material_apoyo, cantidad_material_entregado from registro_visita where cod_contacto='$cod_contacto' and orden_visita='$orden_visita' and codigo_ciclo='$ciclo_global' and codigo_gestion='$codigo_gestion'";
				$resp_aux_prod=mysql_query($sql_aux_prod);
				$productos_entregados="";
				$productos_entregados.="<table class='textomini' width='100%' border=1><tr><th>Producto</th><th>Cantidad</th><th>Material Apoyo</th><th>Cantidad</th></tr>";	
				while($dat_productos=mysql_fetch_array($resp_aux_prod))
				{	$codigo_producto=$dat_productos[0];
					$cantidad_producto=$dat_productos[1];
					$material_apoyo=$dat_productos[2];
					$cantidad_material=$dat_productos[3];
					$sql_nombre_producto=mysql_query("select descripcion, presentacion from muestras_medicas where codigo='$codigo_producto'");
					$dat_nombre_producto=mysql_fetch_array($sql_nombre_producto);
					$nombre_producto="$dat_nombre_producto[0] $dat_nombre_producto[1]";
					$sql_nombre_material=mysql_query("select descripcion_material from material_apoyo where codigo_material='$material_apoyo'");
					$dat_nombre_material=mysql_fetch_array($sql_nombre_material);
					$nombre_material=$dat_nombre_material[0];
					$productos_entregados.="<tr><td>$nombre_producto</td><td>$cantidad_producto</td><td>$nombre_material</td><td>$cantidad_material</td></tr>";
				}
				$productos_entregados.="</table>";
				//fin prod visitados
				$sql_nombre_med=mysql_query("select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$cod_med'");
				$dat_nombre_med=mysql_fetch_array($sql_nombre_med);
				$nombre_medico="$dat_nombre_med[0] $dat_nombre_med[1] $dat_nombre_med[2]";
				$medicos_visitados.="<tr><td align='center'>$orden_visita</td><td>$nombre_medico</td><td align='center'>$cod_especialidad</td><td align='center'>$categoria</td><td>$productos_entregados</td></tr>";
			}
			$medicos_visitados.="</table>";
			echo "<tr><td align='center'>$dia_contacto</td><td align='center'>$medicos_visitados</td></tr>";		
		}
	}
	echo "</table></center><br>";
	$bandera=1;
}
if($dia_contacto!="" and $bandera==0)
{	$sql="select r.cod_contacto, r.dia_contacto from rutero r, orden_dias o where r.dia_contacto='$dia_contacto' and r.dia_contacto=o.dia_contacto and o.id<$id_sistema and r.cod_ciclo='$ciclo_global' and r.cod_visitador='$global_visitador' order by o.id";
	$resp=mysql_query($sql);
	echo "<center>Productos Entregados<br>Dia de Contacto $dia_contacto</center><br>";
	echo "<center><table border='1' class='textomini' width='100%' cellspacing='0'>";
	echo "<tr><th>Medicos</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_contacto=$dat[0];
		$dia_contacto=$dat[1];
		$sql_registro_visita="select muestra, cantidad_muestra_entregado, material_apoyo, cantidad_material_entregado from registro_visita where cod_contacto='$cod_contacto' and codigo_ciclo='$ciclo_global' and codigo_gestion='$codigo_gestion'";
		$resp_registro_visita=mysql_query($sql_registro_visita);
		$filas_registro_visita=mysql_num_rows($resp_registro_visita);
		if($filas_registro_visita!=0)
		{	$sql_detalle="select orden_visita, cod_med, cod_especialidad, categoria_med from rutero_detalle where cod_contacto='$cod_contacto' and estado='1' order by orden_visita";
			$resp_detalle=mysql_query($sql_detalle);
			$medicos_visitados="";
			$medicos_visitados.="<table border=1 width='100%' cellspacing=0 class='textomini'><tr><th width='5%'>Orden Visita</th><th width='20%'>Medico</th><th width='5%'>Especialidad</th><th width='5%'>Categoria</th><th width='55%'>Productos Entregados</th></tr>";
			while($dat_detalle=mysql_fetch_array($resp_detalle))
			{	$orden_visita=$dat_detalle[0];
				$cod_med=$dat_detalle[1];
				$cod_especialidad=$dat_detalle[2];
				$categoria=$dat_detalle[3];
				//saca los productos visitados del medico
				$sql_aux_prod="select muestra, cantidad_muestra_entregado, material_apoyo, cantidad_material_entregado from registro_visita where cod_contacto='$cod_contacto' and orden_visita='$orden_visita' and codigo_ciclo='$ciclo_global' and codigo_gestion='$codigo_gestion'";
				$resp_aux_prod=mysql_query($sql_aux_prod);
				$productos_entregados="";
				$productos_entregados.="<table class='textomini' width='100%' border=1><tr><th>Producto</th><th>Cantidad</th><th>Material Apoyo</th><th>Cantidad</th></tr>";	
				while($dat_productos=mysql_fetch_array($resp_aux_prod))
				{	$codigo_producto=$dat_productos[0];
					$cantidad_producto=$dat_productos[1];
					$material_apoyo=$dat_productos[2];
					$cantidad_material=$dat_productos[3];
					$sql_nombre_producto=mysql_query("select descripcion, presentacion from muestras_medicas where codigo='$codigo_producto'");
					$dat_nombre_producto=mysql_fetch_array($sql_nombre_producto);
					$nombre_producto="$dat_nombre_producto[0] $dat_nombre_producto[1]";
					$sql_nombre_material=mysql_query("select descripcion_material from material_apoyo where codigo_material='$material_apoyo'");
					$dat_nombre_material=mysql_fetch_array($sql_nombre_material);
					$nombre_material=$dat_nombre_material[0];
					$productos_entregados.="<tr><td>$nombre_producto</td><td>$cantidad_producto</td><td>$nombre_material</td><td>$cantidad_material</td></tr>";
				}
				$productos_entregados.="</table>";
				//fin prod visitados
				$sql_nombre_med=mysql_query("select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$cod_med'");
				$dat_nombre_med=mysql_fetch_array($sql_nombre_med);
				$nombre_medico="$dat_nombre_med[0] $dat_nombre_med[1] $dat_nombre_med[2]";
				$medicos_visitados.="<tr><td align='center'>$orden_visita</td><td>$nombre_medico</td><td align='center'>$cod_especialidad</td><td align='center'>$categoria</td><td>$productos_entregados</td></tr>";
			}
			$medicos_visitados.="</table>";
			echo "<tr><td align='center'>$medicos_visitados</td></tr>";		
		}
	}
	echo "</table></center><br>";
}
?>