/**
 * @author Prakash
 */

var RemoveBrackets = function(OldString)
{
  var temp = OldString.replace(/\(/, '');
  var NewString = temp.replace(/\)/, '');
  //alert('[DEBUG]'+NewString);
  return NewString;
}

var RemoveHrefAndSrc = function(OldString)
{
  var temp = OldString.replace(/href/, '');
  var NewString = temp.replace(/src/, '');
  //alert('[DEBUG]'+NewString);
  return NewString;
}

var DetectHref = function(input)
{
     if (input.search(/href=/) != -1)
	 //if (input.search(/[href=]+/) != -1)
        return true;
    else
        return false;	
}

var DetectSrc = function(input)
{
     if (input.search(/src=/) != -1)
        return true;
    else
        return false;	
}

var DetectInvalidCharactersInTags = function(tag)
{
	//alert('[DEBUG] In DetectInvalidCharacatersInTags '+tag);
	//alert('[DEBUG]  '+TAG_UNSUPPORTED_CHARACTERS);
	if (tag.search(/[:;]+/) != -1)
        return true;
    else
        return false;
}


function getOriginalImageName(completeImageName)
{
	var imageNameArray = completeImageName.split("/");
	var photo_filename = '';

	//the last element in imageNameArray will be of the form subid.photo_filename
	//split again on '.' to get the photo_filename
	//alert(imageNameArray.length);
	//alert(imageNameArray[imageNameArray.length - 1]);
	photo_filename = imageNameArray[imageNameArray.length - 1].split(".");	
	
	//this is the case where the image is like 123456.jpg
	if (photo_filename.length == 2) 
	{
		return (photo_filename[0]);
	}
	else
	if (photo_filename.length ==3) 
	{
		//the image could be of the form 54.12346.jpg
		//in which case we only want the 123456
		return (photo_filename[1]);
	}
}
 
 
function getQueueIdFromPhotoPreviewId(photoPreviewId)
{
	var queueIdArray = photoPreviewId.split("_");

return( queueIdArray[0] );	

}
 
 /* @todo - need to check if the extension is ".JPG" or ".JPEG" and only then truncate that */
 function removeJPGExtension(imageName)
 {
 	var withoutJPG = imageName.split(".");
	return (withoutJPG[0]);
 }
 
 
function isValidDisplayName(display_name)
{
     if (display_name.search(/^[A-Za-z0-9]+$/) != -1)
        return true;
    else
        return false;
}

function isValidProfileName(profile_name)
{
     if (profile_name.search(/^[\s'A-Za-z0-9]+$/) != -1)
        return true;
    else
        return false;	
}

function containsSpace(entity)
{
	if (entity.search(/^[\s]+$/) != -1) 
	{
		return true;
	}
	else 
	{
		return false;
	}
}
	

 
 