<script type='text/javascript' language='Javascript'>
    function nuevoAjax() {
        var xmlhttp = false;
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
    function ajaxCiclos(codigo) {
        var codGestion=codigo.value;
        var contenedor;
        contenedor = document.getElementById('divCiclos');
        ajax=nuevoAjax();
        ajax.open('GET', 'ajaxCiclos.php?codGestion='+codGestion+'',true);
        ajax.onreadystatechange=function() {
            if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
            }
        }
        ajax.send(null)
    }
    function ajaxVisitadores(objeto) {
        var codigoTer=objeto.rpt_territorio;
        var codigoLin=objeto.rpt_linea;
        var j=0;
        var codTerritorio=new Array();
        for(var i=0;i<=codigoTer.options.length-1;i++) {
            if(codigoTer.options[i].selected) {
                codTerritorio[j]=codigoTer.options[i].value;
                j++;
            }
        }
        j=0;
        var codLinea=new Array();
        for(var i=0;i<=codigoLin.options.length-1;i++) {
            if(codigoLin.options[i].selected) {
                codLinea[j]=codigoLin.options[i].value;
                j++;
            }
        }
        var cadTer=new String; cadTer=codTerritorio;
        var cadLin=new String; cadLin=codLinea;
        if(cadTer!="" && cadLin!="") {
            var contenedor;
            contenedor = document.getElementById('divVisitadores');
            ajax=nuevoAjax();
            ajax.open('GET', 'ajaxVisitadores2.php?codTerritorio='+codTerritorio+'&codLinea='+codLinea+'',true);
            ajax.onreadystatechange=function() {
                if(ajax.readyState==4) {
                    contenedor.innerHTML = ajax.responseText
                }
            }
            ajax.send(null);
        } //else {alert("Seleccione un territorio y una linea.");}
    }
    function ajaxVisitadorLinea(obj){
        ajaxVisitadores(obj);
    }
    function envia_formulario(f) {
        var rpt_visitador,rpt_gestion, rpt_ciclo;
        var rpt_territorio;
        rpt_territorio=f.rpt_territorio.value;
        rpt_gestion=f.gestion_rpt.value;
        var rpt_ciclo=new Array();
        var rpt_nombreciclo=new Array();
        var rpt_linea=new Array();
        var rpt_nombrelinea=new Array();
        var rpt_visitador=new Array();
        var j=0;
        for(i=0;i<=f.ciclo_rpt.options.length-1;i++) {
            if(f.ciclo_rpt.options[i].selected) {
                rpt_ciclo[j]=f.ciclo_rpt.options[i].value;
                j++;
            }
        }
        j=0;
        for(i=0;i<=f.rpt_visitador.options.length-1;i++) {
            if(f.rpt_visitador.options[i].selected) {
                rpt_visitador[j]=f.rpt_visitador.options[i].value;
                j++;
            }
        }
        window.open('rpt_boletas_visita.php?rpt_visitador='+rpt_visitador+'&rpt_gestion='+rpt_gestion+'&rpt_ciclo='+rpt_ciclo+'&rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
        return(true);
    }
</script>
<?php
 require("conexion.inc");
 require("estilos_administracion.inc");

 echo "<center><table class='textotit'><tr><th>Boletas de Visita</th></tr></table><br>";
 echo "<form method='post' id='frm'>";
 echo "<table class='texto' border='1' align='center' cellSpacing='0' width='30%'>";

 //gestion
 echo "<tr><th align='left'>Gesti&#243;n</th>";
 $sql_gestion="SELECT distinct(codigo_gestion), nombre_gestion, estado FROM gestiones";
 $resp_gestion=mysql_query($sql_gestion);
 echo "<td><select name='gestion_rpt' class='texto' onChange='ajaxCiclos(this)'>";
 $bandera=0;
 echo "<option></option>";
 while($datos_gestion=mysql_fetch_array($resp_gestion)) {
     $cod_gestion_rpt=$datos_gestion[0];$nom_gestion_rpt=$datos_gestion[1];$estado_gestion_rpt=$datos_gestion[2];
     echo "<option value='$cod_gestion_rpt'>$nom_gestion_rpt</option>";
 }
 echo $cod_gestion_rpt;
 echo "</select></td></tr>";

 //ciclo
 echo "<tr><th align='left'>Ciclo</th>";
 echo "<td><div id='divCiclos'></td></tr>";

 //territorio
 echo "<tr><th align='left'>Territorio</th>";
 echo "<td><select name='rpt_territorio' class='texto' onChange='ajaxVisitadorLinea(frm);' size='12'>";
 $sql="SELECT c.cod_ciudad, c.descripcion FROM ciudades c, `funcionarios_agencias` f WHERE
       f.`cod_ciudad`=c.`cod_ciudad` AND f.`codigo_funcionario`=$global_usuario ORDER BY c.descripcion ASC";
 $resp=mysql_query($sql);
 while($dat=mysql_fetch_array($resp)) {
     $codigo_ciudad=$dat[0];
     $nombre_ciudad=$dat[1];
     echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
 }
 echo "</select></td></tr>";

 //linea
 echo "<tr><th align='left'>Lineas</th>";
 echo "<td><select name='rpt_linea' class='texto' onChange='ajaxVisitadorLinea(frm);' size='5'>";
 $sql="SELECT l.codigo_linea, l.nombre_linea FROM lineas l
       WHERE l.estado=1 AND l.linea_promocion=1 ORDER BY l.nombre_linea ASC;";
 $resp=mysql_query($sql);
 while($dat=mysql_fetch_array($resp)) {
     $codigo_linea=$dat[0];
     $nombre_linea=$dat[1];
     echo "<option value='$codigo_linea'>$nombre_linea</option>";
 }
 echo "</select></td></tr>";

 //visitador
 echo "<tr><th align='left'>Visitador</th>";
 echo "<td><div id='divVisitadores'></td></tr>";

 echo "</table><br>";
 echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'></center><br>";
 echo "</form>";

?>
