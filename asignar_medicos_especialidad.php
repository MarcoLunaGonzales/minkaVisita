<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
	require("conexion.inc");
	require("estilos_regional_pri.inc");

	echo "<script language='Javascript'>
		function asignar_medico(f)
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
			{	alert('Debe seleccionar al menos un Medico para Asignarlo al Visitador.');
			}
			else
			{	 location.href='asignar_medico.php?datos='+datos+'&visitador=$visitador';			
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
	//formamos el nombre del funcionario
	$sql_cab="select paterno,materno,nombres from funcionarios where codigo_funcionario='$visitador'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_funcionario="$dat_cab[0] $dat_cab[1] $dat_cab[2]";
	//fin formar nombre funcionario	
	echo "<form method='post' action='opciones_medico.php'>";
	$sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med
		 from medicos m, categorias_lineas c
		 where m.cod_ciudad='$global_agencia' and m.cod_med=c.cod_med and c.codigo_linea=$global_linea and c.cod_especialidad='$cod_espe' order by m.ap_pat_med";
	$resp=mysql_query($sql);
	$sql_cab=mysql_query("select desc_especialidad from especialidades where cod_especialidad='$cod_espe'");
	$dat_cab=mysql_fetch_array($sql_cab);
	$especialidad=$dat_cab[0];
	echo "<center><table border='0' class='textotit'><tr><th>Asignar Medicos - Especialidad $especialidad<br>Visitador: $nombre_funcionario</th></tr></table></center><br>";
	echo "<center><table class='texto' border=1 cellspacing='0'>";
	echo "<tr><td><input type='checkbox' name='todo' onClick='sel_todo(this.form)'>Seleccionar Todo</td></tr></table>";
	echo "<center><table border='1' class='textomini' width='70%' cellspacing='0'>";
	echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Categoria</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod=$dat[0];
		$pat=$dat[1];
		$mat=$dat[2];
		$nom=$dat[3];
		$nombre_completo="$pat $mat $nom";
			$sql_filtro=mysql_query("select * from medico_asignado_visitador where cod_med='$cod' and codigo_visitador='$visitador' and codigo_linea='$global_linea'");
			$num_filtro=mysql_num_rows($sql_filtro);
		$sql2="select categoria_med from categorias_lineas
          			where cod_med=$cod and cod_especialidad='$cod_espe' and codigo_linea=$global_linea";
		$resp2=mysql_query($sql2);
		$dat2=mysql_fetch_array($resp2);
		$categoria=$dat2[0];
		if($num_filtro==0)
		{	echo "<tr><td align='center'><input type='checkbox' name='codigos_ciclos' value=$cod></td><td align='center'>$cod</td><td class='texto'>&nbsp;&nbsp;&nbsp;$nombre_completo</td><td align='center'>$categoria</td></tr>";
		}		
	}
	echo "</table></center><br>";
	echo"\n<table align='center'><tr><td><a href='asignar_med_especialidad.php?visitador=$visitador'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Asignar' class='boton' onclick='asignar_medico(this.form)'></td></tr></table></center>";
	echo "</form>";
?>