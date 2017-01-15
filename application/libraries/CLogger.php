<?php
/**
 * CLogger Class
 * 
 * Purpose
 * 		Generic logger for Lightbox
 * Created 
 * 		Nov 16, 2008
 * 
 * @package		Lightbox
 * @subpackage	Libraries
 * @category	Logging
 * @author		venksster
 * @link		TBD
 */

/* SAMPLE USABILITY CODE
 * 
 * $log = new CLogger(CLogger::$INFO,"logfilename");
 * $log->info("this is an INFO level log and will be displayed");
 * $log->fine("this is a FINE level log and will NOT be displayed");
 * $log->fatal("this is a FATAL level log and will ALWAYS be displayed irrespective of log level");
 * 
 */

define("EOL","\n");
define("TAB","\t");
define("DTAB","\t\t");

class CLogger
{
	public static $FATAL 		= 00;
  	public static $ERROR		= 05;
  	public static $WARNING 	= 10;
  	public static $CONFIG 		= 20;
	public static $BENCHMARK 	= 25;
  	public static $INFO 		= 30;
  	public static $FINE 		= 40;
  	public static $DEBUG 		= 70;
  
  	public function __construct($level,$file,$log_segment_optional=null)
  	{
  		$this->_LOG_LEVEL 	= $level;
		$this->_LOG_FILE		= $file;
		$this->_LOG_SEGMENT = $log_segment_optional;
  	}

	/*
  	* Public interfaces for CLogger
  	* FATAL,ERROR,WARNING,CONFIG,INFO,FINE,DEBUG
  	*  
  	* @access	public
  	* @param	msg
  	* @return 	void
  	*/
  	public function fatal		($msg){										{$this->_logMsg("[FATAL]".DTAB.$msg);	return true;}			     }  
  	public function error		($msg){										{$this->_logMsg("[ERROR]".DTAB.$msg);	return true;}	return false;}
  	public function warning		($msg){if($this->_LOG_LEVEL>=CLogger::$WARNING) 		{$this->_logMsg("[WARNING]".TAB.$msg);	return true;}	return false;}
  	public function config 		($msg){if($this->_LOG_LEVEL>=CLogger::$CONFIG) 		{$this->_logMsg("[CONFIG]".DTAB.$msg);	return true;}	return false;}
	public function benchmark	($msg){if($this->_LOG_LEVEL>=CLogger::$BENCHMARK)	{$this->_logMsg("[BENCHMARK]".TAB.$msg);  	return true;}	return false;}
  	public function info		($msg){if($this->_LOG_LEVEL>=CLogger::$INFO)			{$this->_logMsg("[INFO]".DTAB.$msg);  	return true;}	return false;}
  	public function fine		($msg){if($this->_LOG_LEVEL>=CLogger::$FINE)			{$this->_logMsg("[FINE]".DTAB.$msg); 		return true;}	return false;}
  	public function debug		($msg){if($this->_LOG_LEVEL>=CLogger::$DEBUG) 		{$this->_logMsg("[DEBUG]".DTAB.$msg); 	return true;}	return false;}
  
	/**
  	* Writes the msg to the logfile
  	* 
  	* @access	private
  	* @param	string $msg
  	* @return 	bool :if the $msg is logged then TRUE is returned, else FALSE
  	*/
  	private function _logMsg($msg)
  	{
  		try
		{
			// opening/creating logfile in APPEND mode
			$ofstream = @fopen($this->_LOG_FILE,'a+');
			if(!is_resource($ofstream))
			{
			  throw new Exception("Log file ".$this->_LOG_FILE." could not be opened or created for writing. Check path or file permissions");
			}
			
			$timestamp = strftime("%x %X",time());
			$timestamp = '['.$timestamp.']';
			
			$log_prefix = $timestamp;
			if($this->_LOG_SEGMENT!=null)
			{
				$log_prefix = '['.$this->_LOG_SEGMENT.']'.$timestamp;
			}
			
			// locking logfile; 
			flock($ofstream,LOCK_EX);
			
			// flushing to logfile IF loglevel >=ERROR
			fwrite($ofstream,$log_prefix.TAB.$msg.EOL);
			if($this->_LOG_LEVEL<=CLogger::$ERROR)
			{
				fflush($ofstream);
			}
			
			// unlocking logfile
			flock($ofstream,LOCK_UN);
			
			// closing logfile
			fclose($ofstream);
		}	
		catch(Exception $e)
		{
			//echo $e->getMessage();	
		}
  	}
  
  	/**
  	* @access private
  	*/
  	private $_LOG_SEGMENT;
  
  	/**
  	* @access private
  	*/
  	private $_LOG_LEVEL;
	
	/**
  	* @access private
  	*/
  	private $_LOG_FILE;
  
}
 
/* End of file CLogger.php */


	