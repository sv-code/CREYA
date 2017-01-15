<?php
/**
 * ArtistCollectionModel
 *
 * Interfaces
 * 
 *		AbstractLightboxCollectionModel::GetEntityCount	($filter_name=FILTER_NONE)
 * 		AbstractLightboxCollectionModel::GetEntities		($order_by=ORDER_DATE,$filter_name=FILTER_NONE,$num_entities=RESULTS_ALL,$offset=OFFSET_NONE)
 * 
 * 		ILightboxExplore::GetMostRecent				($num_artists,$offset,$filter_name_keywords=null)
 * 		ILightboxExplore::GetMostRelevant				($num_artists,$offset,$filter_name_keywords)
 * 
 * 		ArtistCollectionModel::GetTopRated				($num_results,$offset,$filter_name_keywords=null)
 * 
 * Created
 * 		March 28, 2009
 *
 * @package 	Lightbox
 * @subpackage 	Models
 * @category 	none
 * @author 	venksster 
 */

require MODEL_BASE_PATH.'Abstract.Collection.LightboxModel'.EXT;

class ArtistCollectionModel extends AbstractLightboxCollectionModel
{
	/**
	* Constructor
	*
	* @access public
	*/
	public function ArtistCollectionModel($module='artistexplore')
	{
		parent::AbstractLightboxCollectionModel($module,$table_entity='artist',$column_entity_name='artist_dname',$column_entity_key='artist_dname',$column_entity_date='artist_join_date');
	}
	
	/**
	* Returns $num_artists groups, and total number of results for pagination
	* ordered by top rated
	*
	* @type	GET
	* 
	* @access public
	* @param int 		$num_artists 		The maximum numbers of group results to retrieve
	* @param int 		$offset 			The offset to start retrieving group results from (used for pagination)
	* @param array 		$filter_name_keywords	Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER
	* @return array 						array of discs
	*/
	public function GetTopRated($num_artists,$offset,$filter_name_keywords)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_artists.' most relevant artists from offset:'.$offset);
		
		$artists = $this->GetEntities($order_by='artist_rating',$filter_name_keywords,$filter_entity_property_array=FILTER_NONE,$num_artists,$offset);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $artists;	
	}
	
}	

/* End of file ArtistCollectionModel.php */