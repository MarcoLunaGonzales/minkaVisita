<html>
<head>
	<meta charset="utf-8" />
	<title>Minka</title>

	<link type="text/css" rel="stylesheet" href="menuLibs/css/demo.css" />
	<link type="text/css" rel="stylesheet" href="menuLibs/dist/jquery.mmenu.css" />
	
	<link rel="icon" type="image/png" href="imagenes/aesculapio2.png" />

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
		MinkaVentas - <span style="color:orange;">(Modulo Supervision)</span>
		<div style="position:absolute; width:95%; height:50px; text-align:right; top:0px; font-size: 11px; font-weight: bold; color: #fff;">
			[<? echo $fechaSistemaSesion?>][<? echo $horaSistemaSesion;?>]			
		<div>
		
		<div style="position:absolute; width:99%; height:50px; text-align:right; top:17px; font-size: 9px; font-weight: bold; color: #fff;">
			<span style="color:#D1C4E9;">PowerOfMinka</span>
		<div>
		
		<div style="position:absolute; width:95%; height:50px; text-align:left; top:-15px; font-size: 11px; font-weight: bold; color: #fff;">
			[<? echo $nombreUsuarioSesion?>]  <span style="color:yellow;">[<? echo $nombreAgenciaSesion;?>] [Act. <?php echo $fechaAct;?>]</span>
		<div>
		
	</div>
	
	
	<div class="content">
		<iframe src="inicio_mensajes.php" frameborder="0" name="contenedorPrincipal"></iframe>
	</div>
	
	
	<nav id="menu">
		<ul>
			<!--li><a href="dashboardPromotor.php" target="contenedorPrincipal">Dashboard</a></li-->
			<li><a href="navegadorAcuerdosComSuper.php" target="contenedorPrincipal">Acuerdos Comerciales</a></li>
			<li><a href="reporteGraficoPresRegional.php" target="contenedorPrincipal">Seguimiento Presupuesto x Linea</a></li>
			<!--li><a href="reporteGraficoPresFuncionario.php" target="contenedorPrincipal">Seguimiento Presupuesto x Funcionario</a></li-->
			<li><a href="rptOpParetoClientes.php" target="contenedorPrincipal">Pareto Clientes</a></li>
			<li><a href="rptOpParetoProductos.php" target="contenedorPrincipal">Pareto Productos</a></li>
			<li><a href="rptOpClientesProducto.php" target="contenedorPrincipal">Ventas x Cliente Individual</a></li>
			<li><a href="rptOpProductoIndividual.php" target="contenedorPrincipal">Ventas x Producto Individual</a></li>
			<li><a href="cambiarMesPres.php" target="contenedorPrincipal">Cambiar Mes Vista Presupuestos</a></li>
			
		</ul>
	</nav>
</div>
	</body>
</html>