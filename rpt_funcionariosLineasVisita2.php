<?php
require("conexion.inc");
require("estilos_reportes.inc");
$rpt_territorio=$_GET["rpt_territorio"];
$datos=$_GET["ciclo"];
$datos11 = explode("|",$datos);
$ciclo = $datos11[0];
$gestion = $datos11[1];
$nom_gestion = $datos11[2];

?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="iso-8859-1">
	<title>Funcionarios en lineas de visita</title>
	<link type="text/css" href="css/style.css" rel="stylesheet" />
	<link type="text/css" href="js/tableDnD/tablednd.css" rel="stylesheet" />
	<link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
	<link rel="stylesheet" href="responsive/stylesheets/style.css">
	<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
</head>
<body>
	
		<?php 
		echo "<center><table border='0' class='textotit' align='center'><tr><th>Funcionarios en Lineas de Visita<br>
		</th></tr></table></center><br>";

		echo "<center><table border=0 class='texto' cellspacing=0 cellpading=0 id='main' align='center' width='70%'>
		<tr><th>Linea  de Visita</th><th>Territorio</th><th>Funcionario</th></tr>";

		$sql="select c.descripcion, concat(f.paterno,' ', f.materno, ' ', f.nombres), l.nombre_linea
			from lineas l, funcionarios_lineas fl, funcionarios f, ciudades c
			where f.cod_ciudad in ($rpt_territorio) and l.codigo_linea=fl.codigo_linea and f.codigo_funcionario=fl.codigo_funcionario and c.cod_ciudad=f.cod_ciudad 
			and f.estado=1 and f.cod_cargo=1011 order by 3,2,1";
		$resp=mysql_query($sql);
		while($dat=mysql_fetch_array($resp)){
			$nombreCiudad=$dat[0];
			$nombreLinea=$dat[2];
			$nombreFuncionario=$dat[1];
			echo "<tr><td>$nombreLinea</td><td>$nombreCiudad</td><td>$nombreFuncionario</td></tr>";
		}
		echo "</table></center>";
		?>
	
</body>
</html>