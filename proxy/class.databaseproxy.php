<?php
/**
 * DatabaseProxy - ApkStore database proxy class
 *     Connect to database, get a PDO instance for query operations
 * NOTE: 
 *     database connection config depend on '../conf/db_conf.xml'
 *
 * @package ApkStore
 * @author wangpeifeng
 */
//date_default_timezone_set('PRC');
class DatabaseProxy
{
    /////////////////////////////////////////////////
    // PROPERTIES, PUBLIC
    /////////////////////////////////////////////////
    private $pdo;
    private $connected;
    
    /////////////////////////////////////////////////
    // PROPERTIES, PRIVATE
    /////////////////////////////////////////////////
    
    /////////////////////////////////////////////////
    // PROPERTIES, PROTECTED
    /////////////////////////////////////////////////
    
    
    /////////////////////////////////////////////////
    // CONSTANTS
    /////////////////////////////////////////////////
    
    const DB_NAME                     = "apk_store";
    const CONF_DIR                    = '/conf';
    const CONF_FILE                   = '/db_conf.xml';

    //const string for columns name
    const STRING_EMAIL                = 'email';
    const STRING_REGISTER_DATE        = 'register_date';
    const STRING_UPDATE_TIME          = 'update_time';
    
    /////////////////////////////////////////////////
    // TABLES, AGENT_LIST
    /////////////////////////////////////////////////
    const DB_TABLE_AGENT_LIST               = 'agent_list';
    
    const DB_COLUMN_AGENT_SERIAL            = 'agent_serial';
    const DB_COLUMN_AGENT_NAME              = 'agent_name';
    const DB_COLUMN_AGENT_CONTACT           = 'agent_contact';
    const DB_COLUMN_CONTACT_EMAIL           = 'contact_email';
    const DB_COLUMN_CONTACT_PHONE           = 'contact_phone';
    const DB_COLUMN_AUDIT_URL               = 'audit_url';
    const DB_COLUMN_AUDIT_ACCOUNT           = 'audit_account';
    const DB_COLUMN_AUDIT_PWD               = 'audit_pwd';
    const DB_COLUMN_AGENT_REGISTER_DATE     = 'register_date';
    const DB_COLUMN_AGENT_UPDATE_TIME       = 'update_time';
    
    /////////////////////////////////////////////////
    // TABLES, ROLES
    /////////////////////////////////////////////////
    const DB_TABLE_ROLES                    = 'roles';
    
    const DB_COLUMN_ROLES_ID                = 'role_id';
    const DB_COLUMN_ROLE_NAME               = 'role_name';
    const DB_COLUMN_ROLE_UPDATE_TIME        = 'update_time';
    
    const DB_VALUE_ROLE_ID_ROOT             = 1;
    const DB_VALUE_ROLE_ID_CUSTOMER         = 7;
    const DB_VALUE_ROLE_ID_CUSTOMER_STATISTICS         = 9;
    const DB_VALUE_ROLE_NO_CUSTOMER         = '尚未授权';
    const DB_VALUE_ROLE_INNER_ACCOUNT       = '内部管理帐号';
    
    const DB_VALUE_ROLE_UNKNOWN             = '未知角色';
    const DB_VALUE_ROLE_ROOT                = '超级管理员';
    const DB_VALUE_ROLE_ADMIN               = '管理员';
    const DB_VALUE_ROLE_INFO_ADMIN          = '客户信息管理员';
    const DB_VALUE_ROLE_APP_ADMIN           = '应用仓库管理员';
    const DB_VALUE_ROLE_DATA_ADMIN          = '统计信息管理员';
    const DB_VALUE_ROLE_STATISTICS          = '信息统计员';
    const DB_VALUE_ROLE_CUSTOMER            = '客户操作员';
    const DB_VALUE_ROLE_AUDIT               = '结算稽核员';
    const DB_VALUE_ROLE_CUSTOMER_STATISTCS  = '客户统计员';
    
