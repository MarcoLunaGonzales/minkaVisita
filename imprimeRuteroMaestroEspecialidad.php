<script language='JavaScript'>
function totales(idtable){
   var main=document.getElementById(idtable);   
   var numFilas=main.rows.length;
   var numCols=main.rows[1].cells.length;
	 
	 for(var j=1; j<=numCols-1; j++){
	 		var subtotal=0;
	 		for(var i=2; i<=numFilas-2; i++){
	 				var dato=parseInt(main.rows[i].cells[j].innerHTML);
	 				subtotal=subtotal+dato;
	 		}
	 		var fila=document.createElement('TH');
			main.rows[numFilas-1].appendChild(fila);
			main.rows[numFilas-1].cells[j].innerHTML=subtotal;
	 }	 
}
</script>
<?php
function imprimeRuteroMaestroEspe($codigoGestion, $codigoCiclo, $tipoRuteroRpt, $codigoLinea, $visitador, $idtable, $especialidades){
require('conexion.inc');
//require('funcion_nombres.php');
$especialidades=str_replace("`","'",$especialidades);
//nombrevisitador
//nombreLinea
$nombreLinea=nombreLinea($codigoLinea);
$sqlNombreVis="select concat(f.paterno, ' ',f.nombres), c.descripcion from 
							funcionarios f, ciudades c where f.codigo_funcionario='$visitador' and 
							f.cod_ciudad=c.cod_ciudad";
$respNombreVis=mysql_query($sqlNombreVis);
$nombreVisitador=mysql_result($respNombreVis,0,0);
$nombreTerritorio=mysql_result($respNombreVis,0,1);

if($tipoRuteroRpt==0){
	$sql="select cod_rutero from rutero_maestro_cab where cod_visitador='$visitador' and 
	codigo_linea='$codigoLinea' and codigo_ciclo='$codigoCiclo' and codigo_gestion='$codigoGestion'";
	$resp=mysql_query($sql);
	$rutero_maestro=mysql_result($resp,0,0);
	$tabla1="rutero_maestro_cab";
	$tabla2="rutero_maestro";
	$tabla3="rutero_maestro_detalle";		
}
if($tipoRuteroRpt==1){
	$sql="select cod_rutero from rutero_maestro_cab_aprobado where cod_visitador='$visitador' and 
	codigo_linea='$codigoLinea' and codigo_ciclo='$codigoCiclo' and codigo_gestion='$codigoGestion'";
	$resp=mysql_query($sql);
	$rutero_maestro=mysql_result($resp,0,0);
	$tabla1="rutero_maestro_cab_aprobado";
	$tabla2="rutero_maestro_aprobado";
	$tabla3="rutero_maestro_detalle_aprobado";		
}
echo "<table class='texto' border='0' cellspacing='0' align='center'width='90%' id='$idtable'>
<tr><th colspan='9'>$nombreLinea -  $nombreVisitador  -  $nombreTerritorio</th></tr>
<tr><th>Especialidad</th>
<th>Categoria A</th><th>Categoria B</th><th>Categoria C</th><th>Total Medicos</th>
<th>Contactos A</th><th>Contactos B</th><th>Contactos C</th><th>Total Contactos</th>
</tr>";
$sql_especialidad="select cod_especialidad, desc_especialidad from especialidades where cod_especialidad in($especialidades) 
order by desc_especialidad";
$resp_especialidad=mysql_query($sql_especialidad);
$numero_total_medicos=0;
$numero_total_contactos=0;
while($dat_espe=mysql_fetch_array($resp_especialidad))
{	$cod_especialidad=$dat_espe[0];
	$desc_especialidad=$dat_espe[1];
	$sql_medicos="select DISTINCT (rmd.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med, rmd.categoria_med
	from $tabla1 rmc, $tabla2 rm, $tabla3 rmd, medicos m
	where rmc.cod_rutero=rm.cod_rutero and rmc.cod_rutero='$rutero_maestro' and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.codigo_linea='$codigoLinea' and rmc.cod_visitador='$visitador' and rmd.cod_visitador='$visitador' and rmd.cod_especialidad='$cod_especialidad' order by m.ap_pat_med, m.ap_mat_med, m.nom_med";
	$resp_medicos=mysql_query($sql_medicos);
	$num_filas=mysql_num_rows($resp_medicos);
	$numero_total_medicos=$numero_total_medicos+$num_filas;
	$numero_a=0;
	$numero_b=0;
	$numero_c=0;
	$cant_contactos_a=0;
	$cant_contactos_b=0;
	$cant_contactos_c=0;
		while($dat_medicos=mysql_fetch_array($resp_medicos))
	{	$cod_med=$dat_medicos[0];
		$sql_cant_contactos="select rmd.cod_med
		from $tabla1 rmc, $tabla2 rm, $tabla3 rmd, medicos m
		where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med 
		and rmc.cod_visitador=rm.cod_visitador
		and rmc.cod_rutero='$rutero_maestro' and rmc.codigo_linea='$codigoLinea' and rmc.cod_visitador='$visitador' 
		and rmd.cod_especialidad='$cod_especialidad' and rmd.cod_med='$cod_med'";
		$resp_cant_contactos=mysql_query($sql_cant_contactos);
		$num_contactos=mysql_num_rows($resp_cant_contactos);
		//echo "$cod_med $num_contactos<br>";
		$categoria_med=$dat_medicos[4];
		if($categoria_med=="A")
		{	$numero_a++;
			$cant_contactos_a=$cant_contactos_a+$num_contactos;
		}
		if($categoria_med=="B")
		{	$numero_b++;
			$cant_contactos_b=$cant_contactos_b+$num_contactos;			
		}
		if($categoria_med=="C")
		{	$numero_c++;
			$cant_contactos_c=$cant_contactos_c+$num_contactos;			
		}
	}
	if($num_filas!=0)
	{	$total_medicos_espe=$numero_a+$numero_b+$numero_c;
		$total_contactos=$cant_contactos_a+$cant_contactos_b+$cant_contactos_c;
		$numero_total_contactos=$numero_total_contactos+$total_contactos;
		
		echo "<tr><td>$desc_especialidad</td><td align='center'>$numero_a</td><td align='center'>$numero_b</td>
		<td align='center'>$numero_c</td><td align='center'>$total_medicos_espe</td>
		<td align='center'>$cant_contactos_a</td><td align='center'>$cant_contactos_b</td>
		<td align='center'>$cant_contactos_c</td><td align='center'>$total_contactos</td></tr>";
	}
}
echo "<tr><th>Totales</th></tr>";
echo "</table>";
echo "<script language='JavaScript'>totales($idtable);</script>";
}
?>