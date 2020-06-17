<?php
require 'inc/class.phpmailer.php';
$mail = new PHPMailer(true);
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'web.tendersontime.com';  // Specify main and backup server
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Port = 465; // or 587
$mail->Username = 'serveralert@tendersontime.com';                            // SMTP username
$mail->Password = 'saM8X9L92*~`VH)%fKYlOo0sa';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
$mail->From = 'alert@tendersontime.com';
$mail->FromName = 'Alert';
$mail->addAddress('abhishek.singh@tendersontime.com');
$mail->addAddress('saalim.bhoraniya@tendersontime.com');
$mail->addAddress('sanjaykvyas@gmail.com');
$mail->addAddress('vrakeshn@gmail.com');
$mail->addAddress('ashwin.gupta@tendersontime.com');
$mail->addAddress('abhimishra121194@gmail.com');


$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = 'List of TED Skiped Or, HTML missing content XML file '.date('l jS \of F Y h:i:s A',time());
//$mail->Subject = 'Testing on Ted'.date('l jS \of F Y h:i:s A',time());
$mail->Body    = $mailData;

if(!$mail->send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
   exit;
}
echo 'Message has been sent';

?>