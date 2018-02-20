<?php
require("conexion.inc");
require("estilos_visitador.inc");

for($i=1;$i<=$numero_productos;$i++){
	$producto="producto$i";
	$valorProd=$$producto;
	$frecuencia="frecuencia$i";
	$valorFrec=$$frecuencia;
	$sqlInsert="insert into encuestamedicos2 values($cod_med,'$valorProd',$valorFrec,0)";
	$respInsert=mysql_query($sqlInsert);
}

for($j=1;$j<=3;$j++){
	$producto="productoExtra$j";
	$valorProd=$$producto;
	$frecuencia="frecuenciaExtra$j";
	$valorFrec=$$frecuencia;
	$sqlInsert="insert into encuestamedicos2 values($cod_med,'$valorProd',$valorFrec,1)";
	$respInsert=mysql_query($sqlInsert);
}


?>
<script language=JavaScript>
	alert("los datos se insertaron satisfactoriamente");
	self.opener.location.reload();
	window.close();
</script>