<script language='JavaScript'>
function envia_formularioResumen(f)
{	var rpt_territorio=new Array();
	var rpt_territorio1=new Array();
	var rpt_producto=f.rpt_producto.value;
	var j=0;
	for(var i=0;i<=f.rpt_territorio.options.length-1;i++)
	{	if(f.rpt_territorio.options[i].selected)
		{	rpt_territorio[j]=f.rpt_territorio.options[i].value;
			rpt_territorio1[j]=f.rpt_territorio.options[i].innerHTML;
			j++;
		}
	}
	var rpt_espe=new Array();
	var rpt_espe1=new Array();
	j=0;
	for(var i=0;i<=f.rpt_espe.options.length-1;i++)
	{	if(f.rpt_espe.options[i].selected)
		{	rpt_espe[j]=f.rpt_espe.options[i].value;
			rpt_espe1[j]=f.rpt_espe.options[i].innerHTML;
			j++;
		}
	}
	var rpt_cat=new Array();
	var rpt_cat1=new Array();
	j=0;
	for(var i=0;i<=f.rpt_cat.options.length-1;i++)
	{	if(f.rpt_cat.options[i].selected)
		{	rpt_cat[j]=f.rpt_cat.options[i].value;
			rpt_cat1[j]=f.rpt_cat.options[i].innerHTML;
			j++;
		}
	}
	
	window.open('rpt_productosTerritorioEspeCat.php?rpt_territorio='+rpt_territorio+'&rpt_nombreTerritorio='+rpt_territorio1+'&rpt_espe='+rpt_espe+'&rpt_espe1='+rpt_espe1+'&rpt_producto='+rpt_producto+'&rpt_cat='+rpt_cat+'&rpt_cat1='+rpt_cat1+'','','scrollbars=yes,status=yes,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');
	return(true);
}
function envia_formularioDetalle(f)
{	var rpt_territorio=new Array();
	var rpt_territorio1=new Array();
	var rpt_producto=f.rpt_producto.value;
	var j=0;
	for(var i=0;i<=f.rpt_territorio.options.length-1;i++)
	{	if(f.rpt_territorio.options[i].selected)
		{	rpt_territorio[j]=f.rpt_territorio.options[i].value;
			rpt_territorio1[j]=f.rpt_territorio.options[i].innerHTML;
			j++;
		}
	}
	var rpt_espe=new Array();
	var rpt_espe1=new Array();
	j=0;
	for(var i=0;i<=f.rpt_espe.options.length-1;i++)
	{	if(f.rpt_espe.options[i].selected)
		{	rpt_espe[j]=f.rpt_espe.options[i].value;
			rpt_espe1[j]=f.rpt_espe.options[i].innerHTML;
			j++;
		}
	}
	
	var rpt_cat=new Array();
	var rpt_cat1=new Array();
	j=0;
	for(var i=0;i<=f.rpt_cat.options.length-1;i++)
	{	if(f.rpt_cat.options[i].selected)
		{	rpt_cat[j]=f.rpt_cat.options[i].value;
			rpt_cat1[j]=f.rpt_cat.options[i].innerHTML;
			j++;
		}
	}

	window.open('rpt_productosTerritorioEspeCatDetallado.php?rpt_territorio='+rpt_territorio+'&rpt_nombreTerritorio='+rpt_territorio1+'&rpt_espe='+rpt_espe+'&rpt_espe1='+rpt_espe1+'&rpt_producto='+rpt_producto+'&rpt_cat='+rpt_cat+'&rpt_cat1='+rpt_cat1+'','','scrollbars=yes,status=yes,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');
	return(true);
}


</script>


<?php
	
require("conexion.inc");
require("estilos_gerencia.inc");

echo "<form action='' method='get'>";
	echo "<center><table class='textotit'><tr><th>Reporte por Producto, Territorio, Especialidad y Categoria de Medico</th></tr></table><br>";
	echo "<center><table class='texto' border='1' cellspacing='0'>";
	
	echo "<tr><th align='left'>Producto</th><td><select name='rpt_producto' class='texto'>";
	$sql="select codigo, concat(descripcion,' ',presentacion) from muestras_medicas order by 2";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigoProd=$dat[0];
		$nombreProd=$dat[1];
		echo "<option value='$codigoProd'>$nombreProd</option>";
	}
	echo "</select></td></tr>";

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
	
	echo "<tr><th align='left'>Especialidad</th><td><select name='rpt_espe' class='texto' size='12' multiple>";
	$sql="select e.`cod_especialidad` from `especialidades` e order by 1";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo=$dat[0];
		echo "<option value='`$codigo`'>$codigo</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Categoria</th><td><select name='rpt_cat' class='texto' size='4' multiple>";
	$sql="select c.`categoria_med` from `categorias_medicos` c where c.`categoria_med`<>'D' order by 1";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo=$dat[0];
		echo "<option value='`$codigo`'>$codigo</option>";
	}
	echo "</select></td></tr>";

	echo "</table><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte Resumen' onClick='envia_formularioResumen(this.form)' class='boton'>
	<input type='button' name='reporte' value='Ver Reporte Detallado' onClick='envia_formularioDetalle(this.form)' class='boton'>
	</center><br>";
	echo "</form>";
?>