<?php
/**
 * ShoeboxCollectionModel Class
 *
 * Interfaces
 * 
 *		AbstractLightboxCollectionModel::GetEntityCount	($filter_name=FILTER_NONE)
 * 		AbstractLightboxCollectionModel::GetEntities		($order_by=ORDER_DATE,$filter_name=FILTER_NONE,$num_entities=RESULTS_ALL,$offset=OFFSET_NONE)
 * 
 * 		ILightboxExplore::GetMostRecent				($num_shoeboxes,$offset,$filter_name_keywords=null)
 * 		ILightboxExplore::GetMostRelevant				($num_shoeboxes,$offset,$filter_name_keywords)
 * 
 * Created
 * 		Feb 08, 2009
 *
 * @package 	Lightbox
 * @subpackage 	Models
 * @category 	none
 * @author 	venksster 
 */

require MODEL_BASE_PATH.'Abstract.Collection.LightboxModel'.EXT;

class ShoeboxCollectionModel extends AbstractLightboxCollectionModel
{
	/**
	* Constructor
	*
	* @access public
	*/
	public function ShoeboxCollectionModel($module='shoebox')
	{
		parent::AbstractLightboxCollectionModel($module,$table_entity='artist_shoebox',$column_entity_name='shoebox_name',$column_entity_key='SHOEBOX_ID',$column_entity_date='shoebox_date');
	}
	
	/**-------------------------------------------------------------------------------------------------------------------*/

}

/* End of file ShoeboxCollectionModel.php */