    /////////////////////////////////////////////////
    // TABLES, ACCOUNTS
    /////////////////////////////////////////////////
    const DB_TABLE_ACCOUNTS = 'accounts';
    
    const DB_COLUMN_ACCOUNT_SERIAL          = 'account_serial';
    const DB_COLUMN_ACCOUNT                 = 'account';
    const DB_COLUMN_ACCOUNT_CUSTOMER_SERIAL = 'customer_serial';
    const DB_COLUMN_ACCOUNT_ROLE_ID         = 'role_id';
    const DB_COLUMN_ACCOUNT_PASSWORD_SHA    = 'password_sha';
    const DB_COLUMN_ACCOUNT_EMAIL           = 'email';
    const DB_COLUMN_ACCOUNT_LOGIN_TIMES     = 'login_times';
    const DB_COLUMN_ACCOUNT_LOGIN_LATEST    = 'login_latest';
    const DB_COLUMN_ACCOUNT_REGISTER_DATE   = 'register_date';
    const DB_COLUMN_ACCOUNT_UPDATE_TIME     = 'update_time';
    
    /////////////////////////////////////////////////
    // TABLES, CUSTOMERS
    /////////////////////////////////////////////////
    const DB_TABLE_CUSTOMERS = 'customers';
    
    const DB_COLUMN_CUSTOMER_SERIAL         = 'customer_serial';
    const DB_COLUMN_CUSTOMER_ACCOUNT_SERIAL = self::DB_COLUMN_ACCOUNT_SERIAL;
    const DB_COLUMN_CUSTOMER                = 'customer';
    const DB_COLUMN_CUSTOMER_CONTACT        = 'contact';
    const DB_COLUMN_CUSTOMER_EMAIL          = self::STRING_EMAIL;
    const DB_COLUMN_CUSTOMER_PHONE          = 'phone';
    const DB_COLUMN_CUSTOMER_TYPE_ID        = 'type_id';
    const DB_COLUMN_CUSTOMER_NOTES          = 'notes';
    const DB_COLUMN_CUSTOMER_REGISTER_DATE  = self::STRING_REGISTER_DATE;
    const DB_COLUMN_CUSTOMER_UPDATE_TIME    = self::STRING_UPDATE_TIME;
    
    const DB_VALUE_CUSTOMER_TYPE_UNKNOWN    = '未知类型';
    const DB_VALUE_CUSTOMER_TYPE_BRAND      = '品牌厂商';
    const DB_VALUE_CUSTOMER_TYPE_ODM        = 'ODM厂商';
    const DB_VALUE_CUSTOMER_TYPE_CHANNEL    = '渠道商';
    const DB_VALUE_CUSTOMER_TYPE_OPEN    	= '开放';
    
    /////////////////////////////////////////////////
    // TABLES, BRANDS
    /////////////////////////////////////////////////
    const DB_TABLE_BRANDS                   = 'brands';
    
    const DB_COLUMN_BRAND_SERIAL            = 'brand_serial';
    const DB_COLUMN_BRAND                   = 'brand';
    const DB_COLUMN_BRAND_NOTES             = 'notes';
    const DB_COLUMN_BRAND_REGISTER_DATE     = 'register_date';
    const DB_COLUMN_BRAND_UPDATE_TIME       = 'update_time';
    const DB_COLUMN_BRAND_FILTERTYPE       	= 'filtertype';
     
    /////////////////////////////////////////////////
    // TABLES, MODEL
    /////////////////////////////////////////////////
    const DB_TABLE_MODELS                    = 'models';
    
