<?php
/**
 * ShoeboxEntityModel Class
 * 
 * Interfaces
 * 
 * 		AbstractLightboxEntityModel::Add				($artist_dname,$entity_required_data,$entity_optional_data=null);
 * 		AbstractLightboxEntityModel::Delete			($shoebox_id)
 * 		AbstractLightboxEntityModel::GetDetails			($shoebox_id)
 * 		AbstractLightboxEntityModel::GetViewCount		($shoebox_id)
 * 		AbstractLightboxEntityModel::IncrementViewCount	($shoebox_id)
 * 
 * 		ILightboxTagable::GetTags					($shoebox_id)
 * 		ILightboxTagable::AddTag					($shoebox_id,$tag)
 * 		ILightboxTagable::AddTags					($shoebox_id,$tags)
 * 		ILightboxTagable::DeleteTag					($shoebox_id,$tag)
 * 
 * 		ShoeboxEntityModel::EditName				($shoebox_id,$shoebox_name)
 *
 * 
 * Created 
 * 		March 28, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @category	none
 * @author		venksster
 * @link		TBD
 */
require MODEL_BASE_PATH.'Abstract.Entity.LightboxModel'.EXT;
require MODEL_INTERFACE_PATH.'ILightboxTagable'.EXT;

class ShoeboxEntityModel extends AbstractLightboxEntityModel implements ILightboxTagable
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function ShoeboxEntityModel($module='shoebox')
  	{
 		parent::AbstractLightboxEntityModel($module,$table_entity='artist_shoebox',$column_entity_key='shoebox_ID',$column_view_count='shoebox_view_count');
		
		$this->SHOEBOX_FILTERS = array
		(
			'photo_tags',
			'photo_type',
			'photo_exif_focal',
			'photo_exif_aperture',
			'photo_exif_shutter',
			'photo_exif_iso'
		);
	}
    		
	public function Add($artist_dname,$shoebox_data,$shoebox_optional_data=null)
	{
		$this->benchmark->Start(__METHOD__);
		
		if(!isset($shoebox_data) || !is_array($shoebox_data) || !array_key_exists('shoebox_name',$shoebox_data) || !array_key_exists('shoebox_filters',$shoebox_data))
		{
			$this->log->error('[MODEL] Cannot Add Shoebox: Reason:shoebox_data MUST have contain shoebox_name AND shoebox_filters');
			throw new Exception('[MODEL] Cannot Add Shoebox: Reason:shoebox_data MUST have contain shoebox_name AND shoebox_filters');
		}
		
		$shoebox_name = $shoebox_data['shoebox_name'];
		$shoebox_filters = $shoebox_data['shoebox_filters'];
		
		// verifying that $shoebox_filters contains atleast one key
		if(!isset($shoebox_filters) || !is_array($shoebox_filters) || !sizeof($shoebox_filters)>=1)
		{
			$this->log->error('[MODEL] Cannot Add Shoebox: Reason:shoebox_filters MUST have atleast one filter');
			throw new Exception('[MODEL] Cannot Add Shoebox: Reason:shoebox_filters empty');
		}
		
		// verifying that $shoebox_filters contains only valid keys
		foreach($shoebox_filters as $key=>$value)
		{
			if(!in_array($key,$this->SHOEBOX_FILTERS))
			{
				$this->log->error('[MODEL] Cannot Add Shoebox: Reason:shoebox_filters contains atleast one invalid key:'.print_r($shoebox_filters,true));
				throw new Exception('[MODEL] Cannot Add Shoebox: Reason:shoebox_filters contains atleast one invalid key');
			}
		}
		
				
		$data_db = array
		(
			'shoebox_name'		=> 	$shoebox_name,
			'artist_dname'		=>	$artist_dname,
			'shoebox_date'		=>	$this->util->GetCurrentDateStamp()
		);
		
		// FILTER - tags
		if(array_key_exists('photo_tags',$shoebox_filters))
		{
			$photo_tags = $shoebox_filters['photo_tags'];
			if(!is_array($photo_tags))
			{
				throw new Exception('[MODEL] Cannot Add Shoebox: Reason:photo_tags MUST be an array');
			}
			
			// convert the tag array to a string delimited by SHOEBO_TAGS_DELIMITER
			$photo_tags = implode(LIGHTBOX_TAG_LIST_DELIMITER,$photo_tags);
			
			$data_db['photo_tag_list'] = $photo_tags;
			unset($shoebox_filters['photo_tags']);
		}
		
		if(is_array($shoebox_filters))
		{
			foreach($shoebox_filters as $filter=>$value)
			{
				$data_db[$filter] = $value;
			}
		}		
		
		$this->log->debug('[MODEL] Shoebox data:'.print_r($shoebox_data,true));
		
		$this->log->info('[MODEL] Creating new shoebox:'.$shoebox_name);
		//$this->db->insert($this->TABLE_ENTITY,$data_db);
		$this->db->insert('artist_shoebox',$data_db);
		$key = mysql_insert_id();
		$this->log->debug('[MODEL] KEY:'.$key);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $key;		
	}
	
	/**
   	* @type	GET
   	* 
   	* @access	public
   	* @param	int 		$shoebox_id		 
   	* @return	array					array of tags 	
   	*/
	public function GetTags($shoebox_id)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning tags for shoebox:'.$shoebox_id);

		$this->db->where($this->COLUMN_ENTITY_KEY,$shoebox_id);
		$tags_list = $this->db->get_attribute('artist_shoebox','shoebox_tag_list');
		
		$tags_array = explode(LIGHTBOX_TAG_LIST_DELIMITER,$tags_list);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $tags_array;
	}
	
	/**
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	int		$shoebox_id		 
   	* @param	string		$tag
   	* @return	void				
   	* @todo					WHY CAN'T THIS BE IMPLEMENTED? 	 			
   	*/
	public function AddTag($shoebox_id,$tag)
	{
		throw new Exception('[MODEL] TAGS CANNOT BE ADDED TO SHOEBOX AT ANY TIME OTHER THAN AT SHOEBOX CREATION');
	}
	
	/**
     	* @type	PUT
   	* 
   	* @access	public
   	* @param	int		$shoebox_id		 
   	* @param	arrays	$tags
   	* @todo					WHY CAN'T THIS BE IMPLEMENTED? 	 			
   	*/
	public function AddTags($shoebox_id,$tags)
	{
		throw new Exception('[MODEL] TAGS CANNOT BE ADDED TO SHOEBOX AT ANY TIME OTHER THAN AT SHOEBOX CREATION');
	}
	
	public function DeleteTag($shoebox_id,$tag)
	{
		// @todo
	}
	
	public function EditName($shoebox_id,$shoebox_name)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Updating name for shoebox:'.$shoebox_id);
		
		if(!isset($shoebox_name))
		{
			$this->log->error('[MODEL] Cannot edit name for shoebox:'.$shoebox_id.' REASON:$shoebox_id=null');
			return;
		}
		
		$shoebox_data = array('shoebox_name' => $shoebox_name);
		
		$this->Edit($shoebox_id,$shoebox_data);
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**-------------------------------------------------------------------------------------------------------------------*/
	
	private $SHOEBOX_FILTERS;
	
}	
 
/* End of file ShoeboxEntityModel.php */


	