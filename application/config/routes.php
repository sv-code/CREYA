<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/

//$route['default_controller'] = "login";
$route['default_controller'] = "gateway";
$route['scaffolding_trigger'] = "";

/*
|--------------------------------------------------------------------------
| LIGHTBOX ROUTING
|--------------------------------------------------------------------------
|  
*/
$route['gateway'] = "gateway/index";
$route['error/(:any)'] = "gateway/OnError/$1";
$route['error'] = "gateway/OnError";

$route['photoexplore'] 				= "PhotoExplore/Index";
$route['photo/(:num)'] 				= "Photo/Index/$1";
$route['photo/addtag'] = "Photo/AddTag";
$route['photo/deletetag'] = "Photo/DeleteTag";
$route['photo/uploadphoto'] = "Upload/UploadPhoto";
$route['photo/saveuploadedphoto'] = "Photo/SaveUploadedPhoto";
$route['photo/postcomment'] = "Photo/PostComment";
$route['photo/deletecomment'] = "Photo/DeleteComment";

$route['artist/isavailableartistdname']			= "gateway/IsAvailableArtistDname";

$route['artist/bookmarks/(:any)']		= "Artist/Bookmarks/$1";
$route['artist/bookmarks']			= "Artist/Bookmarks";

$route['artist/deletephoto']			= "Artist/DeletePhoto";

$route['artist/unbookmarkphoto']		= "Artist/UnBookmarkPhoto";
$route['artist/bookmarkphoto']		= "Artist/BookmarkPhoto";

$route['artist/unbookmarkartist']		= "Artist/UnBookmarkArtist";
$route['artist/bookmarkartist']			= "Artist/BookmarkArtist";

$route['artist/modalgallery/(:any)']	= "Artist/ModalGallery/$1";
$route['artist/editartistprofileavatar'] = "Artist/EditArtistProfileAvatar";

$route['artist/show_edit_focus']		= "Artist/ShowEditFocus";
$route['artist/edit_focus']			= "Artist/EditFocus";
$route['artist/edit_location']			= "Artist/EditLocation";
$route['artist/edit_about_me']		= "Artist/EditAboutMe";
$route['artist/addshoebox']			= "Artist/AddShoebox";
$route['artist/deleteshoebox']		= "Artist/DeleteShoebox";
$route['artist/postcomment']		= "Artist/PostComment";
$route['artist/deletecomment']		= "Artist/DeleteComment";
$route['artist/(:any)/gallery/(:any)']	= "Artist/Gallery/$1/$2";
$route['artist/(:any)/gallery']			= "Artist/Gallery/$1";
$route['artist/(:any)/shoeboxexplore']	= "Artist/ShoeboxExplore/$1";
$route['artist/shoebox/create']		= "Artist/CreateShoebox";
$route['artist/shoebox/preview']		= "Artist/ShoeboxPreview";
$route['artist/shoebox/photopreview']	= "Artist/ShoeboxPhotoPreview";
//$route['artist/(:any)/shoebox/(:num)']	= "Artist/Shoebox/$1/$2";
$route['shoebox/(:num)']			= "Artist/Shoebox/$1";
$route['artist/(:any)/stats'] 			= "Artist/Stats/$1";
$route['photo/uploader']			= "Photo/Upload";
$route['artist/createprofile/(:num)']			= "gateway/CreateProfile/$1";
$route['preferences'] = "Artist/Preferences/$1";
$route['artist/(:any)'] 				= "Artist/Index/$1";
$route['artist/getpermittedphotocount'] 				= "Artist/GetPermittedPhotoCount";
$route['artist/edit_shoebox_name']	=	"Artist/EditShoeboxName";
$route['artist/upload_artist_avatar'] = "Upload/UploadArtistAvatar";
$route['artist/validate_password'] = "Artist/ValidateCurrentPassword";
$route['artist/savepreferences'] = "Artist/SavePreferences";

$route['artist/is_logged_in']			= "gateway/IsLoggedIn";
$route['artist/submitrequest'] 	= "gateway/SubmitInviteRequest";
$route['artist/requestinvite']			= "gateway/RequestInvite";
$route['artist/showlogin']			= "gateway/ShowLogin";
$route['artist/login']				= "gateway/Login";
$route['artist/logout']				= "gateway/Logout";

