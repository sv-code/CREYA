<div id="userpad">
	
	<div class="hdivider clight"></div>
	
	<div class="container">
				
		<? if($session_user!=null)
		{?>
			<a class="avatar hoverhighlight relative thumbnail50" href="/artist/<?=$session_user?>"><img src="<?=cgraphics_image_artist_avatar_url(ARTIST_AVATAR_THUMBNAIL_MEDIUM,$artist_avatar);?>" /></a>
			
			<ul class="mylinks">
			
				<!--li class="artist_dname"><a href="/artist/<?=$session_user?>"><span class="yellow"><?=$session_user?></span></a></li-->
				<li class="gallery"><a href="/artist/<?=$session_user?>/gallery"></a></li>
				<li class="bookmarks"><a href="#" onclick="bookmarks();"></a></li>							
			
			</ul>
			
			<div class="divider left"></div>
		<?}?>
		
		<? if(isset($display_section_search) && $display_section_search==true)
		{?>
			<!--div class="section_search">
				
				<div class="edge start"></div>
				<input id="section_search_keywords" type="text" name="tags" value="filter by tags and names" onfocus="$(this).attr('value','');return false;"/>
				<div class="edge end"></div>	
				
			</div-->
		<?}?>
		
		<? if($session_user!=null && isset($bookmark_entity_id))
		{?>
			<div class="bookmark hoversuperhighlight">
					
				<a class="on <? if($is_bookmarked!=true){?>hidden<?}else{?>block<?}?>" href="#" onclick="RemoveEntityBookmark('<?=$bookmark_entity_id?>','<?=$remove_bookmark_url?>');return false;"></a>
				<a class="off <? if($is_bookmarked==true){?>hidden<?}else{?>block<?}?>" href="#" onclick="AddEntityBookmark('<?=$bookmark_entity_id?>','<?=$add_bookmark_url?>');return false;"></a>
							
			</div>
		<?}?>
			
		<div class="clear"></div>
	
	</div>

</div>
