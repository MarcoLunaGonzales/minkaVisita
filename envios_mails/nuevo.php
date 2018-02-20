<?php 

require("../lib/phpmailer/class.phpmailer.php");
$fecha_actual = date('Y-m-d');
$fecha_caducidad = '2013-06-06';
// $fecha1 = new DateTime($fecha_actual);
// $fecha2 = new DateTime($fecha_caducidad);
// $fecha = $fecha1->diff($fecha2);
// $fecha = round($end->format('U') - $start->format('U') / (60*60*24));

$segundos=strtotime($fecha_caducidad) - strtotime($fecha_actual);
$diferencia_dias=intval($segundos/60/60/24/30);

if($diferencia_dias <= 2){
	$mail = new PHPMailer();


	$mail->AddReplyTo("contrasena@cofar.com.bo","Sistema Contrasenas");

	$mail->SetFrom("contrasena@cofar.com.bo","Sistema Contrasenas");

	$mail->AddReplyTo("contrasena@cofar.com.bo","Sistema Contrasenas");

	$address = "mluna@cofar.com.bo";
	$address2 = "jarze@cofar.com.bo";
	$mail->AddAddress($address, "Marco Luna");
	$mail->AddAddress($address2, "Jorge Arze");

	$mail->Subject    = "Su contrasena expirara en $diferencia_dias mes(es)";

	$mail->AltBody    = "Su contrasena expirara en $diferencia_dias, por favor cambiela antes de este tiempo."; 

	$mail->MsgHTML("Estimado Usuario: <br /> <br />Se le solicita cambiar su contrasena antes de que expire. Esto ocurrira dentro de <strong>$diferencia_dias</strong> mes(es). <br /> <br /> <i>/* Este es un mensaje autom&aacute;tico. Por favor no responda a este correo. */</i>");


	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} 
}
?>