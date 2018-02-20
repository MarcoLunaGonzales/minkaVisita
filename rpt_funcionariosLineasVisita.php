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
		echo "<center><table border='0' class='textotit' align='center'><tr><th>Funcionarios en Lineas de Visita Gestion: $ciclo | $nom_gestion<br>
		</th></tr></table></center><br>";

		echo "<center><table border=1 class='texto' cellspacing=0 cellpading=0 id='main' align='center' width='70%'>
		<tr><th>Territorio</th><th>Especialidad</th><th>Linea  de Visita</th><th>Funcionario</th></tr>";

		$sql="SELECT lvv.cod_especialidad, c.descripcion ,l.nombre_l_visita, concat(f.paterno, ' ', f.materno, ' ', f.nombres) from lineas_visita l, lineas_visita_visitadores lv, funcionarios f, lineas_visita_especialidad lvv, ciudades c where l.codigo_l_visita = lv.codigo_l_visita and lvv.codigo_l_visita=lv.codigo_l_visita and lv.codigo_funcionario = f.codigo_funcionario and lv.codigo_ciclo = $ciclo and lv.codigo_gestion = $gestion and f.cod_ciudad=c.cod_ciudad and c.cod_ciudad in ($rpt_territorio) order by 2,1,3,4";
		$resp=mysql_query($sql);
		while($dat=mysql_fetch_array($resp)){
			$codEspe=$dat[0];
			$nombreCiudad=$dat[1];
			$nombreLinea=$dat[2];
			$nombreFuncionario=$dat[3];
			echo "<tr><td>$nombreCiudad</td><td>$codEspe</td><td>$nombreLinea</td><td>$nombreFuncionario</td></tr>";
		}
		echo "</table></center>";
		?>
	
</body>
</html>