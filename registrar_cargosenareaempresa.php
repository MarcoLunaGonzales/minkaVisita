<?php
	require("conexion.inc");
	require('inicio_admsistema.inc');
	echo "<form method='post' action='guarda_cargosenareaempresa.php'>";
	$sql="select cod_cargo, descripcion_cargo from cargos order by descripcion_cargo";
	$resp=mysql_query($sql);
	$sql_nombre_empresa="select nombre_area_empresa from areas_empresa where cod_area_empresa='$area_empresa'";
	$resp_nombre_empresa=mysql_query($sql_nombre_empresa);
	$dat_nombre_empresa=mysql_fetch_array($resp_nombre_empresa);
	$nombre_areaempresa=$dat_nombre_empresa[0];
	echo "<center><table border='0' class='textotit'><tr><td align='center'>ADICIONAR CARGOS ASOCIADOS A UN NIVEL ORGANIZACIONAL<BR>NIVEL ORGANIZACIONAL <STRONG>$nombre_areaempresa</STRONG></td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0'>";
	echo "<tr><th>Cargo</th></tr>";
	echo "<input type='hidden' name='area_empresa' value='$area_empresa'>";
	echo "<tr><td><select name='cargo_empresa' class='texto'>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_cargo=$dat[0];
		$descripcion_cargo=$dat[1];
		$sql_verifica=mysql_query("select * from cargos_en_empresa where cod_cargo='$cod_cargo' and cod_area_empresa='$area_empresa'");
		$nro_filasverifica=mysql_num_rows($sql_verifica);
		if($nro_filasverifica==0)
		{	echo "<option value='$cod_cargo'>$descripcion_cargo</option>";
		}
	}
	echo "</td></tr>";
	echo "</table></center><br>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='submit' value='Guardar' class='boton'></td></tr></table></center>";
	echo "</form>";
?>