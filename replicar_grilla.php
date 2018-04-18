<script language="JavaScript">
function enviar(f)
{	var rpt_territorio=new Array();
	var j=0;
	var contador=0;
	for(i=0;i<=f.codigo_territorio.options.length-1;i++)
	{	if(f.codigo_territorio.options[i].selected)
		{	rpt_territorio[j]=f.codigo_territorio.options[i].value;
			j++;
			contador++;
		}
	}
	f.codigosTerritorioVector.value=rpt_territorio;
	f.submit();
}
</script>

<?php

require("conexion.inc");
require("estilos_gerencia.inc");
echo "<form method='post' action='guarda_replicagrilla.php'>";
$codigo_grilla=$_GET['codigo_grilla'];

echo "<h1>Replicar Grilla</h1>";

$sql_territorios="select cod_ciudad, descripcion from ciudades order by descripcion";
$resp_territorios=mysql_query($sql_territorios);
echo"<br><table border=1 class='texto' align='center'>";
echo"<tr><th>Territorio</th></tr>
	<tr><td>
		<select name='codigo_territorio' class='texto' size='10' multiple>";
while($dat_territorios=mysql_fetch_array($resp_territorios))
{	$cod_ciudad=$dat_territorios[0];
	$nombre_ciudad=$dat_territorios[1];
	echo "<option value='$cod_ciudad'>$nombre_ciudad</option>";
}
echo "</select></td></tr></table><br>";
echo "<input type='hidden' name='codigo_grilla' value='$codigo_grilla'>";
echo "<input type='hidden' name='codigosTerritorioVector' value='0'>";

echo "<center><input type='button' value='Guardar' class='boton' onClick='enviar(this.form);'></center>";
echo "</form>";
?>