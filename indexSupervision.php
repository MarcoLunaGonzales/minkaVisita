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
		GALES - <span style="color:yellow;">(Modulo Supervision)</span>
		<div style="position:absolute; width:95%; height:50px; text-align:right; top:0px; font-size: 9px; font-weight: bold; color: #fff;">
			[<? echo $fechaSistemaSesion?>][<? echo $horaSistemaSesion;?>]			
		<div>
		
		<div style="position:absolute; width:99%; height:50px; text-align:right; top:17px; font-size: 9px; font-weight: bold; color: #fff;">
			<span style="color:#D1C4E9;">PowerOfMinka</span>
		<div>
		
		<div style="position:absolute; width:95%; height:50px; text-align:left; top:-15px; font-size: 9px; font-weight: bold; color: #fff;">
			[<? echo $nombreUsuarioSesion?>]  [<? echo $nombreAgenciaSesion;?>]  <span style="color:yellow;">[<? echo $nombreLineaSesion;?>]</span>
		<div>
		
	</div>
	
	
	<div class="content">
		<iframe src="inicio_mensajes.php" frameborder="0" name="contenedorPrincipal"></iframe>
	</div>
	
	
	<nav id="menu">
		<ul>
			
			<li><a href="ingreso_lineas_regional.php" target="contenedorPrincipal">Linea de Trabajo</a></li>

			<li><a href="navegador_funcionarios_regional.php" target="contenedorPrincipal">Visitadores</a></li>

			<li><a href="medicosSolicitadosAgencia.php" target="contenedorPrincipal">Revisar Alta de Medicos</a></li>

			<li><a href="cambiar_contrasena_regional.php" target="contenedorPrincipal">Cambiar Clave de Acceso</a></li>
			
			<li><a href="aprobar_bajas_medicos.php" target="contenedorPrincipal">Aprobacion de Baja de Medicos</a></li>

			<li><a href="recalculo_ge.php" target="contenedorPrincipal">Asignacion de Grupos Especiales</a></li>
			
			<li><span>Reportes</span>
				<ul>
					<li><span>Medicos</span>
						<ul>
							<li><a href="rpt_op_central_grupos_especiales.php" target="contenedorPrincipal">Medicos en Grupos Especiales</a></li>
							<li><a href="rpt_op_central_cant_med_territorio.php" target="contenedorPrincipal">Universo de medicos</a></li>
							<li><a href="rpt_op_medicos_rutero_maestroNacional.php" target="contenedorPrincipal">Medicos en Rutero</a></li>
							<li><a href="rpt_op_central_medicos_base_general.php" target="contenedorPrincipal">Medicos listado General</a></li>
							
						</ul>	
					</li>					
					<li><span>Parrillas</span>
						<ul>
							<li><a href="rpt_op_parrilla_central.php" target="contenedorPrincipal">Parrilla Promocional</a></li>
							<li><a href="rptOpParrillaPersonalizada.php" target="contenedorPrincipal">Parrilla Personalizada</a></li>
							<li><a href="rpt_op_parrilla_productoespecialidad.php" target="contenedorPrincipal">Productos por Especialidad</a></li>
						</ul>	
					</li>
					
					<li><span>Ruteros</span>
						<ul>
							<li><a href="rpt_op_ruterosNoAprobados.php" target="contenedorPrincipal">Ruteros en Elaboracion</a></li>
							<li><a href="rptOpRuterosContactos.php" target="contenedorPrincipal">Contactos Promedio/dia en Rutero</a></li>
						</ul>	
					</li>
					
					<li><span>Cobertura</span>
						<ul>
							<li><a href="rpt_op_central_coberturaCategoria1.php" target="contenedorPrincipal">Cobertura Por Visitador</a></li>
							<li><a href="rptOpCoberturaResumen.php" target="contenedorPrincipal">Cobertura Resumen</a></li>
							<li><a href="rpt_op_central_coberturaCategoriaA.php" target="contenedorPrincipal">Cobertura Medicos A</a></li>
						</ul>	
					</li>
					
					<li><span>Visitadores</span>
						<ul>
							<li><a href="rpt_op_central_detallemedicos.php" target="contenedorPrincipal">Rutero Maestro Detallado</a></li>								
							<li><a href="rpt_op_medicos_rutero_maestro2.php" target="contenedorPrincipal">Rutero Maestro Resumido x Visitador</a></li>								
							<li><a href="rpt_op_frecuenciaSecuencia.php" target="contenedorPrincipal">Frecuencia y Secuencia de Visita</a></li>								
							<li><a href="rpt_op_SecuenciaMedico.php" target="contenedorPrincipal">Secuencia de Visita por Medico</a></li>								
							<li><a href="rptOpCoberturaSemanaVisitador.php" target="contenedorPrincipal">Cobertura x Dia</a></li>								
						</ul>	
					</li>
					<li><span>Devolucion de material</span>
						<ul>
							<li><a href="rpt_op_devolucionMMMA.php" target="contenedorPrincipal">Devolucion de Material</a></li>								
						</ul>	
					</li>
				</ul>
			</li>
			
			
		</ul>
	</nav>
</div>
	</body>
</html>