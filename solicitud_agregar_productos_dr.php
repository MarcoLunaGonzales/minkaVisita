<?php  
header ( "Content-Type: text/html; charset=UTF-8" );
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Solicitud para Agregar productos</title>
	<link type="text/css" href="css/style.css" rel="stylesheet" />
	<link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
	<link rel="stylesheet" href="responsive/stylesheets/style.css">
	<link type="text/css" href="js/fancybox/jquery.fancybox.css" rel="stylesheet" />
	<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.fancybox.js"></script>
	<script>
		jQuery(document).ready(function($) {
			$("body").on({
				ajaxStart: function() { 
					$(this).addClass("loading"); 
				},
				ajaxStop: function() { 
					$(this).removeClass("loading"); 
				}    
			});
			$(".fancy").fancybox({
				fitToView   : true
			});
			$(".rechazar").click(function(){
				var checked = "";
				checked = $(this).attr('data');
				$.getJSON("ajax/sugerencias/rechazar_dr_agregados.php",{
					"contactos": checked
				},response2);

				return false;
			})
			function response2(datos){
				alert(datos)
				window.location = "solicitud_agregar_productos_dr.php";
			}
		});
	</script>
	<style>
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
		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
		?>		
		<header id="titulo" style="min-height: 50px">
			<h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Productos Agregados Sugeridos Aprobados por el Supervisor</h3>
		</header>
		<div class="row centered">
			<!-- <div class="three columns centered">
				<center><input type="checkbox" onclick="toggleCheckedA(this.checked)"> Seleccionar todos</center>
			</div> -->
			<div class="twelve columns">
				<?php  
				$count = 1;
				$sql_visitador = mysql_query("SELECT DISTINCT m.cod_med, CONCAT(m.ap_pat_med,' ',m.ap_mat_med,' ',m.nom_med), mm.codigo, CONCAT(mm.descripcion,' ',mm.presentacion), f.codigo_funcionario,CONCAT(f.paterno,' ',f.materno,' ',f.nombres) from medicos m, muestras_medicas mm, funcionarios f, muestras_agregadas_sugeridas mq where mq.cod_visitador = f.codigo_funcionario and mq.muestra_mm = mm.codigo and mq.cod_med = m.cod_med and f.cod_ciudad = $global_agencia and mq.estado = 3");
				echo("SELECT DISTINCT m.cod_med, CONCAT(m.ap_pat_med,' ',m.ap_mat_med,' ',m.nom_med), mm.codigo, CONCAT(mm.descripcion,' ',mm.presentacion), f.codigo_funcionario,CONCAT(f.paterno,' ',f.materno,' ',f.nombres) from medicos m, muestras_medicas mm, funcionarios f, muestras_agregadas_sugeridas mq where mq.cod_visitador = f.codigo_funcionario and mq.muestra_mm = mm.codigo and mq.cod_med = m.cod_med and f.cod_ciudad = $global_agencia and mq.estado = 3");
				?>
				<center>
					<table>
						<thead>
							<tr>
								<th>&nbsp;</th>
								<th>M&eacute;dico</th>
								<th>Muestra</th>
								<th>Visitador</th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<?php  
							while ($row_visitador = mysql_fetch_array($sql_visitador)) {
								?>
								<tr>
									<td><?php echo $count ?></td>
									<td>
										<input type="hidden" name="cod_med" class="cod_med" value="<?php echo $row_visitador[0] ?>">
										<?php echo $row_visitador[1]; ?>
									</td>
									<td>
										<input type="hidden" name="cod_med" class="cod_med" value="<?php echo $row_visitador[2] ?>">
										<?php echo $row_visitador[3]; ?>
									</td>
									<td>
										<input type="hidden" name="cod_med" class="cod_med" value="<?php echo $row_visitador[4] ?>">
										<?php echo $row_visitador[5]; ?>
									</td>
									<td>
										<a href="llenar_form.php?cod_med=<?php echo $row_visitador[0] ?>&muestra=<?php echo $row_visitador[2] ?>" class="fancy fancybox.ajax">Llenar Datos</a>
									</td>
									<td>
										<a href="javascript:void(0)" class="rechazar" data="<?php echo $row_visitador[0].'-'.$row_visitador[2].'-'.$row_visitador[4] ?>">Rechazar</a>
									</td>
								</tr>
								<?php
								$count++;
							}
							?>
						</tbody>
					</table>
				</center>
			</div>
		</div>
	</div>
	<div class="modal"></div>
</body>
</html>