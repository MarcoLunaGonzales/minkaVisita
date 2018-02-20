<?php  
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
?>	
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="iso-8859-1">
	<title>Asignacion de Material De Apoyo</title>
	<link type="text/css" href="../../css/style.css" rel="stylesheet" />
	<link type="text/css" href="../../responsive/stylesheets/foundation.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../responsive/stylesheets/style.css">
	<script type="text/javascript" src="../../lib/jquery-1.7.1.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {

			$("#continuar").click(function(){
				var ciclo_gestion = $("#ciclo").val();
				var windowSizeArray = [ "scrollbars=yes" ];
				var url = "replicar.php?ciclo_gestion="+ciclo_gestion;
				var windowName = "replicar";
				var windowSize = windowSizeArray[0];
				window.open(url, windowName, windowSize);
			})

		});
</script>
<style type="text/css">
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
	form, select {
		margin: 10px 0;
	}
	th {
		padding: 0 10px 
	}
	#overlay { 
		display:none; 
		position:absolute; 
		background:#fff; 
	}
	#img-load { 
		position:absolute; 
	}
</style>
</head>
<body>
	<div id="container">
		<?php require("../../estilos3.inc"); ?>
		<header id="titulo" style="min-height: 50px">
		<h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Replicar lineas funcionarios</h3>
		</header>
		<div id="contenido">
			<div class="row">
				<div class="twelve columns">
					<center>
						<table border="1" style="width:70%;position:relative">
							<tr  id="c">
								<th>Ciclo - Gestion Destino</th>
								<td>
									<?php $sql_ciclo = mysql_query("SELECT distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g where c.codigo_gestion = g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 11"); ?>
									<select name="ciclo" id="ciclo" style="width:83%">
										<option value="0">Seleccione el ciclo-gestion</option>
										<?php while($row_ciclo = mysql_fetch_array($sql_ciclo)){ ?>
										<option value="<?php echo $row_ciclo[0]."-".$row_ciclo[1] ?>"><?php echo $row_ciclo[0]." ".$row_ciclo[2] ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
						</table> 
						<a href="javascript:void(0)" class="button" id="continuar">Replicar</a>
					</center>
				</div>
			</div>
		</div>
	</div>
	<div class="modal"></div>
</body>
</html>