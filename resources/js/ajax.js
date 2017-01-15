function Ajax_AddPhotoRating(pid)
{
	 var ratings = new Array();
	 for(i=1;i<=3;++i)
	 {
	 	metric = '#metric' + i;
	 	ratings.push($(metric).val());
	 }
	 
	 var ratings_json=$.toJSON(ratings);
	 	 
	$('#dashbox_photo').fadeOut("slow");
	$('#dashbox_photo').load
	(
		"/photo/addrating #dashbox_photo", 
		{
			ratings	:	ratings_json,
			pid		:	pid
		},		
		
		function(html)
		{
			$('#dashbox_photo').fadeIn("slow");
			BindDashboxActions();
		}
	);
	
}

/*
 * This is to delete review request from the community explore page
 */
function Ajax_DeleteReviewRequest(disc_id)
{
	$.ajax({
		type: "POST",
		
   		url: "/community/deletereviewrequest",
		
		 dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one
		
   		data: ({
				entity:disc_id
			}),
				
		success: 	function(response)
		{
				if (response.RETURN_CODE == SUCCESS) 
				{
					//alert('[DEBUG] Return code: '+response.RETURN_CODE);
					div = '#' + disc_id;
					$(div).slideToggle("slow");
				}
				else
				if (response.RETURN_CODE == ERROR_UNKNOWN) 
				{
					alert('[ERROR] Unable to delete review request.Please try again.');
				}
		}
 	});
 			
}

/*
 * Generic delete function for shoebox, photo and discussion
 * This function is called from within the explore pages of each of these entities 
 * and from within the individual entity page itself
 */
function Ajax_DeleteEntity(entity_id,delete_entity_url,delete_redirect_url)
{
	$.ajax({
		type: "POST",
		
   		url: delete_entity_url,
		
		dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one
		
   		data: ({
				entity_id:entity_id
			}),
				
		success: 	function(response)
		{
				if (response.RETURN_CODE == SUCCESS) 
				{
					if( typeof delete_redirect_url == 'undefined' )
					{
						div = '#' + entity_id;
						$(div).slideToggle("slow");
					}
					else
					{
						top.location.href = delete_redirect_url;
					}
				}
				else
				if (response.RETURN_CODE == ERROR_UNKNOWN) 
				{
					alert('[ERROR] Unable to delete entity.Please try again.');
				}				
		}
 	});	
}

function Ajax_AddGroupPhoto(gid,pid)
{
	url = "/group/addphoto/"+gid;

	$.ajax({
		type: "POST",
		
   		url: url,
		
   		data: ({
				pid:pid,
			}),
				
		success: 	function(result)
				{
     				
					
   				}
 	});	
}

function Ajax_RemoveGroupPhoto(gid,pid)
{
	url = "/group/removephoto/"+gid;

	$.ajax({
		type: "POST",
		
   		url: url,
		
   		data: ({
				pid:pid,
			}),
				
		success: 	function(result)
				{
     				
					
   				}
 	});	
}


function Ajax_LoadInterestedGroups(group_name)
{
	var is_hidden = ($('.interested_entities').is(':hidden'));
	//alert(is_hidden);
	
	if (is_hidden === false) 
	{
	  	$('.interested_entities').slideToggle(100);
		//$('.interested_entities').attr('display','hidden');
		//$('.interested_entities').hide();
		is_hidden = true;
	}
	
	$('#form_group_create .interested_entities').load
	(
		"/group/create #form_group_create .interested_entities", 
		{
			group_name:group_name
		},
		
		function(src)
		{
			//while(is_hidden===false);
			if (is_hidden === true) 
			{
				is_hidden = false;
			 	$('.interested_entities').slideToggle("fast");
			}
		}
	);
}

