<?php
/**
 *DownloadManager - ApkStore download report management class
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

class DownloadManager{
	
	/////////////////////////////////////////////////
	// PROPERTIES, PUBLIC
	/////////////////////////////////////////////////
	
	
	/////////////////////////////////////////////////
	// PROPERTIES, PRIVATE
	/////////////////////////////////////////////////
	
	private $db         = null;
	private $pdo        = null;
	
	private $database               = DatabaseProxy::DB_NAME;
	
	private $table                  = DatabaseProxy::DB_TABLE_DOWNLOADS;
	
	private $column_serial          = DatabaseProxy::DB_COLUMN_DOWNLOAD_SERIAL;
	private $column_device	     	= DatabaseProxy::DB_COLUMN_DOWNLOAD_DEV_SERIAL;
	private $column_package 		= DatabaseProxy::DB_COLUMN_DOWNLOAD_PACKAGE;
	private $column_vercode       	= DatabaseProxy::DB_COLUMN_DOWNLOAD_VERCODE;
	private $column_action         	= DatabaseProxy::DB_COLUMN_DOWNLOAD_ACTION;
	private $column_action_stamp    = DatabaseProxy::DB_COLUMN_DOWNLOAD_ACTION_STAMP;
	private $column_report_stamp    = DatabaseProxy::DB_COLUMN_DOWNLOAD_REPORT_STAMP;
	
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
	
	
	
	public function addRecord($devsn, $package, $vercode, $action, $stamp){
		$result = FALSE;
		
		if($this->pdo != null){
				
			$sql = "INSERT INTO $this->table ($this->column_device, $this->column_package, ".
					" $this->column_vercode, $this->column_action, $this->column_action_stamp )".
					" VALUES ( $devsn, '$package', $vercode, $action, '$stamp' )";
				
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
	

	public function isNewRecord($devsn,$package, $vercode, $action, $stamp){
		$result = TRUE;
		if($this->pdo != null){
			$sql = "SELECT * FROM $this->table WHERE $this->column_device = $devsn AND $this->column_package='$package'".
				" AND $this->column_vercode=$vercode AND $this->column_action=$action AND $this->column_action_stamp='$stamp'";
				
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
	
	
	public function fetchDownloadCountByApp($app_array, $stamp_start, $stamp_end, $cur_page, $limit)
	{
		$column_value = "value";
		$column_name = "name";
	
		require_once '../utilities/class.applicationmanager.php';
		$appMgr = new ApplicationManager();
		
		$array = FALSE;
		if($this->pdo != null){
			$skip = ($cur_page - 1) * $limit;
			if($skip <0){
				$skip = 0;
			}
			$stop = $skip + $limit;
			if($stop>count($app_array)){
				$stop = count($app_array);
			}
			if($skip>$stop){
				$skip = $stop;
			}
	
			$index=0;
			for ($counter=$skip; $counter<$stop; $counter++){
	
				$sql = "SELECT COUNT(*) as $column_value".
						" FROM $this->table".
						" WHERE $this->column_action_stamp >= '$stamp_start'".
						" AND $this->column_action_stamp <= '$stamp_end'".
						" AND $this->column_action = 3";
	
				
				$package = $app_array[$counter];
				$sql .= " AND $this->column_package = '$package'";
	
	
				//echo $sql;
				$query = $this->pdo->query($sql);
				if($query){
					$row = $query->fetch();
					if ($row["$column_value"]>0) {
						$array[$index]["$column_name"] = $appMgr->getApplicationNameByPackage($package);
						$array[$index]["$column_value"] = $row["$column_value"];
						$index++;
							
					}
						
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
	
	
	
	public function fetchDownloadCountByPeriod($period_array, $app_array, $cur_page, $limit)
	{
		$column_value = "value";
		$column_name = "name";
	
		require_once '../utilities/class.applicationmanager.php';
		$appMgr = new ApplicationManager();
	
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
						" WHERE $this->column_action_stamp >= '$stamp_start'".
						" AND $this->column_action_stamp <= '$stamp_end'".
						" AND $this->column_action = 3";
	
	
				if($app_array){
					for($i=0;$i<count($app_array);$i++){
						if ($i==0){
							$sql .= " AND ( $this->column_package = '$app_array[$i]'";
						}
						else{
							$sql .= " OR $this->column_package = '$app_array[$i]'";
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
					$row = $query->fetch();
					//if ($row["$column_value"]>0) {
						$array[$index]["$column_name"] = date("n月j日", strtotime($stamp_start));
						$array[$index]["$column_value"] = $row["$column_value"];
						$index++;
							
					//}
	
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


	public function getDownloadCountByAppPackage($app_package)
	{
		$column_value = "value";
	
		$count = 0;
		if($this->pdo != null){
				$sql = "SELECT COUNT(*) as $column_value".
						" FROM $this->table".
						" WHERE  $this->column_action = 3".
						" AND $this->column_package = '$app_package'";
	
				$query = $this->pdo->query($sql);
				if($query){
					$row = $query->fetch();
					if ($row["$column_value"]>0) {
						$count = $row["$column_value"];							
					}
	
				}
			
		}
		
		return $count;
	}
	
	
}


?>
