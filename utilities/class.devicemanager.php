<?php
/**
 * DeviceManager - ApkStore device register management class
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

class DeviceManager{
	
	/////////////////////////////////////////////////
	// PROPERTIES, PUBLIC
	/////////////////////////////////////////////////
	
	
	/////////////////////////////////////////////////
	// PROPERTIES, PRIVATE
	/////////////////////////////////////////////////
	
	private $db         = null;
	private $pdo        = null;
	
	private $database               = DatabaseProxy::DB_NAME;
	
	private $table                  = DatabaseProxy::DB_TABLE_DEVICES;
	
	private $column_serial          = DatabaseProxy::DB_COLUMN_DEVICE_SERIAL;
	private $column_imei	     	= DatabaseProxy::DB_COLUMN_IMEI;
	private $column_model_serial 	= DatabaseProxy::DB_COLUMN_DEV_MODEL_SERIAL;
	private $column_sdk_level       = DatabaseProxy::DB_COLUMN_SDK_LEVEL;
	private $column_package         = DatabaseProxy::DB_COLUMN_REGISTER_PACKAGE;
	private $column_vercode         = DatabaseProxy::DB_COLUMN_REGISTER_VERCODE;
	private $column_stamp		    = DatabaseProxy::DB_COLUMN_REGISTER_STAMP;
	private $column_ip	            = DatabaseProxy::DB_COLUMN_REGISTER_IP;
	private $column_province        = DatabaseProxy::DB_COLUMN_REGISTER_PROVINCE;
	private $column_city		    = DatabaseProxy::DB_COLUMN_REGISTER_CITY;
	
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
	
	
	public function getDeviceSN($imei){
		
		$sn = 0;
		
		if($this->pdo != null){
			
			$sql = "SELECT $this->column_serial".
				" FROM $this->table".
				" WHERE $this->column_imei = '$imei'";
			
			$query = $this->pdo->query($sql);
			if($query){
				$row = $query->fetch();
				$sn = $row["$this->column_serial"];
			}
		}
			
		return $sn;
		
	}
	
	public function getDeviceDate($device_serial){
	
		$date = "";
	
		if($this->pdo != null){
				
			$sql = "SELECT $this->column_stamp".
					" FROM $this->table".
					" WHERE $this->column_serial = $device_serial";
				
			$query = $this->pdo->query($sql);
			if($query){
				$row = $query->fetch();
				$date = $row["$this->column_stamp"];
			}
		}
			
		return $date;
	
	}
	
	public function getDeviceCity($device_serial){
	
		$city = "";
	
		if($this->pdo != null){
	
			$sql = "SELECT $this->column_city".
					" FROM $this->table".
					" WHERE $this->column_serial = $device_serial";
	
			$query = $this->pdo->query($sql);
			if($query){
				$row = $query->fetch();
				$city = $row["$this->column_city"];
			}
		}
			
		return $city;
	
	}
	public function addDevice($imei, $model_serial, $package, $vercode, $sdk, $ip,$province,$city){
		$sn = 0;
		
		if($this->pdo != null){
				
			$sql = "INSERT INTO $this->table ($this->column_imei, $this->column_model_serial, ".
					" $this->column_package, $this->column_vercode, $this->column_sdk_level, ".
					" $this->column_ip, $this->column_province, $this->column_city )".
					" VALUES ( '$imei', $model_serial, '$package', $vercode, $sdk, '$ip', '$province', '$city')";
				
			$query = $this->pdo->query($sql);
		    if($query){
                $sn =  $this->getDeviceSN($imei);
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
	
	public function getDeviceInfo($imei){
		$array = false;
		
		if($this->pdo != null){
				
			$sql = "SELECT * ".
					" FROM $this->table".
					" WHERE $this->column_imei = '$imei'";
				
			$query = $this->pdo->query($sql);
			
			if($query){
				$row = $query->fetch();
				$i = 0;
				$array[$i]["$this->column_serial"] = $row["$this->column_serial"];
				$array[$i]["$this->column_stamp"] = $row["$this->column_stamp"];
				$array[$i]["$this->column_province"] = $row["$this->column_province"];
				$array[$i]["$this->column_city"] = $row["$this->column_city"];
				$column_model = DatabaseProxy::DB_COLUMN_MODEL; 
				require_once 'class.modelmanager.php';
				$mgr = new ModelManager();
				$array[$i]["$column_model"] = $mgr->getModel($row["$this->column_model_serial"]); 
				
			}
		}
			
		return $array;
		
	}
	
	
	
	
	public function getDevicesTotalByModel($model_array,$city_array, $date_start, $date_end)
	{
		if($this->pdo != null){
			$column_total = "total";
			$sql = "SELECT COUNT(*) AS $column_total ".
					" FROM $this->table".
					" WHERE $this->column_stamp >= '$date_start'".
					" AND $this->column_stamp <= '$date_end'";
	
			if($model_array){
				for($i=0;$i<count($model_array);$i++){
					if ($i==0){
						$sql .= " AND ( $this->column_model_serial = $model_array[$i]";
					}
					else{
						$sql .= " OR $this->column_model_serial = $model_array[$i]";
					}
				}
				$sql .= ")";
			}
			else{
				;
			}

			if($city_array){
				for($i=0;$i<count($city_array);$i++){
					if ($i==0){
						$sql .= " AND ( $this->column_city = '$city_array[$i]'";
					}
					else{
						$sql .= " OR $this->column_city = '$city_array[$i]'";
					}
				}
				$sql .= ") ";
			}
			else{
				;
			}
				
			
			$query = $this->pdo->query($sql);
			if($query){
				$row = $query->fetch();
				$array = $row["$column_total"];
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
	
	public function fetchDevicesByModel($model_array, $city_array, $date_start, $date_end, $cur_page=1, $limit=0)
	{
		if($this->pdo != null){
			
			$skip = ($cur_page - 1) * $limit;
			if($skip <0){
				$skip = 0;
			}
			$sql = "SELECT ".
					" $this->column_stamp ".
					", $this->column_model_serial".
					", $this->column_imei".
					", $this->column_province".
					", $this->column_city".
					" FROM $this->table".
					" WHERE $this->column_stamp >= '$date_start'".
					" AND $this->column_stamp <= '$date_end'";
						
			if($model_array){
				for($i=0;$i<count($model_array);$i++){
					if ($i==0){
						$sql .= " AND ( $this->column_model_serial = $model_array[$i]";
					}
					else{
						$sql .= " OR $this->column_model_serial = $model_array[$i]";
					}
				}
				$sql .= ") ";
			}
			else{
				;
			}
			
			if($city_array){
				for($i=0;$i<count($city_array);$i++){
					if ($i==0){
						$sql .= " AND ( $this->column_city = '$city_array[$i]' ";
					}
					else{
						$sql .= " OR $this->column_city = '$city_array[$i]' ";
					}
				}
				$sql .= ") ";
			}
			else{
				;
			}
				
			
			if ($limit > 0) {
				$sql .=	"ORDER BY $this->column_stamp LIMIT $skip,$limit";
			}
			
			//file_put_contents("../json/debug.json",$sql);
			
			$query = $this->pdo->query($sql);
    		if($query){
    			$rows = $query->fetchAll();
    			$i = 0;
				foreach ($rows as $row){
				
					$array[$i]["$this->column_stamp"] = $row["$this->column_stamp"];
					$array[$i]["$this->column_model_serial"] = $row["$this->column_model_serial"];
					$array[$i]["$this->column_imei"] = $row["$this->column_imei"];
					$array[$i]["$this->column_province"] = $row["$this->column_province"];
					$array[$i]["$this->column_city"] = $row["$this->column_city"];
					$i++;
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

	
	public function fetchDeviceCountByPeriod($period_array, $model_array, $city_array, $cur_page, $limit, $period_level = 4)
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
						" WHERE $this->column_stamp >= '$stamp_start'".
						" AND $this->column_stamp <= '$stamp_end'";
		
				if($model_array){
					for($i=0;$i<count($model_array);$i++){
						if ($i==0){
							$sql .= " AND ( $this->column_model_serial = $model_array[$i]";
						}
						else{
							$sql .= " OR $this->column_model_serial = $model_array[$i]";
						}
					}
					$sql .= ") ";
				}
				else{
					;
				}
					
				if($city_array){
					for($i=0;$i<count($city_array);$i++){
						if ($i==0){
							$sql .= " AND ( $this->column_city = '$city_array[$i]' ";
						}
						else{
							$sql .= " OR $this->column_city = '$city_array[$i]' ";
						}
					}
					$sql .= ") ";
				}
				else{
					;
				}
				
				//
				$query = $this->pdo->query($sql);
				if($query){
					$row = $query->fetch();
					switch($period_level){
						case 1://year
							$array[$index]["$column_name"] = date("Y年", strtotime($stamp_start));//."~".date("n-j", strtotime($stamp_end)-1);
											
							break;
						case 2://month
							$array[$index]["$column_name"] = date("n月", strtotime($stamp_start));//."~".date("n-j", strtotime($stamp_end)-1);
											
							break;
						case 3://week
							$array[$index]["$column_name"] = date("W周", strtotime($stamp_start))."：".date("n月j日", strtotime($stamp_start));
								
							break;
						case 4://day
							$array[$index]["$column_name"] = date("n月j日", strtotime($stamp_start));//."~".date("n-j", strtotime($stamp_end)-1);
							break;
					}
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
	
	public function fetchDeviceCountByArea($area_level, $area_array, $model_array, $stamp_start, $stamp_end, $cur_page, $limit)
	{
		$column_value = "value";
		$column_name = "name";
		
		$array = FALSE;
		if($this->pdo != null){
	
			$skip = ($cur_page - 1) * $limit;
			if($skip <0){
				$skip = 0;
			}
			$stop = $skip + $limit;
			if($stop>count($area_array)){
				$stop = count($area_array);
			}
			if($skip>$stop){
				$skip = $stop;
			}
				
			$index=0;
			for ($counter=$skip; $counter<$stop; $counter++){
				
				$sql = "SELECT COUNT(*) as $column_value".
						" FROM $this->table".
						" WHERE $this->column_stamp >= '$stamp_start'".
						" AND $this->column_stamp <= '$stamp_end'";
	
				if($model_array){
					for($i=0;$i<count($model_array);$i++){
						if ($i==0){
							$sql .= " AND ( $this->column_model_serial = $model_array[$i]";
						}
						else{
							$sql .= " OR $this->column_model_serial = $model_array[$i]";
						}
					}
					$sql .= ") ";
				}
				else{
					;
				}
				
				$area = $area_array[$counter];
				switch($area_level){
					case 1://province
						$sql .= " AND $this->column_province = '$area'";
						
						break;
						
					case 2://city
						$sql .= " AND $this->column_city = '$area'";
						
						break;
				}
				
	
				//echo $sql;
				$query = $this->pdo->query($sql);
				if($query){
					$row = $query->fetch();
					if ($row["$column_value"]>0) {
						$array[$index]["$column_name"] = $area;
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
	
}


?>
