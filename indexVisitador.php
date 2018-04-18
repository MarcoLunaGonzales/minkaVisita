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
		GALES - <span style="color:yellow;">(Modulo Visita)</span>
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
			
			<li><a href="ingreso_lineas_visitador_detalle.php" target="contenedorPrincipal">Linea de Trabajo</a></li>
			
			<li><span>Ruteros</span>
				<ul>
					<li><a href="navegador_rutero_maestro.php" target="contenedorPrincipal">Rutero Maestro</a></li>
					<li><a href="navegador_rutero_maestroAprobado.php" target="contenedorPrincipal">Rutero Maestro Aprobado x Ciclo</a></li>
					<li><a href="navegador_rutero_actual.php" target="contenedorPrincipal">Rutero Actual</a></li>
				</ul>
			</li>
			
			<li><a href="registrar_visita_medica.php" target="contenedorPrincipal">Registrar Visita</a></li>
			<li><a href="cambiar_contrasena_visitador.php" target="contenedorPrincipal">Cambiar Clave de Acceso</a></li>
			<li><a href="navegador_devolucion_visitadorCiclo.php" target="contenedorPrincipal">Devolucion por Ciclo</a></li>
			<li><a href="medicos_solicitados_lista.php" target="contenedorPrincipal">Alta de Medicos Nuevos</a></li>
			
			<li><span>Reportes</span>
				<ul>
					<li><span>Medicos</span>
						<ul>
							<li><a href="rpt_op_central_grupos_especiales.php" target="contenedorPrincipal">Medicos en Grupos Especiales</a></li>
						</ul>	
					</li>					
					<li><span>Parrillas</span>
						<ul>
							<li><a href="rpt_op_parrilla_central.php" target="contenedorPrincipal">Parrilla Promocional</a></li>
							<li><a href="rpt_op_parrilla_productoespecialidad.php" target="contenedorPrincipal">Productos por Especialidad</a></li>
						</ul>	
					</li>
					
					<li><span>Visitadores</span>
						<ul>
							<li><a href="rpt_op_central_coberturaCategoria1.php" target="contenedorPrincipal">Cobertura x Visitador</a></li>
							<li><a href="rptOpCoberturaSemanaVisitador.php" target="contenedorPrincipal">Cobertura x Dia</a></li>								
							<li><a href="rpt_op_medicos_rutero_maestro.php" target="contenedorPrincipal">Rutero Maestro Detallado</a></li>								
							<li><a href="rpt_op_medicos_rutero_maestro2.php" target="contenedorPrincipal">Rutero Maestro Resumido x Visitador</a></li>								
							
						</ul>	
					</li>
				</ul>
			</li>
			
			
		</ul>
	</nav>
</div>
	</body>
</html>