<?php

/**
 * ModelManager - ApkStore model information management class
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

class ModelManager
{

    /////////////////////////////////////////////////
    // PROPERTIES, PUBLIC
    /////////////////////////////////////////////////


    /////////////////////////////////////////////////
    // PROPERTIES, PRIVATE
    /////////////////////////////////////////////////

    private $db         = null;
    private $pdo        = null;

    
    private $table = DatabaseProxy::DB_TABLE_MODELS;
    
    private $column_model_serial = DatabaseProxy::DB_COLUMN_MODEL_SERIAL;
    private $column_model = DatabaseProxy::DB_COLUMN_MODEL;
    private $column_notes = DatabaseProxy::DB_COLUMN_MODEL_NOTES;
    private $column_register_date = DatabaseProxy::DB_COLUMN_MODEL_REGISTER_DATE;
    
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
        if ($this->db != null){
            $this->db = null;
            $this->pdo = null;
        }
    }
    
    public function getTotal()
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_MODELS;
            $column_model = DatabaseProxy::DB_COLUMN_MODEL;
            $sql = "SELECT $column_model"
                    ." FROM $table";
            Log::i($sql);
            $query = $this->pdo->query($sql);
            if($query){
                $array = $query->fetchAll();
                Log::i(count($array));
                return count($array);
            }
            else {
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    print_r($this->pdo->errorInfo(),TRUE),
                    Log::ERR_ERROR);
                
            }
        }
        return 0;
    }
    

    

    public function fetchModelByBrands($brand_array, $sort, $cur_page, $limit, $order = 0)
    {
    	$array_model = false;
    	if($this->pdo != null){
    		$table_brand = DatabaseProxy::DB_TABLE_BRANDS;
    		$table_customer = DatabaseProxy::DB_TABLE_CUSTOMERS;
    
    		$column_brand_serial = DatabaseProxy::DB_COLUMN_BRAND_SERIAL;
    		$column_brand = DatabaseProxy::DB_COLUMN_BRAND;
    		$column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;
    		$column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
    
    		$skip = ($cur_page - 1) * $limit;
    
    		if($order == 0){
    			$order_type = "ASC";
    		}
    		else{
    			$order_type = "DESC";
    		}
    
    		
    		$sql = "SELECT ".
    			"$this->table.$this->column_model AS $this->column_model".
    			", $this->table.$this->column_model_serial AS $this->column_model_serial".
    			", $table_brand.$column_brand AS $column_brand".
    			", $this->table.$this->column_register_date AS $this->column_register_date".
    			" FROM $this->table".
    			" INNER JOIN $table_brand ON ($this->table.$column_brand_serial = $table_brand.$column_brand_serial)";
    		if ($brand_array) {
					for($i=0;$i<count($brand_array);$i++){
						if ($i==0){
							$sql .= " WHERE ( $this->table.$column_brand_serial = $brand_array[$i]";
						}
						else{
							$sql .= " $this->table.$column_brand_serial = $brand_array[$i]";
						}
					}
					$sql .= ") ";
    		}	
    		$sql .= " ORDER BY $sort $order_type LIMIT $skip,$limit";
    		
    		$query = $this->pdo->query($sql);
    			
    		if($query){
    			$i=0;
    			foreach ($query as $row){
    						$array_model[$i]["$this->column_model_serial"] = $row["$this->column_model_serial"];
    						$array_model[$i]["$this->column_model"] = $row["$this->column_model"];
    						$array_model[$i]["$column_brand"] = $row["$column_brand"];
    						$array_model[$i]["$this->column_register_date"] = $row["$this->column_register_date"];
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
   		return $array_model;
    
    }
    
    public function fetchModel($sort, $cur_page, $limit, $order = 0)
    {
    	if($this->pdo != null){
    		$table_model = DatabaseProxy::DB_TABLE_MODELS;
    		$table_brand = DatabaseProxy::DB_TABLE_BRANDS;
    		$table_customer = DatabaseProxy::DB_TABLE_CUSTOMERS;
    
    		$column_model_serial = DatabaseProxy::DB_COLUMN_MODEL_SERIAL;
    		$column_model = DatabaseProxy::DB_COLUMN_MODEL;
    		$column_notes = DatabaseProxy::DB_COLUMN_MODEL_NOTES;
    		$column_register_date = DatabaseProxy::DB_COLUMN_MODEL_REGISTER_DATE;
    		$column_brand_serial = DatabaseProxy::DB_COLUMN_BRAND_SERIAL;
    		$column_brand = DatabaseProxy::DB_COLUMN_BRAND;
    		$column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;
    		$column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
    
    		$skip = ($cur_page - 1) * $limit;
    
    		if($order == 0){
    			$order_type = "ASC";
    		}
    		else{
    			$order_type = "DESC";
    		}
    
    		$sql = "SELECT $table_model.$column_model AS $column_model, $table_model.$column_model_serial AS $column_model_serial"
    		.", $table_model.$column_notes AS $column_notes, $table_model.$column_register_date AS $column_register_date "
    		.", $table_brand.$column_brand_serial AS $column_brand_serial, $table_brand.$column_brand AS $column_brand"
//    		.", $table_customer.$column_customer_serial AS $column_customer_serial, $table_customer.$column_customer AS $column_customer"
    		." FROM $table_model"
    		." INNER JOIN $table_brand ON ($table_model.$column_brand_serial = $table_brand.$column_brand_serial)"
//    				." INNER JOIN $table_customer ON ($table_model.$column_customer_serial = $table_customer.$column_customer_serial)"
    						." ORDER BY $sort $order_type LIMIT $skip,$limit";
    						$query = $this->pdo->query($sql);
    						if($query){
    						$i=0;
    						foreach ($query as $row){
    						$array_model[$i]["$column_model_serial"] = $row["$column_model_serial"];
    						$array_model[$i]["$column_model"] = $row["$column_model"];
    						$array_model[$i]["$column_brand_serial"] = $row["$column_brand_serial"];
    						$array_model[$i]["$column_brand"] = $row["$column_brand"];
    						$array_model[$i]["$column_customer_serial"] = $row["$column_customer_serial"];
    						$array_model[$i]["$column_customer"] = $row["$column_customer"];
    						$array_model[$i]["$column_notes"] = $row["$column_notes"];
    						$array_model[$i]["$column_register_date"] = $row["$column_register_date"];
    						$i++;
    						}
    						return $array_model;
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
    
    
    
    public function fetchCustomerModel($customer_serial, $sort, $cur_page, $limit, $order = 0)
    {
    	if($this->pdo != null){
    		$table_model = DatabaseProxy::DB_TABLE_MODELS;
    		$table_brand = DatabaseProxy::DB_TABLE_BRANDS;
    		$table_customer = DatabaseProxy::DB_TABLE_CUSTOMERS;
    
    		$column_model_serial = DatabaseProxy::DB_COLUMN_MODEL_SERIAL;
    		$column_model = DatabaseProxy::DB_COLUMN_MODEL;
    		$column_notes = DatabaseProxy::DB_COLUMN_MODEL_NOTES;
    		$column_register_date = DatabaseProxy::DB_COLUMN_MODEL_REGISTER_DATE;
    		$column_brand_serial = DatabaseProxy::DB_COLUMN_BRAND_SERIAL;
    		$column_brand = DatabaseProxy::DB_COLUMN_BRAND;
    		$column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;
    		$column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
    
    		$skip = ($cur_page - 1) * $limit;
    
    		if($order == 0){
    			$order_type = "ASC";
    		}
    		else{
    			$order_type = "DESC";
    		}
    
    		$sql = "SELECT $table_model.$column_model AS $column_model, $table_model.$column_model_serial AS $column_model_serial"
    		.", $table_model.$column_notes AS $column_notes, $table_model.$column_register_date AS $column_register_date "
    		.", $table_brand.$column_brand_serial AS $column_brand_serial, $table_brand.$column_brand AS $column_brand"
    		.", $table_customer.$column_customer_serial AS $column_customer_serial, $table_customer.$column_customer AS $column_customer"
    		." FROM $table_model"
    		." INNER JOIN $table_brand ON ($table_model.$column_brand_serial = $table_brand.$column_brand_serial)"
    				." INNER JOIN $table_customer ON ($table_model.$column_customer_serial = $table_customer.$column_customer_serial)".
    				" WHERE $table_model.$column_customer_serial = $customer_serial "
    						." ORDER BY $sort $order_type LIMIT $skip,$limit";
    		
    						$query = $this->pdo->query($sql);
    						if($query){
    						$i=0;
    						foreach ($query as $row){
    						$array_model[$i]["$column_model_serial"] = $row["$column_model_serial"];
    						$array_model[$i]["$column_model"] = $row["$column_model"];
    						$array_model[$i]["$column_brand_serial"] = $row["$column_brand_serial"];
    						$array_model[$i]["$column_brand"] = $row["$column_brand"];
    						$array_model[$i]["$column_customer_serial"] = $row["$column_customer_serial"];
    						$array_model[$i]["$column_customer"] = $row["$column_customer"];
    						$array_model[$i]["$column_notes"] = $row["$column_notes"];
    						$array_model[$i]["$column_register_date"] = $row["$column_register_date"];
    						$i++;
    						}
    						return $array_model;
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
    
    public function checkModel($model, $customer_serial, $brand_serial)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_MODELS;
            $column_model = DatabaseProxy::DB_COLUMN_MODEL;
            $column_customer_serial = DatabaseProxy::DB_COLUMN_MODEL_CUSTOMER_SERIAL;
            $column_brand_serial = DatabaseProxy::DB_COLUMN_MODEL_BRAND_SERIAL;
            
            $sql = "SELECT $column_model FROM $table"
                    ." WHERE $column_model = '$model' AND $column_customer_serial = $customer_serial AND $column_brand_serial = $brand_serial LIMIT 1";
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                if($row["$column_model"] == $model){
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
        return FALSE;
    }
    
    public function addModel($model, $customer_serial, $brand_serial, $notes)
    {
        if ($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_MODELS;
            $column_model = DatabaseProxy::DB_COLUMN_MODEL;
            $column_customer_serial = DatabaseProxy::DB_COLUMN_MODEL_CUSTOMER_SERIAL;
            $column_brand_serial = DatabaseProxy::DB_COLUMN_MODEL_BRAND_SERIAL;
            $column_notes = DatabaseProxy::DB_COLUMN_MODEL_NOTES;
            $column_register_date = DatabaseProxy::DB_COLUMN_MODEL_REGISTER_DATE;
            $column_update_time = DatabaseProxy::DB_COLUMN_MODEL_UPDATE_TIME;
            $register_date = date('Y-m-d');
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "INSERT INTO $table ( $column_model, $column_customer_serial, $column_brand_serial, $column_notes, $column_register_date, $column_update_time )".
                            " VALUES ( '$model', $customer_serial, $brand_serial, '$notes', '$register_date','$update_time')";
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

    public function addModelAuto($model, $brand_serial,$customer_serial)
    {
    	if ($this->pdo != null){
    		$table = DatabaseProxy::DB_TABLE_MODELS;
    		$column_model = DatabaseProxy::DB_COLUMN_MODEL;
    		$column_customer_serial = DatabaseProxy::DB_COLUMN_MODEL_CUSTOMER_SERIAL;
    		$column_brand_serial = DatabaseProxy::DB_COLUMN_MODEL_BRAND_SERIAL;
    		$column_notes = DatabaseProxy::DB_COLUMN_MODEL_NOTES;
    		$column_register_date = DatabaseProxy::DB_COLUMN_MODEL_REGISTER_DATE;
    		$column_update_time = DatabaseProxy::DB_COLUMN_MODEL_UPDATE_TIME;
    		$register_date = date('Y-m-d');
    		$update_time = date('Y-m-d H:i:s');
    
    		$sql = "INSERT INTO $table ( $column_model, $column_brand_serial,$column_customer_serial,  $column_register_date, $column_update_time )".
    				" VALUES ( '$model', $brand_serial, $customer_serial, '$register_date','$update_time')";
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
    
    public function getModelSerial($model, $brand_serial){
    	
    	if($this->pdo != null){
    		$table = DatabaseProxy::DB_TABLE_MODELS;
    		$column_serial = DatabaseProxy::DB_COLUMN_MODEL_SERIAL;
    		$column_model = DatabaseProxy::DB_COLUMN_MODEL;
    		$column_brand_serial = DatabaseProxy::DB_COLUMN_MODEL_BRAND_SERIAL;
    	
    		$sql = "SELECT * FROM $table"
    		." WHERE $column_model = '$model' AND $column_brand_serial = $brand_serial LIMIT 1";
    		$query = $this->pdo->query($sql);
    		if($query){
    		$row = $query->fetch();
    		if($row["$column_model"] == $model){
    			return $row["$column_serial"];
    		}
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

    public function getModel($model_serial){
    	$model = "";
    	if($this->pdo != null){
    		$table = DatabaseProxy::DB_TABLE_MODELS;
    		$column_serial = DatabaseProxy::DB_COLUMN_MODEL_SERIAL;
    		$column_model = DatabaseProxy::DB_COLUMN_MODEL;
    		 
    		$sql = "SELECT * FROM $table"
    		." WHERE $column_serial = $model_serial LIMIT 1";
    		$query = $this->pdo->query($sql);
    		if($query){
    			$row = $query->fetch();
	    		if($row["$column_serial"] == $model_serial){
	    			$model =  $row["$column_model"];
	    		}
	    	}
    		else{
	    		$log_msg = print_r($this->pdo->errorInfo(),true);
	    		Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
	    		$log_msg,
	    				Log::ERR_ERROR);
	    		}
    		}
    		return $model;
    
    }

    public function getModelByBrand($brand_array=FALSE){
    	$array_model = FALSE;
    	if($this->pdo != null){
    		$table = DatabaseProxy::DB_TABLE_MODELS;
    		$column_serial = DatabaseProxy::DB_COLUMN_MODEL_SERIAL;
    		$column_model = DatabaseProxy::DB_COLUMN_MODEL;
    		$column_brand_serial = DatabaseProxy::DB_COLUMN_MODEL_BRAND_SERIAL;
    		
    		$sql = "SELECT * FROM $table ";
    		
    		if($brand_array){
    			for($i=0;$i<count($brand_array);$i++){
    				if ($i==0){
    					$sql .= " WHERE $column_brand_serial = $brand_array[$i] ";
    				}
    				else{
    					$sql .= " OR $column_brand_serial = $brand_array[$i] ";
    				}
    			}
    		}
    		else{
    			;
    		}
    		//file_put_contents("../json/debug.json",$_SERVER ['PHP_SELF'].date(" Y-m-d H:i:s ").$sql);
    		
    		$query = $this->pdo->query($sql);
    		if($query){
    			$rows = $query->fetchAll();
   					$i=0;
    				foreach ($rows as $row){
    						$array_model[$i]["$column_serial"] = $row["$column_serial"];
    						$array_model[$i]["$column_model"] = $row["$column_model"];
    						$array_model[$i]["$column_brand_serial"] = $row["$column_brand_serial"];
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
    	return $array_model;
    
    }  

    public function getModelNames(){
    	if($this->pdo != null){
    		$table = DatabaseProxy::DB_TABLE_MODELS;
    		$column_serial = DatabaseProxy::DB_COLUMN_MODEL_SERIAL;
    		$column_model = DatabaseProxy::DB_COLUMN_MODEL;
    
    		$sql = "SELECT * FROM $table ";
    		
    		$query = $this->pdo->query($sql);
    		if($query){
    			$rows = $query->fetchAll();
    			$i=0;
    			foreach ($rows as $row){
    				$array["serial_". $row["$column_serial"]] = $row["$column_model"];
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
    
    public function updateModel($model_serial, $brand_serial, $customer_serial, $notes)
    {
        if ($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_MODELS;
            $column_model_serial = DatabaseProxy::DB_COLUMN_MODEL_SERIAL;
            $column_brand_serial = DatabaseProxy::DB_COLUMN_BRAND_SERIAL;
            $column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;
            $column_notes = DatabaseProxy::DB_COLUMN_MODEL_NOTES;
            $column_update_time = DatabaseProxy::DB_COLUMN_MODEL_UPDATE_TIME;
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "UPDATE $table SET "
                        ."$column_brand_serial = $brand_serial"
                        .", $column_customer_serial = $customer_serial"
                        .", $column_notes = '$notes'"
                        .", $column_update_time = '$update_time'"
                        ." WHERE $column_model_serial = $model_serial";
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
    
    
    public function removeModel($model_serial)
    {
        if ($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_MODELS;
            $column_model_serial = DatabaseProxy::DB_COLUMN_MODEL_SERIAL;
            
            $sql = "DELETE FROM $table" 
                        ." WHERE $column_model_serial = $model_serial";
            $query = $this->pdo->query($sql);
            if($query){
                return TRUE;
            }
            else{
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                        print_r($this->pdo->errorInfo(),TRUE),
                        Log::ERR_ERROR);
            }
                
        }
        return FALSE;    
    }
     
}
?>