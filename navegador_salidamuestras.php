<?php
	require("conexion.inc");
	require('function_formatofecha.php');

error_reporting(0);
echo "<script language='Javascript'>
		
		function nuevoAjax()
		{	var xmlhttp=false;
			try {
					xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
			} catch (e) {
			try {
				xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
			} catch (E) {
				xmlhttp = false;
			}
			}
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
			xmlhttp = new XMLHttpRequest();
			}
			return xmlhttp;
		}
			

		function ShowBuscar(){
			document.getElementById('divRecuadroExt').style.visibility='visible';
			document.getElementById('divProfileData').style.visibility='visible';
			document.getElementById('divProfileDetail').style.visibility='visible';
		}

		function HiddenBuscar(){
			document.getElementById('divRecuadroExt').style.visibility='hidden';
			document.getElementById('divProfileData').style.visibility='hidden';
			document.getElementById('divProfileDetail').style.visibility='hidden';
		}

		function ajaxBuscarSalidas(f, grupoSalida){
			var fechaIniBusqueda, fechaFinBusqueda, notaIngreso, verBusqueda, global_almacen, provBusqueda;
			fechaIniBusqueda=document.getElementById('fechaIniBusqueda').value;
			fechaFinBusqueda=document.getElementById('fechaFinBusqueda').value;
			notaIngreso=document.getElementById('notaIngreso').value;
			global_almacen=document.getElementById('global_almacen').value;
			provBusqueda=document.getElementById('provBusqueda').value;
			var contenedor;
			contenedor = document.getElementById('divCuerpo');
			ajax=nuevoAjax();

			ajax.open('GET', 'ajaxNavSalidas.php?fechaIniBusqueda='+fechaIniBusqueda+'&fechaFinBusqueda='+fechaFinBusqueda+'&notaIngreso='+notaIngreso+'&global_almacen='+global_almacen+'&provBusqueda='+provBusqueda+'&grupoSalida='+grupoSalida,true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText;
					HiddenBuscar();
				}
			}
			ajax.send(null)
		}	
		
		function enviar_nav(grupoSalida)
		{	location.href='registrar_salidamuestras.php?grupoSalida='+grupoSalida;
		}
		function editar_salida(f, grupoSalida)
		{
			var i;
			var j=0;
			var j_cod_registro, estado_preparado;
			var fecha_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						estado_preparado=f.elements[i-1].value;
						fecha_registro=f.elements[i-2].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para anularlo.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para anularlo.');
				}
				else
				{	
					if(f.fecha_sistema.value==fecha_registro)
					{	if(estado_preparado==1)
						{	alert('No puede modificar esta salida porque esta en proceso de preparaci�n');
						}
						else
						{	location.href='editar_salidamuestras.php?codigo_registro='+j_cod_registro+'&grupoSalida='+grupoSalida+'&valor_inicial=1';
						}
					}
					else
					{	alert('Usted no esta autorizado(a) para modificar esta salida');
					}
				}
			}
		}
		function anular_salida(f, grupoSalida)
		{
			var i;
			var j=0;
			var j_cod_registro;
			var fecha_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						fecha_registro=f.elements[i-2].value;
						estado_preparado=f.elements[i-1].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para anularlo.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para anularlo.');
				}
				else
				{	if(f.fecha_sistema.value==fecha_registro)
					{	if(estado_preparado==1)
						{	alert('No puede anular esta salida porque esta en proceso de preparaci�n.');
						}
						else
						{	location.href='anular_salida.php?codigo_registro='+j_cod_registro+'&grupoSalida='+grupoSalida;
						}
					}
					else
					{	//alert('Usted no esta autorizado(a) para modificar esta salida');
						location.href='anular_salida.php?codigo_registro='+j_cod_registro+'&grupo_salida='+grupoSalida;				
					}

				}
			}
		}
		function preparar_despacho(f, grupoSalida)
		{
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos una salida para proceder a su preparado.');
			}
			else
			{	location.href='preparar_despacho.php?datos='+datos+'&tipo_material=1&grupoSalida='+grupoSalida;
			}
		}
		function imprimirNotas(f)
		{
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos una salida para imprimir la Nota.');
			}
			else
			{	window.open('navegador_detallesalidamuestrasResumen.php?codigo_salida='+datos+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
			}
		}
		function enviar_datosdespacho(f,grupoSalida)
		{
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos una salida para proceder al registro del despacho.');
			}
			else
			{	location.href='registrar_datosdespacho.php?datos='+datos+'&tipo_material=1&grupoSalida='+grupoSalida;
			}
		}
		
		function llamar_preparado(f, estado_preparado, codigo_salida)
		{	if(estado_preparado==0)
			{	if(confirm('Desea Cambiar el estado a EN PREPARACION.'))
				{	//window.open('navegador_detallesalidamuestras.php?codigo_salida='+codigo_salida,'notaremision','');
					window.open('preparar_despacho.php?datos='+codigo_salida+'&tipo_material=1&grupo_salida=1&cerrar=1','popup','');	
				}
				else
				{	window.open('navegador_detallesalidamuestras.php?codigo_salida='+codigo_salida,'','');				
				}
			}	
			if(estado_preparado==1)
			{	window.open('navegador_detallesalidamuestras.php?codigo_salida='+codigo_salida,'popup','');
			}
		}
		
		</script>";
	
	require("estilos_almacenes.inc");
	
	echo "<form method='get' action=''>";
	$fecha_sistema=date("d-m-Y");
	
	$grupoSalida=$_GET['grupoSalida'];
	echo "<input type='hidden' value='$global_almacen' name='global_almacen' id='global_almacen'>";
	echo "<input type='hidden' value='$grupoSalida' name='grupoSalida' id='grupoSalida'>";

	echo "<input type='hidden' name='fecha_sistema' value='$fecha_sistema'>";
	
	$sql="select DISTINCT ( s.cod_salida_almacenes), s.fecha, s.hora_salida, ts.nombre_tiposalida, c.descripcion, a.nombre_almacen, s.observaciones, 
		s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino, 
		(select es.nombre_estado_salida from estados_salida es where s.estado_salida=es.cod_estado_salida),
		(select es.color from estados_salida es where s.estado_salida=es.cod_estado_salida) 			
		FROM salida_almacenes s, tipos_salida ts, ciudades c, 
		almacenes a where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='$global_almacen' and c.cod_ciudad=s.territorio_destino 
		and a.cod_almacen=s.almacen_destino and s.grupo_salida='$grupoSalida' order by 
		s.fecha desc, s.nro_correlativo desc limit 0,150";
	

	$resp=mysql_query($sql);
	if($grupoSalida==1){
		echo "<h1>Salida de Muestras</h1>";
	}else{
		echo "<h1>Salida de Material</h1>";
	}
	
	//echo "<table border='1' class='textomini' cellspacing='0' width='90%'><tr><th>Leyenda:</th><th>Salidas Despachadas a otras agencias</th><td bgcolor='#bbbbbb' width='5%'></td><th>Salidas recepcionadas</th><td bgcolor='#33ccff' width='5%'></td><th>Salidas Anuladas</th><td bgcolor='#ff8080' width='5%'></td><th>Salidas en proceso de despacho</th><td bgcolor='#ffff99' width='5%'></td><th>Salidas locales</th><td bgcolor='#66ff99' width='5%'></td><th>Salidas pendientes</th><td bgcolor='' width='10%'>&nbsp;</td></tr></table><br>";

	echo "<div class='divBotones'>
		<input type='button' value='Registrar Salida' name='adicionar' class='boton' onclick='enviar_nav($grupoSalida)'>
		<!--input type='button' value='Editar Salida' class='boton' onclick='editar_salida(this.form,$grupoSalida)'-->
		<input type='button' value='Anular Salida' class='boton2' onclick='anular_salida(this.form,$grupoSalida)'>		
		<input type='button' value='Registrar Despacho' class='boton' onclick='enviar_datosdespacho(this.form,$grupoSalida)'>
		<input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'>
		</div>";
	
	//	<input type='button' value='Preparar Despacho' class='boton' onclick='preparar_despacho(this.form)'>
	//	<input type='button' value='Imprimir en Conjunto' class='boton' onclick='imprimirNotas(this.form)'>

	echo "<div id='divCuerpo'>";
	
	echo "<br><center><table class='texto'>";
	echo "<tr>
		<th>&nbsp;</th><th>Nro. Salida</th><th>Fecha/Hora<br>Registro Salida</th><th>Tipo de Salida</th>
		<th>Territorio<br>Destino</th><th>Funcionario Destino</th><th>Observaciones</th>
		<th>Estado</th><th>&nbsp;</th><th>&nbsp;</th>
		</tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$fecha_salida=$dat[1];
		$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
		$hora_salida=$dat[2];
		$nombre_tiposalida=$dat[3];
		$nombre_ciudad=$dat[4];
		$nombre_almacen=$dat[5];
		$obs_salida=$dat[6];
		$estado_almacen=$dat[7];
		$nro_correlativo=$dat[8];
		$salida_anulada=$dat[9];
		$cod_almacen_destino=$dat[10];
		$nombreEstadoSalida=$dat[11];
		$colorSalida=$dat[12];
		
		echo "<input type='hidden' name='fecha_salida$nro_correlativo' value='$fecha_salida_mostrar'>";
		$estado_preparado=0;

		if($estado_almacen==0 || $estado_almacen==3){
			$chk="<input type='checkbox' name='codigo' value='$codigo'>";
		}else{
			$chk="&nbsp;";
		}
		
		if($estado_almacen==3){	
			$estado_preparado=1;
		}
		
		echo "<input type='hidden' name='estado_preparado' value='$estado_preparado'>";
		$sql_funcionario="select f.paterno, f.materno, f.nombres from funcionarios f, salida_detalle_visitador sv
		where sv.cod_salida_almacen='$codigo' and f.codigo_funcionario=sv.codigo_funcionario";
		$resp_funcionario=mysql_query($sql_funcionario);
		$dat_funcionario=mysql_fetch_array($resp_funcionario);
		$nombre_funcionario="$dat_funcionario[0] $dat_funcionario[1] $dat_funcionario[2]";
		//echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>$nombre_almacen</td><td>$nombre_funcionario</td><td>&nbsp;$obs_salida</td><td>$txt_detalle</td></tr>";
		echo "<tr>";
		echo "<td align='center'>$chk</td><td align='center'>$nro_correlativo</td>
			<td align='center'>$fecha_salida_mostrar $hora_salida</td>
			<td>$nombre_tiposalida</td><td>$nombre_ciudad</td>
			<td>&nbsp;$nombre_funcionario</td><td>&nbsp;$obs_salida</td>
			<td bgcolor='$colorSalida'>$nombreEstadoSalida</td>";
		
		$url_notaremision="navegador_detallesalidamuestras.php?codigo_salida=$codigo";
		
		echo "<td><a href='navegador_detallesalidamuestras.php?codigo_salida=$codigo&grupoSalida=$grupoSalida'><img src='imagenes/detalle.png' border='0' title='Ver Detalles de la Salida Interna' width='40'></a></td>";
		echo "<td><a target='_BLANK' href='navegador_detallesalidaenvio.php?codigo_salida=$codigo'><img src='imagenes/detalle.png' border='0' title='Ver Detalles de la Salida Interna' width='40'></a></td>
		</tr>";

	}
	echo "</table></center><br>";	
	
	echo "</div>";

	
	echo "<div class='divBotones'>
		<input type='button' value='Registrar Salida' name='adicionar' class='boton' onclick='enviar_nav($grupoSalida)'>
		<!--input type='button' value='Editar Salida' class='boton' onclick='editar_salida(this.form,$grupoSalida)'-->
		<input type='button' value='Anular Salida' class='boton2' onclick='anular_salida(this.form,$grupoSalida)'>		
		<input type='button' value='Registrar Despacho' class='boton' onclick='enviar_datosdespacho(this.form,$grupoSalida)'>
		<input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'>
		</div>";

		
	echo "</form>";
