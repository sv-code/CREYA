<?php
/**
 * CRanker Class
 * 
 * Purpose
 * 		Lighbox entity ranker
 * Created 
 * 		March 26, 2009
 * 
 * @package	Lightbox
 * @subpackage	Libraries
 * @category	None
 * @author		venksster
 * @link		TBD
 */

class CRanker 
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function __construct()
	{
    		$this->log = clogger_return_instance('cranker');
    	}
	
	/**
   	* Ranks entities in order of keyword relevance:$entity_name_search_keyword_array
   	* 
   	* @access	public
   	* @param	array	$entity_array
   	* @param	string	$entity_key						entity primary key
   	* @param	string	$entity_search_key				entity search field
   	* @param	array	$entity_search_keyword_array			entity search keywords
   	* @param	int	$num_slice_entities				(optional) number of entities to slice from the top
   	* @return	array 								entities sorted by relevance
   	*/
	public function RankEntitiesByKeywordRelevance($entity_array,$entity_key,$entity_search_key,$entity_search_keyword_array,$num_slice_entities=RESULTS_ALL)
	{
		$entity_count_hash 	= array();
		$entity_hash		= array();
	
		// print_r($entity_search_keyword_array); @todo: LOGGER
	
		foreach($entity_array as $entity)
		{
			foreach($entity_search_keyword_array as $entity_search_keyword)
			{
				// CHECKING IF THE ENTITY MATCHES THE KEYWORD
				if(isset($entity[$entity_search_key]) && substr_count(strtolower($entity[$entity_search_key]),strtolower($entity_search_keyword)) > 0)
				{
					$KEY = $entity[$entity_key];
					
					// THE ENTITY IS MATCHED FOR THE FIRST TIME
					if(!isset($entity_count_hash[$KEY]))
					{
						$entity_count_hash[$KEY] = 1;
						$entity['keyword_match_count'] = 1;
						
						$entity_hash[$KEY] = $entity;
					}
					
					// THE ENTITY HAS BEEN MATCHED EARLIER, THEREFORE BUMPING UP IT'S RANK
					else
					{
						++$entity_count_hash[$KEY];
						++$entity['keyword_match_count'];	
					}
				}
			}
		}
		
		// SORTING ENTITIES BY RANK (entity_count)
		array_multisort($entity_count_hash,SORT_DESC,SORT_NUMERIC,$entity_hash);
		
		$this->log->debug('[MODEL] $entity_count_hash by rank:'.print_r($entity_count_hash,true));
		$this->log->debug('[MODEL] $entity_hash:'.print_r($entity_hash,true));
		
		// SLICING THE RESULTS IF REQUESTED
		if($num_slice_entities!=RESULTS_ALL)
		{
			$entity_hash = array_slice($entity_hash,OFFSET_NONE,$num_slice_entities);
		}
		
		return $entity_hash;
	}
	
	public function ReturnByRank($rank,$entity_array,$entity_rank_key)
	{
		$entity_intersection_array = array();
		
		foreach($entity_array as $entity)
		{
			$entity_rank = $entity[$entity_rank_key];
			
			if($entity_rank==$rank)
			{
				array_push($entity_intersection_array,$entity);
			}
		}
		
		return $entity_intersection_array;
	}
	
	/**
  	* @access private
  	*/ 
	private $log;
}

/* End of file CRanker.php */
