<style type="text/css" media="all">
.celdatitulo {
    border-top-style: solid;
    border-top-width: 1px;
    border-top-color: #afafaf;
    border-right-style: solid;
    border-right-width: 1px;
    border-right-color: #afafaf;
    padding-left: 2px;
    padding-right: 2px;
    white-space: nowrap;
}
.celda1 {
    border-left-style: solid;
    border-left-width: 1px;
    border-left-color: #afafaf;
}
.celda {
    border-top-style: solid;
    border-top-width: 1px;
    border-top-color: #afafaf;
    border-right-style: solid;
    border-right-width: 1px;
    border-right-color: #afafaf;
    padding-left: 2px;
    padding-right: 2px;
    white-space: nowrap;
}
.celdatotal {
    border-top-style: solid;
    border-top-width: 1px;
    border-top-color: #afafaf;
    border-right-style: solid;
    border-right-width: 1px;
    border-right-color: #afafaf;
    border-bottom-style: solid;
    border-bottom-width: 1px;
    border-bottom-color: #afafaf;
    padding-left: 2px;
    padding-right: 2px;
}
.registro {
    border-right-style: solid;
    border-right-width: 1px;
    border-right-color: #afafaf;
}
#enviounavez { display: none }
#loader { display: none }
</style>
<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
<script type='text/javascript' language='javascript'>
	$(document).ready(function(){
		$("#enviounavez").click(function(){
			alert("Ya solicito el guardado una vez, sus datos est&aacute;n siendo procesados. Espere por favor.")
		})
	})
