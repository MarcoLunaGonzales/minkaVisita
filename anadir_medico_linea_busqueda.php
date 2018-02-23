<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
echo "<script language='Javascript'>
		function anadir_categoria(f)
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
			{	alert('Debe seleccionar al menos un Medico para añadirle categorias.');
				return(false);
			}
			else
			{	 location.href='categorizar_medico_linea.php?datos='+datos+'';
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
	if($campo=="especialidad")
	{	$sql="select m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med,m.fecha_nac_med,m.telf_med,m.telf_celular_med,
			m.email_med,m.hobbie_med,m.estado_civil_med,m.nombre_secre_med,m.perfil_psicografico_med
	 	from medicos m, especialidades_medicos e
	 	where m.cod_ciudad='$global_agencia' and m.cod_med=e.cod_med and e.cod_especialidad like '$parametro%' and estado_registro = 1 order by m.ap_pat_med";	
	}
	else
	{
		$sql="select cod_med,ap_pat_med,ap_mat_med,nom_med,fecha_nac_med,telf_med,telf_celular_med,
			email_med,hobbie_med,estado_civil_med,nombre_secre_med,perfil_psicografico_med
	 	from medicos m
	 	where cod_ciudad='$global_agencia' and $campo like '$parametro%' and estado_registro  in (1,3) order by m.ap_pat_med";
	}
	//echo $sql;
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Resultados de la Búsqueda</td></tr></table></center><br>";
	$indice_tabla=1;
	echo"\n<table align='center'><tr><td><a href='busqueda_medicos_lineas.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	echo "<center><table border='0' class='texto'>";
	echo "<center><table border='0' class='textomini'><tr><th>Leyenda:</th><th>Medicos ya asignados a la Línea</th><td bgcolor='#66CCFF' width='30%'></td></tr></table></center>";
	echo "<table align='center' border='0' class='textomini'><tr><th>Leyenda:</th><th>Especialidad del Medico en la línea se visualiza con negrita.</th></tr></table><br>";
	echo "<tr><td><input type='button' value='Añadir Categorias' class='boton' onclick='anadir_categoria(this.form)'></td></tr></table></center>";
	echo "<center><table class='texto' border=1 cellspacing='0'>";
	echo "<tr><td><input type='checkbox' name='todo' onClick='sel_todo(this.form)'>Seleccionar Todo</td></tr></table>";	
	echo "<center><table border='1' class='textomini' cellspacing='0'>";
	echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Nacimiento</th><th>Especialidades</th><th>Direcciones</th><th>Teléfonos</th><th>Célular</th><th>Correo Electrónico</th><th>Secretaria</th><th>Perfil Psicografico</th><th>Estado Civil</th><th>Hobbie</th><th>Visitadores Asignados</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod=$dat[0];
		//vemos si el Medico no esta en la lista de la linea
		$sql_aux=mysql_query("select * from categorias_lineas where cod_med='$cod' and codigo_linea='$global_linea'");
		$filas_aux=mysql_num_rows($sql_aux);
		if($filas_aux==0)
		{	$med_en_linea="";
		}
		else
		{	$med_en_linea="#66CCFF";
		}
			$pat=$dat[1];
			$mat=$dat[2];
			$nom=$dat[3];
			$fecha_nac=$dat[4];
			$telf=$dat[5];	
			$cel=$dat[6];
			$email=$dat[7];
			$hobbie=$dat[8];
			$est_civil=$dat[9];
			$secre=$dat[10];
			$perfil=$dat[11];
			$nombre_completo="$pat $mat $nom";
			$sql1="select direccion from direcciones_medicos where cod_med=$cod";
			$resp1=mysql_query($sql1);
			$direccion_medico="<table border=0 class='textosupermini' width='100%'>";
			while($dat1=mysql_fetch_array($resp1))
			{
				$dir=$dat1[0];
				$direccion_medico="$direccion_medico<tr><td align='left'>$dir</td></tr>";
			}
			$direccion_medico="$direccion_medico</table>";
			$sql2="select cod_especialidad from especialidades_medicos
   	       			where cod_med=$cod";
			$resp2=mysql_query($sql2);
			$especialidad="<table border=0 class='textomini' width='50%'>";
			while($dat2=mysql_fetch_array($resp2))
			{
				$espe=$dat2[0];
				$sql_verifica_espelinea="select * from categorias_lineas where codigo_linea='$global_linea' and cod_especialidad='$espe' and cod_med='$cod'";
				$resp_verifica_espelinea=mysql_query($sql_verifica_espelinea);
				$num_filas_verificaespelinea=mysql_num_rows($resp_verifica_espelinea);
				if($num_filas_verificaespelinea!=0)
				{	$especialidad="$especialidad<tr><td align='left'><strong>$espe</strong></td></tr>";
				}
				else
				{	$especialidad="$especialidad<tr><td align='left'>$espe</td></tr>";
				}
			}
			$especialidad="$especialidad</table>";
			$sql_visitador="select f.paterno, f.materno, f.nombres from medico_asignado_visitador m, funcionarios f where m.cod_med='$cod' and m.codigo_visitador=f.codigo_funcionario and m.codigo_linea='$global_linea' and f.estado=1";
			$resp_visitador=mysql_query($sql_visitador);
			$visitadores="<table border=0 class='textosupermini' width='100%'>";
			while($dat_visitador=mysql_fetch_array($resp_visitador))
			{	$nombre_visitador="$dat_visitador[0] $dat_visitador[1] $dat_visitador[2]";
				$visitadores=$visitadores."<tr><td align='left'>$nombre_visitador</td></tr>";
			}
			$visitadores=$visitadores."</table>";
			if($filas_aux!=0)
			{	echo "<tr bgcolor='$med_en_linea'><td align='center'>$indice_tabla</td><td align='center'>&nbsp;</td><td align='center'>$cod</td><td class='texto'>&nbsp;$nombre_completo</th><td align='center'>&nbsp;$fecha_nac</th><td align='center'>&nbsp;$especialidad</th><td align='center'>&nbsp;$direccion_medico</th><td align='center'>&nbsp;$telf</th><td align='center'>&nbsp;$cel</th><td align='center'>&nbsp;$email</th><td align='center'>&nbsp;$secre</th><td align='center'>&nbsp;$perfil</th><td align='center'>&nbsp;$est_civil</th><td align='center'>&nbsp;$hobbie</th><td align='center'>&nbsp;$visitadores</td></tr>";
			}
			else
			{	echo "<tr bgcolor='$med_en_linea'><td align='center'>$indice_tabla</td><td align='center'><input type='checkbox' name='codigos_ciclos' value=$cod></td><td align='center'>$cod</td><td class='texto'>&nbsp;$nombre_completo</th><td align='center'>&nbsp;$fecha_nac</th><td align='center'>&nbsp;$especialidad</th><td align='center'>&nbsp;$direccion_medico</th><td align='center'>&nbsp;$telf</th><td align='center'>&nbsp;$cel</th><td align='center'>&nbsp;$email</th><td align='center'>&nbsp;$secre</th><td align='center'>&nbsp;$perfil</th><td align='center'>&nbsp;$est_civil</th><td align='center'>&nbsp;$hobbie</th><td align='center'>&nbsp;$visitadores</td></tr>";
			}
			$indice_tabla++;
	}
		echo "</table></center><br>";
		echo"\n<table align='center'><tr><td><a href='busqueda_medicos_lineas.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
		echo "<center><table border='0' class='texto'>";
		echo "<tr><td><input type='button' value='Añadir Categorias' class='boton' onclick='anadir_categoria(this.form)'></td></tr></table></center>";
		echo "</form>";
		
?>