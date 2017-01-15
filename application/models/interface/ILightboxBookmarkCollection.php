<?php
/**
 * ILightboxBookmarkCollection Interface
 * 
 * Purpose
 * 		Interface for all Lightbox Collections of bookmarable entities
 * 
 * 		Bookmarkee: 	Entity that request the bookmark
 * 		Bookmarker: 	Entity that is bookmarked
 * 
 * Created 
 * 		November 7, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @category	interface
 * @author		venksster
 * @link		TBD
 */
if(!defined('I_LIGHTBOX_BOOKMARK_COLLECTION'))
{
	define('I_LIGHTBOX_BOOKMARK_COLLECTION',DEFINED); 

	interface ILightboxBookmarkCollection
	{
		/**
		* MUST return bookmark count
		*
		* @param 	mixed 	$bookmarkee 			
		* @param 	mixed 	$filter_keywords
		* @return	bool
		*/
		function GetBookmarkCount($bookmarkee,$filter_keywords=FILTER_NONE);
		
		/**
		* MUST return bookmarks
		*
		* @param 	mixed 	$bookmarkee 	
		* @param	int		$num_results
		* @param	int		$offset		
		* @param 	string 	$filter_keywords
		* @return	bool
		*/
		function GetBookmarks($bookmarkee,$filter_keywords=FILTER_NONE,$num_results=RESULTS_ALL,$offset=OFFSET_NONE);
	}
}

/* End of file ILightboxBookmarkable.php */


	