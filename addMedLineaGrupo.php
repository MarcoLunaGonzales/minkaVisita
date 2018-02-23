<?php

	echo "<form method='GET' action='guardarAddMedLinea.php'>";

	require("conexion.inc");
	require("estilos_gerencia.inc");
	require("funcion_nombres.php");
	$codMed=$_GET['codMed'];
	$codAgencia=$_GET['codAgencia'];
	
	$nombreMed=nombreMedico($codMed);

	echo "<center><table border='0' class='textotit'><tr><td>Adicionar Medico Linea<br>Medico: $nombreMed</td></tr></table></center><br>";
		
	echo "<input type='hidden' name='codMed' value='$codMed'>";

	$sql="select l.codigo_linea, l.nombre_linea from lineas l where l.linea_promocion=1 and l.estado=1 
		and l.codigo_linea not in (select c.codigo_linea from categorias_lineas c where c.cod_med=$codMed) order by 2";
	$resp=mysql_query($sql);

	echo "<center><table border='0' class='texto'>";

	echo "<center><table border='1' class='textomini' cellspacing='0'>";
	echo "<tr><th>Linea;</th><th>Especialidad</th><th>Categoria</th><th>Visitador Asignado</th></tr>";
	$indice=0;
	while($dat=mysql_fetch_array($resp)){
		$codLinea=$dat[0];
		$nombreLinea=$dat[1];
		echo "<input type='hidden' name='codLinea$indice' id='codLinea$indice' value='$codLinea'>";
		echo "<tr><td>$nombreLinea</td><td>";
		
		$sqlEsp="select e.cod_especialidad from especialidades_medicos e where e.cod_med='$codMed'";
		$respEsp=mysql_query($sqlEsp);
		echo "<select name='espe$indice'>";	
		while($datEsp=mysql_fetch_array($respEsp)){
			$codEspe=$datEsp[0];
			echo "<option value='$codEspe'>$codEspe</option>";
		}
		echo "</select></td><td>";
		
		

		$sqlCat="select c.categoria_med from categorias_medicos c where c.categoria_med<>'D'";
		$respCat=mysql_query($sqlCat);
		echo "<select name='cat$indice'>";	
		while($datCat=mysql_fetch_array($respCat)){
			$catBD=$datCat[0];
			echo "<option value='$catBD'>$catBD</option>";
		}
		echo "</select></td><td>";
		
		
		$sqlFunc="SELECT f.codigo_funcionario, concat(f.paterno, ' ',f.materno, ' ',f.nombres)nombreFunc from funcionarios f, 
		 funcionarios_lineas fl where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea=$codLinea and  
		 f.cod_ciudad=$codAgencia and f.estado=1 and f.cod_cargo=1011";
		$respFunc=mysql_query($sqlFunc);
		echo "<select name='func$indice'>";	
		echo "<option value='0'>-- No Seleccionado --</option>";
		while($datFunc=mysql_fetch_array($respFunc)){
			$codFunc=$datFunc[0];
			$nombreFunc=$datFunc[1];
			echo "<option value='$codFunc'>$nombreFunc</option>";
		}
		echo "</select></td><td></tr>";
		
		
		echo "<input type='hidden' name='linea$indice' value='$codLinea'>";
		$indice++;
	}
		echo "<input type='hidden' name='cantidadLineas' value='$indice'>";
		echo "</table></center><br>";
		

	$sql="select l.nombre_linea, c.cod_especialidad, c.categoria_med from categorias_lineas c, lineas l
		where c.cod_med='$codMed' and c.codigo_linea=l.codigo_linea and l.estado=1";
	$resp=mysql_query($sql);
	echo "<center><table border='1' class='textomini' cellspacing='0'>";
	echo "<tr><th colspan='3'>Detalle de Lineas asignadas</th></tr>";
	echo "<tr><th>Linea</th><th>Especialidad</th><th>Categoria</th></tr>";
	while($dat=mysql_fetch_array($resp)){
		$lineaA=$dat[0];
		$codEspeA=$dat[1];
		$codCatA=$dat[2];
		echo "<tr><td>$lineaA</td>";
		echo "<td>$codEspeA</td>";
		echo "<td>$codCatA</td></tr>";	
	}
		echo "</table></center><br>";




	
		echo"\n<table align='center'><tr><td><a href='busqueda_medicos_lineas.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";

		
		echo "<h4 align='center'>Nota: No se guardaran las lineas donde no se asigne visitador.</h4>";
		
		
		echo "<center><table border='0' class='texto'>";
		echo "<tr><td><input type='submit' value='Guardar' class='boton'></td></tr></table></center>";
		echo "</form>";
		
?>