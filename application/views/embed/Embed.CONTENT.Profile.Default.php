<div id="artist_default">
				
	<div id="artist_info" class="left">
																
		<div class="matter">
			<img class="title" src="/resources/images/artist/title_focus.png"/>
			<? if($artist_dname==$session_user)
			{?>
				<a href="#"><img class="edit" src="/resources/images/ibutton.edit.png" onclick="editfocus();"/></a>
			<?}?>
			<span><?=$artist_details['artist_focus']?></span>
			
			<img class="title clear" src="/resources/images/artist/title_location.png"/>
			<span <? if($artist_dname==$session_user){?><?='id="artist_location" class="editable"'?><?}?>><?=$artist_details['artist_location']?></span>
			
			<img class="title clear" src="/resources/images/artist/title_stats.png"/>
			<span>photographs uploaded, 74</span>
			<span><?=$artist_details['artist_join_date']?></span>
							
		</div>
																	
	</div>
					
	<div id="avatar_container" class="thumbnail225">
		<? $this->load->view('Embed.CONTENT.Plugin.Single_Upload.Output')?>
	</div>
	<!--div id="artist_avatar" class="left">
		<img src="<?=cgraphics_image_artist_avatar_url(ARTIST_AVATAR_PROFILE,$artist_details['artist_avatar']);?>"/>
	</div-->
					
	<div id="artist_desc" class="left">
				
		<div class="matter">
			<p <? if($artist_dname==$session_user){?><?='id="artist_about_me" class="editable"'?><?}?>>	<? if($artist_details['artist_about_me']==''){?>click to write about yourself<?}else{echo nl2br($artist_details['artist_about_me']);}?></p>
		</div>
						
	</div>
					
</div>
				
<div class="content_divider clight clear"></div>
<div id="artist_photos" class="clear">
					
	<? if(count($artist_top_rated_photos)>0)
	{?>
		<h3>RECENTLY UPLOADED</h3>					
						
		<div class="thumbRow">
			<? foreach($artist_top_rated_photos as $photo): ?>
				<a class="hoverhighlight thumbnail90 left" href="/photo/<?=$photo['PHOTO_ID']?>"><img src="<?=cgraphics_image_photo_url(PHOTO_PREVIEW,$photo['PHOTO_ID']);?>" alt="" /></a>
			<? endforeach ?>						
		</div>
	<?}
	else
	{?>
		<a href="/photo/uploader"><img class="upload_photos" src="/resources/images/button.upload_photos.png"/></a>
	<?}?>
					
					
</div>
				
<? if(count($artist_top_rated_photos)>0)
{?>
	<div class="content_divider"></div>
	<div class="content_secondary2">
						
		<? $data['comment_hidden_fields'] = 
			array
			(
				array('id'=>'entity','value'=>$artist_dname)
			); 
			
			$data['add_comment_url'] = '/artist/postcomment';
			$data['remove_comment_url'] = '/artist/deletecomment'; 
						
			$data['url_reload'] = '/artist/'.$artist_dname;
							
		?>
		<? $this->load->view('Embed.CONTENT.Comments',$data); ?>						
						
	</div>
<?}?>