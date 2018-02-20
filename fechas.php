<?php
require("conexion.inc");
$sql=mysql_query("select cod_ciclo,fecha_ini,fecha_fin from ciclos where estado='Activo' and codigo_linea='1002'");
	$dat=mysql_fetch_array($sql);
	$ciclo_actual=$dat[0];
	$fecha_ini_actual=$dat[1];
	$fecha_fin_actual=$dat[2];
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
			$num_dia=intval($k/5);
			if($dia_semana=='Monday'){$dias[$k]="Lun $num_dia";}
			if($dia_semana=='Tuesday'){$dias[$k]="Mar $num_dia";}
			if($dia_semana=='Wednesday'){$dias[$k]="Mie $num_dia";}
			if($dia_semana=='Thursday'){$dias[$k]="Jue $num_dia";}
			if($dia_semana=='Friday'){$dias[$k]="Vie $num_dia";}
			
			$fecha_actual=date("Y-m-d",$nueva1);
			$fechas[$k]=$fecha_actual;
			$inicio=$fecha_actual;
			$k++;
			list($anio,$mes,$dia)=explode("-",$fecha_actual);
			$dia1=$dia+1;			
		}
		for($i=0;$i<=$k-1;$i++)
		{	echo "$fechas[$i] $dias[$i] <br>";
		}
?>