<?php
echo "<script language='JavaScript'>
		function envia_select(form){
			form.submit();
			return(true);
		}
		function envia_formulario(f)
		{	var visitador;
			var rpt_territorio;
			rpt_territorio=f.rpt_territorio.value;
			visitador=f.visitador.value;
			window.open('rpt_central_universomedicosvisitador.php?visitador='+visitador+'&rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
			return(true);
		}
		</script>";
require("conexion.inc");
if($global_usuario==1052)
{	require("estilos_gerencia.inc");
}
else
{	require("estilos_inicio_adm.inc");
}
echo "<center><table class='textotit'><tr><th>Universo de Medicos x Visitador</th></tr></table><br>";
echo"<form method='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='30%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
    echo "<option value=0>Seleccione una opción</option>";
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($rpt_territorio==$codigo_ciudad)
		{	echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
		}
		else
		{	echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
		}
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Visitador</th><td><select name='visitador' class='texto'>";
	$sql_visitador="select f.codigo_funcionario, f.paterno, f.materno, f.nombres
	from funcionarios f, cargos c, ciudades ci
	where f.cod_cargo=c.cod_cargo and f.cod_cargo='1011' and f.estado=1
    and f.cod_ciudad='$rpt_territorio' and f.cod_ciudad=ci.cod_ciudad
    order by ci.descripcion,f.paterno,c.cargo";
	$resp_visitador=mysql_query($sql_visitador);
    echo "<option value=0>Todos</option>";
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	$codigo_visitador=$dat_visitador[0];
		$nombre_visitador="$dat_visitador[1] $dat_visitador[2] $dat_visitador[3]";
		echo "<option value='$codigo_visitador'>$nombre_visitador</option>";
	}
	echo "</select>";
	echo "</td></tr>";
	echo"\n </table><br>";
	echo"<table align='center'>";
	echo"</table>";
	if($global_usuario==1032)
	{	require('home_gerencia.inc');
	}
	else
	{	require('home_central.inc');
	}
	//echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'></center><br>";
	echo"</form>";
?>
