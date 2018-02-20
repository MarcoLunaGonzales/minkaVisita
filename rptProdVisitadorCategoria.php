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
require("funcion_nombres.php");
$rpt_visitador=$_GET["rpt_visitador"];
$rpt_gestion=$_GET["rpt_gestion"];
$rpt_ciclo=$_GET["rpt_ciclo"];
$rpt_territorio=$_GET["rpt_territorio"];
$rpt_linea=$_GET['rpt_linea'];
$rptNombreLinea=$_GET['rptNombreLinea'];
$nombreVisitador=nombreVisitador($rpt_visitador);

$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";
$resp1=mysql_query($sql_cab);
$dato=mysql_fetch_array($resp1);
$nombre_territorio=$dato[1];	
$cad_territorio="<br>Territorio: $nombre_territorio";

$sql_nombreGestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion=$rpt_gestion");
$dat_nombreGestion=mysql_fetch_array($sql_nombreGestion);
$nombreGestion=$dat_nombreGestion[0];
echo "<html><body>";

echo "<table border='0' class='textotit' align='center'><tr><th>Reporte Productos x Visitador y Categoria<br>
Gestion: $nombreGestion Ciclo: $rpt_ciclo $cad_territorio<br>
Linea: $rptNombreLinea<br> Funcionario: $nombreVisitador
</th></tr></table></center><br>";


	echo "<table border=1 class='texto' align='center' cellspacing=0 id='main'>";
	echo "<tr><th>Producto</th><th>Categoria</th><th>RPM</th><th>Cantidad</th></tr>";
	$sqlEspe="select distinct(rd.`cod_especialidad`) from `rutero_maestro_cab_aprobado` rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd 
	where rc.`cod_visitador`=$rpt_visitador and rc.`codigo_linea`=$rpt_linea and rc.`codigo_ciclo`=$rpt_ciclo and 
	rc.`codigo_gestion`=$rpt_gestion and rc.`cod_rutero`=rm.`cod_rutero` and rm.`cod_contacto`=rd.`cod_contacto`";
	
	$respEspe=mysql_query($sqlEspe);
	$cadEspe="";
	while($datEspe=mysql_fetch_array($respEspe)){
		$codEspe=$datEspe[0];
		$cadEspe.="'".$codEspe."',";
	}
	$cadEspe=substr($cadEspe,0,strlen($cadEspe)-1);
	
		$sqlProdCat="select pd.`codigo_muestra`, concat(m.descripcion, ' ', m.presentacion),m.cod_categoria, m.rpm, 
		 sum(pd.cantidad_muestra) as cantidad from parrilla p, 
		`parrilla_detalle` pd, muestras_medicas m where pd.codigo_muestra=m.codigo and p.`codigo_parrilla`=pd.`codigo_parrilla` 
		and p.`cod_ciclo`=$rpt_ciclo and p.`codigo_gestion`=$rpt_gestion and p.`agencia`=$rpt_territorio and p.`cod_especialidad` in 
		($cadEspe) group by pd.codigo_muestra
		order by m.rpm, m.cod_categoria, cantidad desc";
		
		$respProdCat=mysql_query($sqlProdCat);
		while($datProdCat=mysql_fetch_array($respProdCat)){
			$codProd=$datProdCat[0];
			$nombreProd=$datProdCat[1];
			$codCat=$datProdCat[2];
			$nombreCat=nombreCategoria($codCat, $conexion);	
			$codRPM=$datProdCat[3];
			$cantMuestra=$datProdCat[4];
			echo "<tr><td>$nombreProd</td><td>$nombreCat</td><td>$codRPM</td><td>$cantMuestra</td></tr>";
		}
	
	echo "</table>";
	
echo "</body></html>";
?>