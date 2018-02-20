<script language='JavaScript'>
function totales(){
   var main=document.getElementById('main');   
   var numFilas=main.rows.length;
   var numCols=main.rows[2].cells.length;
	 
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
require("conexion.inc");
require("estilos_reportes.inc");
require("function_distribucionMMMA.php");
$rpt_visitador=$_GET["rpt_visitador"];
$rpt_gestion=$_GET["rpt_gestion"];
$rpt_ciclo=$_GET["rpt_ciclo"];
$rpt_territorio=$_GET["rpt_territorio"];
$rptVer=$_GET['rpt_ver'];

$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";
$resp1=mysql_query($sql_cab);
$dato=mysql_fetch_array($resp1);
$nombre_territorio=$dato[1];	
$cad_territorio="<br>Territorio: $nombre_territorio";

$sql_nombreGestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion=$rpt_gestion");
$dat_nombreGestion=mysql_fetch_array($sql_nombreGestion);
$nombreGestion=$dat_nombreGestion[0];

echo "<html><body onload='totales();'>";
echo "<table border='0' class='textotit' align='center'><tr><th>Reporte Distribución x Ciclo x Visitador, Linea y Grupos Especiales<br>
Gestion: $nombreGestion Ciclo: $rpt_ciclo $cad_territorio</th></tr></table></center><br>";

echo $rpt_visitador;

$vector=explode(",",$rpt_visitador);

$n=sizeof($vector);
echo $n;
for($i=0;$i < $n;$i++)
{
	$sqlLineas="select r.`codigo_linea` from `rutero_maestro_cab_aprobado` r where r.`cod_visitador`=$vector[$i] and r.`codigo_ciclo`=$rpt_ciclo 
	and r.`codigo_gestion`=$rpt_gestion";
	$respLineas=mysql_query($sqlLineas);
	while($datLineas=mysql_fetch_array($respLineas)){
		$codLinea=$datLineas[0];
		
		$var=insertaDistribucion($vector[$i], $codLinea, $rpt_ciclo, $rpt_gestion);
	}
	
}

for($i=0;$i < $n;$i++)
{
	$var=mostrarDistribucion($vector[$i],$rpt_ciclo, $rpt_gestion);
	$var1=mostrarDistribucionMA($vector[$i],$rpt_ciclo, $rpt_gestion);
	echo "<br>";
}

echo "</form></body></html>";
?>