<?php
/**
 * UtilLightboxController Class
 * 
 * Purpose
 * 		util controller for Lightbox
 * Created 
 * 		April 4, 2008
 * 
 * @package	Lightbox
 * @subpackage	Controllers
 * @category	util
 * @author		venksster
 */

class UtilLightboxController
{
	/**
   	* @access	public
   	* @param 	string $module 		the log file generated would be the file pointed to by 'logfile_$module' specified in config.php
   	* @return	void					
   	*/
  	public function UtilLightboxController($module='util')
  	{
  		$this->log = clogger_return_instance($module);
	}
	
	public function GetPage($data_array)
	{
		if(isset($data_array) && array_key_exists('page',$data_array))
		{
			return $data_array['page'];
		}
		
		return 1;
	}
	
	public function GetFilterKeywords($filter_array)
	{
		if(isset($filter_array) && is_array($filter_array) && array_key_exists('filter_keywords',$filter_array) && $filter_array['filter_keywords']!='All' && $filter_array['filter_keywords']!='' && $filter_array['filter_keywords']!=' ')
		{
			return $filter_array['filter_keywords'];
		}
		
		return FILTER_NONE;
	}
	
	public function TrimAndTruncateTags($tags_array)
	{
		$new_tags_array = array();
		foreach($tags_array as $tag)
		{
			$this->log->debug("[UTIL CONTROLLER] Original tag is ".$tag);
			$tag_length = strlen($tag);
			$min_tag_length = min($tag_length,MAX_TAG_LENGTH);
			
			//Truncate the tag down to 35
			$truncated_tag = substr($tag,0,$min_tag_length);
			$this->log->debug("[UTIL CONTROLLER] Truncated tag is ".$truncated_tag);
			
			//Remove
			$tag = trim($truncated_tag,TAGS_TRIM_CHARACTERS);			
			$this->log->debug("[UTIL CONTROLLER] Trimmed tag is ".$tag);			
			array_push($new_tags_array,$tag);
		}		
		return $new_tags_array;	
	}
	
	public function AddTags($entity_id,$model,$tags)
	{
		//insert tags
		//$tags = trim($_POST['photo_tags']);
		if( $tags !=  "" )
		{
			$this->log->debug("[UTIL CONTROLLER] Adding tags for entity ".$entity_id);
			$tags_array=preg_split(REGEX_MULTI_COMMA,$tags);			
			
			$new_tags_array = $this->TrimAndTruncateTags($tags_array);
			
			if(!empty($new_tags_array))
			{
				$this->log->debug("[UTIL CONTROLLER] Splitting tag string into ".print_r($new_tags_array,true));			
				$model->AddTags($entity_id,$new_tags_array);
				$this->log->debug("[UTIL CONTROLLER] Added tags for ".$entity_id);
				return true;
			}
			else
			{
				$this->log->info("[UTIL CONTROLLER] No tags specified for ".$entity_id);		
				return false;		
			}
		}
	}
	
	public function GetCommentsByPage($id,$comment_page,$view_num_comments,$comment_model,$check_post_data=true,$column_date='comment_date')
	{
		if($check_post_data==true)
		{
			$comment_page = $this->GetPage($_POST);
		}
		
		$this->log->info('[CONTROLLER] COMMENT PAGE:'.$comment_page);
		
		$this->log->fine('[CONTROLLER] CALCULATING COMMENT OFFSET');
		$comment_offset = ($comment_page - 1) * $view_num_comments;
		$this->log->debug('[CONTROLLER] $comment_offset:'.$comment_offset);
		
		$data_array = array();
		
		$this->log->fine('[CONTROLLER] ADDING COMMENTS TO DATA_ARRAY');
		$data_array['current_comment_page']	= $comment_page;
		$data_array['start_comment']		= $comment_offset + 1;
		$data_array['total_comment_count']	= $comment_model->GetCommentCount($id);
		$this->log->debug('[CONTROLLER] total_comment_count:'.$data_array['total_comment_count']);
		
		$data_array['comment_page_count']	= $data_array['total_comment_count'] == 0 ? 0 : ceil($data_array['total_comment_count'] / $view_num_comments);
		$data_array['comments']			= $data_array['total_comment_count'] == 0 ? array() : $comment_model->GetMostRecentComments($id,$view_num_comments,$comment_offset);
		
		/* NOTE: Recursively returning previous page, if current page has no comments 
		 *  Fix for Bug #60
		 */
		if(count($data_array['comments'])==0 && $comment_page > 1)
		{
			$this->log->error('[CONTROLLER] No comments on this page. Returning comments for page:'.$comment_page-1);
			return $this->GetCommentsByPage($id,$comment_page-1,$view_num_comments,$comment_model,$check_post_data=false);
		}
		
		// Converting the timestamp in the comments to relative date values
		$CI = &get_instance();
		$relative_date_mapping = $CI->config->item('relative_date_mapping');
		krsort($relative_date_mapping);
		//$this->log->debug('relative_date_mapping:'.print_r($relative_date_mapping,true));
		foreach($data_array['comments'] as &$comment)
		{
			$comment[$column_date] = $CI->cutil->ReturnRelativeDateStamp($comment[$column_date],$relative_date_mapping);	
		}
		
		return $data_array;
	}  
	
	
	/**
   	* Processes POST data for artist_focus and returns one array having 'other' focii and regular focii
   	* 
   	* @access	private
   	* @return	array		$artist_focus 			
   	*/
	public function GetArtistFocus($post_array)
	{
		$artist_focus = $post_array['artist_focus'];
		if(array_key_exists('artist_focus_other',$post_array))
		{
			$other_index = array_search('other',$artist_focus);
			if( $other_index!=false )
			{
				$artist_focus[$other_index] = $post_array['artist_focus_other'];	
			}
			
			$this->log->debug('[CONTROLLER] Artist focus, other:'.$artist_focus[$other_index]);
		}
		
		return $artist_focus;
	}	
	
	/**
  	* @access protected
  	*/  
  	protected $log;
		
}
 
/* End of file UtilLightboxController.php */


	