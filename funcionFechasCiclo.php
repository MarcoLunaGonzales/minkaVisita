<?php
function devuelveDiaRegistro($fecha){
	
	$ciclo_global=4;
	$codigo_gestion=1007;
	$global_linea=1021;
	
	require('conexion.inc');
	
	$sql_dias_ini_fin="select fecha_ini,fecha_fin from ciclos where cod_ciclo='$ciclo_global' and 
	codigo_gestion='$codigo_gestion'";

	
	$resp_dias_ini_fin=mysql_query($sql_dias_ini_fin);
	$dat_dias=mysql_fetch_array($resp_dias_ini_fin);
	$fecha_ini_actual=$dat_dias[0];
	$fecha_fin_actual=$dat_dias[1];
		
	$fecha_actual=$fecha_ini_actual;
	$inicio=$fecha_ini_actual;
	$k=0;
	list($anio,$mes,$dia)=explode("-",$fecha_actual);
	$dia1=$dia;
		while($inicio < $fecha_fin_actual)
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
			$fecha_actual_formato1="$anio-$mes-$dia";

			$fechas[$k]=$fecha_actual_formato1;
			$k++;
		}

		for($ii=0;$ii<=19;$ii++){
			if($fecha==$fechas[$ii]){
				$fecha_devolver=$dias[$ii];
			}
		}
		return($fecha_devolver);
}

?>