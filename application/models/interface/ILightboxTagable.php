<?php
/**
 * ILightboxTagable Interface
 * 
 * Purpose
 * 		Interface for all Lightbox Tagable Entities
 * 
 * Created 
 * 		March 17, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @category	interface
 * @author		venksster
 * @link		TBD
 */
if(!defined('I_LIGHTBOX_TAGABLE'))
{
	define('I_LIGHTBOX_TAGABLE',DEFINED); 

	interface ILightboxTagable
	{
		/**
		* MUST return all tags for entity:$entity_id
		*
		* @param 	mixed 	$entity_id 			
		* @return	array
		*/
		function GetTags($entity_id);
		
		/**
		* MUST add a tag:$tag for entity:$entity_id
		*
		* @param 	mixed 	$entity_to 						
		* @param 	string 	$tag
		* @return	void
		*/
		function AddTag($entity_id,$tag);
				
   		/**
		* MUST add tags:$tags for entity:$entity_id
		*
		* @param 	mixed		$entity_id 			
		* @return	array		$tags
		*/
		function AddTags($entity_id,$tags);
		
		/**
		* MUST delete the tag:$tag for entity:$entity_id
		*
		* @param 	mixed 	$entity_from						
		* @param 	string 	$tag
		* @return	void
		*/
		function DeleteTag($entity_id,$tag);
	}
}

/* End of file ILightboxTagable.php */


	