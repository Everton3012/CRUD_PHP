<?php 

use PHPMailer\PHPMailer\PHPMailer;

function enviar_email($destinatario, $assunto , $msgHTML) {
    require 'vendor/autoload.php';

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = 'smtp.ethereal.email';
    $mail->SMTPAuth = true;
    $mail->Username = 'hillard40@ethereal.email';
    $mail->Password = 'ADSgAup1cBYCw6mZyj';
    $mail->SMTPSecure = 'tls';
    $mail->setFrom("teste@ethereal.com", "teste PHP Mailer");
    $mail->Port = 587;
    $mail->isHTML(true);
    $mail->CharSet = "UTF-8";

    $mail->addAddress($destinatario);
    $mail->Subject = $assunto;
    $mail->Body = $msgHTML;

    if($mail->send()){
        return true;
    } else {
        return false;
    }

}

?>
