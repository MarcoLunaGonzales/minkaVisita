<?php

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='creacion_contactos_numero.php';
		}
		function recuperar_contactos()
		{	location.href='recuperacion_contactos.php';
		}
		function eliminar_nav(f)
		{
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos un contacto para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_contacto.php?datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}
		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_contacto;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_contacto=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un contacto para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un contacto para editar sus datos.');
				}
				else
				{
					location.href='editar_contacto.php?j_contacto='+j_contacto+'';
				}
			}
		}
		</script>
	";
	
	require("conexion.inc");
	require("estilos_visitador.inc");
	echo "<form method='post' action='opciones_medico.php'>";
	//esta parte saca el ciclo activo
	$sql="SELECT r.cod_ciclo, r.codigo_gestion, r.cod_contacto, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje from rutero r, orden_dias o where r.cod_ciclo='$ciclo_global' and r.codigo_gestion='$codigo_gestion' and r.codigo_linea='$global_linea' and r.cod_visitador=$global_visitador and r.dia_contacto=o.dia_contacto order by o.id";
	//echo $sql;
	$resp=mysql_query($sql);
	echo "<h1>Rutero Actual</h1>";
		
	$sql_numeros="SELECT cd.estado from rutero c, rutero_detalle cd	where c.cod_contacto=cd.cod_contacto and c.cod_ciclo='$ciclo_global' and c.codigo_linea='$global_linea' and c.codigo_gestion='$codigo_gestion' and c.cod_visitador='$global_visitador'";
	$resp_numeros=mysql_query($sql_numeros);
	$total_contactos=mysql_num_rows($resp_numeros);
	$realizados=0;
	$faltantes=0;
	$no_realizados=0;
	while($dat_numeros=mysql_fetch_array($resp_numeros))
	{	$estado=$dat_numeros[0];
		if($estado==0)
		{	$faltantes++;
		}
		if($estado==1)
		{	$realizados++;
		}
		if($estado==2)
		{	$no_realizados++;
		}
	}
	$cobertura=($realizados/$total_contactos)*100;
	$cobertura=round($cobertura);
	echo "<center><table border='0' class='textomini'>";
	echo "<tr><th colspan='3'>Resumen Rutero Actual</th></tr>";
	echo "<tr><td align='left'>Total Contactos: </td><td>$total_contactos</td><td></td></tr>";
	echo "<tr><td align='left'>Realizados: </td><td>$realizados</td><td><img src='imagenes/si.png' width='30'></td></tr>";
	echo "<tr><td align='left'>No Realizados: </td><td>$no_realizados</td><td><img src='imagenes/no.png' width='30'></td></tr>";
	echo "<tr><td align='left'>Sin realizar: </td><td>$faltantes</td><td><img src='imagenes/pendiente.png' width='30'></td></tr>";
	echo "<tr><td align='left'>Cobertura: </td><td>$cobertura %</td><td></td></tr></table></center>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>Dia Contacto</th><th>Turno</th><th>Contactos</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_contacto=$dat[2];
		$dia_contacto=$dat[4];
		$turno=$dat[5];
		$zona_de_viaje=$dat[6];
		if($zona_de_viaje==1)
		{	$fondo_fila="#FFD8BF";
		}
		else
		{	$fondo_fila="";
		}
		$sql1="SELECT c.orden_visita, m.ap_pat_med, m.ap_mat_med, m.nom_med, d.direccion, c.cod_especialidad, c.categoria_med, c.estado from rutero_detalle c, medicos m, direcciones_medicos d	where (c.cod_contacto=$cod_contacto) and (c.cod_visitador=$global_visitador) and (c.cod_med=m.cod_med) and (m.cod_med=d.cod_med) and (c.cod_zona=d.numero_direccion) order by c.orden_visita";
		$resp1=mysql_query($sql1);
		$contacto="<table class='textomini' width='100%'>";
		$contacto=$contacto."<tr><th width='5%'>Orden</th><th width='35%'>Medico</th><th width='10%'>Especialidad</th><th width='10%'>Categoria</th><th width='30%'>Direccion</th><th width='10%'>Visitado</th></tr>";
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
			$estado=$dat1[7];
			if($estado==0)
			{	$det_estado="<img src='imagenes/pendiente.png' width='30'>";
			}
			if($estado==1)
			{	$det_estado="<img src='imagenes/si.png' width='30'>";
			}
			if($estado==2)
			{	$det_estado="<img src='imagenes/no.png' width='30'>";
			}
			$contacto=$contacto."<tr><td align='center'>$dat1[0]</td><td>&nbsp;$nombre_medico</td><td align='center'>$espe</td><td align='center'>$cat</td><td>&nbsp;$direccion </td><td align='center'>$det_estado </td></tr>";
		}
		$contacto=$contacto."</table>";
		echo "<tr bgcolor=$fondo_fila><td align='center'>$dia_contacto</td><td align='center'>$turno</td><td align='center'>$contacto</td></tr>";
	}
	echo "</table></center><br>";
	
	echo "<center><table border='0' class='textomini'>";
	echo "<tr><th colspan='3'>Resumen Rutero Actual</th></tr>";
	echo "<tr><td align='left'>Total Contactos: </td><td>$total_contactos</td><td></td></tr>";
	echo "<tr><td align='left'>Realizados: </td><td>$realizados</td><td><img src='imagenes/si.png' width='30'></td></tr>";
	echo "<tr><td align='left'>No Realizados: </td><td>$no_realizados</td><td><img src='imagenes/no.png' width='30'></td></tr>";
	echo "<tr><td align='left'>Sin realizar: </td><td>$faltantes</td><td><img src='imagenes/pendiente.png' width='30'></td></tr>";
	echo "<tr><td align='left'>Cobertura: </td><td>$cobertura %</td><td></td></tr></table></center>";//echo "<center><table border='0' class='texto'>";

	echo "</form>";
?>