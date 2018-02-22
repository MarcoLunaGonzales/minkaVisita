<?php

	require("conexion.inc");
	require("estilos_gerencia.inc");
	$territorio=$_POST['territorio'];
	echo "<form method='post' action='opciones_medico.php'>";
	if($campo=="especialidad")
	{	$sql="select m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med, m.cod_ciudad
	 	from medicos m, especialidades_medicos e
	 	where m.cod_ciudad='$territorio' and m.cod_med=e.cod_med and e.cod_especialidad like '$parametro%' and estado_registro = 1 order by m.ap_pat_med";	
	}
	else
	{
		$sql="select cod_med,ap_pat_med,ap_mat_med,nom_med,m.cod_ciudad
	 	from medicos m
	 	where cod_ciudad='$territorio' and $campo like '$parametro%' and estado_registro  in (1,3) order by m.ap_pat_med";
	}
	//echo $sql;
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Resultados de la Búsqueda</td></tr></table></center><br>";
	$indice_tabla=1;
	echo"\n<table align='center'><tr><td><a href='busqueda_medicos_lineas.php'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
	echo "<center><table border='0' class='texto'>";

	echo "<center><table border='1' class='textomini' cellspacing='0'>";
	echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>RUC</th><th>Nombre</th><th>Especialidades</th><th>Territorio</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod=$dat[0];
		
			$pat=$dat[1];
			$mat=$dat[2];
			$nom=$dat[3];
			$codCiudad=$dat[4];
			$nombre_completo="$pat $mat $nom";

			$sql2="select cod_especialidad from especialidades_medicos
   	       			where cod_med=$cod";
			$resp2=mysql_query($sql2);
			$especialidad="<table border=0 class='textomini' width='50%'>";
			while($dat2=mysql_fetch_array($resp2))
			{
				$espe=$dat2[0];
				$sql_verifica_espelinea="select * from categorias_lineas where cod_med='$cod'";
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
			
			echo "<tr bgcolor='$med_en_linea'><td align='center'>$indice_tabla</td><td align='center'><input type='checkbox' name='codigos_ciclos' value=$cod></td><td align='center'>$cod</td><td class='texto'>&nbsp;$nombre_completo</th>
			<td align='center'>$especialidad</th><td align='center'>$codCiudad</td>	
			<td align='center'><a href='cambiarCatGeneral.php?codMed=$cod'>Cambiar Cat.</a></td></tr>";
			$indice_tabla++;
	}
		echo "</table></center><br>";
		echo"\n<table align='center'><tr><td><a href='busqueda_medicos_lineas.php'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";

		echo "</form>";
		
?>