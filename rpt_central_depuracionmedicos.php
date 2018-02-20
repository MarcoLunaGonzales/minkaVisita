<?php
require("conexion.inc");
require("estilos_reportes_central.inc");
$codLinea=$_GET['linea_rpt'];
$codTerritorio=$_GET['rpt_territorio'];

$sqlNomLinea=mysql_query("select nombre_linea from lineas where codigo_linea=$codLinea");
$datNomLinea=mysql_fetch_array($sqlNomLinea);
$nombreLinea=$datNomLinea[0];

$sqlNomTerritorio=mysql_query("select descripcion from ciudades where cod_ciudad=$codTerritorio");
$datNomTerritorio=mysql_fetch_array($sqlNomTerritorio);
$nombreTerritorio=$datNomTerritorio[0];

	$sql="select m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med,m.fecha_nac_med,m.telf_med,m.telf_celular_med,
		m.email_med,m.hobbie_med,m.estado_civil_med,m.nombre_secre_med,m.perfil_psicografico_med, c.cod_especialidad, c.categoria_med
	 from medicos m, categorias_lineas c
		 where m.cod_ciudad='$codTerritorio' and c.codigo_linea='$codLinea' and c.cod_med=m.cod_med order by m.ap_pat_med";
	$resp=mysql_query($sql);
	
	echo "<center><table border='0' class='textotit'><tr><th>Depuracion de Medicos<br>Linea: $nombreLinea Territorio: $nombreTerritorio</th></tr></table></center><br>";
	echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><td>Medicos que no se encuentran en Rutero Maestro</td><td bgcolor='#66CCFF' width='30%'></td></tr></table></center><br>";
	echo "<center><table border='1' class='textomini' cellspacing='0'  width='70%'>";
	echo "<tr><th>&nbsp;</th><th>RUC</th><th>Nombre</th><th>Especialidades</th></tr>";
	$indice_tabla=1;
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
		$codEspe=$dat[12];
		$catMed=$dat[13];
		$nombre_completo="$pat $mat $nom";
		$especialidad="$codEspe $catMed";
		$sql_med_asignado="select * from rutero_maestro r, rutero_maestro_cab rm, rutero_maestro_detalle rd
							where rm.cod_rutero=r.cod_rutero and r.cod_contacto=rd.cod_contacto 
							and rm.estado_aprobado=1 and rd.cod_med=$cod and rm.codigo_linea=$codLinea";
		$resp_med_asignado=mysql_query($sql_med_asignado);
		$num_filas_med_asignado=mysql_num_rows($resp_med_asignado);
		if($num_filas_med_asignado==0)
		{	$fondo_med_asignado="#66ccff";
		}
		else
		{	$fondo_med_asignado="";
		}
		echo "<tr bgcolor='$fondo_med_asignado'><td align='center'>$indice_tabla</td><td align='center'>$cod</td><td class='texto'>&nbsp;&nbsp;$nombre_completo</th><td align='center'>&nbsp;$especialidad</th></tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
?>