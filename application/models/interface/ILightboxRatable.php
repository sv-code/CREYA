<?php
/**
 * ILightboxRatable Interface
 * 
 * Purpose
 * 		Interface for all Lightbox Ratable Entities
 * 
 * Created 
 * 		March 16, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @category	interface
 * @author		venksster
 * @link		TBD
 */
if(!defined('I_LIGHTBOX_RATABLE'))
{
	define('I_LIGHTBOX_RATABLE',DEFINED); 

	interface ILightboxRatable
	{
		/**
		* MUST check if the entity:$entity_id has ever been rated
		*
		* @param 	mixed 	$entity_id 			
		* @return	bool
		*/
		function IsRated($entity_id);
		
		/**
		* MUST check if the entity:$entity_id has been rated by artist:$artist_dname
		*
		* @param 	mixed 	$entity_to 						
		* @param 	string 	$artist_dname
		* @return	bool
		*/
		function IsRatedByArtist($entity_id,$artist_dname);
				
   		/**
		* MUST return the number of ratings for entity:$entity_id
		*
		* @param 	mixed		$entity_id 			
		* @return	int
		*/
		function GetRatingCount($entity_id);
		
		/**
		* MUST return overall rating for entity:$entity_id
		*
		* @param 	mixed		$entity_id 			
		* @return	array
		*/
		function GetOverallRating($entity_id);
		
		/**
		* MUST return metric rating:$mid for entity:$entity_id
		*
		* @param 	mixed		$entity_id 			
		* @return	array
		*/
		function GetMetricRating($entity_id,$mid);
		
		/**
		* MUST return metric ratings for entity:$entity_id
		*
		* @param 	mixed		$entity_id 			
		* @return	array
		*/
		function GetAllMetricRatings($entity_id);
		
		/**
		* MUST return suggestion count:$sid for entity:$entity_id
		*
		* @param 	mixed		$entity_id 			
		* @return	array
		*/
		function GetSuggestionCount($entity_id,$sid);
		
		/**
		* MUST return suggestion counts for entity:$entity_id
		*
		* @param 	mixed		$entity_id 			
		* @return	array
		*/
		function GetAllSuggestionCounts($entity_id);
		
		/**
		* MUST add a rating for entity:$entity_id by artist:$artist_dname
		*
		* @param 	mixed		$entity_id 			
		* @return	array
		*/
		function AddRating($entity_id,$artist_dname,$ratings,$ratings_optional_arg=null);
	}
}

/* End of file ILightboxRatable.php */


	