<?php
/**
 *BlacklistManager - ApkStore blacklist management class
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

class BlacklistManager{
	
	/////////////////////////////////////////////////
	// PROPERTIES, PUBLIC
	/////////////////////////////////////////////////
	
	
	/////////////////////////////////////////////////
	// PROPERTIES, PRIVATE
	/////////////////////////////////////////////////
	
	private $db         = null;
	private $pdo        = null;
	
	private $database               = DatabaseProxy::DB_NAME;
	
	private $table                  = DatabaseProxy::DB_TABLE_BLACKLIST;
	
	private $column_serial          = DatabaseProxy::DB_COLUMN_BLACKLIST_SERIAL;
	private $column_app_serial	    = DatabaseProxy::DB_COLUMN_BLACKLIST_APP_SERIAL;
	private $column_brand_serial 	= DatabaseProxy::DB_COLUMN_BLACKLIST_BRAND_SERIAL;
	
	/////////////////////////////////////////////////
	// PROPERTIES, PROTECTED
	/////////////////////////////////////////////////
	
	
	/////////////////////////////////////////////////
	// CONSTANTS
	/////////////////////////////////////////////////
	
	const ERR_CODE                	= 'err_code';
	const ERR_NONE                	= 0;
	const ERR_DATABASE            	= 1;
	const ERR_BRAND               	= 2;
	
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
		Log::i('getTotal');
		if($this->pdo != null){
			$state = self::DB_VAL_PROM_ON;
			
			$sql = "SELECT $this->column_serial"
			." FROM $this->database.$this->table"
			." WHERE $this->column_state = $state";
			$query = $this->pdo->query($sql);
			if($query){
			$array = $query->fetchAll();
			return count($array);
			}
		}
		return 0;
	}


	public function fetchPromotions($sort, $cur_page, $limit, $order = 0, $online = 1)
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
			
			$sql = "SELECT "
					." $this->table.$this->column_serial AS $this->column_serial"
					.", $this->table.$this->column_application AS $this->column_application"
					.", $this->table.$this->column_puup AS $this->column_puup"
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
				$array[$i]["$this->column_description"] = $row["$this->column_description"];
				$array[$i]["$this->column_notes"] = $row["$this->column_notes"];
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
	
	
	public function addBlacklistRecord($brand_serial,$app_serial){
		$result = FALSE;
		
		if($this->pdo != null){
				
			if(!$this->isBlacklist($brand_serial, $app_serial)){
				$sql = "INSERT INTO $this->table ($this->column_app_serial, $this->column_brand_serial)".
					" VALUES ( $app_serial, $brand_serial )";
				
				file_put_contents("../json/addBlacklistRecord.json",$sql);
				$query = $this->pdo->query($sql);
				if($query){
					$result = TRUE;
				}
			}
			else{
				$result = TRUE;
			}
			
			
            
		}
		return $result;
	}
	
	public function removeBlacklistRecord($brand_serial, $app_serial){
		$result = FALSE;
	
		if($this->pdo != null){
				
			$sql = "DELETE FROM $this->database.$this->table" 
                        ." WHERE $this->column_app_serialserial = $app_serial AND $this->column_brand_serial = $brand_serial";
            $query = $this->pdo->query($sql);
			if($query){
				$result = TRUE;
	
			}
			
		}
		return $result;
	}
	
	public function isBlacklist($brand_serial, $app_serial){
		$result = FALSE;
		if($this->pdo != null){
			$sql = "SELECT * FROM $this->table WHERE $this->column_app_serial = $app_serial AND $this->column_brand_serial = $brand_serial";
			file_put_contents("../json/isBlacklist.json",$sql);
			
			$query = $this->pdo->query($sql);
			if($query){
				$row = $query->fetch();
				if($row["$this->column_app_serial"]==$app_serial){
					$result = TRUE;
				}
			}
	
		}
	
		return $result;
	}
	
	
	
	
}


?>
