<?php
/**
 * CGraphics Helper
 * 
 * Purpose
 * 		Provides the CGraphics library with the necessary Lightbox configs
 * 		NOTE: This helper is *NOT* autoloaded. You must load it manually.
 * 
 * Created 
 * 		Dec 16, 2008
 * 
 * Modified
 * 		Apr 20, 2009
 * 
 * @package		Lightbox
 * @subpackage	Helpers
 * @category	Graphics
 * @author		prakash
 * @link		TBD
 */

require(LIBPATH.'CGraphics'.EXT);

/**
 * Given source image and target image type, function retrieves config and creates image 
 * 
 * @access	public
 * @param	array $target_image_type - target image type
 * @param	array $source_image_path - the source image path
 * @param	array $source_image_name - the source image name
 * @param	array $target_image_path - the target image path
 * @return	boolean
 */ 
function cgraphics_create_image($target_image_type,$source_image_path,$source_image_name,$target_image_path)
{
	// Retrieve config from config.php here and populate $img_config
	$target_image_dimension = $CI->config->item($target_image_type);
	
	/*
	$without_ext_array = split("\.",$source_image_name);
	$source_img_without_ext = $without_ext_array[0];
			
	$target_image_name = $target_image_type.'.'.$source_img_without_ext.'.jpg';	
	*/
	
	$target_image_name = $target_image_type.'.'.$source_image_name;
	
	$CI = &get_instance();
	$CI->cutil->log->info('[CGRAPHICS] Calling CGraphics for :'.$source_image_path."/".$source_image_name);	
	
	/*
	$img_config['target_image_type'] = $target_image_type;
	$img_config['source_image_path'] = $source_image_path;
	$img_config['source_image_name'] = $source_image_name;
	$img_config['target_image_path'] = $target_image_path;
	*/
	
	return 	$CI->cgraphics->manipulateImage($target_image_dimension['x'],$target_image_dimension['y'],$source_image_path,$source_image_name,
				$target_image_path,$target_image_name);
}

function cgraphics_get_exif($source_image_name)
{
	$CI = &get_instance();
	
	$required_exif_parameters = array
	(
		EXIF_FNUMBER_CODE	=>$CI->config->item(EXIF_FNUMBER_CODE),
		EXIF_FOCALLENGTH_CODE	=>$CI->config->item(EXIF_FOCALLENGTH_CODE),
		EXIF_SOFTWARE_CODE		=>$CI->config->item(EXIF_SOFTWARE_CODE),
		EXIF_METERINGMODE_CODE	=>$CI->config->item(EXIF_METERINGMODE_CODE),
		EXIF_WHITEBALANCE_CODE	=>$CI->config->item(EXIF_WHITEBALANCE_CODE),
		EXIF_FLASH_CODE	=>$CI->config->item(EXIF_FLASH_CODE),
		EXIF_ISOSPEEDRATINGS_CODE	=>$CI->config->item(EXIF_ISOSPEEDRATINGS_CODE),
		EXIF_EXPOSURETIME_CODE	=>$CI->config->item(EXIF_EXPOSURETIME_CODE),
		EXIF_MODEL_CODE	=>$CI->config->item(EXIF_MODEL_CODE),
		EXIF_DATETIME_CODE	=>$CI->config->item(EXIF_DATETIME_CODE)
	);

	$exif_conversion_array = array( 
									EXPOSURE_MAP => $CI->config->item(EXPOSURE_MAP)
								  );
	return( $CI->cgraphics->GetExif($source_image_name,$exif_conversion_array,$required_exif_parameters));
}

function cgraphics_create_photo_image_standard($source_image_name)
{
	$CI = &get_instance();

	$img_types = array
	(
		PHOTO_STANDARD	=>$CI->config->item(PHOTO_STANDARD)
	);
	
	$CI->cutil->log->debug('[CGRAPHICS] In cgraphics helper - 2');		
	
	foreach($img_types as $img_type=>$img_config)
	{
		$CI->cgraphics->ManipulateImage
		(
			$target_image_x		=	$img_config['x'],
			$target_image_y		=	$img_config['y'],
			$source_image_path	=	PHOTO_ORIGINAL_PATH,
			$source_image_name,
			$target_image_path	=	$img_config['path'],
			$target_image_name	=	$img_type.'.'.$source_image_name,
			$operation_type     	= 	RESIZE_ORIENTATION
		); 
	}
		
}

