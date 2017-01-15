<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.FORM'); ?>	
	<? $this->load->view('Meta.SLIDER'); ?>
	
	<? if($session_user!=null)
	{ 
		$this->load->view('Meta.USERPAD'); 
	}?>
	
	<link href="/resources/css/discussion.css" rel="stylesheet" type="text/css"/>
	<link href="/resources/css/discussion.review_request.css" rel="stylesheet" type="text/css"/>
	
	<script type="text/javascript" src="/resources/js/selectToUISlider.jQuery.js"></script>
	<script type="text/javascript" src="/resources/js/discussion.review_request.js"></script>	
	<script type="text/javascript" src="/resources/js/discussion.js"></script>
	
   	<link href="/resources/css/imgareaselect-default.css" rel="stylesheet" type="text/css"/>
    	<script type="text/javascript" src="/resources/js/jquery.imgareaselect.js"></script>
   
    	<link href="/resources/css/jquery.Jcrop.css" rel="stylesheet" type="text/css"/>
    	<script type="text/javascript" src="/resources/js/jquery.Jcrop.js"></script>
   
    	<script type="text/javascript" src="/resources/js/lightbox.imageareaselect.js"></script>       	
	
	<title>CREYA | review request</title>
	
	<script type="text/javascript">
		$(function() 
		{
			<? 
				$review_request_image_factor = $this->config->item(DISCUSSION_STANDARD); 
				$review_request_image_factor = $review_request_image_factor['x'];
			?>
					
			<?foreach($comment_array['comments'] as $comment)
			{?>

				<?if( array_key_exists('crop_x',$comment) && array_key_exists('crop_y',$comment) && 
					array_key_exists('crop_width',$comment) && array_key_exists('crop_height',$comment) &&
					($comment['crop_x'] >= 0) && ($comment['crop_y'] >= 0) && ($comment['crop_width'] > 0) && 
					($comment['crop_height'] > 0) && array_key_exists('REVIEW_ID',$comment) )
				{?>		
					 ShowCropSuggestion(
						'<?='#'.$comment['REVIEW_ID'].'_cropsuggestion'?>',
						<?=$comment['crop_x']?>,
						<?=$comment['crop_y']?>,
						<?=$comment['crop_width']?>,
						<?=$comment['crop_height']?>,
						500/<?=$review_request_image_factor?>
						);
				<?}?>						

			<?}?>
		});
    </script>	

</head>

<body>
	
	<? $this->load->view('Embed.HEADER'); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
		
		<div id="main_black" class="pageminheight">
			
			<!-- Control Bar -->
			<div id="controls_black">
				<h2 class="flashyellowheader left" style="width:100%;">Review Request</h2>					
			</div>
			<div class="content_divider"></div>
			<div id="subcontrols">
				<a class="view_original_image" href="/photo/<?=$disc_image_attachments[0]?>"></a>
			</div>
			<!-- END Control Bar -->
			
			<!-- Content Column -->
			<div id="content_black" class="fade_preload">
				<div class="preloader_container"><div class="preloader"></div></div>
				
				<div id="discussion_splitview" class="cdashbox_container efloatcontainer">
	
					<div class="discussion_container efloatcontainer">
					
						<div id="<?=$disc_details['DISC_ID']?>" class="discussion discussion_review_request left">
												
							<div id="image_attachments" class="photo_review">
								<img id="review_image" src="<?=cgraphics_image_discussion_preview_url(DISCUSSION_TYPE_COMMUNITY,DISCUSSION_STANDARD,$disc_image_attachments[0]);?>"/>
								<? $this->load->view('Embed.CONTENT.Plugin.PhotoInfoContainer',$disc_details); ?>
							</div>
							
							<div class="lmargin40 rmargin40 tmargin20">
								<!--span class="block time">posted <?=$disc_details['disc_date']?></span-->
								<br>
								<p class="disc_body <? if($disc_details['artist_dname']==$session_user){?>editable editable_inactive<?}?>">
									<? echo $disc_details['disc_body'];
									?>	
								</p>
							</div>
											
							<!--div class="cdivider tmargin20 clight4"></div-->
							<div class="content_secondary2 relative">
										
								<!--input class="cropbutton left" type="image" value="" src="/resources/images/button.rate.png" onclick=""-->
								<!--img class="crop" style="position:absolute;top:-450px;left:100px;display:none;" src="<?=cgraphics_image_discussion_preview_url(DISCUSSION_TYPE_COMMUNITY,DISCUSSION_STANDARD,$disc_image_attachments[0]);?>"/-->
								
								<? /* COMMENT DATA */
								$comment_data['comment_hidden_fields'] = 
								array
								(
									array('id'=>'entity','value'=>$disc_details['DISC_ID']),
									array('id'=>'comment_photo_attachment_id','value'=>'')								
								);
									
								$comment_data['add_comment_url'] = '/community/postdiscussioncomment'; 
								$comment_data['remove_comment_url'] = '/community/deletereview'; 
								
								$comment_data['url_reload'] = '/community/discussion/'.$disc_details['DISC_ID'];
								$comment_data['comment_input_id'] = "CommunityDiscussionCommentInput";
								
								$comment_data['image_attachment'] = $disc_image_attachments[0];			 
								
								$this->load->view('Embed.CONTENT.Reviews',$comment_data); ?>
																				
							</div>
												
																		
						</div>
											
						<div class="cdashbox_content discussion_meta left">
							
							<? $creator=array('creator_artist_dname'=>$disc_creator_details['artist_dname'],'creator_artist_avatar'=>$disc_creator_details['artist_avatar']); 
							$this->load->view('Embed.CDASHBOX.Creator.Right',$creator); ?>	
														
							<div id="review_rating_summary">
								
								<? $this->load->view('Embed.CDASHBOX.ReviewRequest.AverageRating'); ?>
								
								<? $this->load->view('Embed.CDASHBOX.ReviewRequest.ReviewSummary'); ?>
												
							</div>											
							
							
							<? $this->load->view('Embed.CDASHBOX.ReviewRequest.ReviewInput'); ?>
												
												
						</div>
											
						<div class="clear"></div>
					
					</div>
					
				</div>
	
				
			</div>
			<!-- END Content Column -->
								
		</div>
		<!-- END Main -->
			
	</div>
	<!-- END Wrapper 2 -->
	
	<? if($session_user!=null)
	{
		if($disc_details['artist_dname']!=$session_user)
		{
			/*
			$data['bookmark_entity_id'] = $disc_details['DISC_ID'];
			$data['add_bookmark_url'] = '/community/adddiscussionbookmark';
			$data['remove_bookmark_url'] = '/community/removediscussionbookmark';   
			$data['is_bookmarked'] = $is_bookmarked;
			*/
		}
		else
		{
			$data['delete_entity_id'] = $disc_details['DISC_ID'];
			$data['delete_function'] = 'Ajax_DeleteEntity';
			$data['delete_entity_url'] = '/community/deletereviewrequest';
			$data['delete_redirect_url'] = '/community';
			
			$this->load->view('Embed.DOCK.Userpad',$data);
		}
		
		 
	}?>
		
	<? $this->load->view('Embed.FOOTER'); ?>	
		
</body>
</html>
