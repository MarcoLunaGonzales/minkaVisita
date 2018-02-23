<?php

/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
 */
echo "<script language='Javascript'>
		function guardarVisitadorLinea(f)
		{	var i;
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
			{	alert('Debe seleccionar al menos un Visitador.');
				return(false);
			}
			else
			{	 location.href='guardarVisitadorLineaVisita.php?datos='+datos+'&cod_linea_vis=$cod_linea_vis&ciclo=$ciclo&gestion=$gestion';
		    }
		}
		</script>
	";
require("conexion.inc");
require("estilos_gerencia.inc");
$familia = $_GET['familia'];
// $familia = $_GET['cod_linea_vis'];
echo "<form method='post' action=''>";
//formamos la cabecera
$sql_cab  = "SELECT nombre_l_visita from lineas_visita where codigo_l_visita = '$cod_linea_vis'";
$resp_cab = mysql_query($sql_cab);
$dat_cab  = mysql_fetch_array($resp_cab);
$nombre_linea_visita = $dat_cab[0];

$sqll = mysql_query("SELECT codigo_linea from lineas_visita where codigo_l_visita = $cod_linea_vis");
$linn = mysql_result($sqll, 0,0);
//fin formar cabecera
echo "<center><table border='0' class='textotit'><tr><td align='center'>Adicionar Visitadores<br>L&iacute;nea de Visita: <strong>$nombre_linea_visita</strong></td></tr></table></center><br>";

if($linn == 1031){

	$sql = "SELECT f.codigo_funcionario, f.paterno, f.materno, f.nombres, c.descripcion from funcionarios f, ciudades c where f.cod_ciudad=c.cod_ciudad and f.estado = 1 and f.cod_cargo = 1011 order by c.descripcion, f.paterno;";
}else{
	$sql = "SELECT f.codigo_funcionario, f.paterno, f.materno, f.nombres, c.descripcion from funcionarios f, ciudades c where f.cod_ciudad=c.cod_ciudad and f.estado = 1 and f.cod_cargo = 1011 and f.codigo_funcionario not in (SELECT l.codigo_funcionario from lineas_visita_visitadores l WHERE l.codigo_l_visita in ( $familia ) and l.codigo_ciclo = $ciclo and l.codigo_gestion = $gestion) order by c.descripcion, f.paterno;";
}

       // echo $sql;
$resp = mysql_query($sql);
echo "<center><table border='1' cellspacing='0' class='texto'>";
echo "<tr><th>&nbsp;</th><th>Territorio</th><th>Visitador</th></tr>";
while ($dat = mysql_fetch_array($resp)) {
    $codVisitador    = $dat[0];
    $nombreVisitador = "$dat[1] $dat[2] $dat[3]";
    $ciudadVisitador = $dat[4];
    echo "<tr><td align='center'><input type='checkbox' name='codigo' value='$codVisitador'></td>
		<td>$ciudadVisitador</td><td>$nombreVisitador</td></tr>";
}
echo "</table></center><br>";
echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1);'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<center><table border='0' class='texto'>";
echo "<tr><td><input type='button' value='A&ntilde;adir a L&iacute;nea Visita' class='boton' onclick='guardarVisitadorLinea(this.form)'></td></tr></table></center>";
echo "</form>";
?>