</script>
<script type='text/javascript' language='javascript'>
function nuevoAjax()
{   var xmlhttp=false;
    try {
        xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (E) {
            xmlhttp = false;
        }
    }
    if(!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

function envia_campos(f)
{


    var i, indice;
    vector_campos=new Array();
    var var_incompleta;
    indice=1;
    var valor, valor_planificado;
    var global_linea;
    global_linea=f.global_linea.value;
    var comp='valor_maximo';
	var comp2='totalTmp';
	var comp3='porciento';
    var suma_producto=0;
    for(i=1;i<=f.length-1;i++)
    {   if(f.elements[i].name.indexOf(comp)!=-1)
        {   //alert(suma_producto);
            //alert(f.elements[i].value);
            if(suma_producto>f.elements[i].value)
            {   alert('No puede sacar cantidades superiores a las existentes en almacen. Item: '+f.elements[i].id);
				//alert(suma_producto+" "+f.elements[i].value);
                return(false);
            }
            suma_producto=0;
        }
        if(f.elements[i].type=='text' && f.elements[i].value!='' && f.elements[i].name.indexOf(comp2)==-1 && f.elements[i].name.indexOf(comp3)==-1)
        {   if(valor>valor_planificado)
            {   alert('No se pueden sacar cantidades mayores a las planificadas');
                //alert(valor+','+valor_planificado);
                f.elements[i].focus();
                return(false);
            }
            var_incompleta=f.elements[i].name;
            var_completa=f.elements[i].name+f.elements[i].value;
            vector_campos[indice]=var_completa;
            valor=f.elements[i].value*1;
            valor_planificado=f.elements[i-1].value*1;
            indice++;
			//alert(f.elements[i].id+" "+valor);
            suma_producto=suma_producto+valor;
        }
    }
    var dehabilitado_boton = document.getElementById("deshabilitado");
    var enviounavez = document.getElementById("enviounavez");
    var loader = document.getElementById("loader");
    dehabilitado_boton.style.display = 'none'
    enviounavez.style.display = 'block'
    loader.style.display = 'block'
    ajax=nuevoAjax();
    ajax.open('POST', 'guarda_registro_distribucionlineasterritorios.php');
    ajax.onreadystatechange=function()
    {
       if(ajax.readyState==4) {
            //div_contenido.innerHTML=ajax.responseText;
	  loader.style.display = 'none'
            alert('Los datos se insertaron satisfactoriamente');
            location.href='registro_distribucion_lineasterritorios1.php?global_linea=$global_linea';
        }
    }
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
    ajax.send('vector_campos='+vector_campos+'&global_linea='+global_linea);
    //location.href='guarda_registro_distribucionlineasterritorios.php?vector_campos='+vector_campos+'&global_linea='+global_linea+'';
}
function cerear(f, cod_prod)
{   for(i=1;i<=f.length-1;i++)
    {   if(f.elements[i].name.indexOf(cod_prod)!=-1)
        {   f.elements[i].value=0;
        }
    }
}
function soloNumeros(evt) {
    var keyPressed = (evt.which) ? evt.which : event.keyCode
    return !(keyPressed > 31 && (keyPressed < 48 || keyPressed > 57));
}
function suma(fil) {
    var tagTotalCols=document.getElementById("totalcols"+fil);
    var totalCols=parseInt(tagTotalCols.value);
    var cont=0, sw=0;
    for(var i=1;i<=totalCols;i++) {
        var tagCel=document.getElementById("cel"+fil+"_"+i);
        if(tagCel!=null) {
            var val=tagCel.value;
            if(isNaN(val) || val=="") {
                sw=1;
            } else {
                cont=cont+parseInt(val);
            }
        }
    }
    var tagTotalTmp=document.getElementById("totalTmp"+fil);
    if(sw==1) {
        tagTotalTmp.style.background='#eeee00';
    } else {
        tagTotalTmp.style.background='#ffffff';
    }
    tagTotalTmp.value=cont;
}
function generar(fil) {
    var tagCantP=document.getElementById("cantP"+fil); var cantP=parseInt(tagCantP.value);
    var tagCantD=document.getElementById("cantD"+fil); var cantD=parseInt(tagCantD.value);
    var tagCantR=document.getElementById("cantR"+fil); var cantR=parseInt(tagCantR.value);
    var tagPorciento=document.getElementById("porciento"+fil);
    var val=tagPorciento.value;
    var pociento=0;
    if(isNaN(val) || val=="") {pociento=0;} else {pociento=parseInt(val);}
    var tagTotalCols=document.getElementById("totalcols"+fil);
    var totalCols=parseInt(tagTotalCols.value);
    var contColText=0;
    for(var i=1;i<=totalCols;i++) {
        var tagCel=document.getElementById("cel"+fil+"_"+i);
        if(tagCel!=null) {
            contColText=contColText+1;
        }
    }
    for(var i=1;i<=totalCols;i++) {
        var tagCel=document.getElementById("cel"+fil+"_"+i);
        if(tagCel!=null) {
            //
            var tagCelP=document.getElementById("celP"+fil+"_"+i);
            var tagCelD=document.getElementById("celD"+fil+"_"+i);
            cantP=parseInt(tagCelP.value);
            cantD=parseInt(tagCelD.value);
            var valor=( (cantP-cantD)*pociento/100 );
            valor=parseInt(valor);
            tagCel.value=valor;
            //
        }
    }
    var tagTotalTmp=document.getElementById("totalTmp"+fil);
    tagTotalTmp.style.background='#ffffff';
    suma(fil);
}
</script>
<?php
echo "
";
require("conexion.inc");
if($global_usuario==1052)
{   require("estilos_gerencia.inc");
}
else
{   require("estilos_inicio_adm.inc");
}

echo "<form name='form1' onSubmit='return false'>";
echo "<input type='hidden' name='url_guardar' value=''>";
$sql_nombrelinea="select nombre_linea from lineas where codigo_linea='$global_linea'";
$resp_nombrelinea=mysql_query($sql_nombrelinea);
$dat_nombrelinea=mysql_fetch_array($resp_nombrelinea);
$nombre_linea=$dat_nombrelinea[0];
$sql_nombrelinea_todas="select nombre_linea,codigo_linea from lineas where codigo_linea in (1031,1032,1034,1035)";
$resp_nombrelinea_todas=mysql_query($sql_nombrelinea_todas);
while ($row_l = mysql_fetch_array($resp_nombrelinea_todas)) {
    $aa .= "<a href='registro_distribucion_lineasterritorios11.php?global_linea=$row_l[1]'>$row_l[0]</a> ";
}

echo "<input type='hidden' name='global_linea' value='$global_linea'>";
echo "<center><table border='0' class='textotit'><tr><th>Distribucion de Muestras x Linea Gestion: $global_gestion_distribucion Ciclo: $global_ciclo_distribucion L&#237;nea: $nombre_linea</th></tr></table></center><br>";
echo "<center>$aa</center>";
$sql_territorios="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
$resp_territorios=mysql_query($sql_territorios);
echo "<table border='0' class='texto' cellspacing='0' align='center'>";
$estilo="";
while($dat_territorios=mysql_fetch_array($resp_territorios))
{   $cod_territorio=$dat_territorios[0];
    if($estilo=="")
        $estilo="class='celdatotal celda1'";
    else
        $estilo="class='celdatotal'";
    echo "<th $estilo colspan='3'><a href='registro_distribucion_visitadores.php?territorio=$cod_territorio&global_linea=$global_linea'>$dat_territorios[1]</a></th>";
}
echo "</table><br>";
$sql_productos="select codigo, descripcion, presentacion from muestras_medicas order by descripcion";
$resp_productos=mysql_query($sql_productos);
$indice_productos=0;
while($dat_productos=mysql_fetch_array($resp_productos))
{   $indice_productos++;
    $productos[$indice_productos][1]=$dat_productos[0];
    $productos[$indice_productos][2]="$dat_productos[1] $dat_productos[2]";
}
$sql_productos="select codigo_material, descripcion_material from material_apoyo order by descripcion_material";
$resp_productos=mysql_query($sql_productos);
while($dat_productos=mysql_fetch_array($resp_productos))
{   $indice_productos++;
    $productos[$indice_productos][1]=$dat_productos[0];
    $productos[$indice_productos][2]="$dat_productos[1]";
}
$cad_3columns="";
//$cad_3columns="<table border=1 class='textomini' width='100%'><tr><td width='30px'>CP</td><td width='30px'>CD</td><td width='30px'>CR</td></tr></table>";
echo "<table border='0' class='texto' cellspacing='0' width='1600 px'>";
echo "<tr>";
echo "<th class='celda celda1' rowspan='2' style='width: 300px;'>Producto</th>";
$sql_territorios="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
$resp_territorios=mysql_query($sql_territorios);
$cadColumnasCantidad="";
while($dat_territorios=mysql_fetch_array($resp_territorios))
{   //echo "<th colspan='3'>$dat_territorios[1] $cad_3columns</th>";
    echo "<th class='celda' colspan='3'>$dat_territorios[1]</th>";
    $cadColumnasCantidad.="<th class='celda'>CP</th><th class='celda'>CD</th><th class='celda'>CR</th>";
}
echo "<th class='celda' rowspan='2'>Total<br>Planificado</th>";
echo "<th class='celda' rowspan='2'>Total<br>Distribuido</th>";
echo "<th class='celda' rowspan='2'>Existencias<br>Almacen<br>Central</th>";
echo "<th class='celda' rowspan='2'>Cant. Dist. Total</th>";
echo "<th class='celda' rowspan='2'>Estado</th>";
echo "</tr>";
echo "<tr>$cadColumnasCantidad</tr>";

for($j=1;$j<=$indice_productos;$j++)
//for($j=1;$j<=30;$j++)
{   $codigo_producto=$productos[$j][1];
    $nombre_producto=$productos[$j][2];
    $cadena_producto="";
    $cadena_producto.="<tr>";
    $cadena_producto.="<td class='celda celda1'>";
    $cadena_producto.="<table border='0' cellspacing='0'>";
    $cadena_producto.="<tr><td class='texto' colspan='2'>$nombre_producto<td></tr>";
    $cadena_producto.="<tr><td>";
    $cadena_producto.="<span style='white-space: nowrap;'><input type='text' class='texto' value='0' id='porciento$j' name='porciento$j' onkeypress='return soloNumeros(event)' style='text-align: right;' size='1'><a href='javascript:generar($j);'><img src='imagenes/porciento.png' style='border: 0px;' title='Generar porcentaje en cada campo'/></a></span>&nbsp;";
    $cadena_producto.="</td><td>";
    $cadena_producto.="<a href=javascript:cerear(form1,'$codigo_producto')><img src='imagenes/cerear.png' style='border: 0px;' title='Cerear campos'/></a>";
    $cadena_producto.="</td></tr>";
    $cadena_producto.="</table></td>";
    $bandera=0; $swAux=0;
    $suma_cant_distribuida;
    $suma_cant_producto=0;
    $sql_territorios="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
    $resp_territorios=mysql_query($sql_territorios);
    $col=0;
    while($dat_territorios=mysql_fetch_array($resp_territorios))
    {   //echo "hola";
        $col++;
        $cod_territorio=$dat_territorios[0];
        $nombre_territorio=$dat_territorios[1];
        $sql_lineaterritorio_producto="
            select sum(cantidad_planificada), sum(cantidad_distribuida) from distribucion_productos_visitadores
            where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and territorio='$cod_territorio' and
            codigo_linea='$global_linea' and codigo_producto='$codigo_producto'";
        //echo $sql_lineaterritorio_producto."<br>";
        $resp_lineaterritorio_producto=mysql_query($sql_lineaterritorio_producto);
        $dat_lineaterritorio_producto=mysql_fetch_array($resp_lineaterritorio_producto);
        $cantidad_planificada=$dat_lineaterritorio_producto[0];
        $cantidad_distribuida=$dat_lineaterritorio_producto[1];
        $valor_maximo_asacar=$cantidad_planificada-$cantidad_distribuida;
        //echo $cantidad_planificada;
        $cantidad_a_sacar=$cantidad_planificada-$cantidad_distribuida;
        if($cantidad_planificada>0)
        {   $bandera=1;
            $cadena_txt="<input type='hidden' name='' value='$valor_maximo_asacar'>
            <input type='text' class='texto' id='cel$j"."_$col' value='$cantidad_a_sacar' name='$cod_territorio|$codigo_producto|' onkeypress='return soloNumeros(event)' onkeyup='suma($j);' style='text-align: right;' size='3'>";
            //
            if($cantidad_a_sacar<>0)
            {   $swAux=1;
            }
            //
        }
        if($cantidad_planificada<=0)
        {   $cadena_txt="";
            $cantidad_planificada="";
            $cantidad_distribuida="";
        }
        if($cantidad_a_sacar==0)
        {   //$cadena_txt="<input type='hidden' name='' value='$valor_maximo_asacar'><input type='text' disabled='true' class='texto' value='' name='$cod_territorio|$codigo_producto|' size='3'>";
            $cadena_txt="<input type='hidden' name='' value='$valor_maximo_asacar'><input type='hidden' disabled='true' class='texto' value='' name='$cod_territorio|$codigo_producto|' size='3'>";
        }
        $cadena_producto.="<td class='celda' bgcolor='#ffffcc' align='right' width='40px' title='$nombre_territorio $nombre_producto'>$cantidad_planificada&nbsp;<input type='hidden' id='celP$j"."_$col' value='$cantidad_planificada'></td>";
        $cadena_producto.="<td class='celda' align='right' bgcolor='#66ffcc' width='40px' title='$nombre_territorio $nombre_producto'>$cantidad_distribuida&nbsp;<input type='hidden' id='celD$j"."_$col' value='$cantidad_distribuida'></td>";
        $cadena_producto.="<td class='celda' bgcolor='#ffcccc' width='40px' title='$nombre_territorio $nombre_producto'>&nbsp;$cadena_txt</td>";
        $suma_cant_producto=$suma_cant_producto+$cantidad_planificada;
        $suma_cant_distribuida=$suma_cant_distribuida+$cantidad_distribuida;
    }
    if($bandera==1)
    {   $sql_stock="
            select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
            where id.cod_material='$codigo_producto' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and i.cod_almacen='1000'";
        $resp_stock=mysql_query($sql_stock);
        $dat_stock=mysql_fetch_array($resp_stock);
        $stock_real=$dat_stock[0];
        if($stock_real=="")
        {   $stock_real=0;
        }
        //vemos cuanto se saco del item para el ciclo
        /*$sql_cant_sacada="select sum(cantidad_distribuida), sum(cantidad_sacadaalmacen) from distribucion_productos_visitadores where
        codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and codigo_linea='$global_linea' and
        codigo_producto='$codigo_producto'";*/
        $sql_cant_sacada="
            select sum(cantidad_distribuida), sum(cantidad_sacadaalmacen) from distribucion_productos_visitadores where
            codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and
            codigo_producto='$codigo_producto'";

        $resp_cant_sacada=mysql_query($sql_cant_sacada);
        $dat_cant_sacada=mysql_fetch_array($resp_cant_sacada);
        $cant_sacada_pais=$dat_cant_sacada[0];
        $cant_sacada_almacen=$dat_cant_sacada[1];

        //SACAMOS DE LA TABLA GRUPOS ESPECIALES
        $sql_cant_sacadaGrupo="
            select sum(cantidad_distribuida), sum(cantidad_sacadaalmacen) from distribucion_grupos_especiales where
            codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and
            codigo_producto='$codigo_producto'";

        $resp_cant_sacadaGrupo=mysql_query($sql_cant_sacadaGrupo);
        $dat_cant_sacadaGrupo=mysql_fetch_array($resp_cant_sacadaGrupo);
        $cant_sacada_paisGrupo=$dat_cant_sacadaGrupo[0];
        $cant_sacada_almacenGrupo=$dat_cant_sacadaGrupo[1];
        //FIN GRUPOS ESPECIALES

        $sql_cant_sacadaLinea="
            select sum(cantidad_distribuida), sum(cantidad_sacadaalmacen) from distribucion_productos_visitadores where
            codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and
            codigo_producto='$codigo_producto' and codigo_linea='$global_linea'";

        $resp_cant_sacadaLinea=mysql_query($sql_cant_sacadaLinea);
        $dat_cant_sacada=mysql_fetch_array($resp_cant_sacadaLinea);
        $cant_sacada_linea=$dat_cant_sacada[0];

        //esta variable es para validar cantidades por item
        $maximo_a_sacarporitem=$stock_real-$cant_sacada_pais+$cant_sacada_almacen;
        $maximo_a_sacarporitem=$maximo_a_sacarporitem-$cant_sacada_paisGrupo+$cant_sacada_almacenGrupo;
        //echo $maximo_a_sacarporitem."<br>";
        $img_estado="&nbsp;";
        if($cant_sacada_pais==$suma_cant_producto)
        {   $img_estado="<img src='imagenes/si.png'>";
        }
        if($cant_sacada_pais<$suma_cant_producto)
        {   $img_estado="<img src='imagenes/pendiente.png'>";
        }
        if($cant_sacada_pais==0)
        {   $img_estado="<img src='imagenes/no.png'>";
        }
        //stock disponible muestra la cantidad real menos la distribuida hasta el momento
        $stock_disponible=$stock_real-$cant_sacada_pais+$cant_sacada_almacen;
        //
        $cadAcumulador="&nbsp;";
        if($swAux==1)
        {   $numAux=$suma_cant_producto-$cant_sacada_linea;
            $cadAcumulador="<input type='text' id='totalTmp$j' readonly='readonly' value='$numAux' style='text-align: right; background: #ff4242;' size='3' name='totalTmp$j'><input type='hidden' id='totalcols$j' value='$col'>";
        }
        //
        $cadena_producto.="<td class='celda' align='right'>$suma_cant_producto<input type='hidden' id='cantP$j' value='$suma_cant_producto'/></td>";
        $cadena_producto.="<td class='celda' align='right'>$cant_sacada_linea<input type='hidden' id='cantD$j' value='$cant_sacada_linea'/></td>";
        $cadena_producto.="<td class='celda' align='right'>$stock_disponible<input type='hidden' id='cantR$j' value='$stock_disponible'/></td>";
        $cadena_producto.="<td class='celda' align='right'>$cadAcumulador</td>";
        $cadena_producto.="<td class='celda' align='center'>$img_estado</td></tr>";
        echo $cadena_producto;
        //echo "<input type='hidden' id='$nombre_producto' name='valor_maximo$i' value='$maximo_a_sacarporitem'>";
        echo "<input type='hidden' id='$nombre_producto' name='valor_maximo$j' value='$maximo_a_sacarporitem'>";
    }
}
echo "<tr><th class='celda celda1'>Totales Muestras:</th>";
$sql_territorios="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
$resp_territorios=mysql_query($sql_territorios);
while($dat_territorios=mysql_fetch_array($resp_territorios))
{   $cod_territorio=$dat_territorios[0];
    $nombre_territorio=$dat_territorios[1];
    $sql_totales="
        select sum(cantidad_planificada), sum(cantidad_distribuida) from distribucion_productos_visitadores
        where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and
        territorio='$cod_territorio' and codigo_linea='$global_linea' and grupo_salida=1";
    $resp_totales=mysql_query($sql_totales);
    $dat_totales=mysql_fetch_array($resp_totales);
    $total_territorio_planificado=$dat_totales[0];
    $total_territorio_distribuido=$dat_totales[1];
    echo "<th class='celda' title='Cant. Planificada: $nombre_territorio'>&nbsp;$total_territorio_planificado</th>
    <th class='celda' title='Cant. Distribuida: $nombre_territorio'>&nbsp;$total_territorio_distribuido</th><th class='celda'>&nbsp;</th>";
}
echo "<th class='celdatotal' colspan='5' rowspan='2'>&nbsp;</th>";
echo "</tr>";
echo "<tr>";
echo "<th class='celdatotal celda1'>Totales MA:</th>";
$sql_territorios="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
$resp_territorios=mysql_query($sql_territorios);
while($dat_territorios=mysql_fetch_array($resp_territorios))
{   $cod_territorio=$dat_territorios[0];
    $nombre_territorio=$dat_territorios[1];
    $sql_totales="
        select sum(cantidad_planificada), sum(cantidad_distribuida) from distribucion_productos_visitadores
        where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and
        territorio='$cod_territorio' and codigo_linea='$global_linea' and grupo_salida=2";
    $resp_totales=mysql_query($sql_totales);
    $dat_totales=mysql_fetch_array($resp_totales);
    $total_territorio_planificado=$dat_totales[0];
    $total_territorio_distribuido=$dat_totales[1];
    echo "<th class='celdatotal' title='Cant. Planificada: $nombre_territorio'>&nbsp;$total_territorio_planificado</th>
    <th class='celdatotal' title='Cant. Distribuida: $nombre_territorio'>&nbsp;$total_territorio_distribuido</th><th class='celdatotal'>&nbsp;</th>";
}
echo "</tr>";

echo "</table><br>";

echo "<table align='center'>";
echo "<tr><td><a href='navegador_lineas_distribucion.php'><img border='0' src='imagenes/back.png' width='40'></a></td></tr>";
echo "</table>";
echo "<center><input type='button' onClick='envia_campos(this.form)' value='Guardar' class='boton' id='deshabilitado'><div id='loader'><img src='imagenes/ajax-loader.gif' alt=''  />Procesando...</div></center>";
echo "<center><input type='button' value='Guardar' class='boton' id='enviounavez'></center>";
echo "<div id='div_contenido'></div>";
echo "</form>";

?>
<script type='text/javascript' language='javascript'>
    //
</script>
