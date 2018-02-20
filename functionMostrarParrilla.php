<?php
function mostrarParrilla($codGestion, $codCiclo, $codTerritorio, $codLineaVisita, $codEspe, $codCat, $codLinea, $numVisita, $diaContacto, $codMedFrec, $ii){

echo "<form name='form$ii' method='post' action=''>";

$nombreDia=nombreDia($diaContacto);

if($codMedFrec!=0){
	$sql="select md.`cod_dia` from `medico_frec_especialdetalle` md where 
			md.`cod_med_frec`='$codMedFrec' and md.`nro_visita`='$numVisita'";
	$resp=mysql_query($sql);
	$codDia=mysql_result($resp,0,0);
	$nombreDia=nombreDia($codDia);
}
echo "<table align='center' class='textotit'><tr><th>Dia de Contacto: $nombreDia</table><br>";

echo "<table border='1' cellspacing='0' class='textomini' width='80%' align='center'>";
echo "<tr><th>Muestra</th><th>Cantidad</th>
				  <th>Cantidad extra entregada</th>
				  <th>Material de Apoyo</th><th>Cantidad</th>
					<th>Cantidad extra entregada</th><th>Obs.</th>
					<th>Entregado</th></tr>";
					
$sqlParrilla="select pd.`codigo_muestra`, concat(m.`descripcion`,' ',m.presentacion), pd.`cantidad_muestra`, ma.codigo_material, 
	ma.`descripcion_material`, pd.`cantidad_material`
	from `parrilla` p, `parrilla_detalle` pd, `muestras_medicas` m, `material_apoyo` ma
	where p.`codigo_parrilla`=pd.`codigo_parrilla` and p.`agencia`='$codTerritorio' and 
	pd.`codigo_muestra`=m.`codigo` and pd.`codigo_material`=ma.`codigo_material` and
	p.`cod_especialidad`='$codEspe' and p.`categoria_med`='$codCat' and p.`codigo_l_visita`='$codLineaVisita' 
	and p.`cod_ciclo`='$codCiclo' and p.`codigo_gestion`='$codGestion' and p.`codigo_linea`='$codLinea' 
	and p.`numero_visita`='$numVisita' order by pd.prioridad";
	
$respParrilla=mysql_query($sqlParrilla);
$i=$ii;

while($datParrilla=mysql_fetch_array($respParrilla)){
	$codMuestra=$datParrilla[0];
	$nombreMuestra=$datParrilla[1];
	$cantidadMuestra=$datParrilla[2];
	$codMaterial=$datParrilla[3];
	$nombreMaterial=$datParrilla[4];	
	$cantidadMaterial=$datParrilla[5];
	
	echo "<input type='hidden' name='codMuestra$i' value='$codMuestra'>";
	echo "<input type='hidden' name='codMaterial$i' value='$codMaterial'>";
	
	$txtCantidadMuestra="<select name='cantidadMuestra$i' class='textomini' disabled>";
	for($j=0;$j<=$cantidadMuestra;$j++){
		$txtCantidadMuestra.="<option value='$j' selected>$j</option>";
	}
	$txtCantidadMuestra.="</select>";
	
	$txtCantidadMuestraExtra="<select name='cantidadMuestraExtra$i' class='textomini' disabled>";
	for($j=0;$j<=5;$j++){
		$txtCantidadMuestraExtra.="<option value='$j'>$j</option>";
	}
	$txtCantidadMuestraExtra.="</select>";


	$txtCantidadMaterial="<select name='cantidadMaterial$i' class='textomini' disabled>";
	for($j=0;$j<=$cantidadMaterial;$j++){
		$txtCantidadMaterial.="<option value='$j' selected>$j</option>";
	}
	$txtCantidadMaterial.="</select>";
	
	$txtCantidadMaterialExtra="<select name='cantidadMaterialExtra$i' class='textomini' disabled>";
	for($j=0;$j<=5;$j++){
		$txtCantidadMaterialExtra.="<option value='$j'>$j</option>";
	}
	$txtCantidadMaterialExtra.="</select>";
	
	$txtObs="<input type='text' name='obs$i' class='textomini' value='' disabled>";
	$txtEntregado="<input type='checkbox' name='constancia$i' onClick='activarCampos(this.form,this,$i)'>";
	echo "<tr><td>$nombreMuestra</td>
					<td>$txtCantidadMuestra</td>
				  <td>$txtCantidadMuestraExtra</td>
				  <td>$nombreMaterial</td>
				  <td>$txtCantidadMaterial</td>
					<td>$txtCantidadMaterialExtra</th><th>$txtObs</td>
					<td>$txtEntregado</td></tr>";
	
	$i++;
}
echo "</table><br>";
return($i);

echo "</form>";
}
?>