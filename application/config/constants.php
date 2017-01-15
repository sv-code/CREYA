<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
|TIME
|--------------------------------------------------------------------------
*/
define('MINUTE_DURATION_SECONDS',60);
define('HOUR_DURATION_SECONDS',60*60);
define('DAY_DURATION_SECONDS',24*60*60);
define('WEEK_DURATION_SECONDS',7*24*60*60);
define('MONTH_DURATION_SECONDS',30*7*24*60*60);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 					'ab');
define('FOPEN_READ_WRITE_CREATE', 				'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 			'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| CSESSION
|--------------------------------------------------------------------------
*/

define('LIGHTBOX_DEFAULT_SESSION_EXPIRATION',5);

/*
|--------------------------------------------------------------------------
| EXCEPTIONS
|--------------------------------------------------------------------------
*/
define('EXCEPTION_PATH',APPPATH.'exceptions/');

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

define('CONTROLLER_BASE_PATH',CONTROLLER_PATH.'base/');
define('CONTROLLER_UTIL_PATH',CONTROLLER_PATH.'util/');

define('VIEW_NAVIGATION_ACTIVE_PHOTOS',1);
define('VIEW_NAVIGATION_ACTIVE_GROUPS',2);
define('VIEW_NAVIGATION_ACTIVE_COMMUNITY',3);
define('VIEW_NAVIGATION_ACTIVE_NONE',-1);

define('VIEW_COMMUNITY_DISCUSSIONS_NUM_COLUMNS',3);
define('VIEW_COMMUNITY_DISCUSSIONS_NUM_ROWS',5);
define('VIEW_COMMUNITY_NUM_POPULAR_DISCUSSIONS',5);
define('VIEW_COMMUNITY_DISCUSSION_NUM_COMMENTS',20);
define('VIEW_COMMUNITY_DISCUSSION_NUM_RELATED_DISCUSSIONS',5);
define('VIEW_COMMUNITY_DISCUSSION_NUM_ACTIVE_MEMBERS',5);

define('VIEW_GROUP_NUM_TOP_RATED_PHOTOS',5);
define('VIEW_GROUP_NUM_RECENTLY_ADDED_PHOTOS',5);
define('VIEW_GROUP_NUM_RECENT_DISCUSSIONS',3);
define('VIEW_GROUP_NUM_POPULAR_DISCUSSIONS',5);
define('VIEW_GROUP_NUM_DISCUSSIONS',5);
define('VIEW_GROUP_NUM_NEW_MEMBERS',5);

define('VIEW_GROUP_DISCUSSION_NUM_COMMENTS',20);
define('VIEW_GROUP_DISCUSSION_NUM_RELATED_DISCUSSIONS',5);
define('VIEW_GROUP_DISCUSSION_NUM_ACTIVE_MEMBERS',5);

define('VIEW_ARTIST_PROFILE_NUM_RECENTLY_UPLOADED_PHOTOS',6);
define('VIEW_ARTIST_PROFILE_NUM_MOST_BOOKMARKED_PHOTOS',6);
define('VIEW_ARTIST_PROFILE_NUM_TOP_RATED_PHOTOS',9);
define('VIEW_ARTIST_PROFILE_NUM_RECENTLY_BOOKMARKED_PHOTOS',5);
define('VIEW_ARTIST_PROFILE_NUM_RECENTLY_COMMENTED_PHOTOS',5);
define('VIEW_ARTIST_PROFILE_NUM_COMMENTS',20);

define('VIEW_ARTIST_CONTROL_ACTIVE_PROFILE',1);
define('VIEW_ARTIST_CONTROL_ACTIVE_GALLERY',2);
define('VIEW_ARTIST_CONTROL_ACTIVE_STATS',3);
define('VIEW_ARTIST_CONTROL_ACTIVE_SHOEBOXES',4);
define('VIEW_ARTIST_CONTROL_ACTIVE_NONE',-1);

define('VIEW_ARTIST_GALLERY_INFO_ACTIVE_PHOTOS',1);
define('VIEW_ARTIST_GALLERY_INFO_ACTIVE_SHOEBOXES',2);

define('VIEW_ARTIST_SHOEBOX_NUM_COLUMNS',6);
define('VIEW_ARTIST_SHOEBOX_NUM_ROWS',6);

define('VIEW_ARTIST_SHOEBOX_EXPLORE_NUM_COLUMNS',4);
define('VIEW_ARTIST_SHOEBOX_EXPLORE_NUM_ROWS',4);

define('VIEW_ARTIST_STATS_MOST_BOOKMARKED_PHOTOS',8);
define('VIEW_ARTIST_STATS_MOST_COMMENTED_PHOTOS',8);
define('VIEW_ARTIST_STATS_TOP_RATED',5);

define('VIEW_GALLERY_PHOTOS_NUM_COLUMNS',5);
define('VIEW_GALLERY_PHOTOS_NUM_ROWS',7);

define('VIEW_GALLERY_GROUPS_NUM_COLUMNS',2);
define('VIEW_GALLERY_GROUPS_NUM_ROWS',5);

define('VIEW_GALLERY_SHOEBOXES_NUM_COLUMNS',4);
define('VIEW_GALLERY_SHOEBOXES_NUM_ROWS',2);

define('VIEW_GROUP_CONTROL_ACTIVE_INFO',1);
define('VIEW_GROUP_CONTROL_ACTIVE_GALLERY',2);
define('VIEW_GROUP_CONTROL_ACTIVE_POSTS',3);
define('VIEW_GROUP_CONTROL_ACTIVE_NONE',-1);

define('COMMUNITY_DISCUSSIONS_PREVIEW_NUM_COLUMNS',3);

define('VIEW_PAGINATION_MAX_PAGES',6);

define('VIEW_BOOKMARKS_TYPE_PHOTO',0);
define('VIEW_BOOKMARKS_TYPE_ARTIST',1);
define('VIEW_BOOKMARKS_TYPE_GROUP',2);

define('VIEW_PHOTO_MORE',8);
define('VIEW_PHOTO_NUM_COMMENTS',3);

define('DISCUSSION_TYPE_COMMUNITY',0);
define('DISCUSSION_TYPE_GROUP',1);

define('DISCUSSION_SECTION_DEFAULT',0);
define('DISCUSSION_SECTION_REVIEW_REQUEST',1);

/*
|--------------------------------------------------------------------------
| MODELS
|--------------------------------------------------------------------------
*/

define('MODEL_BASE_PATH',MODEL_PATH.'base/');
define('MODEL_INTERFACE_PATH',MODEL_PATH.'interface/');
define('MODEL_UTIL_PATH',MODEL_PATH.'util/');

define('DEFINED',1);

define('INCREMENT',1);
define('DECREMENT',-1);

define('OFFSET_NONE',0);
define('SINGLE_RESULT',1);

define('FILTER_NONE',-7);
define('RESULTS_ALL',-8);

define('ORDER_DATE',-5);
define('ORDER_RELEVANCE',-6);

define('FILTER_PHOTO_NAME',-11);
define('FILTER_PHOTO_TAGS',-12);
define('FILTER_PHOTO_TAGS_ALL',-14);

define('LIGHTBOX_REVIEW_CROP_COORDINATES_DELIMITER',',');
define('LIGHTBOX_IMAGE_ATTACHMENT_LIST_DELIMITER',',');
define('LIGHTBOX_TAG_LIST_DELIMITER',',');
define('LIGHTBOX_SEARCH_KEYWORD_DELIMITER',' ');
define('ARTIST_FOCUS_DELIMITER',', ');
define('WHITESPACE',' ');
define('REGEX_MULTI_WHITESPACE','/[\s]+/');
define('REGEX_MULTI_COMMA','/[,]+/');

define('TAG_NONE','TAG_NONE');

define('KILOBYTE',1000);

define('LIGHTBOX_STAGE_MEMBER',0);
define('LIGHTBOX_STAGE_INVITED',1);
define('LIGHTBOX_STAGE_PENDING_INVITE',2);
define('LIGHTBOX_STAGE_UNKNOWN',3);

define('ALLOW_NULL',1);
define('DISALLOW_NULL',-1);

define('TAGS_TRIM_CHARACTERS',' ,;"');
define('MAX_TAG_LENGTH',35);

/*
|--------------------------------------------------------------------------
| PHOTO PROPERTIES
|--------------------------------------------------------------------------
*/
/*
define('PHOTO_TYPE_LANDSCAPE',1);
define('PHOTO_TYPE_PORTRAITURE',1);
define('PHOTO_TYPE_LANDSCAPE',1);
define('PHOTO_TYPE_LANDSCAPE',1);
define('PHOTO_TYPE_LANDSCAPE',1);
*/

/*
|--------------------------------------------------------------------------
| CGRAPHICS
|--------------------------------------------------------------------------
*/
define('RESIZE_FIXED',200);
define('RESIZE_ORIENTATION',201);

define('OPERATION_RESIZE',100);
define('OPERATION_CROP',101);

define('PHOTO_ZOOM',1);
define('PHOTO_STANDARD',2);
define('PHOTO_SNAPSHOT',3);
define('PHOTO_PREVIEW',4);
define('PHOTO_THUMBNAIL',5);
define('PHOTO_PANORAMA',6);
define('PHOTO_CAPTURE',20);
define('PHOTO_SNAPSHOT_TILE',21);

define('ARTIST_AVATAR_STANDARD',7);
define('ARTIST_AVATAR_PREVIEW',8);
define('ARTIST_AVATAR_THUMBNAIL_MEDIUM',9);
define('ARTIST_AVATAR_THUMBNAIL_SMALL',10);
define('ARTIST_AVATAR_CAPTURE',17);
define('ARTIST_AVATAR_PROFILE',18);

define('GROUP_PREVIEW_PANORAMA',12);
define('GROUP_PREVIEW_SPANORAMA',13);
define('GROUP_PREVIEW_THUMBNAIL_MEDIUM',61);

define('GROUP_DISCUSSION_CAPTURE',40);
define('GROUP_DISCUSSION_STANDARD',41);

define('COMMUNITY_STANDARD',14);
define('COMMUNITY_PREVIEW',15);
define('COMMUNITY_THUMBNAIL',16);

define('DISCUSSION_STANDARD',53);
define('DISCUSSION_CAPTURE',54);
define('DISCUSSION_PREVIEW',55);

define('COMMUNITY_TEMP_STANDARD',50);
define('COMMUNITY_TEMP_PREVIEW',51);
define('COMMUNITY_TEMP_THUMBNAIL',52);

//exif strings 
define('EXIF_FNUMBER_STR','exif:FNumber');
define('EXIF_FOCALLENGTH_STR','exif:FocalLength');
define('EXIF_SOFTWARE_STR','exif:Software');
define('EXIF_METERINGMODE_STR','exif:MeteringMode');
define('EXIF_WHITEBALANCE_STR','exif:WhiteBalance');
define('EXIF_FLASH_STR','exif:Flash');
define('EXIF_ISOSPEEDRATINGS_STR','exif:ISOSpeedRatings');
define('EXIF_EXPOSURETIME_STR','exif:ExposureTime');
define('EXIF_MODEL_STR','exif:Model');
define('EXIF_DATETIME_STR','exif:DateTime');

//exif codes
define('EXIF_FNUMBER_CODE',900);
define('EXIF_FOCALLENGTH_CODE',901);
define('EXIF_SOFTWARE_CODE',902);
define('EXIF_METERINGMODE_CODE',903);
define('EXIF_WHITEBALANCE_CODE',904);
define('EXIF_FLASH_CODE',905);
define('EXIF_ISOSPEEDRATINGS_CODE',906);
define('EXIF_EXPOSURETIME_CODE',907);
define('EXIF_MODEL_CODE',908);
define('EXIF_DATETIME_CODE',909);

define('NUMERIC_EXPOSURE_VALUES',2000);
define('STRING_EXPOSURE_VALUES',2001);
define('EXPOSURE_MAP',2002);

/*
|--------------------------------------------------------------------------
| CIMAGEUPLOADER
|--------------------------------------------------------------------------
*/
define('UPLOAD_IMAGE_MAX_SIZE',50000);
define('IMAGE_MAX_WIDTH','4000');
define('IMAGE_MAX_HEIGHT','4000');

/*
|--------------------------------------------------------------------------
| USER IMAGES
|--------------------------------------------------------------------------
*/
define('PHOTO_ORIGINAL_PATH',USER_IMG_PATH.'photo/ORIGINAL/');
define('PHOTO_ZOOM_PATH',USER_IMG_PATH.'photo/zoom/');
define('PHOTO_STANDARD_PATH',USER_IMG_PATH.'photo/standard/');
define('PHOTO_SNAPSHOT_TILE_PATH',USER_IMG_PATH.'photo/snapshot.tile/');
define('PHOTO_SNAPSHOT_PATH',USER_IMG_PATH.'photo/snapshot/');
define('PHOTO_PREVIEW_PATH',USER_IMG_PATH.'photo/preview/');
define('PHOTO_THUMBNAIL_PATH',USER_IMG_PATH.'photo/thumbnail/');
define('PHOTO_PANORAMA_PATH',USER_IMG_PATH.'photo/panorama/');
define('PHOTO_CAPTURE_PATH',USER_IMG_PATH.'photo/capture/');

define('ARTIST_AVATAR_ORIGINAL_PATH',USER_IMG_PATH.'artist/avatar/ORIGINAL/');
define('ARTIST_AVATAR_STANDARD_PATH',USER_IMG_PATH.'artist/avatar/standard/');
define('ARTIST_AVATAR_PREVIEW_PATH',USER_IMG_PATH.'artist/avatar/preview/');
define('ARTIST_AVATAR_THUMBNAIL_MEDIUM_PATH',USER_IMG_PATH.'artist/avatar/thumbnail/medium/');
define('ARTIST_AVATAR_THUMBNAIL_SMALL_PATH',USER_IMG_PATH.'artist/avatar/thumbnail/small/');
define('ARTIST_AVATAR_CAPTURE_PATH',USER_IMG_PATH.'artist/avatar/capture/');
define('ARTIST_AVATAR_PROFILE_PATH',USER_IMG_PATH.'artist/avatar/profile/');

define('GROUP_PREVIEW_ORIGINAL_PATH',USER_IMG_PATH.'group/preview/ORIGINAL/');
define('GROUP_PREVIEW_SPANORAMA_PATH',USER_IMG_PATH.'group/preview/spanorama/');
define('GROUP_PREVIEW_CAPTURE_PATH',USER_IMG_PATH.'group/preview/capture/');
define('GROUP_PREVIEW_PANORAMA_PATH',USER_IMG_PATH.'group/preview/panorama/');
define('GROUP_PREVIEW_THUMBNAIL_MEDIUM_PATH',USER_IMG_PATH.'group/preview/thumbnail/medium/');

define('GROUP_DISCUSSION_ORIGINAL_PATH',USER_IMG_PATH.'group/disc/ORIGINAL/');
define('GROUP_DISCUSSION_CAPTURE_PATH',USER_IMG_PATH.'group/disc/capture/');
define('GROUP_DISCUSSION_STANDARD_PATH',USER_IMG_PATH.'group/disc/standard/');

define('COMMUNITY_DISC_ORIGINAL_PATH',USER_IMG_PATH.'community/disc/ORIGINAL/');
define('COMMUNITY_DISC_STANDARD_PATH',USER_IMG_PATH.'community/disc/standard/');
define('COMMUNITY_DISC_PREVIEW_PATH',USER_IMG_PATH.'community/disc/preview/');
define('COMMUNITY_DISC_CAPTURE_PATH',USER_IMG_PATH.'community/disc/capture/');

define('COMMUNITY_DISC_THUMBNAIL_PATH',USER_IMG_PATH.'community/disc/thumbnail/');
define('COMMUNITY_DISC_TEMP_PATH',TEMP_IMG_PATH.'community/disc/ORIGINAL/');
define('COMMUNITY_DISC_STANDARD_TEMP_PATH',TEMP_IMG_PATH.'community/disc/standard/');
define('COMMUNITY_DISC_PREVIEW_TEMP_PATH',TEMP_IMG_PATH.'community/disc/preview/');
define('COMMUNITY_DISC_THUMBNAIL_TEMP_PATH',TEMP_IMG_PATH.'community/disc/thumbnail/');

/*
 |--------------------------------------------------------------------------
 | User max image limit
 |-------------------------------------------------------------------------- 
 */
 define('MAX_USER_PHOTOS',600);

/*
|--------------------------------------------------------------------------
| ERROR CODES
|--------------------------------------------------------------------------
*/
//define('RETURN_CODE',0);
//define('RETURN_MESSAGE',1);

define('SUCCESS_WITH_MESSAGE',2);
define('SUCCESS',0);
define('ERROR_INVALID_SESSION',-1);
define('ERROR_VALID_SESSION',-2);
define('ERROR_BAD_POST_DATA',-3);
define('ERROR_CGRAPHICS_MIN_SIZE',-4);
define('ERROR_AUTHENTICATION_FAILED',-5);
define('ERROR_INVALID_USER',-6);
define('ERROR_ARTIST_DNAME_UNAVAILABLE',-7);

define('ERROR_UNKNOWN',-9999);

/* Location: ./system/application/config/constants.php */
  
 /* End of file constants.php */ 