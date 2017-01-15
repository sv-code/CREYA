$(document).ready(function() {
	
	BindGlobalActions();
	
	BindLocalActions();

	BindDock();
	
	// hide comment inputs
	CommentInputExpanded = false;
	BindCommentInputs();

	// deletables
	BindDeletes();
	
	// Initialize shadowbox with no animation
	//----------------------------------------------------------//
	Shadowbox.init({animate: false,modal:true});
	
		
});

var BindGlobalNavigation = function()
{
	$('#wrapper0').css('min-height','10px');
	$('#wrapper1').css('display','none');
	
	$('#wrapper0').hover(function()
	{
		$('#wrapper1').slideToggle('show');
	},
	function()
	{
		$('#wrapper1').slideToggle('hide');
	});
}

var BindGlobalActions = function()
{
	var hoverIntentConfig = 
	{    
     		sensitivity: 3, // number = sensitivity threshold (must be 1 or higher)    
     		interval: 40, // number = milliseconds for onMouseOver polling interval
     		    
     		over: function()
			{
				//$('#wrapper0 ul#header_navigation li.global_actions_trigger div.arrow_down').addClass('arrow_down_active');
				$('#global_actions').fadeIn();
			}, // function = onMouseOver callback (REQUIRED)
			    
     		timeout: 50, // number = milliseconds delay before onMouseOut
     		    
     		out: function()
			{
				$('#global_actions').fadeOut();
				//$('#wrapper0 ul#header_navigation li.global_actions_trigger div.arrow_down').removeClass('arrow_down_active');
			} // function = onMouseOut callback (REQUIRED)    
	};
			
	$('#wrapper0 ul#header_navigation li.global_actions_trigger').hoverIntent(hoverIntentConfig);
	
	
}

var BindLocalActions = function()
{
	var $hoverdiv = $('#filters.local_actions #dock div.delete_entity');
	var $subdock = $('#filters.local_actions #dock div.delete_entity .subdock');
	
	var hoverIntentConfig = 
	{    
     		sensitivity: 3, // number = sensitivity threshold (must be 1 or higher)    
     		interval: 0, // number = milliseconds for onMouseOver polling interval    
     		over: function() 
			{
				$hoverdiv.addClass('delete_entity_active');
				$subdock.fadeIn();
				return false;
				
			},   
     		timeout: 500, // number = milliseconds delay before onMouseOut    
     		out: function()
			{
				$subdock.fadeOut(function(){
					$hoverdiv.removeClass('delete_entity_active');
				});
				return false;
			}    
	};
		
	$hoverdiv.hoverIntent(hoverIntentConfig);	
	
	$('#filters.local_actions #dock div.delete_entity .subdock .cancel').click(function(){
		
		$subdock.fadeOut(function(){
					$hoverdiv.removeClass('delete_entity_active');
				});
				return false;
	});
}

var BindInputPrompt = function(domPath,initialValue)
{
	$(domPath).attr('value', initialValue);
	
	$(domPath).focus(function(){
		ivalue = $(this).attr('value');
		
		//alert('[DEBUG] '+domPath+','+ivalue);
		if (ivalue == initialValue) 
		{
			//$(domPath).attr('value', '');
			$(this).attr('value', '');
		}
	});
	
	//show the prompt again
	$(domPath).blur(function(){
	
		newValue = $(this).attr('value');
		if( newValue == '' )
		{	
			//$(domPath).attr('value',initialValue);
			$(this).attr('value',initialValue);
		}
	});	
}

var BindDeletes = function()
{
	/* DELETABLE */	
	//$('.deletable .delete_start').hide();
	$('.deletable .delete_confirmation').hide();
	
	$('.deletable').hover
	(
		function()
		{
			var DeleteConfimationElement = '#' + $(this).attr('id') + ' .delete_confirmation';
			if($(DeleteConfimationElement).css('display')==='none')
			{
			 	var element = '#' + $(this).attr('id') + ' .delete_start';
			  	$(element).fadeIn('fast');
			}
		},
	       	function () 
		{
	        		if($(this).hasClass('deletable_static')===false)
			{
				var element = '#' + $(this).attr('id') + ' .delete_start';
				$(element).fadeOut('fast');	
			}		
			
			
	      	}
	);
	
	$('.deletable .delete_start').click
	(
		function()
		{
			id = '#' + $(this).parent().parent().attr('id');
			$(id + ' .fade').fadeTo('fast',0.2);
			$(id + ' .delete_confirmation').fadeIn('fast');
			$(this).hide();
			
			return false;
		}
	);
	
	$('.deletable .cancel').click
	(
		function()
		{
			id = '#' + $(this).parent().parent().parent().attr('id');
			$(id + ' .fade').fadeTo('fast',1.0);
			$(id + ' .delete_start').fadeIn('fast');
			$(id + ' .delete_confirmation').hide();
			
			return false;			
		}
	);
	
	
	// DELETABLE THIS
	$('.deletable_this').hover
	(
		function()
		{
			$(this).children('a').fadeIn('fast');
		},
	       	function () 
		{
	        		$(this).children('a').fadeOut('fast');
	      	}
	);
	
	
}

