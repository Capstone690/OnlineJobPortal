<?php
if(!class_exists('PHPMailer')) {
    require('public/phpmailer/class.phpmailer.php');
	require('public/phpmailer/class.smtp.php');
}

require_once("include/mail_configuration.php");

$mail = new PHPMailer();

$emailBody = "<div>Hello Admin,<br><br><p> <a href='" . PROJECT_HOME . "reset-password.php?id=" . $user["id"] . "'>Click here</a> to recover your password<br><br></p>Regards,<br> Admin.</div>";
echo $emailBody;
$mail->IsSMTP();
$mail->SMTPDebug = 0;
$mail->SMTPAuth = TRUE;
$mail->SMTPSecure = "tls";
$mail->Port     = PORT;
$mail->Username = MAIL_USERNAME;
$mail->Password = MAIL_PASSWORD;
$mail->Host     = MAIL_HOST;
$mail->Mailer   = MAILER;

$mail->SetFrom(SERDER_EMAIL, SENDER_NAME);
$mail->AddReplyTo(SERDER_EMAIL, SENDER_NAME);
$mail->ReturnPath=SERDER_EMAIL;
$mail->AddAddress($user["email"]);
$mail->Subject = "Forgot Password Recovery";
$mail->MsgHTML($emailBody);
$mail->IsHTML(true);

if(!$mail->Send()) {
	$error_message = 'Problem in Sending Password Recovery Email';
} else {
	$success_message = 'Please check your email to reset password!';
}

?>
