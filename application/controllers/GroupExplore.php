<?php
/**
 * GroupExplore Controller Class
 * 
 * Created 
 * 		April 3, 2009
 * 
 * @package	Lightbox
 * @subpackage	Controllers
 * @category	none
 * @author		venksster
 */
require CONTROLLER_BASE_PATH.'Abstract.LightboxController'.EXT;

class GroupExplore extends AbstractLightboxController
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @param 	void
   	* @return	void 			
   	*/
	public function GroupExplore()
	{
		parent::AbstractLightboxController('groupexplore');
		$this->load->model('GroupCollectionModel');
	}
  
	/**
   	* ENTRY POINT
   	* 
   	* Displays:the GROUP EXPLORE page
   	* 
   	* @access	public
   	* @param 	string		$order_by		
   	* @return	void 			
   	*/
	public function Index($order_by='recent')
	{
		try
		{
			$this->log->info('[CONTROLLER][ENTRY] -----LOADING GROUP EXPLORE::Order:'.$order_by.'-----');
			
			$page = $this->UtilController->GetPage($_POST);
			$this->log->debug('[CONTROLLER] page:'.$page);
			
			$filter_keywords = $this->UtilController->GetFilterKeywords($_POST);
	
			$this->log->info('[CONTROLLER] PAGE:'.$page);
			$this->log->info('[CONTROLLER] filter-keywords :'.$filter_keywords); 		 
			
			$this->log->fine('[CONTROLLER] CALCULATING OFFSET');
			$view_num_groups = VIEW_GALLERY_GROUPS_NUM_COLUMNS * VIEW_GALLERY_GROUPS_NUM_ROWS;
			$this->log->debug('[CONTROLLER] view_num_groups:'.$view_num_groups);
			
			$offset = ($page - 1) * $view_num_groups;
			$this->log->debug('[CONTROLLER] $offset:'.$offset);
			
			$this->log->fine('[CONTROLLER] ADDING GROUPS');
			$data['current_page']			= $page;
			$data['start_group']		= $offset + 1;
			$data['total_group_count']	= $this->GroupCollectionModel->GetEntityCount($filter_keywords);
			$this->log->debug('[CONTROLLER] total_group_count:'.$data['total_group_count']);
			
			$data['page_count']		= $data['total_group_count'] == 0 ? 0 : ceil($data['total_group_count'] / $view_num_groups);
			$data['groups'] 			= $data['total_group_count'] == 0 ? array() : $this->GroupCollectionModel->GetMostRecent($view_num_groups,$offset,$filter_keywords);
			
			$this->log->info('[CONTROLLER][DISPLAY] GROUP EXPLORE');
			$this->display('View.PRIMARY.GROUPS.Explore',$data);
		}
		catch( Exception $e )
		{
			$this->_OnError($e);
		}
	}  
	
}

/* End of file GroupExplore.php */