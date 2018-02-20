<?php
require("conexion.inc");
// require("estilos_administracion.inc");

function validar_clave($clave,&$error_clave){
	if(strlen($clave) < 8){
		$error_clave = "La clave debe tener al menos 8 caracteres";
		return false;
	}
	if (!preg_match('`[a-z]`',$clave)){
		$error_clave = "La clave debe tener al menos una letra minúscula";
		return false;
	}
	if (!preg_match('`[A-Z]`',$clave)){
		$error_clave = "La clave debe tener al menos una letra mayúscula";
		return false;
	}
	if (!preg_match('`[0-9]`',$clave)){
		$error_clave = "La clave debe tener al menos un caracter numérico";
		return false;
	}
	if (!preg_match('`[-_$@...]`',$clave)){
		$error_clave = "La clave debe tener al menos un caracter especial";
		return false;
	}
	$error_clave = "";
	return true;
}

$date = date('Y-m-d');
$nuevafecha = strtotime ( '+6 month' , strtotime ( $date ) ) ;
$nuevafecha = date ( 'Y-m-d' , $nuevafecha );


$error_encontrado="";

if (validar_clave($contrasena, $error_encontrado)){
	$sql_inserta=mysql_query("INSERT into usuarios_sistema values($codigo_funcionario,'$contrasena', '$nuevafecha')");
	$sql_nombre_fun="select paterno, materno, nombres from funcionarios where codigo_funcionario='$codigo_funcionario'";
	$resp_nombre_fun=mysql_query($sql_nombre_fun);
	$dat_nombre_fun=mysql_fetch_array($resp_nombre_fun);
	$nombre_funcionario="$dat_nombre_fun[0] $dat_nombre_fun[1] $dat_nombre_fun[2]";
	$sql_mail="select email from funcionarios where codigo_funcionario=$codigo_funcionario";
	$resp_mail=mysql_query($sql_mail);
	$dat_mail=mysql_fetch_array($resp_mail);
	$mail_funcionario=$dat_mail[0];
	if($mail_funcionario!="") {
		$correo = $mail_funcionario;
		$asunto="Alta en HERMES";
		$mensaje="Usted fue dado de alta en el sistema HERMES sus datos para el ingreso son: Nombre de Usuario:	$codigo_funcionario,password:			$contrasena";
		$adicionales="FROM:Administrador del sistema HERMES";
		// $url_origen="http://172.16.10.101:8080/actuall/navegador_funcionarios.php?cod_ciudad=$cod_territorio";
		// echo "<script language='JavaScript'>location.href='http://www.cofar.com.bo/envia_correo_hermes.php?correo_para=$correo&asunto=$asunto&mensaje=$mensaje&adicionales=$adicionales&nombre_funcionario=$nombre_funcionario&url_origen=$url_origen';</script>";
		mail("$correo","$asunto","$mensaje","$adicionales");
		mail("recursoshumanos@cofar.com.bo","Alta de usuario en sistema HERMES","El funcionario $nombre_funcionario fue dado de alta en HERMES. \n El nombre de usuario es: $codigo_funcionario \n La Contraseña: $contrasena \n Fecha de caducidad: $nuevafecha","$adicionales");
		//header("location:http://www.cofar.com.bo/envia_correo_hermes.php?correo_para=$correo&asunto=$asunto&mensaje=$mensaje&adicionales=$adicionales&nombre_funcionario=$nombre_funcionario&url_origen=$url_origen");
		echo "<script language='Javascript'>
		alert('Los datos fueron Ingresados correctamente.');
		location.href='navegador_funcionarios.php?cod_ciudad=$cod_territorio';
		</script>";
	}
}else{
	echo "<script language='Javascript'>
	alert('".$error_encontrado."');
	history.go(-1);
	</script>";
}


?>