function cgraphics_create_photo_image_snapshot($source_image_name)
{
	$CI = &get_instance();

	$img_types = array
	(
		PHOTO_SNAPSHOT	=>$CI->config->item(PHOTO_SNAPSHOT)
	);
	
	$CI->cutil->log->debug('[CGRAPHICS] In cgraphics helper - 2');		
	
	foreach($img_types as $img_type=>$img_config)
	{
		$CI->cgraphics->ManipulateImage
		(
			$target_image_x		=	$img_config['x'],
			$target_image_y		=	$img_config['y'],
			$source_image_path	=	PHOTO_ORIGINAL_PATH,
			$source_image_name,
			$target_image_path	=	$img_config['path'],
			$target_image_name	=	$img_type.'.'.$source_image_name,
			$operation_type     	= 	RESIZE_FIXED
		); 
	}
		
}

function cgraphics_create_photo_image_panorama($source_image_name)
{
	$CI = &get_instance();

	$img_types = array
	(
		PHOTO_PANORAMA	=>$CI->config->item(PHOTO_PANORAMA)
	);
	
	$CI->cutil->log->debug('[CGRAPHICS] In cgraphics helper - 2');		
	
	foreach($img_types as $img_type=>$img_config)
	{
		$CI->cgraphics->ManipulateImage
		(
			$target_image_x		=	$img_config['x'],
			$target_image_y		=	$img_config['y'],
			$source_image_path	=	PHOTO_ORIGINAL_PATH,
			$source_image_name,
			$target_image_path	=	$img_config['path'],
			$target_image_name	=	$img_type.'.'.$source_image_name,
			$operation_type     	= 	RESIZE_FIXED
		); 
	}
		
}

/*
function cgraphics_create_photo_image_meta($source_image_name)
{
	$CI = &get_instance();

	$img_types = array
	(
		PHOTO_META	=>$CI->config->item(PHOTO_META)
	);
	
	$CI->cutil->log->debug('[CGRAPHICS] In cgraphics helper - 2');		
	
	foreach($img_types as $img_type=>$img_config)
	{
		$CI->cgraphics->ManipulateImage
		(
			$target_image_x		=	$img_config['x'],
			$target_image_y		=	$img_config['y'],
			$source_image_path	=	PHOTO_ORIGINAL_PATH,
			$source_image_name,
			$target_image_path	=	$img_config['path'],
			$target_image_name	=	$img_type.'.'.$source_image_name,
			$operation_type     	= 	RESIZE_ORIENTATION
		); 
	}
		
}
*/

function cgraphics_create_capture_image($source_image_name)
{
	$CI = &get_instance();
	$CI->cutil->log->debug('[CGRAPHICS] In cgraphics helper:: cgraphics_create_capture_image');	
	
	$manipulation_result = true;
	
	$img_types = array
	(
		PHOTO_CAPTURE		=>$CI->config->item(PHOTO_CAPTURE)	
	);
	
	foreach($img_types as $img_type=>$img_config)
	{
		if( 
		$CI->cgraphics->ManipulateImage
		(
			$target_image_x		=	$img_config['x'],
			$target_image_y		=	$img_config['y'],
			$source_image_path	=	PHOTO_ORIGINAL_PATH,
			$source_image_name,
			$target_image_path	=	$img_config['path'],
			$target_image_name	=	$img_type.'.'.$source_image_name,
			$operation_type     	= 	$img_config['OPERATION_TYPE']
		) != true)
		{
			$manipulation_result = false;		
		} 
	}

	$CI->cutil->log->debug('[CGRAPHICS] Manipulation result for cgraphics_create_capture_image: '.$manipulation_result);
	return $manipulation_result;		
}

