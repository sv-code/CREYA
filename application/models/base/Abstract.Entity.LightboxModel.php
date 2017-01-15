<?php
/**
 * AbstractLightboxEntityModel Class
 * 
 * Definition
 * 		
 * 		A <LightboxEntity> is an Entity, in which ATLEAST one of the following is true
 * 			- IS a lightbox artist 
 * 			- IS created by a lightbox artist
 * 			- BELONGS to a lightbox artist
 * 
 * Interfaces
 * 
 * 		AbstractLightboxEntityModel::Exists				($entity_id);
 * 		AbstractLightboxEntityModel::Add				($artist_dname,$entity_required_data,$entity_optional_data=null);
 * 		AbstractLightboxEntityModel::Delete				($entity_id)
 * 		AbstractLightboxEntityModel::GetDetails			($entity_id)
 * 		AbstractLightboxEntityModel::GetViewCount		($entity_id)
 * 		AbstractLightboxEntityModel::IncrementViewCount	($entity_id)
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
if(!defined('ABSTRACT_LIGHTBOX_ENTITY_MODEL'))
{
	define('ABSTRACT_LIGHTBOX_ENTITY_MODEL',DEFINED);
	
	require MODEL_BASE_PATH.'Abstract.LightboxModel'.EXT;
	
	abstract class AbstractLightboxEntityModel extends AbstractLightboxModel
	{
		/**
	   	* Constructor
	   	* 
	   	* @access	public
	   	* @return	void 			
	   	*/
		public function AbstractLightboxEntityModel($module,$table_entity,$column_entity_key,$column_view_count=null)
		{
			parent::AbstractLightboxModel($module);
			
			$this->TABLE_ENTITY			= $table_entity;
			$this->COLUMN_ENTITY_KEY		= $column_entity_key;
			
			if(isset($column_view_count))
			{
				$this->COLUMN_VIEW_COUNT = $column_view_count;
			}
			else
			{
				$this->COLUMN_VIEW_COUNT = $table_entity.'_view_count';
			}
		}
		
		/**
		* Checks if $entity_id exists
		*
		* @param 	mixed		$entity_id 		
		* @return	bool
		*/
		public function Exists($entity_id)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Checking the existance of entity:'.$entity_id);
			
			$entity_exists = false;
			
			$this->db->where($this->COLUMN_ENTITY_KEY,$entity_id);
			$entity_db = $this->db->get($this->TABLE_ENTITY,SINGLE_RESULT);
			
			if($entity_db->num_rows() > 0)
			{
				$entity_exists = true;
			}
			
			$this->log->debug('[MODEL] Entity exists:'.$entity_exists);
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $entity_exists;
		}
	
		/**
		* MUST add entity to db
		*
		* @param 	string 	$artist_dname 			The creator/owner/self
		* @param 	array 		$entity_required_data
		* @param	array		$entity_optional_data
		* @return	void
		*/
		abstract function Add($artist_dname,$entity_required_data,$entity_optional_data=null);
		
		/**
		* Unconditionally deletes the entity:$entity_id
		*
		* @param 	mixed		$entity_id 		
		* @return	void
		*/
		public function Delete($entity_id)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Deleting entity:'.$entity_id);
			
			$this->db->where($this->COLUMN_ENTITY_KEY,$entity_id);
			$this->db->delete($this->TABLE_ENTITY);
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		}
		
		/**
		* Returns details for the entity:$entity_id
		*
		* @param 	mixed		$entity_id
		* @param	string		$details		comma delimited list of attributes  		
		* @return	array				
		*/
		public function GetDetails($entity_id,$details='*')
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning details for entity:'.$entity_id);
			
			$this->db->select($details);
			$this->db->where($this->COLUMN_ENTITY_KEY,$entity_id);
			
	  		$entity_db = $this->db->get($this->TABLE_ENTITY,SINGLE_RESULT);
			
			if($entity_db->num_rows()==0)
			{
				throw new Exception('[MODEL] No such entitiy:'.$entity_id);
			}
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $entity_db->row_array();
		}
		
		/**
	     	* @type	GET
	   	* 
	   	* @access	public
	   	* @param	mixed		$entity_id 
	      	* @return	void					 			
	   	*/
		public function GetViewCount($entity_id)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Returning view count for entity:'.$entity_id);
			
			$this->db->where($this->COLUMN_ENTITY_KEY,$entity_id);
			$result = $this->db->get_attribute($this->TABLE_ENTITY,$this->COLUMN_VIEW_COUNT);
			
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
			return $result;
		}
		
		/**
	     	* Increments view count for entity:$entity_id
	     	* 
	     	* @type	PUT
	   	* 
	   	* @access	public
	   	* @param	mixed		$entity_id 
	      	* @return	void					 			
	   	*/
		public function IncrementViewCount($entity_id)
		{
			$this->benchmark->Start(__METHOD__);
			$this->log->info('[MODEL] Incrementing view count for entity:'.$entity_id);
			
			$this->db->increment($this->TABLE_ENTITY,$this->COLUMN_VIEW_COUNT,array($this->COLUMN_ENTITY_KEY=>$entity_id));
					
			$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		}
		
		/**-------------------------------------------------------------------------------------------------------------------*/
		
		/**
	     	* Edits entity data
	     	* 
	     	* @type	PUT
	   	* 
	   	* @access	protected
	   	* @param	mixed 	$entity_id
	   	* @param	array		$entity_data	
	   	* @param	bool		$allow_null_data (defaults to false)
		* @return	void					 			
	   	*/
		protected function Edit($entity_key,$entity_data,$allow_null_data=DISALLOW_NULL)
		{
			$this->log->fine('[MODEL] Editing data:'.print_r($entity_data,true).' for entity key:'.$entity_key);
			$this->log->debug('[MODEL] $allow_null_data='.$allow_null_data);
			
			if(!isset($entity_key))
			{
				throw new Exception('[MODEL] entity_key MUST be defined');
			}
			
			if(!isset($entity_data) || !is_array($entity_data))
			{
				throw new Exception('[MODEL] entity_data MUST be an associative array');
			}
			
			if($allow_null_data!=ALLOW_NULL)
			{
				foreach($entity_data as $data=>$value)
				{
					if($entity_data[$data]=='' || $entity_data[$data]==null)
					{
						$this->log->warning('[MODEL] Unsetting edit data for key:'.$data);
						unset($entity_data[$data]);
					}
				}
			}
			
			
			if(!isset($entity_data))
			{
				throw new Exception('[MODEL] Cannot edit. Reason:Invalid data or values=null');
			}
			
			$this->db->where($this->COLUMN_ENTITY_KEY,$entity_key);		
			$this->db->update($this->TABLE_ENTITY,$entity_data);
		}
		
		/**
	  	* @access protected
	  	*/
		protected $COLUMN_VIEW_COUNT;
		
		/**
	  	* @access protected
	  	*/
		protected $COLUMN_ENTITY_KEY;
		
		/**
	  	* @access protected
	  	*/
		protected $TABLE_ENTITY;
	}
}
/* End of file AbstractLightboxEntityModel.php */

