<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	$sql_cab="select paterno,materno,nombres from funcionarios where codigo_funcionario='$visitador'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_funcionario="$dat_cab[0] $dat_cab[1] $dat_cab[2]";
	echo "<form method='post' action='opciones_medico.php'>";
	//esta parte saca el ciclo activo
	$sqlnombreRutero="select nombre_rutero from rutero_maestro_cab where cod_rutero='$rutero'";
	$respNombreRutero=mysql_query($sqlnombreRutero);
	$nombreRutero=mysql_result($respNombreRutero,0,0);
	
	if($global_zona_viaje==1)
	{	$sql="select r.cod_contacto, r.cod_rutero, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje from rutero_maestro r, orden_dias o where r.cod_visitador=$visitador and r.cod_rutero='$rutero' and r.zona_viaje='1' and r.dia_contacto=o.dia_contacto order by o.id";
	}
	else
	{	$sql="select r.cod_contacto, r.cod_rutero, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje from rutero_maestro r, orden_dias o where r.cod_visitador=$visitador and r.cod_rutero='$rutero' and r.zona_viaje='0' and r.dia_contacto=o.dia_contacto order by o.id";
	}
	$resp=mysql_query($sql);
	
	echo "<h1>Rutero Medico Maestro<br>Visitador: $nombre_funcionario<br>Rutero: $nombreRutero
	</h1>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>Dia Contacto</th><th>Turno</th><th>Contactos</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_contacto=$dat[0];
		$cod_ciclo=$dat[1];
		$dia_contacto=$dat[3];
		$turno=$dat[4];
		if($vista==2){
			$tabla="rutero_maestro_detalle_aprobado";
		}else{
			$tabla="rutero_maestro_detalle";
		}
		$sql1="select c.orden_visita, m.ap_pat_med, m.ap_mat_med, m.nom_med, d.direccion, c.cod_especialidad, c.categoria_med, c.estado
				from $tabla c, medicos m, direcciones_medicos d
					where (c.cod_contacto=$cod_contacto) and (c.cod_visitador=$visitador) and (c.cod_med=m.cod_med) and (m.cod_med=d.cod_med) and (c.cod_zona=d.numero_direccion) order by c.orden_visita";
		$resp1=mysql_query($sql1);
		$contacto="<table class='textomini' width='100%'>";
		$contacto=$contacto."<tr><th width='5%'>Orden</th><th width='35%'>Medico</th><th width='10%'>Especialidad</th><th width='10%'>Categoria</th><th width='30%'>Direccion</th></tr>";
		while($dat1=mysql_fetch_array($resp1))
		{
			$orden_visita=$dat1[0];
			$pat=$dat1[1];
			$mat=$dat1[2];
			$nombre=$dat1[3];
			$direccion=$dat1[4];
			$nombre_medico="$pat $mat $nombre";
			$espe=$dat1[5];
			$cat=$dat1[6];
			$contacto=$contacto."<tr><td align='center'>$dat1[0]</td><td>&nbsp;$nombre_medico</td><td align='center'>$espe</td><td align='center'>$cat</td><td>$direccion </td></tr>";
		}
		$contacto=$contacto."</table>";
		echo "<tr><td align='center'>$dia_contacto</td><td align='center'>$turno</td><td align='center'>$contacto</td></tr>";
	}
	echo "</table></center><br>";
	
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	echo "</form>";
?>