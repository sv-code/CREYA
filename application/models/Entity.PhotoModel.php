<?php
/**
 * PhotoEntityModel Class
 * 
 * Interfaces
 * 
 * 		AbstractLightboxEntityModel::Add				($artist_dname,$entity_required_data,$entity_optional_data=null);
 * 		AbstractLightboxEntityModel::Delete			($pid)
 * 		AbstractLightboxEntityModel::GetDetails			($pid)
 * 		AbstractLightboxEntityModel::GetViewCount		($pid)
 * 		AbstractLightboxEntityModel::IncrementViewCount	($pid)
 * 
 *   		ILightboxBookmarkable::GetBookmarkCount		($pid)	
 * 		ILightboxBookmarkable::IsBookmarked			($pid,$artist_dname)		
 *  		ILightboxBookmarkable::Bookmark			($pid,$artist_dname)
 * 		ILightboxBookmarkable::UnBookmark			($pid,$artist_dname)	
 *	 
 *  		ILightboxCommentable::GetCommentCount		($pid)	
 *  		ILightboxCommentable::GetComments			($pid)	
 * 		ILightboxCommentable::AddComment			($pid,$artist_dname,$comment_text,$sid=null)
 * 		ILightboxCommentable::DeleteComment			($comment_id)
 * 
 *   		ILightboxTagable::GetTags					($pid)
 * 		ILightboxTagable::AddTag					($pid,$tag)
 * 		ILightboxTagable::DeleteTag					($pid,$tag)
 *			
 * 		PhotoEntityModel::GetEXIFData				($pid)
 * 		
 * 		PhotoEntityModel::EditName				($pid,$photo_name)
 * 		PhotoEntityModel::EditDescription				($pid,$photo_desc)
 * 		PhotoEntityModel::EditPrimaryExifData			($pid,$photo_exif_data)
 * 		PhotoEntityModel::EditExifData				($pid,$photo_exif_data)
 *	 
 *  		PhotoEntityModel::IncrementViewCount			($pid)
 *  		
 * Created 
 * 		Nov 23, 2008
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @category	none
 * @author		venksster
 * @link		TBD
 */
require MODEL_BASE_PATH.'Abstract.Entity.LightboxCommentableAndBookmarkableModel'.EXT;
require MODEL_INTERFACE_PATH.'ILightboxCommentable'.EXT;
require MODEL_INTERFACE_PATH.'ILightboxTagable'.EXT;
require MODEL_INTERFACE_PATH.'ILightboxRatable'.EXT;

