<?php
/**
 * GroupEntityModel Class
 * 
 * Interfaces
 * 
 * 		AbstractLightboxEntityModel::Add				($artist_dname,$entity_required_data,$entity_optional_data=null);
 * 		AbstractLightboxEntityModel::Delete				($gid)
 * 		AbstractLightboxEntityModel::GetDetails			($gid)
 * 		AbstractLightboxEntityModel::GetViewCount		($gid)
 * 		AbstractLightboxEntityModel::IncrementViewCount	($gid)
 * 		
 * 		GroupEntityModel::GetCreator					($gid)
 * 		GroupEntityModel::GetSlideShowPics				($gid)
 * 		GroupEntityModel::EditDescription				($gid,$group_desc)
 *
 * 		GroupEntityModel::GetMostRecentlyAddedPhotos	($gid,$num_results)
 *  		GroupEntityModel::GetPhotoCount				($gid)
 *  		GroupEntityModel::IsPhotoAdded				($gid,$pid)
 *  		GroupEntityModel::AddPhoto					($gid,$pid)
 * 		GroupEntityModel::RemovePhoto				($gid,$pid)
 * 		
 *		GroupEntityModel::GetMostRecentMembers		($gid,$num_results)
 * 		GroupEntityModel::GetArtistCount				($gid)
 * 		GroupEntityModel::IsArtistMember				($gid,$artist_dname)
 * 		GroupEntityModel::AddArtist					($gid,$artist_dname)
 * 		GroupEntityModel::RemoveArtist				($gid,$artist_dname)
 * 		
 * 
 * Created 
 * 		Jan 24, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @category	none
 * @author		venksster
 * @link		TBD
 */
require MODEL_BASE_PATH.'Abstract.Entity.LightboxModel'.EXT;
 
