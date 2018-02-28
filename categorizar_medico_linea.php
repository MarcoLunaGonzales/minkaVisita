<script language="Javascript">
function crearJson2(f){
	Show1();
	var dataV=[];
	for(var i=0; i<=f.elements.length-5;i=i+5){
			var data=new Object();
			var especialidad=f.elements[i].value;
			var categoria=f.elements[i+1].value;
			var visitador=f.elements[i+2].value;
			var medico=f.elements[i+3].value;
			var linea=f.elements[i+4].value;
			
			//alert(especialidad+' '+categoria+' '+visitador+' '+linea);
			data.esp=especialidad;
			data.cat=categoria;
			data.vis=visitador;
			data.med=medico;
			data.linea=linea;
			dataV.push(data);
	}
	var cadena=JSON.stringify(dataV);
	
	var contenedor;
	contenedor = document.getElementById("divEx");
	ajax=nuevoAjax();
	//ajax.open("POST", "guarda_cat_medico_linea.php?cadena="+cadena,true);
	ajax.open("POST", "guarda_cat_medico_linea.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			alert('Se guardaron los datos correctamente!');
			location.href='medicosCiudadesGeneral.php';
		}
	}
	ajax.send("cadena="+cadena);
}

function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function Show1(){
	document.getElementById('divRecuadroExt').style.visibility='visible';
}
</script>


<?php



require("conexion.inc");
require("estilos_gerencia.inc");
$vector=explode(",",$datos);
$n=sizeof($vector);

$codCiudadGlobal=$_GET['codCiudad'];

echo "<form method='post' action='guarda_cat_medico_linea.php' name='formu'>";

echo "<div id='divRecuadroExt' style='background-color:#666; position:fixed; width:1400px; height: 1000px; top:0px; left:0px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); z-index:2; text-align:center'>
    <span style='position: fixed; top: 300px; left: 600px;'><img src='imagenes/cargando2.gif'></span>
	</div>";
	
echo "<div id='divEx'>";
echo "<br>";
echo "<h1>Asignar / Quitar Medicos de Lineas</h1>";
for($k=0;$k<=$n;$k++)
{	$valor_vector=$vector[$k];
	$cadena=$cadena."|".$valor_vector;
}

//echo "<input type='hidden' name='cadena' value='$cadena'>";

