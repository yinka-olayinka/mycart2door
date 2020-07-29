<?php
require 'PHPMailerAutoload.php';
include_once('mail/mail.php');

/*$email = $_GET['email'];
$fname = $_GET['fname'];
$organisation = $_GET['organisation'];
$mobile = $_GET['mobile'];
$country = $_GET['country'];
$position = $_GET['position'];*/ // TCP port to connect to

function postmail($fname,$fone,$email,$status,$descript){
    $message    =   'Full Name : '.$fname.'<br>Contact Phone : '.$fone.'<br>Email Address : '.$email.'<br>Job Status : '.$status.'<br>Job Description : '.$descript;
   /* $message    .=  'Contact Phone : '.$fone;
    $message    .=  'Email Address : '.$email;
    $message    .=  'Job Status : '.$status;
    $message    .=  'Job Description : '.$descript;
*/
    //$mail->SMTPDebug = 2;                               // Enable verbose debug output
    $mail = new PHPMailer;
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'mail.lonadek.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'executive@lonadek.com';                 // SMTP username
    $mail->Password = 'P@$$w0rd';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 26;
    
    $mail->setFrom('no-reply@lonadek.com', 'Lonadek-IBM Mobile App Online Registration Form');
    $mail->addAddress('training@lonadek.com','Lonadek-IBM Mobile App');     // Add a recipient
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Lonadek-IBM Mobile App Development Training Online Registration';
    $mail->Body    = $message;
    
    $mail->AltBody = $message;
    
    if(!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
    else{
        sendmail($fname, $email);
    }
    
    return(1);
}