?>

<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:78px; left:225px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:100px; left:250px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Buscar Salidas</h2>
		<table align='center' class='texto'>
			<tr>
				<td>Fecha Ini(dd/mm/aaaa)</td>
				<td>
				<input type='date' name='fechaIniBusqueda' id="fechaIniBusqueda" class='texto'>
				</td>
			</tr>
			<tr>
				<td>Fecha Fin(dd/mm/aaaa)</td>
				<td>
				<input type='date' name='fechaFinBusqueda' id="fechaFinBusqueda" class='texto'>
				</td>
			</tr>
			<tr>
				<td>Nota de Salida</td>
				<td>
				<input type='text' name='notaIngreso' id="notaIngreso" class='texto'>
				</td>
			</tr>			
			<tr>
				<td>Muestra/Material</td>
				<td>
					<select name="ProvBusqueda" class="texto" id="provBusqueda" style="width:400px">
						<option value="0">--Todo--</option>
					<?php
						if($grupoSalida==1){
							$sqlProv="select codigo, concat(descripcion,' ',presentacion) from muestras_medicas order by 2";	
						}else{
							$sqlProv="select codigo_material, descripcion_material from material_apoyo order by 2";
						}
						$respProv=mysql_query($sqlProv);
						while($datProv=mysql_fetch_array($respProv)){
							$codProvBus=$datProv[0];
							$nombreProvBus=$datProv[1];
					?>
							<option value="<?php echo $codProvBus;?>"><?php echo $nombreProvBus;?></option>
					<?php
						}
					?>
					</select>
				
				</td>
			</tr>			
		</table>	
		<center>
			<input class='boton' type='button' value='Buscar' onClick="ajaxBuscarSalidas(this.form, <?php echo $grupoSalida;?>)">
			<input class='boton2' type='button' value='Cancelar' onClick="HiddenBuscar()">
			
		</center>
	</div>
</div>
