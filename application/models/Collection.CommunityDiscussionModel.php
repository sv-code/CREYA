<?php
/**
 * CommunityDiscussionCollectionModel Class
 * 
 * Interfaces
 * 
 * 		AbstractLightboxCollectionModel::Get				($num_entities,$offset,$order_by,$filters=null)
 * 
 * 		ILightboxExplore::GetMostRecent					($num_discs,$offset,$filter_name_keywords=null)
 * 		ILightboxExplore::GetMostRelevant					($num_discs,$offset,$filter_name_keywords)
 * 
 * 		AbstractLightboxCollectionModel::GetMostPopular		($num_discs, $offset,$filters=null)
 * 
 * Created 
 * 		Feb 26, 2009
 * 
 * @package		Lightbox
 * @subpackage	Models
 * @category		none
 * @author		venksster
 * @link			TBD
 */
require MODEL_BASE_PATH.'Abstract.Collection.DiscussionModel'.EXT;
 
class CommunityDiscussionCollectionModel extends AbstractDiscussionCollectionModel 
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @param 	void
   	* @return	void 			
   	*/
	public function CommunityDiscussionCollectionModel($module='community')
  	{
 		parent::AbstractDiscussionCollectionModel($module,$table_discussion='community_discussion');
	}
	
	/**
	* OVERRIDE
	* Returns $discs
	* ordered in descending order of disc created
	* filtered by category
	*
	* @type	GET
	* 
	* @access public
	* @param int 	$num_discs 				The maximum numbers of cdisc results to retrieve
	* @param int 	$offset 					The offset to start retrieving cdisc results from (used for pagination)
	* @param array 	$filter_name_keywords (optional)	Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER
	* @return array 							array of cdiscs
	*/
	public function GetMostRecent($num_discs,$offset,$filter_category=FILTER_NONE,$filter_name_keywords=FILTER_NONE)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_discs.' most recent entities from offset:'.$offset.' and total_count for pagination');
		
		$filter_entity_property_array = $filter_category == FILTER_NONE ? FILTER_NONE : array('CAT_ID'=>$filter_category); 
		
		$entities = $this->GetEntities(ORDER_DATE,$filter_name_keywords,$filter_entity_property_array,$num_discs, $offset);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $entities;	
	}
	
	/**
	* OVERRIDE
	* 
	*/
	public function GetEntityCount($filter_section=FILTER_NONE,$filter_name_keywords=FILTER_NONE)
	{
		$filter_entity_property_array = $filter_section == FILTER_NONE ? FILTER_NONE : array('SECTION_iD'=>$filter_section); 
		return parent::GetEntityCount($filter_name_keywords,$filter_entity_property_array);
	}
}  	
 
/* End of file CommunityDiscussionCollectionModel.php */


	