    const DB_COLUMN_MODEL_SERIAL            = 'model_serial';
    const DB_COLUMN_MODEL                   = 'model';
    const DB_COLUMN_MODEL_CUSTOMER_SERIAL   = 'customer_serial';
    const DB_COLUMN_MODEL_BRAND_SERIAL      = 'brand_serial';
    const DB_COLUMN_MODEL_NOTES             = 'notes';
    const DB_COLUMN_MODEL_REGISTER_DATE     = 'register_date';
    const DB_COLUMN_MODEL_UPDATE_TIME       = 'update_time';
    const DB_COLUMN_MODEL_APKINFO_JSON      = 'apkinfo_json';
     
    /////////////////////////////////////////////////
    // TABLES, SUPPLIERS
    /////////////////////////////////////////////////
    const DB_TABLE_SUPPLIERS                = 'suppliers';
    
    const DB_COLUMN_SUPPLIER_SERIAL         = 'supplier_serial';
    const DB_COLUMN_SUPPLIER                = 'supplier';
    const DB_COLUMN_SUPPLIER_TYPE_ID        = 'type_id';
    const DB_COLUMN_SUPPLIER_CONTACT        = 'contact';
    const DB_COLUMN_SUPPLIER_EMAIL          = 'email';
    const DB_COLUMN_SUPPLIER_PHONE          = 'phone';
    const DB_COLUMN_SUPPLIER_NOTES          = 'notes';
    const DB_COLUMN_SUPPLIER_AUDIT_URL      = 'audit_url';
    const DB_COLUMN_SUPPLIER_AUDIT_ACCOUNT  = 'audit_account';
    const DB_COLUMN_SUPPLIER_AUDIT_PWD      = 'audit_pwd';
    const DB_COLUMN_SUPPLIER_REGISTER_DATE  = 'register_date';
    const DB_COLUMN_SUPPLIER_UPDATE_TIME    = 'update_time';
    const DB_COLUMN_SUPPLIER_CUSTOMER_SAERIL= 'customer_serial';
    
    const DB_VALUE_SUPPLIER_TYPE_UNKNOWN    = '未知';
    const DB_VALUE_SUPPLIER_TYPE_ORIGINAL   = '官方';
    const DB_VALUE_SUPPLIER_TYPE_AGENT      = '代理商';
    const DB_VALUE_SUPPLIER_TYPE_FREE       = '免费';
    
    /////////////////////////////////////////////////
    // TABLES, GROUPS
    /////////////////////////////////////////////////
    const DB_TABLE_GROUP                    = 'groups';
    
    const DB_COLUMN_GROUP_SERIAL            = 'group_serial';
    const DB_COLUMN_GROUP                   = 'group_name';
    const DB_COLUMN_GROUP_ICON              = 'icon';
    const DB_COLUMN_GROUP_PRIORITY          = 'priority';
    const DB_COLUMN_GROUP_NOTES             = 'notes';
    const DB_COLUMN_GROUP_REGISTER_DATE     = 'register_date';
    const DB_COLUMN_GROUP_UPDATE_TIME       = 'update_time';
    const DB_COLUMN_GROUP_CUSTOMER_SERIAL   = 'customer_serial';
    
    const DB_VALUE_GROUP_PROMOTION       	= 16;
    
    /////////////////////////////////////////////////
    // TABLES, ANDROIDAID GROUPS
    /////////////////////////////////////////////////
    const DB_TABLE_ANDROIDAID_GROUP                    = 'androidaid_groups';
    
    const DB_COLUMN_ANDROIDAID_GROUP_SERIAL            = 'group_serial';
    const DB_COLUMN_ANDROIDAID_GROUP                   = 'group_name';
    
    
    
    /////////////////////////////////////////////////
    // TABLES, CATEGORIES
    /////////////////////////////////////////////////
    const DB_TABLE_CATEGORIES               = 'categories';
    
