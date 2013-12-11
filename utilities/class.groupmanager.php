<?php

/**
 * GroupManager - ApkStore application group management class
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

class GroupManager
{

    /////////////////////////////////////////////////
    // PROPERTIES, PUBLIC
    /////////////////////////////////////////////////


    /////////////////////////////////////////////////
    // PROPERTIES, PRIVATE
    /////////////////////////////////////////////////

    private $db                     = null;
    private $pdo                    = null;
    
    private $database               = DatabaseProxy::DB_NAME;
    
    private $table                  = DatabaseProxy::DB_TABLE_GROUP;
    
    private $column_group_serial    = DatabaseProxy::DB_COLUMN_GROUP_SERIAL;
    private $column_group           = DatabaseProxy::DB_COLUMN_GROUP;
    private $column_icon            = DatabaseProxy::DB_COLUMN_GROUP_ICON;
    private $column_priority        = DatabaseProxy::DB_COLUMN_GROUP_PRIORITY;
    private $column_notes           = DatabaseProxy::DB_COLUMN_GROUP_NOTES;
    private $column_register_date   = DatabaseProxy::DB_COLUMN_GROUP_REGISTER_DATE;
    private $column_update_time     = DatabaseProxy::DB_COLUMN_GROUP_UPDATE_TIME;
    private $column_customer_serial = DatabaseProxy::DB_COLUMN_GROUP_CUSTOMER_SERIAL;
    

    /////////////////////////////////////////////////
    // PROPERTIES, PROTECTED
    /////////////////////////////////////////////////

    
    /////////////////////////////////////////////////
    // CONSTANTS
    /////////////////////////////////////////////////

    const ERR_CODE                = 'err_code';
    const ERR_NONE                = 0;
    const ERR_DATABASE            = 1;
    const ERR_BRAND               = 2;
    
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
    
    public function getGroups($customer_serial = 0)
    {
        if($this->pdo != null){
            
        	
        	if ($this->isCustomerGroup($customer_serial)) {
            	$sql = "SELECT $this->column_group, $this->column_group_serial,$this->column_icon ,$this->column_priority"
                    ." FROM $this->database.$this->table ".
                    " WHERE ( $this->column_priority = 1 AND $this->column_customer_serial = $customer_serial )".
                    " OR ($this->column_priority>1 AND $this->column_customer_serial = 0) ".
                    " ORDER BY $this->column_group_serial";
        		
        	}
        	else{
            	$sql = "SELECT $this->column_group, $this->column_group_serial,$this->column_icon ,$this->column_priority"
                    ." FROM $this->database.$this->table ".
                    " WHERE $this->column_customer_serial = 0".
            		" ORDER BY $this->column_group_serial";
        	}
        	//file_put_contents("../json/sql.json",$sql);
        	
            $query = $this->pdo->query($sql);
            if($query){
                $i=0;
                $result = $query->fetchAll();
                foreach ($result as $row){
                    $array[$i]["$this->column_priority"] = $row["$this->column_priority"];
                    $array[$i]["$this->column_group"] = $row["$this->column_group"];
                    $array[$i]["$this->column_group_serial"] = $row["$this->column_group_serial"];
                    $array[$i]["$this->column_icon"] = $row["$this->column_icon"];
                    Log::i($array[$i]["$this->column_group"]);   
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
    
    public function isCustomerGroup($customer_serial)
    {
    	$array = FALSE;
    	
    	if($this->pdo != null){
    		 
    		$sql = "SELECT $this->column_group, $this->column_group_serial,$this->column_icon ,$this->column_priority"
    			." FROM $this->database.$this->table ".
    			" WHERE ( $this->column_priority = 1 AND $this->column_customer_serial = $customer_serial )".
    			" ORDER BY $this->column_group_serial";
    			 
    		$query = $this->pdo->query($sql);
    		if($query){

    			$i=0;
    			$result = $query->fetchAll();
    			foreach ($result as $row){
	    			$array[$i]["$this->column_priority"] = $row["$this->column_priority"];
	    			$array[$i]["$this->column_group"] = $row["$this->column_group"];
	    			$array[$i]["$this->column_group_serial"] = $row["$this->column_group_serial"];
	    			$array[$i]["$this->column_icon"] = $row["$this->column_icon"];
	    			Log::i($array[$i]["$this->column_group"]);
	    			$i++;
    			}
    		}
    	}
    	return $array;
    	
    }
    
    public function getGroupsAll()
    {
    	if($this->pdo != null){
    
    		$sql = "SELECT $this->column_group, $this->column_group_serial,$this->column_icon ,$this->column_priority"
    			." FROM $this->database.$this->table ".
    			//" WHERE $this->column_customer_serial = 0".
    			" ORDER BY $this->column_group_serial";
    			//file_put_contents("../json/sql.json",$sql);
    			 
    		$query = $this->pdo->query($sql);
    		if($query){
    			$i=0;
    			$result = $query->fetchAll();
    			foreach ($result as $row){
	    			$array[$i]["$this->column_priority"] = $row["$this->column_priority"];
	    			$array[$i]["$this->column_group"] = $row["$this->column_group"];
	    			$array[$i]["$this->column_group_serial"] = $row["$this->column_group_serial"];
    				$array[$i]["$this->column_icon"] = $row["$this->column_icon"];
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
    
    public function getGroupsSerialArray()
    {
        if($this->pdo != null){
            
            $sql = "SELECT $this->column_group_serial"
                    ." FROM $this->database.$this->table ORDER BY $this->column_group_serial";
            $query = $this->pdo->query($sql);
            if($query){
                $i=0;
                $result = $query->fetchAll();
                foreach ($result as $row){
                    $array[$i] = $row["$this->column_group_serial"];
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
    
    
    public function getGroupsLatestStamp()
    {
    	$stamp = FALSE;
    	if($this->pdo != null){
    
    		$sql = "SELECT $this->column_update_time"
    			." FROM $this->database.$this->table"
    			." ORDER BY $this->column_update_time DESC"
    			." LIMIT 1";
    		
    		$query = $this->pdo->query($sql);
    		if($query){
    		
    			$result = $query->fetch();
	    		$str = $result["$this->column_update_time"];
	    		
	    		$str = preg_replace('/{|-|:| |}/', '', $str);//preg_replace($pattern, $replacement, $subject)
    			$stamp = 0 + substr($str, 0,10);
    		}
    		else{
    			$log_msg = print_r($this->pdo->errorInfo(),true);
    			Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
    			$log_msg,
    			Log::ERR_ERROR);
        	}
    	}
    	return $stamp;
    }
        
    
    public function fetchGroups($sort, $cur_page, $limit, $order = 0, $customer_serial = 0)
    {
        if($this->pdo != null){
            $skip = ($cur_page - 1) * $limit;
            if($order == 0){
                $order_type = "ASC";
            }
            else{
                $order_type = "DESC";
            }
            
            $sql = "SELECT * "
                    ." FROM $this->database.$this->table".
                    " WHERE $this->column_customer_serial = $customer_serial".
                    " ORDER BY $sort $order_type LIMIT $skip,$limit";
            Log::i($sql);
            
            $query = $this->pdo->query($sql);
            if($query){
            	require_once 'class.categorymanager.php';
            	$mgr = new CategoryManager();
            	$column_category        = DatabaseProxy::DB_COLUMN_CATEGORY;
            	
                $i=0;
                foreach ($query as $row){
                    $array[$i]["$this->column_group"] = $row["$this->column_group"];
                    $array[$i]["$this->column_group_serial"] = $row["$this->column_group_serial"];
                    $array[$i]["$this->column_icon"] = $row["$this->column_icon"];
                    $array[$i]["$this->column_priority"] = $row["$this->column_priority"];
                    //$array[$i]["$this->column_notes"] = $row["$this->column_notes"]." 包含门类：";
                    $categories = $mgr->getCategoriesByGroup($row["$this->column_group_serial"]);
                    foreach ($categories as $category){
                    	$array[$i]["$this->column_notes"] .= $category["$column_category"].", ";
                    }
                    $array[$i]["$this->column_register_date"] = $row["$this->column_register_date"];
                    $i++;
                }
                return $array;
            }
            else{
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    print_r($this->pdo->errorInfo(),true),
                    Log::ERR_ERROR);
                
            }
        }
        return FALSE;
        
    }

    public function checkGroup($group)
    {
        if($this->pdo != null){
            $sql = "SELECT $this->column_group FROM $this->database.$this->table WHERE $this->column_group = '$group' LIMIT 1";
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                if($row["$this->column_group"] == $group){
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
    
    public function addGroup($group, $icon, $priority,$notes)
    {
        if ($this->pdo != null){
            $register_date = date('Y-m-d');
            $update_time = date('Y-m-d H:i:s');
/*            
            $sql = "INSERT INTO $this->database.$this->table ( $this->column_group )".
                            " VALUES ( '$group')";
  */          
            $sql = "INSERT INTO $this->database.$this->table ( $this->column_group, $this->column_icon, $this->column_priority, $this->column_notes, $this->column_register_date, $this->column_update_time )".
                            " VALUES ( '$group', '$icon', $priority,'$notes', '$register_date','$update_time')";

            Log::i($sql);
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
    
    public function checkGroupNew($group_serial, $group)
    {
        if($this->pdo != null){
            $sql = "SELECT $this->column_group FROM $this->database.$this->table WHERE $this->column_group = '$group' AND $this->column_group_serial != $group_serial LIMIT 1";
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                if($row["$this->column_group"] == $group){
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
    
    public function updateGroup($group_serial,$group, $icon, $priority,$notes)
    {
        if ($this->pdo != null){
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "UPDATE $this->database.$this->table SET "
                        ."$this->column_group = '$group'"
                        .", $this->column_icon = '$icon'"
                        .", $this->column_priority = $priority"
                        .", $this->column_notes = '$notes'"
                        .", $this->column_update_time = '$update_time'"
                        ." WHERE $this->column_group_serial = $group_serial";
            Log::i($sql);
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
    
    
    public function removeGroup($group)
    {
        if ($this->pdo != null){
            $sql = "DELETE FROM $this->database.$this->table" 
                        ." WHERE $this->column_group = '$group'";
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