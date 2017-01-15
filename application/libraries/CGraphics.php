<?php
/**
 * CGraphics Class
 * 
 * Purpose
 * 		Image manipulations for Lightbox
 * Created 
 * 		Dec 16, 2008
 * 
 * @package		Lightbox
 * @subpackage	Libraries
 * @category	Graphics
 * @author	venksster
 * @modified	prakash
 * @link	TBD
 */

define('X',0);
define('Y',1);

define('EXIF_KEY',0);
define('EXIF_VALUE',1);

require CI_LIBPATH.'Image_lib'.EXT;

class CGraphics extends CI_Image_lib
{

	/**
	* Constructor
  	* 
	* @access	public
	* @param	None
  	*/
	public function __construct()
	{
		parent::CI_Image_lib();
		$this->log = clogger_return_instance('cgraphics');
	}
	
	private function _ReturnClosestExposureMatch($exif_code,$approx_exif_value,$exif_conversion_array)
	{
		switch($exif_code)
		{
			case EXIF_EXPOSURETIME_CODE:
			
				$exposure_numeric_handle = $exif_conversion_array[EXPOSURE_MAP][NUMERIC_EXPOSURE_VALUES];
				$exposure_string_handle = $exif_conversion_array[EXPOSURE_MAP][STRING_EXPOSURE_VALUES];
				for($i=0;$i<count($exposure_numeric_handle);$i++)
				{
					$possible_match = $exposure_numeric_handle[$i];
					if( $possible_match <= $approx_exif_value )
					{
						return ($exposure_string_handle[$i]);
					}
				}
				$this->log->debug('[CGRAPHICS]Did not find exif value matching '.$approx_exif_value.' for '.$exif_code);
			break;
		}
		return $approx_exif_value;
	}

	/**
	* Parses the EXIF from the master image
	* 
	* @access	public
	* @param $image_path - the complete path of the image file that needs its exif read
	* @return exif_array - array having exif parameters
  	*/
	public function GetExif($imagepath,$exif_conversion_array,$required_exif_parameters)
	{
		$this->log->info('[CGRAPHICS] Parsing EXIF for file :'.$imagepath);

		//create the command to run using shell_exec
		$exepath = IMG_LIB_PATH.'/'.'identify';
		$cmd = $exepath.' -format %[exif:*] '.$imagepath;
		$exif_string = shell_exec ($cmd);

		//split the exif string on \n to get to each parameter
		$parsed_exif_string = explode("\n",$exif_string);
		
		//For each exif parameter string
		//split on '=' and get the key-value
		foreach($parsed_exif_string as $exif_parameter)
		{
			$parsed_exif_parameter = explode('=',$exif_parameter);
			//$this->log->debug( " Parsed exif parameter is : ".print_r($parsed_exif_parameter,true));
			if( !empty($parsed_exif_parameter) && 
				array_key_exists(EXIF_KEY,$parsed_exif_parameter) )
			{
				if( array_key_exists(EXIF_VALUE,$parsed_exif_parameter) )
				{
					$EXIF_ARRAY[$parsed_exif_parameter[EXIF_KEY]] = $parsed_exif_parameter[EXIF_VALUE];
				}
				else
				{
					$EXIF_ARRAY[$parsed_exif_parameter[EXIF_KEY]] = '';					
				}
			} 
		}
		
		if( !empty($EXIF_ARRAY) )
		{
			$exif_return= array();
			//iterate over the required exif parameters and index into the EXIF_ARRAY
			//to get to the proper exif values
			foreach($required_exif_parameters as $required_exif_code=>$required_exif_param)
			{
				$this->log->debug('[CGRAPHICS] Looking for exif code -  '.$required_exif_code.' , parameter  - '.$required_exif_param['dbstring']);
				switch(	$required_exif_code )
				{
					//special treatment for aperture
					//special treatment for focal length					
					case EXIF_FNUMBER_CODE:
					case EXIF_FOCALLENGTH_CODE:
					case EXIF_EXPOSURETIME_CODE:
						if( array_key_exists($required_exif_param['matchstring'],$EXIF_ARRAY) )
						{
							//Split by "/"
							list($numerator,$denominator) = split('/',$EXIF_ARRAY[$required_exif_param['matchstring']]);
							if( $numerator != '' && $denominator != '')
							{
								if( $required_exif_code == EXIF_EXPOSURETIME_CODE )
								{
									//check if the values conform to the standard exposure values
									$exif_return[$required_exif_param['dbstring']] = $this->_ReturnClosestExposureMatch(
																								EXIF_EXPOSURETIME_CODE,
																								$numerator/$denominator,
																								$exif_conversion_array);
								}
								else
								{
									$exif_return[$required_exif_param['dbstring']] = $numerator/$denominator;
								}
								$this->log->debug('[CGRAPHICS] Code '.$required_exif_code.' ,numerator: '.$numerator.',denominator: '.$denominator );
							}
							else
							if( $numerator != '' )
							{
								$exif_return[$required_exif_param['dbstring']] = $numerator;
							}
							else
							{
								$this->log->debug(" Missing numerator or denominator for parameter ".
												$required_exif_param['matchstring']."for image ".$imagepath);
							}
						}
						else
						{
							$this->log->debug("Missing ".$required_exif_param['matchstring']." for ".$imagepath);
						}						
						break;
						
					//for all other exif codes
					default:
						//$this->log->debug(" Matching string is ".$required_exif_param['matchstring']);
						if( array_key_exists($required_exif_param['matchstring'],$EXIF_ARRAY) )
						{
							if( !empty($EXIF_ARRAY[$required_exif_param['matchstring']] ) )
							{
								$exif_return[$required_exif_param['dbstring']] =
									$EXIF_ARRAY[$required_exif_param['matchstring']]; 
							}			
						}
						else
						{
							$this->log->debug("Missing ".$required_exif_param['matchstring']." for ".$imagepath);			
						}
						break;
				}

			}
			$this->log->debug( " Command is : ".$cmd);
			//$this->log->debug( " Shell-exec output is: ".print_r($EXIF_ARRAY,true));
			//$this->log->debug('[CGRAPHICS] Printing EXIF parameters ');
			
			foreach ($EXIF_ARRAY as $name => $val) 
			{
		   	    //$this->log->debug("$name = $val\n");
			}
			return $exif_return;			
		}
		else
		{
			$this->log->error("Unable to parse image file for exif ".$imagepath);
			return '';
		}
	}

