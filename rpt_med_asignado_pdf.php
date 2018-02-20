<?php
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=excel.xls"); 
require("conexion.inc");
require("estilos_reportes.inc");
if($ver=="Alfabetico")
{
	$sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med,m.fecha_nac_med,m.telf_med,m.telf_celular_med,
		m.email_med,m.hobbie_med,m.estado_civil_med,m.nombre_secre_med,m.perfil_psicografico_med
	 from medicos m, medico_asignado_visitador c
	 where m.cod_ciudad='$global_agencia' and m.cod_med=c.cod_med and c.codigo_linea=$global_linea and c.codigo_visitador='$global_visitador'
	 order by m.ap_pat_med";
	$resp=mysql_query($sql);
}	
if($ver=="RUC")
{
	$sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med,m.fecha_nac_med,m.telf_med,m.telf_celular_med,
		m.email_med,m.hobbie_med,m.estado_civil_med,m.nombre_secre_med,m.perfil_psicografico_med
	 from medicos m, medico_asignado_visitador c
	 where m.cod_ciudad='$global_agencia' and m.cod_med=c.cod_med and c.codigo_linea=$global_linea and c.codigo_visitador='$global_visitador'
	 order by m.cod_med";
	$resp=mysql_query($sql);
}
if($ver=="Especialidad")
{
	$sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med,m.fecha_nac_med,m.telf_med,m.telf_celular_med,
		m.email_med,m.hobbie_med,m.estado_civil_med,m.nombre_secre_med,m.perfil_psicografico_med
	 from medicos m, medico_asignado_visitador ma, categorias_lineas c
		 where m.cod_ciudad='$global_agencia' and m.cod_med=ma.cod_med and c.cod_med=ma.cod_med and ma.codigo_linea=$global_linea and c.cod_especialidad='$parametro' order by m.ap_pat_med";
	$resp=mysql_query($sql);
}
if($ver=="Categoria")
{
	$sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med,m.fecha_nac_med,m.telf_med,m.telf_celular_med,
		m.email_med,m.hobbie_med,m.estado_civil_med,m.nombre_secre_med,m.perfil_psicografico_med
	 from medicos m, medico_asignado_visitador ma, categorias_lineas c
		 where m.cod_ciudad='$global_agencia' and m.cod_med=ma.cod_med and c.cod_med=ma.cod_med and ma.codigo_linea=$global_linea and c.categoria_med='$parametro' order by m.ap_pat_med";
	$resp=mysql_query($sql);
}
echo "<center><table border='0' class='textotit'><tr><td>Medicos Asignados</td></tr></table></center><br>";
	echo "<center><table border='1' class='textomini' cellspacing='0'>";
	if($formato=="Detallado")
	{	echo "<tr><th>RUC</th><th>Nombre</th><th>Nacimiento</th><th>Especialidades</th><th>Direcciones</th><th>Teléfonos</th><th>Célular</th><th>Correo Electrónico</th><th>Secretaria</th><th>Perfil Psicografico</th><th>Estado Civil</th><th>Hobbie</th></tr>";
	}
	if($formato=="Resumido")
	{	echo "<tr><th>RUC</th><th>Nombre</th><th>Especialidades</th></tr>";
	}
	while($dat=mysql_fetch_array($resp))
	{
		$cod=$dat[0];
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
		$direccion_medico="";
		while($dat1=mysql_fetch_array($resp1))
		{
			$dir=$dat1[0];
			$desc_dir=$dat1[1];
			$direccion_medico="$direccion_medico<br><i>$desc_dir:</i><br>$dir";
		}
		$direccion_medico="$direccion_medico<br><br>";
		$sql2="select c.cod_especialidad, c.categoria_med, e.descripcion
      			from especialidades_medicos e, categorias_lineas c
          			where c.cod_med=e.cod_med and c.cod_med=$cod and c.cod_especialidad=e.cod_especialidad and c.codigo_linea=$global_linea order by e.descripcion";
		$resp2=mysql_query($sql2);
		$especialidad="";
		while($dat2=mysql_fetch_array($resp2))
		{
			$espe=$dat2[0];
			$cat=$dat2[1];
			$desc_espe=$dat2[2];
			$especialidad="$especialidad<br><i>$desc_espe:</i><br>$espe&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$cat";
		}
		$especialidad="$especialidad<br><br>";
		if($formato=="Detallado")
		{		echo "<tr><td align='center'>$cod</td><td align='center' class='texto'>$nombre_completo</th><td align='center'>&nbsp;$fecha_nac</th><td align='center'>&nbsp;$especialidad</th><td align='center'>&nbsp;$direccion_medico</th><td align='center'>&nbsp;$telf</th><td align='center'>&nbsp;$cel</th><td align='center'>&nbsp;$email</th><td align='center'>&nbsp;$secre</th><td align='center'>&nbsp;$perfil</th><td align='center'>&nbsp;$est_civil</th><td align='center'>&nbsp;$hobbie</th></tr>";
		}
		if($formato=="Resumido")
		{		echo "<tr><td align='center'>$cod</td><td align='center' class='texto'>$nombre_completo</th><td align='center'>&nbsp;$especialidad</th></tr>";
		}
	}
	echo "</table></center><br>";

?>