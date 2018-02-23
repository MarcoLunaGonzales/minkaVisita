<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/

	echo "<script LANGUAGE='JavaScript'>
			function activa_select(f,i)
			{

				var indice_chk=(i*7)-1;
				var indice_sel=(i*7)-2;
				var indice_sel1=(i*7)-3;
				var indice_apoyo=(i*7)-4;
				var indice_muestra=(i*7)-5;
				if(f.elements[indice_chk].checked==true)
				{
					if(f.elements[indice_apoyo].value!=0)
					{	f.elements[indice_sel].disabled=false;
					}
					f.elements[indice_sel1].disabled=false;
				}
				else
				{
					f.elements[indice_sel].disabled=true;
					f.elements[indice_sel1].disabled=true;
				}
			}
			function envia_form(f)
			{	f.submit();
			}
			function guarda_form(f)
			{	var indice,valores,indice_extra,num_extras;
				indice=0;
				indice_extra=0;
				var constancia=new Array();
				var muestras=new Array();
				var material=new Array();
				var cantidad_muestras=new Array();
				var cantidad_apoyo=new Array();
				var mat_extra=new Array();
				var prod_extra=new Array();
				var cant_entregada_extra=new Array();
				var cant_entregada_apoyo_extra=new Array();
				var cantidad_extraentregada=new Array();
				var cantidad_extraapoyo=new Array();
				for(j=0;j<=f.length-1;j++)
				{
					if(f.elements[j].name.indexOf('constancia')!=-1)
					{	if(f.elements[j].checked==true)
						{	constancia[indice-1]=1;
						}
						else
						{	constancia[indice-1]=0;
						}
					}
					if(f.elements[j].name.indexOf('cantidad_extraentregada')!=-1)
					{	cantidad_extraentregada[indice]=f.elements[j].value;
					}
					if(f.elements[j].name.indexOf('cantidad_extraapoyo')!=-1)
					{	cantidad_extraapoyo[indice]=f.elements[j].value;
					}
					if(f.elements[j].name.indexOf('muestra')!=-1)
					{	muestras[indice]=f.elements[j].value;
					}
					if(f.elements[j].name.indexOf('material')!=-1)
					{	material[indice]=f.elements[j].value;
					}
					if(f.elements[j].name.indexOf('cantidad_entregada')!=-1)
					{	cantidad_muestras[indice]=f.elements[j].value;
					}
					if(f.elements[j].name.indexOf('cantidad_apoyo')!=-1)
					{	cantidad_apoyo[indice]=f.elements[j].value;
						indice++;
					}
					if(f.elements[j].name.indexOf('prod_extra')!=-1)
					{	prod_extra[indice_extra]=f.elements[j].value;
					}
					if(f.elements[j].name.indexOf('mat_extra')!=-1)
					{	mat_extra[indice_extra]=f.elements[j].value;
					}
					if(f.elements[j].name.indexOf('cant_entregada_extra')!=-1)
					{	cant_entregada_extra[indice_extra]=f.elements[j].value;
					}
					if(f.elements[j].name.indexOf('cant_entregada_apoyo_extra')!=-1)
					{	cant_entregada_apoyo_extra[indice_extra]=f.elements[j].value;
						indice_extra++;
					}

				}
				valores=f.valores.value;
				num_extras=f.muestras_extra.value;
				location.href='guarda_registro_visita.php?constancia='+constancia+'&muestras='+muestras+'&material='+material+'&cantidad_muestras='+cantidad_muestras+'&cantidad_apoyo='+cantidad_apoyo+'&prod_extra='+prod_extra+'&mat_extra='+mat_extra+'&cant_entregada_extra='+cant_entregada_extra+'&cant_entregada_apoyo_extra='+cant_entregada_apoyo_extra+'&cantidad_extraentregada='+cantidad_extraentregada+'&cantidad_extraapoyo='+cantidad_extraapoyo+'&valores='+valores+'&num_extras='+num_extras+'';
			}
		</script>";