    const DB_COLUMN_CATEGORY_SERIAL         = 'category_serial';
    const DB_COLUMN_CATEGORY                = 'category';
    const DB_COLUMN_CATEGORY_GROUP_SERIAL   = 'group_serial';
    const DB_COLUMN_CATEGORY_NOTES          = 'notes';
    const DB_COLUMN_CATEGORY_REGISTER_DATE  = 'register_date';
    const DB_COLUMN_CATEGORY_UPDATE_TIME    = 'update_time';
    const DB_COLUMN_CATEGORY_ANDROIDAID_GROUP_SERIAL   = 'androidaid_group_serial';
    const DB_COLUMN_CATEGORY_CUSTOMER_SERIAL= 'customer_serial';
    
    /////////////////////////////////////////////////
    // TABLES, APPLICATIONS
    /////////////////////////////////////////////////
    const DB_TABLE_APPLICATIONS                 = 'applications';
    
    const DB_COLUMN_APPLICATION_SERIAL          = 'application_serial';
    const DB_COLUMN_APPLICATION                 = 'application';
    const DB_COLUMN_APPLICATION_CATEGORY_SERIAL = 'category_serial';
    const DB_COLUMN_APPLICATION_IOCN            = 'icon';
    const DB_COLUMN_APPLICATION_PACKAGE         = 'package';
    const DB_COLUMN_APPLICATION_PRODUCER        = 'producer';
    const DB_COLUMN_APPLICATION_DESCRIPTION     = 'description';
    const DB_COLUMN_APPLICATION_NOTES           = 'notes';
    const DB_COLUMN_APPLICATION_ONLINE          = 'online';
    const DB_COLUMN_APPLICATION_REGISTER_DATE   = 'register_date';
    const DB_COLUMN_APPLICATION_UPDATE_TIME     = 'update_time';
    const DB_COLUMN_APPLICATION_PUUP_POINT      = 'puup_point';
    const DB_COLUMN_APPLICATION_PUUP_UPDATE_TIME= 'puup_update_time';
    const DB_COLUMN_APPLICATION_PROMOTION		= 'promotion';
    const DB_COLUMN_APPLICATION_CUSTOMER_ONLINE	= 'customer_online';
    const DB_COLUMN_APPLICATION_INTRODUCE		= 'introduce';
    
        
    /////////////////////////////////////////////////
    // TABLES, APKFILES
    /////////////////////////////////////////////////
    const DB_TABLE_APKFFILES                    = 'apkfiles';
    
    const DB_COLUMN_APKFILE_SERIAL              = 'apkfile_serial';
    const DB_COLUMN_APKFILE                     = 'apkfile';
    const DB_COLUMN_APKFILE_APPLICATION_SERIAL  = 'application_serial';
    const DB_COLUMN_APKFILE_SUPPLIER_SERIAL     = 'supplier_serial';
    const DB_COLUMN_APKFILE_VERSION_CODE        = 'version_code';
    const DB_COLUMN_APKFILE_VERSION_NAME        = 'version_name';
    const DB_COLUMN_APKFILE_SDK_MIN             = 'sdk_min';
    const DB_COLUMN_APKFILE_NOTES               = 'notes';
    const DB_COLUMN_APKFILE_CUSTOMER_SERIAL     = 'customer_serial';
    const DB_COLUMN_APKFILE_BRAND_SERIAL        = 'brand_serial';
    const DB_COLUMN_APKFILE_MODEL_SERIAL        = 'model_serial';
    const DB_COLUMN_APKFILE_ONLINE              = 'online';
    const DB_COLUMN_APKFILE_FILE_ORIGINAL       = 'file_original';
    const DB_COLUMN_APKFILE_REGISTER_DATE       = 'register_date';
    const DB_COLUMN_APKFILE_UPDATE_TIME         = 'update_time';
    const DB_COLUMN_APKFILE_SHA1	 	        = 'sha1';
    
    /////////////////////////////////////////////////
    // TABLES, DEVICE
    /////////////////////////////////////////////////
    const DB_TABLE_DEVICES	                    = 'devices';
    
