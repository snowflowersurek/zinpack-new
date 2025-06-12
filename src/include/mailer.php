<?php
require_once "phpmailer/src/Exception.php";
require_once "phpmailer/src/OAuth.php";
require_once "phpmailer/src/PHPMailer.php";
require_once "phpmailer/src/POP3.php";
require_once "phpmailer/src/SMTP.php";

use PHPMailer\PHPMailer\POP3;

function mailer($fromName, $from, $to, $subject, $content, $isHtml) {
	$result = false;
	
	try {
		$pop = POP3::popBeforeSmtp("mail.wizwindigital.com", 110, 30, "zinpack", "Wizwin0)", 0);
		$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
		
		// Test code
// 		$fromName = "ZINPACK";
// 		$from = "no-reply@wizwindigital.com";
// 		$to = "zinpack@wizwindigital.com";
// 		$subject = "Here is the subject";
// 		$content = "This is the HTML message body <b>in bold!</b>";
// 		$content = "This is the body in plain text for non-HTML mail clients";
		if (!$isHtml) {
			$content = nl2br($content);
		}
		
		// Server settings
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->SMTPAuth = true;
		$mail->SMTPAutoTLS = true;
		$mail->SMTPSecure = "";
		$mail->SMTPOptions = array(
			"ssl" => array(
				"verify_peer" => false,
				"verify_peer_name" => false,
				"allow_self_signed" => true
			)
		);
		$mail->Host = "mail.wizwindigital.com";
		$mail->Port = 25;
		$mail->Username = "zinpack";
		$mail->Password = "Wizwin0)";
		$mail->CharSet = "UTF-8";
		$mail->Encoding = "base64";
		
		// Sender
		$mail->From = $from;
		$mail->FromName = $fromName;
		
		// Recipients
		if (strpos($to, ",") !== false){
			$tmp_to_ary = explode(",", $to);
			
			foreach($tmp_to_ary as $tt){
				if ($tt) {
					$mail->addAddress($tt);
				}
			}
		} elseif (strpos($to, ";") !== false){
			$tmp_to_ary = explode(";", $to);
			
			foreach($tmp_to_ary as $tt){
				if ($tt) {
					$mail->addAddress($tt);
				}
			}
		} else {
			$mail->addAddress($to);
		}
		
// 		$mail->addReplyTo("info@example.com", "Information");
// 		$mail->addCC("cc@example.com");
// 		$mail->addBCC("bcc@example.com");
		
		// Attachments
//		$mail->addAttachment("/tmp/image.jpg", "new.jpg"); // Add attachments. Optional name
		
		// Content
		$mail->isHTML($isHtml);
		$mail->Subject = $subject;
		$mail->Body = $content;
		$mail->AltBody = $content;
		
		$mail->send();
		$result = true;
	} catch (Exception $e) {
// 		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		$result = false;
	}
	
	return $result;
}
?>