function CompleteDelete(comment_id)
{
	//alert('[DEBUG] Comment_id '+comment_id);
	var id = '#' + comment_id;
	$(id).children().fadeOut('slow');
	$(id).slideToggle("slow");
	
	StartDelete = false;
}

// hide comment inputs
var BindCommentInputs = function()
{
	//CollapseCommentsInput();
	
	
	$section_comments_add = $('#section_comments .add');
	
		
	$('.comment_input .cancel').click(function()
	{
		return CollapseCommentsInput();
	});
}



function VerifySession(callback,expandInputs,callbackArgs)
{
	//alert(callback);
	
	$.ajax
	({
		type:"POST",
		
		dataType:	'json',		//Important: This is used if the return array from php is a json-encoded one
		
		url:"/artist/is_logged_in",
		
		success: function(response)
		{
			//alert(response.RETURN_CODE);
			
			if (response.RETURN_CODE == SUCCESS)
			{
				//alert('success');
				/*
				if(expandInputs!=undefined && expandInputs === true && CommentInputExpanded === false) 
				{
					ExpandCommentsInput();
				}
				else 
				*/
				
				if(typeof(callback)=='function')
				{
					//alert('[DEBUG] Callback:'+callback);
					if (callbackArgs != 'undefined') 
					{
						  callback(callbackArgs);
					}
					else 
					{
					 	callback;
					}
				}
				else
				{
					//alert('true');
					return true;
				}
			}
			else if (response.RETURN_CODE == ERROR_INVALID_SESSION)
			{
				//alert('[DEBUG] Calling login');
				login();
				return false;
			}
		}
	});
}

var BindDock = function() {
	
	var slideAmount = 0;
	
	if ($(".smalldock").length > 0) 
	{
	  	slideAmount = '-56px';
	}
	if ($(".local_actions").length > 0) 
	{
	  	slideAmount = '-26px';
	}
		
	if ($(".smalldock").length > 0 || $(".local_actions").length > 0) 
	{
		$('.filterUp').click(function()
		{
			var $this = $(this);
			var x = $('#filters').css('bottom');
			$(this).toggleClass("filterDown");
			
			x == slideAmount ? $('#filters').animate({bottom: "0"	}, "medium").toggleClass('minimized') : $('#filters').animate({bottom: slideAmount}, "medium").toggleClass('minimized');
			return false;
		});
	}
}

// Functions for opening modal windows
//----------------------------------------------------------//
function ShowPreloader(id){
	//Shadowbox.open({player: 'iframe',content: '/application/views/modal/Modal.Preloader.php',height: 60,width: 85});
	
	$('.pagination_static').hide();
	
	if (id != undefined) 
	{
	  	div = id + ' .preloader_container';
	}
	else
	{
		div = '.fade_preload .preloader_container';
	}
	
	$(div).fadeIn('fast');
}
function HidePreloader(id){
	
	if (id != undefined) 
	{
		div = id + ' .preloader_container';
	}
	else
	{
		div = '.fade_preload .preloader_container';
	}
	
	$(div).fadeOut('fast');
	
	$('.pagination_static').fadeIn('fast');
		
}
function requestInvite(){
	Shadowbox.open({player: 'iframe',content: '/artist/requestinvite',height: 345,width: 610});
};
/*
 * This function will redirect to photoexplore page
 */
function RedirectToPhotoExplore()
{
	//alert(' in  RedirectToPhotoExplore');
	top.location.href = '/photoexplore';
};
function login()
{
	//alert('[ALPHA] We are currently down for maintenance. Please check back later.');	
	Shadowbox.open({player: 'iframe',content: '/artist/showlogin',height: 239,width: 436});
};
function bookmarks(){
	Shadowbox.open({player: 'iframe',content: '/artist/bookmarks',height: 375,width: 945});
};
function editfocus(){
	Shadowbox.open({player: 'iframe',content: '/artist/show_edit_focus',height: 320,width: 580});
};
function AddRemovePhotosToGroup(gid){
	url = '/group/addremovephotos/'+gid;
	//alert('in addtogroup '+url)
	Shadowbox.open({player: 'iframe',content: url,height: 375,width: 945});
};

