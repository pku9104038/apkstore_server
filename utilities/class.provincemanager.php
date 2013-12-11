<?php
/**
 * ProvinceManager - ApkStore provinces management class
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

class ProvinceManager{
	
	/////////////////////////////////////////////////
	// PROPERTIES, PUBLIC
	/////////////////////////////////////////////////
	
	
	/////////////////////////////////////////////////
	// PROPERTIES, PRIVATE
	/////////////////////////////////////////////////
	
	private $db         = null;
	private $pdo        = null;
	
	private $database               = DatabaseProxy::DB_NAME;
	
	private $table                  = DatabaseProxy::DB_TABLE_PROVINCES;
	
	private $column_serial          = DatabaseProxy::DB_COLUMN_PROVINCE_SERIAL;
	private $column_province	    = DatabaseProxy::DB_COLUMN_PROVINCE;
	private $column_stamp		    = DatabaseProxy::DB_COLUMN_PROVINCE_DATE;
	
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
	
	
	public function getProvinceSN($province){
		
		$sn = 0;
		
		if($this->pdo != null){
			
			$sql = "SELECT $this->column_serial".
				" FROM $this->table".
				" WHERE $this->column_province = '$province'";
			
			$query = $this->pdo->query($sql);
			if($query){
				$row = $query->fetch();
				$sn = $row["$this->column_serial"];
			}
		}
			
		return $sn;
		
	}
	
	public function getProvinceDate($province){
	
		$date = "";
	
		if($this->pdo != null){
				
			$sql = "SELECT $this->column_stamp".
					" FROM $this->table".
					" WHERE $this->column_province = $province";
				
			$query = $this->pdo->query($sql);
			if($query){
				$row = $query->fetch();
				$date = $row["$this->column_stamp"];
			}
		}
			
		return $date;
	
	}
	
	public function getProvinces(){
	
		$array = FALSE;
	
		if($this->pdo != null){
	
			$sql = "SELECT * ".
					" FROM $this->table";
	
			$query = $this->pdo->query($sql);
			if($query){
				$rows = $query->fetchAll();
				$i=0;
				foreach ($rows as $row){
					$array[$i]["$this->column_province"] = $row["$this->column_province"];
					$array[$i]["$this->column_serial"] = $row["$this->column_serial"];
					$i++;
				}
			}
		}
			
		return $array;
	
	}
	
	public function getProvinceArray(){
	
		$array = FALSE;
	
		if($this->pdo != null){
	
			$sql = "SELECT * ".
					" FROM $this->table";
			$query = $this->pdo->query($sql);
			if($query){
				$rows = $query->fetchAll();
				$i=0;
				foreach ($rows as $row){
					$array[$i] = $row["$this->column_province"];
					$i++;
				}
			}
		}
			
		return $array;
	
	}
	
	public function getProvince($province_serial){
	
		$array = FALSE;
	
		if($this->pdo != null){
	
			$sql = "SELECT * ".
					" FROM $this->table WHERE $this->column_serial = $province_serial";
			file_put_contents("../json/debug.json",$_SERVER ['PHP_SELF'].date(" Y-m-d H:i:s ").$sql);
			$query = $this->pdo->query($sql);
			if($query){
				$row = $query->fetch();
				$array = $row["$this->column_province"];
			}
		}
			
		return $array;
	
	}
	
	public function addProvince($province){
		$sn = 0;
		
		if($this->pdo != null){
			
			$date = date("Y-m-d H:i:s");
			
			$sql = "INSERT INTO $this->table ($this->column_province, $this->column_stamp)".
					" VALUES ( '$province', '$date')";
			
			
			$query = $this->pdo->query($sql);
		    if($query){
                $sn =  $this->getProvinceSN($province);
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    $log_msg,
                    Log::ERR_ERROR);
            }
		}
		
		return $sn;
	}
	
}


?>
