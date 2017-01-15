<?php
/**
 * Helper to upload images on group discussions and community discussions
 * 
 * Purpose
 * 
 * Created 
 * 		May 2, 2009
 * 
 * @package		Lightbox
 * @subpackage	Helpers
 * @category	uploader
 * @author		prakash
 * @link		TBD
 */
function cimage_upload($tempFileName,$tempFileSize,$destinationUploadFile)
{
	$CI = &get_instance();
	$CI->cutil->log->info('[CIMAGEUPLOADER] Uploading image '.$tempFileName);
	
	//validation,check size
	//divide by 1000 and round to next highest to get file size in kb
	$upload_image_size = ceil(($tempFileSize)/KILOBYTE);
	$CI->cutil->log->debug('[CONTROLLER] Image size '.$upload_image_size);
	if(  $upload_image_size> UPLOAD_IMAGE_MAX_SIZE )
	{
		$CI->cutil->log->debug("Image size ".$upload_image_size.
				"kb greater than allowed limit of ".UPLOAD_IMAGE_MAX_SIZE." kb");
		//echo "Image size exceeds max allowed limits";
		return false;
	}
	
	//now copy over the file to the upload area
	if (move_uploaded_file($tempFileName, $destinationUploadFile)) 
	{ 
		return true;
	}	
	return false;
}