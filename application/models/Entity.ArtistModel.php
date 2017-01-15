<?php
/**
 * ArtistEntityModel Class
 * 
 * Interfaces
 * 
 * 		AbstractLightboxEntityModel::Add						($artist_dname,$entity_required_data,$entity_optional_data=null);
 * 		AbstractLightboxEntityModel::Delete						($artist_dname)
 * 		AbstractLightboxEntityModel::GetDetails					($artist_dname)
 * 		AbstractLightboxEntityModel::GetViewCount				($artist_dname)
 * 		AbstractLightboxEntityModel::IncrementViewCount			($artist_dname)
 * 		
 * 		ILightboxBookmarkable::GetPhotoBookmarkCount			($artist_dname)
 * 		ILightboxBookmarkable::GetArtistBookmarkCount			($artist_dname)
 * 		ILightboxBookmarkable::IsBookmarked					($bookmarked_artist_dname,$artist_dname)
 * 		ILightboxBookmarkable::Bookmark					($bookmarked_artist_dname,$artist_dname)	
 * 		ILightboxBookmarkable::UnBookmark					($bookmarked_artist_dname,$artist_dname)
 * 
 *  		ILightboxCommentable::GetCommentCount				($artist_dname)	
 *  		ILightboxCommentable::GetComments					($artist_dname)
 * 		ILightboxCommentable::AddComment					($for_artist_dname,$artist_dname,$comment_text,$comment_optional_arg=null)
 * 		ILightboxCommentable::DeleteComment					($comment_id)
 * 
 *		ILightboxRatable::IsRated							($artist_dname)
 * 		ILightboxRatable::IsRatedByArtist						($for_artist_dname,$artist_dname)
 *		ILightboxRatable::GetRatingCount						($artist_dname)
 * 		ILightboxRatable::GetOverallRating						($artist_dname)	
 * 		ILightboxRatable::GetMetricRating						($artist_dname,$mid)	
 * 		ILightboxRatable::GetAllMetricRatings					($artist_dname)
 * 		ILightboxRatable::GetSuggestionCount					($artist_dname,$mid)
 * 		ILightboxRatable::GetAllSuggestionCounts				($artist_dname)
 * 		ILightboxRatable::AddRating							($for_artist_dname,$artist_dname,$metric_ratings,$suggestions=null)
 * 	
 * 		ArtistEntityModel::GetPhotoTags						($artist_dname)
 * 		ArtistEntityModel::GetMostRecentlyBookmarkedPhotos		($artist_dname,$num_results)
 * 		ArtistEntityModel::GetMostBookmarkedPhotos			($artist_dname,$num_results)
 * 		ArtistEntityModel::GetMostRecentPhotoBookmarksByArtist	($artist_dname,$num_results)
 * 		ArtistEntityModel::GetMostRecentArtistBookmarksByArtist	($artist_dname,$num_results)
 * 		ArtistEntityModel::GetMostRecentlyCommentedPhotos		($artist_dname,$num_results)
 * 		ArtistEntityModel::GetMostCommentedPhotos				($artist_dname,$num_results)
 * 		ArtistEntityModel::GetMostRecentlyCommentedPhotosByArtist	($artist_dname,$num_results)
 * 		ArtistEntityModel::GetMostRecentlyJoinedGroups			($artist_dname,$num_results,$filter_keywords)
 *  
 * 		ArtistEntityModel::VerifyDName						($artist_dname)
 * 
 * 		ArtistEntityModel::EditAboutMe						($artist_dname,$about_me)
 * 		ArtistEntityModel::EditLocation						($artist_dname,$artist_location)
 * 		ArtistEntityModel::EditEmail							($artist_dname,$artist_email)
 * 		ArtistEntityModel::EditPassword						($artist_dname,$artist_password)
 * 		ArtistEntityModel::EditFocus							($artist_dname,$artist_location)
 * 		ArtistEntityModel::EditAvatar						($artist_dname,$artist_avatar)
 * 		ArtistEntityModel::UpdateArtistLoginTimeStamp			($artist_dname)
 * 		
 *  		ArtistEntityModel::GetGears							($artist_dname)
 *  		ArtistEntityModel::AddGear							($gear_name,$gear_type,$gear_man,$artist_dname)
 * 		ArtistEntityModel::DeleteGear						($gear_name,$gear_type,$gear_man,$artist_dname)
 * 		
 * Created 
 * 		Jan 26, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @author		prakash
 * @author		venksster
 * @category	none
 * @link		TBD
 */
