<?php
/**
 * PhotoExplore Controller Class
 * 
 * Created 
 * 		April 3, 2009
 * 
 * @package	Lightbox
 * @subpackage	Controllers
 * @category	none
 * @author		venksster
 */
require CONTROLLER_BASE_PATH.'Abstract.PhotoGalleryController'.EXT;

class PhotoExplore extends AbstractPhotoGalleryController
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @param 	void
   	* @return	void 			
   	*/
	public function PhotoExplore()
	{
		parent::AbstractPhotoGalleryController('photoexplore');
		$this->load->model('PhotoCollectionModel');
	}
  
	/**
   	* ENTRY POINT
   	* 
   	* Displays:the PHOTO EXPLORE page
   	* 
   	* @access	public
   	* @param 	string		$order_by		
   	* @return	void 			
   	*/
	public function Index($order_by='recent')
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----LOADING PHOTO EXPLORE::Order:'.$order_by.'-----');
			$this->log->info('[CONTROLLER] ORDER BY:'.$order_by); 
			
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
			$data['total_photo_count']	= $this->PhotoCollectionModel->GetEntityCount($filter_name_tag_keywords,$filter_photo_property_array);
			$data['page_count']		= $data['total_photo_count'] == 0 ? 0 : ceil($data['total_photo_count'] / $view_num_photos);
			$data['photos'] 			= $data['total_photo_count'] == 0 ? array() : $this->PhotoCollectionModel->GetMostRecent($view_num_photos,$offset,$filter_name_tag_keywords,$filter_photo_property_array);
			
			
			$this->log->fine('Shuffling photos');
			//shuffle($data['photos']);
			if(isset($data['photos']) && is_array($data['photos']) && count($data['photos'])>0)
			{
				$data['photos'] = $this->cutil->ArrayShuffle($data['photos']);
			}
			
			$this->log->info('RETRIEVING PHOTO GENRES');
			$data['photo_type_array'] = $this->PhotoModel->GetPhotoTypes();
			sort($data['photo_type_array']);
			
			$this->log->info('TOTAL PHOTO_COUNT:'.$data['total_photo_count']);
			
			if($this->session_user!=null && count($data['photos']))
			{
				$this->AddBookmarkInfo($data['photos'],$this->session_user);
			}
			
			$this->log->info('[CONTROLLER][DISPLAY] PHOTO EXPLORE');
			$this->Display('View.PRIMARY.PHOTOS.Explore',$data);
		}
		catch( Exception $e )
		{
			$this->_OnError($e);
		}
	}  	
		
}

/* End of file PhotoExplore.php */