<?php
/**
 * Static PhotoModel Class
 * 
 * Interfaces
 * 
 * 		PhotoModel::GetAllCategories	()
 * 
 * Created 
 * 		July 8, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @category	none
 * @author		prakash
 * @link		TBD
 */
require MODEL_BASE_PATH.'Abstract.LightboxModel'.EXT;
 
class PhotoModel extends AbstractLightboxModel
{
	public function PhotoModel($module='photo')
	{
 		parent::AbstractLightboxModel($module);
	}
	
	/**
	* Returns all categories of photos
	*
	* @type	GET
	* 
	* @access 	public
	* @return 	array 		array of category data
	*/
	public function GetPhotoTypes()
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[STATIC.PHOTOMODEL] Returning Photo Types');
		
		$this->db->select('photo_type_name');
		$photo_type = $this->db->get('static_photo_type');
		
		$this->log->benchmark('[STATIC.PHOTOMODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $photo_type->result_array();
	}
	
	/**
	*  @type	GET
	* 
	* @access 	public
	* @param	string		photo_type_name
	* @return 	int 		photo_type
	*/
	public function GetPhotoType($photo_type_name)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[STATIC.PHOTOMODEL] Returning photo_type for photo_type_name:'.$photo_type_name);
		
		$this->db->where('photo_type_name',$photo_type_name);
		$photo_type = $this->db->get_attribute('static_photo_type','photo_type');
		$this->log->debug('[MODEL] photo_type:'.$photo_type);
		
		$this->log->benchmark('[STATIC.PHOTOMODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $photo_type;
	}
	
	/**
	*  @type	GET
	* 
	* @access 	public
	* @param	string		photo_type_name
	* @return 	int 		photo_type
	*/
	public function GetPhotoTypeName($photo_type)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[STATIC.PHOTOMODEL] Returning photo_type_name for photo_type:'.$photo_type);
		
		$this->db->where('photo_type',$photo_type);
		$photo_type_name = $this->db->get_attribute('static_photo_type','photo_type_name');
		$this->log->debug('[MODEL] photo_type_name:'.$photo_type_name);
		
		$this->log->benchmark('[STATIC.PHOTOMODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $photo_type_name;
	}
	
	public function GetCreativeCommonsLicenses()
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[STATIC.PHOTOMODEL] Returning Creative Commons Licenses');
		
		$this->db->select('*');
		$licenses = $this->db->get('static_creative_commons_license');
		
		$this->log->benchmark('[STATIC.PHOTOMODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $licenses->result_array();
	}
	
	
	/**-------------------------------------------------------------------------------------------------------------------*/
	
}

/* End of file StatsModel.php */

