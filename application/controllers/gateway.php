<?php
/**
 * Gateway Controller Class
 * 
 * Purpose
 * 		Controls LOGIN,LOGOUT,REQUEST INVITE
 * Created 
 * 		November 1, 2009
 * 
 * @package	Lightbox
 * @subpackage	Controllers
 * @category	none
 * @author		venksster
 */
require EXCEPTION_PATH.'Exception.InvalidInvite'.EXT;
require CONTROLLER_BASE_PATH.'Abstract.LightboxController'.EXT;

class Gateway extends AbstractLightboxController
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @param 	void
   	* @return	void 			
   	*/
	public function Gateway()
	{
		parent::AbstractLightboxController('gateway');
		$this->load->model('AuthModel');
		$this->load->model('ArtistModel');		
		$this->load->model('ArtistEntityModel'); 
		$this->load->library('email');		
	}
	
	public function index()
	{
		$this->display('View.GATEWAY');
	}
	
	/**
   	* Displays: the login input
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function ShowLogin()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----SHOW LOGIN-----');
			
			$this->VerifyInactiveSession();
						
			$this->log->info('[CONTROLLER][MODAL] LOGIN INPUT');
			$this->load->view('Modal.Login');
		}
		catch(Exception $e)
		{
			$this->_OnError($e);
		}
	}
	
	/**
   	* the artist is logged in
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	int 			
   	*/
	public function Login()
	{
		if($this->config->item('lightbox_login_lockdown')==true)
		{
			$this->AjaxResponse(ERROR_AUTHENTICATION_FAILED);
			return; 	
		}			
		
		try
		{
	       		$this->log->info('[CONTROLLER][ENTRY] -----LOGIN-----');
			
			$this->VerifyInactiveSession();
			$this->VerifyPostData($_POST,array('username','password','remember_me'));
			
			$artist_dname 	= $_POST['username'];    
			$password 	= $_POST['password'];  
			$remember_me = false;  
			if( $_POST['remember_me'] == 'true' )
			{
				$remember_me = true;
			} 

			$this->log->info('[CONTROLLER] Authenticating user '.$artist_dname);
			if($this->AuthModel->IsAuthenticated($artist_dname,$password)==true)
			{
				csession_remember_me($remember_me);
				$this->log->info('[CONTROLLER] LOGGING IN:'.$artist_dname);
				$this->log->fine('[CONTROLLER] Creating session for user '.$artist_dname);				
				$this->session->Create($artist_dname);
				
				$session_user = $this->session->GetArtistDName();
			
				$this->log->fine('[CONTROLLER] Updating LoginTimeStamp for artist:'.$session_user);
				$this->ArtistEntityModel->UpdateArtistLoginTimeStamp($session_user);
				
				$this->AjaxResponse(SUCCESS);
			}
			else
			{
				$this->log->error('[CONTROLLER] Authentication failed for '.$artist_dname);
				$this->AjaxResponse(ERROR_AUTHENTICATION_FAILED);
			}
		}
		catch(InvalidUserException $e)
		{
			$this->AjaxResponse(ERROR_INVALID_USER);
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}

	/**
   	* the artist is logged out
   	* 
   	* @type	DISPLAY_REDIRECT
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function Logout()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----LOGOUT-----');
			
			$this->VerifyActiveSession();
						
			$this->log->info('[CONTROLLER] LOGGING OUT:'.$this->session_user);
			$this->session->Destroy($this->session_user);
		
		    	redirect('/gateway');
		}
		catch(Exception $e)
		{
			$this->_OnError($e);
		}
	}	
	
	/**
   	* Displays: the invite input
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function RequestInvite()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----REQUEST INVITE-----');
			
			$this->VerifyInactiveSession();
						
			$this->log->info('[CONTROLLER][MODAL] REQUEST INVITE');
			$this->load->view('Modal.RequestInvite');
		}
		catch(Exception $e)
		{
			$this->_OnError($e);
		}
	}
	
	/**
	 * Process artist invite and send profile creation link
	 * @return 
	 */
	public function SubmitInviteRequest()
	{
		$this->log->info('[CONTROLLER][ENTRY] -----PROCESSING ARTIST INVITE REQUEST-----');
		$this->VerifyPostData($_POST,array('email','work'));		

		//Check this user's status within the application
		//He could have one of 4 states:
		//1) LIGHTBOX_STAGE_MEMBER - Already a part of lightbox
		//2) LIGHTBOX_STAGE_INVITED - He has been sent an invite.
		//3) LIGHTBOX_STAGE_PENDING_INVITE - An invitation email must be extended to the artist. 
		//4) LIGHTBOX_STAGE_UNKNOWN - New artist
		$this->load->model('InviteModel');
		
		$this->log->info('[CONTROLLER] Initializing email library');
		cemail_initialize_library();
		
		$artist_email = $_POST['email'];
		$artist_interest = $_POST['work'];
		$artist_status = $this->InviteModel->GetArtistLightboxStage($artist_email);
		
		 //If a user does not exist in the system then generate an invite code for him
		$this->log->info('[CONTROLLER] Artist Lightbox Stage: '.$artist_status);
		switch( $artist_status)
		{
			case LIGHTBOX_STAGE_MEMBER:
				$this->log->info('[CONTROLLER] Artist is already a part of lightbox:'.$artist_email);
				$data['title'] = "";
				$data['message'] = "You are already a member of Creya. Please login with your credentials.";
			break;
			
			case LIGHTBOX_STAGE_INVITED:
				$this->log->info('[CONTROLLER] Artist has been sent an invite previously.');
				$this->log->info('[CONTROLLER] Sending new invite to artist: '.$artist_email);			
				$artist_inviteid = $this->InviteModel->GetInviteId($artist_email);	
				$artist_invite_link = $this->config->item('base_url').'/artist/createprofile/'.$artist_inviteid;
				if(cemail_send_email('admin@creya.com',$artist_email,null,'Welcome to Creya!','Please register at: '.$artist_invite_link,null))
				{
					$data['title'] = "";
					$data['message'] = "We find that you have already been sent an invite. In any case, we will send you another one shortly.";
				}				
			break;
			
			case LIGHTBOX_STAGE_PENDING_INVITE:
				//The user has requested for an invite but an invite has not yet been sent out by the administrator
				$this->log->info('[CONTROLLER] Artist has already requested for an invite '.$artist_email);
				$data['title'] = "";
				$data['message'] = "Thank you for your request!";		
			break;
			
			case LIGHTBOX_STAGE_UNKNOWN:
				$this->log->info('[CONTROLLER] Artist does not exist in the system. Processing new artist invite');
				$requestTimeStamp = $this->cutil->ReturnMicroTime();
				$this->InviteModel->AddInviteRequest($artist_email,$artist_interest);
				cemail_send_email('admin@creya.com','core@creya.com',null,'Invite Request','EMAIL:'.$artist_email.'  INTEREST:'.$artist_interest);
				
				$data['title'] = "";
				$data['message'] = "Thank you for your request!";				
				
			break;
		}
		
		$this->log->info('[CONTROLLER] Redirecting to message page');
		$this->display('View.MESSAGE',$data);
		
		//send the email now
		/*cemail_initialize_library();
		if( cemail_send_email('admin@creya.com',$_POST['artist_email'],'Welcome to Creya!','Your key is:'.$inviteKey ,null) )
		{
			$this->AjaxResponse(SUCCESS);
			return;
		}
		*/
	}
	
	/**
   	* Displays: the artist profile creation page
   	* 
   	* @type	DISPLAY
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function CreateProfile($invite_id)
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----CREATE PROFILE-----');
			$this->VerifyInactiveSession();
			
			$this->log->info('[CONTROLLER] Invite_id:'.$invite_id);
						
			$this->load->model('InviteModel');
			if(!isset($invite_id) || !$this->InviteModel->IsValidInvite($invite_id))
			{
				throw new InvalidInviteException('Invalid,empty invite_id or invite_id not found in the system:'.$invite_id);
			}
			
			$data['artist_email'] = $this->InviteModel->GetArtistEmail($invite_id);
			
			if(!isset($data['artist_email']))
			{
				throw new Exception('[CONTROLLER] Cannot find artist_email');	
			}
			
			$this->log->info('[CONTROLLER] Email for invite-id is : '.$data['artist_email']);
			
			$artist_status = $this->InviteModel->GetArtistLightboxStage($data['artist_email']);
			$this->log->info('[CONTROLLER] Lightbox Artist Status is: '.$artist_status);
			if( $artist_status == LIGHTBOX_STAGE_MEMBER )
			{
				$this->log->info('[CONTROLLER] Artist is already a part of lightbox');
				$data['title'] = "";
				$data['message'] = "You are already a member of creya. Please login with your credentials.";
				
				$this->log->info('[CONTROLLER] Redirecting to message page');
				$this->display('View.MESSAGE',$data);						
				return;						
			}

			$data['artist_focii'] = $this->ArtistModel->GetFocii();
			sort($data['artist_focii']);
						
			$this->load->model('PhotoModel');
			$data['cc_licenses'] = $this->PhotoModel->GetCreativeCommonsLicenses();
								
			$this->log->info('[CONTROLLER][DISPLAY] ARTIST PROFILE CREATION');
			$this->display('View.ARTIST.Profile.Create',$data);
			
		}
		catch(InvalidInviteException $e)
		{
			$data['title'] = 'Oops!';
			$data['message'] = 'this invite code is invalid or has expired. please request an invite';
			$this->display('View.MESSAGE',$data);
		}
		catch(Exception $e)
		{
			$this->_OnError($e);
		}	
			
	}
	
	
	/**
   	* This function will return the availability of an 'artist_dame'
   	* 
   	* @type	AJAX
   	* 
   	* @access	public
   	* @return	int 			
   	*/
	public function IsAvailableArtistDname()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----CHECKING THE AVAILABILITY OF A DNAME-----');
			
			$this->VerifyPostData($_POST,array('artist_dname'));		
			
			$this->log->fine('[CONTROLLER] Checking availability of artist_dname:'.$_POST['artist_dname']);
			$unavailable = $this->ArtistEntityModel->Exists($_POST['artist_dname']);
						
			$this->log->info('[CONTROLLER][AJAX_RETURN]:'.$unavailable);
			if($unavailable)
			{
				$this->AjaxResponse(ERROR_ARTIST_DNAME_UNAVAILABLE);
			}
			else
			{
				$this->AjaxResponse(SUCCESS);				
			}
		}
		catch(Exception $e)
		{
			$this->AjaxExceptionResponse($e);
		}
	}		
	
	/**
   	* Adds a new artist
   	* 
   	* @type	DISPLAY_REDIRECT
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function AddArtist()
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----ADD ARTIST-----');
			
			$this->VerifyInactiveSession();
			$this->VerifyPostData($_POST,array('artist_email','artist_password_sha1','artist_dname','artist_focus','artist_location'));
			
			$this->log->info('[CONTROLLER] Creating profile for artist_dname:'.$_POST['artist_dname']);
			
			$artist_data['artist_email'] 		= $_POST['artist_email'];
			$artist_data['artist_password'] 	= $_POST['artist_password_sha1']; 
			$artist_data['artist_avatar'] 		= $_POST['preview_filename'];	
			
			$artist_focus = $this->UtilController->GetArtistFocus($_POST);
						
			$artist_data['artist_focus'] = $artist_focus;
			$artist_data['artist_location'] = $_POST['artist_location'];		
			
			$this->ArtistEntityModel->Add($_POST['artist_dname'],$artist_data);
						
			$this->log->info('[CONTROLLER] Creating session for artist:'.$_POST['artist_dname']);
			$this->session->Create($_POST['artist_dname']);
			
			$session_user = $this->session->GetArtistDName();
			
			$this->log->fine('[CONTROLLER] Updating LoginTimeStamp for artist:'.$session_user);
			$this->ArtistEntityModel->UpdateArtistLoginTimeStamp($session_user);
			
			$this->log->fine('[CONTROLLER] Invalidating invite request for user');
			$this->load->model('InviteModel');
			$this->InviteModel->DeleteInviteRequest($artist_data['artist_email']);
							
			//Send an email notification to core-creya indicating that the user has registered
			$this->log->fine('[CONTROLLER] Sending notification to core for user '.$session_user);
			cemail_initialize_library();
			cemail_send_email('notifications@creya.com','core@creya.com',null,
							'Artist registration notification!','User '.$session_user.' has registered.' ,null);
			redirect('/artist/'.$session_user);
			//redirect('/photo/uploader');
		}
		catch(Exception $e)
		{
			$this->_OnError($e);
		}
	}
			
}

/* End of file Gateway.php */