<?php

/**
 * AccountManager - ApkStore account management class
 * NOTE: 
 * Dependencies:
 *     'class.log.php' 
 *     '../proxy/class.databaseproxy.php'
 *     '../proxy/class.mailproxy.php'
 *
 * @package ApkStore
 * @author wangpeifeng
 */
require_once 'class.log.php'; 
require_once '../proxy/class.databaseproxy.php';

class AccountManager
{

    /////////////////////////////////////////////////
    // PROPERTIES, PUBLIC
    /////////////////////////////////////////////////


    /////////////////////////////////////////////////
    // PROPERTIES, PRIVATE
    /////////////////////////////////////////////////

    private $db         = null;
    private $pdo        = null;
    
    private $database               = DatabaseProxy::DB_NAME;
    private $table                  = DatabaseProxy::DB_TABLE_ACCOUNTS;
    
    private $column_serial          = DatabaseProxy::DB_COLUMN_APKFILE_SERIAL;
    private $column_apkfile         = DatabaseProxy::DB_COLUMN_APKFILE;
    private $column_ver_code        = DatabaseProxy::DB_COLUMN_APKFILE_VERSION_CODE;
    private $column_ver_name        = DatabaseProxy::DB_COLUMN_APKFILE_VERSION_NAME;
    private $column_sdk_min         = DatabaseProxy::DB_COLUMN_APKFILE_SDK_MIN;
    private $column_file_original   = DatabaseProxy::DB_COLUMN_APKFILE_FILE_ORIGINAL;
    
    /////////////////////////////////////////////////
    // PROPERTIES, PROTECTED
    /////////////////////////////////////////////////

    
    /////////////////////////////////////////////////
    // CONSTANTS
    /////////////////////////////////////////////////

    const ACCOUNT_REGULAR         = '/^[a-zA-Z0-9][a-zA-Z0-9\._\-@]{5,15}/';
    const PASSWORD_REGULAR        = '/^[a-zA-Z0-9]{6,16}/';
    const EMAIL_REGULAR           = '/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/';
    const PASSWORD_MAX            = 16;
    const PASSWORD_MIN            = 6;
    const ASCII_a                 = 97;
    const ASCII_z                 = 122;
    
    const ERR_CODE                = 'err_code';
    const ERR_NONE                = 0;
    const ERR_DATABASE            = 1;
    const ERR_ACCOUNT             = 2;
    const ERR_PASSWORD            = 3;
    
    const ROLE_ID_UNKNOWN         = 0;
    
    const STR_DB_CONN_SUCCESS        = 'DB Connect Success!';
    const STR_DB_CONN_FAILED         = 'DB Connect Failed!';
    const STR_DB_QUERY_FAILED        = 'Database Query Failed!';
    const STR_ACCOUNT_ERR            = 'Account Not Available!';
		
