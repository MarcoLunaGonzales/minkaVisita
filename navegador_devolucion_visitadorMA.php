<?php
require("conexion.inc");
require("estilos_visitador.inc");
require("funcion_nombres.php");
?>
<script type="text/javascript" src="jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript">
function cargarPnlLjn(selEsp,selDest,prog,params,prefijo,sufijo,funTrgAnt,funTrgDes) {
    if(params!="")params=params+"&";params=params+"rnd="+(Math.random()*999999999999999999);
    $(selEsp).html(function() {funTrgAnt();
        $.get(prog, params, function(informacion) {
          $(selEsp).html("");$(selDest).html(""+prefijo+""+informacion+""+sufijo+"");funTrgDes();
        });
        return "<span class='texto'>Cargando x...</span>";
    });
}
/* procedimientos ajax */
function prvt_getXMLHhttp()
   {var xmlhttp=false;
    try{xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
       }
    catch(e)
       {try{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
           }
        catch(e)
           {xmlhttp=false;
           }
       }
    if (!xmlhttp && typeof XMLHttpRequest!="undefined")
       {xmlhttp=new XMLHttpRequest();
       }
    return xmlhttp;
   }
function actualizarAjax(selDes,prog,params)//selDes='idCmb', prog='programa.jsp', params='p1=v1&p2=v2&...'
   {var ajax=prvt_getXMLHhttp();
    if(params!="") params=params+"&";
    params=params+"rnd="+(Math.random()*999999999999999999);
    var url=prog+"?"+params;
    ajax.open("GET", url, true);
    //ajax.open("POST", url, true);
    ajax.onreadystatechange = function()
       {if(ajax.readyState==1)
           {}
        else if(ajax.readyState==4)
           {if(ajax.status==200)
               {var tagDivGrp=document.getElementById(selDes);
                tagDivGrp.innerHTML=ajax.responseText;
               }
           }
       }
    ajax.send(null);
   }
/* nuevos procedimientos ajax */
function cargarFilaMA(fil)
   {var c=0,n=fil;
    for(i=1;i<n;i++)
       {var tagAux=document.getElementById('maEval'+i);
        if(tagAux===null)
           {//alert('no'+i+':');
           }
        else
           {c=c+1;
           }
       }
    if(c<5)
       {
        var cad="";
        var nTotNo=parseInt(document.getElementById("nrMA").value);
        for(var i=1;i<=nTotNo;i++)
           {var codAux=parseInt(document.getElementById("rMA"+i).value);
            if(cad!="") {cad=cad+",";}
            cad=cad+codAux;
           }
        $("#nregMA"+fil).html("<td>Cargando ...</td>");
        cargarPnlLjn("#nregMA"+fil, "#nregMA"+fil, "ajaxDevolucionMA.php", "nregMA="+fil+"&lstMAno="+cad, "", "", function(){}, function(){prvtAdiOpcNewMA();});
       }
    else
       {alert('Solo puede adicionar hasta 10 elementos extras.');
       }
   }
function prvtAdiOpcNewMA()
   {var tagNfilMA=document.getElementById('nregsMA');
    var n=parseInt(tagNfilMA.value);
    n=n+1; tagNfilMA.value=n;
    $("#idtabMA").append("<tr id='nregMA"+n+"'><td colspan='3'><a href='javascript:cargarFilaMA("+n+")'>Adicionar MA</a></td></tr>");
   }
function eliFilaMA(fil)
   {var tagFilMA=document.getElementById("nregMA"+parseInt(fil));
    var tagTab=tagFilMA.parentNode;
    tagTab.removeChild(tagFilMA);
   }
</script>
<script type="text/javascript">
            $(document).ready(function(){
                $("#guar").click(function(event) {
                    if( !confirm('Esta seguro que desea guardar los datos del furmulario') ) { 
                        event.preventDefault(); 
                    }
                });
            })
        </script>
<?php
$ciclo_global=$cod_ciclo;
$global_gestion=$cod_gestion;
$nombreGestion=nombreGestion($global_gestion);

echo "<form action='guardar_devolucion_visitadorMA.php' method='get'>";
echo "<center><table border='0' class='textotit'><tr><th>Devolucion de Material de Apoyo<br>
Gestion: $nombreGestion Ciclo: $ciclo_global</th></tr></table></center><br>";

echo "<input type='hidden' name='cod_ciclo' value='$ciclo_global'>";
echo "<input type='hidden' name='cod_gestion' value='$global_gestion'>";

$sqlVerificacion="
    select * from devoluciones_ciclo where codigo_ciclo=$ciclo_global and codigo_gestion=$global_gestion
    and codigo_visitador=$global_visitador and tipo_devolucion=2";

$respVerificacion=mysql_query($sqlVerificacion);
$filasVerificacion=mysql_num_rows($respVerificacion);

