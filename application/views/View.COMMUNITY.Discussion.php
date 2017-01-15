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
	
	<link href="/resources/css/discussion.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="/resources/js/discussion.js"></script>	
	
	<title>CREYA | community post</title>
	
	<script type="text/javascript">
	</script>
	
</head>

<body>
	
	<? $this->load->view('Embed.HEADER'); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
			<div id="main_black" class="pageminheight">
				
				<!-- Control Bar -->
				<div id="controls_black">
					<h2 id="discussion_title" class="<? if($disc_details['artist_dname']==$session_user){?>editable editable_header yellow<?}else{?>flashyellowheader<?}?> left" style="width:95%;"><?=$disc_details['disc_title']?></h2>
				</div>
				<div class="content_divider"></div>
				<!-- END Control Bar -->
				
				<!-- Content Column -->
				<div id="content_black" style="float:none;">
					
					<? 
						/* COMMENT DATA */
						$comment_data['comment_hidden_fields'] = 
						array
						(
							array('id'=>'entity','value'=>$disc_details['DISC_ID']),
							array('id'=>'comment_photo_attachment_id','value'=>'')								
						);
							
						$comment_data['add_comment_url'] = '/community/postdiscussioncomment'; 
						$comment_data['remove_comment_url'] = '/community/deletediscussioncomment'; 
						
						$comment_data['url_reload'] = '/community/discussion/'.$disc_details['DISC_ID'];
						$comment_data['comment_input_id'] = "CommunityDiscussionCommentInput";							
													
						
						/* TAG_DATA */
						$tag_data['cdashbox_align_right'] = true;
						$tag_data['tags'] = $disc_tags;
						$tag_data['entity_class'] = '.discussion'; 
						$tag_data['add_tag_url'] = '/community/addtag'; 
						$tag_data['delete_tag_url'] = '/community/deletetag'; 
						
						if($session_user!=null && $disc_details['artist_dname']==$session_user)
						{
							$tag_data['tag_input_allowed'] = true;
						}
						else
						{
							$tag_data['tag_input_allowed'] = false;
						}
						
						$data['comment_data'] = $comment_data;
						$data['tag_data'] = $tag_data;
					?>
				
					<? $data['discussion_type'] = DISCUSSION_TYPE_COMMUNITY;
					$this->load->view('Embed.CONTENT.Discussion',$data); ?>									
					
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
			$data['delete_entity_url'] = '/community/deletepost';
			$data['delete_redirect_url'] = '/community';
			
			$this->load->view('Embed.DOCK.Userpad',$data);
		}
		
		 
	}?>
	
	<? $this->load->view('Embed.FOOTER'); ?>	
	
</body>
</html>
