<?php

require("conexion.inc");

?>
<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>

<script>
    jQuery(document).ready(function($) {
        $(".checksug").change(function(event) {
            cb = $(this);
            cb.val(cb.prop('checked'));
            // cb.val( cb[0].checked ? "true" : "false" );
        });
    });
</script>

<?php

error_reporting(0);

echo "<script LANGUAGE='JavaScript'>
function activa_select(f,i) {
	

    if(document.getElementById('constancia'+i).checked == true){
		document.getElementById('cantidad_entregada'+i).disabled=false;
		document.getElementById('cantidad_extraentregada'+i).disabled=false;
		document.getElementById('cantidad_apoyo'+i).disabled=false;
		document.getElementById('cantidad_extraapoyo'+i).disabled=false;
		document.getElementById('obs'+i).disabled=false;
	}else{	
		document.getElementById('cantidad_entregada'+i).disabled=true;
		document.getElementById('cantidad_extraentregada'+i).disabled=true;
		document.getElementById('cantidad_apoyo'+i).disabled=true;
		document.getElementById('cantidad_extraapoyo'+i).disabled=true;
		document.getElementById('obs'+i).disabled=true;
	}

}
function envia_form(f) {	
    f.submit();
}
function guarda_form(f) {	
    var indice,valores,indice_extra,num_extras, fechaVisitaReal, sug=0,indice_agregado;
    indice=0;
    indice_extra=0;
    indice_agregado=0;
    var cod_med, cod_espe, cat_med, cod_dia;
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
    var obs=new Array();
    var sug=new Array();
    var prod_agregado = new Array();
    for(j=0;j<=f.length-1;j++) {
     if(f.elements[j].name.indexOf('constancia')!=-1) {	
        if(f.elements[j].checked==true) {	
            constancia[indice-1]=1;
        } else {	
            constancia[indice-1]=0;
        }
    }
    if(f.elements[j].name.indexOf('cantidad_extraentregada')!=-1) {	
        cantidad_extraentregada[indice]=f.elements[j].value;
    }
    if(f.elements[j].name.indexOf('cantidad_extraapoyo')!=-1) {	
        cantidad_extraapoyo[indice]=f.elements[j].value;
    }
    if(f.elements[j].name.indexOf('muestra')!=-1) {	
        muestras[indice]=f.elements[j].value;
    }
    if(f.elements[j].name.indexOf('material')!=-1) {	
        material[indice]=f.elements[j].value;
    }
    if(f.elements[j].name.indexOf('cantidad_entregada')!=-1) {	
        cantidad_muestras[indice]=f.elements[j].value;
    }
    if(f.elements[j].name.indexOf('sug')!=-1){
        sug[indice]=f.elements[j].value;
    }
    if(f.elements[j].name.indexOf('cantidad_apoyo')!=-1) {  
        cantidad_apoyo[indice]=f.elements[j].value;
        indice++;
    }
    if(f.elements[j].name.indexOf('prod_extra')!=-1) {  
        prod_extra[indice_extra]=f.elements[j].value;
    }
    if(f.elements[j].name.indexOf('prod_agregado')!=-1){
        prod_agregado[indice_agregado]=f.elements[j].value;
        indice_agregado++;
    }
    if(f.elements[j].name.indexOf('mat_extra')!=-1) {   
        mat_extra[indice_extra]=f.elements[j].value;
    }
    if(f.elements[j].name.indexOf('cant_entregada_extra')!=-1) {
        cant_entregada_extra[indice_extra]=f.elements[j].value;
    }
    if(f.elements[j].name.indexOf('cant_entregada_apoyo_extra')!=-1) {  
        cant_entregada_apoyo_extra[indice_extra]=f.elements[j].value;
        indice_extra++;
    }
    if(f.elements[j].name.indexOf('obs')!=-1) { 
        obs[indice_extra]=f.elements[j].value;
        indice_extra++;
    }

}

valores=f.valores.value;
num_extras=f.muestras_extra.value;
num_muestra_agregar=f.muestra_agregar.value;
fechaVisitaReal=f.fechaVisitaReal.value;
cod_med=f.cod_med.value;
cod_espe=f.cod_espe.value;
cat_med=f.cat_med.value;
cod_dia=f.cod_dia.value;
codigo_parrilla=f.codigo_parrilla.value;
location.href='guarda_registro_visitaNuevo.php?constancia='+constancia+'&muestras='+muestras+'&material='+material+'&cantidad_muestras='+cantidad_muestras+'&cantidad_apoyo='+cantidad_apoyo+'&sug='+sug+'&prod_agregado='+prod_agregado+'&prod_extra='+prod_extra+'&mat_extra='+mat_extra+'&cant_entregada_extra='+cant_entregada_extra+'&cant_entregada_apoyo_extra='+cant_entregada_apoyo_extra+'&cantidad_extraentregada='+cantidad_extraentregada+'&cantidad_extraapoyo='+cantidad_extraapoyo+'&obs='+obs+'&valores='+valores+'&num_extras='+num_extras+'&num_muestra_agregar='+num_muestra_agregar+'&fechaVisitaReal='+fechaVisitaReal+'&cod_med='+cod_med+'&cat_med='+cat_med+'&cod_espe='+cod_espe+'&cod_dia='+cod_dia+'&codigo_parrilla='+codigo_parrilla+'';
}
</script>";

