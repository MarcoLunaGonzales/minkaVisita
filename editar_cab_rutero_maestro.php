<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.nombre_rutero.value=='')
		{	alert('El campo Nombre de Rutero Maestro esta vacio.');
			f.nombre_rutero.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_visitador.inc");
$sql=mysql_query("select cod_rutero, nombre_rutero, codigo_ciclo, codigo_gestion from rutero_maestro_cab where cod_rutero='$cod_rutero'");
$dat=mysql_fetch_array($sql);
$codigo_rutero=$dat[0];
$nombre_rutero=$dat[1];
$codigo_ciclo=$dat[2];
$codigo_gestion=$dat[3];
$sqlGestion="select nombre_gestion from gestiones where codigo_gestion=$codigoGestion";
$respGestion=mysql_query($sqlGestion);
$datGestion=mysql_fetch_array($respGestion);
$nombreGestion=$datGestion[0];
echo "<form action='guarda_modi_cab_rutero_maestro.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Editar Rutero Maestro</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Nombre Rutero Maestro</th><th>Ciclo Asociado</th></tr>";
echo "<input type='hidden' name='codigo' value='$codigo_rutero'>";
echo "<tr><td align='center'><input type='text' class='texto' name='nombre_rutero' value='$nombre_rutero' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>
<td><select name='ciclo' class='texto'>";
$sqlCiclos="select distinct(c.cod_ciclo), g.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion desc, c.cod_ciclo desc";
$respCiclos=mysql_query($sqlCiclos);
while($datCiclos=mysql_fetch_array($respCiclos)){
	$codCiclo=$datCiclos[0];
	$codGestion=$datCiclos[1];
	$nombreGestion=$datCiclos[2];
	if($codCiclo==$codigo_ciclo and $codGestion==$codigo_gestion){
		echo "<option value='$codCiclo|$codGestion' selected>$codCiclo $nombreGestion</option>";
	}else{
		echo "<option value='$codCiclo|$codGestion'>$codCiclo $nombreGestion</option>";
	}	
}
echo "</select></td></tr>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>