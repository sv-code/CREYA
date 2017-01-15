<?php
/**
 * Photo Class
 * 
 * Purpose
 * 		'The Photograph' Controller
 * Created 
 * 		Nov 23, 2008
 * 
 * @package		Lightbox
 * @subpackage	Controllers
 * @category	none
 * @author		venksster
 * @link		TBD
 */
require CONTROLLER_BASE_PATH.'Abstract.LightboxController'.EXT;

class Photo extends AbstractLightboxController
{
	public function Photo()
	{
		parent::AbstractLightboxController('photo');
		$this->load->model('GroupCollectionModel');
		$this->load->model('PhotoEntityModel');
		$this->load->model('PhotoModel');
		$this->load->model('ReviewRequestCommunityDiscussionEntityModel');		
		$this->load->library('CGraphics');
	}
	
	/**
   	* ENTRY POINT
   	* 
   	* Displays:the PHOTO
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @param 	int $pid	PHOTO_ID		
   	* @return	void 			
   	*/
	public function Index($pid)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----LOADING PHOTO:'.$pid.'-----');
			
			$this->log->fine('[CONTROLLER] ADDING PHOTO DETAILS');
			$data['photo_details'] 	= $this->PhotoEntityModel->GetDetails($pid); 
			$data['artist_dname']	= $data['photo_details']['artist_dname'];
			
			$this->log->fine('[CONTROLLER] ADDING PHOTO BOOKMARK INFO');
			$data['is_bookmarked'] = false;
			if($this->session_user)
			{
				$data['is_bookmarked'] = $this->PhotoEntityModel->IsBookmarked($pid,$this->session_user);
			}
			
			$this->log->fine('[CONTROLLER] ADDING PHOTO COMMENTS');
			//$data['photo_comments'] = $this->PhotoEntityModel->GetComments($pid);
			$data['comment_array'] = $this->UtilController->GetCommentsByPage($pid,$comment_page=1,VIEW_PHOTO_NUM_COMMENTS,$this->PhotoEntityModel);
			
			$this->log->fine('[CONTROLLER] ADDING PHOTO OVERALL RATING');
			/*
			$data['display_photo_ratings'] = true;	
			$data['photo_overall_rating'] = $this->PhotoEntityModel->GetOverallRating($pid);
			$data['photo_rate_count'] = $this->PhotoEntityModel->GetRatingCount($pid);
			
			//$data['has_artist_rated'] = $this->PhotoEntityModel->IsRatedByArtist($pid,$this->session_user);		
			if($data['has_artist_rated'] || $data['photo_details']['artist_dname']==$this->session_user)
			{
				$this->log->fine('[CONTROLLER] ADDING PHOTO METRIC RATINGS');
				if($this->PhotoEntityModel->IsRated($pid))
				{
					$data['photo_metric_ratings'] = $this->PhotoEntityModel->GetAllMetricRatings($pid);
				}
				else
				{
					$data['display_photo_ratings'] = false;	
				}
			}
			else
			{
				$this->load->model('StatsModel');
				$this->log->fine('[CONTROLLER] ADDING LIGHTBOX RATING METRICS');
				$data['lightbox_metrics'] = $this->StatsModel->GetMIDs();
				$data['lightbox_metric_suggestions'] = $this->StatsModel->GetSIDs();		
			}
			*/
			
			
			$this->log->fine('[CONTROLLER] ADDING PHOTO TAGS');
			$data['photo_tags_array'] = $this->PhotoEntityModel->GetTags($pid);
			
			$photo_tags = array();
			foreach($data['photo_tags_array'] as $photo_tag)
			{
				array_push($photo_tags,$photo_tag['tag_name']);
			}
			$data['photo_tags'] = $photo_tags;
			
			$this->log->fine('[CONTROLLER] ADDING GROUPS THAT CONTAIN PHOTO');
			$data['photo_groups'] = $this->GroupCollectionModel->GetGroupsHavingPhoto($pid);
			
			$this->log->fine('[CONTROLLER] ADDING PHOTO EXIF');
			$data['photo_exif'] = $this->PhotoEntityModel->GetEXIFData($pid);
			
			$this->load->model('PhotoCollectionModel');
			$this->log->fine('[CONTROLLER] ADDING MORE PHOTOS FROM ARTIST:'.$data['artist_dname']);
			$photo_count = $this->PhotoCollectionModel->GetEntityCountByArtist($data['artist_dname']);
			