require MODEL_BASE_PATH.'Abstract.Entity.LightboxCommentableAndBookmarkableModel'.EXT;
require MODEL_INTERFACE_PATH.'ILightboxBookmarkable'.EXT;
require MODEL_INTERFACE_PATH.'ILightboxCommentable'.EXT;
require MODEL_INTERFACE_PATH.'ILightboxRatable'.EXT;

class ArtistEntityModel extends AbstractLightboxCommentableAndBookmarkableEntityModel implements ILightboxBookmarkable, ILightboxRatable
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function ArtistEntityModel($module='artist')
	{
		parent::AbstractLightboxCommentableAndBookmarkableEntityModel($module,$table_entity='artist',$column_entity_key='artist_dname',$column_comment_key='for_artist_dname');
	}

	/**
	* Verify a display name 
	* 
	* @type	GET
   	* 
   	* @access	public
   	* @param 	string 	$aid
	* @return	void
	* @exception			throws an exception if the display name cannot be found
	*/	
	public function VerifyDName($artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Validating artist:'.$artist_dname);
		
		$this->db->from($this->TABLE_ENTITY);
		$this->db->where($this->COLUMN_ENTITY_KEY,$artist_dname);

		if($this->db->count_all_results() == 0)
		{
		  	throw new Exception('[MODEL] Artist not found:'.$artist_dname);
		}
		
		$this->log->info('[MODEL] Validated artist:'.$artist_dname);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}	
	
	/**
	* This function will return ALL TAGS associated with the artist's photos
	*
	* @type	GET
	* 
   	* @access	public
   	* @param 	string		$artist_dname 
   	* @return	array 					array of tags
   	*/
	public function GetPhotoTags($artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning photo tags for artist:'.$artist_dname);
		
		$this->db->select('tag_name');
		$this->db->where($this->COLUMN_ENTITY_KEY,$artist_dname);
		$this->db->distinct();
		$tags_db = $this->db->get('photo_tag');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $tags_db->result_array();
	}

	/**
	* This function will return the artist's photos that were recently bookmarked
	*
	* @type	GET
	* 
   	* @access	public
   	* @param 	int		$num_results
   	* @param 	string		$artist_dname 
   	* @return	array 					array of photos
   	*/
	public function GetMostRecentlyBookmarkedPhotos($artist_dname,$num_results)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_results.' Most Recently Bookmarked Photos for artist:'.$artist_dname);
		
		$this->db->select('photo.PHOTO_ID');
		$this->db->join('artist_bookmark_photo', 'artist_bookmark_photo.PHOTO_ID = photo.PHOTO_ID');
		$this->db->order_by('artist_bookmark_photo.bookmark_date','desc');
		$this->db->where('photo.artist_dname',$artist_dname);
		
		$photo_db = $this->db->get('photo',$num_results,OFFSET_NONE);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $photo_db->result_array();
	}
	
	/**
	* This function will return the artist's photos that were most bookmarked
	*
	* @type	GET
	* 
   	* @access	public
   	* @param 	int		$num_results
   	* @param 	string		$artist_dname 
   	* @return	array 					array of photos
   	*/
	public function GetMostBookmarkedPhotos($artist_dname,$num_results)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_results.' Most Bookmarked Photos for artist:'.$artist_dname);
		
		/* @todo: TEST THIS group_by,order_by clause*/
		$this->db->select('artist_bookmark_photo.PHOTO_ID,COUNT(bookmark_date) as "bookmark_count"');
		$this->db->join('photo', 'artist_bookmark_photo.PHOTO_ID = photo.PHOTO_ID');
		$this->db->group_by('artist_bookmark_photo.PHOTO_ID');
		$this->db->order_by('bookmark_count', 'desc');		
		
		$this->db->where(array
		(
			'photo.artist_dname'			=>	$artist_dname
		));
		
		$photo_db	= $this->db->get('artist_bookmark_photo',$num_results,OFFSET_NONE);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $photo_db->result_array();
	}
	
	/**
	* This function will return the photos that the artist recently bookmarked
	*
	* @type	GET
	* 
   	* @access	public
   	* @param 	int		$num_results
   	* @param 	string		$artist_dname 
   	* @return	array 					array of photos
   	*/
	/*
	public function GetMostRecentPhotoBookmarksByArtist($artist_dname,$num_results,$filter_keywords=FILTER_NONE)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_results.' Most Recent Photo Bookmarks by artist:'.$artist_dname);
		
		$this->db->select('artist_bookmark_photo.PHOTO_ID,bookmark_date');
		$this->db->order_by('bookmark_date','desc');
		$this->db->where('artist_bookmark_photo.artist_dname',$artist_dname);
		
		if($filter_keywords!=FILTER_NONE)
		{
			$this->db->join('photo', 'artist_bookmark_photo.PHOTO_ID = photo.PHOTO_ID');
			
			$filter_keyword_array = explode(WHITESPACE,$filter_keywords);
			$this->log->info('[MODEL] Filter keywords:'.print_r($filter_keyword_array,true));
			$this->db->or_like_in('photo_name',$filter_keyword_array);		
		}
		
		$bookmark_db = $this->db->get('artist_bookmark_photo',$num_results,OFFSET_NONE);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $bookmark_db->result_array();
	}
	*/
	
	/**
	* This function will return the artists that the artist recently bookmarked
	*
	* @type	GET
	* 
   	* @access	public
   	* @param 	int		$num_results
   	* @param 	string		$artist_dname 
   	* @return	array 					array of photos
   	*/
	public function GetMostRecentArtistBookmarksByArtist($artist_dname,$num_results,$filter_keywords=FILTER_NONE)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_results.' Most Recent Artist Bookmarks by artist:'.$artist_dname);
		
		$this->db->select('bookmarked_artist_dname as "artist_dname",bookmark_date,artist_avatar');
		$this->db->order_by('bookmark_date','desc');
		$this->db->where('artist_bookmark_artist.artist_dname',$artist_dname);
		
		$this->db->join('artist', 'artist_bookmark_artist.bookmarked_artist_dname = artist.artist_dname');
		
		if($filter_keywords!=FILTER_NONE)
		{
			$filter_keyword_array = explode(WHITESPACE,$filter_keywords);
			$this->log->info('[MODEL] Filter keywords:'.print_r($filter_keyword_array,true));
			$this->db->or_like_in('bookmarked_artist_dname',$filter_keyword_array);		
		}
		
		$bookmark_db = $this->db->get('artist_bookmark_artist',$num_results,OFFSET_NONE);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $bookmark_db->result_array();
	}

	/**
	* This function will return the artist's photos that were recently commented on
	*
	* @type	GET
	* 
   	* @access	public
   	* @param 	int		$num_results
   	* @param 	string		$artist_dname 
   	* @return	array 					array of photos
   	*/
	public function GetMostRecentlyCommentedPhotos($artist_dname,$num_results)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_results.' Most Recently Commented Photos for artist:'.$artist_dname);
		
		$this->db->select('photo.PHOTO_ID');
		$this->db->join('photo_comment', 'photo_comment.PHOTO_ID = photo.PHOTO_ID');
		$this->db->order_by('photo_comment.comment_date','desc');
		$this->db->where('photo.artist_dname',$artist_dname);
		
		$photo_db = $this->db->get('photo',$num_results,OFFSET_NONE);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $photo_db->result_array();
	}
	
	/**
	* This function will return the artist's photos that have the most comments
	*
	* @type	GET
	* 
   	* @access	public
   	* @param 	int		$num_results
   	* @param 	string		$artist_dname 
   	* @return	array 					array of photos
   	*/
	public function GetMostCommentedPhotos($artist_dname,$num_results)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_results.' Most Commented Photos for artist:'.$artist_dname);
		
		$this->db->select('PHOTO_ID');
		$this->db->order_by('photo_comment_count','desc');
		
		$this->db->where(array
		(
			'photo_comment_count >' 	=>	0,
			'artist_dname'			=>	$artist_dname
		));
		
		$photo_db	= $this->db->get('photo',$num_results,OFFSET_NONE);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $photo_db->result_array();
	}
	
	/**
	* This function will return the photos that the artist recently commented on
	*
	* @type	GET
	* 
   	* @access	public
   	* @param 	int		$num_results
   	* @param 	string		$artist_dname 
   	* @return	array 					array of photos
   	*/
	public function GetMostRecentlyCommentedPhotosByArtist($artist_dname,$num_results)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_results.' Most Recently Commented Photos by artist:'.$artist_dname);
		
		$this->db->select('PHOTO_ID,comment_date');
		$this->db->order_by('comment_date','desc');
		$this->db->distinct();
		$this->db->where('artist_dname',$artist_dname);
				
		$comment_db = $this->db->get('photo_comment',$num_results,OFFSET_NONE);
				
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $comment_db->result_array();
	}
	
	/**
	* This function will return the artist's recently joined groups
	*
	* @type	GET
	* 
   	* @access	public
   	* @param 	int		$num_results
   	* @param 	string		$artist_dname 
   	* @return	array 					array of photos
   	*/
	public function GetMostRecentlyJoinedGroups($artist_dname,$num_results,$filter_keywords)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_results.' Most Recently Joined Groups for artist:'.$artist_dname);
		
		$this->db->select('*');
		$this->db->join('group_artist','group_artist.GROUP_ID=group.GROUP_ID');
		$this->db->where('group_artist.artist_dname',$artist_dname);
		$this->db->orderby('group_artist.artist_g_join_date','desc');
		
		if($filter_keywords!=FILTER_NONE)
		{
			$filter_keyword_array = explode(WHITESPACE,$filter_keywords);
			$this->log->info('[MODEL] Filter keywords:'.print_r($filter_keyword_array,true));
			$this->db->or_like_in('group_name',$filter_keyword_array);		
		}
		
		$group_db = $this->db->get('group',$num_results,OFFSET_NONE);
				
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $group_db->result_array();
	}

	/**
     	* @type	GET
   	* 
   	* @access	public
   	* @param	string		$bookmarked_artist_dname		
   	* @param	string		$artist_dname
   	* @return	bool						whether $bookmarked_artist_dname has been bookmarked by $artist_dname					 			
   	*/
	public function IsBookmarked($bookmarked_artist_dname,$artist_dname)
	{
		$this->log->info('[MODEL] Checking if b_artist '.$bookmarked_artist_dname.' is bookmarked by artist '.$artist_dname);
		
		$this->db->where(array
		(
			'bookmarked_artist_dname'	=>	$bookmarked_artist_dname,
			'artist_dname'	=>	$artist_dname
		));
		
		$this->db->from('artist_bookmark_artist');
		if($this->db->count_all_results() > 0)
		{
			return true;
		}
		
		return false;
	}
	
	/**
     	* @type	GET
     	* 
   	* @access	public
   	* @param	string 	$artist_dname		
   	* @return	int		bookmark count
   	*/
	/*
	public function GetPhotoBookmarkCount($artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Retrieving PHOTO bookmark count for artist:'.$artist_dname);
		
		$this->db->where('artist_dname',$artist_dname);
		$this->db->from('artist_bookmark_photo');
		$bookmark_count = $this->db->count_all_results();
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $bookmark_count;
	}
	*/
	
	/**
     	* @type	GET
     	* 
   	* @access	public
   	* @param	string 	$artist_dname		
   	* @return	int		bookmark count
   	*/
	public function GetArtistBookmarkCount($artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Retrieving ARTIST bookmark count for artist:'.$artist_dname);
		
		$this->db->where('artist_dname',$artist_dname);
		$this->db->from('artist_bookmark_artist');
		$bookmark_count = $this->db->count_all_results();
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $bookmark_count;
	}
	
	/**
     	* Adds a new artist
     	* 
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	string		$artist_dname
   	* @param	string		$artist_email
   	* @param	string		$artist_password
   	* @param	array		$artist_data (optional)		
      	* @return	void					 			
   	*/
	public function Add($artist_dname,$artist_data,$artist_optional_data=null)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Adding new artist:'.$artist_dname);
		
		if(!isset($artist_data) || !is_array($artist_data) || !array_key_exists('artist_email',$artist_data) || !array_key_exists('artist_password',$artist_data) || !array_key_exists('artist_location',$artist_data) || !array_key_exists('artist_focus',$artist_data) || !is_array($artist_data['artist_focus']))
		{
			throw new Exception('[MODEL] Cannot Add Artist: Reason:artist_email OR artist_password OR artist_focus OR artist_location missing');
		}
		
		$this->log->debug('[MODEL] $artist_data:'.print_r($artist_data,true));
		
		$artist_data['artist_focus'] = implode(ARTIST_FOCUS_DELIMITER,$artist_data['artist_focus']);
		$this->log->debug('[MODEL] artist_focus:'.$artist_data['artist_focus']);
										
		if(is_array($artist_optional_data))
		{
			$artist_data = array_merge($artist_data,$artist_optional_data); 
		}
		
		$artist_data['artist_dname'] 		= $artist_dname;
		$artist_data['artist_join_date'] 	= $this->util->GetCurrentDateStamp();
		
		$this->db->insert($this->TABLE_ENTITY,$artist_data);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
     	* Edits artist 'About Me'
     	* 
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	string		$artist_dname
   	* @param	string		$about_me
	* @return	void					 			
   	*/
	public function EditAboutMe($artist_dname,$about_me,$reset=false)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Updating "About Me" for artist:'.$artist_dname);
		
		if($reset==false && !isset($about_me))
		{
			$this->log->error('[MODEL] Cannot edit about_me for artist:'.$artist_dname.' REASON:$about_me=null');
			return;
		}
		
		if($reset==true)
		{
			$this->log->fine('[MODEL] Resetting about_me to NULL');
			$about_me=null;
		}
						
		$artist_data = array('artist_about_me' => $about_me);
		
		$this->Edit($artist_dname,$artist_data,ALLOW_NULL);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
     	* Edits artist 'location'
     	* 
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	string		$artist_dname
   	* @param	string		$artist_location
	* @return	void					 			
   	*/
	public function EditLocation($artist_dname,$artist_location)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Updating location for artist:'.$artist_dname);
		
		if(!isset($artist_location))
		{
			$this->log->error('[MODEL] Cannot edit location for artist:'.$artist_dname.' REASON:$artist_location=null');
			return;
		}
		
		$artist_data = array('artist_location' => $artist_location);
		
		$this->Edit($artist_dname,$artist_data);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
     	* Edits artist 'email'
     	* 
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	string		$artist_dname
   	* @param	string		$artist_email
	* @return	void					 			
   	*/
	public function EditEmail($artist_dname,$artist_email)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Updating email for artist:'.$artist_dname);
		
		if(!isset($artist_email))
		{
			$this->log->error('[MODEL] Cannot edit email for artist:'.$artist_dname.' REASON:$artist_email=null');
			return;
		}
		
		$artist_data = array('artist_email' => $artist_email);
		
		$this->Edit($artist_dname,$artist_data);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
     	* Edits artist 'password'
     	* 
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	string		$artist_dname
   	* @param	string		$artist_password
	* @return	void					 			
   	*/
	public function EditPassword($artist_dname,$artist_password)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Updating email for artist:'.$artist_dname);
		
		if(!isset($artist_password))
		{
			$this->log->error('[MODEL] Cannot edit password for artist:'.$artist_dname.' REASON:$artist_password=null');
			return;
		}
		
		$artist_data = array('artist_password' => $artist_password);
		
		$this->Edit($artist_dname,$artist_data);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
     	* Edits artist 'focus'
     	* 
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	string		$artist_dname
   	* @param	string		$artist_focus
	* @return	void					 			
   	*/
	public function EditFocus($artist_dname,$artist_focus)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Updating focus for artist:'.$artist_dname);
		
		if(!isset($artist_focus))
		{
			$this->log->error('[MODEL] Cannot edit focus for artist:'.$artist_dname.' REASON:$artist_focus=null');
			return;
		}
		
		$artist_focus = implode(ARTIST_FOCUS_DELIMITER,$artist_focus);
		$this->log->debug('[MODEL] artist_focus:'.$artist_focus);
		
		$artist_data = array('artist_focus' => $artist_focus);
		
		$this->Edit($artist_dname,$artist_data);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
     	* Edits artist 'avatar'
     	* 
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	string		$artist_dname
   	* @param	string		$artist_avatar
	* @return	void					 			
   	*/
	public function EditAvatar($artist_dname,$artist_avatar)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Updating avatar for artist:'.$artist_dname);
		
		if(!isset($artist_avatar))
		{
			$this->log->error('[MODEL] Cannot edit avatar for artist:'.$artist_dname.' REASON:$artist_avatar=null');
			return;
		}
		
		$artist_data = array('artist_avatar' => $artist_avatar);
		
		$this->Edit($artist_dname,$artist_data);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}

	/**
     	* Updates the artist 'last_login' time to current time
     	* 
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	string		$artist_dname		
      	* @return	void					 			
   	*/
	public function UpdateArtistLoginTimeStamp($artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Updating artist login timestamp for artist:'.$artist_dname);
		
		$artist_data = array('artist_last_login' => $this->util->getCurrentDateStamp());
		
		$this->Edit($artist_dname,$artist_data);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));		
	}
	
	/**
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	string		$bookmarked_artist_dname		
   	* @param	string		$artist_dname
   	* @return	void					 			
   	*/
	public function Bookmark($bookmarked_artist_dname,$artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Attempting to bookmark b_artist:'.$bookmarked_artist_dname.' for artist '.$artist_dname);
		
		if(!$this->IsBookmarked($bookmarked_artist_dname,$artist_dname))
		{
			$this->log->info('[MODEL] Bookmarking b_artist '.$bookmarked_artist_dname.' for artist '.$artist_dname);
			
			$data_db = array
			(
				'bookmarked_artist_dname'	=>	$bookmarked_artist_dname,
				'artist_dname'	=>	$artist_dname,
				'bookmark_date' 	=> 	$this->util->GetCurrentDateStamp()
			);
			
			$this->db->insert('artist_bookmark_artist',$data_db);	
			
			/*
			$this->log->fine('[MODEL] Adding to bookmark count for b_artist:'.$bookmarked_artist_dname);
			$this->db->increment('artist','artist_bookmark_count',array('artist_dname'=>$bookmarked_artist_dname));
			*/
		}
		else
		{
			$this->log->error('[MODEL] Bookmark already exists for artist:'.$bookmarked_artist_dname.' by artist:'.$artist_dname);
		}		
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	int		$pid		PHOTO_ID 
   	* @param	string		$artist_dname
   	* @return	void					 			
   	*/
	public function UnBookmark($bookmarked_artist_dname,$artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Attempting to unbookmark artist '.$bookmarked_artist_dname.' for artist '.$artist_dname);
		
		if($this->IsBookmarked($bookmarked_artist_dname,$artist_dname))
		{
			$this->log->info('[MODEL] UnBookmarking b_artist '.$bookmarked_artist_dname.' for artist '.$artist_dname);

			$this->db->where(array
			(
				'bookmarked_artist_dname' 	=> 	$bookmarked_artist_dname, 
				'artist_dname' 	=> 	$artist_dname
			));
			
			$this->db->delete('artist_bookmark_artist');
			
			/*
			$this->log->fine('[MODEL] Subtracting from  bookmark count for artist:'.$bookmarked_artist_dname);
			$this->db->decrement('artist','artist_bookmark_count',array('artist_dname'=>$bookmarked_artist_dname));
			*/
		}
		else
		{
			$this->log->error('[MODEL] Bookmark does not exist for b_artist:'.$bookmarked_artist_dname.' by artist:'.$artist_dname);
		}
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}

	
	/**
	* @type	GET
	*
   	* @access	public
   	* @param 	string		$artist_dname
   	* @return	array 					array of gears
   	*/
	public function GetGears($artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning gears for artist:'.$artist_dname);
		
		$this->db->select('gear_man,gear_type,gear_name');
		$this->db->where('artist_dname',$artist_dname);
		
		$gear_db = $this->db->get('artist_gear');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $gear_db->result_array();
	}
	
	/**
	* @type	PUT
	*
   	* @access	public
   	* @param	string		$gear_name
   	* @param	int		$gear_type
   	* @param	string		$gear_man
   	* @param 	string		$artist_dname
   	* @return	void
   	*/
	public function AddGear($gear_name,$gear_type,$gear_man,$artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Adding new gear for artist:'.$artist_dname);
		
		$data_db = array
		(
			'artist_dname'	=>	$artist_dname,
			'gear_name'	=>	$gear_name,
			'gear_type'		=>	$gear_type,
			'gear_man'		=>	$gear_man,
			'gear_date'		=>	$this->util->GetCurrentDateStamp()
		);
		
		$this->db->insert('artist_gear',$data_db);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
	* @type	PUT
	*
   	* @access	public
   	* @param	string		$gear_name
   	* @param	int		$gear_type
   	* @param	string		$gear_man
   	* @param 	string		$artist_dname
   	* @return	void
   	*/
	public function DeleteGear($gear_name,$gear_type,$gear_man,$artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Deleting gear for artist:'.$artist_dname);
		
		$data_db = array
		(
			'artist_dname'	=>	$artist_dname,
			'gear_name'	=>	$gear_name,
			'gear_type'		=>	$gear_type,
			'gear_man'		=>	$gear_man,
		);
		
		/*
		 * Important: This query will delete only 1 matching row
		 * This is because it is possible that the artist can have 2 rows that have
		 * identical matching gear parameters
		 */
		$delete_query= "delete from artist_gear where artist_dname = ? 
				and gear_name = ? and gear_type = ? and gear_man = limit 1?";
		
		$this->db->query($delete_query,$data_db);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
	* @type	PUT
	*
   	* @access	public
   	* @param 	string		$scrap_dname		artist who wrote the scrap
   	* @param	string		$scrap_text
   	* @param	string		$artist_dname		artist who received the scrap
   	* @return   void
   	*/
	public function AddComment($for_artist_dname,$artist_dname,$comment_text,$comment_optional_arg=null)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Adding new comment for artist:'.$artist_dname);
		
		$data_db = array
		(
			'for_artist_dname'	=>	$for_artist_dname,
			'artist_dname'		=>	$artist_dname,
			'comment_text'		=>	$comment_text,
			'comment_date'		=>	$this->util->GetCurrentDateStamp()
		);
		
		$this->db->insert('artist_comment',$data_db);
		
		/*
		$this->log->fine('[MODEL] Adding to comment count for artist:'.$artist_dname);
		$this->db->increment('artist','artist_comment_count',array('artist_dname'=>$for_artist_dname));
		*/
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**
	* 
	*
	* @param 	string 	$artist_dname 			
	* @return	bool
	*/
	function IsRated($artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Checking if artist has been rated:'.$artist_dname);
		
		if($this->GetRatingCount($artist_dname) > 0)
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
	* 
	*
	* @param 	string 	$for_artist_dname 						
	* @param 	string 	$artist_dname
	* @return	bool
	*/
	function IsRatedByArtist($for_artist_dname,$artist_dname)
	{
		// @todo
	}
			
   	/**
	* 
	*
	* @param 	string		$artist_dname 			
	* @return	int
	*/
	function GetRatingCount($artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning #ratings for artist:'.$artist_dname);
		
		$this->db->where($this->COLUMN_ENTITY_KEY,$artist_dname);
		$result = $this->db->get_attribute('artist','artist_rate_count');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result;		
	}
	
	/**
	* 
	*
	* @param 	string		$artist_dname 			
	* @return	array
	*/
	function GetOverallRating($artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning overall rating for artist:'.$artist_dname);
		
		$this->db->where('artist_dname',$artist_dname);
		$result =  $this->db->get_attribute('artist','artist_rating');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result;
	}
	
	/**
   	* @type	GET
   	* 
   	* @access	public
   	* @param	int		$mid
   	* @param 	string		$artist_dname
   	* @return	int					metric_rating
   	*/
	public function GetMetricRating($artist_dname,$mid)
	{
		$this->log->fine('[MODEL] Getting current metric rating for mid:'.$mid.' for artist:'.$artist_dname);
		
		$this->db->order_by('a_week#','desc');
		$this->db->where(array
		(
			'artist_dname'	=>	$artist_dname,
			'MID'			=>	$mid
		));
		
		return $this->db->get_attribute('artist_stat_metric','AMR');
	}
	
	/**
   	* @type	GET
   	* 
   	* @access	public
   	* @param 	string		$artist_dname
   	* @return	array					artist metric ratings
   	*/
	public function GetAllMetricRatings($artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning metric ratings for artist:'.$artist_dname);
		
		$mids = $this->UtilModel->GetMIDs();
		foreach($mids as &$mid_unit)
		{
			$mid_unit['metric_rating'] = $this->GetMetricRating($artist_dname,$mid_unit['MID']);
			$this->log->debug('[MODEL] Metric Rating, MID:'.$mid_unit['MID'].';Rating:'.$mid_unit['metric_rating']);
		}
	
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $mids;
	}
	
	/**
   	* @type	GET
   	* 
   	* @access	public
   	* @param 	string		$artist_dname
   	* @return	array					array{SID,suggestion_count}
   	*/
	public function GetSuggestionCount($artist_dname,$mid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning Metric Suggestion Count for mid:'.$mid.' for artist:'.$artist_dname);
		
		$this->db->select('SID,suggestion_count');
		$this->db->where(array
		(
			'artist_dname'	=>	$artist_dname,
			'MID'			=>	$mid
		));
		
		$stat_db = $this->db->get('artist_stat_suggestion');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $stat_db->result_array();
	}
	
	/**
   	* @type	GET
   	* 
   	* @access	public
   	* @param 	string		$artist_dname
   	* @return	array					array $mids{MID,metric_name,suggestion_counts(SID,suggestion_count)}
   	*/
	public function GetAllSuggestionCounts($artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning Metric Suggestion Counts for artist:'.$artist_dname);
		
		$mids = $this->UtilModel->GetMIDs();
		foreach($mids as &$mid_unit)
		{
			$mid_unit['suggestion_counts'] = $this->GetSuggestionCount($mid_unit['MID'],$artist_dname);
		}
	
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $mids;
	}
	
	/**
	* 
	*
	* @param 	mixed		$entity_id 			
	* @return	array
	*/
	function AddRating($for_artist_dname,$artist_dname,$ratings,$ratings_optional_arg=null)
	{
		throw new Exception('[MODEL] ARTIST CANNOT BE RATED DIRECTLY');
	}	
	
	/**
   	* @type	GET
   	* 
   	* @access	public
   	* @param	int		$mid
   	* @param 	string		$artist_dname
   	* @return	array					
   	*/
	public function GetMetricRatingDataPoints($mid,$artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning Metric rating data points for artist:'.$artist_dname.'::mid:'.$mid);
		
		
		$this->db->select('MID,a_week#,AMR');
		$this->db->order_by('a_week#','asc');
		$this->db->where('MID',$mid);
		
		$data_points_db = $this->db->get('artist_stat_metric');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $data_points_db->result_array();
		
	}
		
	/**-------------------------------------------------------------------------------------------------------------------*/

}

/* End of file ArtistEntityModel.php */

