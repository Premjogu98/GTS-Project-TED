<?php
require 'inc/PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.mdplisting.com';  // Specify main and backup server
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'serveralert@mdpli.com';                            // SMTP username
$mail->Password = 'go#QCT!0';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
$mail->From = 'serveralert@mdplisting.com';
$mail->FromName = 'Site admin';
//$mail->addAddress('sanjaykvyas@gmail.com', 'Sanjay Vyas');  // Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
$mail->addReplyTo('amit.mourya@tendersontime.com', 'Site Admin');
//$mail->addCC('vrakeshn@gmail.com','Rakesh Verma');
//$mail->addCC('heartpatel@gmail.com','Hriday Patel');
$mail->addBCC('amit.mourya@tendersontime.com');
$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
//$mail->addAttachment($my_file);         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = 'Test mail';
$mail->Body    = 'Dear Sir,<br/> The Automation Report of '.date('d-M-Y',time()).' is attached,So please find attached file ';
$mail->AltBody = 'Dear Sir,<br/> The Automation Report of '.date('d-M-Y',time()).' is attached,So please find attached file ';

if(!$mail->send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
   exit;
}
echo 'Message has been sent';