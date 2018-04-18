<?php

//$usuario=$_post['usuario'];
//$contrasena=$_post['contrasena'];
require("conexionInicial.inc");
require("lib/phpmailer/class.phpmailer.php");

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

$sql = "SELECT f.cod_cargo, f.cod_ciudad, f.codigo_funcionario from funcionarios f, usuarios_sistema u 
where u.codigo_funcionario=f.codigo_funcionario 
and u.nombre_usuario='$usuario' and u.contrasena='$contrasena'";

$resp = mysql_query($sql);
$num_filas = mysql_num_rows($resp);

if ($num_filas != 0) {
	
	session_start();
	
    $dat = mysql_fetch_array($resp);
    $cod_cargo = $dat[0];
    $cod_ciudad = $dat[1];
	$usuario=$dat[2];
	
    if ($cod_cargo == 1000) {
        setcookie("global_usuario", $usuario);
        setcookie("global_agencia", $cod_ciudad);
		header("location:indexAdmin.php");
	}	
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
	 
	/*ESTE ESTA PENDIENTE DE REVISION 
	//este cookie es para el jefe de linea
	if ($cod_cargo == 1007 or $cod_cargo == 1012) {
		setcookie("global_usuario", $usuario);
		// verifica_fecha_caducidad($usuario);
		//header("location:inicio_administracion.php");
		header("location:index_central.html");
	}*/

	//este cookie es para el administrador Gerente o Jefe de Promocion
	//PRIMERA MODIFICACION DE MINKA
		
	if ($cod_cargo == 1014) {
		setcookie("global_visitador", $usuario);
        setcookie("global_usuario", $usuario);
        setcookie("global_agencia", $cod_ciudad);
		setcookie("global_nickname", $usuario);
		header("location:indexGerencia.php");
	}

	//ALMACENES
	if ($cod_cargo == 1016) {
		setcookie("global_usuario", $usuario);
		setcookie("global_agencia", $cod_ciudad);
		
		$sql_almacen="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$cod_ciudad' 
		and responsable_almacen='$usuario'";

		$resp_almacen=mysql_query($sql_almacen);
		$almacenXXX=mysql_result($resp_almacen,0,0);

		setcookie("global_almacen", $almacenXXX);
		header("location:indexAlmacen.php");
	}


	/*if ($cod_cargo == 1017) {
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
	}*/

} 
else {
    echo "<h1>Usuario No Registrado!</h1>";
}
