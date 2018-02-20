<?php  
error_reporting(0);
require("conexion.inc");

$cod_med = $_GET['cod_med'];
$muestra = $_GET['muestra'];

$sql_nom_med = mysql_query("SELECT CONCAT(m.ap_pat_med,' ',m.ap_mat_med, ' ', m.nom_med) as nombre, m.cod_ciudad from medicos m where m.cod_med = $cod_med ");
$nom_med     = mysql_result($sql_nom_med, 0, 0); 
$ciu_med     = mysql_result($sql_nom_med, 0, 1);

$sql_espe_med = mysql_query("SELECT e.desc_especialidad, cl.categoria_med, e.cod_especialidad from especialidades e, categorias_lineas cl where cl.cod_especialidad = e.cod_especialidad and cl.cod_med = $cod_med and cl.codigo_linea = 1021");
$especialidad = mysql_result($sql_espe_med, 0, 0);
$categoria    = mysql_result($sql_espe_med, 0, 1);
$cod_espec    = mysql_result($sql_espe_med, 0, 2);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link type="text/css" href="css/style.css" rel="stylesheet" />
	<link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
	<link rel="stylesheet" href="responsive/stylesheets/style.css">
	<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
	<script>
		jQuery(document).ready(function($) {
			$(".va_ultima_pos").live('click', function(event) {
				if($(this).is(':checked') == true){
					$(this).parent().find('.enter_pos').hide('slow')
					$(this).val("1");
				}else{
					$(this).parent().find('.enter_pos').show('slow')
					$(this).val("0");
				}
			});
			var cadena_frecuencia = '';
			$(".frecuencia").live('change', function(event) {
				$(this).parent().find('.frecuencia:checked').each(function() {
					cadena_frecuencia += $(this).val()+"#" ;
				});
				$(this).parent().parent().find('.cadena_frecuencia').val(cadena_frecuencia);
			});
			$("#adicionar").click(function(event) {

				var cod_med, codigo_muestra, frecuencia, posicion, cantidad_prod, va_ultima_pos;

				cod_med        = $(".cod_med").val();
				codigo_muestra = $(".codigo_muestra").val();
				frecuencia     = $(".cadena_frecuencia").val();
				posicionn      = $(".posicionn").val();
				va_ultima_pos  = $(".va_ultima_pos").val();
				cantidad_prod  = $(".cantidad_prod").val();

				$.ajax({
					type: "POST",
					url: "ajax/sugerencias/aprobar_dr_agregados.php",
					dataType : 'json',
					data: { 
						cod_med        : cod_med,
						codigo_muestra : codigo_muestra,
						frecuencia     : frecuencia,
						posicionn      : posicionn,
						va_ultima_pos  : va_ultima_pos,
						cantidad_prod  : cantidad_prod

					}
				}).done(function(data) {
					alert(data)
					window.location.href = "solicitud_agregar_productos_dr.php";
				});

			});
		});
</script>
</head>
<body>
	<div id="container">
		<header id="titulo" style="min-height: 50px">
			<h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Llenar Datos para Agregar Producto al M&eacute;dico</h3>
			<h3 style="color: #5F7BA9; font-size: 1.2em; font-family: Vernada">M&eacute;dico: <?php echo $nom_med; ?></h3>
		</header>
		<div class="row centered">
			<div class="twelve columns centered">
				<center>
					<table>
						<tr>
							<th>Producto</th>
							<th>Contacto</th>
							<th>Posicion</th>
							<th>Cantidad</th>
						</tr>
						<tr>
							<td>
								<input type="hidden" value="<?php echo $cod_med ?>" class="cod_med">
								<input type="hidden" value="<?php echo $muestra ?>" class="codigo_muestra">
								<?php  
								$sql_muestra = mysql_query("SELECT CONCAT(descripcion,' ', presentacion) from muestras_medicas where codigo = '$muestra'");
								$nom_muestra = mysql_result($sql_muestra, 0, 0);
								echo $nom_muestra;
								?>
							</td>
							<td>
								<?php 
								$sql_frecuencia = mysql_query("SELECT max(gd.frecuencia) from grilla g, grilla_detalle gd where g.codigo_grilla = gd.codigo_grilla and g.estado = 1 and g.codigo_linea = 1021 and gd.cod_especialidad = '$cod_espec' and gd.cod_categoria = '$categoria' and g.agencia = $ciu_med and gd.frecuencia < 8");
								$max_frecuencia = mysql_result($sql_frecuencia, 0, 0);
								for ($i=1; $i <= $max_frecuencia; $i++) { 
									?>
									<div style="margin-right: 15px; float: left">
										<input type="checkbox" name="frecuencia_1" class="frecuencia para_guardar" value="<?php echo $i; ?>"> <?php echo $i ?>
									</div>
									<?php  
								}
								?>
								<input type="hidden" class="cadena_frecuencia" value="" />
							</td>
							<td>
								<div class="enter_pos" style="margin-bottom:10px;display:none">
									<input type="number" placeholder="Posicion" style="width:70%" onkeyup="var no_digito = /\D/g;this.value = this.value.replace(no_digito , '');" class="para_guardar posicionn" value="0">
								</div>
								<input type="checkbox" value="1" checked class="va_ultima_pos para_guardar"> &Uacute;ltima posici&oacute;n  
							</td>
							<td>
								<input type="number" class="cantidad_prod para_guardar" placeholder="Cantidad" onkeyup="var no_digito = /\D/g;this.value = this.value.replace(no_digito , '');" value="0" style="max-width:115px">
							</td>
						</tr>
					</table>
				</center>
			</div>
		</div>
		<div class="row centered">
			<div class="three columns centered">
				<a href="javascript:void(0)" class="button" id="adicionar">Guardar</a>
			</div>
		</div>
	</div>
</body>
</html>