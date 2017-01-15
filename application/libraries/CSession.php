<?php
/**
 * CSession Class
 * 
 * Purpose
 * 		Creates sessions for lightbox artists
 * Created 
 * 		March 04, 2009
 * 
 * @package	Lightbox
 * @subpackage	Libraries
 * @category	Logging
 * @author		venksster
 * @author		prakash
 * @link		TBD
 */

class CSession extends CI_Session
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function CSession()
	{
		parent::CI_Session();
		$this->log = clogger_return_instance('csession');
	}
	
	/**
	 * Checks for active session
	 * 
	 * @access	public
	 * @param	void
	 * @return	boolean 
	 */ 
	public function IsActive()
	{
		if($this->userdata('csession_active')==TRUE)
		{
			$this->log->debug('session_active==TRUE');
		    	return true;
		}
		  
		  $this->log->debug('session_active!=TRUE');
		  return false;
	}
	
	/**
	 * Creates a new CSession for the artist
	 * 
	 *
	 * @access	public
	 * @param	string $artist_dname
	 * @return	void
	 */
	public function Create($artist_dname)
	{
		$this->log->info('Attempting to creating CSession for artist:'.$artist_dname);
		
		if(!$this->IsActive())
		{
			$this->log->info('Creating CSession for artist:'.$artist_dname);
			
			$data = array
			(
				'artist_dname'  	=> $artist_dname,
				'csession_active'  	=> TRUE
			);
	
			$this->set_userdata($data);
		}
		else
		{
			$this->log->error('CSession already active for artist:'.$artist_dname);
		}
		
	}
	
	/**
	 * Destorys an active CSession
	 *
	 * @access	public
	 * @param	void
	 * @return	void
	 */
	public function Destroy()
	{
		if($this->IsActive())
		{
			$this->log->info('Destroying CSession for artist:'.$this->GetArtistDName());
			$this->sess_destroy();
		}
		else
		{
			$this->log->error('No CSession currently active');
		}
	}
	
	/**
	 * get userdata from the session
	 *
	 * @access	public
	 * @param	string $userdata
	 * @return				''/the value of the user-data-variable
	 */
	public function GetUserData($userdata)
	{
		$udata = $this->userdata($userdata);
		
		if($udata=='')
		{
			return null;
		}
		
		return $udata;
	}
	
	/**
	 * Returns the artist_dname whose CSession is currently active
	 *
	 * @access	public
	 * @param	void
	 * @return	string	$artist_dname		
	 */
	public function GetArtistDName()
	{
		return $this->GetUserData('artist_dname');
	}
	
	/**
	 * This function will append the session's userdata array with the array that is passed as a parameter. 
	 * Any existing keys in the session's userdata will be maintained as is, unless a key is being updated with a new value.
	 *
	 * @access	public
	 * @param	$userdata_array  	An associative array of key-value pairs
	 * @return	void
	 */
	public function SetUserData($userdata_array)
	{
		$this->set_userdata($userdata_array);
	}
  
  	/**
  	* @access private
  	*/  
  	private $log;
}
 
/* End of file CSession.php */


	