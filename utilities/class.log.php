<?php

/**
 * Log - ApkStore log infomation class
 * NOTE: 
 * Dependencies:
 *     '../utilities/Utilities.php'
 *
 * @package ApkStore
 * @author wangpeifeng
 */

#require_once dirname(dirname(__FILE__)).'/utilities/Utilities.php';

class Log
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
    
    const LOG_FILE               = '/logs/logs.txt';
    
    const LEVEL_LOG              = 0;
    const LEVEL_WARNING          = 1;
    const LEVEL_ERROR            = 2;
    
    const LEVEL_SETTING          = self::LEVEL_LOG;

    const ERR_LOG                = "LOG!";
    const ERR_WARNING            = "WARNING!!";
    const ERR_ERROR              = "ERROR!!!";
    
    const DEFAULT_MESSAGE        = "STEP HERE!";
    
    /////////////////////////////////////////////////
    // METHODS
    /////////////////////////////////////////////////

    /**
     * Write log infomation to log file according to the LEVEL_SETTING 
     * 
     * @param string $TAG
     * @param string $msg
     * @param string $error
     * 
     * @access public
     */
    public static function i($TAG, $msg = self::DEFAULT_MESSAGE, $error = self::ERR_LOG)
    {
    	return;// log off
        $flag = false;
        switch (self::LEVEL_SETTING){
            case self::LEVEL_LOG:
                if($error == self::ERR_LOG || $error == null){
                    $flag = true;
                }
                //break;
            case self::LEVEL_WARNING:
                if($error == self::ERR_WARNING ){
                    $flag = true;
                }
                //break;
            case self::LEVEL_ERROR:
                if($error == self::ERR_ERROR ){
                    $flag = true;
                }
                break;
        }

        if($flag){
            self::log_to_file($TAG, $msg, $error);
        }
    }

    /**
     * write log infomation to log file
     * 
     * @param string $TAG
     * @param string $msg
     * @param string $error
     * 
     * @access private
     */
    private static function log_to_file($TAG, $msg = self::DEFAULT_MESSAGE, $error = self::ERR_LOG)
    {
        $fd = @fopen((dirname(dirname(__FILE__))).self::LOG_FILE, "a");
        if($fd) {
            $stamp = date('Y-m-d H:i:s');
            $script = $_SERVER['PHP_SELF'];
            if(stristr(php_uname(), 'Win')){
                $newline = "\r\n";
            }
            else{
                $newline = "\n";
            }
            fputs($fd, "$stamp  $error  Message: $newline    '$msg' $newline    $TAG  Script:$script $newline $newline");
            fclose($fd);
        }
    }

}

?>