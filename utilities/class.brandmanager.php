<?php

/**
 * BrandManager - ApkStore brand information management class
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

class BrandManager
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
            $table = DatabaseProxy::DB_TABLE_BRANDS;
            $column_brand = DatabaseProxy::DB_COLUMN_BRAND;
            $sql = "SELECT $column_brand"
                    ." FROM $table";
            $query = $this->pdo->query($sql);
            if($query){
                $array = $query->fetchAll();
                return count($array);
            }
        }
        return 0;
    }
    
    public function getBrands()
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_BRANDS;
            $column_brand_serial = DatabaseProxy::DB_COLUMN_BRAND_SERIAL;
            $column_brand = DatabaseProxy::DB_COLUMN_BRAND;
            
            $sql = "SELECT $column_brand, $column_brand_serial "
                    ." FROM $table ORDER BY $column_brand";
            $query = $this->pdo->query($sql);
            if($query){
                $i=0;
                foreach ($query as $row){
                    $array_brands[$i]["$column_brand"] = $row["$column_brand"];
                    $array_brands[$i]["$column_brand_serial"] = $row["$column_brand_serial"];
                    $i++;
                }
                return $array_brands;
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
    
    public function getBrandsByCustomer($customer_serial)
    {
    	$array =FALSE;
    	if($this->pdo != null){
    		$table = DatabaseProxy::DB_TABLE_BRANDS;
    		$column_brand_serial = DatabaseProxy::DB_COLUMN_BRAND_SERIAL;
    		$column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;
    		$column_brand = DatabaseProxy::DB_COLUMN_BRAND;
    		
    		$sql = "SELECT * ".
    		" FROM $table";
			if($customer_serial){
    			$sql .= " WHERE $column_customer_serial = $customer_serial";
    		}
    		
    		$query = $this->pdo->query($sql);
    		if($query){
	    		$i=0;
	    		$rows = $query->fetchAll();
	    		foreach ($rows as $row){
		    		$array[$i]["$column_brand_serial"] = $row["$column_brand_serial"];
		            $array[$i]["$column_brand"] = $row["$column_brand"];
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
    
    public function getBrandSerialsByCustomer($customer_serial)
    {
    	$array =FALSE;
    	if($this->pdo != null){
    		$table = DatabaseProxy::DB_TABLE_BRANDS;
    		$column_brand_serial = DatabaseProxy::DB_COLUMN_BRAND_SERIAL;
    		$column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;
    		
    		$sql = "SELECT $column_customer_serial, $column_brand_serial ".
    		" FROM $table";
			if($customer_serial){
    			$sql .= " WHERE $column_customer_serial = $customer_serial";
    		}
    		
    		
    		$query = $this->pdo->query($sql);
    		if($query){
	    		$i=0;
	    		$rows = $query->fetchAll();
	    		foreach ($rows as $row){
		    		$array[$i] = $row["$column_brand_serial"];
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
    
    public function fetchBrands($sort, $cur_page, $limit, $order = 0)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_BRANDS;
            $column_brand = DatabaseProxy::DB_COLUMN_BRAND;
            $column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;
            $column_notes = DatabaseProxy::DB_COLUMN_BRAND_NOTES;
            $column_register_date = DatabaseProxy::DB_COLUMN_BRAND_REGISTER_DATE;
            $table_customer = DatabaseProxy::DB_TABLE_CUSTOMERS;
            $column_customer = DatabaseProxy::DB_COLUMN_CUSTOMER;
            
            $skip = ($cur_page - 1) * $limit;
            if($order == 0){
                $order_type = "ASC";
            }
            else{
                $order_type = "DESC";
            }
            
            $sql = "SELECT ".
              	" $table.$column_brand AS $column_brand, ".
              	" $table.$column_notes AS $column_notes, ".
              	" $table.$column_register_date AS $column_register_date, ".
              	" $table.$column_customer_serial AS $column_customer_serial, ".
                " $table_customer.$column_customer AS $column_customer ".
                " FROM $table ". 
                " INNER JOIN $table_customer ".
                " ON ( $table.$column_customer_serial = $table_customer.$column_customer_serial )".
                " ORDER BY $sort $order_type LIMIT $skip,$limit";
            //$sql = "SELECT * FROM $table  ORDER BY $sort $order_type LIMIT $skip,$limit";
            
            Log::i($sql);
            $query = $this->pdo->query($sql);
            if($query){
                $i=0;
                $result = $query->fetchAll();
                foreach ($result as $row){
                    $array_brands[$i]["$column_brand"] = $row["$column_brand"];
                    $array_brands[$i]["$column_notes"] = $row["$column_notes"];
                    $array_brands[$i]["$column_customer"] = $row["$column_customer"];
                    $array_brands[$i]["$column_customer_serial"] = $row["$column_customer_serial"];
                    $array_brands[$i]["$column_register_date"] = $row["$column_register_date"];
                    $i++;
                }
                return $array_brands;
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

    public function checkBrand($brand)
    {
        if($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_BRANDS;
            $column_brand = DatabaseProxy::DB_COLUMN_BRAND;
            $sql = "SELECT $column_brand FROM $table WHERE $column_brand = '$brand' LIMIT 1";
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                if($row["$column_brand"] == $brand){
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
    
    public function getBrandSerial($brand)
    {
    	$serial = 0;
    	if($this->pdo != null){
    		$table = DatabaseProxy::DB_TABLE_BRANDS;
    		$column_brand = DatabaseProxy::DB_COLUMN_BRAND;
    		$column_serial = DatabaseProxy::DB_COLUMN_BRAND_SERIAL;
    		$sql = "SELECT * FROM $table WHERE $column_brand = '$brand' ORDER BY $column_serial LIMIT 1";
    		$query = $this->pdo->query($sql);
    		if($query){
    			$row = $query->fetch();
    			$serial =  $row["$column_serial"];
    			
    		}
    	}
    	return $serial;
    }
    
    public function getBrandCustomerSerial($brand_serial)
    {
    	$serial = 0;
    	if($this->pdo != null){
    		$table = DatabaseProxy::DB_TABLE_BRANDS;
    		$column_brand = DatabaseProxy::DB_COLUMN_BRAND;
    		$column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;
    		$column_serial = DatabaseProxy::DB_COLUMN_BRAND_SERIAL;
    		$sql = "SELECT * FROM $table WHERE $column_serial = $brand_serial ORDER BY $column_serial LIMIT 1";
    		$query = $this->pdo->query($sql);
    		if($query){
    			$row = $query->fetch();
    			$serial =  $row["$column_customer_serial"];
    		}
    	}
    	return $serial;
    }
    
    public function getFiltertype($brand_serial)
    {
    	$result = 0;
    	if($this->pdo != null){
    		$table = DatabaseProxy::DB_TABLE_BRANDS;
    		$column_brand = DatabaseProxy::DB_COLUMN_BRAND;
    		$column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;
    		$column_serial = DatabaseProxy::DB_COLUMN_BRAND_SERIAL;
    		$column_filtertype = DatabaseProxy::DB_COLUMN_BRAND_FILTERTYPE;
    		$sql = "SELECT * FROM $table WHERE $column_serial = $brand_serial ORDER BY $column_serial LIMIT 1";
    		$query = $this->pdo->query($sql);
    		if($query){
    			$row = $query->fetch();
    			$result =  $row["$column_filtertype"];
    		}
    	}
    	return $result;
    }
    
    public function addBrand($brand, $notes = "", $customer_serial=1)
    {
        if ($this->pdo != null){
            $table = DatabaseProxy::DB_TABLE_BRANDS;
            $column_brand = DatabaseProxy::DB_COLUMN_BRAND;
            $column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;
            $column_notes = DatabaseProxy::DB_COLUMN_BRAND_NOTES;
            $column_register_date = DatabaseProxy::DB_COLUMN_BRAND_REGISTER_DATE;
            $column_update_time = DatabaseProxy::DB_COLUMN_BRAND_UPDATE_TIME;
            $register_date = date('Y-m-d');
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "INSERT INTO $table ( $column_brand, $column_notes, $column_customer_serial, $column_register_date, $column_update_time )".
                            " VALUES ( '$brand', '$notes', $customer_serial, '$register_date','$update_time')";
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

    public function updateBrand($brand, $notes, $customer_serial=1)
    {
        if ($this->pdo != null){
//            if (!$this->checkCustomer($customer)){
                $table = DatabaseProxy::DB_TABLE_BRANDS;
                $column_brand = DatabaseProxy::DB_COLUMN_BRAND;
                $column_notes = DatabaseProxy::DB_COLUMN_BRAND_NOTES;
                $column_customer_serial = DatabaseProxy::DB_COLUMN_CUSTOMER_SERIAL;
                $column_update_time = DatabaseProxy::DB_COLUMN_BRAND_UPDATE_TIME;
                $update_time = date('Y-m-d H:i:s');
                
                $sql = "UPDATE $table SET "
                        ."$column_notes = '$notes'"
                        .", $column_update_time = '$update_time'".
                        " , $column_customer_serial = $customer_serial "
                        ." WHERE $column_brand = '$brand'";
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
    
    
    public function removeBrand($brand)
    {
        if ($this->pdo != null){
                $table = DatabaseProxy::DB_TABLE_BRANDS;
                $column_brand = DatabaseProxy::DB_COLUMN_BRAND;
                
                $sql = "DELETE FROM $table" 
                        ." WHERE $column_brand = '$brand'";
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