function cgraphics_create_photo_image_stack($source_image_name)
{
	$CI = &get_instance();
	$CI->cutil->log->debug('[CGRAPHICS] In cgraphics helper:: cgraphics_create_photo_image_stack');	
	
	$manipulation_result = true;
	
	$img_types = array
	(
		PHOTO_STANDARD	=>$CI->config->item(PHOTO_STANDARD),
		PHOTO_SNAPSHOT	=>$CI->config->item(PHOTO_SNAPSHOT),
		PHOTO_PREVIEW		=>$CI->config->item(PHOTO_PREVIEW),
		PHOTO_THUMBNAIL	=>$CI->config->item(PHOTO_THUMBNAIL),
		PHOTO_PANORAMA	=>$CI->config->item(PHOTO_PANORAMA),
		PHOTO_SNAPSHOT_TILE	=>$CI->config->item(PHOTO_SNAPSHOT_TILE)
	);
	
	//$CI->cutil->log->debug('[CGRAPHICS] In cgraphics helper - 2');		
	
	foreach($img_types as $img_type=>$img_config)
	{
		if( 
		$CI->cgraphics->ManipulateImage
		(
			$target_image_x		=	$img_config['x'],
			$target_image_y		=	$img_config['y'],
			$source_image_path	=	PHOTO_ORIGINAL_PATH,
			$source_image_name,
			$target_image_path	=	$img_config['path'],
			$target_image_name	=	$img_type.'.'.$source_image_name,
			$operation_type     	= 	$img_config['OPERATION_TYPE']
		) != true)
		{
			$manipulation_result = false;		
		} 
	}

	$CI->cutil->log->debug('[CGRAPHICS] Manipulation result for cgraphics_create_image_stack: '.$manipulation_result);
	return $manipulation_result;	
	/*
	$CI->cutil->log->debug('[CGRAPHICS] In cgraphics helper - 3');
	$img_config	=$CI->config->item(PHOTO_ZOOM);
	$CI->cgraphics->ManipulateImage
		(
			$target_image_x		=	$img_config['x'],
			$target_image_y		=	$img_config['y'],
			$source_image_path	=	PHOTO_ORIGINAL_PATH,
			$source_image_name,
			$target_image_path	=	$img_config['path'],
			$target_image_name	=	PHOTO_ZOOM.'.'.$source_image_name,
			$operation_type     	= 	RESIZE_ORIENTATION
		);
	$CI->cutil->log->debug('[CGRAPHICS] In cgraphics helper - 4');
	*/	
}

function cgraphics_create_artist_avatar_image_stack($source_image_name)
{
	$CI = &get_instance();
	
	$manipulation_result = true;	
		
	$img_types = array
	(
		ARTIST_AVATAR_STANDARD			=>$CI->config->item(ARTIST_AVATAR_STANDARD),
		ARTIST_AVATAR_PREVIEW			=>$CI->config->item(ARTIST_AVATAR_PREVIEW),
		ARTIST_AVATAR_THUMBNAIL_MEDIUM	=>$CI->config->item(ARTIST_AVATAR_THUMBNAIL_MEDIUM),
		ARTIST_AVATAR_THUMBNAIL_SMALL	=>$CI->config->item(ARTIST_AVATAR_THUMBNAIL_SMALL),
		ARTIST_AVATAR_PROFILE	=>$CI->config->item(ARTIST_AVATAR_PROFILE),
		ARTIST_AVATAR_CAPTURE	=>$CI->config->item(ARTIST_AVATAR_CAPTURE)
	);
	
	foreach($img_types as $img_type=>$img_config)
	{
		if( 
		$CI->cgraphics->ManipulateImage
		(
			$target_image_x		=	$img_config['x'],
			$target_image_y		=	$img_config['y'],
			$source_image_path	=	ARTIST_AVATAR_ORIGINAL_PATH,
			$source_image_name,
			$target_image_path	=	$img_config['path'],
			$target_image_name	=	$img_type.'.'.$source_image_name,
			$operation_type     	= 	RESIZE_FIXED
		) != true )
		{
			$manipulation_result = false;
		}
	}
	
	return $manipulation_result;
}

