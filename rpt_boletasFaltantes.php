<?php
require("conexion.inc");
require('estilos_reportes_administracion.inc');
echo "<table align='center' class='textotit'><tr><th>Boletas Faltantes</th></tr>";
$sql="select distinct(funcionario) from reporte_cab order by 1";
echo "<table border=1 class='texto' align='center'>
<tr><th>Funcionario</th><th>Nro. Boleta</th><th>Línea</th></tr>";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$funcionario=$dat[0];
	$sqlBoletas="select nro_boleta, linea, ciclo, gestion  from reporte_cab where funcionario='$funcionario' order by indice";
	$respBoletas=mysql_query($sqlBoletas);
	$indice=1;
	while($datBoletas=mysql_fetch_array($respBoletas)){
		$nroBoleta=$datBoletas[0];	
		$linea=$datBoletas[1];
		$vectorNro=split(" de ",$nroBoleta);
		$nroBoletaInt=$vectorNro[0];
		if($nroBoletaInt!=$indice){
			$sqlCodLinea="select codigo_linea from lineas where nombre_linea='$linea'";
			$respCodLinea=mysql_query($sqlCodLinea);
			while($datCodLinea=mysql_fetch_array($respCodLinea)){
				$codigoLinea=$datCodLinea[0];
			}
			
			echo "<tr><td>$funcionario</td><td>$indice</td><td>$linea</td></tr>";		
			$indice=$nroBoletaInt+1;	
		}
		else{
			$indice++;
		}
		
	}
}
echo "</table>";
?>