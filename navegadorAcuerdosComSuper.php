<?php
echo "<script>
		
		globalRegistro=0;
		
		function ShowAprobar(idAcuerdo){
			document.getElementById('divRecuadroExt').style.visibility='visible';
			document.getElementById('divProfileData').style.visibility='visible';
			document.getElementById('divProfileDetail').style.visibility='visible';
			globalRegistro=idAcuerdo;
		}

		function HiddenAprobar(){
			document.getElementById('divRecuadroExt').style.visibility='hidden';
			document.getElementById('divProfileData').style.visibility='hidden';
			document.getElementById('divProfileDetail').style.visibility='hidden';
			globalRegistro=0;
		}
		function ajaxAprobarRechazar(f, estado){
			var obsAprobar=document.getElementById('obsAprobar').value;						
			var idAcuerdo=globalRegistro;
			location.href='guardarAprobacionAcuerdosCom.php?codAcuerdoCom='+idAcuerdo+'&estado='+estado+'&observacion='+obsAprobar;
			HiddenAprobar();
		}

		function enviar_nav()
		{	location.href='registrarAcuerdosCom.php';
		}
		function eliminar_nav(f)
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
			{	alert('Debe seleccionar al menos un Registro para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminarAcuerdosCom.php?datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}

		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_cod_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un Almacen para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Almacen para editar sus datos.');
				}
				else
				{
					location.href='editar_almacenes.php?codigo_registro='+j_cod_registro+'';
				}
			}
		}
		</script>";
	require("conexion.inc");
	require("estilos_administracion.inc");
	require("funciones.php");
	
	
	echo "<form>";
	
	$globalUsuario=$_COOKIE['global_usuario'];
	$globalAgencia=$_COOKIE['global_agencia'];
	
	echo "<h1>Acuerdos Comerciales</h1>";
	
	$sql="select a.cod_cliente, (select c.nombre_cliente from clientes c where c.cod_cliente=a.cod_cliente) as cliente,
		a.promedio_ventas, a.porcentaje_crecimiento, a.monto_objetivo, a.porcentaje_rebate, a.monto_rebate, a.numero_meses, 
		DATE_FORMAT(a.fecha_inicio, '%d/%m/%Y'), DATE_FORMAT(a.fecha_final, '%d/%m/%Y'), a.detalle,
		(select e.nombre_estadoacuerdo from estados_acuerdoscom e where e.cod_estadoacuerdo=a.cod_estadoacuerdo) as estado,
		a.id_acuerdos, a.cod_estadoacuerdo,
		(select concat(f.paterno,' ', f.nombres) from funcionarios f where f.codigo_funcionario=a.cod_funcionario) as nombrefuncionario
		from acuerdos_comerciales a
		where a.cod_ciudad='$globalAgencia'";
	//echo $sql;	
	$resp=mysql_query($sql);
	echo "<center><table class='texto'>";
	echo "<tr><th>ID</th><th>Cliente</th><th>Promedio Venta</th><th>% Crec.</th><th>Monto Obj.</th>
	<th>% Rebate</th><th>Monto Rebate</th><th># Meses</th><th>Inicio</th><th>Fin</th><th>Monto<br>Seg.</th><th>Detalle</th><th>Registrado Por</th><th>Estado</th><th>-</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codCliente=$dat[0];
		$nombreCliente=$dat[1];
		$promedioVentas=formatonumero($dat[2]);
		$porcCrecimiento=$dat[3];
		$montoObjetivo=formatonumero($dat[4]);
		$porcRebate=$dat[5];
		$montoRebate=formatonumero($dat[6]);
		$nroMeses=$dat[7];
		$fechaIni=$dat[8];
		$fechaFin=$dat[9];
		$detalleAcuerdo=$dat[10];
		$estadoAcuerdo=$dat[11];
		$idAcuerdo=$dat[12];
		$codEstadoAcuerdo=$dat[13];
		$nombreFuncionario=$dat[14];
		
		$sqlMontoVenta="select ifnull(sum(monto_venta),0) from ventas v where 
		v.cod_cliente='$codCliente' and 
		v.fecha_venta BETWEEN '$fechaIni' and '$fechaFin'";
		$respMontoVenta=mysql_query($sqlMontoVenta);
		$montoVentaSeguimiento=mysql_result($respMontoVenta,0,0);
		
		if($codEstadoAcuerdo==1){
			$chk="<input type='checkbox' name='codigo' value='$idAcuerdo'>";
		}else{
			$chk="";
		}
			
		echo "<tr><td>$idAcuerdo</td>
		<td>$nombreCliente</td><td>$promedioVentas</td><td>$porcCrecimiento</td>
		<td><span class='textograndenegro'>$montoObjetivo</span></td><td>$porcRebate</td><td>$montoRebate</td>
		<td>$nroMeses</td><td>$fechaIni</td><td>$fechaFin</td><td><span class='textogranderojo'>$montoVentaSeguimiento</span></td>
		<td>$detalleAcuerdo</td><td>$nombreFuncionario</td><td>$estadoAcuerdo</td>	
		<td><a href='#' onClick='ShowAprobar($idAcuerdo);'><img src='imagenes/enter.png' width='40' title='Aprobar/Rechazar'></a></td>
		</tr>";
	}
	echo "</table></center><br>";
	
	echo "</form>";
?>

<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Aprobar / Rechazar<br>Acuerdos Comerciales</h2>
		<table align='center' class='texto'>
			<tr>
				<td>Observaciones</td>
				<td>
				<input type='text' name='obsAprobar' id="obsAprobar" class='texto' size='40'>
				</td>
			</tr>			
		</table>	
		<center>
			<input type='button' class="boton" value='Aprobar' onClick="ajaxAprobarRechazar(this.form,1)">
			<input type='button' class="boton2" value='Rechazar' onClick="ajaxAprobarRechazar(this.form,2)">
			<input type='button' class="boton2" value='Cancelar' onClick="HiddenAprobar();">
			
		</center>
	</div>
</div>

        <script type='text/javascript' language='javascript'>
        </script>
        <div id="pnldlgfrm"></div>
        <div id="pnldlgSN"></div>
        <div id="pnldlgAC"></div>
        <div id="pnldlgA1"></div>
        <div id="pnldlgA2"></div>
        <div id="pnldlgA3"></div>
        <div id="pnldlgArespSvr"></div>
        <div id="pnldlggeneral"></div>
        <div id="pnldlgenespera"></div>
