<?php
echo "<script language='Javascript'>
function anadir_categoria(f) {	
	var i;
	var j=0;
	datos=new Array();
	for(i=0;i<=f.length-1;i++) {
		if(f.elements[i].type=='checkbox') {	
			if(f.elements[i].checked==true) {	
				datos[j]=f.elements[i].value;
				j=j+1;
			}
		}
	}
	if(j==0) {	
		alert('Debe seleccionar al menos un Producto para añadirlo a la lista de productos Filtrados.');
		return(false);
	} else {	 
		location.href='guardar_prod_negado.php?datos='+datos+'&j_cod_med=$j_cod_med';
	}
}
function sel_todo(f) {
	var i;
	var j=0;
	for(i=0;i<=f.length-1;i++) {
		if(f.elements[i].type=='checkbox') {	
			if(f.todo.checked==true) {	
				f.elements[i].checked=true;
			} else {	
				f.elements[i].checked=false;
			}

		}
	}
}
</script> ";
require("conexion.inc");
require("estilos_regional_pri.inc");
$sql_cab=mysql_query("SELECT ap_pat_med, ap_mat_med, nom_med, cod_ciudad from medicos where cod_med='$j_cod_med'");
$dat_cab=mysql_fetch_array($sql_cab);
$nombre_medico="$dat_cab[2] $dat_cab[0] $dat_cab[1]";
$sql_cab_espe=mysql_query("SELECT c.cod_especialidad,c.categoria_med, es.desc_especialidad from categorias_lineas c, especialidades_medicos e, especialidades es where c.cod_med = e.cod_med and c.cod_especialidad = es.cod_especialidad and c.cod_med = '$j_cod_med' and c.cod_especialidad = e.cod_especialidad and c.codigo_linea = '$global_linea' order by es.desc_especialidad");
$num_filas_cab=mysql_num_rows($sql_cab_espe);
if($num_filas_cab==1) {	
	$dat=mysql_fetch_array($sql_cab_espe);
	$espe_cab="$dat[2]:<strong>$dat[0]</strong> Categoria:<strong>$dat[1]</strong>";
} else {	
	while($dat=mysql_fetch_array($sql_cab_espe)) {	
		$espe_cab = $espe_cab."$dat[2]:<strong>$dat[0]</strong> Categoria:<strong>$dat[1]</strong> ";
	}
}
echo "<center><table border='0' class='textotit'><tr><td>Seleccionar Productos a Quitar</td></tr></table></center>";
echo "<center><table border='0' class='textotit'><tr><td>Medico: <strong>$nombre_medico</strong> $espe_cab</td></tr></table></center><br>";
echo "<form method='post'>";
// $sql="SELECT codigo,descripcion,presentacion from muestras_medicas where estado = 1 order by descripcion";
$sql="SELECT DISTINCT pd.codigo_muestra, CONCAT(m.descripcion,' ',m.presentacion) from parrilla p , parrilla_detalle pd, muestras_medicas m where p.codigo_parrilla = pd.codigo_parrilla and m.codigo = pd.codigo_muestra and p.codigo_gestion = $codigo_gestion and p.cod_ciclo = $ciclo_global and p.cod_especialidad = '$dat[0]' and p.categoria_med = '$dat[1]' and p.codigo_linea = $global_linea and p.agencia = $dat_cab[3]";
$resp=mysql_query($sql);
echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<center><table border='0' class='texto'>";
echo "<tr><td><input type='button' value='Quitar' class='boton' onclick='anadir_categoria(this.form)'></td></tr></table></center>";
echo "<br><center><table class='texto' border=1 cellspacing='0'>";
echo "<tr><td><input type='checkbox' name='todo' onClick='sel_todo(this.form)'>Seleccionar Todo</td></tr></table></center>";
echo "<center><table border='1' cellspacing='0' class='texto'>";
echo "<tr><th>&nbsp;</th><th>Producto</th></tr>";
while($dat=mysql_fetch_array($resp)) {	
	$codigo_mm = $dat[0];
	$prod      = $dat[1];
	$pres      = $dat[2];
	$ban       = 0;
	// $sql_filtro="SELECT codigo_muestra from productos_objetivo where codigo_linea='$global_linea' and cod_med='$j_cod_med'";
	// $resp_filtro=mysql_query($sql_filtro);
	// while($dat_filtro=mysql_fetch_array($resp_filtro)) {	
	// 	$codigo_filtro=$dat_filtro[0];
	// 	if($codigo_filtro==$codigo_mm) {	
	// 		$ban=1;
	// 	}		
	// }
	$sql_filtro="SELECT codigo_muestra from muestras_negadas where codigo_linea='$global_linea' and cod_med='$j_cod_med'";
	$resp_filtro=mysql_query($sql_filtro);
	while($dat_filtro=mysql_fetch_array($resp_filtro)) {	
		$codigo_filtro=$dat_filtro[0];
		if($codigo_filtro==$codigo_mm) {	
			$ban=1;
		}		
	}
	if($ban==0) {	
		echo "<tr><td align='center'><input type='checkbox' name='codigo' value='$codigo_mm'></td><td>$prod</td></tr>";
	}		
}
echo "</table></center><br>";
echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<center><table border='0' class='texto'>";
echo "<tr><td><input type='button' value='Quitar' class='boton' onclick='anadir_categoria(this.form)'></td></tr></table></center>";
echo "</form>";