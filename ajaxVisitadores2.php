<?php
 require('conexion.inc');
 $codTerritorio = $_GET['codTerritorio'];
 $codLinea      = $_GET['codLinea'];

 $sql_visitador="
     SELECT distinct(f.codigo_funcionario), f.paterno, f.materno, f.nombres
     FROM funcionarios f, cargos c, ciudades ci, funcionarios_lineas fl
     WHERE f.cod_cargo=c.cod_cargo AND f.codigo_funcionario=fl.codigo_funcionario
     AND f.cod_cargo='1011' AND f.estado=1 AND f.cod_ciudad IN ($codTerritorio) AND fl.codigo_linea IN ($codLinea)
     AND f.cod_ciudad=ci.cod_ciudad ORDER BY ci.descripcion,f.paterno";
 $resp_visitador=mysql_query($sql_visitador);
 echo "<select name='rpt_visitador' class='texto' size='15' multiple>";
 while($dat_visitador=mysql_fetch_array($resp_visitador)) {
     $codigo_visitador=$dat_visitador[0];
     $nombre_visitador="$dat_visitador[1] $dat_visitador[2] $dat_visitador[3]";
     echo "<option value='$codigo_visitador'>$nombre_visitador</option>";
 }
 echo "</select>";

?>