    const DB_COLUMN_DEVICE_SERIAL               = 'device_serial';
    const DB_COLUMN_IMEI                     	= 'imei';
    const DB_COLUMN_DEV_MODEL_SERIAL  			= 'model_serial';
    const DB_COLUMN_SDK_LEVEL				    = 'sdk_level';
    const DB_COLUMN_REGISTER_PACKAGE        	= 'register_package';
    const DB_COLUMN_REGISTER_VERCODE        	= 'register_vercode';
    const DB_COLUMN_REGISTER_STAMP             	= 'register_stamp';
    const DB_COLUMN_REGISTER_IP               	= 'register_ip';
    const DB_COLUMN_REGISTER_PROVINCE		    = 'register_province';
    const DB_COLUMN_REGISTER_CITY		        = 'register_city';
    
    /////////////////////////////////////////////////
    // TABLES, PROMOTIONS
    /////////////////////////////////////////////////
    const DB_TABLE_PROMOTIONS	                = 'promotions';
    
    const DB_COLUMN_PROMOTION_SERIAL            = 'promotion_serial';
    const DB_COLUMN_PROM_APP_SERIAL             = 'application_serial';
    const DB_COLUMN_PROM_APK_SERIAL  			= 'apkfile_serial';
    const DB_COLUMN_PROM_USER_SERIAL			= 'user_serial';
    const DB_COLUMN_PROM_STATE        			= 'state';
    const DB_COLUMN_PROM_DATE        			= 'date';
    const DB_COLUMN_PROM_UPDATE_STAMP           = 'update_stamp';

    
    /////////////////////////////////////////////////
    // TABLES, ONBOARDS
    /////////////////////////////////////////////////
    const DB_TABLE_ONBOARDS	                	= 'onboards';
    
    const DB_COLUMN_ONBOARD_SERIAL            	= 'onboard_serial';
    const DB_COLUMN_ONBOARD_DEV_SERIAL          = 'device_serial';
    const DB_COLUMN_ONBOARD_COUNTS  			= 'counts';
    const DB_COLUMN_ONBOARD_DATE				= 'date';
    const DB_COLUMN_ONBOARD_REPORT_STAMP        = 'report_stamp';

    /////////////////////////////////////////////////
    // TABLES, ONLINES
    /////////////////////////////////////////////////
    const DB_TABLE_ONLINES	                	= 'onlines';
    
    const DB_COLUMN_ONLINE_SERIAL            	= 'online_serial';
    const DB_COLUMN_ONLINE_DEV_SERIAL           = 'device_serial';
    const DB_COLUMN_ONLINE_COUNTS  				= 'counts';
    const DB_COLUMN_ONLINE_DATE					= 'date';
    const DB_COLUMN_ONLINE_REPORT_STAMP        	= 'report_stamp';

    /////////////////////////////////////////////////
    // TABLES, CELL_ONLINES
    /////////////////////////////////////////////////
    const DB_TABLE_CELL_ONLINES	                = 'cell_onlines';
    
    const DB_COLUMN_CELL_ONLINE_SERIAL          = 'cell_online_serial';
    const DB_COLUMN_CELL_ONLINE_DEV_SERIAL      = 'device_serial';
    const DB_COLUMN_CELL_ONLINE_COUNTS  		= 'counts';
    const DB_COLUMN_CELL_ONLINE_DATE			= 'date';
    const DB_COLUMN_CELL_ONLINE_REPORT_STAMP    = 'report_stamp';

    /////////////////////////////////////////////////
    // TABLES, WLAN_ONLINES
    /////////////////////////////////////////////////
    const DB_TABLE_WLAN_ONLINES	                = 'wlan_onlines';
    
    const DB_COLUMN_WLAN_ONLINE_SERIAL          = 'wlan_online_serial';
    const DB_COLUMN_WLAN_ONLINE_DEV_SERIAL      = 'device_serial';
    const DB_COLUMN_WLAN_ONLINE_COUNTS  		= 'counts';
    const DB_COLUMN_WLAN_ONLINE_DATE			= 'date';
    const DB_COLUMN_WLAN_ONLINE_REPORT_STAMP    = 'report_stamp';
    
