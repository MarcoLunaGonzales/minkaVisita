<script language='JavaScript'>
	
	function devolverNombre(nombre,nro){
		var reg = self.opener.form1;
		if(nro==1){
			reg.direccion1.value = nombre;
		}
		if(nro==2){
			reg.direccion2.value = nombre;
		}
		if(nro==3){
			reg.direccion3.value = nombre;
		}
		window.close();
	}
</script>
</script>
<?php
	$rpt_territorio=$rpt_territorio;
	$rpt_nro=$rpt_nro;
	
	require("conexion.inc");
	require("estilos_gerencia.inc");
	echo "<form method='post' action=''>";
	$sql="select nombre_institucion, cod_ciudad, direccion from instituciones where cod_ciudad='$rpt_territorio'";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Instituciones</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='80%'>";
	echo "<tr><th>Institucion</th><th>Direccion</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$nombre_institucion=$dat[0]." ".$dat[2];
		echo "<tr><td><a href='javascript:devolverNombre(\"$nombre_institucion\",$rpt_nro);'>$dat[0]</a></td><td>$dat[2]</td></tr>";
	}
	echo "</table></center><br>";
	echo "</form>";
?>