function Ajax_AddShoebox()
{ 
	//First determine if the user is allowed to add any more shoeboxes
	//alert('In Ajax_AddShoebox');
	$.ajax({
   			type: "POST",
			
   			url: "/artist/get_permitted_shoeboxcount", 
				
   			data: ({
	
				}),
				
			dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one				
				
			success: 	function(response)
					{
						//alert('Before success '+response.RETURN_CODE);
						if (response.RETURN_CODE === SUCCESS_WITH_MESSAGE) 
						{
							//alert('After success');
							var PermittedShoeboxCount = response.RETURN_MESSAGE;
							if (PermittedShoeboxCount === 0)
							{
								alert('You have reached your quota of '+ 5 +' shoeboxes.');									
								return;
							}
							else 
							{
								//alert(url);
								Ajax_AddShoebox_OnCallback();
							}
						}
						else
						{
							alert('Unable to add shoebox. Please try again.');
							return;							
						}
   					}
 		});	
}

function Ajax_AddShoebox_OnCallback()
{
	//alert('In Ajax_AddShoebox_OnCallback');	
	var shoebox_name = $('#input_shoebox_name').val();
	
	var photo_keywords = $('.tagsL').attr('value');
	if(photo_keywords=="filter by tags and names")
	{
		photo_keywords="All";	
	}	
	
	var photo_type		= $('.type span').text();
	var fstop 			= $('#fstopVal').text();
	var shutter 			= $('#shutterVal').text();
	var iso 			= $('#isoVal').text();
	var focal 			= $('#focalVal').text();	

	//alert('[DEBUG] Shoebox:'+shoebox_name);
	if( shoebox_name == '' || shoebox_name == 'enter shoebox name' )
	{
		return;
	}
	if( fstop == 'All' && shutter == 'All' && iso == 'All' && focal == 'All' && photo_type == 'genre'  && photo_keywords == 'All')
	{
		return;
	}
	
	if( focal != '' && focal != 'All' )
	{
		alert('[BETA] Creating a shoebox with a focal-length range filter is not yet supported.');
		return;
	}
	
	ShowPreloader();
	
	$.ajax({
   			type: "POST",
			
   			url: "/artist/addshoebox", 
			
   			data: ({
					shoebox_name:shoebox_name,
					filter_photo_keywords:photo_keywords,
					photo_type			:photo_type,
					photo_exif_aperture	:fstop,
					photo_exif_shutter	:shutter,
					photo_exif_iso		:iso,
					photo_exif_focal		:focal		
				}),
				
			dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one					
				
			success: 	function(response)
					{
						//alert(' response '+response.RETURN_CODE);
						if (response.RETURN_CODE == SUCCESS_WITH_MESSAGE) 
						{
							url = '/shoebox/' + response.RETURN_MESSAGE;
							//alert(url);
							top.location.href = url;
						}
						else
						if( response.RETURN_CODE == ERROR_MAX_SHOEBOX_COUNT )						
						{
							HidePreloader();
							alert('You have reached your quota of '+ 5 +' shoeboxes.');
							return;	
						}
						else
						{
							alert('[ERROR] Unable to add shoebox.Please try again.');
						}
						
   					}
 		});		
}


function search() 
{
	
  if (event.keyCode == 13) {
 	Reload();
  } 

}

function Ajax_Reload(url,div,page,filter_prompt)
{
	//alert('div '+div);
	var keywords = $('#section_search_keywords').val();
	
	//this is the reset case
	//if (keywords == 'filter by tags and names') 
	if (filter_prompt != 'undefined') 
	{
		if (keywords == filter_prompt) {
			keywords = 'All';
		}
	}
		
	ShowPreloader();
		
	$(div).load
	(
		url, 
		{
			page	:  page	,
			filter_keywords	:keywords
		},
		
		function(src)
		{
			//alert(src);
			BindDeletes();
			$.scrollTo('body',INTERVAL_RELOAD_SCROLL);
			HidePreloader();
		}
	); 
}

function Ajax_ReloadComments(url,page)
{
	var div = '#section_comments .comments';
	url = url + ' ' + div;
	
	ShowPreloader(div);
	
	$(div).load
	(
		url, 
		{
			page	:  page
		},
		
		function(src)
		{
			HidePreloader(div);
			$.scrollTo('#section_comments',INTERVAL_RELOAD_SCROLL);
			BindDeletes();
		}
	); 
}

