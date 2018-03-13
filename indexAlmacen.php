<html>
<head>
	<meta charset="utf-8" />
	<title>Modulo Inventarios</title>

	<link type="text/css" rel="stylesheet" href="menuLibs/css/demo.css" />
	<link type="text/css" rel="stylesheet" href="menuLibs/dist/jquery.mmenu.css" />

	<script type="text/javascript" src="http://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="menuLibs/dist/jquery.mmenu.js"></script>
	<script type="text/javascript">
		$(function() {
			$('nav#menu').mmenu();
		});
	</script>
		
</head>
<body>
<?
include("datosUsuario.php");
?>
<div id="page">
	<div class="header">
		<a href="#menu"><span></span></a>
		MINKA V++ <span style="color:yellow;">(Modulo Inventarios)</span>
		<div style="position:absolute; width:95%; height:50px; text-align:right; top:0px; font-size: 9px; font-weight: bold; color: #fff;">
			[<? echo $fechaSistemaSesion?>][<? echo $horaSistemaSesion;?>]			
		<div>
		<div style="position:absolute; width:95%; height:50px; text-align:left; top:0px; font-size: 9px; font-weight: bold; color: #fff;">
			[<? echo $nombreUsuarioSesion?>] <span style="color:yellow;">[<? echo $nombreAgenciaSesion;?>]</span>
		<div>
		
	</div>
	
	
	<div class="content">
		<iframe src="inicio_mensajes.php" frameborder="0" name="contenedorPrincipal"></iframe>
	</div>
	
	
	<nav id="menu">
		<ul>
			<li><span>Ingresos</span>
				<ul>
					<li><a href="navegador_ingresomuestras.php?grupoIngreso=1" target="contenedorPrincipal">Muestras -> Listado de Ingresos</a></li>
					<li><a href="navegador_ingresomuestrastransito.php?grupoIngreso=1" target="contenedorPrincipal">Muestras -> Traspasos pendientes de Ingreso</a></li>
					
					<li><a href="navegador_ingresomuestras.php?grupoIngreso=2" target="contenedorPrincipal">Materiales -> Listado Ingresos</a></li>
					<li><a href="navegador_ingresomuestrastransito.php?grupoIngreso=2" target="contenedorPrincipal">Materiales -> Traspasos pendientes de Ingreso</a></li>
				</ul>
			</li>
			
			<li><span>Salidas</span>
				<ul>
					<li><a href="navegador_salidamuestras.php?grupoSalida=1" target="contenedorPrincipal">Muestras -> Listado Salidas</a></li>
					
					<li><a href="navegador_salidamateriales.php?grupoSalida=2" target="contenedorPrincipal">Materiales -> Listado Salidas</a></li>
				</ul>
			</li>
			
			<li><span>Reportes</span>
				<ul>
					<li><a href="rpt_op_inv_kardex.php" target="contenedorPrincipal">Kardex de Movimiento</a></li>
					<li><a href="rpt_op_inv_existencias.php" target="contenedorPrincipal">Existencias</a></li>
					<li><a href="rpt_op_inv_ingresos.php" target="contenedorPrincipal">Ingresos General</a></li>
					<li><a href="rpt_op_inv_salidas.php" target="contenedorPrincipal">Salidas General</a></li>	
					<li><a href="rpt_op_inv_salidasRegional.php" target="contenedorPrincipal">Salidas x Territorio</a></li>					
				</ul>
			</li>			
		</ul>
	</nav>
</div>
	</body>
</html>