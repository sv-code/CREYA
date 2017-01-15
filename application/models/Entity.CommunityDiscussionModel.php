<?php
/**
 * CommunityDiscussionEntityModel Class
 * 
 * Interfaces
 * 
 * 		AbstractLightboxEntityModel::Add				($artist_dname,$photo_name,$photo_type,$photo_data=null)
 * 		AbstractLightboxEntityModel::Delete				($pid)
 * 		AbstractLightboxEntityModel::GetDetails			($pid)
 * 
 *  		ILightboxCommentable::GetCommentsCount		($pid)	
 *  		ILightboxCommentable::GetComments			($pid)	
 * 		ILightboxCommentable::AddComment			($pid,$artist_dname,$comment_text,$sid=null)
 * 		ILightboxCommentable::DeleteComment			($comment_id)
 * 
 * 		AbstractDiscussionEntityModel::GetCreator		($discId)
 * 		AbstractDiscussionEntityModel::GetMostActiveArtists	($num_results,$filters=null)
 * 	
 *  		CommunityDiscussionEntityModel::Create			($artist_dname,$disc_title,$disc_comment,$disc_image_attachments=null,$section_name='default')
 *  		CommunityDiscussionEntityModel::GetSection			($DISC_ID)
 * 
 * Created 
 * 		Feb 28, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @category	none
 * @author		venksster
 * @link		TBD
 */
if(!defined('COMMUNITY_DISCUSSION_ENTITY_MODEL'))
{
	define('COMMUNITY_DISCUSSION_ENTITY_MODEL',DEFINED);
	
	require MODEL_BASE_PATH.'Abstract.Entity.DiscussionModel'.EXT;
	 
	class CommunityDiscussionEntityModel extends AbstractDiscussionEntityModel
	{
		/**
	   	* Constructor
	   	* 
	   	* Instantiates the bass class 'DiscussionModel' with the names and colmns of the tables pertaining to the CommunityDiscussionModel
	   	* 
	   	* @access	public
	   	* @param 	void
	   	* @return	void 			
	   	*/
		public function CommunityDiscussionEntityModel($module='community',$table_discussion_comment='community_discussion_comment')
	  	{
	 		parent::AbstractDiscussionEntityModel($module,$table_discussion='community_discussion',$table_discussion_comment);
		}
		
		/**
		* Returns category for disc:$DISC_ID
		*
		* @type	GET
		* 
		* @access 	public
		* @return 	string		category name
		*/
		public function GetSection($DISC_ID)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning section for disc:'.$DISC_ID);
			
			$this->db->where($this->COLUMN_ENTITY_KEY,$DISC_ID);
			$this->db->join('static_discussion_section','static_discussion_section.SECTION_ID = community_discussion.SECTION_ID');
			$section_name = $this->db->get_attribute('static_discussion_section','section_name');
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $section_name;
		}
		
		/**
	   	* Creates a new discussion
	   	* 
	   	* @type	PUT
	   	* 
	   	* @access	public
	   	* @param 	string		$artist_dname
	   	* @param	string		$disc_title
	   	* @param	string		$disc_body
	   	* @param	string		$disc_image_attachments	list of image attachment PIDs
	   	* @param	string		$section_name			DEFAULT:'default'
	   	* @return	int		$key			
	   	*/
		public function Create($artist_dname,$disc_title,$disc_body,$disc_image_attachments=null,$section_name='default')
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Creating new discussion:'.$disc_title.'::section:'.$section_name);
			
			$additional_field['SECTION_ID'] = $this->GetSectionIdByName($section_name); 	
			$this->log->debug('[MODEL] $additional_fields:'.print_r($additional_field,true));
			
			if($disc_image_attachments!=null)
			{
				$disc_image_attachments = trim($disc_image_attachments,LIGHTBOX_IMAGE_ATTACHMENT_LIST_DELIMITER.WHITESPACE);
			}
			
			$disc_data = array
			(
				'disc_title'	=> 	$disc_title,
				'disc_body'	=>	$disc_body,
				'disc_image_attachments' => $disc_image_attachments
			);
			
			$this->log->debug('[MODEL] $disc_data:'.print_r($disc_data,true));
					
			$key = parent::Add($artist_dname,$disc_data,$additional_field);
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $key;
		}
		
		/**-------------------------------------------------------------------------------------------------------------------*/
		
		/**
		* Returns SECTION_ID by section_name
		*
		* @type	GET
		* 
		* @access 	protected
		* @return 	string		section_name
		*/
		protected function GetSectionIdByName($section_name)
		{
			$this->log->info('[MODEL] Returning section id for section name::'.$section_name);
			
			$this->db->where('section_name',$section_name);
			$section_id = $this->db->get_attribute('static_discussion_section','SECTION_ID');
			$this->log->debug('[MODEL] section_id:'.$section_id);
			
			return $section_id;
		}
	}  	
}
	 
/* End of file CommunityDiscussionEntityModel.php */

	
		