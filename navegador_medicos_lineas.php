<?php
	header('Content-Type: text/html; charset=UTF-8'); 
	echo "<script language='Javascript'>
		function producto_objetivo(f)
		{
			var i;
			var j=0;
			var j_cod_med;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_med=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un Medico.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Medico.');
				}
				else
				{
					location.href='producto_objetivo.php?j_cod_med='+j_cod_med+'';
				}
			}
		}
		function denegar_producto(f)
		{
			var i;
			var j=0;
			var j_cod_med;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_med=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un Medico.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Medico.');
				}
				else
				{
					location.href='denegar_producto.php?j_cod_med='+j_cod_med+'';
				}
			}
		}
		function eliminar_medico(f)
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
			{	alert('Debe seleccionar al menos un Medico para proceder a su eliminación.');
			}
			else
			{	if(confirm('Esta seguro de eliminar estos datos.'))
				{
				  location.href='eliminar_medico_linea.php?datos='+datos+'';
		        }
				else
				{
				 return(false);
				}

			}
		}
		function editar_medico(f)
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
			{	alert('Debe seleccionar al menos un Medico para editar su especialidad/Categoria.');
			}
			else
			{	location.href='editar_medico_linea.php?datos='+datos+'';
			}
		}
		function editar_perfil(f)
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
			{	alert('Debe seleccionar al menos un Medico para editar su perfil prescriptivo.');
			}
			else
			{	location.href='editar_perfilprescriptivo.php?datos='+datos+'';
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
		</script>
	";
	require("conexion.inc");
	require("estilos_gerencia.inc");
	echo "<form method='post' action='opciones_medico.php'>";

	$sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med,
		m.perfil_psicografico_med, c.frecuencia_linea, c.frecuencia_permitida
	 from medicos m, categorias_lineas c
	 where m.cod_ciudad='$global_agencia' and m.cod_med=c.cod_med and c.codigo_linea=$global_linea order by m.ap_pat_med";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Medicos de la Línea</td></tr></table></center><br>";
	$indice_tabla=1;
	require("home_regional1.inc");
	echo "<center><table class='texto' border=1 cellspacing='0'>";
	echo "<tr><td><input type='checkbox' name='todo' onClick='sel_todo(this.form)'>Seleccionar Todo</td></tr></table>";
	echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
	echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Especialidad</th><th>Frec. Linea</th><th>Frec. Especial</th><th>Perfil<br>Prescriptivo</th><th>Direcciones</th><th>Asignado a</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod=$dat[0];
		$pat=$dat[1];
		$mat=$dat[2];
		$nom=$dat[3];
		$perfil=$dat[4];
		$frecuenciaLinea=$dat[5];
		$frecuenciaPermitida=$dat[6];
		
		$sql_perfil="select p.abrev_perfilprescriptivo from perfil_prescriptivo p, medicoslinea_perfilprescriptivo mp
		where p.codigo_perfilprescriptivo=mp.codigo_perfilprescriptivo and mp.cod_med='$cod' and mp.codigo_linea='$global_linea'";
		$resp_perfil=mysql_query($sql_perfil);
		$dat_perfil=mysql_fetch_array($resp_perfil);
		$abrev_perfil=$dat_perfil[0];
		$nombre_completo="$pat $mat $nom";
		$sql1="select direccion  from direcciones_medicos where cod_med=$cod order by direccion asc";
		$resp1=mysql_query($sql1);
		$direccion_medico="<table border=0 class='textosupermini' width='100%'>";
			while($dat1=mysql_fetch_array($resp1))
			{
				$dir=$dat1[0];
				$direccion_medico="$direccion_medico<tr><td align='left'>$dir</td></tr>";
			}
			$direccion_medico="$direccion_medico</table>";
		$sql2="select c.cod_especialidad, c.categoria_med
      			from especialidades_medicos e, categorias_lineas c
          			where c.cod_med=e.cod_med and c.cod_med=$cod and c.cod_especialidad=e.cod_especialidad and c.codigo_linea=$global_linea order by e.cod_especialidad";
		$resp2=mysql_query($sql2);
		$especialidad="<table border=0 class='textomini' width='50%'>";
			while($dat2=mysql_fetch_array($resp2))
			{
				$espe=$dat2[0];
				$desc_espe=$dat2[1];
				$especialidad="$especialidad<tr><td align='left'>$espe</td><td>$desc_espe</td></tr>";
			}
			$especialidad="$especialidad</table>";
			$sql_visitador="select f.paterno, f.materno, f.nombres from medico_asignado_visitador m, funcionarios f, funcionarios_lineas fl where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' and m.cod_med='$cod' and m.codigo_visitador=f.codigo_funcionario and m.codigo_linea='$global_linea' and f.estado=1";
			$resp_visitador=mysql_query($sql_visitador);
			$visitadores="<table border=0 class='textomini' width='100%'>";
			while($dat_visitador=mysql_fetch_array($resp_visitador))
			{	$nombre_visitador="$dat_visitador[0] $dat_visitador[1] $dat_visitador[2]";
				$visitadores=$visitadores."<tr><td align='left'>$nombre_visitador</td></tr>";
			}
			$visitadores=$visitadores."</table>";
		echo "<tr><td align='center'>$indice_tabla</td><td align='center'><input type='checkbox' name='codigos_ciclos' value=$cod></td>
		<td align='center'>$cod</td><td class='textomini'>$nombre_completo</th>
		<td align='center'>&nbsp;$especialidad</th>
		<td>$frecuenciaLinea</td><td>$frecuenciaPermitida</td>
		<td align='center'>&nbsp;$abrev_perfil</td><td align='center'>&nbsp;$direccion_medico</th><td>$visitadores</td></tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	require("home_regional1.inc");
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Eliminar de la Línea' name='eliminar' class='boton' onclick='eliminar_medico(this.form)'></td><td><input type='button' value='Editar Espe/Cat' name='Editar' class='boton' onclick='editar_medico(this.form)'></td><td><input type='button' value='Editar Perfil Prescriptivo' class='boton' onclick='editar_perfil(this.form)'></td><td><input type='button' value='Productos Objetivo' class='boton' onclick='producto_objetivo(this.form)'></td><td><input type='button' value='Filtrar Productos' class='boton' onclick='denegar_producto(this.form)'></td></tr></table></center>";
	echo "</form>";
?>