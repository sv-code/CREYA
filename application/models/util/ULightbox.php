<?php
/**
 * ULightbox Class
 * 
 * Purpose
 * 		Common DB queries
 * Created 
 * 		Feb 16, 2009
 * 
 * @package		Lightbox
 * @subpackage	Models
 * @category		Util
 * @author		venksster
 * @link			TBD
 */

class ULightbox 
{
	/**
	* Constructor
	*
	* @access public
	*/
	public function ULightbox($module='util')
	{
		ULightbox::$log = clogger_return_instance('util');

	}
	
	/**
	* Returns $pid's $artist_dname 
	*
	* @type	GET
	* 
	* @access 	private
	* @param 	int 		$pid 		PHOTO_ID
	* @return 	array 							An array of shoeboxes 
	*/
	public function GetArtistDnameByPid($pid)
	{
		$CI = &get_instance();
		ULightbox::$log->fine('[MODEL] Looking up artist_dname for pid'.$pid);
		
		$CI->db->where('PHOTO_ID',$pid);
		return $CI->db->get_attribute('photo','artist_dname');
	}

	/**
   	* Returns all MIDs 
   	* 
   	* @access	public
   	* @param 	void
   	* @return	array 			
   	*/
	public function GetMIDs() 
	{
		$CI = &get_instance();
		ULightbox::$log->info('[MODEL] Returning MIDs');
		
		$CI->db->select('*');
		$mid_db = $CI->db->get('static_stat_metric');
		
		return $mid_db->result_array();
	}
	
	/**
   	* Returns all SIDs by MID 
   	* 
   	* @access	public
   	* @param 	int		$mid
   	* @return	array 			
   	*/
	public function GetSIDsByMID($mid)
	{
		$CI = &get_instance();
		ULightbox::$log->info('[MODEL] Returning SIDs for mid:'.$mid);
		
		$CI->db->select('*');
		$CI->db->where('MID',$mid);
		$sid_db = $CI->db->get('static_stat_suggestion');

		return $sid_db->result_array();
	}
	
	public function GetSIDs()
	{
		$CI = &get_instance();
		ULightbox::$log->info('[MODEL] Returning SIDs'); 
		
		$CI->db->select('*');
		$sid_db = $CI->db->get('static_stat_suggestion');

		return $sid_db->result_array();
	}
	
	public function GetMIDForSID($sid)
	{
		$CI = &get_instance();
		ULightbox::$log->info('[MODEL] Returning MID for SID:'.$sid);
		
		$CI->db->where('SID',$sid);
		return $CI->db->get_attribute('static_stat_suggestion','MID');
	}
	
	public function IsValidMID($mid)
	{
		$CI = &get_instance();
		ULightbox::$log->info('[MODEL] Validating MID:'.$mid);
		
		$CI->db->where('MID',$mid);
		$result = $CI->db->get_attribute('static_stat_metric','MID');

		if($result!=null)
		{
			ULightbox::$log->fine('[MODEL] Validated MID:'.$mid);
			return true;
		}
		
		return false;
	}	
	
	public function IsValidSID($sid)
	{
		$CI = &get_instance();
		ULightbox::$log->info('[MODEL] Validating SID:'.$sid);
		
		$CI->db->where('SID',$sid);
		$result = $CI->db->get_attribute('static_stat_suggestion','SID');
		
		if($result!=null)
		{
			ULightbox::$log->fine('[MODEL] Validated SID:'.$sid);
			return true;
		}
		
		return false;
	}
	
	/**
  	* @access private
  	* @static
  	*/  
  	private static $log;
	

}

/* End of file ULightbox.php */