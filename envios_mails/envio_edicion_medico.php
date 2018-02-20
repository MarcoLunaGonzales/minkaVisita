<?php

require("../lib/phpmailer/class.phpmailer.php");
require("../conexion.inc");


$medico = $_GET['medico'];
$territorio2 =  $_GET['territorio'];
$dia =  $_GET['dia'];
$visitador2 =  $_GET['visitador'];

$sql_territorio = mysql_query("select descripcion from ciudades where cod_ciudad = $territorio2");
$territorio = mysql_result($sql_territorio, 0,0);

$sql_visitador = mysql_query("SELECT CONCAT(nombres,' ',paterno,' ',materno) from funcionarios where codigo_funcionario = $visitador2 ");
$visitador = mysql_result($sql_visitador,0,0);

$mail = new PHPMailer();


$mail->AddReplyTo("alta_hermes@cofar.com.bo","Alta Medicos");

$mail->SetFrom('alta_hermes@cofar.com.bo','Alta Medicos');

$mail->AddReplyTo('alta_hermes@cofar.com.bo','Alta Medicos');

$address = "mpacheco@cofar.com.bo";
//$address2 = "emejia@gmail.com";
$address3 = "jarze@gmail.com";
$mail->AddAddress($address, "Dr. Miguel A. Pacheco");
//$mail->AddAddress($address2, "Edwin Mejia");
$mail->AddAddress($address3, "Jorge Arze");

$mail->Subject    = "Se modifico un Alta de medico'$territorio' - Visitador '$visitador' ";

$mail->AltBody    = "Se modific&oacute; la solicitud  del m&eacute;dico $medico en la regional $territorio el d&iacute;a $dia"; // optional, comment out and test

$mail->MsgHTML("Estimados Se&ntilde;or@s: <br /> <br />Se modific&oacute; la solicitud  del m&eacute;dico <strong>$medico</strong> en la regional <strong>$territorio</strong> el d&iacute;a <strong>$dia</strong>. <br /> <br />
                            <i>/* Este es un mensaje autom&aacute;tico. Por favor no responda a este correo. */</i>");


if(!$mail->Send()) {
//  echo "Mailer Error: " . $mail->ErrorInfo;
  echo "<script language='Javascript'>
  alert('Error al enviar. Los datos se guardaron pero no se envio el mail. Por favor notifiquelo.');
  location.href='../medicos_solicitados_lista.php';
  </script>";
} else {
  echo "<script language='Javascript'>
  alert('Los datos se enviaron correctamente.');
  location.href='../medicos_solicitados_lista.php';
  </script>";
}

?>
    