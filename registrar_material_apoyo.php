<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("#nom_material").change(function(){
        var nombre_item = $("#nom_material").val();
        $("#status").html('<img src="imagenes/ajax-loader2.gif" align="absmiddle">&nbsp;Comprobando disponibilidad...');
        $.ajax({  
            type: "POST",  
            url: "admin/ajax/check.php",  
            data: "nombre_item="+ nombre_item,  
            success: function(msg){  
                $("#status").ajaxComplete(function(event, request, settings){ 
                    if(msg == 'OK') {
                        $(".enviar").attr("status","bien")
                        $(this).html('&nbsp;<img src="imagenes/si.gif" align="absmiddle">');
                    } else {
                        $(".enviar").attr("status","mal")
                        $(this).html(msg);
                    }  
                });
            } 
        });
    });
    $(".enviar").click(function() {
        if($("#nom_material").val() == ''){
            alert("El campo Nombre de Material esta vacio.")
        } else {
            if($(".enviar").attr('status') == 'bien'){
                $("#formulario_principal").submit()
            }else{
                alert("El item ya existe verifique porfavor.")
            }
        }
    })
})
</script>
<?php
echo "<script language='Javascript'>
function validar(f) {
    if(f.material.value=='') {	
        alert('El campo Nombre de Material esta vacio.');
        f.material.focus();
        return(false);
    }
    f.submit();
}
</script>";
require("conexion.inc");
require('estilos_administracion.inc');
echo "<form action='guarda_material_apoyo.php' method='post' id='formulario_principal'>";
echo "<table border='0' align='center' class='textotit'><tr><th>Adicionar Material de Apoyo</th></tr></table><br>";
echo "<table border='1' align='center' class='texto' cellspacing='0'>";
echo "<tr><th align='left'>Nombre Material</th>";
echo "<td align='left'><input type='text' id='nom_material' class='texto' name='material' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'><div id='status'></div></td></tr>";
echo "<tr><th align='left'>Tipo de Material</th>";
$sql1 = "SELECT * FROM tipos_material WHERE cod_tipomaterial <> 0 ORDER BY nombre_tipomaterial";
$resp1 = mysql_query($sql1);
echo "<td align='left'><select name='tipo_material' class='texto'>";
while ($dat1 = mysql_fetch_array($resp1)) {
    $cod_tipomaterial = $dat1[0];
    $nombre_tipomaterial = $dat1[1];
    echo "<option value='$cod_tipomaterial'>$nombre_tipomaterial</option>";
}
echo "</select></td>";
echo "</tr>";
echo "<tr><th align='left'>Linea</th><td><select name='linea' class='texto'>";
$sql = "SELECT codigo_linea, nombre_linea FROM lineas WHERE linea_inventarios = 1 AND codigo_linea <> 0 ORDER BY nombre_linea";
$resp = mysql_query($sql);
while ($dat = mysql_fetch_array($resp)) {
    $codigo_linea = $dat[0];
    $nombre_linea = $dat[1];
    if ($rpt_linea == $codigo_linea) {
        echo "<option value='$codigo_linea' selected>$nombre_linea</option>";
    } else {
        echo "<option value='$codigo_linea'>$nombre_linea</option>";
    }
}
echo "</select></td></tr>";
echo "<tr><th>Fecha de Expiraci&oacute;n</th><td><INPUT  type='text' class='texto' id='exafinicial' size='10' name='exafinicial'>";
echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
echo" input_element_id='exafinicial' ";
echo" click_element_id='imagenFecha'></DLCALENDAR></td></tr>";
echo "</table><br>";

echo"\n<table align='center'><tr><td><a href='navegador_material.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<center><input type='button' class='boton enviar' value='Guardar'></center>";
echo "</form>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";