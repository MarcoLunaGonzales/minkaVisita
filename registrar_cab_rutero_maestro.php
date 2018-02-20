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
echo "<form action='guarda_cab_rutero_maestro.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Adicionar Rutero Maestro</td></tr></table></center><br>";
// Obtenemos ciclo siguiente
$cicloSiguiente = 0;
$sqlCicloSiguiente="SELECT cod_ciclo
					from ciclos c 
					INNER JOIN gestiones g on c.codigo_gestion = g.codigo_gestion
					where c.estado = 'Inactivo'
					and g.estado = 'Activo'
					order by c.cod_ciclo asc
					LIMIT 0, 1";
$respCicloSiguiente = mysql_query($sqlCicloSiguiente);
if ($datCicloSiguiente=mysql_fetch_array($respCicloSiguiente)) {
	$cicloSiguiente = $datCicloSiguiente[0];
}
// Verificamos que no haya un rutero en el ciclo siguiente
$cantRuteros = 0;
$sqlVerifRutero="SELECT count(*)
			from rutero_maestro_cab rc 
			where rc.cod_visitador = '$global_visitador'
			and rc.codigo_ciclo = '$cicloSiguiente'
			and rc.codigo_linea = '$global_linea' 
			and rc.codigo_gestion = (SELECT codigo_gestion from gestiones where estado = 'Activo' LIMIT 0,1)
			";
$respVerifRutero = mysql_query($sqlVerifRutero);
if ($datVerifRutero=mysql_fetch_array($respVerifRutero)) {
	$cantRuteros = $datVerifRutero[0];
}
$nombreCicloSiguiente = "CICLO ".$cicloSiguiente;
if ($cantRuteros == 0) {
	echo "<center><table border='1' class='texto' cellspacing='0'>";
	echo "<tr><th>Nombre Rutero Maestro</th><th>Ciclo Asociado</th></tr>";
	echo "<tr>
			<td align='center'>";
			echo "<input type='text' class='texto' name='nombre_rutero' size='40' value='$nombreCicloSiguiente' onKeyUp='javascript:this.value=this.value.toUpperCase();'>";
	echo "	</td>
	<td><select name='ciclo' class='texto'>";
	$sqlCiclos="SELECT distinct(c.cod_ciclo), g.codigo_gestion, g.nombre_gestion
				from ciclos c
				inner join gestiones g on c.codigo_gestion = g.codigo_gestion 
				where c.estado = 'Inactivo'
				and g.estado = 'Activo'
				order by c.cod_ciclo asc
				LIMIT 0, 1";
	$respCiclos=mysql_query($sqlCiclos);
	while($datCiclos=mysql_fetch_array($respCiclos)){
		$codCiclo=$datCiclos[0];
		$codGestion=$datCiclos[1];
		$nombreGestion=$datCiclos[2];
		echo "<option value='$codCiclo|$codGestion'>$codCiclo $nombreGestion</option>";
	}
	echo "</select></td></tr>";
	echo "</table><br/>";
	echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";

}
else{
	echo "<center><div style='color:#FF5733;font-size:14pt;width:300px;'>Ya existe un rutero creado para el $nombreCicloSiguiente, no puede crear mas de un rutero por ciclo.</div></center>";

}
echo"<br/><table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "</form>";
?>