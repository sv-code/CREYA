<div id="discussion_splitview" class="cdashbox_container efloatcontainer">
	
	<div class="discussion_container efloatcontainer">
	
		<div id="<?=$disc_details['DISC_ID']?>" class="discussion left pageminheight">
								
			<div class="lmargin40 rmargin40 tmargin10">
				<span class="block time">posted <?=$disc_details['disc_date']?></span>
				<br>
				<p class="disc_body <? if($disc_details['artist_dname']==$session_user){?>editable editable_inactive<?}?>"><?=nl2br($disc_details['disc_body'])?></p>
			</div>
								
			<div id="image_attachments">
				<? for($i=0;$i<count($disc_image_attachments);++$i)
				{?>		
					<img src="<?=cgraphics_image_discussion_preview_url($discussion_type,DISCUSSION_STANDARD,$disc_image_attachments[$i]);?>"/><br>
				<?}?>				
			</div>
								
			<div class="content_secondary2">
									
				<? $this->load->view('Embed.CONTENT.Comments',$comment_data); ?>	
																
			</div>
								
														
		</div>
							
		<div class="cdashbox_content discussion_meta left">
							
			<? $creator=array('creator_artist_dname'=>$disc_creator_details['artist_dname'],'creator_artist_avatar'=>$disc_creator_details['artist_avatar']); 
			$this->load->view('Embed.CDASHBOX.Creator.Right',$creator); ?>	
								
			<!--? $this->load->view('Embed.CDASHBOX.Tags',$tag_data); ?-->
							
								
		</div>
							
		<div class="clear"></div>
	
	</div>
	
</div>