require("estilos_visitador_sincab.inc");
$codigo_parrilla = $_GET['codigo_parrilla'];




//sacamos el ciclo activo de la linea
$sql_ciclo = "SELECT cod_ciclo from ciclos where estado='Activo' and codigo_linea='1032'"; $resp_ciclo = mysql_query($sql_ciclo); $dat_ciclo = mysql_fetch_array($resp_ciclo); $ciclo_activo = $dat_ciclo[0];
//hasta aqui tenemos el ciclo en $ciclo_activo
$vector           = explode("-", $cod_contacto);
$contacto         = $vector[0];
$orden_visita     = $vector[1];
$visitador        = $vector[2];
$fecha_visita     = $vector[3];
$agencia_parrilla = $vector[4];
$visita=$vector[5];
$global_linea=$vector[6];



/*DESPUES SACAR ESTO*/
/*$codCiudadXXX=$agencia_parrilla;
if($codCiudadXXX==116 || $codCiudadXXX==122 || $codCiudadXXX==124 || $codCiudadXXX==118 || $codCiudadXXX==119 || $codCiudadXXX==109 || $codCiudadXXX==113){
	$ciclo_activo = 2;
}*/

//formamos los encabezados nombre medico, especialidad turno
$sql = "SELECT c.turno, m.ap_pat_med, m.ap_mat_med, m.nom_med, dm.direccion, cd.categoria_med, cd.cod_especialidad, 
cd.orden_visita, c.cod_contacto, cd.estado, m.cod_med from rutero c, rutero_detalle cd, medicos m, 
direcciones_medicos dm where c.cod_ciclo='$ciclo_activo' and c.cod_visitador=$global_visitador and 
m.cod_med=cd.cod_med and dm.numero_direccion=cd.cod_zona and cd.cod_med=dm.cod_med and c.cod_contacto='$contacto' 
and c.cod_contacto=cd.cod_contacto and cd.orden_visita='$orden_visita' order by c.turno,cd.orden_visita";
//echo $sql;
$resp              = mysql_query($sql);
$dat_enc           = mysql_fetch_array($resp);
$enc_nombre_medico = "$dat_enc[1] $dat_enc[2] $dat_enc[3]";
$enc_turno         = $dat_enc[0];
$enc_categoria     = $dat_enc[5];
$enc_especialidad  = $dat_enc[6];
$cod_med           = $dat_enc[10];
//fin encabezados
$sql_nombre_dia  = "SELECT dia_contacto from rutero where cod_contacto='$contacto'"; 
$resp_nombre_dia = mysql_query($sql_nombre_dia); 
$dat_nombre_dia  = mysql_fetch_array($resp_nombre_dia); 
$nombre_de_dia   = $dat_nombre_dia[0];

