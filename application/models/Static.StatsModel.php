<?php
/**
 * StatsModel Class
 * 
 * Interfaces
 * 
 * 		StatsModel::GetMIDs		()
 * 		StatsModel::GetSIDs		()
 * 		StatsModel::GetSIDsByMID	($mid)
 * 
 * 		StatsModel::AddMID		($metric_name)
 * 		StatsModel::RemoveMID	($mid)
 * 
 * 		StatsModel::AddSID		($suggestion,$MID)
 * 		StatsModel::RemoveSID		($sid)
 * 
 * Created 
 * 		Jan 4, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @category	none
 * @author		venksster
 * @link		TBD
 */
require MODEL_BASE_PATH.'Abstract.LightboxModel'.EXT;
 
class StatsModel extends AbstractLightboxModel
{
	public function StatsModel($module='stats')
	{
 		parent::AbstractLightboxModel($module);
	}
	
	/**
   	* @type	GET
   	* 
   	* Returns all MIDs 
   	* 
   	* @access	public
   	* @return	array 			
   	*/
	public function GetMIDs()
	{
		return $this->UtilModel->GetMIDs();
	}
	
	/**
   	* @type	GET
   	* 
   	* Returns all SIDs 
   	* 
   	* @access	public
   	* @return	array 			
   	*/
	public function GetSIDs()
	{
		return $this->UtilModel->GetSIDs();
	}
	
	/**
   	* @type	GET
   	* 
   	* Returns all SIDs by MID 
   	* 
   	* @access	public
   	* @param 	int		$mid
   	* @return	array 			
   	*/
	public function GetSIDsByMID($mid)
	{
		return $this->UtilModel->GetSIDsByMID($mid);
	}
	
	/**
   	* @type	PUT
   	* 
   	* Creates a new MID
   	* 
   	* @access	public
   	* @param 	string		$metric_name
   	* @return	void 			
   	*/
	public function AddMID($metric_name)
	{
		// @todo
	}
	
	/**
   	* @type	PUT
   	* 
   	* Removes an MID
   	* 
   	* @access	public
   	* @param 	int		$mid
   	* @return	void 			
   	*/
	public function RemoveMID($mid)
	{
		// @todo
	}
	
	/**
   	* @type	PUT
   	* 
   	* Creates a new SID
   	* 
   	* @access	public
   	* @param 	string		$suggestion
   	* @param	int		$MID
   	* @return	void 			
   	*/
	public function AddSID($suggestion,$MID)
	{
		// @todo
	}
	
	/**
   	* @type	PUT
   	* 
   	* Removes an SID
   	* 
   	* @access	public
   	* @param 	int		$sid
   	* @return	void 			
   	*/
	public function RemoveSID($sid)
	{
		// @todo
	}
	
	/**-------------------------------------------------------------------------------------------------------------------*/
	
}

/* End of file StatsModel.php */

