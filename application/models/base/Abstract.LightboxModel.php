<?php
/**
 * AbstractLightboxModel Class
 * 
 * Definition
 * 		
 * 		An <AbstractLightboxModel> is a CI <Model> that contains a LOGGER and UTIL
 * 
 * Created 
 * 		Nov 16, 2008
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @category	base
 * @author		venksster
 * @link		TBD
 * @abstract
 */
if(!defined('ABSTRACT_LIGHTBOX_MODEL'))
{
	define('ABSTRACT_LIGHTBOX_MODEL',DEFINED);
	
	require MODEL_UTIL_PATH.'ULightbox'.EXT;
	
	abstract class AbstractLightboxModel extends Model
	{
		/**
	   	* Constructor: Initializes the CI Model, UtilModel, Cutil and the logger  
	   	* 
	   	* @access	protected
	   	* @param 	string $module		the log file generated would be the file pointed to by 'logfile_$module' specified in config.php
	   	* @return	void					
	   	*/
	  	protected function AbstractLightboxModel($module)
	  	{
	  		parent::Model();	
			$this->log = clogger_return_instance($module);
			
			$CI = &get_instance();
			$this->util = $CI->cutil;
			
			$this->UtilModel = new ULightbox();
		}
		
		/*
		protected function VerifyNull($args)
		{
			foreach($args as $arg)
			{
				if($arg==null)
				{
					throw new Exception('[MODEL] One of the arguments=NULL');
				}
				
				$this->log->config('[MODEL] Arg:'.$arg);
			}
		}
		*/
		
		/**
	  	* @access protected
	  	*/ 
		protected $log;
		
		/**
	  	* @access protected
	  	*/ 
		protected $util;
		
		/**
	  	* @access protected
	  	*/ 
		protected $UtilModel;
		
	}
} 

/* End of file AbstractLightboxModel.php */


	