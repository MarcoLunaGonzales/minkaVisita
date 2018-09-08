<?php
echo "<script language='Javascript'>
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
	
	echo "<h1>Acuerdos Comerciales</h1>";
	
	$sql="select a.cod_cliente, (select c.nombre_cliente from clientes c where c.cod_cliente=a.cod_cliente) as cliente,
		a.promedio_ventas, a.porcentaje_crecimiento, a.monto_objetivo, a.porcentaje_rebate, a.monto_rebate, a.numero_meses, 
		DATE_FORMAT(a.fecha_inicio, '%d/%m/%Y'), DATE_FORMAT(a.fecha_final, '%d/%m/%Y'), a.detalle,
		(select e.nombre_estadoacuerdo from estados_acuerdoscom e where e.cod_estadoacuerdo=a.cod_estadoacuerdo) as estado,
		a.id_acuerdos, a.cod_estadoacuerdo
		from acuerdos_comerciales a
		where a.cod_funcionario='$globalUsuario'";
	//echo $sql;	
	$resp=mysql_query($sql);
	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Cliente</th><th>Promedio Venta</th><th>% Crec.</th><th>Monto Obj.</th>
	<th>% Rebate</th><th>Monto Rebate</th><th># Meses</th><th>Inicio</th><th>Fin</th><th>MontoSeguimiento</th><th>Detalle</th><th>Estado</th></tr>";
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
			
		echo "<tr><td>$chk</td>
		<td>$nombreCliente</td><td>$promedioVentas</td><td>$porcCrecimiento</td>
		<td><span class='textograndenegro'>$montoObjetivo</span></td><td>$porcRebate</td><td>$montoRebate</td>
		<td>$nroMeses</td><td>$fechaIni</td><td>$fechaFin</td><td><span class='textogranderojo'>$montoVentaSeguimiento</span></td>
		<td>$detalleAcuerdo</td><td>$estadoAcuerdo</td>	
		</tr>";
	}
	echo "</table></center><br>";

	echo "<div class='divBotones'>";
	echo "<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>";
	echo "</div>";	
	
	echo "</form>";
	
?>