function Ajax_ReloadReviews(url,page)
{
	var div = '#reviews';
	url = url + ' ' + div;
	
	ShowPreloader(div);
	
	$(div).load
	(
		url, 
		{
			page	:  page
		},
		
		function(src)
		{
			HidePreloader(div);
			$.scrollTo('#reviews',INTERVAL_RELOAD_SCROLL);
			BindDeletes();
		}
	); 
}


function Ajax_DeleteTag(id,new_tag,url,parentId)
{
	//alert('DeleteTag::'+id+','+new_tag+','+url+','+parentId);
	//ShowPreloader('cdashbox_tags');
	
	$.ajax({
		type: "POST",
			
		url: url,
			
		dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one
		data: ({
			tag	:new_tag,
			id	: id
		}),

		success: 	function(response)
		{
			if( response.RETURN_CODE == SUCCESS )
			{
				//$('.tags_input').attr('value', '');
				//HidePreloader();
				RemoveTagFromPage(parentId);
			}
			else
			{
				alert('[ERROR] Unable to delete tag.Please try again.');
			}			
		}
 	});	
	
	//RemoveTagFromPage(parentId);			
}

function Ajax_AddTag(id,new_tag,url,deleteurl)
{
	//alert(id+','+new_tag+','+url+','+deleteurl);
	//ShowPreloader('cdashbox_tags');
	
	if(!ValidateTags(new_tag))
	{
		return;
	}	
	
	//Truncate the tags at MAX_TAG_LENGTH
	var truncated_tag = new_tag.substring(0, Math.min(MAX_TAG_LENGTH,new_tag.length));

	$.ajax({
		type: "POST",
			
		url: url,
			
		dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one
		data: ({
			tag	:truncated_tag,
			id	: id
		}),

		success: 	function(response)
		{
			if( response.RETURN_CODE == SUCCESS )
			{
				//alert(new_tag);	
				$('.tags_input').attr('value', '');
				//HidePreloader();
				ShowAddedTag(id,truncated_tag,deleteurl);
			}
			else
			{
				alert('[ERROR] Unable to add tag.Please try again.');
			}			
		}
 	});				
}

function AddEntityBookmarkCallback(bookmark_action_object)
{
	bookmark_action_object.addClass('bookmarked');
}

function RemoveEntityBookmarkCallback(bookmark_action_object)
{
	bookmark_action_object.removeClass('bookmarked');
}

var OnBookmarkLocalAction = function(bookmark_entity_id,add_bookmark_url,remove_bookmark_url,bookmark_action_object)
{
	if(bookmark_action_object.hasClass('bookmarked'))
	{
		//RemoveEntityBookmark(bookmark_entity_id,remove_bookmark_url);
		//ProcessBookMarkViaAjax(remove_bookmark_url, bookmark_entity_id, RemoveEntityBookmarkCallback(bookmark_action_object));
		RemoveEntityBookmarkCallback(bookmark_action_object);
		ProcessBookMarkViaAjax(remove_bookmark_url, bookmark_entity_id);
	}
	else
	{
		//AddEntityBookmark(bookmark_entity_id,add_bookmark_url);
		//ProcessBookMarkViaAjax(add_bookmark_url, bookmark_entity_id, AddEntityBookmarkCallback(bookmark_action_object));
		AddEntityBookmarkCallback(bookmark_action_object);
		ProcessBookMarkViaAjax(add_bookmark_url, bookmark_entity_id); 
	}	
}

function AddPhotoBookmark(pid)
{
	ProcessBookMarkViaAjax("/artist/bookmarkphoto", pid );
}

function DeletePhotoBookmark(pid)
{
	ProcessBookMarkViaAjax("/artist/unbookmarkphoto", pid);
}

