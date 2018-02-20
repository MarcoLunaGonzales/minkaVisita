<?php
require("conexion.inc");
require("estilos_regional_pri.inc");
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
if (validar_clave($clave, $error_encontrado)){
	$sql_update=mysql_query("UPDATE usuarios_sistema set contrasena='$clave', fecha_caducidad = '$nuevafecha' where codigo_funcionario='$codigo_funcionario'");
	// echo("UPDATE usuarios_sistema set contrasena='$clave', fecha_caducidad = '$nuevafecha' where codigo_funcionario='$codigo_funcionario'");
		//esta parte envia el mail al usuario
	$sql_mail="select email from funcionarios where codigo_funcionario=$codigo_funcionario";
	$resp_mail=mysql_query($sql_mail);
	$dat_mail=mysql_fetch_array($resp_mail);
	$mail_funcionario=$dat_mail[0];
	// $mail_funcionario='jarze@cofar.com.bo';
	if($mail_funcionario!="") {
		$adminaddress = $mail_funcionario;
		$siteaddress ="http://www.cofar.com.bo";
		$sitename = "COFAR S.A.";
		$date = date("m/d/Y H:i:s");
		mail($mail_funcionario,"Cambio de Contraseña en HERMES", "Su contraseña cambio 
			en el sistema HERMES sus datos para el ingreso ahora son:
			Nombre de Usuario:	$codigo_funcionario,
			password:			$clave,
			Fecha de caducidad: $nuevafecha","FROM:Administrador del sistema HERMES");

			/*$sendresult = "Muchas_gracias_en_breve_nos_comunicaremos_con_usted";
			$send_answer = "Respuesta=";
			$send_answer .= rawurlencode($sendresult);*/
	}
	//fin enviar mail
	echo "<script language='Javascript'>
	alert('Los datos fueron modificados correctamente.');
	location.href='inicio_mensajes.php';
	</script>";
} else{
	echo "<script language='Javascript'>
	alert('".$error_encontrado."');
	location.href='cambiar_contrasena_visitador.php';
	</script>";
	// location.href='restablecer_contrasena.php?cod_ciudad=$cod_territorio&codigo_funcionario=$codigo_funcionario';
}
?>