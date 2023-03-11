<?php
namespace Application\Services;

class EmailService {
    public static function sendOTP($email, $otp) {
        // Set content-type header for sending HTML email 
        $headers = "MIME-Version: 1.0" . "\r\n"; 
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
        
        // Additional headers 
        $headers .= 'From: no-reply@digimoplus.online' . "\r\n"; 

        $body = EmailService::otpTemplate($otp);
        mail($email, "WalletManagement: Recover your password.", $body, $headers);
    }

    private static function otpTemplate($otp) {
        $content = file_get_contents("Templates/Otp.html");
        return str_replace('{{code}}', $otp, $content);
    }
}