	/**
	* Initialize image-library array with parameters depending on image and operation type 
  	* 
  	* @access	private
	* @param	$image_type - type of image
  	* @param	$operation_type - type of operation ( resize or crop )
  	* @param	$image_config - the config array having image dimensions for various types
  	* @return 	$LIBCONFIG - array having all the library config parameters for the corresponding operation
  	*/
	private function _ReturnConfig($image_config,$operation_type,$resize_type=null)
	{
		$this->log->fine('[CGRAPHICS] Initializing configuration for image:'.$image_config['source_image_name'].' operation type '.$operation_type);
		
		// Image library parameters
		$LIBCONFIG['image_library'] = 'imagemagick';
		$LIBCONFIG['library_path'] = IMG_LIB_PATH;
		$this->log->debug('Image library path:'.$LIBCONFIG['library_path']);
		
		// Maintaining 100% image quality
		$LIBCONFIG['quality'] = '100%';
		
		switch($operation_type)
		{
			case OPERATION_RESIZE:
				
				$LIBCONFIG['source_image'] 	= $image_config['source_image_path'].'/'.$image_config['source_image_name'];
				$LIBCONFIG['new_image'] 	= $image_config['target_image_path'].'/'.$image_config['target_image_name'];			
	
				// Retrieving image dimentions
				$image_dimensions = $this->GetImageSize($LIBCONFIG['source_image']);
				
				// Retrieving target image dimensions
				$resize_parameter_array = $this->_ReturnResizeParameters($image_dimensions,$image_config['target_image_x'],$image_config['target_image_y'],$resize_type);
	
				foreach($resize_parameter_array as $name => $val)
				{
					$LIBCONFIG[$name] = $val;
				}
				
				// Maintaining aspect ratio for all resizings
				$LIBCONFIG['maintain_ratio'] = TRUE;
				
				return $LIBCONFIG;
				
			case OPERATION_CROP:

				$LIBCONFIG['source_image'] = $image_config['target_image_path'].'/'.$image_config['target_image_name'];
	
				// Retrieving image dimensions
				$image_dimensions = $this->GetImageSize($LIBCONFIG['source_image']);
	
				$this->log->debug('[CGRAPHICS][OPERATION_CROP] source image - '.$LIBCONFIG['source_image']);
				$this->log->debug('[CGRAPHICS][OPERATION_CROP] resizex - '.$image_dimensions[X].',resizey - '.$image_dimensions[Y]);
				$this->log->debug('[CGRAPHICS][OPERATION_CROP] maxcropx - '.$image_config['target_image_x'].',maxcropy - '.$image_config['target_image_y']);
	
				// Determing the crop parameters for the resized image
				$crop_parameters = $this->_ReturnCropParameters($image_dimensions,$image_config['target_image_x'],$image_config['target_image_y']);
	
				foreach ($crop_parameters as $name => $val) 
				{
					$LIBCONFIG[$name] = $val;
				}
	
				// NOT maintaining aspect ratio for crop
				$LIBCONFIG['maintain_ratio'] = FALSE;
				
				return $LIBCONFIG;
		}
		
		
		return $LIBCONFIG;
	}

