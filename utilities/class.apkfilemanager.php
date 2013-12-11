<?php

/**
 * ApkfileManager - ApkStore application category management class
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
require_once dirname(dirname(__FILE__)).'/utilities/class.applicationmanager.php'; 

class ApkfileManager
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
    
    private $table                  = DatabaseProxy::DB_TABLE_APKFFILES;
    
    private $column_serial          = DatabaseProxy::DB_COLUMN_APKFILE_SERIAL;
    private $column_apkfile         = DatabaseProxy::DB_COLUMN_APKFILE;
    private $column_ver_code        = DatabaseProxy::DB_COLUMN_APKFILE_VERSION_CODE;
    private $column_ver_name        = DatabaseProxy::DB_COLUMN_APKFILE_VERSION_NAME;
    private $column_sdk_min         = DatabaseProxy::DB_COLUMN_APKFILE_SDK_MIN;
    private $column_file_original   = DatabaseProxy::DB_COLUMN_APKFILE_FILE_ORIGINAL;
    
    
    private $column_notes           = DatabaseProxy::DB_COLUMN_APKFILE_NOTES;
    private $column_online          = DatabaseProxy::DB_COLUMN_APKFILE_ONLINE;
    private $column_register_date   = DatabaseProxy::DB_COLUMN_APKFILE_REGISTER_DATE;
    private $column_update_time     = DatabaseProxy::DB_COLUMN_APKFILE_UPDATE_TIME;
    
    private $column_sha1		    = DatabaseProxy::DB_COLUMN_APKFILE_SHA1;
    private $column_customer_serial = DatabaseProxy::DB_COLUMN_APKFILE_CUSTOMER_SERIAL;
    private $column_brand_serial	= DatabaseProxy::DB_COLUMN_APKFILE_BRAND_SERIAL;
    private $column_model_serial	= DatabaseProxy::DB_COLUMN_APKFILE_MODEL_SERIAL;
    
    private $table_app              = DatabaseProxy::DB_TABLE_APPLICATIONS;
    private $column_application     = DatabaseProxy::DB_COLUMN_APPLICATION;
    private $column_package         = DatabaseProxy::DB_COLUMN_APPLICATION_PACKAGE;
    private $column_icon            = DatabaseProxy::DB_COLUMN_APPLICATION_IOCN;
    private $column_app_serial      = DatabaseProxy::DB_COLUMN_APPLICATION_SERIAL;
    
    private $table_category         = DatabaseProxy::DB_TABLE_CATEGORIES;
    private $column_category        = DatabaseProxy::DB_COLUMN_CATEGORY;
    private $column_category_serial = DatabaseProxy::DB_COLUMN_CATEGORY_SERIAL;
    
    private $table_supplier         = DatabaseProxy::DB_TABLE_SUPPLIERS;
    private $column_supplier_serial = DatabaseProxy::DB_COLUMN_SUPPLIER_SERIAL;
    private $column_supplier        = DatabaseProxy::DB_COLUMN_SUPPLIER;
    
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
    
    public function getTotal($online =1 )
    {
        Log::i('getTotal');
        if($this->pdo != null){
            $sql = "SELECT $this->column_serial"
                    ." FROM $this->database.$this->table"
                    ." WHERE $this->column_online = $online"
                    ." AND $this->table.$this->column_apkfile <> 'NA'"
                    ;
            $query = $this->pdo->query($sql);
            if($query){
                $array = $query->fetchAll();
                return count($array);
            }
        }
        return 0;
    }
    
    public function getAllFiles()
    {
    	$array = false;
    	if($this->pdo != null){
    		$sql = "SELECT $this->column_serial".
      		", $this->column_apkfile".
    		" FROM $this->database.$this->table"
    		." WHERE $this->table.$this->column_apkfile <> 'NA'"
    		;
    		
    		$query = $this->pdo->query($sql);
    		if($query){
    			$array = $query->fetchAll();
    		}
    	}
    	return $array;
    }
    
    
    public function fetchApkFiles($sort, $cur_page, $limit, $order = 0, $online = 1)
    {
        Log::i('fetchApkFiles');
        if($this->pdo != null){
            $skip = ($cur_page - 1) * $limit;
            if($order == 0){
                $order_type = "ASC";
            }
            else{
                $order_type = "DESC";
            }
            
            $sql = "SELECT "
            				." $this->table.$this->column_serial AS $this->column_serial"
            				.", $this->table.$this->column_apkfile AS $this->column_apkfile"
            				.", $this->table.$this->column_ver_code AS $this->column_ver_code"
            				.", $this->table.$this->column_ver_name AS $this->column_ver_name"
            				.", $this->table.$this->column_sdk_min AS $this->column_sdk_min"
            				.", $this->table_app.$this->column_app_serial AS $this->column_app_serial"
            				.", $this->table_app.$this->column_application AS $this->column_application"
            				.", $this->table_app.$this->column_icon AS $this->column_icon"
            				.", $this->table_category.$this->column_category AS $this->column_category"
                    .", $this->table_category.$this->column_category_serial AS $this->column_category_serial"
                    .", $this->table_supplier.$this->column_supplier AS $this->column_supplier"
                    .", $this->table_supplier.$this->column_supplier_serial AS $this->column_supplier_serial"
                    .", $this->table.$this->column_notes AS $this->column_notes"
                    .", $this->table.$this->column_register_date AS $this->column_register_date"
                    .", $this->table.$this->column_update_time AS $this->column_update_time"
                    ." FROM $this->database.$this->table "
                    ." INNER JOIN $this->database.$this->table_app AS $this->table_app"
                    ." ON ($this->table.$this->column_app_serial = $this->table_app.$this->column_app_serial)"
                    ." INNER JOIN $this->database.$this->table_category AS $this->table_category"
                    ." ON ($this->table_app.$this->column_category_serial = $this->table_category.$this->column_category_serial)"
                    ." INNER JOIN $this->database.$this->table_supplier AS $this->table_supplier"
                    ." ON ($this->table_supplier.$this->column_supplier_serial = $this->table.$this->column_supplier_serial)"
                    ." WHERE $this->table.$this->column_online = $online"
                    ." AND $this->table.$this->column_apkfile <> 'NA'"
                    ." ORDER BY $sort $order_type LIMIT $skip,$limit";
            
            Log::i($sql);
            
            $query = $this->pdo->query($sql);
            if($query){
                $i=0;
                foreach ($query as $row){
                    $array[$i]["$this->column_serial"] = $row["$this->column_serial"];
                    $array[$i]["$this->column_apkfile"] = $row["$this->column_apkfile"];
                    $array[$i]["$this->column_ver_code"] = $row["$this->column_ver_code"];
                    $array[$i]["$this->column_ver_name"] = $row["$this->column_ver_name"];
                    $array[$i]["$this->column_sdk_min"] = $row["$this->column_sdk_min"];
                    $array[$i]["$this->column_category"] = $row["$this->column_category"];
                    $array[$i]["$this->column_category_serial"] = $row["$this->column_category_serial"];
                    $array[$i]["$this->column_app_serial"] = $row["$this->column_app_serial"];
                    $array[$i]["$this->column_application"] = $row["$this->column_application"];
                    $array[$i]["$this->column_supplier"] = $row["$this->column_supplier"];
                    $array[$i]["$this->column_supplier_serial"] = $row["$this->column_supplier_serial"];
                    $array[$i]["$this->column_icon"] = $row["$this->column_icon"];
                    $array[$i]["$this->column_notes"] = $row["$this->column_notes"];
                    $array[$i]["$this->column_register_date"] = $row["$this->column_register_date"];
                    $array[$i]["$this->column_update_time"] = $row["$this->column_update_time"];
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


    public function fetchApkFilesByPackageArray($packageArray)
    {
        Log::i('fetchApkFilesByPackageArray');
        if($this->pdo != null){
            require_once 'class.applicationmanager.php';
            $appMgr = new ApplicationManager();
            $i = 0;
            $array = FALSE;
            foreach ($packageArray AS $package){
                
                $appSerial = $appMgr->getAppSerial($package, 1);
                Log::i('appSerial='+$appSerial);
                
                $sql = "SELECT "
                ." $this->table.$this->column_serial AS $this->column_serial"
                .", $this->table.$this->column_apkfile AS $this->column_apkfile"
                .", $this->table.$this->column_ver_code AS $this->column_ver_code"
                .", $this->table.$this->column_ver_name AS $this->column_ver_name"
                .", $this->table.$this->column_sdk_min AS $this->column_sdk_min"
                .", $this->table_app.$this->column_app_serial AS $this->column_app_serial"
                .", $this->table_app.$this->column_application AS $this->column_application"
                .", $this->table_app.$this->column_icon AS $this->column_icon"
                .", $this->table_category.$this->column_category AS $this->column_category"
                .", $this->table_category.$this->column_category_serial AS $this->column_category_serial"
                .", $this->table.$this->column_notes AS $this->column_notes"
                .", $this->table.$this->column_register_date AS $this->column_register_date"
                .", $this->table.$this->column_update_time AS $this->column_update_time"
                ." FROM $this->database.$this->table "
                ." INNER JOIN $this->database.$this->table_app AS $this->table_app"
                ." ON ($this->table.$this->column_app_serial = $this->table_app.$this->column_app_serial)"
                ." INNER JOIN $this->database.$this->table_category AS $this->table_category"
                ." ON ($this->table_app.$this->column_category_serial = $this->table_category.$this->column_category_serial)"
                ." WHERE $this->table.$this->column_online = 1"
                ." AND $this->table.$this->column_apkfile <> 'NA'"
                ." AND $this->table_app.$this->column_app_serial = $appSerial"
                ." ORDER BY $this->table.$this->column_update_time DESC LIMIT 1";

                $query = $this->pdo->query($sql);
                if($query){
                    $row = $query->fetch();
                    $array[$i]["$this->column_serial"] = $row["$this->column_serial"];
                    $array[$i]["$this->column_apkfile"] = $row["$this->column_apkfile"];
                    $array[$i]["$this->column_ver_code"] = $row["$this->column_ver_code"];
                    $array[$i]["$this->column_ver_name"] = $row["$this->column_ver_name"];
                    $array[$i]["$this->column_sdk_min"] = $row["$this->column_sdk_min"];
                    $array[$i]["$this->column_category"] = $row["$this->column_category"];
                    $array[$i]["$this->column_category_serial"] = $row["$this->column_category_serial"];
                    $array[$i]["$this->column_app_serial"] = $row["$this->column_app_serial"];
                    $array[$i]["$this->column_application"] = $row["$this->column_application"];
                    $array[$i]["$this->column_icon"] = $row["$this->column_icon"];
                    $array[$i]["$this->column_notes"] = $row["$this->column_notes"];
                    $array[$i]["$this->column_register_date"] = $row["$this->column_register_date"];
                    $array[$i]["$this->column_update_time"] = $row["$this->column_update_time"];
                    $i++;
                 }
                 else{
                     Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                     print_r($this->pdo->errorInfo(),true),
                     Log::ERR_ERROR);
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
    
    public function fetchApkFilesByPackageVercodeMaxArray($packageArray, $vercodeMaxArray)
    {
    	Log::i('fetchApkFilesByPackageArray');
    	if($this->pdo != null){
    		require_once 'class.applicationmanager.php';
    		$appMgr = new ApplicationManager();
    		$i = 0;
    		$array = FALSE;
    		foreach ($packageArray AS $package){
    
    			$appSerial = $appMgr->getAppSerial($package, 1);
    			Log::i('appSerial='+$appSerial);
    
    			$sql = "SELECT "
    					." $this->table.$this->column_serial AS $this->column_serial"
    					.", $this->table.$this->column_apkfile AS $this->column_apkfile"
    					.", $this->table.$this->column_ver_code AS $this->column_ver_code"
    					.", $this->table.$this->column_ver_name AS $this->column_ver_name"
    					.", $this->table.$this->column_sdk_min AS $this->column_sdk_min"
    					.", $this->table_app.$this->column_app_serial AS $this->column_app_serial"
    					.", $this->table_app.$this->column_application AS $this->column_application"
    					.", $this->table_app.$this->column_icon AS $this->column_icon"
    					.", $this->table_category.$this->column_category AS $this->column_category"
    					.", $this->table_category.$this->column_category_serial AS $this->column_category_serial"
    					.", $this->table.$this->column_notes AS $this->column_notes"
    					.", $this->table.$this->column_register_date AS $this->column_register_date"
    					.", $this->table.$this->column_update_time AS $this->column_update_time"
    					." FROM $this->database.$this->table "
    					." INNER JOIN $this->database.$this->table_app AS $this->table_app"
    					." ON ($this->table.$this->column_app_serial = $this->table_app.$this->column_app_serial)"
    					." INNER JOIN $this->database.$this->table_category AS $this->table_category"
    					." ON ($this->table_app.$this->column_category_serial = $this->table_category.$this->column_category_serial)"
    							." WHERE $this->table.$this->column_online = 1"
    							." AND $this->table.$this->column_apkfile <> 'NA'"
    							." AND $this->table_app.$this->column_app_serial = $appSerial"
    							." AND $this->table.$this->column_ver_code < $vercodeMaxArray[$i]"
    							." ORDER BY $this->table.$this->column_update_time DESC LIMIT 1";
    
    							$query = $this->pdo->query($sql);
    							if($query){
    							$row = $query->fetch();
    							$array[$i]["$this->column_serial"] = $row["$this->column_serial"];
    							$array[$i]["$this->column_apkfile"] = $row["$this->column_apkfile"];
    			$array[$i]["$this->column_ver_code"] = $row["$this->column_ver_code"];
    				$array[$i]["$this->column_ver_name"] = $row["$this->column_ver_name"];
    				$array[$i]["$this->column_sdk_min"] = $row["$this->column_sdk_min"];
    				$array[$i]["$this->column_category"] = $row["$this->column_category"];
    				$array[$i]["$this->column_category_serial"] = $row["$this->column_category_serial"];
    				$array[$i]["$this->column_app_serial"] = $row["$this->column_app_serial"];
    				$array[$i]["$this->column_application"] = $row["$this->column_application"];
    				$array[$i]["$this->column_icon"] = $row["$this->column_icon"];
    				$array[$i]["$this->column_notes"] = $row["$this->column_notes"];
    				$array[$i]["$this->column_register_date"] = $row["$this->column_register_date"];
    				$array[$i]["$this->column_update_time"] = $row["$this->column_update_time"];
    				$i++;
    			}
    			else{
    			Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
    			print_r($this->pdo->errorInfo(),true),
    			Log::ERR_ERROR);
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
    
    public function getApkSerial($app_serial, $vercode)
    {
        if($this->pdo != null){
            $sql = "SELECT "
                    ." $this->column_serial "
                    ." ,$this->column_app_serial "
                    ." FROM "
                    ." $this->database.$this->table" 
                    ." WHERE "
                    ." $this->column_app_serial = '$app_serial'"
                    ." AND $this->column_ver_code = $vercode"
                    ." AND $this->column_online = 1"
                    ." LIMIT 1";
                    
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                if($row["$this->column_app_serial"] == $app_serial){
                    return $row[$this->column_serial];
                }
            }
        }
        return 0;
    }
    
    private function checkApkSerial($app_serial, $vercode, $customer_serial = 0, $brand_serial=0, $model_serial=0)
    {
        if($this->pdo != null){
            $sql = "SELECT "
                    ." $this->column_serial "
                    ." ,$this->column_app_serial "
                    ." FROM "
                    ." $this->database.$this->table" 
                    ." WHERE "
                    ." $this->column_app_serial = '$app_serial'"
                    ." AND $this->column_ver_code = $vercode"
                    ." AND $this->column_customer_serial = $customer_serial"
                    ." AND $this->column_brand_serial = $brand_serial"
                    ." AND $this->column_model_serial = $model_serial"
//                    ." AND $this->column_online = 1"
                    ." LIMIT 1";
                    
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                if($row["$this->column_app_serial"] == $app_serial){
                    return $row[$this->column_serial];
                }
            }
        }
        return 0;
    }
    
    public function addApkfile($application_serial, $file_original, 
                $version_code, $version_name, $sdk_min, $supplier_serial=0,
    			$customer_serial=0,$brand_serial=0,$model_serial=0)
    {
        if ($this->pdo != null){
            
            //$serial = $this->checkApkSerial($application_serial, $version_code);
            $serial = $this->checkApkSerial($application_serial, $version_code,
            		$customer_serial,$brand_serial,$model_serial);
            
            if ($serial > 0){
                $update_time = date('Y-m-d H:i:s');
    
                $sql = "UPDATE $this->database.$this->table SET "
                        ." $this->column_file_original = '$file_original'"
                        .", $this->column_ver_name = '$version_name'"
                        .", $this->column_sdk_min = $sdk_min"
                        .", $this->column_supplier_serial = $supplier_serial"
                        .", $this->column_online = 1"
                        .", $this->column_update_time = '$update_time'"
                        ." WHERE $this->column_serial = $serial";
                        
                Log::i($sql);
                $query = $this->pdo->query($sql);
                if($query){
                	$appMgr = new ApplicationManager();
                	$appMgr->updateApplicationStamp($application_serial);
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
    
                $sql = "INSERT INTO $this->database.$this->table ( "
                        ." $this->column_file_original"
                        .", $this->column_app_serial"
                        .", $this->column_ver_code"
                        .", $this->column_ver_name"
                        .", $this->column_sdk_min"
                        .", $this->column_supplier_serial"
                        .", $this->column_customer_serial"
                        .", $this->column_brand_serial"
                        .", $this->column_model_serial"
                        .", $this->column_register_date"
                        .", $this->column_update_time )"
                        ." VALUES ( "
                        ." '$file_original' "
                        .", $application_serial"
                        .", $version_code"
                        .", '$version_name'"
                        .", $sdk_min"
                        .", $supplier_serial"
                        .", $customer_serial"
                        .", $brand_serial"
                        .", $model_serial"
                        .", '$register_date'"
                        .", '$update_time' )";
    
                Log::i($sql);
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
    
    public function isApkRegistered($app_serial, $vercode, $customer_serial, $brand_serial, $model_serial,$online = 1)
    {
    	$result = 0;
    	if($this->pdo != null){
    		$sql = "SELECT "
    				." $this->column_serial "
    				." ,$this->column_app_serial "
    				." FROM "
    				." $this->database.$this->table"
    				." WHERE "
    				." $this->column_app_serial = '$app_serial'"
    				." AND $this->column_ver_code = $vercode"
    				." AND $this->column_customer_serial = $customer_serial"
    				." AND $this->column_brand_serial = $brand_serial"
    				." AND $this->column_model_serial = $model_serial"
    				." AND $this->column_online = $online"
    				." LIMIT 1";
    
    		$query = $this->pdo->query($sql);
    		if($query){
    			$row = $query->fetch();
    			$result = $row["$this->column_serial"];
    		}
    	}
    	return $result;
    
    }
    
    
    
    public function isApkUploaded($apkfile_serial)
    {
        if($this->pdo != null){
            $sql = "SELECT "
                ." $this->table.$this->column_apkfile AS $this->column_apkfile "
                ." ,$this->table.$this->column_serial AS $this->column_serial"
                ." FROM $this->database.$this->table AS $this->table"
                ." WHERE $this->table.$this->column_serial = $apkfile_serial "
                ." LIMIT 1";
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                if($row["$this->column_serial"] == $apkfile_serial){
                    if ($row["$this->column_apkfile"] != "NA"){
                        require_once '../api/api_constants.php';
                        if (file_exists(API_CONSTANTS::PATH_APK.$row["$this->column_apkfile"])){
                            return TRUE;
                        }
                    }
                }
            }
        }
        return FALSE;
        
    }
    
    public function isApkSame($sha1)
    {
    	$samefile = false;    
    	if($this->pdo != null){
    		$sql = "SELECT "
    				." $this->table.$this->column_apkfile AS $this->column_apkfile "
    				." ,$this->table.$this->column_serial AS $this->column_serial"
    				." FROM $this->database.$this->table AS $this->table"
    				." WHERE $this->table.$this->column_sha1 = '$sha1' "
    				." LIMIT 1";
    		$query = $this->pdo->query($sql);
    		if($query){
    			$row = $query->fetch();
    			require_once '../api/api_constants.php';
    			if (file_exists(API_CONSTANTS::PATH_APK.$row["$this->column_apkfile"])){
    				$samefile = $row["$this->column_apkfile"];
    			}
    		}
    	}
    	return $samefile;
    
    }
    
    
    public function getApkPackageByOriginal($file_original)
    {
        Log::i("getApkPackageByOriginal");
        if($this->pdo != null){
            $sql = "SELECT "
                    ." $this->table.$this->column_file_original AS $this->column_file_original "
                    ." ,$this->table_app.$this->column_package AS $this->column_package "
                    ." FROM "
                    ." $this->database.$this->table AS $this->table"
                    ." INNER JOIN "
                    ." $this->database.$this->table_app AS $this->table_app"
                    ." ON ($this->table.$this->column_app_serial = $this->table_app.$this->column_app_serial) " 
                    ." WHERE "
                    ." $this->table.$this->column_file_original = '$file_original'"
                    ." ORDER BY "
                    ." $this->table.$this->column_update_time DESC"
                    ." LIMIT 1";
                    
            Log::i($sql);        
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                if($row["$this->column_file_original"] == $file_original){
                    Log::i($row[$this->column_package]);
                    return $row[$this->column_package];
                }
            }
        }
        return "";
        
    }
    
    public function getAppserialByApkfile($apkfile)
    {
    	$app_serial = 0;
    	if($this->pdo != null){
    		$sql = "SELECT "
    				." $this->table.$this->column_app_serial AS $this->column_app_serial "
    				." FROM "
    				." $this->database.$this->table AS $this->table"
    				." WHERE "
    				." $this->table.$this->column_apkfile = '$apkfile'"
    				." LIMIT 1";
    		
    		$query = $this->pdo->query($sql);
    		if($query){
                $row = $query->fetch();
               	$app_serial=  $row[$this->column_app_serial];
    			
    		}
    	}
    	return $app_serial;
    
    }

    public function getAppserialByApkSerial($apkserial)
    {
    	$app_serial = 0;
    	if($this->pdo != null){
    		$sql = "SELECT "
    				." $this->table.$this->column_app_serial AS $this->column_app_serial "
    				." FROM "
    				." $this->database.$this->table AS $this->table"
        				." WHERE "
    				." $this->table.$this->column_serial = $apkserial"
        				." LIMIT 1";
    
        						$query = $this->pdo->query($sql);
        						if($query){
        						$row = $query->fetch();
        						$app_serial=  $row[$this->column_app_serial];
        						 
        						}
        						}
        						return $app_serial;
    
    }   

    
    public function getApkFileOriginal($apk_serial)
    {
        Log::i("getApkFileOriginal");
        if($this->pdo != null){
            $sql = "SELECT "
                    ." $this->table.$this->column_file_original AS $this->column_file_original "
                    ." ,$this->table.$this->column_serial AS $this->column_serial "
                    ." FROM "
                    ." $this->database.$this->table AS $this->table"
                    ." WHERE "
                    ." $this->column_serial = $apk_serial"
                    ." ORDER BY "
                    ." $this->column_update_time DESC"
                    ." LIMIT 1";
                    
            Log::i($sql);        
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                if($row["$this->column_serial"] == $apk_serial){
                    Log::i($row[$this->column_file_original]);
                    return $row[$this->column_file_original];
                }
            }
            else{
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                        print_r($this->pdo->errorInfo(),true),
                        Log::ERR_ERROR);
                
            }
        }
        return "";
        
    }

    
    public function getSerialByAppVerCode($app_serial,$ver_code)
    {
        Log::i("getSerialByAppVerCode");
        if($this->pdo != null){
            $sql = "SELECT "
                    ." $this->table.$this->column_serial AS $this->column_serial "
                    ." ,$this->table.$this->column_app_serial AS $this->column_app_serial "
                    ." FROM "
                    ." $this->database.$this->table AS $this->table"
                    ." WHERE "
                    ." $this->table.$this->column_app_serial = $app_serial"
                    ." AND $this->table.$this->column_ver_code = $ver_code"
                    ." ORDER BY "
                    ." $this->table.$this->column_update_time DESC"
                    ." LIMIT 1";
                    
            Log::i($sql);        
            $query = $this->pdo->query($sql);
            if($query){
                $row = $query->fetch();
                if($row["$this->column_app_serial"]+0 == $app_serial){
                    Log::i($row[$this->column_serial]);
                    return $row[$this->column_serial];
                }
            }
        }
        return 0;
        
    }
    
    public function getApkFileByApplication($app_serial, $online = 1)
    {
        Log::i('getApkFileByApplication:'.$app_serial);
        if($this->pdo != null){
        
            $sql = "SELECT "
            				." $this->table.$this->column_serial AS $this->column_serial"
            				.", $this->table.$this->column_ver_code AS $this->column_ver_code"
            				.", $this->table.$this->column_ver_name AS $this->column_ver_name"
            				.", $this->table.$this->column_apkfile AS $this->column_apkfile"
            				.", $this->table.$this->column_update_time AS $this->column_update_time"
                    ." FROM "
                    ." $this->database.$this->table "
                    ." WHERE "
                    ." $this->table.$this->column_app_serial = $app_serial "
                    ." AND $this->table.$this->column_online = $online"
                    ." AND $this->table.$this->column_apkfile <> 'NA'"
                    ." ORDER BY "
                    ." $this->column_update_time DESC "
                    ." LIMIT 1";
            
            Log::i($sql);
            
            $query = $this->pdo->query($sql);
            if($query){
                $i=0;
                $result = $query->fetch();
                Log::i("result=".json_encode($result));
    //            foreach ($result as $row){
                if($result){
                    $array["$this->column_serial"] = $result["$this->column_serial"];
                    $array["$this->column_ver_code"] = $result["$this->column_ver_code"];
                    $array["$this->column_ver_name"] = $result["$this->column_ver_name"];
                    $array["$this->column_apkfile"] = $result["$this->column_apkfile"];
                    $array["$this->column_update_time"] = $result["$this->column_update_time"];
                    Log::i($array["$this->column_apkfile"]);   
                    $i++;
    //            }
                    return $array;
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

    public function getApkFileByDevice($pdo, $app_serial, $sdk_level, $customer_serial, $brand_serial, $model_serial,$online = 1)
    {
    	$apk = FALSE;
    	if($pdo != null){
    		
    		file_put_contents("../json/getApkFileByDevice.json","app:".$app_serial.",sdk:".$sdk_level.",customer:".$customer_serial.
    			",brand:".$brand_serial.",model:".$model_serial.",online:".$online);
    		
    		$apk = $this->getApkFileByModel($app_serial, $sdk_level, $model_serial,$online);
    		if (!$apk) {
    			$apk = $this->getApkFileByBrand($app_serial, $sdk_level, $brand_serial,$online);
    			if (!$apk){
    				$apk = $this->getApkFileByAppSDK($pdo, $app_serial,$sdk_level,$online);
    			}
    		}
    	}
    	else{
    		file_put_contents("../json/getApkFileByDevice.json","pdo failed!  app:".$app_serial.",sdk:".$sdk_level.",customer:".$customer_serial.
    		",brand:".$brand_serial.",model:".$model_serial.",online:".$online);
    		
    	}
    	return $apk;
    
    }
    
    public function getApkFileByModel($app_serial, $sdk_level, $model_serial,$online = 1)
    {
    	$column_customer_online = DatabaseProxy::DB_COLUMN_APPLICATION_CUSTOMER_ONLINE;
    	$array = FALSE;
    	if($this->pdo != null){
    		
    		$sql = "SELECT "
    			." $this->table.$this->column_serial AS $this->column_serial"
    			.", $this->table.$this->column_ver_code AS $this->column_ver_code"
    			.", $this->table.$this->column_ver_name AS $this->column_ver_name"
    			.", $this->table.$this->column_apkfile AS $this->column_apkfile"
    			.", $this->table.$this->column_update_time AS $this->column_update_time"
    			." FROM "
    			." $this->database.$this->table "
    			." WHERE "
    			." $this->table.$this->column_app_serial = $app_serial "
    			." AND $this->table.$this->column_online = $online"
    			." AND $this->table.$this->column_model_serial = $model_serial"
    			//." AND $this->table.$this->column_sdk_min <= $sdk_level"
    			." AND $this->table.$this->column_apkfile <> 'NA'"
    			." ORDER BY "
    			." $this->column_update_time DESC "
    			." LIMIT 1";
    		
    		file_put_contents("../json/getApkFileByModel.json",$sql);
    		
    		$query = $this->pdo->query($sql);
    		if($query){
    			$result = $query->fetch();
    			if($result){
    				$array["$this->column_serial"] = $result["$this->column_serial"];
    				$array["$this->column_ver_code"] = $result["$this->column_ver_code"];
    				$array["$this->column_ver_name"] = $result["$this->column_ver_name"];
    				$array["$this->column_apkfile"] = $result["$this->column_apkfile"];
    				$array["$this->column_update_time"] = $result["$this->column_update_time"];
    				$array["$column_customer_online"] = 1;
    			}
    		}
    	}
    	return $array;
    		
    }
    		
    public function getApkFileByBrand($app_serial, $sdk_level, $brand_serial, $online = 1)
    {
    	$column_customer_online = DatabaseProxy::DB_COLUMN_APPLICATION_CUSTOMER_ONLINE;
    	$array = FALSE;
    	if($this->pdo != null){
    						
    		$sql = "SELECT "
    				." $this->table.$this->column_serial AS $this->column_serial"
    				.", $this->table.$this->column_ver_code AS $this->column_ver_code"
    				.", $this->table.$this->column_ver_name AS $this->column_ver_name"
    				.", $this->table.$this->column_apkfile AS $this->column_apkfile"
    				.", $this->table.$this->column_update_time AS $this->column_update_time"
    				." FROM "
    				." $this->database.$this->table "
    				." WHERE "
    				." $this->table.$this->column_app_serial = $app_serial "
    				//." AND $this->table.$this->column_sdk_min <= $sdk_level"
    				." AND $this->table.$this->column_online = $online"
    				." AND $this->table.$this->column_apkfile <> 'NA'"
    				." AND $this->table.$this->column_brand_serial = $brand_serial"
    				." ORDER BY "
    				." $this->column_update_time DESC "
    				." LIMIT 1";
    						
    		file_put_contents("../json/getApkFileByBrand.json",$sql);
    		
    		$query = $this->pdo->query($sql);
    		if($query){
    			$result = $query->fetch();
    			if($result){
    				$array["$this->column_serial"] = $result["$this->column_serial"];
    				$array["$this->column_ver_code"] = $result["$this->column_ver_code"];
    				$array["$this->column_ver_name"] = $result["$this->column_ver_name"];
    				$array["$this->column_apkfile"] = $result["$this->column_apkfile"];
    				$array["$this->column_update_time"] = $result["$this->column_update_time"];
    				$array["$column_customer_online"] = 1;
    			}
    		}
    	}
    	return $array;
    						
    }
    						
    public function getApkFileByAppSDK($pdo, $app_serial, $sdk_level, $online = 1)
    {
    	$column_customer_online = DatabaseProxy::DB_COLUMN_APPLICATION_CUSTOMER_ONLINE;
    	 
    	$array = FALSE;
    	if($pdo != null){
    
    		$sql = "SELECT "
    				." $this->table.$this->column_serial AS $this->column_serial"
    				.", $this->table.$this->column_ver_code AS $this->column_ver_code"
    				.", $this->table.$this->column_ver_name AS $this->column_ver_name"
    				.", $this->table.$this->column_apkfile AS $this->column_apkfile"
    				.", $this->table.$this->column_update_time AS $this->column_update_time"
    				." FROM "
    				." $this->database.$this->table "
    				." WHERE "
    				." $this->table.$this->column_app_serial = $app_serial "
    				//." AND $this->table.$this->column_sdk_min <= $sdk_level"
    				." AND $this->table.$this->column_online = $online"
    				." AND $this->table.$this->column_apkfile <> 'NA'"
    				." ORDER BY "
    				." $this->column_update_time DESC "
    				." LIMIT 1";
    
    		file_put_contents("../json/getApkFileByAppSDK.json",$sql);
    
    		$query = $this->pdo->query($sql);
    		if($query){
    			$result = $query->fetch();
    			if($result){
    				$array["$this->column_serial"] = $result["$this->column_serial"];
    				$array["$this->column_ver_code"] = $result["$this->column_ver_code"];
    				$array["$this->column_ver_name"] = $result["$this->column_ver_name"];
    				$array["$this->column_apkfile"] = $result["$this->column_apkfile"];
    				$array["$this->column_update_time"] = $result["$this->column_update_time"];
    				$array["$column_customer_online"] = 0;
    			}
    		}
    	}
    	return $array;
    
    }
    
    
    
    public function getApkFileByApplicationVercodeMax($app_serial, $vercodemax, $online = 1)
    {
    	Log::i('getApkFileByApplication:'.$app_serial);
    	if($this->pdo != null){
    
    		$sql = "SELECT "
    				." $this->table.$this->column_serial AS $this->column_serial"
    				.", $this->table.$this->column_ver_code AS $this->column_ver_code"
    				.", $this->table.$this->column_ver_name AS $this->column_ver_name"
    				.", $this->table.$this->column_apkfile AS $this->column_apkfile"
    				.", $this->table.$this->column_update_time AS $this->column_update_time"
    				." FROM "
    				." $this->database.$this->table "
    				." WHERE "
    				." $this->table.$this->column_app_serial = $app_serial "
    				." AND $this->table.$this->column_online = $online"
    						." AND $this->table.$this->column_ver_code < $vercodemax"
    						." AND $this->table.$this->column_apkfile <> 'NA'"
    						." ORDER BY "
    						." $this->column_update_time DESC "
    						." LIMIT 1";
    
    						Log::i($sql);
    
    		$query = $this->pdo->query($sql);
    		if($query){
    				$i=0;
    				$result = $query->fetch();
    				Log::i("result=".json_encode($result));
    			//            foreach ($result as $row){
    		if($result){
    		$array["$this->column_serial"] = $result["$this->column_serial"];
    		$array["$this->column_ver_code"] = $result["$this->column_ver_code"];
    		$array["$this->column_ver_name"] = $result["$this->column_ver_name"];
    		$array["$this->column_apkfile"] = $result["$this->column_apkfile"];
    		$array["$this->column_update_time"] = $result["$this->column_update_time"];
    		Log::i($array["$this->column_apkfile"]);
    		$i++;
    		//            }
    		return $array;
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
    
    
    public function updateApkfile($file_original, $apkfile)
    {
        Log::i('updateApkfile');
        if ($this->pdo != null){
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "UPDATE $this->database.$this->table SET "
                        ."$this->column_apkfile = '$apkfile'"
                        .", $this->column_update_time = '$update_time'"
                        ." WHERE $this->column_file_original = '$file_original'";
                        
            Log::i($sql);
            $query = $this->pdo->query($sql);
            if($query){
            	$application_serial = $this->getAppserialByApkfile($apkfile);
            	$appMgr = new ApplicationManager();
            	$appMgr->updateApplicationStamp($application_serial);
            	 
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
    
    public function updateApkfileInfo($app_serial,$version_code, $file_original, $apkfile,$sha1,
    			$customer_serial, $brand_serial, $model_serial)
    {
        Log::i('updateApkfile');
        if ($this->pdo != null){
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "UPDATE $this->database.$this->table SET "
                        ."$this->column_apkfile = '$apkfile'"
                        .", $this->column_update_time = '$update_time'"
                        .", $this->column_sha1 = '$sha1'"
                        .", $this->column_online = 1"
                        ." WHERE $this->column_file_original = '$file_original'"
                        ." AND $this->column_ver_code = $version_code"
                        ." AND $this->column_customer_serial = $customer_serial"
                        ." AND $this->column_brand_serial = $brand_serial"
                        ." AND $this->column_model_serial = $model_serial"
                        ." AND $this->column_app_serial = $app_serial";
                        
            file_put_contents("../json/sql.json",$sql);
            
            $query = $this->pdo->query($sql);
            if($query){
            	$appMgr = new ApplicationManager();
            	$appMgr->updateApplicationStamp($app_serial);
            	 
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
    
    
    public function updateApkfileSupplierNotes($serial, $supplier_serial, $notes)
    {
        if ($this->pdo != null){
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "UPDATE $this->database.$this->table SET "
                        ."$this->column_supplier_serial = $supplier_serial"
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
    
    
    public function updateApkfileBySerial($serial, $apkfile)
    {
        Log::i('updateApkfileBySerial');
        if ($this->pdo != null){
            $update_time = date('Y-m-d H:i:s');
                
            $sql = "UPDATE $this->database.$this->table SET "
                        ."$this->column_apkfile = '$apkfile'"
                        .", $this->column_update_time = '$update_time'"
                        ." WHERE $this->column_serial = $serial";
                        
            Log::i($sql);
            $query = $this->pdo->query($sql);
            if($query){
            	$application_serial = $this->getAppserialByApkfile($apkfile);
            	$appMgr = new ApplicationManager();
            	$appMgr->updateApplicationStamp($application_serial);
            	 
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

    public function updateApkfileSha1($serial, $sha1)
    {
    	if ($this->pdo != null){
    		$update_time = date('Y-m-d H:i:s');
    
    		$sql = "UPDATE $this->database.$this->table SET "
    		."$this->column_sha1 = '$sha1'"
    		.", $this->column_update_time = '$update_time'"
    		." WHERE $this->column_serial = $serial";
    
    		$query = $this->pdo->query($sql);
    		if($query){
    			$application_serial = $this->getAppserialByApkSerial($serial);
    			$appMgr = new ApplicationManager();
    			$appMgr->updateApplicationStamp($application_serial);
    
    			return TRUE;
    		}
    	}
    	return FALSE;
    }
    
    public function onoffApkfile($serial, $online)
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
            	$application_serial = $this->getAppserialByApkSerial($serial);
            	$appMgr = new ApplicationManager();
            	$appMgr->updateApplicationStamp($application_serial);
            	
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