function cgraphics_create_discussion_template($img_types,$source_image_path,$source_image_name)
{
	$CI = &get_instance();		
	$manipulation_result = true;
	
	$CI->cutil->log->debug('[CGRAPHICS HELPER] Invoking manipulate image for discussion');
	foreach($img_types as $img_type=>$img_config)
	{
		if( $CI->cgraphics->ManipulateImage
			(
				$target_image_x		=	$img_config['x'],
				$target_image_y		=	$img_config['y'],
				$source_image_path,
				$source_image_name,
				$target_image_path	=	$img_config['path'],
				$target_image_name	=	$img_type.'.'.$source_image_name,
				$operation_type     	= 	$img_config['OPERATION_TYPE']
			)  != true
		)
		{
			$manipulation_result = false;
		}
	}
	$CI->cutil->log->debug('[CGRAPHICS HELPER] Returning from manipulate image of discussion');	
	
	return $manipulation_result;
}

function  cgraphics_create_group_discussion_image_stack($source_image_name)
{
	$CI = &get_instance();	
	$img_types = array
	(
		GROUP_DISCUSSION_STANDARD		=>$CI->config->item(GROUP_DISCUSSION_STANDARD),
		GROUP_DISCUSSION_CAPTURE		=>$CI->config->item(GROUP_DISCUSSION_CAPTURE)
	);
	$source_image_path	=	GROUP_DISCUSSION_ORIGINAL_PATH;	
		
	return( cgraphics_create_discussion_template($img_types,$source_image_path,$source_image_name) );		
}


function cgraphics_create_discussion_image_stack($source_image_name,$path)
{
	$CI = &get_instance();

	$img_types = array
	(
		DISCUSSION_STANDARD	=>$CI->config->item(DISCUSSION_STANDARD),
		DISCUSSION_PREVIEW		=>$CI->config->item(DISCUSSION_PREVIEW)
	);
	
	//$source_image_path	=	COMMUNITY_DISC_ORIGINAL_PATH;
	$source_image_path	=	$path;

	return( cgraphics_create_discussion_template($img_types,$source_image_path,$source_image_name) );
}


/*
 * Function called from community discussion page when user chooses to attach an external image
 */
function cgraphics_create_discussion_capture_image($source_image_name)
{
	$CI = &get_instance();

	$img_types = array
	(
		DISCUSSION_CAPTURE		=>$CI->config->item(DISCUSSION_CAPTURE)
	);
	
	$source_image_path	=	COMMUNITY_DISC_ORIGINAL_PATH;

	return( cgraphics_create_discussion_template($img_types,$source_image_path,$source_image_name) );
}

function cgraphics_is_min_size($source_image_name,$minx,$miny)
{
	$CI = &get_instance();
	$CI->cutil->log->info('[CGRAPHICS HELPER] Checking minimum size for '.$source_image_name);
	$image_dimensions =  $CI->cgraphics->GetImageSize($source_image_name);
	
	if( is_array($image_dimensions) )
	{
		$CI->cutil->log->debug('[CGRAPHICS HELPER] Image dimensions [x]= '.$image_dimensions[0].',[y]='.$image_dimensions[1]);
		if( $image_dimensions[0] < $minx || $image_dimensions[1] < $miny )
		{
			$CI->cutil->log->info('[CGRAPHICS HELPER] Image dimensions lesser than specified limits for '.$source_image_name);
			return false;
		}
	}
	$CI->cutil->log->info('[CGRAPHICS HELPER] image dimensions greater than specified limits ');
	return true;
}

