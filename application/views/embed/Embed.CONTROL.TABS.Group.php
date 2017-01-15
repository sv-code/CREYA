<div id="control_tabs">

	<a class="tab tab_info <? if(isset($control_active) && $control_active==VIEW_GROUP_CONTROL_ACTIVE_INFO){?>tab_active tab_info_active<?}?>" href="/group/<?=$gid?>"></a>
	
	<a class="tab tab_gallery <? if(isset($control_active) && $control_active==VIEW_GROUP_CONTROL_ACTIVE_GALLERY){?>tab_active tab_gallery_active<?}?>" href="/group/<?=$gid?>/gallery"></a>
	
	<a class="tab tab_posts <? if(isset($control_active) && $control_active==VIEW_GROUP_CONTROL_ACTIVE_POSTS){?>tab_active tab_posts_active<?}?>" href="/group/<?=$gid?>/posts"></a>

</div>

<div class="clear"></div>