<?php

require("../lib/phpmailer/class.phpmailer.php");
require("../conexion.inc");


$medico2 = $_GET['medico'];
$visitador2 =  $_GET['visitador'];

$sql_visitador = mysql_query("SELECT CONCAT(nombres,' ',paterno,' ',materno) from funcionarios where codigo_funcionario = $visitador2 ");
$visitador = mysql_result($sql_visitador,0,0);

$sql_medico = mysql_query("SELECT CONCAT(nom_med,' ',ap_pat_med,' ',ap_mat_med) from medicos where cod_med = $medico2");
$medico = mysql_result($sql_medico, 0, 0);

$date = date('d/m/Y');

$mail = new PHPMailer();


$mail->AddReplyTo("edictio_banco_muestras@cofar.com.bo","Edicion de Banco de Muestras");

$mail->SetFrom("edictio_banco_muestras@cofar.com.bo","Edicion de Banco de Muestras");

$mail->AddReplyTo("edictio_banco_muestras@cofar.com.bo","Edicion de Banco de Muestras");

$address = "mpacheco@cofar.com.bo";
$address2 = "jarze@cofar.com.bo";
$mail->AddAddress($address, "Dr. Miguel A. Pacheco");
$mail->AddAddress($address2, "Jorge Arze");

$mail->Subject    = "Edicion de BM - Medico '$medico' ";

$mail->AltBody    = "Se modifico el Banco de Muestras del medico: '$medico' por solicitud del funionario: '$visitador' el d&iacute;a de hoy($date)"; // optional, comment out and test

$mail->MsgHTML("Estimados Se&ntilde;or@s: <br /> <br />Se modific&oacute; el Banco de Muestras del m&eacute;dico <strong>$medico</strong> por solicitud del funcionario: <strong>$visitador</strong> el d&iacute;a de hoy<strong>($date)</strong>. <br /> <br /> Por favor tomar en cuenta para la aprobaci&oacute;n o no en el Banco de Muestras.<br /> <br /><i>/* Este es un mensaje autom&aacute;tico. Por favor no responda a este correo. */</i>");


if(!$mail->Send()) {
	echo "<script language='Javascript'>
	location.href='../calculo_banco_muestras.php';
	</script>";
} else {
	echo "<script language='Javascript'>
	location.href='../calculo_banco_muestras.php';
	</script>";
}

?>
