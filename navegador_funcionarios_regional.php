<?php

	require("conexion.inc");
	require("estilos_regional_pri.inc");
?>
<script language='Javascript'>
	function depurar(codVisitador){
		if(confirm('Esta seguro de depurar los medicos asignados al visitador.')){		
			location.href='depurarMedicos.php?codVisitador='+codVisitador+'';
		}else{	
			return(false);
		}	
	}
</script>
<?php
	
if($global_linea!=""){
	$sql = "select nombre_linea from lineas where codigo_linea=$global_linea";
	$resp = mysql_query($sql);
	$dat = mysql_fetch_array($resp);
	$nombreLineaGlobal = $dat[0];

	echo "<form method='post' action=''>";
	//esta parte saca el ciclo activo
	$sql="SELECT f.codigo_funcionario,c.cargo,f.paterno,f.materno,f.nombres,f.telefono, f.celular,f.email from funcionarios f, cargos c, ciudades ci, funcionarios_lineas cl where f.cod_cargo=c.cod_cargo and (f.cod_cargo = '1011' or f.cod_cargo =  1022) and f.cod_ciudad='$global_agencia' and f.estado=1 and cl.codigo_funcionario=f.codigo_funcionario and (cl.codigo_linea='$global_linea' or cl.codigo_linea = '0') and f.cod_ciudad=ci.cod_ciudad order by ci.descripcion,f.paterno,c.cargo"; 
	// echo $sql;
	$resp=mysql_query($sql);
	

	
	echo "<h1>Visitadores Medicos de la Linea $nombreLineaGlobal</h1>";
	$indice_tabla=1;
	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Nombre</th><th>Medicos Asignados</th><th>Asignar Medicos</th><th>Ver Medicos</th>
	<th>Depurar Listado</th><th>Importar RM</th><th>RM</th><th>RM Aprobados</th></tr>";
	while($dat=mysql_fetch_array($resp)) {
		$codigo=$dat[0];
		$cargo=$dat[1];
		$paterno=$dat[2];
		$materno=$dat[3];
		$nombre=$dat[4];
		$nombre_f="$paterno $materno $nombre";
		$telf=$dat[5];
		$cel=$dat[6];
		$email=$dat[7];
		$sql_medicos="SELECT distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med
		 from medicos m, categorias_lineas c
		 where m.cod_ciudad='$global_agencia' and m.cod_med=c.cod_med and c.codigo_linea=$global_linea order by m.ap_pat_med";
		$resp_medicos=mysql_query($sql_medicos);
		$cadena = '';
		while($dat=mysql_fetch_array($resp_medicos)){
			$cod=$dat[0];
			$cadena .= $cod.",";
		}
		$cadena = substr($cadena, 0,-1);
		$sql_num_medicos="SELECT * from medico_asignado_visitador where codigo_visitador='$codigo' and cod_med in ($cadena) and codigo_linea='$global_linea'";
		// echo $sql_num_medicos;
		$resp_num_medicos=mysql_query($sql_num_medicos);
		$filas_num_medicos=mysql_num_rows($resp_num_medicos);
		echo "<tr><td align='center'>$indice_tabla</td><td>$nombre_f</td><td align='center'>$filas_num_medicos</td>
		<td align='center'><a href='asignar_med_fun.php?j_funcionario=$codigo'><img src='imagenes/addperson.png' width='40'></a></td>
		<td align='center'><a href='medicos_asignados.php?visitador=$codigo'><img src='imagenes/detalle.png' width='40'></a></td>
		<td align='center'><a href='Javascript:depurar($codigo);'><img src='imagenes/depurar.png' width='40'></a></td>
		<td align='center'><a href='importar_rutero_maestro.php?j_funcionario=$codigo'><img src='imagenes/importar.png' width='40'></a></td>
		<td align='center'><a href='rutero_funcionario.php?visitador=$codigo'><img src='imagenes/rutero.png' width='40'></a></td>
		<td align='center'><a href='ruteroAprobadoxCiclo.php?visitador=$codigo'><img src='imagenes/ruteroaprobado.png' width='40'></a></td></tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	echo "</form>";
}else{
	echo "<h1>Debe seleccionar una Linea de Trabajo.</h1>";
}
?>