$sqlCodDia = "SELECT id from orden_dias where dia_contacto='$nombre_de_dia'"; $respCodDia = mysql_query($sqlCodDia); $codDiaContacto = mysql_result($respCodDia, 0, 0);
$sql = "SELECT cod_especialidad, categoria_med, estado from rutero_detalle where cod_contacto=$contacto and orden_visita=$orden_visita"; $res = mysql_query($sql); $dat = mysql_fetch_array($res); $especialidad = $dat[0];
$categoria  = $dat[1];
$estado_pri = $dat[2];



echo "<h1>Registro de Visita M&eacute;dica<br>M&eacute;dico: <strong>$enc_nombre_medico</strong> 
Especialidad: <strong>$enc_especialidad</strong> Categor&iacute;a: <strong>$categoria</strong><br>
<strong>$nombre_de_dia $fecha_visita</strong></h1>";

echo "<table border='0' class='textomini' align='center'><tr><td>Leyenda:</td><td>Producto Objetivo</td><td bgcolor='#ffff99' width='10%'></td><td>&nbsp;</td><td>Producto Filtrado</td><td bgcolor='#ff7591' width='10%'></td><td>&nbsp;</td><td>Producto Extra</td><td bgcolor='#66ccff' width='10%'></td></table><br>";
//aplicamos una consulta para saber si el visitador hace linea de visita para la especialidad
// $verifica_lineas = "SELECT l.codigo_l_visita from lineas_visita l, lineas_visita_especialidad le, lineas_visita_visitadores lv where l.codigo_l_visita=le.codigo_l_visita and l.codigo_l_visita=lv.codigo_l_visita and le.codigo_l_visita=lv.codigo_l_visita and l.codigo_linea='$global_linea' and lv.codigo_funcionario='$global_visitador' and le.cod_especialidad='$especialidad' and lv.codigo_gestion = $codigo_gestion and lv.codigo_ciclo = $ciclo_activo ";
$verifica_lineas = "SELECT lv.codigo_l_visita from lineas_visita_visitadores_copy lv, lineas_visita_especialidad le WHERE le.codigo_l_visita = lv.codigo_l_visita and lv.codigo_funcionario = $global_visitador and lv.codigo_gestion = $codigo_gestion and lv.codigo_ciclo = $ciclo_activo and lv.codigo_linea_visita = $global_linea and le.cod_especialidad = '$especialidad'";
// echo $verifica_lineas;
$resp_verifica_lineas = mysql_query($verifica_lineas);
$filas_verifica = mysql_num_rows($resp_verifica_lineas);
if ($filas_verifica != 0) {
    $dat_verifica = mysql_fetch_array($resp_verifica_lineas);
    $codigo_l_visita = $dat_verifica[0];
} else {
    $codigo_l_visita = 0;
}

//$sqlX="select * from configuracion_parrilla_personalizada2 c where c.codigo_linea='$global_linea' and c.cod_especialidad='$especialidad'";

//echo $sqlX;

//$respX=mysql_query($sqlX);
//$numFilasX=mysql_num_rows($respX);

//echo $numFilasX;

