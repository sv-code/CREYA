<?php
/**
 * AbstractLightboxCommentableAndBookmarkableEntityModel Class
 * 
 * Definition
 * 		
 * 		A <CommentableLightboxEntity> IS a <LightboxEntity>, that IMPLEMENTS the <ILightboxCommentable> interface
 * 
 * Interfaces
 * 
 * 		AbstractLightboxEntityModel::Add				($artist_dname,$entity_required_data,$entity_optional_data=null);
 * 		AbstractLightboxEntityModel::Delete				($entity_id)
 * 		AbstractLightboxEntityModel::GetDetails			($entity_id)
 * 		AbstractLightboxEntityModel::GetViewCount		($entity_id)
 * 		AbstractLightboxEntityModel::IncrementViewCount	($entity_id)
 * 
 *  		ILightboxCommentable::GetCommentCount		($entity_id)	
 *  		ILightboxCommentable::GetComments			($entity_id)	
 * 		ILightboxCommentable::AddComment			($entity_id,$artist_dname,$comment_text,$sid=null)
 * 		ILightboxCommentable::DeleteComment			($entity_id,$comment_id)
 * 
 *   		ILightboxBookmarkable::GetBookmarkCount		($entity_id)	
 * 		ILightboxBookmarkable::IsBookmarked			($entity_id,$artist_dname)		
 *  		ILightboxBookmarkable::Bookmark			($entity_id,$artist_dname)
 * 		ILightboxBookmarkable::UnBookmark			($entity_id,$artist_dname)	
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
if(!defined('ABSTRACT_LIGHTBOX_COMMENTABLE_ENTITY_MODEL'))
{
	define('ABSTRACT_LIGHTBOX_COMMENTABLE_ENTITY_MODEL',DEFINED);
	
	require MODEL_BASE_PATH.'Abstract.Entity.LightboxModel'.EXT;
	require MODEL_INTERFACE_PATH.'ILightboxCommentable'.EXT;
	require MODEL_INTERFACE_PATH.'ILightboxBookmarkable'.EXT;
	
	abstract class AbstractLightboxCommentableAndBookmarkableEntityModel extends AbstractLightboxEntityModel implements ILightboxCommentable,ILightboxBookmarkable
	{
		/**
	   	* Constructor
	   	* 
	   	* @access	public
	   	* @return	void 			
	   	*/
		public function AbstractLightboxCommentableAndBookmarkableEntityModel($module,$table_entity,$column_entity_key,$column_comment_key=null,$column_view_count=null,$column_comment_count=null,$table_comment=null)
		{
			parent::AbstractLightboxEntityModel($module,$table_entity,$column_entity_key,$column_view_count);
			
			if(isset($table_comment))
			{
				$this->TABLE_COMMENT = $table_comment;
			}
			else
			{
				$this->TABLE_COMMENT = $table_entity.'_comment';	
			}			
			
			$this->TABLE_BOOKMARK = 'artist_bookmark_'.$table_entity;
			
			if(isset($column_comment_count))
			{
				$this->COLUMN_COMMENT_COUNT = $column_comment_count;
			}
			else
			{
				$this->COLUMN_COMMENT_COUNT = $table_entity.'_comment_count';
			}
			
			if(isset($column_comment_key))
			{
				$this->COLUMN_COMMENT_KEY = $column_comment_key;
			}
			else
			{
				$this->COLUMN_COMMENT_KEY = $this->COLUMN_ENTITY_KEY;
			}
			
			$this->load->library('CRanker');
		}
		
		public function GetMostRecentComments($entity_id,$num_comments,$offset,$filter_keywords=FILTER_NONE)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning '.$num_comments.' most recent comments from offset:'.$offset);
			
			$comments = $this->GetComments($entity_id,ORDER_DATE,$filter_keywords,$num_comments,$offset);
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $comments;
		}
		
		public function GetMostRelevantComments($entity_id,$num_comments,$offset,$filter_keywords)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning '.$num_comments.' most recent comments from offset:'.$offset);
			
			$comments = $this->GetComments($entity_id,ORDER_RELEVANCE,$filter_keywords,$num_comments,$offset);
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $comments;
		}
	
		public function GetCommentCount($entity_id,$filter_keywords=FILTER_NONE)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning comments count for entity:'.$entity_id);
			
			$this->db->where($this->COLUMN_COMMENT_KEY,$entity_id);
			
			if($filter_keywords!=FILTER_NONE)
			{
				$comment_keywords = explode(' ',$filter_keywords);
				if(count($comment_keywords) > 0)
				{
					$this->db->or_like_in('comment_text',$entity_name_keywords);	
				}
			}
			
			$comment_count = $this->db->count_all_results($this->TABLE_COMMENT);
			$this->log->fine('[MODEL] Comment count:'.$comment_count);
						
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $comment_count;
		}
		
		public function GetComments($entity_id,$order_by=ORDER_DATE,$filter_keywords=FILTER_NONE,$num_entities=RESULTS_ALL,$offset=OFFSET_NONE)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning comment data for entity:'.$entity_id);
			
			$this->db->select('*');
			$this->db->where($this->COLUMN_COMMENT_KEY,$entity_id);
			
			// JOIN CLAUSE FOR artist_avatar
			$join_clause = 'artist.artist_dname='.$this->TABLE_COMMENT.'.artist_dname';
			$this->db->join('artist',$join_clause);
						
			// FILTER BY KEYWORDS
			$comment_keywords = array();		
			if($filter_keywords!=FILTER_NONE)
			{
				$comment_keywords = explode(' ',$filter_keywords);
				
				if(count($comment_keywords) > 0)
				{
					$this->db->or_like_in('comment_text',$entity_name_keywords);	
				}
			}
			
			// ORDER 
			switch($order_by)
			{
				case ORDER_DATE:
					$this->db->order_by('comment_date','desc');
					$comment_array = $this->db->get($this->TABLE_COMMENT,$num_entities,$offset)->result_array();
					break;

				case ORDER_RELEVANCE:
					$comment_array = $this->db->get($this->TABLE_COMMENT,RESULTS_ALL,OFFSET_NONE)->result_array();
					$comment_array = $this->cranker->RankEntitiesByKeywordRelevance($comment_array,'COMMENT_ID','comment_text',$comment_keywords,$num_entities,$offset);
			}
		
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $comment_array;
		}
		
		public function AddComment($entity_id,$artist_dname,$comment_text,$additional_data=null)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Adding new comment for entity:'.$entity_id.' from artist_dname:'.$artist_dname);
			
			$data_db = array
			(
				$this->COLUMN_COMMENT_KEY	=>	$entity_id,
				'artist_dname'				=>	$artist_dname,
				'comment_text'				=>	$comment_text,
				'comment_date'				=>	$this->util->GetCurrentDateStamp()
			);
			
			if(isset($additional_data) && is_array($additional_data))
			{
				foreach($additional_data as $column=>$value)
				{
					$data_db[$column] = $value;
				}
					
			}
			
			$this->db->insert($this->TABLE_COMMENT,$data_db);	
			
			/*
			$this->log->fine('[MODEL] Adding to comment count for entity_id:'.$entity_id);
			$this->db->increment($this->TABLE_ENTITY,$this->COLUMN_COMMENT_COUNT,array($this->COLUMN_ENTITY_KEY=>$entity_id));
			*/
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		}
		
		public function DeleteComment($entity_id,$comment_id)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Deleting comment:'.$comment_id.' for entity_id:'.$entity_id);
			
			$this->db->delete($this->TABLE_COMMENT,array('COMMENT_ID'=>$comment_id));
			
			/*
			$this->log->fine('[MODEL] Subtracting from comment count for entity:'.$entity_id);
			$this->db->decrement($this->TABLE_ENTITY,$this->COLUMN_COMMENT_COUNT,array($this->COLUMN_ENTITY_KEY=>$entity_id));
			*/
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		}
		
		/**
	     	* @type	GET
     		* 
   		* @access	public
   		* @param	mixed 		$entity_id		
   		* @return	int			bookmark count
   		*/
		public function GetBookmarkCount($entity_id)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning bookmark count for entity:'.$entity_id);
		
			/*
			@TODO: dont use photo_bookmark_count. Do it the long way
			$this->db->where($this->COLUMN_ENTITY_KEY,$entity_id);
			$result = $this->db->get_attribute($this->TABLE_ENTITY,'photo_bookmark_count');
			
			*/
			$result = 100;
		
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $result;
		}
	
		/**
     		* @type	GET
   		* 
   		* @access	public
   		* @param	int		$entity_id			 
   		* @param	string		$artist_dname
   		* @return	bool		whether $entity_id has been bookmarked by $artist_dname					 			
   		*/
		public function IsBookmarked($entity_id,$artist_dname)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Checking if entity'.$entity_id.' is bookmarked by artist '.$artist_dname);
	
			$this->db->where(array
			(
				$this->COLUMN_ENTITY_KEY		=>	$entity_id,
				'artist_dname'	=>	$artist_dname
			));
			
			$this->db->from($this->TABLE_BOOKMARK);
			if($this->db->count_all_results() > 0)
			{
				$result = true;
			}
			else
			{
				$result = false;
			}
				
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $result;
		}
	
		/**
	     	* @type	PUT
	   	* 
	   	* @access	public
	   	* @param	int		$entity_id		 
	   	* @param	string		$artist_dname
	   	* @return	void					 			
	   	*/
		public function Bookmark($entity_id,$artist_dname)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Attempting to bookmark entity '.$entity_id.' for artist '.$artist_dname);
			
			if(!$this->IsBookmarked($entity_id,$artist_dname))
			{
				$this->log->info('[MODEL] Bookmarking entity '.$entity_id.' for artist '.$artist_dname);
				
				$data_db = array
				(
					$this->COLUMN_ENTITY_KEY		=>	$entity_id,
					'artist_dname'	=>	$artist_dname,
					'bookmark_date' 	=> 	$this->util->GetCurrentDateStamp()
				);
				
				$this->db->insert($this->TABLE_BOOKMARK,$data_db);	
				
				/*
				$this->log->fine('[MODEL] Adding to bookmark count for pid:'.$entity_id);
				$this->db->increment('photo','photo_bookmark_count',array($this->COLUMN_ENTITY_KEY=>$entity_id));
				*/
			}
			else
			{
				$this->log->error('[MODEL] Bookmark already exists for entity:'.$entity_id.' by artist:'.$artist_dname);
			}		
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		}
		
		/**
	     	* @type	PUT
	   	* 
	   	* @access	public
	   	* @param	int		$entity_id		 
	   	* @param	string		$artist_dname
	   	* @return	void					 			
	   	*/
		public function UnBookmark($entity_id,$artist_dname)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Attempting to unbookmark entity '.$entity_id.' for artist '.$artist_dname);
			
			if($this->IsBookmarked($entity_id,$artist_dname))
			{
				$this->log->info('[MODEL] UnBookmarking entity '.$entity_id.' for artist '.$artist_dname);
	
				$this->db->where(array
				(
					$this->COLUMN_ENTITY_KEY 	=> 	$entity_id, 
					'artist_dname' 	=> 	$artist_dname
				));
				
				$this->db->delete($this->TABLE_BOOKMARK);
				
				/*
				$this->log->fine('[MODEL] Subtracting from bookmark count for pid:'.$entity_id);
				$this->db->decrement('photo','photo_bookmark_count',array($this->COLUMN_ENTITY_KEY=>$entity_id));
				*/
			}
			else
			{
				$this->log->error('[MODEL] Bookmark does not exist for entity:'.$entity_id.' by artist:'.$artist_dname);
			}
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		}
		
		
		
		/**
	  	* @access protected
	  	*/
		protected $COLUMN_COMMENT_COUNT;
		
		/**
	  	* @access protected
	  	*/
		protected $COLUMN_COMMENT_KEY;
		
		/**
	  	* @access protected
	  	*/
		protected $TABLE_COMMENT;
		
		/**
	  	* @access protected
	  	*/
		protected $TABLE_BOOKMARK;
	}
}
/* End of file AbstractLightboxCommentableAndBookmarkableEntityModel.php */

