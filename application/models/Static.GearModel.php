<?php
/**
 * GearModel Class
 * 
 * Interfaces
 * 
 * 		GearModel::GetTypes			()
 * 		GearModel::GetManufacturers		($gear_type=RESULTS_ALL)
 * 		GearModel::GetNames			($gear_type=RESULTS_ALL,$gear_man=RESULTS_ALL)
 * 
 * 		GearModel::Add				($gear_type,$gear_man,$gear_name)
 * 		GearModel::Delete			($gear_id)
 * 
 * Created 
 * 		Feb 7, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @author		prakash
 * @author		venksster
 * @category	none
 * @link		TBD
 */	
require MODEL_BASE_PATH.'Abstract.LightboxModel'.EXT;

class GearModel extends AbstractLightboxModel
{
	public function GearModel($module='gear')
	{
		parent::AbstractLightboxModel($module);
	}
	
	/**
	* Returns the master gear typecodes
	*
	* @type	GET
	* 
   	* @access	public
   	* @param 	void
   	* @return   array 
   	*/
	public function GetTypes()
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning gear types');
		
		$this->db->select('gear_type');
		$this->db->distinct();

		$gear_db = $this->db->get('static_gear');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $gear_db->result_array();
	}

	/**
	* Returns the gear manufacturer list
	*
	* @access	GET
	* 
   	* @access	public
   	* @param 	int		$type
   	* @return 	array
   	*/
	public function GetManufacturers($gear_type=RESULTS_ALL)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning gear manufacturers');
		
		$this->db->select('gear_man');
		$this->db->distinct();
		
		if($gear_type!=RESULTS_ALL)
		{
			$this->db->where('gear_type',$gear_type);
		}
		
		$gear_db = $this->db->get('static_gear');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $gear_db->result_array();
	}

	/**
	* Returns the gear names
	*
   	* @access	public
   	* @param 	int		$type
   	* @param	string		$gear_man
   	* @return   array
   	*/
	public function GetNames($gear_type=RESULTS_ALL,$gear_man=RESULTS_ALL)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning gear names');
		
		$gear_where = array();
		
		if($gear_type!=RESULTS_ALL)
		{
			$gear_where['gear_type'] = $gear_type;
		}
		
		if($gear_man!=RESULTS_ALL)
		{
			$gear_where['gear_man'] = $gear_man;
		}
		
		$this->db->select('gear_name');
		$this->db->where($gear_where);
		
		$gear_db = $this->db->get('static_gear');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $gear_db->result_array();
	}
	
	/**
   	* @type	PUT
   	* 
   	* Adds a new gear
   	* 
   	* @access	public
   	* @param 	int		$gear_type
   	* @param	string		$gear_man
   	* @param	string		$gear_name
   	* @return	void 			
   	*/
	public function Add($gear_type,$gear_man,$gear_name)
	{
		// @todo
	}
	
	/**
	* Unconditionally deletes the gear:$gear_id
	*
	* @param 	int	$gear_id 		
	* @return	void
	*/
	public function Delete($gear_id)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Deleting gear:'.$gear_id);
		
		$this->db->where('GEAR_ID',$gear_id);
		$this->db->delete('static_gear');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
}

/* End of file GearModel.php */
