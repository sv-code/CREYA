<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.FORM'); ?>
	
	<link href="/resources/css/artist.css" rel="stylesheet" type="text/css"/>
		
	<title>EditFocus</title>
	
	<script type="text/javascript">
		$(function() 
		{
			$('.save_indicator').attr('src','');
			
			//check checkbox_count = 3?
			$('.checkbox_focus').bind('click', function() {
				checkFocusCount();
			});
			
			$('#form_artist_edit_focus').submit(function() {
				if( !checkFocusCount() )
				{
					$('.save_indicator').attr('src','/images/prompt.negative.png');
					return false;
				}
				else
				{
					$('.save_indicator').attr('src','/images/ajax-loader-black-small.gif');
					return true;
				}
			});			
			
			var checkFocusCount = function ()
			{
				var checked_count = $(".checkboxes_focii :checked").size();
				
				if (checked_count > 3) 
				{
					$('#prompt_artist_focii .helper').addClass('red');
					return false;
				}
				else
				if( checked_count == 0)
				{
					return false;	
				}
				else
				{
					$('#prompt_artist_focii .helper').removeClass('red');
					$('.save_indicator').attr('src','');
					return true;										
				}				
				
			} 			
			


		});
	</script>	


</head>

<body class="modal">
	<div id="EditFocusContainer">
		
		<div class="header">
			<h1 class="modal_header right">edit focus</h1>
		</div>
		
		<div class="cdivider clight clear"></div>
		<div id="subcontrols"></div>
		<div class="cdivider clight"></div>
		
		<form id="form_artist_edit_focus" class="form" action="/artist/edit_focus" method="post" target="_parent">
			
				<div id="prompt_artist_focii" class="next" style="margin-bottom:0px;"><span class="helper" style="margin-left:0;">choose a maximum of three</span></div>
				<? $form_data['column_count'] = 3; 
				$this->load->view('Embed.FORM.Checkboxes.Focii',$form_data); ?>
			
			
			
			<input class="submit button_save left" type="submit" value=""/><span class="block save_indicator_ajax left"></span>
			
						
			<!--div class="clear"/>
			<div class="divider"/>
				
			<div class="content_secondary">
				<img class="submit left" src="/resources/images/button.save.png"/><img class="indicator" src="/resources/images/ajax-loader-black-small.gif" />
			</div-->
			
		</form>
		
	</div>
</body>
</html>
	