<?php
/**
 * Static ArtistModel Class
 * 
 * Interfaces
 * 
 * 		ArtistModel::GetAllCategories	()
 * 
 * Created 
 * 		August 8, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @category	none
 * @author		venksster
 * @link		TBD
 */
require MODEL_BASE_PATH.'Abstract.LightboxModel'.EXT;
 
class ArtistModel extends AbstractLightboxModel
{
	public function ArtistModel($module='artist')
	{
 		parent::AbstractLightboxModel($module);
	}
	
	/**
	* Returns all focii 
	*
	* @type	GET
	* 
	* @access 	public
	* @return 	array 		array of focus
	*/
	public function GetFocii()
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning Photo Types');
		
		$this->db->select('artist_focus_name');
		$artist_focii = $this->db->get('static_artist_focus');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $artist_focii->result_array();
	}
	
	
	/**-------------------------------------------------------------------------------------------------------------------*/
	
}

/* End of file StatsModel.php */

