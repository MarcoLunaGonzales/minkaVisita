<?php
require("conexion.inc");
require("estilos_visitador.inc");
require("funcion_nombres.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
            function cargarFilaMM(fil)
            {var c=0,n=fil;
                for(i=1;i<n;i++)
                {var tagAux=document.getElementById('mmEval'+i);
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
                    var nTotNo=parseInt(document.getElementById("nrMM").value);
                    for(var i=1;i<=nTotNo;i++)
                    {var codAux=document.getElementById("rMM"+i).value;
                        if(cad!="") {cad=cad+",";}
                        cad=cad+codAux;
                    }
                    $("#nregMM"+fil).html("<td>Cargando ...</td>");
                    cargarPnlLjn("#nregMM"+fil, "#nregMM"+fil, "ajaxDevolucionMM.php", "nregMM="+fil+"&lstMMno="+cad, "", "", function(){}, function(){prvtAdiOpcNewMM();});
                }
                else
                {alert('Solo puede adicionar hasta 10 elementos extras.');
                }
            }
            function prvtAdiOpcNewMM()
            {var tagNfilMM=document.getElementById('nregsMM');
                var n=parseInt(tagNfilMM.value);
                n=n+1; tagNfilMM.value=n;
                $("#idtabMM").append("<tr id='nregMM"+n+"'><td colspan='3'><a href='javascript:cargarFilaMM("+n+")'>Adicionar MM</a></td></tr>");
            }
            function eliFilaMM(fil)
            {var tagFilMM=document.getElementById("nregMM"+parseInt(fil));
                var tagTab=tagFilMM.parentNode;
                tagTab.removeChild(tagFilMM);
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
    </head>
    <body>
        <?php
        $ciclo_global = $cod_ciclo;
        $global_gestion = $cod_gestion;
        $nombreGestion = nombreGestion($global_gestion);

        echo "<form action='guardar_devolucion_visitador.php' method='get'>";
//        echo "<form action='#' method='get'>";
        echo "<center><table border='0' class='textotit'><tr><th>Devolucion de Muestras<br>
Gestion: $nombreGestion Ciclo: $ciclo_global</th></tr></table></center><br>";

        echo "<input type='hidden' name='cod_ciclo' value='$ciclo_global'>";
        echo "<input type='hidden' name='cod_gestion' value='$global_gestion'>";

        $sqlVerificacion = "
    select * from devoluciones_ciclo where codigo_ciclo=$ciclo_global and codigo_gestion=$global_gestion
    and codigo_visitador=$global_visitador and tipo_devolucion=1";

        $respVerificacion = mysql_query($sqlVerificacion);
        $filasVerificacion = mysql_num_rows($respVerificacion);

        echo "<center><table border='1' class='texto' cellspacing='0'>";
//echo "<tr><th>Producto</th><th>Cantidad Recibida</th><th>Cantidad Entregada</th><th>Cantidad a Devolver</th><th>Cantidad Efectiva a Devolver</th></tr>";

        echo "<tr><th>&nbsp;</th><th>Producto</th><th>Cantidad Efectiva a Devolver</th></tr>";

        $sqlMuestrasEnt = "select distinct (m.codigo), concat(m.descripcion, ' ', m.presentacion), sum(sd.cantidad_unitaria) from salida_detalle_visitador sv, salida_detalle_almacenes sd, muestras_medicas m where sv.cod_salida_almacen = sd.cod_salida_almacen and m.codigo = sd.cod_material and sv.codigo_ciclo = '$ciclo_global' and sv.codigo_gestion = '$global_gestion' and sv.codigo_funcionario in ('$global_visitador') group by m.codigo order by 2";

        $respMuestrasEnt = mysql_query($sqlMuestrasEnt);
        $cont = 0;
        while ($datMuestrasEnt = mysql_fetch_array($respMuestrasEnt)) {
            $codMaterial = $datMuestrasEnt[0];
            $cantidadRecibida = $datMuestrasEnt[2];
            $nombreMaterial = $datMuestrasEnt[1];
            $sqlMuestrasDesp = "select (sum(rd.CANT_MM_ENT) + sum(rd.CANT_MM_EXTENT)) as cantidad from reg_visita_cab r, reg_visita_detalle rd where r.COD_REG_VISITA = rd.COD_REG_VISITA and r.COD_CICLO = '$ciclo_global' and r.COD_GESTION = '$global_gestion' and r.COD_VISITADOR = '$global_visitador' and rd.COD_MUESTRA = '$codMaterial'";
            $respMuestrasDesp = mysql_query($sqlMuestrasDesp);
            $datMuestrasDesp = mysql_fetch_array($respMuestrasDesp);
            $cantidadEntregada = $datMuestrasDesp[0];
            if ($cantidadEntregada == '') {
                $cantidadEntregada = 0;
            }
            $cantidadADevolver = $cantidadRecibida - $cantidadEntregada;
            //echo "<tr><td>$nombreMaterial</td><td align='center'>$cantidadRecibida</td><td align='center'>$cantidadEntregada</td><td align='center'>$cantidadADevolver</td>";
            $cont = $cont + 1;
            echo "<tr>";
            echo "<td>$cont</td>";
            echo "<td><input type='hidden' id='rMM$cont' name='rMM$cont' value='$codMaterial'>$nombreMaterial</td>";
            /* if($filasVerificacion==0){
              echo "<td align='center'><input type='text' class='texto' name='$codMaterial-$cantidadADevolver' size=5 value='0'></td>";
              }else{
              echo "<td align='center'><input type='text' class='texto' name='' size=5 value='-' disabled=true></td>";
              } */
            echo "<td align='center'><input type='hidden' name='rMMteo$cont' value='$cantidadADevolver'><input type='text' class='texto' name='rMMval$cont' size=5 value='0'></td>";
            echo "</tr>";
        }
        echo "</table><br>";
        echo "<input type='hidden' id='nrMM' name='nrMM' value='$cont'>";
        echo "<table id='idtabMM' border=1 class='texto'>";
//echo "<tbody id='idtabMM'>";
        echo "<tr><th colspan=3>Si Ud. tiene un producto que no se encuentra en la tabla de arriba favor registrarlo a continuacion:</th></tr>";
        echo "<tr><th>Producto</th><th>Cantidad a Devolver</th><th>&nbsp;</th></tr>";
//echo "<tr><td>1</td><td>2</td><td>3</td></tr>";
        /* for($i=1;$i<=2;$i++) {
          $sqlProductos="
          select m.codigo, CONCAT(m.descripcion, ' ', m.presentacion)
          from muestras_medicas m
          where m.estado = 1 order by 2";
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
          } */
//echo "</tbody>";
        echo "</table><br>";
        echo "<input type='hidden' id='nregsMM' name='nregsMM' value='0'>";

        if ($filasVerificacion == 0) {
            echo "<input type='Submit' class='boton' value='Guardar' id='guar'>";
        } else {
            echo "<table align='center'>";
            echo "<tr><td>Si usted tiene productos adicionales a devolver, que no se encuentren en la tabla de arriba, por favor registrarlos a continuaci�n.</td></tr>";
            echo "</table>";
        }
        echo "</center></form>";
        ?>
    </body>
    <script type='text/javascript' language='javascript'>
        prvtAdiOpcNewMM();
    </script>
</html>
