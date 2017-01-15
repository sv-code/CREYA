<?php
/**
 * CommunityModel Class
 * 
 * Interfaces
 * 
 * 		CommunityModel::GetCategories	()
 * 		CommunityModel::AddCategory	($category_name)
 * 
 * Created 
 * 		April 1, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @category	none
 * @author		venksster
 * @link		TBD
 */
require MODEL_BASE_PATH.'Abstract.LightboxModel'.EXT;
 
class CommunityModel extends AbstractLightboxModel
{
	public function CommunityModel($module='community')
	{
 		parent::AbstractLightboxModel($module);
	}
	
	/**
	* Returns all categories for lightbox-community-discussions
	*
	* @type	GET
	* 
	* @access 	public
	* @return 	array 		array of category data
	*/
	public function GetAllSections()
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning Sections');
		
		$this->db->select('*');
		$section_db = $this->db->get('static_discussion_section');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $cat_db->result_array();
	}
	
	/**
   	* @type	PUT
   	* 
   	* Adds a new category
   	* 
   	* @access	public
   	* @param 	string		$category_name
   	* @return	void 			
   	*/
	public function AddCategory($category_name,$category_description)
	{
		$this->benchmark->Start(__METHOD__);
		
		$data_db = array
		(
			'cat_name'	=> 	$category_name,
			'cat_desc'	=> 	$category_description
		);		
		
		$this->db->insert('community_category',$data_db);		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	public function GetSectionId($section_name)
	{
		
	}
	
	/**-------------------------------------------------------------------------------------------------------------------*/
	
}

/* End of file StatsModel.php */

