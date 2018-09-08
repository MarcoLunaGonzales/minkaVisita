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
		Minka Ventas - <span style="color:yellow;">(Administracion)</span>
		<div style="position:absolute; width:95%; height:50px; text-align:right; top:0px; font-size: 11px; font-weight: bold; color: #fff;">
			[<? echo $fechaSistemaSesion?>][<? echo $horaSistemaSesion;?>]			
		<div>
		<div style="position:absolute; width:99%; height:50px; text-align:right; top:17px; font-size: 9px; font-weight: bold; color: #fff;">
			<span style="color:yellow;">[CerrarSesion]</span>
			<span style="color:#D1C4E9;">PowerOfMinka</span>
		<div>
		<div style="position:absolute; width:95%; height:50px; text-align:left; top:-15px; font-size: 11px; font-weight: bold; color: #fff;">
			[<? echo $nombreUsuarioSesion?>]  <span style="color:yellow;">[<? echo $nombreAgenciaSesion;?>]</span>
		<div>
		
	</div>
	
	
	<div class="content">
		<iframe src="inicio_mensajes.php" frameborder="0" name="contenedorPrincipal"></iframe>
	</div>
	
	
	<nav id="menu">
		<ul>
			
			<li><a href="navegador_cargos.php" target="contenedorPrincipal">Cargos</a></li>
			<li><a href="navegador_funcionarios1.php" target="contenedorPrincipal">Funcionarios</a></li>
			<li><a href="navegadorSubirVentas.php" target="contenedorPrincipal">Cargar Datos de Ventas</a></li>			
		</ul>
	</nav>
</div>
	</body>
</html>