echo "<center><table border='1' class='texto' cellspacing='0'>";
//echo "<tr><th>Producto</th><th>Cantidad Recibida</th><th>Cantidad Entregada</th><th>Cantidad a Devolver</th><th>Cantidad Efectiva a Devolver</th></tr>";

echo "<tr><th>&nbsp;</th><th>Producto</th><th>Cantidad Efectiva a Devolver</th></tr>";

$sqlMuestrasEnt="
    select distinct (m.codigo_material), m.descripcion_material, sum(sd.cantidad_unitaria)
    from salida_detalle_visitador sv, salida_detalle_almacenes sd, material_apoyo m
    where sv.cod_salida_almacen = sd.cod_salida_almacen and m.codigo_material = sd.cod_material and sv.codigo_ciclo = '$ciclo_global' and
    sv.codigo_gestion = '$global_gestion' and sv.codigo_funcionario in ('$global_visitador')
    and m.codigo_material<>0 group by m.codigo_material order by 2";
$respMuestrasEnt=mysql_query($sqlMuestrasEnt);
$cont=0;
while($datMuestrasEnt=mysql_fetch_array($respMuestrasEnt)) {
    $codMaterial=$datMuestrasEnt[0];
    $cantidadRecibida=$datMuestrasEnt[2];
    $nombreMaterial=$datMuestrasEnt[1];
    $sqlMuestrasDesp="
        select (sum(rd.CANT_MAT_ENT) + sum(rd.CANT_MAT_EXTENT)) as cantidad from reg_visita_cab r,
        reg_visita_detalle rd where r.COD_REG_VISITA = rd.COD_REG_VISITA and r.COD_CICLO = '$ciclo_global' and
        r.COD_GESTION = '$global_gestion' and r.COD_VISITADOR = '$global_visitador' and rd.COD_MATERIAL = '$codMaterial'";
    $respMuestrasDesp=mysql_query($sqlMuestrasDesp);
    $datMuestrasDesp=mysql_fetch_array($respMuestrasDesp);
    $cantidadEntregada=$datMuestrasDesp[0];
    if($cantidadEntregada==''){$cantidadEntregada=0;}
    $cantidadADevolver=$cantidadRecibida-$cantidadEntregada;
    //echo "<tr><td>$nombreMaterial</td><td align='center'>$cantidadRecibida</td><td align='center'>$cantidadEntregada</td><td align='center'>$cantidadADevolver</td>";
    $cont=$cont+1;
    echo "<tr>";
    echo "<td>$cont</td>";
    echo "<td><input type='hidden' id='rMA$cont' name='rMA$cont' value='$codMaterial'>$nombreMaterial</td>";
    /*if($filasVerificacion==0){
        echo "<td align='center'><input type='text' class='texto' name='$codMaterial-$cantidadADevolver' size=5 value='0'></td>";
    }else{
        echo "<td align='center'><input type='text' class='texto' name='' size=5 value='-' disabled=true></td>";
    }*/
    echo "<td align='center'><input type='hidden' name='rMAteo$cont' value='$cantidadADevolver'><input type='text' class='texto' name='rMAval$cont' size=5 value='0'></td>";
    echo "</tr>";
}
echo "</table><br>";
echo "<input type='hidden' id='nrMA' name='nrMA' value='$cont'>";
echo "<table id='idtabMA' border=1 class='texto'>";
//echo "<tbody id='idtabMA'>";
echo "<tr><th colspan=3>Si Ud. tiene un producto que no se encuentra en la tabla de arriba favor registrarlo a continuacion:</th></tr>";
echo "<tr><th>Producto</th><th>Cantidad a Devolver</th><th>&nbsp;</th></tr>";
//echo "<tr><td>1</td><td>2</td><td>3</td></tr>";
/*for($i=1;$i<=2;$i++) {
    $sqlProductos="
        select m.codigo_material, m.descripcion_material
        from material_apoyo m
        where m.estado = 'Activo' and codigo_material<>0 order by 2";
    $respProductos=mysql_query($sqlProductos);
    echo "<tr>";
    echo "<td><select name='' class='texto'>";
    echo "<option value=''></option>";
    while($datProductos=mysql_fetch_array($respProductos)){
        $codProducto=$datProductos[0];
        $nombreProducto=$datProductos[1];
        echo "<option value='$codProducto'>$nombreProducto</option>";
    }
    echo "</select></td>";
    echo "<td><input type='text' class='texto' size=5></td>";
    echo "</tr>";
}*/
//echo "</tbody>";
echo "</table><br>";
echo "<input type='hidden' id='nregsMA' name='nregsMA' value='0'>";

if($filasVerificacion==0){
    echo "<input type='Submit' class='boton' value='Guardar' id='guar'>";
}else{
    echo "<table align='center'>";
    echo "<tr><td>Ud. ya registro su devolucion de MA para el ciclo en curso.</td></tr>";
    echo "</table>";
}
echo "</center></form>";

?>
<script type='text/javascript' language='javascript'>
    prvtAdiOpcNewMA();
</script>
