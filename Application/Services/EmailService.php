<?php
namespace Application\Services;

class EmailService {
    public static function sendOTP($email, $otp) {
        // Set content-type header for sending HTML email 
        $headers = "MIME-Version: 1.0" . "\r\n"; 
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
        
        // Additional headers 
        $headers .= 'From: no-reply@digimoplus.online' . "\r\n"; 

        $body = otpTemplate($otp);
        mail($email, "WalletManagement: Recover your password.", $body, $headers);
    }

    private static function otpTemplate($otp) {
        $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Verify your login</title>
        </head>
        <body style="font-family: Helvetica, Arial, sans-serif; margin: 0px; padding: 0px; background-color: rgb(239, 239, 239);">
          <table role="presentation"
            style="width: 100%; border-collapse: collapse; border: 0px; border-spacing: 0px; font-family: Arial, Helvetica, sans-serif; background-color: rgb(239, 239, 239);">
            <tbody>
              <tr>
                <td align="center" style="padding: 1rem 2rem; vertical-align: top; width: 100%;">
                  <table role="presentation" style="max-width: 600px; border-collapse: collapse; border: 0px; border-spacing: 0px; text-align: left;">
                    <tbody>
                      <tr>
                        <td style="padding: 40px 0px 0px;">
                          <div style="padding: 20px; background-color: rgb(255, 255, 255);">
                            <div style="color: rgb(0, 0, 0); text-align: left;">
                              <h1 style="margin: 1rem 0">Change Password</h1>
                              <p style="padding-bottom: 16px">Please use the verification code below to change your password</p>
                              <p style="padding-bottom: 16px"><strong style="font-size: 130%">{{code}}</strong></p>
                              <p style="padding-bottom: 16px">If you didn’t request this, you can ignore this email.</p>
                              <p style="padding-bottom: 16px">Thanks,<br>The Wallet Management team</p>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
        </body>
        </html>';
        return str_replace('{{code}}', $otp, $message);
    }
}