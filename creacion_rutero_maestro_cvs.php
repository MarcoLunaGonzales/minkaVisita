<?php  
require("conexion.inc");
// require("conexion2.inc");
header ( "Content-Type: text/html; charset=UTF-8" );
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$cod_rutero       = $_GET['rutero'];
$global_visitador = $_REQUEST['global_visitador'];

if($vista==2){
	$nombreTabla="rutero_maestro_cab_aprobado";
	$nombreTabla1="rutero_maestro_aprobado";
	$nombreTabla2="rutero_maestro_detalle_aprobado";
}else{
	$nombreTabla="rutero_maestro_cab";
	$nombreTabla1="rutero_maestro";
	$nombreTabla2="rutero_maestro_detalle";
}
$sql_nom_rutero = mysql_query("SELECT nombre_rutero from $nombreTabla where cod_rutero = '$cod_rutero' and cod_visitador = '$global_visitador'");
$nom_rutero     = mysql_result($sql_nom_rutero, 0, 0);
$dias_contacto  = array("Lunes 1","Martes 1","Miercoles 1","Jueves 1","Viernes 1","Lunes 2","Martes 2","Miercoles 2","Jueves 2","Viernes 2","Lunes 3","Martes 3","Miercoles 3","Jueves 3","Viernes 3","Lunes 4","Martes 4","Miercoles 4","Jueves 4","Viernes 4");
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Rutero Maestro Detallado</title>
	<link type="text/css" href="css/style.css" rel="stylesheet" />
	<link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
	<link rel="stylesheet" href="responsive/stylesheets/style.css">
	<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
	<script>
		jQuery(document).ready(function($) {

		});
	</script>
	<style type="text/css">
		.rutero tr th {
			padding: 5px 10px    
		} 
		.rutero tbody tr td {
			padding: 5px 10px !important   
		}
		table tr th {
			padding: 2px 10px    
		} 
		table tbody tr td {
			padding: 2px 10px !important   
		}
		#add-row {
			background: transparent url('imagenes/add-slide.png') no-repeat scroll 50% 50%;
			cursor: pointer;
			width: 102px;
			height: 32px;
			float: left;
			margin: 0 !important;
		}
		#add-row:hover { 
			background: transparent url('imagenes/add-slide-hover.png') no-repeat scroll 50% 50%;
			cursor: pointer;
			width: 102px;
			height: 32px;
		}
		td.deleteSlide	{
			background: transparent url('imagenes/delete-slide.png') no-repeat scroll 50% 50%;
			cursor:pointer;
		}
		.small {
			height: 25px !important;
			padding: 0 !important;
			margin-top: 0 !important;
		}
		#cab th {
			padding: 0 !important
		}
		#table-clone td, #table-principal td {
			padding: 0 !important
		}
		.numero_visita {
			display: none !important;
		}
	</style>
	<script>
		function envia_select(menu,form){
			form.submit();
			return(true);
		}
		function relacion_fechas_s(form) {	
			form.input_FechaConsulta.value='';
			form.submit();
			return(true);
		}
		function relacion_fechas_t(form) {	
			form.diasciclo.value='';
			form.submit();
			return(true);
		}
		function envia(f){
			var i;
			var el='h_orden';
			var valor;
			var suma=0;
			var suma_real=0;
			var numero;
			variables=new Array(f.length-1);
			for(i=0;i<=f.length-2;i++) {
				if(f.elements[i].type == 'radio'){
					if(f.elements[i].checked == true){
						variables[i]=f.elements[i].value;
					}else{

					}
				}else{
					variables[i]=f.elements[i].value;
				}
				if(f.elements[i].value=='') {
					alert('Algun elemento no tiene valor');
					return(false);
				}

			}
			for(var j=1;j<=f.num_medicos.value;j++) {
				valor=el+j;
				suma_real=suma_real+j;
				for(i=0;i<=f.length-2;i++) {	
					if(f.elements[i].name==valor) {	
						numero=(f.elements[i].value)*1;
						suma=suma+numero;
					}
				}
			}
			if(suma!=suma_real) {	
				alert('El orden de visita debe ser correlativo y no debe repetirse');
				return(false);
			}
			var comp='h_cod_med';
			vector_medicos=new Array(30);
			var indice;
			indice=0;
			for(j=0;j<=f.length-1;j++) {
				if(f.elements[j].name.indexOf(comp)!=-1) {	
					vector_medicos[indice]=f.elements[j].value;
					indice++;	
				}
			}
			var buscado,cant_buscado;
			for(k=0;k<=indice;k++) {	
				buscado=vector_medicos[k];
				cant_buscado=0;
				for(m=0;m<=indice;m++) {	
					if(buscado==vector_medicos[m]) {	
						cant_buscado=cant_buscado+1;
					}
				}
				if(cant_buscado>1) {	
					alert('Los Medicos no pueden repetirse en un mismo Dia de contacto.');
					return(false);
				}
			}
			location.href='guardar_rutero_maestro_cvs.php?variables='+variables+'&rutero=<?php echo $cod_rutero ?>';
			return(true);
		}
	</script>
