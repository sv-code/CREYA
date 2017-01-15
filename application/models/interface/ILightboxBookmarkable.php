<?php
/**
 * ILightboxBookmarkable Interface
 * 
 * Purpose
 * 		Interface for all Lightbox Bookmarkable Entities
 * 
 * 		Bookmarkee: 	Entity that request the bookmark
 * 		Bookmarker: 	Entity that is bookmarked
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
if(!defined('I_LIGHTBOX_BOOKMARKABLE'))
{
	define('I_LIGHTBOX_BOOKMARKABLE',DEFINED); 

	interface ILightboxBookmarkable
	{
		/**
		* MUST check if $bookmarkee is bookmarked by $bookmarker
		*
		* @param 	mixed 	$bookmarkee 			
		* @param 	mixed 	$bookmarker
		* @return	bool
		*/
		function IsBookmarked($bookmarkee,$bookmarker);
		
		/**
		* MUST return the number of bookmarks for the bookmarkee
		*
		* @param 	mixed 	$bookmarkee 			
		* @return	int
		*/
		function GetBookmarkCount($bookmarkee);
		
		/**
		* MUST bookmark $bookmarkee by $bookmarker
		*
		* @param 	mixed 	$bookmarkee 			
		* @param 	mixed 	$bookmarker
		* @return	void
		*/
		function Bookmark($bookmarkee,$bookmarker);
		
  		/**
		* MUST unbookmark $bookmarkee by $bookmarker
		*
		* @param 	mixed 	$bookmarkee 			
		* @param 	mixed 	$bookmarker
		* @return	void
		*/
		function UnBookmark($bookmarkee,$bookmarker);	
		
	}
}

/* End of file ILightboxBookmarkable.php */


	