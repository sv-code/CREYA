<div id="artist_profile" class="cdashbox_container efloatcontainer">
	
	<div class="default_container efloatcontainer">
	
		<div class="cdashbox_content left">
			
			<div class="cdashbox">
							
				<div id="avatar_container" class="thumbnail225 <? if($artist_dname==$session_user){?>hoverhighlight<?}?>">
					<? $this->load->view('Embed.CONTENT.Plugin.Single_Upload.Output')?>
				</div>
				
				<!--span id="artist_dname" class="block right"><?=$artist_dname?></span><div class="clear"></div-->
				
				<!--div class="blockquotes"><span class="left"></span><span class="right"></span></div><div class="clear"></div-->
				
				<p id="artist_about_me" <? if($artist_dname==$session_user){?><?='class="editable editable_inactive"'?><?}?>>	<? echo nl2br($artist_details['artist_about_me']); ?></p-->
				
				<? if($artist_dname==$session_user)
				{?>			
					<div id="shortcuts">
						
						<div class="clear cdivider"></div>
						<a class="bookmarks" href="#" onclick="bookmarks();return false;"></a>
						<div class="clear cdivider"></div>
						<a class="upload_photos" href="/photo/uploader"></a>
						<div class="clear cdivider"></div>
						<a class="create_post" href="/community/post/create"></a>
						<div class="clear cdivider"></div>
						<a class="create_group" href="/group/create"></a>
						<div class="clear cdivider"></div>
						<a class="preferences" href="/preferences"></a>
						<div class="clear cdivider"></div>
						
					</div>		
				<?}?>
			
			</div>			
								
		</div>
		
		
		<div class="default left pageminheight">
			
			<div id="details">
				
				<div class="location detail" style="margin-bottom:30px;">
					<span class="title"></span>
					<div class="cdivider"></div>
					<span id="artist_location" class="matter <? if($artist_dname==$session_user){?>editable<?}?>"><?=$artist_details['artist_location']?></span>
				</div>
				
				<div class="focus detail">
					<span class="title"></span>
					<div class="cdivider"></div>
					<span class="matter left"><?=$artist_details['artist_focus']?></span>
					<? if($artist_dname==$session_user){?><div class="relative left"><a class="edit" href="#" onclick="editfocus();return false;" style="display:none;"></a></div><?}?>
					<div class="clear"></div>
				</div>
				
				<div class="recently_uploaded detail">
					<span class="title"></span>
					<div class="cdivider"></div>
					<? if(count($artist_recently_uploaded_photos)>0)
					{?>
						<div class="thumbRow">
							<? foreach($artist_recently_uploaded_photos as $photo): ?>
								<a class="hoverhighlight thumbnail90 left" href="/photo/<?=$photo['PHOTO_ID']?>"><img src="<?=cgraphics_image_photo_url(PHOTO_PREVIEW,$photo['PHOTO_ID']);?>" alt="" /></a>
							<? endforeach ?>						
						</div>
					<?}
					else
					{?>
						<span class="matter left greyblack bmargin0"><? if($artist_dname==$session_user){?>you have<?}else{?>this artist has<?}?>  not uploaded any photographs</span>					
					<?}?>
				</div>
				<div class="clear"></div>
				
				<? if(count($artist_most_bookmarked_photos)>0)
				{?>
					<div class="most_bookmarked detail">
						<span class="title"></span>
						<div class="cdivider"></div>
						<div class="thumbRow">
							<? foreach($artist_most_bookmarked_photos as $photo): ?>
								<a class="hoverhighlight thumbnail90 left" href="/photo/<?=$photo['PHOTO_ID']?>"><img src="<?=cgraphics_image_photo_url(PHOTO_PREVIEW,$photo['PHOTO_ID']);?>" alt="" /></a>
							<? endforeach ?>	
						</div>
					</div>
				<?}?>
				
			</div>
								
			<? $data['comment_hidden_fields'] = 
			array
			(
				array('id'=>'entity','value'=>$artist_dname)
			); 
			
			$data['add_comment_url'] = '/artist/postcomment';
			$data['remove_comment_url'] = '/artist/deletecomment'; 
						
			$data['url_reload'] = '/artist/'.$artist_dname; ?>
			<? $this->load->view('Embed.CONTENT.Comments',$data); ?>		
															
		</div>							
		
							
		<div class="clear"></div>
	
	</div>
	
</div>

