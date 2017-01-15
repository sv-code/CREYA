<?php
/**
 * Upload Controller Class
 * 
 * Purpose
 * 		Controls flash uploads from 'Uploadify'
 * Created 
 * 		November 7, 2009
 * 
 * @package	Lightbox
 * @subpackage	Controllers
 * @category	none
 * @author		venksster
 */
require CONTROLLER_BASE_PATH.'Abstract.LightboxController'.EXT;

class Upload extends AbstractLightboxController
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @param 	void
   	* @return	void 			
   	*/
	public function Upload()
	{
		parent::AbstractLightboxController('upload');
	//	$this->load->model('AuthModel');
		$this->load->library('CGraphics');
	}	
	
	public function UploadPhoto()
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
	
	/**
   	* Adds the artist avatar
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function UploadArtistAvatar()
	{
		try
		{
			$this->log->info("[CONTROLLER] UploadArtistAvatar");
			
			//set the upload path
			$uploadDir = ARTIST_AVATAR_ORIGINAL_PATH;
			$this->log->debug("[CONTROLLER] Arguments to UploadImage, ".$uploadDir.","."cgraphics_create_artist_avatar_image_stack");
			$uploadedFileName =  $this->UploadImage($uploadDir,'cgraphics_create_artist_avatar_image_stack') ;
			
			$this->log->info("[CONTROLLER] UploadArtistAvatar - uploaded file name is ".$uploadedFileName);
			
			$avatar_dimensions = $this->config->item(ARTIST_AVATAR_CAPTURE);
			
			$this->log->info("[CONTROLLER] UploadArtistAvatar - Checking image dimensions for artist avatar");
			$this->log->info("[CONTROLLER] x=".$avatar_dimensions['x'].",y=".$avatar_dimensions['y']);
			
			//check if this image is lesser than 225x225
			if( !cgraphics_is_min_size(ARTIST_AVATAR_ORIGINAL_PATH.$uploadedFileName,$avatar_dimensions['x'],$avatar_dimensions['y']) )
			{
				//$uploadedFileName = "/".ARTIST_AVATAR_CAPTURE_PATH.GROUP_PREVIEW_PANORAMA.'.'.$uploadedFileName;
				$this->AjaxResponse(ERROR_CGRAPHICS_MIN_SIZE,$uploadedFileName);
			}			
			else
			{
				//$uploadedFileName = "/".ARTIST_AVATAR_CAPTURE_PATH.GROUP_PREVIEW_PANORAMA.'.'.$uploadedFileName;
				$this->AjaxResponse(SUCCESS_WITH_MESSAGE,$uploadedFileName);
			}			
			
			//$this->AjaxResponse(SUCCESS,$uploadedFileName);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}	
	
	
	/**
   	* Upload a discussion image
   	* 
   	* @type	DISPLAY_REDIRECT
   	* 
   	* @access	public
   	* @return	void 			
   	*/	
	public function UploadDiscussionImage()
	{
		try
		{
			$this->log->info("[CONTROLLER] UploadDiscussionImage");
			
			//set the upload path
			$uploadDir = COMMUNITY_DISC_ORIGINAL_PATH;
			$this->log->debug("[CONTROLLER] Arguments to UploadDiscussionImage, ".$uploadDir.","."cgraphics_create_discussion_capture_image");
			$uploadedFileName =  $this->UploadImage($uploadDir,'cgraphics_create_discussion_capture_image') ;
			
			$uploadedFileName = "/".COMMUNITY_DISC_CAPTURE_PATH.DISCUSSION_CAPTURE.'.'.$uploadedFileName;
			$this->log->info("[CONTROLLER] UploadDiscussionImage - uploaded file name is ".$uploadedFileName);
			$this->AjaxResponse(SUCCESS_WITH_MESSAGE,$uploadedFileName);
		}
		catch(Exception $e)
		{
			$this->_OnError($e);
		}		
	}	
	
	/**
   	* Upload the group panorama image
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @return	imageFileName 			
   	*/	
	public function UploadGroupImage()
	{
		try
		{
			$this->log->info("[CONTROLLER] UploadGroupImage");
			
			//set the upload path
			$uploadDir = GROUP_PREVIEW_ORIGINAL_PATH;
			$this->log->debug("[CONTROLLER] Arguments to UploadImage, ".$uploadDir.","."cgraphics_create_group_preview_image_stack");
			$uploadedFileName =  $this->UploadImage($uploadDir,'cgraphics_create_group_preview_image_stack') ;
			
			$this->log->info("[CONTROLLER] UploadGroupImage - uploaded file name is ".$uploadedFileName);
			//echo $uploadedFileName;

			$panorama_dimensions = $this->config->item(GROUP_PREVIEW_PANORAMA);
			
			$this->log->info("[CONTROLLER] UploadGroupImage - Checking image dimensions for group preview");
			$this->log->info("[CONTROLLER] x=".$panorama_dimensions['x'].",y=".$panorama_dimensions['y']);
			
			//check if this image is lesser than 600x350
			if( !cgraphics_is_min_size(GROUP_PREVIEW_ORIGINAL_PATH.$uploadedFileName,$panorama_dimensions['x'],$panorama_dimensions['y']) )
			{
				$uploadedFileName = "/".GROUP_PREVIEW_PANORAMA_PATH.GROUP_PREVIEW_PANORAMA.'.'.$uploadedFileName;
				$this->AjaxResponse(ERROR_CGRAPHICS_MIN_SIZE,$uploadedFileName);
			}			
			else
			{
				$uploadedFileName = "/".GROUP_PREVIEW_PANORAMA_PATH.GROUP_PREVIEW_PANORAMA.'.'.$uploadedFileName;
				$this->AjaxResponse(SUCCESS_WITH_MESSAGE,$uploadedFileName);
			}
		}
		catch(Exception $e)
		{
			$this->_OnError($e);
		}		
	}	
	
	
	public function UploadGroupDiscussionImage()
	{
		try
		{
			$this->log->info("[CONTROLLER] UploadGroupDiscussionImage");
			
			//set the upload path
			$uploadDir = GROUP_DISCUSSION_ORIGINAL_PATH;
			$this->log->debug("[CONTROLLER] Arguments to UploadGroupDiscussionImage, ".$uploadDir.","."cgraphics_create_group_discussion_image_stack");
			$uploadedFileName =  $this->UploadImage($uploadDir,'cgraphics_create_group_discussion_image_stack') ;
			
			$uploadedFileName = "/".GROUP_DISCUSSION_CAPTURE_PATH.GROUP_DISCUSSION_CAPTURE.'.'.$uploadedFileName;			
			$this->log->info("[CONTROLLER] UploadGroupImage - uploaded file name is ".$uploadedFileName);
			$this->AjaxResponse(SUCCESS_WITH_MESSAGE,$uploadedFileName);
		}
		catch(Exception $e)
		{
			$this->_OnError($e);
		}		
	}	
	
}

/* End of file Session.php */