class GroupEntityModel extends AbstractLightboxEntityModel
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function GroupEntityModel($module='group')
  	{
 		parent::AbstractLightboxEntityModel($module,$table_entity='group',$column_entity_key='GROUP_ID',$column_view_count='group_view_count');
	}
  
  	/**
   	* @type	GET
   	* 
   	* @access	private
   	* @param 	int		$gid			GROUP_ID
   	* @return	string		$artist_dname 	null if $gid not found		
   	*/
	public function GetCreator($gid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning creator for group:'.$gid);
		
		$this->db->where($this->COLUMN_ENTITY_KEY,$gid);
		$result = $this->db->get_attribute($this->TABLE_ENTITY,'artist_dname');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result;
	}
	
	/**
   	* Retrieves the latest members 
   	* 
   	* @type	GET
   	* 
   	* @access	public
   	* @param 	int		$gid			GROUP_ID
   	* @param 	int 		$num_results 	the number of members to retrieve
   	* @return	array 			
   	*/
	public function GetMostRecentMembers($gid,$num_results)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning details for group:'.$gid);
		
		$this->db->select('artist_dname,artist_g_join_date');
		$this->db->order_by('artist_g_join_date','desc');
		$this->db->where($this->COLUMN_ENTITY_KEY,$gid);
		
		$group_db = $this->db->get('group_artist',$num_results,OFFSET_NONE);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $group_db->result_array();
	}
		
	/**
   	* Retrieves all recently added pics
   	* 
   	* @type	GET
   	* 
   	* @access	public
   	* @param 	int		$gid			GROUP_ID
   	* @param 	void
   	* @return	array 			
   	*/
	public function GetMostRecentlyAddedPhotos($gid,$num_results)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_results.' recentlly added photos for group:'.$gid);
		
		$this->db->select('PHOTO_ID');
		$this->db->order_by('photo_g_add_date','desc');
		$this->db->where($this->COLUMN_ENTITY_KEY,$gid);
		
		$group_db = $this->db->get('group_photo',$num_results,OFFSET_NONE);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $group_db->result_array();
	}
	
	/**
     	* @type	GET
   	* 
   	* @access	public
   	* @param	int		$gid			GROUP_ID
   	* @param	int		$pid			PHOTO_ID 
     	* @return	bool					whether $gid has $pid 					 			
   	*/
	public function IsPhotoAdded($gid,$pid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Checking if photo:'.$pid.' is added to group:'.$gid);
		
		$this->db->where(array
		(
			'PHOTO_ID'		=>	$pid,
			$this->COLUMN_ENTITY_KEY		=> 	$gid,
		));
		
		$group_db = $this->db->get('group_photo',SINGLE_RESULT); 
		if ($group_db->num_rows() > 0)
		{
			$result = true;
		}
		else
		{
			$result = false;
		}
		
		$this->log->info('[MODEL] Result:'.$result);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result;
	}
	
	/**
     	* @type	GET
   	* 
   	* @access	public
   	* @param	int		$gid			GROUP_ID
   	* @param	int		$artist_dname 
     	* @return	bool					whether $artist_dname is a member of $gid  					 			
   	*/
	public function IsArtistMember($gid,$artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		
		if($artist_dname==null)
		{
			$this->log->error('[MODEL] artist_dname=NULL');
			return false;
		}
				
		$this->log->info('[MODEL] Checking if artist:'.$artist_dname.' is a member of group:'.$gid);
		
		$this->db->where(array
		(
			'artist_dname'	=>	$artist_dname,
			$this->COLUMN_ENTITY_KEY		=> 	$gid,
			
		));
		
		$group_db = $this->db->get('group_artist',SINGLE_RESULT); 
		if ($group_db->num_rows() > 0)
		{
			$result = true;
		}
		else
		{
			$result = false;
		}
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result;
	}
	
	/**
     	* @type	GET
     	* 
   	* @access	public
   	* @param	int 		$gid		GROUP_ID 
   	* @return	int				photo count
   	*/
	public function GetPhotoCount($gid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning photo count for group:'.$gid);
		
		$this->db->where($this->COLUMN_ENTITY_KEY,$gid);
		$result = $this->db->get_attribute($this->TABLE_ENTITY,'group_photo_count');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result;
	}
	
	/**
     	* @type	GET
     	* 
   	* @access	public
   	* @param	int 		$gid		GROUP_ID 
   	* @return	int				artist count
   	*/
	public function GetArtistCount($gid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning artist count for group:'.$gid);
		
		$this->db->where($this->COLUMN_ENTITY_KEY,$gid);
		$result = $this->db->get_attribute($this->TABLE_ENTITY,'group_artist_count');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result;
	}
	
	/**
     	* Adds a new group
     	* 
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	string		$artist_dname
   	* @param	string		$group_name
   	* @param	int		$group_desc
   	* @param	array		$group_data (optional)		
      	* @return	int							primary key of created group					 			
   	*/
	public function Add($artist_dname,$group_data,$group_optional_data=null)
	{
		$this->benchmark->Start(__METHOD__);
				
		if(!isset($group_data) || !is_array($group_data) || 
		!array_key_exists('group_name',$group_data) || 
		!array_key_exists('group_desc',$group_data)|| 
		!array_key_exists('group_preview_filename',$group_data)		)
		{
			throw new Exception('[MODEL] Cannot Add Group: Reason:group_name OR group_desc OR group_preview_filname OR all data missing');
		}
		
		$this->log->info('[MODEL] Creating new group:'.$group_data['group_name']);
		
		$data_db = array
		(
			'group_name'		=> 	$group_data['group_name'],
			'group_desc'		=>	$group_data['group_desc'],
			'group_preview_filename'	=> 	$group_data['group_preview_filename'],
			'artist_dname'		=>	$artist_dname,
			'group_date'		=>	$this->util->GetCurrentDateStamp(),
			'group_artist_count'	=> 	0
		);
		
		$this->db->insert($this->TABLE_ENTITY,$data_db);
		$key = mysql_insert_id();
		$this->log->info('KEY:'.$key);
		
		$this->log->fine('[MODEL] Adding artist as a member:'.$artist_dname);
		$this->AddArtist($key,$artist_dname);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $key;
	}
	
	/**
     	* Edits grop description
     	* 
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	int 		$gid
   	* @param	string		$group_desc
	* @return	void					 			
   	*/
	public function EditDescription($gid,$group_desc)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Editing group description for group:'.$gid);
		
		$group_data = array('group_desc' => $group_desc);
		
		$this->Edit($gid,$group_data);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
   	* Adds a photo to group:$gid
   	* 
   	* @type	PUT
   	* 
   	* @access	public
   	* @param 	int		$gid		GROUP_ID
   	* @param	int		$pid		PHOTO_ID
   	* @return	void 			
   	*/
	public function AddPhoto($gid,$pid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Adding photo:'.$pid.' to group:'.$gid);
		
		if(!$this->IsPhotoAdded($gid,$pid))
		{
			$data_db = array
			(
				'PHOTO_ID'			=> 	$pid,
				$this->COLUMN_ENTITY_KEY			=> 	$gid,
				'photo_g_add_date'	=>	$this->util->GetCurrentDateStamp()
			);
			
			$this->db->insert('group_photo',$data_db); 
			
			$this->log->fine('[MODEL] Adding to group photo count for pid:'.$pid);
			$this->db->increment('group','group_photo_count',array($this->COLUMN_ENTITY_KEY=>$gid));
		}
		else
		{
			$this->log->error('[MODEL] Photo:'.$pid.' already added to group:'.$gid);
		}		
	
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
   	* Adds a photo to group:$gid
   	* 
   	* @type	PUT
   	* 
   	* @access	public
   	* @param 	int		$gid		GROUP_ID
   	* @param	int		$pid		PHOTO_ID
   	* @return	void 			
   	*/
	public function RemovePhoto($gid,$pid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Removing photo:'.$pid.' from group:'.$gid);
		
		if($this->IsPhotoAdded($gid,$pid))
		{
			$data_db = array
			(
				'PHOTO_ID'			=> 	$pid,
				$this->COLUMN_ENTITY_KEY			=> 	$gid,
			);
			
			$this->db->delete('group_photo',$data_db); 
			
			$this->log->fine('[MODEL] Subtracting from group photo count for pid:'.$pid);
			$this->db->decrement('group','group_photo_count',array($this->COLUMN_ENTITY_KEY=>$gid));
		}
		else
		{
			$this->log->error('[MODEL] Photo:'.$pid.' NOT added to group:'.$gid);
		}		
	
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
   	* Adds an artist to group:$gid
   	* 
   	* @type	PUT
   	* 
   	* @access	public
   	* @param 	int		$gid			GROUP_ID
   	* @param	string		$artist_dname
   	* @return	void 			
   	*/
	public function AddArtist($gid,$artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Adding artist:'.$artist_dname.' to group:'.$gid);
		
		if(!$this->IsArtistMember($gid,$artist_dname))
		{
			$data_db = array
			(
				'artist_dname'		=> 	$artist_dname,
				$this->COLUMN_ENTITY_KEY			=> 	$gid,
				'artist_g_join_date'	=>	$this->util->GetCurrentDateStamp()
			);
			
			$this->db->insert('group_artist',$data_db); 
			
			$this->log->fine('[MODEL] Adding to group artist count for artist:'.$artist_dname);
			$this->db->increment('group','group_artist_count',array($this->COLUMN_ENTITY_KEY=>$gid));
		}
		else
		{
			$this->log->error('[MODEL] Artist:'.$artist_dname.' already a member of group:'.$gid);
		}		
	
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
   	* Adds a photo to group:$gid
   	* 
   	* @type	PUT
   	* 
   	* @access	public
   	* @param 	int		$gid			GROUP_ID
   	* @param	string		$artist_dname
   	* @return	void 			
   	*/
	public function RemoveArtist($gid,$artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Removing artist:'.$artist_dname.' from group:'.$gid);
		
		if($this->IsArtistMember($gid,$artist_dname))
		{
			$data_db = array
			(
				'artist_dname'		=> 	$artist_dname,
				$this->COLUMN_ENTITY_KEY			=> 	$gid,
			);
			
			$this->db->delete('group_artist',$data_db); 
			
			$this->log->fine('[MODEL] Subtracting from group artist count for artist_dname:'.$artist_dname);
			$this->db->decrement('group','group_artist_count',array($this->COLUMN_ENTITY_KEY=>$gid));
		}
		else
		{
			$this->log->error('[MODEL] Artist:'.$artist_dname.' NOT a member of group:'.$gid);
		}		
	
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**-------------------------------------------------------------------------------------------------------------------*/
	
}	
 
/* End of file GroupEntityModel.php */


	