if(1==1){
	$sql="SELECT 
	(select mm.descripcion from muestras_medicas mm where mm.codigo=pd.cod_mm), 
	(select mm.presentacion from muestras_medicas mm where mm.codigo=pd.cod_mm), pd.cantidad_mm, 
	(select ma.descripcion_material from material_apoyo ma where ma.codigo_material=pd.cod_ma), pd.cantidad_ma, 
	pd.cod_med, pd.orden_visita, pd.cod_mm, pd.cod_ma from parrilla_personalizadareg pd
	where pd.cod_med='$cod_med' and pd.cod_linea='$global_linea' and 
	pd.numero_visita='$visita' and pd.cod_ciclo='$ciclo_activo' 
	and pd.cod_gestion='$codigo_gestion' order by pd.orden_visita";
}else{
	$sql = "SELECT mm.descripcion, mm.presentacion, pd.cantidad_muestra, ma.descripcion_material, pd.cantidad_material, 
	pd.codigo_parrilla,pd.prioridad,mm.codigo,ma.codigo_material from muestras_medicas mm, parrilla_detalle pd, material_apoyo ma, 
	parrilla p where p.codigo_parrilla=pd.codigo_parrilla and p.cod_especialidad='$especialidad' and p.agencia='$agencia_parrilla' and 
	p.categoria_med='$categoria' and p.codigo_linea='$global_linea' and p.codigo_l_visita='$codigo_l_visita' and 
	mm.codigo=pd.codigo_muestra and p.numero_visita='$visita' and ma.codigo_material=pd.codigo_material and p.cod_ciclo='$ciclo_activo' 
	and p.codigo_gestion='$codigo_gestion' order by pd.prioridad";
}


//echo "<br>".$sql;

