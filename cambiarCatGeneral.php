<?php

	echo "<form method='GET' action='guardarCatGeneral.php'>";

	require("conexion.inc");
	require("estilos_gerencia.inc");
	require("funcion_nombres.php");
	$codMed=$_GET['codMed'];
	$nombreMed=nombreMedico($codMed);
		
	echo "<input type='hidden' name='codMed' value='$codMed'>";

	$sql="select c.codigo_linea, c.cod_especialidad, c.categoria_med, l.nombre_linea 
		from categorias_lineas c, lineas l where l.codigo_linea=c.codigo_linea and l.estado=1 and
		c.cod_med=$codMed order by l.nombre_linea";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Cambiar Categorias Medico: $nombreMed</td></tr></table></center><br>";

	echo "<center><table border='0' class='texto'>";

	echo "<center><table border='1' class='textomini' cellspacing='0'>";
	echo "<tr><th>Linea;</th><th>Especialidad</th><th>Categoria</th></tr>";
	$indice=0;
	while($dat=mysql_fetch_array($resp)){
		$codLinea=$dat[0];
		$codEspe=$dat[1];
		$codCat=$dat[2];
		$nombreLinea=$dat[3];
		echo "<tr><td>$nombreLinea</td><td>$codEspe</td><td>$codCat</td><td>";

		$sqlCat="select c.categoria_med from categorias_medicos c where c.categoria_med<>'D'";
		$respCat=mysql_query($sqlCat);
		echo "<select name='cat$indice'>";	
		while($datCat=mysql_fetch_array($respCat)){
			$catBD=$datCat[0];
			if($catBD==$codCat){
				echo "<option value='$catBD' selected>$catBD</option>";
			}else{
				echo "<option value='$catBD'>$catBD</option>";
			}
		}
		echo "</select></td></tr>";
		echo "<input type='hidden' name='linea$indice' value='$codLinea'>";
		$indice++;
	}
		echo "<input type='hidden' name='cantidadLineas' value='$indice'>";
		echo "</table></center><br>";
		echo"\n<table align='center'><tr><td><a href='busqueda_medicos_lineas.php'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
		
		echo "<center><table border='0' class='texto'>";
		echo "<tr><td><input type='submit' value='Guardar' class='boton'></td></tr></table></center>";
		echo "</form>";
		
?>