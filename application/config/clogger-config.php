<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Module Level Logging - Files
|--------------------------------------------------------------------------
|
| Log files for module level logging
|
*/

require(LIBPATH.'CLogger'.EXT);
 
$config['logfile_photos'] 			= LOGPATH.'photos.log';
$config['logfile_groups'] 			= LOGPATH.'groups.log';

$config['logfile_photo'] 				= LOGPATH.'photo.log';
$config['logfile_group'] 				= LOGPATH.'group.log';
$config['logfile_artist'] 				= LOGPATH.'artist.log';

$config['logfile_stats'] 				= LOGPATH.'stats.log';

$config['logfile_util'] 				= LOGPATH.'util.log';
$config['logfile_auth'] 				= LOGPATH.'auth.log';
$config['logfile_dashboard'] 			= LOGPATH.'dashboard.log';

$config['logfile_photoexplore'] 		= LOGPATH.'photoexplore.log';
$config['logfile_groupexplore'] 		= LOGPATH.'photoexplore.log';
$config['logfile_shoeboxexplore'] 		= LOGPATH.'photoexplore.log';
$config['logfile_communityexplore'] 		= LOGPATH.'photoexplore.log';
$config['logfile_gdiscussionexplore'] 	= LOGPATH.'photoexplore.log';

$config['logfile_communitypost'] 		= LOGPATH.'photoexplore.log';

$config['logfile_shoebox'] 		= LOGPATH.'shoebox.log';

$config['logfile_community'] 		= LOGPATH.'community.log';

$config['logfile_cranker'] 		= LOGPATH.'cranker.log';

$config['logfile_csession'] 			= LOGPATH.'csession.log';
$config['logfile_cgraphics'] 			= LOGPATH.'cgraphics.log';

/*
|--------------------------------------------------------------------------
| Module Level Logging - Levels
|--------------------------------------------------------------------------
|
| Log files for module level logging
|
*/

$config['loglevel_global']			= CLogger::$DEBUG;

$config['loglevel_photos'] 			= CLogger::$INFO;
$config['loglevel_groups'] 			= CLogger::$INFO;

$config['loglevel_photo'] 			= CLogger::$DEBUG;
$config['loglevel_group'] 			= CLogger::$INFO;
$config['loglevel_artist'] 			= CLogger::$INFO;

$config['loglevel_stats'] 			= CLogger::$INFO;

$config['loglevel_util']				= CLogger::$DEBUG;
$config['loglevel_auth']				= CLogger::$INFO;
$config['loglevel_dashboard']			= CLogger::$INFO;

$config['loglevel_photoexplore']		= CLogger::$FINE;
$config['loglevel_groupexplore']		= CLogger::$INFO;
$config['loglevel_shoeboxexplore']		= CLogger::$INFO;
$config['loglevel_communityexplore']	= CLogger::$INFO;
$config['loglevel_gdiscussionexplore']	= CLogger::$INFO;

$config['loglevel_communitypost']		= CLogger::$INFO;

$config['loglevel_community']		= CLogger::$INFO;

$config['loglevel_shoebox']		= CLogger::$INFO;

$config['loglevel_csession']			= CLogger::$INFO;

$config['loglevel_cgraphics']			= CLogger::$INFO;

/* End of file config.php */
/* Location: ./system/application/config/config.php */
