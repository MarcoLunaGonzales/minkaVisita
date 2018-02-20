<?php 
header('Content-Type: text/xml'); 
echo '<markers>';
include("conexionmi.php");
$sql=mysqli_query($con,"select * from boletas_visita_cab where id_boleta=14");
while($row=mysqli_fetch_array($sql))
{
	echo "<marker id ='1' lat='".$row['latitud']."' lng='".$row['longitud']."'>\n";
	echo "<marker id ='2' lat='".$row['latitud']."' lng='".$row['longitud']."'>\n";
	echo "<marker id ='3' lat='".$row['latitud']."' lng='".$row['longitud']."'>\n";
	echo "</marker>\n";
}
//mysql_close($bd);
echo "</markers>\n";
?>