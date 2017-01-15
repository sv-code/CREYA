<?php
/**
 * InviteModel Class
 * 
 * Interfaces
 * 
 * 		InviteModel::RequestInvite($artist_email,$artist_interest)
 *
 * Created 
 * 		Nov 14, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @author		venksster
 * @category	none
 * @link		TBD
 */	

define('INVITE_INVALIDATION_ID',-1);

require MODEL_BASE_PATH.'Abstract.LightboxModel'.EXT;

class InviteModel extends AbstractLightboxModel
{
	public function InviteModel($module='gateway')
	{
		parent::AbstractLightboxModel($module);
	}
	
	/**
	* Assertains the LIGHTBOX_STAGE 
	*
	* @type	PUT
	* 
	* @access	public
	* @param 	string		$artist_email
	* @return  	int
	*/
	public function GetArtistLightboxStage($artist_email)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Assertaining LIGHTBOX_STAGE for:'.$artist_email);
		
		if(!isset($artist_email))
		{
			throw new Exception('Cannot assertain LIGHTBOX_STAGE. Reason:$artist_email=null');
		}
				
		$this->log->fine('Querying for stage:LIGHTBOX_STAGE_MEMBER');
		$this->db->where('artist_email',$artist_email);
		$this->db->from('artist');
		if($this->db->count_all_results() > 0)
		{
		  	return LIGHTBOX_STAGE_MEMBER;
		}
		
		$this->db->where('artist_email',$artist_email);
		$INVITE_ID = $this->db->get_attribute('artist_request_invite','INVITE_ID');
		if($INVITE_ID==null)
		{
		  	return LIGHTBOX_STAGE_UNKNOWN;
		}
		else
		{
			if($INVITE_ID==INVITE_INVALIDATION_ID)
			{
				return LIGHTBOX_STAGE_MEMBER;
			}
			else if($INVITE_ID==0)
			{
				return LIGHTBOX_STAGE_PENDING_INVITE;
			}
			else
			{
				return LIGHTBOX_STAGE_INVITED;
			}
		}
									
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	public function GetInviteId($artist_email)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning invite_id for:'.$artist_email);
		
		if(!isset($artist_email))
		{
			throw new Exception('Cannot complete request. Reason:$artist_email=null');
		}
		
		$this->db->where('artist_email',$artist_email);
		$invite_id = $this->db->get_attribute('artist_request_invite','INVITE_ID');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $invite_id;
	}
	
	public function IsValidInvite($invite_id)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Verifying the validity of invite_id:'.$invite_id);
		
		if(!isset($invite_id))
		{
			throw new Exception('Cannot complete request. Reason:$invite_id=null');
		}
		
		$result=true;
		
		$this->db->where('INVITE_ID',$invite_id);
		$this->db->from('artist_request_invite');
		if($this->db->count_all_results() == 0)
		{
		  	$result=false;
		}
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result;
	}
	
	public function GetArtistEmail($invite_id)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning artist_email for invite_id:'.$invite_id);
		
		if(!isset($invite_id))
		{
			throw new Exception('Cannot complete request. Reason:$invite_id=null');
		}
		
		$this->db->where('INVITE_ID',$invite_id);
		$artist_email = $this->db->get_attribute('artist_request_invite','artist_email');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $artist_email;
	}
	
	public function DeleteInviteRequest($artist_email)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Deleting invite request for:'.$artist_email);
		
		if(!isset($artist_email))
		{
			throw new Exception('Cannot complete request. Reason:$artist_email=null');
		}
		
		$this->db->where('artist_email',$artist_email);
		//$this->db->delete('artist_request_invite');
		$this->db->update('artist_request_invite',array('INVITE_ID'=>INVITE_INVALIDATION_ID));
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
	* Adds an invite request
	*
	* @type	PUT
	* 
	* @access	public
	* @param 	string		$artist_email
	* @param  	string		$artist_interest 
	* @return  	void
	*/
	public function AddInviteRequest($artist_email,$artist_interest)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] New invite request from:'.$artist_email);
		
		if(!isset($artist_email) || !isset($artist_interest))
		{
			throw new Exception('[MODEL] artist_email or artist_interest cannot be null');	
		}
		
		$artist_data['artist_email'] 		= $artist_email;
		$artist_data['artist_interest'] 	= $artist_interest;
		$artist_data['request_date'] 		= $this->util->GetCurrentDateStamp();
		
		$this->db->insert('artist_request_invite',$artist_data);					
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
		
}

/* End of file InviteModel.php */
