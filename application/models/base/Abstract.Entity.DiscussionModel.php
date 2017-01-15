<?php
/**
 * AbstractDiscussionEntityModel Class
 * 
 * Interfaces
 * 
 * 		AbstractLightboxEntityModel::Add				($artist_dname,$photo_name,$photo_type,$photo_data=null)
 * 		AbstractLightboxEntityModel::Delete			($disc_id)
 * 		AbstractLightboxEntityModel::GetDetails			($disc_id)
 * 
 *  		ILightboxCommentable::GetCommentCount		($disc_id)	
 *  		ILightboxCommentable::GetComments			($disc_id)	
 * 		ILightboxCommentable::AddComment			($disc_id,$artist_dname,$comment_text,$comment_photo_attachment_id=null)
 * 		ILightboxCommentable::DeleteComment			($comment_id)
 * 
 * 		ILightboxTagable::GetTags					($disc_id)
 * 		ILightboxTagable::AddTag					($disc_id,$tag)
 * 		ILightboxTagable::DeleteTag					($disc_id,$tag)
 * 
 *   		ILightboxBookmarkable::GetBookmarkCount		($disc_id)	
 * 		ILightboxBookmarkable::IsBookmarked			($disc_id,$artist_dname)		
 *  		ILightboxBookmarkable::Bookmark			($disc_id,$artist_dname)
 * 		ILightboxBookmarkable::UnBookmark			($disc_id,$artist_dname)	
 * 
 * 		AbstractDiscussionEntityModel::GetCreator			($disc_id)
 * 		AbstractDiscussionEntityModel::GetMostActiveArtists	($num_results,$filters=null)
 * 		AbstractDiscussionEntityModel::EditTitle				($disc_id,$disc_title)
 * 		AbstractDiscussionEntityModel::EditBody			($disc_id,$disc_body)
 * 
 * Created 
 * 		Feb 28, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @category	base
 * @author		venksster
 * @link		TBD
 */