echo "<center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Medico</th><th>Linea</th><th>Especialidades</th><th>Categoria</th><th>Visitador</th><th>Observaciones</th></tr>";
$codPivot=0;
$jj=0;
for($i=0;$i<$n;$i++)
{	$cod_med=$vector[$i];
	$sql="select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$cod_med'";
	$resp=mysql_query($sql);
	$dat_med=mysql_fetch_array($resp);
	$pat=$dat_med[0];$mat=$dat_med[1];$nombre=$dat_med[2];
	$nombre_completo="$pat $mat $nombre";
	
	if($codPivot==$cod_med){
	}else{
		if($color=="#F3F781"){
			$color="#81DAF5";
		}else{
			$color="#F3F781";
		}
		$codPivot=$cod_med;
	}
	$indice=$i+1;
	
	$sqlLineas="select codigo_linea, nombre_linea from lineas l where l.linea_promocion=1 and l.estado=1 order by 2";
	$respLineas=mysql_query($sqlLineas);
	
	
	while($datLineas=mysql_fetch_array($respLineas)){
		$codLinea=$datLineas[0];
		$nombreLinea=$datLineas[1];
		//aca verificamos los datos si esta en la linea.
		$sqlVeri="select c.categoria_med, c.cod_especialidad from categorias_lineas c where c.codigo_linea='$codLinea' and c.cod_med='$cod_med'";
		$respVeri=mysql_query($sqlVeri);
		$numRegVeri=mysql_num_rows($respVeri);
		while($datVeri=mysql_fetch_array($respVeri)){
			$catMedVeri=$datVeri[0];
			$codEspeVeri=$datVeri[1];
		}
		
		$sqlVisAsig="select m.codigo_visitador from medico_asignado_visitador m where m.codigo_linea='$codLinea' and m.cod_med='$cod_med'";
		$respVisAsig=mysql_query($sqlVisAsig);
		$codVisitadorAsig=0;
		while($datVisAsig=mysql_fetch_array($respVisAsig)){
			$codVisitadorAsig=$datVisAsig[0];
		}

		
		$cadMostrar="";
		$cadMostrar=$cadMostrar."<tr bgcolor='$color'><td>$indice</td><td>$nombre_completo</td><td>$nombreLinea</td>";
		$sql2="select em.cod_especialidad from especialidades_medicos em, especialidades e where 
		em.cod_med='$cod_med' and em.cod_especialidad=e.cod_especialidad order by em.cod_especialidad";
		$resp2=mysql_query($sql2);
		$cadMostrar=$cadMostrar."<td align='left'><select name='especialidad_med$jj' class='texto'>";
		while($dat2=mysql_fetch_array($resp2))
		{	$espe=$dat2[0];
			if($espe==$codEspeVeri){
				$cadMostrar=$cadMostrar."<option value='$espe' selected>$espe</option>";
			}else{
				$cadMostrar=$cadMostrar."<option value='$espe'>$espe</option>";
			}
			
		}
		$cadMostrar=$cadMostrar."<td align='center'>";
		$sql_cat="select categoria_med from categorias_medicos order by categoria_med asc";
		$resp_cat=mysql_query($sql_cat);
		$cadMostrar=$cadMostrar."<select name='cat$jj' class='texto'>";
		while($dat_cat=mysql_fetch_array($resp_cat))
		{	$cat=$dat_cat[0];
			if($cat==$catMedVeri){
				$cadMostrar=$cadMostrar."<option value='$cat' selected>$cat</option>";	
			}else{
				$cadMostrar=$cadMostrar."<option value='$cat'>$cat</option>";	
			}
		}
		$cadMostrar=$cadMostrar."</select>";
		$cadMostrar=$cadMostrar."</td>";
		
		$cadMostrar=$cadMostrar."<td align='left'>";
		$sqlF="select f.codigo_funcionario, concat(paterno, ' ', materno, ' ',nombres) from funcionarios f, 
			funcionarios_lineas fl where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$codLinea' 
			and f.cod_ciudad='$codCiudadGlobal' and f.estado=1 and f.cod_cargo=1011 order by 2;";
		//echo $sqlF;
		$respF=mysql_query($sqlF);
		$numF=mysql_num_rows($respF);
		$cadMostrar=$cadMostrar."<select name='func$jj' class='texto'><option value='0'>--</option>";
		while($datF=mysql_fetch_array($respF))
		{	$codigoFunc=$datF[0];
			$nombreFunc=$datF[1];
			if($codVisitadorAsig==$codigoFunc){
				$cadMostrar=$cadMostrar."<option value='$codigoFunc' selected>$nombreFunc</option>";	
			}else{
				$cadMostrar=$cadMostrar."<option value='$codigoFunc'>$nombreFunc</option>";	
			}
		}
		$cadMostrar=$cadMostrar."</select>";
		$cadMostrar=$cadMostrar."</td>";
		if($numRegVeri>0){
			$cadMostrar=$cadMostrar."<td>Registrado en la Linea</td>";
		}else{
			$cadMostrar=$cadMostrar."<td>&nbsp;</td>";
		}
		
		$cadMostrar=$cadMostrar."</tr>";
		
		if($numF>=1){
			echo $cadMostrar;
			echo "<input type='hidden' name='med$jj' value='$cod_med'>";
			echo "<input type='hidden' name='linea$jj' value='$codLinea'>";
		}
		
		$jj++;
	}
	
	
}
//echo "<input type='hidden' name='cantidad' value='$jj' id='cantidad'>";
echo "</table></center><br>";

	echo "<table border=0 class='texto'><tr><th>Nota: Si no asigna visitador el medico no sera registrado en la linea.</th></tr></table>";


echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";

echo "<div class='divBotones'>
<input type='button' name='x' value='Guardar' class='boton' onClick='crearJson2(this.form);'>
</div>";

echo "</div>";
echo "</form>";
?>