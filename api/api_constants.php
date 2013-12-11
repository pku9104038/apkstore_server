<?php
/**
 * class API_CONSTANTS
 * 
 */

class API_CONSTANTS
{
	const API									= 'api';
    const API_RESP                              = 'api_resp';
    const API_RESP_ERR                          = 'api_resp_err';
    const API_RESP_MSG                          = 'api_resp_msg';
    const API_RESP_ARRAY                        = 'api_resp_array';
    const API_RESP_STAMP						= 'api_resp_stamp';
    const API_RESP_DEVSN						= 'dev_sn';
    
    const API_PARAM_APKINFO_LIST          		= 'apkinfo_list';
    
    const ONLINE_STATE_UNKNOWN                 	= 0;
    const ONLINE_STATE_APP_ONLINE              	= 1;
    const ONLINE_STATE_APK_ONLINE              	= 2;
    const ONLINE_STATE_APK_UPLOADED         	= 3;
    const ONLINE_STATE_ICON_UPLOAD           	= 4;
  /*  
    const PATH_APK                              = '../download/apk/';
    const PATH_UPLOAD                           = '../upload/';
    const PATH_ICON                             = '../download/icons/';
    const PATH_GUI                              = '../download/gui/';
*/
    
    const PATH_ROOT                             = '../';
    const PATH_APK                              = '../../Downloads/ApkStore/download/apk/';
    const PATH_UPLOAD                           = '../../Downloads/ApkStore/upload/';
    const PATH_ICON                             = '../../Downloads/ApkStore/download/icons/';
    const PATH_GUI                              = '../../Downloads/ApkStore/download/gui/';
    
	/*
	 * 
	 */
    const API_PARAM_APP_NAME					= "app_name";
    const API_PARAM_APP_PACKAGE					= "app_package";
    const API_PARAM_APP_VEZRCODE				= "app_vercode";
    const API_PARAM_APP_UPDATE_STAMP			= "api_param_app_update_stamp";
    const API_PARAM_APP_PUUP_STAMP				= "api_param_app_puup_stamp";
    const API_PARAM_APK_FILE_NAME				= "api_param_filename";
    const API_PARAM_APP_SERIAL					= "api_param_app_serial";
    const API_PARAM_APP_LABEL					= "api_param_app_label";
    
    
    const API_PARAM_DEV_SN						= 'dev_sn';
    const API_PARAM_DEV_IMEI					= 'dev_imei';
    const API_PARAM_DEV_BRAND					= 'dev_brand';
    const API_PARAM_DEV_MODEL					= 'dev_model';
    const API_PARAM_DEV_SDK						= 'dev_sdk';
    
    const API_PARAM_REPORT_PACKAGE				= "package";
    const API_PARAM_REPORT_VERCODE				= "version_code";
    const API_PARAM_REPORT_ACTION				= "action";
    const API_PARAM_REPORT_STAMP				= "stamp";
    const API_PARAM_REPORT_DATE					= 'date';
    const API_PARAM_REPORT_COUNT				= 'count';
    
    const API_PARAM_CUSTOMER_SERIAL				= 'customer_serial';
    const API_PARAM_BRAND_SERIAL				= 'brand_serial';
    const API_PARAM_MODEL_SERIAL				= 'model_serial';
    const API_PARAM_SUPPLIER_SERIAL				= 'supplier_serial';
    const API_PARAM_ROLE_ID						= 'role_id';
    
    
}
?>