function ProcessBookMarkViaAjax(url,id,callback)
{
	//alert('here2');
	$.ajax({
			type: "POST",
			
			url: url ,
			
			data: ({
					id: id
				}),
				
			dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one

			success: 	function(response)
			{
				if(response.RETURN_CODE == SUCCESS )
				{
					if (typeof callback == 'function') 
					{
						callback();
					}
				}
			}				
				
	});
};


function AddArtistBookmark(artist_dname)
{
//	alert(pid);
	$('#none').load
	(
		"/artist/bookmarkartist", 
		{
			artist_dname			:artist_dname
		}
	);

};

function DeleteArtistBookmark(artist_dname)
{
//	alert(pid);
	$('#none').load
	(
		"/artist/unbookmarkartist", 
		{
			artist_dname			:artist_dname
		}
	);

};

function AddReview(Discussion_Id,ImpactMetric,BalanceMetric,TechniqueMetric,
					ImpactComment,BalanceComment,TechniqueComment,
					GeneralComment,CropCoordinates)
{
	//alert('here');
	//var div = '#review_rating_summary';
	//var url = '/community/addreview'+ ' ' + div; 
	
	var CropJsonString = $.toJSON(CropCoordinates);
	//alert('[DEBUG] '+CropJsonString);
	
	 $.ajax({
   			type: "POST",
			
   			url: '/community/addreview',
			
			dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one
							
   			data: ({
				discussion_id : Discussion_Id,
				review_metric_impact_rating: ImpactMetric,
				review_metric_balance_rating: BalanceMetric,
				review_metric_technique_rating: TechniqueMetric,
				review_metric_impact_comment_text: ImpactComment,
				review_metric_balance_comment_text: BalanceComment,
				review_metric_technique_comment_text: TechniqueComment,
				review_comment_text: GeneralComment,
				review_crop:CropJsonString
				}),
				
			success: 	function(response)
			{
				//alert(response);
				if(response.RETURN_CODE == SUCCESS )
				{
					//alert(response.RETURN_MESSAGE);					
					top.location.href = response.RETURN_MESSAGE;
				}
				else
				{
					alert('[ERROR] Unable to add review for photo.Please try again');
				}				
						
			}
 		});		

}

function AddComment(url)
{
	if(CommentInputExpanded==false)
	{
		ExpandCommentsInput();
		return;
	}
	
	var div = '#section_comments .comments';
	var url = url + ' ' + div; 
	
	if($('#GroupDiscussionCommentInput').length > 0) 
	{
		var postdata = 
		{
			entity : $('#entity').val(),
			gid : $('#gid').val(),
			comment_text : $('#comment_text').val()
		};	
	}
	else
	{
		var postdata = 
		{
			entity : $('#entity').val(),
			comment_text : $('#comment_text').val()
		};
	}
	
	if ($('#comment_text').val() != "") 
	{
		TransitionCommentsInputToSave();		
		
		$(div).load(
		url, 
		postdata,
		function(src)
		{
			HidePreloader();
			CollapseCommentsInput();
			BindDeletes();
			
			if( $('#quantifier_comments').length >0 )
			{
				var currentCommentCount = RemoveBrackets( $('#quantifier_comments').text() );
				currentCommentCount++;
				//alert('[DEBUG] tag count '+currentTagCount);
				$('#quantifier_comments').text('('+currentCommentCount+')');				
			}
		});
				
	}

};

var ExpandCommentsInput = function()
{
	if (CommentInputExpanded == false) 
	{
		$('.mini_pagination').hide();
		$('.comment_input .expand .save_indicator_ajax').hide();
		$('#section_comments span.title').hide();
		$('.comment_input .add').addClass('button_save_comment');
		$('.comment_input .expand .cancel').fadeIn();
		$('.comment_input .expand .comment_textbox').slideToggle();
		$('#section_comments .add').show();
		
				
		CommentInputExpanded = true;
	}
	
	return false;
}

var TransitionCommentsInputToSave = function()
{
	$('.comment_input .expand .cancel').hide();
	$('.comment_input .expand .save_indicator_ajax').show();
}

