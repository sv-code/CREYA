<?php
/**
 * GroupDiscussionEntityModel Class
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
 *  		GroupDiscussionEntityModel::Create				($gid,$disc_title,$disc_comment,$artist_dname,$is_image_attached=false)
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
require MODEL_BASE_PATH.'Abstract.Entity.DiscussionModel'.EXT;
 
class GroupDiscussionEntityModel extends AbstractDiscussionEntityModel 
{
	/**
   	* Constructor
   	* 
   	* Instantiates the bass class 'DiscussionModel' with the names and colmns of the tables pertaining to the GroupDiscussionModel
   	* 
   	* @access	public
   	* @param 	void
   	* @return	void 			
   	*/
	public function GroupDiscussionEntityModel($module='group')
  	{
 		parent::AbstractDiscussionEntityModel($module,$table_discussion='group_discussion',$table_discussion_comment='group_discussion_comment');
	}
	
	/**
   	* Creates a new discussion
   	* 
   	* @type	PUT
   	* 
   	* @access	public
   	* @param 	int		$discId
   	* @param	string		$comment_text
   	* @return	int		
   	*/
	public function Create($gid,$artist_dname,$disc_title,$disc_body,$disc_image_attachments=null)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Creating new discussion:'.$disc_title); 
		
		$disc_data = array
		(
			'disc_title'				=> 	$disc_title,
			'disc_body'				=>	$disc_body,
			'disc_image_attachments'	=>	$disc_image_attachments
		);
		
		$this->log->debug('[MODEL] $disc_data:'.print_r($disc_data,true));
		
		$additional_field['GROUP_ID'] = $gid;
		$key = parent::Add($artist_dname,$disc_data,$additional_field);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $key;
	}
}  	
 
/* End of file GroupDiscussionEntityModel.php */


	