    /////////////////////////////////////////////////
    // METHODS, VARIABLES
    /////////////////////////////////////////////////
	
/**
 * 
 * create a new instance, and connect to the database 
 */	
    function __construct()
    {
        $this->db = null;
        $this->pdo = null;
        $this->db = new DatabaseProxy();
        if($this->db->isConnected()){
            $this->pdo = $this->db->getPDO();//
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__ ,
                self::STR_DB_CONN_SUCCESS );
        }
        else{
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__ ,
                self::STR_DB_CONN_FAILED,
                Log::ERR_ERROR);
        }
    }

    function __destruct()
    {
        Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__ );
        if ($this->db != null){
            $this->db = null;
            $this->pdo = null;
        }
    }

    public function initRoles()
    {
        $array[0] = DatabaseProxy::DB_VALUE_ROLE_ROOT;
        $array[1] = DatabaseProxy::DB_VALUE_ROLE_ADMIN;
        $array[2] = DatabaseProxy::DB_VALUE_ROLE_APP_ADMIN;
        $array[3] = DatabaseProxy::DB_VALUE_ROLE_STATISTICS_ADMIN;
        $array[4] = DatabaseProxy::DB_VALUE_ROLE_STATISTICS;
        $array[5] = DatabaseProxy::DB_VALUE_ROLE_CUSTOMER;
        $max = 5;
        $roles = 0;
        
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_ROLES;
            for ($i = 0; $i <= $max; $i++){
                $update_time = date("Y-m-d H:i:s");
                $role_id = $i+1;
                $role_name = $array[$i];
                $sql = "INSERT INTO $table (".
                    DatabaseProxy::DB_COLUMN_ROLES_ID.
                    ','.DatabaseProxy::DB_COLUMN_ROLE_NAME.
                    ','.DatabaseProxy::DB_COLUMN_ROLE_UPDATE_TIME.
                    ") VALUES ($role_id,'$role_name','$update_time')";
                
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    $sql);
                $query = $this->pdo->query($sql);
                if($query){
                    $roles++;
                    Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                        DatabaseProxy::DB_COLUMN_ROLES_ID.' = '.$this->pdo->lastInsertId() );
                }
                else{
                    $this->pdo->errorInfo();
                    $msg = print_r($this->pdo->errorInfo(),true);
                    Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                        $msg,
                        Log::ERR_ERROR);
                }
            }// end of for
        }
        else{
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                "database connection losted!",
                Log::ERR_ERROR);
        }
        return $roles;
    }
    
    public function initRoot()
    {
        $result = false;
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_ACCOUNTS;
            $role_id = 1;
            $account = 'root';
            $password_sha = sha1('root');
            $email = 'pku9104038@hotmail.com';
            $register_date = date('Y-m-d');
            $update_time = date("Y-m-d H:i:s");
            
            $sql = "INSERT INTO $table (".
                DatabaseProxy::DB_COLUMN_ACCOUNT_ROLE_ID.
                ','.DatabaseProxy::DB_COLUMN_ACCOUNT.
                ','.DatabaseProxy::DB_COLUMN_ACCOUNT_PASSWORD_SHA.
                ','.DatabaseProxy::DB_COLUMN_ACCOUNT_EMAIL.
                ','.DatabaseProxy::DB_COLUMN_ACCOUNT_REGISTER_DATE.
                ','.DatabaseProxy::DB_COLUMN_ACCOUNT_UPDATE_TIME.
                ") VALUES ($role_id,'$account','$password_sha','$email','$register_date','$update_time')";
            
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                $sql);
            
            $query = $this->pdo->query($sql);
            if($query){
                $result = true;
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    DatabaseProxy::DB_COLUMN_ACCOUNT_SERIAL.' = '.$this->pdo->lastInsertId() );
            }
            else{
                $this->pdo->errorInfo();
                $msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    $msg,
                    Log::ERR_ERROR);
            }
        }
        else{
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                "database connection losted!",
                Log::ERR_ERROR);
        }
        return $result;
    }
    
    public function isRootAvailable()
    {
        $result = false;
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_ACCOUNTS;
            $column_serial = DatabaseProxy::DB_COLUMN_ACCOUNT_SERIAL;
            $column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
            $role_id = 1;
            $where = DatabaseProxy::DB_COLUMN_ACCOUNT_ROLE_ID;
            $sql = "SELECT * FROM $table WHERE $where = $role_id LIMIT 1";
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                $sql);
                
            $query = $this->pdo->query($sql);
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                $sql);
            if($query){
                $row = $query->fetch();
                if (count($row) > 0){
                    if ($row["$column_account"]=='root'){                
                        $result = true;
                        Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                            DatabaseProxy::DB_COLUMN_ACCOUNT_SERIAL.' = '.$row["$column_serial"] );
                    }
                    else{
                        Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__ 
                             );
                        
                    }
                    
                }
                else {
                        Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__ 
                             );
                }
            }
            else{
                $this->pdo->errorInfo();
                $msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    $msg, 
                    Log::ERR_ERROR);
            }
        }
        else{
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                "database connection losted!",
                Log::ERR_ERROR);
        }
        return $result;
    }

