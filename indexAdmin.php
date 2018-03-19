<html>
<head>
	<meta charset="utf-8" />
	<title>Minka</title>

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
		GALES - <span style="color:yellow;">(Administracion)</span>
		<div style="position:absolute; width:95%; height:50px; text-align:right; top:0px; font-size: 11px; font-weight: bold; color: #fff;">
			[<? echo $fechaSistemaSesion?>][<? echo $horaSistemaSesion;?>]			
		<div>
		<div style="position:absolute; width:99%; height:50px; text-align:right; top:17px; font-size: 9px; font-weight: bold; color: #fff;">
			<span style="color:#D1C4E9;">PowerOfMinka</span>
		<div>
		<div style="position:absolute; width:95%; height:50px; text-align:left; top:-15px; font-size: 11px; font-weight: bold; color: #fff;">
			[<? echo $nombreUsuarioSesion?>]  [<? echo $nombreAgenciaSesion;?>]  <span style="color:yellow;">[<? echo $nombreLineaSesion;?>]</span>
		<div>
		
	</div>
	
	
	<div class="content">
		<iframe src="inicio_mensajes.php" frameborder="0" name="contenedorPrincipal"></iframe>
	</div>
	
	
	<nav id="menu">
		<ul>
			
			<li><a href="navegador_cargos.php" target="contenedorPrincipal">Cargos</a></li>
			<li><a href="navegador_categorias.php" target="contenedorPrincipal">Categorias</a></li>
			<li><a href="navegador_especialidades.php" target="contenedorPrincipal">Especialidades</a></li>
			<li><a href="navegador_funcionarios1.php" target="contenedorPrincipal">Funcionarios</a></li>
			<li><a href="navegador_gestiones.php" target="contenedorPrincipal">Gestiones</a></li>
			<li><a href="navegador_tiposbaja.php" target="contenedorPrincipal">Tipos de Baja</a></li>
			<li><a href="navegador_lineas.php" target="contenedorPrincipal">Lineas</a></li>
			<li><a href="navegador_territorios.php" target="contenedorPrincipal">Territorios</a></li>
			
			<li><span>Gestion de Inventarios</span>
				<ul>
					<li><a href="navegador_almacenes.php" target="contenedorPrincipal">Almacenes</a></li>
					<li><a href="navegador_material.php" target="contenedorPrincipal">Material de Apoyo</a></li>
					<li><a href="navegador_muestras_medicas.php" target="contenedorPrincipal">Muestras Medicas</a></li>
					<li><a href="navegador_tiposmaterial.php" target="contenedorPrincipal">Tipos de Material</a></li>
					<li><a href="navegador_tiposingreso.php" target="contenedorPrincipal">Tipos de Ingreso</a></li>
					<li><a href="navegador_tipossalida.php" target="contenedorPrincipal">Tipos de Salida</a></li>
				</ul>
			</li>		
			
		</ul>
	</nav>
</div>
	</body>
</html>