var CollapseCommentsInput = function()
{
	
	if (CommentInputExpanded == true) 
	{
	 	$('#comment_text').attr({value: ''});
		$('.comment_input .expand .comment_textbox').slideToggle();
		$('.comment_input .expand .cancel').fadeOut();
		$('.comment_input .expand .save_indicator_ajax').hide();
		$('.comment_input .add').removeClass('button_save_comment');
		$('#section_comments span.title').show();
		$('.mini_pagination').fadeIn('slow');
		  	
		CommentInputExpanded = false;
	}
		  
	return false;	
}

function CreateReviewRequest(photo_id)
{
	 ShowPreloader();
	 
	 $.ajax({
   			type: "POST",
			
   			url: '/community/createreviewrequest',
			
			dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one
							
   			data: ({
					photo_id	:photo_id
				}),
				
			success: 	function(response)
			{
				//alert(response);
				if(response.RETURN_CODE == SUCCESS )
				{
					//alert(response.RETURN_MESSAGE);					
					top.location.href = response.RETURN_MESSAGE;
				}
				else
				{
					alert('[ERROR] Unable to create review request.Please try again');
				}				
						
			}
 		});		
	
}

function DeleteComment(url,comment_id)
{
	 $.ajax({
   			type: "POST",
			
   			url: url,
			
			dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one			
			
   			data: ({
					entity: $('#entity').val(),
					comment_id: comment_id
				}),
				
			success: 	function(response)
					{
						//alert('foobar');
     					if (response.RETURN_CODE == SUCCESS) 
						{
							//alert('[DEBUG] Success');	
							CompleteDelete(comment_id);
							
							if ($('#quantifier_comments').length > 0) {
								var currentCommentCount = RemoveBrackets($('#quantifier_comments').text());
								currentCommentCount--;
								//alert('[DEBUG] tag count '+currentTagCount);
								$('#quantifier_comments').text('(' + currentCommentCount + ')');
							}
						}
						else
						{
							alert('[ERROR] Unable to delete comment.Please try again');
						}							
						
   					}
 		});	
	
}


function ReloadGallery(url,div,page)
{
	var gallery_type=-1;
	
	if($('#PhotoExploreGallery').length > 0) 	{ gallery_type=0 }
	if($('#ArtistPhotoGallery').length > 0) 	{ gallery_type=1 }
	if($('#GroupPhotoGallery').length > 0) 	{ gallery_type=2 }
	if($('#ShoeboxPhotoGallery').length > 0) 	{ gallery_type=3 }
	
//	alert(gallery_type);

	switch(gallery_type)
	{
		case 0:
				// PHOTO EXPLORE
				ReloadPhotoGallery('/photoexplore .ReloadGallery', '.ReloadGalleryContainer',page);
				break;
				
		case 1:
				// ARTIST PHOTO GALLERY
				var artist = $("#entity_id").val();
				//alert('[DEBUG]'+artist);
				var url = '/artist/' + artist + '/gallery/' + ' .ReloadGallery'; 
				ReloadPhotoGallery(url, '.ReloadGalleryContainer',page);
				break;
		
		case 2:		
				// GROUP PHOTO GALLERY
				var group = $("#entity_id").val();
				var url = '/group/' + group + '/gallery/' + ' .ReloadGallery'; 
				ReloadPhotoGallery(url, '.ReloadGalleryContainer',page);		
				break;
				
		case 3:		
				// SHOEBOX PHOTO GALLERY
				var shoebox = $("#entity_id").val();
				var url = '/shoebox/' + shoebox  + ' .ReloadGallery'; 
				ReloadPhotoGallery(url, '.ReloadGalleryContainer',page);				
				break;
	}	
}

