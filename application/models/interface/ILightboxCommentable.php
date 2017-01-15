<?php
/**
 * ILightboxCommentable Interface
 * 
 * Purpose
 * 		Interface for all Lightbox Commentable Entities
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
if(!defined('I_LIGHTBOX_COMMENTABLE'))
{
	define('I_LIGHTBOX_COMMENTABLE',DEFINED); 

	interface ILightboxCommentable
	{
		/**
		* MUST Return number of comments for entity:$entity_id
		*
		* @param 	mixed 	$entity_id 			
		* @return	int
		*/
		function GetCommentCount($entity_id,$filter_keywords=FILTER_NONE);
		
		/**
		* MUST Return recent comments for entity:$entity_id
		*
		* @param 	mixed		$entity_id 		
		* @param	int		$num_comments
		* @param	int		$offset	
		* @param	string		$filter_keywords	
		* @return	array
		*/
		function GetMostRecentComments($entity_id,$num_comments,$offset,$filter_keywords=FILTER_NONE);
		
		/**
		* MUST Return relevant comments for entity:$entity_id
		*
		* @param 	mixed 	$entity_id 
		* @param	int		$num_comments
		* @param	int		$offset	
		* @param	string		$filter_keywords			
		* @return	array
		*/
		function GetMostRelevantComments($entity_id,$num_comments,$offset,$filter_keywords);
		
		/**
		* MUST Return comments for entity:$entity_id
		*
		* @param 	mixed 	$entity_id 		
		* @param	int		$order_by
		* @param	string		$filter_keywords	
		* @param	int		$num_comments
		* @param	int		$offset
		* @return	array
		*/
		function GetComments($entity_id,$order_by=ORDER_DATE,$filter_keywords=FILTER_NONE,$num_comments=RESULTS_ALL,$offset=OFFSET_NONE);

		/**
		* MUST Add a comment
		*
		* @param 	mixed 	$entity_to 						
		* @param 	mixed 	$entitiy_from
		* @param	string		$comment_text
		* @param	mixed		$comment_optional_arg(optional)
		* @return	bool
		*/
		function AddComment($entity_to,$entitiy_from,$comment_text,$comment_optional_arg=null);
				
   		/**
		* MUST delete a comment
		*
		* @param 	mixed 	$entity_id 	
		* @param 	int 		$comment_id 			
		* @return	void
		*/
		function DeleteComment($entity_id,$comment_id);
	}
}

/* End of file ILightboxCommentable.php */


	