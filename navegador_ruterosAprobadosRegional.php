<script language=JavaScript>
		function aprobar(f)
		{
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos un registro para enviar a Aprobación.');
			}
			else
			{
				location.href='aprobarRuteroRegional.php?datos='+datos+'';
			}
		}
</script>

<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
require("conexion.inc");
require("estilos_regional_pri.inc");

//$cod_ciclo=$_get['cod_ciclo'];
//$cod_gestion=$_get['cod_gestion'];

$cod_ciclo=$cod_ciclo;
$cod_gestion=$cod_gestion;

echo "<form method='post' action=''>";
echo "<center><table border='0' class='textotit'><tr><th>Ruteros Medico Maestro Aprobados x Ciclo</th></tr></table></center><br>";
echo "<center><table border='1' width='80%' cellspacing='0' class='texto'><tr><th>&nbsp;</th>
<th>Visitador</th><th>Nombre de Rutero</th>
<th>Ciclo Asociado</th><th>Estado</th><th>&nbsp;</th></tr>";

$sql="select r.cod_rutero, r.nombre_rutero, r.estado_aprobado, r.codigo_ciclo, r.codigo_gestion, concat(f.`paterno`,' ', f.`nombres`),
	f.codigo_funcionario
	from rutero_maestro_cab_aprobado r, funcionarios f where r.`cod_visitador`=f.`codigo_funcionario` and 
	r.codigo_gestion = '$cod_gestion' and r.codigo_ciclo = '$cod_ciclo' and r.codigo_linea = '$global_linea' and 
	f.`cod_ciudad`='$global_agencia' order by 6";

$resp=mysql_query($sql);
$filas_ruteros=mysql_num_rows($resp);
$nombreGestion="";
while($dat=mysql_fetch_array($resp))
{
	$cod_rutero=$dat[0];
	$nombre_rutero=$dat[1];
	$estado=$dat[2];
	$codCiclo=$dat[3];
	$codigoGestion=$dat[4];
	$nombreVisitador=$dat[5];
	$codVisitador=$dat[6];
	
	$sqlGestion="select nombre_gestion from gestiones where codigo_gestion=$codigoGestion";
	$respGestion=mysql_query($sqlGestion);
	$datGestion=mysql_fetch_array($respGestion);
	$nombreGestion=$datGestion[0];
	
	if($estado==1){$nombreEstado="Aprobado";}
	if($estado==2){$nombreEstado="En Aprobacion";}
	if($estado==3){$nombreEstado="Aprobado Individual";}
	if($estado==0){$nombreEstado="No Aprobado";}
	

	echo"<tr><td><input type='checkbox' class='texto' name='chk' value='$cod_rutero' checked onClick='return(false);'></td><td>$nombreVisitador</td>
	<td>&nbsp;$nombre_rutero</td><td>$codCiclo/$nombreGestion</td>
	<td align='center'>$nombreEstado</td>
	<td align='center'><a href='rutero_maestro_todo.php?rutero=$cod_rutero&aprobado=1&vista=2'>Ver Todo >></a></td>
	</tr>";	
}
echo "</table><br>";
echo "<center><input type='button' value='Aprobar' name='adicionar' class='boton' onclick='aprobar(this.form)'></center>";
echo "</form>";
?>