function cgraphics_create_group_preview_image_stack($source_image_name)
{
	$CI = &get_instance();
	
	$manipulation_result = true;	
	
	$img_types = array
	(
		GROUP_PREVIEW_PANORAMA			=>$CI->config->item(GROUP_PREVIEW_PANORAMA),
		GROUP_PREVIEW_SPANORAMA			=>$CI->config->item(GROUP_PREVIEW_SPANORAMA),
		GROUP_PREVIEW_THUMBNAIL_MEDIUM		=>$CI->config->item(GROUP_PREVIEW_THUMBNAIL_MEDIUM)
	);
	
	$manipulation_result = true;
	
	$CI->cutil->log->debug('[CGRAPHICS HELPER] Invoking manipulate image for group');
	foreach($img_types as $img_type=>$img_config)
	{
		if( $CI->cgraphics->ManipulateImage
			(
				$target_image_x		=	$img_config['x'],
				$target_image_y		=	$img_config['y'],
				$source_image_path	=	GROUP_PREVIEW_ORIGINAL_PATH,
				$source_image_name,
				$target_image_path	=	$img_config['path'],
				$target_image_name	=	$img_type.'.'.$source_image_name,
				$operation_type     	= 	RESIZE_FIXED
			)  != true
		)
		{
			$manipulation_result = false;
		}
	}
	$CI->cutil->log->debug('[CGRAPHICS HELPER] Returning from manipulate image of group');	
	
	return $manipulation_result;
}

function cgraphics_create_community_image_stack($source_image_name)
{
	$CI = &get_instance();
	
	$img_types = array
	(
		COMMUNITY_TEMP_STANDARD		=>$CI->config->item(COMMUNITY_TEMP_STANDARD),
		COMMUNITY_TEMP_PREVIEW	=>$CI->config->item(COMMUNITY_TEMP_PREVIEW)
	);
	
	$manipulation_result = true;
	foreach($img_types as $img_type=>$img_config)
	{
		if( 
			$CI->cgraphics->ManipulateImage
			(
				$target_image_x		=	$img_config['x'],
				$target_image_y		=	$img_config['y'],
				$source_image_path	=	COMMUNITY_DISC_TEMP_PATH,
				$source_image_name,
				$target_image_path	=	$img_config['path'],
				//$target_image_name	=	$img_type.'.'.$source_image_name,
				$target_image_name	=	$img_type.'.'.$source_image_name,
				$operation_type     	= 	RESIZE_FIXED
			) != true )
			{
				$manipulation_result = false;
			}
		
	}
	return $manipulation_result;
	//return COMMUNITY_TEMP_PREVIEW.'.'.$disc_id.'-'.$source_image_name;
}

function cgraphics_image_group_url($img_type,$pid)
{
	$CI = &get_instance();

	$file = $pid;
	if( $pid != "undefined" )
	{	
		switch($img_type)
		{
			case GROUP_DISCUSSION_META:
				$file =  '/'.GROUP_DISCUSSION_META_PATH.GROUP_DISCUSSION_META.'.'.$pid.'.jpg';
				break;
		}
	}
	return $file;
}

function cgraphics_image_photo_url($img_type,$pid)
{
	$CI = &get_instance();
	//$CI->cutil->log->debug('[CGRAPHICS] pid before is '.$pid);
	
	/*
	$pid = $pid - 920+1;
	
	if($pid>300)
	{
		return '/images/galleryPic.png';
	}*/
	
	//$CI->cutil->log->debug('[CGRAPHICS] pid after is '.$pid);
	
	switch($img_type)
	{
		case PHOTO_SNAPSHOT:
			$file =  '/'.PHOTO_SNAPSHOT_PATH.PHOTO_SNAPSHOT.'.'.$pid.'.jpg';
			break;
			
		case PHOTO_SNAPSHOT_TILE:
			$file =  '/'.PHOTO_SNAPSHOT_TILE_PATH.PHOTO_SNAPSHOT_TILE.'.'.$pid.'.jpg';
			break;
			
		case PHOTO_STANDARD:
			$file =  '/'.PHOTO_STANDARD_PATH.PHOTO_STANDARD.'.'.$pid.'.jpg';
			break;
			
		case PHOTO_ZOOM:
			$file =  '/'.PHOTO_ZOOM_PATH.PHOTO_ZOOM.'.'.$pid.'.jpg';
			break;
			
		case PHOTO_PREVIEW:
			$file =  '/'.PHOTO_PREVIEW_PATH.PHOTO_PREVIEW.'.'.$pid.'.jpg';
			break;
			
		case PHOTO_THUMBNAIL:
			$file =  '/'.PHOTO_THUMBNAIL_PATH.PHOTO_THUMBNAIL.'.'.$pid.'.jpg';
			break;
			
		case PHOTO_PANORAMA:
			$file =  '/'.PHOTO_PANORAMA_PATH.PHOTO_PANORAMA.'.'.$pid.'.jpg';
			break;
	}
		
	return $file;
}

