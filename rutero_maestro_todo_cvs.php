<?php  
require("conexion.inc");
$cod_rutero       = $_GET['rutero'];
$global_visitador = $_REQUEST['global_visitador'];
error_reporting(0);

header ( "Content-Type: text/html; charset=UTF-8" );
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

if($vista==2){
	$nombreTabla="rutero_maestro_cab_aprobado";
	$nombreTabla1="rutero_maestro_aprobado";
	$nombreTabla2="rutero_maestro_detalle_aprobado";
}else{
	$nombreTabla="rutero_maestro_cab";
	$nombreTabla1="rutero_maestro";
	$nombreTabla2="rutero_maestro_detalle";
}
$sql_nom_rutero = mysql_query("SELECT nombre_rutero from $nombreTabla where cod_rutero = '$cod_rutero' and cod_visitador = '$global_visitador'");
$nom_rutero     = mysql_result($sql_nom_rutero, 0, 0);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Rutero Maestro Detallado</title>
	<link type="text/css" href="css/style.css" rel="stylesheet" />
	<link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
	<link rel="stylesheet" href="responsive/stylesheets/style.css">
	<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
	<script>
		jQuery(document).ready(function($) {
			$(".adicionar").click(function(){
				location.href = 'creacion_rutero_maestro_cvs.php?rutero=<?php echo $cod_rutero ?>';
			})
			$(".eliminar").click(function(){
				var codss = "";
				$('.elimi:checked').each(function(){
					codss += $(this).val()+",";
				})
				$.ajax({
					type: "POST",
					url: "ajax/rutero_cvs/eliminar2.php",
					dataType : 'json',
					data: { 
						codigos : codss,
						visitaddor : '<?php echo $global_visitador ?>'
					}
				}).done(function(data) {
					alert('Los datos fueron eliminados.');
					location.reload();
				});
			})	
		});
	</script>
	<style type="text/css">
		.rutero tr th {
			padding: 5px 10px    
		} 
		.rutero tbody tr td {
			padding: 5px 10px !important   
		}
		table tr th {
			padding: 2px 10px    
		} 
		table tbody tr td {
			padding: 2px 10px !important   
		}
		#add-row {
			background: transparent url('imagenes/add-slide.png') no-repeat scroll 50% 50%;
			cursor: pointer;
			width: 102px;
			height: 32px;
			float: left;
			margin: 0 !important;
		}
		#add-row:hover { 
			background: transparent url('imagenes/add-slide-hover.png') no-repeat scroll 50% 50%;
			cursor: pointer;
			width: 102px;
			height: 32px;
		}
		td.deleteSlide	{
			background: transparent url('imagenes/delete-slide.png') no-repeat scroll 50% 50%;
			cursor:pointer;
		}
		.small {
			height: 25px !important;
			padding: 0 !important;
			margin-top: 0 !important;
		}
		#cab th {
			padding: 0 !important
		}
		#table-clone td, #table-principal td {
			padding: 0 !important
		}
		.numero_visita {
			display: none !important;
		}
	</style>
	<script>
		jQuery(document).ready(function($) {
			
		});
	</script>
