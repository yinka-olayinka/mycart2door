<?php
    require 'PHPMailerAutoload.php';

    function sendmail($fname,$email,$sbj,$message){
        //$mail->SMTPDebug = 2;                               // Enable verbose debug output
        $mail               =   new PHPMailer();
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host         =   'mail.mycart2door.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth     =   true;                               // Enable SMTP authentication
        $mail->Username     =   'noreply@mycart2door.com';                 // SMTP username
        $mail->Password     =   '@My2d00r@';                           // SMTP password
        $mail->SMTPSecure   =   'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port         =   465;
        $mail->CharSet      =   'UTF-8';
        
        $mail->setFrom('noreply@mycart2door.com', $sbj);
        $mail->addAddress($email, $fname);     // Add a recipient
        $mail->isHTML(true);
        
        $mail->Subject      =   $sbj;
        $mail->Body         =   $message;

        $mail->AltBody      =   $message; /*'<div style="white-space:normal;border:thin solid #336699;">
                                    <div style="width:100%;padding:10px;color:white;background-color:#336699;"><strong>LITF - SECURITY CODE</strong></div>
                                    <div style="width:100%;padding:50px;white-space:normal;">
                                        Dear '.ucwords($fname).',<br><br>
                                        Thank you for registering for the Lagos International Trade Fair event.<br>Your security code is : <br><br><span style="background-color:#d6e0f5;border:thin solid #6f92dc;padding:5px;color:red;margin-top:10px;box-sizing:border-box;"><strong>'.$token.'</strong></span>
                                        <br><br>Regards<br><b>Trade Fair Promotion Team</b>
                                    </div>
                                </div>';*/
        
        if(!$mail->send()) {
            return '<div>Mailer Error: ' . $mail->ErrorInfo.'</div>';
        }
        
        return(1);
    }
    function sendmailWithAttachment($fname,$email,$sbj,$message,$attach_src){
        //$mail->SMTPDebug = 2;                               // Enable verbose debug output
        $mail               =   new PHPMailer();
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host         =   'mail.mycart2door.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth     =   true;                               // Enable SMTP authentication
        $mail->Username     =   'noreply@mycart2door.com';                 // SMTP username
        $mail->Password     =   '@My2d00r@';                           // SMTP password
        $mail->SMTPSecure   =   'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port         =   465;
        $mail->CharSet      =   'UTF-8';
        
        $mail->setFrom('noreply@mycart2door.com', $sbj);
        $mail->addAddress($email, $fname);     // Add a recipient
        $mail->isHTML(true);
        $mail->addAttachment($attach_src);
        $mail->Subject      =   $sbj;
        $mail->Body         =   $message;

        $mail->AltBody      =   $message; /*'<div style="white-space:normal;border:thin solid #336699;">
                                    <div style="width:100%;padding:10px;color:white;background-color:#336699;"><strong>LITF - SECURITY CODE</strong></div>
                                    <div style="width:100%;padding:50px;white-space:normal;">
                                        Dear '.ucwords($fname).',<br><br>
                                        Thank you for registering for the Lagos International Trade Fair event.<br>Your security code is : <br><br><span style="background-color:#d6e0f5;border:thin solid #6f92dc;padding:5px;color:red;margin-top:10px;box-sizing:border-box;"><strong>'.$token.'</strong></span>
                                        <br><br>Regards<br><b>Trade Fair Promotion Team</b>
                                    </div>
                                </div>';*/
        
        if(!$mail->send()) {
            return '<div>Mailer Error: ' . $mail->ErrorInfo.'</div>';
        }
        
        return(1);
    }