	/**
 	* Creates the image stack (PHOTO_ZOOM,PHOTO_STANDARD,PHOTO_SNAPSHOT,PHOTO_PREVIEW,PHOTO_THUMBNAIL)
	* 
  	* @access	public
	* @param	none
	* @return 	boolean - true if all image resizings were fine
	* 									- false otherwise
	*/	
	public function ManipulateImage($target_image_x,$target_image_y,$source_image_path,$source_image_name,$target_image_path,$target_image_name,$resize_type)
	{
		$image_config = array
		(
			'target_image_x' 		=> $target_image_x,
			'target_image_y' 		=> $target_image_y,
			'source_image_path' 	=> $source_image_path,
			'source_image_name' 	=> $source_image_name,
			'target_image_path' 	=> $target_image_path,		
			'target_image_name' 	=> $target_image_name
		);
		$this->log->debug('[CGRAPHICS] Parameters to ManipulateImage '.print_r($image_config,true));
		$this->log->debug('[CGRAPHICS] Resize_type '.$resize_type);			
		
		if(empty($image_config) || empty( $image_config['target_image_x'] ) || empty( $image_config['target_image_y'] ) || empty($image_config['source_image_path']) || empty($image_config['source_image_name']) || empty($image_config['target_image_path']) ||empty($image_config['target_image_name']))
		{
			$this->log->error("[CGRAPHICS] Empty parameters passed to image manipulation library");
			throw new Exception('[CGRAPHICS] Invalid parameters! Aborting image manipulation');
		}
		
		$without_ext_array = split("\.",$image_config['source_image_name']);
		$image_config['img_file_without_ext'] = $without_ext_array[0];

		// RESIZE 
		try
		{
			$LIBCONFIG = $this->_ReturnConfig($image_config,OPERATION_RESIZE,$resize_type);
			
			$this->log->fine('[CGRAPHICS] Resizing image '.$image_config['source_image_name'].'with parameters:'.print_r($LIBCONFIG,true));
			$this->_Resize_Image($LIBCONFIG);
			
			$this->log->info('[CGRAPHICS] Successfully resized image:'.$image_config['target_image_name']);			
		}
		catch(Exception $e)
		{
			$this->log->fatal('[CGRAPHICS] RESIZE failed for image:'.$image_config['source_image_name'].'. Reason:'.$e);	
			return false;
		}		

		// CROP 
		try
		{		
			if(($resize_type == RESIZE_FIXED) && ($image_config['target_image_x'] !=-1) && ($image_config['target_image_y']) !=-1)
			{
				$LIBCONFIG = $this->_ReturnConfig($image_config,OPERATION_CROP,$resize_type);
				
				$this->log->fine('[CGRAPHICS] Copping image '.$image_config['source_image_name'].'with parameters:'.print_r($LIBCONFIG,true));
				$this->_Crop_Image($LIBCONFIG, $image_config['target_image_x'], $image_config['target_image_y']);
				
				$this->log->info('[CGRAPHICS] Successfully cropped image:'.$target_image_path.'/'.$target_image_name);				
			}
		}
		catch(Exception $e)
		{
			$this->log->fatal('[CGRAPHICS] CROP failed for image:'.$image_config['source_image_name'].'. Reason:'.$e);	
			return false;
		}
		
		return true;
	}
	
	
		
	/**
  	* Resize image given array with resize parameters
  	* 
  	* @access	private
  	* @param	$LIBCONFIG - array with resize parameters
  	* @return 	boolean
  	*/
	private function _Resize_Image($LIBCONFIG)
	{
		$this->initialize($LIBCONFIG);		
		if(!$this->resize())
		{
			$this->log->fatal('[CGRAPHICS] Cannot resize file:'.$LIBCONFIG['source_image'].'::'.$this->display_errors());
			throw new Exception($this->display_errors());
		}
		
		$this->clear();
	}

	/**
  	* Crop image given array with crop parameters
  	* 
  	* @access	private
  	* @param	$LIBCONFIG - array with crop parameters
  	* @return 	boolean
  	*/
	private function _Crop_Image($LIBCONFIG)
	{
		$this->initialize($LIBCONFIG);	
		if (!$this->crop())
		{
			$this->log->fatal('[CGRAPHICS] Cannot crop file:'.$LIBCONFIG['source_image'].'::'.$this->display_errors());
			throw new Exception($this->display_errors());
		}
		
		$this->clear();
	}


