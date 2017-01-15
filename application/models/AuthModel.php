<?php
/**
 * AuthModel Class
 * 
 * Interfaces
 * 
 * 		AuthModel::IsAuthenticated	($artist_dname,$artist_password)
 *
 * Created 
 * 		Feb 16, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @author		prakash
 * @author		venksster
 * @category	none
 * @link		TBD
 */	
require EXCEPTION_PATH.'Exception.InvalidUser'.EXT; 
require MODEL_BASE_PATH.'Abstract.LightboxModel'.EXT;

class AuthModel extends AbstractLightboxModel
{
	public function AuthModel($module='auth')
	{
		parent::AbstractLightboxModel($module);
	}
	
	/**
	* Validates the artist's credentials
	*
	* @type	GET
	* 
	* @access	public
	* @param 	string		$artist_dname
	* @param  	string		$artist_password 		password in md5 hash 
	* @return   bool						true=authenticated
	*/
	public function IsAuthenticated($artist_dname,$artist_password)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Authenticating artist:'.$artist_dname);
		
		$this->db->select('artist_password');
		$this->db->where('artist_dname',$artist_dname);
		
		$login_details = $this->db->get('artist');
		if( $login_details->num_rows() == 0)
		{
			throw new InvalidUserException('[AUTHMODEL] Artist not found:'.$artist_dname);
		}
		
		// now authenticate the artist
		$artist_db = $login_details->row_array();
		if($artist_db['artist_password'] == $artist_password )
		{
			$this->log->info('[MODEL] Authenticated artist:'.$artist_dname);
			$result =  true;
		}
		else
		{
			$this->log->error('[MODEL] Authentication FAILED for artist:'.$artist_dname);
			$result =  false;	
		}
				
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result;
	}
}

/* End of file AuthModel.php */
