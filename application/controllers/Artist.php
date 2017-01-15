<?php
/**
 * Artist Controller Class
 * 
 * Purpose
 * 		Controls the 'Artist Profile' section
 * Created 
 * 		April 1, 2009
 * 
 * @package	Lightbox
 * @subpackage	Controllers
 * @category	none
 * @author		venksster
 */
require CONTROLLER_BASE_PATH.'Abstract.PhotoGalleryController'.EXT;

class Artist extends AbstractPhotoGalleryController
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @param 	void
   	* @return	void 			
   	*/
	public function Artist()
	{
		parent::AbstractPhotoGalleryController('artist');
		$this->load->model('AuthModel');		
		$this->load->model('PhotoCollectionModel');
		$this->load->model('ArtistEntityModel');
		$this->load->model('ArtistModel'); 
		$this->load->library('CGraphics');
		$this->load->model('PhotoModel');	

        $this->load->library('email');		
		//$this->load->library('CUtil');
	}
  
	/**
   	* ENTRY POINT
   	* 
   	* Displays:the ARTIST PROFILE page
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @param 	string		$artist_dname		
   	* @return	void 			
   	*/
	public function Index($artist_dname)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----LOADING ARTIST:'.$artist_dname.'-----');
			$data['artist_dname'] = $artist_dname;
			
			$this->log->fine('[CONTROLLER] ADDING ARTIST DETAILS');
			$data['artist_details'] = $this->ArtistEntityModel->GetDetails($artist_dname);	
			
			$this->log->debug('[CONTROLLER] ARTIST DETAILS for '.$artist_dname.' are '.print_r($data['artist_details'],true));
			
			$this->log->fine('[CONTROLLER] ADDING ARTIST DETAILS');
			$data['artist_gears'] = $this->ArtistEntityModel->GetGears($artist_dname);
			
			$this->log->fine('[CONTROLLER] ADDING ARTIST RECENTLY UPLOADED');
			$data['artist_recently_uploaded_photos'] = $this->PhotoCollectionModel->GetMostRecentByArtist($artist_dname,VIEW_ARTIST_PROFILE_NUM_RECENTLY_UPLOADED_PHOTOS,OFFSET_NONE);
			
			$this->log->fine('[CONTROLLER] ADDING ARTIST MOST BOOKMARKED');
			$data['artist_most_bookmarked_photos'] = $this->ArtistEntityModel->GetMostBookmarkedPhotos($artist_dname,VIEW_ARTIST_PROFILE_NUM_MOST_BOOKMARKED_PHOTOS);
			
			/*
			$this->log->fine('[CONTROLLER] ADDING ARTIST RECENTLY BOOKMARKED PHOTOS');
			$data['artist_recently_bookmarked'] = $this->ArtistEntityModel->GetMostRecentPhotoBookmarksByArtist($artist_dname,VIEW_ARTIST_PROFILE_NUM_RECENTLY_BOOKMARKED_PHOTOS);
			
			$this->log->fine('[CONTROLLER] ADDING ARTIST RECENTLY COMMENTED PHOTOS');
			$data['artist_recently_commented'] = $this->ArtistEntityModel->GetMostRecentlyCommentedPhotosByArtist($artist_dname,VIEW_ARTIST_PROFILE_NUM_RECENTLY_COMMENTED_PHOTOS);
			*/
			
			$this->log->fine('[CONTROLLER] ADDING ARTIST COMMENTS');
			$data['comment_array'] = $this->UtilController->GetCommentsByPage($artist_dname,$comment_page=1,VIEW_ARTIST_PROFILE_NUM_COMMENTS,$this->ArtistEntityModel);
			
			$this->log->fine('[CONTROLLER] ADDING BOOKMARK INFO FOR SESSION USER');
			$data['is_artist_bookmarked'] = $this->ArtistEntityModel->IsBookmarked($artist_dname,$this->session_user);
			
			$this->log->info('[CONTROLLER][DISPLAY] ARTIST PROFILE');
			$this->display('View.ARTIST.Profile',$data);
		}
		catch(Exception $e)
		{
			$this->_OnError($e);
		}
	} 
	
	
	/**
	 * Returns the photos uploaded by this artist across gallery and discussions
	 * @return 
	 * @param object $artist_dname
	 * @param object $order_by[optional]
	 */
	public function GetPermittedPhotoCount()
	{
		$this->log->info('[CONTROLLER][ENTRY] -----GETPERMITTEDPHOTOCOUNT:'.$this->session_user);
		
		$total_photo_count	= $this->PhotoCollectionModel->GetEntityCountByArtist($this->session_user,FILTER_NONE,FILTER_NONE);	
		$permitted_photo_count = MAX_USER_PHOTOS-$total_photo_count;
		
		//Return the difference of MAX_USER_PHOTOS and what the user has already uploaded
		$this->log->info('[CONTROLLER]Returning permitted photo count:'.$permitted_photo_count);
		$this->AjaxResponse(SUCCESS_WITH_MESSAGE,$permitted_photo_count);
	}
	
	/**
   	* Displays:the ARTIST GALLERY page
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @param 	string		$artist_dname		
   	* @return	void 			
   	*/
	public function Gallery($artist_dname,$order_by='recent')
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----LOADING ARTIST GALLERY::Order:'.$order_by.'-----');
			$data['artist_dname'] = $artist_dname;
			
			$this->log->info('[CONTROLLER] Artist:'.$artist_dname); 			
			
			$page = $this->UtilController->GetPage($_POST);
			$this->log->info('[CONTROLLER] PAGE:'.$page); 
			
			$filter_name_tag_keywords 	= $this->GetPhotoFilterKeywords($_POST);
			$this->log->info('[CONTROLLER] PHOTO KEYWORDS:'.print_r($filter_name_tag_keywords,true));
			
			$filter_photo_property_array	= $this->GetPhotoPropertyArray($_POST);
			$this->log->info('[CONTROLLER] PHOTO FILTERS:'.print_r($filter_photo_property_array,true));
			
			$this->log->fine('[CONTROLLER] CALCULATING OFFSET');
			$view_num_photos = VIEW_GALLERY_PHOTOS_NUM_COLUMNS * VIEW_GALLERY_PHOTOS_NUM_ROWS;
			$offset = ($page - 1) * $view_num_photos;
			$this->log->debug('[CONTROLLER] $offset:'.$offset);
			
			$this->log->fine('[CONTROLLER] ADDING PHOTOS');
			$data['current_page']		= $page;
			$data['start_photo']		= $offset + 1;
			$data['total_photo_count']	= $this->PhotoCollectionModel->GetEntityCountByArtist($artist_dname,$filter_name_tag_keywords,$filter_photo_property_array);
			$data['page_count']		= $data['total_photo_count'] == 0 ? 0 : ceil($data['total_photo_count'] / $view_num_photos);
			$data['photos'] 			= $data['total_photo_count'] == 0 ? array() : $this->PhotoCollectionModel->GetMostRecentByArtist($artist_dname,$view_num_photos,$offset,$filter_name_tag_keywords,$filter_photo_property_array);
			//$data['photos'] 			= $data['total_photo_count'] == 0 ? array() : $this->PhotoCollectionModel->GetShoeboxPreview($artist_dname,$tags='white');
			
			$this->log->info('RETRIEVING PHOTO GENRES');
			$data['photo_type_array'] = $this->PhotoModel->GetPhotoTypes();
			sort($data['photo_type_array']);
			
			if($this->session_user!=null && count($data['photos']))
			{
				$this->AddBookmarkInfo($data['photos'],$this->session_user);
			}
			
			$this->load->model('ShoeboxCollectionModel');
			$data['total_shoebox_count']	= $this->ShoeboxCollectionModel->GetEntityCount($filter_name_keywords=FILTER_NONE,$filter_entity_property_array=array('artist_dname'=>$artist_dname));
			
			$this->log->debug('[CONTROLLER] Photo count:: '.$data['total_photo_count'].",Shoebox count: ".$data['total_shoebox_count']);
			$this->log->info('[CONTROLLER][DISPLAY] ARTIST PHOTO GALLERY');
			$this->display('View.ARTIST.Photo_Gallery',$data);
		}
		catch(Exception $e)
		{
			$this->_OnError($e);
		}
	}   
	
	/**
   	* Displays:the ARTIST SHOEBOX EXPLORE page
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @param 	string		$artist_dname		
   	* @return	void 			
   	*/
	public function ShoeboxExplore($artist_dname,$order_by='recent',$page=1)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----LOADING ARTIST SHOEBOX EXPLORE::Order:'.$order_by.'-----');
			$this->load->model('ShoeboxCollectionModel');
			$data['artist_dname'] = $artist_dname;
			
			$this->log->fine('[CONTROLLER] CALCULATING OFFSET');
			$view_num_shoeboxes = VIEW_ARTIST_SHOEBOX_EXPLORE_NUM_COLUMNS * VIEW_ARTIST_SHOEBOX_EXPLORE_NUM_ROWS;
			$offset = ($page - 1) * $view_num_shoeboxes;
			$this->log->debug('[CONTROLLER] $offset:'.$offset);
			
			$this->log->fine('[CONTROLLER] ADDING PHOTOS');
			$filter_name_keywords = $this->UtilController->GetFilterKeywords($_POST);
			$this->log->info('[CONTROLLER] SEARCH KEYWORDS:'.$filter_name_keywords); 
			
			$data['current_page']		= $page;
			$data['start_shoebox']		= $offset + 1;
			$data['total_shoebox_count']	= $this->ShoeboxCollectionModel->GetEntityCount($filter_name_keywords,$filter_entity_property_array=array('artist_dname'=>$artist_dname));
			$data['page_count']		= $data['total_shoebox_count'] == 0 ? 0 : ceil($data['total_shoebox_count'] / $view_num_shoeboxes);
			$data['shoeboxes'] 		= $data['total_shoebox_count'] == 0 ? array() : $this->ShoeboxCollectionModel->GetEntities($order_by=ORDER_DATE,$filter_name_keywords,$filter_entity_property_array=array('artist_dname'=>$artist_dname),$view_num_shoeboxes, $offset);
			
			foreach($data['shoeboxes'] as &$shoebox)
			{
				$shoebox_tags = rtrim($shoebox['photo_tag_list'],LIGHTBOX_TAG_LIST_DELIMITER);
			//	$shoebox['tag_count'] = count(explode(LIGHTBOX_TAG_LIST_DELIMITER,$shoebox_tags));
				$shoebox['tags'] = explode(LIGHTBOX_TAG_LIST_DELIMITER,$shoebox_tags);
				$each_shoebox = $this->PhotoCollectionModel->GetMostRecentByShoebox($shoebox['SHOEBOX_ID'],1,0);
				
				
				if( is_array($each_shoebox) && array_key_exists('0', $each_shoebox) )
				{
					$this->log->debug('[CONTROLLER] Photo_preview '.print_r($each_shoebox[0],true));
					$shoebox['photo_preview'] = $each_shoebox[0]['PHOTO_ID'];
				}
				else
				{
					$shoebox['photo_preview'] = null;	
				}
				
			}
			
			$data['total_photo_count']	= $this->PhotoCollectionModel->GetEntityCountByArtist($artist_dname);
			
			$this->log->info('[CONTROLLER][DISPLAY] ARTIST SHOEBOX GALLERY');
			$this->display('View.ARTIST.Shoeboxes.Explore',$data);
		}
		catch(Exception $e)
		{
			$this->_OnError($e);
		}
	}   
	
	/**
   	* Displays:the ARTIST SHOEBOX page
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @param 	string		$artist_dname		
   	* @return	void 			
   	*/
	public function Shoebox($shoebox_id,$order_by='recent',$page=1)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----LOADING ARTIST SHOEBOX::Order:'.$order_by.'-----');
			
			$page = $this->UtilController->GetPage($_POST);
			$this->log->info('[CONTROLLER] PAGE:'.$page); 
			
			$filter_name_tag_keywords 	= $this->GetPhotoFilterKeywords($_POST);
			$this->log->info('[CONTROLLER] PHOTO KEYWORDS:'.print_r($filter_name_tag_keywords,true));
			
			$filter_photo_property_array	= $this->GetPhotoPropertyArray($_POST);
			$this->log->info('[CONTROLLER] PHOTO FILTERS:'.print_r($filter_photo_property_array,true));
			
			$this->log->fine('[CONTROLLER] ADDING SHOEBOX DETAILS');
			$this->load->model('ShoeboxEntityModel');
			$data['shoebox_details'] = $this->ShoeboxEntityModel->GetDetails($shoebox_id);
			if($data['shoebox_details']['photo_type']!=null && $data['shoebox_details']['photo_type']!=0)
			{
				$data['shoebox_details']['photo_type_name'] = $this->PhotoModel->GetPhotoTypeName($data['shoebox_details']['photo_type']);
			}
			$data['artist_dname'] = $data['shoebox_details']['artist_dname'];		
			$shoebox_tags = rtrim($data['shoebox_details']['photo_tag_list'],LIGHTBOX_TAG_LIST_DELIMITER);	
			$data['tags'] = explode(LIGHTBOX_TAG_LIST_DELIMITER,$shoebox_tags);
			
			$this->log->fine('[CONTROLLER] CALCULATING OFFSET');
			$view_num_photos = VIEW_ARTIST_SHOEBOX_NUM_COLUMNS * VIEW_ARTIST_SHOEBOX_NUM_ROWS;
			$offset = ($page - 1) * $view_num_photos;
			$this->log->debug('[CONTROLLER] $offset:'.$offset);
			
			$this->log->fine('[CONTROLLER] ADDING PHOTOS');
			$data['shoebox_id'] = $shoebox_id;
			$data['current_page']		= $page;
			$data['start_photo']		= $offset + 1;
			$data['total_photo_count']	= 10;//$this->PhotoCollectionModel->GetEntityCount(); // @todo
			$data['page_count']		= $data['total_photo_count'] == 0 ? 0 : ceil($data['total_photo_count'] / $view_num_photos);
			$data['photos'] 			= $data['total_photo_count'] == 0 ? array() : $this->PhotoCollectionModel->GetMostRecentByShoebox($shoebox_id,$view_num_photos,$offset,$filter_name_tag_keywords,$filter_photo_property_array);
			
			if($this->session_user!=null && count($data['photos']))
			{
				$this->AddBookmarkInfo($data['photos'],$this->session_user);
			}
			
			$this->log->info('[CONTROLLER][DISPLAY] ARTIST SHOEBOX');
			$this->display('View.ARTIST.Shoebox',$data);
		}
		catch(Exception $e)
		{
			$this->_OnError($e);
		}
	}
	
	
	/**
   	* Displays:the ARTIST STATS page
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @param 	string		$artist_dname		
   	* @return	void 			
   	*/
	public function Stats($artist_dname)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----LOADING ARTIST STATS:'.$artist_dname.'-----');
			$data['artist_dname'] = $artist_dname;
			
			$this->log->fine('[CONTROLLER] ADDING ARTIST MOST BOOKMARKED');
			$data['artist_most_bookmarked_photos'] = $this->ArtistEntityModel->GetMostBookmarkedPhotos($artist_dname,VIEW_ARTIST_STATS_MOST_BOOKMARKED_PHOTOS);
			
			$this->log->fine('[CONTROLLER] ADDING ARTIST MOST COMMENTED');
			$data['artist_most_commented_photos'] = $this->ArtistEntityModel->GetMostCommentedPhotos($artist_dname,VIEW_ARTIST_STATS_MOST_COMMENTED_PHOTOS);
			
			$this->log->fine('[CONTROLLER] ADDING ARTIST RATINGS');
			$data['artist_overall_rating'] = $this->ArtistEntityModel->GetOverallRating($artist_dname);
			$data['artist_metric_ratings'] = $this->ArtistEntityModel->GetAllMetricRatings($artist_dname);
			
			$this->log->fine('[MODEL] artist_metric_ratings:'.print_r($data['artist_metric_ratings'],true));
			
			$data['artist_metric_suggestions'] = $this->ArtistEntityModel->GetAllSuggestionCounts($artist_dname);
			
			
			$this->log->fine('[CONTROLLER] ADDING TOP RATED PHOTOS');
			$data['artist_top_rated_photos'] = $this->PhotoCollectionModel->GetTopRatedByArtist($artist_dname,VIEW_ARTIST_STATS_TOP_RATED,OFFSET_NONE);
			$data['artist_metric_top_rated_photos'] = array();		
			foreach($data['artist_metric_ratings'] as $metric_rating)
			{
				$mid = $metric_rating['MID'];
				$data['artist_metric_top_rated_photos'][$mid] = $this->PhotoCollectionModel->GetTopRatedByMetric($mid,VIEW_ARTIST_STATS_TOP_RATED,$artist_dname);
			}
			
			$this->log->fine('[CONTROLLER] ADDING METRIC DATA POINTS');
			$data['artist_metric_rating_data_point_array'] = array();
			foreach($data['artist_metric_ratings'] as $metric_rating)
			{
				$mid = $metric_rating['MID'];
				$data['artist_metric_rating_data_point_array'][$mid] = $this->ArtistEntityModel->GetMetricRatingDataPoints($mid,$artist_dname);
				$this->log->debug('artist_metric_rating_data_points::mid:'.$mid.print_r($data['artist_metric_rating_data_point_array'][$mid],true));
			}
			
			$this->log->info('[CONTROLLER][DISPLAY] ARTIST STATS');
			$this->display('View.ARTIST.Stats',$data);
		}
		catch(Exception $e)
		{
			$this->_OnError($e);
		}
	}  
	
	/**
   	* Posts a comment on the artist profile page
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	html 			
   	*/
	public function PostComment()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----POSTING COMMENT-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('entity','comment_text'));
					
			$this->log->fine('[CONTROLLER][ENTRY] Posting new comment for artist:'.$_POST['entity']);
			$this->ArtistEntityModel->AddComment($_POST['entity'],$this->session_user,$_POST['comment_text']);
			
			$this->log->fine('[CONTROLLER][ENTRY] Returning profile page for artist:'.$_POST['entity']);		
			return $this->Index($_POST['entity']);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* Deletes a comment from the artist profile page
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	int 			
   	*/
	public function DeleteComment()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----DELETING COMMENT-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('entity','comment_id'));
					
			$this->log->fine('[CONTROLLER][ENTRY] Deleting new comment for artist:'.$_POST['entity']);
			$this->ArtistEntityModel->DeleteComment($_POST['entity'],$_POST['comment_id']);
			
			//$this->log->fine('[CONTROLLER][ENTRY] Returning profile page for artist:'.$_POST['for_artist_dname']);		
			//return $this->Index($_POST['for_artist_dname']);
			$this->AjaxResponse(SUCCESS);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}	
	
	/**
   	* Bookmarks a photo
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function BookmarkPhoto()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----BOOKMARK PHOTO-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('id'));
						
			$this->PhotoEntityModel->Bookmark($_POST['id'],$this->session_user);
			
			$this->AjaxResponse(SUCCESS);	
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* UnBookmarks a photo
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function UnBookmarkPhoto()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----UNBOOKMARK PHOTO-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('id'));
			
			$this->PhotoEntityModel->UnBookmark($_POST['id'],$this->session_user);
			$this->AjaxResponse(SUCCESS);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* Bookmarks an artist
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function BookmarkArtist()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----BOOKMARK ARTIST-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('id'));
			
			$this->ArtistEntityModel->Bookmark($_POST['id'],$this->session_user);
			$this->AjaxResponse(SUCCESS);			
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* UnBookmarks an artist
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function UnBookmarkArtist()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----UNBOOKMARK ARTIST-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('id'));
			
			$this->ArtistEntityModel->UnBookmark($_POST['id'],$this->session_user);
			$this->AjaxResponse(SUCCESS);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
				
	}
	
	/**
   	* Deletes a photo
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function DeletePhoto()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----DELETE PHOTO-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('entity_id'));
			
			$photo_details = $this->PhotoEntityModel->GetDetails($_POST['entity_id']);
			if($photo_details['artist_dname']!= $this->session_user)
			{
				$this->log->fatal('[CONTROLLER] Permission denied for artist:'.$this->session_user);
				throw new PermissionDeniedException('Permission denied for artist:'.$this->session_user);
			}
						
			$this->PhotoEntityModel->Delete($_POST['entity_id']);
			
			$this->AjaxResponse(SUCCESS);
			
			//Then delete the photo from the file system
			$this->cutil->DeletePhotoImageStack($_POST['entity_id']);			
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* Displays: the artist bookmarks
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @param	$type		photo/artist/group
   	* @return	void 			
   	*/
	public function Bookmarks($type='photo')
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----BOOKMARKS-----');
			
			$this->VerifyActiveSession();
						
			$this->log->info('[CONTROLLER] type:'.$type);
			$this->log->debug('[CONTROLLER] POST array :'.print_r($_POST,true));
			
			$filter_keywords = FILTER_NONE;
			if(isset($_POST) && is_array($_POST) && array_key_exists('filter_keywords',$_POST) && $_POST['filter_keywords']!='' && $_POST['filter_keywords']!=' ')
			{
				$filter_keywords = $_POST['filter_keywords'];
				$this->log->info('[CONTROLLER] filter keywords:'.$filter_keywords);
			}
			
			switch($type)
			{
				case 'photo':
						$data['bookmarks_type'] = VIEW_BOOKMARKS_TYPE_PHOTO;
						$data['bookmarks_type_string'] = 'photo';
						$data['bookmarks_array'] = $this->PhotoCollectionModel->GetBookmarks($this->session_user,$filter_keywords);
						//$data['bookmarks_array'] = $this->ArtistEntityModel->GetMostRecentPhotoBookmarksByArtist($this->session_user,RESULTS_ALL,$filter_keywords);
						$this->log->fine('[CONTROLLER] #bookmarks_photos:'.count($data['bookmarks_array']));
						break;
							
				case 'artist':
						$data['bookmarks_type'] = VIEW_BOOKMARKS_TYPE_ARTIST;
						$data['bookmarks_type_string'] = 'artist';
						$data['bookmarks_array'] = $this->ArtistEntityModel->GetMostRecentArtistBookmarksByArtist($this->session_user,RESULTS_ALL,$filter_keywords);
						$this->log->fine('[CONTROLLER] #bookmarks_artists:'.count($data['bookmarks_array']));
						break;
						
				case 'group':
						$data['bookmarks_type'] = VIEW_BOOKMARKS_TYPE_GROUP;
						$data['bookmarks_type_string'] = 'group';
						$data['bookmarks_array'] = $this->ArtistEntityModel->GetMostRecentlyJoinedGroups($this->session_user,RESULTS_ALL,$filter_keywords);
						$this->log->fine('[CONTROLLER] #bookmarks_groups:'.count($data['bookmarks_array']));					
						break;
			}
			
			//Store all the bookmark counts. this will be used to display the bookmark count in each category
			$data['photo_bookmark_count'] = $this->PhotoCollectionModel->GetBookmarkCount($this->session_user);
			$data['artist_bookmark_count'] = $this->ArtistEntityModel->GetArtistBookmarkCount($this->session_user);		
			$data['group_bookmark_count'] = count($this->ArtistEntityModel->GetMostRecentlyJoinedGroups($this->session_user,RESULTS_ALL,$filter_keywords));	
			
			$this->log->debug('[CONTROLLER] bookmarks_array:'.print_r($data['bookmarks_array'],true));
			
			$this->log->info('[CONTROLLER][MODAL] ARTIST BOOKMARKS');
			$this->load->view('Modal.Bookmarks',$data);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* Delete a new shoebox
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	int		shoebox entity key 			
   	*/
	public function DeleteShoebox()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----DELETE SHOEBOX-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('entity_id'));
			
			$this->load->model('ShoeboxEntityModel');
			$this->ShoeboxEntityModel->Delete($_POST['entity_id']);
						
			$this->log->info('[CONTROLLER] Deleted shoebox:'.$_POST['entity_id']);
			$this->AjaxResponse(SUCCESS);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* Creates a new shoebox
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	int		shoebox entity key 			
   	*/
	public function AddShoebox()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----ADD SHOEBOX-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('shoebox_name'));
			
			$shoebox_filters = $this->GetPhotoPropertyArray($_POST);
			if(!is_array($shoebox_filters) && ($shoebox_filters==FILTER_NONE))
			{
				$this->log->fine('[CONTROLLER] Shoebox does not filter by photo properties');
				unset($shoebox_filters);
			}
			
			if($this->GetPhotoFilterKeywords($_POST)!=FILTER_NONE)
			{
				$shoebox_filters['photo_tags'] = explode(LIGHTBOX_TAG_LIST_DELIMITER,$_POST['filter_photo_keywords']);			
			}
			else
			{
				$this->log->fine('[CONTROLLER] Shoebox does not filter by keywords');
			}
	
			$this->log->info('[CONTROLLER] Shoebox processed filters:'.print_r($shoebox_filters,true));
			
			$shoebox_data = array
			(
				'shoebox_name'	=>	$_POST['shoebox_name'],
				'shoebox_filters' => $shoebox_filters
			);
			
			$this->load->model('ShoeboxEntityModel');
			$key = $this->ShoeboxEntityModel->Add($this->session_user,$shoebox_data);
			
			$result = '/'.'shoebox'.'/'.$key;
			$this->log->info('[CONTROLLER] Returning shoebox key:'.$key);
			echo $key;
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* Displays the modal to edit the atrist focii
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function ShowEditFocus()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----SHOW EDIT FOCUS-----');
			
			$this->VerifyActiveSession();
						
			$data['artist_focii'] = $this->ArtistModel->GetFocii();
			
			$artist_details = $this->ArtistEntityModel->GetDetails($this->session_user,'artist_focus');
			$data['artist_focii_checked'] = explode(ARTIST_FOCUS_DELIMITER,$artist_details['artist_focus']);
			
			//$this->log->info(' Current Artist focus: '.print_r($data['artist_focii_checked'],true));
			$this->log->info(' Current Artist focus: '.print_r($artist_details['artist_focus'],true));
			/*			
			$this->log->debug('Comparing:: '.$data['artist_focii'][0]['artist_focus_name']);

			if(array_search('landscape',$data['artist_focii_checked'] ) === false )
			{
				$this->log->debug();	
			}
		   */
			$this->log->info('[CONTROLLER][MODAL] ARTIST FOCUS EDITOR');
			$this->load->view('Modal.Artist.EditFocus',$data);
		}
		catch(Exception $e)
		{
			$this->_OnError($e);
		}
	}
	
	/**
   	* Changes/edits the artist focii
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	int 			
   	*/
	public function EditFocus()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----EDIT FOCUS-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('artist_focus'));
			
			$artist_focus = $this->UtilController->GetArtistFocus($_POST);
			
			$this->ArtistEntityModel->EditFocus($this->session_user,$artist_focus);
			redirect('/artist/'.$this->session_user);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* Processes POST data for artist_focus
   	* 
   	* @access	private
   	* @return	array		$artist_focus 			
   	*/
	private function ReturnArtistFocusArray($post_array)
	{
		$this->VerifyPostData($post_array,array('artist_focus'));
		
		$artist_focus = $post_array['artist_focus'];
		if(array_key_exists('artist_focus_other',$post_array))
		{
			$other_index = array_search('other',$artist_focus);
			if( $other_index!=false )
			{
				$artist_focus[$other_index] = $post_array['artist_focus_other'];	
			}
			
			$this->log->debug('[CONTROLLER] Artist focus, other:'.$artist_focus[$other_index]);
		}
		
		return $artist_focus;
	}	
	
	/**
   	* Changes/edits the artist location
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	int 			
   	*/
	public function EditLocation()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----EDIT LOCATION-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('artist_location'));
			
			$this->ArtistEntityModel->EditLocation($this->session_user,$_POST['artist_location']);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* Changes/edits the artist 'about me'
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function EditAboutMe()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----EDIT ABOUT ME-----'); 
			
			$this->VerifyActiveSession();
			if( array_key_exists('artist_about_me',$_POST) )
			{
				$this->log->debug('[CONTROLLER] artist_about_me:'.$_POST['artist_about_me']);
				
				$reset = false;
				if( $_POST['artist_about_me'] == null || $_POST['artist_about_me'] == '' )
				{
					$reset = true;
				}
				$this->ArtistEntityModel->EditAboutMe($this->session_user,$_POST['artist_about_me'],$reset);
			}
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* Changes/edits the shoeboxname
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	void 			
   	*/	
	public function EditShoeboxName()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----EDIT SHOEBOX NAME-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('shoebox_id','shoebox_name'));
			
			$this->load->model('ShoeboxEntityModel');
			$this->ShoeboxEntityModel->EditName($_POST['shoebox_id'],$_POST['shoebox_name']);
			$this->log->info('[CONTROLLER] New Shoebox Name:'.$_POST['shoebox_name']);
			
			$this->AjaxResponse(SUCCESS);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}	
	}
	
	/**
   	* Displays the modal to edit the atrist focii
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	/*
	public function ModalGallery($section)
	{
		try
		{
			$this->log->info('[CONTROLLER] Loading modal gallery');
			
			$filter_keywords = FILTER_NONE;
			
			if(isset($_POST) && is_array($_POST) && array_key_exists('filter_keywords',$_POST))
			{
				$filter_keywords = $_POST['filter_keywords'];
				$this->log->info('keywords:'.$filter_keywords);
			}
			
			$data['photos'] = $this->PhotoCollectionModel->GetMostRecentByArtist($this->session_user,RESULTS_ALL,OFFSET_NONE,$filter_keywords);
			$this->log->info('#photos'.count($data['photos']));
			
			$data['url_action'] = $this->GetActionUrl($section);
					
			$this->log->info('[CONTROLLER][MODAL] ARTIST GALLERY');
			$this->load->view('Modal.Artist.Gallery.Add_Photos',$data);
		}
		catch(Exception $e)
		{
			$this->_OnError($e);
		}
	}
	*/
	
	/**
   	*  @type	INTERNAL
   	* 
   	* @access	private
   	* @return	string $section		
   	*/
	private function GetActionUrl($section)
	{
		switch($section)
		{
			case 'group_create':
				return '/group/create';
				
			default:
				return null;
		}
	}
	
	
	/**
   	* Edit the artist profile avatar
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	void 			
   	*/	
	public function EditArtistProfileAvatar()
	{
		$this->VerifyPostData($_POST,array('artist_profile_avatar'));
		
		try
		{		
			//Store this new profile image in the database
			$this->log->info("[CONTROLLER] Updating artist avatar in database for ".$this->session_user);
			$this->ArtistEntityModel->EditAvatar($this->session_user,$_POST['artist_profile_avatar']);	
			$this->AjaxResponse(SUCCESS);
		}
		catch( Exception $e )
		{
			echo $this->_OnError($e);
		}		
		
	}
	
	public function Preferences($artist_dname)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----ACCOUNT PREFERENCES-----');
			
			$this->VerifyActiveSession();
			
			$this->load->model('PhotoModel');
			$data['cc_licenses'] = $this->PhotoModel->GetCreativeCommonsLicenses();
			
			$data['artist_details'] = $this->ArtistEntityModel->GetDetails($this->session_user);	
					
			$this->Display('View.ARTIST.Preferences',$data);
			
		}
		catch(Exception $e)
		{
			echo $this->_OnError($e);
		}			
			
	}
	
	/**
	 *  Validate artist current password. This is called from the "preferences" page.
	 */
	public function ValidateCurrentPassword()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----VALIDATE CURRENT PASSWORD-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('artist_password_sha1'));
		
			$artist_data['artist_details'] = $this->ArtistEntityModel->GetDetails($this->session_user);
			
			$this->log->info('[CONTROLLER]Comparing '.$_POST['artist_password_sha1'].' with '.$artist_data['artist_details']['artist_password']);			
			
			if( $_POST['artist_password_sha1'] == $artist_data['artist_details']['artist_password'] )
			{
				$this->AjaxResponse(SUCCESS);
			}
			else
			{
				$this->AjaxResponse(ERROR_AUTHENTICATION_FAILED);		
			}
		}
		catch(Exception $e)
		{
			echo $this->_OnError($e);
		}
	}	
	
	/**
	 * Save Artist Preferences
	 */
	public function SavePreferences()
	{
		try
		{		
			$this->log->info('[CONTROLLER][ENTRY] -----SAVE PREFERENCES-----');
			$this->VerifyActiveSession();
			
			$preferences_saved = false;
		
			//Only the artist_email might be changing
			if( array_key_exists('artist_email',$_POST) )
			{
				$this->log->info('[CONTROLLER]Editing artist_email ');
				$this->ArtistEntityModel->EditEmail($this->session_user,$_POST['artist_email']);	
				$preferences_saved = true ;							
			}

			//The artist password might also be changing			
			if( array_key_exists('artist_password_sha1',$_POST) && 
				array_key_exists('artist_new_password_sha1',$_POST) && 
				$_POST['artist_password_sha1'] != '' && $_POST['artist_password_sha1'] != NULL )
			{
				$artist_data['artist_details'] = $this->ArtistEntityModel->GetDetails($this->session_user);
				$this->log->info('[CONTROLLER]Comparing '.$_POST['artist_password_sha1'].' with '.$artist_data['artist_details']['artist_password']);			
				
				if( $_POST['artist_password_sha1'] == $artist_data['artist_details']['artist_password'] )
				{			
					$this->log->info('[CONTROLLER]Editing artist_new_password_sha1');
					$this->ArtistEntityModel->EditPassword($this->session_user,$_POST['artist_new_password_sha1']);
					$preferences_saved = true ;
				}
			}
			
			if( $preferences_saved == true )
			{
				$this->log->info('[CONTROLLER] Returning SUCCESS status for preferences-save');
				$this->AjaxResponse(SUCCESS);				
			}
				
		}
		catch(Exception $e)
		{
			echo $this->_OnError($e);
		}
	}
	
}

/* End of file Artist.php */