class PhotoEntityModel extends AbstractLightboxCommentableAndBookmarkableEntityModel implements ILightboxBookmarkable, ILightboxCommentable, ILightboxTagable
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function PhotoEntityModel($module='photo')
  	{
 		parent::AbstractLightboxCommentableAndBookmarkableEntityModel($module,$table_entity='photo',$column_entity_key='PHOTO_ID');
		
		$this->_PRIMARY_EXIF_KEY_ARRAY  = array('photo_exif_aperture','photo_exif_shutter','photo_exif_focal','photo_exif_iso');
	}
	
	/**
   	* Retrieves photo EXIF data
   	*
   	* @type	GET
   	* 
   	* @access	public
   	* @param	int 		$pid		PHOTO_ID 
   	* @return	array				photo exif data 	
   	*/
	public function GetEXIFData($pid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning photo exif data for photo:'.$pid);
		
		$this->db->select('photo_exif_focal,photo_exif_aperture,photo_exif_shutter,photo_exif_iso,photo_exif_camera,photo_exif_time,photo_exif_mm,photo_exif_wb,photo_exif_flash,photo_exif_sw');
		$this->db->where($this->COLUMN_ENTITY_KEY,$pid);
		
  		$photo_db = $this->db->get($this->TABLE_ENTITY,SINGLE_RESULT);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $photo_db->row_array();
	}
	
	/**
   	* @type	GET
   	* 
   	* @access	public
   	* @param	int 		$pid		PHOTO_ID 
   	* @return	array				array of tags 	
   	*/
	public function GetTags($pid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning tags for photo:'.$pid);
		
		$this->db->select('tag_name');
		$this->db->where($this->COLUMN_ENTITY_KEY,$pid);
		$tags_db = $this->db->get('photo_tag');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $tags_db->result_array();
	}
	
	
	
	/**
     	* @type	GET
     	* 
   	* @access	public
   	* @param	int 		$pid		PHOTO_ID 
   	* @return	array				array of ratings data
   	* @todo					CAN WE PLEASE MAINTAIN SOME CONSISTENCY BETWEEN THE IRatable implementations? 
   	*/
	/*
	public function GetAllMetricRatings($pid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning metric rating for photo:'.$pid);
		
		$this->db->select('photo_stat_metric.PHOTO_ID,photo_stat_metric.MID,photo_stat_metric.metric_rating,static_stat_metric.metric_name');
		$this->db->where($this->COLUMN_ENTITY_KEY,$pid);
		$this->db->from('photo_stat_metric');
		$this->db->join('static_stat_metric','static_stat_metric.MID = photo_stat_metric.MID');
		
		$ratings_db = $this->db->get();
			
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $ratings_db->result_array();
	}
	*/
	
	/**
     	* @type	GET
     	* 
   	* @access	public
   	* @param	int 		$pid		PHOTO_ID 
   	* @return	int				overall rating
   	*/
	/*
	public function GetMetricRating($pid,$mid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning metric rating data for mid:'.$mid.' for photo:'.$pid);
		
		$this->db->where(array
		(
			$this->COLUMN_ENTITY_KEY	=> $pid,
			'MID'					=> $mid
		));
		
		$result = $this->db->get_attribute('photo_stat_metric','metric_rating');
			
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result;
	}
	*/
	
	/**
     	* @type	GET
     	* 
   	* @access	public
   	* @param	int 		$pid		PHOTO_ID 
   	* @return	int				overall rating
   	*/
	/*
	public function GetOverallRating($pid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning overall rating data for photo:'.$pid);
		
		$this->db->where($this->COLUMN_ENTITY_KEY,$pid);
		$result = $this->db->get_attribute($this->TABLE_ENTITY,'photo_rating');
			
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result;
	}
	*/
	
	/**
     	* @type	GET
     	* 
   	* @access	public
   	* @param	int 		$pid		PHOTO_ID 
   	* @param	int		$sid		SID
   	* @return	int				suggestion_count
   	*/
	/*
	public function GetSuggestionCount($pid,$sid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning suggestion count for SID:'.$sid.' for pid:'.$pid);
		
		$this->db->where(array
		(
			$this->COLUMN_ENTITY_KEY	=> $pid,
			'SID'					=> $sid
		));
		
		$suggestion_count = $this->db->get_attribute('photo_stat_suggestion','suggestion_count');
		
		if($suggestion_count==null)
		{
			return 0;
		}
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $suggestion_count;
	}
	*/
	
	/**
     	* @type	GET
     	* 
   	* @access	public
   	* @param	int 		$pid		PHOTO_ID 
   	* @param	int		$sid		SID
   	* @return	array				array of suggestion_counts
   	*/
	/*
	public function GetAllSuggestionCounts($pid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning suggestion counts for pid:'.$pid);
		
		$suggestion_count_array = array();
		
		$sid_array = $this->UtilModel->getSIDs();
		foreach($sid_array as $sid_unit)
		{
			$sid = $sid_unit['SID'];
			$this->db->where(array
			(
				$this->COLUMN_ENTITY_KEY	=>	$pid,
				'SID'					=> 	$sid
			));
			
			$suggestion_count = (int)$this->db->get_attribute('photo_stat_suggestion','suggestion_count');
			
			if($suggestion_count > 0)
			{
				$suggestion_count_array[$sid] = $suggestion_count;
			}
		}
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $suggestion_count_array;
	}
	*/
	
	/**
     	* @type	GET
   	* 
   	* @access	public
   	* @param	int		$pid		PHOTO_ID 
   	* @return	int				number of times the photo:$pid has been rated	 			
   	*/
	/*
	public function GetRatingCount($pid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning #ratings for photo:'.$pid);
		
		$this->db->where($this->COLUMN_ENTITY_KEY,$pid);
		$result = $this->db->get_attribute('photo','photo_rate_count');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result;		
	}
	*/
	
	/**
     	* @type	GET
   	* 
   	* @access	public
   	* @param	int		$pid		PHOTO_ID 
   	* @return	bool				whether the photo:$pid has ever been rated	 			
   	*/
	/*
	public function IsRated($pid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Checking if photo has been rated:'.$pid);
		
		if($this->GetRatingCount($pid) > 0)
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
	*/
	
	/**
     	* @type	GET
   	* 
   	* @access	public
   	* @param	int		$pid			PHOTO_ID 
   	* @param	string		$artist_dname
   	* @return	bool					whether the photo:$pid has been rated by artist:$artist_dname	 			
   	*/
	/*
	public function IsRatedByArtist($pid,$artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Checking if artist:'.$artist_dname.' has rated photo:'.$pid);
		
		$this->db->where(array
		(
			$this->COLUMN_ENTITY_KEY		=>	$pid,
			'artist_dname'	=>	$artist_dname
		));
		
		$this->db->from('artist_rated_photo');
		if($this->db->count_all_results() > 0)
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
	*/
	
	/**
   	* @type	PUT
   	* 
   	* @access	public
   	* @param	int		$pid			PHOTO_ID on which the comment is made
   	* @param	string		$artist_dname	artist who wrote the comment
   	* @param	string		$comment_text	the comment
   	* @param	int 		$sid (optional)	if the comment is coming from the "Other Suggestions" (Ratings)
   	* @return	void 			
   	*/
	public function AddComment($pid,$artist_dname,$comment_text,$sid=null)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Adding new comment for photo:'.$pid.' from artist_dname:'.$artist_dname);
		
		$additional_data=null;
		if(isset($sid))
		{
			$additional_data['SID'] = $sid;
		}
		
		parent::AddComment($pid,$artist_dname,$comment_text,$additional_data);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
   	* @type	PUT
   	* 
   	* @access	public
   	* @param	string	$pid				PHOTO_ID that is rated
   	* @param	string	$artist_dname		artist who rated the photo
   	* @param	array	$metric_ratings		array(metric_rating) KEY:MID
   	* 								$metric_ratings[MID] = metric_rating
   	* 
   	* @param 	array 	$suggestions		array(SID)					
   	* @return	void 	
   	* @exception					throws if 1. MID invalid 2. SID invalid		
   	*/
	/*
	public function AddRating($pid,$artist_dname,$metric_ratings,$suggestions=null)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Adding new rating for photo:'.$pid);
		
		// Checking if artist has already rated this photo
		if(!$this->IsRatedByArtist($pid,$artist_dname))
		{
			$mids = $this->UtilModel->getMIDs();
			$this->_VerifyMetricRatingData($mids,$metric_ratings);
			
			// Checking if photo has ever been rated
			if(($num_ratings=$this->GetRatingCount($pid))==0)
			{
				// Inserting to db
				$this->_AddMetricRatingToDb($pid,$mids,$num_ratings,$metric_ratings);
			}
			else
			{
				$this->log->debug('[MODEL] #ratings for pid:'.$pid.'='.$num_ratings);
				
				$this->log->fine('[MODEL] Computing new metric ratings for pid:'.$pid);
				$metric_ratings = $this->_ComputeNewRatings($pid,$num_ratings,$metric_ratings);
				
				// Updating db
				$this->_UpdateMetricRatingInDb($pid,$mids,$num_ratings,$metric_ratings);
			}
			
			// Updating overall rating
			$this->_UpdateOverallRating($pid,$metric_ratings);
			
			if(isset($suggestions) && count($suggestions) > 0)
			{
				$this->_AddSuggestionCounts($pid,$suggestions);
			}
			else
			{
				$this->log->info('[MODEL] No Suggestions');
			}
			
			// Mark photo as rated by artist
			$this->_ArtistHasRated($pid,$artist_dname);
			
			// Add rating count
			$this->db->increment('photo','photo_rate_count',array($this->COLUMN_ENTITY_KEY=>$pid));
		}
		else
		{
			throw new Exception('[MODEL] Artist:'.$artist_dname.' has already rated photo:'.$pid);
		}
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	*/
	
	/**
     	* Adds a new photo
     	* 
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	string		$artist_dname
   	* @param	string		$photo_name
   	* @param	int		$photo_type
   	* @param	array		$photo_data (optional)		
      	* @return	void					 			
   	*/
	public function Add($artist_dname,$photo_data,$photo_optional_data=null)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Adding photo:'.print_r($photo_data,true));
		
		if(!isset($photo_data) || !is_array($photo_data) || !array_key_exists('PHOTO_ID',$photo_data) || !array_key_exists('photo_type_name',$photo_data))
		{
			throw new Exception('[MODEL] Cannot Add Photo: Reason:PHOTO_ID OR photo_type OR both data missing');
		}
		
		$this->log->fine('[MODEL] Mapping photo_type_name to photo_type');
		$photo_type_name = $photo_data['photo_type_name'];
		if($photo_type_name==null)
		{
			throw new Exception('[MODEL] photo_type_name=NULL');
		}
		
		$this->db->where('photo_type_name',$photo_type_name);
		$photo_type = $this->db->get_attribute('static_photo_type','photo_type');
		if($photo_type==null)
		{
			throw new Exception('[MODEL] Invalid photo_type_name:'.$photo_type_name);
		}
			
		$photo_data['photo_type'] = $photo_type;
		unset($photo_data['photo_type_name']);
		
		if(is_array($photo_optional_data))
		{
			$photo_data = array_merge($photo_data,$photo_optional_data);	
		}		
		
		$photo_data['artist_dname'] 	= $artist_dname;
		$photo_data['photo_date'] 	= $this->util->GetCurrentDateStamp();
		
		$this->db->insert($this->TABLE_ENTITY,$photo_data);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
     	* Edits EXIF data for a photo
     	* 
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	int 		$pid
   	* @param	array		$photo_exif_data	
	* @return	void					 			
   	*/
	public function EditExifData($pid,$photo_exif_data)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Editing EXIF data for photo:'.$pid);
		
		if(!isset($photo_exif_data) || !is_array($photo_exif_data))
		{
			throw new Exception('[MODEL] photo_exif_data MUST be an associative array');
		}
		
		$this->log->fine('[MODEL] Removing PRIMARY EXIF data for photo:'.$pid);
		foreach($photo_exif_data as $exif_key=>$exif_value)
		{
			if(array_search($exif_key,$this->_PRIMARY_EXIF_KEY_ARRAY)!=false)
			{
				$this->log->debug('[MODEL] Removing PRIMARY EXIF data:'.$exif_key);
				unset($photo_exif_data[$exif_key]);
			}
		}
				
		$this->log->debug('[MODEL] New EXIF data:'.print_r($photo_exif_data,true));
		
		$this->Edit($pid,$photo_exif_data);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
     	* Edits PRIMARY EXIF data for a photo
     	* 
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	int 		$pid
   	* @param	array		$photo_primary_exif_data	
	* @return	void					 			
   	*/
	public function EditPrimaryExifData($pid,$photo_primary_exif_data)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Editing PRIMARY EXIF data for photo:'.$pid);
		
		if(!isset($photo_primary_exif_data) || !is_array($photo_primary_exif_data))
		{
			$this->log->error('[MODEL] photo_primary_exif_data MUST be an associative array');
			throw new Exception('[MODEL] photo_primary_exif_data MUST be an associative array');
		}
		
		$this->log->fine('[MODEL] Removing non-PRIMARY EXIF data for photo:'.$pid);
		foreach($photo_primary_exif_data as $exif_key=>$exif_value)
		{
			$this->log->debug('[MODEL] exif_key:'.$exif_key);			
			if(!$this->cutil->ArraySearch($exif_key,$this->_PRIMARY_EXIF_KEY_ARRAY))
			{
				$this->log->debug('[MODEL] Removing non-PRIMARY EXIF data:'.$exif_key);
				unset($photo_primary_exif_data[$exif_key]);
			}			
		}
		
		foreach($this->_PRIMARY_EXIF_KEY_ARRAY as $exif_key)
		{
			$this->log->debug('[MODEL] Searching for primary exif:'.$exif_key);
			
			if(!array_key_exists($exif_key,$photo_primary_exif_data))
			{
				$this->log->error('[MODEL] Primary exif:'.$exif_key.' not found.');
				throw new Exception('[MODEL] Primary exif:'.$exif_key.' not found.');
			}
		}
		
		foreach($photo_primary_exif_data as $exif_key=>$exif_value)
		{
			if($photo_primary_exif_data[$exif_key]==0)
			{
				$this->log->error('[MODEL] Invalid value for exif:'.$exif_key.'. Primary exif data is NOT edited.');
				return;
			}
		}
				
		$this->log->debug('[MODEL] New PRIMARY EXIF data:'.print_r($photo_primary_exif_data,true));
		
		$this->Edit($pid,$photo_primary_exif_data);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
     	* Edits photo name
     	* 
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	int 		$pid
   	* @param	string		$photo_name	
	* @return	void					 			
   	*/
	public function EditName($pid,$photo_name)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Editing photo name for photo:'.$pid);
		
		if(!isset($photo_name))
		{
			$this->log->error('[MODEL] Cannot edit photo_name for photo:'.$pid.' REASON:$photo_name=null');
			return;
		}
		
		$photo_data = array('photo_name' => $photo_name);
		
		$this->Edit($pid,$photo_data);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
     	* Edits photo description
     	* 
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	int 		$pid
   	* @param	string		$photo_desc
	* @return	void					 			
   	*/
	public function EditDescription($pid,$photo_desc)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Editing photo description for photo:'.$pid);
		
		if(!isset($photo_desc))
		{
			$this->log->error('[MODEL] Cannot edit photo_desc for photo:'.$pid.' REASON:$photo_desc=null');
			return;
		}
		
		$photo_data = array('photo_desc' => $photo_desc);
		
		$this->Edit($pid,$photo_data);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	int		$pid		PHOTO_ID 
   	* @param	array		$tags
   	* @return	void					 			
   	*/
	public function AddTags($pid,$tags)
	{
		foreach($tags as $tag)
		{
			// @todo: this is inefficient
			$this->AddTag($pid,$tag);
		}
	}
	
	/**
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	int		$pid		PHOTO_ID 
   	* @param	string		$tag
   	* @return	void					 			
   	*/
	public function AddTag($pid,$tag)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Attempting to add new tag:'.$tag.'  for pid:'.$pid);
		
		if(strlen($tag) > MAX_TAG_LENGTH)
		{
			throw new Exception('[MODEL] Cannot add tag:'.$tag.' Reason:Max length exceeded');
		}
		
		/*
		$this->log->fine('[MODEL] Trimming tag for characters:'.TAGS_TRIM_CHARACTERS);
		$tag = trim($tag,TAGS_TRIM_CHARACTERS);
		*/
		
		$this->db->where(array
		(
			$this->COLUMN_ENTITY_KEY	=>	$pid,
			'tag_name'	=>	$tag
		));
		
		$tag_count = $this->db->count_all_results('photo_tag');
		
		// Checking if $pid is already tagged with $tag 
		if ($tag_count == 0)
		{
			$this->log->info('[MODEL] Adding new tag:'.$tag.'  for pid:'.$pid);
			
			$this->db->where($this->COLUMN_ENTITY_KEY,$pid);
			$artist_dname = $this->db->get_attribute('photo','artist_dname');
			
			if($artist_dname == null)
			{
				throw new Exception('[MODEL] Could not retrieve artist_dname for photo:'.$pid);
			}
			
			$data_db = array
			(
	        			'tag_name'		=> $tag,
	            		$this->COLUMN_ENTITY_KEY		=> $pid,
				'artist_dname'	=> $artist_dname,
	            	);

			try
			{
				$this->db->insert('photo_tag',$data_db);
			}
			catch(DBDriverException $e)
			{
				if(strstr($e,'Duplicate entry')==false)
				{
					$this->log->warning('[MODEL] Duplicate tag entry:'.$tag);
					throw $e;
				}				
			}
		}
		else
		{
			$this->log->error('[MODEL] Tag already exists:'.$tag.'  for pid:'.$pid);
		}
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	public function DeleteTag($pid,$tag)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Deleting tag:'.$tag.'  for pid:'.$pid);
		
		$where_db = array('PHOTO_ID'=>$pid,'tag_name'=>$tag);
		
		$this->db->where($where_db);
		$this->db->delete('photo_tag');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**-------------------------------------------------------------------------------------------------------------------*/
	
	/**
     	* @type	PUT
   	* 
   	* @access	private
   	* @param	int		$pid			PHOTO_ID 
   	* @param	string		$artist_dname
   	* @return	void	 			
   	*/
	public function _ArtistHasRated($pid,$artist_dname)
	{
		$this->log->info('[MODEL] Artist:'.$artist_dname.' has rated photo:'.$pid);
		
		$data_db = array
		(
			'PHOTO_ID'			=> 	$pid,
			'artist_dname'		=>	$artist_dname,
			'artist_rated_date'	=> 	$this->util->GetCurrentDateStamp()
		);
		
		$this->db->insert('artist_rated_photo',$data_db);
	}
	
	/**
      	* @type	GET
   	* 
   	* @access	private
   	* @param	array		$mids 
   	* @param	array		$ratings
      	* @return	void
      	* @exception		throws exception if $ratings cannot be verified					 			
   	*/
	private function _VerifyMetricRatingData($mids,$metric_ratings)
	{
		foreach($mids as $mid_unit)
		{
			$mid = $mid_unit['MID'];
			
			/*
			if(!UtilModel::IsValidMID($mid))
			{
				throw new Exception('[MODEL] Invalid MID:'.$mid);
			}
			*/
			
			if(!isset($metric_ratings[$mid]))
			{
				throw new Exception('[MODEL] _VerifyMetricRatingData: Rating data does not contain rating for mid:'.$mid);
			}
		}
	}
	
	/**
      	* @type	PUT
   	* 
   	* @access	private
   	* @param	int		$pid 
   	* @param	array		$mids
   	* @param	int		$num_ratings
   	* @param	array		$ratings
      	* @return	void
   	*/
	private function _AddMetricRatingToDb($pid,$mids,$num_ratings,$metric_ratings)
	{
		// INSERT
		foreach($mids as $mid_unit)			
		{
			$mid = $mid_unit['MID'];
			$metric_rating = $metric_ratings[$mid];
					
			$this->log->fine('[MODEL] Adding rating for metric:'.$mid.',m_rating:'.$metric_rating);
		
			$this->db->where($this->COLUMN_ENTITY_KEY,$pid);
			$artist_dname = $this->db->get_attribute('photo','artist_dname');
			
			if($artist_dname == null)
			{
				throw new Exception('[MODEL] Could not retrieve artist_dname for photo:'.$pid);
			}
		
			$data_db = array
			(
	        			$this->COLUMN_ENTITY_KEY				=> $pid,
	            		'MID'					=> $mid,
				'artist_dname'			=> $artist_dname,
	            		'metric_rating'			=> $metric_rating,
				'metric_num_ratings' 		=> ($num_ratings+1)
	       		);
	
			$this->db->insert('photo_stat_metric',$data_db);
		}
	}
	
	/**
      	* @type	GET
   	* 
   	* @access	private
   	* @param	int		$fgpid 
   	* @param	array		$mids
   	* @param	int		$num_ratings
   	* @param	array		$ratings
      	* @return	array		$new_ratings
   	*/
	private function _ComputeNewRatings($pid,$num_ratings,$metric_ratings)
	{
		$this->db->where($this->COLUMN_ENTITY_KEY,$pid);
		$photo_stat_metric_db = $this->db->get('photo_stat_metric');
		$photo_stat_metrics = $photo_stat_metric_db->result_array();
		
		$rating_precision	= (int)$this->config->item('rating_precision');
  		$this->log->config('[MODEL] Rating Precision: '.$rating_precision);
		
		$new_metric_ratings = array();
		foreach($photo_stat_metrics as $photo_stat_metric)
		{
    			$mid = $photo_stat_metric['MID'];
			$this->log->fine('[MODEL] Computing new metric ratings for mid:'.$mid);
			
			$current_metric_rating = $photo_stat_metric['metric_rating'];
			$this->log->debug('[MODEL] Current rating:'.$current_metric_rating);

			$user_metric_rating = $metric_ratings[$mid];
			$this->log->debug('[MODEL] User rating:'.$user_metric_rating);
			
			$new_metric_ratings[$mid] = round(((($current_metric_rating * $num_ratings) + $user_metric_rating) / ($num_ratings+1)),$rating_precision);
			$this->log->debug('[MODEL] New rating:'.$new_metric_ratings[$mid]);
		}
		
		return $new_metric_ratings;		
	}
	
	/**
      	* @type	PUT
   	* 
   	* @access	private
   	* @param	int		$pid 
   	* @param	array		$mids
   	* @param	int		$num_ratings
   	* @param	array		$new_ratings
      	* @return	void
   	*/
	private function _UpdateMetricRatingInDb($pid,$mids,$num_ratings,$new_metric_ratings)
	{
		foreach($mids as $mid_unit)	
		{
			$mid = $mid_unit['MID'];
			$metric_rating = $new_metric_ratings[$mid];
					
			$this->log->fine('[MODEL] Updating rating for metric:'.$mid.',metric_rating:'.$metric_rating);
		
			$where_db = array
			(
	        			$this->COLUMN_ENTITY_KEY 	=> $pid,
	            		'MID' 					=> $mid,
	        		);
		
			$data_db = array
			(
				'metric_rating' 		=> $metric_rating,
				'metric_num_ratings' 	=> ($num_ratings+1)
			);

			$this->db->where($where_db);
			$this->db->update('photo_stat_metric',$data_db);
		}
	}
	
	/**
      	* @type	PUT
   	* 
   	* @access	private
   	* @param	int		$pid 
      	* @return	void
   	*/
	private function _UpdateOverallRating($pid,$metric_ratings)
	{
		$aggregate = 0;
		foreach($metric_ratings as $mid=>$rating)
		{
			$aggregate+=$rating;
		}
		
		$overall_rating = doubleval($aggregate/count($metric_ratings));
		
		$data_db = array
		(
			'photo_rating' 		=> $overall_rating
		);

		$this->db->where($this->COLUMN_ENTITY_KEY,$pid);
		$this->db->update('photo',$data_db);
		
	}		
	
	/**
      	* @type	PUT
   	* 
   	* @access	private
   	* @param	int		$pid 
   	* @param	array		$suggestions
      	* @return	void
   	*/
	private function _AddSuggestionCounts($pid,$suggestions)
	{
		foreach($suggestions as $sid)
		{
			if(!$this->UtilModel->IsValidSID($sid))
			{
				throw new Exception('[MODEL] Invalid SID:'.$sid);
			}
			
			$mid = $this->UtilModel->GetMIDForSID($sid);
			$artist_dname = $this->UtilModel->GetArtistDnameByPid($pid);
			
			$data_db = array
			(
				$this->COLUMN_ENTITY_KEY 		=> $pid,
				'SID' 						=> $sid,
				'artist_dname'				=> $artist_dname,
				'MID'						=> $mid,
			);
			
			$suggestion_count = $this->GetSuggestionCount($pid,$sid);
			
			if($suggestion_count==0)
			{
				// INSERT
				$data_db['suggestion_count'] = 1;
				$this->db->insert('photo_stat_suggestion',$data_db);
			}
			else
			{
				// UPDATE
				$this->db->where($data_db);
				$data_db['suggestion_count'] = ++$suggestion_count;
				$this->db->update('photo_stat_suggestion',$data_db);
			}
		}	
	}
	
	/**
	* @access private
	*/
	private $_PRIMARY_EXIF_KEY_ARRAY;
}
 
/* End of file PhotoEntityModel.php */


	