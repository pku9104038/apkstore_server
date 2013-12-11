<?php

/**
 * CategoryManager - ApkStore application category management class
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

class CategoryManager
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
    
    private $table                  = DatabaseProxy::DB_TABLE_CATEGORIES;
    
    private $column_serial          = DatabaseProxy::DB_COLUMN_CATEGORY_SERIAL;
    private $column_category        = DatabaseProxy::DB_COLUMN_CATEGORY;
    private $column_group_serial    = DatabaseProxy::DB_COLUMN_CATEGORY_GROUP_SERIAL;
    private $column_notes           = DatabaseProxy::DB_COLUMN_CATEGORY_NOTES;
    private $column_register_date   = DatabaseProxy::DB_COLUMN_CATEGORY_REGISTER_DATE;
    private $column_update_time     = DatabaseProxy::DB_COLUMN_CATEGORY_UPDATE_TIME;
    private $column_customer_serial = DatabaseProxy::DB_COLUMN_CATEGORY_CUSTOMER_SERIAL;
    

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
        Log::i('getTotal');
        if($this->pdo != null){
            $sql = "SELECT $this->column_serial"
                    ." FROM $this->database.$this->table";
            $query = $this->pdo->query($sql);
            if($query){
                $array = $query->fetchAll();
                return count($array);
            }
        }
        return 0;
    }
    
    public function getCategories()
    {
        Log::i("getCategories");
        if($this->pdo != null){
            $table_group = DatabaseProxy::DB_TABLE_GROUP;
            $column_group = DatabaseProxy::DB_COLUMN_GROUP;
            $column_group_priority = DatabaseProxy::DB_COLUMN_GROUP_PRIORITY;
            
            require_once 'class.groupmanager.php';
            $groupMgr = new GroupManager();
            $array_group = $groupMgr->getGroups();
            foreach ($array_group as $row) {
                $name[$row["$this->column_group_serial"]]  = $row["$column_group"];
//                $icon[$row["$this->column_group_serial"]] = $row["$column_group_icon"];
                Log::i("row:",$row["$this->column_group_serial"]);
                Log::i("name:",$name[$row["$this->column_group_serial"]]);
                
            }
            
            
            $sql = "SELECT "
                    ." $this->table.$this->column_category AS $this->column_category"
                    .", $this->table.$this->column_serial AS $this->column_serial"
                    .", $this->table.$this->column_group_serial AS $this->column_group_serial"
//                    .", $table_group.$column_group AS $column_group"
                    ." FROM $this->database.$this->table AS $this->table"
//                    ." INNER JOIN $this->database.$table_group AS $table_group"
//                    ." ON( $this->table.$this->column_group_serial = $table_group.$this->column_group_serial)"
                    ." ORDER BY $this->table.$this->column_group_serial";
            Log::i($sql);      
            $query = $this->pdo->query($sql);
            if($query){
                $result = $query->fetchAll();
                $i=0;
                foreach ($result as $row){
                    $array[$i]["$this->column_serial"] = $row["$this->column_serial"];
                    $array[$i]["$this->column_category"] = $row["$this->column_category"];
                    $array[$i]["$this->column_group_serial"] = $row["$this->column_group_serial"];
                    $array[$i]["$column_group"] = $name[$row["$this->column_group_serial"]];//$row["$column_group"];
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
    
    public function getCategoriesByGroup($group_serial)
    {
        Log::i("getCategoriesByGroup:".$group_serial);
        if($this->pdo != null){
            
            $sql = "SELECT "
                    ." $this->table.$this->column_category AS $this->column_category"
                    .", $this->table.$this->column_serial AS $this->column_serial"
                    .", $this->table.$this->column_group_serial AS $this->column_group_serial"
                    ." FROM $this->database.$this->table AS $this->table"
                    ." WHERE $this->table.$this->column_group_serial = $group_serial"
                    ." ORDER BY $this->table.$this->column_category";
            Log::i($sql);      
            $query = $this->pdo->query($sql);
            if($query){
                $result = $query->fetchAll();
                $i=0;
                $array = FALSE;
                foreach ($result as $row){
                    $array[$i]["$this->column_serial"] = $row["$this->column_serial"];
                    $array[$i]["$this->column_category"] = $row["$this->column_category"];
                    Log::i($array[$i]["$this->column_category"]);   
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

    public function getCategoriesByGroupArray($group_array=FALSE)
    {
    	$array = FALSE;
    	if($this->pdo != null){
    
    		$sql = "SELECT "
    				." $this->table.$this->column_category AS $this->column_category"
    				.", $this->table.$this->column_serial AS $this->column_serial"
    				.", $this->table.$this->column_group_serial AS $this->column_group_serial".
    				" FROM $this->database.$this->table AS $this->table";

    		if($group_array){
    			for($i=0;$i<count($group_array);$i++){
    				if ($i==0){
    					$sql .= " WHERE ( $this->column_group_serial = $group_array[$i]";
    				}
    				else{
    					$sql .= " OR $this->column_group_serial = $group_array[$i]";
    				}
    			}
    			$sql .= ") ";
    		}
    		else{
    			;
    		}
    		
    		//echo $sql;
     		$query = $this->pdo->query($sql);
    		if($query){
    			$result = $query->fetchAll();
    			$i=0;
    			
    			foreach ($result as $row){
    				$array[$i]["$this->column_serial"] = $row["$this->column_serial"];
    				$array[$i]["$this->column_category"] = $row["$this->column_category"];
    				$array[$i]["$this->column_group_serial"] = $row["$this->column_group_serial"];
    				$i++;
    
    			}
    		}
    		else{
	    		$log_msg = print_r($this->pdo->errorInfo(),true);
	    		Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
	    		$log_msg,
	    		Log::ERR_ERROR);
    
    		}
    	}
    	return $array;
    
    }
    
    public function getCategoriesByAndroidAidGroup($group_serial)
    {
        Log::i("getCategoriesByAndroidAidGroup:".$group_serial);
        if($this->pdo != null){
    
            $column_androidaid_group_serial = DatabaseProxy::DB_COLUMN_CATEGORY_ANDROIDAID_GROUP_SERIAL;
            
            $sql = "SELECT "
            ." $this->table.$this->column_category AS $this->column_category"
            .", $this->table.$this->column_serial AS $this->column_serial"
            .", $this->table.$column_androidaid_group_serial AS $this->column_group_serial"
            ." FROM $this->database.$this->table AS $this->table"
            ." WHERE $this->table.$column_androidaid_group_serial = $group_serial"
            ." ORDER BY $this->table.$this->column_category";
            Log::i($sql);
            $query = $this->pdo->query($sql);
            if($query){
            $result = $query->fetchAll();
            $i=0;
            foreach ($result as $row){
            $array[$i]["$this->column_serial"] = $row["$this->column_serial"];
            $array[$i]["$this->column_category"] = $row["$this->column_category"];
            Log::i($array[$i]["$this->column_category"]);
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
    
    public function fetchCategories($sort= DatabaseProxy::DB_COLUMN_CATEGORY, $cur_page = 1 , $limit = 1000, $order = 0)
    {
        Log::i('fetchCategories');
        if($this->pdo != null){
            $table_group = DatabaseProxy::DB_TABLE_GROUP;
            $column_group = DatabaseProxy::DB_COLUMN_GROUP;
            $column_group_icon = DatabaseProxy::DB_COLUMN_GROUP_ICON;
            
            require_once 'class.groupmanager.php';
            $groupMgr = new GroupManager();
            $array_group = $groupMgr->getGroups();
            foreach ($array_group as $row) {
                $name[$row["$this->column_group_serial"]]  = $row["$column_group"];
                $icon[$row["$this->column_group_serial"]] = $row["$column_group_icon"];
	        }
            
            $skip = ($cur_page - 1) * $limit;
            if($order == 0){
                $order_type = "ASC";
            }
            else{
                $order_type = "DESC";
            }
            
            
            $sql = "SELECT "
            				." $this->table.$this->column_serial AS $this->column_serial"
            				.", $this->table.$this->column_category AS $this->column_category"
            				.", $this->table.$this->column_group_serial AS $this->column_group_serial"
            //				.", $table_group.$column_group AS $column_group"
            //				.", $table_group.$column_group_icon AS $column_group_icon"
            				.", $this->table.$this->column_register_date AS $this->column_register_date"
                    .", $this->table.$this->column_notes AS $this->column_notes"
                    ." FROM $this->database.$this->table AS $this->table"
            //        ." INNER JOIN $this->database.$table_group AS $table_group"
            //        ." ON ($this->table.$this->column_group_serial = $table_group.$this->column_group_serial)"
                    ." WHERE $this->column_customer_serial = 0"
            		." ORDER BY $sort $order_type LIMIT $skip,$limit";
            
            Log::i($sql);
            
            $query = $this->pdo->query($sql);
            if($query){
                $i=0;
                $result = $query->fetchAll();
                require_once 'class.applicationmanager.php';
                $appMgr = new ApplicationManager();
                $column_application = DatabaseProxy::DB_COLUMN_APPLICATION;
                $column_package         = DatabaseProxy::DB_COLUMN_APPLICATION_PACKAGE;
                
                foreach ($result as $row){
                    $array[$i]["$this->column_serial"] = $row["$this->column_serial"];
                    $array[$i]["$this->column_category"] = $row["$this->column_category"];
                    $array[$i]["$this->column_group_serial"] = $row["$this->column_group_serial"];
                    $array[$i]["$column_group"] = $name[$row["$this->column_group_serial"]];//$array_group[$this->column_group_serial]["$column_group"];
                    $array[$i]["$column_group_icon"] = $icon[$row["$this->column_group_serial"]];//$array_group[$this->column_group_serial]["$column_group_icon"];
                    //$array[$i]["$this->column_notes"] = $row["$this->column_notes"];
                    $array[$i]["$this->column_register_date"] = $row["$this->column_register_date"];
                    Log::i($row["$this->column_group_serial"],$array[$i]["$column_group"]);
                    $apps = $appMgr->getApplicationsByCategory($array[$i]["$this->column_serial"]);
                    //$appnames=count($apps).": ";
                    $appnames="";
                    foreach($apps as $app){
                    	$appnames = $appnames.$app["$column_application"].",   ";
                    }
                    $array[$i]["$this->column_notes"] = $appnames;
                    
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

    public function fetchAndroidAidCategories($sort= DatabaseProxy::DB_COLUMN_CATEGORY, $cur_page = 1 , $limit = 1000, $order = 0)
    {
        Log::i('fetchAndroidAidCategories');
        if($this->pdo != null){
            $table_group = DatabaseProxy::DB_TABLE_ANDROIDAID_GROUP;
            $column_group = DatabaseProxy::DB_COLUMN_ANDROIDAID_GROUP;
            $column_androidaid_group_serial = DatabaseProxy::DB_COLUMN_CATEGORY_ANDROIDAID_GROUP_SERIAL;
    
            require_once 'class.androidaidgroupmanager.php';
            $groupMgr = new AndroidaidGroupManager();
            $array_group = $groupMgr->getGroups();
            foreach ($array_group as $row) {
                $name[$row["$this->column_group_serial"]]  = $row["$column_group"];
                Log::i("row:",$row["$this->column_group_serial"]);
                Log::i("name:",$name[$row["$this->column_group_serial"]]);
    
            }
    
            $skip = ($cur_page - 1) * $limit;
            if($order == 0){
                $order_type = "ASC";
            }
            else{
                $order_type = "DESC";
            }
    
    
            $sql = "SELECT "
            ." $this->table.$this->column_serial AS $this->column_serial"
            .", $this->table.$this->column_category AS $this->column_category"
            .", $this->table.$column_androidaid_group_serial AS $column_androidaid_group_serial"
            //				.", $table_group.$column_group AS $column_group"
            //				.", $table_group.$column_group_icon AS $column_group_icon"
            .", $this->table.$this->column_register_date AS $this->column_register_date"
            .", $this->table.$this->column_notes AS $this->column_notes"
            ." FROM $this->database.$this->table AS $this->table"
            //        ." INNER JOIN $this->database.$table_group AS $table_group"
            //        ." ON ($this->table.$this->column_group_serial = $table_group.$this->column_group_serial)"
            ." ORDER BY $sort $order_type LIMIT $skip,$limit";
    
            Log::i($sql);
    
            $query = $this->pdo->query($sql);
            if($query){
            $i=0;
            $result = $query->fetchAll();
            foreach ($result as $row){
            $array[$i]["$this->column_serial"] = $row["$this->column_serial"];
            $array[$i]["$this->column_category"] = $row["$this->column_category"];
            $array[$i]["$column_androidaid_group_serial"] = $row["$column_androidaid_group_serial"];
            $array[$i]["$column_group"] = $name[$row["$column_androidaid_group_serial"]];//$array_group[$this->column_group_serial]["$column_group"];
            $array[$i]["$this->column_notes"] = $row["$this->column_notes"];
            $array[$i]["$this->column_register_date"] = $row["$this->column_register_date"];
            Log::i($row["$column_androidaid_group_serial"],$array[$i]["$column_group"]);
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
    
    
    public function checkCategory($category,$customer_serial = 0)
    {
        if($this->pdo != null){
            $sql = "SELECT $this->column_category ".
            		" FROM $this->database.$this->table".
            		" WHERE $this->column_category = '$category'".
            		" AND $this->column_customer_serial = $customer_serial".
            		" LIMIT 1";
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                if($row["$this->column_category"] == $category){
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
    
    public function addCategory($category, $group_serial, $notes, $customer_serial=0)
    {
        if ($this->pdo != null){
            $register_date = date('Y-m-d');
            $update_time = date('Y-m-d H:i:s');

            $sql = "INSERT INTO $this->database.$this->table ( "
                    ." $this->column_category"
                    .", $this->column_group_serial"
                    .", $this->column_notes"
                    .", $this->column_register_date"
                    .", $this->column_customer_serial"
                    .", $this->column_update_time )"
                    ." VALUES ( "
                    ."'$category'"
                    .", $group_serial"
                    .", '$notes'"
                    .", '$register_date'".
                    ", $customer_serial"
                    .",'$update_time')";

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
    
    public function checkCategoryNew($category_serial, $category, $customer_serial = 0)
    {
        if($this->pdo != null){
            $sql = "SELECT $this->column_category "
                ." FROM $this->database.$this->table "
                ." WHERE $this->column_serial != $category_serial "
                ." AND $this->column_category = '$category'"
                ." AND $this->column_customer_serial = $customer_serial"
                ." LIMIT 1";
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                if($row["$this->column_category"] == $category){
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
    
    public function updateCategory($serial,$category, $group_serial, $notes)
    {
        if ($this->pdo != null){
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "UPDATE $this->database.$this->table SET "
                        ."$this->column_category = '$category'"
                        .", $this->column_group_serial = $group_serial"
                        .", $this->column_notes = '$notes'"
                        .", $this->column_update_time = '$update_time'"
                        ." WHERE $this->column_serial = $serial";
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
    
    
    public function updateAndroidAidCategory($serial,$group_serial)
    {
        if ($this->pdo != null){
            $update_time = date('Y-m-d H:i:s');
            $column_androidaid_group_serial = DatabaseProxy::DB_COLUMN_CATEGORY_ANDROIDAID_GROUP_SERIAL;
                
            $sql = "UPDATE $this->database.$this->table SET "
                        ." $column_androidaid_group_serial = $group_serial"
                        .", $this->column_update_time = '$update_time'"
                        ." WHERE $this->column_serial = $serial";
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
    
    public function removeCategory($serial)
    {
        if ($this->pdo != null){
            $sql = "DELETE FROM $this->database.$this->table" 
                        ." WHERE $this->column_serial = $serial";
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
    
    
    public function getCategoriesLatestStamp()
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

    public function getCategoriesSerialByGroup($group_serial)
    {
    	if($this->pdo != null){
    
    		$sql = "SELECT ".
    				" $this->table.$this->column_serial AS $this->column_serial".
    				" FROM $this->database.$this->table AS $this->table".
    				" WHERE $this->table.$this->column_group_serial = $group_serial".
    				" ORDER BY $this->table.$this->column_serial";
    
    		Log::i($sql);
    
    		$query = $this->pdo->query($sql);
    
    		if($query){
    
    			$result = $query->fetchAll();
    			$i=0;
    			$array = FALSE;
    			foreach ($result as $row){
    
    				$array[$i]["$this->column_serial"] = $row["$this->column_serial"];
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
    
    public function getGroupsCategories($groups){
    	$group_num = count($groups);
    	for($i=0 ;$i<$group_num; $i++){
    		$group = $groups[$i];
    		$categories = $this->getCategoriesSerialByGroup($group);
    		$groupscategories[$i]["$this->column_group_serial"] = $group; 
    		$groupscategories[$i]["$this->column_serial"] = $categories;
    		//$array[i] = json_encode($groupscategories[i]);
    		
    	}
    	return $groupscategories;
    }
    
    public function getCategoryGroup($category_serial)
    {
    	$group = FALSE;
    	if($this->pdo != null){
    
    		$sql = "SELECT ".
    				" $this->table.$this->column_group_serial AS $this->column_group_serial".
    				" FROM $this->database.$this->table AS $this->table".
    				" WHERE $this->table.$this->column_serial = $category_serial".
    				" ORDER BY $this->table.$this->column_serial LIMIT 1";
    
    		Log::i($sql);
    
    		$query = $this->pdo->query($sql);
    
    		if($query){
    
    			$result = $query->fetchAll();
    			$i=0;
    			foreach ($result as $row){
    
    				$group = $row["$this->column_group_serial"];
    				$i++;
    
    			}
    		}
    		else{
    			$log_msg = print_r($this->pdo->errorInfo(),true);
    			Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
    			$log_msg,
    			Log::ERR_ERROR);
    
    		}
    	}
    	return $group;
    }
    
    public function getCategoryArrayByCustomer($customer_serial)
    {
    	$array = FALSE;
    	if($this->pdo != null){
    		$i=0;
    		
    		$sql = "SELECT ".
    				" $this->table.$this->column_serial AS $this->column_serial".
    				" FROM $this->database.$this->table AS $this->table".
    				" WHERE $this->table.$this->column_customer_serial = $customer_serial";
    				
    
    		$query = $this->pdo->query($sql);
    
    		if($query){
    
    			$result = $query->fetchAll();
    			foreach ($result as $row){
    
    				$array[$i] = $row["$this->column_serial"];
    				$i++;
    
    			}
    		}
    		
    		$sql = "SELECT ".
    				" $this->table.$this->column_serial AS $this->column_serial".
    				" FROM $this->database.$this->table AS $this->table".
    				" WHERE $this->table.$this->column_customer_serial = 0";
    		
    		
    		$query = $this->pdo->query($sql);
    		
    		if($query){
    		
    			$result = $query->fetchAll();
    			foreach ($result as $row){
    		
    				$array[$i] = $row["$this->column_serial"];
    				$i++;
    		
    			}
    		}
    		    		
    	}
    	return $array;
    }
    public function isCustomizedCategory($customer_serial)
    {
    	$array = FALSE;
    	if($this->pdo != null){
    		$i=0;
    
    		$sql = "SELECT ".
    				" $this->table.$this->column_serial AS $this->column_serial".
    				" FROM $this->database.$this->table AS $this->table".
    				" WHERE $this->table.$this->column_customer_serial = $customer_serial";
    
    
    		$query = $this->pdo->query($sql);
    
    		if($query){
    
    			$result = $query->fetchAll();
    			foreach ($result as $row){
    
    				$array[$i] = $row["$this->column_serial"];
    				$i++;
    
    			}
    		}
    
    	}
    	if ($array) {
    		return count($array);
    	}
    	else{
    		return FALSE;
    	}
    }      
}
?>