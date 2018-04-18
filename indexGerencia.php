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
		GALES - <span style="color:yellow;">  (Modulo Administracion)</span>
		<div style="position:absolute; width:95%; height:50px; text-align:right; top:0px; font-size: 11px; font-weight: bold; color: #fff;">
			[<? echo $fechaSistemaSesion?>][<? echo $horaSistemaSesion;?>]			
		<div>
		<div style="position:absolute; width:99%; height:50px; text-align:right; top:17px; font-size: 9px; font-weight: bold; color: #fff;">
			<span style="color:#D1C4E9;">PowerOfMinka</span>
		<div>
		<div style="position:absolute; width:95%; height:50px; text-align:left; top:-15px; font-size: 11px; font-weight: bold; color: #fff;">
			[<? echo $nombreUsuarioSesion?>]</span>
		<div>
	</div>
	
	
	<div class="content">
		<iframe src="inicio_mensajes.php" width="1000" height="800" frameborder="0" name="contenedorPrincipal"></iframe>
	</div>
	
	
	<nav id="menu">
		<ul>
			
			<li><span>Configuracion General</span>
				<ul>
					<li><a href="navegador_activar_ciclos.php" target="contenedorPrincipal">Activar Ciclo</a></li>
					<li><a href="grilla_lineas.php" target="contenedorPrincipal">Grillas</a></li>
					<li><a href="navegador_funcionarios1.php" target="contenedorPrincipal">Funcionarios</a></li>
					<li><a href="navegador_grupo_especial.php" target="contenedorPrincipal">Grupos Especiales</a></li>
					<li><a href="navegador_mensajes.php" target="contenedorPrincipal">Envio de Mensajes</a></li>	
					<li><a href="cambiar_contrasena_central.php" target="contenedorPrincipal">Cambiar Contraseña</a></li>	
				</ul>
			</li>

					
			<li><span>Gestión de Medicos</span>
				<ul>
					<li><a href="medicos_solicitados_lista_gerencia.php" target="contenedorPrincipal">Aprobar Alta de Medicos</a></li>
					<li><a href="navegador_medicos1.php" target="contenedorPrincipal">Editar Datos de Medico</a></li>
					<li><a href="medicosCiudadesGeneral.php" target="contenedorPrincipal">Asignar/Quitar Medicos de Lineas</a></li>
				</ul>
			</li>
			
			<li><span>Distribución de Material por Ciclo</span>
				<ul>
					<li><a href="navegador_distribucion_ciclos.php" target="contenedorPrincipal">Distribución General de MM y MA</a></li>
					<!--li><a href="navegador_distribucion_ciclos.php" target="contenedorPrincipal">Distribución General de MM y MA GE</a></li-->
				</ul>
			</li>
			
			<li><span>Ruteros</span>
				<ul>
					<li><a href="aprobar_rutero_conjunto.php" target="contenedorPrincipal">Aprobar Ruteros</a></li>
					<li><a href="rechazar_rutero_conjunto.php" target="contenedorPrincipal">Rechazar Ruteros</a></li>
				</ul>
			</li>
			
			<li><span>Baja de Medicos para Cobertura</span>
				<ul>
					<li><a href="aprobar_bajas_medicos_dr.php" target="contenedorPrincipal">Aprobar Bajas</a></li>
				</ul>
			</li>
			
			<li><span>Reportes</span>
				<ul>
					<li><a href="usuariosClavesVis.php" target="contenedorPrincipal">Listado de Usuarios</a></li>
					<li><span>Funcionarios</span>
						<ul>
							<li><a href="rpt_op_funcionariosHabilitados.php" target="contenedorPrincipal">Funcionarios Habilitados</a></li>
							<li><a href="rpt_op_funcionariosLineasVisita2.php" target="contenedorPrincipal">Funcionarios en Lineas</a></li>	
						</ul>	
					</li>
					<li><span>Medicos</span>
						<ul>
							<li><a href="rpt_op_central_medicos_base_general.php" target="contenedorPrincipal">Medicos Listado General</a></li>
							<li><a href="rpt_op_medicos_rutero_maestroNacional.php" target="contenedorPrincipal">Medicos en Rutero Maestro</a></li>								
						</ul>	
					</li>
					<li><span>Parrillas</span>
						<ul>
							<!--li><a href="rpt_op_parrilla_central.php" target="contenedorPrincipal">Parrilla General</a></li>
							<li><a href="rpt_op_parrilla_especial.php" target="contenedorPrincipal">Parrilla Especial</a></li-->
							<li><a href="rptOpParrillaPersonalizada.php" target="contenedorPrincipal">Parrilla x Medico</a></li>
						</ul>	
					</li>
					
					<li><span>Ruteros</span>
						<ul>
							<li><a href="rpt_op_ruterosNoAprobados.php" target="contenedorPrincipal">Estado de Ruteros en Elaboración</a></li>
							<li><a href="rptOpRuterosContactos.php" target="contenedorPrincipal">Contactos Promedio/dia en Rutero</a></li>								
						</ul>	
					</li>
					
					<li><span>Cobertura</span>
						<ul>
							<li><a href="rpt_op_central_coberturaCategoria1.php" target="contenedorPrincipal">Cobertura x Visitador</a></li>
							<li><a href="rptOpCoberturaResumen.php" target="contenedorPrincipal">Cobertura Semanal</a></li>								
						</ul>	
					</li>
					
					<li><span>Visitadores</span>
						<ul>
							<li><a href="rptOpFirmaBoletas.php" target="contenedorPrincipal">Boletas de Visita</a></li>
							<li><a href="rpt_op_medicos_rutero_maestro2.php" target="contenedorPrincipal">Medicos en Rutero Maestro Resumido x Visitador</a></li>	
							<li><a href="rpt_op_medicos_rutero_maestro.php" target="contenedorPrincipal">Medicos en Rutero Maestro Detallado</a></li>	
							<li><a href="rpt_op_frecuenciaSecuencia.php" target="contenedorPrincipal">Frecuencia y Secuencia de Visita</a></li>								
							<li><a href="rpt_op_devolucionMMMA.php" target="contenedorPrincipal">Devolucion de Muestras Medicas</a></li>							
							<li><a href="rpt_op_SecuenciaMedico.php" target="contenedorPrincipal">Secuencia de Visita por Medico</a>														
						</ul>	
					</li>
					
					<li><span>Distribución de MM</span>
						<ul>
							<li><a href="rptOpVerificacionDistNormal.php" target="contenedorPrincipal">Verificacion para la Distribucion</a></li>
							<li><a href="rpt_op_central_distribucionTerritorio2.php" target="contenedorPrincipal">Distribución x Ciclo x Territorio</a></li>	
							<li><a href="rpt_op_central_distribucion2.php" target="contenedorPrincipal">Distribución x Ciclo x Visitador</a></li>	
						</ul>	
					</li>

				</ul>
			</li>
			
			
		</ul>
	</nav>
</div>
	</body>
</html>