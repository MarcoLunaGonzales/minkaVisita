<?php
require("conexion.inc");
// require("estilos_administracion.inc");

function validar_clave($clave,&$error_clave){
	if(strlen($clave) < 8){
		$error_clave = "La clave debe tener al menos 8 caracteres";
		return false;
	}
	if (!preg_match('`[a-z]`',$clave)){
		$error_clave = "La clave debe tener al menos una letra min�scula";
		return false;
	}
	if (!preg_match('`[A-Z]`',$clave)){
		$error_clave = "La clave debe tener al menos una letra may�scula";
		return false;
	}
	if (!preg_match('`[0-9]`',$clave)){
		$error_clave = "La clave debe tener al menos un caracter num�rico";
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
	
	$txtUpd="UPDATE usuarios_sistema set contrasena='$contrasena', nombre_usuario='$nombreusuario',
	fecha_caducidad = '$nuevafecha' where codigo_funcionario='$codigo_funcionario'";
	$sql_update=mysql_query($txtUpd);
	// echo "update usuarios_sistema set contrasena='$contrasena' where codigo_funcionario=$codigo_funcionario";
		//esta parte envia el mail al usuario
	
	/*$sql_mail="select email from funcionarios where codigo_funcionario=$codigo_funcionario";
	$resp_mail=mysql_query($sql_mail);
	$dat_mail=mysql_fetch_array($resp_mail);
	$mail_funcionario=$dat_mail[0];
	if($mail_funcionario!="") {
		$adminaddress = $mail_funcionario;
		$siteaddress ="http://www.cofar.com.bo";
		$sitename = "COFAR S.A.";
		$date = date("m/d/Y H:i:s");
		mail($mail_funcionario,"Cambio de Contrase�a en HERMES", "Su contrase�a cambio 
			en el sistema HERMES sus datos para el ingreso ahora son:
			Nombre de Usuario:	$codigo_funcionario,
			password:			$contrasena,
			Fecha de caducidad: $nuevafecha","FROM:Administrador del sistema HERMES");

			$sendresult = "Muchas_gracias_en_breve_nos_comunicaremos_con_usted";
			$send_answer = "Respuesta=";
			$send_answer .= rawurlencode($sendresult);
	}
	*/
	
	echo "<script language='Javascript'>
	alert('Los datos fueron modificados correctamente.');
	location.href='navegador_funcionarios.php?cod_ciudad=$cod_territorio';
	</script>";
} else{
	echo "<script language='Javascript'>
	alert('".$error_encontrado."');
	history.go(-1);
	</script>";
}
?>