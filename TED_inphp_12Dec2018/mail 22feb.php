<?php
require 'inc/PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.bizmail.yahoo.com';  // Specify main and backup server
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'alert@tendersontime.com';                            // SMTP username
$mail->Password = '0ZavJTYVn8mrIDoe';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
$mail->From = 'alert@tendersontime.com';
$mail->FromName = 'Alert';
$mail->addAddress('abhishek.singh@tendersontime.com');
$mail->addAddress('saalim.bhoraniya@tendersontime.com');
$mail->addAddress('sanjaykvyas@gmail.com');
$mail->addAddress('vrakeshn@gmail.com');
$mail->addAddress('ashwin.gupta@tendersontime.com');
$mail->addAddress('abhimishra121194@gmail.com');

$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = 'List of TED Skiped XML file '.date('l jS \of F Y h:i:s A',time());
$mail->Body    = $mailData;

if(!$mail->send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
   exit;
}
echo 'Message has been sent';