$resp = mysql_query($sql);
$numero_registros = mysql_num_rows($resp);
if ($numero_registros != 0) {
    echo "<form name='form' name='principal' method='get' action=''>";
    echo "<table border='0' cellspacing='0' class='texto' align='center'><tr><th>Fecha Real de Visita</th><th>
    <INPUT  type='text' class='texto' id='fechaVisitaReal' size='10' name='fechaVisitaReal' value='$fecha_visita'>
      <IMG id='imagenFecha' src='imagenes/fecha.bmp'>
          <DLCALENDAR tool_tip='Seleccione la Fecha'
          daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' 
          navbar_style='background-color: 7992B7; color:ffffff;' 
          input_element_id='fechaVisitaReal' 
          click_element_id='imagenFecha'></DLCALENDAR></th>
      </tr></table><br>";
      echo "<table border='1' cellspacing='0' class='textomini' width='100%'>";
      /*echo "<tr><th>Muestra</th><th>Cantidad</th><th>Cantidad extra entregada</th><th>Material de Apoyo</th><th>Cantidad</th>
      <th>Cantidad extra entregada</th><th>Obs.</th><th>Entregado</th><th>Sugerir Quitar</th></tr>";*/
      echo "<tr><th>Muestra</th><th>Cantidad</th><th>Cantidad extra entregada</th><th>Material de Apoyo</th><th>Cantidad</th>
      <th>Cantidad extra entregada</th><th>Obs.</th><th>Entregado</th></tr>";
	  $i = 1;
      $constancia = "constancia";
      $cantidad_extraentregada = "cantidad_extraentregada";
      $cantidad_extraapoyo = "cantidad_extraapoyo";
      while ($dat = mysql_fetch_array($resp)) {
        $muestra         = $dat[0];
        $presentacion    = $dat[1];
        $cantidad        = $dat[2];
        $apoyo           = $dat[3];
        $cant_apoyo      = $dat[4];
        $parrilla        = $dat[5];
        $prioridad       = $dat[6];
        $cod_muestra     = $dat[7];
        $codigo_material = $dat[8];
        
		$sql_negados = "SELECT * from muestras_negadas where cod_med='$cod_med' and codigo_muestra='$cod_muestra' and codigo_linea='$global_linea'";

        $res_negados = mysql_query($sql_negados);
        $num_negados = mysql_num_rows($res_negados);
        if ($num_negados != 0) {
            $fondo = "#ff7591";
        }
        $sql_obj = "SELECT * from productos_objetivo where cod_med='$cod_med' and codigo_muestra='$cod_muestra' and codigo_linea='$global_linea'";
        $res_obj = mysql_query($sql_obj);
        $num_obj = mysql_num_rows($res_obj);
        if ($num_obj != 0) {
            $fondo = "#ffff99";
        }
        echo "<input type='hidden' name='muestra$i' id='muestra$i' value='$cod_muestra'>";
        echo "<input type='hidden' name='material$i' id='material$i' value='$codigo_material'>";
        //echo "<input type='hidden' name='cantidad_entregada$i' value='$cantidad'>";
        //echo "<input type='hidden' name='cantidad_apoyo$i' value='$cant_apoyo'>";
        echo "<tr bgcolor='$fondo'><td>$muestra $presentacion </td><td align='center'>";
        $var_constancia = "$constancia$i";
        $valor_constancia = $$var_constancia;
        if ($valor_constancia == 1) {
            $var_cant_entregada   = "$cantidad_extraentregada$i";
            $valor_cant_entregada = $$var_cant_entregada;
            $var_cant_apoyo       = "$cantidad_extraapoyo$i";
            $valor_cant_apoyo     = $$var_cant_apoyo;
            echo "<select name='cantidad_entregada$i' id='cantidad_entregada$i' class='texto'>";
        } else {
            echo "<select name='cantidad_entregada$i' id='cantidad_entregada$i' class='texto' disabled='true'>";
        }
        for ($ii = 0; $ii <= $cantidad; $ii++) {
            if ($ii == $cantidad) {
                echo "<option value='$ii' selected>$ii</option>";
            } else {
                echo "<option value='$ii'>$ii</option>";
            }
        }
        echo "</select>";
        echo "</td>";
        if ($valor_constancia == 1) {
            echo "<td align='center'><select name='cantidad_extraentregada$i' id='cantidad_extraentregada$i' class='textomini'>";
        } else {
            echo "<td align='center'><select name='cantidad_extraentregada$i' id='cantidad_extraentregada$i' class='textomini' disabled='true'>";
        }
        echo "<option value=''></option>";
        for ($j = 1; $j <= 20; $j++) {
            if ($valor_cant_entregada == $j and $valor_constancia == 1) {
                echo "<option value='$j' selected>$j</option>";
            } else {
                echo "<option value='$j'>$j</option>";
            }
        }
        echo "</td>";
        echo "<td>$apoyo</td><td align='center'>";
        if ($valor_constancia == 1) {
            echo "<select name='cantidad_apoyo$i' id='cantidad_apoyo$i'  class='texto'>";
        } else {
            echo "<select name='cantidad_apoyo$i' id='cantidad_apoyo$i' class='texto' disabled='true'>";
        }
        for ($jj = 0; $jj <= $cant_apoyo; $jj++) {
            if ($jj == $cant_apoyo) {
                echo "<option value='$jj' selected>$jj</option>";
            } else {
                echo "<option value='$jj'>$jj</option>";
            }
        }
        echo "</select>";
        echo "</td>";
        if ($valor_constancia == 1 and $codigo_material != 0) {
            echo "<td align='center'><select name='cantidad_extraapoyo$i' id='cantidad_extraapoyo$i' class='textomini'>";
        } else {
            echo "<td align='center'><select name='cantidad_extraapoyo$i'  id='cantidad_extraapoyo$i' class='textomini' disabled='true'>";
        }
        echo "<option value=''></option>";
        for ($j = 1; $j <= 20; $j++) {
            if ($valor_cant_apoyo == $j and $valor_constancia == 1) {
                echo "<option value='$j' selected>$j</option>";
            } else {
                echo "<option value='$j'>$j</option>";
            }
        }
        echo "</td>";

        if ($valor_constancia == 1) {
            echo "<td align='center'><input type='text' name='obs$i' id='obs$i' class='textomini'></td>";
            echo "<td align='center'><input type=checkbox name='constancia$i' id='constancia$i' value='1' onClick='activa_select(this.form,$i)' checked></td>";
        } else {
            echo "<td align='center'><input type='text' name='obs$i' id='obs$i'  class='textomini' disabled></td>";
            echo "<td align='center'><input type=checkbox name='constancia$i' id='constancia$i' value='1' onClick='activa_select(this.form,$i)'></td>";
        }
        $i = $i + 1;
        $fondo = "";
        //echo "<td align='center'><input type='checkbox' name='sug$i' id='sug$i' value='false' class='checksug'></td>";
        echo "</tr>";
    }
    echo "</table><br>";
	
	
	
	
	
	
	
	
	
    //aqui construimos las muestras extra
    echo "<table border='1' cellspacing='0' class='texto' width='100%' align='center'>";
    echo "<tr><th colspan='5'>Cantidad de muestras de productos extra-parrilla entregadas:&nbsp;&nbsp;";
    echo "<select name='muestras_extra' class='textomini' onChange='envia_form(this.form)'>";
    for ($m = 0; $m <= 10; $m++) {
        if ($m == $muestras_extra) {
            echo "<option value='$m' selected>$m</option>";
        } else {
            echo "<option value='$m'>$m</option>";
        }
    }
    echo "</select>";
    echo "</th></tr>";
    echo "<tr><th class='textomini' width='38%'>Muestra</th><th class='textomini' width='11%'>Cantidad Entregada</th><th class='textomini' width='35%'>Material de Apoyo</th><th class='textomini' width='11%'>Cantidad Entregada</th><th class='textomini' width='5%'>&nbsp;</th></tr>";
    $prod_extra = "prod_extra";
    $cant_entregada_extra = "cant_entregada_extra";
    $material_extra = "mat_extra";
    $cant_entregada_apoyo_extra = "cant_entregada_apoyo_extra";
    for ($filas_extra = 1; $filas_extra <= $muestras_extra; $filas_extra++) {
        $var_prod_extra        = "$prod_extra$filas_extra";
        $valor_prod_extra      = $$var_prod_extra;
        $var_cant_ent_extra    = "$cant_entregada_extra$filas_extra";
        $valor_cant_ent_extra  = $$var_cant_ent_extra;
        $var_material_extra    = "$mat_extra$filas_extra";
        $valor_material_extra  = $$var_material_extra;
        $var_cant_apoyo_extra  = "$cant_entregada_apoyo_extra$filas_extra";
        $valor_can_apoyo_extra = $$var_cant_apoyo_extra;
        echo "<tr bgcolor='#66ccff'><td>";
        $sql_prod_extra = "SELECT codigo, descripcion, presentacion from muestras_medicas where estado=1 order by descripcion, presentacion";
        $resp_prod_extra = mysql_query($sql_prod_extra);
        echo "<select name='prod_extra$filas_extra' class='textomini'>";
        while ($dat_extra = mysql_fetch_array($resp_prod_extra)) {
            $codigo_material_extra = $dat_extra[0];
            $nombre_material_extra = "$dat_extra[1] $dat_extra[2]";
            if ($valor_prod_extra == $codigo_material_extra) {
                echo "<option value='$codigo_material_extra' selected>$nombre_material_extra</option>";
            } else {
                echo "<option value='$codigo_material_extra'>$nombre_material_extra</option>";
            }
        }
        echo "</select></td>";
        echo "<td align='center'><select name='cant_entregada_extra$filas_extra' class='textomini'>";
        for ($ce = 0; $ce <= 20; $ce++) {
            if ($valor_cant_ent_extra == $ce) {
                echo "<option value='$ce' selected>$ce</option>";
            } else {
                echo "<option value='$ce'>$ce</option>";
            }
        }
        echo "</select></td>";
        echo "<td><select name='mat_extra$filas_extra' class='textomini'>";
        $sql_apoyo_extra  = "SELECT codigo_material, descripcion_material from material_apoyo where estado='Activo' order by 2 ASC";
        $resp_apoyo_extra = mysql_query($sql_apoyo_extra);
        while ($dat_apoyo_extra = mysql_fetch_array($resp_apoyo_extra)) {
            $codigo_material_extra      = $dat_apoyo_extra[0];
            $descripcion_material_extra = $dat_apoyo_extra[1];
            if ($valor_material_extra == $codigo_material_extra) {
                echo "<option value='$codigo_material_extra' selected>$descripcion_material_extra</option>";
            } else {
                echo "<option value='$codigo_material_extra'>$descripcion_material_extra</option>";
            }
        }
        echo "</select></td>";
        echo "<td align='center'><select name='cant_entregada_apoyo_extra$filas_extra' class='textomini'>";
        for ($cae = 0; $cae <= 20; $cae++) {
            if ($valor_can_apoyo_extra == $cae) {
                echo "<option value='$cae' selected>$cae</option>";
            } else {
                echo "<option value='$cae'>$cae</option>";
            }
        }
        echo "</select></td><td>&nbsp;</td></tr>";
    }
    echo "</table><br>";

	
    /*--------------------------------------------------------------------------------------------------------------------------*/
    echo "<table border='1' cellspacing='0' class='texto' width='50%' align='center'>";
    echo "<tr><th>Sugerir muestras:&nbsp;&nbsp;";
    echo "<select name='muestra_agregar' class='textomini' onChange='envia_form(this.form)'>";
    for ($m = 0; $m <= 10; $m++) {
        if ($m == $muestra_agregar) {
            echo "<option value='$m' selected>$m</option>";
        } else {
            echo "<option value='$m'>$m</option>";
        }
    }
    echo "</select>";
    echo "</th></tr>";
    echo "<tr><th class='textomini' width='100%'>Muestra</th></tr>";
    $prod_agregado = "prod_agregado";
    for ($filas_extra = 1; $filas_extra <= $muestra_agregar; $filas_extra++) {
        $var_prod_agregado = "$prod_agregado$filas_extra";
        $valor_prod_agregado = $$var_prod_agregado;
        echo "<tr bgcolor='#66ccff'><td>";
        $sql_prod_agregado = "SELECT codigo, descripcion, presentacion from muestras_medicas where estado=1 order by descripcion, presentacion";
        $resp_prod_agregado = mysql_query($sql_prod_agregado);
        echo "<select name='prod_agregado$filas_extra' class='textomini'>";
        while ($dat_extra = mysql_fetch_array($resp_prod_agregado)) {
            $codigo_material_extra = $dat_extra[0];
            $nombre_material_extra = "$dat_extra[1] $dat_extra[2]";
            if ($valor_prod_agregado == $codigo_material_extra) {
                echo "<option value='$codigo_material_extra' selected>$nombre_material_extra</option>";
            } else {
                echo "<option value='$codigo_material_extra'>$nombre_material_extra</option>";
            }
        }
        echo "</select></td>";
        echo "</tr>";
    }
    echo "</table><br>";


    echo "<table border='1' cellspacing='0' class='texto' width='30%' align='center'>";
    echo "<tr><th>Porque sugiri&oacute; quitar o agregar una muestras al m&eacute;dico?:&nbsp;&nbsp;";
    echo "</th></tr>";
    echo "<tr>";
    echo "<td>";
    echo "<textarea name='porque' cols='50' rows='8'></textarea>";
    echo "</td>";
    echo "</tr>";
    echo "</table><br>";



    /*--------------------------------------------------------------------------------------------------------------------------*/


    echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1);'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
    echo "<table border='0' align='center'>";
    echo "<input type='hidden' name='valores' value='$contacto-$orden_visita-$parrilla-$i'>";
    echo "<input type='hidden' name='cod_contacto' value='$cod_contacto'>";
    echo "<input type='hidden' name='visita' value='$visita'>";
    echo "<input type='hidden' name='cod_med' value='$cod_med'>";
    echo "<input type='hidden' name='cod_espe' value='$especialidad'>";
    echo "<input type='hidden' name='cat_med' value='$categoria'>";
    echo "<input type='hidden' name='cod_dia' value='$codDiaContacto'>";
    echo "<input type='hidden' name='codigo_parrilla' value='$codigo_parrilla'>";


    echo "<tr>
    <th><input type='button' OnClick='guarda_form(this.form)' class='boton' value='Guardar'></th>
</tr>";
echo "</form>";
echo "</table>";
} else {
    /*echo "<script language='Javascript'>
    alert('No existe ninguna parrilla definida para la especialidad $especialidad y la categoria $categoria');
    window.close();
</script>";*/
}
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";
?>