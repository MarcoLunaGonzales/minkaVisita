<?php  
error_reporting(0);
require("conexion.inc");

$codigo_parrilla = $_GET['codigo_parrilla'];
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Registro de visita</title>
	<link type="text/css" href="css/style.css" rel="stylesheet" />
	<link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
	<link rel="stylesheet" href="responsive/stylesheets/style.css">
	<link type="text/css" href="js/fancybox/jquery.fancybox.css" rel="stylesheet" />
	<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.fancybox.js"></script>
	<script type="text/javascript">
    $(document).ready(function() {
        $("#sugerir").fancybox({
            fitToView   : true
        });
    });
</script>
</head>
<body>
	<div id="container">
		<?php 
		require("estilos3.inc"); 
		$sql_ciclo = "SELECT cod_ciclo from ciclos where estado='Activo' and codigo_linea='$global_linea'"; 
		$resp_ciclo = mysql_query($sql_ciclo); 
		$dat_ciclo = mysql_fetch_array($resp_ciclo); 
		$ciclo_activo = $dat_ciclo[0];
		$vector = explode("-", $cod_contacto);
		$contacto = $vector[0];
		$orden_visita = $vector[1];
		$visitador = $vector[2];
		$fecha_visita = $vector[3];
		$agencia_parrilla = $vector[4];
		$sql = "SELECT c.turno, m.ap_pat_med, m.ap_mat_med, m.nom_med, dm.direccion, cd.categoria_med, cd.cod_especialidad, cd.orden_visita, c.cod_contacto, cd.estado, m.cod_med from rutero c, rutero_detalle cd, medicos m, direcciones_medicos dm where c.cod_ciclo='$ciclo_activo' and c.cod_visitador=$global_visitador and m.cod_med=cd.cod_med and dm.numero_direccion=cd.cod_zona and cd.cod_med=dm.cod_med and c.cod_contacto='$contacto' and c.cod_contacto=cd.cod_contacto and cd.orden_visita='$orden_visita' order by c.turno,cd.orden_visita";
		$resp = mysql_query($sql);
		$dat_enc = mysql_fetch_array($resp);
		$enc_nombre_medico = "$dat_enc[1] $dat_enc[2] $dat_enc[3]";
		$enc_turno = $dat_enc[0];
		$enc_categoria = $dat_enc[5];
		$enc_especialidad = $dat_enc[6];
		$cod_med = $dat_enc[10];
		$sql_nombre_dia = "SELECT dia_contacto from rutero where cod_contacto='$contacto'"; 
		$resp_nombre_dia = mysql_query($sql_nombre_dia); 
		$dat_nombre_dia = mysql_fetch_array($resp_nombre_dia); 
		$nombre_de_dia = $dat_nombre_dia[0];

		$sqlCodDia = "SELECT id from orden_dias where dia_contacto='$nombre_de_dia'"; 
		$respCodDia = mysql_query($sqlCodDia); 
		$codDiaContacto = mysql_result($respCodDia, 0, 0);
		$sql = "SELECT cod_especialidad, categoria_med, estado from rutero_detalle where cod_contacto=$contacto and orden_visita=$orden_visita"; 
		$res = mysql_query($sql); 
		$dat = mysql_fetch_array($res); 
		$especialidad = $dat[0];
		$categoria = $dat[1];
		$estado_pri = $dat[2];
		?>
		<header id="titulo" style="min-height: 50px">
			<h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Registro de Visita Médica</h3>
			<h3 style="color: #5F7BA9; font-size: 1.2em; font-family: Vernada">Medico: <?php echo $enc_nombre_medico; ?> | Especialidad: <?php echo $enc_especialidad; ?> | Categoria: <?php echo $categoria; ?> </h3>
			<h3 style="color: #5F7BA9; font-size: 1.2em; font-family: Vernada"><?php echo $nombre_de_dia." ".$fecha_visita; ?></h3>
			<center><table border='0' class='textomini' align='center'><tr><td>Leyenda:</td><td>Producto Objetivo</td><td bgcolor='#ffff99' width='10%'></td><td>&nbsp;</td><td>Producto Filtrado</td><td bgcolor='#ff7591' width='10%'></td><td>&nbsp;</td><td>Producto Extra</td><td bgcolor='#66ccff' width='10%'></td></table></center>
		</header>
		<?php  
		$verifica_lineas = "SELECT lv.codigo_l_visita from lineas_visita_visitadores_copy lv, lineas_visita_especialidad le WHERE le.codigo_l_visita = lv.codigo_l_visita and lv.codigo_funcionario = $global_visitador and lv.codigo_gestion = $codigo_gestion and lv.codigo_ciclo = $ciclo_activo and lv.codigo_linea_visita = $global_linea and le.cod_especialidad = '$especialidad'";
		$resp_verifica_lineas = mysql_query($verifica_lineas);
		$filas_verifica = mysql_num_rows($resp_verifica_lineas);
		if ($filas_verifica != 0) {
			$dat_verifica = mysql_fetch_array($resp_verifica_lineas);
			$codigo_l_visita = $dat_verifica[0];
		} else {
			$codigo_l_visita = 0;
		}

		$sql = "SELECT mm.descripcion, mm.presentacion, pd.cantidad_muestra, ma.descripcion_material, pd.cantidad_material, pd.codigo_parrilla,pd.prioridad,mm.codigo,ma.codigo_material from muestras_medicas mm, parrilla_detalle pd, material_apoyo ma, parrilla p where p.codigo_parrilla=pd.codigo_parrilla and p.cod_especialidad='$especialidad' and p.agencia='$agencia_parrilla' and p.categoria_med='$categoria' and p.codigo_linea='$global_linea' and p.codigo_l_visita='$codigo_l_visita' and mm.codigo=pd.codigo_muestra and p.numero_visita='$visita' and ma.codigo_material=pd.codigo_material and p.cod_ciclo='$ciclo_activo' and p.codigo_gestion='$codigo_gestion' order by pd.prioridad";
		$resp = mysql_query($sql);
		$numero_registros = mysql_num_rows($resp);
		if ($numero_registros != 0) {
			echo "<form name='form' name='principal' method='get' action=''>";
			echo "<table border='0' cellspacing='0' class='texto' align='center'><tr><th>Fecha Real de Visita</th><th>
			<INPUT  type='text' class='texto' id='fechaVisitaReal' size='10' name='fechaVisitaReal' value='$fecha_visita'>
				<IMG id='imagenFecha' src='imagenes/fecha.bmp'>
					<DLCALENDAR tool_tip='Seleccione la Fecha'
					daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' 
					navbar_style='background-color: 7992B7; color:ffffff;' 
					input_element_id='fechaVisitaReal' 
					click_element_id='imagenFecha'></DLCALENDAR></th>
				</tr></table><br>";
				echo "<table border='1' cellspacing='0' class='textomini' width='100%'>";
				echo "<tr><th>Muestra</th><th>Cantidad</th><th>Cantidad extra entregada</th><th>Material de Apoyo</th><th>Cantidad</th>
				<th>Cantidad extra entregada</th><th>Obs.</th><th>Entregado</th></tr>";
				$i = 1;
				$constancia = "constancia";
				$cantidad_extraentregada = "cantidad_extraentregada";
				$cantidad_extraapoyo = "cantidad_extraapoyo";
				while ($dat = mysql_fetch_array($resp)) {
					$muestra         = $dat[0];
					$presentacion    = $dat[1];
					$cantidad        = $dat[2];
					$apoyo           = $dat[3];
					$cant_apoyo      = $dat[4];
					$parrilla        = $dat[5];
					$prioridad       = $dat[6];
					$cod_muestra     = $dat[7];
					$codigo_material = $dat[8];
					$sql_negados = "SELECT * from muestras_negadas where cod_med='$cod_med' and codigo_muestra='$cod_muestra' and codigo_linea='$global_linea'";

					$res_negados = mysql_query($sql_negados);
					$num_negados = mysql_num_rows($res_negados);
					if ($num_negados != 0) {
						$fondo = "#ff7591";
					}
					$sql_obj = "SELECT * from productos_objetivo where cod_med='$cod_med' and codigo_muestra='$cod_muestra' and codigo_linea='$global_linea'";
					$res_obj = mysql_query($sql_obj);
					$num_obj = mysql_num_rows($res_obj);
					if ($num_obj != 0) {
						$fondo = "#ffff99";
					}
					echo "<input type='hidden' name='muestra$i' value='$cod_muestra'>";
					echo "<input type='hidden' name='material$i' value='$codigo_material'>";
					echo "<tr bgcolor='$fondo'><td>$muestra $presentacion </td><td align='center'>";
					$var_constancia = "$constancia$i";
					$valor_constancia = $$var_constancia;
					if ($valor_constancia == 1) {
						$var_cant_entregada   = "$cantidad_extraentregada$i";
						$valor_cant_entregada = $$var_cant_entregada;
						$var_cant_apoyo       = "$cantidad_extraapoyo$i";
						$valor_cant_apoyo     = $$var_cant_apoyo;
						echo "<select name='cantidad_entregada$i' class='texto'>";
					} else {
						echo "<select name='cantidad_entregada$i' class='texto' disabled='true'>";
					}
					for ($ii = 0; $ii <= $cantidad; $ii++) {
						if ($ii == $cantidad) {
							echo "<option value='$ii' selected>$ii</option>";
						} else {
							echo "<option value='$ii'>$ii</option>";
						}
					}
					echo "</select>";
					echo "</td>";
					if ($valor_constancia == 1) {
						echo "<td align='center'><select name='cantidad_extraentregada$i' class='textomini'>";
					} else {
						echo "<td align='center'><select name='cantidad_extraentregada$i' class='textomini' disabled='true'>";
					}
					echo "<option value=''></option>";
					for ($j = 1; $j <= 20; $j++) {
						if ($valor_cant_entregada == $j and $valor_constancia == 1) {
							echo "<option value='$j' selected>$j</option>";
						} else {
							echo "<option value='$j'>$j</option>";
						}
					}
					echo "</td>";
					echo "<td>$apoyo</td><td align='center'>";
					if ($valor_constancia == 1) {
						echo "<select name='cantidad_apoyo$i' class='texto'>";
					} else {
						echo "<select name='cantidad_apoyo$i' class='texto' disabled='true'>";
					}
					for ($jj = 0; $jj <= $cant_apoyo; $jj++) {
						if ($jj == $cant_apoyo) {
							echo "<option value='$jj' selected>$jj</option>";
						} else {
							echo "<option value='$jj'>$jj</option>";
						}
					}
					echo "</select>";
					echo "</td>";
					if ($valor_constancia == 1 and $codigo_material != 0) {
						echo "<td align='center'><select name='cantidad_extraapoyo$i' class='textomini'>";
					} else {
						echo "<td align='center'><select name='cantidad_extraapoyo$i' class='textomini' disabled='true'>";
					}
					echo "<option value=''></option>";
					for ($j = 1; $j <= 20; $j++) {
						if ($valor_cant_apoyo == $j and $valor_constancia == 1) {
							echo "<option value='$j' selected>$j</option>";
						} else {
							echo "<option value='$j'>$j</option>";
						}
					}
					echo "</td>";

					if ($valor_constancia == 1) {
						echo "<td align='center'><input type='text' name='obs$i' class='textomini'></td>";
						echo "<td align='center'><input type=checkbox name='constancia$i' value='1' onClick='activa_select(this.form,$i)' checked></td></tr>";
					} else {
						echo "<td align='center'><input type='text' name='obs$i'  class='textomini' disabled></td>";
						echo "<td align='center'><input type=checkbox name='constancia$i' value='1' onClick='activa_select(this.form,$i)'></td></tr>";
					}
					$i = $i + 1;
					$fondo = "";
				}
				echo "</table><br>";
				echo "<table border='1' cellspacing='0' class='texto' width='100%' align='center'>";
				echo "<tr><th colspan='5'>Cantidad de muestras de productos extra-parrilla entregadas:&nbsp;&nbsp;";
				echo "<select name='muestras_extra' class='textomini' onChange='envia_form(this.form)'>";
				for ($m = 0; $m <= 10; $m++) {
					if ($m == $muestras_extra) {
						echo "<option value='$m' selected>$m</option>";
					} else {
						echo "<option value='$m'>$m</option>";
					}
				}
				echo "</select>";
				echo "</th></tr>";
				echo "<tr><th class='textomini' width='38%'>Muestra</th><th class='textomini' width='11%'>Cantidad Entregada</th><th class='textomini' width='35%'>Material de Apoyo</th><th class='textomini' width='11%'>Cantidad Entregada</th><th class='textomini' width='5%'>&nbsp;</th></tr>";
				$prod_extra = "prod_extra";
				$cant_entregada_extra = "cant_entregada_extra";
				$material_extra = "mat_extra";
				$cant_entregada_apoyo_extra = "cant_entregada_apoyo_extra";
				for ($filas_extra = 1; $filas_extra <= $muestras_extra; $filas_extra++) {
					$var_prod_extra = "$prod_extra$filas_extra";
					$valor_prod_extra = $$var_prod_extra;
					$var_cant_ent_extra = "$cant_entregada_extra$filas_extra";
					$valor_cant_ent_extra = $$var_cant_ent_extra;
					$var_material_extra = "$mat_extra$filas_extra";
					$valor_material_extra = $$var_material_extra;
					$var_cant_apoyo_extra = "$cant_entregada_apoyo_extra$filas_extra";
					$valor_can_apoyo_extra = $$var_cant_apoyo_extra;
					echo "<tr bgcolor='#66ccff'><td>";
					$sql_prod_extra = "select codigo, descripcion, presentacion from muestras_medicas where estado=1 order by descripcion, presentacion";
					$resp_prod_extra = mysql_query($sql_prod_extra);
					echo "<select name='prod_extra$filas_extra' class='textomini'>";
					while ($dat_extra = mysql_fetch_array($resp_prod_extra)) {
						$codigo_material_extra = $dat_extra[0];
						$nombre_material_extra = "$dat_extra[1] $dat_extra[2]";
						if ($valor_prod_extra == $codigo_material_extra) {
							echo "<option value='$codigo_material_extra' selected>$nombre_material_extra</option>";
						} else {
							echo "<option value='$codigo_material_extra'>$nombre_material_extra</option>";
						}
					}
					echo "</select></td>";
					echo "<td align='center'><select name='cant_entregada_extra$filas_extra' class='textomini'>";
					for ($ce = 0; $ce <= 20; $ce++) {
						if ($valor_cant_ent_extra == $ce) {
							echo "<option value='$ce' selected>$ce</option>";
						} else {
							echo "<option value='$ce'>$ce</option>";
						}
					}
					echo "</select></td>";
					echo "<td><select name='mat_extra$filas_extra' class='textomini'>";
					$sql_apoyo_extra = "select codigo_material, descripcion_material from material_apoyo where estado='Activo' order by 2 ASC";
					$resp_apoyo_extra = mysql_query($sql_apoyo_extra);
					while ($dat_apoyo_extra = mysql_fetch_array($resp_apoyo_extra)) {
						$codigo_material_extra = $dat_apoyo_extra[0];
						$descripcion_material_extra = $dat_apoyo_extra[1];
						if ($valor_material_extra == $codigo_material_extra) {
							echo "<option value='$codigo_material_extra' selected>$descripcion_material_extra</option>";
						} else {
							echo "<option value='$codigo_material_extra'>$descripcion_material_extra</option>";
						}
					}
					echo "</select></td>";
					echo "<td align='center'><select name='cant_entregada_apoyo_extra$filas_extra' class='textomini'>";
					for ($cae = 0; $cae <= 20; $cae++) {
						if ($valor_can_apoyo_extra == $cae) {
							echo "<option value='$cae' selected>$cae</option>";
						} else {
							echo "<option value='$cae'>$cae</option>";
						}
					}
					echo "</select></td><td>&nbsp;</td></tr>";
				}
				echo "</table><br>";
				echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1);'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
				echo "<table border='0' align='center'>";
				echo "<input type='hidden' name='valores' value='$contacto-$orden_visita-$parrilla-$i'>";
				echo "<input type='hidden' name='cod_contacto' value='$cod_contacto'>";
				echo "<input type='hidden' name='visita' value='$visita'>";
				echo "<input type='hidden' name='cod_med' value='$cod_med'>";
				echo "<input type='hidden' name='cod_espe' value='$especialidad'>";
				echo "<input type='hidden' name='cat_med' value='$categoria'>";
				echo "<input type='hidden' name='cod_dia' value='$codDiaContacto'>";
				echo "<input type='hidden' name='codigo_parrilla' value='$codigo_parrilla'>";


				echo "<tr>
				<th><input type='button' OnClick='guarda_form(this.form)' class='boton' value='Guardar'></th>
				<th><a href='#sugerencias' id='sugerir' class='boton'>Sugerir Parrilla</a></th>
			</tr>";
			echo "</form>";
			echo "</table>";
			?>
			<div id="sugerencias" style="width:100%;display: none;">
				dsjadjskal
			</div>
			<?php
		} else {
			echo "<script language='Javascript'>
			alert('No existe ninguna parrilla definida para la especialidad $especialidad y la categoria $categoria');
			window.close();
		</script>";
	}
	echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";
	?>
</div>
</body>
</html>