function ReloadPhotoGallery(url,div,page)
{
	var photo_keywords = $('.tagsL').attr('value');
	if(photo_keywords=="filter by tags and names")
	{
		photo_keywords="All";	
	}
	
	if(typeof page != 'number') 
	{
	  	page = '1';
	}
		
	//alert(div);
		
	var photo_type		= $('.type span').text();
	var fstop 			= $('#fstopVal').text();
	var shutter 			= $('#shutterVal').text();
	var iso 			= $('#isoVal').text();
	var focal 			= $('#focalVal').text();
	
	
	ShowPreloader();
	//alert(' photo_keywords: ' + photo_keywords +' photo_type: '+photo_type+',fstop: '+fstop+',shutter: '+shutter+',iso: '+iso+',focal: '+focal);
	
	$(div).load
	(
		url, 
		{
			page				:page,
			filter_photo_keywords	:photo_keywords,
			photo_type		:photo_type,
			photo_exif_aperture	:fstop,
			photo_exif_shutter	:shutter,
			photo_exif_iso		:iso,
			photo_exif_focal		:focal
		},
		
		function(src)
		{
			//alert(src);
			//alert('calling bind bookmarks');
			BindBookmarks();
			BindDeletes();
			//BindPhotoSnapshotEffects();	
			$.scrollTo('body',INTERVAL_RELOAD_SCROLL);
			HidePreloader();
		}
	); 

};


function Ajax_AddDiscussion(discussion_title,discussion_body,discussion_tags,external_image_attachments,internal_image_attachments,base_url,discussion_entity) 
{
			ShowPreloader();	
		
			url = base_url+"/add";
			//alert('[DEBUG] adddiscussion '+url+', '+discussion_entity);
			
			$.ajax({
				type: "POST",
					
				url: url,
					
				dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one
				data: ({
					discussion_title	:discussion_title,
					discussion_body	:discussion_body,
					discussion_tags:discussion_tags,
					discussion_entity:  discussion_entity,
					disc_image_attachments_external	:external_image_attachments,
					disc_image_attachments_internal	:internal_image_attachments,
					
				}),
	
				success: 	function(response)
				{
					//alert('[DEBUG] here '+response);
					if(response.RETURN_CODE == SUCCESS )
					{
						//url = '/community/discussion/'+response.RETURN_MESSAGE;
						//url = base_url + '/' + response.RETURN_MESSAGE;
						top.location.href = response.RETURN_MESSAGE;	
					}
					else
					{
						alert('[ERROR] Unable to create discussion.Please try again');
					}						

				}
		 	});					
	
}


function GetPermittedPhotoCount()
{
	$.ajax({
		type: "POST",
			
		url: '/artist/getpermittedphotocount',
			
		dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one
		data: ({
		
		}),

		success: 	function(response)
		{
			//alert('[DEBUG] here '+response);
			if(response.RETURN_CODE == SUCCESS )
			{
				alert(response.RETURN_MESSAGE);
				return false;	
			}

		}
 	});	
	
	return false;	
}


function ValidateTags(tag)
{
	/* Validate the tags */
	if( DetectInvalidCharactersInTags(tag) )
	{
		alert(INVALID_TAG_CHARACTERS_MESSAGE);
		return false;
	}
	else
	{
		//alert('[DEBUG] No invalid char found in tags');
		return true;
	}
}