    /////////////////////////////////////////////////
    // TABLES, APP_ACTIVES
    /////////////////////////////////////////////////
    const DB_TABLE_APP_ACTIVES	                = 'app_actives';
    
    const DB_COLUMN_APP_ACTIVE_SERIAL           = 'app_active_serial';
    const DB_COLUMN_APP_ACTIVE_DEV_SERIAL       = 'device_serial';
    const DB_COLUMN_APP_ACTIVE_COUNTS  			= 'counts';
    const DB_COLUMN_APP_ACTIVE_DATE				= 'date';
    const DB_COLUMN_APP_ACTIVE_REPORT_STAMP     = 'report_stamp';
    
    /////////////////////////////////////////////////
    // TABLES, APP_GROUPS
    /////////////////////////////////////////////////
    const DB_TABLE_APP_GROUPS	                = 'app_groups';
    
    const DB_COLUMN_APP_GROUP_SERIAL            = 'app_group_serial';
    const DB_COLUMN_APP_GROUP_DEV_SERIAL       	= 'device_serial';
    const DB_COLUMN_APP_GROUP_COUNTS  			= 'counts';
    const DB_COLUMN_APP_GROUP_DATE				= 'date';
    const DB_COLUMN_APP_GROUP_REPORT_STAMP      = 'report_stamp';

    /////////////////////////////////////////////////
    // TABLES, WIDGET_GROUPS
    /////////////////////////////////////////////////
    const DB_TABLE_WIDGET_GROUPS	            = 'widget_groups';
    
    const DB_COLUMN_WIDGET_GROUP_SERIAL         = 'widget_group_serial';
    const DB_COLUMN_WIDGET_GROUP_DEV_SERIAL     = 'device_serial';
    const DB_COLUMN_WIDGET_GROUP_COUNTS  		= 'counts';
    const DB_COLUMN_WIDGET_GROUP_DATE			= 'date';
    const DB_COLUMN_WIDGET_GROUP_REPORT_STAMP   = 'report_stamp';
    
    
    /////////////////////////////////////////////////
    // TABLES, DOWNLOADS
    /////////////////////////////////////////////////
    const DB_TABLE_DOWNLOADS	                = 'downloads';
    
    const DB_COLUMN_DOWNLOAD_SERIAL             = 'download_serial';
    const DB_COLUMN_DOWNLOAD_DEV_SERIAL       	= 'device_serial';
    const DB_COLUMN_DOWNLOAD_PACKAGE  			= 'package';
    const DB_COLUMN_DOWNLOAD_VERCODE			= 'vercode';
    const DB_COLUMN_DOWNLOAD_ACTION				= 'action';
    const DB_COLUMN_DOWNLOAD_ACTION_STAMP		= 'action_stamp';
    const DB_COLUMN_DOWNLOAD_REPORT_STAMP       = 'report_stamp';
    
    /////////////////////////////////////////////////
    // TABLES, INSTALLS
    /////////////////////////////////////////////////
    const DB_TABLE_INSTALLS		                = 'installs';
    
    const DB_COLUMN_INSTALL_SERIAL             	= 'install_serial';
    const DB_COLUMN_INSTALL_DEV_SERIAL       	= 'device_serial';
    const DB_COLUMN_INSTALL_PACKAGE  			= 'package';
    const DB_COLUMN_INSTALL_VERCODE				= 'vercode';
    const DB_COLUMN_INSTALL_ACTION				= 'action';
    const DB_COLUMN_INSTALL_ACTION_STAMP		= 'action_stamp';
    const DB_COLUMN_INSTALL_REPORT_STAMP       	= 'report_stamp';
    
