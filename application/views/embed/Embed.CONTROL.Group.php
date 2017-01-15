<div id="controls_black" style="overflow:visible;">
				
	<div id="group_local_action_pointer" <? if($is_member==false) {?> class="transparent pointer" <?}?>>
		
		<? if($is_member==true)
		{?>
			<ul id="group_local_actions">
				<li class="la_add_remove_photos"><a href="#" onclick="AddRemovePhotosToGroup(<?=$gid?>);return false;"></a></li>
				<li class="la_start_disc"><a href="/group/discussion/create/<?=$gid?>"></a></li>
				<li class="la_leave_group"><a href="/group/<?=$gid?>/leave"></a></li>
			</ul>
		<?}?>
	
	</div>
		
	
	
	<h2 class="flashheader left" style="padding:0"><?=$group_name?></h2>
	
	 
	<div id="<?=$gid?>" class="group_control control_links">
		
		<div class="info"><a <? if(isset($control_active) && $control_active==VIEW_GROUP_CONTROL_ACTIVE_INFO){?>class="active"<?}?>" href="/group/<?=$gid?>"></a></div>
		<div class="gallery"><a <? if(isset($control_active) && $control_active==VIEW_GROUP_CONTROL_ACTIVE_GALLERY){?>class="active"<?}?>" href="/group/<?=$gid?>/gallery"></a></div>
		<div class="posts"><a <? if(isset($control_active) && $control_active==VIEW_GROUP_CONTROL_ACTIVE_POSTS){?>class="active"<?}?>" href="/group/<?=$gid?>/posts"></a></div>
			
	</div>
		
</div>
	






