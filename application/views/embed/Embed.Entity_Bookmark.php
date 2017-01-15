<div id="entity_pad">
	
	<div class="background">
		
		<div class="bookmark hoversuperhighlight">
			
			<a class="on <? if($is_bookmarked!=true){?>hidden<?}else{?>block<?}?>" href="#" onclick="RemoveEntityBookmark('<?=$bookmark_entity_id?>','<?=$remove_bookmark_url?>');return false;"></a>
			<a class="off <? if($is_bookmarked==true){?>hidden<?}else{?>block<?}?>" href="#" onclick="AddEntityBookmark('<?=$bookmark_entity_id?>','<?=$add_bookmark_url?>');return false;"></a>
					
		</div>
		
		<div class="clear"></div>
						
	</div>
	
</div>
