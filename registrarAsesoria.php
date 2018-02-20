<?php
	require("conexion.inc");
	require("estilos_regional_pri.inc");

	
	echo "<script language='Javascript'>
	
		function guardarAsesoria(f, codVisitador, diaContacto, codGestion, codCiclo)
		{	
			var i;
			var j=0;
			datos=new Array();
			alert(codVisitador+' '+diaContacto+' '+codGestion+' '+codCiclo);
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
			{	alert('Debe seleccionar al menos un registro.');
			}
			else
			{	
				if(confirm('Esta seguro de guardar la Asesoria en consultorio.'))
				{
					location.href='guardarAsesoria.php?datos='+datos+'&codVisitador='+codVisitador+'&diaContacto='+diaContacto+'&codGestion='+codGestion+'&codCiclo='+codCiclo;
				}
				else
				{
					return(false);
				}
			}
		}
	
		</script>";	

	
	$diaContacto=$_GET['diaContacto'];
	$codVisitador=$_GET['codVisitador'];
	$codCiclo=$_GET['codCiclo'];
	$codGestion=$_GET['codGestion'];
	$nombreVisitador=$_GET['nombreVisitador'];
	$codMedX=$_GET['codMedX'];
	
	
	echo "<form method='post' action=''>";
	echo "<center><table border='0' class='textotit'><tr><th>Registro de Asesoria en Consultorio Ciclo: $codCiclo
	<br>Visitador $nombreVisitador  Dia Contacto: $diaContacto</th></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='85%'>";
	echo "<tr><th>&nbsp;</th><th>Linea</th><th>Medico</th><th>Especialidad</th><th>CUP</th><th>Direccion</th></tr>";


	$sql1="select c.orden_visita, m.ap_pat_med, m.ap_mat_med, m.nom_med, d.direccion, c.cod_especialidad, c.categoria_med, 
		c.estado, (select l.nombre_linea from lineas l where l.codigo_linea=rc.codigo_linea)linea, rm.turno, c.cod_med, m.cod_closeup, m.cod_catcloseup
				from rutero_maestro_detalle_aprobado c, medicos m, direcciones_medicos d, rutero_maestro_aprobado rm, rutero_maestro_cab_aprobado rc
					where c.cod_visitador=$codVisitador and rc.codigo_gestion='$codGestion' and rc.codigo_ciclo='$codCiclo' and 
					rm.cod_contacto=c.cod_contacto and rm.cod_rutero=rc.cod_rutero and rm.dia_contacto='$diaContacto' and 
					(c.cod_med=m.cod_med) and (m.cod_med=d.cod_med) and c.cod_med=d.cod_med and (c.cod_zona=d.numero_direccion) 
					and rc.cod_visitador=rm.cod_visitador and rc.cod_visitador=c.cod_visitador and c.cod_visitador=rm.cod_visitador
					order by linea, rm.turno, c.orden_visita";
	$resp1=mysql_query($sql1);
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
		$nombreLinea=$dat1[8];
		$turno=$dat1[9];
		$codMed=$dat1[10];
		$CUP=$dat1[11]." ".$dat1[12];
		
		$indice++;
		if($codMed==$codMedX){
			$color="#FFFF00";
			echo "<tr bgcolor='$color'><td><input type='checkbox' name='codigo' value='$codMed' checked></td>
				<td align='center'>$nombreLinea</td><td>$nombre_medico</td>
				<td align='center'>$espe $cat</td>
				<td align='center'>$CUP</td>
				<td align='left'>$direccion</td>
				</tr>";
		}else{
			$color="";
			echo "<tr bgcolor='$color'><td><input type='checkbox' name='codigo' value='$codMed'></td>
				<td align='center'>$nombreLinea</td><td>$nombre_medico</td>
				<td align='center'>$espe $cat</td>
				<td align='center'>$CUP</td>
				<td align='left'>$direccion</td>
				</tr>";
		}

	}
	
	echo "</table></center><br>";
	echo"\n<table align='center'><tr><td><a href='navegador_territorios.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><td><input type='button' value='Guardar' name='guardar' class='boton' onclick='guardarAsesoria(this.form, $codVisitador,\"$diaContacto\",$codGestion,$codCiclo)'></td></td></tr></table></center>";
	echo "</form>";
?>