<?php
	require("conexion.inc");
	require("estilos_regional_pri.inc");

	echo "<script language='Javascript'>
		function asignar_med(f)
		{	var i;
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
			{	alert('Debe seleccionar al menos un Medico para asignarlo.');
			}
			else
			{	  location.href='asignar_medico_asignado.php?datos='+datos+'&visitador=$visitador';
			}
		}
		function sel_todo(f)
		{
			var i;
			var j=0;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.todo.checked==true)
					{	f.elements[i].checked=true;
					}
					else
					{	f.elements[i].checked=false;
					}
					
				}
			}
		}
		</script>";
	echo "<script language='Javascript'>
			alert('Los Medicos que aparecen en la tabla ya fueron asignados a uno o mas visitadores, si desea asignar de todas maneras algun(os) Medico(s) al visitador, seleccione la casilla correspondiente y haga click en Asignar.');
			</script>";
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	$indice=0;
	for($i=0;$i<$n;$i++)
	{
		$sql_filtro="select * from medico_asignado_visitador where cod_med='$vector[$i]' and codigo_linea='$global_linea'";
		$resp_filtro=mysql_query($sql_filtro);
		$filas_filtro=mysql_num_rows($resp_filtro);
		if($filas_filtro!=0)
		{	$vector_medicos[$indice]=$vector[$i];
			$vector_num_veces[$indice]=$filas_filtro;
			$indice++;
		}
		else
		{	$sql="insert into medico_asignado_visitador values('$vector[$i]','$visitador','$global_linea')";
			$resp=mysql_query($sql);
		}
	}
	if($indice!=0)
	{	echo "<form>";
		$sql_vis="select paterno,materno,nombres from funcionarios where codigo_funcionario='$visitador'";
		$resp_vis=mysql_query($sql_vis);
		$dat_vis=mysql_fetch_array($resp_vis);
		$nombre_funcionario="$dat_vis[0] $dat_vis[1] $dat_vis[2]";
		echo "<center><table border='0' class='textotit' width='70%' cellspacing='0'>";
		echo "<tr><th>Excepciones en la Asignación de Medicos<br>Visitador: $nombre_funcionario</th></tr></table><br>";
		echo "<center><table class='texto' border=1 cellspacing='0'>";
		echo "<tr><td><input type='checkbox' name='todo' onClick='sel_todo(this.form)'>Seleccionar Todo</td></tr></table>";
	
		echo "<center><table border='1' class='textomini' width='70%' cellspacing='0'>";
		echo "<tr><th>&nbsp;</th><th>Nombre Medico</th><th>Número de Visitadores Asignados</th></tr>";
		for($j=0;$j<=($indice-1);$j++)
		{	$cod_med=$vector_medicos[$j];
			$sql_nom_med="select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$cod_med'";
			$resp_nom_med=mysql_query($sql_nom_med);
			$dat=mysql_fetch_array($resp_nom_med);
			$nombre_medico="$dat[0] $dat[1] $dat[2]";
			$numero_asig=$vector_num_veces[$j];
			echo "<tr><td align='center'><input type='checkbox' name='codigos_medicos' value=$cod_med></td><td align='center'>$nombre_medico</td><td align='center' class='texto'>$numero_asig</td></tr>";			
		}
		echo "</table></center><br>";
		echo"\n<table align='center'><tr><td><a href='javascript:history.back();'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
		echo "<center><table border='0' class='texto'>";
		echo "<tr><td><input type='button' value='Asignar' class='boton' onclick='asignar_med(this.form)'></td></tr></table></center>";
		echo "<br><center><table border='0' class='texto' width='81%'>";
		echo "<tr><th>Nota: Los Medicos que aparecen en la tabla ya fueron asignados a uno o mas visitadores, si desea asignar de todas maneras algun(os) Medico(s) al visitador, seleccione la casilla correspondiente y haga click en 'Asignar'.</th></tr></table></center>";
		echo "</form>";
	}
	else
	{		echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='medicos_asignados.php?visitador=$visitador';
			</script>";
	}
	
?>