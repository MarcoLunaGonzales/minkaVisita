<?php
echo "<script language='JavaScript'>
		function envia_select(form){
			form.submit();
			return(true);
		}
		function envia_formulario(f)
		{	var visitador;
			var rutero_maestro=new Array();
			visitador=f.visitador.value;
			var j=0;
			for(var i=0;i<=f.rutero_maestro.options.length-1;i++)
			{	if(f.rutero_maestro.options[i].selected)
				{	rutero_maestro[j]=f.rutero_maestro.options[i].value;
					j++;
				}
			}
			window.open('rpt_regional_medicos_rutero_maestro_consolidado.php?visitador='+visitador+'&rutero_maestro='+rutero_maestro+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
			return(true);
		}
		</script>";
require("conexion.inc");
require("estilos_regional_pri.inc");
require('espacios_regional.inc');
echo "<center><table class='textotit'><tr><th>Reporte Medicos en Rutero Maestro Consolidado</th></tr></table><br>";
echo"<form method='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='40%'>\n";
	echo "<tr><th align='left'>Visitador</th><td><select name='visitador' class='texto' onChange='envia_select(this.form)'>";
	$sql_visitador="select f.codigo_funcionario, f.paterno, f.materno, f.nombres
	from funcionarios f, cargos c, ciudades ci, funcionarios_lineas fl
	where f.cod_cargo=c.cod_cargo and f.cod_cargo='1011' and f.estado=1 and f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' and f.cod_ciudad='$global_agencia' and f.cod_ciudad=ci.cod_ciudad order by ci.descripcion,f.paterno,c.cargo";
	$resp_visitador=mysql_query($sql_visitador);
	echo "<option value=''></option>";
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	$codigo_visitador=$dat_visitador[0];
		$nombre_visitador="$dat_visitador[1] $dat_visitador[2] $dat_visitador[3]";
		if($visitador==$codigo_visitador)
		{	echo "<option value='$codigo_visitador' selected>$nombre_visitador</option>";
		}
		else
		{
			echo "<option value='$codigo_visitador'>$nombre_visitador</option>";
		}
	}
	echo "</select>";
	echo "</td></tr>";
	echo "<tr><th align='left'>Rutero(s) Maestro</th>
	<td><select name='rutero_maestro' class='texto' size='10' multiple>";
	$sql="select r.cod_rutero, r.nombre_rutero, r.estado_aprobado, r.codigo_ciclo, r.codigo_gestion, g.`nombre_gestion`, l.`nombre_linea`
				from rutero_maestro_cab r, `gestiones` g, `lineas` l 
				where cod_visitador = '$visitador' and g.`codigo_gestion`=r.`codigo_gestion` and l.`codigo_linea`=r.`codigo_linea` 
				order by nombre_rutero";
	$resp=mysql_query($sql);
	$filas_ruteros=mysql_num_rows($resp);
	while($dat=mysql_fetch_array($resp))
	{
		$cod_rutero=$dat[0];
		$nombre_rutero=$dat[1];
		$estado=$dat[2];
		$codCiclo=$dat[3];
		$nombreGestion=$dat[5];
		$nombreLinea=$dat[6];
		if($estado==0)
		{	$estado_desc="No Aprobado";
		}
		else
		{	$estado_desc="Aprobado";
		}
		echo "<option value='$cod_rutero'>$nombre_rutero ($codCiclo/$nombreGestion $nombreLinea)($estado_desc)</option>";
	}
	echo "</select>";
	echo "</td></tr>";
	echo"\n </table><br>";
	require('home_regional1.inc');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
?>