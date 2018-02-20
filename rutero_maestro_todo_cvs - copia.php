<?php  
require("conexion.inc");
$cod_rutero       = $_GET['rutero'];
$global_visitador = $_REQUEST['global_visitador'];

$nombreTabla  = "rutero_maestro_cab";
$nombreTabla1 = "rutero_maestro";
$nombreTabla2 = "rutero_maestro_detalle";
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
	<link type="text/css" href="js/fancybox/jquery.fancybox.css" rel="stylesheet" />
	<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.fancybox.js"></script>
	<script type="text/javascript" src="js/cambios/mine.js"></script>
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
			function deleteSlide() {
				if ($('#table-principal tr').size() == 1) {
					alert("La eliminacion no esta permitida! Al menos una referencia de farmacia debe estar presente.");
					return false;
				} else {
					if (confirm("Borrar esta Muestra?")) {
						$(this).parent().remove();
					}
					$('#table-principal tr').each(function(index) {
						// $("#table-principal tr td.position").eq(index).html(index+1);
						$("#table-principal tr td.position").eq(index).html((index+1) + '<input type="text" value="'+(index+1)+'" class="numero_visita">');
						$(this).attr("id",index+1);
						$(this).find(".fila_nom").attr('id', 'fila_nom_'+(index+1));
						$(this).find(".fila_espe").attr('id', 'fila_espe_'+(index+1));
						$(this).find(".fila_cate").attr('id', 'fila_cate_'+(index+1));
						$(this).find("select.name_farm").attr('name', 'name_farm_'+(index+1));
						$(this).find("select.name_farm").attr('id', 'name_farm_'+(index+1));
						// $(this).find("select.direccion").attr('name','direccion_'+(index+1));
						// $(this).find("select.direccion").attr('id','direccion_'+(index+1));
						// $(this).find("select.especialidad").attr('name','especialidad_'+(index+1));
						// $(this).find("select.especialidad").attr('id','especialidad_'+(index+1));
						// $(this).find("select.categoria").attr('name','categoria_'+(index+1));
						// $(this).find("select.categoria").attr('id','categoria_'+(index+1));
					});

					// event.stopPropagation;
					return false;
				}
			}
			$("#adicionar").fancybox({
				fitToView   : true,
				modal       : true
			});

			$("#guardar_cab").click(function(event) {
				var cadena = '';

				// cadena +=;
			});

			


			$('#table-principal tr:last td.deleteSlide').bind("mousedown", ( deleteSlide ));
			$('#add-row').bind("mousedown", (function(event){
				var highestID = 0;
				$('#table-principal tr').each(function() {
					var curID = parseInt($(this).attr('id'));
					if (highestID < curID){
						highestID = curID;
					}
				});
				$('#table-clone tr').clone().appendTo($('#table-principal'));
				$('#table-principal tr:last').attr("id",++highestID);
				$('#table-principal tr').each(function(index) {
					$("#table-principal tr td.position").eq(index).html((index+1) + '<input type="text" value="'+(index+1)+'" class="numero_visita">');
					// $("#table-principal tr td.position .numero_visita").val(index+1);
					$(this).attr("id",index+1);
					$(this).find(".fila_nom").attr('id', 'fila_nom_'+(index+1));
					$(this).find(".fila_espe").attr('id', 'fila_espe_'+(index+1));
					$(this).find(".fila_cate").attr('id', 'fila_cate_'+(index+1));
					$(this).find("select.name_farm").attr('name', 'name_farm_'+(index+1));
					$(this).find("select.name_farm").attr('id', 'name_farm_'+(index+1));
					// $(this).find("select.direccion").attr('name','direccion_'+(index+1));
					// $(this).find("select.direccion").attr('id','direccion_'+(index+1));
					// $(this).find("select.especialidad").attr('name','especialidad_'+(index+1));
					// $(this).find("select.especialidad").attr('id','especialidad_'+(index+1));
					// $(this).find("input.categoria").attr('name','categoria_'+(index+1));
					// $(this).find("input.categoria").attr('id','categoria_'+(index+1));
				});
				$('#table-principal tr:last td.deleteSlide').bind("mousedown", ( deleteSlide ));
			}));
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
		<div class="row">
			<div class="eight columns centered">
				<a href="javascript:void(0)" class="button">Replicar Rutero</a>
				<a href="#adicionar_medicos" class="button" id="adicionar">Adicionar</a>
				<a href="javascript:void(0)" class="button">Editar</a>
				<a href="javascript:void(0)" class="button">Eliminar</a>
				<a href="javascript:void(0)" class="button">Completar 1/2 Ciclo</a>
			</div>
		</div>
		<div class="row" style="margin: 15px 0">
			<div class="twelve columns">
				<?php  
				$sql_rutero = mysql_query("SELECT r.cod_contacto, r.cod_rutero, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje from $nombreTabla1 r, orden_dias o where r.cod_visitador = $global_visitador and r.cod_rutero = '$cod_rutero' and r.dia_contacto = o.dia_contacto order by o.id, r.turno");
				$num_rutero = mysql_num_rows($sql_rutero);
				?>
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
							?>
							<?php
						}
						?>
					</table>
				</center>
			</div>
		</div>
		<div class="row">
			<div class="eight columns centered">
				<a href="javascript:void(0)" class="button">Replicar Rutero</a>
				<a href="#adicionar_medicos" class="button" id="adicionar">Adicionar</a>
				<a href="javascript:void(0)" class="button">Editar</a>
				<a href="javascript:void(0)" class="button">Eliminar</a>
				<a href="javascript:void(0)" class="button">Completar 1/2 Ciclo</a>
			</div>
		</div>
		<div id="adicionar_medicos" style="width:100%;display: none;">
			<div class="row">
				<h3>Adicionar Rutero Maestro Consultora CVS</h3>
			</div>
			<div class="row">
				<center>
					<?php  
					$dias_contacto  = array("Lunes 1","Martes 1","Miercoles 1","Jueves 1","Viernes 1","Lunes 2","Martes 2","Miercoles 2","Jueves 2","Viernes 2","Lunes 3","Martes 3","Miercoles 3","Jueves 3","Viernes 3","Lunes 4","Martes 4","Miercoles 4","Jueves 4","Viernes 4");
					?>
					<table class="rutero">
						<tr>
							<th>D&iacute;a</th>
							<th>Turno</th>
							<th>&nbsp;</th>
						</tr>
						<tr>
							<td>
								<select name="dia" id="dia" style="height:25px; padding: 0">
									<?php  
									foreach ($dias_contacto as $dias) {
										?>
										<option value="<?php echo $dias ?>"><?php echo $dias ?></option>
										<?php	
									}
									?>
								</select>
							</td>
							<td>
								<select name="turno" id="turno" style="height:25px; padding: 0">
									<option value="am">Mañana</option>
									<option value="pm">Tarde</option>
								</select>
							</td>
							<td>
								<div id="add-row"></div>
							</td>
						</tr>
					</table>
				</center>
			</div>
			<div class="row">
				<center>
					<table width="99%" border="1" cellpadding="0" id="cab" style="margin: 0; padding: 0 !important">
						<tr>
							<th width="4%" scope="col">&nbsp;</th>
							<th width="4%" scope="col">&nbsp;</th>
							<th width="33%" scope="col">Nombre</th>
							<th width="26%" scope="col">Direcci&oacute;n</th>
							<th width="10%" scope="col">Especialidad</th>
							<th width="10%" scope="col">Categor&iacute;a</th>
							<th width="10%" scope="col">Tipo</th>
						</tr>
					</table>
					<table width="99%" border="1" cellpadding="0" id="table-principal" style="padding: 0 !important">
						<tr id="1" class="row-style">
							<td class="deleteSlide" style="text-align: center; font-weight: bold" width="4%"></td>
							<td class="position" style="text-align: center; font-weight: bold; font-size: 2em; line-height: 51px" width="4%">
								1 <input type="text" value="1" class="numero_visita">
							</td>
							<td class="cargar_categoria" width="33%">
								<select class="name_farm" name="name_farm_1" id="name_farm_1" style="width: 98%; border: none; background: #F8E8DB; margin: 5px 0; ">
									<option value="vacio">Selecionar una opcion</option>
									<?php 
									// $query_farmacias = "SELECT DISTINCT(m.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med from medicos m, categorias_lineas c, medico_asignado_visitador v where c.cod_med = m.cod_med  and c.cod_med = v.cod_med and m.cod_ciudad = $global_agencia and v.codigo_visitador = '$global_visitador' order by m.ap_pat_med"; 
									// $resp_query_farmacias = mysql_query($query_farmacias); 
									// while ($row = mysql_fetch_assoc($resp_query_farmacias)) { 
										?>
										<!-- <option value="<?php echo $row['cod_med']; ?>"><?php echo $row['ap_pat_med']." ".$row['ap_mat_med']." ".$row['nom_med'] ?></option> -->
										<?php 
									// } 
									?>
								</select>
							</td>
							<td width="26%" class="fila_nom" id="fila_nom_1">
								<p>Seleccione un m&eacute;dico / centro / cliente.</p>
							</td>
							<td width="10%" class="fila_espe" id="fila_espe_1">
								<p>Seleccione un m&eacute;dico / centro / cliente.</p>
							</td>
							<td width="10%" class="fila_cate" id="fila_cate_1">
								<p>Seleccione un m&eacute;dico / centro / cliente.</p>
							</td>
							<td width="10%" class="fila_tipo" id="fila_tipo_1">
								<input type="radio" name="tipoo" value="cm">Centro M&eacute;dico <br />
								<input type="radio" name="tipoo" value="c">Cliente <br />
								<input type="radio" name="tipoo" value="m">M&eacute;dico 
							</td>
						</tr>
					</table>
					<table width="99%" border="1" cellpadding="0" id="table-clone" style="display:none;">
						<tr id="999" class="row-style">
							<td class="deleteSlide" style="text-align: center; font-weight: bold" width="4%"></td>
							<td class="position"  style="text-align: center; font-weight: bold;font-size: 2em; line-height: 51px" width="4%">
								999 <input type="text" value="999" class="numero_visita">
							</td>
							<td class="cargar_categoria" width="33%">
								<select class="name_farm" name="name_farm_999" id="name_farm_999" style="width: 98%; border: none; background: #F8E8DB; margin: 5px 0; ">
									<option value="vacio">Selecionar una opcion</option>
									<?php 
									// $query_farmacias = "SELECT DISTINCT(m.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med from medicos m, categorias_lineas c, medico_asignado_visitador v where c.cod_med = m.cod_med and c.cod_med = v.cod_med and m.cod_ciudad = $global_agencia and v.codigo_visitador = '$global_visitador' order by m.ap_pat_med"; 
									// $resp_query_farmacias = mysql_query($query_farmacias); 
									// while ($row = mysql_fetch_assoc($resp_query_farmacias)) { 
										?>
										<!-- <option value="<?php echo $row['cod_med']; ?>"><?php echo $row['ap_pat_med']." ".$row['ap_mat_med']." ".$row['nom_med'] ?></option> -->
										<?php 
									// } 
									?>
								</select>
							</td>
							<td width="26%" class="fila_nom" id="fila_nom_1">
								<p>Seleccione un m&eacute;dico / centro / cliente.</p>
							</td>
							<td width="10%" class="fila_espe" id="fila_espe_1">
								<p>Seleccione un m&eacute;dico / centro / cliente.</p>
							</td>
							<td width="10%" class="fila_cate" id="fila_cate_1">
								<p>Seleccione un m&eacute;dico / centro / cliente.</p>
							</td>
							<td width="10%" class="fila_tipo" id="fila_tipo_1">
								<input type="radio" name="tipoo" value="cm">Centro M&eacute;dico <br />
								<input type="radio" name="tipoo" value="c">Cliente <br />
								<input type="radio" name="tipoo" value="m">M&eacute;dico 
							</td>
						</tr>
					</table>
				</center>
			</div>
			<div class="row centered">
				<div class="four columns centered">
					<a href="javascript:void(0)" class="button" id="guardar_cab">Guardar</a>
					<a href="javascript:$.fancybox.close();" class="button">Cerrar</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>