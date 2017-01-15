<?php
/**
 * CExceptions Class
 * 
 * Purpose
 * 	Overrides CI's EXCEPTIONS class to handle 404s
 * 
 * Created 
 * 		Nov 18, 2009
 * 
 * @package	Lightbox
 * @subpackage	Libraries
 * @category	Exceptions
 * @author		venksster
 * @link		TBD
 */

class CExceptions extends CI_Exceptions
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function CExceptions()
	{
		parent::CI_Exceptions();
		//$this->log = clogger_return_instance('exceptions');
	}
	
	/**
	 * 404 Page Not Found Handler
	 *
	 * @access	private
	 * @param	string
	 * @return	string
	 */
	function show_404($page = '')
	{	
		/*
		$heading = "404 Page Not Found";
		$message = "The page you requested was not found.";

		log_message('error', '404 Page Not Found --> '.$page);
		echo $this->show_error($heading, $message, 'error_404');
		exit;
		*/
		//header("Location: error", TRUE, $http_response_code);
		header('Location: error/404');
	}
	
	/**
  	* @access private
  	*/  
  	private $log;
}
 
/* End of file CExceptions.php */


	