require("conexion.inc");
require("estilos_visitador_sincab.inc");
//sacamos el ciclo activo de la linea
$sql_ciclo="select cod_ciclo from ciclos where estado='Activo' and codigo_linea='$global_linea'";
$resp_ciclo=mysql_query($sql_ciclo);
$dat_ciclo=mysql_fetch_array($resp_ciclo);
$ciclo_activo=$dat_ciclo[0];
//hasta aqui tenemos el ciclo en $ciclo_activo
$vector=explode("-",$cod_contacto);
$contacto=$vector[0];
$orden_visita=$vector[1];
$visitador=$vector[2];
//$agencia_parrilla=$vector[3];
	//formamos los encabezados nombre medico, especialidad turno
	$sql="select c.turno, m.ap_pat_med, m.ap_mat_med, m.nom_med, dm.direccion, cd.categoria_med, cd.cod_especialidad, cd.orden_visita, c.cod_contacto, cd.estado, m.cod_med
		from rutero c, rutero_detalle cd, medicos m, direcciones_medicos dm
			where c.cod_ciclo='$ciclo_activo' and c.cod_visitador=$global_visitador and m.cod_med=cd.cod_med and
				dm.numero_direccion=cd.cod_zona and cd.cod_med=dm.cod_med and c.cod_contacto='$contacto' and c.cod_contacto=cd.cod_contacto and cd.orden_visita='$orden_visita' order by c.turno,cd.orden_visita";
	$resp=mysql_query($sql);
	$dat_enc=mysql_fetch_array($resp);
	$enc_nombre_medico="$dat_enc[1] $dat_enc[2] $dat_enc[3]";
	$enc_turno=$dat_enc[0];
	$enc_categoria=$dat_enc[5];
	$enc_especialidad=$dat_enc[6];
	//$fecha=$dat_enc[7];
	$cod_med=$dat_enc[10];
	//fin encabezados
$sql_nombre_dia="select dia_contacto from rutero where cod_contacto='$contacto'";
	$resp_nombre_dia=mysql_query($sql_nombre_dia);
	$dat_nombre_dia=mysql_fetch_array($resp_nombre_dia);
	$nombre_de_dia=$dat_nombre_dia[0];
$sql="select cod_especialidad, categoria_med, estado from rutero_detalle where cod_contacto=$contacto and orden_visita=$orden_visita";
$res=mysql_query($sql);
$dat=mysql_fetch_array($res);
$especialidad=$dat[0];
$categoria=$dat[1];
$estado_pri=$dat[2];
echo "<center><table border='0' class='textotit'><tr><td align='center'>Registro de Visita Médica<br>Medico: <strong>$enc_nombre_medico</strong> Especialidad: <strong>$enc_especialidad</strong> Categoria: <strong>$categoria</strong><br><strong>$nombre_de_dia</strong></td></tr></table></center><br>";
echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><td>Producto Objetivo</td><td bgcolor='#ffff99' width='10%'></td><td>&nbsp;</td><td>Producto Filtrado</td><td bgcolor='#ff7591' width='10%'></td><td>&nbsp;</td><td>Producto Extra</td><td bgcolor='#66ccff' width='10%'></td></table></center><br>";
$sql="select mm.descripcion, mm.presentacion, pd.cantidad_muestra, ma.descripcion_material,
			 pd.cantidad_material, pd.codigo_parrilla_especial, pd.prioridad,mm.codigo,ma.codigo_material
		from muestras_medicas mm, parrilla_detalle_especial pd, material_apoyo ma, parrilla_especial p
		where p.codigo_parrilla_especial=pd.codigo_parrilla_especial and
        	p.cod_especialidad='$especialidad' and p.agencia='$global_agencia'
        	and p.codigo_linea='$global_linea' and mm.codigo=pd.codigo_muestra and p.numero_visita='$visita'
			and ma.codigo_material=pd.codigo_material and p.cod_ciclo='$ciclo_activo'
			and p.codigo_gestion='$codigo_gestion' order by pd.prioridad";
