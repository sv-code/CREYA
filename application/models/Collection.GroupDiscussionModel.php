<?php
/**
 * GroupDiscussionCollectionModel Class
 * 
 * Interfaces
 * 
 * 		AbstractLightboxCollectionModel::Get			($num_entities,$offset,$order_by,$filters=null)
 * 
 * 		ILightboxExplore::GetMostRecent				($num_discs,$offset,$filter_name_keywords=null)
 * 		ILightboxExplore::GetMostRelevant				($num_discs,$offset,$filter_name_keywords)
 * 
 * 		AbstractLightboxCollectionModel::GetMostPopular	($num_discs, $offset,$filters=null)
 * 
 * Created 
 * 		Feb 26, 2009
 * 
 * @package		Lightbox
 * @subpackage		Models
 * @category		none
 * @author			venksster
 * @link			TBD
 */
require MODEL_BASE_PATH.'Abstract.Collection.DiscussionModel'.EXT;
 
class GroupDiscussionCollectionModel extends AbstractDiscussionCollectionModel 
{
	/**
   	* Constructor
    	* 
   	* @access	public
   	* @param 	void
   	* @return	void 			
   	*/
	public function GroupDiscussionCollectionModel($module='gdiscussionexplore')
  	{
 		parent::AbstractDiscussionCollectionModel($module,$table_discussion='group_discussion');
	}
	
	/**
	* Returns $num_entities entities
	* ordered in descending order of date posted
	*
	* @type	GET
	* 
	* @access public
	* @param int 		$num_entities 				The maximum numbers of results to retrieve
	* @param int 		$offset 					The offset to start retrieving results from (used for pagination)
	* @param array 		$filter_name_keywords (optional)	Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER
	* @return array 								array of entities
	*/
	public function GetMostRecentByGroup($gid,$num_entities,$offset,$filter_name_keywords=FILTER_NONE)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_entities.' most recent entities from offset:'.$offset.' and total_count for pagination');
		
		$entities = $this->GetEntities(ORDER_DATE,$filter_name_keywords,$filter_entity_property_array=array('GROUP_ID'=>$gid),$num_entities, $offset);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $entities;	
	}
	
	/**
	* Returns $num_entities entities
	* ordered by most relevant
	*
	* @type	GET
	* 
	* @access public
	* @param int 		$num_entities 		The maximum numbers of group results to retrieve
	* @param int 		$offset 			The offset to start retrieving group results from (used for pagination)
	* @param array 		$filter_name_keywords	Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER
	* @return array 						array of entities
	*/
	public function GetMostRelevantByGroup($gid,$num_entities,$offset,$filter_name_keywords)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_entities.' most relevant entities from offset:'.$offset.' and total_count for pagination');
		
		$entities = $this->GetEntities(ORDER_RELEVANCE,$filter_name_keywords,$filter_entity_property_array=array('GROUP_ID'=>$gid),$num_entities,$offset);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $entities;	
	}
		
	/**
	* Returns $num_discs cdiscs, and total number of results for pagination
	* ordered in descending order of date posted
	*
	* @type	GET
	* 
	* @access public
	* @param int 	$num_discs 				The maximum numbers of cdisc results to retrieve
	* @param int 	$offset 					The offset to start retrieving cdisc results from (used for pagination)
	* @param array 	$filter_name_keywords (optional)	Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER
	* @return array 							array of cdiscs
	*/
	public function GetMostPopularByGroup($gid,$num_discs, $offset, $filter_name_keywords=null)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_discs.' most popular discs from offset:'.$offset.' and total_count for pagination');
		
		// @BUG: disc_comment_count does not exist
		$entities = $this->GetEntities($order_by='disc_comment_count',$filter_name_keywords,$filter_entity_property_array=array('GROUP_ID'=>$gid),$num_discs, $offset);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $entities;	
	}
	
	/**
	* OVERRIDE
	* 
	*/
	public function GetEntityCountByGroup($gid,$filter_name_keywords=FILTER_NONE)
	{
		$filter_entity_property_array = array('GROUP_ID'=>$gid); 
		return parent::GetEntityCount($filter_name_keywords=FILTER_NONE,$filter_entity_property_array);
	}
}  	
 
/* End of file GroupDiscussionCollectionModel.php */


	