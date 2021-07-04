<?PHP 
// header('Content-type: text/html; charset=UTF-8');
class Email {
	public function smtp_envio($email,$assunto,$msg){
		include("mail/phpmailer/class.phpmailer.php");
		$mail = new PHPMailer();
try {
#configurações phpmailer
	$mail->IsSMTP();
	$mail->IsHTML(true);
	$mail->Charset= "UTF-8";	
	$mail->SMTPAuth = true;
	
#configurações smtp
	$mail->Host = "smtp.maissport.com.br";
    $mail->Port = 587;
	$mail->Username = "contato=maissport.com.br";
    $mail->Password = "alemanha.2018[contato]";
#dados do demetente
    $mail->From = "contato@maissport.com.br";
    $mail->FromName = "Site MaisSport";
	$mail->Subject = utf8_decode($assunto);
#corpo	
	$mail->Body = utf8_decode($msg);
	$mail->AltBody = utf8_decode($msg);
#destinatario	
    $mail->AddAddress($email);
    $mail->AddReplyTo("contato@maissport.com.br", '');
    
    if ($mail->Send()) {
        $mail->ClearAllRecipients();
        $mail->ClearAttachments();
		return TRUE;
    } else {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
} catch (phpmailerException $e) {
    echo $e->errorMessage(); //Pretty error messages from PHPMailer
}
}
}
		