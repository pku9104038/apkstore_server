<?php
/**
 * MailProxy - ApkStore smtp mail proxy class
 *     Send smtp mails via phpmailer package
 * NOTE: 
 *     Smtp connection config depend on '../conf/smtp_conf.xml'
 * Dependencies:
 *     '../phpmailer/class.phpmailer.php'
 *     
 * @package ApkStore
 * @author wangpeifeng
 */

require_once dirname(__FILE__).'/phpmailer/class.phpmailer.php';
#require_once dirname(dirname(__FILE__)).'/utilities/class.log.php';
class MailProxy
{
        /////////////////////////////////////////////////
    // PROPERTIES, PUBLIC
    /////////////////////////////////////////////////
    
    /////////////////////////////////////////////////
    // PROPERTIES, PRIVATE
    /////////////////////////////////////////////////
    
    /////////////////////////////////////////////////
    // PROPERTIES, PROTECTED
    /////////////////////////////////////////////////
    
    
    /////////////////////////////////////////////////
    // CONSTANTS
    /////////////////////////////////////////////////
    
    const CONF_DIR                    = '/conf';
    const CONF_FILE                   = '/smtp_conf.xml';

    
    /////////////////////////////////////////////////
    // METHODS
    /////////////////////////////////////////////////
    
    public static function sendMail($to_array, $subject, $message, $from, $from_name)
    {
        $mail = new PHPMailer();
        
        $mail->CharSet = 'UTF-8';
        $mail->Mailer = 'smtp';
        $mail->IsSMTP();                                      // set mailer to use SMTP
        
        $xml = simplexml_load_file(dirname(dirname(__FILE__)).self::CONF_DIR.self::CONF_FILE);
        $json = json_encode($xml);
        $obj = json_decode($json);
        
        $mail->Host = $obj->SMTP_HOST;  // specify main and backup server
        $mail->Port = $obj->SMTP_PORT;
        $mail->SMTPAuth = $obj->SMTP_AUTH;     // turn on SMTP authentication
        $mail->SMTPSecure = $obj->SMTP_SECURE;
        $mail->Username = $obj->SMTP_USERNAME;  // SMTP username
        $mail->Password = $obj->SMTP_PASSWORD; // SMTP password
        
        if(!isset($from)){
            $mail->From = $obj->SMTP_FROM;
        }
        else{
            $mail->From = $from;
        }
        if (!isset($from_name)){
            $mail->FromName = $obj->SMTP_FROMNAME;
        }
        else {
            $mail->FromName = $from_name;
        }
        
        for($i=0; $i<count($to_array); $i++){
            $mail->AddAddress($to_array[$i]);                  // name is optional
        }
        
        #$mail->WordWrap = 50;                                 // set word wrap to 50 characters
        $mail->IsHTML(false);                                  // set email format to !HTML
        
        $mail->Subject = $subject;
        $mail->Body    = $message;
        
        try{
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                "sendMail!");
            $mail->Send();
        }
        catch (phpmailerException $e){
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                "sendMail Exception! $mail->ErrorInfo", 
                Log::ERR_ERROR);
        }
    }
      
}
?>