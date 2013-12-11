<?php

/**
 * CustomerManager - ApkStore customer information management class
 * NOTE: 
 * Dependencies:
 *     'class.log.php' 
 *     '../proxy/class.databaseproxy.php'
 *
 * @package ApkStore
 * @author wangpeifeng
 */
require_once 'class.log.php'; 
require_once '../proxy/class.databaseproxy.php';

class CustomerManager
{

    /////////////////////////////////////////////////
    // PROPERTIES, PUBLIC
    /////////////////////////////////////////////////


    /////////////////////////////////////////////////
    // PROPERTIES, PRIVATE
    /////////////////////////////////////////////////

    private $db         = null;
    private $pdo        = null;

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
            $this->pdo = $this->db->getPDO();
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
    
    public function getTotal()
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_CUSTOMERS;
            $column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
            $sql = "SELECT $column_customer"
                    ." FROM $table";
            $query = $this->pdo->query($sql);
            if($query){
                $array = $query->fetchAll();
                return count($array);
            }
        }
        return 0;
    }
    
    public function getAccountCustomer($account_serial)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_CUSTOMERS;
            $column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
            $column_account_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_ACCOUNT_SERIAL;
            
            $sql = "SELECT $column_customer"
                    ." FROM $table WHERE $column_account_serial = $account_serial LIMIT 1";
            
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                return $row["$column_customer"];
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    $log_msg,
                    Log::ERR_ERROR);
            }
        }
        return "";
    }
    
    public function getCustomerSerial($customer)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_CUSTOMERS;
            $column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
            $column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;
            
            $sql = "SELECT $column_customer_serial"
                    ." FROM $table WHERE $column_customer = '$customer' LIMIT 1";
            
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                return $row["$column_customer_serial"];
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    $log_msg,
                    Log::ERR_ERROR);
            }
        }
        return 0;
    }
    
    public function getCustomerBySerial($customer_serial)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_CUSTOMERS;
            $column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
            $column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;
            
            $sql = "SELECT $column_customer"
                    ." FROM $table WHERE $column_customer_serial = $customer_serial LIMIT 1";
            
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                return $row["$column_customer"];
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    $log_msg,
                    Log::ERR_ERROR);
            }
        }
        return "";
    }

    public function getCustomers()
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_CUSTOMERS;
            $column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
            $column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;
            
            $sql = "SELECT $column_customer,$column_customer_serial"
                    ." FROM $table ORDER BY $column_customer";
                    
            
            $query = $this->pdo->query($sql);
            if($query){
                //$customers = $query->fetchAll();
                $i=0;
                foreach ($query as $row){
                    $array_customers[$i]["$column_customer"] = $row["$column_customer"];
                    $array_customers[$i]["$column_customer_serial"] = $row["$column_customer_serial"];
                    $i++;
                }
                return $array_customers;
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
    
    public function fetchCustomers($sort, $cur_page, $limit, $order = 0)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_CUSTOMERS;
            $column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
            $column_type_id = DatabaseProxy::DB_COLUMN_CUSTOMER_TYPE_ID;
            $column_contact = DatabaseProxy::DB_COLUMN_CUSTOMER_CONTACT;
            $column_email = DatabaseProxy::DB_COLUMN_CUSTOMER_EMAIL;
            $column_phone = DatabaseProxy::DB_COLUMN_CUSTOMER_PHONE;
            $column_notes = DatabaseProxy::DB_COLUMN_CUSTOMER_NOTES;
            $column_register_date = DatabaseProxy::DB_COLUMN_CUSTOMER_REGISTER_DATE;
            $skip = ($cur_page - 1) * $limit;
            if($order == 0){
                $order_type = "ASC";
            }
            else{
                $order_type = "DESC";
            }
            
            $sql = "SELECT $column_customer,$column_type_id, $column_contact, $column_email, $column_phone, $column_notes, $column_register_date"
                    ." FROM $table ORDER BY $sort $order_type LIMIT $skip,$limit";
            $query = $this->pdo->query($sql);
            if($query){
                $i=0;
                foreach ($query as $row){
                    $array_customers[$i]["$column_customer"] = $row["$column_customer"];
                    $array_customers[$i]["$column_type_id"] = $row["$column_type_id"];
                    $array_customers[$i]["$column_contact"] = $row["$column_contact"];
                    $array_customers[$i]["$column_email"] = $row["$column_email"];
                    $array_customers[$i]["$column_phone"] = $row["$column_phone"];
                    $array_customers[$i]["$column_notes"] = $row["$column_notes"];
                    $array_customers[$i]["$column_register_date"] = $row["$column_register_date"];
                    $i++;
                }
                return $array_customers;
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

    public function checkCustomer($customer)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_CUSTOMERS;
            $column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
            $sql = "SELECT $column_customer FROM $table WHERE $column_customer = '$customer' LIMIT 1";
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                if($customer == $row["$column_customer"]){
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
    
    public function addCustomer($customer, $type_id, $contact, $email, $phone, $notes)
    {
        if ($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_CUSTOMERS;
            $column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
            $column_type_id = DatabaseProxy::DB_COLUMN_CUSTOMER_TYPE_ID;
            $column_contact = DatabaseProxy::DB_COLUMN_CUSTOMER_CONTACT;
            $column_email = DatabaseProxy::DB_COLUMN_CUSTOMER_EMAIL;
            $column_phone = DatabaseProxy::DB_COLUMN_CUSTOMER_PHONE;
            $column_notes = DatabaseProxy::DB_COLUMN_CUSTOMER_NOTES;
            $column_register_date = DatabaseProxy::DB_COLUMN_CUSTOMER_REGISTER_DATE;
            $column_update_time = DatabaseProxy::DB_COLUMN_CUSTOMER_UPDATE_TIME;
            $register_date = date('Y-m-d');
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "INSERT INTO $table ( $column_customer, $column_type_id, $column_contact, $column_email,".
                            "$column_phone, $column_notes, $column_register_date, $column_update_time )".
                            " VALUES ( '$customer', $type_id, '$contact', '$email', '$phone', '$notes', '$register_date','$update_time')";
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

    public function updateCustomer($customer, $type_id, $contact, $email, $phone, $notes)
    {
        if ($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_CUSTOMERS;
            $column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
            $column_type_id = DatabaseProxy::DB_COLUMN_CUSTOMER_TYPE_ID;
            $column_contact = DatabaseProxy::DB_COLUMN_CUSTOMER_CONTACT;
            $column_email = DatabaseProxy::DB_COLUMN_CUSTOMER_EMAIL;
            $column_phone = DatabaseProxy::DB_COLUMN_CUSTOMER_PHONE;
            $column_notes = DatabaseProxy::DB_COLUMN_CUSTOMER_NOTES;
            $column_register_date = DatabaseProxy::DB_COLUMN_CUSTOMER_REGISTER_DATE;
            $column_update_time = DatabaseProxy::DB_COLUMN_CUSTOMER_UPDATE_TIME;
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "UPDATE $table SET "
                        ."$column_customer = '$customer'"
                        .", $column_type_id = $type_id"
                        .", $column_contact = '$contact'"
                        .", $column_email = '$email'"
                        .", $column_phone = '$phone'"
                        .", $column_notes = '$notes'"
                        .", $column_update_time = '$update_time'"
                        ."WHERE $column_customer = '$customer'";
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
    
    
    public function removeCustomer($customer)
    {
        if ($this->pdo != null){
//            if (!$this->checkCustomer($customer)){
                $table = DatabaseProxy::DB_TABLE_CUSTOMERS;
                $column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
                
                $sql = "DELETE FROM $table" 
                        ." WHERE $column_customer = '$customer'";
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
                
//            }
        }
        return FALSE;    
    }
     
}
?>