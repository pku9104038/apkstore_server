<?php

/**
 * SupplierManager - ApkStore supplier information management class
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

class SupplierManager
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
            $table = DatabaseProxy::DB_TABLE_SUPPLIERS;
            $column_supplier = DatabaseProxy::DB_COLUMN_SUPPLIER;
            $sql = "SELECT $column_supplier"
                    ." FROM $table";
            $query = $this->pdo->query($sql);
            if($query){
                $array = $query->fetchAll();
                return count($array);
            }
        }
        return 0;
    }
    

    public function getSuppliers()
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_SUPPLIERS;
            $column_supplier = DatabaseProxy::DB_COLUMN_SUPPLIER;
            $column_supplier_serial = DatabaseProxy::DB_COLUMN_SUPPLIER_SERIAL;
            
            $sql = "SELECT $column_supplier,$column_supplier_serial"
                    ." FROM $table ORDER BY $column_supplier_serial";
                    
            
            $query = $this->pdo->query($sql);
            if($query){
                //$customers = $query->fetchAll();
                $i=0;
                foreach ($query as $row){
                    $array[$i]["$column_supplier"] = $row["$column_supplier"];
                    $array[$i]["$column_supplier_serial"] = $row["$column_supplier_serial"];
                    $i++;
                }
                return $array;
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
    
    public function fetchSuppliers($sort, $cur_page, $limit, $order = 0)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_SUPPLIERS;
            $column_supplier = DatabaseProxy::DB_COLUMN_SUPPLIER;
            $column_type_id = DatabaseProxy::DB_COLUMN_SUPPLIER_TYPE_ID;
            $column_contact = DatabaseProxy::DB_COLUMN_SUPPLIER_CONTACT;
            $column_email = DatabaseProxy::DB_COLUMN_SUPPLIER_EMAIL;
            $column_phone = DatabaseProxy::DB_COLUMN_SUPPLIER_PHONE;
            $column_audit_url = DatabaseProxy::DB_COLUMN_SUPPLIER_AUDIT_URL;
            $column_audit_account = DatabaseProxy::DB_COLUMN_SUPPLIER_AUDIT_ACCOUNT;
            $column_audit_pwd = DatabaseProxy::DB_COLUMN_SUPPLIER_AUDIT_PWD;
            $column_notes = DatabaseProxy::DB_COLUMN_SUPPLIER_NOTES;
            $column_register_date = DatabaseProxy::DB_COLUMN_SUPPLIER_REGISTER_DATE;
            $skip = ($cur_page - 1) * $limit;
            if($order == 0){
                $order_type = "ASC";
            }
            else{
                $order_type = "DESC";
            }
            
            $sql = "SELECT * "
                    ." FROM $table ORDER BY $sort $order_type LIMIT $skip,$limit";
            $query = $this->pdo->query($sql);
            if($query){
                $i=0;
                foreach ($query as $row){
                    $array_suppliers[$i]["$column_supplier"] = $row["$column_supplier"];
                    $array_suppliers[$i]["$column_type_id"] = $row["$column_type_id"];
                    $array_suppliers[$i]["$column_contact"] = $row["$column_contact"];
                    $array_suppliers[$i]["$column_email"] = $row["$column_email"];
                    $array_suppliers[$i]["$column_phone"] = $row["$column_phone"];
                    $array_suppliers[$i]["$column_audit_url"] = $row["$column_audit_url"];
                    $array_suppliers[$i]["$column_audit_account"] = $row["$column_audit_account"];
                    $array_suppliers[$i]["$column_audit_pwd"] = $row["$column_audit_pwd"];
                    $array_suppliers[$i]["$column_notes"] = $row["$column_notes"];
                    $array_suppliers[$i]["$column_register_date"] = $row["$column_register_date"];
                    $i++;
                }
                return $array_suppliers;
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

    public function checkSupplier($supplier)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_SUPPLIERS;
            $column_supplier = DatabaseProxy::DB_COLUMN_SUPPLIER;
            $sql = "SELECT $column_supplier FROM $table WHERE $column_supplier = '$supplier' LIMIT 1";
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                if($supplier == $row["$column_supplier"]){
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function getSupplierByCustomer($customer_serial)
    {
    	$supplier_serial = 1;
    	if($this->pdo != null){
    		$table = DatabaseProxy::DB_TABLE_SUPPLIERS;
    		$column_supplier_serial = DatabaseProxy::DB_COLUMN_SUPPLIER_SERIAL;
    		$column_customer_serial = DatabaseProxy::DB_COLUMN_SUPPLIER_CUSTOMER_SAERIL;
    		
    		$sql = "SELECT $column_supplier_serial FROM $table WHERE $column_customer_serial = $column_customer_serial LIMIT 1";
    		$query = $this->pdo->query($sql);
    		if($query){
    			$row = $query->fetch();
    			$supplier_serial = $row["$column_supplier_serial"];
    		}
    	}
    	return $supplier_serial;
    }
    
    public function addSupplier($supplier, $type_id, $contact, $email, $phone, $notes, $audit_url, $audit_account, $audit_pwd)
    {
        if ($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_SUPPLIERS;
            $column_supplier = DatabaseProxy::DB_COLUMN_SUPPLIER;
            $column_type_id = DatabaseProxy::DB_COLUMN_SUPPLIER_TYPE_ID;
            $column_contact = DatabaseProxy::DB_COLUMN_SUPPLIER_CONTACT;
            $column_email = DatabaseProxy::DB_COLUMN_SUPPLIER_EMAIL;
            $column_phone = DatabaseProxy::DB_COLUMN_SUPPLIER_PHONE;
            $column_notes = DatabaseProxy::DB_COLUMN_SUPPLIER_NOTES;
            $column_audit_url = DatabaseProxy::DB_COLUMN_SUPPLIER_AUDIT_URL;
            $column_audit_account = DatabaseProxy::DB_COLUMN_SUPPLIER_AUDIT_ACCOUNT;
            $column_audit_pwd = DatabaseProxy::DB_COLUMN_SUPPLIER_AUDIT_PWD;
            $column_register_date = DatabaseProxy::DB_COLUMN_SUPPLIER_REGISTER_DATE;
            $column_update_time = DatabaseProxy::DB_COLUMN_SUPPLIER_UPDATE_TIME;
            $register_date = date('Y-m-d');
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "INSERT INTO $table ( $column_supplier, $column_type_id, $column_contact, $column_email,".
                            "$column_phone, $column_notes, $column_audit_url, $column_audit_account, $column_audit_pwd, $column_register_date, $column_update_time )".
                            " VALUES ( '$supplier', $type_id, '$contact', '$email', '$phone', '$notes', '$audit_url', '$audit_account', '$audit_pwd', '$register_date','$update_time')";
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

    public function updateSupplier($supplier, $type_id, $contact, $email, $phone, $notes, $audit_url, $audit_account, $audit_pwd)
    {
        if ($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_SUPPLIERS;
            $column_supplier = DatabaseProxy::DB_COLUMN_SUPPLIER;
            $column_type_id = DatabaseProxy::DB_COLUMN_SUPPLIER_TYPE_ID;
            $column_contact = DatabaseProxy::DB_COLUMN_SUPPLIER_CONTACT;
            $column_email = DatabaseProxy::DB_COLUMN_SUPPLIER_EMAIL;
            $column_phone = DatabaseProxy::DB_COLUMN_SUPPLIER_PHONE;
            $column_notes = DatabaseProxy::DB_COLUMN_SUPPLIER_NOTES;
            $column_audit_url = DatabaseProxy::DB_COLUMN_SUPPLIER_AUDIT_URL;
            $column_audit_account = DatabaseProxy::DB_COLUMN_SUPPLIER_AUDIT_ACCOUNT;
            $column_audit_pwd = DatabaseProxy::DB_COLUMN_SUPPLIER_AUDIT_PWD;
            $column_update_time = DatabaseProxy::DB_COLUMN_SUPPLIER_UPDATE_TIME;
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "UPDATE $table SET "
                        ."$column_supplier = '$supplier'"
                        .", $column_type_id = $type_id"
                        .", $column_contact = '$contact'"
                        .", $column_email = '$email'"
                        .", $column_phone = '$phone'"
                        .", $column_notes = '$notes'"
                        .", $column_audit_url = '$audit_url'"
                        .", $column_audit_account = '$audit_account'"
                        .", $column_audit_pwd = '$audit_pwd'"
                        .", $column_update_time = '$update_time'"
                        ."WHERE $column_supplier = '$supplier'";
            $query = $this->pdo->query($sql);
            if($query){
                return TRUE;
            }
            else{
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                        print_r($this->pdo->errorInfo(),true),
                        Log::ERR_ERROR);
            }
        }
        return FALSE;    
    }
    
    
    public function removeSupplier($supplier)
    {
        if ($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_SUPPLIERS;
            $column_supplier = DatabaseProxy::DB_COLUMN_SUPPLIER;
            
            $sql = "DELETE FROM $table" 
                        ." WHERE $column_supplier = '$supplier'";
            $query = $this->pdo->query($sql);
            if($query){
                return TRUE;
            }
            else{
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                        print_r($this->pdo->errorInfo(),true),
                        Log::ERR_ERROR);
            }
        }
        return FALSE;    
    }
     
}
?>