if(!defined('ABSTRACT_DISCUSSION_ENTITY_MODEL'))
{
	define('ABSTRACT_DISCUSSION_ENTITY_MODEL',DEFINED);

	require MODEL_BASE_PATH.'Abstract.Entity.LightboxCommentableAndBookmarkableModel'.EXT;
	require MODEL_INTERFACE_PATH.'ILightboxTagable'.EXT;
	require MODEL_INTERFACE_PATH.'ILightboxBookmarkable'.EXT;
		 
	abstract class AbstractDiscussionEntityModel extends AbstractLightboxCommentableAndBookmarkableEntityModel implements ILightboxTagable,ILightboxBookmarkable
	{
		/**
	   	* Constructor
	   	* 
	   	* @access	protected
	   	* @param 	string $module
	   	* @param 	string $table_discussion
	   	* @param	string $table_discussion_comment
	   	* @return	void 			
	   	*/
		protected function AbstractDiscussionEntityModel($module,$table_discussion,$table_discussion_comment)
	  	{
	 		parent::AbstractLightboxCommentableAndBookmarkableEntityModel($module,$table_entity=$table_discussion,$column_entity_key='DISC_ID',$column_comment_key=null,$column_view_count='disc_view_count',$column_comment_count='disc_comment_count',$table_comment=$table_discussion_comment);
			
			$this->TABLE_DISCUSSION			= $table_discussion;
			$this->TABLE_DISCUSSION_COMMENT	= $table_discussion_comment;
		}
		
		/**
	   	* Creates a new discussion
	   	* 
	   	* @type	PUT
	   	* 
	   	* @access	protected
	   	* @param	string		$artist_dname
	   	* @param 	string		$disc_title
	   	* @param	string		$disc_body
	   	* @param	array		$additional_fields		any additional columns that need to be populated. 
	   	* @param	bool		$is_image_attached
	   	* @return	int		$key				primary key of the inserted discussion		
	   	*/
		public function Add($artist_dname,$disc_data,$additional_fields=null)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Creating new discussion');
			
			if(!isset($disc_data) || !is_array($disc_data) || !array_key_exists('disc_title',$disc_data) || !array_key_exists('disc_body',$disc_data))
			{
				throw new Exception('[MODEL] Cannot Add Discussion: Reason:disc_title OR disc_body OR both data missing');
			}
			
			$data = array
			(
				'artist_dname'			=> 	$artist_dname,
				'disc_title' 				=> 	$disc_data['disc_title'],
				'disc_body'				=>	$disc_data['disc_body'],
				'disc_date'				=>	$this->util->GetCurrentDateStamp(),
				'disc_last_active_date'		=>	$this->util->GetCurrentDateStamp(),
				'disc_image_attachments'	=>	$disc_data['disc_image_attachments']
			);
			
			if(isset($additional_fields) && is_array($additional_fields))
			{
				foreach($additional_fields as $column_name => $column_value)
				{
					$data[$column_name] = $column_value;
				}
			}
			
			$this->db->insert($this->TABLE_DISCUSSION,$data);	
			$key = mysql_insert_id();
			$this->log->info('KEY:'.$key);
		
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $key;
		}
		
		/**
	   	* Adds comments to discussion:$disc_id 
	   	* 
	   	* @type	PUT
	   	* 
	   	* @access	public
	   	* @param 	int		$discId
	   	* @param	string		$comment_text
	   	* @param	string		$artist_dname
	   	* @param	bool		$is_image_attached (default=false)
	   	* @return	void		
	   	*/
		public function AddComment($disc_id,$artist_dname,$comment_text,$comment_photo_attachment_id=null)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Adding new comment for discId:'.$disc_id);
			
			$additional_data=null;
			if(isset($comment_photo_attachment_id))
			{
				$additional_data['comment_photo_attachment_id'] = $comment_photo_attachment_id;
			}
			
			parent::AddComment($disc_id,$artist_dname,$comment_text,$additional_data);
			$this->UpdateActivityTimeStamp($disc_id);
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		}
		
		/**
	   	* Retrieves discussion creator
	   	* 
	   	* @type	GET
	   	* 
	   	* @access	public
	   	* @param 	int		discId
	   	* @return	string 	artist_dname		
	   	*/
		public function GetCreator($disc_id)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning creator artist_dname for discussion:'.$disc_id);
			
			$this->db->select('artist_dname');
			$this->db->where('DISC_ID',$disc_id);
			
			$disc_db = $this->db->get($this->TABLE_DISCUSSION);
			$disc_db = $disc_db->row_array();
			
			if(array_key_exists('artist_dname',$disc_db))
			{
				$this->log->debug('[MODEL] Returning artist_dname:'.$disc_db['artist_dname']);
				return $disc_db['artist_dname'];
			}
			else
			{
				$this->log->error('[MODEL] No creator:artist_dname found for discId:'.$disc_id);
				return null;
			}
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		}
		
		/**
	   	* Retrieves top n active members
	   	* 
	   	* @type	GET
	   	* 
	   	* @access	public
	   	* @param 	array $filters
	   	* @return	array 			Array of artists		
	   	*/
		public function GetMostActiveArtists($disc_id,$num_results)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning '.$num_results.' most active members');
			
			$this->db->select('artist_dname,COUNT(artist_dname) as "comment_count"');
			$this->db->order_by('comment_count', 'desc');
			
			$this->db->where('DISC_ID',$disc_id);			
			$this->db->group_by('artist_dname');
			
			$artists_db = $this->db->get($this->TABLE_DISCUSSION_COMMENT,$num_results,OFFSET_NONE);
			$this->log->fine('[MODEL] Artists:'.print_r($artists_db->result_array(),true));
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $artists_db->result_array();		
		}
		
		/**
		* Returns all tags for disc:$disc_id
		*
		* @param 	int 	$disc_id 			
		* @return	array
		*/
		function GetTags($disc_id)
		{
			
		}
		
		/**
		* Adds a tag:$tag for disc:$disc_id
		*
		* @param	int		$disc_id 						
		* @param 	string 	$tag
		* @return	void
		*/
		function AddTag($disc_id,$tag)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Attempting to add tag:'.$tag.' to disc:'.$disc_id);
			
			$this->db->where('DISC_ID',$disc_id);
			$tags_db = $this->db->get_attribute($this->TABLE_DISCUSSION,'disc_tag_list');
			$this->log->debug('[MODEL] tags_db:'.$tags_db);
			
			$add_tags = false;
			
			if(!isset($tags_db) || $tags_db==null || $tags_db=='' || $tags_db==' ' || $tags_db==TAG_NONE)
			{
				$this->log->fine('[MODEL] Adding the very first tag for disc:'.$disc_id);
				
				$data_db = array
				(
					'disc_tag_list' => $tag
				);
				
				$add_tags = true;
			}
			else
			{
				$tags_array = explode(LIGHTBOX_TAG_LIST_DELIMITER,$tags_db);
				$this->log->debug('[MODEL] Current tags:'.print_r($tags_array,true));
				
				// Checking if $tags_array already contains $tag
				if(!in_array($tag,$tags_array))
				{
					$this->log->info('[MODEL] Adding tag:'.$tag);
					array_push($tags_array,$tag);
					
					$tags_list = implode(LIGHTBOX_TAG_LIST_DELIMITER,$tags_array);
					$this->log->debug('[MODEL] tag_list:'.$tags_list);
					
					$data_db = array
					(
						'disc_tag_list' => $tags_list
					);
					
					$add_tags = true;
				}
				else
				{
					$this->log->error('[MODEL] Discussion already contains tag:'.$tag);
				}
			}
			
			if($add_tags==true)
			{
				$this->log->info('[MODEL] Adding tag:'.$tag);
				
				$this->db->where('DISC_ID',$disc_id);
				$this->db->update($this->TABLE_DISCUSSION,$data_db);
			}
			else
			{
				$this->log->error('[MODEL] tag not added:'.$tag);
			}
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));		
		}
				
   		/**
		* Adds tags:$tags for disc:$disc_id
		*
		* @param 	int 		$disc_id 			
		* @return	array		$tags
		*/
		function AddTags($disc_id,$tags)
		{
			foreach($tags as $tag)
			{
				// @todo: this is inefficient
				$this->AddTag($disc_id,$tag);
			}
		}
		
		/**
		* Deletes the tag:$tag for disc:$disc_id
		*
		* @param 	int 	$disc_id						
		* @param 	string 	$tag
		* @return	void
		*/
		function DeleteTag($disc_id,$tag)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Attempting to delete tag:'.$tag.' from disc:'.$disc_id);
			
			$this->db->where('DISC_ID',$disc_id);
			$tags_db = $this->db->get_attribute($this->TABLE_DISCUSSION,'disc_tag_list');
			$this->log->debug('[MODEL] tags_db:'.$tags_db);
			
			if(isset($tags_db) && $tags_db!=null || $tags_db!='' || $tags_db!=' ' || $tags_db!=TAG_NONE)
			{
				$tags_array = explode(LIGHTBOX_TAG_LIST_DELIMITER,$tags_db);
				$this->log->debug('[MODEL] tags_array before delete:'.print_r($tags_array,true));
				
				// Checking if $tags_array contains $tag
				if(in_array($tag,$tags_array))
				{
					$this->log->info('[MODEL] Deleting tag:'.$tag);
					foreach($tags_array as $key=>$value)
					{
						if($value==$tag)
						{
							$this->log->fine('[MODEL] Removing tag from array:'.$tag);
							unset($tags_array[$key]);
						}
					}
					
					$this->log->debug('[MODEL] tags_array after delete:'.print_r($tags_array,true));
					
					if(isset($tags_array) && $tags_array!=null)
					{
						$tags_list = implode(LIGHTBOX_TAG_LIST_DELIMITER,$tags_array);
					}
					else
					{
						$tags_list = TAG_NONE;	
					}
					
					$this->log->debug('[MODEL] tag_list:'.$tags_list);
					
					$data_db = array
					(
						'disc_tag_list' => $tags_list
					);	
					
					$this->db->where('DISC_ID',$disc_id);
					$this->db->update($this->TABLE_DISCUSSION,$data_db);				
					
				}
				else
				{
					$this->log->error('[MODEL] Tag does not exist:'.$tag);
				}
			}	
			else
			{
				$this->log->error('[MODEL] tag_db is invalid');
			}
							
				
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));		
		}
		
		public function EditTitle($disc_id,$disc_title)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Updating title for disc:'.$disc_id);
			
			if(!isset($disc_title))
			{
				$this->log->error('[MODEL] Cannot edit title for artist:'.$disc_id.' REASON:$disc_title=null');
				return;
			}
			
			$disc_data = array('disc_title' => $disc_title);
			
			$this->Edit($disc_id,$disc_data);
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));	
		}
		
		public function EditBody($disc_id,$disc_body)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Updating body for disc:'.$disc_id);
			
			if(!isset($disc_body))
			{
				$this->log->error('[MODEL] Cannot edit title for artist:'.$disc_id.' REASON:$disc_body=null');
				return;
			}
			
			$disc_data = array('disc_body' => $disc_body);
			
			$this->Edit($disc_id,$disc_data);
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));	
		}
		
				
		/**-------------------------------------------------------------------------------------------------------------------*/
		
		protected function UpdateActivityTimeStamp($disc_id)
		{
			$this->log->info('[MODEL] Updating  disc_last_active_date for discId:'.$disc_id);
			
			$data_db = array
			(
				'disc_last_active_date'	=> $this->util->GetCurrentDateStamp()
			);
			
			$this->db->where('DISC_ID',$disc_id);
			$this->db->update($this->TABLE_DISCUSSION,$data_db);			
		}
		
		/**-------------------------------------------------------------------------------------------------------------------*/
		
		private function _GetDiscIdByPostId($comment_id)
		{
			$this->db->where('comment_ID',$comment_id);
			return $this->db->get_attribute($this->TABLE_DISCUSSION_COMMENT,'DISC_ID');
		}
		
		/**
	  	* @access private
	  	*/
		private $TABLE_DISCUSSION_COMMENT;
		
		/**
	  	* @access private
	  	*/
		private $TABLE_DISCUSSION;
	}	
}
 
/* End of file AbstractDiscussionEntityModel.php */


	