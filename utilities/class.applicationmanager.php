<?php

/**
 * ApplicationManager - ApkStore application category management class
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
require_once dirname(dirname(__FILE__)).'/utilities/class.categorymanager.php';

class ApplicationManager
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
    
    private $table                  = DatabaseProxy::DB_TABLE_APPLICATIONS;
    
    private $column_serial          = DatabaseProxy::DB_COLUMN_APPLICATION_SERIAL;
    private $column_application     = DatabaseProxy::DB_COLUMN_APPLICATION;
    private $column_category_serial = DatabaseProxy::DB_COLUMN_APPLICATION_CATEGORY_SERIAL;
    private $column_package         = DatabaseProxy::DB_COLUMN_APPLICATION_PACKAGE;
    private $column_icon            = DatabaseProxy::DB_COLUMN_APPLICATION_IOCN;
    private $column_producer        = DatabaseProxy::DB_COLUMN_APPLICATION_PRODUCER;
    private $column_description     = DatabaseProxy::DB_COLUMN_APPLICATION_DESCRIPTION;
    private $column_notes           = DatabaseProxy::DB_COLUMN_APPLICATION_NOTES;
    private $column_online          = DatabaseProxy::DB_COLUMN_APPLICATION_ONLINE;
    private $column_register_date   = DatabaseProxy::DB_COLUMN_APPLICATION_REGISTER_DATE;
    private $column_update_time     = DatabaseProxy::DB_COLUMN_APPLICATION_UPDATE_TIME;
    private $column_puup     		= DatabaseProxy::DB_COLUMN_APPLICATION_PUUP_POINT;
    private $column_puup_update_time= DatabaseProxy::DB_COLUMN_APPLICATION_PUUP_UPDATE_TIME;
    private $column_promotion		= DatabaseProxy::DB_COLUMN_APPLICATION_PROMOTION;
    private $column_customer_online	= DatabaseProxy::DB_COLUMN_APPLICATION_CUSTOMER_ONLINE;
    private $column_introduce		= DatabaseProxy::DB_COLUMN_APPLICATION_INTRODUCE;
    

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
//            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__ ,
//                self::STR_DB_CONN_SUCCESS );
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
    
    public function getTotal($online)
    {
        Log::i('getTotal');
        if($this->pdo != null){
            $sql = "SELECT $this->column_serial"
                    ." FROM $this->database.$this->table"
                    ." WHERE $this->column_online = $online";
            $query = $this->pdo->query($sql);
            if($query){
                $array = $query->fetchAll();
                return count($array);
            }
        }
        return 0;
    }
    
    public function getTotalByGroup($group_serial, $online)
    {
    	Log::i('getTotal');
    	if($this->pdo != null){
    		$sql = "SELECT $this->column_serial"
    		." FROM $this->database.$this->table"
    		." WHERE $this->column_online = $online".
    		" AND $this->c";
    		$query = $this->pdo->query($sql);
    		if($query){
    		$array = $query->fetchAll();
    		return count($array);
    		}
    		}
    			return 0;
    		}
    
    public function fetchApplications($sort, $cur_page, $limit, $order = 0, $online = 1)
    {
        Log::i('fetchApplications');
        if($this->pdo != null){
            $skip = ($cur_page - 1) * $limit;
            if($order == 0){
                $order_type = "ASC";
            }
            else{
                $order_type = "DESC";
            }
            
            $table_category = DatabaseProxy::DB_TABLE_CATEGORIES;
            $column_category = DatabaseProxy::DB_COLUMN_CATEGORY;
            $column_promotion = DatabaseProxy::DB_TABLE_PROMOTIONS;
            
            $sql = "SELECT "
            				." $this->table.$this->column_serial AS $this->column_serial"
            				.", $this->table.$this->column_application AS $this->column_application"
            				.", $this->table.$this->column_puup AS $this->column_puup"
            				.", $this->table.$this->column_category_serial AS $this->column_category_serial"
            				.", $table_category.$column_category AS $column_category"
            				.", $this->table.$this->column_icon AS $this->column_icon"
                    .", $this->table.$this->column_package AS $this->column_package"
                    .", $this->table.$this->column_producer AS $this->column_producer"
                    .", $this->table.$this->column_introduce AS $this->column_introduce"
                    .", $this->table.$this->column_register_date AS $this->column_register_date"
                    .", $this->table.$this->column_notes AS $this->column_notes"
                    ." FROM $this->database.$this->table AS $this->table "
                    ." INNER JOIN $this->database.$table_category AS $table_category"
                    ." ON ($this->database.$this->table.$this->column_category_serial = $this->database.$table_category.$this->column_category_serial)"
                    ." WHERE $this->table.$this->column_online = $online"
                    ." ORDER BY $sort $order_type LIMIT $skip,$limit";
            
            Log::i($sql);
            
            $query = $this->pdo->query($sql);
            if($query){
                $i=0;
                foreach ($query as $row){
                    $array[$i]["$this->column_serial"] = $row["$this->column_serial"];
                    $array[$i]["$this->column_application"] = $row["$this->column_application"];
                    $array[$i]["$this->column_puup"] = $row["$this->column_puup"];
                    $array[$i]["$this->column_category_serial"] = $row["$this->column_category_serial"];
                    $array[$i]["$column_category"] = $row["$column_category"];
                    $array[$i]["$this->column_icon"] = $row["$this->column_icon"];
                    $array[$i]["$this->column_package"] = $row["$this->column_package"];
                    $array[$i]["$this->column_producer"] = $row["$this->column_producer"];
                    $array[$i]["$this->column_description"] = $row["$this->column_introduce"];//change from description to introduce but keep description for api common
                    $array[$i]["$this->column_notes"] = $row["$this->column_notes"];
                    $array[$i]["$this->column_register_date"] = $row["$this->column_register_date"];
                    require_once 'class.promotionmanager.php';
                    $proMgr = new PromotionManager();
                    if($proMgr->isPromotionOn($row["$this->column_serial"]+0)){
                    	$array[$i]["$column_promotion"] = 1;
                    }
                    else{
                    	$array[$i]["$column_promotion"] = 0;
                    }
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

    public function fetchApplicationsByGroup($group_serial, $sort, $cur_page, $limit, $order = 0, $online = 1)
    {
    	Log::i('fetchApplications');
    	if($this->pdo != null){
    		$skip = ($cur_page - 1) * $limit;
    		if($order == 0){
    			$order_type = "ASC";
    		}
    		else{
    			$order_type = "DESC";
    		}
    
    		require_once 'class.categorymanager.php';
    		$mgr = new CategoryManager();
    		$categories = $mgr->getCategoriesByGroup($group_serial);

			$table_category = DatabaseProxy::DB_TABLE_CATEGORIES;
    		$column_category = DatabaseProxy::DB_COLUMN_CATEGORY;
    		$column_download = "download_count";
    		
    		require_once 'class.downloadmanager.php';
    		$downloadMgr = new DownloadManager();
    		 
    		$i=0;
    		$s=0;
    
    		foreach ($categories as $category){
    			$category_serial = $category["$this->column_category_serial"]+0;
    			$sql = "SELECT "
    				." $this->table.$this->column_serial AS $this->column_serial"
    				.", $this->table.$this->column_application AS $this->column_application"
    				.", $this->table.$this->column_puup AS $this->column_puup"
    				.", $this->table.$this->column_promotion AS $this->column_promotion"
    				.", $this->table.$this->column_category_serial AS $this->column_category_serial"
    				.", $table_category.$column_category AS $column_category"
    				.", $this->table.$this->column_icon AS $this->column_icon"
    				.", $this->table.$this->column_package AS $this->column_package"
    				.", $this->table.$this->column_producer AS $this->column_producer"
    				.", $this->table.$this->column_description AS $this->column_description"
    				.", $this->table.$this->column_register_date AS $this->column_register_date"
    				.", $this->table.$this->column_notes AS $this->column_notes"
    				." FROM $this->database.$this->table AS $this->table "
    				." INNER JOIN $this->database.$table_category AS $table_category"
    				." ON ($this->database.$this->table.$this->column_category_serial = $this->database.$table_category.$this->column_category_serial)"
    				." WHERE $this->table.$this->column_online = $online".
    				" AND $this->table.$this->column_category_serial= $category_serial"
    				;//." ORDER BY $sort $order_type";// LIMIT $skip,$limit";
    
    			
    			$query = $this->pdo->query($sql);
    			if($query){
    				foreach ($query as $row){
    					if($s>=$skip && $s<=$skip+$limit){
	    					$array[$i]["$this->column_serial"] = $row["$this->column_serial"];
	    					$array[$i]["$this->column_application"] = $row["$this->column_application"];
	    					$array[$i]["$this->column_puup"] = $row["$this->column_puup"];
	    					$array[$i]["$this->column_promotion"] = $row["$this->column_promotion"];
	    					$array[$i]["$this->column_category_serial"] = $row["$this->column_category_serial"];
			    			$array[$i]["$column_category"] = $row["$column_category"];
			    			$array[$i]["$this->column_icon"] = $row["$this->column_icon"];
			    			$array[$i]["$this->column_package"] = $row["$this->column_package"];
			    			$array[$i]["$this->column_producer"] = $row["$this->column_producer"];
			    			$array[$i]["$this->column_description"] = $row["$this->column_description"];
			    			$array[$i]["$this->column_notes"] = $row["$this->column_notes"];
			    			$array[$i]["$this->column_register_date"] = $row["$this->column_register_date"];
    					    
			    			$array[$i]["$column_download"] = $downloadMgr->getDownloadCountByAppPackage($row["$this->column_package"]);
		                    
			    			$i++;
			    			$s++;
    					}
    					else{
    						$s++;
    					}
    				}
    			}
    		}
    		
    		
    		
    		return $array;
    	}
    	else{
    		Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
    		print_r($this->pdo->errorInfo(),true),
    		Log::ERR_ERROR);
    
    	}
    	
   		return FALSE;
    }

    public function fetchApplicationsPromotion($sort, $cur_page, $limit, $order = 0, $online = 1)
    {
    	Log::i('fetchApplications');
    	if($this->pdo != null){
    		$skip = ($cur_page - 1) * $limit;
    		if($order == 0){
    			$order_type = "ASC";
    		}
    		else{
    			$order_type = "DESC";
    		}
    
    		$table_category = DatabaseProxy::DB_TABLE_CATEGORIES;
    		$column_category = DatabaseProxy::DB_COLUMN_CATEGORY;
    
    
    		$i=0;
    		$s=0;
    
    			$sql = "SELECT "
    					." $this->table.$this->column_serial AS $this->column_serial"
    					.", $this->table.$this->column_application AS $this->column_application"
    					.", $this->table.$this->column_puup AS $this->column_puup"
    					.", $this->table.$this->column_promotion AS $this->column_promotion"
    					.", $this->table.$this->column_category_serial AS $this->column_category_serial"
    					.", $table_category.$column_category AS $column_category"
    					.", $this->table.$this->column_icon AS $this->column_icon"
    					.", $this->table.$this->column_package AS $this->column_package"
    					.", $this->table.$this->column_producer AS $this->column_producer"
    					.", $this->table.$this->column_description AS $this->column_description"
    					.", $this->table.$this->column_register_date AS $this->column_register_date"
    					.", $this->table.$this->column_notes AS $this->column_notes"
    					." FROM $this->database.$this->table AS $this->table "
    					." INNER JOIN $this->database.$table_category AS $table_category"
    					." ON ($this->database.$this->table.$this->column_category_serial = $this->database.$table_category.$this->column_category_serial)"
    					." WHERE $this->table.$this->column_online = $online".
    					" AND $this->table.$this->column_promotion = 1"
    					." ORDER BY $sort $order_type";// LIMIT $skip,$limit";
    
    					file_put_contents("../json/debug.json",$sql);
    
    			$query = $this->pdo->query($sql);
    			if($query){
        			foreach ($query as $row){
        			if($s>=$skip && $s<=$skip+$limit){
        				$array[$i]["$this->column_serial"] = $row["$this->column_serial"];
        				$array[$i]["$this->column_application"] = $row["$this->column_application"];
        				$array[$i]["$this->column_puup"] = $row["$this->column_puup"];
        				$array[$i]["$this->column_promotion"] = $row["$this->column_promotion"];
        				$array[$i]["$this->column_category_serial"] = $row["$this->column_category_serial"];
        				$array[$i]["$column_category"] = $row["$column_category"];
        				$array[$i]["$this->column_icon"] = $row["$this->column_icon"];
        				$array[$i]["$this->column_package"] = $row["$this->column_package"];
        				$array[$i]["$this->column_producer"] = $row["$this->column_producer"];
        				$array[$i]["$this->column_description"] = $row["$this->column_description"];
        				$array[$i]["$this->column_notes"] = $row["$this->column_notes"];
        				$array[$i]["$this->column_register_date"] = $row["$this->column_register_date"];
        				$i++;
        				$s++;
        			}
        			else{
        				$s++;
        			}
        		}
        }
    
    
    
        return $array;
        }
        else{
        	Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
        					print_r($this->pdo->errorInfo(),true),
    			Log::ERR_ERROR);
    
    	}
     
    	return FALSE;
    }
    
    public function getApplicationsByCategory($category_serial, $online = 1)
    {
        Log::i('getApplicationsByCategory:'.$category_serial);
        if($this->pdo != null){
            
            $sql = "SELECT "
            				." $this->table.$this->column_serial AS $this->column_serial"
            				.", $this->table.$this->column_application AS $this->column_application"
            				.", $this->table.$this->column_category_serial AS $this->column_category_serial"
            				.", $this->table.$this->column_package AS $this->column_package"
                    ." FROM "
                    ." $this->database.$this->table AS $this->table "
                    ." WHERE "
                    ." $this->table.$this->column_category_serial = $category_serial"
                    ." AND $this->table.$this->column_online = $online"
                    ." ORDER BY "
                    ." $this->column_update_time "
                    ." DESC ";
            
            Log::i($sql);
            
            $query = $this->pdo->query($sql);
            if($query){
                $array = FALSE;
                $i=0;
                foreach ($query as $row){
                    $array[$i]["$this->column_serial"] = $row["$this->column_serial"];
                    $array[$i]["$this->column_application"] = $row["$this->column_application"];
                    $array[$i]["$this->column_package"] = $row["$this->column_package"];
                    Log::i($array[$i]["$this->column_application"]);   
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
    
    public function getAppSerial($package, $online = 0)
    {
        if($this->pdo != null){
            $sql = "SELECT "
                    ." $this->column_serial "
                    ." ,$this->column_package "
                    ." FROM "
                    ." $this->database.$this->table" 
                    ." WHERE "
                    ." $this->column_package = '$package'";
            if ($online == 1){
                $sql .= " AND $this->column_online = 1";
            }        
            $sql .= " ORDER BY $this->column_update_time LIMIT 1";
                    
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                if($row["$this->column_package"] == $package){
                    return $row[$this->column_serial];
                }
            }
        }
        return 0;
        
    }
    
    public function addApplication($application, $category_serial, $package, $iconfile,
    		$customer_serial, $brand_serial,$model_serial)
    {
        if ($this->pdo != null){
            $serial = $this->getAppSerial($package, 0);
            if ($serial > 0){
                $update_time = date('Y-m-d H:i:s');
    
                $sql = "UPDATE $this->database.$this->table SET "
                        ." $this->column_application = '$application'"
                        .", $this->column_category_serial = $category_serial"
                        .", $this->column_icon = '$iconfile'";
                if ($customer_serial==0 && $brand_serial == 0 && $model_serial==0) {
                	$sql .= ", $this->column_online = 1 ";
                }
                else{
                	$sql .= ", $this->column_customer_online = 1";
                }
                			
                       

               $sql .=   ", $this->column_update_time = '$update_time'"
                        ." $this->column_puup = 1"
    					.", $this->column_puup_update_time = '$update_time'"
                        ."  WHERE $this->column_serial = $serial ";
                        
                $query = $this->pdo->query($sql);
                if($query){
                    return $serial;
                }
                else{
                    Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                        print_r($this->pdo->errorInfo(),true),
                        Log::ERR_ERROR);
                }
                
            }
            else{
                $register_date = date('Y-m-d');
                $update_time = date('Y-m-d H:i:s');
                if ($customer_serial==0 && $brand_serial == 0 && $model_serial==0) {
           	        $online = 1;
	    			$customer_online = 0;
                }
                else{
                	$online = 0;
                	$customer_online = 1;
                	 
                }
                $sql = "INSERT INTO $this->database.$this->table ( "
                        ." $this->column_application"
                        .", $this->column_category_serial"
                        .", $this->column_package"
                        .", $this->column_icon"
                        .", $this->column_online"
                        .", $this->column_customer_online"
                        .", $this->column_register_date"
                        .", $this->column_update_time )"
                        ." VALUES ( "
                        ."'$application'"
                        .", $category_serial"
                        .", '$package' "
                        .", '$iconfile' "
                        .", $online"
                        .", $customer_online"
                        .", '$register_date'"
                        .",'$update_time')";
    
                $query = $this->pdo->query($sql);
                if($query){
                    return $this->pdo->lastInsertId();
                }
                else{
                    Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                        print_r($this->pdo->errorInfo(),true),
                        Log::ERR_ERROR);
                }
                
            }
                
        }
        return 0;    
    }
    
    /*
    public function updateApplication($serial,$application, $category_serial, $package, $icon)
    {
        if ($this->pdo != null){
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "UPDATE $this->database.$this->table SET "
                        ."$this->column_application = '$application'"
                        .", $this->column_category_serial = $category_serial"
                        .", $this->column_package = '$package'"
                        .", $this->column_icon = '$icon'"
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
    */
    public function updateApplication($serial,$application, $puup_point, $category_serial,$producer, $description)
    {
        if ($this->pdo != null){
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "UPDATE $this->database.$this->table SET "
                        ."$this->column_application = '$application'"
                        .", $this->column_puup = $puup_point"
                        .", $this->column_category_serial = $category_serial"
                        .", $this->column_producer = '$producer'"
                        .", $this->column_introduce = '$description'"
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
    
    public function updateApplicationPuup($serial,$puup_point)
    {
    	if ($this->pdo != null){
    		$update_time = date('Y-m-d H:i:s');
    
    		$sql = "UPDATE $this->database.$this->table SET "
    		." $this->column_puup = $puup_point"
    		.", $this->column_puup_update_time = '$update_time'"
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

    public function updateApplicationPromotion($serial,$promotion)
    {
    	if ($this->pdo != null){
    		$update_time = date('Y-m-d H:i:s');
    
    		$sql = "UPDATE $this->database.$this->table SET "
    		." $this->column_promotion = $promotion"
    		.", $this->column_update_time = '$update_time'"
    		." WHERE $this->column_serial = $serial";
    		file_put_contents("../json/sql.json",$sql);
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
    
    public function updateApplicationIntroduce($serial,$introduce)
    {
    	if ($this->pdo != null){
    		$update_time = date('Y-m-d H:i:s');
    
    		$sql = "UPDATE $this->database.$this->table SET "
    		." $this->column_introduce = '$introduce'"
    		.", $this->column_update_time = '$update_time'"
    		." WHERE $this->column_serial = $serial";
    		file_put_contents("../json/sql.json",$sql);
    		
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
    
    public function updateApplicationStamp($serial)
    {
    	if ($this->pdo != null){
    		$update_time = date('Y-m-d H:i:s');
    
    		$sql = "UPDATE $this->database.$this->table SET "
    		." $this->column_update_time = '$update_time'"
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
    
    public function onoffApplication($serial,$online)
    {
        if ($this->pdo != null){
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "UPDATE $this->database.$this->table SET "
                        ."$this->column_online = $online"
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
         
    public function removeApplication($serial)
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
    
    public function isAppRegistered($package, $customer_serial=0)
    {
    	if($this->pdo != null){
    		$sql = "SELECT "
    				." $this->column_serial "
    				." ,$this->column_package "
    				." FROM "
    				." $this->database.$this->table"
    				." WHERE "
                    ." $this->column_package = '$package'";
    		$sql .= " AND ($this->column_online = 1 OR $this->column_customer_online = 1) ";
    		$sql .= " ORDER BY $this->column_update_time LIMIT 1";
    	
    		$query = $this->pdo->query($sql);
    		if($query){
    			$row = $query->fetch();
    			if($row["$this->column_package"] == $package){
    				return $row[$this->column_serial];
    			}
    		}
    	}
    	return 0;
    	 
    }
    
    public function isIconUploaded($serial)
    {
        if ($this->pdo != null){
            
           $sql = "SELECT "
            ." $this->table.$this->column_serial AS $this->column_serial"
            .", $this->table.$this->column_icon AS $this->column_icon"
            ." FROM $this->database.$this->table AS $this->table "
            ." WHERE $this->table.$this->column_serial = $serial";
            
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                   
                require_once '../api/api_constants.php';
                
                    $iconfile = API_CONSTANTS::PATH_ICON.$row["$this->column_icon"];
                    if (!empty($iconfile)){
                        $icon_array = glob($iconfile);
                        if(count($icon_array)>0){
                            return TRUE;
                        }
                    }
                }
            else{
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                print_r($this->pdo->errorInfo(),true),
                Log::ERR_ERROR);
            
            }
                
        }
        return FALSE;
    }
    
    
    /**
     * getApplications for api_applications_sync
     * @param unknown $sort
     * @param unknown $cur_page
     * @param unknown $limit
     * @param number $order
     * @param number $online
     * @return unknown|boolean
     */
    public function getApplications($customer_serial=0, $update_stamp = "2013-01-01 00:00:00",$brand_serial = 0, $model_serial = 0, $sdk_level = 100)
    {
    	
    	$array = FALSE;

    	$group_promotion = DatabaseProxy::DB_VALUE_GROUP_PROMOTION;
    	
    	require_once dirname(dirname(__FILE__)).'/utilities/class.categorymanager.php';
    	$mgr = new CategoryManager();
    	$column_group_serial = DatabaseProxy::DB_COLUMN_CATEGORY_GROUP_SERIAL;
    	$array_category = $mgr->getCategoryArrayByCustomer($customer_serial);
    	if ($customer_serial>0) {
    		$isCustomizedCategory = $mgr->isCustomizedCategory($customer_serial);
    	}
    	else{
    		$isCustomizedCategory = false;
    	}
    	
    	require_once dirname(dirname(__FILE__)).'/utilities/class.apkfilemanager.php';
    	$apkMgr = new ApkfileManager();
    	$column_apkfile = DatabaseProxy::DB_COLUMN_APKFILE;
    	$column_vercode = DatabaseProxy::DB_COLUMN_APKFILE_VERSION_CODE;
    	$column_vername = DatabaseProxy::DB_COLUMN_APKFILE_VERSION_NAME;
    	 
    	if($this->pdo != null){
    		
    		$table_category = DatabaseProxy::DB_TABLE_CATEGORIES;
    		$column_category = DatabaseProxy::DB_COLUMN_CATEGORY;
    		$sql = "SELECT "
    				." $this->table.$this->column_serial AS $this->column_serial"
    				.", $this->table.$this->column_application AS $this->column_application"
    				.", $this->table.$this->column_puup AS $this->column_puup"
    				.", $this->table.$this->column_promotion AS $this->column_promotion"
    				.", $this->table.$this->column_category_serial AS $this->column_category_serial"
    				.", $this->table.$this->column_package AS $this->column_package"
    				.", $this->table.$this->column_online AS $this->column_online"
    				." FROM $this->database.$this->table AS $this->table "
    				//." WHERE $this->table.$this->column_online = $online"
    				." WHERE $this->table.$this->column_update_time > '$update_stamp'";
    	
    		if($array_category){
				for($i=0;$i<count($array_category);$i++){
					if ($i==0){
						$sql .= " AND ( $this->column_category_serial = $array_category[$i]";
					}
					else{
						$sql .= " OR $this->column_category_serial = $array_category[$i]";
					}
				}
				$sql .= ") ";
			}
			else{
				;
			}
    		    		
    		$sql .=	" ORDER BY $this->column_serial DESC";
    		
 			$query = $this->pdo->query($sql);
    		if($query){
    			$i=0;
    			foreach ($query as $row){
    				
    				$array[$i]["$this->column_serial"] = 0 + $row["$this->column_serial"];
    				$array[$i]["$this->column_application"] = $row["$this->column_application"];
    				$array[$i]["$this->column_puup"] = $row["$this->column_puup"];
    				$array[$i]["$this->column_category_serial"] = 0 + $row["$this->column_category_serial"];
    				$array[$i]["$this->column_package"] = $row["$this->column_package"];
    				$array[$i]["$this->column_online"] = 0 + $row["$this->column_online"];
    				
    				if ($row["$this->column_promotion"] && !$isCustomizedCategory ) {
    					$array[$i]["$column_group_serial"] = $group_promotion;
    				}
    				else{
    					$array[$i]["$column_group_serial"] 
    						= 0 + $mgr->getCategoryGroup($row["$this->column_category_serial"]);
    				}
    				
    				$online = false;
    				$apk = false;
    				//$apk = $apkMgr->getApkFileByApplication($row["$this->column_serial"]);
    				
    				$apk = $apkMgr->getApkFileByDevice($this->pdo, $row["$this->column_serial"],$sdk_level,$customer_serial,$brand_serial,$model_serial,1);
    				
    				$column_customer_online = DatabaseProxy::DB_COLUMN_APPLICATION_CUSTOMER_ONLINE;
    					
    				
    				if ($apk) {
    						
    					if ($apk["$column_customer_online"]==1) {
    						$online = true;
    					}
    					
    					else{
    						require_once '../utilities/class.brandmanager.php';
    						$brandMgr = new BrandManager();
    						$filtertype = $brandMgr->getFiltertype($brand_serial);
    						
    						switch ($filtertype){
    							case 0:// no filter
    								if ($row["$this->column_online"] == 1) {
    									$online = true;
    								}
    								break;
    								
    							case 1:// filter all
    								$online = false;
    								
    								break;
    							case 2:// filter list
									require_once '../utilities/class.blacklistmanager.php';
									$blacklistMgr = new BlacklistManager();
									if ($blacklistMgr->isBlacklist($brand_serial, $row["$this->column_serial"])) {
										$online = false;
									}
									else{
										if ($row["$this->column_online"] == 1) {
											$online = true;
										}
										
									}
    								break;
    						}
    						
    						
    					}
    					
    				}
    				
    				
    				
    				if ($online) {
    					$array[$i]["$column_apkfile"] = $apk["$column_apkfile"];
	    				$array[$i]["$column_vercode"] = 0 + $apk["$column_vercode"];
	    				$array[$i]["$column_vername"] = $apk["$column_vername"];
	    				$array[$i]["$this->column_online"] = 1;
	    				$array[$i]["$column_customer_online"] = $apk["$column_customer_online"];
    				}
    				else{
    					$array[$i]["$column_apkfile"] = "";
    					$array[$i]["$column_vercode"] = 0;
    					$array[$i]["$column_vername"] = "";
    					$array[$i]["$this->column_online"] = 0;
	    				$array[$i]["$column_customer_online"] = -1;
    				}
    				
    				
    				$i++;
    			}
    		}
    		else{
    			Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
    			print_r($this->pdo->errorInfo(),true),
    			Log::ERR_ERROR);
    
    		}
    	}
    	return $array;
    
    }

    
    public function getApplicationsByCustomer($customer_serial = 0)
    {
    	 
    	$array = FALSE;
    	 
    	require_once dirname(dirname(__FILE__)).'/utilities/class.categorymanager.php';
    	$mgr = new CategoryManager();
    	$column_group_serial = DatabaseProxy::DB_COLUMN_CATEGORY_GROUP_SERIAL;
    	 
  
    	if($this->pdo != null){
    
    		$table_category = DatabaseProxy::DB_TABLE_CATEGORIES;
    		$column_category = DatabaseProxy::DB_COLUMN_CATEGORY;
    		$sql = "SELECT "
    				." $this->table.$this->column_serial AS $this->column_serial"
    				.", $this->table.$this->column_application AS $this->column_application"
    				.", $this->table.$this->column_package AS $this->column_package"
    				.", $this->table.$this->column_category_serial AS $this->column_category_serial"
    				." FROM $this->database.$this->table AS $this->table "
    				." WHERE $this->table.$this->column_online = 1";
    				
    
    		$query = $this->pdo->query($sql);
    		if($query){
    			$i=0;
    			foreach ($query as $row){
    				$array[$i]["$this->column_serial"] = 0 + $row["$this->column_serial"];
    				$array[$i]["$this->column_application"] = $row["$this->column_application"];
    				$array[$i]["$this->column_package"] = $row["$this->column_package"];
    				$array[$i]["$this->column_category_serial"] = 0 + $row["$this->column_category_serial"];
    				$array[$i]["$column_group_serial"]
    				= 0 + $mgr->getCategoryGroup($row["$this->column_category_serial"]);
    
    				$i++;
    			}
    		
    		}
    		else{
    				Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
    				print_r($this->pdo->errorInfo(),true),
    				Log::ERR_ERROR);
    
    		}
    	}
    	return $array;
    
    }
    
    public function getApplicationsByCategoryArray($category_array)
    {
    	 
    	$array = FALSE;
    	 
    	if($this->pdo != null){
    
    		$sql = "SELECT "
    				." $this->table.$this->column_serial AS $this->column_serial"
    				.", $this->table.$this->column_application AS $this->column_application"
    				.", $this->table.$this->column_category_serial AS $this->column_category_serial"
    				." FROM $this->database.$this->table AS $this->table "
    				." WHERE $this->table.$this->column_online = 1";
    		
    		if ($category_array) {
    			for($i=0;$i<count($category_array);$i++){
    				if ($i==0){
    					$sql .= " AND ( $this->column_category_serial = $category_array[$i]";
    				}
    				else{
    					$sql .= " OR $this->column_category_serial = $category_array[$i]";
    				}
    			}
    			$sql .= ") ";
       		}
    		
    		echo $sql;	
    		$query = $this->pdo->query($sql);
    		if($query){
    			$rows = $query->fetchAll();
    			$i=0;
    			foreach ($rows as $row){
    				$array[$i]["$this->column_serial"] = 0 + $row["$this->column_serial"];
    				$array[$i]["$this->column_application"] = $row["$this->column_application"];
    				$array[$i]["$this->column_category_serial"] = 0 + $row["$this->column_category_serial"];
    			
    				$i++;
    			}
    				 
    			
    		}
    
    				 
    	}
    	else{
    				Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
    				print_r($this->pdo->errorInfo(),true),
    				Log::ERR_ERROR);
    
    	}
    				return $array;
    
    }
    
    
    public function getApplicationsPuup($stamp = "2013-07-01 00:00:00")
    {
    	 
    	$array = FALSE;
    	 
    	require_once dirname(dirname(__FILE__)).'/utilities/class.categorymanager.php';
    	$mgr = new CategoryManager();
    	$column_group_serial = DatabaseProxy::DB_COLUMN_CATEGORY_GROUP_SERIAL;
    	 
    	require_once dirname(dirname(__FILE__)).'/utilities/class.apkfilemanager.php';
    	$apkMgr = new ApkfileManager();
    	$column_apkfile = DatabaseProxy::DB_COLUMN_APKFILE;
    	$column_vercode = DatabaseProxy::DB_COLUMN_APKFILE_VERSION_CODE;
    	$column_vername = DatabaseProxy::DB_COLUMN_APKFILE_VERSION_NAME;
    
    	if($this->pdo != null){
    
    		$table_category = DatabaseProxy::DB_TABLE_CATEGORIES;
    		$column_category = DatabaseProxy::DB_COLUMN_CATEGORY;
    		$sql = "SELECT "
    				." $this->table.$this->column_serial AS $this->column_serial"
    				.", $this->table.$this->column_puup AS $this->column_puup"
    				." FROM $this->database.$this->table AS $this->table "
    				." WHERE $this->table.$this->column_online = 1".
    				" AND $this->table.$this->column_puup_update_time > '$stamp'"
    				." ORDER BY $this->column_serial";// LIMIT $skip,$limit";
    
    				Log::i($sql);
    
    		$query = $this->pdo->query($sql);
    		if($query){
	    		$i=0;
    			foreach ($query as $row){
		    		$array[$i]["$this->column_serial"] = 0 + $row["$this->column_serial"];
		    		$array[$i]["$this->column_puup"] = 0 + $row["$this->column_puup"];
		    		
		    		$i++;
    			}
    
    		}
    	}
    	else{
    		Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
    		print_r($this->pdo->errorInfo(),true),
    		Log::ERR_ERROR);
    
    	}
   		return $array;
    
    }
    
    /**
     * getApplications for api_applications_sync
     * @param unknown $sort
     * @param unknown $cur_page
     * @param unknown $limit
     * @param number $order
     * @param number $online
     * @return unknown|boolean
     */
    public function getApplicationByPackage($package)
    {
    	require_once dirname(dirname(__FILE__)).'/utilities/class.categorymanager.php';
    	$mgr = new CategoryManager();
    	$column_group_serial = DatabaseProxy::DB_COLUMN_CATEGORY_GROUP_SERIAL;
    	 
    	require_once dirname(dirname(__FILE__)).'/utilities/class.apkfilemanager.php';
    	$apkMgr = new ApkfileManager();
    	$column_apkfile = DatabaseProxy::DB_COLUMN_APKFILE;
    	$column_vercode = DatabaseProxy::DB_COLUMN_APKFILE_VERSION_CODE;
    	$column_vername = DatabaseProxy::DB_COLUMN_APKFILE_VERSION_NAME;
    
    	if($this->pdo != null){
    
    		$table_category = DatabaseProxy::DB_TABLE_CATEGORIES;
    		$column_category = DatabaseProxy::DB_COLUMN_CATEGORY;
    		$sql = "SELECT "
    				." $this->table.$this->column_serial AS $this->column_serial"
    				.", $this->table.$this->column_application AS $this->column_application"
    				.", $this->table.$this->column_category_serial AS $this->column_category_serial"
    				.", $this->table.$this->column_package AS $this->column_package"
    				.", $this->table.$this->column_online AS $this->column_online"
    				." FROM $this->database.$this->table AS $this->table "
    				." WHERE $this->table.$this->column_package = '$package'"
    				." ORDER BY $this->column_serial";// LIMIT $skip,$limit";
    
    				Log::i($sql);
    
    		$query = $this->pdo->query($sql);
    		if($query){
    		$i=0;
    		foreach ($query as $row){
    		$array[$i]["$this->column_serial"] = 0 + $row["$this->column_serial"];
    		$array[$i]["$this->column_application"] = $row["$this->column_application"];
    		$array[$i]["$this->column_category_serial"] = 0 + $row["$this->column_category_serial"];
    		$array[$i]["$this->column_package"] = $row["$this->column_package"];
    		$array[$i]["$this->column_online"] = 0 + $row["$this->column_online"];
    		$array[$i]["$column_group_serial"]
    		= 0 + $mgr->getCategoryGroup($row["$this->column_category_serial"]);
    
    		$apk = $apkMgr->getApkFileByApplication($row["$this->column_serial"]);
    		$array[$i]["$column_apkfile"] = $apk["$column_apkfile"];
    		$array[$i]["$column_vercode"] = 0 + $apk["$column_vercode"];
    		$array[$i]["$column_vername"] = $apk["$column_vername"];
    
    
    
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
    		     
    		public function getApplicationNameByPackage($package)
    		{
    			$array = FALSE;
    			if($this->pdo != null){
    		
    				$table_category = DatabaseProxy::DB_TABLE_CATEGORIES;
    				$column_category = DatabaseProxy::DB_COLUMN_CATEGORY;
    				$sql = "SELECT "
    						." $this->table.$this->column_application AS $this->column_application"
    						." FROM $this->database.$this->table AS $this->table "
    						." WHERE $this->table.$this->column_package = '$package'";
    						
    				$query = $this->pdo->query($sql);
    				if($query){
    					$row =$query->fetch();
    					
    					$array = $row["$this->column_application"];
    				}
    			
    				else{
	    				Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
	    				print_r($this->pdo->errorInfo(),true),
	    				Log::ERR_ERROR);
	    		
    				}
    			}
    			return $array;
    		
    		}
    					
    		
    	public function getApplicationByPackageVercodeMax($package,$vercodemax)
    		{
    			require_once dirname(dirname(__FILE__)).'/utilities/class.categorymanager.php';
    			$mgr = new CategoryManager();
    			$column_group_serial = DatabaseProxy::DB_COLUMN_CATEGORY_GROUP_SERIAL;
    		
    			require_once dirname(dirname(__FILE__)).'/utilities/class.apkfilemanager.php';
    			$apkMgr = new ApkfileManager();
    			$column_apkfile = DatabaseProxy::DB_COLUMN_APKFILE;
    			$column_vercode = DatabaseProxy::DB_COLUMN_APKFILE_VERSION_CODE;
    			$column_vername = DatabaseProxy::DB_COLUMN_APKFILE_VERSION_NAME;
    		
    			if($this->pdo != null){
    		
    				$table_category = DatabaseProxy::DB_TABLE_CATEGORIES;
    				$column_category = DatabaseProxy::DB_COLUMN_CATEGORY;
    				$sql = "SELECT "
    						." $this->table.$this->column_serial AS $this->column_serial"
    						.", $this->table.$this->column_application AS $this->column_application"
    						.", $this->table.$this->column_category_serial AS $this->column_category_serial"
    						.", $this->table.$this->column_package AS $this->column_package"
    						.", $this->table.$this->column_online AS $this->column_online"
    						." FROM $this->database.$this->table AS $this->table "
    						." WHERE $this->table.$this->column_package = '$package'"
    						." ORDER BY $this->column_serial";// LIMIT $skip,$limit";
    		
    						Log::i($sql);
    		
    				$query = $this->pdo->query($sql);
    				if($query){
    				$i=0;
    				foreach ($query as $row){
    				$array[$i]["$this->column_serial"] = 0 + $row["$this->column_serial"];
    				$array[$i]["$this->column_application"] = $row["$this->column_application"];
    				$array[$i]["$this->column_category_serial"] = 0 + $row["$this->column_category_serial"];
    				$array[$i]["$this->column_package"] = $row["$this->column_package"];
    				$array[$i]["$this->column_online"] = 0 + $row["$this->column_online"];
    				$array[$i]["$column_group_serial"]
    				= 0 + $mgr->getCategoryGroup($row["$this->column_category_serial"]);
    		
    				$apk = $apkMgr->getApkFileByApplicationVercodeMax($row["$this->column_serial"],$vercodemax);
    				$array[$i]["$column_apkfile"] = $apk["$column_apkfile"];
    				$array[$i]["$column_vercode"] = 0 + $apk["$column_vercode"];
    				$array[$i]["$column_vername"] = $apk["$column_vername"];
    		
    		
    		
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
    		
    
    public function getApplicationsLatestStamp()
    {
    	$stamp = FALSE;
    	if($this->pdo != null){
    
    		$sql = "SELECT $this->column_update_time".
    			" FROM $this->database.$this->table".
    			" ORDER BY $this->column_update_time DESC".
    			" LIMIT 1";
    
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
    
    public function getApplicationsLatestUpdateStamp()
    {
    	$stamp = "2013-01-01 00:00:00";
    	if($this->pdo != null){
    
    		$sql = "SELECT $this->column_update_time".
    				" FROM $this->database.$this->table".
    				" ORDER BY $this->column_update_time DESC".
    				" LIMIT 1";
    
    		$query = $this->pdo->query($sql);
    		if($query){
    
    			$result = $query->fetch();
    			$stamp = $result["$this->column_update_time"];
    
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

    public function getApplicationsLatestPuupStamp()
    {
    	$stamp = "2013-07-01 00:00:00";
    	if($this->pdo != null){
    
    		$sql = "SELECT $this->column_puup_update_time".
    				" FROM $this->database.$this->table".
    				" ORDER BY $this->column_puup_update_time DESC".
    				" LIMIT 1";
    
    		$query = $this->pdo->query($sql);
    		if($query){
    
    			$result = $query->fetch();
    			$stamp = $result["$this->column_puup_update_time"];
    
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
    
    public function getApplicationIntroduce($serial)
    {
    	
    	if($this->pdo != null){
    
    		$sql = "SELECT $this->column_introduce".
    				" FROM $this->database.$this->table".
    				" WHERE $this->column_serial = $serial ";
    
    		$query = $this->pdo->query($sql);
    		if($query){

       			$result = $query->fetch();
    			$introduce = $result["$this->column_introduce"];
    
    		}
    		else{
    			$log_msg = print_r($this->pdo->errorInfo(),true);
    			Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
    			$log_msg,
    			Log::ERR_ERROR);
    		}
    	}
    	return $introduce;
    }

    public function IntroduceTransform()
    {
    	$transform = 0;
    	if($this->pdo != null){
    
    		$sql = "SELECT $this->column_serial, $this->column_description".
    				" FROM $this->database.$this->table";
    
    		$query = $this->pdo->query($sql);
    		if($query){
    
    			$result = $query->fetchAll();
    			foreach($result as $app){
    				$desc = $app["$this->column_description"];
    				$serial = $app["$this->column_serial"];
    				$sql = "UPDATE $this->database.$this->table SET $this->column_introduce = '$desc' WHERE $this->column_serial = $serial";	
    				$query = $this->pdo->query($sql);
    				$transform++;
    			}
    			
    		}
    		else{
    			$log_msg = print_r($this->pdo->errorInfo(),true);
    			Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
    			$log_msg,
    			Log::ERR_ERROR);
    		}
    	}
    	return $transform;
    }
    
}
