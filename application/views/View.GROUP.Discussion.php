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
			
	<script type="text/javascript" src="/resources/js/group.js"></script>
	<script type="text/javascript" src="/resources/js/discussion.js"></script>	
				
	<link href="/resources/css/group.css" rel="stylesheet" type="text/css"/>
	<link href="/resources/css/discussion.css" rel="stylesheet" type="text/css"/>
	
	<title>CREYA | "<?=$group_name?>" | post</title>
	
	<script type="text/javascript">
	$(function() {
		$('.reply').click(function(){
			$.scrollTo($('.discussionForm'), 500 );
		});
		$('a.addThumb').click(function(){
			selectImage();
			return false;
		});
		
		var uploaded_path = '/group/disc/capture/40.';
		setUploadedPath(uploaded_path);
						
		//<span><img  src="/images.user/photo/preview/4.263.jpg" alt=""/></span>
		createInputElement("groupDiscussionInput","group_disc_image_add");
		createFlashElement("groupDiscussionInput",'/Group/storeGroupDiscussionImage');

	});
		
	</script>
	
</head>

<body>
	
	<? $data=array('navbar_dim'=>true);
	$this->load->view('Embed.HEADER',$data); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
		
		<!-- Control TABS -->
		<? $this->load->view('Embed.CONTROL.TABS.Group'); ?>
		<!-- END Control TABS -->
		
		<div id="main_black" class="pageminheight">
			
			<!-- Control Bar -->
			<!--? $data['control_active'] = VIEW_GROUP_CONTROL_ACTIVE_DISCUSSIONS ?-->
			<? $this->load->view('Embed.CONTROL.Group',$data=null); ?>
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
						array('id'=>'gid','value'=>$gid),
						array('id'=>'comment_photo_attachment_id','value'=>'')								
					);
						
					$comment_data['add_comment_url'] = '/group/postdiscussioncomment'; 
					$comment_data['remove_comment_url'] = '/group/deletediscussioncomment'; 
					
					$comment_data['url_reload'] = '/group/'.$gid.'/discussion/'.$disc_details['DISC_ID'];
					$comment_data['comment_input_id'] = "GroupDiscussionCommentInput";							
												
					
					/* TAG_DATA */
					$tag_data['cdashbox_align_right'] = true;
					$tag_data['tags'] = $disc_tags;
					$tag_data['entity_class'] = '.discussion'; 
					$tag_data['add_tag_url'] = '/group/addtag'; 
					$tag_data['delete_tag_url'] = '/group/deletetag'; 
					
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
			
				<? $data['discussion_type'] = DISCUSSION_TYPE_GROUP;
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
			$data['delete_entity_url'] = '/group/deletepost/'.$gid;
			$data['delete_redirect_url'] = '/group/'.$gid.'/posts';
			
			$this->load->view('Embed.DOCK.Userpad',$data);
		}
		
		 
	}?>
	
	<? $this->load->view('Embed.FOOTER'); ?>	
	
</body>
</html>
