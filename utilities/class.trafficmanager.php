<?php
/**
 * TrafficManager - ApkStore traffic counts management class
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

class TrafficManager{
	
	/////////////////////////////////////////////////
	// PROPERTIES, PUBLIC
	/////////////////////////////////////////////////
	
	
	/////////////////////////////////////////////////
	// PROPERTIES, PRIVATE
	/////////////////////////////////////////////////
	
	private $db         = null;
	private $pdo        = null;
	
	private $database               = DatabaseProxy::DB_NAME;
	
	private $table                  = DatabaseProxy::DB_TABLE_TRAFFICS;
	
	private $column_serial          = DatabaseProxy::DB_COLUMN_TRAFFIC_SERIAL;
	private $column_device          = DatabaseProxy::DB_COLUMN_TRAFFIC_DEV_SERIAL;
	private $column_date 			= DatabaseProxy::DB_COLUMN_TRAFFIC_DATE;
	private $column_my_rx       	= DatabaseProxy::DB_COLUMN_TRAFFIC_MY_RX;
	private $column_my_tx       	= DatabaseProxy::DB_COLUMN_TRAFFIC_MY_TX;
	private $column_mobile_rx       = DatabaseProxy::DB_COLUMN_TRAFFIC_MOBILE_RX;
	private $column_mobile_tx       = DatabaseProxy::DB_COLUMN_TRAFFIC_MOBILE_TX;
	private $column_total_rx       	= DatabaseProxy::DB_COLUMN_TRAFFIC_TOTAL_RX;
	private $column_total_tx       	= DatabaseProxy::DB_COLUMN_TRAFFIC_TOTAL_TX;
	
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
	
	
	
	public function addRecord($devsn, $date, $my_rx, $my_tx, $mobile_rx, $mobile_tx, $total_rx, $total_tx){
		$result = FALSE;
		
		if($this->pdo != null){
				
			$sql = "INSERT INTO $this->table ($this->column_device, $this->column_date, ".
					" $this->column_my_rx, $this->column_my_tx, $this->column_mobile_rx ".
					" , $this->column_mobile_tx, $this->column_total_rx, $this->column_total_tx )".
					" VALUES ( $devsn, '$date', $my_rx, $my_tx, $mobile_rx, $mobile_tx, $total_rx, $total_tx)";
				
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
}

?>