function SaveUploadedPhotoSync()
{
	/* Validate the global tags */
	if(!ValidateTags($('#global_tags').attr('value')))
	{
		return;
	}
	
	var valid_local_tag = true;
	/* Validate the local tags */
	$('.local_tags').each( function(){
		
		//alert('In local_tags check ');
		if(  !ValidateTags( $(this).attr('value') ) )
		{
			valid_local_tag = false;
		}
	});
	
	if( !valid_local_tag )
	{
		return;	
	}

	var SelectedUploadCount = 0;
	var callbackCount = 0;
	var SelectedUploadCount = $('.imageToUpload').size();	
	ShowPreloader();
	
	//Check if this user is permitted to upload the photos he has selected
	$.ajax({
		type: "POST",
			
		url: '/artist/getpermittedphotocount',
			
		dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one
		data: ({
		
		}),

		success: 	function(response)
		{
			//alert('[DEBUG] here '+response);
			if(response.RETURN_CODE == SUCCESS_WITH_MESSAGE )
			{
				var PermittedPhotoCount = response.RETURN_MESSAGE;
				//alert('[DEBUG] PermittedPhotoCount: '+PermittedPhotoCount);
				
				var AllowedUploads = (PermittedPhotoCount - SelectedUploadCount );
				//If (PermittedPhotoCount - SelectedUploadCount ) >= 0 then
				//it means that the user is allowed to upload all the photos he has selected 
				if( AllowedUploads >= 0 )
				{
						//show the save all progress bar
						$('#upload_save_all').fadeOut( function()
						{
							$('#upload_saving_all').fadeIn();
						});

						$('.imageToUpload').each( function() {
							
							UploadPhotoArray.push($(this));
							
						});
						
						UploadPhotosSynchronously(0);						

				}
				else
				{
					//Alert that the user has too many pictures selected  
					alert('[DEBUG] You are attempting to add '+ SelectedUploadCount +' photos against your remaining quota of '
										+ PermittedPhotoCount +'. Please try uploading fewer photos');	
					HidePreloader();
					return false;
				}
					
			}//end of RESPONSE_CODE SUCCESS

		}//end of success
		
 	});//end of $.ajax
}

var UploadPhotoArray = new Array();
var UploadIndex = 0;

function CombineTags(localTags,globalTags)
{
	var photoTags = "";
	if(localTags == "enter tags")
	{
		localTags = '';
	}
					
	if( globalTags != "" && globalTags != UPLOAD_PHOTOS_GLOBAL_TAGS_INPUT_PROMPT )
	{
		photoTags = globalTags;
	}
	
	if( localTags != "" )
	{
		photoTags = photoTags+ TAGS_DELIMITER +localTags;
	}
	
	return photoTags;	
}

function UploadPhotosSynchronously(callbackCount)
{
	var $Photo = UploadPhotoArray[UploadIndex++];
	
	if( $Photo == 'undefined' )
	{
		return;
	}
	
	//this is the id of the img tag
	var imageName = $Photo.attr('src');
	var id = $Photo.attr('id');
				
	//obtain the local tags value
	var tagId = getQueueIdFromPhotoPreviewId(id);
	var localTags = $('#'+tagId+' .local_tags').attr('value');
	var globalTags = $('#global_tags').attr('value');
	var photoTags = CombineTags(localTags,globalTags);
		
	var photoFilename = getOriginalImageName(imageName);
	//alert(photoFilename);	
	var photoTypeName = $('#upload_photo_type .selected').text();
	var SelectedUploadCount = $('.imageToUpload').size();	

	$.ajax ({
		type: "POST",
			
		url: '/photo/saveuploadedphoto',
			
		dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one
		data: ({
			PHOTO_ID	:	photoFilename,
			photo_type_name	:	photoTypeName,
			photo_tags	: photoTags							
		}),	
		
		success: function(response)
		{
			if( response.RETURN_CODE == SUCCESS_WITH_MESSAGE )
			{
				//this should be /artist/'name'/gallery					
				url = '/artist/' + response.RETURN_MESSAGE + '/gallery';
				//alert(url);
					
				++callbackCount;
				
				var PercentDone = Math.ceil((callbackCount/SelectedUploadCount)*100);

				$('#upload_saving_all .progress_fill').css('width', PercentDone + '%')
					
				if( callbackCount == SelectedUploadCount )
				{
					//HidePreloader();
					top.location.href = url;
				}
				else
				{
					UploadPhotosSynchronously(callbackCount);
				}
			}
		}						
		
		
	});//end of ajax
}

function Ajax_SendArtistInvite(artist_email,artist_work)
{
	//alert('in ajax');
	$.ajax ({
		type: "POST",
			
		url: '/artist/submit_invite',
			
		dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one
		data: ({
			artist_email	:	artist_email,
			artist_work	:	artist_work					
		}),
		
		success: function(response)
		{
			if( response.RETURN_CODE == SUCCESS_WITH_MESSAGE )
			{
				alert(response.RETURN_MESSAGE);
			}
		}
	});
}

