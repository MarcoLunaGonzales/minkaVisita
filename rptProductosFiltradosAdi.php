<style type="text/css">
    .resul  th { color: #000; font-size: 14px}
</style>
<?php
set_time_limit(0);
require("conexion.inc");
require("estilos_reportes.inc");

$territorio = $_REQUEST['territorio'];
$formato    = $_REQUEST['formato'];

$sql_nombre_territorio = mysql_query(" SELECT cod_ciudad,descripcion from ciudades where cod_ciudad in ($territorio) ");
while ($row_nombre_territorio = mysql_fetch_array($sql_nombre_territorio)) {
    $nombre_territorio .= $row_nombre_territorio[1] . ", ";
}
$nombre_territorio = substr($nombre_territorio, 0, -2);
?>
<center>
    <table class='textotit'>
        <tr>
            <th>
                Productos Quitados y Adicionados por Medico <br />
                Territorio : <?php echo $nombre_territorio; ?> <br />
                Ver Productos: <?php echo $formato; ?><br/><br/>
            </th>
        </tr>
    </table>
</center>

<?php 
	if($formato=="Quitados"){
		$count = 1;
?>
    <center>
        <table class="resul" border="1">
            <tr>
                <th>&nbsp;</th>
                <th>Territorio</th>
                <th>Medico</th>
                <th>Especialidad</th>
                <th>Categoria</th>
                <th>Producto</th>
            </tr>
            <?php

                $sqlString="SELECT c.descripcion, concat(m.ap_pat_med,' ', m.ap_mat_med,' ' ,m.nom_med)medico, cl.cod_especialidad, cl.categoria_med, 
				CONCAT(mm.descripcion,' ', mm.presentacion)producto from muestras_negadas mn, medicos m, ciudades c, muestras_medicas mm, 
				categorias_lineas cl where mn.codigo_muestra=mm.codigo and m.cod_med=mn.cod_med and m.cod_ciudad=c.cod_ciudad and 
				m.cod_med=cl.cod_med and m.cod_ciudad in ($territorio) group By medico, producto order by 1,2,5";
				
				//echo $sqlString;
				$sql = mysql_query($sqlString);
				while($datos=mysql_fetch_array($sql)){
					$nombreCiudad=$datos[0];
					$nombreMedico=$datos[1];
					$codEspe=$datos[2];
					$catMed=$datos[3];
					$nombreProd=$datos[4];
                ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $nombreCiudad; ?></td>
                    <td><?php echo $nombreMedico; ?></td>
                    <td><?php echo $codEspe; ?></td>
                    <td><?php echo $catMed; ?></td>
                    <td><?php echo $nombreProd; ?></td>
                    <td>
                </tr>
                <?php
					$count++;
				}
            ?>
        </table>
    </center>
<?php
	}else{
		$count = 1;
?>
    <center>
        <table class="resul" border="1">
            <tr>
                <th>&nbsp;</th>
                <th>Territorio</th>
                <th>Medico</th>
                <th>Especialidad</th>
                <th>Categoria</th>
                <th>Producto</th>
                <th>Cantidad</th>
            </tr>
            <?php

                $stringSql="SELECT c.descripcion, concat(m.ap_pat_med,' ', m.ap_mat_med,' ' ,m.nom_med), cl.cod_especialidad, cl.categoria_med, CONCAT(mm.descripcion,' ', mm.presentacion), mn.frecuencia, mn.cantidad, mn.posicion from muestras_agregadas mn, medicos m, ciudades c, muestras_medicas mm, categorias_lineas cl where mn.codigo_muestra=mm.codigo and m.cod_med=mn.cod_med and c.cod_ciudad in ($territorio) and m.cod_ciudad=c.cod_ciudad and m.cod_med=cl.cod_med order by 1,2,5";
                //echo $stringSql;
                $sql = mysql_query($stringSql);
				while($datos=mysql_fetch_array($sql)){
					$nombreCiudad=$datos[0];
					$nombreMedico=$datos[1];
					$codEspe=$datos[2];
					$catMed=$datos[3];
					$nombreProd=$datos[4];
					$frecuencia=$datos[5];
					$cantidad=$datos[6];
					$posicion=$datos[7];
                ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $nombreCiudad; ?></td>
                    <td><?php echo $nombreMedico; ?></td>
                    <td><?php echo $codEspe; ?></td>
                    <td><?php echo $catMed; ?></td>
                    <td><?php echo $nombreProd; ?></td>
                    <td><?php echo $cantidad; ?></td>
                    <td>
                </tr>
                <?php
					$count++;
				}
            ?>
        </table>
    </center>
<?php	
	}
?>