function ShowAddedTag(id,new_tag,deleteurl)
{
	var argument_list = id+',\''+new_tag+'\',\''+deleteurl+'\','+'$(this).parent()' ;
	var deleteElementTag = '<a href="#" onclick="VerifySession(Ajax_DeleteTag( '+argument_list+'),false);return false;"><div class="delete_tag"></div></a>';
	//var newTagDom = '<span class="tag"><div class="start"></div><div class="text"><span>'+new_tag+'</span></div>'+deleteElementTag+'</span>';
	var newTagDom = '<div class="tag background_rounded_border left"><span>'+new_tag+'</span>'+deleteElementTag+'<div class="clear"></div></div>';
	$('#tags_list').append(newTagDom);

	if( $('#quantifier_tags').length != 0 )
	{
		var currentTagCount = RemoveBrackets( $('#quantifier_tags').text() );
		currentTagCount++;
		//alert('[DEBUG] tag count '+currentTagCount);
		$('#quantifier_tags').text('('+currentTagCount+')');
	}
}

function RemoveTagFromPage(parentId)
{
	parentId.fadeOut('fast');
		
	if( $('#quantifier_tags').length != 0 )
	{
		var currentTagCount = RemoveBrackets( $('#quantifier_tags').text() );
		currentTagCount--;
		//alert('[DEBUG] tag count '+currentTagCount);
		$('#quantifier_tags').text('('+currentTagCount+')');
	}	
}



var ControlGlobalLights = function(switch_position)
{
	//alert(switch_position);
	
	$body = $('body');
	$dimmable = $('.dimmable');
	FadeTo = 1;
	
	switch(switch_position)
	{
		case SWITCH_POSITION_OFF:
		{
			$body.addClass('dim_this');
			FadeTo = 0.2;
			break;
		}
		
		case SWITCH_POSITION_ON:
		{
			$body.removeClass('dim_this');
			FadeTo = 1;
			break;
		}
		
		default:
		{
			return false;
		}
	}
	
	$dimmable.fadeTo('medium',FadeTo);
	return false;	
	
}

var CarouselElements = { };
var CurrentCarouselPosition = 0;

var InitCarousel = function (PhotoArray)
{
	CarouselElements = PhotoArray;
	CurrentCarouselPosition = 0;
}

var BindCarousel = function()
{
	//Store photos in a global array
			
	jQuery('.jcarousel-skin-tango').jcarousel({
		scroll: 7,
		wrap: 'both'
		//itemLoadCallback: LoadJCarousel
		
	});
	
}

var ReloadCarousel = function(url,filter,filter_type_value,complete_callback)
{
	if( typeof filter == 'undefined' || filter == FILTER_NONE )
	{
		filter_keywords = '';
	}
	else
	{
		filter_keywords = filter;	
	}
	
	if( typeof filter_type_value == 'undefined' || filter_type_value == FILTER_NONE )
	{
		filter_type = '';
	}
	else
	{
		filter_type = filter_type_value;	
	}	
	
	//alert('[DEBUG]'+filter_keywords);
	div_reload = '.jcarousel-skin-tango';
	//div_reload = 'jcarousel-container';
	//div_reload = 'body.modal';
	
	ShowPreloader();	
	$(div_reload).load
	(
		url+' '+div_reload,
		{
			filter_keywords	:	filter_keywords,
			filter_type:	filter_type
		},		
		
		function(html)
		{
			//alert(html);
			BindCarousel();
			HidePreloader();
			
			if( typeof complete_callback == 'function')
			{
				complete_callback();
			}
		}
	);
}

var BindFullScreenMode = function()
{
	$body = $('body');
	$fsmode0 = $('.fsmode0');
	$fsmode1 = $('.fsmode1');
	
	$('.fsmode_trigger_on').click(function(){
	
		$fsmode1.hide();
		$body.addClass('fs');
		$fsmode0.fadeIn('fast');
				
	});
	
	$('.fsmode_trigger_off').click(function(){
	
		$fsmode0.fadeOut('fast',function(){
		
			$body.removeClass('fs');
			$fsmode1.show();
				
		});
					
	});
}

$(document).keyup(function(e) 
{
	//alert(e.keyCode);
	
	switch(e.keyCode)
	{
		case KEYCODE_ESCAPE:
		{
			$fsmode0.hide();
			$body.removeClass('fs');
			$fsmode1.show();
						
			break;
		}
		
		case KEYCODE_ENTER:
		{
			break;
		}
	}
		
});