function Ajax_SavePhotoGenre(photo_id,genre)
{
	//alert('savegenre');
	
	$.ajax ({
		type: "POST",
			
		url: '/photo/savegenre',
			
		dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one
		data: ({
			id	:	photo_id,
			genre: genre
		}),
		
		success: function(response)
		{
			//alert('[DEBUG] In Ajax_SavePhotoGenre return');			
			if(response.RETURN_CODE == SUCCESS)
			{
				
			}

		}
	});		
}

function Ajax_EditPhotoName(photo_id,photo_name)
{
	var photo_title = '';
	if( photo_name == 'undefined' || photo_name == null )
	{
		photo_title = '';
	} 
	else
	{
		photo_title = photo_name;
	}
	$.ajax ({
		type: "POST",
			
		url: '/photo/editname',
			
		dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one
		data: ({
			pid	:	photo_id,
			photo_name: photo_title
		}),
		
		success: function(response)
		{
			if(response.RETURN_CODE == SUCCESS)
			{
				
			}
			else
			{
				alert('[ERROR] Unable to edit photo name.Please try again');
			}			

		}
	});		
}

function Ajax_EditPhotoDescription(photo_id,photo_desc)
{
	var photo_description = '';
	if( photo_desc == 'undefined' || photo_desc == null )
	{
		photo_description = '';
	}	
	else
	{
		photo_description = photo_desc;
	}
	$.ajax ({
		type: "POST",
			
		url: '/photo/editdescription',
			
		dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one
		data: ({
			pid	:	photo_id,
			photo_desc: photo_description
		}),
		
		success: function(response)
		{
			if(response.RETURN_CODE == SUCCESS)
			{
				
			}
			else
			{
				alert('[ERROR] Unable to edit photo description.Please try again');
			}			

		}
	});		
}

/*
 * Validate the artist current password and invoke callback
 */
function Ajax_ValidateArtistCurrentPassword(current_passsword_sha1,callback)
{
		$.ajax({
		type: "POST",
		
		url: '/artist/validate_password',
		
		dataType: 'json', //Important: This is used if the return array from php is a json-encoded one
		data: ({
			artist_password_sha1: current_passsword_sha1
		}),
		
		success: function(response){
			//alert('[DEBUG] In Ajax_ValidateArtistCurrentPassword :'+response.RETURN_CODE);			
			if( typeof callback == 'function' )
			{
				callback(response.RETURN_CODE);
			}
		}
	});
}


function Ajax_SaveNewPassword(artist_password_sha1,artist_new_password_sha1,callback)
{
		$.ajax({
		type: "POST",
		
		url: '/artist/savepreferences',
		
		dataType: 'json', //Important: This is used if the return array from php is a json-encoded one
		data: ({
			artist_password_sha1: artist_password_sha1,
			artist_new_password_sha1: artist_new_password_sha1,			
		}),
		
		success: function(response){
			//alert('[DEBUG] In Ajax_ValidateArtistCurrentPassword :'+response.RETURN_CODE);			
			if( typeof callback == 'function' )
			{
				callback(response.RETURN_CODE);
			}
		}
	});
}

/*
 * Generic function that takes in a preference type and a value.
 * The controller will accordingly process the preference value depending on the type. 
 */
function Ajax_SavePreferenceChoice(type,value,callback)
{
	//alert('[DEBUG] In Ajax_SavePreferenceChoice '+value[0]);
	
	var jsonValue = $.toJSON(value);
	$.ajax({
		type: "POST", 
		
		url: '/artist/savepreferences',
		
		dataType: 'json', //Important: This is used if the return array from php is a json-encoded one
		data: ({
			artist_preference_type: type,
			artist_preference_value: jsonValue,
		}),
		
		success: function(response)
		{
			//alert('[DEBUG] On return');
			if( typeof callback == 'function' )
			{
				callback(response.RETURN_CODE);
			}			
		}
	});	

}
