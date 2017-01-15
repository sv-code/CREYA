<?php
/**
 * CLogger Helper
 * 
 * Purpose
 * 		Returns a CLogger object
 * Created 
 * 		Nov 22, 2008
 * 
 * @package		Lightbox
 * @subpackage	Helpers
 * @category	Logging
 * @author		venksster
 * @link		TBD
 */

/**
 * Initializes the logger and returns a pointer to it
 * 
 * @access	public
 * @param	string $module
 * @return	CLogger 
 */ 
function clogger_return_instance($module)
{
	$logLevel 	= _get_log_level($module);
	$logFile  	= _get_log_file($module);
	
	$session_id = 'session-none';
	
	$CI = &get_instance();
	if(isset($CI->session))
	{
		$session_id = $CI->session->GetArtistDName();
		
		if($session_id==null)
		{
			$session_id='session-none';
		}
		else
		{
			$session_id='session-'.$session_id;
		}	
	}
		
	return new CLogger($logLevel,$logFile,$session_id);
}

/**
 * Returns the loglevel for a given module from config.php
 * 
 * priority order
 * 		1. greater of (global logging level AND module logging level) in config.php
 * 	 	2. CLogger::INFO 
 * 
 * @access	(private)
 * @param	string $module
 * @return	CLogger::${LEVEL}
 */ 
function _get_log_level($module)
{
	$CI = &get_instance();
	$globalLogLevel=$CI->config->item('loglevel_global');	
  
	if(($moduleLogLevel=$CI->config->item('loglevel_'.$module)))
	{
		if($globalLogLevel >= $moduleLogLevel)
		{
      		return $globalLogLevel;
		}
		else
		{
	  		return $moduleLogLevel;			
		}
	}
		
  	if(isset($globalLogLevel))
	{
  		return $globalLogLevel;
	}
	
	return CLogger::$INFO;
}

/**
 * Returns the logfile value for a given module from config.php
 * 
 * @access	(private)
 * @param	string $module
 * @return	string logfile (path+name) 
 */ 
function _get_log_file($module)
{
	$CI = &get_instance();
  	if(($logFile=$CI->config->item('logfile_'.$module)))
  	{
  		return $logFile;
  	}
  
	return null;
}
 
/* End of file clogger_helper.php */