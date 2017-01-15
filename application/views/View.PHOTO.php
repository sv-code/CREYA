<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	
	<? if($session_user!=null)
	{ 
		$this->load->view('Meta.USERPAD'); 
	}?>
		
	<script type="text/javascript" src="/resources/js/photo.js"></script>
		
	<link href="/resources/css/artist.css" rel="stylesheet" type="text/css"/>
	<link href="/resources/css/photo.css" rel="stylesheet" type="text/css"/>	
	
	<title>CREYA <? if($photo_details['photo_name']!='noname'){echo '| "'.$photo_details['photo_name'].'"';}?></title>

</head>

<body>
	
	<? $this->load->view('Embed.HEADER'); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
		
		<div id="main_black" class="pageminheight">
			
			<!-- Control Bar -->
			<div id="controls_black">
				<div id="photo_info">
					<!--? $this->load->view('Embed.INFO.Photo'); ?-->
					<h2 id="photo_name" class="<? if($photo_details['artist_dname']==$session_user){?>editable editable_header<?}else{?>flashheader<?}?> left">
						<? if($photo_details['photo_name']!='noname') 
						{
							echo $photo_details['photo_name'];
						}?>
					</h2>
				</div>
				
				<div class="control_links">
					<? $this->load->view('Embed.CONTROL.Artist'); ?>
				</div>
			</div>
			
			<div class="content_divider"></div>
			
			<? if((isset($review_request_id) && $review_request_id!=null) || ($photo_details['artist_dname']==$session_user))
			{?>
				<div id="subcontrols">
					<div class="review_request hoversuperhighlight">
						<? if(isset($review_request_id) && $review_request_id!=null)
						{?>
							<a class="view_review" href="/community/reviewrequest/<?=$review_request_id?>"></a>
						<?}
						else
						{?>
							<a id="create_review_request" class="submit_review" href="#" onclick="CreateReviewRequest('<?=$photo_details['PHOTO_ID']?>');return false;"></a>
						<?}?>
						<!--textarea rows="" cols="">click to add comment</textarea-->
					</div>
				</div>
			<?}?>
			<!-- END Control Bar -->
			
			<!-- Content Column -->
			<div id="content_black" class="fade_preload">
				
				<div class="preloader_container"><div class="preloader"></div></div>
				
				<? $this->load->view('Embed.CONTENT.Photo.The_Photo'); ?>
				
			</div>
			<!-- END Content Column -->
			
			<div class="clear"></div>
			<div class="content_divider" style="margin-top:0px;"></div>
			
			
			<div id="content_meta" class="cdashbox_container efloatcontainer">
	
				<div class="default_container efloatcontainer">
				
					<div class="default left pageminheight">
											
						<? $data['comment_hidden_fields'] = 
							array
							(
								array('id'=>'entity','value'=>$photo_details['PHOTO_ID'])
							);
							
							$data['add_comment_url'] = '/photo/postcomment'; 
							$data['remove_comment_url'] = '/photo/deletecomment'; 
							
							$data['url_reload'] = '/photo/'.$photo_details['PHOTO_ID'];
						?>
						<? $this->load->view('Embed.CONTENT.Comments',$data); ?>						
																	
					</div>
										
					<div class="cdashbox_content left">
										
						<?
							/* TAG_DATA */
							$tag_data['cdashbox_align_right'] = true;
							$tag_data['tags'] = $photo_tags;
							$tag_data['entity_class'] = '.photo'; 
							$tag_data['add_tag_url'] = '/photo/addtag'; 
							$tag_data['delete_tag_url'] = '/photo/deletetag';
							
							if($session_user!=null && $photo_details['artist_dname']==$session_user)
							{
								$tag_data['tag_input_allowed'] = true;
								$tag_data['tag_delete_allowed'] = true;
							}
							else
							{
								$tag_data['tag_input_allowed'] = false;
							}
						?>					
						<? $this->load->view('Embed.CDASHBOX.Tags',$tag_data); ?>
						
						<? $this->load->view('Embed.CDASHBOX.Photos.More'); ?>				
												
					</div>
										
					<div class="clear"></div>
				
				</div>
				
			</div>

				
		</div>
		<!-- END Main -->
			
	</div>
	<!-- END Wrapper 2 -->
	
	<? if($session_user!=null)
	{
		if($photo_details['artist_dname']!=$session_user)
		{
			$data['bookmark_entity_id'] = $photo_details['PHOTO_ID'];
			$data['add_bookmark_url'] = '/artist/bookmarkphoto';
			$data['remove_bookmark_url'] = '/artist/unbookmarkphoto';   
			$data['is_bookmarked'] = $is_bookmarked;
		}
		else
		{
			$data['delete_entity_id'] = $photo_details['PHOTO_ID'];
			$data['delete_function'] = 'Ajax_DeleteEntity';
			$data['delete_entity_url'] = '/artist/deletephoto';
			$data['delete_redirect_url'] = '/artist/'.$artist_dname.'/gallery';
		}
		
		$this->load->view('Embed.DOCK.Userpad',$data); 
	}?>
	
	<? $this->load->view('Embed.FOOTER'); ?>	
		
</body>
</html>
