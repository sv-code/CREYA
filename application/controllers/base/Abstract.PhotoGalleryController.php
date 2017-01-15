<?php
/**
 * AbstractPhotoGalleryController Class
 * 
 * Created 
 * 		April 11, 2009
 * 
 * @package	Lightbox
 * @subpackage	Controllers
 * @category	base
 * @author		venksster
 * @link		TBD
 * @abstract
 */
if(!defined('ABSTRACT_PHOTO_GALLERY_CONTROLLER'))
{
	define('ABSTRACT_PHOTO_GALLERY_CONTROLLER',DEFINED);
	
	require CONTROLLER_BASE_PATH.'Abstract.LightboxController'.EXT;
	 
	abstract class AbstractPhotoGalleryController extends AbstractLightboxController
	{
		protected function AbstractPhotoGalleryController($module)
	  	{
	  		parent::AbstractLightboxController($module);
			$this->load->model('PhotoEntityModel');
			$this->load->model('PhotoModel');
	  	}
	 
	 	protected function AddBookmarkInfo(&$photos,$artist_dname)
		{
			foreach($photos as &$photo)
			{
				$photo['IsBookmarked'] = $this->PhotoEntityModel->IsBookmarked($photo['PHOTO_ID'],$artist_dname); 
			}
		}
		
		protected function GetPhotoFilterKeywords($filter_array)
		{
			if(isset($filter_array) && is_array($filter_array) && array_key_exists('filter_photo_keywords',$filter_array) && $filter_array['filter_photo_keywords']!='All')
			{
				return $filter_array['filter_photo_keywords'];
			}
			
			return FILTER_NONE;
		}
		
		private function GetPhotoPropertyRange($filter,$value)
		{
			$this->log->debug('[CONTROLLER] '.$filter.': '.$value);
			
			$newvalue = $value;
			if(strpos($value,'-'))
			{
				$this->log->fine('[CONTROLLER] '.$filter.' :MID_RANGE');
				$values = explode('-',$value);
				$newvalue = array('min'=> $values[0]-1,'max'=> $values[1]+1);
			}
			elseif($value[0]=='>')
			{
				$this->log->fine('[CONTROLLER] '.$filter.' :HIGH_RANGE');
				$values = explode('> ',$value);
				$newvalue = array('min'=> $values[1],'max'=> 65535); // @todo make 65535 a CONST
			}
			elseif($value[0]=='<')
			{
				$this->log->fine('[CONTROLLER] '.$filter.' :LOW_RANGE');
				$values = explode('<=',$value);
				$newvalue = array('min'=> -1,'max'=> $values[1] + 1); 
			}
						
			return $newvalue;
		} 
		
		protected function GetPhotoPropertyArray($filter_array)
		{
			$this->log->fine('[CONTROLLER] Pre-processed photo filters:'.print_r($filter_array,true));
			
			if(isset($filter_array) && is_array($filter_array))
			{
				foreach($filter_array as $filter=>$value)
				{
					if($value=='ALL' || $value=='All' || !($filter=='photo_type' || $filter=='photo_exif_focal' || $filter=='photo_exif_aperture' || $filter=='photo_exif_shutter' || $filter=='photo_exif_iso'))
					{
						$this->log->warning('[CONTROLLER] Unsetting filter:: '.$filter.' from filter_array');
						unset($filter_array[$filter]);
					}
					else
					{
						/* SPECIAL CASES
						1. APERTURE needs to be stripped of prefix 'f/'
						*/
						if($filter=='photo_exif_aperture')
						{
							$this->log->debug('[CONTROLLER] photo_exif_aperture:'.$value);
							$value = substr($value,2);
							$filter_array['photo_exif_aperture'] = $value;
						}
						
						/*
						2. SHUTTER SPEED needs to be stripped of suffix 's'
						*/
						if($filter=='photo_exif_shutter')
						{
							$this->log->debug('[CONTROLLER] photo_exif_shutter:'.$value);
							$value = rtrim($value,'s');
							$filter_array['photo_exif_shutter'] = $value;
						}
						
						/*
						3. FOCAL LENGTH needs to be stripped of suffix 'mm' AND the range needs to be split into an array
						*/
						if($filter=='photo_exif_focal')
						{
							$filter_array[$filter] = $this->GetPhotoPropertyRange($filter,rtrim($value,'mm'));
						}
						
						if( $filter=='photo_exif_iso' )
						{
							$filter_array[$filter] = $this->GetPhotoPropertyRange($filter,$value);
						}
						
						/*
						4. PHOTO TYPE needs to be mapped
						*/
						if($filter=='photo_type')
						{
							$this->log->debug('[CONTROLLER] photo_type:'.$value);
							if($value=='genre')
							{
								$this->log->warning('[CONTROLLER] Unsetting filter:: '.$filter.' from filter_array');
								unset($filter_array[$filter]);	
							}
							else
							{
								$value = $this->GetPhotoType($value);
								$filter_array['photo_type'] = $value;
							}
						}
					}
				}
				
				if(count($filter_array)==0)
				{
					return FILTER_NONE;
				}
								
				$this->log->info('[CONTROLLER] filter_array:'.print_r($filter_array,true));
				return $filter_array;
			}
			
			return FILTER_NONE;
		}
	 
	 	protected function GetPhotoType($str_photo_type)
	 	{
	 		return $this->PhotoModel->GetPhotoType($str_photo_type);
	 	}
	 }
	 
	
}
 
/* End of file AbstractPhotoGalleryController.php */


	