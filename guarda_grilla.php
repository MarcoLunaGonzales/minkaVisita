<?php

require("conexion.inc");
require("estilos_gerencia.inc");
$sql="select codigo_grilla from grilla order by codigo_grilla desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$codigo=1000;
}
else
{	$codigo=$dat[0];
	$codigo++;
}

$global_linea=$_POST['codigoLinea'] ;
$idDistrito=$_POST['idDistrito'];

$fecha=date("Y-m-d");
$sql_inserta="insert into grilla values($codigo,'$nombre_grilla',$agencia,0,0,0,0,'$fecha','$fecha','$global_linea',0,'$idDistrito')";
$resp_inserta=mysql_query($sql_inserta);

	for($i=1;$i<=($cantidad-1);$i++)
	{	$frecuencia="frecuencia$i";
		$contacto="contacto$i";
		$medico="medicos$i";
		$especialidad="especialidad$i";
		$categoria="categoria$i";
		$lineavisita="lineaVisita$i";
		$val_frecuencia=$$frecuencia;
		$val_contacto=$$contacto;
		$val_medico=$$medico;
		$val_espe=$$especialidad;
		$val_cat=$$categoria;
		$val_lineaVisita=$$lineavisita;
		//echo "$val_espe $val_cat $val_frecuencia $val_medico $val_contacto";
		$sql_inserta_detalle="insert into grilla_detalle values($codigo,'$val_espe','$val_cat','$val_frecuencia','$val_lineaVisita')";
		//echo $sql_inserta_detalle;
		$resp_inserta_detalle=mysql_query($sql_inserta_detalle);
	}
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_grillas.php?cod_ciudad=$agencia&codigo_linea=$global_linea';
			</script>";
?>