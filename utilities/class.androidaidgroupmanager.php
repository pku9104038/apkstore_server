<?php

/**
 * AndroidaidGroupManager - ApkStore application group management class
 * NOTE: 
 * Dependencies:
 *     'class.log.php' 
 *     '../proxy/class.databaseproxy.php'
 *
 * @package ApkStore
 * @author wangpeifeng
 */
require_once dirname(dirname(__FILE__)).'/utilities/class.log.php'; 
require_once dirname(dirname(__FILE__)).'/proxy/class.databaseproxy.php';

class AndroidaidGroupManager
{

    /////////////////////////////////////////////////
    // PROPERTIES, PUBLIC
    /////////////////////////////////////////////////


    /////////////////////////////////////////////////
    // PROPERTIES, PRIVATE
    /////////////////////////////////////////////////

    private $db                      = null;
    private $pdo                    = null;
    
    private $database           = DatabaseProxy::DB_NAME;
    
    private $table                  = DatabaseProxy::DB_TABLE_ANDROIDAID_GROUP;
    
    private $column_group_serial    = DatabaseProxy::DB_COLUMN_GROUP_SERIAL;
    private $column_group               = DatabaseProxy::DB_COLUMN_GROUP;
   

    /////////////////////////////////////////////////
    // PROPERTIES, PROTECTED
    /////////////////////////////////////////////////

    
    /////////////////////////////////////////////////
    // CONSTANTS
    /////////////////////////////////////////////////

    const ERR_CODE                = 'err_code';
    const ERR_NONE                = 0;
    const ERR_DATABASE         = 1;
    const ERR_BRAND              = 2;
    
    const STR_DB_CONN_SUCCESS        = 'DB Connect Success!';
    const STR_DB_CONN_FAILED           = 'DB Connect Failed!';
    const STR_DB_QUERY_FAILED         = 'Database Query Failed!';
    const STR_ACCOUNT_ERR               = 'Account Not Available!';
		
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
        }
        else{
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__ ,
                self::STR_DB_CONN_FAILED,
                Log::ERR_ERROR);
        }
    }

    function __destruct()
    {
        if ($this->db != null){
            $this->db = null;
            $this->pdo = null;
        }
    }
    
    public function getTotal()
    {
        if($this->pdo != null){
            $sql = "SELECT $this->column_group_serial"
                    ." FROM $this->database.$this->table";
            $query = $this->pdo->query($sql);
            if($query){
                $array = $query->fetchAll();
                return count($array);
            }
        }
        return 0;
    }
    
    public function getGroups()
    {
        if($this->pdo != null){
            
            $sql = "SELECT $this->column_group, $this->column_group_serial"
                    ." FROM $this->database.$this->table ORDER BY $this->column_group_serial";
            $query = $this->pdo->query($sql);
            if($query){
                $i=0;
                $result = $query->fetchAll();
                foreach ($result as $row){
                    $array[$i]["$this->column_group"] = $row["$this->column_group"];
                    $array[$i]["$this->column_group_serial"] = $row["$this->column_group_serial"];
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
    

}
?>