<?php

//$usuario=$_post['usuario'];
//$contrasena=$_post['contrasena'];
require("conexion.inc");
require("lib/phpmailer/class.phpmailer.php");

$sql = "SELECT f.cod_cargo, f.cod_ciudad from funcionarios f, usuarios_sistema u where u.codigo_funcionario=f.codigo_funcionario 
and u.codigo_funcionario='$usuario' and u.contrasena='$contrasena'";

//echo $sql;

$resp = mysql_query($sql);
$num_filas = mysql_num_rows($resp);

function verifica_fecha_caducidad($usuario){
    $sql_veriica_fecha = mysql_query("SELECT fecha_caducidad from usuarios_sistema where codigo_funcionario = $usuario");
    $fecha_caducidad = mysql_result($sql_veriica_fecha, 0, 0);
    $fecha_actual = date('Y-m-d');
    $fecha1 = new DateTime($fecha_actual);
    $fecha2 = new DateTime($fecha_caducidad);
    $fecha = $fecha1->diff($fecha2);
    if($fecha->m <= 2){
        $mail = new PHPMailer();

        $mail->AddReplyTo("contrasena@cofar.com.bo","Sistema Contrasenas");
        $mail->SetFrom("contrasena@cofar.com.bo","Sistema Contrasenas");
        $mail->AddReplyTo("contrasena@cofar.com.bo","Sistema Contrasenas");
        $address3 = "jarze@cofar.com.bo";
        $mail->AddAddress($address3, "Jorge Arze");
        $mail->Subject    = "Su contraseña expirará en $fecha->m";
        $mail->AltBody    = "Su contraseña expirará en $fecha->m, por favor cambiela antes de este tiempo."; // optional, comment out and test
        $mail->MsgHTML("Estimado Usuario: <br /> <br />Se le solicita cambiar su contraseña antes de que expire. Esto ocurrira dentro de <strong>%fecha-m</strong> meses. <br /> <br /> <i>/* Este es un mensaje autom&aacute;tico. Por favor no responda a este correo. */</i>");


        if(!$mail->Send()){
            echo "Mailer Error: " . $mail->ErrorInfo;
        } 
    }
}

//este cookie es para el visitador
if ($num_filas != 0) {
    $dat = mysql_fetch_array($resp);
    $cod_cargo = $dat[0];
    $cod_ciudad = $dat[1];
    if ($cod_cargo == 1011) {
        setcookie("global_visitador", $usuario);
        setcookie("global_usuario", $usuario);
        setcookie("global_agencia", $cod_ciudad);
		header("location:indexVisitador.php");
 }
 
//este cookie es para el jefe regional o el supervisor
if ($cod_cargo == 1001 or $cod_cargo == 1002) {
    setcookie("global_usuario", $usuario);
    setcookie("global_agencia", $cod_ciudad);
	header("location:indexSupervision.php");
}
 
 //este cookie es para el jefe de linea
if ($cod_cargo == 1007 or $cod_cargo == 1012) {
    setcookie("global_usuario", $usuario);
    // verifica_fecha_caducidad($usuario);
    //header("location:inicio_administracion.php");
    header("location:index_central.html");
}



//este cookie es para el GG(solo reportes) o jefe promocion medica
//PRIMERA MODIFICACION DE MINKA
	
if ($cod_cargo == 1013 or $cod_cargo == 1014) {
    setcookie("global_usuario", 1052);
    setcookie("global_nickname", $usuario);
	setcookie("global_agencia",0);
    header("location:indexGerencia.php");
}

if ($cod_cargo == 1019) {
    setcookie("global_usuario", $usuario);
    setcookie("global_nickname", $usuario);
    header("location:index_gerencia.html");
}

if ($cod_cargo == 1018) {
    setcookie("global_usuario", $usuario);
    setcookie("global_nickname", $usuario);
    header("location:index_superusuario.html");
}

if ($cod_cargo == 1020) {
    setcookie("global_usuario", $usuario);
    setcookie("global_nickname", $usuario);
    setcookie("global_tipoalmacen", 1);
    header("location:index_logistica.html");
}

if ($cod_cargo == 1021) {
    setcookie("global_usuario", $usuario);
    setcookie("global_nickname", $usuario);
    header("location:index_central2.html");
}

if ($cod_cargo == 1022) {
    setcookie("global_visitador", $usuario);
    setcookie("global_usuario", $usuario);
    setcookie("global_agencia", $cod_ciudad);
    header("location:index_consultora.html");
}
 
    //responsables de almacenes
if ($cod_cargo == 1016) {
    setcookie("global_usuario", $usuario);
    setcookie("global_agencia", $cod_ciudad);
	
	if($usuario==1062 || $usuario==1416 || $usuario==1417 || $usuario==1438 || $usuario==1470){
		$usuario=1061;
	}
	$sql_almacen="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$cod_ciudad' 
	and responsable_almacen='$usuario'";
	
	//echo $sql_almacen;
	
	$resp_almacen=mysql_query($sql_almacen);
	$almacenXXX=mysql_result($resp_almacen,0,0);
	
	//echo $almacenXXX." :x";
	
	setcookie("global_almacen", $almacenXXX);
    // verifica_fecha_caducidad($usuario);
    if ($cod_ciudad == 115) {
        setcookie("global_tipoalmacen", 1);
		header("location:index_almacencentral.html");
    } else {
        setcookie("global_tipoalmacen", 2);
        header("location:index_almacenregional.html");
	}
}
    //usuarios vista almacenes
if ($cod_cargo == 1017) {
    setcookie("global_usuario", $usuario);
    setcookie("global_agencia", $cod_ciudad);
    // verifica_fecha_caducidad($usuario);
    if ($usuario == 1129) {
        header("location:inicio_almacenregionalconsulta.php");
        setcookie("global_tipoalmacen", 2);
    }
    if ($usuario == 1120) {
        setcookie("global_tipoalmacen", 1);
        setcookie("global_almacen", 1000);
        header("location:index_almacencentral.html");
    }
}
} else {
    echo "<link href='stilos.css' rel='stylesheet' type='text/css'>";
    echo "<form action='problemas_ingreso.php' method='post' name='formulario'>
    <center>
    <table class='texto'><tr><th>Usuario No Registrado<br>Problemas con la contrase&ntilde;a</th></tr></table>
    </center><br>
    <center><table class='texto'>
    <tr><td>No recuerdo</td><td><select name='parametro' class='texto'>
    <option value='0'>Mi contrase&ntilde;a</option>
    <option value='1'>Mi usuario y contrase&ntilde;a</option>
    </select></td></tr>
    <tr><td>Usuario</td><td><input type='text' name='usuario' ></td></tr>
    <tr><td>Email</td><td><input type='text' name='email' ></td></tr>
    </table></center>
    <table align='center'><tr><td><a href='index1.html'><img  border='0' src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>
    <center><table><tr><td><input class='boton' type='submit' name='aceptar' value='Aceptar'></td></tr></table></center>
    </form>";
}
