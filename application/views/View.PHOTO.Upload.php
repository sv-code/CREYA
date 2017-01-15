<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.FORM'); ?>
	<? $this->load->view('Meta.UPLOADER.MULTI'); ?>
		
	<title>CREYA | upload photographs</title>
	
	<script type="text/javascript">
	
		var LoadFlash = function()
		{
			var selected_type = $('#upload_photo_type .selected').text();
			
			$('#upload_browse').fadeIn();
			if( selected_type != "select a genre")
			{
				//$("#photo_type option:first").remove();
				//$('#upload_browse').css('background-image','url(/resources/images/button.browse.png)');
				
				if( FlashPresent() == false)
				{
					return false;
				}				
				createBrowseButton();
			}
		}
		
		var DropDownSelectedCallback = function()
		{
			LoadFlash();
		}
	</script>					
	
</head>

<body>
	
	<? $this->load->view('Embed.HEADER'); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
	
	
		<div id="main_black" class="pageminheight">
			<!-- Control Bar -->
			<div id="controls_black">
				<h2 class="flashheader left">Upload photographs</h2>
			</div>
			<div class="content_divider"></div>
			<!-- END Control Bar -->
			
			<div id="content_black" style="padding-bottom:30px;">
								
				<div class="fade_preload">
					<div id="save_cancel_preload" class="preloader_container"></div>
				
					<div id="upload_input">
						
						<div class="cdivider"></div>
						
						<div class="form"> 
							
							<? $data['dropdown_id'] = 'upload_photo_type';$data['dropdown_array'] = $photo_type_array;$data['dropdown_array_key'] = 'photo_type_name';$this->load->view('Embed.FORM.Dropdown',$data); ?>
							<a id="upload_browse" class="button_addphotos left" style="display:none;"></a>
							<div id="global_tags_container" class="left" style="display:none;"><input type="text" id="global_tags" class="text left" name="global_tags" value="click to enter global tags separated by COMMA (,)" /></div>
							<a id="upload_cancel_all" class="button_cancel_all left" style="display:none;" onclick="cancelAllUploads();return false;"></a>
							<div class="clear"></div>
							
						</div>
						
						
									
					</div>
					
					<div id="upload_output" class="content_secondary hidden">
												
						<div id="global_progress_bar" class="progress_bar left"><div class="progress_fill" style="width:0%;"></div></div><div class="progress_done right hidden"></div><span class="percentage block right yellow"></span><div class="clear"></div>
						
						<? $data['background_class'] = 'secondary'; 
						$this->load->view('Embed.CONTENT.MultiUpload.Output',$data); ?>					
						
					</div>
					
				</div>
				
				<a id="upload_save_all" class="button_save_all" style="display:none;" onClick="VerifySession(SaveUploadedPhotoSync());return false;"></a>	
				
				<div id="upload_saving_all">
					<span class="saving"></span>		
					<div class="progress_bar"><div class="progress_fill" style="width:0%;"></div></div>			
				</div>					
																
			</div>
				
		</div>
	
	</div>
	<!-- END Wrapper 2 -->
	
	<!--? $this->load->view('Embed.USERPAD'); ?-->
	
	<? $this->load->view('Embed.FOOTER'); ?>		
	
</body>
</html>
