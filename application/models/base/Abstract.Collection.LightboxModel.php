<?php
/**
 * AbstractLightboxCollectionModel Class
 * 
 *  Definition
 * 		
 * 		A <LightboxCollection> is a collection of <LightboxEntity>
 * 
 * Interfaces
 * 
 *		AbstractLightboxCollectionModel::GetEntityCount	($filter_name=FILTER_NONE)
 * 		AbstractLightboxCollectionModel::GetEntities		($order_by=ORDER_DATE,$filter_name=FILTER_NONE,$num_entities=RESULTS_ALL,$offset=OFFSET_NONE)
 * 		AbstractLightboxCollectionModel::GetRelated		($entity_id,$num_entities=RESULTS_ALL,$offset=OFFSET_NONE)
 * 		
 * 		ILightboxExplore::GetMostRecent				($num_entities,$offset,$filter_name_keywords=null)
 * 		ILightboxExplore::GetMostRelevant				($num_entities,$offset,$filter_name_keywords)
 * 			
 * Created 
 * 		March 17, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @author		venksster
 * @category	base
 * @link		TBD
 */
if(!defined('ABSTRACT_LIGHTBOX_COLLECTION_MODEL'))
{
	define('ABSTRACT_LIGHTBOX_COLLECTION_MODEL',DEFINED);

	require MODEL_BASE_PATH.'Abstract.LightboxModel'.EXT;
	require MODEL_INTERFACE_PATH.'ILightboxExplorable'.EXT;
	
	abstract class AbstractLightboxCollectionModel extends AbstractLightboxModel implements ILightboxExplorable
	{
		/**
	   	* Constructor
	   	* 
	   	* @access	public
	   	* @return	void 			
	   	*/
		public function AbstractLightboxCollectionModel($module,$table_entity,$column_entity_name,$column_entity_key,$column_entity_date)
		{
			parent::AbstractLightboxModel($module);
			
			$this->TABLE_ENTITY			= $table_entity;
			$this->COLUMN_ENTITY_NAME	= $column_entity_name;
			$this->COLUMN_ENTITY_KEY		= $column_entity_key;
			$this->COLUMN_ENTITY_DATE	= $column_entity_date;
			
			$this->load->library('CRanker');
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
		public function GetMostRecent($num_entities,$offset,$filter_name_keywords=FILTER_NONE)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning '.$num_entities.' most recent entities from offset:'.$offset.' and total_count for pagination');
			
			$entities = $this->GetEntities(ORDER_DATE,$filter_name_keywords,$filter_entity_property_array=FILTER_NONE,$num_entities, $offset);
			
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
		public function GetMostRelevant($num_entities,$offset,$filter_name_keywords)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning '.$num_entities.' most relevant entities from offset:'.$offset.' and total_count for pagination');
			
			$entities = $this->GetEntities(ORDER_RELEVANCE,$filter_name_keywords,$filter_entity_property_array=FILTER_NONE,$num_entities,$offset);
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $entities;	
		}
		
		/**
		* @access public
		* @param string 		$orderBy 				The field name to order the search results by (eg: 'entity_date')
		* @param array 		$filter_name_keywords 		Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER
		* @return int 							number of entities  
		*/
		public function GetEntityCount($filter_name_keywords=FILTER_NONE,$filter_entity_property_array=FILTER_NONE)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning entitie count for:'.$this->TABLE_ENTITY);
			
			$this->log->fine('[MODEL] FILTER_KEYWORDS:'.				$filter_name_keywords);
			$this->log->fine('[MODEL] FILTER_ENTITY_PROPERTY_ARRAY:'.	print_r($filter_entity_property_array,true));
			
			$this->db->select('*');
					
			// FILTER BY ENTITY NAME	
			if($filter_name_keywords!=FILTER_NONE)
			{
				$entity_name_keywords = explode(LIGHTBOX_SEARCH_KEYWORD_DELIMITER,$filter_name_keywords);
				
				if(count($entity_name_keywords) > 0)
				{
					$this->db->or_like_in($this->COLUMN_ENTITY_NAME,$entity_name_keywords);	
				}
			}
			
			// FILTER BY ENTITY PROPERTIES
			if($filter_entity_property_array!=FILTER_NONE && is_array($filter_entity_property_array))
			{
				$this->db->where($filter_entity_property_array);
			}
			
			$entity_count = $this->db->count_all_results($this->TABLE_ENTITY);
			$this->log->info('[MODEL] ENTITY COUNT:'.$entity_count);
						
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $entity_count;		
		}
	
		/**
		* @access public
		* @param string 		$orderBy 			The field name to order the search results by (eg: 'entity_date')
		* @param array 		$filter_name_keywords 	Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER
		* @param int 		$num_entities 		The maximum numbers of entity results to retrieve
		* @param int 		$offset 			The offset to start retrieving entity results from (used for pagination)
		* @return array 						array of entities  
		*/
		public function GetEntities($order_by=ORDER_DATE,$filter_name_keywords=FILTER_NONE,$filter_entity_property_array=FILTER_NONE,$num_entities=RESULTS_ALL,$offset=OFFSET_NONE)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning entities for:'.$this->TABLE_ENTITY);
			
			$this->log->fine('[MODEL] ORDER_BY:'.					$order_by);
			$this->log->fine('[MODEL] FILTER_KEYWORDS:'.				$filter_name_keywords);
			$this->log->fine('[MODEL] FILTER_ENTITY_PROPERTY_ARRAY:'.	print_r($filter_entity_property_array,true));
			$this->log->fine('[MODEL] NUM_ENTITIES:'.				$num_entities);
			$this->log->fine('[MODEL] OFFSET:'.					$offset);	
			
			$this->db->select('*');
					
			// FILTER BY ENTITY NAME	
			$entity_name_keywords = array();		
			if($filter_name_keywords!=FILTER_NONE)
			{
				$filter_name_keywords = rtrim($filter_name_keywords,LIGHTBOX_SEARCH_KEYWORD_DELIMITER);
				$entity_name_keywords = explode(LIGHTBOX_SEARCH_KEYWORD_DELIMITER,$filter_name_keywords);
				
				if(count($entity_name_keywords) > 0)
				{
					$this->db->or_like_in($this->COLUMN_ENTITY_NAME,$entity_name_keywords);	
				}
			}
			
			// FILTER BY ENTITY PROPERTIES
			if($filter_entity_property_array!=FILTER_NONE && is_array($filter_entity_property_array))
			{
				$this->db->where($filter_entity_property_array);
			}
			
			// ORDER 
			switch($order_by)
			{
				case ORDER_DATE:
					$this->db->order_by($this->COLUMN_ENTITY_DATE,'desc');
					$entity_array = $this->db->get($this->TABLE_ENTITY,$num_entities,$offset)->result_array();
					break;

				case ORDER_RELEVANCE:
					$entity_array = $this->db->get($this->TABLE_ENTITY,RESULTS_ALL,OFFSET_NONE)->result_array();
					$entity_array = $this->cranker->RankEntitiesByKeywordRelevance($entity_array,$this->COLUMN_ENTITY_KEY,$this->COLUMN_ENTITY_NAME,$entity_name_keywords,$num_entities,$offset);
					break;
					
				DEFAULT:
					$this->db->order_by($order_by,'desc');
					$entity_array = $this->db->get($this->TABLE_ENTITY,$num_entities,$offset)->result_array();
			}
			
			$this->log->debug('[MODEL] Returning '.count($entity_array).' entities:'.print_r($entity_array,true));
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $entity_array;		
		}
		
		/**
		* @access public
		* @param mixed 		$entity_id 		
		* @return array				array of entities  
		*/
		public function GetSimilar($entity_id,$num_entities=RESULTS_ALL,$offset=OFFSET_NONE)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning related entities for:'.$this->TABLE_ENTITY);
			
			// Retrieving entity name
			$this->db->where($this->COLUMN_ENTITY_KEY,$entity_id);
			$entity_name = $this->db->get_attribute($this->TABLE_ENTITY,$this->COLUMN_ENTITY_NAME);
			
			if($entity_name==null)
			{
				throw new Exception('[MODEL]'.__METHOD__.':$entity_id DOES NOT EXIST OR $entity_name=NULL');
			} 
			
			// Substituting WHITESPACE with LIGHTBOX_SEARCH_KEYWORD_DELIMITER
			$entity_name = str_ireplace(WHITESPACE,LIGHTBOX_SEARCH_KEYWORD_DELIMITER,$entity_name);
			
			// Retrieving related entities
			$entity_array = $this->GetEntities($order_by=ORDER_RELEVANCE,$filter_name_keywords=$entity_name,$filter_entity_property_array=FILTER_NONE,$num_entities,$offset);
			
			// Remove SELF:$entity_id from relevant entities
			for($i=0;$i<count($entity_array);++$i)
			{
				if($entity_array[$i][$this->COLUMN_ENTITY_KEY]==$entity_id)
				{
					unset($entity_array[$i]);
				}
			}
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $entity_array;	
		}
		
		/**-------------------------------------------------------------------------------------------------------------------*/
		
		/**
	  	* @access private
	  	*/
		private $COLUMN_ENTITY_DATE;
		
		/**
	  	* @access private
	  	*/
		private $COLUMN_ENTITY_KEY;
		
		/**
	  	* @access private
	  	*/
		private $COLUMN_ENTITY_NAME;
		
		/**
	  	* @access private
	  	*/
		private $TABLE_ENTITY;
		
	}
}
/* End of file AbstractLightboxCollectionModel.php */