/**
 * 
 * check the form input of the account register page
 *  
 * @param string $account
 * @param string $pwd
 * @param string $pwd2
 * @param string $email
 * 
 * @return boolean
 */	
    public function checkAccountInfo($account, $pwd, $pwd2, $email)
    {
        $result = true;

        if(!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\._\-@]{5,15}/', $account)){
            Log::i('function '.__FUNCTION__.'()'.' class::'.__CLASS__.' Line:'.__LINE__.' at '.__FILE__ );
            $this->err_msg_account = self::ACCOUNT_ERR_MSG;
            $result = false;
        }
        else{
            $this->err_msg_account = "";
        }
        
        Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__ );
        if (!preg_match(self::PASSWORD_REGULAR, $pwd)){
            $this->err_msg_pwd = self::PASSWORD_ERR_MSG;
            $result = false;
        }
        else{
            $this->err_msg_pwd = "";
        
            if ($pwd2 != $pwd){
                $this->err_msg_pwd2 = self::PASSWORD2_ERR_MSG;
                $result = false;
            }
            else{
                $this->err_msg_pwd2 = "";
            }
        }
        Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__ );

        if (!preg_match(self::EMAIL_REGULAR, $email)){
            $this->err_msg_email = self::EMAIL_ERR_MSG;
            $result = false;
        }
        else{
            $domain = preg_replace(self::EMAIL_REGULAR, '', $email);
            if(!Utilities::checkDNSRR($domain)){
                $this->err_msg_email = self::EMAIL_ERR_MSG;
                $result = false;
            }
            else {
                $this->err_msg_email = "";
                $result = false;
            }
        }
        return $result;
    }
    
    
    public static function pre_matchPassword($pwd)
    {
        if (strlen($pwd)<self::PASSWORD_MIN || strlen($pwd) >self::PASSWORD_MAX){
            return false;
        }
        else{
            if (!preg_match(self::PASSWORD_REGULAR, $pwd)){
                return false;
            }
            else{
                return true;
            }
        }
    }
    /**
     * 
     * Enter description here ...
     * @param string $account
     * @param string $pwd
     */
    public function checkLogin($account, $pwd)
    {
        Log::i("checkLogin",$account."@".$pwd);
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_ACCOUNTS;
            $column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
            $column_password_sha = DatabaseProxy::DB_COLUMN_ACCOUNT_PASSWORD_SHA;
            $column_role_id = DatabaseProxy::DB_COLUMN_ACCOUNT_ROLE_ID;
            $column_login_times = DatabaseProxy::DB_COLUMN_ACCOUNT_LOGIN_TIMES;
            $column_login_latest = DatabaseProxy::DB_COLUMN_ACCOUNT_LOGIN_LATEST;
            $err_code = self::ERR_CODE;
            
            $sql = "SELECT * "
                ." FROM $table WHERE $table.$column_account = '$account' LIMIT 1";
            
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,$sql);
            
            $query = $this->pdo->query($sql);
            
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, "query return!");
            
            if($query){
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__);
            
                $row = $query->fetch(PDO::FETCH_ASSOC);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__);
            
                $account_name = $row["$column_account"];
                if ($account_name == $account){
                    $password_sha = $row["$column_password_sha"];
                    if ($password_sha != sha1($pwd)){
                        $result["$err_code"] = self::ERR_PASSWORD;
                    }
                    else{
                        $result["$err_code"] = self::ERR_NONE;
                        $result["$column_login_times"] = $row["$column_login_times"] + 1;
                        if ($result["$column_login_times"] == 1){
                            $result["$column_login_latest"] = "";
                        }
                        else{
                            $result["$column_login_latest"] = $row["$column_login_latest"];
                        }
                        $result["$column_role_id"] = $row["$column_role_id"];
                        $sql = "UPDATE $table SET $column_login_times = ".$result["$column_login_times"]
                                .", $column_login_latest = '".date("Y-m-d H:i:s")
                                ."' WHERE $column_account = '$account'";
                        Log::i($sql);
                        $query = $this->pdo->query($sql);
                        
                    }
                }
                else{
                    $result["$err_code"] = self::ERR_ACCOUNT;
                    Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                        self::STR_ACCOUNT_ERR,
                        Log::ERR_ERROR);
                }
            }
            else{
                $result["$err_code"] = self::ERR_DATABASE;
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                    $log_msg,
                    Log::ERR_ERROR);
            }
        }
        else{
            $result["$err_code"] = self::ERR_DATABASE;
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                self::STR_DB_CONN_FAILED,
                Log::ERR_ERROR);
        }
        
        $xml = simplexml_load_file('../conf/app_conf.xml');
        $json = json_encode($xml);
        $obj = json_decode($json);
        
        $app_login = $obj->APP_LOGIN;
        if($app_login=="true"){
        	;
        }
        else{
        	$result["$err_code"] = self::ERR_DATABASE;
        }
        
       	return $result;
       	
    }
    
    public function getRoleID($account){
        $role_id = self::ROLE_ID_UNKNOWN;
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_ACCOUNTS;
            $column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
            $column_role_id = DatabaseProxy::DB_COLUMN_ACCOUNT_ROLE_ID;
            $sql = "SELECT $column_role_id FROM $table WHERE $column_account = '$account' LIMIT 1";
            
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch(PDO::FETCH_ASSOC);
                $role_id = $row["$column_role_id"];
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                    $log_msg,
                    Log::ERR_ERROR);
            }
        }
        else{
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                self::STR_DB_CONN_FAILED,
                Log::ERR_ERROR);
        }
        return $role_id;
        
    }
    
    public function setPassword($account, $pwd)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_ACCOUNTS;
            $column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
            $column_password_sha = DatabaseProxy::DB_COLUMN_ACCOUNT_PASSWORD_SHA;
            $password_sha = sha1($pwd);
            $column_update_time = DatabaseProxy::DB_COLUMN_ACCOUNT_UPDATE_TIME;
            $update_time = date('Y-m-d H:i:s');
            $sql = "UPDATE $table SET $column_password_sha = '$password_sha', $column_update_time = '$update_time' WHERE $column_account = '$account'";

            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                        $sql);
            
            $query = $this->pdo->query($sql);
            if($query){
                $email = $this->getAccountEmail($account);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                        $email);
                $subject = "ApkStore帐号密码设置!";
                $body = '您在ApkStore的帐号: "'.$account.'"已经设置 新密码："'.$pwd.'"，请及时登录并修改密码。';
                require_once '../proxy/class.mailproxy.php';
                MailProxy::sendMail(Array($email), $subject, $body);
                $log_msg = "Send new password to $email!";
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                        $log_msg);
                return TRUE;
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                        $log_msg,
                        Log::ERR_ERROR);
                return false;
            }
        }
        else{
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                    self::STR_DB_CONN_FAILED,
                    Log::ERR_ERROR);
            return false;
        }
        
    }
    /**
     * 
     * Check the availability of account email, 
     *     then reset and send the new password to the email
     *     
     * @param string $email
     * 
     * @access public
     */
    public function resetPasswordByEmail($email)
    {
        $account = $this->checkAccountEmail($email);
        if ($account) {
            $new_pwd = "";
            for($i=0;$i<self::PASSWORD_MIN;$i++){
                $pwd[$i] = chr(rand(self::ASCII_a, self::ASCII_z));
                $new_pwd .= $pwd[$i];
            }
       
            if($this->pdo != null){
                $table = DatabaseProxy::DB_TABLE_ACCOUNTS;
                $column_email = DatabaseProxy::DB_COLUMN_ACCOUNT_EMAIL;
                $column_password_sha = DatabaseProxy::DB_COLUMN_ACCOUNT_PASSWORD_SHA;
                $password_sha = sha1($new_pwd);
                $column_update_time = DatabaseProxy::DB_COLUMN_ACCOUNT_UPDATE_TIME;
                $update_time = date('Y-m-d H:i:s');
                $sql = "UPDATE $table SET $column_password_sha = '$password_sha', $column_update_time = '$update_time' WHERE $column_email = '$email'";
        
                $query = $this->pdo->query($sql);
                if($query){
                    $subject = "ApkStore帐号密码设置!";
                    $body = '您在ApkStore的帐号: "'.$account.'"已经设置新密码："'.$new_pwd.'"，请及时登录并修改密码。';
                    require_once '../proxy/class.mailproxy.php';
    				$to_array[0] = "pku9104038@hotmail.com";
    				$to_array[1] = $email;
    				
    				MailProxy::sendMail($to_array, $subject, $body);
    				//MailProxy::sendMail(Array($email), $subject, $body);
    				
    				$log_msg = "Send new password to $email!";
                    Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                        $log_msg);
                  }
                else{
                    $result = self::ERR_DATABASE;
                    $log_msg = print_r($this->pdo->errorInfo(),true);
                    Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                        $log_msg,
                        Log::ERR_ERROR);
                }
            }
            else{
                $result = self::ERR_DATABASE;
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                    self::STR_DB_CONN_FAILED,
                    Log::ERR_ERROR);
            }
        }
    }
    
     
    
    public function checkAccount($account)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_ACCOUNTS;
            $column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
            $sql = "SELECT $column_account FROM $table WHERE $column_account = '$account' LIMIT 1";

            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch(PDO::FETCH_ASSOC);
                $account_value  = $row["$column_account"];
                if ($account == $account_value){
                    return TRUE;
                }
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                    $log_msg,
                    Log::ERR_ERROR);
            }
        }
        else{
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                self::STR_DB_CONN_FAILED,
                Log::ERR_ERROR);
        }
        return FALSE;
    }
    
    public function getAccountEmail($account)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_ACCOUNTS;
            $column_email = DatabaseProxy::DB_COLUMN_ACCOUNT_EMAIL;
            $column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
            $sql = "SELECT $column_email FROM $table WHERE $column_account = '$account' LIMIT 1";

            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch(PDO::FETCH_ASSOC);
                return $row["$column_email"];
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                    $log_msg,
                    Log::ERR_ERROR);
            }
        }
        else{
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                self::STR_DB_CONN_FAILED,
                Log::ERR_ERROR);
        }
        return FALSE;
    }
    
    public function getAccountCustomer($account)
    {
    	if($this->pdo != null){
    		$table = DatabaseProxy::DB_TABLE_ACCOUNTS;
    		$column_customer_serial = DatabaseProxy::DB_COLUMN_ACCOUNT_CUSTOMER_SERIAL;
    		$column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
    		$table_customer = DatabaseProxy::DB_TABLE_CUSTOMERS;
    		$column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
    		
    		$sql = "SELECT $table_customer.$column_customer ".
      			"FROM $table ".
      			" INNER JOIN $table_customer ".
      			" ON ($table.$column_customer_serial = $table_customer.$column_customer_serial) ".
      			" WHERE $table.$column_account = '$account' LIMIT 1";
    
    		$query = $this->pdo->query($sql);
    		if($query){
    			$row = $query->fetch(PDO::FETCH_ASSOC);
    			return $row["$column_customer"];
    		}
    		else{
    			$log_msg = print_r($this->pdo->errorInfo(),true);
    			Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
    			$log_msg,
    			Log::ERR_ERROR);
    		}
    	}
    	else{
    		Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
    		self::STR_DB_CONN_FAILED,
    		Log::ERR_ERROR);
    	}
    	return FALSE;
    }

    public function getAccountCustomerSerial($account)
    {
    	
    	$customer = 0;
    	if($this->pdo != null){
    		$table = DatabaseProxy::DB_TABLE_ACCOUNTS;
    		$column_customer_serial = DatabaseProxy::DB_COLUMN_ACCOUNT_CUSTOMER_SERIAL;
    		$column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
    		$table_customer = DatabaseProxy::DB_TABLE_CUSTOMERS;
    		$column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
    
    		$sql = "SELECT $column_customer_serial ".
    				"FROM $table  WHERE $table.$column_account = '$account' LIMIT 1";
    
    		$query = $this->pdo->query($sql);
    		if($query){
    			$row = $query->fetch();
    			$customer =  $row["$column_customer_serial"];
    		}
    		else{
    			$log_msg = print_r($this->pdo->errorInfo(),true);
    			Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
    			$log_msg,
    			Log::ERR_ERROR);
    		}
    	}
    	else{
    		Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
    		self::STR_DB_CONN_FAILED,
    		Log::ERR_ERROR);
    	}
    	return $customer;
    }
    
    /**
     * 
     * Check the availability of account email
     * @param string $email
     * 
     * @access public
     * @return boolean
     */
    public function checkAccountEmail($email)
    {
        Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                "checkAccountEmail");
        
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_ACCOUNTS;
            $column_email = DatabaseProxy::DB_COLUMN_ACCOUNT_EMAIL;
            $column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
            $sql = "SELECT $column_account, $column_email FROM $table WHERE $column_email = '$email' LIMIT 1";

            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch(PDO::FETCH_ASSOC);
                return $row["$column_account"];
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                    $log_msg,
                    Log::ERR_ERROR);
            }
        }
        else{
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                self::STR_DB_CONN_FAILED,
                Log::ERR_ERROR);
        }
        return FALSE;
    }
    
    
    public function checkAccountNewEmail($account, $email)
    {
        $checked = $this->checkAccountEmail($email);
        if ($checked == FALSE || $account == $checked){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    
    
    public function getTotal()
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_ACCOUNTS;
            $column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
            $sql = "SELECT $column_account"
                    ." FROM $table";
            $query = $this->pdo->query($sql);
            if($query){
                $array = $query->fetchAll();
                return count($array);
            }
        }
        return 0;
        
    }
    
    public function fetchAccounts($sort, $cur_page, $limit, $order)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_ACCOUNTS;
            $column_account_serial = DatabaseProxy::DB_COLUMN_ACCOUNT_SERIAL;
            $column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
            $column_role_id = DatabaseProxy::DB_COLUMN_ACCOUNT_ROLE_ID;
            $column_customer_serial = DatabaseProxy::DB_COLUMN_ACCOUNT_CUSTOMER_SERIAL;
            $column_email = DatabaseProxy::DB_COLUMN_ACCOUNT_EMAIL;
            $column_register_date = DatabaseProxy::DB_COLUMN_ACCOUNT_REGISTER_DATE;
            $column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
            $skip = ($cur_page - 1) * $limit;
            $role_id_root = DatabaseProxy::DB_VALUE_ROLE_ID_ROOT;
            $role_id_customer = DatabaseProxy::DB_VALUE_ROLE_ID_CUSTOMER;
            $role_id_customer_statistics = DatabaseProxy::DB_VALUE_ROLE_ID_CUSTOMER_STATISTICS;
            
            if ($order == 0){
                $order_type = "ASC";
            }
            else{
                $order_type = "DESC";
            }
            
            $sql = "SELECT * "
                    ." FROM $table WHERE $column_role_id > $role_id_root ORDER BY $sort $order_type LIMIT $skip,$limit";
            
            $query = $this->pdo->query($sql);
            if($query){
                $i=0;
                foreach ($query as $row){
                    $array_accounts[$i]["$column_account"] = $row["$column_account"];
                    $array_accounts[$i]["$column_role_id"] = $row["$column_role_id"];
                    $array_accounts[$i]["$column_email"] = $row["$column_email"];
                    $array_accounts[$i]["$column_register_date"] = $row["$column_register_date"];
                    if($row["$column_role_id"] == $role_id_customer ||
                    	$row["$column_role_id"] == $role_id_customer_statistics ){
                        require_once 'class.customermanager.php';
                        $mgr = new CustomerManager();
                        //$array_accounts[$i]["$column_customer"] = $mgr->getAccountCustomer($row["$column_account_serial"]);
                        $array_accounts[$i]["$column_customer"] = $mgr->getCustomerBySerial($row["$column_customer_serial"]);
                        if (empty($array_accounts[$i]["$column_customer"])){
                            $array_accounts[$i]["$column_customer"] = DatabaseProxy::DB_VALUE_ROLE_NO_CUSTOMER;
                        }
                    }
                    else {
                        $array_accounts[$i]["$column_customer"] = DatabaseProxy::DB_VALUE_ROLE_INNER_ACCOUNT;
                    }
                    Log::i($array_accounts[$i]["$column_account"]);
                    $i++;
                }
                return $array_accounts;
            }
            else{
                $result = self::ERR_ACCOUNT;
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                    $log_msg,
                    Log::ERR_ERROR);
            }
        }
        return FALSE;
        
    }
    
    
    public function addAccount($account, $role_id, $email, $pwd)
    {
        if ($this->pdo != null){
                $table = DatabaseProxy::DB_TABLE_ACCOUNTS;
                $column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
                $column_role_id = DatabaseProxy::DB_COLUMN_ACCOUNT_ROLE_ID;
                $column_email = DatabaseProxy::DB_COLUMN_CUSTOMER_EMAIL;
                $column_password_sha = DatabaseProxy::DB_COLUMN_ACCOUNT_PASSWORD_SHA;
                $column_register_date = DatabaseProxy::DB_COLUMN_ACCOUNT_REGISTER_DATE;
                $column_update_time = DatabaseProxy::DB_COLUMN_ACCOUNT_UPDATE_TIME;
                $register_date = date('Y-m-d');
                $update_time = date('Y-m-d H:i:s');
                $pwd_sha = sha1($pwd);
                
                $sql = "INSERT INTO $table ( $column_account, $column_role_id, $column_email, $column_password_sha,".
                            "$column_register_date, $column_update_time )".
                            " VALUES ( '$account', $role_id, '$email', '$pwd_sha', '$register_date','$update_time')";
                $query = $this->pdo->query($sql);
                if($query){
                    $this->resetPasswordByEmail($email);
                    return TRUE;
                }
                else{
                    $log_msg = print_r($this->pdo->errorInfo(),true);
                    Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                        $log_msg,
                        Log::ERR_ERROR);
                }
                
        }
        return FALSE;    
   
    }
    
    public function updateAccount($account, $role_id, $email)
    {
Log::i("updateAccount");
        if ($this->pdo != null){
                $table = DatabaseProxy::DB_TABLE_ACCOUNTS;
                $column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
                $column_role_id = DatabaseProxy::DB_COLUMN_ACCOUNT_ROLE_ID;
                $column_email = DatabaseProxy::DB_COLUMN_CUSTOMER_EMAIL;
                $column_update_time = DatabaseProxy::DB_COLUMN_ACCOUNT_UPDATE_TIME;
                $update_time = date('Y-m-d H:i:s');
                
                $sql = "UPDATE $table SET $column_role_id = $role_id, $column_email = '$email', $column_update_time = '$update_time' ".
                            " WHERE $column_account = '$account'";
                
                $query = $this->pdo->query($sql);
                if($query){
                    return TRUE;
                }
                else{
                    $log_msg = print_r($this->pdo->errorInfo(),true);
                    Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                        $log_msg,
                        Log::ERR_ERROR);
                }
                
        }
        return FALSE;    
   
    }
    
    public function grantCustomer($account, $customer_serial)
    {
        if ($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_ACCOUNTS;
            $column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
            $column_account_customer_serial = DatabaseProxy::DB_COLUMN_ACCOUNT_CUSTOMER_SERIAL;
            $column_update_time = DatabaseProxy::DB_COLUMN_ACCOUNT_UPDATE_TIME;
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "UPDATE $table SET "
                        ."$column_account_customer_serial = $customer_serial"
                       .", $column_update_time = '$update_time'"
                        ."WHERE $column_account = '$account'";
            $query = $this->pdo->query($sql);
            if($query){
                return TRUE;
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                        $log_msg,
                        Log::ERR_ERROR);
            }
                
        }
        return FALSE;    
    }

    public function removeAccount($account)
    {
        if ($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_ACCOUNTS;
            $column_account = DatabaseProxy::DB_COLUMN_ACCOUNT;
                
            $sql = "DELETE FROM $table" 
                        ." WHERE $column_account = '$account'";
            $query = $this->pdo->query($sql);
            if($query){
                return TRUE;
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                        $log_msg,
                        Log::ERR_ERROR);
            }
        }
        return FALSE;    
    }
    
    public function getBrandSerialsByCustomer($account){
    	$brands = FALSE;
    	$customer_serial = $this->getAccountCustomerSerial($account);
    	if($customer_serial>0){
    		require_once '../utilities/class.brandmanager.php';
    		$mgr = new BrandManager();
    		$brands = $mgr->getBrandSerialsByCustomer($customer_serial);
    	}
    	return $brands;
    }
}
?>