function cgraphics_image_artist_avatar_url($img_type,$image_name)
{
	switch($img_type)
	{
		case ARTIST_AVATAR_STANDARD:
			$file =  '/'.ARTIST_AVATAR_STANDARD_PATH.ARTIST_AVATAR_STANDARD.'.'.$image_name.'.jpg';
			break;
			
		case ARTIST_AVATAR_PREVIEW:
			$file =  '/'.ARTIST_AVATAR_PREVIEW_PATH.ARTIST_AVATAR_PREVIEW.'.'.$image_name.'.jpg';
			break;
			
		case ARTIST_AVATAR_THUMBNAIL_MEDIUM:
			$file =  '/'.ARTIST_AVATAR_THUMBNAIL_MEDIUM_PATH.ARTIST_AVATAR_THUMBNAIL_MEDIUM.'.'.$image_name.'.jpg';
			break;
			
		case ARTIST_AVATAR_THUMBNAIL_SMALL:
			$file =  '/'.ARTIST_AVATAR_THUMBNAIL_SMALL_PATH.ARTIST_AVATAR_THUMBNAIL_SMALL.'.'.$image_name.'.jpg';
			break;
			
		case ARTIST_AVATAR_PROFILE:
			$file =  '/'.ARTIST_AVATAR_PROFILE_PATH.ARTIST_AVATAR_PROFILE.'.'.$image_name.'.jpg';
			break;
	}
		
	return $file;
}

function cgraphics_image_group_preview_url($img_type,$preview_filename)
{
	//$gid=rand(1,294);
		
	switch($img_type)
	{
		case GROUP_PREVIEW_PANORAMA:
			$file =  '/'.GROUP_PREVIEW_PANORAMA_PATH.GROUP_PREVIEW_PANORAMA.'.'.$preview_filename.'.jpg';
			break;
			
		case GROUP_PREVIEW_SPANORAMA:
			$file =  '/'.GROUP_PREVIEW_SPANORAMA_PATH.GROUP_PREVIEW_SPANORAMA.'.'.$preview_filename.'.jpg';
			break;
			
		case GROUP_PREVIEW_THUMBNAIL_MEDIUM:
			$file =  '/'.GROUP_PREVIEW_THUMBNAIL_MEDIUM_PATH.GROUP_PREVIEW_THUMBNAIL_MEDIUM.'.'.$preview_filename.'.jpg';
			break;
	}
		
	return $file;
}

function cgraphics_image_group_discussion_preview_url($img_type,$preview_filename)
{
	//$gid=rand(1,294);
		
	switch($img_type)
	{
		case GROUP_DISCUSSION_STANDARD:
			$file =  '/'.GROUP_DISCUSSION_STANDARD_PATH.GROUP_DISCUSSION_STANDARD.'.'.$preview_filename.'.jpg';
			break;
	}
		
	return $file;
}

function cgraphics_image_discussion_preview_url($entity_type,$img_type,$filename)
{
	//$gid=rand(1,294);
		
	if( $entity_type == DISCUSSION_TYPE_COMMUNITY )
	{	
		switch($img_type)
		{
			case DISCUSSION_PREVIEW:
				$file =  '/'.COMMUNITY_DISC_PREVIEW_PATH.DISCUSSION_PREVIEW.'.'.$filename.'.jpg';
				break;
				
			case DISCUSSION_STANDARD:
				$file =  '/'.COMMUNITY_DISC_STANDARD_PATH.DISCUSSION_STANDARD.'.'.$filename.'.jpg';
				break;
		}
	}
	else
	if( $entity_type == DISCUSSION_TYPE_GROUP )
	{	
		switch($img_type)
		{
			case DISCUSSION_STANDARD:
				$file =  '/'.GROUP_DISCUSSION_STANDARD_PATH.GROUP_DISCUSSION_STANDARD.'.'.$filename.'.jpg';
				break;
		}
	}
		
	return $file;
}


/* End of file cgraphics_helper.php */
