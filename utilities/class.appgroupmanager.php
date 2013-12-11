<?php
/**
 * AppGroupManager - ApkStore app_group counts management class
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

class AppGroupManager{
	
	/////////////////////////////////////////////////
	// PROPERTIES, PUBLIC
	/////////////////////////////////////////////////
	
	
	/////////////////////////////////////////////////
	// PROPERTIES, PRIVATE
	/////////////////////////////////////////////////
	
	private $db         = null;
	private $pdo        = null;
	
	private $database               = DatabaseProxy::DB_NAME;
	
	private $table                  = DatabaseProxy::DB_TABLE_APP_GROUPS;
	
	private $column_serial          = DatabaseProxy::DB_COLUMN_APP_GROUP_SERIAL;
	private $column_device	     	= DatabaseProxy::DB_COLUMN_APP_GROUP_DEV_SERIAL;
	private $column_date 			= DatabaseProxy::DB_COLUMN_APP_GROUP_DATE;
	private $column_counts       	= DatabaseProxy::DB_COLUMN_APP_GROUP_COUNTS;
	private $column_stamp         	= DatabaseProxy::DB_COLUMN_APP_GROUP_REPORT_STAMP;
	
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
	
	
	
	public function addRecord($devsn, $date, $counts){
		$result = FALSE;
		
		if($this->pdo != null){
				
			$sql = "INSERT INTO $this->table ($this->column_device, $this->column_date, ".
					" $this->column_counts )".
					" VALUES ( $devsn, '$date', $counts)";
				
			$query = $this->pdo->query($sql);
		    if($query){
            	$result = TRUE;
            }
            else{
                $log_msg = print_r($this->pdo->errorInfo(),true);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__, 
                    $log_msg,
                    Log::ERR_ERROR);
            }
		}
		return $result;
	}
	

	public function isNewRecord($devsn, $date){
		$result = TRUE;
		if($this->pdo != null){
			$sql = "SELECT * FROM $this->table WHERE $this->column_device = $devsn AND $this->column_date='$date'";
				
			$query = $this->pdo->query($sql);
			if($query){
				$row = $query->fetch();
				if($row["$this->column_device"]==$devsn){
					$result = FALSE;
				}
			}
			else{
				$log_msg = print_r($this->pdo->errorInfo(),true);
				Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
				$log_msg,
				Log::ERR_ERROR);
			}
	
		}
	
		return $result;
	}
	

	public function fetchDeviceAppGroupByPeriod($period_array,$cur_page, $limit)
	{
		$column_name = "name";
		$column_value = "value";
	
		$array = FALSE;
		if($this->pdo != null){
	
			$skip = ($cur_page - 1) * $limit;
			if($skip <0){
				$skip = 0;
			}
			$stop = $skip + $limit;
			if($stop>count($period_array)){
				$stop = count($period_array);
			}
			if($skip>$stop){
				$skip = $stop;
			}
	
			$index=0;
			for ($counter=$skip; $counter<$stop; $counter++){
	
				$stamp_start = $period_array[$counter]["stamp_start"];
				$stamp_end = $period_array[$counter]["stamp_end"];
	
				$sql = "SELECT COUNT(*) as $column_value".
						" FROM $this->table".
						" WHERE $this->column_date >= '$stamp_start'".
						" AND $this->column_date <= '$stamp_end'";
	
	
				$query = $this->pdo->query($sql);
				if($query){
					$row = $query->fetch();
					$array[$index]["$column_name"] = date("n月j日", strtotime($stamp_start));//."~".date("n-j", strtotime($stamp_end)-1);
					$array[$index]["$column_value"] = $row["$column_value"];
					$index++;
	
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
	
	
	public function getRecords($date_start,$date_end)
	{
	
		$array = FALSE;
		if($this->pdo != null){
	
	
			$sql = "SELECT *".
					" FROM $this->table".
					" WHERE $this->column_date >= '$date_start'".
					" AND $this->column_date <= '$date_end'";
	
	
			$query = $this->pdo->query($sql);
			if($query){
				$rows = $query->fetchAll();
				$index=0;
				foreach ($rows as $row){
					$array[$index]["$this->column_device"] = $row["$this->column_device"];
					$array[$index]["$this->column_date"] = $row["$this->column_date"];
					$array[$index]["$this->column_counts"] = $row["$this->column_counts"];
					$index++;
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
}


?>
