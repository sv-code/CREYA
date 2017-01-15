<div id="groupinfo" class="cdashbox_container efloatcontainer">
	
	<div class="default_container efloatcontainer">
	
		<div class="cdashbox_content left">
			
			<? $creator=array('creator_artist_dname'=>$group_creator_details['artist_dname'],'creator_artist_avatar'=>$group_creator_details['artist_avatar']); 
			$this->load->view('Embed.CDASHBOX.Creator.Right',$creator); ?>	
			
		</div>
		
		
		<div class="default left pageminheight">
			
			<p class="group_desc <? if($group_creator_details['artist_dname']==$session_user){?>editable editable_inactive<?}?>"><?=nl2br($group_details['group_desc']) ?></p>
			
			<? if($is_member==false)
			{?>
				<a href="/group/<?=$gid?>/join" class="join button_join left"></a>
			<?}?>
															
		</div>		
							
		<div class="clear"></div>
	
	</div>
	
</div>

