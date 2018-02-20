<script language='JavaScript'>

function envia_formulario(f)
{ var j=0;
	var rpt_territorio=new Array();
	var rpt_territorio1=new Array();
	for(var i=0;i<=f.rpt_territorio.options.length-1;i++)
	{	if(f.rpt_territorio.options[i].selected)
		{	rpt_territorio[j]=f.rpt_territorio.options[i].value;
			rpt_territorio1[j]=f.rpt_territorio.options[i].innerHTML;
			j++;
		}
	}
	window.open('rpt_grillaEstado.php?rpt_territorio='+rpt_territorio+'&rpt_nombreTerritorio='+rpt_territorio1+'','','scrollbars=yes,status=yes,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');
	return(true);
}
</script>


<?php
	
require("conexion.inc");
require("estilos_gerencia.inc");

echo "<form action='' method='get'>";
	echo "<center><table class='textotit'><tr><th>Reporte Grilla</th></tr></table><br>";
	echo "<center><table class='texto' border='1' cellspacing='0'>";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' size='12' multiple>";
	$sql="select c.cod_ciudad, c.descripcion from ciudades c, `funcionarios_agencias` f where 
				f.`cod_ciudad`=c.`cod_ciudad` and f.`codigo_funcionario`=$global_usuario order by c.descripcion";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	

	echo "</table><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo "</form>";
?>