    /////////////////////////////////////////////////
    // TABLES, LAUNCHS
    /////////////////////////////////////////////////
    const DB_TABLE_LAUNCHS		                = 'launchs';
    
    const DB_COLUMN_LAUNCH_SERIAL             	= 'launch_serial';
    const DB_COLUMN_LAUNCH_DEV_SERIAL       	= 'device_serial';
    const DB_COLUMN_LAUNCH_PACKAGE  			= 'package';
    const DB_COLUMN_LAUNCH_VERCODE				= 'vercode';
    const DB_COLUMN_LAUNCH_STAMP				= 'launch_stamp';
    const DB_COLUMN_LAUNCH_REPORT_STAMP       	= 'report_stamp';
    
    /////////////////////////////////////////////////
    // TABLES, RUNNINGS
    /////////////////////////////////////////////////
    const DB_TABLE_RUNNINGS		                = 'runnings';
    
    const DB_COLUMN_RUNNING_SERIAL             	= 'running_serial';
    const DB_COLUMN_RUNNING_DEV_SERIAL       	= 'device_serial';
    const DB_COLUMN_RUNNING_PACKAGE  			= 'package';
    const DB_COLUMN_RUNNING_VERCODE				= 'vercode';
    const DB_COLUMN_RUNNING_COUNTS				= 'counts';
    const DB_COLUMN_RUNNING_DATE				= 'date';
    const DB_COLUMN_RUNNING_REPORT_STAMP       	= 'report_stamp';
    
    /////////////////////////////////////////////////
    // TABLES, PROVINCES
    /////////////////////////////////////////////////
    const DB_TABLE_PROVINCES		            = 'provinces';
    
    const DB_COLUMN_PROVINCE_SERIAL             = 'province_serial';
    const DB_COLUMN_PROVINCE       				= 'province';
    const DB_COLUMN_PROVINCE_DATE				= 'update_date';

    /////////////////////////////////////////////////
    // TABLES, CITIES
    /////////////////////////////////////////////////
    const DB_TABLE_CITIES		                = 'cities';
    
    const DB_COLUMN_CITY_SERIAL             	= 'city_serial';
    const DB_COLUMN_CITY_PROVINCE_SERIAL       	= 'province_serial';
    const DB_COLUMN_CITY  						= 'city';
    const DB_COLUMN_CITY_DATE					= 'update_date';
    
    /////////////////////////////////////////////////
    // TABLES, TRAFFICS
    /////////////////////////////////////////////////
    const DB_TABLE_TRAFFICS		                = 'traffics';
    
    const DB_COLUMN_TRAFFIC_SERIAL             	= 'traffic_serial';
    const DB_COLUMN_TRAFFIC_DEV_SERIAL       	= 'device_serial';
    const DB_COLUMN_TRAFFIC_DATE       			= 'date';
    const DB_COLUMN_TRAFFIC_MY_RX  				= 'my_rx';
    const DB_COLUMN_TRAFFIC_MY_TX  				= 'my_tx';
    const DB_COLUMN_TRAFFIC_MOBILE_RX  			= 'mobile_rx';
    const DB_COLUMN_TRAFFIC_MOBILE_TX  			= 'mobile_tx';
    const DB_COLUMN_TRAFFIC_TOTAL_RX  			= 'total_rx';
    const DB_COLUMN_TRAFFIC_TOTAL_TX  			= 'total_tx';
    const DB_COLUMN_TRAFFIC_STAMP       		= 'stamp';
    
    /////////////////////////////////////////////////
    // TABLES, BLACKLIST
    /////////////////////////////////////////////////
    const DB_TABLE_BLACKLIST		            = 'blacklist';
    
