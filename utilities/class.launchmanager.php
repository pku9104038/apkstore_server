<?php
/**
 *LaunchManager - ApkStore launch report management class
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

class LaunchManager{
	
	/////////////////////////////////////////////////
	// PROPERTIES, PUBLIC
	/////////////////////////////////////////////////
	
	
	/////////////////////////////////////////////////
	// PROPERTIES, PRIVATE
	/////////////////////////////////////////////////
	
	private $db         = null;
	private $pdo        = null;
	
	private $database               = DatabaseProxy::DB_NAME;
	
	private $table                  = DatabaseProxy::DB_TABLE_LAUNCHS;
	
	private $column_serial          = DatabaseProxy::DB_COLUMN_LAUNCH_SERIAL;
	private $column_device	     	= DatabaseProxy::DB_COLUMN_LAUNCH_DEV_SERIAL;
	private $column_package 		= DatabaseProxy::DB_COLUMN_LAUNCH_PACKAGE;
	private $column_vercode       	= DatabaseProxy::DB_COLUMN_LAUNCH_VERCODE;
	private $column_launch_stamp    = DatabaseProxy::DB_COLUMN_LAUNCH_STAMP;
	private $column_report_stamp    = DatabaseProxy::DB_COLUMN_LAUNCH_REPORT_STAMP;
	
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
	
	
	
	public function addRecord($devsn, $package, $vercode, $stamp){
		$result = FALSE;
		
		if($this->pdo != null){
				
			$sql = "INSERT INTO $this->table ($this->column_device, $this->column_package, ".
					" $this->column_vercode, $this->column_launch_stamp )".
					" VALUES ( $devsn, '$package', $vercode,'$stamp' )";
			
			
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
	
	public function isNewRecord($devsn,$package, $vercode,  $stamp){
		$result = TRUE;
		if($this->pdo != null){
			$sql = "SELECT * FROM $this->table WHERE $this->column_device = $devsn AND $this->column_package='$package'".
					" AND $this->column_vercode=$vercode  AND $this->column_action_stamp='$stamp'";
	
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