</head>
<body>
	<div id="container">
		<?php 
		require("estilos3.inc"); 
		?>		
		<header id="titulo" style="min-height: 50px">
			<h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Creación de Contactos en Rutero Maestro CVS</h3>
			<h3 style="color: #5F7BA9; font-size: 1.3em; font-family: Vernada">Nombre Rutero: <?php echo $nom_rutero; ?></h3>
		</header>
		<form action="">
			<div class="row">
				<div class="six columns centered">
					<center>
						<table class='texto' border='1' cellspacing='0' width='80%'>
							<tr>
								<th>Dia</th>
								<th>Turno</th>
								<th>Número de Contactos</th>
							</tr>
							<tr>
								<td align='center'>
									<select name='diasciclo' class='texto'>
										<?php  
										for($j=0;$j<=19;$j++) {
											if($diasciclo==$dias_contacto[$j]) {		
												echo "<option value='$dias_contacto[$j]' selected>$dias_contacto[$j]</option>";
											} else {		
												echo "<option value='$dias_contacto[$j]'>$dias_contacto[$j]</option>";
											}	
										}	
										?>
									</select>
								</td>
								<td align='center'>
									<select class='texto' name='turno'>
										<?php  
										if($turno=='Am') {	
											echo"<option value='Am' selected>Mañana</option>";
											echo"<option value='Pm'>Tarde</option>";
										} else {	
											echo"<option value='Am'>Mañana</option>";
											echo"<option value='Pm' selected>Tarde</option>";
										}
										?>
									</select>
								</td>
								<td align='center'>
									<select class='texto' name='num_medicos' onChange='envia_select(this,this.form)'>
										<option></option>
										<?php  
										for($i=1;$i<=20;$i++) {
											if($num_medicos==$i) {	
												echo "<option value=$i selected>$i</option>";
											} else {	
												echo "<option value=$i>$i</option>";
											}

										}
										?>
									</select>
								</td>
							</tr>
						</table>
					</center>
				</div>
			</div>

			<div class="row">
				<div class="eleven columns centered">
					<table border='1' span class='texto' cellspacing='0'>
						<tr>
							<th>Orden Visita</th>
							<th>Nombre Medico</th>
							<th>Direccion</th>
							<th>Especialidad</th>
							<th>Categoria</th>
							<th>Tipo</th>
						</tr>
						<?php  
						for($i=1;$i<=$num_medicos;$i++) {
							$h_orden_visita       = "h_orden$i";
							$h_cod_med            = "h_cod_med$i";
							$h_cod_zona           = "h_cod_zona$i";
							$h_especialidad_med   = "h_especialidad_med$i";
							$h_categoria_med      = "h_categoria_med$i";
							$h_tipoo_med          = "h_tipo_med$i";
							$v_h_cod_med          = $$h_cod_med;
							$v_h_cod_zona         = $$h_cod_zona;
							$v_h_especialidad_med = $$h_especialidad_med;
							$v_h_categoria_med    = $$h_categoria_med;
							$v_h_tipo_med         = $$h_tipoo_med;
							if($v_h_tipo_med == 'cm'){
								$sql="SELECT * from centros_medicos where cod_ciudad = $global_agencia";
							}
							if($v_h_tipo_med == 'c'){
								$sql="SELECT * from clientes2 where cod_area_empresa = $global_agencia";
							}
							if($v_h_tipo_med == 'm'){
								// $sql="SELECT DISTINCT(m.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med from medicos m, categorias_lineas c, medico_asignado_visitador v where c.cod_med=m.cod_med and c.cod_med=v.cod_med and v.codigo_visitador='$global_visitador' order by m.ap_pat_med";
								$sql="SELECT DISTINCT(m.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med from medicos m, categorias_lineas c where c.cod_med=m.cod_med  order by m.ap_pat_med";
							}
							// echo $sql."<br />";
							$res=mysql_query($sql);
							?>
							<tr>
								<td align='center'>
									<input type='text' class='texto' maxlength='2' size='1' name='<?php echo $h_orden_visita ?>' value='<?php echo $i ?>' onKeypress='if (event.keyCode < 48 || event.keyCode > 57 ) event.returnValue = false;'>
								</td>
								<td>
									<select class='texto' name='<?php echo $h_cod_med ?>'>
										<option>Seleccionar Medico</option>
										<?php  
										while($dat=mysql_fetch_array($res)) {	
											if($v_h_tipo_med == 'cm'){
												$codigo  = $dat[0];
												$nombre  = $dat[1];
												if($codigo==$v_h_cod_med) {
													echo "<option value='$codigo' selected>$nombre</option>";
												} else {
													echo "<option value='$codigo'>$nombre</option>";
												}
											}
											if($v_h_tipo_med == 'c'){
												$codigo  = $dat[0];
												$nombre  = $dat[1];
												if($codigo==$v_h_cod_med) {
													echo "<option value='$codigo' selected>$nombre</option>";
												} else {
													echo "<option value='$codigo'>$nombre</option>";
												}
											}
											if($v_h_tipo_med == 'm'){
												$codigo  = $dat[0];
												$paterno = $dat[1];
												$materno = $dat[2];
												$nombre  = $dat[3];
												$nombre_completo = "$paterno $materno $nombre";
												if($codigo==$v_h_cod_med) {
													echo "<option value='$codigo' selected>$nombre_completo</option>";
												} else {
													echo "<option value='$codigo'>$nombre_completo</option>";
												}
											}
										}
										?>
									</select>
								</td>
								<?php  
								if($v_h_tipo_med == 'cm'){
									$sql2="SELECT cod_centro_medico, direccion from centros_medicos where cod_centro_medico='$v_h_cod_med'";
								}
								if($v_h_tipo_med == 'c'){
									$sql2="SELECT cod_cliente, dir_cliente from clientes2 where cod_cliente='$v_h_cod_med'";
								}
								if($v_h_tipo_med == 'm'){
									$sql2="SELECT cod_zona, direccion, numero_direccion from direcciones_medicos where cod_med='$v_h_cod_med'";
								}
								// echo $sql2."<br />";
								$res2=mysql_query($sql2);
								?>
								<td>
									<select class='texto' name='<?php echo $h_cod_zona?>'>
										<?php  
										while($dat2=mysql_fetch_array($res2)) {
											if($v_h_tipo_med == 'cm'){
												$cod_centro       = $dat2[0];
												$direccion        = $dat2[1];
												if($direccion == ''){
													$direccion = "S/D";
												}
												if($$v_h_cod_med == $cod_centro) {
													echo "<option value='$cod_centro' selected>$direccion</option>"; 
												} else {
													echo "<option value='$cod_centro'>$direccion</option>"; 
												}
											}
											if($v_h_tipo_med == 'c'){
												$cod_cliente      = $dat2[0];
												$direccion        = $dat2[1];
												if($direccion == ''){
													$direccion = "S/D";
												}
												if($$v_h_cod_med == $cod_cliente) {
													echo "<option value='$cod_cliente' selected>$direccion</option>"; 
												} else {
													echo "<option value='$cod_cliente'>$direccion</option>"; 
												}
											}
											if($v_h_tipo_med == 'm'){
												$zona             = $dat2[0];
												$direccion        = $dat2[1];
												$numero_direccion = $dat2[2];
												if($direccion == ''){
													$direccion = "S/D";
													$numero_direccion = 0;	
												}
												if($$h_cod_zona==$zona) {
													echo "<option value='$numero_direccion' selected>$direccion</option>"; 
												} else {
													echo "<option value='$numero_direccion'>$direccion</option>"; 
												}
											}
										}
										?>
									</select>
								</td>
								<?php  
								if($v_h_tipo_med == 'm'){
									$sql3="SELECT e.cod_especialidad, e.desc_especialidad, c.categoria_med from especialidades e, categorias_lineas c where e.cod_especialidad=c.cod_especialidad and c.cod_med='$v_h_cod_med' and c.codigo_linea='1021'";
									$resp3=mysql_query($sql3);
								}
								?>
								<td>
									<select class='texto' name='$h_especialidad_med' onChange='envia_select(this,this.form)'>
										<?php  
										if($v_h_tipo_med == 'cm'){
											echo "<option value='0' selected>Sin Especialidad</option>";
										}
										if($v_h_tipo_med == 'c'){
											echo "<option value='0' selected>Sin Especialidad</option>";
										}
										if($v_h_tipo_med == 'm'){
											while($dat3=mysql_fetch_array($resp3)) {
												$cod_esp  = $dat3[0];
												$desc_esp = $dat3[1];
												$cat_med  = $dat3[2];
												if($cod_esp==$v_h_especialidad_med) {
													echo "<option value='$cod_esp' selected>$desc_esp</option>";
												} else {	
													echo "<option value='$cod_esp'>$desc_esp</option>";
												}
											}
										}
										?>
									</select>
								</td>
								<td>
									<?php  
									if($v_h_tipo_med == 'm'){
										$sql4  = "SELECT categoria_med from categorias_lineas where codigo_linea='1021' and cod_med='$v_h_cod_med'";
										$resp4 = mysql_query($sql4);
										$dat   = mysql_fetch_array($resp4);
										$p_categoria_med=$dat[0];
									}
									if($v_h_tipo_med == 'cm'){
										echo "<input type='text' class='texto' size='5' name='-' value='-' disabled='true'>";
									}
									if($v_h_tipo_med == 'c'){
										echo "<input type='text' class='texto' size='5' name='-' value='-' disabled='true'>";
									}
									if($v_h_tipo_med == 'm'){
										?>
										<input type='text' class='texto' size='5' name='<?php echo $h_categoria_med ?>' value='<?php echo $p_categoria_med ?>' disabled='true'>
										<?php
									}
									?>
								</td>
								<td>

									<input type="radio" value="cm" name="<?php echo $h_tipoo_med ?>" <?php if($v_h_tipo_med == 'cm'){echo "checked";}else{echo "";} ?>>Centro Medico <br />
									<input type="radio" value="c" name="<?php echo $h_tipoo_med ?>" <?php if($v_h_tipo_med == 'c'){echo "checked";}else{echo "";} ?>>Clientes<br />
									<input type="radio" value="m" name="<?php echo $h_tipoo_med ?>" <?php if($v_h_tipo_med == 'm'){echo "checked";}else{echo "";} ?>>Medicos
								</td>
							</tr>
							<?php  
						}
						?>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="four columns centered">
					<input type='hidden' name='rutero' value='<?php echo $cod_rutero ?>'>
					<input type='submit' value='Actualizar Datos' class='button'>
					<input type='button' value='Guardar' onClick='envia(this.form)' class='button'>
				</div>
			</div>
		</form>
	</div>
</body>
</html>