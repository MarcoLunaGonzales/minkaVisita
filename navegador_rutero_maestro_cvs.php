<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Rutero Consultoras CVS</title>
	<link type="text/css" href="css/style.css" rel="stylesheet" />
	<link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
	<link rel="stylesheet" href="responsive/stylesheets/style.css">
	<link type="text/css" href="js/fancybox/jquery.fancybox.css" rel="stylesheet" />
	<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.fancybox.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$("#adicionar").fancybox({
				fitToView   : true,
				modal       : true
			});
			$("#guardar_cab").click(function(){
				var nombre_rutero, ciclo_gestion;
				nombre_rutero = $('#nombre_rutero').val();
				ciclo_gestion = $('#ciclo_gestion option:selected').val()
				$.ajax({
					type: "POST",
					url: "ajax/rutero_cvs/adicionar.php",
					dataType : 'json',
					data: { 
						nombre_rutero : nombre_rutero,
						ciclo_gestion : ciclo_gestion,
						global_visitador : '<?php echo $global_visitador; ?>'
					}
				}).done(function(data) {
					alert("Guardado");
					$.fancybox.close();
					location.reload();
				});
			});
			$('#eliminar').click(function(){
				if( $(".checkbox:checked").length == 0){
					alert("Debe seleccionar un rutero para eliminar")
				}else{
					var cadena_eliminar = '';
					$('.checkbox:checked').each(function(){
						cadena_eliminar += $(this).val()+",";
					})
					$.ajax({
						type: "POST",
						url: "ajax/rutero_cvs/eliminar.php",
						dataType : 'json',
						data: { 
							cadena_eliminar : cadena_eliminar,
							global_visitador : '<?php echo $global_visitador; ?>'
						}
					}).done(function(data) {
						alert("Eliminardo");
						location.reload();
					});
				}
			});
			$('#aprobacion').click(function(){
				if( $(".checkbox:checked").length == 0){
					alert("Debe seleccionar un rutero para aprobar")
				}else{
					if( $(".checkbox:checked").length == 1){
						var cadena_eliminar = '';
						$('.checkbox:checked').each(function(){
							cadena_eliminar = $(this).val();
						})
						$.ajax({
							type: "POST",
							url: "ajax/rutero_cvs/aprobar.php",
							dataType : 'json',
							data: { 
								cadena_eliminar : cadena_eliminar,
								global_visitador : '<?php echo $global_visitador; ?>'
							}
						}).done(function(data) {
							alert("El rutero se aprobo satisfactoriamente.");
							location.reload();
						});
					}else{
						alert("Solo pouede aprobar 1 rutero a la vez");
					}
				}
			});
		});
</script>
<style type="text/css">
	table tr th {
		padding: 5px 10px    
	} 
	table tbody tr td {
		padding: 5px 10px !important   
	}
	.modal {
		display:    none;
		position:   fixed;
		z-index:    1000;
		top:        0;
		left:       0;
		height:     100%;
		width:      100%;
		background: rgba( 0, 0, 0,.8 ) 
		url('http://i.stack.imgur.com/FhHRx.gif') 
		50% 50% 
		no-repeat;
	}
	body.loading {
		overflow: hidden;   
	}
	body.loading .modal {
		display: block;
	}
</style>
</head>
<body>
	<div id="container">
		<?php 
		require("estilos3.inc"); 
		?>		
		<header id="titulo" style="min-height: 50px">
			<h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Registro de Rutero CVS</h3>
		</header>
		<div class="row">
			<div class="twelve columns">
				<center>
					<?php
					$sql_rutero = mysql_query("SELECT r.cod_rutero, r.nombre_rutero, r.estado_aprobado, r.codigo_ciclo, r.codigo_gestion from rutero_maestro_cab r where r.cod_visitador='$global_visitador' and r.cvs = 1 order by r.nombre_rutero"); 
					$num_rutero = mysql_num_rows($sql_rutero);
					?>
					<table>
						<tr>
							<th>&nbsp;</th>
							<th>Nombre de Rutero</th>
							<th>Ciclo Asociado</th>
							<th>Estado</th>
							<th>&nbsp;</th>
						</tr>
						<?php  
						if($num_rutero == 0){
							?>
							<tr>
								<td colspan="6" style="text-align: center; font-size: 1.0em; font-weight: bold">No hay ruteros guardados para el visitador.</td>
							</tr>
							<?php
						}else{
							while ($row_rutero = mysql_fetch_array($sql_rutero)) {
								$cod_rutero    = $row_rutero[0];
								$nombre_rutero = $row_rutero[1];
								$estado        = $row_rutero[2];
								$codCiclo      = $row_rutero[3];
								$codigoGestion = $row_rutero[4];

								$sql_nom_gestion = mysql_query("SELECT nombre_gestion from gestiones where codigo_gestion = $codigoGestion");
								$nombreGestion   = mysql_result($sql_nom_gestion, 0, 0);
								?>
								<tr>
									<td>
										<?php
										if($estado == 0){
											$estado_fin = "No Aprobado";
											?>
											<input type='checkbox' name='codigo' value="<?php echo $cod_rutero ?>" class="checkbox">
											<?php
										}
										if($estado == 1){
											$estado_fin = "Aprobado";
											echo "&nbsp;";	
										}
										if($estado == 2){
											$estado_fin = "En Aprobacion";
											echo "&nbsp;";	
										}  
										?>
									</td>
									<td><?php echo $nombre_rutero; ?></td>
									<td><?php echo $codCiclo." / ".$nombreGestion ?></td>
									<td><?php echo $estado_fin; ?></td>
									<td><a href='rutero_maestro_todo_cvs.php?rutero=<?php echo $cod_rutero ?>'>Ver Todo >></a></td>
								</tr>
								<?php
							}
						}
						?>
					</table>
				</center>
			</div>
			<div class="twelve columns">
				<center>
					<a href="#guardar_cabecera"  class="button" id="adicionar">Adicionar</a>
					<a href="javascript:void(0)" class="button" id="eliminar">Eliminar</a>
					<a href="javascript:void(0)" class="button" id="aprobacion">Enviar a Aprobaci&oacute;n</a>
				</center>		
			</div>
			<div id="guardar_cabecera" style="width:100%;display: none;">
				<h3>Adicionar Rutero Maestro Consultora CVS</h3>
				<div class="row">
					<div class="eight columns">
						<input type="text" placeholder="Nombre Rutero Maestro" id="nombre_rutero">
					</div>
					<div class="four columns">
						<?php  
						$sql_ciclo_gestion = mysql_query("SELECT DISTINCT c.cod_ciclo, g.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g where c.codigo_gestion = g.codigo_gestion and g.estado = 'Activo' ORDER BY 1 DESC");
						?>
						<select name="ciclo_gestion" id="ciclo_gestion">
							<?php  
							while ($row_ciclo_gestion = mysql_fetch_array($sql_ciclo_gestion)) {
								?>
								<option value="<?php echo $row_ciclo_gestion[0]."|".$row_ciclo_gestion[1] ?>"><?php echo $row_ciclo_gestion[0]." ".$row_ciclo_gestion[2] ?></option>
								<?php
							}
							?>
						</select>
					</div>
				</div>
				<div class="row centered">
					<div class="four columns centered">
						<a href="javascript:void(0)" class="button" id="guardar_cab">Guardar</a>
						<a href="javascript:$.fancybox.close();" class="button">Cerrar</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>