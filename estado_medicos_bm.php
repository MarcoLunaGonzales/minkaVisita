<?php
error_reporting(0);
require("conexion.inc");
$estado = $_GET['estado'];
$ciclo_gestion_inicio = $_GET['ciclo_inicio'];
$ciclo_gestion_destino = $_GET['ciclo_destino'];

$ciclo_gestion_inicio_explode = explode("-", $ciclo_gestion_inicio);
$ciclo_inicio = $ciclo_gestion_inicio_explode[0];
$gestion_inicio = $ciclo_gestion_inicio_explode[1];

$ciclo_gestion_destino_final = explode("-", $ciclo_gestion_destino);
$ciclo_destino = $ciclo_gestion_destino_final[0];
$gestion_destino = $ciclo_gestion_destino_final[1];



$territorios = $_GET['territorios'];

if($estado == 0) {
    $estado_final = "Aprobados, Rechazados, Pre-Aprobados";
    $cadena = '';
}
if($estado == 1) {
    $estado_final = "Pre-Aprobados";
    $cadena = ' and bm.estado = 3 ';
}
if($estado == 2) {
    $estado_final = "Aprobados";
    $cadena = 'and bm.estado = 1';
}
if($estado == 3) {
    $estado_final = "Rechazados";
    $cadena = 'and bm.estado = 2';
}

$sql_ciudades = mysql_query("SELECT descripcion from ciudades where cod_ciudad in ($territorios)");
while ($row_ciudades = mysql_fetch_array($sql_ciudades)) {
    $regionales_finales .= $row_ciudades[0].", ";
}
$regionales_finales = substr($regionales_finales, 0, -2);
$regionales_finales_explode = explode(", ", $regionales_finales);
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Asignacion De Productos</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function($) {

    });
    </script>
    <style type="text/css">
    table tr th {
        padding: 0 10px    
    }
    </style>
</head>
<body>
    <div id="container">
        <?php require("estilos3.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Reporte de M&eacute;dicos <?php echo $estado_final; ?></h3>
            <h3 style="color: #5F7BA9; font-size: 1.0em; font-family: Vernada">Para las regionales: <?php echo $regionales_finales; ?> </h3>
        </header>
        <div class="row">
            <?php foreach($regionales_finales_explode as $territorios_nombre){ ?>
                <div class="twelve columns centered">
                    <h2 style="color: #5F7BA9; font-size: 1.3em; font-family: Vernada; text-align: left"><?php echo $territorios_nombre; ?></h2>
                    <table border="1">
                        <tr>
                            <th>RUC</th>
                            <th>Nombre M&eacute;dicos</th>
                            <th>Ciclo Inicio</th>
                            <th>Ciclo Final</th>
                            <th>Detallado</th>
                            <th>Cantidad Total Muestras</th>
                            <th>Estado</th>
                            <th>Obs</th>
                        </tr>
                        <?php 
						$txtMedicos="SELECT DISTINCT CONCAT(m.nom_med,' ',m.ap_pat_med,' ',m.ap_mat_med) as nombre, bm.estado, 
						bm.cod_med, bm.ciclo_inicio, bm.ciclo_final from banco_muestras bm, banco_muestras_detalle bmd, 
						banco_muestra_cantidad_visitador bmv, ciudades c, medicos m where bm.id = bmd.id and bm.id = bmv.id_for 
						and bm.cod_med = m.cod_med and m.cod_ciudad = c.cod_ciudad 
						and bm.ciclo_final >= $ciclo_inicio and bm.ciclo_inicio <= $ciclo_destino and bm.gestion = $gestion_destino
						and c.descripcion = '$territorios_nombre' $cadena order by 1";
						//echo $txtMedicos;
						$sql_medicos = mysql_query($txtMedicos); ?>
                        <?php while($row_medicos = mysql_fetch_array($sql_medicos)){ ?>
                            <tr>
                                <td><?php echo $row_medicos[2]; ?></td>
                                <td><?php echo $row_medicos[0]; ?></td>
                                <td><?php echo $row_medicos[3]; ?></td>
                                <td><?php echo $row_medicos[4]; ?></td>
                                <td>
                                    <table border="1" style="margin-top:10px">
                                        <tr>
                                            <th>MM</th>
                                            <th>Visitador</th>
                                            <th>Total</th>
                                        </tr>
                                        <?php  
                                        $txtMuestra="SELECT bm.id,bmd.cod_muestra, CONCAT(m.descripcion,' ',m.presentacion) from 
										banco_muestras bm, banco_muestras_detalle bmd, muestras_medicas m where bm.id = bmd.id 
										and bm.cod_med = $row_medicos[2] and bmd.cod_muestra = m.codigo";
										$sql_cod_muestra = mysql_query($txtMuestra);
										
										$totalMuestrasxMedico=0;
										
                                        while ($row_cod_muestra = mysql_fetch_array($sql_cod_muestra)) {
                                        $sum_parcial = 0;
                                        ?>
                                        <tr>
                                            <td><?php echo $row_cod_muestra[2]."($row_cod_muestra[0])"; ?></td>
                                            <td>
                                                <table border="1" style="margin-top:10px">
                                                    <?php $sql_visitadores = mysql_query("SELECT CONCAT(f.nombres,' ',f.paterno,' ',f.materno) as nombres, bmv.cantidad FROM banco_muestra_cantidad_visitador bmv, funcionarios f where f.codigo_funcionario = bmv.cod_visitador and bmv.id_for = $row_cod_muestra[0] and bmv.cantidad>0 and bmv.codigo_muestra = '$row_cod_muestra[1]'"); ?>
                                                    <?php while($row_vis = mysql_fetch_array($sql_visitadores)){ ?>
                                                    <tr>
                                                        <td><?php echo $row_vis[0]; ?></td>
                                                        <td><?php echo $row_vis[1]; ?></td>
                                                    </tr>
                                                    <?php $sum_parcial = $sum_parcial + $row_vis[1]; ?>
                                                    <?php } ?>
                                                </table>
                                            </td>
                                            <td><?php 
											$totalMuestrasxMedico=$totalMuestrasxMedico+$sum_parcial;
											echo $sum_parcial; 
											?></td>
                                        </tr>
                                        <?php    
                                        }
                                        ?>
                                    </table>
                                </td>
                                <td>
                                    <?php $sql_total = mysql_query("SELECT SUM(cantidad) from banco_muestras_detalle where cod_med = $row_medicos[2]; ") ?>
                                    <?php $numTotalMedico=mysql_result($sql_total, 0, 0);?>
									<?php echo $numTotalMedico;?>
                                </td>
                                <td>
                                    <?php 
                                        if($row_medicos[1] == 0){echo "<span style=''>Registrado</span>";} 
                                        if($row_medicos[1] == 3){echo "<span style='color:blue;font-weight:bold'>Pre-Aprobado</span>";} 
                                        if($row_medicos[1] == 1){echo "<span style='color:green;font-weight:bold'>Aprobado</span>";} 
                                        if($row_medicos[1] == 2){echo "<span style='color:red;font-weight:bold'>Rechazado</span>";} 
                                    ?>
                                </td>
								<?php
									if($numTotalMedico!=$totalMuestrasxMedico){
										$bgColor="#ff0000";
									}else{
										$bgColor="#ffffff";
									}
								?>
								<td bgcolor="<?php echo $bgColor; ?>">			&nbsp;	
								</td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>