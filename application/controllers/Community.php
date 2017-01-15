<?php
/**
 * Community Controller Class
 * 
 * Created 
 * 		September 4, 2009
 * 
 * @package	Lightbox
 * @subpackage	Controllers
 * @category	none
 * @author		venksster
 */
require CONTROLLER_BASE_PATH.'Abstract.LightboxController'.EXT;

class Community extends AbstractLightboxController
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @param 	void
   	* @return	void 			
   	*/
	public function Community()
	{
		parent::AbstractLightboxController('community');
		$this->load->model('CommunityDiscussionCollectionModel');
		$this->load->model('CommunityDiscussionEntityModel');
		$this->load->model('ReviewRequestCommunityDiscussionEntityModel');
		$this->load->model('CommunityModel');
		$this->load->model('PhotoEntityModel');		
		$this->load->library('CGraphics');
		//$this->load->library('session');
	}
  
	/**
   	* ENTRY POINT
   	* 
   	* Displays:the COMMUNITY page
   	* 
   	* @access	public
   	* @param 	string		$order_by		
   	* @return	void 			
   	*/
	public function Index($order_by='recent')
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----LOADING COMMUNITY::PAGE:-----');
			
			$page = $this->UtilController->GetPage($_POST);
			$this->log->info('[CONTROLLER] PAGE:'.$page); 
			
			$filter_keywords = $this->UtilController->GetFilterKeywords($_POST);
			$this->log->info('[CONTROLLER] SEARCH KEYWORDS:'.$filter_keywords); 
			
			$this->log->fine('[CONTROLLER] CALCULATING OFFSET');
			$view_num_discussions = VIEW_COMMUNITY_DISCUSSIONS_NUM_COLUMNS * VIEW_COMMUNITY_DISCUSSIONS_NUM_ROWS;
			$offset = ($page - 1) * $view_num_discussions;
			$this->log->debug('[CONTROLLER] $offset:'.$offset);
			
			$this->log->fine('[CONTROLLER] ADDING DISCUSSIONS');
			$data['current_page']		= $page;
			$data['start_disc']		= $offset + 1;
			$data['total_disc_count']	= $this->CommunityDiscussionCollectionModel->GetEntityCount($filter_section=FILTER_NONE,$filter_keywords);
			$data['page_count']		= $data['total_disc_count'] == 0 ? 0 : ceil($data['total_disc_count'] / $view_num_discussions); 
			$data['discs'] 			= $data['total_disc_count'] == 0 ? array() : $this->CommunityDiscussionCollectionModel->GetMostActive($view_num_discussions,$offset,$filter_category=FILTER_NONE,$filter_keywords);
			 
			// Converting the timestamp in the comments to relative date values and exploding tag list
			$relative_date_mapping = $this->config->item('relative_date_mapping');
			krsort($relative_date_mapping);
			//$this->log->debug('relative_date_mapping:'.print_r($relative_date_mapping,true));
			foreach($data['discs']  as &$disc)
			{			
				$disc['disc_date'] = $this->cutil->ReturnRelativeDateStamp($disc['disc_date'],$relative_date_mapping);
				$disc['disc_tags'] = explode(LIGHTBOX_TAG_LIST_DELIMITER,$disc['disc_tag_list']);
				
				$disc_image_attachments = explode(LIGHTBOX_IMAGE_ATTACHMENT_LIST_DELIMITER,$disc['disc_image_attachments']);
				if(isset($disc_image_attachments[0]))
				{				
					$disc['disc_image_attachment_preview'] = $disc_image_attachments[0];
				}
				else
				{
					$disc['disc_image_attachment_preview'] = null;
				}
				
				// comment_count
				$disc['disc_comment_count'] = $disc['SECTION_ID'] == DISCUSSION_SECTION_REVIEW_REQUEST ? $this->ReviewRequestCommunityDiscussionEntityModel->GetCommentCount($disc['DISC_ID']) : $this->CommunityDiscussionEntityModel->GetCommentCount($disc['DISC_ID']);
			}
						
			$this->log->info('[CONTROLLER][DISPLAY] COMMUNITY');
			$this->Display('View.PRIMARY.COMMUNITY.Explore',$data);
		}
		catch( Exception $e )
		{
			$this->_OnError($e);
		}
	}  	
	
	public function AddDiscussionBookmark()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----BOOKMARK DISCUSSION-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('id'));
						
			$this->CommunityDiscussionEntityModel->Bookmark($_POST['id'],$this->session_user);
			
			$this->AjaxResponse(SUCCESS);	
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}		
	}
	
	public function RemoveDiscussionBookmark()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----UNBOOKMARK DISCUSSION-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('id'));
			
			$this->CommunityDiscussionEntityModel->UnBookmark($_POST['id'],$this->session_user);
			$this->AjaxResponse(SUCCESS);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}		
	}	

	/**
   	* Create a group discussion
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function CreateDiscussion()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----CREATE COMMUNITY DISCUSSION-----');
			
			$this->VerifyActiveSession();
			
			$data['base_url'] = "/community/discussion";
			
			//$data['gid'] = $gid;
			
			// Checking if the artist is a member of this group
			/*if(!($is_member=$this->GroupEntityModel->IsArtistMember($gid,$this->session_user)))
			{
				throw new Exception('[CONTROLLER] '.$this->session_user. ' is not a member of group:'.$gid);
			}	
			
			$this->log->debug('[CONTROLLER] is_member:'.$is_member);
			$data['is_member'] = $is_member;
									
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
			*/
			
			$this->load->model('PhotoCollectionModel');
			$data['photos'] = $this->PhotoCollectionModel->GetMostRecentByArtist($this->session_user,RESULTS_ALL,OFFSET_NONE,$filter_keywords=FILTER_NONE);
			$this->log->info('#photos'.count($data['photos']));
			
			$this->log->info('[CONTROLLER][DISPLAY] CREATE COMMUNITY DISCUSSION');
			$this->display('View.COMMUNITY.Discussion.Create',$data);
		}
		catch( Exception $e )
		{
			$this->_OnError($e);
		}
	}	
	
	/**
   	* Create a community discussion
   	* 
   	* @type	DISPLAY_REDIRECT
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function AddDiscussion()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----ADDING COMMUNITY DISCUSSION-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('discussion_title','discussion_body'));
			
			$disc_image_attachments_external = array_key_exists('disc_image_attachments_external', $_POST)? $_POST['disc_image_attachments_external']:null;
			$disc_image_attachments_internal = array_key_exists('disc_image_attachments_internal', $_POST)? $_POST['disc_image_attachments_internal']:null;
			$disc_image_attachments = null;
			
			//use the cgraphics helper on the internal image and external image			
			$internal_manipulation_status = $disc_image_attachments_internal? $this->Create_Discussion_Image_Stack($disc_image_attachments_internal,PHOTO_ORIGINAL_PATH): false;
			if($internal_manipulation_status)
			{
				$disc_image_attachments = $disc_image_attachments_internal;
			}
			
			$external_manipulation_status = $disc_image_attachments_external? $this->Create_Discussion_Image_Stack($disc_image_attachments_external,COMMUNITY_DISC_ORIGINAL_PATH): false;		
			if($external_manipulation_status)
			{
				if($internal_manipulation_status)
				{
					$disc_image_attachments = $disc_image_attachments.',';
				}
				
				$disc_image_attachments = $disc_image_attachments.$disc_image_attachments_external;
			}
			
			$disc_image_attachments = $disc_image_attachments_internal.','.$disc_image_attachments_external;
				
			$key = $this->CommunityDiscussionEntityModel->Create($this->session_user,$_POST['discussion_title'],$_POST['discussion_body'],$disc_image_attachments);
			$this->log->info('[CONTROLLER] CREATED DISCUSSION:'.$key);
			
			$this->log->info('[CONTROLLER] ADDING TAGS FOR DISCUSSION:'.$key);									
			$this->UtilController->AddTags($key,$this->CommunityDiscussionEntityModel,$_POST['discussion_tags']);

			//redirect('/community/discussion/'.$key);
			//$this->AjaxResponse(SUCCESS,$key);
			$this->AjaxResponse(SUCCESS,'/community/post/'.$key);
			
		}
		catch( Exception $e )
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	
	/**
	 * Creates a review request for a photo
	 */
	public function CreateReviewRequest()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----ADDING COMMUNITY REVIEW REQUEST-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('photo_id'));
			
			$photo_id = $_POST['photo_id'];

			//$disc_image_attachments = $disc_image_attachments_internal.','.$disc_image_attachments_external;
			if( cgraphics_create_discussion_image_stack($photo_id.'.jpg',PHOTO_ORIGINAL_PATH) )
			{
				$this->log->debug('[CONTROLLER] Created discussion image stack for '.PHOTO_ORIGINAL_PATH.$photo_id);
				
				$key = $this->ReviewRequestCommunityDiscussionEntityModel->Create($photo_id,$this->session_user);
				
				$this->log->info('[CONTROLLER] CREATED REVIEW REQUEST:'.$key);
				//redirect('/community/discussion/'.$key);
				//$this->AjaxResponse(SUCCESS,$key);
				$this->AjaxResponse(SUCCESS,'/community/reviewrequest/'.$key);				
			}
			else
			{
				$this->log->debug('[CONTROLLER] Failed to create discussion image stack for '.PHOTO_ORIGINAL_PATH.$photo_id);
				$this->AjaxResponse(FAILURE);
			}				

		}
		catch( Exception $e )
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	
	/**
	 * Edit the body of a discussion
	 */
	public function EditDiscussionBody()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----EDIT DISCUSSION BODY-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('disc_body','disc_id'));
			
			$this->CommunityDiscussionEntityModel->EditBody($_POST['disc_id'],$_POST['disc_body']);
			$this->AjaxResponse(SUCCESS);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}		
	}
	
	/**
	 * Edit the body of a title
	 */
	public function EditDiscussionTitle()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----EDIT DISCUSSION TITLE-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('disc_title','disc_id'));
			
			$this->CommunityDiscussionEntityModel->EditTitle($_POST['disc_id'],$_POST['disc_title']);
			$this->AjaxResponse(SUCCESS);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}		
	}	
		
	/**
	 * Submit ratings and review for a photo
	 */
	public function AddReview()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----ADDING REVIEW-----');	
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('discussion_id','review_metric_impact_rating','review_metric_balance_rating',
											'review_metric_technique_rating','review_comment_text'));
											
			//generate associative array after json decoding the crop object											
			$review_crop_coords = json_decode($_POST['review_crop'],true);
			
			if(!is_array($review_crop_coords))
			{
				$this->log->debug('[CONTROLLER] INVALID CROP COORDINATES ARRAY ');
				$this->AjaxResponse(FAILURE);	
				return;			
			}
			
			//if( array_key_exists('crop_x', $review_crop_coords) && array_key_exists('crop_y', $review_crop_coords) && 
			//	array_key_exists('crop_width', $review_crop_coords) && array_key_exists('crop_height', $review_crop_coords) )
			if( count($review_crop_coords) == 4 ) 
			{
				$this->log->debug('[CONTROLLER] CROP PARAMETERS: '.print_r($review_crop_coords,true));							
			}
			else
			{
				$this->log->debug('[CONTROLLER] NO CROP PARAMETERS');	
				$review_crop_coords = 0;
			}
									
			$metric_data['metric_impact']['rating'] = $_POST['review_metric_impact_rating'];										
			$metric_data['metric_impact']['comment'] = $_POST['review_metric_impact_comment_text'];
			
			$metric_data['metric_balance']['rating'] = $_POST['review_metric_balance_rating'];
			$metric_data['metric_balance']['comment'] = $_POST['review_metric_balance_comment_text'];
			
			$metric_data['metric_technique']['rating'] = $_POST['review_metric_technique_rating'];
			$metric_data['metric_technique']['comment'] = $_POST['review_metric_technique_comment_text'];			
			
			$this->ReviewRequestCommunityDiscussionEntityModel->AddReview($_POST['discussion_id'],
																			$this->session_user,$metric_data,
																			$_POST['review_comment_text'],
																			$review_crop_coords);	
																			
			//return $this->Discussion($_POST['discussion_id'],DISCUSSION_SECTION_REVIEW_REQUEST);
			$this->AjaxResponse(SUCCESS,'/community/reviewrequest/'.$_POST['discussion_id']);

		}
		catch( Exception $e )
		{
			$this->AjaxExceptionResponse($e);
		}		
	}
	
	
	/**
	* 
	*/
	private function Create_Discussion_Image_Stack($disc_image_attachments_string,$path)
	{
		$created = true;
		if( $disc_image_attachments_string == TAG_NONE || $disc_image_attachments_string == null )
		{
			return $created;
		}
				
		$disc_image_attachments = explode(LIGHTBOX_TAG_LIST_DELIMITER,$disc_image_attachments_string);

		foreach( $disc_image_attachments as $imageName )
		{
			if( cgraphics_create_discussion_image_stack($imageName.'.jpg',$path) )
			{
				$this->log->debug('[CONTROLLER] Created discussion image stack for '.$path.$imageName);
			}
			else
			{
				$this->log->debug('[CONTROLLER] Failed to create discussion image stack for '.$path.$imageName);
				$created = false;				
			}
		}
		
		return $created;		
	}

	
	public function Discussion($DISC_ID,$discussion_type=DISCUSSION_SECTION_DEFAULT,$discussion_dummy_paramater=null)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----LOADING DISCUSSION::ID:'.$DISC_ID.'::DISCUSSION_TYPE:'.$discussion_type.'-----');

			switch($discussion_type)
			{
				case DISCUSSION_SECTION_DEFAULT:
				$model = 'CommunityDiscussionEntityModel';
				$feedback_date = 'comment_date';
				break;
				
				case DISCUSSION_SECTION_REVIEW_REQUEST:
				$model = 'ReviewRequestCommunityDiscussionEntityModel';
				$feedback_date = 'review_date';
				break;				
			}
			
			$this->log->fine('[CONTROLLER] USING MODEL: '.$model);
			$this->log->fine('[CONTROLLER] USING DATE: '.$feedback_date);			
						
			$this->log->fine('[CONTROLLER] ADDING DISCUSSION DETAILS');
			$data['disc_details']= $this->$model->GetDetails($DISC_ID);
						
			// Converting the timestamp in the comments to relative date values
			$relative_date_mapping = $this->config->item('relative_date_mapping');
			krsort($relative_date_mapping);
			$this->log->debug('relative_date_mapping:'.print_r($relative_date_mapping,true));
			$data['disc_details']['disc_date'] = $this->cutil->ReturnRelativeDateStamp($data['disc_details']['disc_date'],$relative_date_mapping);
			
			// Checking if the discussion is bookmarked by $session_user
			$data['is_bookmarked'] = false;
			if($this->session_user)
			{
				$data['is_bookmarked'] = $this->$model->IsBookmarked($DISC_ID,$this->session_user);
			}
			
			$data['disc_image_attachments'] = explode(LIGHTBOX_IMAGE_ATTACHMENT_LIST_DELIMITER,$data['disc_details']['disc_image_attachments']); 
			$this->log->fine('[CONTROLLER] DISCUSSION ATTACHMENTS: '.print_r($data['disc_image_attachments'],true));
			
			$this->log->fine('[CONTROLLER] ADDING DISCUSSION CREATOR DETAILS');
			$disc_creator = $data['disc_details']['artist_dname'];
			$this->load->model('ArtistEntityModel');
			$data['disc_creator_details'] = $this->ArtistEntityModel->GetDetails($disc_creator);	
			
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
			
			$this->log->fine('[CONTROLLER] ADDING DISCUSSION COMMENTS');
			$data['comment_array'] = $this->UtilController->GetCommentsByPage($DISC_ID,$comment_page=1,VIEW_COMMUNITY_DISCUSSION_NUM_COMMENTS,$this->$model,$check_post_data=true,$feedback_date);			
			
			switch($discussion_type)
			{		
				case DISCUSSION_SECTION_DEFAULT:
				$this->log->info('[CONTROLLER][DISPLAY] DISCUSSION');
				$this->display('View.COMMUNITY.Discussion',$data);
				break;
				
				case DISCUSSION_SECTION_REVIEW_REQUEST:
					
				$this->log->fine('[CONTROLLER] ADDING PHOTO EXIF');
				$data['photo_exif'] = $this->PhotoEntityModel->GetEXIFData($data['disc_image_attachments'][0]);
			
				$this->log->debug('[CONTROLLER] # of Comments:: '.count($data['comment_array']['comments']));
				$this->log->debug('[CONTROLLER] Comments are:: '.print_r($data['comment_array']['comments'],true));
				$this->log->info('[CONTROLLER][DISPLAY] REVIEWREQEUST');
				$this->display('View.COMMUNITY.Review_Request',$data);
				break;				
			}
		}
		catch( Exception $e )
		{
			$this->_OnError($e);
		}		
	}
	
	public function AddTag()
	{
		try
		{
			$this->log->info("[CONTROLLER] AddTag");
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('id','tag'));
						
			$this->CommunityDiscussionEntityModel->AddTag($_POST['id'],$_POST['tag']);

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
						
			$this->CommunityDiscussionEntityModel->DeleteTag($_POST['id'],$_POST['tag']);
			$this->AjaxResponse(SUCCESS);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}			
	}
	
	/**
   	* Add a comment to a community discussion
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
			$this->log->info('[CONTROLLER][ENTRY] -----POST COMMUNITY DISCUSSION COMMENT-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('entity','comment_text'));
			
			$disc_attachment = array_key_exists('discussion_attachment', $_POST)? $_POST['discussion_attachment']:null;
	
			$this->CommunityDiscussionEntityModel->AddComment($_POST['entity'],$this->session_user,$_POST['comment_text'],$disc_attachment);
			
			return $this->Discussion($_POST['entity']);
		}
		catch( Exception $e )
		{
			$this->AjaxExceptionResponse($e);
		}
	}	
	
	/**
   	* Deletes a comment from the community discussion page
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
			$this->log->info('[CONTROLLER][ENTRY] -----DELETING COMMUNITY DISCUSSION COMMENT-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('entity','comment_id'));
					
			$this->log->fine('[CONTROLLER][ENTRY] Deleting new comment for disc:'.$_POST['entity']);
			$this->CommunityDiscussionEntityModel->DeleteComment($_POST['entity'],$_POST['comment_id']);
			
			//$this->log->fine('[CONTROLLER][ENTRY] Returning profile page for artist:'.$_POST['for_artist_dname']);		
			//return $this->Index($_POST['for_artist_dname']);
			$this->AjaxResponse(SUCCESS);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/*
	 * Delete individual review on review request page
	 */
	public function DeleteReview()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----DELETING REVIEW-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('entity','comment_id'));
					
			$this->log->fine('[CONTROLLER][ENTRY] Deleting new review for disc:'.$_POST['entity']);
			$this->ReviewRequestCommunityDiscussionEntityModel->DeleteReview($_POST['entity'],$_POST['comment_id']);
			
			$this->AjaxResponse(SUCCESS);
			//echo 0;
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
	/*
	 * Delete a general discussion from the community explore page
	 */
	public function DeleteDiscussion()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----DELETING DISCUSSION-----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('entity_id'));

			$this->log->fine('[CONTROLLER][ENTRY] Deleting discussion:'.$_POST['entity_id']);
			$this->CommunityDiscussionEntityModel->Delete($_POST['entity_id']);
			$this->AjaxResponse(SUCCESS);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}
	
		/*
	 * Delete a review request from the community explore page
	 */
	public function DeleteReviewRequest()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----DELETING REVIEW REQUEST -----');
			
			$this->VerifyActiveSession();
			$this->VerifyPostData($_POST,array('entity_id'));

			$this->log->fine('[CONTROLLER][ENTRY] Deleting review request:'.$_POST['entity_id']);
			$this->ReviewRequestCommunityDiscussionEntityModel->Delete($_POST['entity_id']);
			$this->AjaxResponse(SUCCESS);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}	
		
	}
	
	
	public function ReviewRequest()
	{
		$this->log->info('[CONTROLLER][DISPLAY] REVIEW REQUEST');
		$this->display('View.COMMUNITY.Review_Request',$data=null);
	}		
		
}

/* End of file Community.php */
