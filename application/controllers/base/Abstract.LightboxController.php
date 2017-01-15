<?php
/**
 * AbstractLightboxController Class
 * 
 * Purpose
 * 		base controller for Lightbox
 * Created 
 * 		Nov 16, 2008
 * 
 * @package		Lightbox
 * @subpackage	Controllers
 * @category	base
 * @author		venksster
 * @link		TBD
 * @abstract
 */
if(!defined('ABSTRACT_LIGHTBOX_CONTROLLER'))
{
	define('ABSTRACT_LIGHTBOX_CONTROLLER',DEFINED);
	
	require EXCEPTION_PATH.'Exception.InactiveSession'.EXT;
	require EXCEPTION_PATH.'Exception.BadPost'.EXT;
	require EXCEPTION_PATH.'Exception.PermissionDenied'.EXT;
	require EXCEPTION_PATH.'Exception.ActiveSession'.EXT;
	require EXCEPTION_PATH.'Exception.Http404'.EXT;
	
	require CONTROLLER_UTIL_PATH.'Util.LightboxController'.EXT;
		
	abstract class AbstractLightboxController extends Controller
	{
		/**
	   	* Constructor: Initializes the CI Controller, the logger and the session user  
	   	* 
	   	* @access	protected
	   	* @param 	string $module 		the log file generated would be the file pointed to by 'logfile_$module' specified in config.php
	   	* @return	void					
	   	*/
	  	protected function AbstractLightboxController($module)
	  	{
	  		parent::Controller();
			
			$this->log = clogger_return_instance($module);
			$this->log->info('[CONTROLLER] Initializing');
			
			$this->VerifyStandardBaseUrl();
			
			$this->log->fine('[CONTROLLER] Reading session_user');
			$this->session_user = $this->session->GetArtistDName();
			$this->log->debug('[CONTROLLER] session_user:'.$this->session_user);
			
			$this->VerifyLockdownMode($module);			
			
			$this->base_url = $this->config->item('base_url');
			$this->UtilController = new UtilLightboxController();
			
			// Set the http timeout
			$http_timeout = $this->config->item('http_timeout');
			$this->log->debug('[CONTROLLER] http_timeout:'.$http_timeout);
			set_time_limit($http_timeout);
						
	  	}
		
		/**
	   	* @TYPE	AJAX
	   	*  
	   	* @access	public
	   	* @return	void
	   	*/
		public function IsLoggedIn()
		{
			$this->log->fine('[CONTROLLER] Verifying if a session is active...');
			
			if($this->session_user!=null)
			{
				$this->AjaxResponse(SUCCESS);
			}
			else
			{	
				$this->AjaxResponse(ERROR_INVALID_SESSION);
			}
		}	
				
		/**
	   	* Displays an error page
	   	* 
	   	* @type	DISPLAY
	   	* 
	   	* @access	protected
	   	* @return	void 			
	   	*/
		public function OnError($reason=null)
		{
			if($reason=='loggedout')
			{
				return $this->_OnError(new InactiveSessionException('[CONTROLLER] User needs to be logged in to view this page'));	
			}
			
			return $this->_OnError(new Http404Exception('[CONTROLLER] 404 not found'));
		}
		
		/**-------------------------------------------------------------------------------------------------------------------*/
		
		protected function VerifyStandardBaseUrl()
		{
			/*
			$this->log->fine('[CONTROLLER] Querying for WWW in url');
			
			$url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			$this->log->fine('[CONTROLLER] current_url:'.$url);
			
			if(strstr($url,'www.') || strstr($url,'WWW.'))
			{
				$this->log->warning('[CONTROLLER] url found with WWW. Redirecting 301...');
				
				//$redirect_url = preg_replace('www','', $subject);
				$redirect_url = 'http://'.substr($url,4);
				$this->log->warning('[CONTROLLER] REDIRECTING TO:'.$redirect_url);
				
				//redirect($redirect_url,'location', 301);
				$this->output->set_status_header('301');
				header('Location: '.$redirect_url);
			}
			*/
			
			$this->log->fine('[CONTROLLER] Verifying standard base_url...');
			
			$base_url = $this->config->item('base_url');
			$base_url_stripped_http = substr($base_url,strlen('http://'));
			
			if($_SERVER['SERVER_NAME']!=$base_url_stripped_http)
			{
				$this->log->warning('[CONTROLLER] url found with WWW or different from base_url:'.$_SERVER['SERVER_NAME']);
				
				$redirection_location = 'Location: '.$base_url.$_SERVER['REQUEST_URI'];
				$this->log->warning('[CONTROLLER] Redirecting 301 to '.$redirection_location);
				
			   	header("HTTP/1.1 301 Moved Permanently");
			       	header($redirection_location); 
				exit;
			}
			else
			{
				$this->log->fine('[CONTROLLER] ...Done');
			}

			
		}
		
		protected function VerifyLockdownMode($module)
		{
			if($this->config->item('lightbox_lockdown')==true)
			{
				$this->log->config('[CONTROLLER] LockdownMode:TRUE');
				$this->log->fine('[CONTROLLER] session_user:'.$this->session_user);
				
				if($this->session_user==null && $this->cutil->ArraySearch($module,$this->config->item('lightbox_lockdown_allowed_controllers'))==false)
				{
					$this->log->error('[CONTROLLER] Cannot load disallowed controller module:'.$module);
					redirect($this->config->item('lightbox_lockdown_redirect_controller'));
				}
				
				/*
				if($this->session_user==null && $module!='csession' && $module!='gateway' && $module!='upload')
				{
					redirect('/gateway');
				}
				*/	
			}
		}
		
		/**
	   	* Logs the exception and displays the Lightbox 404 page
	   	* 
	   	* @access	protected
	   	* @param	Exception $e		the exception that caused the 404
	   	* @return	void
	   	*/
		protected function _OnError($e)
		{
			$this->log->debug('[EXCEPTION CLASS] '.get_class($e));
			
			switch(get_class($e))
			{
				case 'InactiveSessionException':
				{
					$data['error']='[EXCEPTION] InactiveSessionException';
					$this->display('View.ERROR.InactiveSession');
					return;
				}
				
				case 'ActiveSessionException':
				{
					$data['error']='[EXCEPTION] ActiveSessionException';
					break;
				}
				
				case 'Http404Exception':
				{
					$data['error']='[EXCEPTION] Http404Exception';
					$this->output->set_status_header('404');
					$data['message'] = "this page doesn't exist. check the url, start afresh or use the links on this page to navigate";
					break;
				}
				
				default:
				{
					$data['error'] = $e->getMessage();
					$data['message'] = "either this page doesn't exist or something went terribly wrong! if you think this is a bug, feel free to contact us";
				}
			}
			
			//$data['stacktrace'] = $e->getTraceAsString();
			$this->log->fatal('[EXCEPTION ON_ERROR] '.$data['error']);		
			
			$data['title'] = 'Oops!';
			if($this->config->item('on_error_debug_mode')==true)
			{
				$this->display('View.ERROR.Debug',$data);
			}
			else
			{
				$this->display('View.ERROR',$data);
			}
		}
		
		protected function AjaxExceptionResponse($e)
		{
			if($e->getMessage()!=null && $e->getMessage()!='')
			{
				$this->log->fatal('[EXCEPTION] '.$e->getMessage());
			}
			
			switch(get_class($e))
			{
				case 'InactiveSessionException':
				{
					$response_array['RETURN_CODE'] = ERROR_INVALID_SESSION;
				}
				
				case 'ActiveSessionException':
				{
					$response_array['RETURN_CODE'] = ERROR_VALID_SESSION;
				}
				
				case 'BadPostException':
				{
					$response_array['RETURN_CODE'] = ERROR_BAD_POST_DATA;
				}
				
				default:
				{
					$response_array['RETURN_CODE'] = ERROR_UNKNOWN;
				}
			}
			
			$this->log->debug('[CONTROLLER] Ajax response: '.json_encode($response_array));
			echo json_encode($response_array);
		}
		
		protected function AjaxResponse($e,$optional_message=null)
		{
			$response_array['RETURN_CODE'] = $e;
			if( $optional_message != null )
			{
				$response_array['RETURN_MESSAGE'] = $optional_message;
			}
			$this->log->debug('[CONTROLLER] Ajax response: '.json_encode($response_array));
			echo json_encode($response_array);
		}
		
		/**
	   	* Verifies that a session is active
	   	* 
	   	* @access	protected
	   	* @return	void
	   	* @throws	SessionInactiveException					
	   	*/
		protected function VerifyActiveSession()
		{
			if($this->session_user==null)
			{
				throw new InactiveSessionException();
			}
			
			$this->log->fine('[CONTROLLER] ActiveSession verified');
			return;
		}
		
		/**
	   	* Verifies that a session is inactive
	   	* 
	   	* @access	protected
	   	* @return	void
	   	* @throws	SessionActiveException					
	   	*/
		protected function VerifyInactiveSession()
		{
			if($this->session_user!=null)
			{
				throw new ActiveSessionException();
			}
			
			$this->log->fine('[CONTROLLER] InactiveSession verified');
			return;
		}
		
		/**
	   	* Verifies POST data
	   	* 
	   	* @access	protected
	   	* @param	array $post_data_array
	   	* @param	array	$required_data_array
	   	* @return	void
	   	* @throws	BadPostException					
	   	*/
		protected function VerifyPostData($post_data_array,$required_data_array)
		{
			if(!isset($post_data_array) || !is_array($post_data_array) || !isset($required_data_array) || !is_array($required_data_array))
			{
				throw new BadPostException('post_data_array or required_data_array invalid');
			}
			
			$this->log->debug('[CONTROLLER] PostData:'.print_r($post_data_array,true));
			
			foreach($required_data_array as $required_data)
			{
				if(!array_key_exists($required_data,$post_data_array) || $post_data_array[$required_data]=='' || $post_data_array[$required_data]==' ')
				{
					throw new BadPostException('Could not find required data:'.$required_data);
				}
			}
			
			$this->log->fine('[CONTROLLER] PostData verified');
			return;
		}
		
		/**
	   	* Checks if POST data contains a certain key/value
	   	* 
	   	* @access	protected
	   	* @param	array $post_data_array
	   	* @param	string $key
	   	* @return	value/null					
	   	*/
		protected function IsPostDataAvailable($post_data_array,$key)
		{
			if(!isset($post_data_array) || !is_array($post_data_array) || !isset($key) || $key==null)
			{
				return null;
			}
			
			$this->log->debug('[CONTROLLER] PostData:'.print_r($post_data_array,true));
			
			if(!array_key_exists($key,$post_data_array) || $post_data_array[$key]=='' || $post_data_array[$key]==' ')
			{
				return null;
			}
			
			$this->log->fine('[CONTROLLER] Returning PostData for key:'.$key);
			return $post_data_array[$key];
		}
		
		/**
	   	* Displays the specified view and adds the header/footer/template
	   	* 
	   	* The specified view should *NOT* contain the header/footer
	   	* 
	   	* @access	protected
	   	* @param	string $target		the view file without the header/footer/template
	   	* @return	void
	   	*/
	  	protected function Display($target,$data=NULL)
	  	{
	  		try
			{
				$this->log->fine('[CONTROLLER] Adding session user to data:'.$this->session_user);
				$data['session_user'] = $this->session_user;
				
				if($this->session_user!=null)
				{
					$this->load->model('ArtistEntityModel');
					$artist_details = $this->ArtistEntityModel->GetDetails($this->session_user,'artist_avatar');
					$data['artist_avatar'] = $artist_details['artist_avatar'];
				}
										
				$this->log->fine('[CONTROLLER] Displaying target view:'.$target);
				$this->load->view($target,$data);	
			}
			catch(Exception $e)
			{
				$this->_OnError($e);
			}
						
		}
		
		
		/**
	   	* Stores an uploaded image from upload,artist or group pages
	   	* 
	   	* @access	protected
	   	* @param	cgraphics_handler
	   	* @param	The upload directory path
	   	* @return	void
	   	*/		
		protected function UploadImage($uploadDir,$cgraphics_handler)
		{
			$this->VerifyPostData($_FILES['Filedata'],array('tmp_name','size'));

			$this->log->info('[CONTROLLER] UploadImage');
			$uploadFileTimeStamp = $this->cutil->ReturnMicroTime();
			$uploadFileName = $uploadFileTimeStamp.$this->cutil->getRandomNumber().'.jpg';
			$destinationUploadFile = $uploadDir.$uploadFileName;		
			
			//the temporary file parameters
			//important - Filedata is the name of the files array given by the flash control
			$tempFileName = $_FILES['Filedata']['tmp_name'];
			$tempFileSize = $_FILES['Filedata']['size'];
	
			$this->log->info('[CONTROLLER] Uploading image '.$uploadFileName);
			$this->log->debug('[CONTROLLER] Temporary image is '.$tempFileName);			
	
			if (cimage_upload($tempFileName ,$tempFileSize,$destinationUploadFile))
			{
		  		$this->log->debug('[CONTROLLER] '.$destinationUploadFile.' uploaded successfully');
		  		//Now call the cgraphics helper to resize this image
				if( $cgraphics_handler($uploadFileName) == true )
				{
					$this->log->debug('[CONTROLLER] Resized '.$destinationUploadFile.' image successfully');
					$this->log->debug('[CONTROLLER] Returning '.$uploadFileName);				
					//echo GROUP_DISCUSSION_CAPTURE_PATH.GROUP_DISCUSSION_CAPTURE.'.'.$uploadFileName;
			  		return $uploadFileName;				
				}	
				else
				{
					return '';
				}
				
			}				
		}
				
			
		/*
		protected function ReturnLocalActions($module,$args=null)
		{
			$actions_local = array();
			
			switch($module)
			{
				case 'Group':
	
					$gid = $args['gid'];
					$is_member = $args['is_member'];
			
					if($is_member)
					{
						$actions_local = array(
							array
							(
								'action_href' 	=> '#',
								'action_class' 	=> 'addToGroup',
								'action_src' 		=> '/images/nav-group-add-photos.png',
								'action_caption' 	=> 'Add/Remove photographs for this group'
							),
							array
							(
								'action_href' 	=> '/group/discussion/create/'.$gid,
								'action_class' 	=> 0,
								'action_src' 		=> '/images/nav-group-discussion.png',
								'action_caption' 	=> 'Start a group discussion'
							),
							array
							(
								'action_href' 	=> '/group/'.$gid.'/leave',
								'action_class' 	=> 0,
								'action_src' 		=> '/images/nav-group-leave.png',
								'action_caption' 	=> 'Leave this group'
							)
						);
					
					}
					elseif($this->session_user!=null)
					{
						$actions_local = array(
							array
							(
								'action_href' 	=> '/group/'.$gid.'/join',
								'action_class' 	=> 0,
								'action_src' 		=> '/images/nav-group-join.png',
								'action_caption' 	=> 'Join this group'
							)
						);
					}
					
					break;
					
			}
			
			return $actions_local;
		}
		*/
		
		/**
	  	* @access protected
	  	*/  
	  	protected $log;
		
		/**
	  	* @access protected
	  	*/  
	  	protected $session_user;
		
		/**
	  	* @access protected
	  	*/		
		protected $artist_avatar;
		
		/**
	  	* @access protected
	  	*/  
	  	protected $base_url;
		
		/**
	  	* @access protected
	  	*/ 
		protected $UtilController;
	}
}
 
/* End of file AbstractLightboxController.php */


	