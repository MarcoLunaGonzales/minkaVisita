<?php
require("conexion.inc");
require("estilos_reportes.inc");
$rpt_visitador=$rpt_visitador;
$rpt_gestion=$rpt_gestion;
$rpt_ciclo=$rpt_ciclo;
$rpt_territorio=$rpt_territorio;

echo "<table align='center' class='textotit'><tr><th>Fechas de Registro x Ciclo<br>
			</table><br>";

echo "<table class='texto' border='1' cellspacing='0' cellpading='0' align='center'>
			<tr><th>Visitador</th><th>Fecha Registro</th><th>Dia Contacto</th><th>% de Registro</th></tr>";
		
$sql="select concat(f.`paterno`, ' ', f.`nombres`) as visitador, r.`FECHA_REGISTRO`,
       r.`COD_DIA_CONTACTO`, o.`dia_contacto`, f.codigo_funcionario
       from `reg_visita_cab` r, `orden_dias` o, `funcionarios` f 
       where r.`COD_CICLO` = ($rpt_ciclo) and o.`id` = r.`COD_DIA_CONTACTO` and 
       f.`codigo_funcionario` = r.`COD_VISITADOR` and r.`COD_GESTION` = $rpt_gestion and 
       r.`COD_VISITADOR` in ($rpt_visitador) group by visitador, r.`FECHA_REGISTRO`, r.`COD_DIA_CONTACTO` 
       order by visitador, r.`FECHA_REGISTRO`, o.`id`";

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$nombreVis=$dat[0];
	$fechaRegistro=$dat[1];
	$diaContacto=$dat[3];
	$codVisitador=$dat[4];
	//sacamos el total de visitas y el total de visitados
	$sqlVeri="select estado from rutero_detalle where cod_contacto in 
	(select cod_contacto from rutero where cod_visitador=$codVisitador and cod_ciclo=$rpt_ciclo and codigo_gestion=$rpt_gestion and dia_contacto='$diaContacto')";
	$respVeri=mysql_query($sqlVeri);
	$filasVeri=mysql_num_rows($respVeri);
	$numVisitas=0;
	while($datVeri=mysql_fetch_array($respVeri)){
		$codEstado=$datVeri[0];
		if($codEstado==1){
			$numVisitas++;
		}
	}
	$porcVisitas=($numVisitas/$filasVeri)*100;
	echo "<tr><td>$nombreVis</td><td>$fechaRegistro</td><td>$diaContacto</td><td>$porcVisitas</td></tr>";     

}
echo "</table>";
?>