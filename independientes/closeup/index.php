<!DOCTYPE HTML>
<html lang="es-US">
<head>
	<title>Closeup</title>
</head>
<body>
	<?php  
	set_time_limit(0);
	require("../../conexion.inc");

	$sql = mysql_query("SELECT * from aux");
	while ($row = mysql_fetch_array($sql)) {
		echo("UPDATE medicos set cod_catcloseup = $row[2] where cod_closeup = $row[3]");
		$que = mysql_query("UPDATE medicos set cod_catcloseup = $row[2] where cod_closeup = $row[3]");
		if($que == true){
			echo "OK <br />";
		}else{
			echo "Mal <br />";
		}
	}
	?>
</body>
</html>