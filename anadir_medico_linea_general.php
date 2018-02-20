<?php
	require("conexion.inc");
?>
<script language='Javascript'>
		</script>

<?php

echo "<script language='Javascript'>
	function aparece_visitador(f,cadena,tamano)
	{ 	var cad;
		var tam=tamano;
		var chk_estado;
		cad=cadena.value;
		vector_datos=new Array(tam);
		vector_perfil=new Array(tam);
		datos=new Array(tam);
		vector_datos=cad.split('|');
		for(i=1;i<=tam;i++)
		{	datos[i-1]=vector_datos[i];
		}
		if(f.chk_visitador.checked==true)
		{	chk_estado=1;
		}
		else
		{	chk_estado=0;
		}
		location.href='categorizar_medico_linea2.php?datos='+datos+'&chk_visitador='+chk_estado+'';
	}

		function nuevoAjax()
		{	var xmlhttp=false;
 			try {
 				xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	 		} catch (e) {
 				try {
 					xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
 				} catch (E) {
 					xmlhttp = false;
 				}
  			}
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 			xmlhttp = new XMLHttpRequest();
			}
			return xmlhttp;
		}
		function anadir_categoria(f, codCiudad)
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
			{	alert('Debe seleccionar al menos un m�dico para poder categorizarlo.');
				return(false);
			}
			else
			{	/*var contenedor;
				contenedor= document.getElementById('contenedor');
				ajax=nuevoAjax();
				ajax.open('POST', 'categorizar_medico_linea2.php?datos='+datos+'',true);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4) {
						contenedor.innerHTML = ajax.responseText
					}
				}
	 			ajax.send(null);*/
                                location.href='categorizar_medico_linea.php?datos='+datos+'&codCiudad='+codCiudad+'';
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
	require("conexion.inc");
	require("estilos_gerencia.inc");
	require("funcion_nombres.php");
	echo "<form method='post' action='guarda_cat_medico_linea.php'>";
	
	$nombreCiudad=nombreTerritorio($codCiudadGlobal);
	
	$ver=$_GET['ver'];
	if($ver==1){
		$txtVer="order by m.ap_pat_med, m.ap_mat_med, m.nom_med asc";
	}else{
		$txtVer="order by m.cod_med asc";
	}
	echo "<div id='contenedor'>";
	$sql="select DISTINCT m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med,m.fecha_nac_med,m.telf_med,m.telf_celular_med,
		m.email_med,m.hobbie_med,m.estado_civil_med,m.nombre_secre_med,m.perfil_psicografico_med
	 from medicos m, especialidades_medicos e
	 where m.cod_med=e.cod_med and m.cod_ciudad='$codCiudadGlobal' and estado_registro = 1 $txtVer";
    
	//m.ap_pat_med, m.ap_mat_med, m.nom_med
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>A&ntildeadir M&eacute;dicos a la L&iacute;nea</td></tr></table></center><br>";
	//echo "<center><table border='0' class='textomini'><tr><th>Leyenda:</th><th>M&eacute;dicos ya asignados a la L&iacute;nea</th><td bgcolor='#66CCFF' width='30%'></td></tr></table></center>";
	echo "<table align='center' border='0' class='textomini'><tr><th>Leyenda:</th><th>Especialidad del M&eacute;dico en la l&iacute;nea se visualiza con negrita.</th></tr></table><br>";
	require("home_regional1.inc");
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='A&ntilde;adir / Editar' class='boton' onclick='anadir_categoria(this.form, $codCiudadGlobal)'></td></tr></table></center>";
	echo "<center><table class='texto' border=1 cellspacing='0'>";
	echo "<tr><td><input type='checkbox' name='todo' onClick='sel_todo(this.form)'>Seleccionar Todo</td></tr></table>";	
	echo "<center><table border='1' class='textosupermini' cellspacing='0' width='80%'>";
	$indice_tabla=1;
	echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>RUC</th><th>Nombre</th><th>Nacimiento</th><th>Especialidades</th>
	<th>Direcciones</th><th>Linea / Visitador</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod=$dat[0];
		//vemos si el m�dico no esta en la lista de la linea
		/*$sql_aux=mysql_query("select * from categorias_lineas where cod_med='$cod' and codigo_linea='$global_linea'");
		$filas_aux=mysql_num_rows($sql_aux);
		if($filas_aux==0)
		{	$med_en_linea="";
		}
		else
		{	$med_en_linea="#66CCFF";
		}*/
		
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
			$sql1="select direccion from direcciones_medicos where cod_med=$cod order by direccion asc";
			$resp1=mysql_query($sql1);
			$direccion_medico="<table border=0 class='textosupermini' width='100%'>";
			while($dat1=mysql_fetch_array($resp1))
			{
				$dir=$dat1[0];
				$direccion_medico="$direccion_medico<tr><td align='left'>$dir</td></tr>";
			}
			$direccion_medico="$direccion_medico</table>";
			$sql2="select cod_especialidad
   	   			from especialidades_medicos
   	       			where cod_med=$cod order by cod_especialidad";
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
			
			$lineas_medico="select distinct(l.nombre_linea), c.cod_especialidad, c.categoria_med, l.codigo_linea from lineas l, 
			 categorias_lineas c where c.cod_med='$cod' and l.codigo_linea=c.codigo_linea and l.estado=1";
			 $resp_lineas=mysql_query($lineas_medico);
			 $cad_lineas="<table class='textomini' border='0'>";
			 while($dat_lineas=mysql_fetch_array($resp_lineas)){
				$nombre_linea="$dat_lineas[0] $dat_lineas[1] $dat_lineas[2]";
				$codLineaAsig=$dat_lineas[3];
				
				$sqlMedAsignado="select f.paterno, f.materno, f.nombres from funcionarios f, medico_asignado_visitador m where
											 f.codigo_funcionario=m.codigo_visitador and m.cod_med=$cod and f.estado=1 and m.codigo_linea='$codLineaAsig'";
				$respMedAsignado=mysql_query($sqlMedAsignado);
				$visitadoresAsignados="";
				while($datMedAsignado=mysql_fetch_array($respMedAsignado)){
					$visitadoresAsignados=$visitadoresAsignados."- $datMedAsignado[0] $datMedAsignado[2]";
				}
				
				//$cad_lineas="$cad_lineas <tr><td>$nombre_linea</td><td>$visitadoresAsignados</td></tr>";
				$cad_lineas="$cad_lineas <tr><td>$nombre_linea</td><td>&nbsp;</td></tr>";
			}
			$cad_lineas="$cad_lineas</table>";
			
			echo "<tr bgcolor='$med_en_linea'><td align='center'>$indice_tabla</td><td align='center'>
			<input type='checkbox' name='codigos_ciclos' value=$cod></td><td align='center'>$cod</td><td class='textomini'>&nbsp;$nombre_completo</th>
			<td align='center'>&nbsp;$fecha_nac</th><td align='center'>&nbsp;$especialidad</th><td align='center'>&nbsp;$direccion_medico</th>
			<td>&nbsp;$cad_lineas</td></tr>";
			/*if($filas_aux==0)
			{	echo "<tr bgcolor='$med_en_linea'><td align='center'>$indice_tabla</td><td align='center'><input type='checkbox' name='codigos_ciclos' value=$cod></td><td align='center'>$cod</td><td class='textomini'>&nbsp;$nombre_completo</th><td align='center'>&nbsp;$fecha_nac</th><td align='center'>&nbsp;$especialidad</th><td align='center'>&nbsp;$direccion_medico</th><td align='center'>&nbsp;$telf</th><td align='center'>&nbsp;$cel</th><td>&nbsp;$visitadores</td></tr>";
			}
			else
			{	echo "<tr bgcolor='$med_en_linea'><td align='center'>$indice_tabla</td><td align='center'>&nbsp;</td><td align='center'>$cod</td><td class='textomini'>&nbsp;$nombre_completo</th><td align='center'>&nbsp;$fecha_nac</th><td align='center'>&nbsp;$especialidad</th><td align='center'>&nbsp;$direccion_medico</th><td align='center'>&nbsp;$telf</th><td align='center'>&nbsp;$cel</th><td>&nbsp;$visitadores</td></tr>";
			}*/
			$indice_tabla++;
		
	}
		echo "</table></center><br>";
		require("home_regional1.inc");
		echo "<center><table border='0' class='texto'>";
		echo "<tr><td><input type='button' value='A&ntilde;adir / Editar' class='boton' onclick='anadir_categoria(this.form, $codCiudadGlobal)'></td></tr></table></center>";
		echo "</form>";
echo"</div>";

?>