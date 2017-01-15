<?php
/**
 * ILightboxExplorable Interface
 * 
 * Purpose
 * 		Interface for all Lighbox Explorables
 * Created 
 * 		Feb 24, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @category	interface
 * @author		venksster
 * @link		TBD
 */
if(!defined('I_LIGHTBOX_EXPLORABLE'))
{
	define('I_LIGHTBOX_EXPLORABLE',DEFINED); 

	interface ILightboxExplorable 
	{
		/**
		* MUST return total number of entities
		*
		* @param array 	$filter_name_keywords 	(optional) Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER		
		* @return array 					array of entities 
		*/
		function GetEntityCount($filter_name_keywords=FILTER_NONE);
		
		/**
		* MUST return $num_entities entities
		* ordered in descending order of date posted
		*
		* @param int 	$num_entities 		The maximum numbers of entities to retrieve
		* @param int 	$offset 			The offset to start retrieving entities from 
		* @param array 	$filter_name_keywords 	(optional) Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER		
		* @return array 					array of entities 
		*/
		function GetMostRecent($num_entities,$offset,$filter_name_keywords=FILTER_NONE);
		
		/**
		* MUST return $num_entities entities
		* ordered by most relevant
		*
		* @param int 	$num_entities 		The maximum numbers of entities to retrieve
		* @param int 	$offset 			The offset to start retrieving entities from
		* @param array 	$filter_name_keywords	Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER 			
		* @return array 					array of entities 
		*/
		function GetMostRelevant($num_entities,$offset,$filter_name_keywords);
		
	}
}

/* End of file ILightboxExplorable.php */


	