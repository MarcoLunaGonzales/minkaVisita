<?php
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	
	$sql_cab="select paterno,materno,nombres from funcionarios where codigo_funcionario='$j_funcionario'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_funcionario="$dat_cab[0] $dat_cab[1] $dat_cab[2]";
	$sql_lineas="select nombre_linea from lineas where codigo_linea='$linea_importada'";
	$resp_lineas=mysql_query($sql_lineas);
	$dat_lineas=mysql_fetch_array($resp_lineas);
	$nombre_linea=$dat_lineas[0];
	$sql_visitador="select paterno, materno, nombres from funcionarios 
					where codigo_funcionario='$visitador_importado'";
	$resp_visitador=mysql_query($sql_visitador);
	$dat_vis=mysql_fetch_array($resp_visitador);
	$nombre_vis_impor="$dat_vis[0] $dat_vis[1] $dat_vis[2]";
	echo "<center><table border='0' class='textotit'><tr><th>Importar Rutero Maestro<br>Visitador: $nombre_funcionario<br>Línea a importar el rutero maestro: $nombre_linea<br>Visitador a importar el rutero maestro: $nombre_vis_impor</th></tr></table></center><br>";
	
	//esta parte saca el ciclo activo
	$sql="select r.cod_contacto, r.cod_rutero, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje from rutero_maestro r, orden_dias o where r.cod_visitador=$visitador_importado and r.cod_rutero='$rutero_importado' and r.dia_contacto=o.dia_contacto order by o.id";
	$resp=mysql_query($sql);
	echo "<center><table border='1' class='textomini' cellspacing='0' width='90%'>";
	echo "<tr><th>Dia Contacto</th><th>Turno</th><th>Contactos</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_contacto=$dat[0];
		$cod_ciclo=$dat[1];
		$dia_contacto=$dat[3];
		$turno=$dat[4];
		$sql1="select c.orden_visita, m.ap_pat_med, m.ap_mat_med, m.nom_med, d.direccion, c.cod_especialidad, c.categoria_med, c.estado
				from rutero_maestro_detalle c, medicos m, direcciones_medicos d
					where (c.cod_contacto=$cod_contacto) and (c.cod_visitador=$visitador_importado) and (c.cod_med=m.cod_med) and (m.cod_med=d.cod_med) and (c.cod_zona=d.numero_direccion) order by c.orden_visita";
		$resp1=mysql_query($sql1);
		$contacto="<table class='textomini' width='100%'>";
		$contacto=$contacto."<tr><th width='5%'>Orden</th><th width='35%'>Medico</th><th width='10%'>Especialidad</th><th width='10%'>Categoria</th><th width='30%'>Direccion</th></tr>";
		while($dat1=mysql_fetch_array($resp1))
		{
			$orden_visita=$dat1[0];
			$pat=$dat1[1];
			$mat=$dat1[2];
			$nombre=$dat1[3];
			$direccion=$dat1[4];
			$nombre_medico="$pat $mat $nombre";
			$espe=$dat1[5];
			$cat=$dat1[6];
			$contacto=$contacto."<tr><td align='center'>$dat1[0]</td><td>&nbsp;$nombre_medico</td><td align='center'>$espe</td><td align='center'>$cat</td><td>$direccion </td></tr>";
		}
		$contacto=$contacto."</table>";
		echo "<tr><td align='center'>$dia_contacto</td><td align='center'>$turno</td><td align='center'>$contacto</td></tr>";
	}
	echo "</table></center><br>";
	
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
	//<td align='center'><input type='checkbox' name='cod_contacto' value=$cod_contacto></td>
	//echo "<center><table border='0' class='texto'>";
	//echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	echo "</form>";
?>