	/**
  	* Determine height and width of resized image
  	* 
  	* @access	private
  	* @param	$image_type - image_type
	* @param	$maxsizex - the max length for this type
	* @param	$maxsizey - the max height for this type
	* @return 	image_dimensions - array having original image dimensions
  	*/
	private function _ReturnResizeParameters($image_dimensions,$target_x,$target_y,$resize_type)
	{
		$this->log->fine('[CGRAPHICS][OPERATION_RESIZE] Dimensions:'.$image_dimensions[X].'\t'.$image_dimensions[Y]);
		
		$source_aspect_ratio = doubleval(($image_dimensions[X]/$image_dimensions[Y]));
		$this->log->fine('[CGRAPHICS][OPERATION_RESIZE] Source aspect ratio:'.$source_aspect_ratio);
				
		$target_aspect_ratio = doubleval(($target_x/$target_y));
		$this->log->fine('[CGRAPHICS][OPERATION_RESIZE] Target aspect ratio:'.$target_aspect_ratio);	

		switch($resize_type)
		{
			case RESIZE_ORIENTATION:
			{	
				if(($source_aspect_ratio < $target_aspect_ratio) || ($target_x == -1))
				{
					return array('width'=>'','height'=>$target_y);
				}
				else //if(($source_aspect_ratio <= $target_aspect_ratio))
				{
					return array('width'=>$target_x,'height'=>'');
				}
				
				break; 
			}
			
			//6144,4096 : 390,260
			case RESIZE_FIXED:
			{
				/*
				$diff_x_coordinates = abs($image_dimensions[X] - $target_x);
				$diff_y_coordinates = abs($image_dimensions[Y] - $target_y);
				*/
						
				if($target_x == -1)
				{
					return array('width'=>'','height'=>$target_y);
				}
				elseif($target_y == -1)
				{
					return array('width'=>$target_x,'height'=>'');
				}
				else
				{
					/*
					if(($diff_x_coordinates > $diff_y_coordinates))
					{
						return array('width'=>'','height'=>$target_y);
				
					}
					elseif(($diff_y_coordinates >= $diff_x_coordinates))
					{
						return array('width'=>$target_x,'height'=>'');
					}
					*/
					if(($source_aspect_ratio > $target_aspect_ratio))
					{
						return array('width'=>'','height'=>$target_y);
				
					}
					else //if(($source_aspect_ratio <= $target_aspect_ratio))
					{
						return array('width'=>$target_x,'height'=>'');
					}
				}
							
				break;
			}
		}
		
	}


	/**
  	* Determine x,y,width and height for cropping an image
  	* 
  	* @access	private
	* @param	$resized_dim - the dimensions from the prior resized image for this type
	* @param	$maxsizex - the max length for this type
	* @param	$maxsizey - the max height for this type
  	* @return 	crop_array - array having crop parameters
  	*/
	private function _ReturnCropParameters($image_dimensions,$target_x,$target_y)
	{
		$crop_array['width'] = $target_x;
		$crop_array['height'] = $target_y;
				
		if($target_x > $target_y)
		{
			$crop_array['y_axis'] =  ($image_dimensions[Y] - $target_y)/2 ;
			$crop_array['x_axis'] = 0;

			$this->log->debug('[CGRAPHICS] OPERATION_CROP has :'.$crop_array['x_axis'].','.$crop_array['y_axis'].','.$crop_array['width'].','.$crop_array['height']);
		}
		elseif($target_x <= $target_y)
		{
			$crop_array['y_axis'] = 0;
			$crop_array['x_axis'] = ($image_dimensions[X] - $target_x)/2 ;

			$this->log->debug('[CGRAPHICS] OPERATION_CROP has :'.$crop_array['x_axis'].','.$crop_array['y_axis'].','.$crop_array['width'].','.$crop_array['height']);
		}
		
		return $crop_array;
	}

	/**
  	* Determine image resolution
  	* 
  	* @access	public
	* @param	$imagepath - the complete image path
  	* @return 	size - array containing the height and width
  	*/
	public function GetImageSize($imagepath)
	{
		$size = getimagesize($imagepath);
		
		if( $size == false )
		{
			throw new Exception("[CGRAPHICS] Could not get image size for image:".$imagepath);
		}
		
		return $size;
	}
	
	/**
  	* @access private
  	*/
  	private $log;
}

/* End of file CGraphics.php */
