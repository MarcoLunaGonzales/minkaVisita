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
	echo "<form method='post' action=''>";
	
	$sql="SELECT distinct(f.codigo_funcionario),c.cargo,f.paterno,f.materno,f.nombres,f.telefono, f.celular,f.email from funcionarios f, 
	cargos c, ciudades ci, funcionarios_lineas cl where f.cod_cargo=c.cod_cargo and (f.cod_cargo = '1011' or f.cod_cargo =  1022) 
	and f.cod_ciudad='$global_agencia' and f.estado=1 and cl.codigo_funcionario=f.codigo_funcionario and 
	f.cod_ciudad=ci.cod_ciudad order by ci.descripcion,f.paterno,c.cargo"; 
	
	//echo $sql;
	
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Visitadores Medicos</td></tr></table></center><br>";
	$indice_tabla=1;
	echo "<center><table border='1' class='texto' cellspacing='0' width='70%'>";
	echo "<tr><th>&nbsp;</th><th>Nombre</th><th>&nbsp;</th></tr>";
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
		echo "<tr><td align='center'>$indice_tabla</td><td>$nombre_f</td>
		<td align='center'><a href='verRuteroCompleto.php?codVisitador=$codigo'>Ver Rutero</a></td></tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	echo "</form>";
	require("home_regional1.inc");
?>