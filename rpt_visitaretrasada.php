<?php
//header("Content-type: application/vnd.ms-excel"); 
//header("Content-Disposition: attachment; filename=archivo.xls"); 	
require("conexion.inc");
require("estilos_reportes_central.inc");

function compara_fechas($fecha1,$fecha2)
{	if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1))
    list($dia1,$mes1,$año1)=split("/",$fecha1);
    if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1))
    list($dia1,$mes1,$año1)=split("-",$fecha1);
    if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2))
    list($dia2,$mes2,$año2)=split("/",$fecha2);
    if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2))
    list($dia2,$mes2,$año2)=split("-",$fecha2);
    $dif = mktime(0,0,0,$mes1,$dia1,$año1) - mktime(0,0,0, $mes2,$dia2,$año2);
    return ($dif);                         
}

	$fecha_actual=date("Y-m-d");
	list($año1,$mes1,$dia1)=split("-",$fecha_actual);
	$mktfechaanterior=mktime(0,0,0,$mes1,$dia1,$año1)-(86400*5);
	$fecha_anterior=date("Y-m-d",$mktfechaanterior);

echo "<center><table border='0' class='textotit'><tr><th>Visitadores que no realizaron su descarga los ultimos 5 dias</th></tr></table></center><br>";
echo "<center><table border='1' class='texto' width='100%' cellspacing='0'>";
echo "<tr><th>&nbsp;</th><th>Visitador</th><th>Fecha Ultima Descarga</th><th>Territorio</th></tr>";
$sql="select f.codigo_funcionario, f.paterno, f.materno, f.nombres, c.descripcion from funcionarios f, ciudades c 
		where f.cod_ciudad=c.cod_ciudad and f.estado=1 and f.cod_cargo=1011 order by c.descripcion";
$resp=mysql_query($sql);
$indice_tabla=1;
while($dat=mysql_fetch_array($resp))
{	$codigoFuncionario=$dat[0];
	$nombreFuncionario="$dat[1] $dat[2] $dat[3]";
	$nombreCiudad=$dat[4];
	$sqlfechadescarga="select max(r.fecha_registro) from registro_visita r, rutero ru 
						where r.cod_contacto=ru.cod_contacto and ru.cod_visitador=$codigoFuncionario";
	$respfechadescarga=mysql_query($sqlfechadescarga);
	$datfechadescarga=mysql_fetch_array($respfechadescarga);
	$ultimaFechaDescarga=$datfechadescarga[0];
	$diferencia=compara_fechas($fecha_anterior, $ultimaFechaDescarga);
	if($diferencia>0){
			echo "<tr><td align='center'>$indice_tabla</td><td>$nombreFuncionario</td><td align='center'>&nbsp;$ultimaFechaDescarga</td>
			<td align='center'>$nombreCiudad</td></tr>";		
			$indice_tabla++;
	}
}	
echo "</table></center><br>";
?>