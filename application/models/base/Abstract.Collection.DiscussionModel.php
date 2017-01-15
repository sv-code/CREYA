<?php
/**
 * AbstractDiscussionCollectionModel Class
 * 
 * Interfaces
 * 
 * 		AbstractLightboxCollectionModel::Get			($num_entities,$offset,$order_by,$filters=null)
 * 
 * 		ILightboxExplore::GetMostRecent				($num_discs,$offset,$filter_name_keywords=null)
 * 		ILightboxExplore::GetMostRelevant				($num_discs,$offset,$filter_name_keywords)
 * 
 * 		AbstractLightboxCollectionModel::GetMostPopular	($num_discs, $offset,$filter_name_keywords=null)
 * 
 * Created 
 * 		Feb 26, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @category	base
 * @author		venksster
 * @link		TBD
 */
if(!defined('ABSTRACT_DISCUSSION_EXPLORE_MODEL'))
{
	define('ABSTRACT_DISCUSSION_EXPLORE_MODEL',DEFINED);

	require MODEL_BASE_PATH.'Abstract.Collection.LightboxModel'.EXT;
		 
	abstract class AbstractDiscussionCollectionModel extends AbstractLightboxCollectionModel
	{
		/**
	   	* Constructor
	   	* 
	   	* @access	protected
	   	* @param 	string $module
	   	* @param 	string $table_discussion
	   	* @return	void 			
	   	*/
		protected function AbstractDiscussionCollectionModel($module,$table_discussion)
	  	{
	 		parent::AbstractLightboxCollectionModel($module,$table_entity=$table_discussion,$column_entity_name='disc_title',$column_entity_key='DISC_ID',$column_entity_date='disc_date');
			
		}
		
		/**
		* Returns $discs
		* ordered in descending order of disc_comment_count
		*
		* @type	GET
		* 
		* @access public
		* @param int 	$num_discs 				The maximum numbers of cdisc results to retrieve
		* @param int 	$offset 					The offset to start retrieving cdisc results from (used for pagination)
		* @param array 	$filter_name_keywords (optional)	Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER
		* @return array 							array of cdiscs
		*/
		public function GetMostPopular($num_discs, $offset, $filter_name_keywords=null,$filter_entity_property_array=FILTER_NONE)
	  	{
	  		$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning '.$num_discs.' most popular discs from offset:'.$offset.' and total_count for pagination');
			
			// @BUG: 'disc_comment_count' is defunct
			$entities = $this->GetEntities($order_by='disc_comment_count',$filter_name_keywords,$filter_entity_property_array,$num_discs, $offset);
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $entities;	
		}
		
		/**
		* Returns $discs
		* ordered in descending order of disc_last_active_date
		*
		* @type	GET
		* 
		* @access public
		* @param int 	$num_discs 				The maximum numbers of cdisc results to retrieve
		* @param int 	$offset 					The offset to start retrieving cdisc results from (used for pagination)
		* @param array 	$filter_name_keywords (optional)	Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER
		* @return array 							array of cdiscs
		*/
		public function GetMostActive($num_discs, $offset, $filter_name_keywords=null,$filter_entity_property_array=FILTER_NONE)
	  	{
	  		$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning '.$num_discs.' most active discs from offset:'.$offset.' and total_count for pagination');
			
			// @BUG: 'disc_comment_count' is defunct
			$entities = $this->GetEntities($order_by='disc_last_active_date',$filter_name_keywords,$filter_entity_property_array,$num_discs, $offset);
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $entities;	
		}
				
		/**-------------------------------------------------------------------------------------------------------------------*/	
	}	
}
 
/* End of file AbstractDiscussionCollectionModel.php */


	