$resp=mysql_query($sql);
$numero_registros=mysql_num_rows($resp);
if($numero_registros!=0)
{

	echo "<form name='form' name='principal' method='get' action=''>";
	echo "<center><table border='1' cellspacing='0' class='texto' width='100%'>";
	echo "<tr><th width='30%' class='textomini'>Muestra</th><th width='5%' class='textomini'>Cantidad</th><th width='11%' class='textomini'>Cantidad extra entregada</th><th class='textomini' width='30%'>Material de Apoyo</th><th class='textomini' width='5%'>Cantidad</th><th class='textomini' width='11%'>Cantidad extra entregada</th><th class='textomini' width='5%'>Entregado</th></tr>";
	$i=1;
	$constancia="constancia";
	$cantidad_extraentregada="cantidad_extraentregada";
	$cantidad_extraapoyo="cantidad_extraapoyo";
	while($dat=mysql_fetch_array($resp))
	{
		$muestra=$dat[0];
		$presentacion=$dat[1];
		$cantidad=$dat[2];
		$apoyo=$dat[3];
		$cant_apoyo=$dat[4];
		$parrilla=$dat[5];
		$prioridad=$dat[6];
		$cod_muestra=$dat[7];
		$codigo_material=$dat[8];
		$sql_negados="select * from muestras_negadas where cod_med='$cod_med' and codigo_muestra='$cod_muestra' and codigo_linea='$global_linea'";

		$res_negados=mysql_query($sql_negados);
		$num_negados=mysql_num_rows($res_negados);
		if($num_negados!=0)
		{	$fondo="#ff7591";}
		$sql_obj="select * from productos_objetivo where cod_med='$cod_med' and codigo_muestra='$cod_muestra' and codigo_linea='$global_linea'";
		$res_obj=mysql_query($sql_obj);
		$num_obj=mysql_num_rows($res_obj);
		if($num_obj!=0)
		{	$fondo="#ffff99";}
		echo "<input type='hidden' name='muestra$i' value='$cod_muestra'>";
		echo "<input type='hidden' name='material$i' value='$codigo_material'>";
		echo "<input type='hidden' name='cantidad_entregada$i' value='$cantidad'>";
		echo "<input type='hidden' name='cantidad_apoyo$i' value='$cant_apoyo'>";
		echo "<tr bgcolor='$fondo'><td>$muestra $presentacion </td><td align='center'>$cantidad</td>";
		$var_constancia="$constancia$i";
		$valor_constancia=$$var_constancia;
		if($valor_constancia==1)
		{	$var_cant_entregada="$cantidad_extraentregada$i";
			$valor_cant_entregada=$$var_cant_entregada;
			$var_cant_apoyo="$cantidad_extraapoyo$i";
			$valor_cant_apoyo=$$var_cant_apoyo;
			echo "<td align='center'><select name='cantidad_extraentregada$i' class='textomini'>";
		}
		else
		{	echo "<td align='center'><select name='cantidad_extraentregada$i' class='textomini' disabled='true'>";
		}
				echo "<option value=''></option>";
				for($j=1;$j<=10;$j++)
				{
					if($valor_cant_entregada==$j and $valor_constancia==1)
					{	echo "<option value='$j' selected>$j</option>";
					}
					else
					{	echo "<option value='$j'>$j</option>";
					}
				}
				echo "</td>";
		echo "<td>$apoyo</td><td align='center'>$cant_apoyo</td>";
		if($valor_constancia==1 and $codigo_material!=0)
		{	echo "<td align='center'><select name='cantidad_extraapoyo$i' class='textomini'>";
		}
		else
		{	echo "<td align='center'><select name='cantidad_extraapoyo$i' class='textomini' disabled='true'>";
		}
				echo "<option value=''></option>";
				for($j=1;$j<=10;$j++)
				{	if($valor_cant_apoyo==$j and $valor_constancia==1)
					{	echo "<option value='$j' selected>$j</option>";
					}
					else
					{	echo "<option value='$j'>$j</option>";
					}
				}
				echo "</td>";

		if($valor_constancia==1)
		{	echo "<td align='center'><input type=checkbox name='constancia$i' value='1' onClick='activa_select(this.form,$i)' checked></td></tr>";
		}
		else
		{	echo "<td align='center'><input type=checkbox name='constancia$i' value='1' onClick='activa_select(this.form,$i)'></td></tr>";
		}
		$i=$i+1;
		$fondo="";
	}
	echo "</table><br>";
	//aqui construimos las muestras extra
	echo "<table border='1' cellspacing='0' class='texto' width='100%' align='center'>";
	echo "<tr><th colspan='5'>Cantidad de muestras de productos extra-parrilla entregadas:&nbsp;&nbsp;";
	echo "<select name='muestras_extra' class='textomini' onChange='envia_form(this.form)'>";
	for($m=0;$m<=10;$m++)
	{	if($m==$muestras_extra)
		{	echo "<option value='$m' selected>$m</option>";
		}
		else
		{	echo "<option value='$m'>$m</option>";
		}
	}
	echo "</select>";
	echo "</th></tr>";
	echo "<tr><th class='textomini' width='38%'>Muestra</th><th class='textomini' width='11%'>Cantidad Entregada</th><th class='textomini' width='35%'>Material de Apoyo</th><th class='textomini' width='11%'>Cantidad Entregada</th><th class='textomini' width='5%'>&nbsp;</th></tr>";
	$prod_extra="prod_extra";
	$cant_entregada_extra="cant_entregada_extra";
	$material_extra="mat_extra";
	$cant_entregada_apoyo_extra="cant_entregada_apoyo_extra";
	for($filas_extra=1;$filas_extra<=$muestras_extra;$filas_extra++)
	{	$var_prod_extra="$prod_extra$filas_extra";$valor_prod_extra=$$var_prod_extra;
		$var_cant_ent_extra="$cant_entregada_extra$filas_extra";$valor_cant_ent_extra=$$var_cant_ent_extra;
		$var_material_extra="$mat_extra$filas_extra";$valor_material_extra=$$var_material_extra;
		$var_cant_apoyo_extra="$cant_entregada_apoyo_extra$filas_extra";$valor_can_apoyo_extra=$$var_cant_apoyo_extra;
		echo "<tr bgcolor='#66ccff'><td>";
		$sql_prod_extra="select codigo, descripcion, presentacion from muestras_medicas where estado=1 order by descripcion, presentacion";
		$resp_prod_extra=mysql_query($sql_prod_extra);
		echo "<select name='prod_extra$filas_extra' class='textomini'>";
		while($dat_extra=mysql_fetch_array($resp_prod_extra))
		{	$codigo_material_extra=$dat_extra[0];
			$nombre_material_extra="$dat_extra[1] $dat_extra[2]";
			if($valor_prod_extra==$codigo_material_extra)
			{	echo "<option value='$codigo_material_extra' selected>$nombre_material_extra</option>";
			}
			else
			{	echo "<option value='$codigo_material_extra'>$nombre_material_extra</option>";
			}
		}
		echo "</select></td>";
		echo "<td align='center'><select name='cant_entregada_extra$filas_extra' class='textomini'>";
		for($ce=0;$ce<=10;$ce++)
		{	if($valor_cant_ent_extra==$ce)
			{	echo "<option value='$ce' selected>$ce</option>";
			}
			else
			{	echo "<option value='$ce'>$ce</option>";
			}
		}
		echo "</select></td>";
		echo "<td><select name='mat_extra$filas_extra' class='textomini'>";
		$sql_apoyo_extra="select codigo_material, descripcion_material from material_apoyo where estado='Activo'";
		$resp_apoyo_extra=mysql_query($sql_apoyo_extra);
		while($dat_apoyo_extra=mysql_fetch_array($resp_apoyo_extra))
		{	$codigo_material_extra=$dat_apoyo_extra[0];
			$descripcion_material_extra=$dat_apoyo_extra[1];
			if($valor_material_extra==$codigo_material_extra)
			{	echo "<option value='$codigo_material_extra' selected>$descripcion_material_extra</option>";
			}
			else
			{	echo "<option value='$codigo_material_extra'>$descripcion_material_extra</option>";
			}
		}
		echo "</select></td>";
		echo "<td align='center'><select name='cant_entregada_apoyo_extra$filas_extra' class='textomini'>";
		for($cae=0;$cae<=10;$cae++)
		{	if($valor_can_apoyo_extra==$cae)
			{	echo "<option value='$cae' selected>$cae</option>";
			}
			else
			{	echo "<option value='$cae'>$cae</option>";
			}
		}
		echo "</select></td><td>&nbsp;</td></tr>";

	}
	echo "</table><br>";
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1);'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	echo "<table border='0'>";
	echo "<input type='hidden' name='valores' value='$contacto-$orden_visita-$parrilla-$i'>";
	echo "<input type='hidden' name='cod_contacto' value='$cod_contacto'>";
	echo "<input type='hidden' name='visita' value='$visita'>";
	echo "<tr><th><input type='button' OnClick='guarda_form(this.form)' class='boton' value='Guardar'></th></tr>";
	echo "</form>";
	echo "</table></center>";
}
/*else
{
	echo "<script language='Javascript'>
			alert('No existe ninguna parrilla definida para la especialidad $especialidad y la categoria $categoria');
			window.close();
	</script>";
}*/
?>