<?php

	echo "<script language='JavaScript'>
		function activa(chk, f){
		var i;
		var linea;
		linea=chk.value;
		valor=chk.checked;
		for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].id==linea)
					{	if(valor==true)
						{	f.elements[i].checked=true;
						}
						else
						{	f.elements[i].checked=false;
						}
					}
				}
			}
		}
		function verifica(f)
		{	var i;
			var ban=0;
			for(i=0;i<=f.length-1;i++)
			{	if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].id=='linea')
					{	if(f.elements[i].checked==true)
						{	ban=1;
						}
					}	
				}
			}
			if(ban==0)
			{	alert('Debe seleccionar al menos una Línea.');
				return(false);
			}
			else
			{	f.submit();
			}
		}
		</script>";
	require("conexion.inc");
	require("estilos_regional.inc");
	echo "<center><table border='0' class='textotit'><tr><th>Adicionar Baja de Dias de Visita</th></tr></table></center><br>";
	echo "<form method='post' action='guarda_adi_bajas_visita.php'>";
	echo "<table border=1 class='texto' align='center' width='60%'>";
	echo "<tr><th align='left'>Línea</th>";
	$sql_lineas="select codigo_linea, nombre_linea from lineas where linea_promocion=1 and estado=1 order by nombre_linea";
	$resp_lineas=mysql_query($sql_lineas);
	echo "<td>";
	$i=1;
	$j=1;
	while($dat_lineas=mysql_fetch_array($resp_lineas))
	{	$codigo_linea=$dat_lineas[0];
		$nombre_linea=$dat_lineas[1];
		$sql_visitadores="select f.codigo_funcionario, f.paterno, f.materno, f.nombres from funcionarios f, funcionarios_lineas fl
		where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$codigo_linea' and 
		f.cod_ciudad='$global_agencia' and f.cod_cargo='1011' and f.estado=1 order by f.paterno";
		$resp_visitadores=mysql_query($sql_visitadores);
		echo "<table border='1' cellspacing='0' class='texto' width='100%'>";
		echo "<tr><td width='40%'><input id='linea' type=checkbox name='linea$i' value='$codigo_linea' onClick='activa(this,this.form)'>$nombre_linea</td>";
		echo "<td width='60%'>";
		while($dat_visitadores=mysql_fetch_array($resp_visitadores))
		{	$codigo_visitador=$dat_visitadores[0];
			$nombre_visitador="$dat_visitadores[1] $dat_visitadores[2] $dat_visitadores[3]";
			echo "<input type='checkbox' id='$codigo_linea' name='visitador$j' value='$codigo_visitador'>$nombre_visitador<br>";	
			$j++;
		}
		echo "</td></tr>";
		echo "</table>";		
		$i++;
	}
	echo "<tr><th align='left'>Dia de Baja</th>";
	$sql_dias="select id, dia_contacto from orden_dias order by id";
	$resp_dias=mysql_query($sql_dias);
	echo "<td>";
	echo "<select name='dia_contacto' class='texto'>";
	while($dat_dias=mysql_fetch_array($resp_dias))
	{	$dia_contacto=$dat_dias[1];
		echo "<option value='$dia_contacto'>$dia_contacto</option>";
	}
	echo "</select>";
	echo "</td></tr>";
	echo "<tr><th align='left'>Turno</th>";
	echo "<td>";
	echo "<select name='turno' class='texto'>";
	echo "<option value='Am'>Am</option>";
	echo "<option value='Pm'>Pm</option>";
	echo "<option value='Am/Pm'>Dia Completo</option>";
	echo "</select>";
	echo "</td></tr>";
	echo "<tr><th align='left'>Motivo</th>";
	$sql_motivo="select codigo_motivo, descripcion_motivo from motivos_baja where tipo_motivo='1' order by descripcion_motivo";
	$resp_motivo=mysql_query($sql_motivo);
	echo "<td>";
	echo "<select name='motivo' class='texto'>";
	while($dat_motivo=mysql_fetch_array($resp_motivo))
	{	$codigo_motivo=$dat_motivo[0];
		$descripcion_motivo=$dat_motivo[1];
		echo "<option value='$codigo_motivo'>$descripcion_motivo</option>";
	}
	echo "</select>";
	echo "</td></tr></table><br>";
	echo"\n<table align='center'><tr><td><a href='javascript:history.back()'><img border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
	echo "<input type='hidden' name='numero_lineas' value='$i'>";
	echo "<center><input type='button' value='Guardar' class='boton' onClick='verifica(this.form)'></center>";
	echo "</form>";
?>