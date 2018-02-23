<?php
require("conexion.inc");
require("estilos_reportes.inc");
if($ver=="Alfabetico")
{
	$sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med,m.fecha_nac_med,m.telf_med,m.telf_celular_med,
		m.email_med,m.hobbie_med,m.estado_civil_med,m.nombre_secre_med,m.perfil_psicografico_med
	 from medicos m
	 where m.cod_ciudad='$global_agencia' order by m.ap_pat_med";
	$resp=mysql_query($sql);
}	
if($ver=="RUC")
{
	$sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med,m.fecha_nac_med,m.telf_med,m.telf_celular_med,
		m.email_med,m.hobbie_med,m.estado_civil_med,m.nombre_secre_med,m.perfil_psicografico_med
	 from medicos m
	 where m.cod_ciudad='$global_agencia' order by m.cod_med";
	$resp=mysql_query($sql);
}
if($ver=="Especialidad")
{
	$sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med,m.fecha_nac_med,m.telf_med,m.telf_celular_med,
		m.email_med,m.hobbie_med,m.estado_civil_med,m.nombre_secre_med,m.perfil_psicografico_med
	 from medicos m, especialidades_medicos e
		 where m.cod_ciudad='$global_agencia' and e.cod_med=m.cod_med and e.cod_especialidad='$parametro' order by m.ap_pat_med";
	$resp=mysql_query($sql);
}
echo "<center><table border='0' class='textotit'><tr><th>Medicos de la Línea</th></tr></table></center><br>";
	echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><td>Medicos asignados</td><td bgcolor='#66CCFF' width='30%'></td></tr></table></center><br>";
	echo "<center><table border='1' class='textomini' cellspacing='0'  width='100%'>";
	if($formato=="Detallado")
	{	echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Nacimiento</th><th>Especialidades</th><th>Direcciones</th><th>Teléfonos</th><th>Célular</th><th>Correo Electrónico</th><th>Secretaria</th><th>Perfil Psicografico</th><th>Estado Civil</th><th>Hobbie</th></tr>";
	}
	if($formato=="Resumido")
	{	echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Especialidades</th></tr>";
	}
	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$cod=$dat[0];
		$sql_aux=mysql_query("select * from categorias_lineas where cod_med='$cod' and codigo_linea='$global_linea'");
		$filas_aux=mysql_num_rows($sql_aux);
		if($filas_aux!=0)
		{	
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
			$sql1="select direccion, descripcion from direcciones_medicos where cod_med=$cod order by descripcion asc";
			$resp1=mysql_query($sql1);     
			$direccion_medico="<table border=0 class='textosupermini' width='100%'>";
			while($dat1=mysql_fetch_array($resp1))
			{
				$dir=$dat1[0];
				$desc_dir=$dat1[1];
				$direccion_medico="$direccion_medico<tr><th>$desc_dir:</th></tr><tr><td align='left'>$dir</td></tr>";
			}
			$direccion_medico="$direccion_medico</table>";
			$sql2="select c.cod_especialidad, c.categoria_med, e.descripcion
      			from especialidades_medicos e, categorias_lineas c
          			where c.cod_med=e.cod_med and c.cod_med=$cod and c.cod_especialidad=e.cod_especialidad and c.codigo_linea=$global_linea order by e.descripcion";
			$resp2=mysql_query($sql2);
			$especialidad="<table border='0' class='texto' align='center' width='100%'>";
			while($dat2=mysql_fetch_array($resp2))
			{
				$espe=$dat2[0];
				$cat=$dat2[1];
				$desc_espe=$dat2[2];
				$especialidad="$especialidad<tr><td align='left' width='80%'>$espe</td><td align='left' width='20%'>$cat</td></tr>";			
				}
			$especialidad="$especialidad</table>";
			$sql_med_asignado="select * from medico_asignado_visitador where cod_med='$cod' and codigo_visitador='$global_visitador' and codigo_linea='$global_linea'";
			$resp_med_asignado=mysql_query($sql_med_asignado);
			$num_filas_med_asignado=mysql_num_rows($resp_med_asignado);
			if($num_filas_med_asignado==0)
			{	$fondo_med_asignado="";
			}
			else
			{	$fondo_med_asignado="#66ccff";
			}
			if($formato=="Detallado")
			{		echo "<tr bgcolor='$fondo_med_asignado'><td align='center'>$indice_tabla</td><td align='center'>$cod</td><td class='textomini'>&nbsp;&nbsp;$nombre_completo</th><td align='center'>&nbsp;$fecha_nac</th><td align='center'>&nbsp;$especialidad</th><td align='center'>&nbsp;$direccion_medico</th><td align='center'>&nbsp;$telf</th><td align='center'>&nbsp;$cel</th><td align='center'>&nbsp;$email</th><td align='center'>&nbsp;$secre</th><td align='center'>&nbsp;$perfil</th><td align='center'>&nbsp;$est_civil</th><td align='center'>&nbsp;$hobbie</th></tr>";
			}
			if($formato=="Resumido")
			{		echo "<tr bgcolor='$fondo_med_asignado'><td align='center'>$indice_tabla</td><td align='center'>$cod</td><td class='texto'>&nbsp;&nbsp;$nombre_completo</th><td align='center'>&nbsp;$especialidad</th></tr>";
			}
			$indice_tabla++;
		}
	}
	echo "</table></center><br>";
	echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

?>