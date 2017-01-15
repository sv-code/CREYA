<?php
/**
 * Group Controller Class
 * 
 * Purpose
 * 		Controls the 'Group' section
 * Created 
 * 		April 1, 2009
 * 
 * @package	Lightbox
 * @subpackage	Controllers
 * @category	none
 * @author		venksster
 */
require CONTROLLER_BASE_PATH.'Abstract.PhotoGalleryController'.EXT;

class Group extends AbstractPhotoGalleryController
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @param 	void
   	* @return	void 			
   	*/
	public function Group()
	{
		parent::AbstractPhotoGalleryController('group');
		$this->load->model('GroupDiscussionCollectionModel');
		$this->load->model('GroupDiscussionEntityModel');
		$this->load->model('PhotoCollectionModel');	
		$this->load->model('GroupEntityModel');
		$this->load->model('PhotoModel');	
		$this->load->library('CGraphics');			
		
	}
  
	/**
   	* ENTRY POINT
   	* 
   	* Displays:the GROUP
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @param 	int $gid	GROUP_ID		
   	* @return	void 			
   	*/
	public function Index($gid)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----LOADING GROUP:'.$gid.'-----');
			$data['gid'] = $gid;
			
			$this->log->fine('[CONTROLLER] ADDING GROUP DETAILS');
			$data['group_details']= $this->GroupEntityModel->GetDetails($gid);
			$data['group_name']= $data['group_details']['group_name'];
			
			$this->log->fine('[CONTROLLER] ADDING GROUP CREATOR DETAILS');
			$group_creator = $data['group_details']['artist_dname'];
			$this->load->model('ArtistEntityModel');
			$data['group_creator_details'] = $this->ArtistEntityModel->GetDetails($group_creator);	
			
			$this->log->fine('[CONTROLLER] ADDING GROUP RECENTLY ADDED PHOTOS');
			$data['group_recently_added_photos']= $this->GroupEntityModel->GetMostRecentlyAddedPhotos($gid,VIEW_GROUP_NUM_RECENTLY_ADDED_PHOTOS);	
			
			$data['group_slideshow_photo_urls']= array();
			$this->log->fine('[CONTROLLER] ADDING GROUP PREVIEW IMAGE');
			array_push($data['group_slideshow_photo_urls'],cgraphics_image_group_preview_url(GROUP_PREVIEW_PANORAMA,$data['group_details']['group_preview_filename']));
					
			$this->log->fine('[CONTROLLER] ADDING GROUP SLIDSHOW PHOTOS');
			foreach($data['group_recently_added_photos'] as $photo)
			{
				array_push($data['group_slideshow_photo_urls'],cgraphics_image_photo_url(PHOTO_PANORAMA,$photo['PHOTO_ID']));
				array_slice($data['group_slideshow_photo_urls'],0,5);	
			}
			
			$this->log->fine('[CONTROLLER] ADDING GROUP RECENT DISCUSSIONS');
			$data['group_recent_discussions']= $this->GroupDiscussionCollectionModel->GetMostRecentByGroup($gid,VIEW_GROUP_NUM_RECENT_DISCUSSIONS,OFFSET_NONE);
			
			$this->log->fine('[CONTROLLER] ADDING GROUP NEW MEMBERS');
			$data['group_new_members']= $this->GroupEntityModel->GetMostRecentMembers($gid,VIEW_GROUP_NUM_NEW_MEMBERS);	
			
			$data['is_member'] = $this->GroupEntityModel->IsArtistMember($gid,$this->session_user); 
			$this->log->debug('[CONTROLLER] is_member:'.$data['is_member']);
						
			$this->log->info('[CONTROLLER][DISPLAY] GROUP INFO');
			$this->display('View.GROUP.Info',$data);
		}
		catch( Exception $e )
		{
			$this->_OnError($e);
		}
	}
	
	/**
   	* Displays:the GROUP GALLERY page
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @param 	string		$artist_dname		
   	* @return	void 			
   	*/
	public function Gallery($gid,$order_by='recent')
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----LOADING GROUP GALLERY::Order:'.$order_by.'-----');
			$data['gid'] = $gid;
	
			$this->log->info('[CONTROLLER] GROUP-ID:'.$data['gid']);	
			
			$this->log->fine('[CONTROLLER] ADDING GROUP DETAILS');
			$data['group_details']= $this->GroupEntityModel->GetDetails($gid);
			$data['group_name']= $data['group_details']['group_name'];
			
			$page = $this->UtilController->GetPage($_POST);
			$this->log->info('[CONTROLLER] PAGE:'.$page); 
			
			$filter_name_tag_keywords 	= $this->GetPhotoFilterKeywords($_POST);
			$this->log->info('[CONTROLLER] PHOTO KEYWORDS:'.print_r($filter_name_tag_keywords,true));
			
			$filter_photo_property_array	= $this->GetPhotoPropertyArray($_POST);
			$this->log->info('[CONTROLLER] PHOTO FILTERS:'.print_r($filter_photo_property_array,true));
			
			$this->log->fine('[CONTROLLER] CALCULATING OFFSET');
			$view_num_photos = VIEW_GALLERY_PHOTOS_NUM_COLUMNS * VIEW_GALLERY_PHOTOS_NUM_COLUMNS;
			$offset = ($page - 1) * $view_num_photos;
			$this->log->debug('[CONTROLLER] $offset:'.$offset);
			
			$this->log->fine('[CONTROLLER] ADDING PHOTOS');
			$data['current_page']		= $page;
			$data['start_photo']		= $offset + 1;
			$data['total_photo_count']	= $this->PhotoCollectionModel->GetEntityCountByGroup($gid,$filter_name_tag_keywords,$filter_photo_property_array);
			$data['page_count']		= $data['total_photo_count'] == 0 ? 0 : ceil($data['total_photo_count'] / $view_num_photos);
			$data['photos'] 			= $data['total_photo_count'] == 0 ? array() : $this->PhotoCollectionModel->GetMostRecentByGroup($gid,$view_num_photos,$offset,$filter_name_tag_keywords,$filter_photo_property_array);
			//$data['photos'] 		= $this->PhotoCollectionModel->GetMostRecent($view_num_photos,$offset,$filter_name_tag_keywords,$filter_photo_property_array);
			
			$this->log->info('RETRIEVING PHOTO GENRES');
			$data['photo_type_array'] = $this->PhotoModel->GetPhotoTypes();
			sort($data['photo_type_array']);
			
			if($this->session_user!=null && count($data['photos']))
			{
				$this->AddBookmarkInfo($data['photos'],$this->session_user);
			}
			
			$data['is_member'] = $this->GroupEntityModel->IsArtistMember($gid,$this->session_user); 
			$this->log->debug('[CONTROLLER] is_member:'.$data['is_member']);
						
			$this->log->info('[CONTROLLER][DISPLAY] GROUP GALLERY');
			$this->display('View.GROUP.Photo_Gallery',$data);
		}
		catch( Exception $e )
		{
			$this->_OnError($e);
		}
	}   
	
	/**
   	* Changes/edits the group description
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function EditDescription()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----EDIT GROUP DESCRIPTION-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('group_description','gid'));
			
			$this->GroupEntityModel->EditDescription($_POST['gid'],$_POST['group_description']);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}	
	
	/**
   	* Displays:GROUP DISCUSSIONS, 
   	* sorted by default sort order:ORDER_DATE
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @param 	int		$page		
   	* @return	void 			
   	*/
	public function DiscussionExplore($gid)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----LOADING GROUP DISCUSSIONS::PAGE:-----');
			$data['gid'] = $gid;
			
			$this->log->fine('[CONTROLLER] ADDING GROUP DETAILS');
			$data['group_details']= $this->GroupEntityModel->GetDetails($gid);
			$data['group_name']= $data['group_details']['group_name'];
			
			$page = $this->UtilController->GetPage($_POST);
			$this->log->info('[CONTROLLER] PAGE:'.$page); 
			
			$filter_keywords = $this->UtilController->GetFilterKeywords($_POST);
			$this->log->info('[CONTROLLER] SEARCH KEYWORDS:'.$filter_keywords); 
			
			$this->log->fine('[CONTROLLER] CALCULATING OFFSET');
			$offset = ($page - 1) * VIEW_GROUP_NUM_DISCUSSIONS;
			$this->log->debug('[CONTROLLER] $offset:'.$offset);
			
			$data_array = array();
			
			$this->log->fine('[CONTROLLER] ADDING DISCUSSIONS TO DATA_ARRAY');
			$data['current_page']		= $page;
			$data['start_disc']		= $offset + 1;
			$data['total_disc_count']	= $this->GroupDiscussionCollectionModel->GetEntityCountByGroup($gid);
			$data['page_count']		= $data['total_disc_count'] == 0 ? 0 : ceil($data['total_disc_count'] / VIEW_GROUP_NUM_DISCUSSIONS); 
			$data['discs'] 			= $data['total_disc_count'] == 0 ? array() : $this->GroupDiscussionCollectionModel->GetMostRecentByGroup($gid,VIEW_GROUP_NUM_DISCUSSIONS,$offset,$filter_keywords);
			 
			// Converting the timestamp in the comments to relative date values
			$relative_date_mapping = $this->config->item('relative_date_mapping');
			krsort($relative_date_mapping);
			//$this->log->debug('relative_date_mapping:'.print_r($relative_date_mapping,true));
			foreach($data['discs'] as &$disc)
			{			
				$disc['disc_date'] = $this->cutil->ReturnRelativeDateStamp($disc['disc_date'],$relative_date_mapping);
				$disc['disc_comment_count'] = $this->GroupDiscussionEntityModel->GetCommentCount($disc['DISC_ID']);				
			}
						
			/*
			$this->log->fine('[CONTROLLER] ADDING GROUP POPULAR DISCUSSIONS');
			$data['disc_popular_array'] = $this->GroupDiscussionCollectionModel->GetMostPopular(VIEW_GROUP_NUM_POPULAR_DISCUSSIONS,OFFSET_NONE);
			*/
			
			//$this->log->fine('[CONTROLLER] ADDING ACTIVE MEMBERS');
			//$data['active_members'] = $this->GroupDiscussionCollectionModel->GetMostActiveArtists(1,VIEW_COMMUNITY_DISCUSSION_NUM_ACTIVE_MEMBERS);
			
			$data['is_member'] = $this->GroupEntityModel->IsArtistMember($gid,$this->session_user); 
			
			$this->log->debug('[CONTROLLER] is_member'.$data['is_member']);
						
			$this->log->info('[CONTROLLER][DISPLAY] GROUP DISCUSSION EXPLORE');
			$this->display('View.GROUP.Discussion.Explore',$data);
		}
		catch( Exception $e )
		{
			$this->_OnError($e);
		}
	}  
	
	/**
   	* Displays a GROUP DISCUSSION
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @param 	int		$DISC_ID	
   	* @return	void 			
   	*/
	public function Discussion($gid,$DISC_ID,$comment_page=1)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----LOADING GROUP DISCUSSION::ID:'.$DISC_ID.'::PAGE:'.$comment_page.'-----');
			$data['gid'] = $gid;
			
			$this->log->fine('[CONTROLLER] ADDING GROUP DETAILS');
			$data['group_details']= $this->GroupEntityModel->GetDetails($gid);
			$data['group_name']= $data['group_details']['group_name'];
					
			$this->log->fine('[CONTROLLER] ADDING GROUP DISCUSSION DETAILS');
			$data['disc_details']= $this->GroupDiscussionEntityModel->GetDetails($DISC_ID);
			
			// Converting the timestamp in the comments to relative date values
			$relative_date_mapping = $this->config->item('relative_date_mapping');
			krsort($relative_date_mapping);
			$this->log->debug('relative_date_mapping:'.print_r($relative_date_mapping,true));
			$data['disc_details']['disc_date'] = $this->cutil->ReturnRelativeDateStamp($data['disc_details']['disc_date'],$relative_date_mapping);			
			
			$this->log->fine('[CONTROLLER] ADDING GROUP DISCUSSION CREATOR DETAILS');
			$disc_creator = $data['disc_details']['artist_dname'];
			$this->load->model('ArtistEntityModel');
			$data['disc_creator_details'] = $this->ArtistEntityModel->GetDetails($disc_creator);	
			
			$this->log->fine('[CONTROLLER] ADDING GROUP DISCUSSION COMMENTS');
			$data['comment_array'] = $this->UtilController->GetCommentsByPage($DISC_ID,$comment_page,VIEW_GROUP_DISCUSSION_NUM_COMMENTS,$this->GroupDiscussionEntityModel);
					
			$this->log->fine('[CONTROLLER] ADDING GROUP ACTIVE MEMBERS');
			$data['active_members'] = $this->GroupDiscussionEntityModel->GetMostActiveArtists($DISC_ID,VIEW_GROUP_DISCUSSION_NUM_ACTIVE_MEMBERS);
			
			//$this->log->fine('[CONTROLLER] ADDING RELATED DISCUSSIONS');
			//$data['discs_related'] = $this->GroupDiscussionCollectionModel->GetSimilar($DISC_ID,VIEW_GROUP_DISCUSSION_NUM_RELATED_DISCUSSIONS);
			
			$data['is_member'] = $this->GroupEntityModel->IsArtistMember($gid,$this->session_user); 
			$this->log->debug('[CONTROLLER] is_member:'.$data['is_member']);
			
			if($data['disc_details']['disc_tag_list']!=TAG_NONE)
			{
				$this->log->debug('[CONTROLLER] disc_tag_list:'.$data['disc_details']['disc_tag_list']);
				
				$this->log->fine('[CONTROLLER] ADDING DISCUSSION TAGS');
				$data['disc_tags'] = explode(LIGHTBOX_TAG_LIST_DELIMITER,$data['disc_details']['disc_tag_list']);	
				$this->log->debug('[CONTROLLER] disc_tags:'.print_r($data['disc_tags'],true));	
			}
			else
			{
				$data['disc_tags'] = null;
			}	
			
			$data['disc_image_attachments'] = explode(LIGHTBOX_IMAGE_ATTACHMENT_LIST_DELIMITER,$data['disc_details']['disc_image_attachments']);		
				
			$this->log->debug('[CONTROLLER] Discussion Attachments '.print_r($data['disc_image_attachments'],true));
			$this->log->info('[CONTROLLER][DISPLAY] GROUP DISCUSSION');
			$this->display('View.GROUP.Discussion',$data);
		}
		catch( Exception $e )
		{
			$this->_OnError($e);
		}		
	}
	

	
	/**
   	* Displays:the GROUP CREATION page
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function Create()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----CREATE A GROUP-----');
			
			$this->VerifyActiveSession();
			
			$data= array();
			
			// Check for group_name
			if($group_name=$this->IsPostDataAvailable($_POST,'group_name')!=null)
			{
			//	$group_name='land';
			
				$this->log->debug('Looking for groups similar to:'.$_POST['group_name']);
				$this->load->model('GroupCollectionModel');
				$data['interested_entities'] = $this->GroupCollectionModel->GetMostRelevant(RESULTS_ALL,OFFSET_NONE,$_POST['group_name']);
				
				$this->log->debug('Number of interested groups found:#'.count($data['interested_entities']));
			}
			
			/*
			// Check for group_image
			if($group_image=$this->IsPostDataAvailable($_POST,'group_image')!=null)
			{
				$data['group_image_preview'] = $group_image;
				if( $group_name=$this->IsPostDataAvailable($_POST,'group_image') !=null )
				{	
					$data['group_name'] = $group_name;
				}
				
				if( $group_desc=$this->IsPostDataAvailable($_POST,'') !=null )
				{	
					$data['group_desc'] = $group_desc;
				}				
			}
			*/
									
			$this->display('View.GROUP.Create',$data);
		}
		catch( Exception $e )
		{
			$this->_OnError($e);
		}
	}
	
	/**
   	* Adds a new group
   	* 
   	* @type	DISPLAY_REDIRECT
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function Add()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----ADD GROUP-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('group_name','group_desc','preview_filename'));
			
			$group_data = array
			(
				'group_name'	=>	$_POST['group_name'],
				'group_desc'	=>	$_POST['group_desc'],
				'group_preview_filename' => $_POST['preview_filename']
			);
			
			$this->log->info('[CONTROLLER] Adding group:'.$_POST['group_name']);
			$key = $this->GroupEntityModel->Add($this->session_user,$group_data);
			
			$this->log->info('[CONTROLLER] CREATED GROUP:'.$key);
			redirect('/group/'.$key);
		}
		catch( Exception $e )
		{
			$this->_OnError($e);
		}
	}
	
	/**
   	* Displays a gallery modal of all uploaded photos of an artist. The artist may choose to add/remove them to the group
   	* 
   	* @type	DISPLAY_MODAL
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function AddRemovePhotos($gid)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----ADD/REMOVE PHOTOS TO/FROM GROUP-----');
			
			$this->VerifyActiveSession();
			
			$data['gid'] = $gid;
			
			$filter_keywords = FILTER_NONE;
			$filter_type = FILTER_NONE;
			if(isset($_POST) && is_array($_POST) && array_key_exists('filter_keywords',$_POST))
			{
				$filter_keywords = $_POST['filter_keywords'];
				if( $filter_keywords == '' )
				{
					$filter_keywords = FILTER_NONE;
				}
				$this->log->info('[CONTROLLER] filter keywords:'.$filter_keywords);
			}
			
			if(isset($_POST) && is_array($_POST) && array_key_exists('filter_type',$_POST))
			{
				$filter_type = $_POST['filter_type'];
				if( $filter_type == '' )
				{
					$filter_type = FILTER_NONE;
				}
				$this->log->info('[CONTROLLER] filter type:'.$filter_type);				
			}
			
			$data['photos'] = $this->PhotoCollectionModel->GetMostRecentByArtist($this->session_user,RESULTS_ALL,OFFSET_NONE,$filter_keywords);
			$this->log->debug('[CONTROLLER] Artist photo count:'.count($data['photos']));
			
			if(count($data['photos'])==0)
			{
				//throw new Exception("Artist has not uploaded any photos");
			}
			
			$this->MarkPhotosAddedToGroup($data['photos'],$gid);
			
			//Do a final processing on the photos now
			if( $filter_type == "added" )
			{
				$filtered_list = array();				
				//send back only the added ones
				foreach($data['photos'] as $photo)
				{
					if( $photo['IsAdded'] == true )
					{
						array_push($filtered_list,$photo);
						//$this->log->debug('[CONTROLLER] Keeping photo '.$photo['PHOTO_ID']);
					}
				}
				$data['photos'] = $filtered_list; 
			}
			
			$this->log->info('[CONTROLLER][MODAL] ADD/REMOVE PHOTOS TO/FROM GROUP');
			$this->load->view('Modal.MiniGallery.AddRemove.Group_Photo',$data);
		}
		catch( Exception $e )
		{
			$this->_OnError($e);
		}
	}	
	
	/**
   	*  @type	GET
   	* 
   	* @access	protected
   	* @return	void 			
   	*/
	protected function MarkPhotosAddedToGroup(&$photos,$gid)
	{
		$this->log->fine('[CONTROLLER] Marking photos already added to group');
			
		foreach($photos as &$photo)
		{
			$photo['IsAdded'] = $this->GroupEntityModel->IsPhotoAdded($gid,$photo['PHOTO_ID']); 
		}
	}
	
	/**
   	* Adds a photo to a group
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @param	int $gid
   	* @return	void 			
   	*/
	public function AddPhoto($gid)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----ADDING PHOTO TO GROUP-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('pid'));
			
			$this->GroupEntityModel->AddPhoto($gid,$_POST['pid']);
		}
		catch( Exception $e )
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* Removes a photo from a group
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @param	int $gid
   	* @return	void 			
   	*/
	public function RemovePhoto($gid)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----REMOVING PHOTO FROM GROUP-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('pid'));
			
			$this->GroupEntityModel->RemovePhoto($gid,$_POST['pid']);
		}
		catch( Exception $e )
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* Join a group
   	* 
   	* @type	DISPLAY_REDIRECT
   	* 
   	* @access	public
   	* @param	int $gid
   	* @return	void 			
   	*/
	public function Join($gid)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----JOINING GROUP-----');
			
			$this->VerifyActiveSession();
			
			$this->GroupEntityModel->AddArtist($gid,$this->session_user);
			
			return $this->Index($gid);
		}
		catch( Exception $e )
		{
			$this->_OnError($e);
		}
	}
	
	/**
   	* Leave a group
   	* 
   	* @type	DISPLAY_REDIRECT
   	* 
   	* @access	public
   	* @param	int $gid
   	* @return	void 			
   	*/
	public function Leave($gid)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----LEAVING GROUP-----');
			
			$this->VerifyActiveSession();
			
			$this->GroupEntityModel->RemoveArtist($gid,$this->session_user);
			
			return $this->Index($gid);
		}
		catch( Exception $e )
		{
			$this->_OnError($e);
		}
	}
	
	/**
   	* Create a group discussion
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @param	int $gid
   	* @return	void 			
   	*/
	public function CreateDiscussion($gid)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----CREATE GROUP DISCUSSION-----');
			
			$this->VerifyActiveSession();
			
			$data['gid'] = $gid;
			$data['discussion_entity'] = $gid;
			
			// Checking if the artist is a member of this group
			if(!($is_member=$this->GroupEntityModel->IsArtistMember($gid,$this->session_user)))
			{
				throw new Exception('[CONTROLLER] '.$this->session_user. ' is not a member of group:'.$gid);
			}			
			
			$this->log->debug('[CONTROLLER] is_member:'.$is_member);
			$data['is_member'] = $is_member;
			$data['base_url'] = "/group/discussion";
									
			$this->log->fine('[CONTROLLER] ADDING GROUP DETAILS');
			$data['group_details']= $this->GroupEntityModel->GetDetails($gid);
			$data['group_name']= $data['group_details']['group_name'];
					
			if(isset($_POST) && is_array($_POST) && array_key_exists('disc_title',$_POST) && $_POST['disc_title']!='')
			{
				$this->log->info('LOOKING FOR DISCUSSIONS SIMILAR TO:'.$_POST['disc_title']);
				
				$data['discs_interested'] = $this->GroupDiscussionCollectionModel->GetMostRelevant(RESULTS_ALL,OFFSET_NONE,$_POST['disc_title']);
				$this->log->debug('Number of similar discussions found:'.count($data['discs_interested']));
			}
			else
			{
				$data['discs_interested'] = $this->GroupDiscussionCollectionModel->GetMostRecent($count=10,OFFSET_NONE);
			}
			
			$this->log->info('[CONTROLLER][DISPLAY] CREATE GROUP DISCUSSION');
			$this->display('View.GROUP.Discussion.Create',$data);
		}
		catch( Exception $e )
		{
			$this->_OnError($e);
		}
	}
	
	/**
   	* Create a group discussion
   	* 
   	* @type	DISPLAY_REDIRECT
   	* 
   	* @access	public
   	* @param	int $gid
   	* @return	void 			
   	*/
	public function AddDiscussion()
	{
		try
		{
			
			$this->log->info('[CONTROLLER][ENTRY] -----ADDING GROUP DISCUSSION-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('discussion_entity','discussion_body','discussion_title'));
			
			$disc_image_attachments_external = array_key_exists('disc_image_attachments_external', $_POST)? $_POST['disc_image_attachments_external']:null;
			$disc_image_attachments_internal = array_key_exists('disc_image_attachments_internal', $_POST)? $_POST['disc_image_attachments_internal']:null;
			
			$disc_image_attachments = $disc_image_attachments_internal.','.$disc_image_attachments_external;						
	
			$this->log->debug('[CONTROLLER]Group discussion input:'.print_r($_POST,true));
			
			$key = $this->GroupDiscussionEntityModel->Create($_POST['discussion_entity'],$this->session_user,$_POST['discussion_title'],$_POST['discussion_body'],$disc_image_attachments);
			
			$this->log->info('[CONTROLLER] CREATED DISCUSSION:'.$key);
			$this->log->info('[CONTROLLER] ADDING TAGS FOR DISCUSSION:'.$key);									
			$this->UtilController->AddTags($key,$this->GroupDiscussionEntityModel,$_POST['discussion_tags']);			
			$this->AjaxResponse(SUCCESS,'/group/'.$_POST['discussion_entity'].'/post/'.$key);
			
			//$this->AjaxResponse(SUCCESS,"1");
			//echo($key);
			//redirect('/group/'.$_POST['gid'].'/discussion/'.$key);
		}
		catch( Exception $e )
		{
			$this->_OnError($e);
		}
	}
	
	/**
   	* Add a comment to a group discussion
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @param	int $gid
   	* @return	html 			
   	*/
	public function PostDiscussionComment()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----POST GROUP DISCUSSION COMMENT-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('gid','entity','comment_text'));
			
			$disc_attachment = array_key_exists('discussion_attachment', $_POST)? $_POST['discussion_attachment']:null;
	
			$this->GroupDiscussionEntityModel->AddComment($_POST['entity'],$this->session_user,$_POST['comment_text'],$disc_attachment);
			
			return $this->Discussion($_POST['gid'],$_POST['entity']);
		}
		catch( Exception $e )
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/**
   	* Deletes a comment from the group discussion page
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	int 			
   	*/
	public function DeleteDiscussionComment()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----DELETING COMMENT-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('entity','comment_id'));
					
			$this->log->fine('[CONTROLLER][ENTRY] Deleting new comment for artist:'.$_POST['entity']);
			$this->GroupDiscussionEntityModel->DeleteComment($_POST['entity'],$_POST['comment_id']);
			
			//$this->log->fine('[CONTROLLER][ENTRY] Returning profile page for artist:'.$_POST['for_artist_dname']);		
			//return $this->Index($_POST['for_artist_dname']);
			echo 0;
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}	
	
	/*
	 * Delete a general discussion from the community explore page
	 */
	public function DeleteDiscussion($gid)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----DELETING DISCUSSION IN GROUP:'.$gid.'-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('entity_id'));
			
			if($gid==null)
			{
				throw new Exception('[CONTROLLER] gid cannot be null');
			}

			$this->log->fine('[CONTROLLER][ENTRY] Deleting discussion:'.$_POST['entity_id']);
			$this->GroupDiscussionEntityModel->Delete($_POST['entity_id']);
			$this->AjaxResponse(SUCCESS);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}	

	
	public function AddTag()
	{
		try
		{
			$this->log->info("[CONTROLLER] AddTag");
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('id','tag'));
						
			$this->GroupDiscussionEntityModel->AddTag($_POST['id'],$_POST['tag']);

			$this->AjaxResponse(SUCCESS);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}			
	}
	
	public function DeleteTag()
	{
		try
		{
			$this->log->info("[CONTROLLER] DeleteTag");
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('id','tag'));
						
			$this->GroupDiscussionEntityModel->DeleteTag($_POST['id'],$_POST['tag']);
			$this->AjaxResponse(SUCCESS);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}			
	}


}

/* End of file Group.php */