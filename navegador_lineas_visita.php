<?php
require("conexion.inc");
$ciclo = $_GET['ciclo'];
$gestion = $_GET['gestion'];
//$sql="select codigo_l_visita, nombre_l_visita from lineas_visita where codigo_linea='$global_linea' order by nombre_l_visita";
$sql = "SELECT codigo_l_visita, nom_orden from lineas_visita where codigo_linea in('1021','1031') and codigo_l_visita <> 0 order by nom_orden ASC";
$resp = mysql_query($sql);
//$sql_gestion = mysql_query("Select codigo_gestion, nombre_gestion from gestiones where estado = 'Activo' ");
//$codigo_gestion = mysql_result($sql_gestion, 0, 0);
$codigo_gestion = $gestion;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href='stilos.css' rel='stylesheet' type='text/css' />
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="lib/noty/jquery.noty.js"></script>
    <script type="text/javascript" src="lib/noty/center.js"></script>
    <script type="text/javascript" src="lib/noty/default.js"></script>
    <script type="text/javascript" src="ajax/cerrarLinea/send.data.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("body").on({
                ajaxStart: function() { 
                    $(this).addClass("loading"); 
                },
                ajaxStop: function() { 
                    $(this).removeClass("loading"); 
                }    
            });
            $("#cerrar_ciclo").click(function(){
                var n = noty({
                    text: "Esta seguro de cerrar el ciclo?, no se podran hacer cambios luego de cerrarlo.",
                    type: "error",
                    dismissQueue: true,
                    layout: "center",
                    theme: 'defaultTheme',
                    modal:true,
                    buttons: [
                    {
                        addClass: 'btn btn-primary', 
                        text: 'Ok', 
                        onClick: function($noty) {
                            $noty.close();
                            sendData($('#cerrar_ciclo').attr('ciclo'),$('#cerrar_ciclo').attr('gestion'));
                        }
                    },
                    {
                        addClass: 'btn btn-danger', 
                        text: 'Cancel', 
                        onClick: function($noty) {
                            $noty.close();
                            alert("No se cerro el ciclo")
                        }
                    }
                    ]
                });

})
})
</script>
<?php
echo "<script language='Javascript'>
function enviar_nav()
{	location.href='registrar_linea_visita.php';
}
function replicar_nav(){
    location.href='replicarLineaVisitaxCicloTotal.php';
}
function editar_nav(f)
{
   var i;
   var j=0;
   var j_linea;
   for(i=0;i<=f.length-1;i++)
   {
    if(f.elements[i].type=='checkbox')
        {	if(f.elements[i].checked==true)
         {	j_linea=f.elements[i].value;
          j=j+1;
      }
  }
}
if(j>1)
   {	alert('Debe seleccionar solamente una L&iacute;nea de Visita para editar sus datos.');
}
else
{
    if(j==0)
    {
     alert('Debe seleccionar una L&iacute;nea de Visita para editar sus datos.');
 }
 else
 {
     location.href='editar_l_visita.php?cod_linea_vis='+j_linea+'';
 }
}
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
   {	alert('Debe seleccionar al menos un Producto para eliminarlo de la L&iacute;nea de Visita.');
}
else
{
    if(confirm('Esta seguro de eliminar los datos.'))
    {
     location.href='eliminar_prod_linea.php?datos='+datos+'';
 }
 else
 {
     return(false);
 }
}
}
</script>";
?>
<style type="text/css">
    #header h2 { color: #5F7BA9; font-size: 11pt; font-family: Verdana}
    body { background: #fff url('imagenes/fondo_pagina.jpg') no-repeat right; }
    #filtro th { text-align: left;; width: 30%; font-size: 12px }
    .button { padding: 0.4em 1em; cursor: pointer; text-align: center; background: url("images/ui-bg_glass_80_d7ebf9_1x400.png") repeat-x ; margin: 3px 5px 0; text-decoration: none !important; border: 1px solid #A1C2EB ; line-height: 30px; color: #000; -webkit-border-radius: 6px;-moz-border-radius: 6px;border-radius: 6px; font-size: 12px; color: #000; width: 100px}
    .button:hover { opacity: 0.60 }
    .modal {
        display:    none;
        position:   fixed;
        z-index:    1000;
        top:        0;
        left:       0;
        height:     100%;
        width:      100%;
        background: rgba( 0, 0, 0,.8 ) 
        url('http://i.stack.imgur.com/FhHRx.gif') 
        50% 50% 
        no-repeat;
    }
    body.loading {
        overflow: hidden;   
    }

    body.loading .modal {
        display: block;
    }
</style>
</head>
<body style="background: url('imagenes/fondo_pagina.jpg') no-repeat top right">
    <?php
    echo "<form>";
    echo "<center><table border='0' class='textotit'><tr><th>Registro de L&iacute;neas de Visita</th></tr></table></center><br>";
    echo "<center><table border='0' class='textotit'><tr><th>Ciclo: $ciclo</th></tr></table></center><br>";
    $indice_tabla = 1;
    echo "<center><table border='1' class='texto' cellspacing='0' width='50%'>";
    echo "<tr><td>&nbsp;</td><td>&nbsp;</td><th>L&iacute;nea de Visita</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
    while ($dat = mysql_fetch_array($resp)) {
        $cod_l_visita = $dat[0];
        $nom_l_vis = $dat[1];
        echo "<tr>
        <td align='center'>$indice_tabla</td>
        <td align='center'><input type='checkbox' name='codigo' value='$cod_l_visita&ciclo=$ciclo'></td>
        <td align='center'>$nom_l_vis</td>
        <td align='center'><a href='navegador_l_visita_detalle.php?cod_linea_vis=$cod_l_visita&ciclo=$ciclo&gestion=$codigo_gestion'>Ver Productos >></a></td>
        <td align='center'><a href='navegador_l_visita_espe.php?cod_linea_vis=$cod_l_visita&ciclo=$ciclo&gestion=$codigo_gestion'>Ver Especialidades >></a></td>
        <td align='center'><a href='navegador_lineasvisitafuncionario.php?cod_linea_vis=$cod_l_visita&ciclo=$ciclo&gestion=$codigo_gestion'>Ver Visitadores >></a></td>
    </tr>";
    $indice_tabla++;
}
echo "</table></center><br>";
require('home_central1.inc');
echo "<center>";
$sql_veri = mysql_query("SELECT estado from lineas_visitadores_estados where ciclo = $ciclo and gestion = $codigo_gestion");
$num_veri = mysql_num_rows($sql_veri);
if ($num_veri >= 1) {
    echo "<center><h1>El ciclo se encuentra cerrado</h1></center>";
} else {
    echo "<table border='0' class='texto'><tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' class='boton' onclick='editar_nav(this.form)'></td><td><input type='button' value='Replicar' class='boton' onclick='replicar_nav()'></td><td><input type='button' value='Cerrar Ciclo' class='boton' id='cerrar_ciclo' ciclo='$ciclo' gestion ='$codigo_gestion'/></td></tr></table></center>";
}
echo "</form>";
echo "<div class='modal'></div>";
?>
</body>
</html>