</head>
<body>
	<div id="container">
		<?php 
		require("estilos3.inc"); 
		?>		
		<header id="titulo" style="min-height: 50px">
			<h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Registro de Rutero M&eacute;dico Maestro CVS</h3>
			<h3 style="color: #5F7BA9; font-size: 1.3em; font-family: Vernada">Nombre Rutero: <?php echo $nom_rutero; ?></h3>
		</header>
		<?php  
		$sql_rutero = mysql_query("SELECT r.cod_contacto, r.cod_rutero, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje from $nombreTabla1 r, orden_dias o where r.cod_visitador = $global_visitador and r.cod_rutero = '$cod_rutero' and r.dia_contacto = o.dia_contacto order by o.id, r.turno");
		$num_rutero = mysql_num_rows($sql_rutero);
		?>
		<div class="row">
			<div class="four columns centered">
				<?php  
				if($num_rutero == 0){
					?>
					<a href="javascript:void(0)" class="button">Replicar Rutero</a>
					<?php  
				}
				?>
				<a href="#adicionar_medicos" class="button adicionar">Adicionar</a>
				<!-- <a href="javascript:void(0)" class="button">Editar</a> -->
				<a href="javascript:void(0)" class="button">Eliminar</a>
				<!-- <a href="javascript:void(0)" class="button">Completar 1/2 Ciclo</a> -->
			</div>
		</div>
		<div class="row" style="margin: 15px 0">
			<div class="twelve columns">
				<center>
					<table class="rutero">
						<tr>
							<th>&nbsp;</th>
							<th>D&iacute;a Contacto</th>
							<th>Turno</th>
							<th>Contactos</th>
						</tr>
						<?php  
						if($num_rutero == 0){
							?>
							<tr>
								<td colspan="4" style="text-align: center; font-weight:bold">No hay detalle del rutero</td>
							</tr>
							<?php
						} else{
							$indice = 1;
							while ($row_rutero = mysql_fetch_array($sql_rutero)) {
								$cod_contacto  = $row_rutero[0];
								$cod_ciclo     = $row_rutero[1];
								$dia_contacto  = $row_rutero[3];
								$turno         = $row_rutero[4];
								$zona_de_viaje = $row_rutero[5];							

								$sql_fi = mysql_query("SELECT * from rutero_maestro_detalle where cod_contacto = $cod_contacto and cod_visitador = $global_visitador");
								$tabla_aux = "<table class='textomini' width='100%'>";
								$tabla_aux = $tabla_aux."<tr><th width='5%'>Orden</th><th>Nro.Cont.</th><th width='25%'>Medico</th><th width='5%'>Especialidad</th><th width='10%'>Categoria</th><th width='30%'>Direccion</th><th width='15%'>Zona</th></tr>";
								while ($row_fi =  mysql_fetch_array($sql_fi)) {
									$orden_visita  = $row_fi[1];
									$cod_med       = $row_fi[3];
									$cod_espe      = $row_fi[4];
									$cod_cat       = $row_fi[5];
									$cod_zona      = $row_fi[6];
									$estado        = $row_fi[7];
									$tipoo         = $row_fi[8];

									
									if($tipoo == 1){
										$sql_orden = mysql_query("SELECT nombre, direccion from centros_medicos where cod_centro_medico = $cod_med");
										$nombre = mysql_result($sql_orden, 0, 0);
										$direccion = mysql_result($sql_orden, 0, 1);
										if($direccion == ''){
											$direccion = '-';
										}
										$zona_f = '-';
									}
									if($tipoo == 2){
										$sql_orden = mysql_query("SELECT m.ap_pat_med, m.ap_mat_med, m.nom_med, d.direccion from medicos m, direcciones_medicos d where  (m.cod_med = d.cod_med)  and m.cod_med = $cod_med ");
										$nombre = mysql_result($sql_orden, 0, 0)." ".mysql_result($sql_orden, 0, 1)." ".mysql_result($sql_orden, 0, 2);
										$direccion = mysql_result($sql_orden, 0, 3);
										if($direccion == ''){
											$direccion = '-';
										}
										$zona_f = mysql_result(mysql_query("SELECT zona from zonas where cod_zona = $cod_zona"), 0, 0);
										if($zona_f == ''){
											$zona_f = '-';
										}
									}
									if($tipoo == 3){
										$sql_orden = mysql_query("SELECT nombre_cliente, dir_cliente, cod_zona from clientes2 where cod_cliente = $cod_med");
										$nombre = mysql_result($sql_orden, 0, 0);
										$direccion = mysql_result($sql_orden, 0, 1);
										$cod_zonaa = mysql_result($sql_orden, 0, 2);	
										if($direccion == ''){
											$direccion = '-';
										}
										$zona_f = mysql_result(mysql_query("SELECT zona from zonas where cod_zona = $cod_zonaa"), 0, 0);
										if($zona_f == ''){
											$zona_f = '-';
										}
									}
									$tabla_aux = $tabla_aux."<tr><td align='center'>$orden_visita</td><td>$indice</td><td>&nbsp;$nombre</td><td>&nbsp;$cod_espe</td><td align='center'>$cod_cat</td><td>&nbsp;$direccion </td><td align='center'>$zona_f</td></tr>";
									$indice++;
								}
								$tabla_aux = $tabla_aux."</table>";
								?>
								<tr>
									<?php
									if($aprobado!=1) { 
										?>
										<td align="left"><input type='checkbox' class="elimi" name='cod_contacto' value="<?php echo $cod_contacto ?>"</td>
										<?php  
									}else{
										?>
										<td align="left">&nbsp;</td>
										<?php
									}
									?>
									<td align="left"><?php echo $dia_contacto ?></td>
									<td align="center"><?php echo $turno ?></td>
									<td align="center"><?php echo $tabla_aux ?></td>
								</tr>
								<?php
							}
						}
						?>
					</table>
				</center>
			</div>
		</div>
		<div class="row">
			<div class="four columns centered">
				<?php  
				if($num_rutero == 0){
					?>
					<a href="javascript:void(0)" class="button">Replicar Rutero</a>
					<?php  
				}
				?>
				<a href="#adicionar_medicos" class="button adicionar">Adicionar</a>
				<!-- <a href="javascript:void(0)" class="button">Editar</a> -->
				<a href="javascript:void(0)" class="button eliminar">Eliminar</a>
				<!-- <a href="javascript:void(0)" class="button">Completar 1/2 Ciclo</a> -->
			</div>
		</div>
	</div>
</div>
</body>
</html>

