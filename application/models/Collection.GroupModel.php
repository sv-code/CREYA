<?php
/**
 * GroupCollectionModel Class
 *
 * Interfaces
 * 
 *		AbstractLightboxCollectionModel::GetEntityCount	($filter_name=FILTER_NONE)
 * 		AbstractLightboxCollectionModel::GetEntities		($order_by=ORDER_DATE,$filter_name=FILTER_NONE,$num_entities=RESULTS_ALL,$offset=OFFSET_NONE)
 * 
 * 		ILightboxExplore::GetMostRecent				($num_results,$offset,$filter_name_keywords=null)
 * 		ILightboxExplore::GetMostRelevant				($num_results,$offset,$filter_name_keywords)
 * 
 * 		GroupCollectionModel::GetMostActive			($num_groups,$offset,$filter_name_keywords=null)
 *  		GroupCollectionModel::GetGroupsHavingPhoto		($pid)
 * 
 * Created
 * 		Feb 07, 2009
 *
 * @package 	Lightbox
 * @subpackage 	Models
 * @category 	none
 * @author		venksster 
 */

require MODEL_BASE_PATH.'Abstract.Collection.LightboxModel'.EXT;

class GroupCollectionModel extends AbstractLightboxCollectionModel
{
	/**
	* Constructor
	*
	* @access public
	*/
	public function GroupCollectionModel($module='groupexplore')
	{
		parent::AbstractLightboxCollectionModel($module,$table_entity='group',$column_entity_name='group_name',$column_entity_key='GROUP_ID',$column_entity_date='group_date');
	}
	
	/**
	* Returns $num_groups groups, and total number of results for pagination
	* ordered in descending order of number of photos
	*
	* @type	GET
	* 
	* @access public
	* @param int 		$num_groups 				The maximum numbers of group results to retrieve
	* @param int 		$offset 					The offset to start retrieving group results from (used for pagination)
	* @param array 		$filter_name_keywords (optional)	Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER
	* @return array 								array of groups
	*/
	public function GetMostActive($num_groups,$offset,$filter_name_keywords=FILTER_NONE)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_groups.' most active groups from offset:'.$offset);
		
		$groups = $this->GetEntities($order_by='group_photo_count',$filter_name_keywords,$num_groups,$offset);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $groups;	
	}
	
	/**
     	* @type	GET
     	* 
   	* @access	public
   	* @param	int 		$pid		PHOTO_ID 
   	* @return	array				array of groups 	
   	*/
	public function GetGroupsHavingPhoto($pid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning groups having photo:'.$pid);
		
		$this->db->select('group.GROUP_ID,group.group_name');
		$this->db->where('PHOTO_ID',$pid); 
		$this->db->from('group');
		$this->db->join('group_photo','group_photo.GROUP_ID = group.GROUP_ID');
		
		$groups = $this->db->get();
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $groups->result_array();
	}
	
	/**-------------------------------------------------------------------------------------------------------------------*/
}

/* End of file GroupCollectionModel.php */