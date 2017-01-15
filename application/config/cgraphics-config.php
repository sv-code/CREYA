<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| CGraphics 
|--------------------------------------------------------------------------
|
| Image Sizes for the CGraphics image stack
|
*/
$config['img_size_zoom']		= 500;
$config['img_size_standard']	= 400;
$config['img_size_snapshot']	= 300;
$config['img_size_preview']		= 200;
$config['img_size_thumbnail']	= 100;

$config[PHOTO_ZOOM]		= array( 'x' => 1920, 'y' => 1280, 'OPERATION_TYPE'=>RESIZE_ORIENTATION,'path' =>PHOTO_ZOOM_PATH);
$config[PHOTO_STANDARD]	= array('x' => 550, 'y' => -1, 'OPERATION_TYPE'=>RESIZE_FIXED, 'path' =>PHOTO_STANDARD_PATH);
$config[PHOTO_SNAPSHOT]	= array('x' =>115,'y' => 115, 'OPERATION_TYPE'=>RESIZE_FIXED, 'path' =>PHOTO_SNAPSHOT_PATH);
$config[PHOTO_PREVIEW]	= array('x' => 90,'y' => 90, 'OPERATION_TYPE'=>RESIZE_FIXED, 'path' =>PHOTO_PREVIEW_PATH);
$config[PHOTO_THUMBNAIL]	= array('x' => 65,'y' => 65, 'OPERATION_TYPE'=>RESIZE_FIXED, 'path' =>PHOTO_THUMBNAIL_PATH);
$config[PHOTO_PANORAMA]	= array('x' => 540,'y' => 260, 'OPERATION_TYPE'=>RESIZE_FIXED, 'path' =>PHOTO_PANORAMA_PATH);

$config[ARTIST_AVATAR_STANDARD]			= array('x' =>170,'y' => 170, 'OPERATION_TYPE'=>RESIZE_FIXED, 'path' =>ARTIST_AVATAR_STANDARD_PATH);
$config[ARTIST_AVATAR_PREVIEW]			= array('x' => 90,'y' => 90, 'OPERATION_TYPE'=>RESIZE_FIXED, 'path' =>ARTIST_AVATAR_PREVIEW_PATH);
$config[ARTIST_AVATAR_THUMBNAIL_MEDIUM]	= array('x' => 50,'y' => 50, 'OPERATION_TYPE'=>RESIZE_FIXED, 'path' =>ARTIST_AVATAR_THUMBNAIL_MEDIUM_PATH);
$config[ARTIST_AVATAR_THUMBNAIL_SMALL]	= array('x' => 40,'y' => 40, 'OPERATION_TYPE'=>RESIZE_FIXED, 'path' =>ARTIST_AVATAR_THUMBNAIL_SMALL_PATH);

$config[GROUP_PREVIEW_SNAPSHOT]	= array('x' =>115,'y' => 115, 'OPERATION_TYPE'=>RESIZE_FIXED, 'path' =>GROUP_PREVIEW_SNAPSHOT_PATH);
$config[GROUP_PREVIEW_PREVIEW]	= array('x' => 90,'y' => 90, 'OPERATION_TYPE'=>RESIZE_FIXED, 'path' =>GROUP_PREVIEW_PREVIEW_PATH);
$config[GROUP_PREVIEW_THUMBNAIL]	= array('x' => 50,'y' => 50, 'OPERATION_TYPE'=>RESIZE_FIXED, 'path' =>GROUP_PREVIEW_THUMBNAIL_PATH);

$config[COMMUNITY_STANDARD]	= array('x' =>550,'y' => -1, 'path' =>COMMUNITY_DISC_STANDARD_PATH);
$config[COMMUNITY_THUMBNAIL]	= array('x' => 50,'y' => 50, 'path' =>COMMUNITY_DISC_THUMBNAIL_PATH);

$config[COMMUNITY_TEMP_STANDARD]	= array('x' =>550,'y' => -1, 'path' =>COMMUNITY_DISC_STANDARD_TEMP_PATH);
$config[COMMUNITY_TEMP_PREVIEW]	= array('x' => 90,'y' => 90, 'path' =>COMMUNITY_DISC_PREVIEW_TEMP_PATH);
$config[COMMUNITY_TEMP_THUMBNAIL]	= array('x' => 50,'y' => 50, 'path' =>COMMUNITY_DISC_THUMBNAIL_TEMP_PATH);

//$config['image_library'] = 'imagemagick';
//$config['library_path'] = 'C:/Progra~1/ImageMagick-6.4.9-Q16';
$config['cgraphics_timeout'] = 85;
$config['default_timeout'] = 30;

$config[EXIF_FNUMBER_CODE] = array ( 'dbstring' => 'photo_exif_aperture' , 'matchstring' => EXIF_FNUMBER_STR );
$config[EXIF_FOCALLENGTH_CODE] = array ( 'dbstring' => 'photo_exif_focal' , 'matchstring' => EXIF_FOCALLENGTH_STR );
$config[EXIF_SOFTWARE_CODE] = array ( 'dbstring' => 'photo_exif_sw' , 'matchstring' => EXIF_SOFTWARE_STR );
$config[EXIF_METERINGMODE_CODE] = array ( 'dbstring' => 'photo_exif_mm' , 'matchstring' => EXIF_METERINGMODE_STR );
$config[EXIF_WHITEBALANCE_CODE] = array ( 'dbstring' => 'photo_exif_wb' , 'matchstring' => EXIF_WHITEBALANCE_STR );
$config[EXIF_FLASH_CODE] = array ( 'dbstring' => 'photo_exif_flash' , 'matchstring' => EXIF_FLASH_STR );
$config[EXIF_ISOSPEEDRATINGS_CODE] = array ( 'dbstring' => 'photo_exif_iso' , 'matchstring' => EXIF_ISOSPEEDRATINGS_STR );
$config[EXIF_EXPOSURETIME_CODE] = array ( 'dbstring' => 'photo_exif_shutter' , 'matchstring' => EXIF_EXPOSURETIME_STR );
$config[EXIF_MODEL_CODE] = array ( 'dbstring' => 'photo_exif_camera' , 'matchstring' => EXIF_MODEL_STR );
$config[EXIF_DATETIME_CODE] = array ( 'dbstring' => 'photo_exif_time' , 'matchstring' => EXIF_DATETIME_STR );

/* End of file config.php */
/* Location: ./system/application/config/config.php */
