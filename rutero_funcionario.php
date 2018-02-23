<?php
require("conexion.inc");
require("estilos_regional_pri.inc");

echo "<script language='Javascript'>
		function aprobar_rutero(f)
		{
			var i;
			var j=0;
			var j_cargo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cargo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un Rutero Maestro para aprobarlo.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Rutero Maestro para aprobarlo.');
				}
				else
				{
					location.href='aprobar_rutero_maestro.php?cod_rutero='+j_cargo+'&visitador=$visitador';
				}
			}
		}
		
		function rechazar_rutero(f)
		{
			var i;
			var j=0;
			var j_cargo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cargo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un Rutero Maestro para rechazarlo.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Rutero Maestro.');
				}
				else
				{
					location.href='rechazar_rutero_maestro.php?cod_rutero='+j_cargo+'&visitador=$visitador';
				}
			}
		}
		</script>";
		
$sql_cab = "select paterno,materno,nombres from funcionarios where codigo_funcionario='$visitador'";
$resp_cab = mysql_query($sql_cab);
$dat_cab = mysql_fetch_array($resp_cab);
$nombre_funcionario = "$dat_cab[0] $dat_cab[1] $dat_cab[2]";
echo "<form method='post' action=''>";
echo "<h1>Ruteros Medicos Maestro<br>Visitador: $nombre_funcionario</h1>";

echo "<center><table class='texto'><tr><th>&nbsp;</th><th>Nombre de Rutero</th>
	<th>Ciclo Asociado</th><th>Estado</th><th>Ver</th></tr>";

$sql_cargo = mysql_query("SELECT cod_cargo from funcionarios where codigo_funcionario = $visitador");
$cargo = mysql_result($sql_cargo, 0, 0);

$sql = "SELECT cod_rutero, nombre_rutero, estado_aprobado, codigo_ciclo, codigo_gestion from rutero_maestro_cab  where cod_visitador='$visitador' and codigo_linea='$global_linea' order by nombre_rutero";
$resp = mysql_query($sql);
$filas_ruteros = mysql_num_rows($resp);
while ($dat = mysql_fetch_array($resp)) {
    $cod_rutero = $dat[0];
    $nombre_rutero = $dat[1];
    $estado = $dat[2];
    $codCiclo = $dat[3];
    $codigoGestion = $dat[4];

    $sqlGestion = "select nombre_gestion from gestiones where codigo_gestion=$codigoGestion";
    $respGestion = mysql_query($sqlGestion);
    $datGestion = mysql_fetch_array($respGestion);
    $nombreGestion = $datGestion[0];

    if ($estado == 0) {
        $estado_desc = "No Aprobado";
        $check="";
    } 
    if ($estado == 1) {
        $estado_desc = "Aprobado";
        $check="";
    } 
    if ($estado == 3) {
        $estado_desc = "Aprobado Individualmente";
        $check="";
    } 
    if ($estado == 2) {
        $estado_desc = "En Aprobacion";
        $check="<input type='checkbox' name='codigo' value='$cod_rutero'>";
    } 
    echo"<tr><td>&nbsp;$check</td><td align='center'>$nombre_rutero</td><td>$codCiclo/$nombreGestion</td><td align='center'>$estado_desc</td>
    <td align='center'>
		<a href='funcionario_rutero_maestro_todo.php?rutero=$cod_rutero&visitador=$visitador'>
			<img src='imagenes/detalle.png' width='40' title='Ver Rutero Linea'></a> -- 
		<a href='ruteroConsolidado.php?codCiclo=$codCiclo&codGestion=$codigoGestion&visitador=$visitador&vista=0'>
			<img src='imagenes/detalles.png' width='40' title='Consolidado'></a>
		</td></tr>";
} 
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_funcionarios_regional.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<center><table border='0' class='texto'>";
echo "<tr><td><input type='button' value='Aprobar' class='boton' onclick='aprobar_rutero(this.form)'>
<input type='button' value='Rechazar' class='boton' onclick='rechazar_rutero(this.form)'>
</td></tr></table></center>";
echo "</form>";

?>