    const DB_COLUMN_BLACKLIST_SERIAL            = 'blacklist_serial';
    const DB_COLUMN_BLACKLIST_BRAND_SERIAL      = 'brand_serial';
    const DB_COLUMN_BLACKLIST_APP_SERIAL       	= 'application_serial';
    
    
    /////////////////////////////////////////////////
    // METHODS
    /////////////////////////////////////////////////
    /**
     * 
     * Constructure a new DatabaseProxy instance and connect to database 
     */	
    function __construct()
    {
        $this->pdo = null;
        $this->connected = false;
        $this->connectDatabase();
    }
    
    private function connectDatabase()
    {
        $result = false;
        
        date_default_timezone_set('Asia/Shanghai'); //系统时间差8小时问题
        
        $xml = simplexml_load_file(dirname(dirname(__FILE__)).self::CONF_DIR.self::CONF_FILE);
        $json = json_encode($xml);
        $obj = json_decode($json);
        
        $dsn = $obj->DB_DRIVER.
            'host='.$obj->DB_HOST.';'.
            'port='.$obj->DB_PORT.';'.
            'dbname='.self::DB_NAME;
        $user = $obj->DB_USER;
        $password = $obj->DB_PWD;
        
        Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                "dsn=$dsn, user=$user, pwd=$password" );
        
        try {
            $this->pdo = new PDO($dsn, $user, $password, 
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8';", 
                       PDO::ATTR_PERSISTENT => FALSE)
                );
            if ($this->pdo == NULL){
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__ , "PDO NULL!");
            }
            else{
                $this->connected = true;
                $result = true;
                $sql = "set time_zone='+8:00'";
                $query = $this->pdo->query($sql);
                Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__ );
            }
        }
        catch (PDOException $e) {
            $this->pdo = null;
            Log::i(__CLASS__.'::'.__FUNCTION__.'() Line:'.__LINE__.' at '.__FILE__,
                 print_r($e->getMessage(),TRUE) );
        }
        return $result;
    }
    /**
     * 
     * check the database connection
     * @return boolean
     */
    public function isConnected(){
        return $this->connected;
    }
    /**
     * 
     * Get the PDO object for database query
     * @return PDO
     */
    public function getPDO()
    {
        return $this->pdo;
    }
    /**
     * get constants of DB_VALUE_ACCOUNT_TYPE_NAME
     * @return Array<string>
     */
    public static function _DB_VALUE_ACCOUNT_ROLE_NAME()
    {
        $account_type_name = array(
            self::DB_VALUE_ROLE_UNKNOWN,
            self::DB_VALUE_ROLE_ROOT,
            self::DB_VALUE_ROLE_ADMIN,
            self::DB_VALUE_ROLE_INFO_ADMIN,
            self::DB_VALUE_ROLE_APP_ADMIN,
            self::DB_VALUE_ROLE_DATA_ADMIN,
            self::DB_VALUE_ROLE_STATISTICS,
            self::DB_VALUE_ROLE_CUSTOMER,
            self::DB_VALUE_ROLE_AUDIT,
        	self::DB_VALUE_ROLE_CUSTOMER_STATISTCS
        );
        return $account_type_name;
    }

    public static function _DB_VALUE_CUSTOMER_TYPE()
    {
        $array = Array(
            self::DB_VALUE_CUSTOMER_TYPE_UNKNOWN,
            self::DB_VALUE_CUSTOMER_TYPE_BRAND,
            self::DB_VALUE_CUSTOMER_TYPE_ODM,
            self::DB_VALUE_CUSTOMER_TYPE_CHANNEL,
            self::DB_VALUE_CUSTOMER_TYPE_OPEN
        );
        return $array;
    }
    
    public static function _DB_VALUE_SUPPLIER_TYPE()
    {
        $array = Array(
            self::DB_VALUE_SUPPLIER_TYPE_UNKNOWN,
            self::DB_VALUE_SUPPLIER_TYPE_ORIGINAL,
            self::DB_VALUE_SUPPLIER_TYPE_AGENT,
            self::DB_VALUE_SUPPLIER_TYPE_FREE
        );
        return $array;
    }
}	

?>