$route['artist/add_artist']			= "gateway/AddArtist";

$route['community'] 	= "Community";
$route['community/post/(:num)']	= "Community/Discussion/$1/";
//$route['community/post/(:num)/(:any)']	= "Community/Discussion/$1/".DISCUSSION_SECTION_DEFAULT."/$2";
$route['community/post/create'] 	= "Community/CreateDiscussion";
$route['community/discussion/add'] 		= "Community/AddDiscussion";
$route['community/discussion/create'] 	= "Community/CreateDiscussion";
$route['community/adddiscussionbookmark'] 		= "Community/AddDiscussionBookmark";
$route['community/removediscussionbookmark'] 		= "Community/RemoveDiscussionBookmark";
$route['community/addtag']	=	"Community/AddTag";
$route['community/deletetag'] = "Community/DeleteTag";
$route['community/deletereview'] = "Community/DeleteReview";
$route['community/edit_discussion_body']	=	"Community/EditDiscussionBody";
$route['community/edit_discussion_title']   =   "Community/EditDiscussionTitle";
$route['community/deletepost']   =   "Community/DeleteDiscussion";

$route['community/upload_discussion_image'] 	=	"Upload/UploadDiscussionImage";

$route['groupexplore']			= "GroupExplore";
$route['group/add']			= "Group/Add";
$route['group/create']			= "Group/Create";
$route['group/addphoto/(:num)']			= "Group/AddPhoto/$1";
$route['group/deletephoto/(:num)']			= "Group/DeletePhoto/$1";
$route['group/addremovephotos/(:num)']		= "Group/AddRemovePhotos/$1";
$route['group/discussion/create/(:num)']	= "Group/CreateDiscussion/$1";
$route['group/discussion/add'] 		= "Group/AddDiscussion";
$route['group/edit_description']	= "Group/EditDescription";
$route['group/addtag']	=	"Group/AddTag";
$route['group/deletetag'] = "Group/DeleteTag";
$route['group/group_upload_image'] = "Upload/UploadGroupImage";
$route['group/upload_group_discussion_image'] = "Upload/UploadGroupDiscussionImage";
$route['group/deletepost/(:num)']			= "Group/DeleteDiscussion/$1"; 

$route['group/postdiscussioncomment'] 		= "Group/PostDiscussionComment";
$route['group/deletediscussioncomment'] 		= "Group/DeleteDiscussionComment";

$route['community/postdiscussioncomment'] = "Community/PostDiscussionComment";
$route['community/deletediscussioncomment'] = "Community/DeleteDiscussionComment";
$route['community/reviewrequest/(:num)'] = "Community/Discussion/$1/".DISCUSSION_SECTION_REVIEW_REQUEST;
$route['community/reviewrequest/add'] 		= "Community/AddReviewRequest";
$route['community/addreview'] = "Community/AddReview";
$route['community/createreviewrequest'] = "Community/CreateReviewRequest";
$route['community/deletereviewrequest'] = "Community/DeleteReviewRequest";


$route['group/(:num)/join']			= "Group/Join/$1";
$route['group/(:num)/leave']			= "Group/Leave/$1";

//$route['group/(:num)/discussion/create']	= "Group/CreateDiscussion/$1";
$route['group/(:num)/post/(:num)']	= "Group/Discussion/$1/$2";
$route['group/(:num)/posts']		= "Group/DiscussionExplore/$1";
$route['group/(:num)/gallery/(:any)']		= "Group/Gallery/$1/$2";
$route['group/(:num)/gallery']			= "Group/Gallery/$1";
$route['group/(:num)'] 				= "Group/Index/$1";

$route['community/(:num)/(:num)'] 		= "Community/index/$1/$2";
$route['community/(:num)'] 			= "Community/index/$1";



$route['meta/about']			= "Meta/About";
$route['meta/termsofservice']	= "Meta/TermsOfService";
$route['meta/privacypolicy']		= "Meta/PrivacyPolicy";
$route['meta/faq']		= "Meta/FAQ";
$route['meta/contact']			= "Meta/Contact";


/* End of file routes.php */
/* Location: ./system/application/config/routes.php */
 