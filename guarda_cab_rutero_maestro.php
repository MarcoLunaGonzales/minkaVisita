<?php
require("conexion.inc");
require("estilos_visitador.inc");
$sql="select max(cod_rutero) as cod_rutero from rutero_maestro_cab order by cod_rutero desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
$nombre_rutero=$_POST['nombre_rutero'];
$ciclo=$_POST['ciclo'];
list($codCiclo,$codGestion)=explode("|",$ciclo);

if($num_filas==0)
{	$codigo=1000;
}
else
{	$codigo=$dat[0];
	$codigo++;
}
$sqlVeri="select (max(codigo_ciclo)) from rutero_maestro_cab where cod_visitador=$global_visitador and codigo_linea=$global_linea and codigo_gestion=$codGestion";
$respVeri=mysql_query($sqlVeri);
$maxCiclo=mysql_result($respVeri,0,0);

//if($maxCiclo==$codCiclo || $maxCiclo==0){
	$sql_inserta=mysql_query("insert into rutero_maestro_cab values($codigo,'$nombre_rutero','$global_visitador',0,'$global_linea','$codCiclo','$codGestion','0000-00-00',0)");
	if($sql_inserta==1) {
		echo "<script language='Javascript'>
				alert('Los datos fueron insertados correctamente.');
				location.href='navegador_rutero_maestro.php';
				</script>";  
	}
	else {
	  echo "<script language='Javascript'>
				alert('No puede insertar un Rutero Maestro con ese nombre porque ya existe.');
				history.back(-1);
				</script>";
	}
//}else{
//	echo "<script language='Javascript'>
//		alert('El rutero debe estar asociado al ciclo $maxCiclo.');
//		history.back(-1);
//		</script>";
//}



?>