			// @todo bug:algo
			$offset_max = $photo_count - VIEW_PHOTO_MORE;
			if( $offset_max <= 0 )
			{
				$offset_max = 0;				
			}

			$data['more_photos'] = $this->PhotoCollectionModel->GetMostRecentByArtist($data['artist_dname'],
										$num_photos=VIEW_PHOTO_MORE,
										$offset=rand(0,$offset_max)
										);
					
			$data['review_request_id'] = $this->ReviewRequestCommunityDiscussionEntityModel->GetReviewRequestId($pid);		
			$this->log->fine('[CONTROLLER] ADDING REVIEW REQUEST ID :'.$data['review_request_id']);			
					
			$this->log->info('[CONTROLLER][DISPLAY] THE PHOTO');
			$this->display('View.PHOTO',$data);
		}
		catch( Exception $e )
		{
			return $this->_OnError($e);
		}
	}
	

  
  	/**
   	* Post a comment for a photo
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
			$this->log->info('[CONTROLLER][ENTRY] -----POST COMMENT-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('entity','comment_text'));
			
			$this->PhotoEntityModel->AddComment($_POST['entity'],$this->session_user,$_POST['comment_text']);
					
			return $this->Index($_POST['entity']);
		}
		catch( Exception $e )
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* Deletes a comment from a photo
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
			$this->PhotoEntityModel->DeleteComment($_POST['entity'],$_POST['comment_id']);
			
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
   	* Delete a tag from a photo
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	int 			
   	*/
	public function DeleteTag()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----DELETE TAG-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('id','tag'));
			
			$this->PhotoEntityModel->DeleteTag($_POST['id'],$_POST['tag']);
			$this->AjaxResponse(SUCCESS);			
		}
		catch( Exception $e )
		{
			$this->AjaxExceptionResponse($e);
		}
	}	
	
	/**
   	* Add a tag to a photo
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	int 			
   	*/
	public function AddTag()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----ADD TAG-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('id','tag'));
			
			$this->PhotoEntityModel->AddTag($_POST['id'],$_POST['tag']);
			$this->AjaxResponse(SUCCESS);			
		}
		catch( Exception $e )
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* Upload photos
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function Upload()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----UPLOAD-----');
			
			$this->VerifyActiveSession();
			
			$this->log->info('RETRIEVING PHOTO GENRES');
			$data['photo_type_array'] = $this->PhotoModel->GetPhotoTypes();
			
			$this->log->fine('Sorting photo types in ascending order');
			sort($data['photo_type_array']);
			
			$this->log->info('[CONTROLLER][DISPLAY] UPLOAD PHOTOS');
			$this->display('View.PHOTO.Upload',$data);
		}
		catch( Exception $e )
		{
			return $this->_OnError($e);
		}
	}
	
	public function UploadPhotos()
	{
		try
		{
			$this->log->info("[CONTROLLER][ENTRY] UploadPhotos");
			
			//set the upload path
			$uploadDir = PHOTO_ORIGINAL_PATH;
			$this->log->debug("[CONTROLLER] Arguments to UploadImage, ".$uploadDir.","."cgraphics_create_capture_image");
			$uploadedFileName =  $this->UploadImage($uploadDir,'cgraphics_create_capture_image') ;
			
			$this->log->info("[CONTROLLER] UploadPhotos - uploaded file name is ".$uploadedFileName);
			//echo PHOTO_CAPTURE_PATH.PHOTO_CAPTURE.'.'.$uploadedFileName;
			$this->AjaxResponse(SUCCESS_WITH_MESSAGE,"/".PHOTO_CAPTURE_PATH.PHOTO_CAPTURE.'.'.$uploadedFileName);			
			
		}
		catch( Exception $e )
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	public function SaveUploadedPhoto()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] SaveUploadedPhoto ');
			$this->log->debug("[PHOTO CONTROLLER] 'PHOTO_ID' is ".$_POST['PHOTO_ID']);
			$this->log->debug("[PHOTO CONTROLLER] 'photo_type' is ".$_POST['photo_type_name']);
			$this->log->debug("[PHOTO CONTROLLER] 'photo_tags' are ".$_POST['photo_tags']);			
			//$this->log->debug("[PHOTO CONTROLLER] Post array is ".print_r($_POST,true));
			
			$photo_data = array (
									'PHOTO_ID' => $_POST['PHOTO_ID'],
									'photo_type_name'	=>	$_POST['photo_type_name']
								);

			$photo_path = PHOTO_ORIGINAL_PATH;								
			$image_name = $photo_data['PHOTO_ID'].'.jpg';
			if( cgraphics_create_photo_image_stack($image_name,$photo_path) )
			{
				$this->log->debug('[CONTROLLER] Created photo image stack for '.$photo_path.$image_name);
			}
			else
			{
				$this->log->debug('[CONTROLLER] Failed to create photo image stack for '.$photo_path.$image_name);
			}															
								
			$this->PhotoEntityModel->Add($this->session_user,$photo_data,NULL);
			$this->log->info("[PHOTO CONTROLLER] Inserted photos to db ");		
			
			$completePathName = PHOTO_STANDARD_PATH.PHOTO_STANDARD.'.'.$_POST['PHOTO_ID'].'.jpg';
			
			$this->log->debug("[PHOTO CONTROLLER] Generating exif for ".$completePathName);
			$exif_data = cgraphics_get_exif($completePathName);
			
			//insert exif
			if( isset($exif_data) )
			{
				$this->log->debug("[PHOTO CONTROLLER] Exif for PHOTO_ID: ".$_POST['PHOTO_ID']." is ".print_r($exif_data,true));
				try
				{
					$this->PhotoEntityModel->EditPrimaryExifData($_POST['PHOTO_ID'],$exif_data);
					$this->PhotoEntityModel->EditExifData($_POST['PHOTO_ID'],$exif_data);
					$this->log->debug("[PHOTO CONTROLLER] Edited EXIF for ".$_POST['PHOTO_ID']);
				}
				catch(Exception $e)
				{
					$this->log->error($e->getMessage());
					$this->log->error("[PHOTO CONTROLLER] Could not edif EXIF for ".$_POST['PHOTO_ID']);
				}
			}
	
			$this->log->info('[CONTROLLER] ADDING TAGS FOR PHOTO:'.$_POST['PHOTO_ID']);									
			$this->UtilController->AddTags($_POST['PHOTO_ID'],$this->PhotoEntityModel,$_POST['photo_tags']);	
			
			/*	
			//insert tags
			$tags = trim($_POST['photo_tags']);
			if( $tags !=  "" )
			{
				$this->log->debug("[PHOTO CONTROLLER] Adding tags for ".$_POST['PHOTO_ID']);
				$tags_array=preg_split(REGEX_MULTI_WHITESPACE,$tags);			
				
				if(!empty($tags_array))
				{
					$this->log->debug("[PHOTO CONTROLLER] Splitting tag string into ".print_r($tags_array,true));			
					$this->PhotoEntityModel->AddTags($_POST['PHOTO_ID'],$tags_array);
					$this->log->debug("[PHOTO CONTROLLER] Added tags for ".$_POST['PHOTO_ID']);
				}
				else
				{
					$this->log->info("[PHOTO CONTROLLER] No tags specified for ".$_POST['PHOTO_ID']);				
				}
			}
			*/
			
			//echo($this->session_user);
			$this->AjaxResponse(SUCCESS_WITH_MESSAGE,$this->session_user);
		}
		catch( Exception $e )
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* Edits photo name
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	int 			
   	*/
	public function EditName()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----EDIT NAME-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('pid','photo_name'));
			
			$this->PhotoEntityModel->EditName($_POST['pid'],$_POST['photo_name']);
		}
		catch( Exception $e )
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* Adds photo rating
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	html 			
   	*/
	public function AddRating()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----ADD RATING-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('pid','ratings'));
			
			/* NOTE: is_array check fails for json input array */
			/*
			if(!is_array($_POST['ratings']))
			{
				throw new BadPostException('Should be an array:RATINGS');
			}
			*/
			
			$metric_ratings = array();
			$photo_ratings = json_decode($_POST['ratings'],true);
			
			if(!is_array($photo_ratings))
			{
				throw new BadPostException('Should be an array:ratings');
			}
			
			$this->log->debug('[CONTROLLER] Photo Ratings:'.print_r($photo_ratings,true));
			
			foreach($photo_ratings as $key=>$rating)
			{
				$metric_ratings[$key+1] = $rating;
			}
			
			$this->log->fine('[CONTROLLER] Metric Ratings:'.print_r($metric_ratings,true));
			
			$this->PhotoEntityModel->AddRating($_POST['pid'],$this->session_user,$metric_ratings,$suggestions=null);
			return $this->Index($_POST['pid']);
		}
		catch( Exception $e )
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/*
	public function AddBookmark()
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
	
	public function RemoveBookmark()
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
	*/
	
}

/* End of file Photo.php */


	